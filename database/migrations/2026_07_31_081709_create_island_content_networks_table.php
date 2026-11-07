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
        Schema::create('island_content_networks', function (Blueprint $table) {
            $table->id();
            $table->string('kategori_submenu'); // 'mitra', 'cloud', 'kurasi'
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('file_video')->nullable();
            $table->string('file_pdf')->nullable();
            $table->string('tautan_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('island_content_networks');
    }
};
