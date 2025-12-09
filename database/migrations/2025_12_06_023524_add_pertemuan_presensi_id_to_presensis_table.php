<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->foreignId('pertemuan_presensi_id')
                ->nullable()
                ->after('id')
                ->constrained('pertemuan_presensis')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->dropForeign(['pertemuan_presensi_id']);
            $table->dropColumn('pertemuan_presensi_id');
        });
    }
};
