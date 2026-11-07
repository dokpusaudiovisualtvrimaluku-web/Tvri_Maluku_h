const fs = require('fs');
const path = require('path');

const baseDir = path.join(__dirname, 'resources', 'views', 'kebudayaan');

function ensureDir(dir) {
    if (!fs.existsSync(dir)) {
        fs.mkdirSync(dir, { recursive: true });
    }
}

['cultures', 'map', 'gallery'].forEach(d => ensureDir(path.join(baseDir, d)));

// 1. CULTURES INDEX
const culturesIndex = `@extends('layouts.app')

@section('title', 'Pangkalan Data Kebudayaan Provinsi Maluku')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div>
            <div class="flex items-center space-x-3">
                <span class="p-2.5 bg-amber-50 text-amber-600 rounded-xl">
                    <i class="fas fa-landmark text-xl"></i>
                </span>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Pangkalan Data Objek Kebudayaan Maluku</h1>
                    <p class="text-sm text-slate-500 mt-0.5">Kelola 16 kategori objek pemajuan kebudayaan, cagar budaya, WBTb, seni & tradisi Maluku</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('kebudayaan.cultures.index', array_merge(request()->all(), ['export' => 'csv'])) }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                <i class="fas fa-file-excel mr-2"></i> Export CSV
            </a>
            <a href="{{ route('kebudayaan.map.index') }}" class="inline-flex items-center px-4 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                <i class="fas fa-map-marked-alt mr-2"></i> Peta Budaya
            </a>
            <a href="{{ route('kebudayaan.cultures.create') }}" class="inline-flex items-center px-4 py-2.5 bg-[#003B70] hover:bg-[#002b52] text-white text-sm font-semibold rounded-xl transition shadow-sm">
                <i class="fas fa-plus mr-2"></i> Tambah Budaya
            </a>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('kebudayaan.cultures.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Pencarian</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama objek, kata kunci..." class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Kategori Budaya</label>
                <select name="kategori" class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500">
                    <option value="">Semua 16 Kategori</option>
                    @foreach($kategoriList as $kat)
                        <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Kabupaten/Kota</label>
                <select name="kabupaten_kota" class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500">
                    <option value="">Semua Wilayah</option>
                    @foreach($kabupatenList as $kab)
                        <option value="{{ $kab }}" {{ request('kabupaten_kota') == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Status Validasi</label>
                <select name="status_validasi" class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500">
                    <option value="">Semua Status</option>
                    @foreach(['DRAFT', 'SUBMITTED', 'VERIFIED', 'VALIDATED', 'REJECTED'] as $st)
                        <option value="{{ $st }}" {{ request('status_validasi') == $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="w-full py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold rounded-xl transition">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('kebudayaan.cultures.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Objek Kebudayaan</th>
                        <th class="px-6 py-4">Kategori & Subkategori</th>
                        <th class="px-6 py-4">Kabupaten/Kota</th>
                        <th class="px-6 py-4">Tahun & Status</th>
                        <th class="px-6 py-4 text-center">Media / Galeri</th>
                        <th class="px-6 py-4">Status Validasi</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($cultures as $item)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $item->foto_url }}" alt="{{ $item->nama }}" class="w-12 h-12 rounded-xl object-cover border border-slate-200 flex-shrink-0">
                                <div>
                                    <a href="{{ route('kebudayaan.cultures.show', $item->id) }}" class="font-bold text-slate-900 hover:text-amber-600 transition block">
                                        {{ $item->nama }}
                                    </a>
                                    <span class="text-xs text-slate-400">{{ Str::limit($item->lokasi ?? $item->kecamatan ?? '-', 35) }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-amber-50 text-amber-800 rounded-lg text-xs font-bold">{{ $item->kategori }}</span>
                            @if($item->subkategori)
                                <span class="text-xs text-slate-400 block mt-1">{{ $item->subkategori }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-700 font-medium">{{ $item->kabupaten_kota }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ $item->tahun }}</div>
                            <span class="text-xs text-slate-500">{{ $item->status }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-1.5 text-xs text-slate-400">
                                @if($item->foto)<i class="fas fa-image text-blue-500" title="Ada Foto"></i>@endif
                                @if($item->video_url)<i class="fas fa-video text-rose-500" title="Ada Video"></i>@endif
                                @if($item->audio_url)<i class="fas fa-volume-up text-amber-500" title="Ada Audio"></i>@endif
                                @if($item->dokumen_pendukung)<i class="fas fa-file-pdf text-emerald-500" title="Ada Dokumen PDF"></i>@endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $valColors = [
                                    'VALIDATED' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'VERIFIED' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'SUBMITTED' => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'DRAFT' => 'bg-slate-100 text-slate-700 border-slate-200',
                                    'REJECTED' => 'bg-rose-100 text-rose-800 border-rose-200',
                                ];
                            @endphp
                            <span class="px-2.5 py-1 rounded-md text-xs font-bold border {{ $valColors[$item->status_validasi] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $item->status_validasi }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('kebudayaan.cultures.show', $item->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('kebudayaan.cultures.edit', $item->id) }}" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('kebudayaan.cultures.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data objek kebudayaan ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-slate-400">
                            <i class="fas fa-landmark text-4xl mb-3 block"></i>
                            Belum ada data objek kebudayaan yang cocok.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($cultures->hasPages())
        <div class="p-6 border-t border-slate-100">
            {{ $cultures->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
`;

