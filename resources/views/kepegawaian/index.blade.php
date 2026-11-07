@extends('layouts.app')

@section('title', 'Sub Bagian Kepegawaian dan Umum - TVRI Maluku')

@section('content')
<div class="space-y-6">

    <!-- Header & Welcome Banner with Google Sites Link -->
    <div class="bg-gradient-to-r from-[#00264A] via-[#003B70] to-[#005B9F] rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 w-96 h-96 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <span class="px-3 py-1 bg-amber-500/20 text-amber-300 border border-amber-500/40 rounded-full text-xs font-bold tracking-wider uppercase">
                        Sub Bagian Kepegawaian & Umum
                    </span>
                    <span class="text-xs text-blue-200">Layanan Terpadu Satu Pintu</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight text-white">
                    Pangkalan Data Dinas PUPR Maluku & Layanan Kepegawaian
                </h1>
                <p class="text-sm text-blue-100 max-w-2xl mt-1">
                    Sistem informasi pengelolaan 10 layanan persuratan, kenaikan pangkat, KGB, cuti, izin belajar, pensiun, dan naskah dinas resmi TVRI Maluku.
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-xl border border-white/20 backdrop-blur-md transition-all duration-200">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Export CSV</span>
                </a>
                <a href="{{ route('kepegawaian.layanan.create') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-lg hover:shadow-emerald-600/30 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>+ Buat Permohonan Layanan</span>
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

    <!-- 4 Metric Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Layanan -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Permohonan</p>
                <p class="text-2xl font-black text-[#00264A] mt-1">{{ number_format($totalLayanan, 0, ',', '.') }}</p>
                <p class="text-[10px] text-slate-500 mt-0.5">10 Jenis Layanan Terdata</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#003B70] flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>

        <!-- Menunggu Verifikasi -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Menunggu Verifikasi</p>
                <p class="text-2xl font-black text-amber-600 mt-1">{{ number_format($totalPending, 0, ',', '.') }}</p>
                <p class="text-[10px] text-amber-600 font-semibold mt-0.5">Perlu Tindak Lanjut</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Sedang Diproses -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Sedang Diproses</p>
                <p class="text-2xl font-black text-blue-600 mt-1">{{ number_format($totalProses, 0, ',', '.') }}</p>
                <p class="text-[10px] text-blue-600 font-semibold mt-0.5">Kajian & Koordinasi BKD</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </div>
        </div>

        <!-- Selesai & SK Terbit -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Selesai & SK Terbit</p>
                <p class="text-2xl font-black text-emerald-600 mt-1">{{ number_format($totalSelesai, 0, ',', '.') }}</p>
                <p class="text-[10px] text-emerald-600 font-semibold mt-0.5">SK Resmi Diterbitkan</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
        <!-- Donut Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Status Permohonan</h3>
                <p class="text-[11px] text-slate-500 mb-4">Visualisasi jumlah permohonan berdasarkan status</p>
            </div>
            
            <div class="relative h-36 w-36 mx-auto my-2 flex items-center justify-center">
                <canvas id="statusChart"></canvas>
                <!-- inner text for donut -->
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none mt-1">
                    <span class="text-xl font-black text-slate-800">{{ number_format($totalLayanan, 0, ',', '.') }}</span>
                    <span class="text-[8px] text-slate-500 uppercase tracking-widest font-bold mt-0.5">Total</span>
                </div>
            </div>

            <!-- Custom Legend -->
            <div class="mt-4 space-y-2">
                @php
                    $chartStatuses = [
                        'SELESAI' => ['color' => '#10b981', 'label' => 'Selesai & SK Terbit', 'val' => $totalSelesai],
                        'PROSES_VERIFIKASI' => ['color' => '#8b5cf6', 'label' => 'Sedang Diproses', 'val' => $totalProses],
                        'PENDING' => ['color' => '#f59e0b', 'label' => 'Menunggu Verifikasi', 'val' => $totalPending],
                        'DITOLAK' => ['color' => '#94a3b8', 'label' => 'Perlu Tindak Lanjut', 'val' => \App\Models\SimandatKepegawaianLayanan::where('status_layanan', 'DITOLAK')->count()],
                        'DISETUJUI' => ['color' => '#3b82f6', 'label' => 'Kajian & Koordinasi BKD', 'val' => \App\Models\SimandatKepegawaianLayanan::where('status_layanan', 'DISETUJUI')->count()],
                    ];
                @endphp
                @foreach($chartStatuses as $st)
                <div class="flex items-center justify-between text-[11px]">
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $st['color'] }}"></span>
                        <span class="text-slate-600 font-medium">{{ $st['label'] }}</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="font-bold text-slate-800">{{ $st['val'] }}</span>
                        <span class="text-slate-400 w-8 text-right text-[10px]">({{ $totalLayanan > 0 ? round(($st['val'] / $totalLayanan) * 100) : 0 }}%)</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Bar Chart -->
        <div class="lg:col-span-3 bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Jenis Layanan</h3>
                <p class="text-[11px] text-slate-500 mb-4">Distribusi 10 jenis layanan kepegawaian</p>
            </div>
            
            <div class="relative h-52 w-full mt-2">
                <canvas id="layananChart"></canvas>
            </div>
        </div>
    </div>

    <!-- 10 Jenis Dokumen & Layanan Kepegawaian (Grid View) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-5">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h3 class="text-sm font-bold text-[#00264A] tracking-wide">10 Jenis Dokumen & Layanan Kepegawaian</h3>
            <div class="flex items-center space-x-2">
                <button class="inline-flex items-center space-x-1.5 px-4 py-2 bg-white border border-slate-200 text-blue-600 text-[11px] font-bold rounded-lg hover:bg-slate-50 transition-colors shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <span>Filter</span>
                </button>
                <a href="{{ route('kepegawaian.layanan.index') }}" class="inline-flex items-center px-4 py-2 bg-[#005B9F] text-white text-[11px] font-bold rounded-lg hover:bg-[#003B70] transition-colors shadow-sm shadow-[#005B9F]/20">
                    Tampilkan Semua (10)
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3">
            @php $index = 1; @endphp
            @foreach($allJenisLayanan as $namaLayanan => $cfg)
                @php
                    $isActive = request('jenis_layanan') === $namaLayanan;
                    $count = $serviceCounts[$namaLayanan] ?? 0;
                    
                    // Assign generic icons based on index
                    $icons = [
                        1 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>',
                        2 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>',
                        3 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                        4 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>',
                        5 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>',
                        6 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>',
                        7 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>',
                        8 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>',
                        9 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>',
                        10 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>',
                    ];
                @endphp
                <a href="{{ route('kepegawaian.layanan.index', ['jenis_layanan' => $namaLayanan]) }}" 
                   class="flex items-center justify-between p-3.5 rounded-xl border {{ $isActive ? 'border-blue-400 bg-blue-50/50' : 'border-slate-100 bg-white hover:border-blue-200 hover:bg-blue-50/40' }} transition-all duration-200 group">
                    <div class="flex items-center space-x-4">
                        <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition-colors flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $icons[$index] !!}</svg>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-[11px] font-bold text-slate-400">{{ $index }}.</span>
                            <span class="text-[11px] font-bold text-slate-700 leading-snug group-hover:text-blue-700 transition-colors">{{ $namaLayanan }}</span>
                        </div>
                    </div>
                    <span class="w-8 h-8 rounded-lg {{ $isActive ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-600' }} text-[11px] font-bold flex items-center justify-center shrink-0">
                        {{ $count }}
                    </span>
                </a>
                @php $index++; @endphp
            @endforeach
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
        <form method="GET" action="{{ route('kepegawaian.layanan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            <div class="md:col-span-2 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pemohon, NIP, nomor agenda, atau SK..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            <div>
                <select name="status_layanan" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                    <option value="">Semua Status</option>
                    @foreach($statusOptions as $sKey => $sVal)
                        <option value="{{ $sKey }}" {{ request('status_layanan') == $sKey ? 'selected' : '' }}>{{ $sVal['label'] }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="kabupaten_kota" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                    <option value="">Semua Wilayah</option>
                    @foreach($kabupatenOptions as $kab)
                        <option value="{{ $kab }}" {{ request('kabupaten_kota') == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center space-x-2">
                <button type="submit" class="flex-1 py-2 px-3 bg-[#003B70] hover:bg-[#00264A] text-white text-xs font-bold rounded-xl transition-colors">
                    Filter
                </button>
                <a href="{{ route('kepegawaian.layanan.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-4">Nomor Agenda & Tanggal</th>
                        <th class="px-4 py-4">Jenis Layanan</th>
                        <th class="px-4 py-4">Pemohon & NIP</th>
                        <th class="px-4 py-4">Unit Kerja / Sekolah</th>
                        <th class="px-4 py-4">Status & No. SK</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($layananList as $item)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <!-- Agenda & Date -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="font-mono text-[11px] font-bold text-slate-800 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200 block w-max">
                                    {{ $item->nomor_agenda }}
                                </span>
                                <p class="text-[10px] text-slate-400 mt-1">{{ $item->tanggal_permohonan ? $item->tanggal_permohonan->format('d M Y') : '-' }}</p>
                            </td>

                            <!-- Service Type -->
                            <td class="px-4 py-4">
                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg border {{ $item->jenis_layanan_config['badge'] }} block w-max">
                                    {{ $item->jenis_layanan }}
                                </span>
                                @if($item->perihal_deskripsi)
                                    <p class="text-[11px] text-slate-500 line-clamp-1 mt-1">{{ $item->perihal_deskripsi }}</p>
                                @endif
                            </td>

                            <!-- Applicant & NIP -->
                            <td class="px-4 py-4">
                                <a href="{{ route('kepegawaian.layanan.show', $item->id) }}" class="font-bold text-slate-800 hover:text-blue-700 block">
                                    {{ $item->nama_pemohon }}
                                </a>
                                <p class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $item->nip_nik_nisn ?: 'Non-NIP' }}</p>
                            </td>

                            <!-- Unit Kerja -->
                            <td class="px-4 py-4">
                                <p class="font-semibold text-slate-700">{{ $item->jabatan_unit_kerja }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ $item->kabupaten_kota }}</p>
                            </td>

                            <!-- Status & SK -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-md border {{ $item->status_config['badge'] }} inline-block">
                                    {{ $item->status_config['label'] }}
                                </span>
                                @if($item->nomor_sk_surat)
                                    <p class="font-mono text-[10px] font-bold text-emerald-700 mt-1">No: {{ $item->nomor_sk_surat }}</p>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-1.5">
                                    <a href="{{ route('kepegawaian.layanan.show', $item->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>

                                    <a href="{{ route('kepegawaian.layanan.edit', $item->id) }}" class="p-1.5 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors" title="Proses / Update Status">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>

                                    <a href="{{ route('kepegawaian.layanan.download', ['id' => $item->id, 'type' => $item->file_hasil_sk ? 'sk' : 'syarat']) }}" class="p-1.5 text-emerald-600 hover:bg-emerald-100 rounded-lg transition-colors" title="Unduh Berkas / Tanda Bukti">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    </a>

                                    @if(auth()->user()->hasRole('ADMIN'))
                                        <form action="{{ route('kepegawaian.layanan.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus permohonan layanan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-100 rounded-lg transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-xs text-slate-500">
                                <div class="max-w-xs mx-auto text-center space-y-3">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <p class="text-sm font-bold text-slate-700">Belum ada permohonan</p>
                                    <p class="text-xs text-slate-400">Tidak ada data permohonan yang sesuai dengan filter pencarian.</p>
                                    <a href="{{ route('kepegawaian.layanan.create') }}" class="inline-block px-4 py-2 bg-[#003B70] text-white text-xs font-bold rounded-xl">
                                        + Buat Permohonan
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    @if($layananList->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $layananList->links() }}
        </div>
    @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Donut Chart
        const ctxStatus = document.getElementById('statusChart');
        if(ctxStatus) {
            new Chart(ctxStatus.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Selesai & SK Terbit', 'Sedang Diproses', 'Menunggu Verifikasi', 'Perlu Tindak Lanjut', 'Kajian & Koordinasi BKD'],
                    datasets: [{
                        data: [
                            {{ $totalSelesai }}, 
                            {{ $totalProses }}, 
                            {{ $totalPending }}, 
                            {{ \App\Models\SimandatKepegawaianLayanan::where('status_layanan', 'DITOLAK')->count() }},
                            {{ \App\Models\SimandatKepegawaianLayanan::where('status_layanan', 'DISETUJUI')->count() }}
                        ],
                        backgroundColor: ['#10b981', '#8b5cf6', '#f59e0b', '#94a3b8', '#3b82f6'],
                        borderWidth: 0,
                        cutout: '75%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) { label += ': '; }
                                    label += context.raw;
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Bar Chart
        const ctxLayanan = document.getElementById('layananChart');
        if(ctxLayanan) {
            new Chart(ctxLayanan.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
                    datasets: [{
                        data: [
                            @foreach($allJenisLayanan as $nama => $cfg)
                                {{ $serviceCounts[$nama] ?? 0 }},
                            @endforeach
                        ],
                        backgroundColor: [
                            '#3b82f6', '#14b8a6', '#10b981', '#f59e0b', '#f97316', 
                            '#ef4444', '#a855f7', '#6366f1', '#8b5cf6', '#4f46e5'
                        ],
                        borderRadius: 4,
                        borderWidth: 0,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                title: function(context) {
                                    const names = [
                                        @foreach($allJenisLayanan as $nama => $cfg)
                                            "{!! $nama !!}",
                                        @endforeach
                                    ];
                                    return names[context[0].dataIndex];
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, color: '#94a3b8', font: { size: 10 } },
                            grid: { color: '#f1f5f9', drawBorder: false }
                        },
                        x: {
                            ticks: { color: '#64748b', font: { size: 10 } },
                            grid: { display: false, drawBorder: false }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection
