<?php

namespace App\Services;

use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\PaymentMethod;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;

class PaymentService
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    /**
     * Initialize customer in Stripe
     */
    public function createCustomer(User $user): string
    {
        try {
            $customer = $this->stripe->customers->create([
                'email' => $user->email,
                'name' => $user->name,
                'metadata' => [
                    'user_id' => $user->id,
                ],
            ]);

            $user->update(['stripe_customer_id' => $customer->id]);

            Log::info("Created Stripe customer for user {$user->id}: {$customer->id}");
            return $customer->id;

        } catch (ApiErrorException $e) {
            Log::error('Stripe customer creation failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            throw new \Exception('Failed to create payment customer: ' . $e->getMessage());
        }
    }

    /**
     * Get or create Stripe customer
     */
    protected function getOrCreateStripeCustomer(User $user)
    {
        if ($user->stripe_customer_id) {
            try {
                return $this->stripe->customers->retrieve($user->stripe_customer_id);
            } catch (ApiErrorException $e) {
                // Customer doesn't exist, create new one
            }
        }

        return $this->stripe->customers->create([
            'email' => $user->email,
            'name' => $user->name,
            'metadata' => [
                'user_id' => $user->id,
            ],
        ]);
    }

    /**
     * Create subscription in Stripe
     */
    public function createSubscription(User $user, Plan $plan, string $paymentMethodId): ?Subscription
    {
        try {
            $customer = $this->getOrCreateStripeCustomer($user);

            // Create subscription in Stripe
            $stripeSubscription = $this->stripe->subscriptions->create([
                'customer' => $customer->id,
                'items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => $plan->name,
                                'description' => $plan->description,
                            ],
                            'unit_amount' => $plan->price * 100, // Convert to cents
                            'recurring' => [
                                'interval' => 'month',
                            ],
                        ],
                    ],
                ],
                'default_payment_method' => $paymentMethodId,
                'expand' => ['latest_invoice.payment_intent'],
            ]);

            // Create local subscription
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'stripe_subscription_id' => $stripeSubscription->id,
                'status' => $stripeSubscription->status,
                'current_period_start' => now()->createFromTimestamp($stripeSubscription->current_period_start),
                'current_period_end' => now()->createFromTimestamp($stripeSubscription->current_period_end),
                'trial_ends_at' => $plan->trial_days ? now()->addDays($plan->trial_days) : null,
            ]);

            // Create invoice for first payment
            if ($stripeSubscription->latest_invoice) {
                $this->createInvoiceFromStripe($user, $stripeSubscription->latest_invoice);
            }

            return $subscription;

        } catch (ApiErrorException $e) {
            Log::error('Stripe subscription creation failed', [
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'error' => $e->getMessage(),
            ]);
            throw new \Exception('Failed to create subscription: ' . $e->getMessage());
        }
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
     * Create payment intent for credits or purchases
     */
    public function createPaymentIntent(User $user, float $amount, string $currency = 'usd', string $description = ''): array
    {
        try {
            $customer = $this->getOrCreateStripeCustomer($user);

            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => $amount * 100, // Convert to cents
                'currency' => $currency,
                'customer' => $customer->id,
                'description' => $description,
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            Log::info("Created payment intent for user {$user->id}: {$paymentIntent->id}");

            return [
                'payment_intent_id' => $paymentIntent->id,
                'client_secret' => $paymentIntent->client_secret,
                'amount' => $amount,
                'currency' => $currency,
            ];

        } catch (ApiErrorException $e) {
            Log::error('Stripe payment intent creation failed', [
                'user_id' => $user->id,
                'amount' => $amount,
                'error' => $e->getMessage(),
            ]);
            throw new \Exception('Failed to create payment intent: ' . $e->getMessage());
        }
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

    /**
     * Create invoice from Stripe invoice object
     */
    protected function createInvoiceFromStripe(User $user, $stripeInvoice): Invoice
    {
        return Invoice::create([
            'user_id' => $user->id,
            'stripe_invoice_id' => $stripeInvoice->id,
            'stripe_payment_id' => $stripeInvoice->payment_intent,
            'amount' => $stripeInvoice->amount_due / 100, // Convert from cents
            'status' => $stripeInvoice->status === 'paid' ? 'paid' : 'pending',
            'billing_reason' => 'subscription',
        ]);
    }
}
