<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('shipping_types')->insert([
            ['title' => 'Parcel'],
            ['title' => 'Freight'],
            ['title' => 'Air Freight']
        ]);
    }
}
