<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_standar_fisiks', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->unsignedInteger('umur_bulan'); // sesuai ERD

            // tinggi badan (cm)
            $table->float('tb_min');
            $table->float('tb_max');

            // berat badan (kg)
            $table->float('bb_min');
            $table->float('bb_max');

            // lingkar kepala (cm)
            $table->float('lk_min')->nullable();
            $table->float('lk_max')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_standar_fisiks');
    }
};