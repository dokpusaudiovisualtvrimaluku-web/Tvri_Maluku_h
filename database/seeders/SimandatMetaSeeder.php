<?php

namespace Database\Seeders;

use App\Models\MarqueeAnnouncement;
use App\Models\Organization;
use App\Models\Tag;
use App\Models\Urusan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SimandatMetaSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Organization::truncate();
        Urusan::truncate();
        Tag::truncate();
        MarqueeAnnouncement::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $orgData = [
            'id' => 'dinas-pendidikan-kebudayaan',
            'name' => 'TVRI Maluku',
            'description' => 'Integrated Digital Operations Platform yang mencakup Tata Usaha, berita, program, konten media baru, Promo, Umum, teknik, Teknik transmisi, Pengembagan Usaha, dan Keuangan.',
            'phone' => '(0911) 341851',
            'address' => 'Jl. Pemancar TVRI, Gunung Nona, Benteng, Kec. Nusaniwe, Kota Ambon, Maluku 97117',
            'website' => 'maluku.tvri.go.id',
            'email' => 'maluku@tvri.go.id',
            'logo' => '/images/logo-tvri.png',
            'dataset_count' => 10,
            'sort_order' => 0,
        ];
        Organization::create($orgData);

        // Also create alias tvri-maluku
        $orgData['id'] = 'tvri-maluku';
        Organization::create($orgData);

        $opds = [
            ['tata-usaha', 'Tata Usaha TVRI Maluku', 1],
            ['berita', 'Bidang Pemberitaan (Newsroom)', 1],
            ['program', 'Bidang Program & Siaran', 1],
            ['konten-media-baru', 'Konten Media Baru & Digital', 1],
            ['promo', 'Promosi & Citra Lembaga', 1],
            ['umum', 'Subbagian Umum & Sarpras', 1],
            ['teknik', 'Bidang Teknik Studio & Produksi', 1],
            ['teknik-transmisi', 'Teknik Transmisi & Jaringan Digital', 1],
            ['pengembangan-usaha', 'Pengembangan Usaha & Kerja Sama', 1],
            ['keuangan', 'Pengelolaan Keuangan & PNBP', 1],
        ];
        foreach ($opds as $i => [$id, $name, $count]) {
            Organization::create([
                'id' => $id,
                'name' => $name,
                'dataset_count' => $count,
                'sort_order' => $i + 1,
            ]);
        }

        $urusans = [
            1 => 'Tata Usaha',
            2 => 'Berita',
            3 => 'Program',
            4 => 'Konten Media Baru',
            5 => 'Promo',
            6 => 'Umum',
            7 => 'Teknik',
            8 => 'Teknik Transmisi',
            9 => 'Pengembangan Usaha',
            10 => 'Keuangan',
        ];
        $order = 0;
        foreach ($urusans as $id => $name) {
            Urusan::create(['id' => $id, 'name' => $name, 'sort_order' => $order++]);
        }

        $tags = [
            'tata-usaha',
            'berita',
            'program',
            'konten-media-baru',
            'promo',
            'umum',
            'teknik',
            'transmisi',
            'pengembangan-usaha',
            'keuangan',
            'penyiaran',
            'tvri',
            'digital',
            'dvb-t2',
        ];
        foreach ($tags as $i => $tag) {
            Tag::create(['name' => $tag, 'sort_order' => $i]);
        }

        $announcements = [
            'SELAMAT DATANG DI PUSAT DATA TVRI MALUKU • PUSAT INFORMASI DAN MONITORING DATA TERINTEGRASI • MENYAJIKAN LAPORAN DATA SECARA DIGITAL, TERPADU, AKURAT, DAN REAL-TIME • SATU DATA, SATU SISTEM, SATU LAPORAN DIGITAL UNTUK MENDUKUNG KINERJA TVRI MALUKU • TVRI MALUKU — TERDEPAN DALAM INFORMASI, TERINTEGRASI DALAM DATA',
            'SELAMAT DATANG DI PUSAT DATA TVRI MALUKU • PUSAT INFORMASI DAN MONITORING DATA TERINTEGRASI • MENYAJIKAN LAPORAN DATA SECARA DIGITAL, TERPADU, AKURAT, DAN REAL-TIME • SATU DATA, SATU SISTEM, SATU LAPORAN DIGITAL UNTUK MENDUKUNG KINERJA TVRI MALUKU • TVRI MALUKU — TERDEPAN DALAM INFORMASI, TERINTEGRASI DALAM DATA',
        ];
        foreach ($announcements as $i => $text) {
            MarqueeAnnouncement::create(['announcement' => $text, 'sort_order' => $i]);
        }
    }
}
