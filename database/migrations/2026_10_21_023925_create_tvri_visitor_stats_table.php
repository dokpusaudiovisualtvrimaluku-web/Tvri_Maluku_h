<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tvri_visitor_stats', function (Blueprint $table) {
            $table->id();
            $table->string('portal_key')->default('tvri_maluku_hub')->unique();
            $table->unsignedBigInteger('hari_ini_kunjungan')->default(248);
            $table->unsignedBigInteger('hari_ini_unik')->default(236);
            $table->unsignedBigInteger('bulan_ini_kunjungan')->default(5420);
            $table->unsignedBigInteger('bulan_ini_unik')->default(4890);
            $table->unsignedBigInteger('total_kunjungan')->default(168940);
            $table->unsignedBigInteger('total_unik')->default(142300);
            $table->date('last_visit_date')->nullable();
            $table->string('updated_by')->nullable()->default('Super Admin');
            $table->timestamps();
        });

        // Insert initial baseline record
        DB::table('tvri_visitor_stats')->insert([
            'portal_key' => 'tvri_maluku_hub',
            'hari_ini_kunjungan' => 248,
            'hari_ini_unik' => 236,
            'bulan_ini_kunjungan' => 5420,
            'bulan_ini_unik' => 4890,
            'total_kunjungan' => 168940,
            'total_unik' => 142300,
            'last_visit_date' => now()->toDateString(),
            'updated_by' => 'Super Admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tvri_visitor_stats');
    }
};
