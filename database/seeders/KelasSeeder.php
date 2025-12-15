<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $kelas = [
            [
                'id' => 1,
                'nama' => 'K1',
                'tingkat' => 'TK-A',
                'tahun_ajaran_id' => 1,
                'guru_id' => 1,
            ],
            [
                'id' => 2,
                'nama' => 'K2',
                'tingkat' => 'TK-B',
                'tahun_ajaran_id' => 1,
                'guru_id' => 2,
            ],
        ];

        foreach ($kelas as $data) {
            Kelas::updateOrCreate(
                ['id' => $data['id']],
                $data,
            );
        }
    }
}
