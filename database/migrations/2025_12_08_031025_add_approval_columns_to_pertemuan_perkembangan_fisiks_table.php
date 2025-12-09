<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pertemuan_perkembangan_fisiks', function (Blueprint $table) {
            // status pertemuan: menunggu / disetujui / ditolak
            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->default('pending')
                ->after('jam_selesai');

            // kepsek yang menyetujui (asumsi pakai tabel gurus)
            $table->foreignId('approved_by')
                ->nullable()
                ->after('status')
                ->constrained('gurus')
                ->nullOnDelete();

            // waktu persetujuan
            $table->timestamp('approved_at')
                ->nullable()
                ->after('approved_by');
        });
    }

    public function down(): void
    {
        Schema::table('pertemuan_perkembangan_fisiks', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['status', 'approved_by', 'approved_at']);
        });
    }
};
