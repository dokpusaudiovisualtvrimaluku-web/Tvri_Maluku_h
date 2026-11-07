<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SimandatKepegawaianLayanan;
use App\Models\User;

class KepegawaianUmumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();
        $adminId = $admin ? $admin->id : 1;
        $adminName = $admin ? $admin->name : 'Super Admin (Disdikbud)';

        $samples = [
            [
                'nomor_agenda' => 'REG-ND/2026/0001',
                'jenis_layanan' => 'Surat / Naskah Dinas',
                'nama_pemohon' => 'Drs. Hein Nanlohy, M.Pd',
                'nip_nik_nisn' => '197003151998021004',
                'pangkat_golongan' => 'Pembina Tk. I (IV/b)',
                'jabatan_unit_kerja' => 'Kepala Cabang Dinas Pendidikan Maluku Tengah',
                'kabupaten_kota' => 'Maluku Tengah',
                'tanggal_permohonan' => '2026-08-10',
                'tanggal_selesai' => '2026-08-12',
                'nomor_sk_surat' => '005/142/DISDIKBUD/2026',
                'perihal_deskripsi' => 'Naskah Dinas Undangan Rapat Koordinasi Evaluasi Kelulusan SMA/SMK Semester Ganjil 2026.',
                'status_layanan' => 'SELESAI',
                'catatan_petugas' => 'Surat dinas telah ditandatangani Kepala Dinas dan diteruskan ke seluruh Cabang Dinas.',
            ],
            [
                'nomor_agenda' => 'REG-KP/2026/0002',
                'jenis_layanan' => 'Layanan Kenaikan Pangkat',
                'nama_pemohon' => 'Maria Martha Tuhuteru, S.Pd',
                'nip_nik_nisn' => '198204122006042011',
                'pangkat_golongan' => 'Penata Tk. I (III/d)',
                'jabatan_unit_kerja' => 'Guru Madya Fisika - SMAN 1 Siwalima Ambon',
                'kabupaten_kota' => 'Kota Ambon',
                'tanggal_permohonan' => '2026-07-15',
                'tanggal_selesai' => '2026-08-01',
                'nomor_sk_surat' => '823.3/089-KP/BKD/2026',
                'perihal_deskripsi' => 'Pengusulan Kenaikan Pangkat Pilihan Guru periode Oktober 2026 dari Penata Tk. I (III/d) ke Pembina (IV/a) dengan PAK 425.',
                'status_layanan' => 'SELESAI',
                'catatan_petugas' => 'Berkas PAK dan syarat kenaikan pangkat telah diverifikasi BKD Provinsi Maluku. SK terbit.',
            ],
            [
                'nomor_agenda' => 'REG-KGB/2026/0003',
                'jenis_layanan' => 'Kenaikan Gaji Berkala (KGB)',
                'nama_pemohon' => 'Jacobus Pattipeilohy, S.Pd',
                'nip_nik_nisn' => '198509202010011018',
                'pangkat_golongan' => 'Penata (III/c)',
                'jabatan_unit_kerja' => 'Guru Biologi - SMA Negeri 2 Masohi',
                'kabupaten_kota' => 'Maluku Tengah',
                'tanggal_permohonan' => '2026-08-01',
                'tanggal_selesai' => '2026-08-05',
                'nomor_sk_surat' => '822.2/214-KGB/DISDIKBUD/2026',
                'perihal_deskripsi' => 'Permohonan Kenaikan Gaji Berkala (KGB) TMT 1 September 2026 masa kerja golongan 14 tahun 00 bulan.',
                'status_layanan' => 'SELESAI',
                'catatan_petugas' => 'SK KGB telah diterbitkan dan dikirimkan tembusan ke BPKAD untuk penyesuaian gaji berkala.',
            ],
            [
                'nomor_agenda' => 'REG-PKL/2026/0004',
                'jenis_layanan' => 'Layanan Izin Praktik Kerja Industri (PKL Siswa/Mahasiswa)',
                'nama_pemohon' => 'Rizky Pratama Saputra (Ketua Kelompok)',
                'nip_nik_nisn' => 'NISN: 0078492011',
                'pangkat_golongan' => 'Siswa Kelas XI',
                'jabatan_unit_kerja' => 'SMK Negeri 1 Ambon (Jurusan TKJ)',
                'kabupaten_kota' => 'Kota Ambon',
                'tanggal_permohonan' => '2026-08-15',
                'tanggal_selesai' => '2026-08-18',
                'nomor_sk_surat' => '421.5/312-PKL/DISDIKBUD/2026',
                'perihal_deskripsi' => 'Permohonan izin Praktik Kerja Lapangan (PKL) 5 orang siswa jurusan Teknik Komputer & Jaringan di UPTD BTKP Maluku.',
                'status_layanan' => 'SELESAI',
                'catatan_petugas' => 'Surat Izin PKL diterbitkan untuk periode 1 September - 30 November 2026.',
            ],
            [
                'nomor_agenda' => 'REG-CUTI/2026/0005',
                'jenis_layanan' => 'Layanan Izin Cuti PNS',
                'nama_pemohon' => 'Siti Rahmawati Lestaluhu, S.Sos',
                'nip_nik_nisn' => '199008142015032005',
                'pangkat_golongan' => 'Penata Muda Tk. I (III/b)',
                'jabatan_unit_kerja' => 'Pengadministrasi Umum - Subbag Kepegawaian Disdikbud Maluku',
                'kabupaten_kota' => 'Kota Ambon',
                'tanggal_permohonan' => '2026-09-01',
                'tanggal_selesai' => '2026-09-03',
                'nomor_sk_surat' => '850/045-CUTI/DISDIKBUD/2026',
                'perihal_deskripsi' => 'Pengajuan Cuti Melahirkan selama 3 (tiga) bulan terhitung mulai tanggal 15 September 2026.',
                'status_layanan' => 'SELESAI',
                'catatan_petugas' => 'Surat Izin Cuti Melahirkan disetujui Kepala Dinas.',
            ],
            [
                'nomor_agenda' => 'REG-CERAI/2026/0006',
                'jenis_layanan' => 'Layanan Izin Perceraian PNS',
                'nama_pemohon' => 'Petrus Latuheru, S.Pd',
                'nip_nik_nisn' => '197906112005011008',
                'pangkat_golongan' => 'Penata Tk. I (III/d)',
                'jabatan_unit_kerja' => 'Guru Matematika - SMA Negeri 1 Tual',
                'kabupaten_kota' => 'Kota Tual',
                'tanggal_permohonan' => '2026-08-20',
                'tanggal_selesai' => null,
                'nomor_sk_surat' => null,
                'perihal_deskripsi' => 'Permohonan surat izin untuk melakukan gugatan perceraian sesuai PP No. 45 Tahun 1990.',
                'status_layanan' => 'PROSES_VERIFIKASI',
                'catatan_petugas' => 'Tahap mediasi internal kepegawaian pertama telah dilaksanakan. Berita acara pemeriksaan sedang disusun.',
            ],
            [
                'nomor_agenda' => 'REG-IB-TB/2026/0007',
                'jenis_layanan' => 'Penerbitan Izin Belajar dan Tugas Belajar',
                'nama_pemohon' => 'Yolanda Sahertian, M.Pd',
                'nip_nik_nisn' => '198811252011012014',
                'pangkat_golongan' => 'Penata (III/c)',
                'jabatan_unit_kerja' => 'Guru Bahasa Inggris - SMAN 1 Siwalima Ambon',
                'kabupaten_kota' => 'Kota Ambon',
                'tanggal_permohonan' => '2026-07-28',
                'tanggal_selesai' => '2026-08-10',
                'nomor_sk_surat' => '892.2/189-IB/DISDIKBUD/2026',
                'perihal_deskripsi' => 'Pengajuan Izin Belajar Program Doktor (S3) Pendidikan Bahasa di Universitas Negeri Malang (Kuliah Mandiri di Luar Jam Dinas).',
                'status_layanan' => 'SELESAI',
                'catatan_petugas' => 'Surat Izin Belajar disetujui dan diterbitkan.',
            ],
            [
                'nomor_agenda' => 'REG-PENSIUN/2026/0008',
                'jenis_layanan' => 'Usul Pensiun PNS',
                'nama_pemohon' => 'Dra. Wilhelmina Soumokil',
                'nip_nik_nisn' => '196612051992032003',
                'pangkat_golongan' => 'Pembina Utama Muda (IV/c)',
                'jabatan_unit_kerja' => 'Guru Ahli Utama - SMA Negeri 1 Saparua',
                'kabupaten_kota' => 'Maluku Tengah',
                'tanggal_permohonan' => '2026-08-05',
                'tanggal_selesai' => null,
                'nomor_sk_surat' => null,
                'perihal_deskripsi' => 'Pengusulan Pensiun Batas Usia Pensiun (BUP) 60 Tahun TMT 1 Januari 2027.',
                'status_layanan' => 'DISETUJUI',
                'catatan_petugas' => 'Berkas lengkap dan telah diusulkan melalui SAPK / SIASN BKN Regional IV Makassar.',
            ],
            [
                'nomor_agenda' => 'REG-RISET/2026/0009',
                'jenis_layanan' => 'Layanan Izin Penelitian',
                'nama_pemohon' => 'Grace Manuhutu (Mahasiswi S1)',
                'nip_nik_nisn' => 'NIM: 2022-72-045',
                'pangkat_golongan' => 'Mahasiswa FKIP',
                'jabatan_unit_kerja' => 'Universitas Pattimura Ambon',
                'kabupaten_kota' => 'Kota Ambon',
                'tanggal_permohonan' => '2026-09-02',
                'tanggal_selesai' => '2026-09-04',
                'nomor_sk_surat' => '070/418-RISET/DISDIKBUD/2026',
                'perihal_deskripsi' => 'Permohonan Izin Penelitian Skripsi: Efektivitas Penggunaan Platform Digital SIMANDAT SIWALIMA dalam Peningkatan Literasi Guru SMA.',
                'status_layanan' => 'SELESAI',
                'catatan_petugas' => 'Surat Izin Penelitian resmi diterbitkan untuk 5 sekolah sasaran di Kota Ambon.',
            ],
            [
                'nomor_agenda' => 'REG-GELAR/2026/0010',
                'jenis_layanan' => 'Usul Penyesuaian Gelar Akademik',
                'nama_pemohon' => 'Abdul Malik Rumaf, S.Pd., M.Pd',
                'nip_nik_nisn' => '198402172009041003',
                'pangkat_golongan' => 'Penata Tk. I (III/d)',
                'jabatan_unit_kerja' => 'Guru Geografi - SMA Negeri 1 Bula',
                'kabupaten_kota' => 'Seram Bagian Timur',
                'tanggal_permohonan' => '2026-08-12',
                'tanggal_selesai' => null,
                'nomor_sk_surat' => null,
                'perihal_deskripsi' => 'Pengusulan pencantuman gelar Magister Pendidikan (M.Pd) lulusan Universitas Pattimura pada profil Kepegawaian SIASN BKN.',
                'status_layanan' => 'PROSES_VERIFIKASI',
                'catatan_petugas' => 'Verifikasi keaslian ijazah PDDikti valid. Menunggu persetujuan Pertek BKN.',
            ],
        ];

        foreach ($samples as $s) {
            $s['user_id'] = $adminId;
            $s['petugas_nama'] = $adminName;
            SimandatKepegawaianLayanan::create($s);
        }
    }
}
