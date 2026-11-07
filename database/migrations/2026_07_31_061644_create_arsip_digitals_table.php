<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('arsip_digitals', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori_lokasi')->nullable(); // Ambon, Maluku Tengah dll
            $table->string('kategori_program')->nullable(); // Berita, Promo dll
            $table->integer('tahun')->default(date('Y'));
            $table->string('jenis_file')->nullable(); // Video, Audio, Foto, Dokumen
            $table->string('file_path')->nullable();
            $table->string('tags')->nullable();
            $table->integer('version')->default(1);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('arsip_digitals'); }
};
