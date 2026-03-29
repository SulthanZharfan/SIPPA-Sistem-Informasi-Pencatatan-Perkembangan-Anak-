<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $fillable = [
        'nis',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'kelas_id',
        'tahun_ajaran_id',
        'wali_id',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function wali()
    {
        return $this->belongsTo(Wali::class);
    }

    public function perkembanganFisik()
    {
        return $this->hasMany(PerkembanganFisik::class);
    }

    public function perkembanganKognitif()
    {
        return $this->hasMany(PerkembanganKognitif::class);
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }

    public function raporDigitals()
    {
        return $this->hasMany(RaporDigital::class);
    }
}
