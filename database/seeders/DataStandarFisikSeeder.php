<?php

namespace Database\Seeders;

use App\Models\DataStandarFisik;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DataStandarFisikSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            ['id' => 1, 'jenis_kelamin' => 'L', 'umur_bulan' => 36, 'tb_min' => 89.0,  'tb_max' => 103.5, 'bb_min' => 11.5, 'bb_max' => 18.5, 'lk_min' => 46.5, 'lk_max' => 52.5],
            ['id' => 2, 'jenis_kelamin' => 'P', 'umur_bulan' => 36, 'tb_min' => 87.5,  'tb_max' => 103.0, 'bb_min' => 11.0, 'bb_max' => 18.0, 'lk_min' => 46.0, 'lk_max' => 51.5],
            ['id' => 3, 'jenis_kelamin' => 'L', 'umur_bulan' => 48, 'tb_min' => 95.0,  'tb_max' => 112.0, 'bb_min' => 13.0, 'bb_max' => 21.0, 'lk_min' => 48.0, 'lk_max' => 53.0],
            ['id' => 4, 'jenis_kelamin' => 'P', 'umur_bulan' => 48, 'tb_min' => 94.0,  'tb_max' => 111.0, 'bb_min' => 12.5, 'bb_max' => 21.5, 'lk_min' => 46.5, 'lk_max' => 52.0],
            ['id' => 5, 'jenis_kelamin' => 'L', 'umur_bulan' => 60, 'tb_min' => 101.0, 'tb_max' => 119.0, 'bb_min' => 14.0, 'bb_max' => 24.0, 'lk_min' => 48.0, 'lk_max' => 54.0],
            ['id' => 6, 'jenis_kelamin' => 'P', 'umur_bulan' => 60, 'tb_min' => 100.0, 'tb_max' => 119.0, 'bb_min' => 13.5, 'bb_max' => 25.0, 'lk_min' => 47.0, 'lk_max' => 53.0],
            ['id' => 7, 'jenis_kelamin' => 'L', 'umur_bulan' => 72, 'tb_min' => 101.0, 'tb_max' => 119.0, 'bb_min' => 14.0, 'bb_max' => 24.0, 'lk_min' => 48.0, 'lk_max' => 54.0],
            ['id' => 8, 'jenis_kelamin' => 'P', 'umur_bulan' => 72, 'tb_min' => 100.0, 'tb_max' => 119.0, 'bb_min' => 13.5, 'bb_max' => 25.0, 'lk_min' => 47.0, 'lk_max' => 53.0],
            ['id' => 9, 'jenis_kelamin' => 'L', 'umur_bulan' => 84, 'tb_min' => 112.0, 'tb_max' => 131.0, 'bb_min' => 18.0, 'bb_max' => 31.5, 'lk_min' => 48.0, 'lk_max' => 54.0],
            ['id' => 10,'jenis_kelamin' => 'P', 'umur_bulan' => 84, 'tb_min' => 111.0, 'tb_max' => 130.5, 'bb_min' => 17.5, 'bb_max' => 32.5, 'lk_min' => 47.0, 'lk_max' => 53.0],
        ];

        foreach ($data as $row) {
            DataStandarFisik::updateOrCreate(
                ['id' => $row['id']],
                array_merge($row, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]),
            );
        }
    }
}
