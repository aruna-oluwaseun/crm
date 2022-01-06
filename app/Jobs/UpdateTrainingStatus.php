<?php

namespace App\Jobs;

use App\Models\TrainingDate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateTrainingStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //$dates = TrainingDate::all();
        $date = new \DateTime('now');
        $today = $date->format('Y-m-d');
        $date->modify('- 5 days');

        $training = TrainingDate::whereDate('date_start','>=',$date->format('Y-m-d'))->whereNotIn('status',['deleted','completed'])->get();

        if( $training && $training->count() )
        {
            foreach ($training as $training_date)
            {
                list($start_date,$start_time) = explode(' ',$training_date->date_start);
                list($end_date,$end_time) = explode(' ',$training_date->date_end);

                if($today < $start_date) {
                    continue;
                }

                if($today >= $start_date && $today <= $end_date)
                {
                    $status = 'live';
                }

                /*if($date->format('Y-m-d') >= $start_date && $date->format('Y-m-d') > $end_date)
                {
                    $status = 'completed';
                }*/

                if($today > $end_date)
                {
                    $status = 'completed';
                }

                if(isset($status))
                {
                    $training_date->status = $status;
                    $training_date->save();
                }
            }
        }
    }
}
