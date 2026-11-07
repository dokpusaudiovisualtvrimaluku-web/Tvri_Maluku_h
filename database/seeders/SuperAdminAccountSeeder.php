<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class SuperAdminAccountSeeder extends Seeder
{
    /**
     * Kredensial Akun Super Admin TVRI Maluku
     */
    public string $name = 'Administrator TI (Super Admin)';
    public string $username = 'admin';
    public string $email = 'admin@tvri.go.id';
    public string $password = 'AdminTI@Maluku2026';
    public array $roles = ['ADMIN', 'Super Admin'];

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

        $this->command->info("Akun [Super Admin] berhasil diperbarui: {$this->username} / {$this->email}");
    }
}
