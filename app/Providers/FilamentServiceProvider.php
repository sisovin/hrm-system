<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use App\Filament\Resources\EmployeeResource;
use App\Filament\Resources\PayrollResource;

class FilamentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (class_exists(Filament::class)) {
            Filament::registerResources([
                EmployeeResource::class,
                PayrollResource::class,
            ]);
        }
    }
}
