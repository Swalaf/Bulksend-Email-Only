<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Models\CampaignSubscriber;
use App\Models\SmtpAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessCampaignBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public function __construct(
        public Campaign $campaign,
        public int $offset,
        public int $batchSize = 100
    ) {}

    public function handle(): void
    {
        $campaign = $this->campaign->fresh();

        if (!$campaign->isSending()) {
            return;
        }

        $smtpAccount = $campaign->smtpAccount;
        if (!$smtpAccount) {
            $campaign->cancel();
            return;
        }

        $subscribers = CampaignSubscriber::where('campaign_id', $this->campaign->id)
            ->where('status', CampaignSubscriber::STATUS_PENDING)
            ->with('subscriber')
            ->offset($this->offset)
            ->limit($this->batchSize)
            ->get();

        if ($subscribers->isEmpty()) {
            $pendingCount = CampaignSubscriber::where('campaign_id', $this->campaign->id)
                ->where('status', CampaignSubscriber::STATUS_PENDING)
                ->count();

            if ($pendingCount === 0) {
                $campaign->completeSending();
            }
            return;
        }

        foreach ($subscribers as $campaignSubscriber) {
            SendCampaignEmail::dispatch($campaign, $campaignSubscriber, $smtpAccount);
        }

        $nextOffset = $this->offset + $this->batchSize;
        if ($subscribers->count() === $this->batchSize) {
            ProcessCampaignBatch::dispatch($campaign, $nextOffset, $this->batchSize)
                ->delay(now()->addSeconds($campaign->batch_delay ?? 5));
        } else {
            $campaign->completeSending();
        }
    }
}
