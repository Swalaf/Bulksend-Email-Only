<?php

namespace App\Console\Commands;

use App\Models\EmailWarmup;
use App\Services\WarmupService;
use Illuminate\Console\Command;

class ProcessWarmupBatch extends Command
{
    protected $signature = 'warmup:process {--warmup-id= : Specific warmup ID to process}';
    protected $description = 'Process warmup email batch for all active SMTP accounts';

    public function __construct(
        private WarmupService $warmupService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $warmupId = $this->option('warmup-id');

        if ($warmupId) {
            $warmups = EmailWarmup::where('id', $warmupId)->get();
        } else {
            $warmups = EmailWarmup::active()->get();
        }

        $totalSent = 0;

        foreach ($warmups as $warmup) {
            if (!$warmup->canSend()) {
                continue;
            }

            $sent = $this->warmupService->processWarmupBatch($warmup);
            $totalSent += $sent;

            $this->info("Processed warmup for SMTP {$warmup->smtp_account_id}: {$sent} emails sent");
        }

        $this->info("Total warmup emails sent: {$totalSent}");

        return Command::SUCCESS;
    }
}
