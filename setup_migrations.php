<?php

$dir = __DIR__ . '/database/migrations/';

function updateMigration($dir, $pattern, $upContent) {
    $files = glob($dir . '*' . $pattern);
    if(count($files) > 0) {
        $file = $files[0];
        $content = file_get_contents($file);
        
        // Find the public function up() block
        $patternRegex = '/public function up\(\): void\s*\{\s*Schema::create\(\'([a-zA-Z_]+)\', function \(Blueprint \$table\) \{\s*\$table->id\(\);\s*\$table->timestamps\(\);\s*\}\);\s*\}/s';
        
        $replacement = 'public function up(): void
    {
        Schema::create(\'$1\', function (Blueprint $table) {
            $table->id();
' . $upContent . '
            $table->timestamps();
        });
    }';

        $newContent = preg_replace($patternRegex, $replacement, $content);
        file_put_contents($file, $newContent);
        echo "Updated $file\n";
    }
}

// Provinsis
updateMigration($dir, '_create_provinsis_table.php', "            \$table->string('name');");

// Kabupatens
updateMigration($dir, '_create_kabupatens_table.php', "            \$table->foreignId('provinsi_id')->constrained('provinsis')->onDelete('cascade');
            \$table->string('name');");

// Kecamatans
updateMigration($dir, '_create_kecamatans_table.php', "            \$table->foreignId('kabupaten_id')->constrained('kabupatens')->onDelete('cascade');
            \$table->string('name');");

// Desas
updateMigration($dir, '_create_desas_table.php', "            \$table->foreignId('kecamatan_id')->constrained('kecamatans')->onDelete('cascade');
            \$table->string('name');");

// Master Data
$masters = ['kategori_tokohs', 'organisasis', 'profesis', 'pendidikans', 'agamas', 'sukus'];
foreach($masters as $master) {
    updateMigration($dir, '_create_'.$master.'_table.php', "            \$table->string('name');");
}

// Tokohs
$tokohs = <<<EOT
            \$table->string('nik', 16)->unique();
            \$table->string('nama_lengkap');
            \$table->string('tempat_lahir');
            \$table->date('tanggal_lahir');
            \$table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            \$table->foreignId('agama_id')->nullable()->constrained('agamas')->onDelete('set null');
            \$table->foreignId('suku_id')->nullable()->constrained('sukus')->onDelete('set null');
            \$table->text('alamat');
            \$table->string('rt_rw', 10)->nullable();
            \$table->foreignId('desa_id')->nullable()->constrained('desas')->onDelete('set null');
            \$table->foreignId('kecamatan_id')->nullable()->constrained('kecamatans')->onDelete('set null');
            \$table->foreignId('kabupaten_id')->nullable()->constrained('kabupatens')->onDelete('set null');
            \$table->foreignId('provinsi_id')->nullable()->constrained('provinsis')->onDelete('set null');
            \$table->string('no_hp', 20)->nullable();
            \$table->string('whatsapp', 20)->nullable();
            \$table->string('email')->nullable();
            \$table->foreignId('profesi_id')->nullable()->constrained('profesis')->onDelete('set null');
            \$table->foreignId('pendidikan_id')->nullable()->constrained('pendidikans')->onDelete('set null');
            \$table->string('pekerjaan')->nullable();
            \$table->foreignId('organisasi_id')->nullable()->constrained('organisasis')->onDelete('set null');
            \$table->string('jabatan')->nullable();
            \$table->foreignId('kategori_tokoh_id')->nullable()->constrained('kategori_tokohs')->onDelete('set null');
            \$table->boolean('status_aktif')->default(true);
            \$table->date('tanggal_pendataan');
            \$table->foreignId('pendata_id')->nullable()->constrained('users')->onDelete('set null');
            \$table->string('latitude')->nullable();
            \$table->string('longitude')->nullable();
            \$table->string('foto')->nullable();
            \$table->text('catatan')->nullable();
            \$table->enum('status_verifikasi', ['Draft', 'Menunggu Verifikasi', 'Disetujui', 'Ditolak'])->default('Draft');
EOT;
updateMigration($dir, '_create_tokohs_table.php', $tokohs);

// Dokumens
$dokumens = <<<EOT
            \$table->foreignId('tokoh_id')->constrained('tokohs')->onDelete('cascade');
            \$table->string('jenis'); // KTP, KK, Foto Rumah, Pendukung
            \$table->string('file_path');
EOT;
updateMigration($dir, '_create_dokumens_table.php', $dokumens);

// Verifikasis
$verifikasis = <<<EOT
            \$table->foreignId('tokoh_id')->constrained('tokohs')->onDelete('cascade');
            \$table->foreignId('verifikator_id')->constrained('users')->onDelete('cascade');
            \$table->enum('status', ['Disetujui', 'Ditolak']);
            \$table->text('catatan')->nullable();
EOT;
updateMigration($dir, '_create_verifikasis_table.php', $verifikasis);

// Audit Logs
$auditLogs = <<<EOT
            \$table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            \$table->string('action');
            \$table->string('model')->nullable();
            \$table->unsignedBigInteger('model_id')->nullable();
            \$table->json('payload')->nullable();
            \$table->string('ip_address')->nullable();
            \$table->string('user_agent')->nullable();
EOT;
updateMigration($dir, '_create_audit_logs_table.php', $auditLogs);

echo "Migrations setup completed.\n";
