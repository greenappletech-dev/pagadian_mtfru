<?php

namespace App\Console;

use App\Console\Commands\OperatorImageBackUp;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\URL;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        OperatorImageBackUp::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->exec('curl ' . URL::current() . '/uploadImage')
            ->sendOutputTo(public_path() . '/task/log/gdrive_up_log' . date('m-d-y-H-i-s') . '.txt');

//        $schedule->call('App\Http\Controllers\ScheduledController@uploadImages')
//            ->sendOutputTo(public_path() . '/task/log/gdrive_up_log' . date('m-d-y-H-i-s') . '.txt');
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
