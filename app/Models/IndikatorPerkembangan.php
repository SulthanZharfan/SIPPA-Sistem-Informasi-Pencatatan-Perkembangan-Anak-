<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndikatorPerkembangan extends Model
{
    protected $fillable = [
        'aspek',
        'deskripsi',
    ];

    public function perkembanganKognitif()
    {
        return $this->hasMany(PerkembanganKognitif::class, 'indikator_id');
    }
}
