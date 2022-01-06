<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Run from old_website
        $res = DB::connection('old_website')->table('jf_categories')->get();

        if($res->count())
        {
            foreach($res as $item)
            {
                DB::table('categories')->insert([
                    'title'                 => $item->cat_name,
                    'code'                  => null,
                    'slug'                  => Str::slug(ucwords($item->cat_name),'-'),
                    'created_at'            => date('Y-m-d H:i:s'),
                    'updated_at'            => date('Y-m-d H:i:s')
                ]);
            }
        }
    }
}
