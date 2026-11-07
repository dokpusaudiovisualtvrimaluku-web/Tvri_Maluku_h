<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tvri_keuangan_sops')) {
            Schema::create('tvri_keuangan_sops', function (Blueprint $table) {
                $table->id();
                $table->string('kode')->nullable();
                $table->string('judul');
                $table->string('kategori')->default('Pengelolaan Anggaran');
                $table->string('versi')->default('v1.0');
                $table->string('status')->default('Aktif');
                $table->string('file_path')->nullable();
                $table->text('keterangan')->nullable();
                $table->integer('urutan')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tvri_keuangan_sops');
    }
};
