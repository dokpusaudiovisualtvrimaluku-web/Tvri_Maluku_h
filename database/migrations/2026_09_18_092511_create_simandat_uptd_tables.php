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
        // 1. Tabel UPTD Balai Teknologi Komunikasi Pendidikan (BTKP)
        if (!Schema::hasTable('simandat_uptd_btkp')) {
            Schema::create('simandat_uptd_btkp', function (Blueprint $table) {
                $table->id();
                $table->string('kode')->unique();
                $table->string('nama_program');
                $table->string('penanggung_jawab')->nullable();
                $table->string('sasaran')->nullable();
                $table->string('platform')->default('SIWALIMA LMS / Cloud');
                $table->bigInteger('anggaran')->default(0);
                $table->string('status')->default('Aktif');
                $table->text('deskripsi')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        // 2. Tabel UPTD Taman Budaya Provinsi Maluku
        if (!Schema::hasTable('simandat_uptd_taman_budaya')) {
            Schema::create('simandat_uptd_taman_budaya', function (Blueprint $table) {
                $table->id();
                $table->string('kode')->unique();
                $table->string('nama_kegiatan');
                $table->string('jenis')->default('Festival & Pagelaran');
                $table->string('lokasi')->default('Panggung Terbuka Taman Budaya Karpan Ambon');
                $table->string('sanggar')->nullable();
                $table->integer('peserta')->default(0);
                $table->string('status')->default('Terjadwal');
                $table->text('deskripsi')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        // 3. Tabel UPTD Museum Siwa Lima Provinsi Maluku
        if (!Schema::hasTable('simandat_uptd_museum')) {
            Schema::create('simandat_uptd_museum', function (Blueprint $table) {
                $table->id();
                $table->string('no_inventaris')->unique();
                $table->string('nama_koleksi');
                $table->string('kategori')->default('Etnografika');
                $table->string('asal')->nullable();
                $table->string('bahan')->nullable();
                $table->string('kondisi')->default('Sangat Baik');
                $table->string('tahun_perolehan')->nullable();
                $table->string('lokasi_simpan')->default('Gedung A Ruang Pamer Utama');
                $table->text('deskripsi')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        // 4. Tambah kolom pendukung pangkalan data laporan di simandat_archives
        if (Schema::hasTable('simandat_archives')) {
            Schema::table('simandat_archives', function (Blueprint $table) {
                if (!Schema::hasColumn('simandat_archives', 'description')) {
                    $table->text('description')->nullable()->after('category');
                }
                if (!Schema::hasColumn('simandat_archives', 'budget')) {
                    $table->bigInteger('budget')->default(0)->after('description');
                }
                if (!Schema::hasColumn('simandat_archives', 'pic')) {
                    $table->string('pic')->nullable()->after('budget');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simandat_uptd_museum');
        Schema::dropIfExists('simandat_uptd_taman_budaya');
        Schema::dropIfExists('simandat_uptd_btkp');
    }
};
