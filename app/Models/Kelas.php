<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $fillable = [
        'nama',
        'tingkat',
        'tahun_ajaran_id',
        'guru_id',
    ];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }
}
