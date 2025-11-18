<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AverageSalaryByDepartmentBarChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 1;

    public function getHeading(): ?string
    {
        return 'Average Salary by Department';
    }

    protected function getData(): array
    {
        $salaryData = Employee::select('department', DB::raw('AVG(salary) as avg_salary'))
            ->where('status', 'active')
            ->whereNotNull('department')
            ->whereNotNull('salary')
            ->groupBy('department')
            ->orderBy('avg_salary', 'desc')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Average Salary',
                    'data' => $salaryData->pluck('avg_salary')->map(fn ($salary) => round((float) $salary, 2))->toArray(),
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)',  // Blue
                        'rgba(16, 185, 129, 0.8)',  // Green
                        'rgba(245, 158, 11, 0.8)',  // Amber
                        'rgba(239, 68, 68, 0.8)',   // Red
                        'rgba(139, 92, 246, 0.8)',  // Purple
                        'rgba(236, 72, 153, 0.8)',  // Pink
                        'rgba(20, 184, 166, 0.8)',  // Teal
                    ],
                    'borderColor' => [
                        'rgba(59, 130, 246, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(245, 158, 11, 1)',
                        'rgba(239, 68, 68, 1)',
                        'rgba(139, 92, 246, 1)',
                        'rgba(236, 72, 153, 1)',
                        'rgba(20, 184, 166, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $salaryData->pluck('department')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return "$" + value.toLocaleString(); }',
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return "Average: $" + context.parsed.y.toLocaleString(); }',
                    ],
                ],
            ],
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'admin']);
    }
}
