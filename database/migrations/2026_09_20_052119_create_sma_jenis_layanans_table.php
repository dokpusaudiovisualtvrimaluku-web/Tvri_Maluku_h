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
        Schema::create('sma_jenis_layanans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_layanan');
            $table->string('seksi')->nullable();
            $table->string('prefix')->nullable();
            $table->string('deskripsi')->nullable();
            $table->string('icon')->nullable();
            $table->string('badge')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sma_jenis_layanans');
    }
};
