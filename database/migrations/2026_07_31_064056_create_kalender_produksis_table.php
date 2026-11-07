<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('kalender_produksis', function (Blueprint $table) {
            $table->id();
            $table->string('judul_produksi');
            $table->string('kategori'); // Program, Berita, Event
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('pic')->nullable();
            $table->string('status')->default('Direncanakan'); // Direncanakan, Berjalan, Selesai, Dibatalkan
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('kalender_produksis'); }
};
