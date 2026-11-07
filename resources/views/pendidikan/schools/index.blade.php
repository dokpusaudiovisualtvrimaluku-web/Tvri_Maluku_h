@extends('layouts.app')

@section('title', 'Data Satuan Pendidikan - Pangkalan Data Dinas PUPR Maluku')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Banner -->
    <div class="bg-gradient-to-r from-[#00264A] via-[#003B70] to-[#005B9F] rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 w-96 h-96 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <span class="px-3 py-1 bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 rounded-full text-xs font-bold tracking-wider uppercase">
                        Pangkalan Data Pendidikan
                    </span>
                    <span class="text-xs text-blue-200">Tahap 3 — Terintegrasi</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight text-white">
                    Data Satuan Pendidikan
                </h1>
                <p class="text-sm text-blue-100 max-w-3xl mt-1 leading-relaxed">
                    Pangkalan data master seluruh satuan pendidikan jenjang PAUD, TK, SD, SMP, SMA, SMK, dan SLB di 11 Kabupaten/Kota se-Provinsi Maluku.
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('pendidikan.import.index') }}" class="inline-flex items-center space-x-1.5 px-3.5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-bold rounded-xl shadow-lg transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span>Import Excel</span>
                </a>
                <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="inline-flex items-center space-x-1.5 px-3.5 py-2.5 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-xl border border-white/20 backdrop-blur-md transition-all">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Export CSV</span>
                </a>
                <a href="{{ route('pendidikan.schools.create') }}" class="inline-flex items-center space-x-1.5 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-lg hover:shadow-emerald-600/30 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>+ Tambah Sekolah</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold">&times;</button>
        </div>
    @endif

    <!-- 5 Metric Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <!-- Total Sekolah -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Satuan Pendidikan</p>
                <p class="text-2xl font-black text-[#00264A] mt-1">{{ number_format($totalSekolah ?? $schools->total(), 0, ',', '.') }}</p>
                <p class="text-[10px] text-slate-500 mt-0.5">
                    <span class="text-blue-600 font-bold">{{ $totalNegeri ?? 0 }}</span> Negeri • <span class="text-amber-600 font-bold">{{ $totalSwasta ?? 0 }}</span> Swasta
                </p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-[#003B70] flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
        </div>

        <!-- Total Siswa -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Peserta Didik</p>
                <p class="text-2xl font-black text-cyan-600 mt-1">{{ number_format($totalSiswa ?? 0, 0, ',', '.') }}</p>
                <p class="text-[10px] text-slate-500 mt-0.5">Siswa Aktif Terdata</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>

        <!-- Total Guru -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Guru & Tendik</p>
                <p class="text-2xl font-black text-indigo-600 mt-1">{{ number_format($totalGuru ?? 0, 0, ',', '.') }}</p>
                <p class="text-[10px] text-slate-500 mt-0.5">PTK Terdaftar</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        <!-- Tervalidasi -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Status Validasi</p>
                <p class="text-2xl font-black text-emerald-600 mt-1">{{ number_format($totalValidated ?? 0, 0, ',', '.') }}</p>
                <p class="text-[10px] text-emerald-600 font-semibold mt-0.5">Tervalidasi Dinas</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Wilayah Cakupan -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center justify-between col-span-2 md:col-span-1">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Wilayah Cakupan</p>
                <p class="text-2xl font-black text-slate-800 mt-1">11</p>
                <p class="text-[10px] text-slate-500 mt-0.5">Kabupaten / Kota Maluku</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Quick Jenjang Filter Ribbon -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center gap-2 overflow-x-auto select-none">
        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap mr-2 flex items-center space-x-1.5">
            <svg class="w-4 h-4 text-[#003B70]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
            <span>Filter Jenjang:</span>
        </span>

        @php
            $activeJenjang = request('jenjang', 'all');
            $jenjangItems = ['all' => 'Semua', 'PAUD' => 'PAUD', 'TK' => 'TK', 'SD' => 'SD', 'SMP' => 'SMP', 'SMA' => 'SMA', 'SMK' => 'SMK', 'SLB' => 'SLB'];
        @endphp

        @foreach($jenjangItems as $jKey => $jTitle)
            @php
                $isActive = $activeJenjang === $jKey;
                $jCount = ($jKey === 'all') ? ($totalSekolah ?? 0) : ($jenjangCounts[$jKey] ?? 0);
            @endphp
            <a href="{{ route('pendidikan.schools.index', array_merge(request()->except(['jenjang', 'page']), ['jenjang' => $jKey])) }}" 
               class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ $isActive ? 'bg-[#003B70] text-white shadow' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                <span>{{ $jTitle }}</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $isActive ? 'bg-white/20 text-white' : 'bg-white text-slate-600 border border-slate-200' }}">
                    {{ $jCount }}
                </span>
            </a>
        @endforeach
    </div>

    <!-- Filter & Search Card -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
        <form method="GET" action="{{ route('pendidikan.schools.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
            @if(request()->filled('jenjang'))
                <input type="hidden" name="jenjang" value="{{ request('jenjang') }}">
            @endif

            <!-- Search -->
            <div class="md:col-span-4">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Pencarian</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NPSN, Nama Sekolah, Kepala Sekolah..." 
                           class="w-full text-xs pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#005B9F] focus:ring-1 focus:ring-[#005B9F] transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <!-- Kabupaten / Kota -->
            <div class="md:col-span-3">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Kabupaten / Kota</label>
                <select name="kabupaten_kota" class="w-full text-xs py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#005B9F] focus:ring-1 focus:ring-[#005B9F]">
                    <option value="all">Semua Wilayah (11 Kab/Kota)</option>
                    @foreach($kabupatenOptions as $kab)
                        <option value="{{ $kab }}" {{ request('kabupaten_kota') == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Sekolah -->
            <div class="md:col-span-2">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Status Sekolah</label>
                <select name="status_sekolah" class="w-full text-xs py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#005B9F] focus:ring-1 focus:ring-[#005B9F]">
                    <option value="all">Semua Status</option>
                    <option value="Negeri" {{ request('status_sekolah') == 'Negeri' ? 'selected' : '' }}>Negeri</option>
                    <option value="Swasta" {{ request('status_sekolah') == 'Swasta' ? 'selected' : '' }}>Swasta</option>
                </select>
            </div>

            <!-- Akreditasi -->
            <div class="md:col-span-1">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Akreditasi</label>
                <select name="akreditasi" class="w-full text-xs py-2 px-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#005B9F] focus:ring-1 focus:ring-[#005B9F]">
                    <option value="all">Semua</option>
                    <option value="A" {{ request('akreditasi') == 'A' ? 'selected' : '' }}>A</option>
                    <option value="B" {{ request('akreditasi') == 'B' ? 'selected' : '' }}>B</option>
                    <option value="C" {{ request('akreditasi') == 'C' ? 'selected' : '' }}>C</option>
                    <option value="Belum Akreditasi" {{ request('akreditasi') == 'Belum Akreditasi' ? 'selected' : '' }}>Belum</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="md:col-span-2 flex items-end space-x-2">
                <button type="submit" class="w-full py-2 px-3 bg-[#003B70] hover:bg-[#00264A] text-white text-xs font-bold rounded-xl shadow transition-all">
                    Filter Data
                </button>
                <a href="{{ route('pendidikan.schools.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all" title="Reset Filter">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Daftar Satuan Pendidikan Terdaftar</h3>
                <p class="text-xs text-slate-500">Menampilkan {{ $schools->firstItem() ?? 0 }} - {{ $schools->lastItem() ?? 0 }} dari {{ $schools->total() }} sekolah</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3.5">NPSN & Jenjang</th>
                        <th class="px-4 py-3.5">Nama Satuan Pendidikan & Kepala Sekolah</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5">Wilayah Kabupaten/Kota</th>
                        <th class="px-4 py-3.5 text-center">Guru</th>
                        <th class="px-4 py-3.5 text-center">Siswa</th>
                        <th class="px-4 py-3.5 text-center">Akreditasi</th>
                        <th class="px-4 py-3.5 text-center">Status Validasi</th>
                        <th class="px-4 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($schools as $s)
                        @php
                            $jenjangBadge = match($s->jenjang) {
                                'SMA' => 'bg-blue-100 text-blue-800 border-blue-300',
                                'SMK' => 'bg-cyan-100 text-cyan-800 border-cyan-300',
                                'SLB' => 'bg-purple-100 text-purple-800 border-purple-300',
                                'SMP' => 'bg-amber-100 text-amber-800 border-amber-300',
                                'SD' => 'bg-rose-100 text-rose-800 border-rose-300',
                                default => 'bg-emerald-100 text-emerald-800 border-emerald-300'
                            };

                            $akrBadge = match($s->akreditasi) {
                                'A' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                'B' => 'bg-blue-100 text-blue-800 border-blue-300',
                                'C' => 'bg-amber-100 text-amber-800 border-amber-300',
                                default => 'bg-slate-100 text-slate-600 border-slate-200'
                            };

                            $validBadge = match($s->status_validasi) {
                                'VALIDATED' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                'VERIFIED' => 'bg-blue-100 text-blue-800 border-blue-300',
                                'SUBMITTED' => 'bg-amber-100 text-amber-800 border-amber-300',
                                'REJECTED' => 'bg-rose-100 text-rose-800 border-rose-300',
                                default => 'bg-slate-100 text-slate-600 border-slate-300'
                            };
                        @endphp
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <!-- NPSN & Jenjang -->
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <div class="font-mono font-bold text-[#003B70] text-xs">{{ $s->npsn }}</div>
                                <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-extrabold border {{ $jenjangBadge }}">
                                    {{ $s->jenjang }}
                                </span>
                            </td>

                            <!-- Nama Sekolah & Kepsek -->
                            <td class="px-4 py-3.5 max-w-xs">
                                <a href="{{ route('pendidikan.schools.show', $s->id) }}" class="font-bold text-slate-800 hover:text-[#003B70] line-clamp-1">
                                    {{ $s->nama_sekolah ?: $s->nama }}
                                </a>
                                <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5 font-medium flex items-center space-x-1">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <span>Kepsek: {{ $s->kepala_sekolah ?: '-' }}</span>
                                </p>
                                @if($s->latitude && $s->longitude)
                                    <div class="mt-0.5 flex items-center space-x-1 text-[10px] text-cyan-600">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        <span>Koordinat GIS Terpetakan</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg border {{ $s->status_sekolah === 'Negeri' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-amber-50 text-amber-700 border-amber-200' }}">
                                    {{ $s->status_sekolah ?: 'Negeri' }}
                                </span>
                            </td>

                            <!-- Wilayah -->
                            <td class="px-4 py-3.5 text-slate-700">
                                <div class="font-bold text-slate-800">{{ $s->kabupaten_kota ?: $s->cabang }}</div>
                                <div class="text-[11px] text-slate-500">{{ $s->kecamatan ?: $s->desa_kelurahan ?: 'Provinsi Maluku' }}</div>
                            </td>

                            <!-- Guru -->
                            <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                <span class="font-bold text-slate-800 text-xs">{{ $s->jumlah_guru ?: ($s->teachers_count ?? 0) }}</span>
                                <span class="text-[10px] text-slate-400 block">Guru</span>
                            </td>

                            <!-- Siswa -->
                            <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                <span class="font-bold text-slate-800 text-xs">{{ $s->jumlah_peserta_didik ?: $s->jumlah_siswa ?: ($s->students_count ?? 0) }}</span>
                                <span class="text-[10px] text-slate-400 block">Siswa</span>
                            </td>

                            <!-- Akreditasi -->
                            <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                <span class="px-2.5 py-0.5 text-[10px] font-black rounded border {{ $akrBadge }}">
                                    {{ $s->akreditasi ?: 'Belum' }}
                                </span>
                            </td>

                            <!-- Validasi -->
                            <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg border {{ $validBadge }}">
                                    {{ $s->status_validasi ?: 'VALIDATED' }}
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center space-x-1">
                                    <a href="{{ route('pendidikan.schools.show', $s->id) }}" class="p-1.5 bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-[#003B70] rounded-lg transition-colors" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>

                                    <a href="{{ route('pendidikan.schools.edit', $s->id) }}" class="p-1.5 bg-slate-100 hover:bg-amber-100 text-slate-600 hover:text-amber-800 rounded-lg transition-colors" title="Edit Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>

                                    <form action="{{ route('pendidikan.schools.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data sekolah ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-slate-100 hover:bg-rose-100 text-slate-600 hover:text-rose-700 rounded-lg transition-colors" title="Hapus Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-12 text-center text-slate-400">
                                <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <p class="text-sm font-bold text-slate-600">Tidak ada data satuan pendidikan yang sesuai filter</p>
                                <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau ubah filter jenjang/wilayah.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($schools->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $schools->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
