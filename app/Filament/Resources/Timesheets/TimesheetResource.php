<?php

namespace App\Filament\Resources\Timesheets;

use App\Filament\Resources\Timesheets\Pages\CreateTimesheet;
use App\Filament\Resources\Timesheets\Pages\EditTimesheet;
use App\Filament\Resources\Timesheets\Pages\ListTimesheets;
use App\Filament\Resources\Timesheets\Schemas\TimesheetForm;
use App\Filament\Resources\Timesheets\Tables\TimesheetsTable;
use App\Models\Timesheet;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TimesheetResource extends Resource
{
    protected static ?string $model = Timesheet::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-table-cells';
    protected static string | UnitEnum | null $navigationGroup = 'Employees management';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return TimesheetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TimesheetsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTimesheets::route('/'),
            'create' => CreateTimesheet::route('/create'),
            'edit' => EditTimesheet::route('/{record}/edit'),
        ];
    }
}
