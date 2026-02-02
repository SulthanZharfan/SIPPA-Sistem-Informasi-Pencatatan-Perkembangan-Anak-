<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tahun_ajarans', function (Blueprint $table) {
            $table->unique(['tahun', 'semester'], 'tahun_ajarans_tahun_semester_unique');
        });
    }

    public function down(): void
    {
        Schema::table('tahun_ajarans', function (Blueprint $table) {
            $table->dropUnique('tahun_ajarans_tahun_semester_unique');
        });
    }
};
