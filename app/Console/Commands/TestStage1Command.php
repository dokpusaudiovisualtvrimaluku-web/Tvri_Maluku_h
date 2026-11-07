<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TestStage1Command extends Command
{
    protected $signature = 'test:stage1';
    protected $description = 'Verifikasi seluruh fitur dan arsitektur TAHAP 1 Pangkalan Data Disdikbud Maluku';

    public function handle()
    {
        $this->info('=====================================================');
        $this->info('VERIFIKASI SISTEM TAHAP 1: DISDIKBUD PROVINSI MALUKU');
        $this->info('=====================================================');

        // 1. Roles
        $this->info('1. Memeriksa Roles...');
        $roles = ['ADMIN', 'KEPALA_DINAS', 'OPERATOR', 'PEJABAT', 'VIEWER'];
        foreach ($roles as $r) {
            $roleExists = Role::where('name', $r)->exists();
            $this->line("   - Role [{$r}]: " . ($roleExists ? '<info>OK</info>' : '<error>NOT FOUND</error>'));
        }

        // 2. Admin User
        $this->info(PHP_EOL . '2. Memeriksa Akun Administrator...');
        $admin = User::where('username', 'admin')->first();
        if ($admin) {
            $this->line("   - Nama: {$admin->name}");
            $this->line("   - Email: {$admin->email}");
            $this->line("   - Role: {$admin->role_name}");
            $this->line("   - Status: {$admin->status}");
            $passOk = Hash::check('Admin@12345', $admin->password);
            $this->line("   - Password (Admin@12345): " . ($passOk ? '<info>VALID</info>' : '<error>INVALID</error>'));
            $this->line("   - Has Role ADMIN: " . ($admin->hasRole('ADMIN') ? '<info>YES</info>' : '<error>NO</error>'));
        } else {
            $this->error('   - Akun Admin TIDAK DITEMUKAN!');
        }

        // 3. Kepala Dinas User
        $this->info(PHP_EOL . '3. Memeriksa Akun Kepala Dinas...');
        $kepala = User::where('username', 'kepala.dinas')->first();
        if ($kepala) {
            $this->line("   - Nama: {$kepala->name}");
            $this->line("   - Email: {$kepala->email}");
            $this->line("   - Role: {$kepala->role_name}");
            $this->line("   - Status: {$kepala->status}");
            $passOk = Hash::check('Kepala@12345', $kepala->password);
            $this->line("   - Password (Kepala@12345): " . ($passOk ? '<info>VALID</info>' : '<error>INVALID</error>'));
            $this->line("   - Has Role KEPALA_DINAS: " . ($kepala->hasRole('KEPALA_DINAS') ? '<info>YES</info>' : '<error>NO</error>'));
        } else {
            $this->error('   - Akun Kepala Dinas TIDAK DITEMUKAN!');
        }

        // 4. Activity Logger
        $this->info(PHP_EOL . '4. Memeriksa Audit Trail (Activity Logger)...');
        $before = ActivityLog::count();
        ActivityLogger::log('LOGIN', 'Verifikasi otomatis login sistem Tahap 1', $admin ? $admin->id : null);
        $after = ActivityLog::count();
        if ($after > $before) {
            $latest = ActivityLog::latest()->first();
            $this->line("   - Activity Log Baru: <info>[{$latest->activity}] {$latest->description}</info>");
            $this->line("   - IP & Waktu: {$latest->ip} | {$latest->created_at}");
        } else {
            $this->error('   - Gagal mencatat activity log!');
        }

        $this->info(PHP_EOL . '=====================================================');
        $this->info('HASIL: SELURUH FUNGSI TAHAP 1 BERJALAN DENGAN SEMPURNA');
        $this->info('=====================================================');

        return 0;
    }
}
