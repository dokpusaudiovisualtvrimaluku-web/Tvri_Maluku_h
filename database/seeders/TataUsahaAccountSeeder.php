<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class TataUsahaAccountSeeder extends Seeder
{
    /**
     * Kredensial Akun Tata Usaha TVRI Maluku
     */
    public string $name = 'Tata Usaha TVRI Maluku';
    public string $username = 'admin.tu';
    public string $email = 'tu@tvri.go.id';
    public string $password = 'TvriMalukuTU@2026';
    public array $roles = ['ADMIN', 'Tata Usaha'];

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

        $this->command->info("Akun [Tata Usaha] berhasil diperbarui: {$this->username} / {$this->email}");
    }
}
