<?php

namespace App\Filament\Kepsek\Widgets;

use App\Filament\Kepsek\Resources\Siswas\SiswaResource;
use App\Models\Siswa;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class KepsekStudentsK1Table extends TableWidget
{
    protected static bool $isLazy = false;

    protected int $defaultTableRecordsPerPage = 10;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->paginated(true)
            ->paginationMode(PaginationMode::Default)
            ->paginationPageOptions([10, 25, 50]);
    }

    protected function getTableQuery(): Builder
    {
        return Siswa::query()
            ->with(['kelas'])
            ->whereHas('kelas', function (Builder $query) {
                $query->where('tingkat', 'K1')->orWhere('nama', 'K1');
            })
            ->orderBy('nama');
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
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('detail')
                ->label('Detail')
                ->url(fn (Siswa $record) => SiswaResource::getUrl('view', ['record' => $record])),
        ];
    }

    protected function getTableHeading(): ?string
    {
        return 'Kelas K1';
    }
}
