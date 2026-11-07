<?php
$file = 'app/Http/Controllers/Pendidikan/SmkLayananController.php';
$content = file_get_contents($file);

// Normalize newlines to \n to make regex easier
$content = str_replace("\r\n", "\n", $content);

// Remove ALL lines that contain 'file_size' =>  ?? 0,
$content = preg_replace("/[ \t]*'file_size'[ \t]*=>[ \t]*\\\[ \t]*\?\?[ \t]*0,?\n/m", "", $content);

// Now re-insert carefully exactly once.
// SYNC 1: Dokumen Layanan
$content = str_replace(
    "'file_type' => pathinfo(\ ?? '', PATHINFO_EXTENSION) ?: 'pdf',",
    "'file_type' => pathinfo(\ ?? '', PATHINFO_EXTENSION) ?: 'pdf',\n                'file_size' => \ ?? 0,",
    $content
);

// SYNC 3: Pangkalan Dokumen (for filePermohonan)
$content = str_replace(
    "'description' => 'Berkas persyaratan permohonan layanan SMK.',\n                'file_path' => \,",
    "'description' => 'Berkas persyaratan permohonan layanan SMK.',\n                'file_path' => \,\n                'file_size' => \ ?? 0,",
    $content
);

// SYNC SK: Dokumen Layanan (update)
$content = str_replace(
    "'file_type' => pathinfo(\ ?? '', PATHINFO_EXTENSION) ?: 'pdf',",
    "'file_type' => pathinfo(\ ?? '', PATHINFO_EXTENSION) ?: 'pdf',\n                'file_size' => \ ?? 0,",
    $content
);

// SYNC SK: Pangkalan Dokumen (update)
$content = str_replace(
    "'description' => 'SK / Surat Rekomendasi resmi hasil verifikasi layanan SMK.',\n                'file_path' => \,",
    "'description' => 'SK / Surat Rekomendasi resmi hasil verifikasi layanan SMK.',\n                'file_path' => \,\n                'file_size' => \ ?? 0,",
    $content
);

file_put_contents($file, $content);
echo "Fixed 3";
