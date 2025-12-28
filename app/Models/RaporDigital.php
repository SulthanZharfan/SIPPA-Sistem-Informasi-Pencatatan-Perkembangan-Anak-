<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class RaporDigital extends Model
{
    protected $fillable = [
        'siswa_id',
        'tahun_ajaran_id',
        'pembuat_rapor',
        'tanggal_generate',
        'periode',
        'file_path',
    ];

    protected $casts = [
        'tanggal_generate' => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function pembuat()
    {
        return $this->belongsTo(Guru::class, 'pembuat_rapor');
    }
}
