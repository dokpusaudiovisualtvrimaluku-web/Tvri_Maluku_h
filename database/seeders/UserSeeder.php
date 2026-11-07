<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Mengorkestrasi seeder akun TVRI Maluku yang telah dipisahkan secara independen.
     */
    public function run(): void
    {
        // 1. Pastikan seluruh master roles terdaftar di sistem
        $roles = [
            'ADMIN',
            'Super Admin',
            'KEPALA_DINAS',
            'PEJABAT',
            'OPERATOR',
            'VIEWER',
            'Admin Kabupaten/Kota',
            'Admin Kecamatan',
            'Operator Desa/Kelurahan',
            'Viewer/Pimpinan',
            'Kepala Stasiun',
            'Tata Usaha',
            'Berita',
            'Program',
            'Konten Media Baru',
            'Promo',
            'Pengembangan Usaha',
            'Teknik',
            'Teknik Transmisi',
            'Umum',
            'Keuangan',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // 2. Jalankan seeder masing-masing akun secara terpisah & independen
        $this->call([
            // Akun Pimpinan & Administrasi Utama
            KepalaStasiunAccountSeeder::class,
            SuperAdminAccountSeeder::class,
            TataUsahaAccountSeeder::class,

            // Akun Bidang Operasional & Penyiaran
            BeritaAccountSeeder::class,
            ProgramAccountSeeder::class,
            KontenMediaBaruAccountSeeder::class,
            PromoAccountSeeder::class,

            // Akun Bidang Usaha, Fasilitas & Penunjang
            PengembanganUsahaAccountSeeder::class,
            TeknikAccountSeeder::class,
            TeknikTransmisiAccountSeeder::class,
            UmumAccountSeeder::class,
            KeuanganAccountSeeder::class,

            // Akun Resmi 12 Bidang TVRI Maluku
            TvriAccountsSeeder::class,

            // Akun Kompatibilitas Sistem (Legacy / Fallback)
            LegacyAccountSeeder::class,
        ]);
    }
}
