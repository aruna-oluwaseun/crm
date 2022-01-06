<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('shipping_options')->insert([
            'title'             => 'Collection',
            'code'              => null,
            'is_free_shipping'  => 1
        ]);
    }
}
