<?php

namespace App\Jobs;

use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class GeneratePayrollJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        // Job data if needed
    }

    public function handle(): void
    {
        $periodStart = Carbon::now()->startOfMonth();
        $periodEnd = Carbon::now()->endOfMonth();

        Employee::where('status', 'active')->chunk(100, function ($employees) use ($periodStart, $periodEnd) {
            foreach ($employees as $employee) {
                Payroll::create([
                    'employee_id' => $employee->id,
                    'period_start' => $periodStart->toDateString(),
                    'period_end' => $periodEnd->toDateString(),
                    'amount' => $employee->salary,
                    'status' => 'generated',
                ]);
            }
        });
    }
}
