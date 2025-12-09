<?php

namespace App\Filament\Resources\Admin\Widgets;

use App\Models\Kelas;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class AdminKelasTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;

    protected int $defaultTableRecordsPerPage = 6;

    protected function getTableQuery(): Builder
    {
        return Kelas::query()
            ->with(['guru'])
            ->withCount('siswas')
            ->latest('created_at')
            ->latest('id');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('nama')
                ->label('Nama Kelas')
                ->badge()
                ->color('info')
                ->sortable()
                ->searchable(),

            TextColumn::make('tingkat')
                ->label('Tingkat')
                ->badge()
                ->color('gray')
                ->sortable()
                ->searchable(),

            TextColumn::make('guru.nama')
                ->label('Wali Kelas')
                ->badge()
                ->color('success')
                ->placeholder('Belum ada')
                ->sortable()
                ->searchable(),

            TextColumn::make('siswas_count')
                ->label('Jumlah Siswa')
                ->badge()
                ->color('primary')
                ->sortable(),
        ];
    }

    protected function getTableHeading(): ?string
    {
        return 'Data Kelas';
    }
}
