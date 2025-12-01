<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerkembanganFisik extends Model
{
    protected $fillable = [
        'siswa_id',
        'guru_id',
        'tahun_ajaran_id',
        'standar_id',
        'tinggi_badan',
        'berat_badan',
        'lingkar_kepala',
        'tanggal_ukur',
        'umur_bulan',
        'foto',
        'kategori_tb',
        'kategori_bb',
        'kategori_lk',
        'status_persetujuan',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function standar()
    {
        return $this->belongsTo(DataStandarFisik::class, 'standar_id');
    }
}
