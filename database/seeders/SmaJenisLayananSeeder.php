<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SmaJenisLayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $layananList = \App\Models\SimandatSmaLayanan::JENIS_LAYANAN_LIST;

        $newOptions = [
            'Kesiswaan & Mutasi' => [
                'icon' => 'users',
                'badge' => 'bg-blue-100 text-blue-800 border-blue-300',
                'prefix' => 'SMA-KES',
                'seksi' => 'Seksi Kesiswaan & Mutasi',
                'deskripsi' => 'Layanan terkait kesiswaan dan mutasi siswa SMA.',
            ],
            'Dokumen & Legalitas' => [
                'icon' => 'file-text',
                'badge' => 'bg-amber-100 text-amber-800 border-amber-300',
                'prefix' => 'SMA-DOK',
                'seksi' => 'Seksi Dokumen & Legalitas',
                'deskripsi' => 'Layanan terkait dokumen dan legalitas administrasi SMA.',
            ],
            'Kelembagaan & Perizinan' => [
                'icon' => 'building',
                'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                'prefix' => 'SMA-IZN',
                'seksi' => 'Seksi Kelembagaan & Perizinan',
                'deskripsi' => 'Layanan terkait kelembagaan dan perizinan sekolah.',
            ],
            'Kurikulum & Data' => [
                'icon' => 'book',
                'badge' => 'bg-purple-100 text-purple-800 border-purple-300',
                'prefix' => 'SMA-KUR',
                'seksi' => 'Seksi Kurikulum & Data',
                'deskripsi' => 'Layanan terkait kurikulum pendidikan dan pengolahan data.',
            ],
            'Pemerataan & Pend' => [
                'icon' => 'map',
                'badge' => 'bg-sky-100 text-sky-800 border-sky-300',
                'prefix' => 'SMA-PEM',
                'seksi' => 'Seksi Pemerataan & Pend',
                'deskripsi' => 'Layanan terkait pemerataan akses dan layanan pendidikan SMA.',
            ],
        ];

        $layananList = array_merge($layananList, $newOptions);

        foreach ($layananList as $title => $data) {
            \App\Models\SmaJenisLayanan::updateOrCreate(
                ['nama_layanan' => $title],
                [
                    'seksi' => $data['seksi'] ?? 'Bidang Pembinaan SMA',
                    'prefix' => $data['prefix'] ?? 'SMA',
                    'deskripsi' => $data['deskripsi'] ?? 'Layanan Dokumen Bidang SMA',
                    'icon' => $data['icon'] ?? 'file-text',
                    'badge' => $data['badge'] ?? 'bg-gray-100 text-gray-800 border-gray-300',
                ]
            );
        }
    }
}
