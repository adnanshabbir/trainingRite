<?php

namespace App\Console;

use App\Console\Commands\SendScheduledMessages;
use App\Mail\DailyCallsLogs;
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
        Commands\SendScheduledMessages::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(SendScheduledMessages::class)->everyMinute();

        // Send daily Calls logs
        $schedule->call(function () {
            \Mail::to('trainingrite@gmail.com')->send(new DailyCallsLogs);
        })->daily();


    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
