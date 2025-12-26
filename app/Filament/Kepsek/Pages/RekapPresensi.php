<?php

namespace App\Filament\Kepsek\Pages;

use App\Filament\Kepsek\Pages\RekapPresensiDetail;
use App\Models\PertemuanPresensi;
use App\Models\Presensi;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Resources\Concerns\HasTabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use UnitEnum;
use BackedEnum;
use Filament\Schemas\Components\Livewire;
use App\Filament\Kepsek\Widgets\KepsekRekapPresensiTable;

class RekapPresensi extends Page implements HasTable
{
    use HasTabs;
    use InteractsWithTable;

    protected static ?string $navigationLabel = 'Rekap Presensi';
    protected static string|UnitEnum|null $navigationGroup = 'Monitoring';
    protected static string |BackedEnum| null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected string $view = 'filament-panels::pages.page';

    #[Url(as: 'tab')]
    public ?string $activeTab = null;

    #[Url(as: 'filters')]
    public ?array $tableFilters = null;

    #[Url(as: 'search')]
    public $tableSearch = '';

    #[Url(as: 'sort')]
    public ?string $tableSort = null;

    public function mount(): void
    {
        $this->loadDefaultActiveTab();
        $this->mountInteractsWithTable();
    }

    public function getTabs(): array
    {
        return [
            'harian' => Tab::make('Harian'),
            'mingguan' => Tab::make('Mingguan'),
            'bulanan' => Tab::make('Bulanan'),
        ];
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            $this->getTabsContentComponent(),
            Livewire::make(KepsekRekapPresensiTable::class, fn () => [
                'kelasTingkat' => 'K1',
                'activeTab' => $this->activeTab ?? 'harian',
            ])->key('rekap-k1-' . ($this->activeTab ?? 'harian')),
            Livewire::make(KepsekRekapPresensiTable::class, fn () => [
                'kelasTingkat' => 'K2',
                'activeTab' => $this->activeTab ?? 'harian',
            ])->key('rekap-k2-' . ($this->activeTab ?? 'harian')),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn () => $this->getRekapQuery())
            ->columns($this->getRekapColumns())
            ->filters($this->getRekapFilters())
            ->actions($this->getRekapActions())
            ->paginationMode(PaginationMode::Default)
            ->paginationPageOptions([10, 25, 50])
            ->extremePaginationLinks()
            ->defaultKeySort(fn () => $this->activeTab === 'harian')
            ->defaultSort(fn () => $this->activeTab === 'harian' ? 'tanggal' : 'periode_mulai', 'desc')
            ->searchPlaceholder('Cari kelas / guru / tanggal');
    }

    public function getTableRecordKey($record): string
    {
        if ($this->activeTab === 'harian') {
            return (string) $record->id;
        }

        $kelasId = $record->kelas_id ?? '0';
        $guruId = $record->guru_id ?? '0';
        $tahunAjaranId = $record->tahun_ajaran_id ?? '0';
        $periodeKey = $record->periode_key ?? '0';

        return implode(':', [$kelasId, $guruId, $tahunAjaranId, $periodeKey]);
    }

    protected function getRekapQuery(): Builder
    {
        return match ($this->activeTab) {
            'mingguan' => $this->getMingguanQuery(),
            'bulanan' => $this->getBulananQuery(),
            default => $this->getHarianQuery(),
        };
    }

    protected function getHarianQuery(): Builder
    {
        return PertemuanPresensi::query()
            ->with(['kelas', 'guru', 'tahunAjaran'])
            ->withCount([
                'presensis as total_siswa',
                'presensis as hadir_count' => fn ($q) => $q->where('status_kehadiran', 'hadir'),
                'presensis as alfa_count' => fn ($q) => $q->where('status_kehadiran', 'alfa'),
                'presensis as izin_count' => fn ($q) => $q->where('status_kehadiran', 'izin'),
                'presensis as sakit_count' => fn ($q) => $q->where('status_kehadiran', 'sakit'),
            ])
            ->orderByDesc('tanggal')
            ->orderByDesc('id');
    }

    protected function getMingguanQuery(): Builder
    {
        $startSubquery = Presensi::query()
            ->selectRaw('tahun_ajaran_id, MIN(tanggal) as tahun_mulai')
            ->groupBy('tahun_ajaran_id');

        return Presensi::query()
            ->joinSub($startSubquery, 'ta_start', function ($join) {
                $join->on('presensis.tahun_ajaran_id', '=', 'ta_start.tahun_ajaran_id');
            })
            ->selectRaw("
                presensis.kelas_id,
                presensis.guru_id,
                presensis.tahun_ajaran_id,
                FLOOR(DATEDIFF(presensis.tanggal, ta_start.tahun_mulai) / 7) + 1 as periode_key,
                MIN(presensis.tanggal) as periode_mulai,
                MAX(presensis.tanggal) as periode_selesai,
                COUNT(*) as total_siswa,
                SUM(presensis.status_kehadiran = 'hadir') as hadir_count,
                SUM(presensis.status_kehadiran = 'alfa') as alfa_count,
                SUM(presensis.status_kehadiran = 'izin') as izin_count,
                SUM(presensis.status_kehadiran = 'sakit') as sakit_count
            ")
            ->with(['kelas', 'guru', 'tahunAjaran'])
            ->groupBy('presensis.kelas_id', 'presensis.guru_id', 'presensis.tahun_ajaran_id', 'periode_key', 'ta_start.tahun_mulai')
            ->orderByDesc('periode_mulai');
    }

    protected function getBulananQuery(): Builder
    {
        return Presensi::query()
            ->selectRaw("
                kelas_id,
                guru_id,
                tahun_ajaran_id,
                DATE_FORMAT(tanggal, '%Y-%m') as periode_key,
                MIN(tanggal) as periode_mulai,
                MAX(tanggal) as periode_selesai,
                COUNT(*) as total_siswa,
                SUM(status_kehadiran = 'hadir') as hadir_count,
                SUM(status_kehadiran = 'alfa') as alfa_count,
                SUM(status_kehadiran = 'izin') as izin_count,
                SUM(status_kehadiran = 'sakit') as sakit_count
            ")
            ->with(['kelas', 'guru', 'tahunAjaran'])
            ->groupBy('kelas_id', 'guru_id', 'tahun_ajaran_id', 'periode_key')
            ->orderByDesc('periode_mulai');
    }

    protected function getRekapColumns(): array
    {
        return [
            TextColumn::make('periode_label')
                ->label('Periode')
                ->getStateUsing(fn ($record) => $this->formatPeriodeLabel($record)),

            TextColumn::make('pertemuan_ke')
                ->label('Pertemuan ke-')
                ->toggleable()
                ->visible(fn () => $this->activeTab === 'harian'),

            TextColumn::make('kelas.nama')
                ->label('Kelas')
                ->badge()
                ->color('success')
                ->sortable(),

            TextColumn::make('guru.nama')
                ->label('Guru')
                ->toggleable(),

            TextColumn::make('total_siswa')
                ->label('Total Siswa')
                ->sortable(),

            TextColumn::make('hadir_count')
                ->label('Hadir')
                ->sortable(),

            TextColumn::make('alfa_count')
                ->label('Alfa')
                ->sortable(),

            TextColumn::make('izin_count')
                ->label('Izin')
                ->sortable(),

            TextColumn::make('sakit_count')
                ->label('Sakit')
                ->sortable(),
        ];
    }

    protected function getRekapFilters(): array
    {
        return [
            SelectFilter::make('tahun_ajaran_id')
                ->label('Tahun Ajaran')
                ->relationship('tahunAjaran', 'tahun')
                ->getOptionLabelFromRecordUsing(fn ($record) => $record->label),

            SelectFilter::make('kelas_id')
                ->label('Kelas')
                ->relationship('kelas', 'nama'),

            SelectFilter::make('guru_id')
                ->label('Guru')
                ->relationship('guru', 'nama'),

            Filter::make('periode')
                ->label('Periode')
                ->form([
                    DatePicker::make('tanggal')
                        ->label('Tanggal')
                        ->visible(fn () => $this->activeTab === 'harian')
                        ->native(false),
                    DatePicker::make('minggu')
                        ->label('Minggu')
                        ->visible(fn () => $this->activeTab === 'mingguan')
                        ->native(false),
                    Select::make('bulan')
                        ->label('Bulan')
                        ->visible(fn () => $this->activeTab === 'bulanan')
                        ->options($this->getMonthlyOptions())
                        ->searchable(),
                ])
                ->query(function (Builder $query, array $data) {
                    $tanggalColumn = $this->activeTab === 'harian' ? 'tanggal' : 'presensis.tanggal';

                    if ($this->activeTab === 'harian') {
                        return $query->when(
                            $data['tanggal'] ?? null,
                            fn (Builder $q, string $date) => $q->whereDate($tanggalColumn, $date),
                        );
                    }

                    if ($this->activeTab === 'mingguan') {
                        return $query->when($data['minggu'] ?? null, function (Builder $q, string $date) use ($tanggalColumn) {
                            $base = Carbon::parse($date);
                            return $q->whereDate($tanggalColumn, '>=', $base->copy()->startOfWeek(Carbon::MONDAY)->toDateString())
                                ->whereDate($tanggalColumn, '<=', $base->copy()->endOfWeek(Carbon::SUNDAY)->toDateString());
                        });
                    }

                    return $query->when($data['bulan'] ?? null, function (Builder $q, string $month) use ($tanggalColumn) {
                        $base = Carbon::createFromFormat('Y-m', $month);
                        return $q->whereDate($tanggalColumn, '>=', $base->copy()->startOfMonth()->toDateString())
                            ->whereDate($tanggalColumn, '<=', $base->copy()->endOfMonth()->toDateString());
                    });
                }),
        ];
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

    protected function getRekapActions(): array
    {
        return [
            Action::make('detail')
                ->label('Detail')
                ->icon('heroicon-o-eye')
                ->url(fn ($record) => $this->getDetailUrl($record)),
        ];
    }

    protected function getDetailUrl($record): string
    {
        if ($this->activeTab === 'harian') {
            return RekapPresensiDetail::getUrl([
                'mode' => 'harian',
                'pertemuan' => $record->id,
            ]);
        }

        return RekapPresensiDetail::getUrl([
            'mode' => $this->activeTab,
            'kelas_id' => $record->kelas_id,
            'guru_id' => $record->guru_id,
            'tahun_ajaran_id' => $record->tahun_ajaran_id,
            'mulai' => $record->periode_mulai,
            'sampai' => $record->periode_selesai,
        ]);
    }

    protected function formatPeriodeLabel($record): string
    {
        if ($this->activeTab === 'harian') {
            $tanggal = $record->tanggal ?? null;
            return $tanggal ? Carbon::parse($tanggal)->format('d M Y') : '-';
        }

        $mulai = $record->periode_mulai ?? null;
        $sampai = $record->periode_selesai ?? null;
        if (! $mulai || ! $sampai) {
            return '-';
        }

        $mulaiDate = Carbon::parse($mulai);
        $sampaiDate = Carbon::parse($sampai);

        if ($this->activeTab === 'mingguan') {
            $minggu = (int) ($record->periode_key ?? 0);
            $tahun = $mulaiDate->isoWeekYear();
            return "Minggu {$minggu} ({$mulaiDate->format('d M')} - {$sampaiDate->format('d M Y')})";
        }

        return $mulaiDate->format('M Y');
    }
}
