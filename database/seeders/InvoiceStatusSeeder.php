<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('invoice_statuses')->insert([
            ['title' => 'Pending payment', 'classes' => 'warning'],
            ['title' => 'Part paid', 'classes' => 'success'],
            ['title' => 'Paid', 'classes' => 'success'],
            ['title' => 'Voided','classes' => 'danger']
        ]);
    }
}
