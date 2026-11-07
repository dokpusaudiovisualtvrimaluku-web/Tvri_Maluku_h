<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class TeknikAccountSeeder extends Seeder
{
    /**
     * Kredensial Akun Bidang Teknik Studio & Fasilitas TVRI Maluku
     */
    public string $name = 'Bidang Teknik Studio & Fasilitas';
    public string $username = 'teknik';
    public string $email = 'teknik@tvri.go.id';
    public string $password = 'TvriMalukuteknik@2026';
    public array $roles = ['OPERATOR', 'Teknik'];

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

        $this->command->info("Akun [Teknik] berhasil diperbarui: {$this->username} / {$this->email}");
    }
}
