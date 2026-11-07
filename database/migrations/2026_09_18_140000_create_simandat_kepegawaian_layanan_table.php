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
        Schema::create('simandat_kepegawaian_layanan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_agenda')->unique()->index(); // e.g. REG-KP/2026/001
            $table->string('jenis_layanan')->index(); // 10 jenis layanan
            $table->string('nama_pemohon')->index();
            $table->string('nip_nik_nisn')->nullable()->index(); // NIP / NIK / NIM / NISN
            $table->string('pangkat_golongan')->nullable(); // Penata Muda (III/a), Pembina (IV/a), dll.
            $table->string('jabatan_unit_kerja')->nullable(); // Guru / Staf / Siswa / Mahasiswa di SMAN 1 Siwalima, Unpatti, dll.
            $table->string('kabupaten_kota')->default('Kota Ambon')->index();
            $table->date('tanggal_permohonan')->index();
            $table->date('tanggal_selesai')->nullable();
            $table->string('nomor_sk_surat')->nullable()->index(); // No SK / Surat Terbit
            $table->text('perihal_deskripsi')->nullable();
            $table->enum('status_layanan', ['PENDING', 'PROSES_VERIFIKASI', 'DISETUJUI', 'DITOLAK', 'SELESAI'])->default('PENDING')->index();
            $table->string('file_permohonan')->nullable(); // Path berkas syarat pengajuan
            $table->string('file_hasil_sk')->nullable(); // Path berkas SK / Surat Izin terbit
            $table->text('catatan_petugas')->nullable();
            $table->string('petugas_nama')->default('Petugas Kepegawaian & Umum');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simandat_kepegawaian_layanan');
    }
};
