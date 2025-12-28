<?php

namespace App\Filament\Wali\Pages;

use App\Models\Presensi as PresensiModel;
use App\Models\Wali;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Pages\Page;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use BackedEnum;
use UnitEnum;

class Presensi extends Page implements Forms\Contracts\HasForms, HasTable
{
    use Forms\Concerns\InteractsWithForms;
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Presensi';

    protected static ?string $title = 'Presensi Anak';

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

        $this->filters = [
            'mode' => 'semua',
            'tanggal_mingguan' => Carbon::parse($defaultDate)
                ->startOfWeek(Carbon::MONDAY)
                ->toDateString(),
            'bulan' => Carbon::parse($defaultDate)->format('Y-m'),
        ];

        $this->mountInteractsWithTable();
    }

    public function content(Schema $schema): Schema
    {
        if (! $this->getSelectedSiswa()) {
            return $schema->components([
                Section::make('Presensi')
                    ->schema([
                        Text::make('Belum ada data anak yang terhubung dengan akun wali ini.'),
                    ]),
            ]);
        }

        [$startDate, $endDate] = $this->getPresensiRange();
        $summary = $this->getPresensiSummary(null, null);

        return $schema->components([
            Section::make('Presensi')
                ->schema([
                    Form::make([
                        EmbeddedSchema::make('filtersForm'),
                    ]),
                    Grid::make(4)
                        ->schema([
                            Text::make('Hadir: ' . $summary['hadir'])
                                ->badge()
                                ->color('success'),
                            Text::make('Alfa: ' . $summary['alfa'])
                                ->badge()
                                ->color('danger'),
                            Text::make('Izin: ' . $summary['izin'])
                                ->badge()
                                ->color('warning'),
                            Text::make('Sakit: ' . $summary['sakit'])
                                ->badge()
                                ->color('info'),
                        ]),
                    EmbeddedTable::make(),
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

    public function table(Table $table): Table
    {
        return $table
            ->query(fn () => $this->getPresensiQuery())
            ->columns($this->getTableColumns())
            ->paginationMode(PaginationMode::Default)
            ->paginationPageOptions([10, 25, 50])
            ->extremePaginationLinks()
            ->defaultSort('tanggal', 'desc')
            ->searchPlaceholder('Cari tanggal / guru');
    }

    protected function getPresensiQuery(): Builder
    {
        [$startDate, $endDate] = $this->getPresensiRange();
        $allowedSiswaIds = $this->getAllowedSiswaIds();

        if (empty($allowedSiswaIds)) {
            return PresensiModel::query()->whereRaw('1 = 0');
        }

        return PresensiModel::query()
            ->with(['pertemuan.guru', 'guru'])
            ->whereIn('siswa_id', $allowedSiswaIds)
            ->when($startDate, fn (Builder $q) => $q->whereDate('tanggal', '>=', $startDate))
            ->when($endDate, fn (Builder $q) => $q->whereDate('tanggal', '<=', $endDate))
            ->orderByDesc('tanggal')
            ->orderByDesc('id');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('tanggal')
                ->label('Tanggal')
                ->date('d M Y')
                ->sortable(),

            TextColumn::make('pertemuan.pertemuan_ke')
                ->label('Pertemuan ke-')
                ->sortable(),

            TextColumn::make('guru.nama')
                ->label('Guru')
                ->getStateUsing(function ($record): string {
                    $guru = $record?->guru?->nama ?: $record?->pertemuan?->guru?->nama;
                    return $guru ?? '-';
                })
                ->sortable()
                ->searchable(),

            TextColumn::make('status_kehadiran')
                ->label('Status')
                ->badge()
                ->formatStateUsing(fn (?string $state) => match ($state) {
                    'hadir' => 'Hadir',
                    'alfa' => 'Alfa',
                    'izin' => 'Izin',
                    'sakit' => 'Sakit',
                    default => $state ?? '-',
                })
                ->colors([
                    'success' => 'hadir',
                    'danger' => 'alfa',
                    'warning' => 'izin',
                    'info' => 'sakit',
                ]),
        ];
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

    protected function getPresensiSummary(?string $startDate, ?string $endDate): array
    {
        $allowedSiswaIds = $this->getAllowedSiswaIds();

        if (empty($allowedSiswaIds)) {
            return [
                'hadir' => 0,
                'alfa' => 0,
                'izin' => 0,
                'sakit' => 0,
            ];
        }

        $result = PresensiModel::query()
            ->whereIn('siswa_id', $allowedSiswaIds)
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

    protected function getDefaultFilterDate(): ?string
    {
        $allowedSiswaIds = $this->getAllowedSiswaIds();

        if (empty($allowedSiswaIds)) {
            return null;
        }

        $presensiDate = PresensiModel::query()
            ->whereIn('siswa_id', $allowedSiswaIds)
            ->latest('tanggal')
            ->value('tanggal');

        return $presensiDate ? Carbon::parse($presensiDate)->toDateString() : null;
    }

    protected function getAllowedSiswaIds(): array
    {
        $wali = $this->getWali();

        return $wali?->siswas?->pluck('id')->all() ?? [];
    }

    protected function getSelectedSiswa()
    {
        return $this->getWali()?->siswas?->sortBy('nama')->first();
    }

    protected function getWali(): ?Wali
    {
        if ($this->wali) {
            return $this->wali;
        }

        $this->wali = Wali::with(['siswas.kelas.guru'])
            ->where('user_id', Auth::id())
            ->first();

        return $this->wali;
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

        $dates = PresensiModel::query()
            ->where('siswa_id', $siswa->id)
            ->orderByDesc('tanggal')
            ->pluck('tanggal');

        $options = [];

        foreach ($dates as $date) {
            $start = Carbon::parse($date)->startOfWeek(Carbon::MONDAY);
            $end = $start->copy()->endOfWeek(Carbon::SUNDAY);
            $key = $start->toDateString();

            if (! array_key_exists($key, $options)) {
                $weekNumber = $start->isoWeek();
                $options[$key] = "Minggu {$weekNumber} ({$start->format('d M')} - {$end->format('d M Y')})";
            }
        }

        return $options;
    }
}
