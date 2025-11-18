<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Profile Information Form --}}
        <x-filament::card>
            <form wire:submit="updateProfile">
                {{ $this->profileForm }}
                
                <div class="mt-6">
                    <x-filament::button type="submit">
                        Update Profile
                    </x-filament::button>
                </div>
            </form>
        </x-filament::card>

        {{-- Update Password Form --}}
        <x-filament::card>
            <form wire:submit="updatePassword">
                {{ $this->passwordForm }}
                
                <div class="mt-6">
                    <x-filament::button type="submit" color="warning">
                        Update Password
                    </x-filament::button>
                </div>
            </form>
        </x-filament::card>

        {{-- Employee Information --}}
        @if(Auth::user()->employee)
        <x-filament::card>
            <div class="space-y-4">
                <h3 class="text-lg font-semibold">My Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Employee ID</p>
                        <p class="text-sm text-gray-900 dark:text-gray-100">
                            #{{ Auth::user()->employee->id }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Department</p>
                        <p class="text-sm text-gray-900 dark:text-gray-100">
                            {{ Auth::user()->employee->department ?? 'Not Assigned' }}
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Position</p>
                        <p class="text-sm text-gray-900 dark:text-gray-100">
                            {{ Auth::user()->employee->position ?? 'Not Assigned' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Employment Type</p>
                        <p class="text-sm text-gray-900 dark:text-gray-100">
                            {{ ucfirst(str_replace('_', ' ', Auth::user()->employee->employment_type ?? 'N/A')) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Hire Date</p>
                        <p class="text-sm text-gray-900 dark:text-gray-100">
                            {{ Auth::user()->employee->hire_date ? \Carbon\Carbon::parse(Auth::user()->employee->hire_date)->format('F j, Y') : 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</p>
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full
                            @if(Auth::user()->employee->status === 'active') bg-green-50 text-green-700 dark:bg-green-400/10 dark:text-green-400
                            @elseif(Auth::user()->employee->status === 'pending') bg-yellow-50 text-yellow-700 dark:bg-yellow-400/10 dark:text-yellow-400
                            @else bg-gray-50 text-gray-700 dark:bg-gray-400/10 dark:text-gray-400
                            @endif">
                            {{ ucfirst(Auth::user()->employee->status) }}
                        </span>
                    </div>

                    @if(Auth::user()->employee->phone)
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</p>
                        <p class="text-sm text-gray-900 dark:text-gray-100">
                            {{ Auth::user()->employee->phone }}
                        </p>
                    </div>
                    @endif

                    @if(Auth::user()->employee->address)
                    <div class="col-span-2">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</p>
                        <p class="text-sm text-gray-900 dark:text-gray-100">
                            {{ Auth::user()->employee->address }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </x-filament::card>
        @endif

        {{-- Account Information --}}
        <x-filament::card>
            <div class="space-y-4">
                <h3 class="text-lg font-semibold">Account Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Member Since</p>
                        <p class="text-sm text-gray-900 dark:text-gray-100">
                            {{ Auth::user()->created_at->format('F j, Y') }}
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</p>
                        <p class="text-sm text-gray-900 dark:text-gray-100">
                            {{ Auth::user()->updated_at->diffForHumans() }}
                        </p>
                    </div>
                    
                    @if(Auth::user()->email_verified_at)
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Verified</p>
                        <p class="text-sm text-green-600 dark:text-green-400">
                            ✓ Verified on {{ Auth::user()->email_verified_at->format('F j, Y') }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </x-filament::card>
    </div>
</x-filament-panels::page>
