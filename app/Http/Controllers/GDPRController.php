<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Campaign;
use App\Models\Subscriber;
use App\Models\Analytic;
use App\Services\EmailNotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GDPRController extends Controller
{
    protected $emailService;

    public function __construct(EmailNotificationService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Export user data
     */
    public function exportData(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $exportData = [
                'user_profile' => $this->getUserProfileData($user),
                'campaigns' => $this->getCampaignsData($user),
                'subscribers' => $this->getSubscribersData($user),
                'analytics' => $this->getAnalyticsData($user),
                'billing' => $this->getBillingData($user),
                'exported_at' => now()->toISOString(),
                'gdpr_compliance' => true,
            ];

            // Generate filename
            $filename = 'bulk_send_data_export_' . $user->id . '_' . now()->format('Y-m-d_H-i-s') . '.json';

            // Store the export file temporarily
            Storage::put('gdpr-exports/' . $filename, json_encode($exportData, JSON_PRETTY_PRINT));

            // Send notification email
            $this->emailService->sendNotification(
                $user,
                'Your Data Export is Ready',
                'Your BulkSend data export has been generated and is available for download. The export includes all your campaigns, subscribers, analytics, and account information.'
            );

            return response()->json([
                'success' => true,
                'message' => 'Data export initiated. You will receive an email when it\'s ready for download.',
                'download_url' => route('gdpr.download', ['filename' => $filename]),
                'expires_at' => now()->addHours(24)->toISOString(),
            ]);

        } catch (\Exception $e) {
            Log::error('GDPR Data Export Failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to export data. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download exported data
     */
    public function downloadExport(Request $request, string $filename): \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\JsonResponse
    {
        try {
            // Validate filename belongs to current user
            if (!str_contains($filename, 'bulk_send_data_export_' . Auth::id() . '_')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to export file.'
                ], 403);
            }

            if (!Storage::exists('gdpr-exports/' . $filename)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Export file not found or has expired.'
                ], 404);
            }

            return Storage::download('gdpr-exports/' . $filename, $filename);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to download export file.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Request account deletion
     */
    public function requestDeletion(Request $request): JsonResponse
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
            'confirm_deletion' => 'required|accepted',
        ]);

        try {
            $user = Auth::user();

            // Mark user for deletion (soft delete after 30 days)
            $user->update([
                'deletion_requested_at' => now(),
                'deletion_reason' => $request->reason,
                'status' => 'pending_deletion',
            ]);

            // Send confirmation email
            $this->emailService->sendNotification(
                $user,
                'Account Deletion Requested',
                'Your account deletion request has been received. Your account will be permanently deleted in 30 days. During this period, you can cancel the deletion request by logging back into your account.'
            );

            // Log the deletion request
            Log::info("User {$user->id} ({$user->email}) requested account deletion");

            return response()->json([
                'success' => true,
                'message' => 'Account deletion request submitted. Your account will be deleted in 30 days.',
                'deletion_date' => now()->addDays(30)->toISOString(),
            ]);

        } catch (\Exception $e) {
            Log::error('GDPR Deletion Request Failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to process deletion request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel deletion request
     */
    public function cancelDeletion(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!$user->deletion_requested_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'No deletion request found.'
                ], 404);
            }

            $user->update([
                'deletion_requested_at' => null,
                'deletion_reason' => null,
                'status' => 'active',
            ]);

            $this->emailService->sendNotification(
                $user,
                'Account Deletion Cancelled',
                'Your account deletion request has been cancelled. Your account remains active.'
            );

            return response()->json([
                'success' => true,
                'message' => 'Account deletion request cancelled successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel deletion request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get data portability information
     */
    public function dataPortability(Request $request): JsonResponse
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => [
                'data_categories' => [
                    'Personal Information',
                    'Campaign Data',
                    'Subscriber Data',
                    'Analytics Data',
                    'Billing Information',
                    'Communication History',
                ],
                'retention_periods' => [
                    'Account data' => 'Deleted immediately upon account deletion',
                    'Campaign data' => 'Deleted with account',
                    'Analytics data' => 'Retained for 3 years for legal compliance',
                    'Billing data' => 'Retained for 7 years for tax compliance',
                ],
                'user_rights' => [
                    'Right to access your data',
                    'Right to data portability',
                    'Right to rectification',
                    'Right to erasure',
                    'Right to restrict processing',
                    'Right to object to processing',
                ],
                'last_export' => $user->last_data_export_at?->toISOString(),
                'deletion_requested' => $user->deletion_requested_at ? true : false,
                'deletion_date' => $user->deletion_requested_at?->addDays(30)->toISOString(),
            ]
        ]);
    }

    /**
     * Get user profile data for export
     */
    private function getUserProfileData(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at?->toISOString(),
            'updated_at' => $user->updated_at?->toISOString(),
            'email_verified_at' => $user->email_verified_at?->toISOString(),
            'timezone' => $user->timezone,
            'status' => $user->status,
        ];
    }

    /**
     * Get campaigns data for export
     */
    private function getCampaignsData(User $user): array
    {
        return $user->campaigns()->with(['smtpAccount', 'analytics'])->get()->map(function ($campaign) {
            return [
                'id' => $campaign->id,
                'name' => $campaign->name,
                'subject' => $campaign->subject,
                'status' => $campaign->status,
                'subscribers_count' => $campaign->subscribers_count,
                'smtp_account' => $campaign->smtpAccount ? [
                    'name' => $campaign->smtpAccount->name,
                    'from_address' => $campaign->smtpAccount->from_address,
                ] : null,
                'created_at' => $campaign->created_at?->toISOString(),
                'sent_at' => $campaign->sent_at?->toISOString(),
                'analytics' => $campaign->analytics ? [
                    'opens' => $campaign->analytics->opens,
                    'clicks' => $campaign->analytics->clicks,
                    'unsubscribes' => $campaign->analytics->unsubscribes,
                    'bounces' => $campaign->analytics->bounces,
                ] : null,
            ];
        })->toArray();
    }

    /**
     * Get subscribers data for export
     */
    private function getSubscribersData(User $user): array
    {
        return $user->subscribers()->with('lists')->get()->map(function ($subscriber) {
            return [
                'id' => $subscriber->id,
                'email' => $subscriber->email,
                'first_name' => $subscriber->first_name,
                'last_name' => $subscriber->last_name,
                'status' => $subscriber->status,
                'custom_fields' => $subscriber->custom_fields,
                'lists' => $subscriber->lists->pluck('name')->toArray(),
                'created_at' => $subscriber->created_at?->toISOString(),
                'updated_at' => $subscriber->updated_at?->toISOString(),
            ];
        })->toArray();
    }

    /**
     * Get analytics data for export
     */
    private function getAnalyticsData(User $user): array
    {
        return Analytic::whereHas('campaign', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['campaign', 'subscriber'])->get()->map(function ($analytic) {
            return [
                'id' => $analytic->id,
                'campaign_id' => $analytic->campaign_id,
                'campaign_name' => $analytic->campaign->name,
                'subscriber_email' => $analytic->subscriber->email,
                'event_type' => $analytic->event_type,
                'link_url' => $analytic->link_url,
                'user_agent' => $analytic->user_agent,
                'ip_address' => $analytic->ip_address,
                'tracked_at' => $analytic->tracked_at?->toISOString(),
            ];
        })->toArray();
    }

    /**
     * Get billing data for export
     */
    private function getBillingData(User $user): array
    {
        return [
            'subscription' => $user->subscription ? [
                'plan' => $user->subscription->plan->name ?? null,
                'status' => $user->subscription->status,
                'current_period_start' => $user->subscription->current_period_start?->toISOString(),
                'current_period_end' => $user->subscription->current_period_end?->toISOString(),
            ] : null,
            'invoices' => $user->invoices ? $user->invoices->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'amount' => $invoice->amount,
                    'status' => $invoice->status,
                    'created_at' => $invoice->created_at?->toISOString(),
                ];
            })->toArray() : [],
        ];
    }
}