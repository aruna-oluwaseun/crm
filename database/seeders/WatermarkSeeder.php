<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WatermarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('watermarks')->insert([
            ['file' => 'watermarks/Jenflow-Systems-Logo.png', 'default' => 1],
            ['file' => 'watermarks/Jenflow-Systems-Logo-Inverse.png', 'default' => 0]
        ]);
    }
}
