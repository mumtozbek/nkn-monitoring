<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\SyncUptime::class,
        Commands\SyncMonitor::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Sync node information.
        $schedule->command('sync:uptime')->everyTenMinutes()->withoutOverlapping(3600);
        $schedule->command('sync:monitor')->everyThirtyMinutes()->withoutOverlapping(3600);
        $schedule->command('sync:location')->everyMinute()->withoutOverlapping(3600);

        // Check wallet id every 30 minutes.
        $schedule->command('wallets:id')->everyThirtyMinutes()->withoutOverlapping(3600);

        // Run scheduled jobs.
        $schedule->command('queue:retry all')->everyMinute()->withoutOverlapping(3600);
        $schedule->command('queue:work --stop-when-empty')->everyMinute()->withoutOverlapping(3600);

        // Need to run restart and reboot commands after all jobs.
        $schedule->command('restart:slow')->everyMinute()->withoutOverlapping(3600);
        $schedule->command('restart:reboot')->everyMinute()->withoutOverlapping(3600);
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
