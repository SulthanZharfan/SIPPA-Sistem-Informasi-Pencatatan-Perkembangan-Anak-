<?php

namespace App\Services;

use App\Models\DataStandarFisik;
use App\Models\PerkembanganFisik;

class StandarFisikCalculator
{
    /**
     * Preview kalkulasi tanpa model (dipakai live preview di form).
     *
     * @param  array{jenis_kelamin:string, umur_bulan:int, tinggi_badan:float|int|null, berat_badan:float|int|null, lingkar_kepala:float|int|null}  $data
     */
    public function preview(array $data): array
    {
        return $this->evaluate(
            jenisKelamin: $data['jenis_kelamin'] ?? null,
            umurBulan: $data['umur_bulan'] ?? null,
            tinggiBadan: $data['tinggi_badan'] ?? null,
            beratBadan: $data['berat_badan'] ?? null,
            lingkarKepala: $data['lingkar_kepala'] ?? null,
        );
    }

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

    /**
     * Evaluasi berdasar data terukur.
     */
    protected function evaluate(
        ?string $jenisKelamin,
        ?int $umurBulan,
        $tinggiBadan,
        $beratBadan,
        $lingkarKepala,
    ): array {
        $standarId = null;
        $kategoriTb = null;
        $kategoriBb = null;
        $kategoriLk = null;
        $statusRingkas = null;

        if (! $jenisKelamin || $umurBulan === null || $tinggiBadan === null || $beratBadan === null) {
            return compact('standarId', 'kategoriTb', 'kategoriBb', 'kategoriLk', 'statusRingkas');
        }

        $umurStandar = StandarFisikUmurMapper::map((int) $umurBulan);

        $standar = DataStandarFisik::query()
            ->where('jenis_kelamin', $jenisKelamin)
            ->where('umur_bulan', $umurStandar)
            ->first();

        if (! $standar) {
            return compact('standarId', 'kategoriTb', 'kategoriBb', 'kategoriLk', 'statusRingkas');
        }

        $standarId = $standar->id;

        // Kategori TB
        if ($tinggiBadan < $standar->tb_min || $tinggiBadan > $standar->tb_max) {
            $kategoriTb = 'tidak_normal';
        } else {
            $kategoriTb = 'normal';
        }

        // Kategori BB
        if ($beratBadan < $standar->bb_min || $beratBadan > $standar->bb_max) {
            $kategoriBb = 'tidak_normal';
        } else {
            $kategoriBb = 'normal';
        }

        // Kategori LK
        if ($lingkarKepala === null || is_null($standar->lk_min) || is_null($standar->lk_max)) {
            $kategoriLk = null;
        } elseif ($lingkarKepala < $standar->lk_min || $lingkarKepala > $standar->lk_max) {
            $kategoriLk = 'tidak_normal';
        } else {
            $kategoriLk = 'normal';
        }

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
