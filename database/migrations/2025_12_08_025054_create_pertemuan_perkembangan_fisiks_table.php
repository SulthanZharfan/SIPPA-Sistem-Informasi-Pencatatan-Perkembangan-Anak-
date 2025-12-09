<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pertemuan_perkembangan_fisiks', function (Blueprint $table) {
            $table->id();

            // Kelas yang dipegang
            $table->foreignId('kelas_id')
                ->constrained('kelas')
                ->cascadeOnDelete();

            // Guru yang membuat pertemuan
            $table->foreignId('guru_id')
                ->constrained('gurus')
                ->cascadeOnDelete();

            // Tahun ajaran aktif
            $table->foreignId('tahun_ajaran_id')
                ->constrained('tahun_ajarans')
                ->cascadeOnDelete();

            // Nomor pertemuan (1,2,3,...)
            $table->unsignedInteger('pertemuan_ke');

            // Info waktu
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pertemuan_perkembangan_fisiks');
    }

};
