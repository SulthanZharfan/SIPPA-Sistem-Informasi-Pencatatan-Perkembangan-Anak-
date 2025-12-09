<?php

namespace App\Filament\Guru\Widgets;

use App\Models\Guru;
use App\Models\PertemuanPresensi;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class GuruHeroWidget extends Widget
{
    protected string $view = 'capy';

    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;

    protected function getViewData(): array
    {
        /** @var Guru|null $guru */
        $guru = Auth::user()?->guru;

        $kelas = $guru?->kelas()
            ->withCount('siswas')
            ->orderBy('nama')
            ->get() ?? collect();

        $kelasNames = $kelas->pluck('nama');
        $totalKelas = $kelas->count();
        $totalSiswa = $kelas->sum('siswas_count');

        $nextPertemuan = $this->getNextOrLatestPertemuan($guru?->id);

        return [
            'guruName'          => $guru?->nama ?? Auth::user()?->name,
            'kelasNames'        => $kelasNames,
            'totalKelas'        => $totalKelas,
            'totalSiswa'        => $totalSiswa,
            'nextPertemuan'     => $nextPertemuan,
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
