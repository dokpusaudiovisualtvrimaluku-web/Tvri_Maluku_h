@extends('layouts.app')

@section('title', 'Tambah Satuan Pendidikan - Pangkalan Data Pendidikan')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Breadcrumb & Back -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-xs text-slate-500">
            <a href="{{ route('pendidikan.schools.index') }}" class="hover:text-blue-600 font-medium">Pangkalan Data Pendidikan</a>
            <span>/</span>
            <span class="text-slate-800 font-bold">Input Satuan Pendidikan</span>
        </div>
        <a href="{{ route('pendidikan.schools.index') }}" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Kembali ke Daftar</span>
        </a>
    </div>

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-[#00264A] to-[#004880] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10">
            <span class="px-2.5 py-0.5 bg-blue-400/20 text-blue-200 border border-blue-400/40 rounded-full text-[11px] font-bold tracking-wider uppercase">
                Formulir Pangkalan Data Pendidikan
            </span>
            <h1 class="text-2xl font-black text-white mt-2">Registrasi Satuan Pendidikan Baru</h1>
            <p class="text-xs text-blue-100 max-w-2xl mt-1 leading-relaxed">
                Silakan lengkapi data identitas, lokasi, dan status satuan pendidikan (Sekolah) ke dalam pangkalan data terpusat (Tahap 3).
            </p>
        </div>
        <div class="absolute right-0 top-0 opacity-10 pointer-events-none">
            <svg class="w-48 h-48 -mt-6 -mr-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
        </div>
    </div>

    @if (->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 flex items-start space-x-3">
            <svg class="w-5 h-5 text-rose-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <h4 class="text-xs font-bold text-rose-700">Terdapat kesalahan pada isian formulir:</h4>
                <ul class="text-[11px] text-rose-600 mt-1 space-y-1 list-disc list-inside">
                    @foreach (->all() as )
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('pendidikan.schools.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- 1. IDENTITAS & LEGALITAS -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="bg-slate-50 border-b border-slate-200 px-6 py-4 flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">1</div>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Identitas & Legalitas Satuan Pendidikan</h2>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">NPSN <span class="text-rose-500">*</span></label>
                        <input type="text" name="npsn" value="{{ old('npsn') }}" required placeholder="Contoh: 60100123" 
                               class="w-full text-sm rounded-lg border-slate-300 focus:border-[#00264A] focus:ring-[#00264A] py-2 px-3 shadow-sm">
                        <p class="text-[10px] text-slate-400 mt-1">Nomor Pokok Sekolah Nasional (Unik)</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Satuan Pendidikan <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_sekolah" value="{{ old('nama_sekolah', old('nama')) }}" required placeholder="Contoh: SMA Negeri 1 Ambon" 
                               class="w-full text-sm rounded-lg border-slate-300 focus:border-[#00264A] focus:ring-[#00264A] py-2 px-3 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenjang Pendidikan <span class="text-rose-500">*</span></label>
                        <select name="jenjang" required class="w-full text-sm rounded-lg border-slate-300 focus:border-[#00264A] focus:ring-[#00264A] py-2 px-3 shadow-sm font-medium">
                            <option value="">-- Pilih Jenjang --</option>
                            @foreach(['PAUD', 'TK', 'SD', 'SMP', 'SMA', 'SMK', 'SLB'] as $j)
                                <option value="{{ $j }}" {{ old('jenjang') == $j ? 'selected' : '' }}>{{ $j }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Sekolah <span class="text-rose-500">*</span></label>
                        <select name="status_sekolah" required class="w-full text-sm rounded-lg border-slate-300 focus:border-[#00264A] focus:ring-[#00264A] py-2 px-3 shadow-sm font-medium">
                            <option value="Negeri" {{ old('status_sekolah') == 'Negeri' ? 'selected' : '' }}>Negeri</option>
                            <option value="Swasta" {{ old('status_sekolah') == 'Swasta' ? 'selected' : '' }}>Swasta</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Akreditasi</label>
                        <select name="akreditasi" class="w-full text-sm rounded-lg border-slate-300 focus:border-[#00264A] focus:ring-[#00264A] py-2 px-3 shadow-sm font-medium">
                            <option value="A" {{ old('akreditasi') == 'A' ? 'selected' : '' }}>Akreditasi A</option>
                            <option value="B" {{ old('akreditasi') == 'B' ? 'selected' : '' }}>Akreditasi B</option>
                            <option value="C" {{ old('akreditasi') == 'C' ? 'selected' : '' }}>Akreditasi C</option>
                            <option value="Belum Terakreditasi" {{ old('akreditasi') == 'Belum Terakreditasi' ? 'selected' : '' }}>Belum Terakreditasi</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tahun Data</label>
                        <input type="text" name="tahun_data" value="{{ old('tahun_data', '2026') }}" required 
                               class="w-full text-sm rounded-lg border-slate-300 focus:border-[#00264A] focus:ring-[#00264A] py-2 px-3 shadow-sm">
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. LOKASI & WILAYAH ADMINISTRASI -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="bg-slate-50 border-b border-slate-200 px-6 py-4 flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">2</div>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Lokasi & Wilayah Administrasi</h2>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Kabupaten / Kota <span class="text-rose-500">*</span></label>
                        <select name="kabupaten_kota" required class="w-full text-sm rounded-lg border-slate-300 focus:border-[#00264A] focus:ring-[#00264A] py-2 px-3 shadow-sm font-medium">
                            <option value="">-- Pilih Wilayah --</option>
                            @foreach($kabupatenOptions as $kab)
                                <option value="{{ $kab }}" {{ old('kabupaten_kota') == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Kecamatan</label>
                        <input type="text" name="kecamatan" value="{{ old('kecamatan') }}" placeholder="Contoh: Sirimau" 
                               class="w-full text-sm rounded-lg border-slate-300 focus:border-[#00264A] focus:ring-[#00264A] py-2 px-3 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Desa / Kelurahan</label>
                        <input type="text" name="desa_kelurahan" value="{{ old('desa_kelurahan') }}" placeholder="Contoh: Rijali" 
                               class="w-full text-sm rounded-lg border-slate-300 focus:border-[#00264A] focus:ring-[#00264A] py-2 px-3 shadow-sm">
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Lengkap</label>
                    <textarea name="alamat" rows="2" placeholder="Nama jalan, gedung, RT/RW..." 
                              class="w-full text-sm rounded-lg border-slate-300 focus:border-[#00264A] focus:ring-[#00264A] py-2 px-3 shadow-sm">{{ old('alamat') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Latitude (Garis Lintang)</label>
                        <input type="text" name="latitude" value="{{ old('latitude', '-3.654703') }}" placeholder="-3.654703" 
                               class="w-full text-sm rounded-lg border-slate-300 focus:border-[#00264A] focus:ring-[#00264A] py-2 px-3 shadow-sm font-mono text-slate-600">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Longitude (Garis Bujur)</label>
                        <input type="text" name="longitude" value="{{ old('longitude', '128.190643') }}" placeholder="128.190643" 
                               class="w-full text-sm rounded-lg border-slate-300 focus:border-[#00264A] focus:ring-[#00264A] py-2 px-3 shadow-sm font-mono text-slate-600">
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. KONTAK & STATUS VALIDASI -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="bg-slate-50 border-b border-slate-200 px-6 py-4 flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">3</div>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Kontak & Status Validasi</h2>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Kepala Sekolah</label>
                        <input type="text" name="kepala_sekolah" value="{{ old('kepala_sekolah') }}" placeholder="Masukkan Nama Lengkap" 
                               class="w-full text-sm rounded-lg border-slate-300 focus:border-[#00264A] focus:ring-[#00264A] py-2 px-3 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Validasi Data</label>
                        <select name="status_validasi" class="w-full text-sm rounded-lg border-slate-300 focus:border-[#00264A] focus:ring-[#00264A] py-2 px-3 shadow-sm font-medium">
                            <option value="DRAFT" {{ old('status_validasi') == 'DRAFT' ? 'selected' : '' }}>DRAFT (Belum Lengkap)</option>
                            <option value="SUBMITTED" {{ old('status_validasi') == 'SUBMITTED' ? 'selected' : '' }}>SUBMITTED (Diajukan)</option>
                            <option value="VERIFIED" {{ old('status_validasi') == 'VERIFIED' ? 'selected' : '' }}>VERIFIED (Terverifikasi Cabdin)</option>
                            <option value="VALIDATED" {{ old('status_validasi', 'VALIDATED') == 'VALIDATED' ? 'selected' : '' }}>VALIDATED (Disetujui Dinas)</option>
                            <option value="REJECTED" {{ old('status_validasi') == 'REJECTED' ? 'selected' : '' }}>REJECTED (Ditolak)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('pendidikan.schools.index') }}" class="px-5 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 text-xs font-bold text-white bg-[#00264A] hover:bg-[#001c36] rounded-xl shadow-lg transition-colors flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Simpan ke Pangkalan Data Dinas PUPR Maluku</span>
            </button>
        </div>

    </form>
</div>
@endsection
