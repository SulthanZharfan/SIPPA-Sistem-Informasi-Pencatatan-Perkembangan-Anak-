<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataStandarFisik extends Model
{
    protected $fillable = [
        'jenis_kelamin',
        'umur_bulan',
        'tb_min', 'tb_max',
        'bb_min', 'bb_max',
        'lk_min', 'lk_max',
    ];

    public function perkembanganFisik()
    {
        return $this->hasMany(PerkembanganFisik::class, 'standar_id');
    }
}
