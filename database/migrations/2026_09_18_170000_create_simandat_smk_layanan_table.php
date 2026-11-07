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
        Schema::create('simandat_smk_layanan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_registrasi')->unique();
            $table->string('jenis_layanan'); // 7 Layanan Resmi SMK
            $table->string('nama_pemohon'); // Siswa / Alumni / Kepala SMK / Yayasan
            $table->string('nomor_identitas')->nullable(); // NISN / NIK / NIP / NPSN
            $table->string('nama_sekolah_asal'); // Nama SMK
            $table->string('npsn_sekolah')->nullable();
            $table->string('konsentrasi_keahlian')->nullable(); // Jurusan / Program Keahlian / Konsentrasi Keahlian
            $table->string('kabupaten_kota');
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_selesai')->nullable();
            $table->string('nomor_surat_keluar')->nullable(); // No SK / Rekomendasi / Keterangan
            $table->text('perihal_uraian');
            $table->string('status_layanan')->default('PROSES_VERIFIKASI'); // DRAFT, PROSES_VERIFIKASI, DISETUJUI, DITOLAK, SELESAI
            $table->string('file_permohonan')->nullable(); // Berkas syarat
            $table->string('file_nama_permohonan')->nullable();
            $table->string('file_hasil_sk')->nullable(); // Berkas SK / Rekomendasi hasil
            $table->string('file_nama_hasil_sk')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->text('catatan_verifikator')->nullable();
            $table->string('petugas_verifikator')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['jenis_layanan', 'status_layanan', 'kabupaten_kota'], 'idx_smk_filter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simandat_smk_layanan');
    }
};
