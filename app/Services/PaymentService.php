<?php

namespace App\Services;

use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\PaymentMethod;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    private string $provider;

    public function __construct()
    {
        $this->provider = config('billing.provider', 'stripe');
    }

    /**
     * Initialize customer in payment provider
     */
    public function createCustomer(User $user): string
    {
        // In production, this would call Stripe API
        // For now, return a mock customer ID
        Log::info("Creating customer for user {$user->id}");
        
        return 'cus_' . uniqid();
    }

    /**
     * Create subscription in payment provider
     */
    public function createSubscription(User $user, Plan $plan, string $paymentMethodId): array
    {
        // In production, this would call Stripe API
        Log::info("Creating subscription for user {$user->id} with plan {$plan->slug}");

        return [
            'subscription_id' => 'sub_' . uniqid(),
            'status' => 'active',
            'current_period_end' => now()->addDays($plan->billing_period)->toIso8601String(),
        ];
    }

    /**
     * Cancel subscription in payment provider
     */
    public function cancelSubscription(string $subscriptionId): bool
    {
        Log::info("Cancelling subscription {$subscriptionId}");
        return true;
    }

    /**
     * Create payment intent for adding credits
     */
    public function createPaymentIntent(User $user, float $amount, string $currency = 'usd'): array
    {
        Log::info("Creating payment intent for user {$user->id}: {$amount} {$currency}");

        return [
            'client_secret' => 'pi_' . uniqid() . '_secret_' . uniqid(),
            'payment_intent_id' => 'pi_' . uniqid(),
        ];
    }

    /**
     * Process successful payment
     */
    public function handleSuccessfulPayment(User $user, array $data): Transaction
    {
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'stripe_payment_id' => $data['payment_id'] ?? null,
            'type' => Transaction::TYPE_PAYMENT,
            'amount' => $data['amount'],
            'fee' => $data['fee'] ?? 0,
            'currency' => $data['currency'] ?? 'usd',
            'status' => Transaction::STATUS_COMPLETED,
            'payment_method' => $data['payment_method'] ?? 'card',
            'description' => $data['description'] ?? 'Payment',
        ]);

        // Add credits if this was a credit purchase
        if (isset($data['credits'])) {
            $billingService = app(BillingService::class);
            $billingService->addCredits($user, $data['credits'], 'Credit purchase');
        }

        return $transaction;
    }

    /**
     * Process webhook from payment provider
     */
    public function handleWebhook(array $payload): void
    {
        $eventType = $payload['type'] ?? '';
        
        match($eventType) {
            'invoice.payment_succeeded' => $this->handlePaymentSucceeded($payload),
            'invoice.payment_failed' => $this->handlePaymentFailed($payload),
            'customer.subscription.deleted' => $this->handleSubscriptionDeleted($payload),
            'customer.subscription.updated' => $this->handleSubscriptionUpdated($payload),
            default => Log::info("Unhandled webhook event: {$eventType}"),
        };
    }

    private function handlePaymentSucceeded(array $payload): void
    {
        $invoiceId = $payload['data']['object']['id'] ?? null;
        
        $invoice = Invoice::where('stripe_invoice_id', $invoiceId)->first();
        
        if ($invoice) {
            $invoice->markAsPaid();
            Log::info("Invoice {$invoiceId} marked as paid");
        }
    }

    private function handlePaymentFailed(array $payload): void
    {
        $invoiceId = $payload['data']['object']['id'] ?? null;
        
        $invoice = Invoice::where('stripe_invoice_id', $invoiceId)->first();
        
        if ($invoice) {
            $invoice->update(['status' => Invoice::STATUS_FAILED]);
            
            // Mark subscription as past due
            $invoice->subscription?->update(['status' => Subscription::STATUS_PAST_DUE]);
            
            Log::info("Invoice {$invoiceId} payment failed");
        }
    }

    private function handleSubscriptionDeleted(array $payload): void
    {
        $subscriptionId = $payload['data']['object']['id'] ?? null;
        
        $subscription = Subscription::where('stripe_subscription_id', $subscriptionId)->first();
        
        if ($subscription) {
            $subscription->update([
                'status' => Subscription::STATUS_CANCELLED,
                'ends_at' => now(),
            ]);
            
            Log::info("Subscription {$subscriptionId} cancelled");
        }
    }

    private function handleSubscriptionUpdated(array $payload): void
    {
        $subscriptionId = $payload['data']['object']['id'] ?? null;
        $status = $payload['data']['object']['status'] ?? null;
        
        $subscription = Subscription::where('stripe_subscription_id', $subscriptionId)->first();
        
        if ($subscription) {
            $subscription->update([
                'status' => match($status) {
                    'active' => Subscription::STATUS_ACTIVE,
                    'past_due' => Subscription::STATUS_PAST_DUE,
                    'canceled' => Subscription::STATUS_CANCELLED,
                    default => $subscription->status,
                },
            ]);
        }
    }

    /**
     * Get available plans
     */
    public function getPlans(): array
    {
        return Plan::active()->ordered()->get()->toArray();
    }
}
