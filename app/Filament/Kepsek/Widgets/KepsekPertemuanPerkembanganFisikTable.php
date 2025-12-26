<?php

namespace App\Filament\Kepsek\Widgets;

use App\Filament\Kepsek\Resources\PertemuanPerkembanganFisiks\PertemuanPerkembanganFisikResource;
use App\Models\Kelas;
use App\Models\PertemuanPerkembanganFisik;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class KepsekPertemuanPerkembanganFisikTable extends TableWidget
{
    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    public string $kelasTingkat = 'K1';

    public function table(Table $table): Table
    {
        return PertemuanPerkembanganFisikResource::table($table)
            ->query(fn () => $this->getPertemuanQuery());
    }

    protected function getTableHeading(): ?string
    {
        return 'Kelas ' . $this->kelasTingkat;
    }

    protected function getPertemuanQuery(): Builder
    {
        $kelasIds = Kelas::query()
            ->where('tingkat', $this->kelasTingkat)
            ->orWhere('nama', $this->kelasTingkat)
            ->pluck('id');

        return PertemuanPerkembanganFisikResource::getEloquentQuery()
            ->whereIn('kelas_id', $kelasIds);
    }
}
