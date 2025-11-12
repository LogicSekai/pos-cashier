<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperadminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create superadmin role
        $superadminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'superadmin']);
        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);

        // Create superadmin user
        $superadmin = \App\Models\User::firstOrCreate(
            ['email' => 'superadmin@pos.com'],
            [
                'name' => 'Super Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );
        $superadmin->assignRole($superadminRole);

        // Create admin user
        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'admin@pos.com'],
            [
                'name' => 'Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );
        $admin->assignRole($adminRole);

        $this->command->info('Superadmin and Admin users created successfully!');
        $this->command->info('Superadmin: superadmin@pos.com / password');
        $this->command->info('Admin: admin@pos.com / password');
    }
}
