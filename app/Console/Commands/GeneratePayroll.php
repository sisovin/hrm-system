<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\GeneratePayrollJob;

class GeneratePayroll extends Command
{
    protected $signature = 'hr:generate-payroll';
    protected $description = 'Generate monthly payroll for active employees';

    public function handle(): int
    {
        GeneratePayrollJob::dispatch();
        $this->info('Payroll generation dispatched.');

        return Command::SUCCESS;
    }
}
