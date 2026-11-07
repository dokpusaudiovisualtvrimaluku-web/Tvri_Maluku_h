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
        Schema::create('newsroom_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('reporter_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('editor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('cameraman_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('duration')->nullable();
            $table->string('location')->nullable();
            $table->string('keywords')->nullable();
            $table->json('metadata')->nullable();
            $table->string('status');
            $table->string('file_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsroom_items');
    }
};
