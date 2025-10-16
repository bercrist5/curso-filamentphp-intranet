<?php

namespace App\Filament\Personal\Resources\Timesheets\Pages;

use App\Filament\Personal\Resources\Timesheets\TimesheetResource;
use App\Models\Timesheet;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListTimesheets extends ListRecords
{
    protected static string $resource = TimesheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('inwork')->label('Start to Work')
            ->color('success')
            ->requiresConfirmation()
            ->keyBindings(['command+w', 'ctrl+w'])
            ->action(function (){
                $user = Auth::user();
                $timesheet = new Timesheet();
                $timesheet->calendar_id =1;
                $timesheet->user_id = $user->id;
                $timesheet->type = 'work';
                $timesheet->day_in = Carbon::now();
                $timesheet->day_out = Carbon::now();;
                $timesheet->save();

            }),
            Action::make('inPause')->label('Start to Pause')
            ->color('info')
            ->requiresConfirmation()
            ->keyBindings(['command+p', 'ctrl+p'])
        ];
    }
}
