<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            ['id' => 1, 'tahun' => '2025/2026', 'semester' => 'Genap', 'is_active' => true],
            ['id' => 2, 'tahun' => '2024/2025', 'semester' => 'Ganjil', 'is_active' => false],
        ];

        foreach ($records as $data) {
            TahunAjaran::updateOrCreate(
                ['id' => $data['id']],
                $data,
            );
        }
    }
}
