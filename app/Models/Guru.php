<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $fillable = [
        'user_id',
        'nama',
        'nip',
        'telepon',
        'alamat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
        return $this->hasMany(RaporDigital::class, 'pembuat_rapor');
    }

        public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }

}
