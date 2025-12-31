<?php

namespace App\Filament\Resources\Siswas\Widgets;

use App\Filament\Resources\Siswas\Tables\SiswasTable;
use App\Filament\Resources\Siswas\SiswaResource;
use App\Models\Siswa;
use Filament\Actions\EditAction;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class SiswaK1Table extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;

    public function table(Table $table): Table
    {
        return SiswasTable::configure($table)
            ->paginationMode(PaginationMode::Default)
            ->paginationPageOptions([10, 25, 50])
            ->extremePaginationLinks()
            ->recordActions([
                EditAction::make()
                    ->url(fn ($record) => SiswaResource::getUrl('edit', ['record' => $record])),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        return Siswa::query()
            ->with(['kelas', 'tahunAjaran', 'wali'])
            ->whereHas('kelas', function (Builder $query) {
                $query->where('tingkat', 'K1')->orWhere('nama', 'K1');
            });
    }

    protected function getTableHeading(): ?string
    {
        return 'Siswa K1';
    }
}
