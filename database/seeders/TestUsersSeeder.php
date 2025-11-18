<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create HR Manager
        $hrManager = User::create([
            'name' => 'Sarah Johnson',
            'email' => 'hr.manager@hrm.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $hrManager->assignRole('hr_manager');
        
        // Create employee record for HR Manager
        Employee::create([
            'user_id' => $hrManager->id,
            'first_name' => 'Sarah',
            'last_name' => 'Johnson',
            'email' => 'hr.manager@hrm.com',
            'phone' => '555-0101',
            'hire_date' => now()->subYears(3)->toDateString(),
            'position' => 'HR Manager',
            'department' => 'Human Resources',
            'salary' => 75000.00,
            'status' => 'active',
            'employment_type' => 'full_time',
        ]);

        // Create Regular Employee
        $employee = User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@hrm.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $employee->assignRole('employee');
        
        // Create employee record for Regular Employee
        Employee::create([
            'user_id' => $employee->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@hrm.com',
            'phone' => '555-0102',
            'hire_date' => now()->subYear()->toDateString(),
            'position' => 'Software Developer',
            'department' => 'IT',
            'salary' => 65000.00,
            'status' => 'active',
            'employment_type' => 'full_time',
        ]);

        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin.user@hrm.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');
        
        // Create employee record for Admin
        Employee::create([
            'user_id' => $admin->id,
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin.user@hrm.com',
            'phone' => '555-0100',
            'hire_date' => now()->subYears(5)->toDateString(),
            'position' => 'System Administrator',
            'department' => 'IT',
            'salary' => 80000.00,
            'status' => 'active',
            'employment_type' => 'full_time',
        ]);

        $this->command->info('Test users created successfully!');
        $this->command->info('');
        $this->command->info('Login Credentials (all passwords: password123):');
        $this->command->info('Super Admin: admin@hrm.com');
        $this->command->info('Admin: admin.user@hrm.com');
        $this->command->info('HR Manager: hr.manager@hrm.com');
        $this->command->info('Employee: john.doe@hrm.com');
    }
}
