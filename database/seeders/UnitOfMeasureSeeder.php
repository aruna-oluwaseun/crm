<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitOfMeasureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('unit_of_measures')->insert([
            ['title' => 'grams', 'code' => 'g'],
            ['title' => 'Kilograms', 'code' => 'Kg'],
            ['title' => 'Millilitres', 'code' => 'ml'],
            ['title' => 'Litres', 'code' => 'L'],
            ['title' => 'Meter Squared', 'code' => 'm2'],
        ]);
    }
}
