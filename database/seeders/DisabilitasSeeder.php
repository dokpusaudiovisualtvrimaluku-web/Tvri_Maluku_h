<?php

namespace Database\Seeders;

use App\Models\DisabilitasAnak;
use App\Models\DisabilitasDewasa;
use Illuminate\Database\Seeder;

class DisabilitasSeeder extends Seeder
{
    public function run(): void
    {
        $data = json_decode(file_get_contents(database_path('seeders/data/disabilitas.json')), true);

        foreach ($data['dewasa']['disabilitas'] as $i => $label) {
            DisabilitasDewasa::create([
                'disabilitas' => $label,
                'pria' => $data['dewasa']['pria'][$i],
                'wanita' => $data['dewasa']['wanita'][$i],
                'sort_order' => $i,
            ]);
        }

        foreach ($data['anak']['series'] as $i => $s) {
            DisabilitasAnak::create([
                'name' => $s['name'],
                'color' => $s['color'],
                'usia_0_4' => $s['data'][0],
                'usia_5_9' => $s['data'][1],
                'usia_10_14' => $s['data'][2],
                'usia_15_17' => $s['data'][3],
                'sort_order' => $i,
            ]);
        }
    }
}
