<?php

namespace App\Observers;

use App\Models\PerkembanganFisik;
use App\Services\StandarFisikCalculator;

class PerkembanganFisikObserver
{
    public function saving(PerkembanganFisik $perkembanganFisik): void
    {
        $calculator = app(StandarFisikCalculator::class);
        $result = $calculator->calculate($perkembanganFisik);

        $perkembanganFisik->standar_id = $result['standar_id'];
        $perkembanganFisik->kategori_tb = $result['kategori_tb'];
        $perkembanganFisik->kategori_bb = $result['kategori_bb'];
        $perkembanganFisik->kategori_lk = $result['kategori_lk'];
        $perkembanganFisik->status_ringkas = $result['status_ringkas'];
    }
}
