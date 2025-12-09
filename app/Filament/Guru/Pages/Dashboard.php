<?php

namespace App\Filament\Guru\Pages;

use App\Filament\Guru\Widgets\GuruHeroWidget;
use App\Filament\Guru\Widgets\GuruStatsOverview;
use App\Filament\Guru\Widgets\GuruStudentsTable;
use Filament\Pages\Dashboard as BaseDashboard;
use BackedEnum;
use UnitEnum ;

class Dashboard extends BaseDashboard
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Dashboard Guru';

    protected static ?string $title = 'Dashboard Guru';

    protected static string | UnitEnum | null $navigationGroup = null;

    public function getHeaderWidgets(): array
    {
        return [
            GuruStatsOverview::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
            GuruStudentsTable::class,
        ];
    }
}
