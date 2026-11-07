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
        // 1. Update simandat_schools table if columns don't exist
        Schema::table('simandat_schools', function (Blueprint $table) {
            if (!Schema::hasColumn('simandat_schools', 'status_sekolah')) {
                $table->string('status_sekolah')->default('Negeri')->after('jenjang');
            }
            if (!Schema::hasColumn('simandat_schools', 'alamat')) {
                $table->string('alamat')->nullable()->after('status_sekolah');
            }
            if (!Schema::hasColumn('simandat_schools', 'desa_kelurahan')) {
                $table->string('desa_kelurahan')->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('simandat_schools', 'kecamatan')) {
                $table->string('kecamatan')->nullable()->after('desa_kelurahan');
            }
            if (!Schema::hasColumn('simandat_schools', 'kabupaten_kota')) {
                $table->string('kabupaten_kota')->nullable()->index()->after('kecamatan');
            }
            if (!Schema::hasColumn('simandat_schools', 'kepala_sekolah')) {
                $table->string('kepala_sekolah')->nullable()->after('kabupaten_kota');
            }
            if (!Schema::hasColumn('simandat_schools', 'jumlah_guru')) {
                $table->integer('jumlah_guru')->default(0)->after('kepala_sekolah');
            }
            if (!Schema::hasColumn('simandat_schools', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('akreditasi');
            }
            if (!Schema::hasColumn('simandat_schools', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }
            if (!Schema::hasColumn('simandat_schools', 'tahun_data')) {
                $table->string('tahun_data', 10)->default('2026')->index()->after('longitude');
            }
            if (!Schema::hasColumn('simandat_schools', 'status_validasi')) {
                $table->enum('status_validasi', ['DRAFT', 'SUBMITTED', 'VERIFIED', 'VALIDATED', 'REJECTED'])->default('VALIDATED')->index()->after('tahun_data');
            }
        });

        // 2. Create Students table
        if (!Schema::hasTable('simandat_students')) {
            Schema::create('simandat_students', function (Blueprint $table) {
                $table->id();
                $table->string('nisn')->index();
                $table->string('nama');
                $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
                $table->foreignId('school_id')->nullable()->constrained('simandat_schools')->nullOnDelete();
                $table->string('jenjang')->default('SMA');
                $table->string('kabupaten_kota')->nullable()->index();
                $table->string('tahun_ajaran', 20)->default('2025/2026')->index();
                $table->enum('status', ['Aktif', 'Lulus', 'Pindah', 'Drop Out'])->default('Aktif')->index();
                $table->enum('status_validasi', ['DRAFT', 'SUBMITTED', 'VERIFIED', 'VALIDATED', 'REJECTED'])->default('VALIDATED')->index();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        // 3. Create Teachers table
        if (!Schema::hasTable('simandat_teachers')) {
            Schema::create('simandat_teachers', function (Blueprint $table) {
                $table->id();
                $table->string('nip')->nullable()->index();
                $table->string('nama');
                $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
                $table->string('jabatan')->default('Guru Mata Pelajaran');
                $table->string('pangkat_golongan')->nullable();
                $table->enum('status_kepegawaian', ['PNS', 'PPPK', 'Honorer / GTT'])->default('PNS')->index();
                $table->foreignId('school_id')->nullable()->constrained('simandat_schools')->nullOnDelete();
                $table->string('pendidikan_terakhir')->default('S1');
                $table->enum('sertifikasi', ['Sudah Sertifikasi', 'Belum Sertifikasi'])->default('Sudah Sertifikasi')->index();
                $table->string('kabupaten_kota')->nullable()->index();
                $table->string('tahun_data', 10)->default('2026')->index();
                $table->enum('status_validasi', ['DRAFT', 'SUBMITTED', 'VERIFIED', 'VALIDATED', 'REJECTED'])->default('VALIDATED')->index();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        // 4. Create Sarpras table
        if (!Schema::hasTable('simandat_sarpras')) {
            Schema::create('simandat_sarpras', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->constrained('simandat_schools')->cascadeOnDelete();
                $table->string('jenis_sarpras')->index(); // Ruang Kelas, Laboratorium, Perpustakaan, Sanitasi, Lapangan, Ruang Guru, Ruang Kepala Sekolah, Bangunan
                $table->integer('jumlah')->default(1);
                $table->integer('kondisi_baik')->default(1);
                $table->integer('rusak_ringan')->default(0);
                $table->integer('rusak_berat')->default(0);
                $table->string('tahun_data', 10)->default('2026')->index();
                $table->enum('status_validasi', ['DRAFT', 'SUBMITTED', 'VERIFIED', 'VALIDATED', 'REJECTED'])->default('VALIDATED')->index();
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simandat_sarpras');
        Schema::dropIfExists('simandat_teachers');
        Schema::dropIfExists('simandat_students');
    }
};