// 2. CULTURES CREATE
const culturesCreate = `@extends('layouts.app')

@section('title', 'Tambah Objek Kebudayaan Maluku')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center space-x-3">
            <span class="p-2.5 bg-amber-50 text-amber-600 rounded-xl">
                <i class="fas fa-plus-circle text-xl"></i>
            </span>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Tambah Data Objek Kebudayaan</h1>
                <p class="text-sm text-slate-500">Input data kekayaan budaya Maluku (Cagar Budaya, WBTb, Seni, Tradisi, dll)</p>
            </div>
        </div>
        <a href="{{ route('kebudayaan.cultures.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
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

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
        <form action="{{ route('kebudayaan.cultures.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Nama Objek Kebudayaan *</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Benteng Belgica Banda / Tari Lenso / Kain Tenun Tanimbar" class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Kategori Utama *</label>
                    <select name="kategori" required class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">
                        @foreach($kategoriList as $kat)
                            <option value="{{ $kat }}" {{ old('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Subkategori / Domain</label>
                    <input type="text" name="subkategori" value="{{ old('subkategori') }}" placeholder="Contoh: Seni Pertunjukan / Cagar Budaya Kebendaan / Ritus" class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Kabupaten / Kota *</label>
                    <select name="kabupaten_kota" required class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">
                        <option value="">-- Pilih Wilayah --</option>
                        @foreach($kabupatenList as $kab)
                            <option value="{{ $kab }}" {{ old('kabupaten_kota') == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Kecamatan</label>
                    <input type="text" name="kecamatan" value="{{ old('kecamatan') }}" class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Desa / Kelurahan / Negeri</label>
                    <input type="text" name="desa_kelurahan" value="{{ old('desa_kelurahan') }}" class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Lokasi / Alamat Spesifik</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi') }}" class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Tahun Pencatatan / Penetapan *</label>
                    <input type="number" name="tahun" value="{{ old('tahun', 2024) }}" required class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Status Penetapan *</label>
                    <select name="status" required class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">
                        @foreach(['Ditetapkan Nasional', 'Ditetapkan Provinsi', 'Ditetapkan Kabupaten/Kota', 'Terdaftar', 'Diusulkan', 'Terancam Punah', 'Aktif'] as $st)
                            <option value="{{ $st }}" {{ old('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Latitude (Koordinat GIS)</label>
                    <input type="number" step="0.0000001" name="latitude" value="{{ old('latitude', -3.6547) }}" placeholder="-3.6547000" class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Longitude (Koordinat GIS)</label>
                    <input type="number" step="0.0000001" name="longitude" value="{{ old('longitude', 128.1906) }}" placeholder="128.1906000" class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Deskripsi Sejarah, Makna & Nilai Budaya</label>
                <textarea name="deskripsi" rows="4" placeholder="Jelaskan sejarah, filosofi, ritual, atau latar belakang objek kebudayaan..." class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">{{ old('deskripsi') }}</textarea>
            </div>

            <!-- Multimedia & Files -->
            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center">
                    <i class="fas fa-photo-video text-amber-600 mr-2"></i> Berkas Multimedia & Dokumen Pendukung
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Unggah Foto Utama</label>
                        <input type="file" name="foto" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-800 hover:file:bg-amber-200 cursor-pointer">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Unggah File Audio Rekaman (.mp3, .wav)</label>
                        <input type="file" name="audio_file" accept="audio/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-100 file:text-purple-800 hover:file:bg-purple-200 cursor-pointer">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Unggah Dokumen / Naskah Kajian (.pdf)</label>
                        <input type="file" name="dokumen_file" accept=".pdf,.doc,.docx" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200 cursor-pointer">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">URL Video YouTube / Streaming</label>
                        <input type="url" name="video_url" value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=..." class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2 focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Link Eksternal Referensi (WBTb Kemdikbud/Portal Resmi)</label>
                        <input type="url" name="link_eksternal" value="{{ old('link_eksternal') }}" placeholder="https://warisanbudaya.kemdikbud.go.id/..." class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2 focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Kata Kunci / Tags (Pisahkan dengan koma)</label>
                        <input type="text" name="kata_kunci" value="{{ old('kata_kunci') }}" placeholder="ambon, tifa, musik tradisional, wbtb" class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2 focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Status Validasi</label>
                <select name="status_validasi" class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">
                    @foreach(['DRAFT', 'SUBMITTED', 'VERIFIED', 'VALIDATED', 'REJECTED'] as $v)
                        <option value="{{ $v }}" {{ old('status_validasi') == $v ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>

            <div class="pt-6 border-t border-slate-100 flex justify-end space-x-3">
                <a href="{{ route('kebudayaan.cultures.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-[#003B70] hover:bg-[#002b52] text-white text-sm font-semibold rounded-xl transition shadow-sm">
                    <i class="fas fa-save mr-2"></i> Simpan Data Kebudayaan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
`;

