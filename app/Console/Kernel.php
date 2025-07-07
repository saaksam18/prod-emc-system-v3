<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        // Clear cache every day at midnight (12:00 AM)
        $schedule->command('cache:clear')->daily();

        // Clear cache Route every day at midnight (12:00 AM)
        $schedule->command('route:clear')->daily();

        // Clear cache View every day at midnight (12:00 AM)
        $schedule->command('view:clear')->daily();
        
        // Clear cache Config route every day at midnight (12:00 AM)
        $schedule->command('config:clear')->daily();

        // Run a full backup daily at 1:00 AM, copying to all configured disks
        $schedule->command('backup:run')->dailyAt('01:00');

        // Clean up old backups according to your strategy
        //$schedule->command('backup:clean')->dailyAt('02:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
