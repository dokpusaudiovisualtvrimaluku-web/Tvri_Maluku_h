<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use App\Models\SimandatSchool;
use App\Models\SimandatTeacher;
use App\Models\SimandatStudent;
use App\Models\SimandatSarpras;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{
    public function index()
    {
        return view('pendidikan.import.index');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'entity_type' => 'required|in:schools,teachers,students,sarpras',
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('file');
        $entityType = $request->entity_type;
        $originalName = $file->getClientOriginalName();

        $path = $file->storeAs('imports/temp', uniqid() . '_' . $originalName);

        $fullPath = storage_path('app/' . $path);
        if (!file_exists($fullPath)) {
            $fullPath = storage_path('app/private/' . $path);
        }

        $rows = [];
        $header = [];
        if (($handle = fopen($fullPath, 'r')) !== false) {
            $delimiter = ',';
            // Auto detect delimiter
            $firstLine = fgets($handle);
            if (strpos($firstLine, ';') !== false && strpos($firstLine, ',') === false) {
                $delimiter = ';';
            }
            rewind($handle);

            $line = 0;
            while (($data = fgetcsv($handle, 2000, $delimiter)) !== false) {
                if ($line === 0) {
                    $header = array_map('trim', $data);
                } else {
                    if (count(array_filter($data)) > 0) {
                        $rows[] = $data;
                    }
                }
                $line++;
            }
            fclose($handle);
        }

        $previewRows = array_slice($rows, 0, 10);
        $totalRows = count($rows);

        return view('pendidikan.import.preview', [
            'entity_type' => $entityType,
            'file_path' => $path,
            'original_name' => $originalName,
            'header' => $header,
            'preview_rows' => $previewRows,
            'total_rows' => $totalRows,
        ]);
    }

    public function execute(Request $request)
    {
        $request->validate([
            'entity_type' => 'required|in:schools,teachers,students,sarpras',
            'file_path' => 'required|string',
        ]);

        $path = $request->file_path;
        $entityType = $request->entity_type;

        $fullPath = storage_path('app/' . $path);
        if (!file_exists($fullPath)) {
            $fullPath = storage_path('app/private/' . $path);
        }

        if (!file_exists($fullPath)) {
            return redirect()->route('pendidikan.import.index')->with('error', 'File import tidak ditemukan atau sesi kadaluarsa.');
        }

        $importedCount = 0;
        $skippedCount = 0;
        $errors = [];
        $rowNumber = 1; // Baris 1 adalah header

        if (($handle = fopen($fullPath, 'r')) !== false) {
            $delimiter = ',';
            $firstLine = fgets($handle);
            if (strpos($firstLine, ';') !== false && strpos($firstLine, ',') === false) {
                $delimiter = ';';
            }
            rewind($handle);

            $header = fgetcsv($handle, 2000, $delimiter);
            $header = array_map(function($h) {
                return strtolower(trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $h)));
            }, $header);

            while (($row = fgetcsv($handle, 2000, $delimiter)) !== false) {
                $rowNumber++;
                if (count($row) < count($header)) {
                    continue;
                }
                $data = array_combine($header, array_slice($row, 0, count($header)));
                
                try {
                    if ($entityType === 'schools') {
                        $npsn = $data['npsn'] ?? null;
                        if ($npsn) {
                            $nama = $data['nama_sekolah'] ?? $data['nama'] ?? 'Sekolah ' . $npsn;
                            $kab = $data['kabupaten_kota'] ?? $data['kabupaten'] ?? 'Kota Ambon';
                            SimandatSchool::updateOrCreate(
                                ['npsn' => $npsn],
                                [
                                    'nama' => $nama,
                                    'nama_sekolah' => $nama,
                                    'jenjang' => strtoupper($data['jenjang'] ?? 'SMA'),
                                    'status_sekolah' => $data['status_sekolah'] ?? $data['status'] ?? 'Negeri',
                                    'cabang' => $kab,
                                    'kabupaten_kota' => $kab,
                                    'alamat' => $data['alamat'] ?? null,
                                    'kecamatan' => $data['kecamatan'] ?? null,
                                    'kepala_sekolah' => $data['kepala_sekolah'] ?? null,
                                    'akreditasi' => $data['akreditasi'] ?? 'A',
                                    'tahun_data' => $data['tahun_data'] ?? '2024',
                                    'status_validasi' => 'VALIDATED',
                                ]
                            );
                            $importedCount++;
                        }
                    } elseif ($entityType === 'teachers') {
                        $nama = $data['nama'] ?? null;
                        if ($nama) {
                            $npsn = trim($data['npsn_sekolah'] ?? $data['npsn'] ?? '');
                            if (empty($npsn)) {
                                $skippedCount++;
                                $errors[] = "Baris " . ($rowNumber) . ": NPSN Sekolah wajib diisi untuk data guru ($nama).";
                                continue;
                            }

                            $school = SimandatSchool::where('npsn', $npsn)->first();
                            if (!$school) {
                                $skippedCount++;
                                $errors[] = "Baris " . ($rowNumber) . ": Sekolah dengan NPSN '{$npsn}' tidak ditemukan di database.";
                                continue;
                            }

                            SimandatTeacher::create([
                                'nama' => $nama,
                                'nip' => $data['nip'] ?? null,
                                'nuptk' => $data['nuptk'] ?? null,
                                'jenis_kelamin' => strtoupper($data['jenis_kelamin'] ?? 'L') === 'P' ? 'P' : 'L',
                                'jabatan' => $data['jabatan'] ?? 'Guru Mata Pelajaran',
                                'mata_pelajaran' => $data['mata_pelajaran'] ?? $data['mapel'] ?? null,
                                'status_kepegawaian' => $data['status_kepegawaian'] ?? 'PNS',
                                'pangkat_golongan' => $data['pangkat_golongan'] ?? null,
                                'school_id' => $school->id,
                                'pendidikan_terakhir' => $data['pendidikan_terakhir'] ?? 'S1',
                                'sertifikasi' => in_array(strtolower($data['sertifikasi'] ?? ''), ['1', 'sudah', 'ya', 'true']) ? 1 : 0,
                                'kabupaten_kota' => $data['kabupaten_kota'] ?? $school->kabupaten_kota,
                                'tahun_data' => $data['tahun_data'] ?? 2024,
                                'status_validasi' => 'VALIDATED',
                            ]);
                            $importedCount++;
                        }
                    } elseif ($entityType === 'students') {
                        $nisn = $data['nisn'] ?? null;
                        if ($nisn) {
                            $npsn = trim($data['npsn_sekolah'] ?? $data['npsn'] ?? '');
                            if (empty($npsn)) {
                                $skippedCount++;
                                $errors[] = "Baris " . ($rowNumber) . ": NPSN Sekolah wajib diisi untuk siswa NISN {$nisn}.";
                                continue;
                            }

                            $school = SimandatSchool::where('npsn', $npsn)->first();
                            if (!$school) {
                                $skippedCount++;
                                $errors[] = "Baris " . ($rowNumber) . ": Sekolah dengan NPSN '{$npsn}' tidak ditemukan di database.";
                                continue;
                            }

                            SimandatStudent::updateOrCreate(
                                ['nisn' => $nisn],
                                [
                                    'nama' => $data['nama'] ?? 'Peserta Didik',
                                    'nik' => $data['nik'] ?? null,
                                    'jenis_kelamin' => strtoupper($data['jenis_kelamin'] ?? 'L') === 'P' ? 'P' : 'L',
                                    'school_id' => $school->id,
                                    'jenjang' => $data['jenjang'] ?? $school->jenjang,
                                    'tingkat_kelas' => $data['tingkat_kelas'] ?? $data['kelas'] ?? 'Kelas X',
                                    'kabupaten_kota' => $data['kabupaten_kota'] ?? $school->kabupaten_kota,
                                    'tahun_ajaran' => $data['tahun_ajaran'] ?? '2024/2025',
                                    'status' => 'AKTIF',
                                    'status_validasi' => 'VALIDATED',
                                ]
                            );
                            $importedCount++;
                        }
                    } elseif ($entityType === 'sarpras') {
                        $nama = $data['nama_sarpras'] ?? $data['nama'] ?? null;
                        if ($nama) {
                            $npsn = trim($data['npsn_sekolah'] ?? $data['npsn'] ?? '');
                            if (empty($npsn)) {
                                $skippedCount++;
                                $errors[] = "Baris " . ($rowNumber) . ": NPSN Sekolah wajib diisi untuk data sarpras ($nama).";
                                continue;
                            }

                            $school = SimandatSchool::where('npsn', $npsn)->first();
                            if (!$school) {
                                $skippedCount++;
                                $errors[] = "Baris " . ($rowNumber) . ": Sekolah dengan NPSN '{$npsn}' tidak ditemukan di database.";
                                continue;
                            }

                            SimandatSarpras::create([
                                'nama_sarpras' => $nama,
                                'jenis_sarpras' => $data['jenis_sarpras'] ?? 'Ruang Kelas',
                                'school_id' => $school->id,
                                'jumlah' => intval($data['jumlah'] ?? 1),
                                'kondisi_baik' => intval($data['kondisi_baik'] ?? 1),
                                'rusak_ringan' => intval($data['rusak_ringan'] ?? 0),
                                'rusak_berat' => intval($data['rusak_berat'] ?? 0),
                                'kabupaten_kota' => $data['kabupaten_kota'] ?? $school->kabupaten_kota,
                                'tahun_data' => $data['tahun_data'] ?? 2024,
                                'status_validasi' => 'VALIDATED',
                            ]);
                            $importedCount++;
                        }
                    }
                } catch (\Exception $e) {
                    $skippedCount++;
                    $errors[] = "Baris " . ($rowNumber) . ": Gagal diproses (" . $e->getMessage() . ").";
                }
            }
            fclose($handle);
        }

        ActivityLogger::log('IMPORT_DATA', "Melakukan import data {$entityType} ({$importedCount} baris berhasil, {$skippedCount} baris dilewati)");

        $redirect = redirect()->route('pendidikan.' . $entityType . '.index');
        $message = "Proses import selesai! Total {$importedCount} data {$entityType} berhasil dimasukkan ke sistem.";

        if ($skippedCount > 0) {
            $message .= " Namun, {$skippedCount} baris dilewati karena sekolah/NPSN tidak ditemukan atau format tidak valid.";
            return $redirect->with('warning', $message)->with('import_errors', array_slice($errors, 0, 10));
        }

        return $redirect->with('success', $message);
    }
}
