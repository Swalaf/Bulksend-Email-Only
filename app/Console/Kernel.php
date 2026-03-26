<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Process warmup batches every 15 minutes
        $schedule->command('warmup:process')
            ->everyFifteenMinutes()
            ->withoutOverlapping()
            ->runInBackground();

        // Advance warmup day once per day at midnight
        $schedule->command('warmup:advance-day')
            ->dailyAt('00:00')
            ->withoutOverlapping();

        // Additional batch processing to ensure emails are sent throughout the day
        $schedule->command('warmup:process')
            ->hourly()
            ->withoutOverlapping()
            ->runInBackground();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
