<?php

namespace App\Services;

use App\Models\EmailWarmup;
use App\Models\WarmupEmail;
use App\Models\WarmupDailyStat;
use App\Models\WarmupAutoReply;
use App\Models\SmtpAccount;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class WarmupService
{
    private EmailService $emailService;

    private array $warmupSubjects = [
        'Quick question',
        'Following up',
        'Thanks for connecting',
        'Great meeting you',
        'Check this out',
        'Thought you might find this interesting',
        'No rush - just checking in',
        'Quick favor',
        'Ideas for you',
        'Interesting article',
    ];

    private array $engagementSubjects = [
        'Your opinion matters',
        'Help us improve',
        'Quick survey',
        'We need your feedback',
    ];

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function startWarmup(SmtpAccount $smtpAccount, array $settings = []): EmailWarmup
    {
        $warmup = EmailWarmup::create([
            'smtp_account_id' => $smtpAccount->id,
            'status' => EmailWarmup::STATUS_ACTIVE,
            'current_daily_limit' => $settings['initial_daily_limit'] ?? 10,
            'target_daily_limit' => $settings['target_daily_limit'] ?? 500,
            'current_day' => 1,
            'total_days' => $settings['total_days'] ?? 30,
            'started_at' => now(),
            'settings' => $settings,
        ]);

        $this->createWarmupRecipients($warmup, $settings['recipients'] ?? []);

        Log::info("Email warmup started for SMTP account {$smtpAccount->id}");

        return $warmup;
    }

    public function pauseWarmup(EmailWarmup $warmup): void
    {
        $warmup->pause();
        Log::info("Email warmup paused for SMTP account {$warmup->smtp_account_id}");
    }

    public function resumeWarmup(EmailWarmup $warmup): void
    {
        $warmup->resume();
        Log::info("Email warmup resumed for SMTP account {$warmup->smtp_account_id}");
    }

    public function processWarmupBatch(EmailWarmup $warmup): int
    {
        if (!$warmup->canSend()) {
            return 0;
        }

        $quota = $warmup->getTodayRemainingQuota();
        if ($quota <= 0) {
            return 0;
        }

        $recipients = $warmup->warmupEmails()
            ->where('status', WarmupEmail::STATUS_PENDING)
            ->orderBy('send_order')
            ->limit($quota)
            ->get();

        $sent = 0;
        foreach ($recipients as $recipient) {
            try {
                $this->sendWarmupEmail($warmup, $recipient);
                $sent++;
            } catch (\Exception $e) {
                Log::error("Failed to send warmup email", [
                    'warmup_id' => $warmup->id,
                    'recipient' => $recipient->recipient_email,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $sent;
    }

    public function sendWarmupEmail(EmailWarmup $warmup, WarmupEmail $email): void
    {
        $smtpAccount = $warmup->smtpAccount;
        $subject = $this->getSubjectForType($email->type);
        $htmlContent = $this->generateWarmupContent($email->type, $email->recipient_email);
        $messageId = Str::uuid()->toString();

        try {
            $this->emailService->sendViaSmtp(
                $smtpAccount,
                $email->recipient_email,
                $subject,
                $htmlContent,
                strip_tags($htmlContent),
                $messageId,
                null,
                null
            );

            $email->update([
                'subject' => $subject,
                'status' => WarmupEmail::STATUS_SENT,
                'sent_at' => now(),
            ]);

            $this->simulateDelivery($email);

        } catch (\Exception $e) {
            Log::error("Warmup email failed to send", ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function simulateDelivery(WarmupEmail $email): void
    {
        $delay = rand(5, 30);
        $email->update([
            'status' => WarmupEmail::STATUS_DELIVERED,
            'delivered_at' => now()->addSeconds($delay),
        ]);

        $this->scheduleEngagementSimulation($email);
    }

    public function scheduleEngagementSimulation(WarmupEmail $email): void
    {
        $type = $email->type;
        
        $engagementConfig = match($type) {
            'engagement' => [
                'open_probability' => 0.7,
                'reply_probability' => 0.15,
                'click_probability' => 0.25,
                'delay_minutes' => [30, 180],
            ],
            'reply' => [
                'open_probability' => 0.6,
                'reply_probability' => 0.4,
                'click_probability' => 0.1,
                'delay_minutes' => [60, 240],
            ],
            default => [
                'open_probability' => 0.5,
                'reply_probability' => 0.05,
                'click_probability' => 0.1,
                'delay_minutes' => [120, 360],
            ],
        };

        $baseDelay = rand($engagementConfig['delay_minutes'][0], $engagementConfig['delay_minutes'][1]);
        
        if (rand(1, 100) <= $engagementConfig['open_probability'] * 100) {
            $this->simulateEngagement($email, 'open', $baseDelay);
        }

        if (rand(1, 100) <= $engagementConfig['click_probability'] * 100) {
            $this->simulateEngagement($email, 'click', $baseDelay + rand(60, 300));
        }

        if (rand(1, 100) <= $engagementConfig['reply_probability'] * 100) {
            $this->simulateEngagement($email, 'reply', $baseDelay + rand(300, 720));
        }
    }

    private function simulateEngagement(WarmupEmail $email, string $type, int $delayMinutes): void
    {
        $engagedAt = now()->addMinutes($delayMinutes);

        $email->update([
            'status' => $type === 'opened' ? WarmupEmail::STATUS_OPENED : $email->status,
            'opened_at' => $type === 'open' ? $engagedAt : $email->opened_at,
            'replied_at' => $type === 'reply' ? $engagedAt : $email->replied_at,
        ]);

        $email->engagementLogs()->create([
            'email_warmup_id' => $email->email_warmup_id,
            'engagement_type' => $type,
            'engaged_at' => $engagedAt,
        ]);
    }

    public function advanceWarmupDay(EmailWarmup $warmup): void
    {
        if ($warmup->current_day >= $warmup->total_days) {
            $warmup->complete();
            return;
        }

        $warmup->incrementDay();
        
        WarmupDailyStat::firstOrCreate([
            'email_warmup_id' => $warmup->id,
            'date' => today(),
        ], [
            'daily_limit' => $warmup->current_daily_limit,
        ]);

        Log::info("Warmup day advanced", [
            'smtp_account_id' => $warmup->smtp_account_id,
            'new_day' => $warmup->current_day,
            'new_limit' => $warmup->current_daily_limit,
        ]);
    }

    public function processAutoReply(string $recipientEmail): ?WarmupAutoReply
    {
        return WarmupAutoReply::active()
            ->get()
            ->first(fn($reply) => $reply->matches($recipientEmail));
    }

    private function createWarmupRecipients(EmailWarmup $warmup, array $customRecipients = []): void
    {
        $defaultRecipients = config('warmup.default_recipients', [
            'test1@warmup-test.com',
            'test2@warmup-test.com',
            'verify@warmup-test.com',
            'noreply@warmup-test.com',
        ]);

        $recipients = array_merge($defaultRecipients, $customRecipients);
        
        $emails = [];
        $order = 1;
        
        foreach ($recipients as $email) {
            $type = $this->determineEmailType($order, count($recipients));
            
            $emails[] = [
                'email_warmup_id' => $warmup->id,
                'smtp_account_id' => $warmup->smtp_account_id,
                'recipient_email' => $email,
                'type' => $type,
                'status' => WarmupEmail::STATUS_PENDING,
                'send_order' => $order,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $order++;
        }

        for ($i = 0; $i < 50; $i++) {
            $emails[] = [
                'email_warmup_id' => $warmup->id,
                'smtp_account_id' => $warmup->smtp_account_id,
                'recipient_email' => "warmup{$i}@warmup-valid-" . Str::random(6) . ".com",
                'type' => $this->determineEmailType($order, 50),
                'status' => WarmupEmail::STATUS_PENDING,
                'send_order' => $order,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $order++;
        }

        WarmupEmail::insert($emails);
    }

    private function determineEmailType(int $order, int $total): string
    {
        $ratio = $order / $total;
        
        if ($ratio < 0.1) {
            return WarmupEmail::TYPE_CONFIRMATION;
        } elseif ($ratio < 0.7) {
            return WarmupEmail::TYPE_ENGAGEMENT;
        } else {
            return WarmupEmail::TYPE_REPLY;
        }
    }

    private function getSubjectForType(string $type): string
    {
        return match($type) {
            WarmupEmail::TYPE_CONFIRMATION => 'Welcome! Please confirm your subscription',
            WarmupEmail::TYPE_ENGAGEMENT => $this->engagementSubjects[array_rand($this->engagementSubjects)],
            WarmupEmail::TYPE_REPLY => $this->warmupSubjects[array_rand($this->warmupSubjects)],
            default => $this->warmupSubjects[array_rand($this->warmupSubjects)],
        };
    }

    private function generateWarmupContent(string $type, string $recipientEmail): string
    {
        $content = match($type) {
            WarmupEmail::TYPE_CONFIRMATION => "
                <h2>Welcome!</h2>
                <p>Thank you for subscribing. Please confirm your email address.</p>
                <a href='{{verify_url}}' style='background: #4F46E5; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block;'>Confirm Subscription</a>
            ",
            WarmupEmail::TYPE_ENGAGEMENT => "
                <h2>Quick Question</h2>
                <p>Hi there,</p>
                <p>I wanted to get your thoughts on something.</p>
                <p>Would love to hear from you!</p>
            ",
            WarmupEmail::TYPE_REPLY => "
                <h2>Following Up</h2>
                <p>Hello,</p>
                <p>Just wanted to follow up on my previous email.</p>
                <p>Let me know if you have any questions!</p>
            ",
            default => "<p>Welcome to our email list!</p>",
        };

        return "<!DOCTYPE html>
<html>
<head><meta charset='UTF-8'><title>Email</title></head>
<body style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
    {$content}
    <hr style='margin-top: 40px; border: none; border-top: 1px solid #eee;'>
    <p style='font-size: 12px; color: #999;'>
        You're receiving this because you subscribed to our newsletter.<br>
        <a href='{{unsubscribe_url}}' style='color: #666;'>Unsubscribe</a>
    </p>
</body>
</html>";
    }
}
