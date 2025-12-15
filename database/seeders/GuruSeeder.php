<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        $gurus = [
            [
                'id' => 1,
                'user' => [
                    'name' => 'Guru 1',
                    'email' => 'guru@sippa.com',
                ],
                'nama' => 'Guru 1',
                'nip' => '001',
                'telepon' => '081100000001',
                'alamat' => 'Bogor',
            ],
            [
                'id' => 2,
                'user' => [
                    'name' => 'Guru 2',
                    'email' => 'guru1@sippa.com',
                ],
                'nama' => 'Guru 2',
                'nip' => '002',
                'telepon' => '081100000002',
                'alamat' => 'Bogor',
            ],
        ];

        foreach ($gurus as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['user']['email']],
                [
                    'name' => $data['user']['name'],
                    'password' => Hash::make('password'),
                ],
            );

            Guru::updateOrCreate(
                ['id' => $data['id']],
                [
                    'user_id' => $user->id,
                    'nama' => $data['nama'],
                    'nip' => $data['nip'],
                    'telepon' => $data['telepon'],
                    'alamat' => $data['alamat'],
                ],
            );
        }
    }
}
