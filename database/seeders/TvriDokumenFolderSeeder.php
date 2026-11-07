<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SimandatDocumentFolder;
use App\Models\SimandatDocument;
use Illuminate\Support\Facades\DB;

class TvriDokumenFolderSeeder extends Seeder
{
    public function run(): void
    {
        $folders = [
            [
                'id' => 1,
                'name' => 'SOP',
                'code' => 'FLD-SOP',
                'icon' => 'folder',
                'color' => 'rose',
                'description' => 'Standar Operasional Prosedur & Regulasi TVRI Maluku',
                'parent_id' => null,
            ],
            [
                'id' => 2,
                'name' => 'Tata Usaha',
                'code' => 'FLD-TU',
                'icon' => 'folder',
                'color' => 'blue',
                'description' => 'Arsip & Dokumen Administrasi Tata Usaha',
                'parent_id' => null,
            ],
            [
                'id' => 3,
                'name' => 'Berita',
                'code' => 'FLD-BERITA',
                'icon' => 'folder',
                'color' => 'red',
                'description' => 'Arsip & Naskah Liputan Berita TVRI Maluku',
                'parent_id' => null,
            ],
            [
                'id' => 4,
                'name' => 'Umum',
                'code' => 'FLD-UMUM',
                'icon' => 'folder',
                'color' => 'emerald',
                'description' => 'Dokumen Kerumahtanggaan, Logistik & Sarana Prasarana',
                'parent_id' => null,
            ],
            [
                'id' => 5,
                'name' => 'Program',
                'code' => 'FLD-PROGRAM',
                'icon' => 'folder',
                'color' => 'purple',
                'description' => 'Dokumen Jadwal & Produksi Siaran Program TVRI Maluku',
                'parent_id' => null,
            ],
            [
                'id' => 6,
                'name' => 'KBM',
                'code' => 'FLD-KBM',
                'icon' => 'folder',
                'color' => 'pink',
                'description' => 'Dokumen Konten Media Baru, Multiplatform & Media Sosial',
                'parent_id' => null,
            ],
            [
                'id' => 7,
                'name' => 'promo',
                'code' => 'FLD-PROMO',
                'icon' => 'folder',
                'color' => 'indigo',
                'description' => 'Dokumen & Materi Promosi, Citra Stasiun & Kampanye Publik',
                'parent_id' => null,
            ],
            [
                'id' => 8,
                'name' => 'Pengembangan Usaha',
                'code' => 'FLD-USAHA',
                'icon' => 'folder',
                'color' => 'amber',
                'description' => 'Dokumen Kemitraan Usaha, Kontrak Iklan & Sponsorship (PNBP)',
                'parent_id' => null,
            ],
            [
                'id' => 9,
                'name' => 'Teknik',
                'code' => 'FLD-TEKNIK',
                'icon' => 'folder',
                'color' => 'cyan',
                'description' => 'Dokumen Pemeliharaan Fasilitas & Peralatan Teknik Studio',
                'parent_id' => null,
            ],
            [
                'id' => 10,
                'name' => 'Teknik Transmisi',
                'code' => 'FLD-TX',
                'icon' => 'folder',
                'color' => 'sky',
                'description' => 'Dokumen Pemeliharaan Satuan Transmisi & Pemancar TVRI Maluku',
                'parent_id' => null,
            ],
            [
                'id' => 11,
                'name' => 'Keuangan',
                'code' => 'FLD-KEU',
                'icon' => 'folder',
                'color' => 'teal',
                'description' => 'Dokumen DIPA, RKA, Pembukuan & Laporan Realisasi Keuangan',
                'parent_id' => null,
            ],
        ];

        // Matikan sementara foreign key check jika ada
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Hapus folder lama
        SimandatDocumentFolder::truncate();

        foreach ($folders as $f) {
            SimandatDocumentFolder::create($f);
        }

        // Hubungkan dokumen eksisting ke folder Keuangan (ID 11)
        SimandatDocument::whereIn('id', [1, 2, 3, 4])->update(['folder_id' => 11]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
