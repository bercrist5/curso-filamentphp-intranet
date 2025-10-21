<?php

namespace App\Filament\Personal\Resources\Timesheets\Pages;

use App\Filament\Personal\Resources\Timesheets\TimesheetResource;
use App\Models\Timesheet;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListTimesheets extends ListRecords
{
    protected static string $resource = TimesheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create Timesheet')
                ->visible(false), // Ocultamos el botón por defecto de Filament

            Action::make('inwork')
                ->label('Start Working')
                ->color('success')
                ->hidden(function (): bool {
                    $lastTimesheet = Timesheet::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
                    // Ocultar si hay un fichaje sin hora de salida
                    return $lastTimesheet && is_null($lastTimesheet->day_out);
                })
                ->requiresConfirmation()
                ->keyBindings(['command+w', 'ctrl+w'])
                ->action(function () {
                    $user = Auth::user();
                    $timesheet = new Timesheet();
                    $timesheet->calendar_id = 1;
                    $timesheet->user_id = $user->id;
                    $timesheet->type = 'work';
                    $timesheet->day_in = Carbon::now();
                    $timesheet->save();
                    Notification::make()
                    ->title('Has entrado a trabajar')
                    ->success()
                    ->send();                
                })->after(fn () => $this->dispatch('$refresh')),

            Action::make('stopwork')
                ->label('Stop Working')
                ->color('danger')
                ->hidden(function (): bool {
                    $lastTimesheet = Timesheet::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
                    // Ocultar si no hay un fichaje activo o si el fichaje activo no es de tipo 'work'
                    return !$lastTimesheet || !is_null($lastTimesheet->day_out) || $lastTimesheet->type !== 'work';
                })
                ->requiresConfirmation()
                ->keyBindings(['command+w', 'ctrl+w'])
                ->action(function () {
                    $lastTimesheet = Timesheet::where('user_id', Auth::user()->id)->whereNull('day_out')->first();
                    if (!$lastTimesheet) return;
                    $lastTimesheet->day_out = Carbon::now();
                    $lastTimesheet->save();
                    Notification::make()
                    ->title('Has dejado de trabajar')
                    ->success()
                    ->send();  
                })->after(fn () => $this->dispatch('$refresh')),

            Action::make('inPause')
                ->label('Start Pausing')
                ->color('info')
                ->hidden(function (): bool {
                    $lastTimesheet = Timesheet::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
                    // Ocultar si no hay un fichaje activo o si el fichaje activo no es de tipo 'work'
                    return !$lastTimesheet || !is_null($lastTimesheet->day_out) || $lastTimesheet->type !== 'work';
                })
                ->requiresConfirmation()
                ->keyBindings(['command+p', 'ctrl+p'])
                ->action(function () {
                    $lastTimesheet = Timesheet::where('user_id', Auth::user()->id)->whereNull('day_out')->first();
                    $lastTimesheet->day_out = Carbon::now();
                    $lastTimesheet->save();

                    $timesheet = new Timesheet();
                    $timesheet->calendar_id = 1;
                    $timesheet->user_id = Auth::user()->id;
                    $timesheet->day_in = Carbon::now();
                    $timesheet->type = 'pause';
                    $timesheet->save();
                    Notification::make()
                    ->title('Comienzas tu pausa')
                    ->success()
                    ->send();  
                })->after(fn () => $this->dispatch('$refresh')),

            Action::make('stopPause')
                ->label('Stop Pausing')
                ->color('info')
                ->hidden(function (): bool {
                    $lastTimesheet = Timesheet::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
                    // Ocultar si no hay un fichaje activo o si el fichaje activo no es de tipo 'pause'
                    return !$lastTimesheet || !is_null($lastTimesheet->day_out) || $lastTimesheet->type !== 'pause';
                })
                ->requiresConfirmation()
                ->keyBindings(['command+p', 'ctrl+p'])
                ->action(function () {
                    $lastTimesheet = Timesheet::where('user_id', Auth::user()->id)->whereNull('day_out')->first();
                    $lastTimesheet->day_out = Carbon::now();
                    $lastTimesheet->save();

                    $timesheet = new Timesheet();
                    $timesheet->calendar_id = 1;
                    $timesheet->user_id = Auth::user()->id;
                    $timesheet->day_in = Carbon::now();
                    $timesheet->type = 'work';
                    $timesheet->save();
                    Notification::make()
                    ->title('Empiezas a trabajar')
                    ->success()
                    ->send();  
                })->after(fn () => $this->dispatch('$refresh')),
        ];
    }
}
