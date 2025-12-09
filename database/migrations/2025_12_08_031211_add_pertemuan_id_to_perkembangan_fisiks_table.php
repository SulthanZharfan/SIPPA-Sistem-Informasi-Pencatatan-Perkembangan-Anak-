<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perkembangan_fisiks', function (Blueprint $table) {
            $table->foreignId('pertemuan_perkembangan_fisik_id')
                ->nullable() // sementara boleh null biar data lama aman
                ->after('id')
                ->constrained('pertemuan_perkembangan_fisiks')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('perkembangan_fisiks', function (Blueprint $table) {
            $table->dropForeign(['pertemuan_perkembangan_fisik_id']);
            $table->dropColumn('pertemuan_perkembangan_fisik_id');
        });
    }
};
