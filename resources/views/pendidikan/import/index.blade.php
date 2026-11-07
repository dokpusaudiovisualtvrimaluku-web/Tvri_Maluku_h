@extends('layouts.app')

@section('title', 'Import Data Pendidikan (Excel/CSV)')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center space-x-3">
            <span class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl">
                <i class="fas fa-file-import text-xl"></i>
            </span>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Import Data Pendidikan Terintegrasi</h1>
                <p class="text-sm text-slate-500">Tahap 1: Upload File -> Tahap 2: Preview & Validasi Kolom -> Tahap 3: Eksekusi Import</p>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Upload Card -->
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
        <form action="{{ route('pendidikan.import.preview') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-slate-800 mb-2">Pilih Jenis Entitas Data *</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <label class="relative flex flex-col p-4 border-2 rounded-xl cursor-pointer hover:border-blue-500 transition border-slate-200">
                        <input type="radio" name="entity_type" value="schools" checked class="text-blue-600 focus:ring-blue-500">
                        <span class="font-bold text-slate-800 mt-2 text-sm">Satuan Pendidikan</span>
                        <span class="text-xs text-slate-400 mt-1">NPSN, Nama, Jenjang, dll</span>
                    </label>
                    <label class="relative flex flex-col p-4 border-2 rounded-xl cursor-pointer hover:border-blue-500 transition border-slate-200">
                        <input type="radio" name="entity_type" value="teachers" class="text-blue-600 focus:ring-blue-500">
                        <span class="font-bold text-slate-800 mt-2 text-sm">Guru & Tendik</span>
                        <span class="text-xs text-slate-400 mt-1">NIP, NUPTK, Jabatan, dll</span>
                    </label>
                    <label class="relative flex flex-col p-4 border-2 rounded-xl cursor-pointer hover:border-blue-500 transition border-slate-200">
                        <input type="radio" name="entity_type" value="students" class="text-blue-600 focus:ring-blue-500">
                        <span class="font-bold text-slate-800 mt-2 text-sm">Peserta Didik</span>
                        <span class="text-xs text-slate-400 mt-1">NISN, Nama, Rombel, dll</span>
                    </label>
                    <label class="relative flex flex-col p-4 border-2 rounded-xl cursor-pointer hover:border-blue-500 transition border-slate-200">
                        <input type="radio" name="entity_type" value="sarpras" class="text-blue-600 focus:ring-blue-500">
                        <span class="font-bold text-slate-800 mt-2 text-sm">Sarana Prasarana</span>
                        <span class="text-xs text-slate-400 mt-1">Jenis, Kondisi, Jumlah, dll</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-800 mb-2">Unggah File CSV / Excel (.csv, .txt) *</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-2xl hover:border-blue-500 transition">
                    <div class="space-y-2 text-center">
                        <i class="fas fa-cloud-upload-alt text-4xl text-slate-400"></i>
                        <div class="flex text-sm text-slate-600 justify-center">
                            <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                <span>Pilih file dari komputer</span>
                                <input id="file-upload" name="file" type="file" required class="sr-only" accept=".csv,.txt">
                            </label>
                        </div>
                        <p class="text-xs text-slate-500">Format CSV standar UTF-8 dengan delimiter koma (,) atau titik koma (;)</p>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 flex items-start space-x-3">
                <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                <div class="text-xs text-blue-800 leading-relaxed">
                    <strong>Petunjuk Format Kolom CSV:</strong><br>
                    - <b>Satuan Pendidikan:</b> npsn, nama_sekolah, jenjang, status_sekolah, kabupaten_kota, alamat, akreditasi, kepala_sekolah<br>
                    - <b>Guru & Tendik:</b> nip, nama, jenis_kelamin, jabatan, status_kepegawaian, kabupaten_kota, npsn_sekolah<br>
                    - <b>Peserta Didik:</b> nisn, nama, jenis_kelamin, jenjang, tingkat_kelas, kabupaten_kota, npsn_sekolah<br>
                    - <b>Sarpras:</b> nama_sarpras, jenis_sarpras, jumlah, kondisi_baik, rusak_ringan, rusak_berat, kabupaten_kota, npsn_sekolah
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                    <i class="fas fa-eye mr-2"></i> Preview & Validasi Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
