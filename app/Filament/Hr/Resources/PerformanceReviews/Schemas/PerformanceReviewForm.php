<?php

namespace App\Filament\Hr\Resources\PerformanceReviews\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PerformanceReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('employee_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('review_date')
                    ->required(),
                TextInput::make('score')
                    ->required()
                    ->numeric(),
                Textarea::make('notes')
                    ->columnSpanFull(),
                TextInput::make('reviewed_by')
                    ->numeric(),
            ]);
    }
}
