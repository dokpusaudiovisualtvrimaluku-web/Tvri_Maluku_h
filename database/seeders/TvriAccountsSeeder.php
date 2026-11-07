<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class TvriAccountsSeeder extends Seeder
{
    /**
     * Daftar 12 Akun Resmi TVRI Maluku:
     * 1. Super Admin
     * 2. Kepala Stasiun
     * 3. Tata Usaha
     * 4. Berita
     * 5. Program
     * 6. KMB
     * 7. Promo
     * 8. Pengembangan Usaha
     * 9. Teknik
     * 10. Teknik Transmisi
     * 11. Umum
     * 12. Keuangan
     */
    public array $accounts = [
        [
            'name' => 'Super Admin',
            'username' => 'admin',
            'email' => 'admin@tvri.go.id',
            'password' => 'AdminTI@Maluku2026',
            'roles' => ['ADMIN', 'Super Admin'],
        ],
        [
            'name' => 'Kepala Stasiun',
            'username' => 'kepala.stasiun',
            'email' => 'kepala.stasiun@tvri.go.id',
            'password' => 'KepstaTvriMaluku@2026',
            'roles' => ['KEPALA_DINAS', 'Kepala Stasiun'],
        ],
        [
            'name' => 'Tata Usaha',
            'username' => 'tu',
            'email' => 'tu@tvri.go.id',
            'password' => 'TvriMalukuTU@2026',
            'roles' => ['OPERATOR', 'Tata Usaha'],
        ],
        [
            'name' => 'Berita',
            'username' => 'berita',
            'email' => 'berita@tvri.go.id',
            'password' => 'TvriMalukuberita@2026',
            'roles' => ['PEJABAT', 'Berita'],
        ],
        [
            'name' => 'Program',
            'username' => 'program',
            'email' => 'program@tvri.go.id',
            'password' => 'TvriMalukuprogram@2026',
            'roles' => ['PEJABAT', 'Program'],
        ],
        [
            'name' => 'KMB',
            'username' => 'kmb',
            'email' => 'kmb@tvri.go.id',
            'password' => 'TvriMalukuKMB@2026',
            'roles' => ['PEJABAT', 'Konten Media Baru'],
        ],
        [
            'name' => 'Ketua Tim Promo',
            'username' => 'promo',
            'email' => 'promo@tvri.go.id',
            'password' => 'TvriMalukupromo@2026',
            'roles' => ['PEJABAT', 'Promo'],
        ],
        [
            'name' => 'Pengembangan Usaha',
            'username' => 'usaha',
            'email' => 'usaha@tvri.go.id',
            'password' => 'TvriMalukuPU@2026',
            'roles' => ['PEJABAT', 'Pengembangan Usaha'],
        ],
        [
            'name' => 'Teknik',
            'username' => 'teknik',
            'email' => 'teknik@tvri.go.id',
            'password' => 'TvriMalukuteknik@2026',
            'roles' => ['PEJABAT', 'Teknik'],
        ],
        [
            'name' => 'Teknik Transmisi',
            'username' => 'transmisi',
            'email' => 'transmisi@tvri.go.id',
            'password' => 'TvriMalukutransmisi@2026',
            'roles' => ['PEJABAT', 'Teknik Transmisi'],
        ],
        [
            'name' => 'Umum',
            'username' => 'umum',
            'email' => 'umum@tvri.go.id',
            'password' => 'TvriMaluku@2026',
            'roles' => ['OPERATOR', 'Umum'],
        ],
        [
            'name' => 'Keuangan',
            'username' => 'keuangan',
            'email' => 'keuangan@tvri.go.id',
            'password' => 'TvriMalukukeuangan@2026',
            'roles' => ['PEJABAT', 'Keuangan'],
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allRoles = [
            'ADMIN',
            'Super Admin',
            'KEPALA_DINAS',
            'PEJABAT',
            'OPERATOR',
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

        foreach ($allRoles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        foreach ($this->accounts as $acc) {
            $user = User::where('username', $acc['username'])
                ->orWhere('email', $acc['email'])
                ->first();

            if (!$user) {
                $user = new User();
            }

            $user->name = $acc['name'];
            $user->username = $acc['username'];
            $user->email = $acc['email'];
            $user->password = Hash::make($acc['password']);
            $user->status = 'ACTIVE';
            $user->save();

            if (!empty($acc['roles'])) {
                $user->syncRoles($acc['roles']);
            }

            $this->command?->info("Akun TVRI [{$acc['name']}] berhasil disiapkan: {$acc['username']} ({$acc['email']})");
        }

        // Pastikan alias admin juga aktif dengan kredensial resmi
        $admin = User::where('username', 'admin')->orWhere('email', 'admin@tvri.go.id')->first();
        if ($admin) {
            $admin->name = 'Administrator TI (Super Admin)';
            $admin->email = 'admin@tvri.go.id';
            $admin->password = Hash::make('AdminTI@Maluku2026');
            $admin->save();
        }
    }
}
