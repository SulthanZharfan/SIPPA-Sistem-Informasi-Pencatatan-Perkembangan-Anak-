<?php

namespace App\Filament\Kepsek\Widgets;

use App\Models\PerkembanganKognitif;
use App\Models\PertemuanPerkembanganFisik;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class KepsekStatsOverview extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected int|array|null $columns = 2;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $fisikPending = PertemuanPerkembanganFisik::where('status', 'pending')->count();
        $kognitifPending = PerkembanganKognitif::where('status_persetujuan', 'menunggu')->count();

        $kepsekName = Auth::user()?->name ?? 'Kepala Sekolah';

        return [
            Stat::make('Selamat datang,', $kepsekName)
                ->description('Ringkasan monitoring sekolah')
                ->color('primary')
                ->icon('heroicon-o-user-circle')
                ->columnSpan(2)
                ->extraAttributes([
                    'style' => 'background: linear-gradient(135deg, #fff7d6 0%, #e6f7ff 100%); border-color: #f2e8c9;',
                ]),

            $this->buildStat('Fisik menunggu', $fisikPending, 'Pertemuan menunggu persetujuan', 'warning', 'heroicon-o-clock', 'background: #fff7e6; border-color: #f7ddb0;'),
            $this->buildStat('Kognitif menunggu', $kognitifPending, 'Menunggu persetujuan', 'warning', 'heroicon-o-clock', 'background: #fff7e6; border-color: #f7ddb0;'),
        ];
    }


    protected function buildStat(string $label, int $value, string $description, string $color, string $icon, string $style): Stat
    {
        return Stat::make($label, $value)
            ->description($description)
            ->color($color)
            ->icon($icon)
            ->extraAttributes([
                'style' => $style,
            ]);
    }

}
