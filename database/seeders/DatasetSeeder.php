<?php

namespace Database\Seeders;

use App\Models\Dataset;
use App\Models\Organization;
use App\Models\Urusan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatasetSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan Organisasi Utama TVRI Maluku
        $opd = Organization::firstOrCreate(
            ['id' => 'tvri-maluku'],
            [
                'name' => 'TVRI Maluku',
                'description' => 'TVRI Maluku menyelenggarakan kegiatan penyiaran publik terintegrasi.',
                'phone' => '(0911) 341851',
                'address' => 'Jl. Perumtel Gunung Nona Ambon Kec. Nusaniwe, Kota Ambon, Maluku 97124',
                'website' => 'https://ppid.tvrimaluku.id',
                'email' => 'humas@tvri.go.id',
                'logo' => '/images/logo-tvri.png',
                'dataset_count' => 6,
                'sort_order' => 1,
            ]
        );
        $opd->dataset_count = 6;
        $opd->save();

        Organization::updateOrCreate(
            ['id' => 'dinas-pendidikan-kebudayaan'],
            [
                'name' => 'TVRI Maluku',
                'description' => 'TVRI Maluku.',
                'phone' => '(0911) 341851',
                'address' => 'Jl. Perumtel Gunung Nona Ambon Kec. Nusaniwe, Kota Ambon, Maluku 97124',
                'website' => 'https://ppid.tvrimaluku.id',
                'email' => 'humas@tvri.go.id',
                'logo' => '/images/logo-tvri.png',
                'dataset_count' => 6,
                'sort_order' => 2,
            ]
        );

        // 2. Pastikan Urusan Terdaftar
        Urusan::firstOrCreate(['id' => 3], ['name' => 'Urusan Pemerintahan Bidang Pendidikan', 'sort_order' => 1]);
        Urusan::firstOrCreate(['id' => 23], ['name' => 'Urusan Pemerintahan Bidang Kebudayaan', 'sort_order' => 2]);
        Urusan::firstOrCreate(['id' => 1], ['name' => 'TVRI Maluku', 'sort_order' => 3]);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Dataset::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $datasets = json_decode(file_get_contents(database_path('seeders/data/datasets.json')), true);

        $allTags = collect($datasets)->flatMap(fn ($d) => $d['tags'] ?? [])->unique();
        $order = 1;
        foreach ($allTags as $tag) {
            \App\Models\Tag::firstOrCreate(['name' => $tag], ['sort_order' => $order++]);
        }

        foreach ($datasets as $d) {
            Dataset::create([
                'id' => $d['id'],
                'slug' => $d['slug'],
                'title' => $d['title'],
                'organization_id' => 'tvri-maluku',
                'urusan_id' => $d['urusanId'] ?? 3,
                'date' => $d['date'],
                'views' => $d['views'] ?? 100,
                'downloads' => $d['downloads'] ?? 50,
                'description' => $d['description'],
                'tags' => $d['tags'] ?? [],
                'columns' => $d['columns'] ?? [],
                'rows' => $d['rows'] ?? [],
                'chart_config' => $d['chartConfig'] ?? null,
            ]);
        }

        $this->command?->info("Katalog Data Operasional TVRI Maluku (" . count($datasets) . " dataset) berhasil di-seed ke database.");
    }
}
