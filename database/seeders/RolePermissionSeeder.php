<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Create Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $user  = Role::firstOrCreate(['name' => 'user']);

        // Create Permissions
        $permissions = [
            'browse skills',
            'browse jobs',
            'post skill',
            'post job',
            'manage own profile',
            'send message',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign Permissions
        $admin->givePermissionTo(Permission::all());
        $user->givePermissionTo($permissions);
    }
}