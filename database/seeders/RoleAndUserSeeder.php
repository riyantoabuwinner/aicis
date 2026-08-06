<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $authorRole = Role::firstOrCreate(['name' => 'author']);
        $reviewerRole = Role::firstOrCreate(['name' => 'reviewer']);

        // Create superadmin user
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@aicis.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'), // default password
            ]
        );
        $superadmin->assignRole($superadminRole);

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@aicis.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'), // default password
            ]
        );
        $admin->assignRole($adminRole);
    }
}
