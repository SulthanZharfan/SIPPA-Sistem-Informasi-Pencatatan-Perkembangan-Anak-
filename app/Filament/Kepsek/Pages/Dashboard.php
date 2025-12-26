<?php

namespace App\Filament\Kepsek\Pages;

use App\Filament\Kepsek\Widgets\KepsekStatsOverview;
use App\Filament\Kepsek\Widgets\KepsekStudentsK1Table;
use App\Filament\Kepsek\Widgets\KepsekStudentsK2Table;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;
use UnitEnum;

class Dashboard extends BaseDashboard
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Dashboard Kepsek';

    protected static string|UnitEnum|null $navigationGroup = null;

    public function getHeaderWidgets(): array
    {
        return [
            KepsekStatsOverview::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
            KepsekStudentsK1Table::class,
            KepsekStudentsK2Table::class,
        ];
    }
}
