const fs = require('fs');
let file = 'app/Http/Controllers/Pendidikan/SmkLayananController.php';
let content = fs.readFileSync(file, 'utf8');

// Literally remove ALL occurrences
while(content.includes("'file_size' => $fileSize ?? 0,")) {
    content = content.replace("'file_size' => $fileSize ?? 0,", "");
}

// Remove empty lines that were left behind
content = content.replace(/^[ \t]*\r?\n/gm, "\n");

// Re-inject exactly where needed
content = content.replace(
    "'file_type' => pathinfo($filePermohonanName ?? '', PATHINFO_EXTENSION) ?: 'pdf',",
    "'file_type' => pathinfo($filePermohonanName ?? '', PATHINFO_EXTENSION) ?: 'pdf',\n                'file_size' => $fileSize ?? 0,"
);

content = content.replace(
    "'description' => 'Berkas persyaratan permohonan layanan SMK.',\n                'file_path' => $filePermohonanPath,",
    "'description' => 'Berkas persyaratan permohonan layanan SMK.',\n                'file_path' => $filePermohonanPath,\n                'file_size' => $fileSize ?? 0,"
);

// We need to match the SECOND file_type which is for update (hasil SK)
// Since we already replaced the first one, let's replace the other one
content = content.replace(
    "'file_type' => pathinfo($fileHasilSkName ?? '', PATHINFO_EXTENSION) ?: 'pdf',",
    "'file_type' => pathinfo($fileHasilSkName ?? '', PATHINFO_EXTENSION) ?: 'pdf',\n                'file_size' => $fileSize ?? 0,"
);

content = content.replace(
    "'description' => 'SK / Surat Rekomendasi resmi hasil verifikasi layanan SMK.',\n                'file_path' => $fileHasilSkPath,",
    "'description' => 'SK / Surat Rekomendasi resmi hasil verifikasi layanan SMK.',\n                'file_path' => $fileHasilSkPath,\n                'file_size' => $fileSize ?? 0,"
);

fs.writeFileSync(file, content);
console.log("Fixed via Node literals!");
