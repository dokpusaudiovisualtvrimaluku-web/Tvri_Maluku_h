<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('social_media_publishes', function (Blueprint $table) {
            $table->id();
            $table->string('platform'); // IG, TikTok, YouTube, Facebook
            $table->string('caption')->nullable();
            $table->string('hashtag')->nullable();
            $table->dateTime('jadwal_publish')->nullable();
            $table->string('status')->default('Scheduled');
            $table->integer('viewer')->default(0);
            $table->integer('likes')->default(0);
            $table->integer('share')->default(0);
            $table->integer('comment')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('social_media_publishes'); }
};
