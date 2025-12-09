<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PertemuanPresensi extends Model
{
    use HasFactory;

    protected $table = 'pertemuan_presensis';

    protected $fillable = [
        'kelas_id',
        'guru_id',
        'tahun_ajaran_id',
        'pertemuan_ke',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    // Relasi ke kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Relasi ke guru
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    // Relasi ke tahun ajaran
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    // Relasi ke daftar presensi siswa (detail)
    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }
}
