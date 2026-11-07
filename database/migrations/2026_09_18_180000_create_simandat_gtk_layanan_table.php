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
        Schema::create('simandat_gtk_layanan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_registrasi')->unique();
            $table->string('jenis_layanan'); // 4 Layanan GTK: Sertifikasi Pendidik, NUPTK, Tunjangan Pendidik, SIMPKB/PKB
            $table->string('nama_lengkap_guru'); // Nama Guru / Tenaga Kependidikan
            $table->string('nuptk')->nullable();
            $table->string('nip_nik')->nullable();
            $table->string('tempat_tugas_sekolah'); // Satuan Pendidikan tempat bertugas
            $table->string('npsn_sekolah')->nullable();
            $table->string('jenjang_sekolah')->nullable(); // PAUD/TK, SD, SMP, SMA, SMK, SLB
            $table->string('status_kepegawaian')->default('PNS'); // PNS, PPPK, Guru Non-ASN/GTT, Tendik
            $table->string('kabupaten_kota');
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_selesai')->nullable();
            $table->string('nomor_sk_rekomendasi')->nullable(); // No SK TPG / Rekomendasi NUPTK / Sertifikat
            $table->text('perihal_uraian');
            $table->string('status_layanan')->default('PROSES_VERIFIKASI'); // DRAFT, PROSES_VERIFIKASI, DISETUJUI, DITOLAK, SELESAI
            $table->string('file_permohonan')->nullable(); // Berkas syarat
            $table->string('file_nama_permohonan')->nullable();
            $table->string('file_hasil_sk')->nullable(); // Berkas SK / Sertifikat hasil
            $table->string('file_nama_hasil_sk')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->text('catatan_verifikator')->nullable();
            $table->string('petugas_verifikator')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['jenis_layanan', 'status_layanan', 'kabupaten_kota'], 'idx_gtk_filter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simandat_gtk_layanan');
    }
};
