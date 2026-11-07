<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('distribusi_kontens', function (Blueprint $table) {
            $table->id();
            $table->string('konten');
            $table->string('tujuan');
            $table->string('status')->default('Pending'); // Pending, Diproses, Dikirim, Diterima
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('distribusi_kontens'); }
};
