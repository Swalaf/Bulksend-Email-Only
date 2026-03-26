<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\UsageRecord;
use App\Models\Credit;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\PaymentMethod;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BillingService
{
    /**
     * Get user's current subscription
     */
    public function getSubscription(User $user): ?Subscription
    {
        return $user->subscriptions()
            ->where('status', Subscription::STATUS_ACTIVE)
            ->with('plan')
            ->first();
    }

    /**
     * Check if user can perform action based on plan limits
     */
    public function canPerform(User $user, string $action, int $count = 1): array
    {
        $subscription = $this->getSubscription($user);
        
        if (!$subscription) {
            return ['allowed' => false, 'reason' => 'No active subscription'];
        }

        $plan = $subscription->plan;

        // Check based on action type
        $result = match($action) {
            'send_emails' => $this->checkEmailLimit($user, $count, $plan),
            'add_subscribers' => $this->checkSubscriberLimit($user, $count, $plan),
            'add_smtp' => $this->checkSmtpLimit($user, $count, $plan),
            'create_campaign' => $this->checkCampaignLimit($user, $count, $plan),
            'use_analytics' => $this->checkFeature($plan, 'analytics'),
            'use_ai' => $this->checkFeature($plan, 'ai'),
            'use_api' => $this->checkFeature($plan, 'api'),
            default => ['allowed' => true],
        };

        return $result;
    }

    /**
     * Check email sending limit
     */
    private function checkEmailLimit(User $user, int $count, Plan $plan): array
    {
        if ($plan->max_emails === 0) {
            return ['allowed' => true];
        }

        $period = UsageRecord::getCurrentPeriod();
        
        $usage = UsageRecord::where('user_id', $user->id)
            ->where('resource_type', 'emails')
            ->where('period_start', $period['start'])
            ->first();

        $currentUsage = $usage?->count ?? 0;
        $remaining = $plan->max_emails - $currentUsage;

        if ($remaining >= $count) {
            return ['allowed' => true, 'remaining' => $remaining];
        }

        return [
            'allowed' => false,
            'reason' => 'Email limit exceeded',
            'limit' => $plan->max_emails,
            'used' => $currentUsage,
        ];
    }

    /**
     * Check subscriber limit
     */
    private function checkSubscriberLimit(User $user, int $count, Plan $plan): array
    {
        if ($plan->max_subscribers === 0) {
            return ['allowed' => true];
        }

        $totalSubscribers = $user->subscribers()->count();
        $remaining = $plan->max_subscribers - $totalSubscribers;

        if ($remaining >= $count) {
            return ['allowed' => true, 'remaining' => $remaining];
        }

        return [
            'allowed' => false,
            'reason' => 'Subscriber limit exceeded',
            'limit' => $plan->max_subscribers,
            'used' => $totalSubscribers,
        ];
    }

    /**
     * Check SMTP account limit
     */
    private function checkSmtpLimit(User $user, int $count, Plan $plan): array
    {
        if ($plan->max_smtp_accounts === 0) {
            return ['allowed' => true];
        }

        $totalSmtp = $user->smtpAccounts()->count();
        $remaining = $plan->max_smtp_accounts - $totalSmtp;

        if ($remaining >= $count) {
            return ['allowed' => true, 'remaining' => $remaining];
        }

        return [
            'allowed' => false,
            'reason' => 'SMTP account limit exceeded',
            'limit' => $plan->max_smtp_accounts,
            'used' => $totalSmtp,
        ];
    }

    /**
     * Check campaign limit
     */
    private function checkCampaignLimit(User $user, int $count, Plan $plan): array
    {
        if ($plan->max_campaigns === 0) {
            return ['allowed' => true];
        }

        $totalCampaigns = $user->campaigns()->count();
        $remaining = $plan->max_campaigns - $totalCampaigns;

        if ($remaining >= $count) {
            return ['allowed' => true, 'remaining' => $remaining];
        }

        return [
            'allowed' => false,
            'reason' => 'Campaign limit exceeded',
            'limit' => $plan->max_campaigns,
            'used' => $totalCampaigns,
        ];
    }

    /**
     * Check plan feature
     */
    private function checkFeature(Plan $plan, string $feature): array
    {
        if ($plan->hasFeature($feature)) {
            return ['allowed' => true];
        }

        return [
            'allowed' => false,
            'reason' => "Plan does not include {$feature} feature",
        ];
    }

    /**
     * Record email usage
     */
    public function recordEmailUsage(User $user, int $count): void
    {
        $period = UsageRecord::getCurrentPeriod();
        
        $usage = UsageRecord::firstOrCreate(
            [
                'user_id' => $user->id,
                'resource_type' => 'emails',
                'period_start' => $period['start'],
            ],
            [
                'period_end' => $period['end'],
                'period' => 'monthly',
                'limit' => $this->getSubscription($user)?->plan->max_emails ?? 0,
            ]
        );

        $usage->increment('count', $count);

        // Also deduct from credits if needed
        $this->deductCreditsIfNeeded($user, $count);
    }

    /**
     * Deduct credits for email sending
     */
    private function deductCreditsIfNeeded(User $user, int $count): void
    {
        $subscription = $this->getSubscription($user);
        
        // Only deduct if on a plan that requires credits
        if (!$subscription || $subscription->plan->max_emails > 0) {
            return;
        }

        $balance = Credit::getBalance($user->id);
        
        if ($balance >= $count) {
            Credit::deductCredits(
                $user->id,
                $count,
                Credit::TYPE_USAGE,
                'Email sending deduction'
            );
        }
    }

    /**
     * Get usage stats for user
     */
    public function getUsageStats(User $user): array
    {
        $subscription = $this->getSubscription($user);
        
        if (!$subscription) {
            return [
                'has_subscription' => false,
                'plan' => null,
                'usage' => [],
            ];
        }

        $plan = $subscription->plan;
        $period = UsageRecord::getCurrentPeriod();

        $emailUsage = UsageRecord::where('user_id', $user->id)
            ->where('resource_type', 'emails')
            ->where('period_start', $period['start'])
            ->first();

        return [
            'has_subscription' => true,
            'plan' => [
                'name' => $plan->name,
                'max_emails' => $plan->max_emails,
                'max_subscribers' => $plan->max_subscribers,
                'max_smtp_accounts' => $plan->max_smtp_accounts,
                'features' => [
                    'analytics' => $plan->has_analytics,
                    'ai' => $plan->has_ai,
                    'api' => $plan->has_api,
                    'white_label' => $plan->has_white_label,
                ],
            ],
            'usage' => [
                'emails' => [
                    'used' => $emailUsage?->count ?? 0,
                    'limit' => $emailUsage?->limit ?? $plan->max_emails,
                    'percentage' => $emailUsage?->getPercentageUsed() ?? 0,
                ],
                'subscribers' => [
                    'used' => $user->subscribers()->count(),
                    'limit' => $plan->max_subscribers,
                    'percentage' => $plan->max_subscribers > 0 
                        ? round(($user->subscribers()->count() / $plan->max_subscribers) * 100, 1)
                        : 0,
                ],
                'smtp_accounts' => [
                    'used' => $user->smtpAccounts()->count(),
                    'limit' => $plan->max_smtp_accounts,
                ],
            ],
            'credits' => [
                'balance' => Credit::getBalance($user->id),
            ],
            'subscription' => [
                'status' => $subscription->status,
                'ends_at' => $subscription->ends_at?->format('Y-m-d'),
                'on_trial' => $subscription->isOnTrial(),
            ],
        ];
    }

    /**
     * Create a new subscription
     */
    public function createSubscription(User $user, Plan $plan): Subscription
    {
        return Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => Subscription::STATUS_ACTIVE,
            'starts_at' => now(),
            'ends_at' => now()->addDays($plan->billing_period),
        ]);
    }

    /**
     * Change user plan
     */
    public function changePlan(User $user, Plan $newPlan): bool
    {
        $subscription = $this->getSubscription($user);
        
        if (!$subscription) {
            return false;
        }

        $subscription->changePlan($newPlan);
        
        Log::info("User {$user->id} changed plan to {$newPlan->name}");
        
        return true;
    }

    /**
     * Cancel subscription
     */
    public function cancelSubscription(User $user, bool $immediately = false): bool
    {
        $subscription = $this->getSubscription($user);
        
        if (!$subscription) {
            return false;
        }

        $subscription->cancel($immediately);
        
        Log::info("User {$user->id} cancelled subscription");
        
        return true;
    }

    /**
     * Add credits to user account
     */
    public function addCredits(User $user, int $amount, string $description = null): Credit
    {
        return Credit::addCredits($user->id, $amount, Credit::TYPE_PURCHASE, $description);
    }

    /**
     * Get user credit balance
     */
    public function getCreditBalance(User $user): int
    {
        return Credit::getBalance($user->id);
    }
}
