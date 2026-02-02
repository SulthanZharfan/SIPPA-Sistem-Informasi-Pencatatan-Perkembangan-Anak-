<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_standar_fisiks', function (Blueprint $table) {
            $table->unique(['umur_bulan', 'jenis_kelamin'], 'data_standar_fisiks_umur_jk_unique');
        });
    }

    public function down(): void
    {
        Schema::table('data_standar_fisiks', function (Blueprint $table) {
            $table->dropUnique('data_standar_fisiks_umur_jk_unique');
        });
    }
};
