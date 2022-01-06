<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('couriers')->insert([
            ['title' => 'TNT', 'url' => 'https://direct.tnt.co.uk/tracking?tk={CODE}'],
            ['title' => 'DPD', 'url' => 'https://track.dpd.co.uk/search?reference={CODE}'],
            ['title' => 'DHL', 'url' => 'https://www.dhl.com/en/express/tracking.html?AWB={CODE}'],
            ['title' => 'FedEx', 'url' => 'https://www.fedex.com/fedextrack/{CODE}']
        ]);
    }
}
