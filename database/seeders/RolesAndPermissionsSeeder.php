<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create or get roles
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $hrManager = Role::firstOrCreate(['name' => 'hr_manager']);
        $employee = Role::firstOrCreate(['name' => 'employee']);

        // Get all permissions
        $allPermissions = Permission::all();

        // Super Admin gets all permissions
        $superAdmin->syncPermissions($allPermissions);

        // Admin gets most permissions except super admin specific ones
        $adminPermissions = Permission::where('name', 'not like', '%role%')
            ->where('name', 'not like', '%shield%')
            ->get();
        $admin->syncPermissions($adminPermissions);

        // HR Manager gets employee, payroll, attendance, and performance review permissions
        $hrPermissions = Permission::where(function ($query) {
            $query->where('name', 'like', '%employee%')
                ->orWhere('name', 'like', '%payroll%')
                ->orWhere('name', 'like', '%attendance%')
                ->orWhere('name', 'like', '%performance_review%');
        })->get();
        $hrManager->syncPermissions($hrPermissions);

        // Employee gets limited view permissions
        $employeePermissions = Permission::whereIn('name', [
            'view_employee',
            'view_any_employee',
            'view_payroll',
            'view_any_payroll',
            'view_attendance',
            'view_any_attendance',
            'view_performance_review',
            'view_any_performance_review',
        ])->get();
        $employee->syncPermissions($employeePermissions);

        // Assign super_admin role to the first user
        $user = User::where('email', 'admin@hrm.com')->first();
        if ($user) {
            $user->assignRole('super_admin');
            $this->command->info('Super Admin role assigned to admin@hrm.com');
        }

        $this->command->info('Roles and permissions created successfully!');
        $this->command->info('');
        $this->command->info('Created Roles:');
        $this->command->info('- super_admin (Full Access)');
        $this->command->info('- admin (Most Permissions)');
        $this->command->info('- hr_manager (HR Management)');
        $this->command->info('- employee (View Only)');
    }
}
