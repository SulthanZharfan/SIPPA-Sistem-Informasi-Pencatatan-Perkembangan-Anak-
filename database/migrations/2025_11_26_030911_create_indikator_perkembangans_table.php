<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indikator_perkembangans', function (Blueprint $table) {
            $table->id();
            $table->string('aspek');             // contoh: "Bahasa", "Kognitif"
            $table->text('deskripsi')->nullable(); // detail indikator
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indikator_perkembangans');
    }
};