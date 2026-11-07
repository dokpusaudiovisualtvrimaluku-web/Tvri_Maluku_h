<?php

namespace Database\Seeders;

use App\Models\SimandatPerencanaanProgram;
use App\Models\User;
use Illuminate\Database\Seeder;

class PerencanaanProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();
        $userId = $admin ? $admin->id : 1;

        $sampleData = [
            // 1. Program
            [
                'nomor_registrasi'          => 'PRG-2026-0001',
                'jenis_dokumen'             => 'Program',
                'bidang_penanggung_jawab'   => 'Pembinaan SMA',
                'tahun_anggaran'            => 2026,
                'kode_rekening'             => '1.01.02.1.01.0001',
                'nama_program_kegiatan'     => 'Program Pengelolaan dan Peningkatan Mutu Pendidikan Menengah SMA Provinsi Maluku',
                'uraian_kegiatan'           => 'Program prioritas pengelolaan seluruh satuan pendidikan menengah SMA di 11 kabupaten/kota provinsi Maluku guna peningkatan angka partisipasi murni dan mutu akreditasi.',
                'periode_pelaksanaan'       => 'Januari - Desember 2026',
                'pagu_anggaran'             => 14500000000,
                'realisasi_anggaran'        => 11800000000,
                'target_kinerja'            => '285 SMA Terfasilitasi Mutu & Sarpras',
                'realisasi_kinerja'         => '240 SMA Telah Tervalidasi',
                'status_pelaksanaan'        => 'BERJALAN',
                'pejabat_penanggung_jawab'  => 'Drs. J. Sahertian, M.Pd (Kabid SMA)',
                'catatan_evaluasi'          => 'Realisasi penyerapan dana telah mencapai 81.38% per Triwulan III.',
            ],
            [
                'nomor_registrasi'          => 'PRG-2026-0002',
                'jenis_dokumen'             => 'Program',
                'bidang_penanggung_jawab'   => 'Pembinaan SMK',
                'tahun_anggaran'            => 2026,
                'kode_rekening'             => '1.01.02.1.02.0001',
                'nama_program_kegiatan'     => 'Program Revitalisasi SMK dan Pengembangan Link and Match Vokasi Maluku',
                'uraian_kegiatan'           => 'Penguatan sarana workshop keahlian, kemitraan DUDI, serta sertifikasi kompetensi keahlian peserta didik SMK berbasis potensi kemaritiman dan pariwisata Maluku.',
                'periode_pelaksanaan'       => 'Triwulan I - IV 2026',
                'pagu_anggaran'             => 12000000000,
                'realisasi_anggaran'        => 9500000000,
                'target_kinerja'            => '110 SMK Mengembangkan Unit Produksi & TEFA',
                'realisasi_kinerja'         => '92 SMK Berjalan',
                'status_pelaksanaan'        => 'BERJALAN',
                'pejabat_penanggung_jawab'  => 'Matheos Lewier, S.Pd (Kabid SMK)',
                'catatan_evaluasi'          => 'Penyelarasan kurikulum vokasi telah selesai di 8 Kabupaten/Kota.',
            ],

            // 2. Kegiatan
            [
                'nomor_registrasi'          => 'KGT-2026-0001',
                'jenis_dokumen'             => 'Kegiatan',
                'bidang_penanggung_jawab'   => 'Pendidikan Khusus (SLB)',
                'tahun_anggaran'            => 2026,
                'kode_rekening'             => '1.01.02.1.03.0002',
                'nama_program_kegiatan'     => 'Kegiatan Pengadaan Media Pembelajaran Braille dan Asesmen ABK SLB se-Maluku',
                'uraian_kegiatan'           => 'Pemberian bantuan media pembelajaran khusus bagi peserta didik tunanetra, tunarungu, dan autis pada seluruh SLB Negeri dan Swasta di Maluku.',
                'periode_pelaksanaan'       => 'Maret - September 2026',
                'pagu_anggaran'             => 1750000000,
                'realisasi_anggaran'        => 1750000000,
                'target_kinerja'            => '22 Satuan SLB Terdistribusi Paket Media',
                'realisasi_kinerja'         => '22 Satuan SLB Selesai',
                'status_pelaksanaan'        => 'SELESAI',
                'pejabat_penanggung_jawab'  => 'Ny. Sarah Latuihamallo, M.Si (PPTK PKLK)',
                'catatan_evaluasi'          => 'Distribusi 100% selesai tepat waktu dan telah dilakukan serah terima BAST.',
            ],
            [
                'nomor_registrasi'          => 'KGT-2026-0002',
                'jenis_dokumen'             => 'Kegiatan',
                'bidang_penanggung_jawab'   => 'GTK',
                'tahun_anggaran'            => 2026,
                'kode_rekening'             => '1.01.02.1.04.0003',
                'nama_program_kegiatan'     => 'Kegiatan Verifikasi dan Validasi Pemberian Tunjangan Profesi Guru Non-PNS',
                'uraian_kegiatan'           => 'Pemeriksaan berkas SKTP, beban jam mengajar 24 jam linear, dan keabsahan rekening bank penerima tunjangan profesi dan insentif daerah 3T.',
                'periode_pelaksanaan'       => 'Triwulan I - IV 2026',
                'pagu_anggaran'             => 3200000000,
                'realisasi_anggaran'        => 2850000000,
                'target_kinerja'            => '4.500 Guru Tersalurkan Tunjangan',
                'realisasi_kinerja'         => '4.120 Guru Telah Menerima SP2D',
                'status_pelaksanaan'        => 'BERJALAN',
                'pejabat_penanggung_jawab'  => 'Hendrik Pattinama, S.Sos (Kasie Tunjangan GTK)',
                'catatan_evaluasi'          => 'Penyaluran Triwulan 3 telah rampung 95%.',
            ],

            // 3. Renstra
            [
                'nomor_registrasi'          => 'RNS-2026-0001',
                'jenis_dokumen'             => 'Renstra',
                'bidang_penanggung_jawab'   => 'Sekretariat',
                'tahun_anggaran'            => 2026,
                'kode_rekening'             => '1.01.01.1.01.0001',
                'nama_program_kegiatan'     => 'Rencana Strategis (RENSTRA) Dinas Pendidikan dan Kebudayaan Provinsi Maluku 2025-2029',
                'uraian_kegiatan'           => 'Dokumen induk perencanaan pembangunan jangka menengah 5 tahunan Disdikbud Provinsi Maluku yang memuat visi, misi, tujuan, sasaran, strategi, arah kebijakan, dan target kinerja makro.',
                'periode_pelaksanaan'       => '2025 - 2029',
                'pagu_anggaran'             => 450000000,
                'realisasi_anggaran'        => 450000000,
                'target_kinerja'            => '1 Dokumen Renstra Resmi Terbit Perda/Pergub',
                'realisasi_kinerja'         => '1 Dokumen Final Terbit',
                'status_pelaksanaan'        => 'SELESAI',
                'pejabat_penanggung_jawab'  => 'Sekretaris Dinas Pendidikan & Kebudayaan',
                'catatan_evaluasi'          => 'Telah disahkan oleh Bappeda Provinsi Maluku dan siap dijadikan acuan Renja tahunan.',
            ],

            // 4. Renja
            [
                'nomor_registrasi'          => 'RNJ-2026-0001',
                'jenis_dokumen'             => 'Renja',
                'bidang_penanggung_jawab'   => 'Sekretariat',
                'tahun_anggaran'            => 2026,
                'kode_rekening'             => '1.01.01.1.01.0002',
                'nama_program_kegiatan'     => 'Rencana Kerja Tahunan (RENJA) Dinas Pendidikan dan Kebudayaan TA 2026',
                'uraian_kegiatan'           => 'Dokumen perencanaan tahunan dinas yang memuat kerangka program, target output sub kegiatan, lokasi indikatif, dan kebutuhan pagu indikatif SKPD.',
                'periode_pelaksanaan'       => 'Tahun Anggaran 2026',
                'pagu_anggaran'             => 320000000,
                'realisasi_anggaran'        => 320000000,
                'target_kinerja'            => '1 Dokumen Renja Terintegrasi SIPD RI',
                'realisasi_kinerja'         => '1 Dokumen Selesai Diinput ke SIPD',
                'status_pelaksanaan'        => 'SELESAI',
                'pejabat_penanggung_jawab'  => 'Kasubbag Perencanaan & Program',
                'catatan_evaluasi'          => 'Telah sinkron 100% dengan RKPD Provinsi Maluku 2026.',
            ],

            // 5. RKA
            [
                'nomor_registrasi'          => 'RKA-2026-0001',
                'jenis_dokumen'             => 'RKA',
                'bidang_penanggung_jawab'   => 'Sekretariat',
                'tahun_anggaran'            => 2026,
                'kode_rekening'             => '1.01.01.1.02.0001',
                'nama_program_kegiatan'     => 'Rencana Kerja dan Anggaran (RKA-SKPD) Dinas Pendidikan & Kebudayaan 2026',
                'uraian_kegiatan'           => 'Rencana kerja dan anggaran belanja operasi, belanja modal sarpras sekolah, belanja transfer BOSDA, dan belanja pegawai Disdikbud Maluku.',
                'periode_pelaksanaan'       => 'Tahun Anggaran 2026',
                'pagu_anggaran'             => 85000000000,
                'realisasi_anggaran'        => 68500000000,
                'target_kinerja'            => '1 Dokumen Buku RKA SKPD Lengkap',
                'realisasi_kinerja'         => 'Telah Disetujui TAPD',
                'status_pelaksanaan'        => 'SELESAI',
                'pejabat_penanggung_jawab'  => 'Kasubbag Keuangan & Perencanaan',
                'catatan_evaluasi'          => 'RKA telah melalui proses desk pembahasan bersama Badan Anggaran DPRD Maluku.',
            ],

            // 6. DPA
            [
                'nomor_registrasi'          => 'DPA-2026-0001',
                'jenis_dokumen'             => 'DPA',
                'bidang_penanggung_jawab'   => 'Sekretariat',
                'tahun_anggaran'            => 2026,
                'kode_rekening'             => '1.01.01.1.02.0002',
                'nama_program_kegiatan'     => 'Dokumen Pelaksanaan Anggaran (DPA-SKPD) Disdikbud TA 2026',
                'uraian_kegiatan'           => 'Dokumen operasional pelaksanaan anggaran pendapatan, belanja belanja modal dan barang/jasa yang disahkan oleh PPKD BPKAD Provinsi Maluku.',
                'periode_pelaksanaan'       => '1 Januari - 31 Desember 2026',
                'pagu_anggaran'             => 85000000000,
                'realisasi_anggaran'        => 68500000000,
                'target_kinerja'            => 'DPA Induk & Perubahan Terverifikasi',
                'realisasi_kinerja'         => '100% Berjalan Sesuai SPD Triwulanan',
                'status_pelaksanaan'        => 'BERJALAN',
                'pejabat_penanggung_jawab'  => 'Kuasa Pengguna Anggaran (KPA) Disdikbud',
                'catatan_evaluasi'          => 'SPD Triwulan I s.d IV telah diterbitkan oleh BPKAD Provinsi Maluku.',
            ],

            // 7. Kebudayaan & UPTD
            [
                'nomor_registrasi'          => 'PRG-2026-0003',
                'jenis_dokumen'             => 'Program',
                'bidang_penanggung_jawab'   => 'Kebudayaan',
                'tahun_anggaran'            => 2026,
                'kode_rekening'             => '1.01.03.1.01.0001',
                'nama_program_kegiatan'     => 'Program Pelindungan dan Pelestarian Cagar Budaya Kepulauan Maluku',
                'uraian_kegiatan'           => 'Konservasi benteng peninggalan kolonial, naskah kuno manuskrip, serta registrasi WBTB (Warisan Budaya Takbenda) khas Maluku.',
                'periode_pelaksanaan'       => 'Tahun 2026',
                'pagu_anggaran'             => 3500000000,
                'realisasi_anggaran'        => 2700000000,
                'target_kinerja'            => '45 Objek Cagar Budaya & 10 WBTB Terdaftar',
                'realisasi_kinerja'         => '38 Objek Selesai Sidang TACB',
                'status_pelaksanaan'        => 'BERJALAN',
                'pejabat_penanggung_jawab'  => 'Kabid Kebudayaan Disdikbud Maluku',
                'catatan_evaluasi'          => 'Sidang penetapan TACB Nasional menjadwalkan 5 WBTB baru asal Maluku.',
            ],
            [
                'nomor_registrasi'          => 'KGT-2026-0003',
                'jenis_dokumen'             => 'Kegiatan',
                'bidang_penanggung_jawab'   => 'UPTD Museum',
                'tahun_anggaran'            => 2026,
                'kode_rekening'             => '1.01.03.1.02.0002',
                'nama_program_kegiatan'     => 'Kegiatan Pameran Temporer Etnografi Budaya Bahari Museum Siwalima',
                'uraian_kegiatan'           => 'Penyelenggaraan pameran edukasi sejarah jalur rempah dan artefak maritim untuk pelajar dan masyarakat umum di Museum Siwalima Ambon.',
                'periode_pelaksanaan'       => 'Juni - Agustus 2026',
                'pagu_anggaran'             => 600000000,
                'realisasi_anggaran'        => 600000000,
                'target_kinerja'            => '15.000 Pengunjung Pelajar & Wisatawan',
                'realisasi_kinerja'         => '17.400 Pengunjung Terdata',
                'status_pelaksanaan'        => 'SELESAI',
                'pejabat_penanggung_jawab'  => 'Kepala UPTD Museum Siwalima',
                'catatan_evaluasi'          => 'Melampaui target indikator pengunjung berkat integrasi kunjungan sekolah SMA/SMK.',
            ],
        ];

        foreach ($sampleData as $item) {
            SimandatPerencanaanProgram::updateOrCreate(
                ['nomor_registrasi' => $item['nomor_registrasi']],
                array_merge($item, ['user_id' => $userId])
            );
        }
    }
}
