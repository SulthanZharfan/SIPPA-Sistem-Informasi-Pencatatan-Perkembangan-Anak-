<?php

namespace App\Filament\Resources\Admin\Widgets;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Wali;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class AdminStatsOverview extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected int|array|null $columns = 4;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $guruCount = Guru::count();
        $waliCount = Wali::count();
        $siswaCount = Siswa::count();
        $kelasCount = Kelas::count();
        $kepsekCount = User::role('kepsek')->count();
        $siswaK1Count = Siswa::whereHas('kelas', fn ($q) => $q->where('tingkat', 'K1')->orWhere('nama', 'K1'))->count();
        $siswaK2Count = Siswa::whereHas('kelas', fn ($q) => $q->where('tingkat', 'K2')->orWhere('nama', 'K2'))->count();
        $adminName = Auth::user()?->name ?? 'Admin';

        return [
            Stat::make('Selamat datang,', $adminName)
                ->description('Anda masuk sebagai admin')
                ->color('primary')
                ->icon('heroicon-o-user-circle')
                ->columnSpan(4)
                ->extraAttributes([
                    'style' => 'background: linear-gradient(135deg, #fff7d6 0%, #e6f7ff 100%); border-color: #f2e8c9;',
                ]),

            Stat::make('Guru', $guruCount)
                ->description('Total guru terdaftar')
                ->color('primary')
                ->icon('heroicon-o-academic-cap')
                ->extraAttributes([
                    'style' => 'background: #f0f6ff; border-color: #d6e8ff;',
                ]),

            Stat::make('Wali Murid', $waliCount)
                ->description('Total wali murid')
                ->color('success')
                ->icon('heroicon-o-users')
                ->extraAttributes([
                    'style' => 'background: #f2fbf2; border-color: #cdefd0;',
                ]),

            Stat::make('Siswa', $siswaCount)
                ->description('Total siswa aktif')
                ->color('info')
                ->icon('heroicon-o-identification')
                ->extraAttributes([
                    'style' => 'background: #f0f6ff; border-color: #d6e8ff;',
                ]),

            Stat::make('Siswa K1', $siswaK1Count)
                ->description('Total siswa kelas K1')
                ->color('info')
                ->icon('heroicon-o-academic-cap')
                ->extraAttributes([
                    'style' => 'background: #e6f7ff; border-color: #cdeeff;',
                ]),

            Stat::make('Siswa K2', $siswaK2Count)
                ->description('Total siswa kelas K2')
                ->color('info')
                ->icon('heroicon-o-academic-cap')
                ->extraAttributes([
                    'style' => 'background: #e6f1ff; border-color: #cde2ff;',
                ]),

            Stat::make('Kelas', $kelasCount)
                ->description('Total kelas')
                ->color('warning')
                ->icon('heroicon-o-building-library')
                ->extraAttributes([
                    'style' => 'background: #fff3f0; border-color: #f8d4cc;',
                ]),

            Stat::make('Kepsek', $kepsekCount)
                ->description('Pengguna dengan role kepsek')
                ->color('danger')
                ->icon('heroicon-o-shield-check')
                ->extraAttributes([
                    'style' => 'background: #ffe8e8; border-color: #f8d4cc;',
                ]),
        ];
    }
}
