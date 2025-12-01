<?php

namespace Database\Seeders;

use App\Models\DataStandarFisik;
use Illuminate\Database\Seeder;

class DataStandarFisikSeeder extends Seeder
{
    public function run(): void
    {
        // Contoh data standar (dummy, bisa kamu ganti nanti sesuai tabel WHO)

        $data = [
            // Laki-laki 36 bulan (3 tahun)
            [
                'jenis_kelamin' => 'L',
                'umur_bulan'    => 36,
                'tb_min'        => 92.0,
                'tb_max'        => 103.0,
                'bb_min'        => 12.0,
                'bb_max'        => 16.0,
                'lk_min'        => 48.0,
                'lk_max'        => 52.0,
            ],
            // Perempuan 36 bulan
            [
                'jenis_kelamin' => 'P',
                'umur_bulan'    => 36,
                'tb_min'        => 91.0,
                'tb_max'        => 102.0,
                'bb_min'        => 11.5,
                'bb_max'        => 15.5,
                'lk_min'        => 47.5,
                'lk_max'        => 51.5,
            ],
            // Laki-laki 48 bulan
            [
                'jenis_kelamin' => 'L',
                'umur_bulan'    => 48,
                'tb_min'        => 99.0,
                'tb_max'        => 110.0,
                'bb_min'        => 13.0,
                'bb_max'        => 18.0,
                'lk_min'        => 49.0,
                'lk_max'        => 53.0,
            ],
            // Perempuan 48 bulan
            [
                'jenis_kelamin' => 'P',
                'umur_bulan'    => 48,
                'tb_min'        => 98.0,
                'tb_max'        => 109.0,
                'bb_min'        => 12.5,
                'bb_max'        => 17.5,
                'lk_min'        => 48.5,
                'lk_max'        => 52.5,
            ],
        ];

        foreach ($data as $row) {
            DataStandarFisik::firstOrCreate([
                'jenis_kelamin' => $row['jenis_kelamin'],
                'umur_bulan'    => $row['umur_bulan'],
            ], $row);
        }
    }
}
