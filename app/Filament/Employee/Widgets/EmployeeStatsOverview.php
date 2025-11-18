<?php

namespace App\Filament\Employee\Widgets;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Attendance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmployeeStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $employee = Employee::where('user_id', auth()->id())->first();
        
        if (!$employee) {
            return [];
        }

        $thisMonthPayroll = Payroll::where('employee_id', $employee->id)
            ->whereYear('pay_period_start', now()->year)
            ->whereMonth('pay_period_start', now()->month)
            ->first();
        
        $thisMonthAttendance = Attendance::where('employee_id', $employee->id)
            ->whereYear('check_in', now()->year)
            ->whereMonth('check_in', now()->month)
            ->count();

        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('check_in', today())
            ->first();

        return [
            Stat::make('This Month Salary', $thisMonthPayroll ? '$' . number_format($thisMonthPayroll->net_amount, 2) : 'Not processed')
                ->description('Net amount after deductions')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
            
            Stat::make('Days Present', $thisMonthAttendance)
                ->description('This month')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
            
            Stat::make('Today Status', $todayAttendance ? 'Checked In' : 'Not Checked In')
                ->description($todayAttendance ? 'At: ' . $todayAttendance->check_in->format('H:i A') : 'No check-in today')
                ->descriptionIcon('heroicon-m-clock')
                ->color($todayAttendance ? 'success' : 'gray'),
        ];
    }
}
