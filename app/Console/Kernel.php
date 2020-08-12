<?php

namespace App\Console;

use App\Jobs\PingHealthChecks;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new PingHealthChecks);

        $schedule->command('steamorders:refresh')->everyMinute();

        $schedule->command('orders:recheck')->everyMinute();

        $schedule->command('backup:clean')->daily()->at('04:00');
        $schedule->command('backup:run')->daily()->at('05:00');

        $schedule->command('webhooks:send')->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
