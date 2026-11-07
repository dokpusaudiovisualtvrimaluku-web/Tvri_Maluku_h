<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tvri_promo_sops')) {
            Schema::create('tvri_promo_sops', function (Blueprint $table) {
                $table->id();
                $table->string('kode')->nullable();
                $table->string('judul');
                $table->string('kategori')->default('Produksi Promo');
                $table->string('versi')->default('v1.0');
                $table->date('tanggal_efektif')->nullable();
                $table->string('status')->default('Aktif');
                $table->string('file_path')->nullable();
                $table->string('file_url')->nullable();
                $table->text('keterangan')->nullable();
                $table->integer('urutan')->default(1);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tvri_promo_sops');
    }
};
