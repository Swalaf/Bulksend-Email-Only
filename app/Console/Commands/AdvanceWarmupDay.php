<?php

namespace App\Console\Commands;

use App\Models\EmailWarmup;
use App\Services\WarmupService;
use Illuminate\Console\Command;

class AdvanceWarmupDay extends Command
{
    protected $signature = 'warmup:advance-day';
    protected $description = 'Advance warmup day for all active warmups (run daily)';

    public function __construct(
        private WarmupService $warmupService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $warmups = EmailWarmup::where('status', EmailWarmup::STATUS_ACTIVE)->get();

        foreach ($warmups as $warmup) {
            $this->warmupService->advanceWarmupDay($warmup);
            
            $this->info("Advanced warmup for SMTP {$warmup->smtp_account_id}: Day {$warmup->current_day}/{$warmup->total_days}");
        }

        $this->info("Processed {$warmups->count()} warmups");

        return Command::SUCCESS;
    }
}
