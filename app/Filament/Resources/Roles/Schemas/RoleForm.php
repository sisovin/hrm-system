<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Permission;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Role Name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('e.g., hr_manager')
                    ->helperText('Use lowercase with underscores (e.g., hr_manager, employee)'),
                
                TextInput::make('guard_name')
                    ->label('Guard Name')
                    ->default('web')
                    ->required()
                    ->helperText('The authentication guard that protects this role'),
                
                Select::make('permissions')
                    ->label('Assign Permissions')
                    ->multiple()
                    ->relationship('permissions', 'name')
                    ->preload()
                    ->searchable()
                    ->placeholder('Select permissions for this role')
                    ->helperText('Choose which permissions this role should have'),
            ])
            ->columns(2);
    }
}
