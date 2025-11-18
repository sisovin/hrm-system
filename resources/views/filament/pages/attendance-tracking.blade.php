<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Quick Check-in/Check-out Form -->
        <x-filament::section>
            <x-slot name="heading">
                Quick Check-in / Check-out
            </x-slot>
            
            <x-slot name="description">
                Record employee attendance for today or a specific date
            </x-slot>

            <form wire:submit="checkIn" class="space-y-6">
                {{ $this->form }}
                
                <div class="flex gap-3">
                    <x-filament::button type="submit" color="success" icon="heroicon-o-arrow-right-on-rectangle">
                        Check In
                    </x-filament::button>
                    
                    <x-filament::button type="button" wire:click="checkOut" color="danger" icon="heroicon-o-arrow-left-on-rectangle">
                        Check Out
                    </x-filament::button>
                </div>
            </form>
        </x-filament::section>

        <!-- Recent Attendance Table -->
        <x-filament::section>
            <x-slot name="heading">
                Recent Attendance Records
            </x-slot>
            
            {{ $this->table }}
        </x-filament::section>
    </div>
</x-filament-panels::page>
