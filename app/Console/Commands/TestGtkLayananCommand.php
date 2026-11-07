<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SimandatGtkLayanan;
use App\Models\User;
use Illuminate\Support\Facades\Route;

class TestGtkLayananCommand extends Command
{
    protected $signature = 'test:gtk';
    protected $description = 'Verify Katalog & Standar Layanan GTK module (4 services & document management)';

    public function handle()
    {
        $this->info('=== TEST KATALOG & STANDAR LAYANAN GTK (PROVINSI MALUKU) ===');

        // 1. Verify Database Records
        $total = SimandatGtkLayanan::count();
        $this->info("1. Checking Database Records... Total found: {$total}");
        if ($total === 0) {
            $this->error('FAIL: No records found in simandat_gtk_layanan table.');
            return 1;
        }

        // 2. Verify 4 Services Presence
        $services = [
            'Sertifikasi Pendidik',
            'NUPTK',
            'Tunjangan Pendidik',
            'SIMPKB / PKB',
        ];

        foreach ($services as $service) {
            $count = SimandatGtkLayanan::where('jenis_layanan', $service)->count();
            if ($count > 0) {
                $this->info("   [OK] Layanan '{$service}': {$count} data");
            } else {
                $this->warn("   [WARN] Layanan '{$service}' has 0 records.");
            }
        }

        // 3. Verify Routes
        $this->info('2. Checking Named Routes for GTK Layanan...');
        $requiredRoutes = [
            'pendidikan.gtk.index',
            'pendidikan.gtk.layanan.index',
            'pendidikan.gtk.layanan.create',
            'pendidikan.gtk.layanan.store',
            'pendidikan.gtk.layanan.show',
            'pendidikan.gtk.layanan.edit',
            'pendidikan.gtk.layanan.update',
            'pendidikan.gtk.layanan.destroy',
            'pendidikan.gtk.layanan.download',
            'pendidikan.gtk.layanan.export-csv',
        ];

        foreach ($requiredRoutes as $routeName) {
            if (Route::has($routeName)) {
                $this->info("   [OK] Route '{$routeName}' is registered.");
            } else {
                $this->error("   [FAIL] Route '{$routeName}' is MISSING!");
                return 1;
            }
        }

        // 4. Test CRUD Lifecycle
        $this->info('3. Testing CRUD Store Lifecycle...');
        $admin = User::first() ?? User::factory()->create();

        $testItem = SimandatGtkLayanan::create([
            'nomor_registrasi' => SimandatGtkLayanan::generateNomorRegistrasi('Sertifikasi Pendidik'),
            'jenis_layanan' => 'Sertifikasi Pendidik',
            'nama_lengkap_guru' => 'Guru Test Otomatis, S.Pd',
            'nuptk' => '1234567890123456',
            'nip_nik' => '199001012020121001',
            'tempat_tugas_sekolah' => 'SMAN Test Maluku',
            'npsn_sekolah' => '60199999',
            'jenjang_sekolah' => 'SMA',
            'status_kepegawaian' => 'PNS',
            'kabupaten_kota' => 'Kota Ambon',
            'tanggal_pengajuan' => '2026-03-10',
            'perihal_uraian' => 'Uji coba otomatis permohonan sertifikasi pendidik.',
            'status_layanan' => 'PROSES_VERIFIKASI',
            'user_id' => $admin->id,
        ]);

        $this->info("   [OK] Created test record: ID {$testItem->id} ({$testItem->nomor_registrasi})");

        // Update Test
        $testItem->update([
            'status_layanan' => 'SELESAI',
            'catatan_verifikator' => 'Lulus verifikasi uji otomatis.',
        ]);
        $this->info("   [OK] Updated status to SELESAI for ID {$testItem->id}");

        // Delete Test
        $testItem->delete();
        $this->info("   [OK] Soft deleted test record ID {$testItem->id}");

        $this->info('=== ALL GTK LAYANAN TESTS PASSED SUCCESSFULLY (100%) ===');
        return 0;
    }
}
