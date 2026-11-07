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
        Schema::create('simandat_perencanaan_programs', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_registrasi')->unique();
            $table->string('jenis_dokumen'); // Program, Kegiatan, Renstra, Renja, RKA, DPA
            $table->string('kode_rekening')->nullable(); // Kode Subkegiatan/Program e.g. 1.01.02.1.01.0001
            $table->string('nama_program_kegiatan');
            $table->string('bidang_penanggung_jawab'); // Sekretariat, SMA, SMK, SLB, Kebudayaan, GTK
            $table->integer('tahun_anggaran')->default(2026);
            $table->string('periode_pelaksanaan')->nullable(); // e.g. 2024-2026 / Triwulan I - IV
            $table->decimal('pagu_anggaran', 15, 2)->default(0);
            $table->decimal('realisasi_anggaran', 15, 2)->default(0);
            $table->string('target_kinerja')->nullable(); // e.g. 100 Unit Sekolah / 12 Dokumen
            $table->string('realisasi_kinerja')->nullable();
            $table->decimal('persentase_capaian', 5, 2)->default(0);
            $table->string('status_pelaksanaan')->default('PERENCANAAN'); // PERENCANAAN, SEDANG_BERJALAN, SELESAI, DITUNDA
            $table->text('uraian_kegiatan');
            $table->string('file_dokumen')->nullable();
            $table->string('file_nama_dokumen')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('pejabat_penanggung_jawab')->nullable(); // PPTK / Kabid / Kasubbag
            $table->text('catatan_evaluasi')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['jenis_dokumen', 'bidang_penanggung_jawab', 'tahun_anggaran', 'status_pelaksanaan'], 'idx_perencanaan_filter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simandat_perencanaan_programs');
    }
};
