<?php

namespace App\Filament\Kepsek\Resources\Siswas\Pages;

use App\Filament\Kepsek\Resources\Siswas\SiswaResource;
use App\Filament\Kepsek\Widgets\SiswaPerkembanganFisikChart;
use App\Filament\Kepsek\Widgets\SiswaPerkembanganFisikTable;
use App\Filament\Kepsek\Widgets\SiswaPerkembanganKognitifTable;
use App\Filament\Kepsek\Widgets\SiswaPresensiTable;
use App\Models\PerkembanganFisik;
use App\Models\PerkembanganKognitif;
use App\Models\Presensi;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class ViewSiswa extends ViewRecord implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $resource = SiswaResource::class;

    public ?array $filters = [];

    public function mount(int | string $record): void
    {
        parent::mount($record);

        $this->record->loadMissing(['kelas.guru', 'wali']);

        $defaultDate = $this->getDefaultFilterDate() ?? now()->toDateString();
        $defaultWeeklyStart = $this->getWeeklyPeriodStart($defaultDate);

        $this->filters = [
            'mode' => 'semua',
            'tanggal_mingguan' => $defaultWeeklyStart ?? $defaultDate,
            'bulan' => Carbon::parse($defaultDate)->format('Y-m'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTitle(): string
    {
        return 'Ringkasan Siswa: ' . ($this->record->nama ?? '-');
    }

    public function getHeading(): string
    {
        return 'Ringkasan Siswa: ' . ($this->record->nama ?? '-');
    }

    public function content(Schema $schema): Schema
    {
        [$fisikStart, $fisikEnd] = $this->getFisikRange();
        $kognitifTahunAjaranId = $this->getKognitifTahunAjaranId();

        [$presensiStart, $presensiEnd] = $this->getPresensiRange();

        $presensiSummary = $this->getPresensiSummary(null, null);
        $kognitifSummary = $this->getKognitifSummary($kognitifTahunAjaranId);
        $fisikLatest = $this->getLatestFisik($fisikStart, $fisikEnd);
        $fisikRekomendasiText = $this->getFisikRecommendation($fisikLatest['status_raw'] ?? null);

        return $schema->components([
            Section::make('Informasi Umum')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Text::make('Nama: ' . ($this->record->nama ?? '-')),
                            Text::make('NISN: ' . ($this->record->nis ?? '-')),
                            Text::make('Kelas: ' . ($this->record->kelas?->nama ?? '-')),
                            Text::make('Guru: ' . ($this->record->kelas?->guru?->nama ?? '-')),
                            Text::make('Wali Murid: ' . ($this->record->wali?->nama_tampil ?? '-')),
                        ]),
                ]),

            Section::make('Ringkasan Presensi')
                ->schema([
                    Form::make([
                        EmbeddedSchema::make('filtersForm'),
                    ]),
                    Grid::make(4)
                        ->schema([
                            Text::make('Hadir: ' . $presensiSummary['hadir'])
                                ->badge()
                                ->color('success'),
                            Text::make('Alfa: ' . $presensiSummary['alfa'])
                                ->badge()
                                ->color('danger'),
                            Text::make('Izin: ' . $presensiSummary['izin'])
                                ->badge()
                                ->color('warning'),
                            Text::make('Sakit: ' . $presensiSummary['sakit'])
                                ->badge()
                                ->color('info'),
                        ]),
                    Livewire::make(SiswaPresensiTable::class, fn () => [
                        'siswaId' => $this->record->id,
                        'startDate' => $presensiStart,
                        'endDate' => $presensiEnd,
                    ])->key('presensi-' . $this->record->id . '-' . ($presensiStart ?? 'all') . '-' . ($presensiEnd ?? 'all')),
                ]),

            Section::make('Ringkasan Perkembangan Fisik')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Text::make('Status Terbaru: ' . ($fisikLatest['status'] ?? '-'))
                                ->badge()
                                ->color($fisikLatest['status_color'] ?? 'gray'),
                            Text::make('Tanggal Terbaru: ' . ($fisikLatest['tanggal'] ?? '-')),
                        ]),
                    Livewire::make(SiswaPerkembanganFisikChart::class, fn () => [
                        'siswaId' => $this->record->id,
                    ])->key('fisik-chart-' . $this->record->id)->columnSpanFull(),
                    Section::make('Usulan / Rekomendasi')
                        ->schema([
                            Html::make(new HtmlString(
                                '<div style="font-size: 1rem; line-height: 1.65; color: #1f2937;">' .
                                    e($fisikRekomendasiText) .
                                '</div>' .
                                '<div style="margin-top: 0.5rem; font-size: 0.875rem; line-height: 1.5; color: #6b7280;">' .
                                    'Catatan: Usulan ini merupakan hasil pengolahan sistem dan digunakan sebagai bahan pertimbangan pendukung.' .
                                '</div>'
                            )),
                        ])
                        ->columnSpanFull(),
                    Livewire::make(SiswaPerkembanganFisikTable::class, fn () => [
                        'siswaId' => $this->record->id,
                        'startDate' => $fisikStart,
                        'endDate' => $fisikEnd,
                    ])->key('fisik-' . $this->record->id . '-' . $fisikStart . '-' . $fisikEnd),
                ]),

            Section::make('Ringkasan Perkembangan Kognitif')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            Text::make('Disetujui: ' . $kognitifSummary['disetujui'])
                                ->badge()
                                ->color('success'),
                            Text::make('Menunggu: ' . $kognitifSummary['menunggu'])
                                ->badge()
                                ->color('warning'),
                            Text::make('Revisi: ' . $kognitifSummary['revisi'])
                                ->badge()
                                ->color('danger'),
                        ]),
                    Livewire::make(SiswaPerkembanganKognitifTable::class, fn () => [
                        'siswaId' => $this->record->id,
                        'tahunAjaranId' => $kognitifTahunAjaranId,
                    ])->key('kognitif-' . $this->record->id . '-' . ($kognitifTahunAjaranId ?? 'all')),
                ]),
        ]);
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(3)
                    ->schema([
                        ToggleButtons::make('mode')
                            ->label('Periode')
                            ->options([
                                'semua' => 'Semua',
                                'mingguan' => 'Mingguan',
                                'bulanan' => 'Bulanan',
                            ])
                            ->grouped()
                            ->inline()
                            ->default('semua')
                            ->live(),

                        Select::make('tanggal_mingguan')
                            ->label('Minggu')
                            ->visible(fn (Get $get) => $get('mode') === 'mingguan')
                            ->options($this->getWeeklyOptions())
                            ->searchable()
                            ->live(),

                        Select::make('bulan')
                            ->label('Bulan')
                            ->visible(fn (Get $get) => $get('mode') === 'bulanan')
                            ->options($this->getMonthlyOptions())
                            ->searchable()
                            ->live(),
                    ]),
            ])
            ->statePath('filters');
    }

    protected function getPresensiRange(): array
    {
        $mode = $this->filters['mode'] ?? 'semua';

        $date = match ($mode) {
            'mingguan' => $this->filters['tanggal_mingguan'] ?? null,
            'bulanan' => $this->filters['bulan'] ?? null,
            default => null,
        };

        $base = $date
            ? ($mode === 'bulanan' ? Carbon::createFromFormat('Y-m', $date) : Carbon::parse($date))
            : Carbon::today();

        return match ($mode) {
            'semua' => [null, null],
            'mingguan' => [
                $base->copy()->toDateString(),
                $base->copy()->addDays(6)->toDateString(),
            ],
            'bulanan' => [
                $base->copy()->startOfMonth()->toDateString(),
                $base->copy()->endOfMonth()->toDateString(),
            ],
            default => [
                $base->toDateString(),
                $base->toDateString(),
            ],
        };
    }

    protected function getPresensiSummary(?string $startDate, ?string $endDate): array
    {
        $result = Presensi::query()
            ->where('siswa_id', $this->record->id)
            ->when($startDate, fn ($q) => $q->whereDate('tanggal', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->whereDate('tanggal', '<=', $endDate))
            ->selectRaw("
                SUM(status_kehadiran = 'hadir') as hadir_count,
                SUM(status_kehadiran = 'alfa') as alfa_count,
                SUM(status_kehadiran = 'izin') as izin_count,
                SUM(status_kehadiran = 'sakit') as sakit_count
            ")
            ->first();

        return [
            'hadir' => (int) ($result->hadir_count ?? 0),
            'alfa' => (int) ($result->alfa_count ?? 0),
            'izin' => (int) ($result->izin_count ?? 0),
            'sakit' => (int) ($result->sakit_count ?? 0),
        ];
    }

    protected function getKognitifSummary(?int $tahunAjaranId): array
    {
        $result = PerkembanganKognitif::query()
            ->where('siswa_id', $this->record->id)
            ->when($tahunAjaranId, fn (\Illuminate\Database\Eloquent\Builder $q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
            ->selectRaw("
                SUM(status_persetujuan = 'disetujui') as disetujui_count,
                SUM(status_persetujuan = 'menunggu') as menunggu_count,
                SUM(status_persetujuan = 'revisi') as revisi_count
            ")
            ->first();

        return [
            'disetujui' => (int) ($result->disetujui_count ?? 0),
            'menunggu' => (int) ($result->menunggu_count ?? 0),
            'revisi' => (int) ($result->revisi_count ?? 0),
        ];
    }

    protected function getLatestFisik(string $startDate, string $endDate): array
    {
        $latest = PerkembanganFisik::query()
            ->where('siswa_id', $this->record->id)
            ->whereBetween('tanggal_ukur', [$startDate, $endDate])
            ->latest('tanggal_ukur')
            ->first();

        $statusRaw = $latest?->status_ringkas;
        $statusLabel = match ($statusRaw) {
            'normal' => 'Normal',
            'perlu_perhatian' => 'Perlu Perhatian',
            default => $statusRaw,
        };
        $statusColor = match ($statusRaw) {
            'normal' => 'success',
            'perlu_perhatian' => 'warning',
            default => 'gray',
        };

        return [
            'status' => $statusLabel ?? '-',
            'status_raw' => $statusRaw,
            'status_color' => $statusColor,
            'tanggal' => $latest?->tanggal_ukur?->format('d M Y') ?? '-',
        ];
    }

    protected function getFisikRecommendation(?string $statusRaw): string
    {
        if (! $statusRaw) {
            return 'Belum ada data perkembangan fisik untuk ditampilkan.';
        }

        if ($statusRaw === 'normal') {
            return 'Perkembangan fisik anak berada dalam kategori normal. Disarankan untuk melanjutkan pemantauan rutin serta menjaga pola makan dan aktivitas fisik yang seimbang.';
        }

        return 'Perkembangan fisik anak memerlukan perhatian lebih. Disarankan untuk melakukan pemantauan lanjutan dan berkonsultasi dengan tenaga kesehatan profesional, seperti dokter anak atau ahli gizi, untuk mendapatkan rekomendasi yang sesuai.';
    }

    protected function getFisikRange(): array
    {
        $latestDate = PerkembanganFisik::query()
            ->where('siswa_id', $this->record->id)
            ->latest('tanggal_ukur')
            ->value('tanggal_ukur');

        if (! $latestDate) {
            $today = Carbon::today();
            return [
                $today->copy()->startOfMonth()->toDateString(),
                $today->copy()->endOfMonth()->toDateString(),
            ];
        }

        $base = Carbon::parse($latestDate);

        return [
            $base->copy()->startOfMonth()->toDateString(),
            $base->copy()->endOfMonth()->toDateString(),
        ];
    }

    protected function getKognitifTahunAjaranId(): ?int
    {
        return $this->record->tahun_ajaran_id
            ?? PerkembanganKognitif::query()
                ->where('siswa_id', $this->record->id)
                ->latest('created_at')
                ->value('tahun_ajaran_id');
    }

    protected function getDefaultFilterDate(): ?string
    {
        $presensiDate = Presensi::query()
            ->where('siswa_id', $this->record->id)
            ->latest('tanggal')
            ->value('tanggal');

        return $presensiDate ? Carbon::parse($presensiDate)->toDateString() : null;
    }

    protected function getMonthlyOptions(): array
    {
        $start = Carbon::now()->startOfMonth()->subMonths(12);
        $end = Carbon::now()->startOfMonth()->addMonths(2);
        $options = [];

        for ($date = $start->copy(); $date->lte($end); $date->addMonth()) {
            $key = $date->format('Y-m');
            $options[$key] = $date->translatedFormat('M Y');
        }

        return $options;
    }

    protected function getWeeklyOptions(): array
    {
        $dates = Presensi::query()
            ->where('siswa_id', $this->record->id)
            ->orderByDesc('tanggal')
            ->get(['tanggal', 'tahun_ajaran_id']);

        $tahunMulaiMap = Presensi::query()
            ->selectRaw('tahun_ajaran_id, MIN(tanggal) as tahun_mulai')
            ->groupBy('tahun_ajaran_id')
            ->pluck('tahun_mulai', 'tahun_ajaran_id');

        $options = [];

        foreach ($dates as $date) {
            $tahunMulai = $tahunMulaiMap->get($date->tahun_ajaran_id);

            if (! $tahunMulai) {
                continue;
            }

            $startBase = Carbon::parse($tahunMulai);
            $current = Carbon::parse($date->tanggal);
            $diffDays = $startBase->diffInDays($current);
            $weekNumber = (int) floor($diffDays / 7) + 1;
            $start = $startBase->copy()->addDays(($weekNumber - 1) * 7);
            $end = $start->copy()->addDays(6);
            $key = $start->toDateString();

            if (! array_key_exists($key, $options)) {
                $options[$key] = "Minggu {$weekNumber} ({$start->format('d M')} - {$end->format('d M Y')})";
            }
        }

        return $options;
    }

    protected function getWeeklyPeriodStart(string $date): ?string
    {
        $record = Presensi::query()
            ->where('siswa_id', $this->record->id)
            ->whereDate('tanggal', $date)
            ->orderByDesc('tanggal')
            ->first(['tanggal', 'tahun_ajaran_id']);

        if (! $record) {
            return null;
        }

        $tahunMulai = Presensi::query()
            ->where('tahun_ajaran_id', $record->tahun_ajaran_id)
            ->min('tanggal');

        if (! $tahunMulai) {
            return null;
        }

        $startBase = Carbon::parse($tahunMulai);
        $current = Carbon::parse($record->tanggal);
        $diffDays = $startBase->diffInDays($current);
        $weekNumber = (int) floor($diffDays / 7) + 1;

        return $startBase->copy()->addDays(($weekNumber - 1) * 7)->toDateString();
    }

}
