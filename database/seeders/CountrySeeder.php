<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $query = DB::connection('old_website')->table('countries')->get();

        if( $query->count() )
        {
            foreach($query as $item)
            {
                DB::table('countries')->insert([
                    'title' => $item->country,
                    'code'  => $item->country_code
                ]);
            }
        }
    }
}
