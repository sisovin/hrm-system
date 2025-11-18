<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">
                Personal Information
            </x-slot>
            
            <x-slot name="description">
                Update your personal details. Some fields are read-only and can only be updated by HR.
            </x-slot>

            <form wire:submit="save" class="space-y-6">
                {{ $this->form }}
                
                <div class="flex gap-3 pt-4 border-t">
                    <x-filament::button type="submit" color="primary" icon="heroicon-o-check">
                        Update Profile
                    </x-filament::button>
                </div>
            </form>
        </x-filament::section>

        @if($employee)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-filament::section>
                <x-slot name="heading">
                    Attendance Summary
                </x-slot>
                
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">This Month</span>
                        <span class="font-semibold">{{ $employee->attendances()->whereMonth('date', now()->month)->count() }} days</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">This Year</span>
                        <span class="font-semibold">{{ $employee->attendances()->whereYear('date', now()->year)->count() }} days</span>
                    </div>
                </div>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">
                    Payroll Summary
                </x-slot>
                
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Current Salary</span>
                        <span class="font-semibold text-green-600">${{ number_format($employee->salary, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Paid</span>
                        <span class="font-semibold">${{ number_format($employee->payrolls()->where('status', 'paid')->sum('net_salary'), 2) }}</span>
                    </div>
                </div>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">
                    Employment Info
                </x-slot>
                
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Department</span>
                        <span class="font-semibold">{{ $employee->department }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Tenure</span>
                        <span class="font-semibold">{{ $employee->hire_date?->diffForHumans(['parts' => 1]) }}</span>
                    </div>
                </div>
            </x-filament::section>
        </div>
        @endif
    </div>
</x-filament-panels::page>