// 3. CULTURES EDIT
const culturesEdit = `@extends('layouts.app')

@section('title', 'Edit Objek Kebudayaan Maluku')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center space-x-3">
            <span class="p-2.5 bg-amber-50 text-amber-600 rounded-xl">
                <i class="fas fa-edit text-xl"></i>
            </span>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Data Kebudayaan</h1>
                <p class="text-sm text-slate-500">Perbarui informasi objek: {{ $culture->nama }}</p>
            </div>
        </div>
        <a href="{{ route('kebudayaan.cultures.show', $culture->id) }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
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

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
        <form action="{{ route('kebudayaan.cultures.update', $culture->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Nama Objek Kebudayaan *</label>
                    <input type="text" name="nama" value="{{ old('nama', $culture->nama) }}" required class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Kategori Utama *</label>
                    <select name="kategori" required class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">
                        @foreach($kategoriList as $kat)
                            <option value="{{ $kat }}" {{ old('kategori', $culture->kategori) == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Subkategori / Domain</label>
                    <input type="text" name="subkategori" value="{{ old('subkategori', $culture->subkategori) }}" class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Kabupaten / Kota *</label>
                    <select name="kabupaten_kota" required class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">
                        @foreach($kabupatenList as $kab)
                            <option value="{{ $kab }}" {{ old('kabupaten_kota', $culture->kabupaten_kota) == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Kecamatan</label>
                    <input type="text" name="kecamatan" value="{{ old('kecamatan', $culture->kecamatan) }}" class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Desa / Kelurahan / Negeri</label>
                    <input type="text" name="desa_kelurahan" value="{{ old('desa_kelurahan', $culture->desa_kelurahan) }}" class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Lokasi / Alamat Spesifik</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi', $culture->lokasi) }}" class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Tahun Pencatatan / Penetapan *</label>
                    <input type="number" name="tahun" value="{{ old('tahun', $culture->tahun) }}" required class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Status Penetapan *</label>
                    <select name="status" required class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">
                        @foreach(['Ditetapkan Nasional', 'Ditetapkan Provinsi', 'Ditetapkan Kabupaten/Kota', 'Terdaftar', 'Diusulkan', 'Terancam Punah', 'Aktif'] as $st)
                            <option value="{{ $st }}" {{ old('status', $culture->status) == $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Latitude (Koordinat GIS)</label>
                    <input type="number" step="0.0000001" name="latitude" value="{{ old('latitude', $culture->latitude) }}" class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Longitude (Koordinat GIS)</label>
                    <input type="number" step="0.0000001" name="longitude" value="{{ old('longitude', $culture->longitude) }}" class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Deskripsi Sejarah, Makna & Nilai Budaya</label>
                <textarea name="deskripsi" rows="4" class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">{{ old('deskripsi', $culture->deskripsi) }}</textarea>
            </div>

            <!-- Multimedia & Files -->
            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center">
                    <i class="fas fa-photo-video text-amber-600 mr-2"></i> Berkas Multimedia & Dokumen Pendukung
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Ganti Foto Utama</label>
                        <input type="file" name="foto" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-800 hover:file:bg-amber-200 cursor-pointer">
                        @if($culture->foto)
                            <span class="text-[11px] text-emerald-600 mt-1 block"><i class="fas fa-check-circle mr-1"></i> Foto sudah terpasang</span>
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Ganti File Audio (.mp3, .wav)</label>
                        <input type="file" name="audio_file" accept="audio/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-100 file:text-purple-800 hover:file:bg-purple-200 cursor-pointer">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Ganti Dokumen PDF (.pdf)</label>
                        <input type="file" name="dokumen_file" accept=".pdf,.doc,.docx" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200 cursor-pointer">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">URL Video YouTube</label>
                        <input type="url" name="video_url" value="{{ old('video_url', $culture->video_url) }}" class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2 focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Link Eksternal Referensi</label>
                        <input type="url" name="link_eksternal" value="{{ old('link_eksternal', $culture->link_eksternal) }}" class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2 focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Kata Kunci / Tags</label>
                        <input type="text" name="kata_kunci" value="{{ old('kata_kunci', $culture->kata_kunci) }}" class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2 focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Status Validasi</label>
                <select name="status_validasi" class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-amber-500">
                    @foreach(['DRAFT', 'SUBMITTED', 'VERIFIED', 'VALIDATED', 'REJECTED'] as $v)
                        <option value="{{ $v }}" {{ old('status_validasi', $culture->status_validasi) == $v ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>

            <div class="pt-6 border-t border-slate-100 flex justify-end space-x-3">
                <a href="{{ route('kebudayaan.cultures.show', $culture->id) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                    <i class="fas fa-save mr-2"></i> Perbarui Data Kebudayaan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
`;

