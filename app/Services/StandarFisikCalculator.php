<?php

namespace App\Services;

use App\Models\DataStandarFisik;
use App\Models\PerkembanganFisik;

class StandarFisikCalculator
{
    /**
     * Hitung standar_id, kategori, dan status ringkas untuk perkembangan fisik.
     */
    public function calculate(PerkembanganFisik $pf): array
    {
        $standarId = null;
        $kategoriTb = null;
        $kategoriBb = null;
        $kategoriLk = null;
        $statusRingkas = 'perlu_perhatian';

        $siswa = $pf->siswa;
        if (! $siswa) {
            return compact('standarId', 'kategoriTb', 'kategoriBb', 'kategoriLk', 'statusRingkas');
        }

        $umurStandar = StandarFisikUmurMapper::map((int) $pf->umur_bulan);

        $standar = DataStandarFisik::query()
            ->where('jenis_kelamin', $siswa->jenis_kelamin)
            ->where('umur_bulan', $umurStandar)
            ->first();

        if (! $standar) {
            return compact('standarId', 'kategoriTb', 'kategoriBb', 'kategoriLk', 'statusRingkas');
        }

        $standarId = $standar->id;

        // Kategori TB
        if ($pf->tinggi_badan < $standar->tb_min || $pf->tinggi_badan > $standar->tb_max) {
            $kategoriTb = 'tidak_normal';
        } else {
            $kategoriTb = 'normal';
        }

        // Kategori BB
        if ($pf->berat_badan < $standar->bb_min || $pf->berat_badan > $standar->bb_max) {
            $kategoriBb = 'tidak_normal';
        } else {
            $kategoriBb = 'normal';
        }

        // Kategori LK
        if (is_null($pf->lingkar_kepala) || is_null($standar->lk_min) || is_null($standar->lk_max)) {
            $kategoriLk = null;
        } elseif ($pf->lingkar_kepala < $standar->lk_min || $pf->lingkar_kepala > $standar->lk_max) {
            $kategoriLk = 'tidak_normal';
        } else {
            $kategoriLk = 'normal';
        }

        // Status ringkas
        $statusRingkas = ($kategoriTb === 'normal'
            && $kategoriBb === 'normal'
            && ($kategoriLk === 'normal' || $kategoriLk === null))
            ? 'normal'
            : 'perlu_perhatian';

        return [
            'standar_id' => $standarId,
            'kategori_tb' => $kategoriTb,
            'kategori_bb' => $kategoriBb,
            'kategori_lk' => $kategoriLk,
            'status_ringkas' => $statusRingkas,
        ];
    }
}
