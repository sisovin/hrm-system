<?php

namespace App\Filament\Hr\Widgets;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Attendance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class HrStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $activeEmployees = Employee::where('status', 'active')->count();
        $pendingEmployees = Employee::where('status', 'pending')->count();
        $thisMonthPayroll = Payroll::whereYear('pay_period_start', now()->year)
            ->whereMonth('pay_period_start', now()->month)
            ->sum('net_amount');
        $todayAttendance = Attendance::whereDate('check_in', today())->count();

        return [
            Stat::make('Active Employees', $activeEmployees)
                ->description('Currently active')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
            
            Stat::make('Pending Approvals', $pendingEmployees)
                ->description('Awaiting approval')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            
            Stat::make('This Month Payroll', '$' . number_format($thisMonthPayroll, 2))
                ->description('Total payroll this month')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('info'),
            
            Stat::make('Today\'s Attendance', $todayAttendance)
                ->description('Checked in today')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),
        ];
    }
}
