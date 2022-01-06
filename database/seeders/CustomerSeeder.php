<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Run from old_website
        $res = DB::connection('old_website')->table('jf_customers')->get();

        if($res->count())
        {
            foreach($res as $item) {
                $bill_address = false;
                $ship_address = false;
                // Process address
                if ($item->bill_address) {
                    $address = json_decode($item->bill_address);

                    if(!empty($address->address1) && !empty($address->address2) && !empty($address->town) && !empty($address->city) && !empty($address->county) && !empty($address->country)) {
                        $bill_address = DB::table('addresses')->insertGetId([
                            'line1' => isset($address->address1) ? $address->address1 : null,
                            'line2' => isset($address->address2) ? $address->address2 : null,
                            'line3' => isset($address->town) ? $address->town : null,
                            'city' => isset($address->city) ? $address->city : null,
                            'county' => isset($address->county) ? $address->county : null,
                            'postcode' => isset($address->postcode) ? $address->postcode : null,
                            'country' => isset($address->country) ? $address->country : null,
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s')
                        ]);
                    }


                }
                if ($item->ship_address) {
                    $address = json_decode($item->ship_address);

                    if(!empty($address->address1) && !empty($address->address2) && !empty($address->town) && !empty($address->city) && !empty($address->county) && !empty($address->country)) {
                        $ship_address = DB::table('addresses')->insertGetId([
                            'line1' => isset($address->address1) ? $address->address1 : null,
                            'line2' => isset($address->address2) ? $address->address2 : null,
                            'line3' => isset($address->town) ? $address->town : null,
                            'city' => isset($address->city) ? $address->city : null,
                            'county' => isset($address->county) ? $address->county : null,
                            'postcode' => isset($address->postcode) ? $address->postcode : null,
                            'country' => isset($address->country) ? $address->country : null,
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s')
                        ]);
                    }

                }

                if($item->username == null || $item->username == '') {
                    $username = null;
                } else {
                    $username = $item->username;
                }

                $customer_id = DB::table('customers')->insertGetId([
                    'first_name' => $item->firstName,
                    'last_name' => $item->lastName,
                    'company' => $item->company,
                    'contact_number' => $item->mobile,
                    'contact_number2' => $item->telephone,
                    'email' => $username,
                    'password' => $item->password,
                    'old_customer_id' => $item->id_customer,
                    'billing_id' => $bill_address ? $bill_address : null,
	                'delivery_id' => $ship_address ? $ship_address : null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                if ($bill_address && $customer_id) {
                    DB::table('customer_addresses')->insert(['customer_id' => $customer_id, 'address_id' => $bill_address]);
                }
                if ($ship_address && $customer_id) {
                    DB::table('customer_addresses')->insert(['customer_id' => $customer_id, 'address_id' => $ship_address]);
                }
            }
        }
    }
}
