<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class KontenMediaBaruAccountSeeder extends Seeder
{
    /**
     * Kredensial Akun Bidang Konten Media Baru (KMB) TVRI Maluku
     */
    public string $name = 'Bidang Konten Media Baru (KMB)';
    public string $username = 'konten.media.baru';
    public string $email = 'kmb@tvri.go.id';
    public string $password = 'TvriMalukuKMB@2026';
    public array $roles = ['OPERATOR', 'Konten Media Baru'];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

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

        $this->command->info("Akun [Konten Media Baru] berhasil diperbarui: {$this->username} / {$this->email}");
    }
}
