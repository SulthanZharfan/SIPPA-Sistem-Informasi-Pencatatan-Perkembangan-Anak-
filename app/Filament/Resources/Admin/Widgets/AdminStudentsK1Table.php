<?php

namespace App\Filament\Resources\Admin\Widgets;

use App\Models\Siswa;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class AdminStudentsK1Table extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;

    protected int $defaultTableRecordsPerPage = 6;

    public function table(Table $table): Table
    {
        return $table
            ->paginationMode(PaginationMode::Default)
            ->paginationPageOptions([6, 10, 25])
            ->extremePaginationLinks();
    }

    protected function getTableQuery(): Builder
    {
        return Siswa::query()
            ->with(['kelas', 'wali'])
            ->whereHas('kelas', function (Builder $query) {
                $query->where('tingkat', 'K1')->orWhere('nama', 'K1');
            })
            ->latest('created_at')
            ->latest('id');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('nama')
                ->label('Nama')
                ->searchable()
                ->sortable(),

            TextColumn::make('nis')
                ->label('NISN')
                ->badge()
                ->color('info')
                ->sortable(),

            TextColumn::make('kelas.nama')
                ->label('Kelas')
                ->badge()
                ->color('success')
                ->sortable(),

            TextColumn::make('wali.nama')
                ->label('Wali')
                ->badge()
                ->color('gray')
                ->formatStateUsing(fn ($state, $record) => $record->wali?->nama_tampil ?? $state)
                ->placeholder('Belum ada')
                ->sortable(),
        ];
    }

    protected function getTableHeading(): ?string
    {
        return 'Siswa K1';
    }
}
