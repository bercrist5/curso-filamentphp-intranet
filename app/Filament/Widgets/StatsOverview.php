<?php

namespace App\Filament\Widgets;

use App\Models\Holiday;
use App\Models\Timesheet;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalEmployees = User::all()->count();
        $totalHolidays = Holiday::all()->count();
        $totalTimesheets = Timesheet::all()->count();

        return [
            Stat::make('Total Employeess', $totalEmployees),
            Stat::make('Total Holidays', $totalHolidays),
            Stat::make('Total Timesheets', $totalTimesheets),
        ];
    }
}
