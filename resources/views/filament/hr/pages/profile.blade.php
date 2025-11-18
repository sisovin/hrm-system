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

                    @if(Auth::user()->roles->isNotEmpty())
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Roles</p>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach(Auth::user()->roles as $role)
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-primary-50 text-primary-700 dark:bg-primary-400/10 dark:text-primary-400">
                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if(Auth::user()->employee)
                    <div class="col-span-2">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Employee Details</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500">Department</p>
                                <p class="text-sm font-medium">{{ Auth::user()->employee->department ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Position</p>
                                <p class="text-sm font-medium">{{ Auth::user()->employee->position ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </x-filament::card>
    </div>
</x-filament-panels::page>