// 4. CULTURES SHOW
const culturesShow = `@extends('layouts.app')

@section('title', $culture->nama . ' - Pangkalan Data Kebudayaan')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm relative overflow-hidden">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-5">
                <img src="{{ $culture->foto_url }}" alt="{{ $culture->nama }}" class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover ring-4 ring-amber-50 border border-slate-200 flex-shrink-0 shadow-md">
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="px-3 py-1 bg-amber-50 text-amber-800 rounded-full text-xs font-bold border border-amber-200">
                            {{ $culture->kategori }}
                        </span>
                        @if($culture->subkategori)
                            <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-semibold">
                                {{ $culture->subkategori }}
                            </span>
                        @endif
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-800 rounded-full text-xs font-bold border border-emerald-200">
                            {{ $culture->status }} ({{ $culture->tahun }})
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $culture->nama }}</h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 flex items-center">
                        <i class="fas fa-map-marker-alt text-rose-500 mr-1.5"></i>
                        {{ $culture->lokasi ?? $culture->desa_kelurahan ?? '-' }}, {{ $culture->kecamatan ?? '-' }}, {{ $culture->kabupaten_kota }}
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-2.5">
                <a href="{{ route('kebudayaan.cultures.edit', $culture->id) }}" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl transition shadow-sm flex items-center space-x-1.5">
                    <i class="fas fa-edit"></i>
                    <span>Edit Data</span>
                </a>
                <a href="{{ route('kebudayaan.cultures.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content: Details + Multimedia + Map -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2 Cols: Description & Multimedia -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Deskripsi & Sejarah -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 mb-3 flex items-center">
                    <i class="fas fa-book-open text-amber-600 mr-2"></i> Deskripsi, Sejarah & Nilai Filosofis
                </h3>
                <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                    {{ $culture->deskripsi ?: 'Belum ada deskripsi naratif untuk objek budaya ini.' }}
                </div>
                
                @if($culture->kata_kunci)
                    <div class="mt-4 pt-4 border-t border-slate-100 flex flex-wrap gap-1.5 items-center">
                        <span class="text-xs font-semibold text-slate-400 mr-2"><i class="fas fa-tags"></i> Tags:</span>
                        @foreach(explode(',', $culture->kata_kunci) as $tag)
                            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-medium">#{{ trim($tag) }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Multimedia Players (Audio / Video / PDF) -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 flex items-center">
                    <i class="fas fa-photo-video text-amber-600 mr-2"></i> Berkas Multimedia & Arsip Pendukung
                </h3>

                <!-- Audio Player -->
                @if($culture->audio_url)
                    <div class="p-4 bg-purple-50 rounded-2xl border border-purple-100 space-y-2">
                        <p class="text-xs font-bold text-purple-900 flex items-center">
                            <i class="fas fa-volume-up text-purple-600 mr-2"></i> Rekaman Suara / Instrumen Tradisional / Dialek
                        </p>
                        <audio controls class="w-full">
                            <source src="{{ str_starts_with($culture->audio_url, 'http') ? $culture->audio_url : asset('storage/' . $culture->audio_url) }}">
                            Browser Anda tidak mendukung pemutar audio.
                        </audio>
                    </div>
                @endif

                <!-- Video Embed / Link -->
                @if($culture->video_url)
                    <div class="p-4 bg-rose-50 rounded-2xl border border-rose-100 space-y-2">
                        <p class="text-xs font-bold text-rose-900 flex items-center justify-between">
                            <span class="flex items-center"><i class="fas fa-video text-rose-600 mr-2"></i> Dokumentasi Video</span>
                            <a href="{{ $culture->video_url }}" target="_blank" class="text-xs text-rose-700 hover:underline">Buka di Tab Baru &rarr;</a>
                        </p>
                        @php
                            $youtubeId = null;
                            if (preg_match('/(?:youtube\\.com\\/(?:[^\\/]+\\/.+\\/|(?:v|e(?:mbed)?)\\/|.*[?&]v=)|youtu\\.be\\/)([^"&?\\/ ]{11})/i', $culture->video_url, $match)) {
                                $youtubeId = $match[1];
                            }
                        @endphp
                        @if($youtubeId)
                            <div class="aspect-video w-full rounded-xl overflow-hidden shadow-inner">
                                <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $youtubeId }}" frameborder="0" allowfullscreen></iframe>
                            </div>
                        @else
                            <a href="{{ $culture->video_url }}" target="_blank" class="block p-4 bg-white rounded-xl border border-rose-200 text-sm font-semibold text-rose-800 hover:bg-rose-50">
                                <i class="fas fa-play-circle text-rose-600 mr-2"></i> Tonton Video: {{ $culture->video_url }}
                            </a>
                        @endif
                    </div>
                @endif

                <!-- Dokumen PDF Pendukung -->
                @if($culture->dokumen_pendukung)
                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="p-2.5 bg-emerald-100 text-emerald-700 rounded-xl">
                                <i class="fas fa-file-pdf text-xl"></i>
                            </span>
                            <div>
                                <p class="text-xs font-bold text-emerald-900">Dokumen Kajian / Naskah Warisan Budaya</p>
                                <p class="text-[11px] text-emerald-700">Format PDF Resmi Disdikbud Maluku</p>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $culture->dokumen_pendukung) }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                            <i class="fas fa-download mr-1"></i> Unduh / Buka PDF
                        </a>
                    </div>
                @endif

                @if(!$culture->audio_url && !$culture->video_url && !$culture->dokumen_pendukung)
                    <div class="p-6 text-center text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                        <i class="fas fa-file-alt text-3xl mb-2 block"></i>
                        Belum ada berkas multimedia tambahan. Anda dapat mengunggahnya pada menu Edit.
                    </div>
                @endif
            </div>

        </div>

        <!-- Right Col: Metadata, Map & External Links -->
        <div class="space-y-6">
            
            <!-- Metadata Card -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 pb-3 border-b border-slate-100">
                    Informasi Objek Budaya
                </h3>
                <dl class="space-y-3 text-xs">
                    <div>
                        <dt class="text-slate-400 font-medium">Kabupaten/Kota</dt>
                        <dd class="text-slate-800 font-bold mt-0.5">{{ $culture->kabupaten_kota }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Kecamatan</dt>
                        <dd class="text-slate-800 font-bold mt-0.5">{{ $culture->kecamatan ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Desa / Negeri</dt>
                        <dd class="text-slate-800 font-bold mt-0.5">{{ $culture->desa_kelurahan ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Tahun Pencatatan</dt>
                        <dd class="text-slate-800 font-bold mt-0.5">{{ $culture->tahun }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Status Validasi</dt>
                        <dd class="mt-1">
                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-lg">
                                {{ $culture->status_validasi }}
                            </span>
                        </dd>
                    </div>
                    @if($culture->link_eksternal)
                    <div class="pt-2">
                        <dt class="text-slate-400 font-medium">Portal / Referensi Resmi</dt>
                        <dd class="mt-1">
                            <a href="{{ $culture->link_eksternal }}" target="_blank" class="text-blue-600 hover:underline font-semibold block truncate">
                                <i class="fas fa-external-link-alt mr-1"></i> Buka Tautan Referensi
                            </a>
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Mini Map Preview -->
            @if($culture->latitude && $culture->longitude)
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center justify-between">
                    <span>Titik Lokasi Geografis</span>
                    <a href="{{ route('kebudayaan.map.index', ['kabupaten' => $culture->kabupaten_kota]) }}" class="text-[10px] text-cyan-600 font-bold hover:underline">Peta Penuh &rarr;</a>
                </h3>
                <div id="miniMap" class="w-full h-48 rounded-xl overflow-hidden border border-slate-200 z-0"></div>
                <p class="text-[11px] text-slate-400 font-mono text-center">
                    Lat: {{ $culture->latitude }}, Lng: {{ $culture->longitude }}
                </p>
            </div>

            <!-- Leaflet CSS & JS -->
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const map = L.map('miniMap').setView([{{ $culture->latitude }}, {{ $culture->longitude }}], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(map);
                L.marker([{{ $culture->latitude }}, {{ $culture->longitude }}]).addTo(map)
                    .bindPopup("<b>{{ $culture->nama }}</b><br>{{ $culture->kategori }}").openPopup();
            });
            </script>
            @endif

        </div>

    </div>
</div>
@endsection
`;

