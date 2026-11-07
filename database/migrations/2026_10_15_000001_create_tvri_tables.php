<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // === TATA USAHA ===
        Schema::create('tvri_surat_masuks', function (Blueprint $t) {
            $t->id();
            $t->date('tanggal')->nullable();
            $t->string('nomor')->nullable();
            $t->string('pengirim')->nullable();
            $t->text('perihal')->nullable();
            $t->string('kategori')->nullable();
            $t->string('status')->nullable();
            $t->timestamps();
        });

        Schema::create('tvri_surat_keluars', function (Blueprint $t) {
            $t->id();
            $t->date('tanggal')->nullable();
            $t->string('nomor')->nullable();
            $t->string('tujuan')->nullable();
            $t->text('perihal')->nullable();
            $t->string('status')->nullable();
            $t->timestamps();
        });

        Schema::create('tvri_layanan_adms', function (Blueprint $t) {
            $t->id();
            $t->string('jenis')->nullable();
            $t->integer('jumlah')->default(0);
            $t->string('keterangan')->nullable();
            $t->timestamps();
        });

        Schema::create('tvri_agenda_kegiatans', function (Blueprint $t) {
            $t->id();
            $t->date('tanggal')->nullable();
            $t->string('waktu')->nullable();
            $t->string('kegiatan')->nullable();
            $t->string('lokasi')->nullable();
            $t->string('pic')->nullable();
            $t->string('jenis')->nullable();
            $t->string('status')->nullable();
            $t->timestamps();
        });

        // === UMUM & KEPEGAWAIAN ===
        Schema::create('tvri_asets', function (Blueprint $t) {
            $t->id();
            $t->string('kode')->nullable();
            $t->string('nama')->nullable();
            $t->string('kategori')->nullable();
            $t->string('kondisi')->nullable();
            $t->integer('jumlah')->default(0);
            $t->string('lokasi')->nullable();
            $t->smallInteger('tahun')->nullable();
            $t->decimal('nilai', 18, 2)->default(0);
            $t->timestamps();
        });

        Schema::create('tvri_kendaraans', function (Blueprint $t) {
            $t->id();
            $t->string('jenis')->nullable();
            $t->string('nama')->nullable();
            $t->string('plat_nomor')->nullable();
            $t->string('status')->nullable();
            $t->timestamps();
        });

        Schema::create('tvri_sarpras', function (Blueprint $t) {
            $t->id();
            $t->string('nama')->nullable();
            $t->string('kategori')->nullable();
            $t->integer('jumlah_unit')->default(0);
            $t->string('kondisi_baik')->nullable();
            $t->string('lokasi')->nullable();
            $t->timestamps();
        });

        Schema::create('tvri_permintaan_layanans', function (Blueprint $t) {
            $t->id();
            $t->string('nomor')->nullable();
            $t->string('jenis')->nullable();
            $t->string('pemohon')->nullable();
            $t->string('unit')->nullable();
            $t->string('status')->nullable();
            $t->date('tanggal')->nullable();
            $t->timestamps();
        });

        Schema::create('tvri_pemeliharaans', function (Blueprint $t) {
            $t->id();
            $t->string('bidang')->nullable();
            $t->date('tanggal')->nullable();
            $t->string('objek')->nullable();
            $t->string('jenis')->nullable();
            $t->string('pic')->nullable();
            $t->string('status')->nullable();
            $t->timestamps();
        });

        Schema::create('tvri_stok_barangs', function (Blueprint $t) {
            $t->id();
            $t->string('kategori')->nullable();
            $t->string('nama')->nullable();
            $t->integer('jumlah')->default(0);
            $t->string('satuan')->nullable();
            $t->string('status_stok')->nullable();
            $t->timestamps();
        });

        // === TRANSMISI & TEKNIK ===
        Schema::create('tvri_stasiun_pemancars', function (Blueprint $t) {
            $t->id();
            $t->string('nama')->nullable();
            $t->string('lokasi')->nullable();
            $t->string('frekuensi')->nullable();
            $t->string('kanal')->nullable();
            $t->decimal('daya_kw', 10, 2)->default(0);
            $t->string('coverage')->nullable();
            $t->string('status')->nullable();
            $t->decimal('kualitas', 5, 2)->nullable();
            $t->decimal('latitude', 10, 6)->nullable();
            $t->decimal('longitude', 10, 6)->nullable();
            $t->timestamps();
        });

        Schema::create('tvri_gangguans', function (Blueprint $t) {
            $t->id();
            $t->date('tanggal')->nullable();
            $t->string('site')->nullable();
            $t->string('jenis')->nullable();
            $t->string('status')->nullable();
            $t->text('keterangan')->nullable();
            $t->timestamps();
        });

        Schema::create('tvri_insiden_tekniks', function (Blueprint $t) {
            $t->id();
            $t->date('tanggal')->nullable();
            $t->string('lokasi')->nullable();
            $t->text('deskripsi')->nullable();
            $t->string('status')->nullable();
            $t->timestamps();
        });

        Schema::create('tvri_peralatans', function (Blueprint $t) {
            $t->id();
            $t->string('nama')->nullable();
            $t->string('kategori')->nullable();
            $t->integer('jumlah')->default(0);
            $t->integer('jumlah_baik')->default(0);
            $t->string('lokasi')->nullable();
            $t->timestamps();
        });

        // === KEUANGAN & USAHA ===
        Schema::create('tvri_anggarans', function (Blueprint $t) {
            $t->id();
            $t->string('kode')->nullable();
            $t->string('uraian')->nullable();
            $t->decimal('pagu', 18, 2)->default(0);
            $t->decimal('realisasi', 18, 2)->default(0);
            $t->smallInteger('tahun')->nullable();
            $t->timestamps();
        });

        Schema::create('tvri_pnbps', function (Blueprint $t) {
            $t->id();
            $t->string('jenis')->nullable();
            $t->decimal('target', 18, 2)->default(0);
            $t->decimal('realisasi', 18, 2)->default(0);
            $t->smallInteger('tahun')->nullable();
            $t->timestamps();
        });

        Schema::create('tvri_pendapatans', function (Blueprint $t) {
            $t->id();
            $t->string('sumber')->nullable();
            $t->string('bulan')->nullable();
            $t->smallInteger('tahun')->nullable();
            $t->decimal('target', 18, 2)->default(0);
            $t->decimal('realisasi', 18, 2)->default(0);
            $t->timestamps();
        });

        Schema::create('tvri_piutangs', function (Blueprint $t) {
            $t->id();
            $t->string('klien')->nullable();
            $t->decimal('nilai', 18, 2)->default(0);
            $t->date('jatuh_tempo')->nullable();
            $t->string('status')->nullable();
            $t->timestamps();
        });

        Schema::create('tvri_mitras', function (Blueprint $t) {
            $t->id();
            $t->string('nama')->nullable();
            $t->string('jenis_kerja_sama')->nullable();
            $t->decimal('nilai_kontrak', 18, 2)->default(0);
            $t->string('status')->nullable();
            $t->string('lokasi')->nullable();
            $t->timestamps();
        });

        Schema::create('tvri_kegiatan_usahas', function (Blueprint $t) {
            $t->id();
            $t->date('tanggal')->nullable();
            $t->string('kegiatan')->nullable();
            $t->string('jenis')->nullable();
            $t->decimal('estimasi_nilai', 18, 2)->default(0);
            $t->string('status')->nullable();
            $t->timestamps();
        });

        // === PROGRAM, BERITA, KONTEN, PROMO ===
        Schema::create('tvri_programs', function (Blueprint $t) {
            $t->id();
            $t->string('judul')->nullable();
            $t->string('kategori')->nullable();
            $t->string('tahap')->nullable();
            $t->date('target_tayang')->nullable();
            $t->string('status')->nullable();
            $t->string('pic')->nullable();
            $t->integer('audience')->default(0);
            $t->decimal('rating', 6, 2)->nullable();
            $t->timestamps();
        });

        Schema::create('tvri_beritas', function (Blueprint $t) {
            $t->id();
            $t->string('judul')->nullable();
            $t->string('kategori')->nullable();
            $t->string('jenis')->nullable();
            $t->string('lokasi')->nullable();
            $t->string('status')->nullable();
            $t->string('reporter')->nullable();
            $t->date('tanggal_rencana')->nullable();
            $t->date('tanggal_tayang')->nullable();
            $t->integer('views')->default(0);
            $t->timestamps();
        });

        Schema::create('tvri_kontens', function (Blueprint $t) {
            $t->id();
            $t->string('judul')->nullable();
            $t->string('platform')->nullable();
            $t->string('kategori')->nullable();
            $t->integer('views')->default(0);
            $t->integer('reach')->default(0);
            $t->integer('engagement')->default(0);
            $t->decimal('watch_time', 12, 2)->default(0);
            $t->date('tanggal')->nullable();
            $t->string('status')->nullable();
            $t->timestamps();
        });

        Schema::create('tvri_materi_promos', function (Blueprint $t) {
            $t->id();
            $t->string('nama')->nullable();
            $t->string('jenis')->nullable();
            $t->integer('durasi_detik')->default(0);
            $t->string('status')->nullable();
            $t->timestamps();
        });

        Schema::create('tvri_kampanyes', function (Blueprint $t) {
            $t->id();
            $t->string('nama')->nullable();
            $t->string('media')->nullable();
            $t->decimal('target', 10, 2)->nullable();
            $t->decimal('realisasi', 10, 2)->nullable();
            $t->integer('reach')->default(0);
            $t->integer('engagement')->default(0);
            $t->string('status')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        $tables = [
            'tvri_kampanyes', 'tvri_materi_promos', 'tvri_kontens', 'tvri_beritas', 'tvri_programs',
            'tvri_kegiatan_usahas', 'tvri_mitras', 'tvri_piutangs', 'tvri_pendapatans', 'tvri_pnbps',
            'tvri_anggarans', 'tvri_peralatans', 'tvri_insiden_tekniks', 'tvri_gangguans',
            'tvri_stasiun_pemancars', 'tvri_stok_barangs', 'tvri_pemeliharaans', 'tvri_permintaan_layanans',
            'tvri_sarpras', 'tvri_kendaraans', 'tvri_asets', 'tvri_agenda_kegiatans',
            'tvri_layanan_adms', 'tvri_surat_keluars', 'tvri_surat_masuks',
        ];
        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
};
