<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Models\CampaignSendLog;
use App\Models\CampaignSubscriber;
use App\Models\SmtpAccount;
use App\Services\EmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class SendCampaignEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        public Campaign $campaign,
        public CampaignSubscriber $campaignSubscriber,
        public SmtpAccount $smtpAccount
    ) {}

    public function handle(EmailService $emailService): void
    {
        $subscriber = $this->campaignSubscriber->subscriber;
        
        if (!$subscriber || !$subscriber->is_active) {
            $this->campaignSubscriber->markAsBounced();
            return;
        }

        $trackingToken = $this->campaignSubscriber->tracking_token ?? Str::random(32);
        if (!$this->campaignSubscriber->tracking_token) {
            $this->campaignSubscriber->update(['tracking_token' => $trackingToken]);
        }

        $messageId = Str::uuid()->toString();

        $log = CampaignSendLog::create([
            'campaign_id' => $this->campaign->id,
            'subscriber_id' => $subscriber->id,
            'smtp_account_id' => $this->smtpAccount->id,
            'message_id' => $messageId,
            'from_address' => $this->smtpAccount->from_address,
            'to_address' => $subscriber->email,
            'subject' => $this->campaign->subject,
            'status' => CampaignSendLog::STATUS_SENDING,
        ]);

        try {
            $emailService->sendViaSmtp(
                $this->smtpAccount,
                $subscriber->email,
                $this->campaign->subject,
                $this->campaign->html_content,
                $this->campaign->plain_text_content,
                $messageId,
                $trackingToken,
                $this->campaign->id
            );

            $log->markAsSent();
            $this->campaignSubscriber->markAsSent($messageId);
            $this->campaign->incrementSent();

        } catch (\Exception $e) {
            $log->markAsFailed($e->getMessage());
            $this->campaignSubscriber->markAsBounced();
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        \Log::error("Failed to send campaign email", [
            'campaign_id' => $this->campaign->id,
            'subscriber_id' => $this->campaignSubscriber->subscriber_id,
            'error' => $exception->getMessage(),
        ]);
    }
}
