<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pertemuan_perkembangan_fisiks', function (Blueprint $table) {
            // drop FK lama (nama constraint bisa beda, ini cara aman kalau kamu tahu namanya)
            // Kalau error karena nama constraint beda, jalankan SHOW CREATE TABLE dan aku bantu fix namanya.
            $table->dropForeign(['approved_by']);

            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pertemuan_perkembangan_fisiks', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);

            $table->foreign('approved_by')
                ->references('id')
                ->on('gurus')
                ->nullOnDelete();
        });
    }
};
