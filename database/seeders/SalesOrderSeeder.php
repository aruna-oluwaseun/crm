<?php

namespace Database\Seeders;

use App\Models\UnitOfMeasure;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sales_orders')->insert([
            'customer_id' => 1,
            'customer_ref' => 'My house stuff',
            'first_name' => 'Richard',
            'last_name' => 'Holmes',
            'order_number' => '123',
            'contact_number' => '07951132117',
            'billing_address_data' => '{"line1":"29 Vicarage Road","line2":"Brownhills","line3":"Walsall","county":"West Midlands","postcode":"Ws8 6ar"}',
            'delivery_address_data' => '{"line1":"29 Vicarage Road","line2":"Brownhills","line3":"Walsall","county":"West Midlands","postcode":"Ws8 6ar"}',
            'email' => 'riggerz29@hotmail.com',
            'shipping_cost' => 9.99,
            'shipping_vat' => 1.998,
            'shipping_gross' => 11.99,
            'net_cost'  => 19.99,
            'vat_cost'  => 3.998,
            'gross_cost' => 35.976,
            'outstanding' => 35.976,
            'cost_to_us' => 0.00,
            'weight_kg' => 0.0010,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'payment_term_id' => 1,
            'order_type_id' => 2,
            'sales_order_status_id' => 3,
        ]);

        DB::table('sales_order_items')->insert([
            'sales_order_id' => 1,
            'product_title' => 'Test Product',
            'qty' => 1,
            'weight' => 1.000,
            'weight_kg' => 0.0010,//UnitOfMeasure::convert(1,'Kg',1.000)->value,
            'unit_of_measure_id' => 1,
            'vat_type_id' => 1,
            'vat_percentage' => 20.00,
            'item_cost'  => 19.99,
            'net_cost'  => 19.99,
            'vat_cost'  => 3.998,
            'gross_cost' => 23.998,
            'cost_to_us' => 0.00,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}
