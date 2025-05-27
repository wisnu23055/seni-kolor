<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat permission
        Permission::create(['name' => 'access-admin']);
        Permission::create(['name' => 'manage-products']); // Contoh permission tambahan
    
        // 2. Buat role
        $adminRole = Role::create(['name' => 'admin']);
        $ownerRole = Role::create(['name' => 'owner']);
        $customerRole = Role::create(['name' => 'customer']);
    
        // 3. Assign permission ke role admin
        $adminRole->givePermissionTo(['access-admin', 'manage-products']);

        // 4. Assign permission ke role owner
        $ownerRole->givePermissionTo(['access-admin', 'manage-products']);
    }
}