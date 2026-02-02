<?php

namespace App\Filament\Kepsek\Widgets;

use App\Filament\Kepsek\Pages\RekapPresensiDetail;
use App\Models\Kelas;
use App\Models\PertemuanPresensi;
use App\Models\Presensi;
use App\Models\Siswa;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class KepsekRekapPresensiTable extends TableWidget
{
    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    public string $kelasTingkat = 'K1';
    public string $activeTab = 'harian';

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

    protected function getTableHeading(): ?string
    {
        return 'Kelas ' . $this->kelasTingkat;
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
            ->addSelect([
                'total_siswa' => Siswa::query()
                    ->selectRaw('count(*)')
                    ->whereColumn('siswas.kelas_id', 'pertemuan_presensis.kelas_id'),
            ])
            ->with(['kelas', 'guru', 'tahunAjaran'])
            ->withCount([
                'presensis as hadir_count' => fn ($q) => $q->where('status_kehadiran', 'hadir'),
                'presensis as alfa_count' => fn ($q) => $q->where('status_kehadiran', 'alfa'),
                'presensis as izin_count' => fn ($q) => $q->where('status_kehadiran', 'izin'),
                'presensis as sakit_count' => fn ($q) => $q->where('status_kehadiran', 'sakit'),
            ])
            ->whereIn('kelas_id', $this->getKelasIds())
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
                SUM(presensis.status_kehadiran = 'hadir') as hadir_count,
                SUM(presensis.status_kehadiran = 'alfa') as alfa_count,
                SUM(presensis.status_kehadiran = 'izin') as izin_count,
                SUM(presensis.status_kehadiran = 'sakit') as sakit_count
            ")
            ->addSelect([
                'total_siswa' => Siswa::query()
                    ->selectRaw('count(*)')
                    ->whereColumn('siswas.kelas_id', 'presensis.kelas_id'),
            ])
            ->with(['kelas', 'guru', 'tahunAjaran'])
            ->whereIn('presensis.kelas_id', $this->getKelasIds())
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
                SUM(status_kehadiran = 'hadir') as hadir_count,
                SUM(status_kehadiran = 'alfa') as alfa_count,
                SUM(status_kehadiran = 'izin') as izin_count,
                SUM(status_kehadiran = 'sakit') as sakit_count
            ")
            ->addSelect([
                'total_siswa' => Siswa::query()
                    ->selectRaw('count(*)')
                    ->whereColumn('siswas.kelas_id', 'presensis.kelas_id'),
            ])
            ->with(['kelas', 'guru', 'tahunAjaran'])
            ->whereIn('kelas_id', $this->getKelasIds())
            ->groupBy('kelas_id', 'guru_id', 'tahun_ajaran_id', 'periode_key')
            ->orderByDesc('periode_mulai');
    }

    protected function getRekapColumns(): array
    {
        return [
            TextColumn::make('periode_label')
                ->label('Periode')
                ->toggleable()
                ->getStateUsing(fn ($record) => $this->formatPeriodeLabel($record)),

            TextColumn::make('pertemuan_ke')
                ->label('Pertemuan ke-')
                ->toggleable()
                ->visible(fn () => $this->activeTab === 'harian'),

            TextColumn::make('kelas.nama')
                ->label('Kelas')
                ->badge()
                ->color('success')
                ->toggleable()
                ->sortable(),

            TextColumn::make('guru.nama')
                ->label('Guru')
                ->toggleable()
                ->toggleable(),

            TextColumn::make('total_siswa')
                ->label('Total Siswa')
                ->toggleable()
                ->sortable(),

            TextColumn::make('hadir_count')
                ->label('Hadir')
                ->toggleable()
                ->sortable(),

            TextColumn::make('alfa_count')
                ->label('Alfa')
                ->toggleable()
                ->sortable(),

            TextColumn::make('izin_count')
                ->label('Izin')
                ->toggleable()
                ->sortable(),

            TextColumn::make('sakit_count')
                ->label('Sakit')
                ->toggleable()
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
            return "Minggu {$minggu} ({$mulaiDate->format('d M')} - {$sampaiDate->format('d M Y')})";
        }

        return $mulaiDate->format('M Y');
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

    protected function getKelasIds(): array
    {
        return Kelas::query()
            ->where('tingkat', $this->kelasTingkat)
            ->orWhere('nama', $this->kelasTingkat)
            ->pluck('id')
            ->all();
    }
}
