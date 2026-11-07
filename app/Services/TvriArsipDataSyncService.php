<?php

namespace App\Services;

use App\Models\SimandatDocument;
use App\Models\SimandatDocumentFolder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class TvriArsipDataSyncService
{
    /**
     * Folder Mapping per Bidang / Divisi TVRI Maluku
     */
    public const FOLDER_MAP = [
        'SOP' => 1,
        'Tata Usaha' => 2,
        'Berita' => 3,
        'Umum' => 4,
        'Program' => 5,
        'KBM' => 6,
        'KMB' => 6,
        'promo' => 7,
        'Promo' => 7,
        'Pengembangan Usaha' => 8,
        'Teknik' => 9,
        'Teknik Studio' => 9,
        'Teknik Transmisi' => 10,
        'Keuangan' => 11,
    ];

    /**
     * Daftarkan model hooks / listeners untuk auto-sync saat create/update data
     */
    public static function registerModelHooks(): void
    {
        $models = [
            // 1. Berita
            \App\Models\TvriBerita::class,
            \App\Models\TvriBeritaProduksi::class,
            \App\Models\TvriBeritaMonitoring::class,
            \App\Models\TvriBeritaDokumentasi::class,
            \App\Models\TvriBeritaAgendaLiputan::class,

            // 2. Program
            \App\Models\TvriProgram::class,
            \App\Models\TvriProgramAgenda::class,
            \App\Models\TvriProgramHighlight::class,
            \App\Models\TvriProgramSop::class,

            // 3. Teknik Studio
            \App\Models\TvriTeknikInsiden::class,
            \App\Models\TvriTeknikJadwal::class,
            \App\Models\TvriTeknikMaintenance::class,
            \App\Models\TvriTeknikTransmitter::class,
            \App\Models\TvriPeralatan::class,

            // 4. Umum & Logistik
            \App\Models\TvriAset::class,
            \App\Models\TvriKendaraan::class,
            \App\Models\TvriSarpras::class,
            \App\Models\TvriStokBarang::class,
            \App\Models\TvriPemeliharaan::class,

            // 5. Konten Media Baru (KMB)
            \App\Models\TvriKonten::class,

            // 6. Tata Usaha (TU)
            \App\Models\TvriSuratMasuk::class,
            \App\Models\TvriSuratKeluar::class,
            \App\Models\TvriAgendaKegiatan::class,
            \App\Models\TvriLayananAdm::class,

            // 7. Promo
            \App\Models\TvriKampanye::class,
            \App\Models\TvriMateriPromo::class,

            // 8. Teknik Transmisi
            \App\Models\TvriStasiunPemancar::class,
            \App\Models\TvriTransmisiLog::class,
            \App\Models\TvriTransmisiPemeliharaan::class,
            \App\Models\TvriGangguan::class,

            // 9. Pengembangan Usaha
            \App\Models\TvriKegiatanUsaha::class,
            \App\Models\TvriMitra::class,
            \App\Models\TvriPendapatan::class,

            // 10. Keuangan
            \App\Models\TvriAnggaran::class,
            \App\Models\TvriPnbp::class,
            \App\Models\TvriPiutang::class,
        ];

        foreach ($models as $modelClass) {
            if (class_exists($modelClass)) {
                $modelClass::saved(function ($item) {
                    try {
                        self::syncModelItem($item);
                    } catch (\Throwable $e) {
                        Log::warning('TvriArsipDataSyncService auto-sync warning: ' . $e->getMessage());
                    }
                });
            }
        }
    }

    /**
     * Sinkronisasi record model tertentu ke SimandatDocument (Arsip Data)
     */
    public static function syncModelItem($item): ?SimandatDocument
    {
        $id = $item->id ?? null;
        if (!$id) return null;

        $division = 'Umum';
        $category = 'Data Pokok & Statistik';
        $folderId = 4;
        $title = '';
        $docNumber = '';
        $docDate = now()->toDateString();
        $desc = '';
        $confidentiality = 'PUBLIK';

        if ($item instanceof \App\Models\TvriBerita) {
            $division = 'Berita';
            $category = 'Dokumentasi Kegiatan & Media';
            $folderId = 3;
            $docNumber = 'BRT-' . str_pad($item->id, 5, '0', STR_PAD_LEFT);
            $title = $item->judul ?: 'Berita TVRI #' . $item->id;
            $docDate = $item->tanggal_tayang ?? $item->tanggal_rencana ?? $item->created_at ?? now();
            $desc = "Berita/Liputan: {$item->kategori} | Lokasi: {$item->lokasi} | Status: {$item->status} | Reporter: {$item->reporter}";
        } elseif ($item instanceof \App\Models\TvriBeritaProduksi) {
            $division = 'Berita';
            $category = 'Dokumentasi Kegiatan & Media';
            $folderId = 3;
            $docNumber = 'BRT-PROD-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = $item->judul_paket ?: 'Paket Produksi Berita #' . $item->id;
            $docDate = $item->created_at ?? now();
            $desc = "Produksi Paket: {$item->kategori} | Durasi: {$item->durasi} | Produser: {$item->produser} | Status: {$item->status}";
        } elseif ($item instanceof \App\Models\TvriBeritaMonitoring) {
            $division = 'Berita';
            $category = 'Laporan Kinerja & Akuntabilitas';
            $folderId = 3;
            $docNumber = 'BRT-MON-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = $item->judul_berita ?: 'Monitoring Berita #' . $item->id;
            $docDate = $item->tanggal ?? $item->created_at ?? now();
            $desc = "Monitoring Berita: Media {$item->media_pantau} | Tone: {$item->tone}";
        } elseif ($item instanceof \App\Models\TvriBeritaDokumentasi) {
            $division = 'Berita';
            $category = 'Dokumentasi Kegiatan & Media';
            $folderId = 3;
            $docNumber = 'BRT-DOK-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = $item->judul_dokumentasi ?: 'Dokumentasi Siaran #' . $item->id;
            $docDate = $item->tanggal ?? $item->created_at ?? now();
            $desc = "Dokumentasi Siaran: Kategori {$item->kategori} | Lokasi: {$item->lokasi}";
        } elseif ($item instanceof \App\Models\TvriBeritaAgendaLiputan) {
            $division = 'Berita';
            $category = 'Dokumentasi Kegiatan & Media';
            $folderId = 3;
            $docNumber = 'BRT-AGD-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = $item->nama_agenda ?: 'Agenda Liputan #' . $item->id;
            $docDate = $item->tanggal ?? $item->created_at ?? now();
            $desc = "Agenda Liputan: Lokasi {$item->lokasi} | Tim: {$item->tim_liputan} | Status: {$item->status}";
        } elseif ($item instanceof \App\Models\TvriProgram) {
            $division = 'Program';
            $category = 'Dokumentasi Kegiatan & Media';
            $folderId = 5;
            $docNumber = 'PRG-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = $item->judul ?: 'Program Siaran #' . $item->id;
            $docDate = $item->target_tayang ?? $item->created_at ?? now();
            $desc = "Program Siaran: Kategori {$item->kategori} | Tahap: {$item->tahap} | Status: {$item->status} | PIC: {$item->pic}";
        } elseif ($item instanceof \App\Models\TvriProgramAgenda) {
            $division = 'Program';
            $category = 'Dokumentasi Kegiatan & Media';
            $folderId = 5;
            $docNumber = 'PRG-AGD-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = $item->nama_agenda ?: $item->judul ?: 'Agenda Program #' . $item->id;
            $docDate = $item->tanggal ?? $item->created_at ?? now();
            $desc = "Agenda Program Siaran: {$item->keterangan}";
        } elseif ($item instanceof \App\Models\TvriProgramHighlight) {
            $division = 'Program';
            $category = 'Dokumentasi Kegiatan & Media';
            $folderId = 5;
            $docNumber = 'PRG-HL-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = $item->judul_acara ?: $item->judul ?: 'Highlight Program #' . $item->id;
            $docDate = $item->tanggal_tayang ?? $item->created_at ?? now();
            $desc = "Highlight Acara TVRI: Jam {$item->jam_tayang}";
        } elseif ($item instanceof \App\Models\TvriProgramSop) {
            $division = 'Program';
            $category = 'Pedoman, Panduan & SOP';
            $folderId = 1;
            $docNumber = $item->nomor_dokumen ?: ('SOP-PRG-' . str_pad($item->id, 3, '0', STR_PAD_LEFT));
            $title = $item->judul ?: 'SOP Program Siaran #' . $item->id;
            $docDate = $item->created_at ?? now();
            $desc = "Standar Operasional Prosedur Program Siaran TVRI Maluku";
        } elseif ($item instanceof \App\Models\TvriTeknikInsiden) {
            $division = 'Teknik Studio';
            $category = 'Laporan Kinerja & Akuntabilitas';
            $folderId = 9;
            $docNumber = 'TEK-INS-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = ($item->peralatan ?: $item->lokasi ?: 'Insiden Teknik') . ' #' . $item->id;
            $docDate = $item->tanggal ?? $item->created_at ?? now();
            $desc = "Insiden/Gangguan Teknik Studio: {$item->deskripsi} | Status: {$item->status}";
        } elseif ($item instanceof \App\Models\TvriTeknikJadwal) {
            $division = 'Teknik Studio';
            $category = 'Laporan Kinerja & Akuntabilitas';
            $folderId = 9;
            $docNumber = 'TEK-JDW-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = $item->nama_kegiatan ?: 'Jadwal Produksi Teknik #' . $item->id;
            $docDate = $item->tanggal ?? $item->created_at ?? now();
            $desc = "Jadwal Operasional Studio: {$item->studio} | Status: {$item->status}";
        } elseif ($item instanceof \App\Models\TvriTeknikMaintenance) {
            $division = 'Teknik Studio';
            $category = 'Laporan Kinerja & Akuntabilitas';
            $folderId = 9;
            $docNumber = 'TEK-MNT-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = ($item->peralatan ?: 'Maintenance Teknik') . ' #' . $item->id;
            $docDate = $item->tanggal ?? $item->created_at ?? now();
            $desc = "Pemeliharaan Perangkat Studio: {$item->jenis_perawatan} | Kondisi: {$item->kondisi}";
        } elseif ($item instanceof \App\Models\TvriTeknikTransmitter) {
            $division = 'Teknik Studio';
            $category = 'Laporan Kinerja & Akuntabilitas';
            $folderId = 9;
            $docNumber = 'TEK-TXM-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = ($item->stasiun ?: 'Pemancar Studio') . ' #' . $item->id;
            $docDate = $item->created_at ?? now();
            $desc = "Data Teknis Pemancar: Frekuensi {$item->frekuensi} | Status: {$item->status}";
        } elseif ($item instanceof \App\Models\TvriPeralatan) {
            $division = 'Teknik Studio';
            $category = 'Data Pokok & Statistik';
            $folderId = 9;
            $docNumber = $item->kode_aset ?: ('ALAT-' . str_pad($item->id, 4, '0', STR_PAD_LEFT));
            $title = $item->nama_peralatan ?: 'Inventaris Peralatan #' . $item->id;
            $docDate = $item->created_at ?? now();
            $desc = "Peralatan Studio: {$item->spesifikasi} | Kondisi: {$item->kondisi}";
        } elseif ($item instanceof \App\Models\TvriAset) {
            $division = 'Umum';
            $category = 'Data Pokok & Statistik';
            $folderId = 4;
            $docNumber = $item->kode_bmn ?: ('BMN-' . str_pad($item->id, 5, '0', STR_PAD_LEFT));
            $title = $item->nama_aset ?: 'Aset BMN TVRI #' . $item->id;
            $docDate = $item->tahun_perolehan ? date('Y-01-01', strtotime($item->tahun_perolehan . '-01-01')) : ($item->created_at ?? now());
            $desc = "Aset BMN TVRI: Kategori {$item->kategori} | Kondisi: {$item->kondisi} | Nilai: Rp " . number_format($item->nilai_perolehan ?? 0);
        } elseif ($item instanceof \App\Models\TvriKendaraan) {
            $division = 'Umum';
            $category = 'Data Pokok & Statistik';
            $folderId = 4;
            $docNumber = $item->plat_nomor ?: ('KND-' . str_pad($item->id, 4, '0', STR_PAD_LEFT));
            $title = ($item->nama_kendaraan ?: 'Kendaraan Operasional') . ' (' . ($item->plat_nomor ?: '#' . $item->id) . ')';
            $docDate = $item->created_at ?? now();
            $desc = "Kendaraan Operasional: {$item->jenis} | Pengemudi: {$item->pengemudi} | Kondisi: {$item->kondisi}";
        } elseif ($item instanceof \App\Models\TvriSarpras) {
            $division = 'Umum';
            $category = 'Data Pokok & Statistik';
            $folderId = 4;
            $docNumber = 'SARPRAS-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = $item->nama_sarpras ?: 'Sarana Prasarana #' . $item->id;
            $docDate = $item->created_at ?? now();
            $desc = "Fasilitas Sarpras: Lokasi {$item->lokasi} | Kondisi: {$item->kondisi}";
        } elseif ($item instanceof \App\Models\TvriStokBarang) {
            $division = 'Umum';
            $category = 'Data Pokok & Statistik';
            $folderId = 4;
            $docNumber = $item->kode_barang ?: ('STK-' . str_pad($item->id, 4, '0', STR_PAD_LEFT));
            $title = $item->nama_barang ?: 'Stok Barang Logistik #' . $item->id;
            $docDate = $item->created_at ?? now();
            $desc = "Persediaan Logistik: Jumlah {$item->jumlah} {$item->satuan} | Kategori: {$item->kategori}";
        } elseif ($item instanceof \App\Models\TvriPemeliharaan) {
            $division = 'Umum';
            $category = 'Laporan Kinerja & Akuntabilitas';
            $folderId = 4;
            $docNumber = 'MNT-UMM-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = ($item->nama_barang ?: 'Pemeliharaan Fasilitas') . ' #' . $item->id;
            $docDate = $item->tanggal ?? $item->created_at ?? now();
            $desc = "Pemeliharaan: {$item->jenis_pemeliharaan} | Biaya: Rp " . number_format($item->biaya ?? 0) . " | Status: {$item->status}";
        } elseif ($item instanceof \App\Models\TvriKonten) {
            $division = 'KBM';
            $category = 'Dokumentasi Kegiatan & Media';
            $folderId = 6;
            $docNumber = 'KMB-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = $item->judul ?: 'Konten Media Baru #' . $item->id;
            $docDate = $item->tanggal_unggah ?? $item->created_at ?? now();
            $desc = "Konten Media Baru: Platform {$item->platform} | Views: " . number_format($item->views ?? 0) . " | Status: {$item->status}";
        } elseif ($item instanceof \App\Models\TvriSuratMasuk) {
            $division = 'Tata Usaha';
            $category = 'Surat Keputusan (SK)';
            $folderId = 2;
            $docNumber = $item->nomor ?: ('SM-' . str_pad($item->id, 4, '0', STR_PAD_LEFT));
            $title = $item->perihal ?: 'Surat Masuk #' . $item->id;
            $docDate = $item->tanggal ?? $item->created_at ?? now();
            $desc = "Surat Masuk: Pengirim {$item->pengirim} | Kategori: {$item->kategori} | Status: {$item->status}";
        } elseif ($item instanceof \App\Models\TvriSuratKeluar) {
            $division = 'Tata Usaha';
            $category = 'Surat Edaran & Instruksi';
            $folderId = 2;
            $docNumber = $item->nomor ?: ('SK-' . str_pad($item->id, 4, '0', STR_PAD_LEFT));
            $title = $item->perihal ?: 'Surat Keluar #' . $item->id;
            $docDate = $item->tanggal ?? $item->created_at ?? now();
            $desc = "Surat Keluar: Tujuan {$item->tujuan} | Kategori: {$item->kategori} | Status: {$item->status}";
        } elseif ($item instanceof \App\Models\TvriAgendaKegiatan) {
            $division = 'Tata Usaha';
            $category = 'Dokumentasi Kegiatan & Media';
            $folderId = 2;
            $docNumber = 'TU-AGD-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = $item->nama_agenda ?: 'Agenda Kegiatan TU #' . $item->id;
            $docDate = $item->tanggal ?? $item->created_at ?? now();
            $desc = "Agenda Kerja Tata Usaha: {$item->lokasi} | Penyelenggara: {$item->penyelenggara}";
        } elseif ($item instanceof \App\Models\TvriLayananAdm) {
            $division = 'Tata Usaha';
            $category = 'Laporan Kinerja & Akuntabilitas';
            $folderId = 2;
            $docNumber = 'TU-LAY-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = ($item->jenis_layanan ?: 'Layanan Administrasi') . ' - ' . ($item->pemohon ?: '#' . $item->id);
            $docDate = $item->created_at ?? now();
            $desc = "Layanan Administrasi TU: Pemohon {$item->pemohon} | Status: {$item->status}";
        } elseif ($item instanceof \App\Models\TvriKampanye) {
            $division = 'Promo';
            $category = 'Dokumentasi Kegiatan & Media';
            $folderId = 7;
            $docNumber = 'PRM-KMP-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = $item->nama_kampanye ?: 'Kampanye Promo #' . $item->id;
            $docDate = $item->created_at ?? now();
            $desc = "Kampanye Promosi: Tema {$item->tema} | Periode: {$item->periode} | Status: {$item->status}";
        } elseif ($item instanceof \App\Models\TvriMateriPromo) {
            $division = 'Promo';
            $category = 'Dokumentasi Kegiatan & Media';
            $folderId = 7;
            $docNumber = 'PRM-MAT-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = $item->judul_promo ?: 'Materi Promo #' . $item->id;
            $docDate = $item->created_at ?? now();
            $desc = "Materi Promosi: Media {$item->jenis_media} | Durasi: {$item->durasi}";
        } elseif ($item instanceof \App\Models\TvriStasiunPemancar) {
            $division = 'Teknik Transmisi';
            $category = 'Laporan Kinerja & Akuntabilitas';
            $folderId = 10;
            $docNumber = 'TX-STN-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = ($item->nama_stasiun ?: 'Stasiun Pemancar') . ' - ' . ($item->wilayah ?: 'Maluku');
            $docDate = $item->created_at ?? now();
            $desc = "Satuan Transmisi Pemancar: Wilayah {$item->wilayah} | Daya: {$item->daya} kW | Status: {$item->status_operasional}";
        } elseif ($item instanceof \App\Models\TvriTransmisiLog) {
            $division = 'Teknik Transmisi';
            $category = 'Laporan Kinerja & Akuntabilitas';
            $folderId = 10;
            $docNumber = 'TX-LOG-' . str_pad($item->id, 5, '0', STR_PAD_LEFT);
            $title = ($item->stasiun ?: 'Log Transmisi') . ' #' . $item->id;
            $docDate = $item->created_at ?? now();
            $desc = "Log Operasional Transmisi: Operator {$item->operator} | Catatan: {$item->catatan}";
        } elseif ($item instanceof \App\Models\TvriTransmisiPemeliharaan) {
            $division = 'Teknik Transmisi';
            $category = 'Laporan Kinerja & Akuntabilitas';
            $folderId = 10;
            $docNumber = 'TX-MNT-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = ($item->stasiun ?: 'Pemeliharaan TX') . ' #' . $item->id;
            $docDate = $item->tanggal ?? $item->created_at ?? now();
            $desc = "Pemeliharaan Transmisi: {$item->uraian_kegiatan} | Petugas: {$item->petugas}";
        } elseif ($item instanceof \App\Models\TvriGangguan) {
            $division = 'Teknik Transmisi';
            $category = 'Laporan Kinerja & Akuntabilitas';
            $folderId = 10;
            $docNumber = 'TX-GNG-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = ($item->stasiun ?: 'Gangguan Transmisi') . ' #' . $item->id;
            $docDate = $item->tanggal ?? $item->created_at ?? now();
            $desc = "Gangguan Siaran Transmisi: {$item->jenis_gangguan} | Status: {$item->status}";
        } elseif ($item instanceof \App\Models\TvriKegiatanUsaha) {
            $division = 'Pengembangan Usaha';
            $category = 'MoU & Kerjasama Kemitraan';
            $folderId = 8;
            $docNumber = 'USH-KGT-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = $item->nama_kegiatan ?: 'Kegiatan Usaha #' . $item->id;
            $docDate = $item->tanggal ?? $item->created_at ?? now();
            $desc = "Pengembangan Usaha / Sponsorship: Kategori {$item->kategori} | Nilai Kontrak: Rp " . number_format($item->nilai_kontrak ?? 0);
        } elseif ($item instanceof \App\Models\TvriMitra) {
            $division = 'Pengembangan Usaha';
            $category = 'MoU & Kerjasama Kemitraan';
            $folderId = 8;
            $docNumber = 'USH-MTR-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = ($item->nama_mitra ?: 'Kemitraan Usaha') . ' - ' . ($item->bidang_kerjasama ?: '#' . $item->id);
            $docDate = $item->created_at ?? now();
            $desc = "Mitra Usaha & Kerjasama: {$item->bidang_kerjasama} | Status: {$item->status}";
        } elseif ($item instanceof \App\Models\TvriPendapatan) {
            $division = 'Pengembangan Usaha';
            $category = 'Laporan Kinerja & Akuntabilitas';
            $folderId = 8;
            $docNumber = 'USH-PNBP-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = ($item->sumber_pnbp ?: 'Realisasi PNBP Usaha') . ' #' . $item->id;
            $docDate = $item->created_at ?? now();
            $desc = "Pendapatan Usaha PNBP: Target Rp " . number_format($item->target ?? 0) . " | Realisasi: Rp " . number_format($item->realisasi ?? 0);
        } elseif ($item instanceof \App\Models\TvriAnggaran) {
            $division = 'Keuangan';
            $category = 'RKA & DPA Perencanaan';
            $folderId = 11;
            $docNumber = $item->kode ?: ('DIPA-' . str_pad($item->id, 4, '0', STR_PAD_LEFT));
            $title = ($item->uraian ?: 'Mata Anggaran DIPA') . ' - TA ' . ($item->tahun ?: date('Y'));
            $docDate = date('Y-01-01', strtotime(($item->tahun ?: date('Y')) . '-01-01'));
            $desc = "DIPA Anggaran Keuangan: Pagu Rp " . number_format($item->pagu ?? 0) . " | Realisasi: Rp " . number_format($item->realisasi ?? 0);
        } elseif ($item instanceof \App\Models\TvriPnbp) {
            $division = 'Keuangan';
            $category = 'Laporan Kinerja & Akuntabilitas';
            $folderId = 11;
            $docNumber = 'PNBP-KEU-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = ($item->jenis_pnbp ?: 'Laporan Penerimaan PNBP') . ' - TA ' . ($item->tahun ?: date('Y'));
            $docDate = date('Y-01-01', strtotime(($item->tahun ?: date('Y')) . '-01-01'));
            $desc = "Laporan Realisasi PNBP Keuangan: Target Rp " . number_format($item->target ?? 0) . " | Realisasi: Rp " . number_format($item->realisasi ?? 0);
        } elseif ($item instanceof \App\Models\TvriPiutang) {
            $division = 'Keuangan';
            $category = 'Laporan Kinerja & Akuntabilitas';
            $folderId = 11;
            $docNumber = 'PIUTANG-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $title = 'Tagihan Piutang Mitra: ' . ($item->mitra ?: '#' . $item->id);
            $docDate = $item->tanggal_tagihan ?? $item->created_at ?? now();
            $desc = "Piutang & Tagihan: Nilai Rp " . number_format($item->nilai ?? 0) . " | Status: {$item->status}";
        } else {
            return null;
        }

        $year = (int) date('Y', strtotime($docDate));
        $user = auth()->user();
        $uploaderName = $user ? $user->name : ('Sistem ' . $division . ' TVRI Maluku');

        return SimandatDocument::updateOrCreate(
            ['document_number' => $docNumber],
            [
                'title' => Str::limit($title, 250),
                'category' => $category,
                'division' => $division,
                'description' => $desc,
                'document_date' => date('Y-m-d', strtotime($docDate)),
                'year' => $year,
                'confidentiality' => $confidentiality,
                'status' => 'AKTIF',
                'current_version' => 'v1.0',
                'file_type' => 'DATA',
                'file_size' => '1024',
                'folder_id' => $folderId,
                'uploader_name' => $uploaderName,
            ]
        );
    }

    /**
     * Sinkronisasi massal seluruh data operasional eksisting ke Arsip Data
     */
    public static function syncAllExistingData(): int
    {
        $count = 0;
        $models = [
            \App\Models\TvriBerita::class,
            \App\Models\TvriBeritaProduksi::class,
            \App\Models\TvriBeritaMonitoring::class,
            \App\Models\TvriBeritaDokumentasi::class,
            \App\Models\TvriBeritaAgendaLiputan::class,
            \App\Models\TvriProgram::class,
            \App\Models\TvriProgramAgenda::class,
            \App\Models\TvriProgramHighlight::class,
            \App\Models\TvriProgramSop::class,
            \App\Models\TvriTeknikInsiden::class,
            \App\Models\TvriTeknikJadwal::class,
            \App\Models\TvriTeknikMaintenance::class,
            \App\Models\TvriTeknikTransmitter::class,
            \App\Models\TvriPeralatan::class,
            \App\Models\TvriAset::class,
            \App\Models\TvriKendaraan::class,
            \App\Models\TvriSarpras::class,
            \App\Models\TvriStokBarang::class,
            \App\Models\TvriPemeliharaan::class,
            \App\Models\TvriKonten::class,
            \App\Models\TvriSuratMasuk::class,
            \App\Models\TvriSuratKeluar::class,
            \App\Models\TvriAgendaKegiatan::class,
            \App\Models\TvriLayananAdm::class,
            \App\Models\TvriKampanye::class,
            \App\Models\TvriMateriPromo::class,
            \App\Models\TvriStasiunPemancar::class,
            \App\Models\TvriTransmisiLog::class,
            \App\Models\TvriTransmisiPemeliharaan::class,
            \App\Models\TvriGangguan::class,
            \App\Models\TvriKegiatanUsaha::class,
            \App\Models\TvriMitra::class,
            \App\Models\TvriPendapatan::class,
            \App\Models\TvriAnggaran::class,
            \App\Models\TvriPnbp::class,
            \App\Models\TvriPiutang::class,
        ];

        foreach ($models as $modelClass) {
            if (class_exists($modelClass)) {
                try {
                    $items = $modelClass::all();
                    foreach ($items as $item) {
                        self::syncModelItem($item);
                        $count++;
                    }
                } catch (\Throwable $e) {
                    // Ignore non-existent tables
                }
            }
        }

        return $count;
    }
}
