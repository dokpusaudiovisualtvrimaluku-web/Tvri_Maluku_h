<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SimandatSchool;
use App\Models\SimandatTeacher;
use App\Models\SimandatStudent;
use App\Models\SimandatSarpras;

class PendidikanStage3Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regencies = [
            'Kota Ambon', 'Maluku Tengah', 'Maluku Tenggara', 'Kepulauan Tanimbar',
            'Buru', 'Seram Bagian Barat', 'Seram Bagian Timur', 'Kepulauan Aru',
            'Maluku Barat Daya', 'Buru Selatan', 'Kota Tual'
        ];

        $schoolsData = [
            [
                'npsn' => '60101991',
                'nama_sekolah' => 'SMAN Siwalima Ambon',
                'jenjang' => 'SMA',
                'status_sekolah' => 'Negeri',
                'alamat' => 'Jl. Wolter Monginsidi, Lateri, Teluk Ambon Baguala',
                'kecamatan' => 'Teluk Ambon Baguala',
                'kabupaten_kota' => 'Kota Ambon',
                'kepala_sekolah' => 'Drs. Nasir Rahayaan, M.Pd',
                'akreditasi' => 'A',
                'latitude' => -3.6498,
                'longitude' => 128.2325,
                'tahun_data' => 2024,
                'status_validasi' => 'VALIDATED',
            ],
            [
                'npsn' => '60101992',
                'nama_sekolah' => 'SMKN 1 Ambon',
                'jenjang' => 'SMK',
                'status_sekolah' => 'Negeri',
                'alamat' => 'Jl. Dr. Kayadoe, Kudamati, Nusaniwe',
                'kecamatan' => 'Nusaniwe',
                'kabupaten_kota' => 'Kota Ambon',
                'kepala_sekolah' => 'Dra. Elsye Tahapary',
                'akreditasi' => 'A',
                'latitude' => -3.7120,
                'longitude' => 128.1690,
                'tahun_data' => 2024,
                'status_validasi' => 'VALIDATED',
            ],
            [
                'npsn' => '60101993',
                'nama_sekolah' => 'SLB Negeri Pembina Ambon',
                'jenjang' => 'SLB',
                'status_sekolah' => 'Negeri',
                'alamat' => 'Jl. Wolter Monginsidi No. 12',
                'kecamatan' => 'Sirimau',
                'kabupaten_kota' => 'Kota Ambon',
                'kepala_sekolah' => 'Johana Loupatty, S.Pd',
                'akreditasi' => 'B',
                'latitude' => -3.6821,
                'longitude' => 128.1920,
                'tahun_data' => 2024,
                'status_validasi' => 'VALIDATED',
            ],
            [
                'npsn' => '60101994',
                'nama_sekolah' => 'SMAN 1 Maluku Tengah (Masohi)',
                'jenjang' => 'SMA',
                'status_sekolah' => 'Negeri',
                'alamat' => 'Jl. Pala No. 1 Masohi',
                'kecamatan' => 'Kota Masohi',
                'kabupaten_kota' => 'Maluku Tengah',
                'kepala_sekolah' => 'Ahmad Latuconsina, M.Pd',
                'akreditasi' => 'A',
                'latitude' => -3.3082,
                'longitude' => 128.9567,
                'tahun_data' => 2024,
                'status_validasi' => 'VALIDATED',
            ],
            [
                'npsn' => '60101995',
                'nama_sekolah' => 'SMKN 2 Maluku Tengah',
                'jenjang' => 'SMK',
                'status_sekolah' => 'Negeri',
                'alamat' => 'Jl. Trans Seram, Banda',
                'kecamatan' => 'Banda',
                'kabupaten_kota' => 'Maluku Tengah',
                'kepala_sekolah' => 'Rustam Baadilla, S.Pd',
                'akreditasi' => 'B',
                'latitude' => -4.5262,
                'longitude' => 129.9048,
                'tahun_data' => 2024,
                'status_validasi' => 'VERIFIED',
            ],
            [
                'npsn' => '60101996',
                'nama_sekolah' => 'SMAN 1 Kei Kecil (Maluku Tenggara)',
                'jenjang' => 'SMA',
                'status_sekolah' => 'Negeri',
                'alamat' => 'Jl. Soekarno Hatta, Langgur',
                'kecamatan' => 'Kei Kecil',
                'kabupaten_kota' => 'Maluku Tenggara',
                'kepala_sekolah' => 'Petrus Renyut, S.Pd, M.M',
                'akreditasi' => 'A',
                'latitude' => -5.6333,
                'longitude' => 132.7333,
                'tahun_data' => 2024,
                'status_validasi' => 'VALIDATED',
            ],
            [
                'npsn' => '60101997',
                'nama_sekolah' => 'SMAN 1 Saumlaki (Kepulauan Tanimbar)',
                'jenjang' => 'SMA',
                'status_sekolah' => 'Negeri',
                'alamat' => 'Jl. Mathilda Batlayeri, Saumlaki',
                'kecamatan' => 'Tanimbar Selatan',
                'kabupaten_kota' => 'Kepulauan Tanimbar',
                'kepala_sekolah' => 'Fransiskus Batlayeri, S.Pd',
                'akreditasi' => 'B',
                'latitude' => -7.9792,
                'longitude' => 131.2974,
                'tahun_data' => 2024,
                'status_validasi' => 'VERIFIED',
            ],
            [
                'npsn' => '60101998',
                'nama_sekolah' => 'SMAN 1 Namlea (Buru)',
                'jenjang' => 'SMA',
                'status_sekolah' => 'Negeri',
                'alamat' => 'Jl. Pendidikan No. 4 Namlea',
                'kecamatan' => 'Namlea',
                'kabupaten_kota' => 'Buru',
                'kepala_sekolah' => 'Drs. Ridwan Bessy',
                'akreditasi' => 'A',
                'latitude' => -3.2458,
                'longitude' => 127.1082,
                'tahun_data' => 2024,
                'status_validasi' => 'VALIDATED',
            ],
            [
                'npsn' => '60101999',
                'nama_sekolah' => 'SMKN 1 Piru (Seram Bagian Barat)',
                'jenjang' => 'SMK',
                'status_sekolah' => 'Negeri',
                'alamat' => 'Jl. Trans Seram Piru',
                'kecamatan' => 'Seram Barat',
                'kabupaten_kota' => 'Seram Bagian Barat',
                'kepala_sekolah' => 'Zeth Salhuteru, S.Pd',
                'akreditasi' => 'B',
                'latitude' => -3.0732,
                'longitude' => 128.1884,
                'tahun_data' => 2024,
                'status_validasi' => 'SUBMITTED',
            ],
            [
                'npsn' => '60102000',
                'nama_sekolah' => 'SMAN 1 Bula (Seram Bagian Timur)',
                'jenjang' => 'SMA',
                'status_sekolah' => 'Negeri',
                'alamat' => 'Jl. Ampera, Bula',
                'kecamatan' => 'Bula',
                'kabupaten_kota' => 'Seram Bagian Timur',
                'kepala_sekolah' => 'Halim Rumadaul, M.Pd',
                'akreditasi' => 'B',
                'latitude' => -3.1022,
                'longitude' => 130.4958,
                'tahun_data' => 2024,
                'status_validasi' => 'VALIDATED',
            ],
            [
                'npsn' => '60102001',
                'nama_sekolah' => 'SMAN 1 Dobo (Kepulauan Aru)',
                'jenjang' => 'SMA',
                'status_sekolah' => 'Negeri',
                'alamat' => 'Jl. Cendrawasih, Dobo',
                'kecamatan' => 'Pulau-Pulau Aru',
                'kabupaten_kota' => 'Kepulauan Aru',
                'kepala_sekolah' => 'Markus Galanggadjir, S.Pd',
                'akreditasi' => 'B',
                'latitude' => -5.7672,
                'longitude' => 134.2256,
                'tahun_data' => 2024,
                'status_validasi' => 'VERIFIED',
            ],
            [
                'npsn' => '60102002',
                'nama_sekolah' => 'SMAN 1 Tiakur (Maluku Barat Daya)',
                'jenjang' => 'SMA',
                'status_sekolah' => 'Negeri',
                'alamat' => 'Jl. Trans Tiakur Moa',
                'kecamatan' => 'Moa Lakor',
                'kabupaten_kota' => 'Maluku Barat Daya',
                'kepala_sekolah' => 'Albertus Rupilu, S.Pd',
                'akreditasi' => 'B',
                'latitude' => -8.1963,
                'longitude' => 127.9150,
                'tahun_data' => 2024,
                'status_validasi' => 'SUBMITTED',
            ],
            [
                'npsn' => '60102003',
                'nama_sekolah' => 'SMAN 1 Namrole (Buru Selatan)',
                'jenjang' => 'SMA',
                'status_sekolah' => 'Negeri',
                'alamat' => 'Jl. Batu Kepala, Namrole',
                'kecamatan' => 'Namrole',
                'kabupaten_kota' => 'Buru Selatan',
                'kepala_sekolah' => 'Said Solissa, S.Pd',
                'akreditasi' => 'B',
                'latitude' => -3.8564,
                'longitude' => 126.7481,
                'tahun_data' => 2024,
                'status_validasi' => 'VALIDATED',
            ],
            [
                'npsn' => '60102004',
                'nama_sekolah' => 'SMAN 1 Kota Tual',
                'jenjang' => 'SMA',
                'status_sekolah' => 'Negeri',
                'alamat' => 'Jl. Ki Hajar Dewantara, Dullah Selatan',
                'kecamatan' => 'Pulau Dullah Selatan',
                'kabupaten_kota' => 'Kota Tual',
                'kepala_sekolah' => 'Drs. Ibrahim Renhoran',
                'akreditasi' => 'A',
                'latitude' => -5.6264,
                'longitude' => 132.7521,
                'tahun_data' => 2024,
                'status_validasi' => 'VALIDATED',
            ],
        ];

        foreach ($schoolsData as $data) {
            $school = SimandatSchool::updateOrCreate(
                ['npsn' => $data['npsn']],
                $data
            );

            // Generate realistic teachers for this school
            $teachers = [
                ['nama' => 'Drs. Hein Wattimena, M.Si', 'nip' => '197503122000031001', 'jabatan' => 'Guru Madya', 'mapel' => 'Matematika', 'gol' => 'Pembina / IV/a', 'status' => 'PNS', 'sert' => 1, 'jk' => 'L'],
                ['nama' => 'Maria Magdalena Silooy, S.Pd', 'nip' => '198205142006042003', 'jabatan' => 'Guru Muda', 'mapel' => 'Bahasa Inggris', 'gol' => 'Penata / III/c', 'status' => 'PNS', 'sert' => 1, 'jk' => 'P'],
                ['nama' => 'Abdul Malik Bugis, S.Pd.I', 'nip' => '199008222019031007', 'jabatan' => 'Guru Pertama', 'mapel' => 'Pendidikan Agama Islam', 'gol' => 'Penata Muda Tk.I / III/b', 'status' => 'PPPK', 'sert' => 1, 'jk' => 'L'],
                ['nama' => 'Debby Patty, S.Si', 'nip' => null, 'jabatan' => 'Guru Mata Pelajaran', 'mapel' => 'Fisika / IPA', 'gol' => null, 'status' => 'GTY', 'sert' => 0, 'jk' => 'P'],
            ];

            foreach ($teachers as $t) {
                SimandatTeacher::updateOrCreate(
                    [
                        'school_id' => $school->id,
                        'nama' => $t['nama']
                    ],
                    [
                        'nip' => $t['nip'],
                        'nuptk' => $t['nip'] ? '9876' . substr($t['nip'], 0, 8) : null,
                        'jenis_kelamin' => $t['jk'],
                        'jabatan' => $t['jabatan'],
                        'pangkat_golongan' => $t['gol'],
                        'status_kepegawaian' => $t['status'],
                        'pendidikan_terakhir' => 'S1',
                        'mata_pelajaran' => $t['mapel'],
                        'sertifikasi' => $t['sert'],
                        'kabupaten_kota' => $school->kabupaten_kota,
                        'tahun_data' => 2024,
                        'status_validasi' => 'VALIDATED',
                    ]
                );
            }

            // Generate realistic students for this school
            $students = [
                ['nisn' => '0081' . rand(100000, 999999), 'nama' => 'Christian Alexander Patty', 'jk' => 'L', 'kelas' => 'Kelas X-A'],
                ['nisn' => '0082' . rand(100000, 999999), 'nama' => 'Fatimah Az-Zahra Latuconsina', 'jk' => 'P', 'kelas' => 'Kelas X-B'],
                ['nisn' => '0073' . rand(100000, 999999), 'nama' => 'Yohanes Ronald Tetelepta', 'jk' => 'L', 'kelas' => 'Kelas XI MIPA 1'],
                ['nisn' => '0074' . rand(100000, 999999), 'nama' => 'Nurul Hidayah Rumakat', 'jk' => 'P', 'kelas' => 'Kelas XI IPS 2'],
                ['nisn' => '0065' . rand(100000, 999999), 'nama' => 'Geraldo Samuel Soumokil', 'jk' => 'L', 'kelas' => 'Kelas XII MIPA 1'],
            ];

            foreach ($students as $st) {
                SimandatStudent::updateOrCreate(
                    ['nisn' => $st['nisn']],
                    [
                        'nama' => $st['nama'],
                        'nik' => '8171' . rand(1000000000, 9999999999),
                        'jenis_kelamin' => $st['jk'],
                        'school_id' => $school->id,
                        'jenjang' => $school->jenjang,
                        'tingkat_kelas' => $st['kelas'],
                        'kabupaten_kota' => $school->kabupaten_kota,
                        'tahun_ajaran' => '2024/2025',
                        'status' => 'AKTIF',
                        'status_validasi' => 'VALIDATED',
                    ]
                );
            }

            // Generate Sarpras for this school
            $sarprasData = [
                ['nama' => 'Ruang Kelas Teori', 'jenis' => 'Ruang Kelas', 'total' => 18, 'baik' => 15, 'ringan' => 3, 'berat' => 0],
                ['nama' => 'Laboratorium Komputer & Multimedia', 'jenis' => 'Laboratorium', 'total' => 2, 'baik' => 2, 'ringan' => 0, 'berat' => 0],
                ['nama' => 'Laboratorium IPA / Sains', 'jenis' => 'Laboratorium', 'total' => 2, 'baik' => 1, 'ringan' => 1, 'berat' => 0],
                ['nama' => 'Perpustakaan Sekolah Digital', 'jenis' => 'Perpustakaan', 'total' => 1, 'baik' => 1, 'ringan' => 0, 'berat' => 0],
                ['nama' => 'Toilet Siswa & Guru (Sanitasi)', 'jenis' => 'Sanitasi', 'total' => 8, 'baik' => 6, 'ringan' => 2, 'berat' => 0],
                ['nama' => 'Lapangan Olahraga Multifungsi', 'jenis' => 'Lapangan', 'total' => 1, 'baik' => 1, 'ringan' => 0, 'berat' => 0],
            ];

            foreach ($sarprasData as $sp) {
                SimandatSarpras::updateOrCreate(
                    [
                        'school_id' => $school->id,
                        'nama_sarpras' => $sp['nama'] . ' - ' . $school->nama_sekolah
                    ],
                    [
                        'jenis_sarpras' => $sp['jenis'],
                        'jumlah' => $sp['total'],
                        'kondisi_baik' => $sp['baik'],
                        'rusak_ringan' => $sp['ringan'],
                        'rusak_berat' => $sp['berat'],
                        'kabupaten_kota' => $school->kabupaten_kota,
                        'tahun_data' => 2024,
                        'status_validasi' => 'VALIDATED',
                    ]
                );
            }

            // Recalculate School student and teacher aggregates
            $school->update([
                'jumlah_guru' => $school->teachers()->count(),
                'jumlah_siswa' => $school->students()->count(),
            ]);
        }
    }
}
