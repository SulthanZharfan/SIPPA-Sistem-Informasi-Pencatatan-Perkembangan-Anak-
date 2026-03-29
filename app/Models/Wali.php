<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wali extends Model
{
    protected $fillable = [
        'user_id',
        'nama',
        'telepon',
        'alamat',
        'pekerjaan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }
}
