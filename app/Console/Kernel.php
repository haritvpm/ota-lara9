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
        \App\Console\Commands\fetchAttendaceTraceToday::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

//every 5 minutes between 9:30-17:30.        
//30-59/5 9 * * * script.sh
// */5 10-16 * * * script.sh
// 0-30/5 17 * * * script.sh
        $schedule->command('fetch:attendancetracetoday')
            ->cron('0,6,15 8 * * *'); //for sabha days at different minutes at 8 am
        
        $schedule->command('fetch:attendancetracetoday')
            ->cron('15-59/5 10 * * *'); //every five min between 10:15 to 11
        
        $schedule->command('fetch:attendancetracetoday')
            ->cron('0 11-17 * * *'); //hourly from  11 to 17
        
        

        $schedule->command('fetch:attendancetraceyesterday')
            ->cron('0 8,10 * * *'); //will the server be up at 8 am?

        $schedule->command('fetch:attendanceyesterday')
                ->cron('2 8,10 * * *');	//Run the task daily at 8:02 & 10:02

    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
