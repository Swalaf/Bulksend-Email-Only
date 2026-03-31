<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Campaign;
use App\Models\Subscriber;
use App\Models\Analytic;
use App\Models\SmtpAccount;
use App\Models\SubscriberList;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ProcessGdprDeletions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gdpr:process-deletions {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process GDPR account deletion requests that are past the grace period';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('DRY RUN MODE - No data will be actually deleted');
        }

        // Find users whose deletion was requested more than 30 days ago
        $usersToDelete = User::where('deletion_requested_at', '<=', now()->subDays(30))
            ->where('status', 'pending_deletion')
            ->get();

        if ($usersToDelete->isEmpty()) {
            $this->info('No accounts ready for deletion.');
            return;
        }

        $this->info("Found {$usersToDelete->count()} accounts ready for deletion.");

        $progressBar = $this->output->createProgressBar($usersToDelete->count());
        $progressBar->start();

        $deletedCount = 0;

        foreach ($usersToDelete as $user) {
            try {
                $this->deleteUserData($user, $dryRun);
                $deletedCount++;

                Log::info("GDPR deletion processed for user {$user->id} ({$user->email})");

                if (!$dryRun) {
                    // Send final confirmation email
                    // Note: Email service might not work if user data is deleted, so this should be sent before deletion
                }

            } catch (\Exception $e) {
                $this->error("Failed to delete user {$user->id}: {$e->getMessage()}");
                Log::error("GDPR deletion failed for user {$user->id}: {$e->getMessage()}");
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();

        if ($dryRun) {
            $this->info("DRY RUN COMPLETE: Would have deleted {$deletedCount} accounts.");
        } else {
            $this->info("Successfully deleted {$deletedCount} accounts.");
        }
    }

    /**
     * Delete all user data according to GDPR requirements
     */
    private function deleteUserData(User $user, bool $dryRun = false): void
    {
        DB::beginTransaction();

        try {
            // Delete campaigns and related data
            $campaigns = Campaign::where('user_id', $user->id)->get();
            foreach ($campaigns as $campaign) {
                if (!$dryRun) {
                    // Delete campaign analytics
                    Analytic::where('campaign_id', $campaign->id)->delete();

                    // Delete campaign subscriber relationships
                    $campaign->subscriberLists()->detach();

                    // Delete campaign itself
                    $campaign->delete();
                }
                $this->line("  - Campaign: {$campaign->name}");
            }

            // Delete subscribers and their relationships
            $subscribers = Subscriber::where('user_id', $user->id)->get();
            foreach ($subscribers as $subscriber) {
                if (!$dryRun) {
                    // Remove from all lists
                    $subscriber->lists()->detach();

                    // Delete subscriber analytics
                    Analytic::where('subscriber_id', $subscriber->id)->delete();

                    // Delete subscriber
                    $subscriber->delete();
                }
                $this->line("  - Subscriber: {$subscriber->email}");
            }

            // Delete subscriber lists
            $lists = SubscriberList::where('user_id', $user->id)->get();
            foreach ($lists as $list) {
                if (!$dryRun) {
                    $list->delete();
                }
                $this->line("  - List: {$list->name}");
            }

            // Delete SMTP accounts
            $smtpAccounts = SmtpAccount::where('user_id', $user->id)->get();
            foreach ($smtpAccounts as $account) {
                if (!$dryRun) {
                    $account->delete();
                }
                $this->line("  - SMTP Account: {$account->name}");
            }

            // Delete any remaining analytics
            if (!$dryRun) {
                Analytic::whereHas('campaign', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->delete();
            }

            // Delete user profile and related data
            if (!$dryRun) {
                // Delete user avatar if exists
                if ($user->avatar) {
                    Storage::delete($user->avatar);
                }

                // Delete any GDPR export files
                $exportFiles = Storage::files('gdpr-exports');
                foreach ($exportFiles as $file) {
                    if (str_contains($file, "bulk_send_data_export_{$user->id}_")) {
                        Storage::delete($file);
                    }
                }

                // Finally delete the user
                $user->delete();
            }

            $this->line("  - User account: {$user->email}");

            if (!$dryRun) {
                DB::commit();
            } else {
                DB::rollBack();
            }

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
