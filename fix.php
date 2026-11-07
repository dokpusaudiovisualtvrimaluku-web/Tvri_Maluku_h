<?php
$file = 'app/Http/Controllers/Pendidikan/SmkLayananController.php';
$content = file_get_contents($file);
$content = preg_replace("/\s*'file_size'\s*=>\s*\\\\s*\?\?\s*0\s*,/m", "", $content);

// Now re-insert exactly where needed
// SYNC 1: Dokumen Layanan
$content = str_replace(
    "'file_type' => pathinfo(\ ?? '', PATHINFO_EXTENSION) ?: 'pdf',",
    "'file_type' => pathinfo(\ ?? '', PATHINFO_EXTENSION) ?: 'pdf',\n                'file_size' => \ ?? 0,",
    $content
);

// SYNC 3: Pangkalan Dokumen (for filePermohonan)
$content = str_replace(
    "'file_path' => \,",
    "'file_path' => \,\n                'file_size' => \ ?? 0,",
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
    "'file_path' => \,",
    "'file_path' => \,\n                'file_size' => \ ?? 0,",
    $content
);

file_put_contents($file, $content);
echo "Fixed";
