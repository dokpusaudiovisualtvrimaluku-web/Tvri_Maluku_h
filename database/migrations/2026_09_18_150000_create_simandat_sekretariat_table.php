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
        Schema::create('simandat_sekretariat_data', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_agenda')->unique();
            $table->string('kategori_fungsi'); // 8 Fungsi Utama
            $table->string('sub_kegiatan');
            $table->string('judul_kegiatan');
            $table->string('penanggung_jawab'); // Kasubbag/Koordinator
            $table->string('bidang_unit_terkait')->nullable(); // Bidang/Unit terkait
            $table->date('tanggal_kegiatan');
            $table->date('tanggal_target_selesai')->nullable();
            $table->integer('tahun_anggaran')->default(2026);
            $table->decimal('alokasi_anggaran', 15, 2)->default(0);
            $table->decimal('realisasi_anggaran', 15, 2)->default(0);
            $table->integer('persentase_capaian')->default(0); // 0 - 100%
            $table->string('status_pelaksanaan')->default('Dalam Proses'); // Direncanakan, Sedang Berjalan, Menunggu Telaah, Selesai, Perlu Evaluasi, Tertunda
            $table->string('tingkat_prioritas')->default('Sedang'); // Tinggi, Sedang, Biasa
            $table->text('deskripsi_uraian')->nullable();
            $table->text('hasil_keluaran_output')->nullable();
            $table->text('catatan_arahan_sekretaris')->nullable(); // Arahan & Pembinaan Sekretaris Dinas
            $table->string('file_dokumen')->nullable();
            $table->string('file_nama_asli')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['kategori_fungsi', 'status_pelaksanaan', 'tahun_anggaran'], 'idx_sek_filter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simandat_sekretariat_data');
    }
};
