<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\PerkembanganFisikObserver;

class PerkembanganFisik extends Model
{
    use HasFactory;

    protected $table = 'perkembangan_fisiks';

    protected $fillable = [
        // Relasi kunci
        'pertemuan_perkembangan_fisik_id',
        'siswa_id',
        'guru_id',
        'tahun_ajaran_id',

        // Data fisik
        'tinggi_badan',
        'berat_badan',
        'lingkar_kepala',
        'umur_bulan',
        'tanggal_ukur',

        // Foto perkembangan
        'foto',

        // Kategori fisik
        'kategori_bb',
        'kategori_tb',
        'kategori_lk',
        'status_ringkas',
    ];

    // PER PERTEMUAN 
    public function pertemuan()
    {
        return $this->belongsTo(PertemuanPerkembanganFisik::class, 'pertemuan_perkembangan_fisik_id');
    }

    // SISWA
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    // GURU
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    // TAHUN AJARAN
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    protected static function booted(): void
    {
        static::observe(PerkembanganFisikObserver::class);
    }
}
