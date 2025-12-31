<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wali extends Model
{
    public const SALUTATIONS = [
        'Bapak',
        'Ibu',
        'Kakak',
        'Om',
        'Tante',
        'Kakek',
        'Nenek',
    ];

    protected $fillable = [
        'user_id',
        'nama',
        'salutation',
        'telepon',
        'alamat',
        'pekerjaan',
    ];

    public static function salutationOptions(): array
    {
        return array_combine(self::SALUTATIONS, self::SALUTATIONS);
    }

    public static function sanitizeNama(?string $nama): ?string
    {
        if ($nama === null) {
            return null;
        }

        $nama = trim($nama);

        if ($nama === '') {
            return $nama;
        }

        $pattern = '/^(' . implode('|', array_map('preg_quote', self::SALUTATIONS)) . ')\.?\s+/i';
        $nama = preg_replace($pattern, '', $nama) ?? $nama;

        return trim($nama);
    }

    public function getNamaTampilAttribute(): string
    {
        $nama = trim((string) $this->nama);
        $salutation = trim((string) ($this->salutation ?? ''));

        if ($salutation !== '') {
            return trim($salutation . ' ' . $nama);
        }

        return $nama;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function siswas(): HasMany
    {
        return $this->hasMany(Siswa::class, 'wali_id');
    }
}
