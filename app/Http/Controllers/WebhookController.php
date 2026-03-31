<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use App\Services\EmailNotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class WebhookController extends Controller
{
    protected $stripe;
    protected $emailService;

    public function __construct(EmailNotificationService $emailService)
    {
        $this->emailService = $emailService;
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    /**
     * Handle Stripe webhooks
     */
    public function handleStripeWebhook(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            // Verify webhook signature
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe webhook signature verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        Log::info('Stripe webhook received: ' . $event->type);

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handleCheckoutSessionCompleted($event->data->object);
                break;

            case 'invoice.payment_succeeded':
                $this->handleInvoicePaymentSucceeded($event->data->object);
                break;

            case 'invoice.payment_failed':
                $this->handleInvoicePaymentFailed($event->data->object);
                break;

            case 'customer.subscription.created':
                $this->handleSubscriptionCreated($event->data->object);
                break;

            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($event->data->object);
                break;

            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;

            case 'customer.subscription.trial_will_end':
                $this->handleSubscriptionTrialWillEnd($event->data->object);
                break;

            default:
                Log::info('Unhandled webhook event: ' . $event->type);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle successful checkout session completion
     */
    private function handleCheckoutSessionCompleted($session): void
    {
        try {
            // Find user by customer ID
            $user = User::where('stripe_customer_id', $session->customer)->first();

            if (!$user) {
                Log::error('User not found for Stripe customer: ' . $session->customer);
                return;
            }

            // Create transaction record
            Transaction::create([
                'user_id' => $user->id,
                'stripe_payment_intent_id' => $session->payment_intent,
                'amount' => $session->amount_total / 100, // Convert from cents
                'currency' => $session->currency,
                'status' => 'completed',
                'type' => 'subscription',
                'metadata' => json_encode($session),
            ]);

            // Send confirmation email
            $this->emailService->sendSubscriptionConfirmation($user, [
                'amount' => $session->amount_total / 100,
                'currency' => strtoupper($session->currency),
            ]);

            Log::info("Checkout session completed for user {$user->id}");

        } catch (\Exception $e) {
            Log::error('Error handling checkout session completed: ' . $e->getMessage());
        }
    }

    /**
     * Handle successful invoice payment
     */
    private function handleInvoicePaymentSucceeded($invoice): void
    {
        try {
            $user = User::where('stripe_customer_id', $invoice->customer)->first();

            if (!$user) {
                Log::error('User not found for Stripe customer: ' . $invoice->customer);
                return;
            }

            // Update or create invoice record
            Invoice::updateOrCreate(
                ['stripe_invoice_id' => $invoice->id],
                [
                    'user_id' => $user->id,
                    'subscription_id' => $user->subscription?->id,
                    'amount' => $invoice->amount_paid / 100,
                    'currency' => $invoice->currency,
                    'status' => 'paid',
                    'paid_at' => now(),
                    'metadata' => json_encode($invoice),
                ]
            );

            // Create transaction record
            Transaction::create([
                'user_id' => $user->id,
                'stripe_invoice_id' => $invoice->id,
                'amount' => $invoice->amount_paid / 100,
                'currency' => $invoice->currency,
                'status' => 'completed',
                'type' => 'subscription_renewal',
                'metadata' => json_encode($invoice),
            ]);

            Log::info("Invoice payment succeeded for user {$user->id}");

        } catch (\Exception $e) {
            Log::error('Error handling invoice payment succeeded: ' . $e->getMessage());
        }
    }

    /**
     * Handle failed invoice payment
     */
    private function handleInvoicePaymentFailed($invoice): void
    {
        try {
            $user = User::where('stripe_customer_id', $invoice->customer)->first();

            if (!$user) {
                Log::error('User not found for Stripe customer: ' . $invoice->customer);
                return;
            }

            // Update invoice status
            Invoice::updateOrCreate(
                ['stripe_invoice_id' => $invoice->id],
                [
                    'user_id' => $user->id,
                    'subscription_id' => $user->subscription?->id,
                    'amount' => $invoice->amount_due / 100,
                    'currency' => $invoice->currency,
                    'status' => 'failed',
                    'metadata' => json_encode($invoice),
                ]
            );

            // Send payment failed notification
            $this->emailService->sendPaymentFailed($user, [
                'amount' => $invoice->amount_due / 100,
                'currency' => strtoupper($invoice->currency),
                'attempt_count' => $invoice->attempt_count,
            ]);

            Log::warning("Invoice payment failed for user {$user->id}");

        } catch (\Exception $e) {
            Log::error('Error handling invoice payment failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle subscription creation
     */
    private function handleSubscriptionCreated($subscription): void
    {
        try {
            $user = User::where('stripe_customer_id', $subscription->customer)->first();

            if (!$user) {
                Log::error('User not found for Stripe customer: ' . $subscription->customer);
                return;
            }

            // Get plan details from Stripe
            $plan = $this->stripe->plans->retrieve($subscription->items->data[0]->plan->id);

            // Create or update subscription
            Subscription::updateOrCreate(
                ['stripe_subscription_id' => $subscription->id],
                [
                    'user_id' => $user->id,
                    'plan_id' => $plan->id, // We'll need to map this to our plans table
                    'status' => $subscription->status,
                    'stripe_price_id' => $subscription->items->data[0]->price->id,
                    'current_period_start' => date('Y-m-d H:i:s', $subscription->current_period_start),
                    'current_period_end' => date('Y-m-d H:i:s', $subscription->current_period_end),
                    'trial_ends_at' => $subscription->trial_end ? date('Y-m-d H:i:s', $subscription->trial_end) : null,
                    'ends_at' => $subscription->ended_at ? date('Y-m-d H:i:s', $subscription->ended_at) : null,
                    'metadata' => json_encode($subscription),
                ]
            );

            Log::info("Subscription created for user {$user->id}");

        } catch (\Exception $e) {
            Log::error('Error handling subscription created: ' . $e->getMessage());
        }
    }

    /**
     * Handle subscription updates
     */
    private function handleSubscriptionUpdated($subscription): void
    {
        try {
            $localSubscription = Subscription::where('stripe_subscription_id', $subscription->id)->first();

            if (!$localSubscription) {
                Log::error('Local subscription not found for Stripe subscription: ' . $subscription->id);
                return;
            }

            // Update subscription details
            $localSubscription->update([
                'status' => $subscription->status,
                'current_period_start' => date('Y-m-d H:i:s', $subscription->current_period_start),
                'current_period_end' => date('Y-m-d H:i:s', $subscription->current_period_end),
                'trial_ends_at' => $subscription->trial_end ? date('Y-m-d H:i:s', $subscription->trial_end) : null,
                'ends_at' => $subscription->ended_at ? date('Y-m-d H:i:s', $subscription->ended_at) : null,
                'metadata' => json_encode($subscription),
            ]);

            // Handle status changes
            if ($subscription->status === 'canceled') {
                // Subscription was canceled
                Log::info("Subscription canceled for user {$localSubscription->user_id}");
            } elseif ($subscription->status === 'past_due') {
                // Payment is past due
                $this->emailService->sendPaymentFailed($localSubscription->user, [
                    'message' => 'Your subscription payment is past due. Please update your payment method.',
                ]);
            }

            Log::info("Subscription updated for user {$localSubscription->user_id}");

        } catch (\Exception $e) {
            Log::error('Error handling subscription updated: ' . $e->getMessage());
        }
    }

    /**
     * Handle subscription deletion
     */
    private function handleSubscriptionDeleted($subscription): void
    {
        try {
            $localSubscription = Subscription::where('stripe_subscription_id', $subscription->id)->first();

            if (!$localSubscription) {
                Log::error('Local subscription not found for Stripe subscription: ' . $subscription->id);
                return;
            }

            // Update subscription status
            $localSubscription->update([
                'status' => 'canceled',
                'ends_at' => now(),
                'metadata' => json_encode($subscription),
            ]);

            // Downgrade user to free plan or mark as expired
            $user = $localSubscription->user;
            if ($user) {
                // You might want to implement logic to downgrade the user here
                Log::info("User {$user->id} subscription ended");
            }

            Log::info("Subscription deleted for user {$localSubscription->user_id}");

        } catch (\Exception $e) {
            Log::error('Error handling subscription deleted: ' . $e->getMessage());
        }
    }

    /**
     * Handle trial ending soon
     */
    private function handleSubscriptionTrialWillEnd($subscription): void
    {
        try {
            $user = User::where('stripe_customer_id', $subscription->customer)->first();

            if (!$user) {
                Log::error('User not found for Stripe customer: ' . $subscription->customer);
                return;
            }

            // Send trial ending notification
            $this->emailService->sendNotification(
                $user,
                'Your Trial is Ending Soon',
                'Your free trial will end in 3 days. Please add a payment method to continue using BulkSend.',
                ['trial_end' => date('Y-m-d', $subscription->trial_end)]
            );

            Log::info("Trial ending notification sent to user {$user->id}");

        } catch (\Exception $e) {
            Log::error('Error handling trial will end: ' . $e->getMessage());
        }
    }
}