<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SimandatDocumentFolder;
use App\Models\SimandatDocument;
use App\Models\SimandatDocumentVersion;
use App\Models\SimandatSchool;
use App\Models\SimandatCulture;
use App\Models\User;
use Illuminate\Support\Str;

class DokumenStage5Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();
        $adminId = $admin ? $admin->id : 1;
        $adminName = $admin ? $admin->name : 'Super Admin (Disdikbud)';

        // 1. Root Folders
        $fSekr = SimandatDocumentFolder::create([
            'name' => 'Sekretariat & Tata Usaha Dinas',
            'code' => 'FLD-SEKR',
            'icon' => 'folder',
            'color' => 'blue',
            'description' => 'Dokumen persuratan, kepegawaian, keuangan, dan aset perlengkapan dinas.',
            'created_by' => $adminId,
        ]);

        $fSmaSmk = SimandatDocumentFolder::create([
            'name' => 'Bidang Pembinaan SMA & SMK',
            'code' => 'FLD-SMA-SMK',
            'icon' => 'folder',
            'color' => 'emerald',
            'description' => 'Arsip kurikulum, sarana prasarana sekolah, kesiswaan dan kelembagaan.',
            'created_by' => $adminId,
        ]);

        $fGtk = SimandatDocumentFolder::create([
            'name' => 'Bidang Guru & Tenaga Kependidikan (GTK)',
            'code' => 'FLD-GTK',
            'icon' => 'folder',
            'color' => 'amber',
            'description' => 'Dokumen sertifikasi, tunjangan profesi, kenaikan pangkat, dan pelatihan guru.',
            'created_by' => $adminId,
        ]);

        $fBudaya = SimandatDocumentFolder::create([
            'name' => 'Bidang Pembinaan Kebudayaan',
            'code' => 'FLD-BUDAYA',
            'icon' => 'folder',
            'color' => 'purple',
            'description' => 'Naskah kajian cagar budaya, warisan budaya takbenda, seni tradisi dan festival.',
            'created_by' => $adminId,
        ]);

        $fRegulasi = SimandatDocumentFolder::create([
            'name' => 'Regulasi, SK & Produk Hukum',
            'code' => 'FLD-REGULASI',
            'icon' => 'folder',
            'color' => 'rose',
            'description' => 'Surat Keputusan Kepala Dinas/Gubernur, Surat Edaran, SOP dan Peraturan Daerah.',
            'created_by' => $adminId,
        ]);

        // Subfolders
        $fKeuangan = SimandatDocumentFolder::create([
            'parent_id' => $fSekr->id,
            'name' => 'Subbag Perencanaan & Keuangan (DPA/RKA)',
            'code' => 'FLD-SEKR-KEU',
            'icon' => 'folder',
            'color' => 'blue',
            'description' => 'Dokumen anggaran DPA, RKA, dan laporan realisasi keuangan tahunan.',
            'created_by' => $adminId,
        ]);

        $fCagarBudaya = SimandatDocumentFolder::create([
            'parent_id' => $fBudaya->id,
            'name' => 'Kajian Cagar Budaya & Permuseuman',
            'code' => 'FLD-BUD-CAGAR',
            'icon' => 'folder',
            'color' => 'purple',
            'description' => 'Dokumen penetapan dan registrasi cagar budaya kepulauan Maluku.',
            'created_by' => $adminId,
        ]);

        // Link with School and Culture if available
        $school1 = SimandatSchool::first();
        $culture1 = SimandatCulture::where('kategori', 'Cagar Budaya')->first();
        $culture2 = SimandatCulture::where('kategori', 'Warisan Budaya')->first();

        // 2. Documents Seed Data
        $docs = [
            [
                'folder_id' => $fRegulasi->id,
                'document_number' => 'SK.420/128/DISDIKBUD/2026',
                'title' => 'SK Penetapan Satuan Pendidikan Penerima Bantuan Operasional Sekolah (BOS) Kinerja Maluku 2026',
                'category' => 'Surat Keputusan (SK)',
                'division' => 'Sekretariat',
                'description' => 'Surat Keputusan Kepala Dinas Pendidikan dan Kebudayaan Provinsi Maluku mengenai alokasi dan verifikasi sekolah penerima BOS Kinerja jenjang SMA/SMK/SLB tahun anggaran 2026.',
                'document_date' => '2026-02-15',
                'year' => 2026,
                'confidentiality' => 'PUBLIK',
                'status' => 'AKTIF',
                'current_version' => 'v1.1',
                'file_name' => 'SK_Kepala_Dinas_BOS_Kinerja_Maluku_2026_Rev1.pdf',
                'file_type' => 'PDF',
                'file_size' => '3.4 MB',
                'school_id' => $school1 ? $school1->id : null,
                'tags' => 'BOS Kinerja, SK Kadis, Dana Sekolah, Alokasi 2026',
                'download_count' => 142,
                'view_count' => 520,
                'versions' => [
                    ['version_number' => 'v1.0', 'note' => 'Draf awal pengesahan SK BOS Kinerja 2026.', 'file_name' => 'SK_BOS_Kinerja_2026_draft.pdf', 'file_size' => '3.2 MB'],
                    ['version_number' => 'v1.1', 'note' => 'Revisi lampiran daftar sekolah penerima di Maluku Barat Daya dan Kepulauan Tanimbar.', 'file_name' => 'SK_Kepala_Dinas_BOS_Kinerja_Maluku_2026_Rev1.pdf', 'file_size' => '3.4 MB'],
                ],
            ],
            [
                'folder_id' => $fKeuangan->id,
                'document_number' => 'DPA/A.1/1.01.0.00.0.00.01.0000/2026',
                'title' => 'Dokumen Pelaksanaan Anggaran (DPA) Induk Disdikbud Provinsi Maluku TA 2026',
                'category' => 'RKA & DPA Perencanaan',
                'division' => 'Sekretariat',
                'description' => 'Dokumen resmi pagu anggaran, rincian program belanja operasional, belanja modal, dan program prioritas Satu Data Pendidikan Kebudayaan Maluku.',
                'document_date' => '2026-01-08',
                'year' => 2026,
                'confidentiality' => 'TERBATAS',
                'status' => 'AKTIF',
                'current_version' => 'v1.0',
                'file_name' => 'DPA_Induk_Dinas_Pendidikan_Kebudayaan_Maluku_2026.pdf',
                'file_type' => 'PDF',
                'file_size' => '6.8 MB',
                'tags' => 'DPA 2026, APBD, Belanja Modal, Anggaran Dinas',
                'download_count' => 45,
                'view_count' => 180,
                'versions' => [
                    ['version_number' => 'v1.0', 'note' => 'Pengesahan DPA Induk oleh BPKAD Provinsi Maluku.', 'file_name' => 'DPA_Induk_Dinas_Pendidikan_Kebudayaan_Maluku_2026.pdf', 'file_size' => '6.8 MB'],
                ],
            ],
            [
                'folder_id' => $fCagarBudaya->id,
                'document_number' => 'KAJIAN-WBTB/04/BUD-MAL/2026',
                'title' => 'Naskah Akademik & Rekomendasi Penetapan Tradisi Pela Gandong sebagai Warisan Budaya Takbenda Dunia UNESCO',
                'category' => 'Naskah Akademik & Kajian Budaya',
                'division' => 'Bidang Kebudayaan',
                'description' => 'Kajian komprehensif antropologi, sosiologi, dan nilai historis persaudaraan adat Pela Gandong di Kepulauan Maluku sebagai instrumen perdamaian lestari.',
                'document_date' => '2026-04-20',
                'year' => 2026,
                'confidentiality' => 'PUBLIK',
                'status' => 'AKTIF',
                'current_version' => 'v2.0',
                'file_name' => 'Naskah_Akademik_Kajian_Pela_Gandong_Maluku_Final_v2.docx',
                'file_type' => 'DOCX',
                'file_size' => '5.1 MB',
                'culture_id' => $culture2 ? $culture2->id : null,
                'tags' => 'Pela Gandong, WBTb, UNESCO, Budaya Maluku, Adat',
                'download_count' => 230,
                'view_count' => 780,
                'versions' => [
                    ['version_number' => 'v1.0', 'note' => 'Naskah kajian tim ahli cagar budaya tahap I.', 'file_name' => 'Naskah_Kajian_Pela_Gandong_v1.docx', 'file_size' => '4.2 MB'],
                    ['version_number' => 'v2.0', 'note' => 'Penambahan bab analisis resolusi konflik kearifan lokal dan dokumentasi ritual adat.', 'file_name' => 'Naskah_Akademik_Kajian_Pela_Gandong_Maluku_Final_v2.docx', 'file_size' => '5.1 MB'],
                ],
            ],
            [
                'folder_id' => $fSmaSmk->id,
                'document_number' => 'REKAP-SARPRAS/SMK-SMA/MAL/2026',
                'title' => 'Master Data Evaluasi & Kebutuhan Sarana Laboratorium TIK SMA/SMK 11 Kabupaten/Kota',
                'category' => 'Data Pokok & Statistik',
                'division' => 'Bidang SMA & SMK',
                'description' => 'Rekapitulasi kondisi sarana ruang kelas, laboratorium komputer/TIK, sanitasi, dan rasio perangkat digital per siswa di seluruh Maluku.',
                'document_date' => '2026-05-10',
                'year' => 2026,
                'confidentiality' => 'INTERNAL',
                'status' => 'AKTIF',
                'current_version' => 'v1.0',
                'file_name' => 'Master_Data_Sarpras_TIK_SMA_SMK_Maluku_2026.xlsx',
                'file_type' => 'XLSX',
                'file_size' => '2.9 MB',
                'tags' => 'Sarpras, Lab Komputer, TIK, Digitalisasi Sekolah',
                'download_count' => 88,
                'view_count' => 310,
                'versions' => [
                    ['version_number' => 'v1.0', 'note' => 'Sinkronisasi data Dapodik & SIMANDAT wilayah kepulauan.', 'file_name' => 'Master_Data_Sarpras_TIK_SMA_SMK_Maluku_2026.xlsx', 'file_size' => '2.9 MB'],
                ],
            ],
            [
                'folder_id' => $fGtk->id,
                'document_number' => 'PANDUAN-TPG/GTK-MAL/2026',
                'title' => 'Petunjuk Teknis Penyaluran Tunjangan Profesi Guru (TPG) dan Insentif Daerah 3T Maluku',
                'category' => 'Pedoman, Panduan & SOP',
                'division' => 'Bidang Guru & Tenaga Kependidikan (GTK)',
                'description' => 'Pedoman teknis verifikasi keaktifan mengajar, linieritas ijazah, dan tata cara verifikasi berkas TPG untuk guru SMA/SMK/SLB di daerah terpencil dan kepulauan.',
                'document_date' => '2026-03-01',
                'year' => 2026,
                'confidentiality' => 'PUBLIK',
                'status' => 'AKTIF',
                'current_version' => 'v1.0',
                'file_name' => 'Juknis_Penyaluran_TPG_Insentif_3T_Maluku_2026.pdf',
                'file_type' => 'PDF',
                'file_size' => '4.1 MB',
                'tags' => 'TPG, Sertifikasi Guru, Daerah 3T, GTK, Juknis',
                'download_count' => 312,
                'view_count' => 950,
                'versions' => [
                    ['version_number' => 'v1.0', 'note' => 'Publikasi resmi Juknis TPG Triwulan I 2026.', 'file_name' => 'Juknis_Penyaluran_TPG_Insentif_3T_Maluku_2026.pdf', 'file_size' => '4.1 MB'],
                ],
            ],
            [
                'folder_id' => $fBudaya->id,
                'document_number' => 'MAT-FEST/BANDA/BUD/2026',
                'title' => 'Paparan Presentasi Rencana Pelaksanaan Festival Budaya Banda Neira & Pesta Rakyat Rempah 2026',
                'category' => 'Dokumentasi Kegiatan & Media',
                'division' => 'Bidang Kebudayaan',
                'description' => 'Bahan tayang paparan eksekutif mengenai jadwal event, atraksi Belang Adat, pameran kuliner tradisi, dan kolaborasi ekonomi kreatif di Kepulauan Banda.',
                'document_date' => '2026-06-15',
                'year' => 2026,
                'confidentiality' => 'INTERNAL',
                'status' => 'AKTIF',
                'current_version' => 'v1.0',
                'file_name' => 'Presentasi_Eksekutif_Festival_Banda_Neira_2026.pptx',
                'file_type' => 'PPTX',
                'file_size' => '12.4 MB',
                'culture_id' => $culture1 ? $culture1->id : null,
                'tags' => 'Festival Banda, Jalur Rempah, Presentasi, Wisata Budaya',
                'download_count' => 64,
                'view_count' => 240,
                'versions' => [
                    ['version_number' => 'v1.0', 'note' => 'Materi presentasi rapat koordinasi dinas.', 'file_name' => 'Presentasi_Eksekutif_Festival_Banda_Neira_2026.pptx', 'file_size' => '12.4 MB'],
                ],
            ],
            [
                'folder_id' => $fRegulasi->id,
                'document_number' => 'RAHASIA/INSP/09/MAL/2026',
                'title' => 'Laporan Khusus Audit Kepatuhan & Tata Kelola Aset Pendidikan Wilayah Kepulauan Terluar',
                'category' => 'Laporan Kinerja & Akuntabilitas',
                'division' => 'Sekretariat',
                'description' => 'Dokumen hasil evaluasi kepatuhan aset tanah, bangunan gedung sekolah, dan inventarisasi kendaraan dinas yang membutuhkan perhatian khusus pimpinan.',
                'document_date' => '2026-07-10',
                'year' => 2026,
                'confidentiality' => 'RAHASIA',
                'status' => 'AKTIF',
                'current_version' => 'v1.0',
                'file_name' => 'Laporan_Audit_Khusus_Aset_Pendidikan_Maluku_Confidential.pdf',
                'file_type' => 'PDF',
                'file_size' => '3.8 MB',
                'tags' => 'Audit, Aset, Rahasia, Laporan Khusus',
                'download_count' => 12,
                'view_count' => 38,
                'versions' => [
                    ['version_number' => 'v1.0', 'note' => 'Dokumen rahasia untuk Kepala Dinas dan Inspektorat Daerah.', 'file_name' => 'Laporan_Audit_Khusus_Aset_Pendidikan_Maluku_Confidential.pdf', 'file_size' => '3.8 MB'],
                ],
            ],
            [
                'folder_id' => $fSekr->id,
                'document_number' => 'MOU.07/DISDIKBUD-UNPATTI/2026',
                'title' => 'Perjanjian Kerja Sama (MoU) Penguatan Mutu Riset Kebudayaan & Pendidikan Vokasi dengan Universitas Pattimura',
                'category' => 'MoU & Kerjasama Kemitraan',
                'division' => 'Sekretariat',
                'description' => 'Naskah kesepakatan bersama pengembangan kurikulum vokasi kelautan Maluku dan revitalisasi dialek bahasa daerah kepulauan terancam punah.',
                'document_date' => '2026-05-02',
                'year' => 2026,
                'confidentiality' => 'PUBLIK',
                'status' => 'AKTIF',
                'current_version' => 'v1.0',
                'file_name' => 'MoU_Disdikbud_Maluku_Unpatti_2026_Signed.pdf',
                'file_type' => 'PDF',
                'file_size' => '2.5 MB',
                'tags' => 'MoU, Unpatti, Kerjasama, Vokasi, Bahasa Daerah',
                'download_count' => 95,
                'view_count' => 410,
                'versions' => [
                    ['version_number' => 'v1.0', 'note' => 'Naskah MoU ditandatangani kedua belah pihak.', 'file_name' => 'MoU_Disdikbud_Maluku_Unpatti_2026_Signed.pdf', 'file_size' => '2.5 MB'],
                ],
            ],
        ];

        foreach ($docs as $d) {
            $versions = $d['versions'];
            unset($d['versions']);

            $d['qr_token'] = (string) Str::uuid();
            $d['uploader_id'] = $adminId;
            $d['uploader_name'] = $adminName;

            $createdDoc = SimandatDocument::create($d);

            foreach ($versions as $v) {
                SimandatDocumentVersion::create([
                    'document_id' => $createdDoc->id,
                    'version_number' => $v['version_number'],
                    'file_path' => 'documents/seeds/' . $v['file_name'],
                    'file_name' => $v['file_name'],
                    'file_type' => $createdDoc->file_type,
                    'file_size' => $v['file_size'],
                    'changelog_note' => $v['note'],
                    'uploaded_by' => $adminId,
                    'uploader_name' => $adminName,
                ]);
            }
        }
    }
}
