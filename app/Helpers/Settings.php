<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class Settings
{
    /**
     * Get a setting value
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = Cache::get('system_settings', []);
        
        return $settings[$key] ?? $default;
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, mixed $value): void
    {
        $settings = Cache::get('system_settings', []);
        $settings[$key] = $value;
        
        Cache::forever('system_settings', $settings);
    }

    /**
     * Get all settings
     */
    public static function all(): array
    {
        return Cache::get('system_settings', []);
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache(): void
    {
        Cache::forget('system_settings');
    }

    // Convenience methods for common settings

    public static function companyName(): string
    {
        return self::get('company_name', config('app.name', 'HR Management System'));
    }

    public static function companyEmail(): ?string
    {
        return self::get('company_email');
    }

    public static function companyPhone(): ?string
    {
        return self::get('company_phone');
    }

    public static function companyLogo(): ?string
    {
        return self::get('company_logo');
    }

    public static function workStartTime(): string
    {
        return self::get('work_start_time', '09:00');
    }

    public static function workEndTime(): string
    {
        return self::get('work_end_time', '17:00');
    }

    public static function lateThreshold(): int
    {
        return self::get('late_threshold_minutes', 15);
    }

    public static function currency(): string
    {
        return self::get('currency', 'USD');
    }

    public static function taxRate(): float
    {
        return self::get('tax_rate', 10.0);
    }

    public static function payrollFrequency(): string
    {
        return self::get('payroll_frequency', 'monthly');
    }

    public static function annualLeaveDays(): int
    {
        return self::get('annual_leave_days', 20);
    }

    public static function sickLeaveDays(): int
    {
        return self::get('sick_leave_days', 10);
    }

    public static function timezone(): string
    {
        return self::get('timezone', config('app.timezone', 'UTC'));
    }

    public static function dateFormat(): string
    {
        return self::get('date_format', 'Y-m-d');
    }

    public static function timeFormat(): string
    {
        return self::get('time_format', 'H:i');
    }

    public static function emailNotificationsEnabled(): bool
    {
        return self::get('email_notifications', true);
    }

    public static function overtimeEnabled(): bool
    {
        return self::get('enable_overtime', true);
    }

    public static function overtimeMultiplier(): float
    {
        return self::get('overtime_multiplier', 1.5);
    }

    public static function maintenanceMode(): bool
    {
        return self::get('maintenance_mode', false);
    }
}
