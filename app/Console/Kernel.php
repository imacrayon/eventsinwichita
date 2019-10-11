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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $email = config('eventsinwichita.mail.admin');
        $schedule->command('sync:eventbrite')->daily()->emailOutputOnFailure($email);
        $schedule->command('sync:ticketmaster')->daily()->emailOutputOnFailure($email);
        $schedule->command('sync:wichita360')->daily()->emailOutputOnFailure($email);
        $schedule->command('sync:kirbys')->daily()->emailOutputOnFailure($email);
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
