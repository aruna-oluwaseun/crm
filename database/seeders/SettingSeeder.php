<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('settings')->insert([
            ['key' => 'default_holiday_days','value' => 28],
            ['key' => 'inclusive_of_bank_holidays', 'value' => 1],
            ['key' => 'testing_payments', 'value' => 1],
            ['key' => 'expire_training_quotes','value' => 15]
        ]);
    }
}
