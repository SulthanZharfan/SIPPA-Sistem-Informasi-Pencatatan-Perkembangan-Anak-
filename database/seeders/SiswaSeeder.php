<?php

namespace Database\Seeders;

use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $siswas = [
            ['nis' => '2425190467', 'nama' => 'ARETHA TANTRI MALEYAKA', 'jenis_kelamin' => 'P', 'tempat_lahir' => 'BOGOR', 'tanggal_lahir' => '2019-11-28', 'kelas_id' => 2, 'tahun_ajaran_id' => 1],
            ['nis' => '2425190470', 'nama' => 'AMANATA TIRTA WINATA', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'JAKARTA', 'tanggal_lahir' => '2020-09-23', 'kelas_id' => 2, 'tahun_ajaran_id' => 1],
            ['nis' => '2425190471', 'nama' => 'SAMIH KAY YAZEED', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'BOGOR', 'tanggal_lahir' => '2019-12-07', 'kelas_id' => 2, 'tahun_ajaran_id' => 1],
            ['nis' => '2425190472', 'nama' => 'ASETA KRIELOCA', 'jenis_kelamin' => 'P', 'tempat_lahir' => 'BOGOR', 'tanggal_lahir' => '2019-08-25', 'kelas_id' => 2, 'tahun_ajaran_id' => 1],
            ['nis' => '2425190473', 'nama' => 'ALGHIFARI PUTRA BAHTERA', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'PANGKAL PINANG', 'tanggal_lahir' => '2019-09-13', 'kelas_id' => 2, 'tahun_ajaran_id' => 1],
            ['nis' => '2425190474', 'nama' => 'MUHAMMAD RAFEYYA ZHAFRAN', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'PEKAN BARU', 'tanggal_lahir' => '2019-11-16', 'kelas_id' => 2, 'tahun_ajaran_id' => 1],
            ['nis' => '2425190475', 'nama' => 'DIMMY CARINA ANDRIANI', 'jenis_kelamin' => 'P', 'tempat_lahir' => 'JAKARTA', 'tanggal_lahir' => '2019-12-30', 'kelas_id' => 2, 'tahun_ajaran_id' => 1],
            ['nis' => '2425190477', 'nama' => 'KHALIFA BORNEO AIDAN', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'BANJARBARU', 'tanggal_lahir' => '2018-10-16', 'kelas_id' => 2, 'tahun_ajaran_id' => 1],
            ['nis' => '2425190478', 'nama' => 'MUHAMMAD DYTTA AROGHAL', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'BOGOR', 'tanggal_lahir' => '2019-02-24', 'kelas_id' => 2, 'tahun_ajaran_id' => 1],
            ['nis' => '2425190480', 'nama' => 'ADIVA ALMAHYRA ARVIKA', 'jenis_kelamin' => 'P', 'tempat_lahir' => 'BOGOR', 'tanggal_lahir' => '2019-12-23', 'kelas_id' => 2, 'tahun_ajaran_id' => 1],
            ['nis' => '2425190481', 'nama' => 'MUHAMMAD FIRDUSI NURAQHA', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'BOGOR', 'tanggal_lahir' => '2019-06-12', 'kelas_id' => 2, 'tahun_ajaran_id' => 1],
            ['nis' => '2526205903', 'nama' => 'MUHAMMAD IHSAN ABURROHMAN', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'BOGOR', 'tanggal_lahir' => '2019-05-04', 'kelas_id' => 2, 'tahun_ajaran_id' => 1],
            ['nis' => '2526205908', 'nama' => 'QAIREEN BISYRI', 'jenis_kelamin' => 'P', 'tempat_lahir' => 'KUNINGAN', 'tanggal_lahir' => '2019-08-25', 'kelas_id' => 2, 'tahun_ajaran_id' => 1],
            ['nis' => '2526205907', 'nama' => 'AIVAARA AZKADIANRA ZHAHIR', 'jenis_kelamin' => 'P', 'tempat_lahir' => 'PONOROGO', 'tanggal_lahir' => '2020-05-30', 'kelas_id' => 2, 'tahun_ajaran_id' => 1],
            ['nis' => '2526205910', 'nama' => 'JUNIOR GHINAYA HADYA ABIDA', 'jenis_kelamin' => 'P', 'tempat_lahir' => 'BOGOR', 'tanggal_lahir' => '2020-03-01', 'kelas_id' => 2, 'tahun_ajaran_id' => 1],
            ['nis' => '2526205928', 'nama' => 'RADJA DHANIEL ARBENO', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'KOTA BOGOR', 'tanggal_lahir' => '2020-04-01', 'kelas_id' => 2, 'tahun_ajaran_id' => 1],
            ['nis' => '2526205970', 'nama' => 'SARFARAZ DANIAL AHMAD', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'BOGOR', 'tanggal_lahir' => '2019-10-01', 'kelas_id' => 2, 'tahun_ajaran_id' => 1],
            ['nis' => '2526205975', 'nama' => 'RODE SAAFFANA RAMADHANI', 'jenis_kelamin' => 'P', 'tempat_lahir' => 'KENDARI', 'tanggal_lahir' => '2020-05-08', 'kelas_id' => 2, 'tahun_ajaran_id' => 1],
            ['nis' => '2526205979', 'nama' => 'GHALIAL MALIK YUSUF', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'PROBOLINGGO', 'tanggal_lahir' => '2020-05-03', 'kelas_id' => 2, 'tahun_ajaran_id' => 1],
            ['nis' => '252620481', 'nama' => 'HANIN HANANIA ALFATUNISSA', 'jenis_kelamin' => 'P', 'tempat_lahir' => 'BOGOR', 'tanggal_lahir' => '2021-03-27', 'kelas_id' => 1, 'tahun_ajaran_id' => 1],
            ['nis' => '252620486', 'nama' => 'MUHAMMAD ZAYN CALIJEF ARSHAKA', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'SURAKARTA', 'tanggal_lahir' => '2021-07-29', 'kelas_id' => 1, 'tahun_ajaran_id' => 1],
            ['nis' => '252620487', 'nama' => 'SADEWO ALVARENDRA PRADANA', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'BOGOR', 'tanggal_lahir' => '2021-04-30', 'kelas_id' => 1, 'tahun_ajaran_id' => 1],
            ['nis' => '252620476', 'nama' => 'ADIVA KAYSA MAULLANA', 'jenis_kelamin' => 'P', 'tempat_lahir' => 'BANDAR LAMPUNG', 'tanggal_lahir' => '2021-02-02', 'kelas_id' => 1, 'tahun_ajaran_id' => 1],
            ['nis' => '252620477', 'nama' => 'ADNAN ALFARIZKI AZHARI', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'LIMA PULUH KOTA', 'tanggal_lahir' => '2020-12-16', 'kelas_id' => 1, 'tahun_ajaran_id' => 1],
            ['nis' => '252620478', 'nama' => 'ANDI ARSY NUR ZANTHIHA', 'jenis_kelamin' => 'P', 'tempat_lahir' => 'BOGOR', 'tanggal_lahir' => '2021-03-10', 'kelas_id' => 1, 'tahun_ajaran_id' => 1],
            ['nis' => '252620479', 'nama' => 'AQILA NURSYIFA FATHIYAH', 'jenis_kelamin' => 'P', 'tempat_lahir' => 'PALEMBANG', 'tanggal_lahir' => '2021-02-22', 'kelas_id' => 1, 'tahun_ajaran_id' => 1],
            ['nis' => '252620480', 'nama' => 'FAIZAN MUDRIKA AKBAR', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'PURWAKARTA', 'tanggal_lahir' => '2021-03-16', 'kelas_id' => 1, 'tahun_ajaran_id' => 1],
            ['nis' => '252620482', 'nama' => 'KIYOSHI SAVDA ABDILLAH', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'BOGOR', 'tanggal_lahir' => '2020-03-06', 'kelas_id' => 1, 'tahun_ajaran_id' => 1],
            ['nis' => '252620483', 'nama' => 'MUHAMMAD ALKHALIFI SUDRAJAT', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'BOGOR', 'tanggal_lahir' => '2020-12-18', 'kelas_id' => 1, 'tahun_ajaran_id' => 1],
            ['nis' => '252620484', 'nama' => 'MUHAMMAD FAQIH ALGHIFARI', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'JAKARTA', 'tanggal_lahir' => '2020-06-17', 'kelas_id' => 1, 'tahun_ajaran_id' => 1],
            ['nis' => '252620485', 'nama' => 'MUHAMMAD HISYAM ARRAYAN', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'BOGOR', 'tanggal_lahir' => '2020-08-28', 'kelas_id' => 1, 'tahun_ajaran_id' => 1],
            ['nis' => '252620488', 'nama' => 'SHAFA NORA AKTARACH', 'jenis_kelamin' => 'P', 'tempat_lahir' => 'BOGOR', 'tanggal_lahir' => '2020-11-12', 'kelas_id' => 1, 'tahun_ajaran_id' => 1],
            ['nis' => '252620489', 'nama' => 'WIDIA SHANUM KAMILA', 'jenis_kelamin' => 'P', 'tempat_lahir' => 'BOGOR', 'tanggal_lahir' => '2020-04-20', 'kelas_id' => 1, 'tahun_ajaran_id' => 1],
            ['nis' => '252620490', 'nama' => 'BIANCA INARA MECCA LA ELO', 'jenis_kelamin' => 'P', 'tempat_lahir' => 'BANDA', 'tanggal_lahir' => '2020-12-13', 'kelas_id' => 1, 'tahun_ajaran_id' => 1],
        ];

        foreach ($siswas as $data) {
            Siswa::updateOrCreate(
                ['nis' => $data['nis']],
                [
                    'nama' => $data['nama'],
                    'jenis_kelamin' => $data['jenis_kelamin'],
                    'tempat_lahir' => $data['tempat_lahir'] ?? null,
                    'tanggal_lahir' => $data['tanggal_lahir'],
                    'kelas_id' => $data['kelas_id'],
                    'tahun_ajaran_id' => $data['tahun_ajaran_id'],
                    'wali_id' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            );
        }
    }
}
