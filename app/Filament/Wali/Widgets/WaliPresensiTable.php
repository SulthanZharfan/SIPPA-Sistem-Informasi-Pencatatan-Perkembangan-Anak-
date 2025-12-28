<?php

namespace App\Filament\Wali\Widgets;

use App\Models\Presensi;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class WaliPresensiTable extends TableWidget
{
    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    public ?int $siswaId = null;
    public array $allowedSiswaIds = [];
    public ?string $startDate = null;
    public ?string $endDate = null;

    public function table(Table $table): Table
    {
        return $table
            ->paginationMode(PaginationMode::Default)
            ->paginationPageOptions([10, 25, 50])
            ->extremePaginationLinks();
    }

    protected function getTableQuery(): Builder
    {
        if (! $this->siswaId || empty($this->allowedSiswaIds)) {
            return Presensi::query()->whereRaw('1 = 0');
        }

        return Presensi::query()
            ->with(['siswa', 'pertemuan.kelas', 'pertemuan.guru'])
            ->where('siswa_id', $this->siswaId)
            ->whereIn('siswa_id', $this->allowedSiswaIds)
            ->when($this->startDate, fn (Builder $q) => $q->whereDate('tanggal', '>=', $this->startDate))
            ->when($this->endDate, fn (Builder $q) => $q->whereDate('tanggal', '<=', $this->endDate))
            ->orderByDesc('tanggal')
            ->orderByDesc('id');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('siswa.nama')
                ->label('Siswa')
                ->sortable(),

            TextColumn::make('tanggal')
                ->label('Tanggal')
                ->date('d M Y')
                ->sortable(),

            TextColumn::make('pertemuan.pertemuan_ke')
                ->label('Pertemuan ke-')
                ->sortable(),

            TextColumn::make('pertemuan.guru.nama')
                ->label('Guru')
                ->toggleable(),

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

    protected function getTableActions(): array
    {
        return [];
    }

    protected function getTableHeading(): ?string
    {
        return 'Detail Presensi';
    }
}
