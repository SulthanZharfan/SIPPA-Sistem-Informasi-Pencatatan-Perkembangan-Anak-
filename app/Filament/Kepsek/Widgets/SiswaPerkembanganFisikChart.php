<?php

namespace App\Filament\Kepsek\Widgets;

use App\Models\PerkembanganFisik;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Collection;

class SiswaPerkembanganFisikChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Perkembangan Fisik';

    public ?int $siswaId = null;
    public ?string $startDate = null;
    public ?string $endDate = null;

    protected function getData(): array
    {
        if (! $this->siswaId) {
            return [
                'labels' => [],
                'datasets' => [],
            ];
        }

        $records = PerkembanganFisik::query()
            ->where('siswa_id', $this->siswaId)
            ->when($this->startDate, fn ($q) => $q->whereDate('tanggal_ukur', '>=', $this->startDate))
            ->when($this->endDate, fn ($q) => $q->whereDate('tanggal_ukur', '<=', $this->endDate))
            ->orderBy('tanggal_ukur')
            ->get(['tanggal_ukur', 'tinggi_badan', 'berat_badan', 'lingkar_kepala']);

        return $this->buildDailyDataset($records);
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function buildDailyDataset(Collection $records): array
    {
        if ($records->isEmpty()) {
            return [
                'labels' => [],
                'datasets' => [],
            ];
        }

        $labels = [];
        $tinggiBadan = [];
        $beratBadan = [];
        $lingkarKepala = [];

        $records->each(function ($record) use (&$labels, &$tinggiBadan, &$beratBadan, &$lingkarKepala) {
            $labels[] = Carbon::parse($record->tanggal_ukur)->format('d M Y');
            $tinggiBadan[] = (float) $record->tinggi_badan;
            $beratBadan[] = (float) $record->berat_badan;
            $lingkarKepala[] = (float) $record->lingkar_kepala;
        });

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'TB (cm)',
                    'data' => $tinggiBadan,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.2)',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'BB (kg)',
                    'data' => $beratBadan,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.2)',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'LK (cm)',
                    'data' => $lingkarKepala,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'tension' => 0.3,
                ],
            ],
        ];
    }
}
