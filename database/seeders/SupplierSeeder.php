<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $res = DB::connection('old_finance')->table('supplier')->get();

        if($res->count())
        {
            foreach($res as $item)
            {
                // check for supplier address
                $billing = DB::connection('old_finance')->table('addresses')->where('address_id', '=', $item->supplier_billing)->first();
                $supplier_head_office = DB::connection('old_finance')->table('addresses')->where('address_id', '=', $item->supplier_head_office)->first();

                if( $billing ) {
                    $bill_address = DB::table('addresses')->insertGetId([
                        'line1'     => $billing->firstline,
                        'line2'     => isset($billing->secondline) ? $billing->secondline : null,
                        'line3'     => null,
                        'city'      => isset($billing->county) ? $billing->country : null,
                        'county'    => null,
                        'postcode'  => isset($billing->postcode) ? $billing->postcode : null,
                        'country'   => null,
                        "created_at" =>  date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]);
                }

                if( $supplier_head_office ) {
                    $head_address = DB::table('addresses')->insertGetId([
                        'line1'     => $supplier_head_office->firstline,
                        'line2'     => isset($supplier_head_office->secondline) ? $supplier_head_office->secondline : null,
                        'line3'     => null,
                        'city'      => isset($supplier_head_office->county) ? $supplier_head_office->country : null,
                        'county'    => null,
                        'postcode'  => isset($supplier_head_office->postcode) ? $supplier_head_office->postcode : null,
                        'country'   => null,
                        "created_at" =>  date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]);
                }

                DB::table('suppliers')->insert([
                    'title'             => $item->supplier_name,
                    'billing_id'        => isset($bill_address) ? $bill_address : null,
                    'head_office_id'    => isset($head_address) ? $head_address : null,
                    'contact_number'    => $item->supplier_phone,
                    'contact_name'      => $item->supplier_contact,
                    'email'             => $item->supplier_email,
                    "created_at" =>  date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s')
                ]);
            }
        }
    }
}
