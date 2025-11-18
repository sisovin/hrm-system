<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AssignRolesSeeder extends Seeder
{
    public function run(): void
    {
        // Assign roles to existing users
        $adminUser = User::where('email', 'admin@example.com')->first();
        if ($adminUser) {
            $adminUser->syncRoles(['super_admin']);
            $this->command->info('✓ admin@example.com -> super_admin');
        }

        $testUser = User::where('email', 'test@example.com')->first();
        if ($testUser) {
            $testUser->syncRoles(['admin']);
            $this->command->info('✓ test@example.com -> admin');
        }

        $hrManager = User::where('email', 'hr.manager@hrm.com')->first();
        if ($hrManager) {
            $hrManager->syncRoles(['hr_manager']);
            $this->command->info('✓ hr.manager@hrm.com -> hr_manager');
        }

        $employee = User::where('email', 'john.doe@hrm.com')->first();
        if ($employee) {
            $employee->syncRoles(['employee']);
            $this->command->info('✓ john.doe@hrm.com -> employee');
        }

        $adminUser2 = User::where('email', 'admin.user@hrm.com')->first();
        if ($adminUser2) {
            $adminUser2->syncRoles(['admin']);
            $this->command->info('✓ admin.user@hrm.com -> admin');
        }

        $this->command->info('');
        $this->command->info('=== Login Credentials ===');
        $this->command->info('Super Admin: admin@example.com / password');
        $this->command->info('Admin: test@example.com / password');
        $this->command->info('Admin: admin.user@hrm.com / password123');
        $this->command->info('HR Manager: hr.manager@hrm.com / password123');
        $this->command->info('Employee: john.doe@hrm.com / password123');
    }
}
