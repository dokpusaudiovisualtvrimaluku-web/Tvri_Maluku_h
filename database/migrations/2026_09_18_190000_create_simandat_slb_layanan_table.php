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
        Schema::create('simandat_slb_layanan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_registrasi')->unique();
            $table->string('jenis_layanan'); // 4 Layanan: Rekomendasi Pendirian SLB, Rekomendasi Mutasi Siswa SLB, BOSP SLB, Legalisir Ijazah/STTB & SKL SLB
            $table->string('nama_pemohon'); // Nama Pemohon (Kepala Sekolah, Orang Tua/Wali, Guru, Yayasan)
            $table->string('nomor_identitas')->nullable(); // NIK / NIP / NISN
            $table->string('nama_satuan_pendidikan'); // Nama SLB / Unit Kerja
            $table->string('npsn_sekolah')->nullable();
            $table->string('kabupaten_kota');
            $table->string('no_hp_whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_selesai')->nullable();
            $table->string('nomor_surat_rekomendasi')->nullable(); // Nomor Surat Rekomendasi / SK resmi
            $table->text('perihal_uraian');
            $table->string('status_layanan')->default('PROSES_VERIFIKASI'); // DRAFT, PROSES_VERIFIKASI, DISETUJUI, DITOLAK, SELESAI
            $table->string('file_persyaratan')->nullable();
            $table->string('file_nama_persyaratan')->nullable();
            $table->string('file_hasil_rekomendasi')->nullable();
            $table->string('file_nama_hasil')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->text('catatan_verifikator')->nullable();
            $table->string('petugas_verifikator')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['jenis_layanan', 'status_layanan', 'kabupaten_kota'], 'idx_slb_filter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simandat_slb_layanan');
    }
};
