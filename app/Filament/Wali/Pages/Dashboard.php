<?php

namespace App\Filament\Wali\Pages;

use App\Filament\Wali\Widgets\WaliPerkembanganFisikChart;
use App\Filament\Wali\Widgets\WaliPerkembanganKognitifTable;
use App\Filament\Wali\Widgets\WaliPresensiTable;
use App\Filament\Wali\Widgets\WaliStatsOverview;
use App\Models\PerkembanganFisik;
use App\Models\Presensi;
use App\Models\Wali;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Pages\Page;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use BackedEnum;
use UnitEnum;

class Dashboard extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Dashboard Wali';

    protected static string|UnitEnum|null $navigationGroup = null;

    protected string $view = 'filament-panels::pages.page';

    public ?array $filters = [];

    protected ?Wali $wali = null;

    public function mount(): void
    {
        $this->wali = Wali::with(['siswas.kelas.guru'])
            ->where('user_id', Auth::id())
            ->first();

        if (! $this->wali) {
            abort(403);
        }

        $defaultDate = $this->getDefaultFilterDate() ?? now()->toDateString();
        $defaultWeeklyStart = $this->getWeeklyPeriodStart($defaultDate);

        $this->filters = [
            'mode' => 'semua',
            'tanggal_mingguan' => $defaultWeeklyStart ?? $defaultDate,
            'bulan' => Carbon::parse($defaultDate)->format('Y-m'),
        ];
    }

    public function content(Schema $schema): Schema
    {
        $siswa = $this->getSelectedSiswa();
        if (! $siswa) {
            return $schema->components([
                Livewire::make(WaliStatsOverview::class)->columnSpanFull(),
                Section::make('Informasi Umum Anak')
                    ->schema([
                        Text::make('Belum ada data anak yang terhubung dengan akun wali ini.'),
                    ]),
            ]);
        }

        [$presensiStart, $presensiEnd] = $this->getPresensiRange();
        $presensiSummary = $this->getPresensiSummary($siswa->id, null, null);
        $fisikLatest = $this->getLatestFisik($siswa->id);
        $fisikRekomendasiText = $this->getFisikRecommendation($fisikLatest['status_raw'] ?? null);

        return $schema->components([
            Livewire::make(WaliStatsOverview::class)->columnSpanFull(),
            Section::make('Informasi Umum Anak')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Html::make(new HtmlString('<div class="text-sm text-gray-900 dark:text-gray-100">Nama: ' . e($siswa->nama ?? '-') . '</div>')),
                            Html::make(new HtmlString('<div class="text-sm text-gray-900 dark:text-gray-100">NISN: ' . e($siswa->nis ?? '-') . '</div>')),
                            Html::make(new HtmlString('<div class="text-sm text-gray-900 dark:text-gray-100">Kelas: ' . e($siswa->kelas?->nama ?? '-') . '</div>')),
                            Html::make(new HtmlString('<div class="text-sm text-gray-900 dark:text-gray-100">Guru Kelas: ' . e($siswa->kelas?->guru?->nama ?? '-') . '</div>')),
                            Html::make(new HtmlString('<div class="text-sm text-gray-900 dark:text-gray-100">Wali Murid: ' . e($siswa->wali?->nama_tampil ?? '-') . '</div>')),
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
                    Livewire::make(WaliPresensiTable::class, fn () => [
                        'siswaId' => $siswa->id,
                        'allowedSiswaIds' => $this->wali?->siswas?->pluck('id')->all() ?? [],
                        'startDate' => $presensiStart,
                        'endDate' => $presensiEnd,
                    ])->key('wali-presensi-' . $siswa->id . '-' . ($presensiStart ?? 'all') . '-' . ($presensiEnd ?? 'all')),
                ]),

            Section::make('Grafik Perkembangan Fisik')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Text::make('Status Terbaru: ' . ($fisikLatest['status'] ?? '-'))
                                ->badge()
                                ->color($fisikLatest['status_color'] ?? 'gray'),
                            Text::make('Tanggal Terbaru: ' . ($fisikLatest['tanggal'] ?? '-')),
                        ]),
                    Livewire::make(WaliPerkembanganFisikChart::class, fn () => [
                        'siswaId' => $siswa->id,
                        'allowedSiswaIds' => $this->wali?->siswas?->pluck('id')->all() ?? [],
                    ])->key('wali-fisik-chart-' . $siswa->id)->columnSpanFull(),
                ]),

            Section::make('Usulan / Rekomendasi')
                ->schema([
                    Html::make(new HtmlString(
                        '<div class="text-base leading-relaxed text-gray-900 dark:text-gray-100">' .
                            e($fisikRekomendasiText) .
                        '</div>' .
                        '<div class="mt-2 text-sm leading-relaxed text-gray-600 dark:text-gray-100">' .
                            'Catatan: Rekomendasi ini merupakan hasil pengolahan sistem dan digunakan sebagai bahan pertimbangan pendukung.' .
                        '</div>'
                    )),
                ])
                ->columnSpanFull(),

            Section::make('Ringkasan Perkembangan Kognitif')
                ->schema([
                    Livewire::make(WaliPerkembanganKognitifTable::class, fn () => [
                        'siswaId' => $siswa->id,
                        'allowedSiswaIds' => $this->wali?->siswas?->pluck('id')->all() ?? [],
                    ])->key('wali-kognitif-' . $siswa->id),
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

    protected function getPresensiSummary(int $siswaId, ?string $startDate, ?string $endDate): array
    {
        $result = Presensi::query()
            ->where('siswa_id', $siswaId)
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

    protected function getLatestFisik(int $siswaId): array
    {
        $latest = PerkembanganFisik::query()
            ->where('siswa_id', $siswaId)
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

        return 'Perkembangan fisik anak memerlukan perhatian. Disarankan untuk berkonsultasi dengan tenaga kesehatan seperti dokter anak atau ahli gizi.';
    }

    protected function getDefaultFilterDate(): ?string
    {
        $siswa = $this->getSelectedSiswa();

        if (! $siswa) {
            return null;
        }

        $presensiDate = Presensi::query()
            ->where('siswa_id', $siswa->id)
            ->latest('tanggal')
            ->value('tanggal');

        return $presensiDate ? Carbon::parse($presensiDate)->toDateString() : null;
    }

    protected function getSelectedSiswa()
    {
        if (! $this->wali) {
            $this->wali = Wali::with(['siswas.kelas.guru'])
                ->where('user_id', Auth::id())
                ->first();
        }

        return $this->wali?->siswas?->sortBy('nama')->first();
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
        $siswa = $this->getSelectedSiswa();

        if (! $siswa) {
            return [];
        }

        $dates = Presensi::query()
            ->where('siswa_id', $siswa->id)
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
        $siswa = $this->getSelectedSiswa();

        if (! $siswa) {
            return null;
        }

        $record = Presensi::query()
            ->where('siswa_id', $siswa->id)
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
