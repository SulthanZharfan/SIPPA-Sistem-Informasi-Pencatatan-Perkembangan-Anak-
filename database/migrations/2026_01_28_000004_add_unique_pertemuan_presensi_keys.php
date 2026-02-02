<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pertemuan_presensis', function (Blueprint $table) {
            $table->unique(['kelas_id', 'tahun_ajaran_id', 'pertemuan_ke'], 'pertemuan_presensis_kelas_tahun_pertemuan_unique');
            $table->unique(['kelas_id', 'tahun_ajaran_id', 'tanggal'], 'pertemuan_presensis_kelas_tahun_tanggal_unique');
        });
    }

    public function down(): void
    {
        Schema::table('pertemuan_presensis', function (Blueprint $table) {
            $table->dropUnique('pertemuan_presensis_kelas_tahun_pertemuan_unique');
            $table->dropUnique('pertemuan_presensis_kelas_tahun_tanggal_unique');
        });
    }
};
