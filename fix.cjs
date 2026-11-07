const fs = require('fs');
let file = 'app/Http/Controllers/Pendidikan/SmkLayananController.php';
let content = fs.readFileSync(file, 'utf8');

// Remove all injected file_size ?? 0, lines
content = content.replace(/^[ \t]*'file_size' => \ \?\? 0,.*$/gm, '');
content = content.replace(/^[ \t]*'file_size' => \ \?\? 0,.*$/gm, ''); // repeat in case of multiples

// Re-inject exactly where needed
content = content.replace(
    /('file_type' => pathinfo\(\ \?\? '', PATHINFO_EXTENSION\) \?: 'pdf',)/g,
    "$1\n                'file_size' => \ ?? 0,"
);

content = content.replace(
    /('description' => 'Berkas persyaratan permohonan layanan SMK.',\r?\n\s*'file_path' => \,)/g,
    "$1\n                'file_size' => \ ?? 0,"
);

content = content.replace(
    /('file_type' => pathinfo\(\ \?\? '', PATHINFO_EXTENSION\) \?: 'pdf',)/g,
    "$1\n                'file_size' => \ ?? 0,"
);

content = content.replace(
    /('description' => 'SK \/ Surat Rekomendasi resmi hasil verifikasi layanan SMK.',\r?\n\s*'file_path' => \,)/g,
    "$1\n                'file_size' => \ ?? 0,"
);

fs.writeFileSync(file, content);
console.log("Fixed via Node!");
