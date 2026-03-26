<?php

namespace App\Jobs;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessScheduledCampaign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Campaign $campaign
    ) {}

    public function handle(): void
    {
        $campaign = $this->campaign->fresh();

        if (!$campaign->isScheduled()) {
            return;
        }

        $campaign->startSending();
        ProcessCampaignBatch::dispatch($campaign, 0);
    }
}
