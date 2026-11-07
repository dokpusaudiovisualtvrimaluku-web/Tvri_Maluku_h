<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penugasans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_tugas')->unique();
            $table->string('pic');
            $table->string('lokasi');
            $table->string('agenda');
            $table->string('target')->nullable();
            $table->string('status')->default('Belum Mulai'); // Belum Mulai, Berjalan, Revisi, Selesai, Batal
            $table->date('deadline');
            $table->integer('progress')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penugasans');
    }
};
