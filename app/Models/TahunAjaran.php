<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $fillable = [
        'tahun',
        'semester',
        'is_active',
    ];

    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }

    public function siswas()
    {
        return $this->hasMany(Siswa::class);
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
