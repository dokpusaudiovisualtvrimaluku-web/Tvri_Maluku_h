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
        Schema::create('hybrid_broadcasts', function (Blueprint $table) {
            $table->id();
            $table->string('platform');
            $table->string('title');
            $table->string('thumbnail_path')->nullable();
            $table->text('caption')->nullable();
            $table->string('hashtags')->nullable();
            $table->dateTime('publish_schedule')->nullable();
            $table->string('status');
            $table->integer('views')->default(0);
            $table->integer('likes')->default(0);
            $table->integer('shares')->default(0);
            $table->integer('comments')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hybrid_broadcasts');
    }
};
