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
        Schema::create('simandat_laporans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_laporan')->unique();
            $table->string('kategori_laporan');
            $table->string('judul_laporan');
            $table->date('tanggal_laporan');
            $table->string('pembuat_laporan')->nullable();
            $table->string('wilayah')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('status')->default('Draft');
            $table->string('file_dokumen')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simandat_laporans');
    }
};
