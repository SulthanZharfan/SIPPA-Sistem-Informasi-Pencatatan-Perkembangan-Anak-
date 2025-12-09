<?php

namespace App\Filament\Resources\Admin\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class AdminKepsekTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;

    protected int $defaultTableRecordsPerPage = 6;

    protected function getTableQuery(): Builder
    {
        return User::role('kepsek')
            ->latest('created_at')
            ->latest('id');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->label('Nama')
                ->searchable()
                ->sortable(),

            TextColumn::make('email')
                ->label('Email')
                ->icon('heroicon-o-envelope')
                ->sortable(),

            TextColumn::make('created_at')
                ->label('Dibuat')
                ->dateTime('d M Y')
                ->sortable(),
        ];
    }

    protected function getTableHeading(): ?string
    {
        return 'Data Kepsek';
    }
}
