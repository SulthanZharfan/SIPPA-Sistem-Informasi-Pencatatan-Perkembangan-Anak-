<?php

namespace App\Filament\Wali\Widgets;

use App\Filament\Kepsek\Widgets\SiswaPerkembanganFisikChart;
use App\Models\PerkembanganFisik;

class WaliPerkembanganFisikChart extends SiswaPerkembanganFisikChart
{
    public array $allowedSiswaIds = [];

    protected function getData(): array
    {
        if (! $this->siswaId || ! in_array($this->siswaId, $this->allowedSiswaIds, true)) {
            return [
                'labels' => [],
                'datasets' => [],
            ];
        }

        $records = PerkembanganFisik::query()
            ->where('siswa_id', $this->siswaId)
            ->where('status_persetujuan', 'disetujui')
            ->when($this->startDate, fn ($q) => $q->whereDate('tanggal_ukur', '>=', $this->startDate))
            ->when($this->endDate, fn ($q) => $q->whereDate('tanggal_ukur', '<=', $this->endDate))
            ->orderBy('tanggal_ukur')
            ->get(['tanggal_ukur', 'tinggi_badan', 'berat_badan', 'lingkar_kepala']);

        return $this->buildDailyDataset($records);
    }
}
