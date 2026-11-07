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
        // 1. Table: simandat_cultures
        if (!Schema::hasTable('simandat_cultures')) {
            Schema::create('simandat_cultures', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->string('kategori')->index(); // 16 Kategori
                $table->string('subkategori')->nullable();
                $table->text('deskripsi')->nullable();
                $table->string('lokasi')->nullable();
                $table->string('desa_kelurahan')->nullable();
                $table->string('kecamatan')->nullable();
                $table->string('kabupaten_kota')->index();
                $table->string('tahun', 10)->default('2024')->index();
                $table->string('status', 50)->default('Terdaftar')->index(); // Terdaftar, Ditetapkan Nasional, Diusulkan, dll.
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->string('foto')->nullable();
                $table->string('video_url')->nullable();
                $table->string('audio_url')->nullable();
                $table->string('dokumen_pendukung')->nullable();
                $table->string('link_eksternal')->nullable();
                $table->text('kata_kunci')->nullable();
                $table->enum('status_validasi', ['DRAFT', 'SUBMITTED', 'VERIFIED', 'VALIDATED', 'REJECTED'])->default('VALIDATED')->index();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        // 2. Table: simandat_culture_galleries
        if (!Schema::hasTable('simandat_culture_galleries')) {
            Schema::create('simandat_culture_galleries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('culture_id')->constrained('simandat_cultures')->cascadeOnDelete();
                $table->enum('media_type', ['photo', 'video', 'audio', 'pdf'])->default('photo')->index();
                $table->string('file_path');
                $table->string('caption')->nullable();
                $table->boolean('is_primary')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simandat_culture_galleries');
        Schema::dropIfExists('simandat_cultures');
    }
};
