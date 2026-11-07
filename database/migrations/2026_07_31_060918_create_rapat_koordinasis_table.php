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
        Schema::create('rapat_koordinasis', function (Blueprint $table) {
            $table->id();
            $table->dateTime('jadwal_rapat');
            $table->string('agenda');
            $table->text('notulen')->nullable();
            $table->string('file_materi')->nullable();
            $table->text('keputusan')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapat_koordinasis');
    }
};
