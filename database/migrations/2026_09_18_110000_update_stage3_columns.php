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
        // 1. Schools
        Schema::table('simandat_schools', function (Blueprint $table) {
            if (!Schema::hasColumn('simandat_schools', 'nama_sekolah')) {
                $table->string('nama_sekolah')->nullable()->after('npsn');
            }
            if (!Schema::hasColumn('simandat_schools', 'jumlah_siswa')) {
                $table->integer('jumlah_siswa')->default(0)->after('jumlah_guru');
            }
        });

        // 2. Students
        Schema::table('simandat_students', function (Blueprint $table) {
            if (!Schema::hasColumn('simandat_students', 'nik')) {
                $table->string('nik')->nullable()->after('nama');
            }
            if (!Schema::hasColumn('simandat_students', 'tingkat_kelas')) {
                $table->string('tingkat_kelas')->nullable()->after('jenjang');
            }
            if (!Schema::hasColumn('simandat_students', 'tempat_lahir')) {
                $table->string('tempat_lahir')->nullable()->after('tingkat_kelas');
            }
            if (!Schema::hasColumn('simandat_students', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            }
            if (!Schema::hasColumn('simandat_students', 'nama_ibu_kandung')) {
                $table->string('nama_ibu_kandung')->nullable()->after('tanggal_lahir');
            }
        });

        // 3. Teachers
        Schema::table('simandat_teachers', function (Blueprint $table) {
            if (!Schema::hasColumn('simandat_teachers', 'nuptk')) {
                $table->string('nuptk')->nullable()->after('nip');
            }
            if (!Schema::hasColumn('simandat_teachers', 'mata_pelajaran')) {
                $table->string('mata_pelajaran')->nullable()->after('jabatan');
            }
        });

        // 4. Sarpras
        Schema::table('simandat_sarpras', function (Blueprint $table) {
            if (!Schema::hasColumn('simandat_sarpras', 'nama_sarpras')) {
                $table->string('nama_sarpras')->nullable()->after('school_id');
            }
            if (!Schema::hasColumn('simandat_sarpras', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('rusak_berat');
            }
            if (!Schema::hasColumn('simandat_sarpras', 'kabupaten_kota')) {
                $table->string('kabupaten_kota')->nullable()->index()->after('keterangan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safe to leave
    }
};
