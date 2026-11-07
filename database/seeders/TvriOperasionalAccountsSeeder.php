<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class TvriOperasionalAccountsSeeder extends Seeder
{
    /**
     * Akun Khusus Operasional TVRI Maluku:
     * 1. Super Admin: admin@tvri.go.id / AdminTI@Maluku2026
     * 2. Umum: umum@tvri.go.id / TvriMaluku@2026
     * 3. Kepala Stasiun (kepsta): kepala.stasiun@tvri.go.id / TvriMaluku@2026
     * 4. Berita: berita@tvri.go.id / TvriMaluku@2026
     * 5. Keuangan: keuangan@tvri.go.id / TvriMaluku@2026
     * 6. KMB: kmb@tvri.go.id / TvriMaluku@2026
     * 7. Pengembangan Usaha: usaha@tvri.go.id / TvriMaluku@2026
     * 8. Promo: promo@tvri.go.id / TvriMaluku@2026
     * 9. Tata Usaha: tu@tvri.go.id / TvriMaluku@2026
     * 10. Teknik: teknik@tvri.go.id / TvriMaluku@2026
     * 11. Teknik Transmisi: transmisi@tvri.go.id / TvriMaluku@2026
     * 12. Program: program@tvri.go.id / TvriMaluku@2026
     */
    public array $accounts = [
        [
            'name' => 'Administrator TI (Super Admin)',
            'username' => 'admin',
            'email' => 'admin@tvri.go.id',
            'password' => 'AdminTI@Maluku2026',
            'roles' => ['ADMIN', 'Super Admin'],
        ],
        [
            'name' => 'Pimpinan Stasiun TVRI Maluku',
            'username' => 'kepala.stasiun',
            'email' => 'kepala.stasiun@tvri.go.id',
            'password' => 'KepstaTvriMaluku@2026',
            'roles' => ['KEPALA_DINAS', 'Kepala Stasiun'],
        ],
        [
            'name' => 'Ketua Tim Tata Usaha',
            'username' => 'tu',
            'email' => 'tu@tvri.go.id',
            'password' => 'TvriMalukuTU@2026',
            'roles' => ['OPERATOR', 'Tata Usaha'],
        ],
        [
            'name' => 'Ketua Tim Berita (Newsroom)',
            'username' => 'berita',
            'email' => 'berita@tvri.go.id',
            'password' => 'TvriMalukuberita@2026',
            'roles' => ['PEJABAT', 'Berita'],
        ],
        [
            'name' => 'Ketua Tim Program & Siaran',
            'username' => 'program',
            'email' => 'program@tvri.go.id',
            'password' => 'TvriMalukuprogram@2026',
            'roles' => ['PEJABAT', 'Program'],
        ],
        [
            'name' => 'Ketua Tim Konten Media Baru',
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
            'name' => 'Ketua Tim Pengembangan Usaha',
            'username' => 'usaha',
            'email' => 'usaha@tvri.go.id',
            'password' => 'TvriMalukuPU@2026',
            'roles' => ['PEJABAT', 'Pengembangan Usaha'],
        ],
        [
            'name' => 'Ketua Tim Teknik Penyiaran',
            'username' => 'teknik',
            'email' => 'teknik@tvri.go.id',
            'password' => 'TvriMalukuteknik@2026',
            'roles' => ['PEJABAT', 'Teknik'],
        ],
        [
            'name' => 'Ketua Tim Teknik Transmisi',
            'username' => 'transmisi',
            'email' => 'transmisi@tvri.go.id',
            'password' => 'TvriMalukutransmisi@2026',
            'roles' => ['PEJABAT', 'Teknik Transmisi'],
        ],
        [
            'name' => 'Ketua Tim Layanan Umum & Sarpras',
            'username' => 'umum',
            'email' => 'umum@tvri.go.id',
            'password' => 'TvriMaluku@2026',
            'roles' => ['OPERATOR', 'Umum'],
        ],
        [
            'name' => 'Ketua Tim Pengelolaan Keuangan',
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
            $user = User::where('email', $acc['email'])
                ->orWhere('username', $acc['username'])
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

            $this->command?->info("Akun Operasional TVRI [{$acc['name']}] berhasil disiapkan: {$acc['email']} (Password: {$acc['password']})");
        }

        // Sinkronisasi alias khusus untuk kompatibilitas nama login
        $aliases = [
            'admin.tu' => ['email' => 'tu@tvri.go.id', 'role' => 'Tata Usaha', 'password' => 'TvriMalukuTU@2026'],
            'konten.media.baru' => ['email' => 'kmb@tvri.go.id', 'role' => 'Konten Media Baru', 'password' => 'TvriMalukuKMB@2026'],
            'pengembangan.usaha' => ['email' => 'usaha@tvri.go.id', 'role' => 'Pengembangan Usaha', 'password' => 'TvriMalukuPU@2026'],
            'teknik.transmisi' => ['email' => 'transmisi@tvri.go.id', 'role' => 'Teknik Transmisi', 'password' => 'TvriMalukutransmisi@2026'],
        ];

        foreach ($aliases as $aliasUsername => $aliasData) {
            $aliasUser = User::where('username', $aliasUsername)->first();
            if ($aliasUser && $aliasUser->email !== $aliasData['email']) {
                $aliasUser->password = Hash::make($aliasData['password']);
                $aliasUser->status = 'ACTIVE';
                $aliasUser->save();
            }
        }
    }
}
