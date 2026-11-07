const fs = require('fs');
const file = 'app/Http/Controllers/Pendidikan/SmaLayananController.php';
let content = fs.readFileSync(file, 'utf8');

const syncStore = `        // SYNC 1: Dokumen Layanan
        if (isset($filePermohonanPath)) {
            \\App\\Models\\SimandatDokumenLayanan::create([
                'kategori' => 'Kearsipan',
                'nama_dokumen' => 'Permohonan SMA: ' . $layanan->nomor_registrasi . ' - ' . $layanan->nama_pemohon,
                'keterangan' => 'Layanan SMA (' . $layanan->jenis_layanan . '). ' . $layanan->perihal_uraian,
                'file_path' => $filePermohonanPath,
                'file_type' => pathinfo($filePermohonanName ?? '', PATHINFO_EXTENSION) ?: 'pdf',
                'file_size' => $fileSize ?? 0,
                'user_id' => $layanan->user_id,
                'petugas_nama' => auth()->user()->name ?? 'Pemohon SMA',
                'status' => 'AKTIF',
            ]);
            
            // SYNC 2: Laporan
            \\App\\Models\\SimandatLaporan::create([
                'nomor_laporan' => $layanan->nomor_registrasi,
                'kategori_laporan' => 'Pelayanan Publik',
                'judul_laporan' => 'Permohonan SMA: ' . $layanan->nama_pemohon,
                'tanggal_laporan' => $layanan->tanggal_pengajuan,
                'pembuat_laporan' => $layanan->nama_pemohon,
                'file_dokumen' => $filePermohonanPath,
                'user_id' => $layanan->user_id,
            ]);

            // SYNC 3: Pangkalan Dokumen
            \\App\\Models\\SimandatDocument::create([
                'document_number' => $layanan->nomor_registrasi,
                'title' => 'Berkas SMA: ' . $layanan->nama_pemohon,
                'category' => 'Lainnya',
                'division' => 'Bidang Pembinaan SMA',
                'description' => 'Berkas persyaratan permohonan layanan SMA.',
                'file_path' => $filePermohonanPath,
                'file_size' => $fileSize ?? 0,
                'status' => 'AKTIF',
                'uploaded_by' => $layanan->user_id,
            ]);
        }`;

const syncUpdate = `        // SYNC SK / Hasil Rekomendasi ke Repositori jika diunggah
        if ($request->hasFile('file_hasil_sk') && isset($fileHasilSkPath)) {
            \\App\\Models\\SimandatDokumenLayanan::create([
                'kategori' => 'Surat Keluar',
                'nama_dokumen' => 'SK/Rekomendasi SMA: ' . $layanan->nomor_registrasi,
                'keterangan' => 'Surat Terbit (SK) untuk layanan SMA: ' . $layanan->nama_pemohon,
                'file_path' => $fileHasilSkPath,
                'file_type' => pathinfo($fileHasilSkName ?? '', PATHINFO_EXTENSION) ?: 'pdf',
                'file_size' => $fileSize ?? 0,
                'user_id' => auth()->id() ?? 1,
                'petugas_nama' => auth()->user()->name ?? 'Kepala Dinas',
                'status' => 'AKTIF',
            ]);

            \\App\\Models\\SimandatDocument::create([
                'document_number' => $layanan->nomor_surat_keluar ?? $layanan->nomor_registrasi,
                'title' => 'SK/Rekomendasi SMA: ' . $layanan->nama_pemohon,
                'category' => 'Surat Keputusan (SK)',
                'division' => 'Bidang Pembinaan SMA',
                'description' => 'SK / Surat Rekomendasi resmi hasil verifikasi layanan SMA.',
                'file_path' => $fileHasilSkPath,
                'file_size' => $fileSize ?? 0,
                'status' => 'AKTIF',
                'uploaded_by' => auth()->id() ?? 1,
            ]);
            
            \\App\\Models\\SimandatLaporan::create([
                'nomor_laporan' => $layanan->nomor_surat_keluar ?? $layanan->nomor_registrasi,
                'kategori_laporan' => 'Kinerja Bidang',
                'judul_laporan' => 'SK/Rekomendasi SMA: ' . $layanan->nama_pemohon,
                'tanggal_laporan' => date('Y-m-d'),
                'pembuat_laporan' => 'Kepala Dinas / Verifikator',
                'file_dokumen' => $fileHasilSkPath,
                'user_id' => auth()->id() ?? 1,
            ]);
        }`;

// Replace store
content = content.replace(/(\]\);\s+ActivityLogger::log\(\s+'create_sma_layanan',)/m, "];\n\n" + syncStore + "\n\n        ActivityLogger::log(\n            'create_sma_layanan',");

// Replace update
content = content.replace(/(\]\);\s+ActivityLogger::log\(\s+'update_sma_layanan',)/m, "];\n\n" + syncUpdate + "\n\n        ActivityLogger::log(\n            'update_sma_layanan',");

fs.writeFileSync(file, content);
console.log("SMA Sync injected via Node!");
