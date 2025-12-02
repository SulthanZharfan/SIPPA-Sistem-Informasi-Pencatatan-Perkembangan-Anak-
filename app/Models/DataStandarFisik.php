<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataStandarFisik extends Model
{
    protected $table = 'data_standar_fisiks';

    protected $fillable = [
        'jenis_kelamin',
        'umur_bulan',
        'tb_min', 'tb_max',
        'bb_min', 'bb_max',
        'lk_min', 'lk_max',
    ];

    protected $casts = [
        'umur_bulan' => 'integer',
        'tb_min' => 'float',
        'tb_max' => 'float',
        'bb_min' => 'float',
        'bb_max' => 'float',
        'lk_min' => 'float',
        'lk_max' => 'float',
    ];

    public function getLabelAttribute(): string
    {
        $jk = $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
        return "{$this->umur_bulan} bln - {$jk}";
    }

    public function perkembanganFisik()
    {
        return $this->hasMany(PerkembanganFisik::class, 'standar_id');
    }
}
