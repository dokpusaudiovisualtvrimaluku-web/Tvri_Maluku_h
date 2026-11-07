<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\KategoriTokoh;
use App\Models\Agama;
use App\Models\Profesi;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TvriAccountsSeeder::class,
            TvriOperasionalAccountsSeeder::class,
            TvriDataSeeder::class,
            DatasetSeeder::class,
        ]);
    }
}