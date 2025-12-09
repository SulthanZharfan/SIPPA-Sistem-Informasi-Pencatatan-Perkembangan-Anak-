<?php

namespace App\Filament\Guru\Widgets;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\PertemuanPresensi;
use App\Models\Siswa;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class GuruStatsOverview extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected int|array|null $columns = 3;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        /** @var Guru|null $guru */
        $guru = Guru::with('kelas')
            ->where('user_id', Auth::id())
            ->first();
        $guruId = $guru?->id;

        $kelasIds = $guruId
            ? Kelas::where('guru_id', $guruId)->pluck('id')
            : collect();

        $totalKelas = $kelasIds->count();
        $totalSiswa = $kelasIds->isNotEmpty()
            ? Siswa::whereIn('kelas_id', $kelasIds)->count()
            : 0;

        $totalPertemuan = $guruId
            ? PertemuanPresensi::where('guru_id', $guruId)->count()
            : 0;

        $kelasNames = $guru?->kelas
            ? $guru->kelas->pluck('nama')->join(', ')
            : '';

        $nextPertemuan = $this->getNextOrLatestPertemuan($guruId);
        $agendaText = $nextPertemuan
            ? sprintf(
                '%s • Pertemuan %s (%s %s-%s)',
                $nextPertemuan->kelas->nama ?? 'Kelas',
                $nextPertemuan->pertemuan_ke,
                $nextPertemuan->tanggal?->format('d M Y'),
                $nextPertemuan->jam_mulai?->format('H:i'),
                $nextPertemuan->jam_selesai?->format('H:i'),
            )
            : 'Belum ada agenda presensi';

        $guruName = $guru?->nama ?? Auth::user()?->name ?? 'Guru';

        $greetingDescription = $kelasNames
            ? 'Membina: '.$kelasNames
            : 'Belum ada kelas terdaftar';

        return [
            Stat::make('Selamat datang,', $guruName)
                ->description($greetingDescription)
                ->color('primary')
                ->icon('heroicon-o-user-circle')
                ->extraAttributes([
                    'title' => $agendaText,
                    'style' => 'background: linear-gradient(135deg, #fff7d6 0%, #e6f7ff 100%); border-color: #f2e8c9;',
                ])
                ->columnSpan(3),

            Stat::make('Total siswa', $totalSiswa)
                ->description('Akumulasi seluruh kelas')
                ->color('success')
                ->icon('heroicon-o-users')
                ->extraAttributes([
                    'style' => 'background: #f2fbf2; border-color: #cdefd0;',
                ]),

            Stat::make('Kelas dibina', $totalKelas)
                ->description('Kelas aktif yang diawasi')
                ->color('info')
                ->icon('heroicon-o-building-library')
                ->extraAttributes([
                    'style' => 'background: #f0f6ff; border-color: #d6e8ff;',
                ]),

            Stat::make('Pertemuan tercatat', $totalPertemuan)
                ->description('Jadwal presensi yang dibuat')
                ->color('warning')
                ->icon('heroicon-o-calendar-days')
                ->extraAttributes([
                    'style' => 'background: #fff3f0; border-color: #f8d4cc;',
                ]),
        ];
    }

    protected function getNextOrLatestPertemuan(?int $guruId): ?PertemuanPresensi
    {
        if (!$guruId) {
            return null;
        }

        $today = now()->startOfDay();

        $next = PertemuanPresensi::where('guru_id', $guruId)
            ->whereDate('tanggal', '>=', $today)
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->first();

        if ($next) {
            return $next;
        }

        return PertemuanPresensi::where('guru_id', $guruId)
            ->orderByDesc('tanggal')
            ->orderByDesc('jam_mulai')
            ->first();
    }
}
