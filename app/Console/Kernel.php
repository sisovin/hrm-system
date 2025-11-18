<?php

namespace App\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Console\Kernel as KernelContract;
use Illuminate\Console\Scheduling\Schedule;

class Kernel extends ConsoleKernel implements KernelContract
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Generate payroll on the 1st of every month at 1:00 AM
        $schedule->command('hr:generate-payroll')->monthlyOn(1, '1:00');
    }
}
