<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    // opsional, sebenernya default-nya juga sudah 'tahun_ajarans'
    protected $table = 'tahun_ajarans';

    protected $fillable = [
        'tahun',
        'semester',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Pastikan hanya satu tahun ajaran yang aktif.
     */
    protected static function booted()
    {
        static::saving(function ($record) {
            if ($record->is_active) {
                static::where('id', '!=', $record->id)
                    ->update(['is_active' => false]);
            }
        });
    }

    /**
     * Label gabungan tahun + semester (opsional, tapi kepake buat tampilan).
     */
    public function getLabelAttribute(): string
    {
        return "{$this->tahun} - {$this->semester}";
    }

    // === RELASI YANG SUDAH ADA ===

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
