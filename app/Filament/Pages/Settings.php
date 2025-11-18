<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
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
                Tabs::make('Settings')
                    ->tabs([
                        Tabs\Tab::make('General')
                            ->icon('heroicon-o-building-office')
                            ->schema([
                                TextInput::make('company_name')
                                    ->label('Company Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->default('HR Management System'),
                                
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
                                    ->maxLength(500),
                                
                                FileUpload::make('company_logo')
                                    ->label('Company Logo')
                                    ->image()
                                    ->directory('company')
                                    ->visibility('public')
                                    ->maxSize(2048)
                                    ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/jpg']),
                            ]),

                        Tabs\Tab::make('Attendance')
                            ->icon('heroicon-o-clock')
                            ->schema([
                                TimePicker::make('work_start_time')
                                    ->label('Work Start Time')
                                    ->default('09:00')
                                    ->seconds(false),
                                
                                TimePicker::make('work_end_time')
                                    ->label('Work End Time')
                                    ->default('17:00')
                                    ->seconds(false),
                                
                                TextInput::make('late_threshold_minutes')
                                    ->label('Late Threshold (Minutes)')
                                    ->numeric()
                                    ->default(15)
                                    ->minValue(0)
                                    ->maxValue(60)
                                    ->helperText('Number of minutes after start time to mark as late'),
                                
                                TextInput::make('half_day_hours')
                                    ->label('Half Day Hours')
                                    ->numeric()
                                    ->default(4)
                                    ->minValue(1)
                                    ->maxValue(8),
                                
                                TextInput::make('full_day_hours')
                                    ->label('Full Day Hours')
                                    ->numeric()
                                    ->default(8)
                                    ->minValue(1)
                                    ->maxValue(12),
                                
                                Toggle::make('enable_auto_checkout')
                                    ->label('Enable Auto Check-out')
                                    ->helperText('Automatically check out employees at end time if not checked out')
                                    ->default(true),
                            ]),

                        Tabs\Tab::make('Payroll')
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                Select::make('payroll_frequency')
                                    ->label('Payroll Frequency')
                                    ->options([
                                        'weekly' => 'Weekly',
                                        'bi-weekly' => 'Bi-Weekly',
                                        'monthly' => 'Monthly',
                                        'semi-monthly' => 'Semi-Monthly',
                                    ])
                                    ->default('monthly')
                                    ->required(),
                                
                                TextInput::make('payroll_day')
                                    ->label('Payroll Day of Month')
                                    ->numeric()
                                    ->default(25)
                                    ->minValue(1)
                                    ->maxValue(31)
                                    ->helperText('Day of the month when payroll is processed'),
                                
                                TextInput::make('tax_rate')
                                    ->label('Default Tax Rate (%)')
                                    ->numeric()
                                    ->default(10)
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->step(0.01),
                                
                                TextInput::make('currency')
                                    ->label('Currency')
                                    ->default('USD')
                                    ->maxLength(3)
                                    ->helperText('Currency code (e.g., USD, EUR, GBP)'),
                                
                                Toggle::make('enable_overtime')
                                    ->label('Enable Overtime Calculation')
                                    ->default(true),
                                
                                TextInput::make('overtime_multiplier')
                                    ->label('Overtime Rate Multiplier')
                                    ->numeric()
                                    ->default(1.5)
                                    ->minValue(1)
                                    ->maxValue(3)
                                    ->step(0.1)
                                    ->visible(fn ($get) => $get('enable_overtime')),
                            ]),

                        Tabs\Tab::make('Leave Management')
                            ->icon('heroicon-o-calendar-days')
                            ->schema([
                                TextInput::make('annual_leave_days')
                                    ->label('Annual Leave Days')
                                    ->numeric()
                                    ->default(20)
                                    ->minValue(0)
                                    ->maxValue(365),
                                
                                TextInput::make('sick_leave_days')
                                    ->label('Sick Leave Days')
                                    ->numeric()
                                    ->default(10)
                                    ->minValue(0)
                                    ->maxValue(365),
                                
                                TextInput::make('casual_leave_days')
                                    ->label('Casual Leave Days')
                                    ->numeric()
                                    ->default(5)
                                    ->minValue(0)
                                    ->maxValue(365),
                                
                                Toggle::make('carry_forward_leaves')
                                    ->label('Carry Forward Unused Leaves')
                                    ->helperText('Allow employees to carry forward unused leaves to next year')
                                    ->default(false),
                                
                                TextInput::make('max_carry_forward_days')
                                    ->label('Max Carry Forward Days')
                                    ->numeric()
                                    ->default(5)
                                    ->minValue(0)
                                    ->maxValue(30)
                                    ->visible(fn ($get) => $get('carry_forward_leaves')),
                            ]),

                        Tabs\Tab::make('Performance')
                            ->icon('heroicon-o-star')
                            ->schema([
                                Select::make('review_frequency')
                                    ->label('Review Frequency')
                                    ->options([
                                        'quarterly' => 'Quarterly',
                                        'semi-annually' => 'Semi-Annually',
                                        'annually' => 'Annually',
                                    ])
                                    ->default('annually')
                                    ->required(),
                                
                                TextInput::make('rating_scale')
                                    ->label('Rating Scale (1 to X)')
                                    ->numeric()
                                    ->default(5)
                                    ->minValue(3)
                                    ->maxValue(10),
                                
                                Toggle::make('enable_self_review')
                                    ->label('Enable Self Review')
                                    ->helperText('Allow employees to submit self-reviews')
                                    ->default(true),
                                
                                Toggle::make('enable_peer_review')
                                    ->label('Enable Peer Review')
                                    ->helperText('Allow peer-to-peer reviews')
                                    ->default(false),
                                
                                Toggle::make('mandatory_review_comments')
                                    ->label('Mandatory Review Comments')
                                    ->default(true),
                            ]),

                        Tabs\Tab::make('Notifications')
                            ->icon('heroicon-o-bell')
                            ->schema([
                                Toggle::make('email_notifications')
                                    ->label('Enable Email Notifications')
                                    ->default(true),
                                
                                Toggle::make('notify_leave_requests')
                                    ->label('Notify Leave Requests')
                                    ->helperText('Send notifications for new leave requests')
                                    ->default(true),
                                
                                Toggle::make('notify_attendance_issues')
                                    ->label('Notify Attendance Issues')
                                    ->helperText('Send notifications for late arrivals or absences')
                                    ->default(true),
                                
                                Toggle::make('notify_payroll_processed')
                                    ->label('Notify Payroll Processed')
                                    ->helperText('Send notifications when payroll is processed')
                                    ->default(true),
                                
                                Toggle::make('notify_review_due')
                                    ->label('Notify Review Due')
                                    ->helperText('Send notifications for upcoming performance reviews')
                                    ->default(true),
                                
                                TextInput::make('admin_notification_email')
                                    ->label('Admin Notification Email')
                                    ->email()
                                    ->maxLength(255)
                                    ->helperText('Email address for admin notifications'),
                            ]),

                        Tabs\Tab::make('System')
                            ->icon('heroicon-o-server')
                            ->schema([
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
                                    ->default('UTC')
                                    ->required()
                                    ->searchable(),
                                
                                Select::make('date_format')
                                    ->label('Date Format')
                                    ->options([
                                        'Y-m-d' => 'YYYY-MM-DD (2025-01-15)',
                                        'd/m/Y' => 'DD/MM/YYYY (15/01/2025)',
                                        'm/d/Y' => 'MM/DD/YYYY (01/15/2025)',
                                        'd-M-Y' => 'DD-MMM-YYYY (15-Jan-2025)',
                                    ])
                                    ->default('Y-m-d')
                                    ->required(),
                                
                                Select::make('time_format')
                                    ->label('Time Format')
                                    ->options([
                                        'H:i' => '24-hour (13:00)',
                                        'h:i A' => '12-hour (01:00 PM)',
                                    ])
                                    ->default('H:i')
                                    ->required(),
                                
                                Toggle::make('maintenance_mode')
                                    ->label('Maintenance Mode')
                                    ->helperText('Enable maintenance mode for system updates')
                                    ->default(false),
                                
                                Toggle::make('enable_cache')
                                    ->label('Enable Cache')
                                    ->default(true),
                                
                                TextInput::make('session_lifetime')
                                    ->label('Session Lifetime (Minutes)')
                                    ->numeric()
                                    ->default(120)
                                    ->minValue(30)
                                    ->maxValue(1440),
                            ]),
                    ]),
            ])
            ->statePath('data');
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
            'half_day_hours' => 4,
            'full_day_hours' => 8,
            'enable_auto_checkout' => true,
            'payroll_frequency' => 'monthly',
            'payroll_day' => 25,
            'tax_rate' => 10,
            'currency' => 'USD',
            'enable_overtime' => true,
            'overtime_multiplier' => 1.5,
            'annual_leave_days' => 20,
            'sick_leave_days' => 10,
            'casual_leave_days' => 5,
            'carry_forward_leaves' => false,
            'max_carry_forward_days' => 5,
            'review_frequency' => 'annually',
            'rating_scale' => 5,
            'enable_self_review' => true,
            'enable_peer_review' => false,
            'mandatory_review_comments' => true,
            'email_notifications' => true,
            'notify_leave_requests' => true,
            'notify_attendance_issues' => true,
            'notify_payroll_processed' => true,
            'notify_review_due' => true,
            'timezone' => config('app.timezone', 'UTC'),
            'date_format' => 'Y-m-d',
            'time_format' => 'H:i',
            'maintenance_mode' => false,
            'enable_cache' => true,
            'session_lifetime' => 120,
        ];

        $this->data = array_merge($defaults, $settings);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Save to cache
        Cache::forever('system_settings', $data);

        // Optionally save to database or config files
        // You can create a settings table and store there

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
