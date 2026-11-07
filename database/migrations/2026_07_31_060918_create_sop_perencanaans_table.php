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
        Schema::create('sop_perencanaans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_sop')->unique();
            $table->string('judul');
            $table->string('kategori')->nullable();
            $table->string('dokumen_pdf')->nullable();
            $table->string('versi')->default('1.0');
            $table->date('tanggal_berlaku');
            $table->text('riwayat_revisi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sop_perencanaans');
    }
};
