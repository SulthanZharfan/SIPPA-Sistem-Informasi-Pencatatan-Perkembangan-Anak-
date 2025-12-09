<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PertemuanPerkembanganFisik extends Model
{
    use HasFactory;

    protected $table = 'pertemuan_perkembangan_fisiks';

    protected $fillable = [
        'kelas_id',
        'guru_id',
        'tahun_ajaran_id',
        'pertemuan_ke',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
        'approved_at' => 'datetime',
    ];

    // 1 pertemuan → banyak perkembangan fisik siswa
    public function perkembanganFisiks()
    {
        return $this->hasMany(PerkembanganFisik::class, 'pertemuan_perkembangan_fisik_id');
    }

    // Guru yang membuat pertemuan
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    // Kelas yang dipegang
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // Tahun ajaran
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    // Kepsek/guru yang approve
    public function approver()
    {
        return $this->belongsTo(Guru::class, 'approved_by');
    }
}
