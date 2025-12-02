<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerkembanganKognitif extends Model
{
    protected $fillable = [
        'siswa_id',
        'guru_id',
        'indikator_id',
        'tahun_ajaran_id',
        'narasi',
        'foto',
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

    public function indikator()
    {
        return $this->belongsTo(IndikatorPerkembangan::class, 'indikator_id');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
