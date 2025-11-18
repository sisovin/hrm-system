<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        if (! class_exists(Role::class)) {
            // Spatie permission is not installed; exit gracefully
            return;
        }

        // Create core roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $hr = Role::firstOrCreate(['name' => 'hr']);
        $employee = Role::firstOrCreate(['name' => 'employee']);

        // Create an admin user if none exists
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        if (method_exists($adminUser, 'assignRole')) {
            $adminUser->assignRole($admin->name);
        }
    }
}
