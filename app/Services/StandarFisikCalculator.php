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

        // Kategori TB (pendek/normal/tinggi)
        $kategoriTb = $this->kategoriTigaLevel(
            nilai: $pf->tinggi_badan,
            normalMin: $standar->tb_min,
            normalMax: $standar->tb_max,
            rendah: 'pendek',
            tinggi: 'tinggi',
        );

        // Kategori BB (kurang/normal/lebih)
        $kategoriBb = $this->kategoriTigaLevel(
            nilai: $pf->berat_badan,
            normalMin: $standar->bb_min,
            normalMax: $standar->bb_max,
            rendah: 'kurang',
            tinggi: 'lebih',
        );

        // Kategori LK (kecil/normal/besar) — tetap boleh null jika standar tidak ada
        if (is_null($pf->lingkar_kepala) || is_null($standar->lk_min) || is_null($standar->lk_max)) {
            $kategoriLk = null;
        } else {
            $kategoriLk = $this->kategoriTigaLevel(
                nilai: $pf->lingkar_kepala,
                normalMin: $standar->lk_min,
                normalMax: $standar->lk_max,
                rendah: 'kecil',
                tinggi: 'besar',
            );
        }

        // Status ringkas: normal hanya jika semua kategori normal (LK boleh null)
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

        // Kategori TB (pendek/normal/tinggi)
        $kategoriTb = $this->kategoriTigaLevel(
            nilai: $tinggiBadan,
            normalMin: $standar->tb_min,
            normalMax: $standar->tb_max,
            rendah: 'pendek',
            tinggi: 'tinggi',
        );

        // Kategori BB (kurang/normal/lebih)
        $kategoriBb = $this->kategoriTigaLevel(
            nilai: $beratBadan,
            normalMin: $standar->bb_min,
            normalMax: $standar->bb_max,
            rendah: 'kurang',
            tinggi: 'lebih',
        );

        // Kategori LK (kecil/normal/besar) — tetap boleh null jika standar tidak ada
        if ($lingkarKepala === null || is_null($standar->lk_min) || is_null($standar->lk_max)) {
            $kategoriLk = null;
        } else {
            $kategoriLk = $this->kategoriTigaLevel(
                nilai: $lingkarKepala,
                normalMin: $standar->lk_min,
                normalMax: $standar->lk_max,
                rendah: 'kecil',
                tinggi: 'besar',
            );
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

    /**
     * Helper: hitung kategori tiga level berdasarkan batas normal bawah/atas.
     */
    protected function kategoriTigaLevel(
        float | int $nilai,
        float | int $normalMin,
        float | int $normalMax,
        string $rendah,
        string $tinggi,
    ): string {
        if ($nilai < $normalMin) {
            return $rendah;
        }

        if ($nilai > $normalMax) {
            return $tinggi;
        }

        return 'normal';
    }
}