// 5. MAP INDEX
const mapIndex = `@extends('layouts.app')

@section('title', 'Peta Geografis Sebaran Kebudayaan Provinsi Maluku')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div>
            <div class="flex items-center space-x-3">
                <span class="p-2.5 bg-cyan-50 text-cyan-600 rounded-xl">
                    <i class="fas fa-map-marked-alt text-xl"></i>
                </span>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Peta Persebaran Kebudayaan Maluku</h1>
                    <p class="text-sm text-slate-500 mt-0.5">Pemetaan geografis interaktif titik cagar budaya, situs, sanggar, dan objek budaya di 11 Kab/Kota</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <span class="px-3 py-1.5 bg-cyan-100 text-cyan-800 rounded-xl text-xs font-bold border border-cyan-200">
                <i class="fas fa-map-pin mr-1"></i> {{ $totalMarkers }} Titik Terpetakan
            </span>
            <a href="{{ route('kebudayaan.cultures.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                <i class="fas fa-list mr-2"></i> Tabel Data
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('kebudayaan.map.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Pilih Kabupaten / Kota</label>
                <select name="kabupaten" class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-cyan-500">
                    <option value="all">Semua Kabupaten/Kota di Maluku</option>
                    @foreach($kabupatenList as $kab)
                        <option value="{{ $kab }}" {{ $kabupaten == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Pilih Kategori Budaya</label>
                <select name="kategori" class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-cyan-500">
                    <option value="all">Semua 16 Kategori</option>
                    @foreach($kategoriList as $kat)
                        <option value="{{ $kat }}" {{ $kategori == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="w-full py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-semibold rounded-xl transition">
                    <i class="fas fa-filter mr-1"></i> Terapkan Filter Peta
                </button>
                <a href="{{ route('kebudayaan.map.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Map Canvas Container -->
    <div class="bg-white p-4 rounded-3xl shadow-sm border border-slate-100">
        <div id="malukuCultureMap" class="w-full h-[600px] rounded-2xl overflow-hidden z-0 border border-slate-200"></div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const markersData = {!! json_encode($markers) !!};

    // Center on Maluku (approx lat -3.7, lng 128.8)
    const map = L.map('malukuCultureMap').setView([-3.70, 128.80], 7);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© OpenStreetMap contributors | Disdikbud Provinsi Maluku'
    }).addTo(map);

    const bounds = [];

    markersData.forEach(function(m) {
        if (m.lat && m.lng) {
            const popupContent = \`
                <div style="font-family: sans-serif; width: 200px;">
                    <img src="\${m.foto}" style="width: 100%; height: 110px; object-fit: cover; border-radius: 8px; margin-bottom: 8px;" />
                    <span style="font-size: 10px; font-weight: bold; background: #FEF3C7; color: #92400E; padding: 2px 6px; border-radius: 4px;">\${m.kategori}</span>
                    <h4 style="font-size: 13px; font-weight: bold; color: #0F172A; margin: 4px 0;">\${m.nama}</h4>
                    <p style="font-size: 11px; color: #64748B; margin: 0 0 8px 0;">📍 \${m.kabupaten_kota}</p>
                    <a href="\${m.url}" style="display: block; text-align: center; background: #003B70; color: white; padding: 5px; font-size: 11px; font-weight: bold; border-radius: 6px; text-decoration: none;">Lihat Detail &rarr;</a>
                </div>
            \`;

            const marker = L.marker([m.lat, m.lng]).addTo(map).bindPopup(popupContent);
            bounds.push([m.lat, m.lng]);
        }
    });

    if (bounds.length > 0) {
        map.fitBounds(bounds, { padding: [40, 40] });
    }
});
</script>
@endsection
`;

