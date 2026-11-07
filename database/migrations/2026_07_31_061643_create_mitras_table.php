<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('mitras', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->string('kontak')->nullable();
            $table->string('email')->nullable();
            $table->string('kategori')->nullable(); // Pemda, Kampus, Komunitas, dll
            $table->string('jenis_kerjasama')->nullable();
            $table->string('dokumen_mou')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('mitras'); }
};
