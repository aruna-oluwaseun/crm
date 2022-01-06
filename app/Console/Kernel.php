<?php

namespace App\Console;

use App\Jobs\AddNationalHoliday;
use App\Jobs\UpdateTrainingStatus;
use App\Jobs\WeeklySalesReport;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /*
     * Create single cron to run all your schedule i.e
     * /-   * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1  -/
     *
     * Push jobs onto queue if need be etc etc
     **/

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
        // For shared hosting you could issue the queue to work like so
            /* $schedule->command('queue:work --tries=3')
            ->cron('* * * * * *')
            ->withoutOverlapping();
            */
        //$schedule->job(new WeeklySalesReport)->withoutOverlapping()->everyFifteenMinutes();

        // Add national holidays from Gov website
        $schedule->job(new AddNationalHoliday)->withoutOverlapping()->yearlyOn(1,1);
        $schedule->job(new UpdateTrainingStatus)->withoutOverlapping()->everyFifteenMinutes();
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
