<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SimandatSekretariat;
use App\Models\User;

class SekretariatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();
        $userId = $admin ? $admin->id : null;

        $items = [
            // 1. Koordinasi Administrasi
            [
                'nomor_agenda' => 'SEK-ADM-2026-0001-KOOR',
                'kategori_fungsi' => 'koordinasi_administrasi',
                'sub_kegiatan' => 'Mengkoordinasikan kegiatan antarbidang dan unit kerja',
                'judul_kegiatan' => 'Rapat Koordinasi Sinkronisasi Program Kerja Antar-Bidang & UPTD Triwulan I TA 2026',
                'penanggung_jawab' => 'Kasubbag Perencanaan & Pelaporan',
                'bidang_unit_terkait' => 'Sekretariat Dinas',
                'tanggal_kegiatan' => '2026-01-15',
                'tanggal_target_selesai' => '2026-01-16',
                'tahun_anggaran' => 2026,
                'alokasi_anggaran' => 25000000,
                'realisasi_anggaran' => 24250000,
                'persentase_capaian' => 100,
                'status_pelaksanaan' => 'Selesai',
                'tingkat_prioritas' => 'Tinggi',
                'deskripsi_uraian' => 'Penyelenggaraan rapat koordinasi lintas bidang (SMA, SMK, PKLK, GTK, Kebudayaan, UPTD BTKP, Museum Siwalima, Taman Budaya) dalam rangka sinkronisasi kalender kegiatan dan target serapan APBD 2026.',
                'hasil_keluaran_output' => 'Matriks Integrasi Jadwal Program Semester I TA 2026 dan Notulensi Kesepakatan Lintas Bidang.',
                'catatan_arahan_sekretaris' => 'Pastikan seluruh bidang menyampaikan laporan progres bulanan tepat waktu pada tanggal 5 setiap bulannya.',
            ],
            [
                'nomor_agenda' => 'SEK-ADM-2026-0002-INST',
                'kategori_fungsi' => 'koordinasi_administrasi',
                'sub_kegiatan' => 'Menyiapkan bahan koordinasi dengan perangkat daerah lainnya',
                'judul_kegiatan' => 'Penyusunan Bahan Koordinasi & Sinkronisasi DAK Fisik Pendidikan Bersama Bappeda & BPKAD Prov. Maluku',
                'penanggung_jawab' => 'Tim Kerja Perencanaan & Tata Usaha',
                'bidang_unit_terkait' => 'Bappeda Prov. Maluku',
                'tanggal_kegiatan' => '2026-02-10',
                'tanggal_target_selesai' => '2026-02-12',
                'tahun_anggaran' => 2026,
                'alokasi_anggaran' => 15000000,
                'realisasi_anggaran' => 14800000,
                'persentase_capaian' => 100,
                'status_pelaksanaan' => 'Selesai',
                'tingkat_prioritas' => 'Tinggi',
                'deskripsi_uraian' => 'Konsolidasi data spasial sarana prasarana sekolah rusak di 11 kabupaten/kota untuk diajukan dalam usulan DAK Fisik 2027 melalui aplikasi KRISNA Bappenas.',
                'hasil_keluaran_output' => 'Dokumen Usulan DAK Fisik Pendidikan Prov. Maluku TA 2027 dan Berita Acara Harmonisasi Bappeda.',
                'catatan_arahan_sekretaris' => 'Data dapodik dan verifikasi sarpras harus clean sebelum batas cut-off Krisna Bappenas.',
            ],

            // 2. Perencanaan dan Program
            [
                'nomor_agenda' => 'SEK-REN-2026-0001-RENJA',
                'kategori_fungsi' => 'perencanaan_program',
                'sub_kegiatan' => 'Mengkoordinasikan penyusunan Renstra, Renja, dan dokumen perencanaan lainnya',
                'judul_kegiatan' => 'Penyusunan Rancangan Rencana Kerja (Renja) Dinas Pendidikan dan Kebudayaan Tahun 2027',
                'penanggung_jawab' => 'Kasubbag Perencanaan & Program',
                'bidang_unit_terkait' => 'Bappeda Prov. Maluku',
                'tanggal_kegiatan' => '2026-03-01',
                'tanggal_target_selesai' => '2026-04-30',
                'tahun_anggaran' => 2026,
                'alokasi_anggaran' => 45000000,
                'realisasi_anggaran' => 38500000,
                'persentase_capaian' => 85,
                'status_pelaksanaan' => 'Sedang Berjalan',
                'tingkat_prioritas' => 'Tinggi',
                'deskripsi_uraian' => 'Penyusunan dokumen Renja Disdikbud Prov. Maluku Tahun 2027 yang mengintegrasikan target Indikator Kinerja Utama (IKU), SPM Pendidikan, dan pelestarian 16 cagar budaya unggulan Maluku.',
                'hasil_keluaran_output' => 'Draft Rancangan Awal Renja 2027 dan Cascading Kinerja Eselon II s.d IV.',
                'catatan_arahan_sekretaris' => 'Sesuaikan alokasi prioritas dengan arahan Gubernur Maluku untuk pengentasan wilayah 3T kepulauan.',
            ],

            // 3. Keuangan
            [
                'nomor_agenda' => 'SEK-KEU-2026-0001-RKA',
                'kategori_fungsi' => 'keuangan',
                'sub_kegiatan' => 'Melakukan pemantauan realisasi anggaran',
                'judul_kegiatan' => 'Monitoring Evaluasi Serapan APBD & Rekonsiliasi Realisasi Belanja Modal Triwulan I TA 2026',
                'penanggung_jawab' => 'Kasubbag Keuangan & Bendahara Pengeluaran',
                'bidang_unit_terkait' => 'BPKAD Prov. Maluku',
                'tanggal_kegiatan' => '2026-03-25',
                'tanggal_target_selesai' => '2026-03-31',
                'tahun_anggaran' => 2026,
                'alokasi_anggaran' => 20000000,
                'realisasi_anggaran' => 19500000,
                'persentase_capaian' => 95,
                'status_pelaksanaan' => 'Sedang Berjalan',
                'tingkat_prioritas' => 'Tinggi',
                'deskripsi_uraian' => 'Evaluasi realisasi anggaran kas belanja pegawai, belanja barang/jasa, dan belanja modal untuk seluruh bidang dan UPTD, serta rekonsiliasi SPJ dan e-Billing BPKAD.',
                'hasil_keluaran_output' => 'Laporan Realisasi Anggaran (LRA) Triwulan I TA 2026 dan Rekapitulasi Sisa Kas Bendahara.',
                'catatan_arahan_sekretaris' => 'Segera percepat pengajuan SPP-LS bagi kegiatan yang telah selesai 100% di lapangan.',
            ],

            // 4. Kepegawaian
            [
                'nomor_agenda' => 'SEK-PEG-2026-0001-ASN',
                'kategori_fungsi' => 'kepegawaian',
                'sub_kegiatan' => 'Mengkoordinasikan disiplin, kinerja dan administrasi ASN',
                'judul_kegiatan' => 'Evaluasi Penilaian Kinerja ASN, SKP Triwulan & Penegakan Disiplin Pegawai Lingkup Disdikbud',
                'penanggung_jawab' => 'Kasubbag Kepegawaian & Tim Penilai Kinerja',
                'bidang_unit_terkait' => 'BKD Prov. Maluku',
                'tanggal_kegiatan' => '2026-02-20',
                'tanggal_target_selesai' => '2026-02-28',
                'tahun_anggaran' => 2026,
                'alokasi_anggaran' => 12000000,
                'realisasi_anggaran' => 12000000,
                'persentase_capaian' => 100,
                'status_pelaksanaan' => 'Selesai',
                'tingkat_prioritas' => 'Sedang',
                'deskripsi_uraian' => 'Pemeriksaan kepatuhan presensi biometrik, rekapitulasi penilaian SKP melalui e-Kinerja BKN, serta pembinaan disiplin pegawai negeri sipil dan PPPK di lingkungan dinas.',
                'hasil_keluaran_output' => 'Rekapitulasi Kinerja SKP 240 ASN Dinas dan Surat Rekomendasi Pembinaan Disiplin.',
                'catatan_arahan_sekretaris' => 'Berikan surat teguran tertulis bagi pegawai yang tingkat kehadirannya di bawah standar minimal.',
            ],

            // 5. Administrasi Umum
            [
                'nomor_agenda' => 'SEK-UMM-2026-0001-TND',
                'kategori_fungsi' => 'administrasi_umum',
                'sub_kegiatan' => 'Mengelola surat-menyurat dan tata naskah dinas',
                'judul_kegiatan' => 'Digitalisasi Tata Naskah Dinas Elektronik (TNDE) & Penataan Arsip Dinamis Aktif Disdikbud',
                'penanggung_jawab' => 'Kasubbag Kepegawaian & Umum / Arsiparis',
                'bidang_unit_terkait' => 'Sekretariat Dinas',
                'tanggal_kegiatan' => '2026-01-08',
                'tanggal_target_selesai' => '2026-06-30',
                'tahun_anggaran' => 2026,
                'alokasi_anggaran' => 35000000,
                'realisasi_anggaran' => 22000000,
                'persentase_capaian' => 65,
                'status_pelaksanaan' => 'Sedang Berjalan',
                'tingkat_prioritas' => 'Sedang',
                'deskripsi_uraian' => 'Penerapan registrasi nomor surat dinas otomatis, klasifikasi kode arsip kearsipan pola baru, dan alih media digital surat keputusan Kepala Dinas.',
                'hasil_keluaran_output' => 'Buku Register Digital TNDE 2026 dan Penataan 500 Berkas Arsip Aktif ke Box Arsip Standar ANRI.',
                'catatan_arahan_sekretaris' => 'Seluruh persuratan keluar harus melalui verifikasi paraf hirarki Sekretaris Dinas sebelum diteken Kadis.',
            ],

            // 6. Pelaporan dan Evaluasi
            [
                'nomor_agenda' => 'SEK-MONEV-2026-0001-LAKIP',
                'kategori_fungsi' => 'pelaporan_evaluasi',
                'sub_kegiatan' => 'Menyiapkan bahan laporan kinerja dinas kepada Kepala Dinas dan pimpinan daerah',
                'judul_kegiatan' => 'Penyusunan Laporan Kinerja Instansi Pemerintah (LKjIP / LAKIP) Disdikbud Provinsi Maluku TA 2025',
                'penanggung_jawab' => 'Tim Penyusun LAKIP & Monev',
                'bidang_unit_terkait' => 'Inspektorat Prov. Maluku',
                'tanggal_kegiatan' => '2026-01-20',
                'tanggal_target_selesai' => '2026-02-15',
                'tahun_anggaran' => 2026,
                'alokasi_anggaran' => 30000000,
                'realisasi_anggaran' => 30000000,
                'persentase_capaian' => 100,
                'status_pelaksanaan' => 'Selesai',
                'tingkat_prioritas' => 'Tinggi',
                'deskripsi_uraian' => 'Penyusunan akuntabilitas pencapaian sasaran strategis, pengukuran capaian IKU, analisis efisiensi penggunaan sumber daya, serta penyampaian laporan ke KemenPAN-RB dan Inspektorat.',
                'hasil_keluaran_output' => 'Buku Laporan Kinerja Instansi Pemerintah (LKjIP) Disdikbud Prov. Maluku TA 2025 (Nilai Akuntabilitas: A).',
                'catatan_arahan_sekretaris' => 'Pertahankan predikat nilai SAKIP dan perbaiki catatan evaluasi pada cascading level staf operasional.',
            ],

            // 7. Pengelolaan Barang Milik Daerah (BMD)
            [
                'nomor_agenda' => 'SEK-BMD-2026-0001-ASET',
                'kategori_fungsi' => 'pengelolaan_bmd',
                'sub_kegiatan' => 'Mengkoordinasikan administrasi dan inventarisasi barang milik daerah',
                'judul_kegiatan' => 'Sensus & Inventarisasi Fisik Barang Milik Daerah (BMD), KIB A s.d. F Serta Sertifikasi Tanah Sekolah',
                'penanggung_jawab' => 'Pengurus Barang Pengguna & Tim Aset',
                'bidang_unit_terkait' => 'BPKAD Prov. Maluku',
                'tanggal_kegiatan' => '2026-02-01',
                'tanggal_target_selesai' => '2026-05-31',
                'tahun_anggaran' => 2026,
                'alokasi_anggaran' => 50000000,
                'realisasi_anggaran' => 35000000,
                'persentase_capaian' => 70,
                'status_pelaksanaan' => 'Sedang Berjalan',
                'tingkat_prioritas' => 'Tinggi',
                'deskripsi_uraian' => 'Pelaksanaan verifikasi fisik aset gedung, laboratorium, peralatan TIK sekolah, kendaraan dinas, serta inventarisasi tanah milik dinas di kabupaten/kota untuk kepastian hukum sertifikat Hak Pakai Pemprov.',
                'hasil_keluaran_output' => 'Buku Inventaris KIB A, B, C, D, E, F Terverifikasi di Aplikasi e-BMD BPKAD.',
                'catatan_arahan_sekretaris' => 'Fokuskan percepatan sertifikasi lahan SMA/SMK yang berada di wilayah pulau terluar.',
            ],

            // 8. Pelayanan Internal
            [
                'nomor_agenda' => 'SEK-INT-2026-0001-LAY',
                'kategori_fungsi' => 'pelayanan_internal',
                'sub_kegiatan' => 'Memastikan kebutuhan administrasi, kepegawaian, keuangan dan perlengkapan unit kerja terpenuhi',
                'judul_kegiatan' => 'Pemenuhan Logistik Kantor, Sarana Kerja TIK & Fasilitasi Operasional Lintas Bidang Dinas TA 2026',
                'penanggung_jawab' => 'Kasubbag Kepegawaian & Umum',
                'bidang_unit_terkait' => 'Sekretariat Dinas',
                'tanggal_kegiatan' => '2026-01-05',
                'tanggal_target_selesai' => '2026-12-31',
                'tahun_anggaran' => 2026,
                'alokasi_anggaran' => 80000000,
                'realisasi_anggaran' => 28000000,
                'persentase_capaian' => 35,
                'status_pelaksanaan' => 'Sedang Berjalan',
                'tingkat_prioritas' => 'Biasa',
                'deskripsi_uraian' => 'Penyediaan Alat Tulis Kantor (ATK), pemeliharaan jaringan internet dinas, perangkat komputer kerja, kebersihan gedung kantor, dan akomodasi pelayanan rapat dinas.',
                'hasil_keluaran_output' => 'Distribusi Logistik Bulanan untuk 6 Bidang & 3 UPTD serta Pemeliharaan Berkala 12 Unit AC & Jaringan Server.',
                'catatan_arahan_sekretaris' => 'Lakukan penghematan pemakaian energi listrik dan efisiensi belanja ATK tanpa mengurangi kualitas pelayanan.',
            ],
        ];

        foreach ($items as $item) {
            SimandatSekretariat::updateOrCreate(
                ['nomor_agenda' => $item['nomor_agenda']],
                array_merge($item, ['user_id' => $userId])
            );
        }
    }
}
