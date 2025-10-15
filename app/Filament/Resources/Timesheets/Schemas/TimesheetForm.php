<?php

namespace App\Filament\Resources\Timesheets\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TimesheetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('calendar_id')
                    ->label('Calendar')
                    ->relationship(name : 'calendar', titleAttribute:'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('user_id')
                    ->label('Usuario')
                    ->relationship(name : 'user', titleAttribute:'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('type')
                    ->options([
                        'work' => 'Working',
                        'pause' => 'In pause',
                    ])
                    ->required(),
                DateTimePicker::make('day_in')
                    ->required(),
                DateTimePicker::make('day_out')
                    ->required(),
            ]);
    }
}
