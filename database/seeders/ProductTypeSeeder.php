<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $res = DB::connection('old_finance')->table('product_type')->get();

        if($res->count())
        {
            foreach ($res as $item)
            {
                DB::table('product_types')->insert(['title' => $item->type_title,'code' => $item->type_short_code,'status' => 'active']);
            }
        }

    }
}