// 6. GALLERY INDEX
const galleryIndex = `@extends('layouts.app')

@section('title', 'Galeri Multimedia Kebudayaan Maluku')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div>
            <div class="flex items-center space-x-3">
                <span class="p-2.5 bg-purple-50 text-purple-600 rounded-xl">
                    <i class="fas fa-photo-video text-xl"></i>
                </span>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Galeri Multimedia Kebudayaan Maluku</h1>
                    <p class="text-sm text-slate-500 mt-0.5">Eksplorasi visual foto, rekaman audio, dokumentasi video, dan naskah warisan budaya</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('kebudayaan.cultures.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                <i class="fas fa-list mr-1"></i> Data Objek
            </a>
            <a href="{{ route('kebudayaan.cultures.create') }}" class="px-4 py-2.5 bg-[#003B70] hover:bg-[#002b52] text-white text-sm font-semibold rounded-xl transition shadow-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Berkas
            </a>
        </div>
    </div>

    <!-- Media Filter Tabs & Category Filter -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <!-- Media Type Tabs -->
        <div class="flex items-center space-x-2 overflow-x-auto pb-1 md:pb-0">
            <a href="{{ route('kebudayaan.gallery.index', ['type' => 'all', 'kategori' => $selectedKategori]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $selectedType === 'all' ? 'bg-[#003B70] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua Media
            </a>
            <a href="{{ route('kebudayaan.gallery.index', ['type' => 'photo', 'kategori' => $selectedKategori]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ $selectedType === 'photo' ? 'bg-[#003B70] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <i class="fas fa-image"></i>
                <span>Foto ({{ $stats['total_foto'] }})</span>
            </a>
            <a href="{{ route('kebudayaan.gallery.index', ['type' => 'video', 'kategori' => $selectedKategori]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ $selectedType === 'video' ? 'bg-[#003B70] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <i class="fas fa-video"></i>
                <span>Video ({{ $stats['total_video'] }})</span>
            </a>
            <a href="{{ route('kebudayaan.gallery.index', ['type' => 'audio', 'kategori' => $selectedKategori]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ $selectedType === 'audio' ? 'bg-[#003B70] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <i class="fas fa-volume-up"></i>
                <span>Audio ({{ $stats['total_audio'] }})</span>
            </a>
            <a href="{{ route('kebudayaan.gallery.index', ['type' => 'pdf', 'kategori' => $selectedKategori]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ $selectedType === 'pdf' ? 'bg-[#003B70] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <i class="fas fa-file-pdf"></i>
                <span>Dokumen ({{ $stats['total_dokumen'] }})</span>
            </a>
        </div>

        <!-- Category Dropdown Filter -->
        <form method="GET" action="{{ route('kebudayaan.gallery.index') }}" class="flex items-center space-x-2">
            <input type="hidden" name="type" value="{{ $selectedType }}">
            <select name="kategori" onchange="this.form.submit()" class="text-xs rounded-xl border border-slate-200 px-3 py-2 font-semibold text-slate-700 focus:ring-2 focus:ring-purple-500">
                <option value="all">Semua Kategori Budaya</option>
                @foreach($kategoriList as $kat)
                    <option value="{{ $kat }}" {{ $selectedKategori === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Multimedia Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($cultures as $item)
        <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-md transition group flex flex-col justify-between">
            <div class="relative aspect-video w-full overflow-hidden bg-slate-100">
                <img src="{{ $item->foto_url }}" alt="{{ $item->nama }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <span class="absolute top-3 left-3 px-2.5 py-1 bg-slate-900/70 backdrop-blur-md text-amber-300 text-[10px] font-bold rounded-lg uppercase tracking-wider">
                    {{ $item->kategori }}
                </span>
                
                <!-- Media Indicators -->
                <div class="absolute bottom-3 right-3 flex items-center space-x-1.5">
                    @if($item->video_url)
                        <span class="w-6 h-6 rounded-full bg-rose-600 text-white flex items-center justify-center text-[10px] shadow" title="Ada Video">
                            <i class="fas fa-play"></i>
                        </span>
                    @endif
                    @if($item->audio_url)
                        <span class="w-6 h-6 rounded-full bg-purple-600 text-white flex items-center justify-center text-[10px] shadow" title="Ada Audio">
                            <i class="fas fa-volume-up"></i>
                        </span>
                    @endif
                    @if($item->dokumen_pendukung)
                        <span class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px] shadow" title="Ada PDF">
                            <i class="fas fa-file-pdf"></i>
                        </span>
                    @endif
                </div>
            </div>

            <div class="p-5 flex-1 flex flex-col justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 group-hover:text-amber-600 transition line-clamp-1">
                        {{ $item->nama }}
                    </h3>
                    <p class="text-xs text-slate-400 mt-1 flex items-center">
                        <i class="fas fa-map-marker-alt text-rose-400 mr-1 text-[10px]"></i>
                        {{ $item->kabupaten_kota }}
                    </p>
                    <p class="text-xs text-slate-600 mt-2.5 line-clamp-2 leading-relaxed">
                        {{ $item->deskripsi ?: 'Tidak ada deskripsi singkat.' }}
                    </p>
                </div>

                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-slate-400">{{ $item->tahun }} &bull; {{ $item->status }}</span>
                    <a href="{{ route('kebudayaan.cultures.show', $item->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl transition text-xs font-bold flex items-center">
                        Detail <i class="fas fa-chevron-right ml-1 text-[10px]"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 text-center text-slate-400 bg-white rounded-3xl border border-slate-100">
            <i class="fas fa-photo-video text-5xl mb-3 block"></i>
            <p class="text-sm font-semibold">Tidak ditemukan berkas media pada filter ini.</p>
        </div>
        @endforelse
    </div>

    @if($cultures->hasPages())
    <div class="p-6 bg-white rounded-2xl border border-slate-100 shadow-sm">
        {{ $cultures->links() }}
    </div>
    @endif
</div>
@endsection
`;

// Write all views
fs.writeFileSync(path.join(baseDir, 'cultures', 'index.blade.php'), culturesIndex);
fs.writeFileSync(path.join(baseDir, 'cultures', 'create.blade.php'), culturesCreate);
fs.writeFileSync(path.join(baseDir, 'cultures', 'edit.blade.php'), culturesEdit);
fs.writeFileSync(path.join(baseDir, 'cultures', 'show.blade.php'), culturesShow);
fs.writeFileSync(path.join(baseDir, 'map', 'index.blade.php'), mapIndex);
fs.writeFileSync(path.join(baseDir, 'gallery', 'index.blade.php'), galleryIndex);

console.log('All 6 Stage 4 views generated successfully!');
