<?php

namespace App\Filament\Hr\Resources\Employees\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->numeric(),
                TextInput::make('first_name')
                    ->required(),
                TextInput::make('last_name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DatePicker::make('date_of_birth'),
                Select::make('gender')
                    ->options(['male' => 'Male', 'female' => 'Female', 'other' => 'Other']),
                Textarea::make('address')
                    ->columnSpanFull(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('position'),
                TextInput::make('department'),
                TextInput::make('salary')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
                Select::make('employment_type')
                    ->options([
            'full_time' => 'Full time',
            'part_time' => 'Part time',
            'contract' => 'Contract',
            'intern' => 'Intern',
        ])
                    ->default('full_time')
                    ->required(),
                DatePicker::make('hire_date'),
                TextInput::make('experience_years')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
