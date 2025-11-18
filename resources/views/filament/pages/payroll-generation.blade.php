<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Payroll Generation Form -->
        <x-filament::section>
            <x-slot name="heading">
                Generate Payroll
            </x-slot>
            
            <x-slot name="description">
                Generate payroll for active employees for a specific pay period
            </x-slot>

            <form wire:submit="generatePayroll" class="space-y-6">
                {{ $this->form }}
                
                <div class="flex items-center gap-4">
                    <x-filament::button type="submit" color="success" icon="heroicon-o-currency-dollar" size="lg">
                        Generate Payroll
                    </x-filament::button>
                    
                    <div class="text-sm text-gray-500">
                        <p>This will create payroll records for all active employees in the selected period.</p>
                    </div>
                </div>
            </form>
        </x-filament::section>

        <!-- Payroll History Table -->
        <x-filament::section>
            <x-slot name="heading">
                Payroll History
            </x-slot>
            
            <x-slot name="description">
                View and manage generated payroll records
            </x-slot>
            
            {{ $this->table }}
        </x-filament::section>
    </div>
</x-filament-panels::page>
