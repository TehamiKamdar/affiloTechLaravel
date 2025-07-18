<?php

namespace App\Console;

use App\Models\FetchDailyData;
use App\Models\GenerateLink;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('app:temp-command')->everyMinute()->withoutOverlapping();
        $schedule->command('sync:data')->everyMinute();
        $schedule->command('test:schedule')->everyMinute();
        $schedule->command('daily-data-transaction-fetch')->everyMinute();
        $schedule->command('daily-data-fetch')->everyMinute();
        $schedule->command('daily-make-history')->everyMinute();
        $schedule->command('daily-clicks-history')->everyMinute();
        $schedule->command('generate:link')->everyTwoMinutes();
        $schedule->command('send:email')->everyFiveMinutes();
        $schedule->command('checker-tracking-url')->everyFourHours();
         $schedule->command('check-tracking-link')->everyMinute();
        $schedule->command('sync-payment-total')->everyTenMinutes();
        $schedule->command('clear-notifications')->monthlyOn(1, '12:00');
        $schedule->command('clean:links')->everyTwoMinutes();
        $schedule->command('links:delete')->daily();
        $schedule->command('make-custom-domain')->daily();
        $schedule->command('advertiser-link-transaction');
        $schedule->command('app:generate-export-export')->everyMinute();
        $schedule->command('app-fetch-and-store-logo')->everyMinute();
        $schedule->command('app-daily-data-fetch')->everyMinute();
        $schedule->command('app:remove-exported-files')->daily();
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
