<?php

namespace App\Filament\Wali\Widgets;

use App\Filament\Kepsek\Widgets\SiswaPerkembanganFisikChart;

class WaliPerkembanganFisikChart extends SiswaPerkembanganFisikChart
{
    public array $allowedSiswaIds = [];

    protected function getData(): array
    {
        if (! $this->siswaId || ! in_array($this->siswaId, $this->allowedSiswaIds, true)) {
            return [
                'labels' => [],
                'datasets' => [],
            ];
        }

        return parent::getData();
    }
}
