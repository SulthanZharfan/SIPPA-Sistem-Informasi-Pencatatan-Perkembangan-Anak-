<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wali extends Model
{
    protected $fillable = [
        'user_id',
        'nama',
        'telepon',
        'alamat',
        'pekerjaan',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function siswas(): HasMany
    {
        return $this->hasMany(Siswa::class, 'wali_id');
    }
}
