<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TvriTuRingkasan;
use App\Models\TvriTuSop;
use App\Models\TvriSuratMasuk;
use App\Models\TvriSuratKeluar;
use App\Models\TvriAgendaKegiatan;
use App\Models\TvriLayananAdm;
use Illuminate\Support\Facades\DB;

class TvriTuDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ringkasan Dashboard TU
        $kpiData = [
            [
                'indikator' => 'Persuratan Masuk',
                'target' => '120 Surat',
                'realisasi' => '128 Surat',
                'capaian' => 107,
                'status' => 'Tercapai',
                'keterangan' => 'Arus surat masuk terkelola cepat dan terekam sistem'
            ],
            [
                'indikator' => 'Persuratan Keluar',
                'target' => '90 Surat',
                'realisasi' => '96 Surat',
                'capaian' => 107,
                'status' => 'Tercapai',
                'keterangan' => 'Naskah dinas keluar sesuai tata persuratan'
            ],
            [
                'indikator' => 'Kecepatan Disposisi (<24 Jam)',
                'target' => '85%',
                'realisasi' => '89%',
                'capaian' => 105,
                'status' => 'Sangat Baik',
                'keterangan' => 'Disposisi pimpinan diproses dan diteruskan tepat waktu'
            ],
            [
                'indikator' => 'Penyelesaian Layanan Adm',
                'target' => '90%',
                'realisasi' => '94%',
                'capaian' => 104,
                'status' => 'Sangat Baik',
                'keterangan' => 'Layanan pegawai & stakeholder selesai sesuai SLA'
            ],
            [
                'indikator' => 'Digitalisasi Arsip',
                'target' => '75%',
                'realisasi' => '68%',
                'capaian' => 91,
                'status' => 'Dalam Proses',
                'keterangan' => 'Alih media digital terus berjalan menuju target 75%'
            ]
        ];

        $notifikasiData = [
            [
                'tipe' => 'Surat Perlu Disposisi',
                'judul' => 'Surat Menkominfo perihal Rakor Digital butuh disposisi Kastasiun',
                'level' => 'Baru',
                'waktu' => '02 Sep 2025, 08:30 WIT'
            ],
            [
                'tipe' => 'Batas Waktu Disposisi',
                'judul' => 'Disposisi TVRI Pusat perihal Juknis Pilkada tersisa 2 hari',
                'level' => 'Penting',
                'waktu' => '01 Sep 2025, 14:00 WIT'
            ],
            [
                'tipe' => 'Agenda Hari Ini',
                'judul' => 'Rakor Evaluasi Siaran pukul 09:00 WIT di R. Rapat Lt. 2',
                'level' => 'Hari Ini',
                'waktu' => '02 Sep 2025, 09:00 WIT'
            ],
            [
                'tipe' => 'Layanan Tertunda',
                'judul' => '1 permohonan legalisasi dokumen menunggu verifikasi kasubbag',
                'level' => 'Tertunda',
                'waktu' => '01 Sep 2025, 16:45 WIT'
            ],
            [
                'tipe' => 'Pengingat Arsip',
                'judul' => 'Jadwal pemusnahan arsip retensi 2020 direncanakan akhir September',
                'level' => 'Info',
                'waktu' => '30 Agu 2025, 11:20 WIT'
            ]
        ];

        TvriTuRingkasan::updateOrCreate(
            ['id' => 1],
            [
                'periode' => 'September 2025',
                'header_waktu' => 'Selasa, 2 September 2025 | 10:24 WIT',
                'online_status' => 'Online | Periode: September 2025',
                'slogan_header' => 'TERTIB ADMINISTRASI • CEPAT LAYANAN • AKURAT DATA • MENDUKUNG KINERJA',
                'slogan_sub' => 'Maluku Bisa!',
                'surat_masuk' => 128,
                'diff_surat_masuk' => '+12% dari bulan lalu',
                'surat_keluar' => 96,
                'diff_surat_keluar' => '+8%',
                'disposisi' => 110,
                'diff_disposisi' => '+15%',
                'agenda_kegiatan' => 24,
                'diff_agenda' => '+20%',
                'layanan_adm' => 86,
                'diff_layanan' => '+10%',
                'service_completion_rate' => 94,
                'target_completion_rate' => 90,
                'disposisi_selesai' => 78,
                'disposisi_proses' => 18,
                'disposisi_menunggu' => 9,
                'disposisi_terlambat' => 5,
                'kat_kementerian' => 32,
                'kat_lpp' => 24,
                'kat_pemda' => 20,
                'kat_vertikal' => 16,
                'kat_mitra' => 14,
                'kat_masyarakat' => 12,
                'kat_lainnya' => 10,
                'layanan_sk' => 24,
                'layanan_data' => 18,
                'layanan_internal' => 22,
                'layanan_eksternal' => 16,
                'layanan_lainnya' => 6,
                'arsip_aktif' => 1250,
                'arsip_inaktif' => 320,
                'arsip_digital' => 850,
                'arsip_retensi' => 42,
                'kepatuhan_administrasi' => 96,
                'target_kepatuhan' => 90,
                'kpi_json' => $kpiData,
                'notifikasi_json' => $notifikasiData,
            ]
        );

        // 2. SOP & Regulasi Tata Usaha
        TvriTuSop::truncate();
        $sops = [
            [
                'kode' => 'SOP-TU-01',
                'judul' => 'SOP Pengelolaan Surat Masuk & Keluar',
                'kategori' => 'Persuratan',
                'versi' => 'v2.1',
                'tanggal_efektif' => '2025-01-10',
                'status' => 'Aktif',
                'file_url' => '#',
                'keterangan' => 'Prosedur penerimaan, pencatatan, agenda, dan penomoran surat dinas TVRI Stasiun Maluku.',
                'urutan' => 1,
            ],
            [
                'kode' => 'SOP-TU-02',
                'judul' => 'SOP Disposisi Elektronik Kepala Stasiun',
                'kategori' => 'Disposisi',
                'versi' => 'v1.4',
                'tanggal_efektif' => '2025-02-01',
                'status' => 'Aktif',
                'file_url' => '#',
                'keterangan' => 'Alur penelaahan, penerusan arahan pimpinan ke bidang terkait melalui modul disposisi terpadu.',
                'urutan' => 2,
            ],
            [
                'kode' => 'SOP-TU-03',
                'judul' => 'SOP Kearsipan & Retensi Dokumen',
                'kategori' => 'Kearsipan',
                'versi' => 'v2.0',
                'tanggal_efektif' => '2025-01-15',
                'status' => 'Aktif',
                'file_url' => '#',
                'keterangan' => 'Tata cara penyimpanan berkas aktif, inaktif, alih media digital, dan prosedur retensi arsip.',
                'urutan' => 3,
            ],
            [
                'kode' => 'SOP-TU-04',
                'judul' => 'SOP Pelayanan Administrasi Pegawai',
                'kategori' => 'Layanan Kepegawaian',
                'versi' => 'v1.8',
                'tanggal_efektif' => '2025-03-01',
                'status' => 'Aktif',
                'file_url' => '#',
                'keterangan' => 'Penerbitan SK tugas, surat keterangan dinas, izin, dan layanan administrasi personalia.',
                'urutan' => 4,
            ],
            [
                'kode' => 'SOP-TU-05',
                'judul' => 'SOP Tata Naskah Dinas LPP TVRI',
                'kategori' => 'Tata Naskah',
                'versi' => 'v3.0',
                'tanggal_efektif' => '2025-01-02',
                'status' => 'Aktif',
                'file_url' => '#',
                'keterangan' => 'Standarisasi format surat dinas, nota dinas, undangan, dan laporan dinas resmi TVRI.',
                'urutan' => 5,
            ],
            [
                'kode' => 'SOP-TU-06',
                'judul' => 'SOP Keterbukaan Informasi Publik',
                'kategori' => 'Informasi Publik',
                'versi' => 'v1.2',
                'tanggal_efektif' => '2025-04-10',
                'status' => 'Aktif',
                'file_url' => '#',
                'keterangan' => 'Mekanisme permohonan data dan informasi dari masyarakat, akademisi, dan stakeholder.',
                'urutan' => 6,
            ],
        ];
        foreach ($sops as $sop) {
            TvriTuSop::create($sop);
        }

        // 3. Surat Masuk Terbaru (5 item)
        TvriSuratMasuk::truncate();
        $suratMasukList = [
            [
                'tanggal' => '2025-09-02',
                'nomor' => 'B-142/KOMINFO/DJAI/09/2025',
                'pengirim' => 'Kementerian Komunikasi dan Informatika RI',
                'perihal' => 'Undangan Rapat Koordinasi Penyiaran Digital Wilayah Timur',
                'kategori' => 'Rahasia',
                'status' => 'Didisposisikan'
            ],
            [
                'tanggal' => '2025-09-01',
                'nomor' => '892/TVRI-PUSAT/DIRUT/08/2025',
                'pengirim' => 'LPP TVRI Pusat Jakarta',
                'perihal' => 'Petunjuk Teknis Pelaksanaan Siaran Pilkada Serentak 2025',
                'kategori' => 'Penting',
                'status' => 'Didisposisikan'
            ],
            [
                'tanggal' => '2025-09-01',
                'nomor' => '045.2/1120/SETDA-MALUKU',
                'pengirim' => 'Sekretariat Daerah Provinsi Maluku',
                'perihal' => 'Permohonan Peliputan Kegiatan HUT Provinsi Maluku ke-80',
                'kategori' => 'Biasa',
                'status' => 'Selesai'
            ],
            [
                'tanggal' => '2025-08-30',
                'nomor' => 'B/215/VIII/2025/KODAM-PTM',
                'pengirim' => 'Kodam XV/Pattimura Ambon',
                'perihal' => 'Pemberitahuan Latihan Gabungan dan Undangan Media',
                'kategori' => 'Penting',
                'status' => 'Diproses'
            ],
            [
                'tanggal' => '2025-08-29',
                'nomor' => '114/UNPATTI/LPPM/VIII/2025',
                'pengirim' => 'Universitas Pattimura Ambon',
                'perihal' => 'Kerja Sama Pengabdian Masyarakat Program Siaran Desa',
                'kategori' => 'Biasa',
                'status' => 'Selesai'
            ],
        ];
        foreach ($suratMasukList as $sm) {
            TvriSuratMasuk::create($sm);
        }

        // 4. Surat Keluar Contoh
        if (TvriSuratKeluar::count() == 0) {
            $suratKeluarList = [
                [
                    'tanggal' => '2025-09-02',
                    'nomor' => '092/TU/TVRI-MLK/09/2025',
                    'tujuan' => 'Dinas Kominfo Provinsi Maluku',
                    'perihal' => 'Konfirmasi Kehadiran Rapat Sinergi Penyiaran Digital',
                    'status' => 'Terkirim'
                ],
                [
                    'tanggal' => '2025-09-01',
                    'nomor' => '091/TU/TVRI-MLK/09/2025',
                    'tujuan' => 'LPP TVRI Pusat Jakarta',
                    'perihal' => 'Laporan Kinerja Administrasi dan Persuratan Bulan Agustus 2025',
                    'status' => 'Terkirim'
                ],
                [
                    'tanggal' => '2025-08-31',
                    'nomor' => '090/TU/TVRI-MLK/08/2025',
                    'tujuan' => 'Universitas Pattimura',
                    'perihal' => 'Persetujuan Kerjasama Program Siaran Desa Berdaya',
                    'status' => 'Selesai'
                ],
            ];
            foreach ($suratKeluarList as $sk) {
                TvriSuratKeluar::create($sk);
            }
        }

        // 5. Agenda Kegiatan Hari Ini & Minggu Ini
        TvriAgendaKegiatan::truncate();
        $agendaList = [
            [
                'tanggal' => '2025-09-02',
                'waktu' => '09:00 - 11:30 WIT',
                'kegiatan' => 'Rapat Koordinasi Evaluasi Siaran Bulan Agustus',
                'lokasi' => 'Ruang Rapat Lt. 2 / Hybrid',
                'pic' => 'Internal TU & Bidang',
                'jenis' => 'Hari Ini',
                'status' => 'Terjadwal'
            ],
            [
                'tanggal' => '2025-09-02',
                'waktu' => '14:00 - 15:30 WIT',
                'kegiatan' => 'Koordinasi Tim Humas Pemprov Maluku',
                'lokasi' => 'Ruang Tamu KTU',
                'pic' => 'Eksternal',
                'jenis' => 'Hari Ini',
                'status' => 'Terjadwal'
            ],
            [
                'tanggal' => '2025-09-03',
                'waktu' => '10:00 WIT',
                'kegiatan' => 'Sosialisasi SOP Pengelolaan Arsip Digital',
                'lokasi' => 'Aula Pattimura',
                'pic' => 'Tim Tata Usaha',
                'jenis' => 'Minggu Ini',
                'status' => 'Mendatang'
            ],
            [
                'tanggal' => '2025-09-04',
                'waktu' => '09:00 WIT',
                'kegiatan' => 'Verifikasi Dokumen Kerja Sama Mitra TVRI',
                'lokasi' => 'Ruang Rapat Lt. 2',
                'pic' => 'Tim Kerjasama & TU',
                'jenis' => 'Minggu Ini',
                'status' => 'Mendatang'
            ],
            [
                'tanggal' => '2025-09-05',
                'waktu' => '08:00 WIT',
                'kegiatan' => 'Senam Pagi Bersama & Penataan Ruang Arsip',
                'lokasi' => 'Halaman Depan TVRI',
                'pic' => 'Seluruh Pegawai & TU',
                'jenis' => 'Minggu Ini',
                'status' => 'Mendatang'
            ],
        ];
        foreach ($agendaList as $ag) {
            TvriAgendaKegiatan::create($ag);
        }

        // 6. Layanan Administrasi
        TvriLayananAdm::truncate();
        $layananList = [
            [
                'jenis' => 'SK & Keputusan',
                'jumlah' => 24,
                'keterangan' => '24 Selesai (96%)'
            ],
            [
                'jenis' => 'Permintaan Data & Informasi',
                'jumlah' => 18,
                'keterangan' => '18 Selesai (90%)'
            ],
            [
                'jenis' => 'Surat Keterangan Internal',
                'jumlah' => 22,
                'keterangan' => '22 Selesai (100%)'
            ],
            [
                'jenis' => 'Layanan Tamu & Eksternal',
                'jumlah' => 16,
                'keterangan' => '16 Selesai (94%)'
            ],
            [
                'jenis' => 'Legalisasi Dokumen',
                'jumlah' => 6,
                'keterangan' => '6 Selesai (83%)'
            ],
        ];
        foreach ($layananList as $ly) {
            TvriLayananAdm::create($ly);
        }
    }
}
