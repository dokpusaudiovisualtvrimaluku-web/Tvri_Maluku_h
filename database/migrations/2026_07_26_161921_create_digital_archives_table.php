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
        Schema::create('digital_archives', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type');
            $table->string('category');
            $table->string('region')->nullable();
            $table->integer('year');
            $table->string('file_path');
            $table->string('tags')->nullable();
            $table->integer('version')->default(1);
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_archives');
    }
};
