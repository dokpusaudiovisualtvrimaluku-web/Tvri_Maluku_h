@extends('layouts.app')

@section('title', 'Dashboard Administrator - Pangkalan Data Dinas PUPR Maluku')

@section('content')
<div class="space-y-6">

    <!-- Welcome Hero Banner -->
    <div class="bg-gradient-to-r from-[#003B70] via-[#005B9F] to-[#00264A] rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="inline-flex items-center space-x-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-[11px] font-bold text-blue-200 uppercase tracking-widest border border-white/10 mb-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Pusat Kendali Sistem &bull; Tahap 2</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Selamat Datang, {{ auth()->user()->name }}
                </h1>
                <p class="text-xs sm:text-sm text-blue-100 font-medium mt-1 max-w-2xl">
                    PANGKALAN DATA DINAS PUPR MALUKU &bull; TVRI Maluku
                </p>
            </div>
            
            <div class="flex items-center space-x-3">
                <div class="bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-white/15 text-right">
                    <p class="text-[10px] text-blue-200 font-bold uppercase tracking-wider">Tanggal Sistem</p>
                    <p class="text-xs font-bold text-white">{{ date('d F Y') }}</p>
                </div>
                <button onclick="window.print()" class="hidden sm:inline-flex items-center space-x-2 px-3.5 py-2.5 bg-white/15 hover:bg-white/25 rounded-2xl border border-white/20 text-xs font-bold text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>Cetak</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Global Dynamic Filter Bar -->
    <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('admin.dashboard') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Tahun Anggaran</label>
                <select name="tahun" class="w-full text-xs font-semibold rounded-xl border-slate-200 focus:border-[#005B9F] focus:ring-[#005B9F] bg-slate-50/50 py-2">
                    <option value="all" {{ ($filter['tahun'] ?? '') == 'all' ? 'selected' : '' }}>Semua Tahun</option>
                    <option value="2026" {{ ($filter['tahun'] ?? '') == '2026' ? 'selected' : '' }}>2026 (Tahun Berjalan)</option>
                    <option value="2025" {{ ($filter['tahun'] ?? '') == '2025' ? 'selected' : '' }}>2025</option>
                    <option value="2024" {{ ($filter['tahun'] ?? '') == '2024' ? 'selected' : '' }}>2024</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Bidang Kerja</label>
                <select name="bidang" class="w-full text-xs font-semibold rounded-xl border-slate-200 focus:border-[#005B9F] focus:ring-[#005B9F] bg-slate-50/50 py-2">
                    <option value="all" {{ ($filter['bidang'] ?? '') == 'all' ? 'selected' : '' }}>Semua Bidang</option>
                    <option value="Sekretariat" {{ ($filter['bidang'] ?? '') == 'Sekretariat' ? 'selected' : '' }}>Sekretariat</option>
                    <option value="SMA" {{ ($filter['bidang'] ?? '') == 'SMA' ? 'selected' : '' }}>Bidang SMA</option>
                    <option value="SMK" {{ ($filter['bidang'] ?? '') == 'SMK' ? 'selected' : '' }}>Bidang SMK</option>
                    <option value="SLB" {{ ($filter['bidang'] ?? '') == 'SLB' ? 'selected' : '' }}>Bidang Khusus (SLB)</option>
                    <option value="Kebudayaan" {{ ($filter['bidang'] ?? '') == 'Kebudayaan' ? 'selected' : '' }}>Bidang Kebudayaan</option>
                    <option value="GTK" {{ ($filter['bidang'] ?? '') == 'GTK' ? 'selected' : '' }}>Bidang Ketenagaan (GTK)</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Kabupaten / Kota (11 Wilayah)</label>
                <select name="kabupaten" class="w-full text-xs font-semibold rounded-xl border-slate-200 focus:border-[#005B9F] focus:ring-[#005B9F] bg-slate-50/50 py-2">
                    <option value="all" {{ ($filter['kabupaten'] ?? '') == 'all' ? 'selected' : '' }}>Semua Kabupaten/Kota</option>
                    @foreach($kabupaten_options as $kab)
                        <option value="{{ $kab }}" {{ ($filter['kabupaten'] ?? '') == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Kategori Dokumen</label>
                <select name="kategori" class="w-full text-xs font-semibold rounded-xl border-slate-200 focus:border-[#005B9F] focus:ring-[#005B9F] bg-slate-50/50 py-2">
                    <option value="all" {{ ($filter['kategori'] ?? '') == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                    <option value="SOP" {{ ($filter['kategori'] ?? '') == 'SOP' ? 'selected' : '' }}>SOP & Tata Kelola</option>
                    <option value="SK" {{ ($filter['kategori'] ?? '') == 'SK' ? 'selected' : '' }}>Surat Keputusan (SK)</option>
                    <option value="Laporan" {{ ($filter['kategori'] ?? '') == 'Laporan' ? 'selected' : '' }}>Laporan Kinerja</option>
                    <option value="Kurikulum" {{ ($filter['kategori'] ?? '') == 'Kurikulum' ? 'selected' : '' }}>Kurikulum & Vokasi</option>
                    <option value="Anggaran" {{ ($filter['kategori'] ?? '') == 'Anggaran' ? 'selected' : '' }}>Pagu & DPA</option>
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="w-full bg-[#003B70] hover:bg-[#00264A] text-white font-bold text-xs py-2 px-3 rounded-xl transition flex items-center justify-center space-x-1 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <span>Terapkan</span>
                </button>
                <a href="{{ route('admin.dashboard') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs py-2 px-3 rounded-xl transition flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- 8 Main Real Metric Cards (Database-driven) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        
        <!-- 1. Total Data -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Data</p>
                    <h3 class="text-2xl font-black text-[#00264A] mt-1">{{ number_format($stats['total_data']) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#003B70] flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1.5 3 3.5 3h9c2 0 3.5-1 3.5-3V7c0-2-1.5-3-3.5-3h-9C5.5 4 4 5 4 7z"></path></svg>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 font-medium">Agregat seluruh pangkalan data</p>
        </div>

        <!-- 2. Total Dokumen -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Dokumen</p>
                    <h3 class="text-2xl font-black text-[#00264A] mt-1">{{ number_format($stats['total_dokumen']) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 font-medium">Arsip & berkas digital dinas</p>
        </div>

        <!-- 3. Total Sekolah -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Sekolah</p>
                    <h3 class="text-2xl font-black text-[#00264A] mt-1">{{ number_format($stats['total_sekolah']) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-sky-50 text-[#005B9F] flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 font-medium">SMA, SMK, SLB di Maluku</p>
        </div>

        <!-- 4. Total Guru -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Guru (GTK)</p>
                    <h3 class="text-2xl font-black text-[#00264A] mt-1">{{ number_format($stats['total_guru']) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 font-medium">Pendidik & tenaga kependidikan</p>
        </div>

        <!-- 5. Total Peserta Didik -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Peserta Didik</p>
                    <h3 class="text-2xl font-black text-emerald-700 mt-1">{{ number_format($stats['total_siswa']) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 font-medium">Siswa terdaftar aktif</p>
        </div>

        <!-- 6. Total Data Kebudayaan -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Data Kebudayaan</p>
                    <h3 class="text-2xl font-black text-amber-700 mt-1">{{ number_format($stats['total_kebudayaan']) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 font-medium">Cagar budaya, UPTD & seni</p>
        </div>

        <!-- 7. Total Program -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Program</p>
                    <h3 class="text-2xl font-black text-purple-700 mt-1">{{ number_format($stats['total_program']) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 font-medium">Rencana strategis dinas</p>
        </div>

        <!-- 8. Total Kegiatan -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Kegiatan</p>
                    <h3 class="text-2xl font-black text-rose-700 mt-1">{{ number_format($stats['total_kegiatan']) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-700 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 font-medium">Aktivitas & pelaksanaan dinas</p>
        </div>

    </div>

    <!-- Quick Actions for Admin & System Health Status -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Quick Actions Admin -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <h3 class="text-sm font-extrabold text-[#00264A] uppercase tracking-wider mb-4 flex items-center space-x-2">
                <svg class="w-4 h-4 text-[#005B9F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                <span>Aksi Cepat Administrator</span>
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ url('/simandat') }}" class="group p-4 rounded-2xl border border-blue-100 bg-[#EAF4FB]/50 hover:bg-[#003B70] hover:text-white transition-all flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-white group-hover:bg-white/20 text-[#003B70] group-hover:text-white flex items-center justify-center mb-3 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <h4 class="text-xs font-extrabold group-hover:text-white text-[#00264A]">Tambah Data</h4>
                        <p class="text-[11px] text-slate-500 group-hover:text-blue-100 mt-1">Input data sekolah, UPTD atau aset baru</p>
                    </div>
                    <span class="text-[10px] font-bold text-[#005B9F] group-hover:text-white mt-3 inline-flex items-center space-x-1">
                        <span>Buka Form</span> &rarr;
                    </span>
                </a>

                <a href="{{ url('/simandat') }}" class="group p-4 rounded-2xl border border-indigo-100 bg-indigo-50/40 hover:bg-[#003B70] hover:text-white transition-all flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-white group-hover:bg-white/20 text-indigo-600 group-hover:text-white flex items-center justify-center mb-3 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        </div>
                        <h4 class="text-xs font-extrabold group-hover:text-white text-[#00264A]">Upload Dokumen</h4>
                        <p class="text-[11px] text-slate-500 group-hover:text-blue-100 mt-1">Unggah berkas arsip, SOP, & SK dinas</p>
                    </div>
                    <span class="text-[10px] font-bold text-indigo-600 group-hover:text-white mt-3 inline-flex items-center space-x-1">
                        <span>Unggah File</span> &rarr;
                    </span>
                </a>

                <a href="{{ url('/simandat') }}" class="group p-4 rounded-2xl border border-emerald-100 bg-emerald-50/40 hover:bg-[#003B70] hover:text-white transition-all flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-white group-hover:bg-white/20 text-emerald-600 group-hover:text-white flex items-center justify-center mb-3 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h4 class="text-xs font-extrabold group-hover:text-white text-[#00264A]">Import Excel</h4>
                        <p class="text-[11px] text-slate-500 group-hover:text-blue-100 mt-1">Sinkronisasi data rekapitulasi massal</p>
                    </div>
                    <span class="text-[10px] font-bold text-emerald-600 group-hover:text-white mt-3 inline-flex items-center space-x-1">
                        <span>Pusat Data</span> &rarr;
                    </span>
                </a>
            </div>
        </div>

        <!-- System Health Status -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-extrabold text-[#00264A] uppercase tracking-wider mb-4 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <span>Status Server & Sistem</span>
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50">
                        <span class="text-xs font-bold text-slate-600">Database Engine</span>
                        <span class="inline-flex items-center space-x-1 text-xs font-extrabold text-emerald-600 bg-emerald-100/60 px-2.5 py-0.5 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <span>{{ $system_status['database'] }}</span>
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50">
                        <span class="text-xs font-bold text-slate-600">Storage Driver</span>
                        <span class="inline-flex items-center space-x-1 text-xs font-extrabold text-emerald-600 bg-emerald-100/60 px-2.5 py-0.5 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <span>{{ $system_status['storage'] }}</span>
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50">
                        <span class="text-xs font-bold text-slate-600">Status Operasional</span>
                        <span class="inline-flex items-center space-x-1 text-xs font-extrabold text-[#005B9F] bg-sky-100/60 px-2.5 py-0.5 rounded-full">
                            <span>{{ $system_status['system'] }}</span>
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50">
                        <span class="text-xs font-bold text-slate-600">Last Backup</span>
                        <span class="text-xs font-semibold text-slate-400">{{ $system_status['last_backup'] }}</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-400 font-medium">
                Host: <code class="font-mono text-[#003B70]">127.0.0.1:8000</code> &bull; PHP {{ PHP_VERSION }}
            </div>
        </div>

    </div>

    <!-- 5 Chart.js Visualizations (Interactive & Responsive) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Chart 1: Perkembangan Data Per Tahun -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-extrabold text-[#00264A]">1. Perkembangan Data Per Tahun</h3>
                    <p class="text-[11px] text-slate-400 font-medium">Tren pertumbuhan data pendidikan, kebudayaan & dokumen (2022-2026)</p>
                </div>
                <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-[#003B70] text-[10px] font-extrabold uppercase">Multi-Tahun</span>
            </div>
            <div class="h-64">
                <canvas id="chartPerkembanganTahun"></canvas>
            </div>
        </div>

        <!-- Chart 2: Data Per 11 Kabupaten/Kota -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-extrabold text-[#00264A]">2. Sebaran 11 Kabupaten/Kota</h3>
                    <p class="text-[11px] text-slate-400 font-medium">Distribusi satuan pendidikan & cagar budaya di Maluku</p>
                </div>
                <span class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-[10px] font-extrabold uppercase">11 Wilayah</span>
            </div>
            <div class="h-64">
                <canvas id="chartKabupaten"></canvas>
            </div>
        </div>

        <!-- Chart 3: Dokumen Berdasarkan Kategori -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-extrabold text-[#00264A]">3. Dokumen Berdasarkan Kategori</h3>
                    <p class="text-[11px] text-slate-400 font-medium">Klasifikasi arsip SOP, SK, Laporan & Regulasi</p>
                </div>
                <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[10px] font-extrabold uppercase">Kategori</span>
            </div>
            <div class="h-64 flex items-center justify-center">
                <canvas id="chartKategoriDokumen"></canvas>
            </div>
        </div>

        <!-- Chart 4: Kelengkapan Data Sektoral -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-extrabold text-[#00264A]">4. Kelengkapan Data Sektoral (%)</h3>
                    <p class="text-[11px] text-slate-400 font-medium">Persentase kelengkapan data per bidang dinas</p>
                </div>
                <span class="px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 text-[10px] font-extrabold uppercase">Persentase</span>
            </div>
            <div class="h-64">
                <canvas id="chartKelengkapanBidang"></canvas>
            </div>
        </div>

        <!-- Chart 5: Program dan Kegiatan (Full width on lg) -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-extrabold text-[#00264A]">5. Distribusi Program & Kegiatan Sektoral</h3>
                    <p class="text-[11px] text-slate-400 font-medium">Perbandingan alokasi program dan kegiatan per bidang kerja dinas</p>
                </div>
                <span class="px-2.5 py-1 rounded-lg bg-purple-50 text-purple-700 text-[10px] font-extrabold uppercase">Program vs Kegiatan</span>
            </div>
            <div class="h-64">
                <canvas id="chartProgramKegiatan"></canvas>
            </div>
        </div>

    </div>

        <!-- 3 Detailed Structured Tables: Aktivitas Terbaru, Data Terbaru, Dokumen Terbaru -->
    <div class="grid grid-cols-1 gap-6">
        
        <!-- Table 1: Aktivitas Terbaru -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-extrabold text-[#00264A] uppercase tracking-wider flex items-center space-x-2">
                    <svg class="w-4 h-4 text-[#005B9F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>5. Aktivitas Terbaru</span>
                </h3>
                <a href="{{ route('admin.activity-logs.index') }}" class="text-xs font-bold text-[#005B9F] hover:underline">Lihat Semua Log &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-[11px] font-extrabold uppercase text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4 rounded-l-xl">User</th>
                            <th class="py-3 px-4">Aktivitas</th>
                            <th class="py-3 px-4">Waktu</th>
                            <th class="py-3 px-4 rounded-r-xl">Jenis Data</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recent_activities as $act)
                            <tr class="hover:bg-[#EAF4FB]/40 transition">
                                <td class="py-3 px-4 font-bold text-[#00264A] flex items-center space-x-2">
                                    <div class="w-7 h-7 rounded-full bg-blue-100 text-[#003B70] flex items-center justify-center font-bold text-[10px]">
                                        {{ strtoupper(substr($act['user'] ?? 'U', 0, 1)) }}
                                    </div>
                                    <span>{{ $act['user'] }}</span>
                                </td>
                                <td class="py-3 px-4 font-medium text-slate-700">{{ $act['aktivitas'] }}</td>
                                <td class="py-3 px-4 text-slate-500 font-medium whitespace-nowrap">{{ $act['waktu'] }}</td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-[#003B70]">
                                        {{ $act['jenis_data'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-4 text-center text-slate-400">Belum ada aktivitas tercatat</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Two Column Tables: Data Terbaru & Dokumen Terbaru -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Table 2: Data Terbaru -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-extrabold text-[#00264A] uppercase tracking-wider flex items-center space-x-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1.5 3 3.5 3h9c2 0 3.5-1 3.5-3V7c0-2-1.5-3-3.5-3h-9C5.5 4 4 5 4 7z"></path></svg>
                        <span>6. Data Terbaru</span>
                    </h3>
                    <a href="{{ url('/simandat') }}" class="text-xs font-bold text-emerald-600 hover:underline">Kelola Data &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-[11px] font-extrabold uppercase text-slate-500 border-b border-slate-200">
                            <tr>
                                <th class="py-2.5 px-3 rounded-l-xl">Nama</th>
                                <th class="py-2.5 px-3">Kategori</th>
                                <th class="py-2.5 px-3">Tahun</th>
                                <th class="py-2.5 px-3">Status</th>
                                <th class="py-2.5 px-3 rounded-r-xl">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recent_data as $item)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-2.5 px-3 font-bold text-[#00264A]">{{ $item['nama'] }}</td>
                                    <td class="py-2.5 px-3 text-slate-600">{{ $item['kategori'] }}</td>
                                    <td class="py-2.5 px-3 font-semibold">{{ $item['tahun'] }}</td>
                                    <td class="py-2.5 px-3">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                            {{ $item['status'] }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 text-slate-400 whitespace-nowrap">{{ $item['tanggal'] }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-4 text-center text-slate-400">Belum ada data terbaru</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Table 3: Dokumen Terbaru -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-extrabold text-[#00264A] uppercase tracking-wider flex items-center space-x-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>7. Dokumen Terbaru</span>
                    </h3>
                    <a href="{{ url('/simandat') }}" class="text-xs font-bold text-indigo-600 hover:underline">Pusat Arsip &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-[11px] font-extrabold uppercase text-slate-500 border-b border-slate-200">
                            <tr>
                                <th class="py-2.5 px-3 rounded-l-xl">Nama Dokumen</th>
                                <th class="py-2.5 px-3">Kategori</th>
                                <th class="py-2.5 px-3">Tahun</th>
                                <th class="py-2.5 px-3">Uploader</th>
                                <th class="py-2.5 px-3 rounded-r-xl">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recent_documents as $doc)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-2.5 px-3 font-bold text-[#00264A]">{{ $doc['nama'] }}</td>
                                    <td class="py-2.5 px-3 text-slate-600">{{ $doc['kategori'] }}</td>
                                    <td class="py-2.5 px-3 font-semibold">{{ $doc['tahun'] }}</td>
                                    <td class="py-2.5 px-3 text-slate-700 font-medium">{{ $doc['uploader'] }}</td>
                                    <td class="py-2.5 px-3 text-slate-400 whitespace-nowrap">{{ $doc['tanggal'] }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-4 text-center text-slate-400">Belum ada dokumen</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Table 4: Master Katalog Data Operasional TVRI Maluku -->
            @if(isset($katalog_datasets) && count($katalog_datasets) > 0)
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-extrabold text-[#00264A] uppercase tracking-wider flex items-center space-x-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <span>8. Master Katalog Data Operasional TVRI Maluku</span>
                    </h3>
                    <a href="{{ url('/') }}" target="_blank" class="text-xs font-bold text-blue-600 hover:underline">Lihat di Portal Publik &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-[11px] font-extrabold uppercase text-slate-500 border-b border-slate-200">
                            <tr>
                                <th class="py-2.5 px-3 rounded-l-xl">Nama Dataset</th>
                                <th class="py-2.5 px-3">Urusan / Bidang</th>
                                <th class="py-2.5 px-3 text-center">Baris Data</th>
                                <th class="py-2.5 px-3 text-center">Dilihat</th>
                                <th class="py-2.5 px-3 text-center">Diunduh</th>
                                <th class="py-2.5 px-3">Tanggal</th>
                                <th class="py-2.5 px-3 rounded-r-xl text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @foreach($katalog_datasets as $ds)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-2.5 px-3">
                                        <div class="font-bold text-[#00264A]">{{ $ds->title }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $ds->slug }}</div>
                                    </td>
                                    <td class="py-2.5 px-3">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-700">
                                            {{ $ds->urusan?->name ?? 'TVRI Maluku' }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 text-center font-mono font-bold text-slate-700">
                                        {{ is_array($ds->rows) ? count($ds->rows) : 0 }} baris
                                    </td>
                                    <td class="py-2.5 px-3 text-center font-mono font-bold text-emerald-600">
                                        {{ number_format($ds->views, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2.5 px-3 text-center font-mono font-bold text-blue-600">
                                        {{ number_format($ds->downloads, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2.5 px-3 text-slate-400 whitespace-nowrap">{{ $ds->date }}</td>
                                    <td class="py-2.5 px-3 text-center">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                            Terverifikasi Master
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>

    </div>

</div>

<!-- Include Chart.js & Initialize Visualizations -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const primaryBlue = '#003B70';
    const secondaryBlue = '#005B9F';
    const accentRed = '#E31837';
    const emeraldColor = '#10B981';
    const amberColor = '#F59E0B';
    const purpleColor = '#8B5CF6';

    // 1. Chart: Perkembangan Data Per Tahun
    const ctxTahun = document.getElementById('chartPerkembanganTahun');
    if (ctxTahun) {
        new Chart(ctxTahun, {
            type: 'line',
            data: {
                labels: {!! json_encode($charts['perkembangan_tahun']['labels']) !!},
                datasets: [
                    {
                        label: 'Pendidikan',
                        data: {!! json_encode($charts['perkembangan_tahun']['series']['pendidikan']) !!},
                        borderColor: primaryBlue,
                        backgroundColor: 'rgba(0, 59, 112, 0.1)',
                        tension: 0.3,
                        fill: true,
                        pointRadius: 4,
                    },
                    {
                        label: 'Kebudayaan',
                        data: {!! json_encode($charts['perkembangan_tahun']['series']['kebudayaan']) !!},
                        borderColor: amberColor,
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        tension: 0.3,
                        fill: true,
                        pointRadius: 4,
                    },
                    {
                        label: 'Dokumen',
                        data: {!! json_encode($charts['perkembangan_tahun']['series']['dokumen']) !!},
                        borderColor: secondaryBlue,
                        backgroundColor: 'rgba(0, 91, 159, 0.1)',
                        tension: 0.3,
                        fill: true,
                        pointRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { font: { family: 'Inter', size: 11, weight: 'bold' } } }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#F1F5F9' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // 2. Chart: Data Per 11 Kabupaten/Kota
    const ctxKab = document.getElementById('chartKabupaten');
    if (ctxKab) {
        new Chart(ctxKab, {
            type: 'bar',
            data: {
                labels: {!! json_encode($charts['kabupaten']['labels']) !!},
                datasets: [{
                    label: 'Jumlah Satuan/Aset Data',
                    data: {!! json_encode($charts['kabupaten']['data']) !!},
                    backgroundColor: '#005B9F',
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#F1F5F9' } },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 9 },
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });
    }

    // 3. Chart: Dokumen Berdasarkan Kategori
    const ctxKat = document.getElementById('chartKategoriDokumen');
    if (ctxKat) {
        new Chart(ctxKat, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($charts['kategori_dokumen']['labels']) !!},
                datasets: [{
                    data: {!! json_encode($charts['kategori_dokumen']['data']) !!},
                    backgroundColor: [primaryBlue, secondaryBlue, emeraldColor, amberColor, purpleColor, accentRed],
                    borderWidth: 2,
                    borderColor: '#FFFFFF',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right', labels: { font: { size: 10, weight: '600' } } }
                }
            }
        });
    }

    // 4. Chart: Kelengkapan Data Sektoral
    const ctxBid = document.getElementById('chartKelengkapanBidang');
    if (ctxBid) {
        new Chart(ctxBid, {
            type: 'radar',
            data: {
                labels: {!! json_encode($charts['kelengkapan_bidang']['labels']) !!},
                datasets: [{
                    label: 'Kelengkapan (%)',
                    data: {!! json_encode($charts['kelengkapan_bidang']['data']) !!},
                    backgroundColor: 'rgba(0, 91, 159, 0.2)',
                    borderColor: '#005B9F',
                    pointBackgroundColor: '#003B70',
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        suggestedMin: 50,
                        suggestedMax: 100,
                        ticks: { stepSize: 10 }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    // 5. Chart: Program dan Kegiatan
    const ctxProg = document.getElementById('chartProgramKegiatan');
    if (ctxProg) {
        new Chart(ctxProg, {
            type: 'bar',
            data: {
                labels: {!! json_encode($charts['program_kegiatan']['labels']) !!},
                datasets: [
                    {
                        label: 'Program Kerja',
                        data: {!! json_encode($charts['program_kegiatan']['program']) !!},
                        backgroundColor: '#003B70',
                        borderRadius: 6,
                    },
                    {
                        label: 'Kegiatan Terlaksana',
                        data: {!! json_encode($charts['program_kegiatan']['kegiatan']) !!},
                        backgroundColor: '#10B981',
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { font: { size: 11, weight: 'bold' } } }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#F1F5F9' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
});
</script>
@endsection
