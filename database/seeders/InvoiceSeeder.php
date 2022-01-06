<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('invoices')->insert([
            'sales_order_id' => 1,
            'invoice_status_id' => 1,
            'invoice_date' => date('Y-m-d'),
            'invoice_due' => date('Y-m-d'),
            'net_cost' => 19.99,
            'discount_cost' => 0,
            'vat_cost' => 5.996,
            'gross_cost' => 35.976,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}
