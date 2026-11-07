<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class LegacyAccountSeeder extends Seeder
{
    /**
     * Akun Kompatibilitas & Fallback TVRI Maluku
     */
    public array $users = [
        [
            'username' => 'kasubag.keuangan',
            'name' => 'Kasubag Keuangan TVRI Maluku',
            'email' => 'kasubag.keuangan@tvri.go.id',
            'password' => 'TvriMalukukeuangan@2026',
            'roles' => ['PEJABAT', 'Keuangan'],
        ],
        [
            'username' => 'superadmin',
            'name' => 'Super Administrator',
            'email' => 'superadmin@tvri.go.id',
            'password' => 'AdminTI@Maluku2026',
            'roles' => ['ADMIN', 'Super Admin'],
        ],
        [
            'username' => 'sekretaris.dinas',
            'name' => 'Sekretaris Stasiun TVRI Maluku',
            'email' => 'sekretaris@tvri.go.id',
            'password' => 'TvriMaluku@2026',
            'roles' => ['PEJABAT'],
        ],
        [
            'username' => 'pegawai',
            'name' => 'Pelaksana Staf TVRI Maluku',
            'email' => 'pegawai@tvri.go.id',
            'password' => 'TvriMaluku@2026',
            'roles' => ['OPERATOR'],
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->users as $data) {
            if (!empty($data['roles'])) {
                foreach ($data['roles'] as $roleName) {
                    Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
                }
            }

            $user = User::where('username', $data['username'])
                ->orWhere('email', $data['email'])
                ->first();

            if (!$user) {
                $user = new User();
            }

            $user->name = $data['name'];
            $user->username = $data['username'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);
            $user->status = 'ACTIVE';
            $user->save();

            if (!empty($data['roles'])) {
                $user->syncRoles($data['roles']);
            }
        }

        $this->command->info("Akun kompatibilitas (Legacy Accounts) berhasil diperbarui.");
    }
}
