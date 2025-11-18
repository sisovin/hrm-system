<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 'active')->count();
        $pendingApprovals = Employee::where('status', 'pending')->count();
        $averageSalary = Employee::where('status', 'active')->avg('salary');

        return [
            Stat::make('Total Employees', $totalEmployees)
                ->description('All registered employees')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary')
                ->chart([7, 12, 15, 18, 20, 22, $totalEmployees]),
            
            Stat::make('Active Employees', $activeEmployees)
                ->description('Currently working')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([5, 10, 12, 15, 17, 19, $activeEmployees]),
            
            Stat::make('Pending Approvals', $pendingApprovals)
                ->description('Awaiting HR review')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->chart([3, 2, 4, 3, 1, 2, $pendingApprovals]),
            
            Stat::make('Average Salary', '$' . number_format($averageSalary, 2))
                ->description('Across all employees')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('info'),
        ];
    }
}
