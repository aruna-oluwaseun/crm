<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('permissions')->insert([
            // Create seeders
            ['title' => 'Create Roles', 'slug' => 'create-roles', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Create Franchises', 'slug' => 'create-franchises', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Create Users', 'slug' => 'create-users', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Create Customers', 'slug' => 'create-customers', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Create Admins', 'slug' => 'create-admins', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Create Products', 'slug' => 'create-products', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Create Sale Orders', 'slug' => 'create-sale-orders', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Create Production Orders', 'slug' => 'create-production-orders', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Create Categories', 'slug' => 'create-categories', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Create Product Types', 'slug' => 'create-product-types', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Create Product Attributes', 'slug' => 'create-product-attributes', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Create Training Dates', 'slug' => 'create-training-dates', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Create Invoices', 'slug' => 'create-invoices', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Create Dispatches', 'slug' => 'create-dispatches', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Create Purchase Orders', 'slug' => 'create-purchase-orders', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Create Expenses', 'slug' => 'create-expenses', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Create Refunds', 'slug' => 'create-refunds', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Create Shipping Options', 'slug' => 'create-shipping-options', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Create Couriers', 'slug' => 'create-couriers', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Create Suppliers', 'slug' => 'create-suppliers', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Create Vat Submissions', 'slug' => 'create-vat-submissions', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Create Payments', 'slug' => 'create-payments', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Create Distributors', 'slug' => 'create-distributors', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Create Files', 'slug' => 'create-files', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],

            // Delete Files
            ['title' => 'Delete Files', 'slug' => 'delete-files', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],

            // Update
            ['title' => 'Modify Settings', 'slug' => 'modify-settings', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],


            // View seeders
            ['title' => 'View Franchises', 'slug' => 'view-franchises', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'View Users', 'slug' => 'view-users', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'View Customers', 'slug' => 'view-customers', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'View Admins', 'slug' => 'view-admins', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'View Products', 'slug' => 'view-products', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'View Sale Orders', 'slug' => 'view-sale-orders', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'View Production Orders', 'slug' => 'view-production-orders', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'View Categories', 'slug' => 'view-categories', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'View Product Types', 'slug' => 'view-product-types', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'View Product Attributes', 'slug' => 'view-product-attributes', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'View Training Dates', 'slug' => 'view-training-dates', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'View Invoices', 'slug' => 'view-invoices', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'View Dispatches', 'slug' => 'view-dispatches', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'View Purchase Orders', 'slug' => 'view-purchase-orders', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'View Expenses', 'slug' => 'view-expenses', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'View Refunds', 'slug' => 'view-refunds', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'View Shipping Options', 'slug' => 'view-shipping-options', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'View Couriers', 'slug' => 'view-couriers', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'View Suppliers', 'slug' => 'view-suppliers', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'View Vat', 'slug' => 'view-vat', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'View Payments', 'slug' => 'view-payments', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'View Settings', 'slug' => 'view-settings', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'View Distributors', 'slug' => 'view-distributors', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'View Files Manager', 'slug' => 'view-files', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],


            // Approve
            ['title' => 'Add Staff Holidays / Sick Days', 'slug' => 'add-staff-holidays', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Approve Holidays', 'slug' => 'approve-holidays', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],

        ]);
    }
}
