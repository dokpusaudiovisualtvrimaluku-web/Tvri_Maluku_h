@extends('layouts.app')

@section('title', 'Bidang Pembinaan SMK - Layanan & Dokumen Resmi TVRI Maluku')

@section('content')
<div class="space-y-6">

    <!-- Header & Welcome Banner -->
    <div class="bg-gradient-to-r from-[#00264A] via-[#003B70] to-[#005B9F] rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 w-96 h-96 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <span class="px-3 py-1 bg-cyan-400/20 text-cyan-200 border border-cyan-400/40 rounded-full text-xs font-bold tracking-wider uppercase">
                        Bidang Pembinaan SMK
                    </span>
                    <span class="text-xs text-blue-200">Vokasi & Kejuruan Terintegrasi</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight text-white">
                    Pangkalan Data Dinas PUPR Maluku & Layanan Dokumen SMK
                </h1>
                <p class="text-sm text-blue-100 max-w-3xl mt-1 leading-relaxed">
                    Sistem informasi dan administrasi dokumen resmi Sekolah Menengah Kejuruan (SMK) se-Provinsi Maluku: Rekomendasi Mutasi, BOSP, Ijazah & Legalisir, Supervisi Kurikulum KSP, Izin Pendirian, Izin Operasional, dan Penambahan Konsentrasi Keahlian Baru.
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-xl border border-white/20 backdrop-blur-md transition-all duration-200">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Export CSV</span>
                </a>
                <a href="{{ route('pendidikan.smk.layanan.create') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold rounded-xl shadow-lg hover:shadow-cyan-600/30 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>+ Buat Permohonan Dokumen SMK</span>
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

        <!-- 5 Main Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 lg:grid-cols-5 gap-4">
        <!-- Kelembagaan -->
        <div class="bg-gradient-to-br from-[#005B9F] to-[#003B70] rounded-2xl p-5 shadow-lg text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-20">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
            </div>
            <p class="text-xs font-bold text-blue-200 uppercase tracking-wider mb-1">Kelembagaan & Perizinan</p>
            <p class="text-3xl font-black">{{ $serviceCounts["Kelembagaan & Perizinan"] ?? 0 }}</p>
            <div class="flex items-center space-x-1 mt-2 text-xs">
                <span class="bg-blue-400/30 px-2 py-0.5 rounded text-white font-bold">Layanan SMK</span>
            </div>
        </div>

        <!-- Kesiswaan -->
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-5 shadow-lg text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-20">
                <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
            </div>
            <p class="text-xs font-bold text-emerald-100 uppercase tracking-wider mb-1">Kesiswaan & Mutasi</p>
            <p class="text-3xl font-black">{{ $serviceCounts["Kesiswaan & Mutasi"] ?? 0 }}</p>
            <div class="flex items-center space-x-1 mt-2 text-xs">
                <span class="bg-emerald-400/30 px-2 py-0.5 rounded text-white font-bold">Layanan SMK</span>
            </div>
        </div>

        <!-- Pendanaan -->
        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-5 shadow-lg text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-20">
                <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="text-xs font-bold text-amber-100 uppercase tracking-wider mb-1">Pemerataan & Pendanaan</p>
            <p class="text-3xl font-black">{{ $serviceCounts["Pemerataan & Pendanaan"] ?? 0 }}</p>
            <div class="flex items-center space-x-1 mt-2 text-xs">
                <span class="bg-amber-400/30 px-2 py-0.5 rounded text-white font-bold">Layanan SMK</span>
            </div>
        </div>

        <!-- Legalitas -->
        <div class="bg-gradient-to-br from-rose-500 to-red-600 rounded-2xl p-5 shadow-lg text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-20">
                <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <p class="text-xs font-bold text-rose-100 uppercase tracking-wider mb-1">Dokumen & Legalitas</p>
            <p class="text-3xl font-black">{{ $serviceCounts["Dokumen & Legalitas"] ?? 0 }}</p>
            <div class="flex items-center space-x-1 mt-2 text-xs">
                <span class="bg-rose-400/30 px-2 py-0.5 rounded text-white font-bold">Layanan SMK</span>
            </div>
        </div>

        <!-- Kurikulum -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-5 shadow-lg text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-20">
                <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <p class="text-xs font-bold text-purple-100 uppercase tracking-wider mb-1">Kurikulum & Vokasi</p>
            <p class="text-3xl font-black">{{ $serviceCounts["Kurikulum & Vokasi"] ?? 0 }}</p>
            <div class="flex items-center space-x-1 mt-2 text-xs">
                <span class="bg-purple-400/30 px-2 py-0.5 rounded text-white font-bold">Layanan SMK</span>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Trend Chart -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-4 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-[#005B9F]"></span>
                <span>Trend Layanan SMK</span>
            </h3>
            <div class="relative h-64 w-full">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Donut Chart -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-4 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                <span>Komposisi Layanan SMK</span>
            </h3>
            <div class="relative h-64 w-full flex justify-center items-center">
                <canvas id="tunjanganChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Layanan SMK Quick Filter Bar -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-600 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-[#003B70]"></span>
                <span>Layanan & Dokumen Resmi Bidang Pembinaan SMK</span>
            </h2>
            <span class="text-[11px] text-slate-400">Pilih tab untuk filter layanan</span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-2.5">
            @php
                $activeLayanan = request('jenis_layanan', 'all');
            @endphp
            
            <a href="{{ route('pendidikan.smk.layanan.index') }}" 
               class="p-2.5 rounded-xl border text-left transition-all duration-200 {{ $activeLayanan === 'all' ? 'bg-[#003B70] text-white border-[#003B70] shadow-md' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200' }}">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold truncate">Semua Layanan SMK</span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full {{ $activeLayanan === 'all' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }} font-bold">
                        {{ $totalLayanan }}
                    </span>
                </div>
                <p class="text-[10px] {{ $activeLayanan === 'all' ? 'text-blue-100' : 'text-slate-400' }} mt-0.5 truncate">Seluruh portofolio vokasi</p>
            </a>

            @foreach($allJenisLayanan as $svcName => $svcMeta)
                @php
                    $isSelected = $activeLayanan === $svcName;
                    $count = $serviceCounts[$svcName] ?? 0;
                @endphp
                <a href="{{ route('pendidikan.smk.layanan.index', array_merge(request()->except(['jenis_layanan', 'page']), ['jenis_layanan' => $svcName])) }}" 
                   class="p-2.5 rounded-xl border text-left transition-all duration-200 {{ $isSelected ? 'bg-[#003B70] text-white border-[#003B70] shadow-md' : 'bg-white hover:bg-cyan-50/50 text-slate-800 border-slate-200 hover:border-cyan-300' }}"
                   title="{{ $svcName }} - {{ $svcMeta['deskripsi'] }}">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold truncate" title="{{ $svcName }}">{{ $svcName }}</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-full {{ $isSelected ? 'bg-white/20 text-white' : 'bg-cyan-50 text-cyan-800 border border-cyan-200' }} font-bold">
                            {{ $count }}
                        </span>
                    </div>
                    <p class="text-[10px] {{ $isSelected ? 'text-blue-100' : 'text-slate-400' }} mt-0.5 truncate">{{ $svcMeta['seksi'] }}</p>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('pendidikan.smk.layanan.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
            @if(request()->filled('jenis_layanan'))
                <input type="hidden" name="jenis_layanan" value="{{ request('jenis_layanan') }}">
            @endif

            <!-- Search -->
            <div class="md:col-span-4">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Pencarian</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pemohon, NISN, no reg, nama SMK, jurusan..." 
                           class="w-full text-xs pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <!-- Status Layanan -->
            <div class="md:col-span-3">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Status Permohonan</label>
                <select name="status_layanan" class="w-full text-xs py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70]">
                    <option value="all">Semua Status</option>
                    @foreach($statusOptions as $st => $stBadge)
                        <option value="{{ $st }}" {{ request('status_layanan') === $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Kabupaten / Kota -->
            <div class="md:col-span-3">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Kabupaten / Kota</label>
                <select name="kabupaten_kota" class="w-full text-xs py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70]">
                    <option value="all">Semua Wilayah</option>
                    @foreach($kabupatenOptions as $kab)
                        <option value="{{ $kab }}" {{ request('kabupaten_kota') === $kab ? 'selected' : '' }}>{{ $kab }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Actions -->
            <div class="md:col-span-2 flex items-end space-x-2">
                <button type="submit" class="w-full py-2 px-3 bg-[#003B70] hover:bg-[#00264A] text-white text-xs font-bold rounded-xl shadow transition-all">
                    Filter Data
                </button>
                <a href="{{ route('pendidikan.smk.layanan.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all" title="Reset Filter">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Daftar Dokumen & Permohonan Layanan SMK</h3>
                <p class="text-xs text-slate-500">Menampilkan {{ $layananList->firstItem() ?? 0 }} - {{ $layananList->lastItem() ?? 0 }} dari total {{ $layananList->total() }} permohonan</p>
            </div>
            @if(request()->filled('jenis_layanan') && request('jenis_layanan') !== 'all')
                <div class="flex items-center space-x-2">
                    <span class="text-xs font-medium text-slate-500">Layanan:</span>
                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg {{ $allJenisLayanan[request('jenis_layanan')]['badge'] ?? 'bg-cyan-100 text-cyan-800' }}">
                        {{ request('jenis_layanan') }}
                    </span>
                </div>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3.5">No. Registrasi</th>
                        <th class="px-4 py-3.5">Jenis Layanan SMK</th>
                        <th class="px-4 py-3.5">Pemohon & SMK Asal</th>
                        <th class="px-4 py-3.5">Konsentrasi Keahlian</th>
                        <th class="px-4 py-3.5">Wilayah</th>
                        <th class="px-4 py-3.5">Tgl Pengajuan</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5 text-center">Berkas</th>
                        <th class="px-4 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($layananList as $item)
                        @php
                            $meta = $item->layanan_meta;
                            $statusBadge = $statusOptions[$item->status_layanan] ?? 'bg-gray-100 text-gray-800 border-gray-300';
                        @endphp
                        <tr class="hover:bg-cyan-50/30 transition-colors">
                            <!-- No. Registrasi -->
                            <td class="px-4 py-3 font-mono font-bold text-[#003B70] whitespace-nowrap">
                                <a href="{{ route('pendidikan.smk.layanan.show', $item->id) }}" class="hover:underline">
                                    {{ $item->nomor_registrasi }}
                                </a>
                                @if($item->nomor_surat_keluar)
                                    <div class="text-[10px] text-emerald-700 font-medium">SK/No: {{ $item->nomor_surat_keluar }}</div>
                                @endif
                            </td>

                            <!-- Jenis Layanan -->
                            <td class="px-4 py-3 max-w-xs">
                                <span class="px-2.5 py-1 rounded text-[11px] font-bold border inline-block {{ $meta['badge'] ?? 'bg-cyan-100 text-cyan-800 border-cyan-200' }}">
                                    {{ $item->jenis_layanan }}
                                </span>
                                <p class="text-[10px] text-slate-400 mt-1 line-clamp-1">{{ $meta['seksi'] ?? 'Bidang Pembinaan SMK' }}</p>
                            </td>

                            <!-- Nama Pemohon & Sekolah Asal -->
                            <td class="px-4 py-3">
                                <div class="font-bold text-slate-800">{{ $item->nama_pemohon }}</div>
                                <div class="text-[11px] text-slate-500 font-medium">
                                    {{ $item->nama_sekolah_asal }}
                                    @if($item->nomor_identitas)
                                        <span class="text-slate-400">({{ $item->nomor_identitas }})</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Konsentrasi Keahlian -->
                            <td class="px-4 py-3 max-w-xs">
                                @if($item->konsentrasi_keahlian)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200 line-clamp-1" title="{{ $item->konsentrasi_keahlian }}">
                                        {{ $item->konsentrasi_keahlian }}
                                    </span>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>

                            <!-- Wilayah -->
                            <td class="px-4 py-3 text-slate-700 whitespace-nowrap">
                                <div class="font-semibold">{{ $item->kabupaten_kota }}</div>
                            </td>

                            <!-- Tanggal Pengajuan -->
                            <td class="px-4 py-3 text-slate-600 whitespace-nowrap">
                                <div>{{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d/m/Y') : '-' }}</div>
                                @if($item->tanggal_selesai)
                                    <div class="text-[10px] text-emerald-600 font-medium">Selesai: {{ $item->tanggal_selesai->format('d/m/Y') }}</div>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg border {{ $statusBadge }}">
                                    {{ str_replace('_', ' ', $item->status_layanan) }}
                                </span>
                            </td>

                            <!-- Berkas -->
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center space-x-1">
                                    @if($item->file_permohonan)
                                        <a href="{{ route('pendidikan.smk.layanan.download', ['id' => $item->id, 'type' => 'permohonan']) }}" class="p-1 bg-slate-100 hover:bg-cyan-100 text-cyan-700 rounded" title="Unduh Berkas Syarat">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        </a>
                                    @endif
                                    @if($item->file_hasil_sk)
                                        <a href="{{ route('pendidikan.smk.layanan.download', ['id' => $item->id, 'type' => 'hasil_sk']) }}" class="p-1 bg-emerald-100 hover:bg-emerald-200 text-emerald-800 rounded font-bold" title="Unduh SK/Surat Terbit">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </a>
                                    @endif
                                </div>
                            </td>

                            <!-- Aksi -->
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center space-x-1">
                                    <a href="{{ route('pendidikan.smk.layanan.show', $item->id) }}" class="p-1.5 bg-slate-100 hover:bg-cyan-100 text-slate-600 hover:text-cyan-800 rounded-lg transition-colors" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>

                                    <a href="{{ route('pendidikan.smk.layanan.edit', $item->id) }}" class="p-1.5 bg-slate-100 hover:bg-amber-100 text-slate-600 hover:text-amber-800 rounded-lg transition-colors" title="Edit Permohonan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>

                                    <form action="{{ route('pendidikan.smk.layanan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data permohonan ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-slate-100 hover:bg-rose-100 text-slate-600 hover:text-rose-700 rounded-lg transition-colors" title="Hapus Permohonan">
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
                                <p class="text-sm font-bold text-slate-600">Belum ada data permohonan layanan SMK tercatat</p>
                                <p class="text-xs text-slate-400 mt-1">Gunakan tombol "+ Buat Permohonan Dokumen SMK" untuk mendaftarkan permohonan baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($layananList->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $layananList->links() }}
            </div>
        @endif
    </div>

</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Trend Chart
        const ctxTrend = document.getElementById('trendChart').getContext('2d');
        if (ctxTrend) {
            new Chart(ctxTrend, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [
                        {
                            label: 'Layanan SMK',
                            data: {!! json_encode($trend ?? []) !!},
                            borderColor: '#005B9F',
                            backgroundColor: 'rgba(0, 91, 159, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3,
                            pointRadius: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: { font: { size: 10 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });
        }

        // Donut Chart
        const ctxTunjangan = document.getElementById('tunjanganChart').getContext('2d');
        if (ctxTunjangan) {
            new Chart(ctxTunjangan, {
                type: 'doughnut',
                data: {
                    labels: ['Kesiswaan', 'Pendanaan', 'Legalitas', 'Kurikulum', 'Kelembagaan'],
                    datasets: [{
                        data: [
                            {{ $serviceCounts["Kesiswaan & Mutasi"] ?? 0 }}, 
                            {{ $serviceCounts["Pemerataan & Pendanaan"] ?? 0 }}, 
                            {{ $serviceCounts["Dokumen & Legalitas"] ?? 0 }}, 
                            {{ $serviceCounts["Kurikulum & Vokasi"] ?? 0 }}, 
                            {{ $serviceCounts["Kelembagaan & Perizinan"] ?? 0 }}
                        ],
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#a855f7', '#005B9F'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { boxWidth: 10, font: { size: 11 } } },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.raw;
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        }
    });
</script>
@endpush
@endsection


