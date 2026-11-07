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
        // 1. Tabel Folder Hierarkis Dokumen
        Schema::create('simandat_document_folders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('simandat_document_folders')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->nullable()->index();
            $table->string('icon')->default('folder');
            $table->string('color')->default('blue');
            $table->text('description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // 2. Tabel Dokumen & Arsip Digital Utama
        Schema::create('simandat_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('folder_id')->nullable()->constrained('simandat_document_folders')->onDelete('set null');
            $table->string('document_number')->nullable()->index(); // Nomor SK / Surat / Dokumen
            $table->string('title')->index();
            $table->string('category')->default('Lainnya')->index(); // SK, Surat Edaran, Laporan Kinerja, dll.
            $table->string('division')->default('Sekretariat')->index(); // Bidang / Subbag
            $table->text('description')->nullable();
            $table->date('document_date')->nullable();
            $table->integer('year')->nullable()->index();
            $table->enum('confidentiality', ['PUBLIK', 'INTERNAL', 'TERBATAS', 'RAHASIA'])->default('INTERNAL')->index();
            $table->enum('status', ['AKTIF', 'INAKTIF', 'RETENSI', 'DIARSIPKAN'])->default('AKTIF')->index();
            $table->string('current_version')->default('v1.0');
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_type', 20)->default('PDF'); // PDF, DOCX, XLSX, PPTX, JPG, PNG, MP4, MP3, ZIP
            $table->string('file_size', 50)->default('0 KB');
            $table->foreignId('school_id')->nullable()->constrained('simandat_schools')->onDelete('set null');
            $table->foreignId('culture_id')->nullable()->constrained('simandat_cultures')->onDelete('set null');
            $table->text('tags')->nullable();
            $table->unsignedInteger('download_count')->default(0);
            $table->unsignedInteger('view_count')->default(0);
            $table->string('qr_token', 64)->unique();
            $table->foreignId('uploader_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('uploader_name')->default('Admin Disdikbud');
            $table->softDeletes();
            $table->timestamps();
        });

        // 3. Tabel Riwayat Versi Dokumen (Versioning)
        Schema::create('simandat_document_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('simandat_documents')->onDelete('cascade');
            $table->string('version_number', 20); // v1.0, v1.1, v2.0
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type', 20)->default('PDF');
            $table->string('file_size', 50)->default('0 KB');
            $table->text('changelog_note')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('uploader_name')->default('Admin Disdikbud');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simandat_document_versions');
        Schema::dropIfExists('simandat_documents');
        Schema::dropIfExists('simandat_document_folders');
    }
};
