<?php

namespace App\Models;

use App\Observers\PerkembanganFisikObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerkembanganFisik extends Model
{
    use HasFactory;

    protected $table = 'perkembangan_fisiks';

    protected $fillable = [
        'pertemuan_perkembangan_fisik_id',
        'siswa_id',
        'guru_id',
        'tahun_ajaran_id',
        'standar_id',
        'tinggi_badan',
        'berat_badan',
        'lingkar_kepala',
        'tanggal_ukur',
        'umur_bulan',
        'foto',
        'kategori_tb',
        'kategori_bb',
        'kategori_lk',
        'status_ringkas',      // normal|perlu_perhatian
        'status_persetujuan',  // menunggu|disetujui|revisi
    ];

    protected $casts = [
        'tanggal_ukur' => 'date',
    ];

    public function pertemuan(): BelongsTo
    {
        return $this->belongsTo(PertemuanPerkembanganFisik::class, 'pertemuan_perkembangan_fisik_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function standar(): BelongsTo
    {
        return $this->belongsTo(DataStandarFisik::class, 'standar_id');
    }

    protected static function booted(): void
    {
        static::observe(PerkembanganFisikObserver::class);
    }
}
