<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SimandatSlbLayanan;
use App\Models\User;

class SlbLayananSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first() ?? User::factory()->create();

        $sampleData = [
            [
                'nomor_registrasi' => 'SLB-IZN-DIRI-2026-0001-A1B2',
                'jenis_layanan' => 'Rekomendasi Pendirian SLB Negeri dan SLB Swasta',
                'nama_pemohon' => 'Yayasan Kasih Mandiri Maluku (Ketua: Robert Latuihamallo)',
                'nomor_identitas' => '8171011504780004',
                'nama_satuan_pendidikan' => 'SLB Swasta Kasih Mandiri Ambon',
                'npsn_sekolah' => null,
                'kabupaten_kota' => 'Kota Ambon',
                'no_hp_whatsapp' => '081240987654',
                'email' => 'yayasan.kasihmandiri@gmail.com',
                'tanggal_pengajuan' => '2026-01-20',
                'tanggal_selesai' => '2026-02-15',
                'nomor_surat_rekomendasi' => '421.9/104/DISDIKBUD-MAL/2026',
                'perihal_uraian' => 'Permohonan Rekomendasi Teknis Pendirian SLB Swasta Jenjang SDLB dan SMPLB untuk anak berkebutuhan khusus Autis dan Tunagrahita di Kota Ambon.',
                'status_layanan' => 'SELESAI',
                'catatan_verifikator' => 'Verifikasi dokumen proposal dan visitasi lapangan kelayakan sarpras telah selesai. Surat Rekomendasi Pendirian resmi telah diterbitkan.',
                'petugas_verifikator' => 'Tim Kelembagaan Pendidikan Khusus',
                'user_id' => $admin->id,
            ],
            [
                'nomor_registrasi' => 'SLB-MUTASI-2026-0002-C3D4',
                'jenis_layanan' => 'Rekomendasi Mutasi (Perpindahan) Siswa SLB',
                'nama_pemohon' => 'Hasan Pelu (Orang Tua / Wali Siswa)',
                'nomor_identitas' => '8101031908850001',
                'nama_satuan_pendidikan' => 'SLB Negeri Maluku Tengah',
                'npsn_sekolah' => '60103512',
                'kabupaten_kota' => 'Maluku Tengah',
                'no_hp_whatsapp' => '082198112233',
                'email' => 'hasan.pelu@gmail.com',
                'tanggal_pengajuan' => '2026-02-05',
                'tanggal_selesai' => '2026-02-12',
                'nomor_surat_rekomendasi' => '422.1/088/SLB-MUT/DISDIKBUD/2026',
                'perihal_uraian' => 'Permohonan rekomendasi mutasi perpindahan siswa berkebutuhan khusus (Tunarungu) kelas VII SMPLB dari SLB Negeri Ambon ke SLB Negeri Maluku Tengah karena kepindahan domisili orang tua.',
                'status_layanan' => 'SELESAI',
                'catatan_verifikator' => 'Rapor, surat lolos butuh, dan asesmen kekhususan telah divalidasi. Surat rekomendasi mutasi diterbitkan.',
                'petugas_verifikator' => 'Seksi Peserta Didik SLB',
                'user_id' => $admin->id,
            ],
            [
                'nomor_registrasi' => 'SLB-BOSP-2026-0003-E5F6',
                'jenis_layanan' => 'Bantuan Operasional Satuan Pendidikan (BOSP) SLB',
                'nama_pemohon' => 'Dra. Helena Siahaya (Kepala Sekolah)',
                'nomor_identitas' => '197508122000032001',
                'nama_satuan_pendidikan' => 'SLB Negeri Ambon',
                'npsn_sekolah' => '60102005',
                'kabupaten_kota' => 'Kota Ambon',
                'no_hp_whatsapp' => '081344223344',
                'email' => 'slbnegeriambon@yahoo.co.id',
                'tanggal_pengajuan' => '2026-02-10',
                'tanggal_selesai' => null,
                'nomor_surat_rekomendasi' => null,
                'perihal_uraian' => 'Verifikasi dan validasi kelengkapan dokumen pelaporan LPJ BOSP SLB Tahap I dan pengusulan rekomendasi penyaluran BOSP Reguler Tahap II TA 2026.',
                'status_layanan' => 'PROSES_VERIFIKASI',
                'catatan_verifikator' => 'Sedang dilakukan pencocokan data realisasi ARKAS dan verifikasi cut-off peserta didik aktif berkebutuhan khusus.',
                'petugas_verifikator' => 'Tim Manajemen BOSP Pendidikan Khusus',
                'user_id' => $admin->id,
            ],
            [
                'nomor_registrasi' => 'SLB-IJZ-LEGAL-2026-0004-G7H8',
                'jenis_layanan' => 'Legalisir Ijazah/STTB dan Surat Keterangan Lulus (SKL) SLB',
                'nama_pemohon' => 'Dominggus Wattimena, A.Md',
                'nomor_identitas' => '8171032509980002',
                'nama_satuan_pendidikan' => 'SLB Negeri Tual',
                'npsn_sekolah' => '60102789',
                'kabupaten_kota' => 'Kota Tual',
                'no_hp_whatsapp' => '085244556677',
                'email' => 'dominggus.wattimena@gmail.com',
                'tanggal_pengajuan' => '2026-02-28',
                'tanggal_selesai' => '2026-03-02',
                'nomor_surat_rekomendasi' => 'LEGAL-IJZ/SLB-TL/2026/019',
                'perihal_uraian' => 'Permohonan legalisasi ijazah SMALB (Sekolah Menengah Atas Luar Biasa) Jurusan Keterampilan Vokasi Kelulusan Tahun 2020 sebanyak 5 lembar untuk persyaratan kerja.',
                'status_layanan' => 'SELESAI',
                'catatan_verifikator' => 'Ijazah asli telah diverifikasi dengan buku induk kelulusan SLB Negeri Tual. Legalisir telah disahkan dan stempel basah.',
                'petugas_verifikator' => 'Seksi Kurikulum & Penilaian SLB',
                'user_id' => $admin->id,
            ],
            [
                'nomor_registrasi' => 'SLB-IZN-DIRI-2026-0005-I9J0',
                'jenis_layanan' => 'Rekomendasi Pendirian SLB Negeri dan SLB Swasta',
                'nama_pemohon' => 'Yayasan Pendidikan Inklusi Seram Bagian Barat',
                'nomor_identitas' => '8106020511820003',
                'nama_satuan_pendidikan' => 'SLB Swasta Pelita Harapan Piru',
                'npsn_sekolah' => null,
                'kabupaten_kota' => 'Seram Bagian Barat',
                'no_hp_whatsapp' => '081388990011',
                'email' => 'pelitaharapan.piru@gmail.com',
                'tanggal_pengajuan' => '2026-03-01',
                'tanggal_selesai' => null,
                'nomor_surat_rekomendasi' => null,
                'perihal_uraian' => 'Pengajuan rekomendasi teknis izin operasional dan pendirian unit baru SLB Swasta di Piru, Kabupaten Seram Bagian Barat.',
                'status_layanan' => 'DRAFT',
                'catatan_verifikator' => 'Menunggu kelengkapan berita acara musyawarah pendirian dan sertifikat tanah hibah yayasan.',
                'petugas_verifikator' => null,
                'user_id' => $admin->id,
            ],
            [
                'nomor_registrasi' => 'SLB-BOSP-2026-0006-K1L2',
                'jenis_layanan' => 'Bantuan Operasional Satuan Pendidikan (BOSP) SLB',
                'nama_pemohon' => 'M. Thahir Rengur, S.Pd',
                'nomor_identitas' => '198104152008011009',
                'nama_satuan_pendidikan' => 'SLB Cenderawasih Buru',
                'npsn_sekolah' => '60103980',
                'kabupaten_kota' => 'Buru',
                'no_hp_whatsapp' => '082211445566',
                'email' => 'slb.cenderawasih.buru@gmail.com',
                'tanggal_pengajuan' => '2026-02-15',
                'tanggal_selesai' => '2026-02-25',
                'nomor_surat_rekomendasi' => 'BOSP-SLB/BURU/2026/007',
                'perihal_uraian' => 'Rekomendasi persetujuan penyaluran dana BOSP Kinerja Sekolah Penggerak SLB Tahun Anggaran 2026.',
                'status_layanan' => 'DISETUJUI',
                'catatan_verifikator' => 'Dokumen Rencana Anggaran Biaya (RAB) dan SK Penetapan Sekolah Penggerak telah disetujui Bidang Pendidikan Khusus.',
                'petugas_verifikator' => 'Tim Manajemen BOSP Pendidikan Khusus',
                'user_id' => $admin->id,
            ],
        ];

        foreach ($sampleData as $data) {
            SimandatSlbLayanan::updateOrCreate(
                ['nomor_registrasi' => $data['nomor_registrasi']],
                $data
            );
        }
    }
}
