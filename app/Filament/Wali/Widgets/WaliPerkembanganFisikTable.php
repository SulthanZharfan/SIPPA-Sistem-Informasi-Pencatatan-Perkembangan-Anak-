<?php

namespace App\Filament\Wali\Widgets;

use App\Filament\Kepsek\Widgets\SiswaPerkembanganFisikTable;
use App\Models\PerkembanganFisik;
use Illuminate\Database\Eloquent\Builder;

class WaliPerkembanganFisikTable extends SiswaPerkembanganFisikTable
{
    public array $allowedSiswaIds = [];

    protected function getTableQuery(): Builder
    {
        if (! $this->siswaId || ! in_array($this->siswaId, $this->allowedSiswaIds, true)) {
            return PerkembanganFisik::query()->whereRaw('1 = 0');
        }

        return parent::getTableQuery();
    }
}
