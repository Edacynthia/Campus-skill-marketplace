<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create the admin role if it doesn't exist
        $role = Role::firstOrCreate(['name' => 'admin']);

        // Create the admin user if they don't exist
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'first_name'      => 'Admin',
                'last_name'       => 'User',
                'email'           => 'admin@gmail.com',
                'password'        => Hash::make('Admin@1234'),
                'role'            => 'admin',
                'is_approved'     => true,
                'approval_status' => 'approved',
                'otp_verified'    => true,
            ]
        );

        // Assign the admin role
        $admin->assignRole('admin');
    }
}