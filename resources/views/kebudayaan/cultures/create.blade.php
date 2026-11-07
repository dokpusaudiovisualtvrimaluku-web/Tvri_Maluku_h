@extends('layouts.app')

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
