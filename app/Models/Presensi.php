<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensis';

    protected $fillable = [
        'pertemuan_presensi_id',
        'siswa_id',
        'guru_id',
        'kelas_id',
        'tahun_ajaran_id',
        'tanggal',
        'status_kehadiran',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // 🔗 Header pertemuan
    public function pertemuan()
    {
        return $this->belongsTo(PertemuanPresensi::class, 'pertemuan_presensi_id');
    }

    // 🔗 Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // 🔗 Guru
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    // 🔗 Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // 🔗 Tahun ajaran
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}
