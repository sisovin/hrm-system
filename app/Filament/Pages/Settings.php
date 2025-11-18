<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Cache;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected string $view = 'filament.pages.settings';

    protected static ?string $navigationLabel = 'Settings';

    protected static string|\UnitEnum|null $navigationGroup = 'System';

    protected static ?int $navigationSort = 999;

    public ?array $data = [];

    public function mount(): void
    {
        $this->loadSettings();
    }

    protected function getForms(): array
    {
        return [
            'form',
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('company_name')
                    ->label('Company Name')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('company_email')
                    ->label('Company Email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('company_phone')
                    ->label('Company Phone')
                    ->tel()
                    ->maxLength(255),
                
                Textarea::make('company_address')
                    ->label('Company Address')
                    ->rows(3)
                    ->maxLength(500)
                    ->columnSpanFull(),
                
                FileUpload::make('company_logo')
                    ->label('Company Logo')
                    ->image()
                    ->directory('company')
                    ->visibility('public')
                    ->maxSize(2048)
                    ->columnSpanFull(),

                TimePicker::make('work_start_time')
                    ->label('Work Start Time')
                    ->seconds(false),
                
                TimePicker::make('work_end_time')
                    ->label('Work End Time')
                    ->seconds(false),
                
                TextInput::make('late_threshold_minutes')
                    ->label('Late Threshold (Minutes)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(60),
                
                Select::make('payroll_frequency')
                    ->label('Payroll Frequency')
                    ->options([
                        'weekly' => 'Weekly',
                        'bi-weekly' => 'Bi-Weekly',
                        'monthly' => 'Monthly',
                        'semi-monthly' => 'Semi-Monthly',
                    ])
                    ->required(),
                
                TextInput::make('payroll_day')
                    ->label('Payroll Day of Month')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(31),
                
                TextInput::make('tax_rate')
                    ->label('Default Tax Rate (%)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->step(0.01),
                
                TextInput::make('currency')
                    ->label('Currency Code')
                    ->maxLength(3),
                
                Toggle::make('enable_overtime')
                    ->label('Enable Overtime Calculation'),
                
                TextInput::make('annual_leave_days')
                    ->label('Annual Leave Days')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(365),
                
                TextInput::make('sick_leave_days')
                    ->label('Sick Leave Days')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(365),
                
                Toggle::make('email_notifications')
                    ->label('Enable Email Notifications'),
                
                Toggle::make('notify_leave_requests')
                    ->label('Notify Leave Requests'),
                
                Toggle::make('notify_attendance_issues')
                    ->label('Notify Attendance Issues'),
                
                Select::make('timezone')
                    ->label('System Timezone')
                    ->options([
                        'UTC' => 'UTC',
                        'America/New_York' => 'America/New_York (EST)',
                        'America/Los_Angeles' => 'America/Los_Angeles (PST)',
                        'Europe/London' => 'Europe/London (GMT)',
                        'Europe/Paris' => 'Europe/Paris (CET)',
                        'Asia/Tokyo' => 'Asia/Tokyo (JST)',
                        'Asia/Shanghai' => 'Asia/Shanghai (CST)',
                        'Australia/Sydney' => 'Australia/Sydney (AEST)',
                    ])
                    ->required()
                    ->searchable(),
                
                Select::make('date_format')
                    ->label('Date Format')
                    ->options([
                        'Y-m-d' => 'YYYY-MM-DD (2025-01-15)',
                        'd/m/Y' => 'DD/MM/YYYY (15/01/2025)',
                        'm/d/Y' => 'MM/DD/YYYY (01/15/2025)',
                    ])
                    ->required(),
                
                Toggle::make('maintenance_mode')
                    ->label('Maintenance Mode'),
            ])
            ->statePath('data')
            ->columns(2);
    }

    protected function loadSettings(): void
    {
        $settings = Cache::get('system_settings', []);
        
        $defaults = [
            'company_name' => config('app.name', 'HR Management System'),
            'company_email' => config('mail.from.address'),
            'work_start_time' => '09:00',
            'work_end_time' => '17:00',
            'late_threshold_minutes' => 15,
            'payroll_frequency' => 'monthly',
            'payroll_day' => 25,
            'tax_rate' => 10,
            'currency' => 'USD',
            'enable_overtime' => true,
            'annual_leave_days' => 20,
            'sick_leave_days' => 10,
            'email_notifications' => true,
            'notify_leave_requests' => true,
            'notify_attendance_issues' => true,
            'timezone' => config('app.timezone', 'UTC'),
            'date_format' => 'Y-m-d',
            'maintenance_mode' => false,
        ];

        $this->data = array_merge($defaults, $settings);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Save to cache
        Cache::forever('system_settings', $data);

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'admin']);
    }
}
