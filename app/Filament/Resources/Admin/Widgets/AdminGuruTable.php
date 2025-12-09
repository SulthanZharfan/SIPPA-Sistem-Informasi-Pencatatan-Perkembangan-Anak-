<?php

namespace App\Filament\Resources\Admin\Widgets;

use App\Models\Guru;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class AdminGuruTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;

    protected int $defaultTableRecordsPerPage = 6;

    protected function getTableQuery(): Builder
    {
        return Guru::query()
            ->withCount('kelas')
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

            TextColumn::make('nip')
                ->label('NIP')
                ->badge()
                ->color('info')
                ->sortable(),

            TextColumn::make('telepon')
                ->label('Telepon')
                ->placeholder('-')
                ->sortable(),

            TextColumn::make('kelas_count')
                ->label('Jumlah Kelas')
                ->badge()
                ->color('success')
                ->sortable(),
        ];
    }

    protected function getTableHeading(): ?string
    {
        return 'Data Guru';
    }
}
