<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class EmployeesByDepartmentChart extends ChartWidget
{
    protected static ?int $sort = 2;
    
    public function getHeading(): ?string
    {
        return 'Employees by Department';
    }

    protected function getData(): array
    {
        $data = Employee::select('department', DB::raw('count(*) as total'))
            ->where('status', 'active')
            ->groupBy('department')
            ->orderBy('total', 'desc')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Employees',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        'rgb(59, 130, 246)',
                        'rgb(34, 197, 94)',
                        'rgb(249, 115, 22)',
                        'rgb(168, 85, 247)',
                        'rgb(236, 72, 153)',
                        'rgb(14, 165, 233)',
                        'rgb(234, 179, 8)',
                    ],
                ],
            ],
            'labels' => $data->pluck('department')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
