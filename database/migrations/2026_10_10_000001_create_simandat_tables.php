<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->integer('dataset_count')->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('urusans', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('datasets', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('slug');
            $table->string('title');
            $table->string('organization_id');
            $table->integer('urusan_id');
            $table->string('date');
            $table->integer('views')->default(0);
            $table->integer('downloads')->default(0);
            $table->text('description');
            $table->json('tags');
            $table->json('columns');
            $table->json('rows');
            $table->json('chart_config')->nullable();
            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->foreign('urusan_id')->references('id')->on('urusans');
        });

        Schema::create('disabilitas_dewasa', function (Blueprint $table) {
            $table->id();
            $table->string('disabilitas');
            $table->integer('pria');
            $table->integer('wanita');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('disabilitas_anak', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color');
            $table->integer('usia_0_4');
            $table->integer('usia_5_9');
            $table->integer('usia_10_14');
            $table->integer('usia_15_17');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('marquee_announcements', function (Blueprint $table) {
            $table->id();
            $table->text('announcement');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marquee_announcements');
        Schema::dropIfExists('disabilitas_anak');
        Schema::dropIfExists('disabilitas_dewasa');
        Schema::dropIfExists('datasets');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('urusans');
        Schema::dropIfExists('organizations');
    }
};
