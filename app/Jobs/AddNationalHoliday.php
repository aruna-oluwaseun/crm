<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class AddNationalHoliday implements ShouldQueue
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
        try {
            $response = \Illuminate\Support\Facades\Http::get('https://www.gov.uk/bank-holidays.json');

            DB::beginTransaction();
            $result = $response->json();
            foreach ($result as $location => $dates)
            {
                foreach ($dates['events'] as $event)
                {
                    $dt = new \DateTime($event['date']);

                    if(in_array($dt->format('l'), ['Saturday','Sunday'])) {
                        continue; // only include working days
                    }

                    $data = [
                        'title' => $event['title'],
                        'date' => $event['date'],
                        'division' => $location
                    ];

                    if(!DB::table('national_holidays')->updateOrInsert($data, $data))
                    {
                        Throw new \Exception('Could not add national holidays');
                    }
                }
            }


            DB::commit();

            return true;

        } catch (\Throwable $e) {
            report($e);
            DB::rollBack();
        }

    }
}
