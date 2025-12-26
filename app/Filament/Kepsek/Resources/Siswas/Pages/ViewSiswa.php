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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;

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

        $this->filters = [
            'mode' => 'harian',
            'tanggal' => $defaultDate,
            'tanggal_mingguan' => $defaultDate,
            'tanggal_bulanan' => $defaultDate,
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

        $presensiSummary = $this->getPresensiSummary($presensiStart, $presensiEnd);
        $kognitifSummary = $this->getKognitifSummary($kognitifTahunAjaranId);
        $fisikLatest = $this->getLatestFisik($fisikStart, $fisikEnd);

        return $schema->components([
            Section::make('Informasi Umum')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Text::make('Nama: ' . ($this->record->nama ?? '-')),
                            Text::make('NIS: ' . ($this->record->nis ?? '-')),
                            Text::make('Kelas: ' . ($this->record->kelas?->nama ?? '-')),
                            Text::make('Guru: ' . ($this->record->kelas?->guru?->nama ?? '-')),
                            Text::make('Wali Murid: ' . ($this->record->wali?->nama ?? '-')),
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
                    ])->key('presensi-' . $this->record->id . '-' . $presensiStart . '-' . $presensiEnd),
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
                                'harian' => 'Harian',
                                'mingguan' => 'Mingguan',
                                'bulanan' => 'Bulanan',
                            ])
                            ->grouped()
                            ->inline()
                            ->default('harian')
                            ->live(),

                        DatePicker::make('tanggal')
                            ->label('Tanggal')
                            ->visible(fn (Get $get) => $get('mode') === 'harian')
                            ->native(false)
                            ->live(),

                        DatePicker::make('tanggal_mingguan')
                            ->label('Tanggal')
                            ->visible(fn (Get $get) => $get('mode') === 'mingguan')
                            ->native(false)
                            ->live(),

                        DatePicker::make('tanggal_bulanan')
                            ->label('Bulan')
                            ->visible(fn (Get $get) => $get('mode') === 'bulanan')
                            ->native(false)
                            ->live(),
                    ]),
            ])
            ->statePath('filters');
    }

    protected function getPresensiRange(): array
    {
        $mode = $this->filters['mode'] ?? 'harian';

        $date = match ($mode) {
            'mingguan' => $this->filters['tanggal_mingguan'] ?? null,
            'bulanan' => $this->filters['tanggal_bulanan'] ?? null,
            default => $this->filters['tanggal'] ?? null,
        };

        $base = $date ? Carbon::parse($date) : Carbon::today();

        return match ($mode) {
            'mingguan' => [
                $base->copy()->startOfWeek(Carbon::MONDAY)->toDateString(),
                $base->copy()->endOfWeek(Carbon::SUNDAY)->toDateString(),
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

    protected function getPresensiSummary(string $startDate, string $endDate): array
    {
        $result = Presensi::query()
            ->where('siswa_id', $this->record->id)
            ->whereBetween('tanggal', [$startDate, $endDate])
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
            'status_color' => $statusColor,
            'tanggal' => $latest?->tanggal_ukur?->format('d M Y') ?? '-',
        ];
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

}
