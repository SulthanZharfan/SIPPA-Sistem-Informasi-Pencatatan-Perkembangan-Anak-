<?php

namespace App\Filament\Kepsek\Pages;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\PertemuanPresensi;
use App\Models\Presensi;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

class RekapPresensiDetail extends Page implements HasTable
{
    use InteractsWithTable;

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament-panels::pages.page';

    #[Url]
    public ?string $mode = null;

    #[Url]
    public ?int $pertemuan = null;

    #[Url]
    public ?int $kelas_id = null;

    #[Url]
    public ?int $guru_id = null;

    #[Url]
    public ?int $tahun_ajaran_id = null;

    #[Url]
    public ?string $mulai = null;

    #[Url]
    public ?string $sampai = null;

    public function mount(): void
    {
        $this->mountInteractsWithTable();
    }

    public function getTitle(): string
    {
        return 'Detail Rekap Presensi';
    }

    public function content(Schema $schema): Schema
    {
        $summary = $this->getSummaryCounts();

        return $schema->components([
            Section::make('Info')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            Text::make(fn () => 'Periode: ' . $this->getPeriodeLabel()),
                            Text::make(fn () => 'Kelas: ' . $this->getKelasLabel()),
                            Text::make(fn () => 'Guru: ' . $this->getGuruLabel()),
                            Text::make(fn () => 'Tahun Ajaran: ' . $this->getTahunAjaranLabel()),
                        ]),
                ]),

            Section::make('Ringkasan')
                ->schema([
                    Grid::make(4)
                        ->schema([
                            Text::make(fn () => 'Hadir: ' . $summary['hadir'])
                                ->badge()
                                ->color('success'),
                            Text::make(fn () => 'Alfa: ' . $summary['alfa'])
                                ->badge()
                                ->color('danger'),
                            Text::make(fn () => 'Izin: ' . $summary['izin'])
                                ->badge()
                                ->color('warning'),
                            Text::make(fn () => 'Sakit: ' . $summary['sakit'])
                                ->badge()
                                ->color('info'),
                        ]),
                ]),

            EmbeddedTable::make(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn () => $this->getDetailQuery())
            ->columns($this->getDetailColumns())
            ->actions($this->getDetailActions())
            ->searchPlaceholder('Cari siswa / tanggal');
    }

    protected function getDetailQuery(): Builder
    {
        if ($this->getMode() === 'harian') {
            return Presensi::query()
                ->with(['siswa'])
                ->where('pertemuan_presensi_id', $this->pertemuan);
        }

        return PertemuanPresensi::query()
            ->with(['kelas', 'guru'])
            ->withCount([
                'presensis as hadir_count' => fn ($q) => $q->where('status_kehadiran', 'hadir'),
                'presensis as alfa_count' => fn ($q) => $q->where('status_kehadiran', 'alfa'),
                'presensis as izin_count' => fn ($q) => $q->where('status_kehadiran', 'izin'),
                'presensis as sakit_count' => fn ($q) => $q->where('status_kehadiran', 'sakit'),
            ])
            ->when($this->kelas_id, fn (Builder $q) => $q->where('kelas_id', $this->kelas_id))
            ->when($this->guru_id, fn (Builder $q) => $q->where('guru_id', $this->guru_id))
            ->when($this->tahun_ajaran_id, fn (Builder $q) => $q->where('tahun_ajaran_id', $this->tahun_ajaran_id))
            ->when($this->mulai, fn (Builder $q) => $q->whereDate('tanggal', '>=', $this->mulai))
            ->when($this->sampai, fn (Builder $q) => $q->whereDate('tanggal', '<=', $this->sampai))
            ->orderByDesc('tanggal');
    }

    protected function getDetailColumns(): array
    {
        if ($this->getMode() === 'harian') {
            return [
                TextColumn::make('siswa.nama')
                    ->label('Siswa')
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

                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->toggleable(),
            ];
        }

        return [
            TextColumn::make('tanggal')
                ->label('Tanggal')
                ->date('d M Y')
                ->sortable(),

            TextColumn::make('pertemuan_ke')
                ->label('Pertemuan ke-')
                ->sortable(),

            TextColumn::make('guru.nama')
                ->label('Guru')
                ->toggleable(),

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

    protected function getDetailActions(): array
    {
        if ($this->getMode() === 'harian') {
            return [];
        }

        return [
            Action::make('detail')
                ->label('Detail')
                ->icon('heroicon-o-eye')
                ->url(fn (PertemuanPresensi $record) => self::getUrl([
                    'mode' => 'harian',
                    'pertemuan' => $record->id,
                ])),
        ];
    }

    protected function getMode(): string
    {
        if ($this->mode === 'mingguan' || $this->mode === 'bulanan') {
            return $this->mode;
        }

        return 'harian';
    }

    protected function getPeriodeLabel(): string
    {
        if ($this->getMode() === 'harian' && $this->pertemuan) {
            $pertemuan = $this->getPertemuan();
            return $pertemuan?->tanggal?->format('d M Y') ?? '-';
        }

        if ($this->mulai && $this->sampai) {
            $mulai = Carbon::parse($this->mulai)->format('d M Y');
            $sampai = Carbon::parse($this->sampai)->format('d M Y');
            return "{$mulai} - {$sampai}";
        }

        return '-';
    }

    protected function getKelasLabel(): string
    {
        if ($this->getMode() === 'harian' && $this->pertemuan) {
            return $this->getPertemuan()?->kelas?->nama ?? '-';
        }

        if (! $this->kelas_id) {
            return 'Semua';
        }

        return Kelas::find($this->kelas_id)?->nama ?? '-';
    }

    protected function getGuruLabel(): string
    {
        if ($this->getMode() === 'harian' && $this->pertemuan) {
            return $this->getPertemuan()?->guru?->nama ?? '-';
        }

        if (! $this->guru_id) {
            return 'Semua';
        }

        return Guru::find($this->guru_id)?->nama ?? '-';
    }

    protected function getTahunAjaranLabel(): string
    {
        if ($this->getMode() === 'harian' && $this->pertemuan) {
            return $this->getPertemuan()?->tahunAjaran?->label ?? '-';
        }

        if (! $this->tahun_ajaran_id) {
            return 'Semua';
        }

        return TahunAjaran::find($this->tahun_ajaran_id)?->label ?? '-';
    }

    protected function getPertemuan(): ?PertemuanPresensi
    {
        if (! $this->pertemuan) {
            return null;
        }

        return PertemuanPresensi::query()
            ->with(['kelas', 'guru', 'tahunAjaran'])
            ->find($this->pertemuan);
    }

    protected function getSummaryCounts(): array
    {
        $query = Presensi::query();
        $kelasId = $this->kelas_id;
        $guruId = $this->guru_id;
        $tahunAjaranId = $this->tahun_ajaran_id;

        if ($this->getMode() === 'harian' && $this->pertemuan) {
            $pertemuan = $this->getPertemuan();
            $kelasId = $kelasId ?? $pertemuan?->kelas_id;
            $guruId = $guruId ?? $pertemuan?->guru_id;
            $tahunAjaranId = $tahunAjaranId ?? $pertemuan?->tahun_ajaran_id;
        }

        $query
            ->when($kelasId, fn (Builder $q) => $q->where('kelas_id', $kelasId))
            ->when($guruId, fn (Builder $q) => $q->where('guru_id', $guruId))
            ->when($tahunAjaranId, fn (Builder $q) => $q->where('tahun_ajaran_id', $tahunAjaranId));

        $result = $query->selectRaw("
            SUM(status_kehadiran = 'hadir') as hadir_count,
            SUM(status_kehadiran = 'alfa') as alfa_count,
            SUM(status_kehadiran = 'izin') as izin_count,
            SUM(status_kehadiran = 'sakit') as sakit_count
        ")->first();

        return [
            'hadir' => (int) ($result->hadir_count ?? 0),
            'alfa' => (int) ($result->alfa_count ?? 0),
            'izin' => (int) ($result->izin_count ?? 0),
            'sakit' => (int) ($result->sakit_count ?? 0),
        ];
    }
}
