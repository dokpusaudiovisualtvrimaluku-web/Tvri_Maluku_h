<?php
$file = 'app/Http/Controllers/Pendidikan/SmkLayananController.php';
$content = file_get_contents($file);

$toRemove1 = "\n                'file_size' => \ ?? 0,";
$toRemove2 = "\n                'file_size' => \ ?? 0, "; // with space

$content = str_replace($toRemove1, "", $content);
$content = str_replace($toRemove2, "", $content);

// Now carefully re-insert only where we exactly need it!
// We only want it inside \App\Models\SimandatDokumenLayanan::create and \App\Models\SimandatDocument::create

// SYNC 1: Dokumen Layanan
$content = str_replace(
    "'file_type' => pathinfo(\ ?? '', PATHINFO_EXTENSION) ?: 'pdf',",
    "'file_type' => pathinfo(\ ?? '', PATHINFO_EXTENSION) ?: 'pdf',\n                'file_size' => \ ?? 0,",
    $content
);

// SYNC 3: Pangkalan Dokumen (for filePermohonan)
// Since file_path appears multiple times, we will replace specifically for SimandatDocument
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
echo "Fixed 2";
