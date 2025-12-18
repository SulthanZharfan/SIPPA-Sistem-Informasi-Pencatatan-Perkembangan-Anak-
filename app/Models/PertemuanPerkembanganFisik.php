<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'status',        // pending|approved|rejected
        'approved_by',   // nanti kita arahkan ke users.id (kepsek)
        'approved_at',
    ];

    protected $casts = [
        'tanggal'     => 'date',
        'jam_mulai'   => 'datetime:H:i:s',
        'jam_selesai' => 'datetime:H:i:s',
        'approved_at' => 'datetime',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function perkembanganFisiks(): HasMany
    {
        return $this->hasMany(PerkembanganFisik::class, 'pertemuan_perkembangan_fisik_id');
    }

    // Setelah kita ubah FK approved_by -> users.id, ini yang dipakai
    public function approverUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
