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
        if (!Schema::hasTable('tvri_promo_ringkasans')) {
            Schema::create('tvri_promo_ringkasans', function (Blueprint $table) {
                $table->id();

                // 1. Ringkasan Metrik Utama
                $table->integer('total_kampanye')->default(24);
                $table->string('diff_kampanye')->default('+33%');
                $table->integer('materi_diproduksi')->default(56);
                $table->string('diff_materi')->default('+27%');
                $table->string('penayangan_onair')->default('1.230 spot');
                $table->string('diff_onair')->default('+18%');
                $table->string('reach_total')->default('1,8 Juta');
                $table->string('diff_reach')->default('+42%');
                $table->string('engagement_digital')->default('286 Ribu');
                $table->string('diff_engagement')->default('+35%');
                $table->integer('tingkat_capaian')->default(92);
                $table->integer('target_capaian')->default(90);

                // 2 s/d 9: Komponen Terstruktur
                $table->json('distribusi_media_json')->nullable();
                $table->json('capaian_kampanye_json')->nullable();
                $table->json('top_kampanye_json')->nullable();
                $table->json('platform_digital_json')->nullable();
                $table->json('status_produksi_json')->nullable();
                $table->json('jadwal_tayang_json')->nullable();
                $table->json('agenda_promo_json')->nullable();

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tvri_promo_ringkasans');
    }
};
