<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('roles')->insert([
            ['title' => 'Super Admin', 'slug' => 'super-admin', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Admin', 'slug' => 'admin', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Accounts', 'slug' => 'accounts', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Warehouse', 'slug' => 'warehouse', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Warehouse Supervisor', 'slug' => 'warehouse-supervisor', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Custom Permissions', 'slug' => 'custom-permissions', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
            ['title' => 'Warehouse Manager', 'slug' => 'warehouse-manager', 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')],
        ]);

        $permissions = DB::table('permissions')->get();

        // super admin gets all
        if($permissions) {
            foreach($permissions as $permission)
            {
                // Super Admin
                DB::table('permission_role')->insert(['role_id' => 1, 'permission_id' => $permission->id]);
            }
        }
    }
}
