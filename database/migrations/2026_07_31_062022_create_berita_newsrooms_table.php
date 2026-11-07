<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('berita_newsrooms', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('reporter')->nullable();
            $table->string('cameraman')->nullable();
            $table->string('editor')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('durasi')->nullable();
            $table->string('status')->default('Draft'); // Draft, Editing, Siap Tayang, Sudah Tayang
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('berita_newsrooms'); }
};
