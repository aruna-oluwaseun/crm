<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class
DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            FranchiseSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            UnitOfMeasureSeeder::class,
            SupplierSeeder::class,
            VatTypeSeeder::class,
            ProductTypeSeeder::class,
            ProductSeeder::class,
            CustomerSeeder::class,
            OrderTypeSeeder::class,
            PaymentTermSeeder::class,
            PurchaseOrderStatusSeeder::class,
            SalesOrderStatusSeeder::class,
            //SalesOrderSeeder::class,
            InvoiceStatusSeeder::class,
            //InvoiceSeeder::class,
            ProductChildSeeder::class,
            LeadTimeSeeder::class,
            CountrySeeder::class,
            ShippingOptionSeeder::class,
            ShippingTypeSeeder::class,
            CourierSeeder::class,
            PaymentMethodSeeder::class,
            SettingSeeder::class,
            WatermarkSeeder::class
        ]);
    }
}
