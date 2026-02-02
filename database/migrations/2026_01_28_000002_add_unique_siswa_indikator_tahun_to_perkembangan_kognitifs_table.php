<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perkembangan_kognitifs', function (Blueprint $table) {
            $table->unique(
                ['siswa_id', 'indikator_id', 'tahun_ajaran_id'],
                'perkembangan_kognitifs_siswa_indikator_tahun_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('perkembangan_kognitifs', function (Blueprint $table) {
            $table->dropUnique('perkembangan_kognitifs_siswa_indikator_tahun_unique');
        });
    }
};
