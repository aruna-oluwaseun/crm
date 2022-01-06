<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FranchiseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('franchises')->insert([
            [
                'title'               => 'Jenflow Systems Ltd',
                'code'                => 'JEN1',
                'national_holiday'    => 'england-and-wales',
                'global_owner'        => 1,
                'contact_number'      => '01922 907711',
                'contact_name'        => 'Tom or Mike',
                'email'               => 'info@jenflow.co.uk',
                'payment_term_id'     => null,
                'vat_number'          => '234 3454 24',
                'company_number'      => '526771',
                'status'              => 'active'
            ],
            [
                'title'               => 'Jenflow Brownhills',
                'code'                => 'JEN2',
                'national_holiday'    => 'england-and-wales',
                'global_owner'        => 0,
                'contact_number'      => '01543374819',
                'contact_name'        => 'Rich the sexy bitch',
                'email'               => 'rich@richmedia-design.co.uk',
                'payment_term_id'     => null,
                'vat_number'          => '234 3454 24',
                'company_number'      => '526771',
                'status'              => 'active'
            ]

        ]);
    }
}
