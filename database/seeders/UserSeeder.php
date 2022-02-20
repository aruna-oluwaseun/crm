<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
           [
               'first_name' => 'Richard',
               'last_name'  => 'Holmes',
               'email'      => 'developers@jenflow.co.uk',
               'password'   => Hash::make('Jenflow-321!'),
               'position_in_company' => 'PHP Developer',
               'holiday_allowance' => 28,
               'status'     => 'active',
               'updated_at' => date('Y-m-d H:i:s'),
               'created_at' => date('Y-m-d H:i:s'),
               'franchise_id'=> 1,
           ],
            [
                'first_name' => 'Mike',
                'last_name'  => 'Gibbs',
                'email'      => 'mike@jenflow.co.uk',
                'password'   => Hash::make('Jenflow-321!'),
                'position_in_company' => 'Director',
                'holiday_allowance' => 28,
                'status'     => 'active',
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'franchise_id'=> 1,
            ]
        ]);

       
    }
}
