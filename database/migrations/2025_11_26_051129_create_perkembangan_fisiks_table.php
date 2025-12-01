<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perkembangan_fisiks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->foreignId('guru_id')->constrained('gurus')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->cascadeOnDelete();
            $table->foreignId('standar_id')->nullable()->constrained('data_standar_fisiks')->nullOnDelete();

            $table->float('tinggi_badan');
            $table->float('berat_badan');
            $table->float('lingkar_kepala')->nullable();
            $table->date('tanggal_ukur');
            $table->integer('umur_bulan')->nullable();
            $table->string('foto')->nullable();

            $table->string('kategori_tb')->nullable();
            $table->string('kategori_bb')->nullable();
            $table->string('kategori_lk')->nullable();

            $table->enum('status_persetujuan', ['menunggu', 'disetujui', 'revisi'])
                ->default('menunggu');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perkembangan_fisiks');
    }
};
