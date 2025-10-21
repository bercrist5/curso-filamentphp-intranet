<?php

namespace App\Filament\Personal\Widgets;

use App\Models\Holiday;
use App\Models\Timesheet;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class PersonalWidgetStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {

        return [
            Stat::make('Pending Holidays', $this->getPendingHoliday(Auth::user())),
            Stat::make('Approved Holidays', $this->getApprovedHolidays(Auth::user())),
            Stat::make('Declined Holidays', $this->getDeclinedHolidays(Auth::user())),
            Stat::make('Total work', $this->getTotalWork(Auth::user()))
             ->description('hh:mm'),
        ];
    }

    protected function getPendingHoliday(User $user)
    {
        $totalPendingHolidays = Holiday::where('user_id', $user->id)->where('type', 'pending')->get()->count();
        return $totalPendingHolidays;
    }

    protected function getApprovedHolidays(User $user)
    {
        $totalApprovedHolidays = Holiday::where('user_id', $user->id)->where('type', 'aprove')->get()->count();
        return $totalApprovedHolidays;
    }

    protected function getDeclinedHolidays(User $user)
    {
        $totalDeclinedHolidays = Holiday::where('user_id', $user->id)->where('type', 'decline')->get()->count();
        return $totalDeclinedHolidays;
    }

    protected function getTotalWork(User $user)
    {
        $totalTimesheets = Timesheet::where('user_id', $user->id)
            ->where('type', 'work')->whereDate('created_at', Carbon::today())->get();
        $totalMinutes = 0;
        foreach ($totalTimesheets as $timesheet) {
            $startTime = Carbon::parse($timesheet->day_in);
            $finishTime = Carbon::parse($timesheet->day_out);
            $totalMinutes += $finishTime->diffInMinutes($startTime);
        }

        if ($totalMinutes === 0) {
            return '00:00';
        }

        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        return sprintf('%02d:%02d', $hours, $minutes);
    }
}
