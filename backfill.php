<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$layanan = \App\Models\SimandatSmaLayanan::find(1);
if ($layanan && $layanan->file_hasil_sk) {
    \App\Models\SimandatDokumenLayanan::create([
        'kategori' => 'Surat Keluar',
        'nama_dokumen' => 'SK/Rekomendasi SMA: ' . $layanan->nomor_registrasi,
        'keterangan' => 'Surat Terbit (SK) untuk layanan SMA: ' . $layanan->nama_pemohon,
        'file_path' => $layanan->file_hasil_sk,
        'file_type' => pathinfo($layanan->file_nama_hasil_sk ?? '', PATHINFO_EXTENSION) ?: 'pdf',
        'file_size' => $layanan->file_size ?? 0,
        'user_id' => $layanan->user_id ?? 1,
        'petugas_nama' => 'Kepala Dinas',
        'status' => 'AKTIF',
    ]);
    
    // Also sync to Pangkalan Dokumen and Laporan just to be complete
    \App\Models\SimandatDocument::create([
        'document_number' => $layanan->nomor_surat_keluar ?? $layanan->nomor_registrasi,
        'title' => 'SK/Rekomendasi SMA: ' . $layanan->nama_pemohon,
        'category' => 'Surat Keputusan (SK)',
        'division' => 'Bidang Pembinaan SMA',
        'description' => 'SK / Surat Rekomendasi resmi hasil verifikasi layanan SMA.',
        'file_path' => $layanan->file_hasil_sk,
        'file_size' => $layanan->file_size ?? 0,
        'status' => 'AKTIF',
        'uploaded_by' => $layanan->user_id ?? 1,
    ]);
    
    \App\Models\SimandatLaporan::create([
        'nomor_laporan' => $layanan->nomor_surat_keluar ?? $layanan->nomor_registrasi,
        'kategori_laporan' => 'Kinerja Bidang',
        'judul_laporan' => 'SK/Rekomendasi SMA: ' . $layanan->nama_pemohon,
        'tanggal_laporan' => date('Y-m-d'),
        'pembuat_laporan' => 'Kepala Dinas / Verifikator',
        'file_dokumen' => $layanan->file_hasil_sk,
        'user_id' => $layanan->user_id ?? 1,
    ]);
    
    echo "Synced!";
} else {
    echo "No file to sync or not found.";
}
