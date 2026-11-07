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
        Schema::create('tokohs', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique();
            $table->string('nama_lengkap');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->foreignId('agama_id')->nullable()->constrained('agamas')->onDelete('set null');
            $table->foreignId('suku_id')->nullable()->constrained('sukus')->onDelete('set null');
            $table->text('alamat');
            $table->string('rt_rw', 10)->nullable();
            $table->foreignId('desa_id')->nullable()->constrained('desas')->onDelete('set null');
            $table->foreignId('kecamatan_id')->nullable()->constrained('kecamatans')->onDelete('set null');
            $table->foreignId('kabupaten_id')->nullable()->constrained('kabupatens')->onDelete('set null');
            $table->foreignId('provinsi_id')->nullable()->constrained('provinsis')->onDelete('set null');
            $table->string('no_hp', 20)->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->string('email')->nullable();
            $table->foreignId('profesi_id')->nullable()->constrained('profesis')->onDelete('set null');
            $table->foreignId('pendidikan_id')->nullable()->constrained('pendidikans')->onDelete('set null');
            $table->string('pekerjaan')->nullable();
            $table->foreignId('organisasi_id')->nullable()->constrained('organisasis')->onDelete('set null');
            $table->string('jabatan')->nullable();
            $table->foreignId('kategori_tokoh_id')->nullable()->constrained('kategori_tokohs')->onDelete('set null');
            $table->boolean('status_aktif')->default(true);
            $table->date('tanggal_pendataan');
            $table->foreignId('pendata_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('foto')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status_verifikasi', ['Draft', 'Menunggu Verifikasi', 'Disetujui', 'Ditolak'])->default('Draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tokohs');
    }
};
