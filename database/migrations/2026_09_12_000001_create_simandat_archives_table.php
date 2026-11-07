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
        Schema::create('simandat_archives', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable()->index();
            $table->string('title');
            $table->string('module_id')->index();
            $table->string('category')->nullable();
            $table->string('file_name');
            $table->string('file_path')->nullable();
            $table->string('format')->default('PDF');
            $table->string('file_size')->default('2.4 MB');
            $table->string('day')->nullable();
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->string('uploader')->default('Super Admin (Disdikbud)');
            $table->string('status')->default('VALIDATED');
            $table->boolean('is_protected')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simandat_archives');
    }
};
