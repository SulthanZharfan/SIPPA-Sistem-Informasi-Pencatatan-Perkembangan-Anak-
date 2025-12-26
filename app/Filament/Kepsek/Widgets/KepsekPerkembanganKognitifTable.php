<?php

namespace App\Filament\Kepsek\Widgets;

use App\Filament\Kepsek\Resources\PerkembanganKognitifs\PerkembanganKognitifResource;
use App\Models\Kelas;
use App\Models\PerkembanganKognitif;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class KepsekPerkembanganKognitifTable extends TableWidget
{
    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    public string $kelasTingkat = 'K1';

    public function table(Table $table): Table
    {
        return PerkembanganKognitifResource::table($table)
            ->query(fn () => $this->getKognitifQuery());
    }

    protected function getTableHeading(): ?string
    {
        return 'Kelas ' . $this->kelasTingkat;
    }

    protected function getKognitifQuery(): Builder
    {
        $kelasIds = Kelas::query()
            ->where('tingkat', $this->kelasTingkat)
            ->orWhere('nama', $this->kelasTingkat)
            ->pluck('id');

        return PerkembanganKognitifResource::getEloquentQuery()
            ->whereHas('siswa', fn (Builder $q) => $q->whereIn('kelas_id', $kelasIds));
    }
}
