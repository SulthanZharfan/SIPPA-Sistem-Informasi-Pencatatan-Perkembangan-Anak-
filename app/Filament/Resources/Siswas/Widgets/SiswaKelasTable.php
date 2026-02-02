<?php

namespace App\Filament\Resources\Siswas\Widgets;

use App\Filament\Resources\Siswas\SiswaResource;
use App\Filament\Resources\Siswas\Tables\SiswasTable;
use App\Models\Siswa;
use Filament\Actions\EditAction;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class SiswaKelasTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;

    public ?int $kelasId = null;

    public ?string $kelasNama = null;

    public ?string $kelasTingkat = null;

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
            ->when($this->kelasId, fn (Builder $query) => $query->where('kelas_id', $this->kelasId));
    }

    protected function getTableHeading(): ?string
    {
        $parts = array_filter([$this->kelasNama, $this->kelasTingkat]);
        $label = $parts ? implode(' - ', $parts) : 'Kelas';

        return 'Siswa ' . $label;
    }
}
