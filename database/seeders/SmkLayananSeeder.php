<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SimandatSmkLayanan;
use App\Models\User;

class SmkLayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();
        $userId = $admin ? $admin->id : null;

        $items = [
            [
                'nomor_registrasi' => 'SMK-MUT/2026/0001/M8Q2',
                'jenis_layanan' => 'Rekomendasi Mutasi (Perpindahan) Siswa SMK',
                'nama_pemohon' => 'Petrus Patty (Orang Tua Siswa)',
                'nomor_identitas' => '0059182344 (NISN)',
                'nama_sekolah_asal' => 'SMKN 1 Ambon',
                'npsn_sekolah' => '60101995',
                'konsentrasi_keahlian' => 'Teknik Komputer dan Jaringan (TKJ)',
                'kabupaten_kota' => 'Kota Ambon',
                'tanggal_pengajuan' => '2026-01-18',
                'tanggal_selesai' => '2026-01-22',
                'nomor_surat_keluar' => '422.3/110/DISDIKBUD-SMK/2026',
                'perihal_uraian' => 'Permohonan rekomendasi mutasi siswa kelas XI jurusan TKJ dari SMKN 1 Ambon ke SMKN 2 Kota Makassar (Sulawesi Selatan) mengikuti kepindahan domisili orang tua.',
                'status_layanan' => 'SELESAI',
                'catatan_verifikator' => 'Kesesuaian kurikulum dan konsentrasi keahlian TKJ terverifikasi valid.',
                'petugas_verifikator' => 'Kasi Peserta Didik SMK',
            ],
            [
                'nomor_registrasi' => 'SMK-BOSP/2026/0001/B2K9',
                'jenis_layanan' => 'Bantuan Operasional Satuan Pendidikan (BOSP) SMK',
                'nama_pemohon' => 'Tim Manajemen BOSP SMKN 3 Ambon',
                'nomor_identitas' => 'NPSN: 60101997',
                'nama_sekolah_asal' => 'SMKN 3 Ambon',
                'npsn_sekolah' => '60101997',
                'konsentrasi_keahlian' => 'Perhotelan & Akomodasi Pariwisata',
                'kabupaten_kota' => 'Kota Ambon',
                'tanggal_pengajuan' => '2026-01-25',
                'tanggal_selesai' => '2026-02-05',
                'nomor_surat_keluar' => '900/BOSP-SMK/018/2026',
                'perihal_uraian' => 'Verifikasi rekonsiliasi SPJ BOSP Kinerja SMK Pusat Keunggulan (SMK PK) TA 2025 untuk pencairan dana BOSP Reguler Tahap I TA 2026.',
                'status_layanan' => 'SELESAI',
                'catatan_verifikator' => 'Laporan ARKAS dan buku kas pembantu belanja bahan praktik kejuruan lengkap.',
                'petugas_verifikator' => 'Tim BOSP Bidang SMK',
            ],
            [
                'nomor_registrasi' => 'SMK-IJZ/2026/0001/J4L1',
                'jenis_layanan' => 'Pelayanan Ijazah SMK (Legalisir dan Surat Keterangan Pengganti yang Hilang / Rusak)',
                'nama_pemohon' => 'Achmad Fauzy Renwarin',
                'nomor_identitas' => '0038192004 (NISN)',
                'nama_sekolah_asal' => 'SMKN 1 Tual',
                'npsn_sekolah' => '60102555',
                'konsentrasi_keahlian' => 'Nautika Kapal Penangkap Ikan (NKPI)',
                'kabupaten_kota' => 'Kota Tual',
                'tanggal_pengajuan' => '2026-02-02',
                'tanggal_selesai' => '2026-02-08',
                'nomor_surat_keluar' => '422.5/076/SKP-IJZ-SMK/2026',
                'perihal_uraian' => 'Penerbitan Surat Keterangan Pengganti Ijazah SMK Lulusan Tahun 2020 Jurusan Nautika Kapal Penangkap Ikan yang rusak terkena air laut saat berlayar.',
                'status_layanan' => 'SELESAI',
                'catatan_verifikator' => 'Blangko ijazah asli rusak terlampir beserta Surat Keterangan Kepala Sekolah.',
                'petugas_verifikator' => 'Kasi Kurikulum & Penilaian SMK',
            ],
            [
                'nomor_registrasi' => 'SMK-KSP/2026/0001/K9S3',
                'jenis_layanan' => 'Verifikasi dan Supervisi Dokumen Kurikulum SMK',
                'nama_pemohon' => 'Ir. Marthinus Telehala, M.Pd (Kepala Sekolah)',
                'nomor_identitas' => '197103141998021003 (NIP)',
                'nama_sekolah_asal' => 'SMKN 2 Banda (Maluku Tengah)',
                'npsn_sekolah' => '60102040',
                'konsentrasi_keahlian' => 'Agribisnis Pengolahan Hasil Perikanan (APHP)',
                'kabupaten_kota' => 'Maluku Tengah',
                'tanggal_pengajuan' => '2026-02-12',
                'tanggal_selesai' => '2026-02-20',
                'nomor_surat_keluar' => '423.7/088/PENG-KSP-SMK/2026',
                'perihal_uraian' => 'Validasi dan pengesahan Dokumen Kurikulum Satuan Pendidikan (KSP) Konsentrasi Keahlian Agribisnis Perikanan bermitra dengan PT. Maluku Fishery Banda.',
                'status_layanan' => 'SELESAI',
                'catatan_verifikator' => 'Penyelarasan kurikulum bersama mitra DUDI telah memenuhi standar 80% materi berbasis industri.',
                'petugas_verifikator' => 'Kasi Kurikulum & Penilaian SMK',
            ],
            [
                'nomor_registrasi' => 'SMK-IZN-DIRI/2026/0001/D1R5',
                'jenis_layanan' => 'Rekomendasi Pendirian SMK Negeri dan SMK Swasta',
                'nama_pemohon' => 'Yayasan Bahari Mandiri Kepulauan Aru',
                'nomor_identitas' => 'AHU-0008912.AH.01.04.Tahun 2022',
                'nama_sekolah_asal' => 'SMK Maritim Kepulauan Aru',
                'npsn_sekolah' => 'Usulan Baru',
                'konsentrasi_keahlian' => 'Teknika Kapal Penangkap Ikan (TKPI)',
                'kabupaten_kota' => 'Kepulauan Aru',
                'tanggal_pengajuan' => '2026-02-25',
                'tanggal_selesai' => null,
                'nomor_surat_keluar' => null,
                'perihal_uraian' => 'Permohonan Rekomendasi Izin Pendirian SMK Swasta Baru berbasis Kemaritiman & Perikanan Tangkap di Dobo, Kepulauan Aru.',
                'status_layanan' => 'PROSES_VERIFIKASI',
                'catatan_verifikator' => 'Sedang dijadwalkan verifikasi fisik ketersediaan workshop mesin diesel perkapalan dan kolam simulasi.',
                'petugas_verifikator' => 'Kasi Kelembagaan & Sarpras SMK',
            ],
            [
                'nomor_registrasi' => 'SMK-IZN-OPS/2026/0001/O7P2',
                'jenis_layanan' => 'Rekomendasi Izin Operasional SMK',
                'nama_pemohon' => 'Yayasan Pendidikan Kristen Seram Bagian Barat',
                'nomor_identitas' => 'AHU-0002145.AH.01.04.Tahun 2018',
                'nama_sekolah_asal' => 'SMK Kristen Piru',
                'npsn_sekolah' => '60102210',
                'konsentrasi_keahlian' => 'Teknik Bisnis Sepeda Motor (TBSM)',
                'kabupaten_kota' => 'Seram Bagian Barat',
                'tanggal_pengajuan' => '2026-02-28',
                'tanggal_selesai' => '2026-03-08',
                'nomor_surat_keluar' => '422.1/195/OPS-SMK/2026',
                'perihal_uraian' => 'Perpanjangan Izin Operasional SMK Kristen Piru periode 2026 s.d. 2031 dan akreditasi sarana praktik bengkel otomotif.',
                'status_layanan' => 'SELESAI',
                'catatan_verifikator' => 'Standar rasio alat bengkel terhadap siswa 1:2 terpenuhi. Sertifikat uji kelayakan disetujui.',
                'petugas_verifikator' => 'Kasi Kelembagaan & Sarpras SMK',
            ],
            [
                'nomor_registrasi' => 'SMK-KOMP-BARU/2026/0001/K1B8',
                'jenis_layanan' => 'Izin Penambahan / Penggabungan Kompetensi (Konsentrasi Keahlian) Baru SMK',
                'nama_pemohon' => 'SMKN 1 Namlea (Kabupaten Buru)',
                'nomor_identitas' => 'NPSN: 60102315',
                'nama_sekolah_asal' => 'SMKN 1 Namlea',
                'npsn_sekolah' => '60102315',
                'konsentrasi_keahlian' => 'Bisnis Digital & Pemasaran',
                'kabupaten_kota' => 'Buru',
                'tanggal_pengajuan' => '2026-03-01',
                'tanggal_selesai' => null,
                'nomor_surat_keluar' => null,
                'perihal_uraian' => 'Pengajuan izin pembukaan Konsentrasi Keahlian Baru: Bisnis Digital & Pemasaran untuk merespons pertumbuhan UMKM dan e-commerce di Kabupaten Buru.',
                'status_layanan' => 'PROSES_VERIFIKASI',
                'catatan_verifikator' => 'Pemeriksaan kesiapan Lab Komputer Digital Marketing dan ketersediaan guru kejuruan bersertifikat BNSP.',
                'petugas_verifikator' => 'Kasi Kelembagaan & Kurikulum SMK',
            ],
        ];

        foreach ($items as $item) {
            SimandatSmkLayanan::updateOrCreate(
                ['nomor_registrasi' => $item['nomor_registrasi']],
                array_merge($item, ['user_id' => $userId])
            );
        }
    }
}
