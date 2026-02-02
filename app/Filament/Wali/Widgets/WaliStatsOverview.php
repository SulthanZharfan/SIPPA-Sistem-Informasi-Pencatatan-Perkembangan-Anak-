<?php

namespace App\Filament\Wali\Widgets;

use App\Models\Wali;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class WaliStatsOverview extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected int|array|null $columns = 1;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $wali = Wali::where('user_id', Auth::id())->first();
        $waliName = $wali?->nama_tampil ?? (Auth::user()?->name ?? 'Wali Murid');

        return [
            Stat::make('Selamat datang,', $waliName)
                ->description('Ringkasan informasi perkembangan anak.')
                ->color('primary')
                ->icon('heroicon-o-user-circle')
                ->extraAttributes([
                    'class' => 'wali-welcome-stat',
                ]),
        ];
    }
}
