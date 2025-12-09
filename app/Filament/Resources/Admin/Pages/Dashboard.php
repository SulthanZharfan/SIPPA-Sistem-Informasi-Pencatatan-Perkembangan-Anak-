<?php

namespace App\Filament\Resources\Admin\Pages;

use App\Filament\Resources\Admin\Widgets\AdminGuruTable;
use App\Filament\Resources\Admin\Widgets\AdminKelasTable;
use App\Filament\Resources\Admin\Widgets\AdminKepsekTable;
use App\Filament\Resources\Admin\Widgets\AdminStatsOverview;
use App\Filament\Resources\Admin\Widgets\AdminStudentsK1Table;
use App\Filament\Resources\Admin\Widgets\AdminStudentsK2Table;
use App\Filament\Resources\Admin\Widgets\AdminWaliTable;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-home';

    protected static ?string $title = 'Dashboard Admin';

    protected function getHeaderWidgets(): array
    {
        return [
            AdminStatsOverview::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
            AdminStudentsK1Table::class,
            AdminStudentsK2Table::class,
            AdminKelasTable::class,
            AdminGuruTable::class,
            AdminWaliTable::class,
            AdminKepsekTable::class,
        ];
    }
}
