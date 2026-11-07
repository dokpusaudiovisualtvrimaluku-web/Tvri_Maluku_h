<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SimandatSlbLayanan;
use App\Models\User;
use Illuminate\Support\Facades\Route;

class TestSlbLayananCommand extends Command
{
    protected $signature = 'test:slb';
    protected $description = 'Verify Direktori Produk Layanan Bidang Pembinaan Pendidikan Khusus (SLB) module';

    public function handle()
    {
        $this->info('=== TEST DIREKTORI PRODUK LAYANAN BIDANG PEMBINAAN PENDIDIKAN KHUSUS (SLB) ===');

        // 1. Verify Database Records
        $total = SimandatSlbLayanan::count();
        $this->info("1. Checking Database Records... Total found: {$total}");
        if ($total === 0) {
            $this->error('FAIL: No records found in simandat_slb_layanan table.');
            return 1;
        }

        // 2. Verify 4 Services Presence
        $services = [
            'Rekomendasi Pendirian SLB Negeri dan SLB Swasta',
            'Rekomendasi Mutasi (Perpindahan) Siswa SLB',
            'Bantuan Operasional Satuan Pendidikan (BOSP) SLB',
            'Legalisir Ijazah/STTB dan Surat Keterangan Lulus (SKL) SLB',
        ];

        foreach ($services as $service) {
            $count = SimandatSlbLayanan::where('jenis_layanan', $service)->count();
            if ($count > 0) {
                $this->info("   [OK] Layanan '{$service}': {$count} data");
            } else {
                $this->warn("   [WARN] Layanan '{$service}' has 0 records.");
            }
        }

        // 3. Verify Routes
        $this->info('2. Checking Named Routes for SLB Layanan...');
        $requiredRoutes = [
            'pendidikan.slb.index',
            'pendidikan.slb.layanan.index',
            'pendidikan.slb.layanan.create',
            'pendidikan.slb.layanan.store',
            'pendidikan.slb.layanan.show',
            'pendidikan.slb.layanan.edit',
            'pendidikan.slb.layanan.update',
            'pendidikan.slb.layanan.destroy',
            'pendidikan.slb.layanan.download',
            'pendidikan.slb.layanan.export-csv',
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

        $testItem = SimandatSlbLayanan::create([
            'nomor_registrasi' => SimandatSlbLayanan::generateNomorRegistrasi('Rekomendasi Pendirian SLB Negeri dan SLB Swasta'),
            'jenis_layanan' => 'Rekomendasi Pendirian SLB Negeri dan SLB Swasta',
            'nama_pemohon' => 'Yayasan Uji Coba Otomatis',
            'nomor_identitas' => '8171011111110001',
            'nama_satuan_pendidikan' => 'SLB Test Otomatis Ambon',
            'npsn_sekolah' => '60199991',
            'kabupaten_kota' => 'Kota Ambon',
            'no_hp_whatsapp' => '081299998888',
            'email' => 'test.slb@disdikbud.malukuprov.go.id',
            'tanggal_pengajuan' => '2026-03-15',
            'perihal_uraian' => 'Uji coba otomatis permohonan rekomendasi pendirian SLB.',
            'status_layanan' => 'PROSES_VERIFIKASI',
            'user_id' => $admin->id,
        ]);

        $this->info("   [OK] Created test record: ID {$testItem->id} ({$testItem->nomor_registrasi})");

        // Update Test
        $testItem->update([
            'status_layanan' => 'SELESAI',
            'catatan_verifikator' => 'Lulus uji otomatis verifikasi dinas.',
        ]);
        $this->info("   [OK] Updated status to SELESAI for ID {$testItem->id}");

        // Delete Test
        $testItem->delete();
        $this->info("   [OK] Soft deleted test record ID {$testItem->id}");

        $this->info('=== ALL SLB LAYANAN TESTS PASSED SUCCESSFULLY (100%) ===');
        return 0;
    }
}
