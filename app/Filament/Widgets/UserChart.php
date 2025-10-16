<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class UserChart extends ChartWidget
{
    protected ?string $heading = 'User Chart';
    public ?string $filter = 'year';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Usuarios de vacaciones',
                    'data' => $this->getDataUser(),
                    'backgroundColor' => '#dcdf2173',
                    'borderColor' => '#f5d234ff',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    // Por defecto, para 'year'



    protected function getDataUser()
    {
        return [2,6,85,4,5,9,6,8,4,2,3,6];
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hoy',
            'week' => 'Última semana',
            'month' => 'Este mes',
            'year' => 'Este año',
        ];
    }
}
