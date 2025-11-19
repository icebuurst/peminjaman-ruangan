<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Daily full backup at 02:00
        $schedule->command('backup:run')->dailyAt('02:00');

        // Weekly DB-only backup on Monday at 03:00
        $schedule->command('backup:run --only-db')->weeklyOn(1, '03:00');

        // Monthly full backup on day 1 at 04:00
        $schedule->command('backup:run')->monthlyOn(1, '04:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/../../routes/console.php');
    }
}
