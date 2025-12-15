<?php

namespace App\Services;

class StandarFisikUmurMapper
{
    /**
        * Map umur anak (bulan) ke standar umur_bulan terdekat.
        *
        * Tie-break: jika selisih sama, pilih umur standar yang lebih besar.
        *
        * Contoh:
        * - map(37) => 36
        * - map(44) => 48
        * - map(58) => 60
        * - map(67) => 72
        * - map(80) => 84
        */
    public static function map(int $umurBulan): int
    {
        $standards = [36, 48, 60, 72, 84];

        $nearest = $standards[0];
        $nearestDiff = abs($umurBulan - $nearest);

        foreach ($standards as $standard) {
            $diff = abs($umurBulan - $standard);

            // Pilih lebih dekat; jika tie, pilih yang lebih besar (ke atas).
            if ($diff < $nearestDiff || ($diff === $nearestDiff && $standard > $nearest)) {
                $nearest = $standard;
                $nearestDiff = $diff;
            }
        }

        return $nearest;
    }
}
