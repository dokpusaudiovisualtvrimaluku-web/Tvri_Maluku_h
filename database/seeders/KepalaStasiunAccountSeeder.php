<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class KepalaStasiunAccountSeeder extends Seeder
{
    /**
     * Kredensial Akun Kepala Stasiun TVRI Maluku
     */
    public string $name = 'Kepala Stasiun TVRI Maluku';
    public string $username = 'kepala.stasiun';
    public string $email = 'kepala.stasiun@tvri.go.id';
    public string $password = 'KepstaTvriMaluku@2026';
    public array $roles = ['KEPALA_DINAS', 'Kepala Stasiun'];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan roles terdaftar
        foreach ($this->roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Cari berdasarkan username atau email agar tidak terjadi duplikasi
        $user = User::where('username', $this->username)
            ->orWhere('email', $this->email)
            ->first();

        if (!$user) {
            $user = new User();
        }

        $user->name = $this->name;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->password = Hash::make($this->password);
        $user->status = 'ACTIVE';
        $user->save();

        if (!empty($this->roles)) {
            $user->syncRoles($this->roles);
        }

        $this->command->info("Akun [Kepala Stasiun] berhasil diperbarui: {$this->username} / {$this->email}");
    }
}
