<?php

namespace App\Services;

use App\Models\User;
use App\Models\Campaign;
use App\Models\MarketplacePurchase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailNotificationService
{
    /**
     * Send welcome email to new user
     */
    public function sendWelcomeEmail(User $user): void
    {
        try {
            Mail::send('emails.welcome', [
                'user' => $user,
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Welcome to BulkSend!')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

            Log::info("Welcome email sent to user {$user->id}");

        } catch (\Exception $e) {
            Log::error("Failed to send welcome email to user {$user->id}: " . $e->getMessage());
        }
    }

    /**
     * Send email verification notification
     */
    public function sendEmailVerification(User $user, string $verificationUrl): void
    {
        try {
            Mail::send('emails.verify-email', [
                'user' => $user,
                'verificationUrl' => $verificationUrl,
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Verify Your Email Address')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

        } catch (\Exception $e) {
            Log::error("Failed to send verification email to user {$user->id}: " . $e->getMessage());
        }
    }

    /**
     * Send password reset notification
     */
    public function sendPasswordReset(User $user, string $resetUrl): void
    {
        try {
            Mail::send('emails.reset-password', [
                'user' => $user,
                'resetUrl' => $resetUrl,
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Reset Your Password')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

        } catch (\Exception $e) {
            Log::error("Failed to send password reset email to user {$user->id}: " . $e->getMessage());
        }
    }

    /**
     * Send campaign completion notification
     */
    public function sendCampaignCompleted(User $user, Campaign $campaign): void
    {
        try {
            Mail::send('emails.campaign-completed', [
                'user' => $user,
                'campaign' => $campaign,
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject("Campaign '{$campaign->name}' Completed")
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

        } catch (\Exception $e) {
            Log::error("Failed to send campaign completion email to user {$user->id}: " . $e->getMessage());
        }
    }

    /**
     * Send vendor application approved notification
     */
    public function sendVendorApproved(User $user): void
    {
        try {
            Mail::send('emails.vendor-approved', [
                'user' => $user,
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Your Vendor Application Has Been Approved!')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

        } catch (\Exception $e) {
            Log::error("Failed to send vendor approval email to user {$user->id}: " . $e->getMessage());
        }
    }

    /**
     * Send vendor application rejected notification
     */
    public function sendVendorRejected(User $user, string $reason = null): void
    {
        try {
            Mail::send('emails.vendor-rejected', [
                'user' => $user,
                'reason' => $reason,
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Vendor Application Update')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

        } catch (\Exception $e) {
            Log::error("Failed to send vendor rejection email to user {$user->id}: " . $e->getMessage());
        }
    }

    /**
     * Send marketplace purchase confirmation
     */
    public function sendPurchaseConfirmation(User $user, MarketplacePurchase $purchase): void
    {
        try {
            Mail::send('emails.purchase-confirmation', [
                'user' => $user,
                'purchase' => $purchase,
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('SMTP Purchase Confirmation')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

        } catch (\Exception $e) {
            Log::error("Failed to send purchase confirmation email to user {$user->id}: " . $e->getMessage());
        }
    }

    /**
     * Send subscription confirmation
     */
    public function sendSubscriptionConfirmation(User $user, $subscription): void
    {
        try {
            Mail::send('emails.subscription-confirmation', [
                'user' => $user,
                'subscription' => $subscription,
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Subscription Activated')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

        } catch (\Exception $e) {
            Log::error("Failed to send subscription confirmation email to user {$user->id}: " . $e->getMessage());
        }
    }

    /**
     * Send payment failed notification
     */
    public function sendPaymentFailed(User $user, $invoice): void
    {
        try {
            Mail::send('emails.payment-failed', [
                'user' => $user,
                'invoice' => $invoice,
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Payment Failed - Action Required')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

        } catch (\Exception $e) {
            Log::error("Failed to send payment failed email to user {$user->id}: " . $e->getMessage());
        }
    }

    /**
     * Send generic notification
     */
    public function sendNotification(User $user, string $subject, string $message, array $data = []): void
    {
        try {
            Mail::send('emails.notification', [
                'user' => $user,
                'subject' => $subject,
                'message' => $message,
                'data' => $data,
            ], function ($mail) use ($user, $subject) {
                $mail->to($user->email)
                     ->subject($subject)
                     ->from(config('mail.from.address'), config('mail.from.name'));
            });

        } catch (\Exception $e) {
            Log::error("Failed to send notification email to user {$user->id}: " . $e->getMessage());
        }
    }
}