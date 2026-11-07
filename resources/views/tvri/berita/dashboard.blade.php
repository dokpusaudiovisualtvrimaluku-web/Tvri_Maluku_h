@extends('layouts.app')

@section('title', 'Dashboard Berita TVRI Maluku')

@php
    $formatNum = fn($n) => number_format($n, 0, ',', '.');
    $user = auth()->user();
    $isKepalaStasiun = $user && ($user->hasRole('KEPALA_DINAS') || $user->hasRole('Kepala Stasiun') || $user->username === 'kepala.stasiun' || str_contains(strtolower($user->name ?? ''), 'kepala stasiun'));
@endphp

@section('content')
<div class="space-y-5 pb-12" id="beranda">

    <!-- ===== HEADER NEWSROOM INFOGRAFIS ===== -->
    <div class="relative bg-gradient-to-r from-[#00142A] via-[#002244] to-[#003B70] rounded-3xl p-6 sm:p-7 text-white shadow-xl overflow-hidden border border-cyan-400/20">
        <div class="absolute -right-12 -top-16 w-80 h-80 bg-cyan-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-12 -bottom-16 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5">
            <!-- Brand & Info -->
            <div class="flex items-start sm:items-center space-x-4">
                <div class="w-16 h-16 rounded-2xl bg-white p-2 shadow-xl flex-shrink-0 flex items-center justify-center border-2 border-cyan-300/40">
                    <img src="{{ asset('images/logo_tvri.png') }}" alt="Logo TVRI" class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-cyan-400/20 text-cyan-300 border border-cyan-400/30">
                            BIDANG PEMBERITAAN (NEWSROOM)
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-400/20 text-amber-300 border border-amber-400/30 italic">
                            Jendela Maluku untuk Indonesia
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white mt-1 leading-tight uppercase">
                        Dashboard Berita TVRI Maluku
                    </h1>
                    <p class="text-xs text-blue-200 font-extrabold tracking-wider uppercase mt-0.5">
                        CEPAT &bull; AKURAT &bull; TERPERCAYA &bull; UNTUK MALUKU DAN INDONESIA
                    </p>
                </div>
            </div>

            <!-- Live Status & Action Buttons -->
            <div class="flex flex-col sm:flex-row xl:flex-col items-start xl:items-end justify-between gap-3">
                <div class="bg-white/10 backdrop-blur-md px-4 py-2 rounded-2xl border border-white/15 text-left xl:text-right">
                    <div class="flex items-center xl:justify-end space-x-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-[10px] text-emerald-300 font-black uppercase tracking-wider">Live Newsroom</span>
                    </div>
                    <p class="text-xs font-black text-white global-live-datetime-bullet" id="liveClockWidget">{{ now()->isoFormat('dddd, D MMMM Y') }} &bull; {{ now()->format('H:i') }} WIT</p>
                </div>

                <div class="flex items-center space-x-2">
                    @if(!$isKepalaStasiun)
                    <a href="{{ route('tvri.berita.manage') }}" 
                       class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl text-xs font-black bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white shadow-md border border-amber-300/30 transition-all hover:scale-[1.02]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        <span>Input Data Berita</span>
                    </a>
                    @else
                    <a href="{{ route('tvri.ecc.dashboard') }}" 
                       class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl text-xs font-black bg-gradient-to-r from-cyan-600 to-blue-700 hover:from-cyan-700 hover:to-blue-800 text-white shadow-md border border-cyan-300/30 transition-all hover:scale-[1.02]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span>Kembali ke Command Center</span>
                    </a>
                    @endif
                    <a href="{{ route('dokumen.documents.index') }}" 
                       class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-white/10 hover:bg-white/20 text-white border border-white/15 transition-all">
                        <svg class="w-4 h-4 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Pangkalan Dokumen</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== MENU NAVIGASI NEWSROOM ===== -->
    <div class="bg-white rounded-2xl p-2.5 border border-slate-200 shadow-sm flex items-center justify-between overflow-x-auto gap-2">
        <div class="flex items-center space-x-1.5 flex-nowrap">
            <a href="#beranda" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-[#00264A] transition-colors whitespace-nowrap">Dashboard</a>
            <a href="#kategori" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-[#00264A] transition-colors whitespace-nowrap">Kategori Berita</a>
            <a href="#sebaran" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-[#00264A] transition-colors whitespace-nowrap">Sebaran Lokasi</a>
            <a href="#status-liputan" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-[#00264A] transition-colors whitespace-nowrap">Status Liputan</a>
            <a href="#realtime" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-[#00264A] transition-colors whitespace-nowrap">Real Time Newsroom</a>
            <a href="#top-berita" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-[#00264A] transition-colors whitespace-nowrap">Top 5 Digital</a>
            <a href="#distribusi" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-[#00264A] transition-colors whitespace-nowrap">Distribusi Media</a>
            <a href="#agenda" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-[#00264A] transition-colors whitespace-nowrap">Agenda Liputan</a>
            <a href="#produksi-paket" class="px-3 py-1.5 rounded-xl text-xs font-bold text-amber-700 hover:bg-amber-50 hover:text-amber-800 transition-colors whitespace-nowrap">Produksi Paket</a>
            <a href="#monitoring-berita" class="px-3 py-1.5 rounded-xl text-xs font-bold text-blue-700 hover:bg-blue-50 hover:text-blue-800 transition-colors whitespace-nowrap">Monitoring Berita</a>
            <a href="#dokumentasi-berita" class="px-3 py-1.5 rounded-xl text-xs font-bold text-purple-700 hover:bg-purple-50 hover:text-purple-800 transition-colors whitespace-nowrap">Dokumentasi Berita</a>
            <a href="#jadwal-liputan" 
               class="px-3 py-1.5 rounded-xl text-xs font-black bg-gradient-to-r from-blue-600 to-[#003B70] text-white hover:from-blue-700 hover:to-[#00264A] shadow-sm transition-all whitespace-nowrap inline-flex items-center space-x-1.5 cursor-pointer">
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-300 animate-pulse"></span>
                <span>Jadwal Liputan TVRI</span>
            </a>
            <a href="#sop" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-[#00264A] transition-colors whitespace-nowrap">Dokumen & SOP</a>
        </div>

        @if($isKepalaStasiun || (auth()->user() && auth()->user()->username !== 'berita' && in_array(auth()->user()->username, ['admin', 'superadmin', 'keuangan'])))
        <div class="flex items-center space-x-2 pl-3 border-l border-slate-200 flex-shrink-0">
            @if($isKepalaStasiun)
            <a href="{{ route('tvri.ecc.dashboard') }}" 
               class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-xl text-xs font-bold bg-cyan-50 text-cyan-800 hover:bg-cyan-100 border border-cyan-200 transition-colors whitespace-nowrap">
                <svg class="w-3.5 h-3.5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Command Center</span>
            </a>
            @endif
            @if(auth()->user() && auth()->user()->username !== 'berita' && in_array(auth()->user()->username, ['admin', 'superadmin', 'keuangan']))
                <a href="{{ route('tvri.keuangan.dashboard') }}" 
                   class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-xl text-xs font-bold bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 transition-colors whitespace-nowrap">
                    <span>Ke Dashboard Keuangan &rarr;</span>
                </a>
            @endif
        </div>
        @endif
    </div>

    <!-- ===== 6 TOP KPI CARDS UTAMA ===== -->
    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-4">
        <!-- 1. Rencana Liputan -->
        <div class="bg-white rounded-2xl p-4 border border-blue-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">Rencana Liputan</span>
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <p class="text-2xl font-black text-[#00264A] tracking-tight">{{ $ringkasan->rencana_liputan }}</p>
                <div class="flex items-center space-x-1 mt-1 text-[11px] font-bold text-emerald-600">
                    <span>▲ +12%</span>
                    <span class="text-slate-400 font-semibold text-[10px]">dari bln lalu</span>
                </div>
            </div>
            <div class="mt-3 w-full bg-blue-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-blue-600 h-full rounded-full w-full"></div>
            </div>
        </div>

        <!-- 2. Liputan Dilakukan -->
        <div class="bg-white rounded-2xl p-4 border border-emerald-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-black uppercase tracking-wider text-emerald-700">Liputan Dilakukan</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <p class="text-2xl font-black text-emerald-700 tracking-tight">{{ $ringkasan->liputan_dilakukan }}</p>
                <div class="flex items-center space-x-1 mt-1 text-[11px] font-bold text-slate-600">
                    <span class="px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px]">85%</span>
                    <span class="text-slate-400 text-[10px]">dari rencana</span>
                </div>
            </div>
            <div class="mt-3 w-full bg-emerald-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-emerald-500 h-full rounded-full w-[85%]"></div>
            </div>
        </div>

        <!-- 3. Berita Diproduksi -->
        <div class="bg-white rounded-2xl p-4 border border-amber-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-black uppercase tracking-wider text-amber-700">Berita Diproduksi</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <p class="text-2xl font-black text-amber-700 tracking-tight">{{ $ringkasan->berita_diproduksi }}</p>
                <div class="flex items-center space-x-1 mt-1 text-[11px] font-bold text-emerald-600">
                    <span>▲ +8%</span>
                    <span class="text-slate-400 text-[10px]">selesai edit</span>
                </div>
            </div>
            <div class="mt-3 w-full bg-amber-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-amber-500 h-full rounded-full w-[80%]"></div>
            </div>
        </div>

        <!-- 4. Berita Tayang -->
        <div class="bg-white rounded-2xl p-4 border border-rose-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-black uppercase tracking-wider text-rose-700">Berita Tayang</span>
                <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <p class="text-2xl font-black text-rose-700 tracking-tight">{{ $ringkasan->berita_tayang }}</p>
                <div class="flex items-center space-x-1 mt-1 text-[11px] font-bold text-emerald-600">
                    <span>▲ +12%</span>
                    <span class="text-slate-400 text-[10px]">siap siar</span>
                </div>
            </div>
            <div class="mt-3 w-full bg-rose-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-rose-500 h-full rounded-full w-[78%]"></div>
            </div>
        </div>

        <!-- 5. Distribusi Digital -->
        <div class="bg-white rounded-2xl p-4 border border-purple-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-black uppercase tracking-wider text-purple-700">Distribusi Digital</span>
                <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <p class="text-2xl font-black text-purple-800 tracking-tight">1,2 Juta</p>
                <div class="flex items-center space-x-1 mt-1 text-[11px] font-bold text-emerald-600">
                    <span>▲ +35%</span>
                    <span class="text-slate-400 text-[10px]">Views</span>
                </div>
            </div>
            <div class="mt-3 w-full bg-purple-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-purple-600 h-full rounded-full w-[90%]"></div>
            </div>
        </div>

        <!-- 6. Newsroom Performance Index -->
        <div class="bg-white rounded-2xl p-4 border border-cyan-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-black uppercase tracking-wider text-cyan-800">Newsroom Index</span>
                <span class="text-[10px] font-bold text-slate-400">Target 90%</span>
            </div>
            <div class="flex items-center space-x-3 mt-1">
                <div class="relative w-12 h-12 flex-shrink-0 flex items-center justify-center">
                    <canvas id="gaugeIndexChart"></canvas>
                    <span class="absolute text-xs font-black text-[#00264A]">{{ $ringkasan->performance_index }}%</span>
                </div>
                <div>
                    <p class="text-xs font-black text-emerald-600">Sangat Baik</p>
                    <p class="text-[10px] text-slate-500 font-semibold">Tercapai &bull; On Track</p>
                </div>
            </div>
            <div class="mt-3 w-full bg-cyan-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-cyan-500 h-full rounded-full w-[92%]"></div>
            </div>
        </div>
    </div>

    <!-- ===== SECTION 1: TREN, KATEGORI & SEBARAN ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6" id="kategori">
        <!-- Tren Produksi & Tayang Berita (5 Kolom) -->
        <div class="lg:col-span-5 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">
                        Tren Produksi & Tayang Berita
                    </h3>
                    <p class="text-xs text-slate-500">Pergerakan harian 1 Sep - 10 Sep 2025</p>
                </div>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800">Harian</span>
            </div>
            <div class="relative w-full h-[260px]">
                <canvas id="trenBeritaChart"></canvas>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-center space-x-5 text-xs font-semibold text-slate-600">
                <div class="flex items-center space-x-1.5"><span class="w-3 h-3 rounded-full bg-blue-500"></span><span>Rencana</span></div>
                <div class="flex items-center space-x-1.5"><span class="w-3 h-3 rounded-full bg-emerald-500"></span><span>Produksi</span></div>
                <div class="flex items-center space-x-1.5"><span class="w-3 h-3 rounded-full bg-amber-500"></span><span>Tayang</span></div>
            </div>
        </div>

        <!-- Kategori Berita (Donut - 4 Kolom) -->
        <div class="lg:col-span-4 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Kategori Berita</h3>
                    <p class="text-xs text-slate-500">Distribusi topik penyiaran</p>
                </div>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">95 Berita</span>
            </div>
            <div class="relative w-full h-[200px] flex items-center justify-center">
                <canvas id="kategoriDonutChart"></canvas>
            </div>
            <div class="grid grid-cols-2 gap-x-3 gap-y-1 mt-2 text-[11px]">
                @foreach($kategoriList as $kat)
                    <div class="flex items-center justify-between py-0.5 border-b border-slate-50">
                        <div class="flex items-center space-x-1.5 truncate">
                            <span class="w-2 h-2 rounded-full flex-shrink-0" style="background-color: {{ $kat['color'] }}"></span>
                            <span class="text-slate-700 truncate text-[10px]">{{ $kat['nama'] }}</span>
                        </div>
                        <span class="font-bold text-slate-900 text-[10px]">{{ $kat['jumlah'] }} ({{ $kat['persen'] }}%)</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Sebaran Lokasi Liputan (3 Kolom) -->
        <div class="lg:col-span-3 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between" id="sebaran">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Sebaran Lokasi</h3>
                    <p class="text-xs text-slate-500">Liputan Kab/Kota Maluku</p>
                </div>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700">11 Wilayah</span>
            </div>

            <!-- Mini Map Graphic Card -->
            <div class="p-2.5 rounded-xl bg-gradient-to-br from-[#001D38] to-[#003B70] text-white text-center shadow-inner relative overflow-hidden mb-2">
                <p class="text-[10px] font-extrabold text-cyan-300 uppercase tracking-widest">Gugus Kepulauan Maluku</p>
                <p class="text-xs font-black mt-0.5">Liputan Berimbang & Merata</p>
            </div>

            <div class="space-y-1 text-xs divide-y divide-slate-100">
                @foreach($sebaranLokasi as $wil => $jml)
                    <div class="flex items-center justify-between py-1 text-[11px]">
                        <span class="text-slate-600">{{ $wil }}</span>
                        <span class="font-black text-[#00264A] bg-slate-100 px-2 py-0.5 rounded">{{ $jml }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- ===== SECTION 2: STATUS, REAL TIME FEED & TOP 5 DIGITAL ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6" id="status-liputan">
        <!-- Status Liputan (3 Kolom) -->
        <div class="lg:col-span-3 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Status Liputan</h3>
                    <p class="text-xs text-slate-500">120 Rencana Liputan</p>
                </div>
            </div>
            <div class="relative w-full h-[180px] flex items-center justify-center">
                <canvas id="statusLiputanChart"></canvas>
            </div>
            <div class="space-y-1.5 mt-2 text-xs">
                @foreach($statusList as $stName => $stVal)
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <div class="flex items-center space-x-2">
                            <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $stVal['color'] }}"></span>
                            <span class="text-slate-700 font-medium">{{ $stName }}</span>
                        </div>
                        <span class="font-bold text-slate-900">{{ $stVal['jumlah'] }} ({{ $stVal['persen'] }}%)</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Real Time Newsroom (• LIVE) (5 Kolom) -->
        <div class="lg:col-span-5 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between" id="realtime">
            <div>
                <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-600 animate-ping"></span>
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Real Time Newsroom</h3>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-100 text-rose-700">LIVE FEED</span>
                </div>

                <div class="space-y-2.5">
                    @forelse($realtimeFeeds as $feed)
                        <div class="flex items-start space-x-2.5 text-xs p-2 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-black text-[10px] flex-shrink-0">{{ $feed->waktu }}</span>
                            <p class="text-slate-700 font-semibold leading-relaxed">{{ $feed->kegiatan }}</p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-4">Belum ada feed live redaksi.</p>
                    @endforelse
                </div>
            </div>

            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Tim Newsroom Terhubung di Seluruh Titik Siar</span>
                <a href="{{ route('tvri.berita.manage') }}?tab=realtime" class="text-blue-600 font-bold hover:underline">+ Update Feed</a>
            </div>
        </div>

        <!-- Top 5 Berita (Digital) (4 Kolom) -->
        <div class="lg:col-span-4 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between" id="top-berita">
            <div>
                <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Top 5 Berita (Digital)</h3>
                    <span class="text-[10px] font-bold text-slate-400">Paling Banyak Ditonton</span>
                </div>

                <div class="space-y-2">
                    @foreach($topBerita as $idx => $tb)
                        <div class="flex items-center space-x-2.5 p-2 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors">
                            <span class="w-6 h-6 rounded-lg bg-[#00264A] text-white flex items-center justify-center font-black text-xs flex-shrink-0">
                                {{ $idx + 1 }}
                            </span>
                            <div class="min-w-0 flex-1 truncate">
                                <p class="text-xs font-bold text-slate-800 truncate">{{ $tb->judul }}</p>
                                <p class="text-[10px] text-slate-400 font-medium">{{ number_format($tb->views) }} views &bull; {{ $tb->tanggal ?? '2 Sep 2025' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-3 pt-2 border-t border-slate-100 text-right">
                <a href="{{ route('tvri.berita.manage') }}" class="text-xs text-blue-600 font-bold hover:underline">Kelola Konten Digital &rarr;</a>
            </div>
        </div>
    </div>

    <!-- ===== SECTION 3: JENIS PENYIARAN, DISTRIBUSI & KUALITAS ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6" id="distribusi">
        <!-- Jenis Penyiaran Berita (4 Kolom) -->
        <div class="lg:col-span-4 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide mb-1">Jenis Penyiaran Berita</h3>
                <p class="text-xs text-slate-500 mb-3">Distribusi format penayangan</p>

                <div class="space-y-3 text-xs">
                    @foreach($jenisPenyiaran as $jName => $jVal)
                        <div>
                            <div class="flex items-center justify-between font-bold mb-1">
                                <span class="text-slate-700">{{ $jName }}</span>
                                <span class="text-slate-900">{{ $jVal['jumlah'] }} ({{ $jVal['persen'] }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="h-full rounded-full" style="width: {{ $jVal['persen'] }}%; background-color: {{ $jVal['color'] }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Kecepatan Publikasi & Media Distribusi (4 Kolom) -->
        <div class="lg:col-span-4 space-y-4">
            <!-- Kecepatan Publikasi -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase">Kecepatan Publikasi</p>
                        <p class="text-xl font-black text-[#00264A]">{{ $ringkasan->kecepatan_publikasi_menit }} Menit</p>
                        <p class="text-[10px] text-slate-500">Dari liputan ke tayang</p>
                    </div>
                </div>
                <span class="px-2 py-1 rounded-md text-[10px] font-black bg-emerald-100 text-emerald-800">▼ -20% Lebih Cepat</span>
            </div>

            <!-- Media Distribusi -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-2.5">Media Distribusi Berita</p>
                <div class="grid grid-cols-5 gap-2 text-center text-xs">
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-blue-600 text-base font-bold block">📺</span>
                        <span class="font-black text-slate-800 block text-xs mt-1">{{ $ringkasan->dist_tv }}</span>
                        <span class="text-[9px] text-slate-400 font-bold">TV</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-teal-600 text-base font-bold block">🌐</span>
                        <span class="font-black text-slate-800 block text-xs mt-1">{{ $ringkasan->dist_website }}</span>
                        <span class="text-[9px] text-slate-400 font-bold">Web</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-rose-600 text-base font-bold block">▶️</span>
                        <span class="font-black text-slate-800 block text-xs mt-1">{{ $ringkasan->dist_youtube }}</span>
                        <span class="text-[9px] text-slate-400 font-bold">YouTube</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-pink-600 text-base font-bold block">📸</span>
                        <span class="font-black text-slate-800 block text-xs mt-1">{{ $ringkasan->dist_instagram }}</span>
                        <span class="text-[9px] text-slate-400 font-bold">IG</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-900 text-base font-bold block">🎵</span>
                        <span class="font-black text-slate-800 block text-xs mt-1">{{ $ringkasan->dist_tiktok }}</span>
                        <span class="text-[9px] text-slate-400 font-bold">TikTok</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kualitas Berita (Checklist - 4 Kolom) -->
        <div class="lg:col-span-4 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center space-x-2 mb-3 pb-2 border-b border-slate-100">
                    <div class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs">✓</div>
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Kualitas Berita</h3>
                </div>

                <div class="space-y-2 text-xs">
                    <div class="flex items-center justify-between p-2 rounded-xl bg-emerald-50/70 border border-emerald-100">
                        <span class="font-bold text-emerald-900">☑ Fakta Terverifikasi</span>
                        <span class="font-black text-emerald-700">{{ $ringkasan->kualitas_fakta }}%</span>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-xl bg-emerald-50/70 border border-emerald-100">
                        <span class="font-bold text-emerald-900">☑ Sesuai Kode Etik Jurnalistik</span>
                        <span class="font-black text-emerald-700">{{ $ringkasan->kualitas_etik }}%</span>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-xl bg-emerald-50/70 border border-emerald-100">
                        <span class="font-bold text-emerald-900">☑ Akurasi Data & Narasumber</span>
                        <span class="font-black text-emerald-700">{{ $ringkasan->kualitas_akurasi }}%</span>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-xl bg-emerald-50/70 border border-emerald-100">
                        <span class="font-bold text-emerald-900">☑ Penggunaan Visual / Footage Berkualitas</span>
                        <span class="font-black text-emerald-700">{{ $ringkasan->kualitas_visual }}%</span>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-xl bg-emerald-50/70 border border-emerald-100">
                        <span class="font-bold text-emerald-900">☑ Kepatuhan SOP Newsroom</span>
                        <span class="font-black text-emerald-700">{{ $ringkasan->kualitas_sop }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== SECTION 4: BERITA TERBARU (VIDEO CARDS), AGENDA & CATATAN ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6" id="agenda">
        <!-- Berita Terbaru Video Cards (12 Kolom) -->
        <div class="lg:col-span-12 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Berita Terbaru (Siap Siar)</h3>
                    <p class="text-xs text-slate-500">Tayangan berita terkini TVRI Stasiun Maluku</p>
                </div>
                <a href="{{ route('tvri.berita.manage') }}" class="text-xs font-bold text-blue-600 hover:underline">Lihat Semua Berita &rarr;</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3.5">
                @php
                    $sampleBroadcastVideos = [
                        'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
                        'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
                        'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
                        'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4',
                        'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/TearsOfSteel.mp4',
                    ];
                @endphp
                @foreach($beritaTerbaru as $b)
                    @php
                        $targetVideoUrl = !empty($b['video_url']) ? $b['video_url'] : ($b['playable_url'] ?? $sampleBroadcastVideos[$loop->index % count($sampleBroadcastVideos)]);
                    @endphp
                    <div class="group rounded-2xl bg-slate-50 border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col justify-between cursor-pointer"
                         data-video-url="{{ $targetVideoUrl }}"
                         data-video-title="{{ $b['judul'] }}"
                         onclick="openVideoPlayer(this.dataset.videoUrl, this.dataset.videoTitle)"
                         title="Klik untuk memutar video siaran: {{ $b['judul'] }}">
                        <!-- Video Mock / Real Video Thumbnail -->
                        <div class="relative h-28 bg-gradient-to-tr from-[#001D38] to-[#003B70] flex items-center justify-center p-3 text-center select-none">
                            @if(!empty($b['video_url']))
                                <span class="w-10 h-10 rounded-full bg-emerald-500 hover:bg-emerald-600 flex items-center justify-center text-white group-hover:scale-110 transition-transform shadow-lg">
                                    <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </span>
                                <span class="absolute top-2 right-2 px-1.5 py-0.5 rounded bg-emerald-600/90 text-white font-black text-[8px] uppercase tracking-wider shadow">
                                    Video Ready
                                </span>
                            @else
                                <span class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white group-hover:scale-110 transition-transform shadow-lg">
                                    ▶
                                </span>
                            @endif
                            <span class="absolute bottom-2 right-2 px-1.5 py-0.5 rounded bg-black/70 text-white font-black text-[9px]">
                                {{ $b['durasi'] }}
                            </span>
                            <span class="absolute top-2 left-2 px-1.5 py-0.5 rounded bg-blue-600/80 text-white font-bold text-[8px] uppercase">
                                {{ $b['kategori'] }}
                            </span>
                        </div>
                        <div class="p-3">
                            <h4 class="text-xs font-bold text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-2 leading-tight">
                                {{ $b['judul'] }}
                            </h4>
                            <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-200/60 text-[10px] text-slate-400 font-semibold">
                                <span>📍 {{ $b['lokasi'] }}</span>
                                <span>{{ $b['tanggal'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Agenda Liputan Hari Ini & Jadwal Mingguan (7 Kolom) -->
        <div class="lg:col-span-7 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between scroll-mt-24"
             id="jadwal-liputan"
             x-data="{ 
                 selectedHari: '', 
                 searchLiputan: '', 
                 selectedCrew: '', 
                 selectedJenis: '',
                 activeSlide: 0,
                 totalSlides: {{ max(1, (int) ceil($agendaLiputan->count() / 2)) }},
                 autoSlide: true,
                 viewMode: 'slide',
                 timer: null,
                 init() {
                     if (this.totalSlides > 1) {
                         this.timer = setInterval(() => {
                             if (this.autoSlide && this.viewMode === 'slide' && !this.isFiltered()) {
                                 this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
                             }
                         }, 8000);
                     }
                 },
                 pauseSlide() { this.autoSlide = false; },
                 resumeSlide() { this.autoSlide = true; },
                 nextSlide() { this.activeSlide = (this.activeSlide + 1) % this.totalSlides; },
                 prevSlide() { this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides; },
                 goToSlide(i) { this.activeSlide = i; },
                 isFiltered() {
                     return Boolean(this.selectedHari || this.searchLiputan || this.selectedCrew || this.selectedJenis);
                 }
             }"
             @mouseenter="pauseSlide()"
             @mouseleave="resumeSlide()">
            <div>
                <!-- Header Card Agenda -->
                <div class="flex items-center justify-between mb-3 pb-2.5 border-b border-slate-100 flex-wrap gap-2">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Jadwal Peliputan Berita</h3>
                            <span class="text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-full lowercase">{{ $totalLiputan }} liputan</span>
                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full font-mono">Live Real-Time</span>
                        </div>
                        <p class="text-[11px] text-slate-500 font-medium mt-1 flex items-center space-x-1.5 flex-wrap">
                            <span class="text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 font-mono text-[10px] font-bold" id="agendaDashRealtimeClock">
                                {{ now()->isoFormat('dddd, D MMMM Y') }} &bull; {{ now()->format('H:i:s') }} WIT
                            </span>
                        </p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button type="button" onclick="openJadwalVercelModal()" 
                                class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-xl text-xs font-bold bg-blue-50 text-[#005B9F] hover:bg-blue-100 border border-blue-200 transition-colors cursor-pointer">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                            <span>Portal Jadwal &amp; Kru</span>
                            <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </button>
                        @if(!$isKepalaStasiun)
                        <a href="{{ route('tvri.berita.manage') }}?tab=agenda" class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 transition-colors">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Kelola Data</span>
                        </a>
                        @endif
                    </div>
                </div>

                <!-- 4 KPI Badges Peliputan -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-3">
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-[9px] uppercase font-bold text-slate-400 block">Total Liputan</span>
                            <span class="text-sm font-black text-[#00264A]">{{ $totalLiputan }}</span>
                        </div>
                        <span class="text-xs">📋</span>
                    </div>
                    <div class="p-2 rounded-xl bg-rose-50/70 border border-rose-100 flex items-center justify-between">
                        <div>
                            <span class="text-[9px] uppercase font-bold text-rose-500 block">Siaran Langsung</span>
                            <span class="text-sm font-black text-rose-600">{{ $siaranLangsung }} Live</span>
                        </div>
                        <span class="text-xs">📡</span>
                    </div>
                    <div class="p-2 rounded-xl bg-amber-50/70 border border-amber-100 flex items-center justify-between">
                        <div>
                            <span class="text-[9px] uppercase font-bold text-amber-600 block">Prioritas</span>
                            <span class="text-sm font-black text-amber-700">{{ $prioritasPenting }}</span>
                        </div>
                        <span class="text-xs">⚡</span>
                    </div>
                    <div class="p-2 rounded-xl bg-emerald-50/70 border border-emerald-100 flex items-center justify-between">
                        <div>
                            <span class="text-[9px] uppercase font-bold text-emerald-600 block">Crew Bertugas</span>
                            <span class="text-sm font-black text-emerald-700">{{ $crewBertugas }} Kru</span>
                        </div>
                        <span class="text-xs">👥</span>
                    </div>
                </div>

                <!-- Search & Filters Strip (Real-Time Berdasarkan Hari, Bulan & Tahun) -->
                <div class="space-y-2 mb-3">
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2">
                        <div class="sm:col-span-4">
                            <input type="text" x-model="searchLiputan" placeholder="Cari liputan / lokasi..."
                                   class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-medium">
                        </div>
                        <div class="sm:col-span-3">
                            <div class="relative flex items-center">
                                <input type="date" x-model="selectedHari" title="Filter Real-Time Berdasarkan Hari, Bulan &amp; Tahun"
                                       class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 bg-white">
                                <button type="button" x-show="selectedHari" 
                                        @click="selectedHari = ''" 
                                        class="absolute right-2 text-slate-400 hover:text-slate-600 text-xs font-bold" title="Tampilkan Semua Tanggal">✕</button>
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            @php
                                $crewOptions = collect(['Sarles Bolaman', 'Yaser Tuarita', 'Christine Sipahelut', 'Novita Bonara', 'Monica Sipahelut', 'Devy Bin Umar', 'Yohanis Limba', 'Shinta', 'Peggy Nahumury', 'Minggus Noya', 'Junaedi Asril', 'Fernanda Rasyid', 'Andre Uktolseja', 'Kurniawan Suwarno', 'Idrus', 'Max Appono', 'Della']);
                                foreach($agendaLiputan as $a) {
                                    if(!empty($a->reporter)) $crewOptions->push($a->reporter);
                                    if(!empty($a->kamerawan)) $crewOptions->push($a->kamerawan);
                                    if(!empty($a->driver) && $a->driver !== '-') $crewOptions->push($a->driver);
                                }
                                $crewOptions = $crewOptions->filter()->unique()->values();
                            @endphp
                            <select x-model="selectedCrew" class="w-full px-2 py-1.5 rounded-xl border border-slate-200 text-xs">
                                <option value="">Semua Crew</option>
                                @foreach($crewOptions as $c)
                                    <option value="{{ $c }}">{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <select x-model="selectedJenis" class="w-full px-2 py-1.5 rounded-xl border border-slate-200 text-xs">
                                <option value="">Semua Jenis</option>
                                @foreach(['Berita Harian', 'Ekonomi', 'Budaya', 'Live', 'Feature', 'Sosial', 'Investigasi', 'Olahraga', 'Lainnya'] as $j)
                                    <option value="{{ $j }}">{{ $j }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Slide Controls Bar -->
                <div class="flex items-center justify-between py-1.5 px-2.5 bg-slate-50/80 rounded-xl border border-slate-100 text-xs mb-2.5">
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center space-x-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-[#005B9F] border border-blue-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600" :class="autoSlide && viewMode === 'slide' && !isFiltered() ? 'animate-pulse' : ''"></span>
                            <span x-text="isFiltered() ? 'Hasil Filter' : (viewMode === 'all' ? 'Semua Liputan ({{ $agendaLiputan->count() }})' : 'Slide ' + (activeSlide + 1) + ' dari ' + totalSlides)"></span>
                        </span>
                        <template x-if="totalSlides > 1 && !isFiltered() && viewMode === 'slide'">
                            <div class="flex items-center space-x-1">
                                @for($s = 0; $s < max(1, (int) ceil($agendaLiputan->count() / 2)); $s++)
                                    <button type="button" @click="goToSlide({{ $s }})" 
                                            class="h-1.5 rounded-full transition-all duration-300 cursor-pointer"
                                            :class="activeSlide === {{ $s }} ? 'w-5 bg-[#005B9F]' : 'w-1.5 bg-slate-300 hover:bg-slate-400'"></button>
                                @endfor
                            </div>
                        </template>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <template x-if="totalSlides > 1 && !isFiltered() && viewMode === 'slide'">
                            <div class="inline-flex items-center space-x-1">
                                <button type="button" @click="prevSlide()" title="Slide Sebelumnya"
                                        class="p-1 rounded-lg hover:bg-slate-200/70 text-slate-600 transition-colors cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                <button type="button" @click="autoSlide = !autoSlide" :title="autoSlide ? 'Jeda Slide Otomatis' : 'Putar Slide Otomatis'"
                                        class="px-1.5 py-0.5 rounded-md text-[10px] font-bold hover:bg-slate-200/70 transition-colors cursor-pointer"
                                        :class="autoSlide ? 'text-emerald-700' : 'text-slate-500'">
                                    <span x-text="autoSlide ? '⏸️ Jeda' : '▶️ Putar'"></span>
                                </button>
                                <button type="button" @click="nextSlide()" title="Slide Berikutnya"
                                        class="p-1 rounded-lg hover:bg-slate-200/70 text-slate-600 transition-colors cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </template>
                        <button type="button" @click="viewMode = (viewMode === 'slide' ? 'all' : 'slide')"
                                class="px-2 py-0.5 rounded-lg text-[10px] font-bold transition-colors border cursor-pointer"
                                :class="viewMode === 'all' ? 'bg-[#00264A] text-white border-[#00264A]' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-100'">
                            <span x-text="viewMode === 'all' ? 'Mode Slide' : 'Semua'"></span>
                        </button>
                    </div>
                </div>

                <!-- Rich Interactive Agenda List -->
                <div class="space-y-2.5 max-h-[500px] overflow-y-auto pr-1">
                    @if($agendaLiputan->isEmpty())
                        <p class="text-xs text-slate-400 py-6 text-center">Belum ada agenda liputan terdaftar.</p>
                    @else
                        <!-- 1. MODE SLIDE: Kotak tetap stabil di tempat, tulisan di dalam kotak yang di-slide berganti -->
                        <div x-show="viewMode === 'slide' && !isFiltered()" class="space-y-2.5">
                            <!-- Slot 1: Kotak tetap permanen -->
                            <div class="p-3 rounded-xl border border-slate-200 hover:border-blue-300 hover:shadow-sm transition-all bg-slate-50/50 min-h-[142px] relative overflow-hidden flex flex-col justify-between">
                                @foreach($agendaLiputan as $ag)
                                    @if($loop->index % 2 === 0)
                                        @php
                                            $agTglStr = $ag->tanggal ? (is_string($ag->tanggal) ? substr($ag->tanggal, 0, 10) : $ag->tanggal->format('Y-m-d')) : '';
                                            $agCrewAll = strtolower($ag->reporter . ' ' . $ag->kamerawan . ' ' . $ag->driver . ' ' . $ag->pic);
                                            $agJenisVal = $ag->jenis ?: 'Berita Harian';
                                            $agPrioClass = match($ag->prioritas) {
                                                'Mendesak' => 'bg-rose-100 text-rose-700 border-rose-200',
                                                'Penting' => 'bg-amber-100 text-amber-800 border-amber-200',
                                                default => 'bg-slate-100 text-slate-700 border-slate-200',
                                            };
                                            $agStatusClass = match($ag->status) {
                                                'Selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                                'On Air' => 'bg-rose-100 text-rose-800 border-rose-300 animate-pulse',
                                                'Proses' => 'bg-blue-100 text-blue-800 border-blue-300',
                                                default => 'bg-slate-100 text-slate-700 border-slate-200',
                                            };
                                        @endphp
                                        <div x-show="activeSlide === {{ (int)($loop->index / 2) }}"
                                             x-transition:enter="transition-opacity duration-700 ease-out"
                                             x-transition:enter-start="opacity-0"
                                             x-transition:enter-end="opacity-100"
                                             x-transition:leave="transition-opacity duration-700 ease-in absolute inset-0 p-3 pointer-events-none"
                                             x-transition:leave-start="opacity-100"
                                             x-transition:leave-end="opacity-0"
                                             class="flex flex-col justify-between h-full gap-2">
                                            <div class="flex items-start justify-between gap-2">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center space-x-1.5 flex-wrap gap-y-1">
                                                        <span class="font-mono text-[10px] font-bold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200">
                                                            {{ $ag->kode ?: 'LP-' . $ag->id }}
                                                        </span>
                                                        <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">
                                                            {{ $ag->waktu }} @if($ag->durasi_label) &bull; {{ $ag->durasi_label }} @endif
                                                        </span>
                                                        <span class="text-[10px] font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200">
                                                            {{ $ag->jenis ?: 'Berita Harian' }}
                                                        </span>
                                                        @if($ag->prioritas && $ag->prioritas !== 'Biasa')
                                                            <span class="text-[10px] font-extrabold px-2 py-0.5 rounded border {{ $agPrioClass }}">
                                                                {{ $ag->prioritas }}
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <!-- Slide HANYA pada Tulisan Agenda & Lokasi (Halus & Profesional) -->
                                                    <div class="overflow-hidden relative my-0.5 min-h-[38px]">
                                                        <div x-show="activeSlide === {{ (int)($loop->index / 2) }}"
                                                             x-transition:enter="transition-all duration-700 ease-out transform"
                                                             x-transition:enter-start="opacity-0 translate-y-3.5"
                                                             x-transition:enter-end="opacity-100 translate-y-0"
                                                             x-transition:leave="transition-all duration-700 ease-in-out transform absolute top-0 left-0 w-full pointer-events-none"
                                                             x-transition:leave-start="opacity-100 translate-y-0"
                                                             x-transition:leave-end="opacity-0 -translate-y-3.5">
                                                            <h4 class="text-xs font-bold text-slate-900 mt-1 leading-snug">
                                                                {{ $ag->agenda }}
                                                            </h4>

                                                            <p class="text-[11px] text-slate-500 mt-0.5 flex items-center space-x-1">
                                                                <svg class="w-3 h-3 text-rose-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                                                @if($ag->link && $ag->link !== '-')
                                                                    <a href="{{ $ag->link }}" target="_blank" rel="noopener noreferrer" class="hover:underline text-blue-600 font-medium truncate">
                                                                        {{ $ag->lokasi }}
                                                                    </a>
                                                                @else
                                                                    <span class="truncate">{{ $ag->lokasi }}</span>
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="flex-shrink-0 text-right">
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold border {{ $agStatusClass }}">
                                                        {{ $ag->status }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="pt-2 border-t border-slate-200/60 flex flex-col sm:flex-row sm:items-center justify-between text-[10px] text-slate-500 gap-1.5">
                                                <div class="flex items-center space-x-1.5 flex-wrap gap-1">
                                                    @if($ag->reporter)
                                                        <span class="bg-white px-1.5 py-0.5 rounded border border-slate-200 font-bold text-slate-700">
                                                            🎤 {{ $ag->reporter }}
                                                        </span>
                                                    @endif
                                                    @if($ag->kamerawan)
                                                        <span class="bg-white px-1.5 py-0.5 rounded border border-slate-200 font-bold text-slate-700">
                                                            📹 {{ $ag->kamerawan }}
                                                        </span>
                                                    @endif
                                                    @if($ag->driver && $ag->driver !== '-')
                                                        <span class="bg-white px-1.5 py-0.5 rounded border border-slate-200 font-semibold text-slate-600">
                                                            🚗 {{ $ag->driver }}
                                                        </span>
                                                    @endif
                                                    @if($ag->kendaraan && $ag->kendaraan !== '-')
                                                        <span class="bg-cyan-50 text-cyan-800 font-mono px-1.5 py-0.5 rounded border border-cyan-200 font-bold">
                                                            {{ $ag->kendaraan }}
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($ag->catatan)
                                                    <p class="text-[10px] text-amber-900 bg-amber-50/80 px-2 py-0.5 rounded border border-amber-200/80 italic line-clamp-1">
                                                        Catatan EIC: {{ $ag->catatan }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <!-- Slot 2: Kotak tetap permanen -->
                            @if($agendaLiputan->count() > 1)
                            <div class="p-3 rounded-xl border border-slate-200 hover:border-blue-300 hover:shadow-sm transition-all bg-slate-50/50 min-h-[142px] relative overflow-hidden flex flex-col justify-between">
                                @foreach($agendaLiputan as $ag)
                                    @if($loop->index % 2 === 1)
                                        @php
                                            $agTglStr = $ag->tanggal ? (is_string($ag->tanggal) ? substr($ag->tanggal, 0, 10) : $ag->tanggal->format('Y-m-d')) : '';
                                            $agCrewAll = strtolower($ag->reporter . ' ' . $ag->kamerawan . ' ' . $ag->driver . ' ' . $ag->pic);
                                            $agJenisVal = $ag->jenis ?: 'Berita Harian';
                                            $agPrioClass = match($ag->prioritas) {
                                                'Mendesak' => 'bg-rose-100 text-rose-700 border-rose-200',
                                                'Penting' => 'bg-amber-100 text-amber-800 border-amber-200',
                                                default => 'bg-slate-100 text-slate-700 border-slate-200',
                                            };
                                            $agStatusClass = match($ag->status) {
                                                'Selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                                'On Air' => 'bg-rose-100 text-rose-800 border-rose-300 animate-pulse',
                                                'Proses' => 'bg-blue-100 text-blue-800 border-blue-300',
                                                default => 'bg-slate-100 text-slate-700 border-slate-200',
                                            };
                                        @endphp
                                        <div x-show="activeSlide === {{ (int)($loop->index / 2) }}"
                                             x-transition:enter="transition-opacity duration-700 ease-out"
                                             x-transition:enter-start="opacity-0"
                                             x-transition:enter-end="opacity-100"
                                             x-transition:leave="transition-opacity duration-700 ease-in absolute inset-0 p-3 pointer-events-none"
                                             x-transition:leave-start="opacity-100"
                                             x-transition:leave-end="opacity-0"
                                             class="flex flex-col justify-between h-full gap-2">
                                            <div class="flex items-start justify-between gap-2">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center space-x-1.5 flex-wrap gap-y-1">
                                                        <span class="font-mono text-[10px] font-bold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200">
                                                            {{ $ag->kode ?: 'LP-' . $ag->id }}
                                                        </span>
                                                        <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">
                                                            {{ $ag->waktu }} @if($ag->durasi_label) &bull; {{ $ag->durasi_label }} @endif
                                                        </span>
                                                        <span class="text-[10px] font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200">
                                                            {{ $ag->jenis ?: 'Berita Harian' }}
                                                        </span>
                                                        @if($ag->prioritas && $ag->prioritas !== 'Biasa')
                                                            <span class="text-[10px] font-extrabold px-2 py-0.5 rounded border {{ $agPrioClass }}">
                                                                {{ $ag->prioritas }}
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <!-- Slide HANYA pada Tulisan Agenda & Lokasi (Halus & Profesional) -->
                                                    <div class="overflow-hidden relative my-0.5 min-h-[38px]">
                                                        <div x-show="activeSlide === {{ (int)($loop->index / 2) }}"
                                                             x-transition:enter="transition-all duration-700 ease-out transform"
                                                             x-transition:enter-start="opacity-0 translate-y-3.5"
                                                             x-transition:enter-end="opacity-100 translate-y-0"
                                                             x-transition:leave="transition-all duration-700 ease-in-out transform absolute top-0 left-0 w-full pointer-events-none"
                                                             x-transition:leave-start="opacity-100 translate-y-0"
                                                             x-transition:leave-end="opacity-0 -translate-y-3.5">
                                                            <h4 class="text-xs font-bold text-slate-900 mt-1 leading-snug">
                                                                {{ $ag->agenda }}
                                                            </h4>

                                                            <p class="text-[11px] text-slate-500 mt-0.5 flex items-center space-x-1">
                                                                <svg class="w-3 h-3 text-rose-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                                                @if($ag->link && $ag->link !== '-')
                                                                    <a href="{{ $ag->link }}" target="_blank" rel="noopener noreferrer" class="hover:underline text-blue-600 font-medium truncate">
                                                                        {{ $ag->lokasi }}
                                                                    </a>
                                                                @else
                                                                    <span class="truncate">{{ $ag->lokasi }}</span>
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="flex-shrink-0 text-right">
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold border {{ $agStatusClass }}">
                                                        {{ $ag->status }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="pt-2 border-t border-slate-200/60 flex flex-col sm:flex-row sm:items-center justify-between text-[10px] text-slate-500 gap-1.5">
                                                <div class="flex items-center space-x-1.5 flex-wrap gap-1">
                                                    @if($ag->reporter)
                                                        <span class="bg-white px-1.5 py-0.5 rounded border border-slate-200 font-bold text-slate-700">
                                                            🎤 {{ $ag->reporter }}
                                                        </span>
                                                    @endif
                                                    @if($ag->kamerawan)
                                                        <span class="bg-white px-1.5 py-0.5 rounded border border-slate-200 font-bold text-slate-700">
                                                            📹 {{ $ag->kamerawan }}
                                                        </span>
                                                    @endif
                                                    @if($ag->driver && $ag->driver !== '-')
                                                        <span class="bg-white px-1.5 py-0.5 rounded border border-slate-200 font-semibold text-slate-600">
                                                            🚗 {{ $ag->driver }}
                                                        </span>
                                                    @endif
                                                    @if($ag->kendaraan && $ag->kendaraan !== '-')
                                                        <span class="bg-cyan-50 text-cyan-800 font-mono px-1.5 py-0.5 rounded border border-cyan-200 font-bold">
                                                            {{ $ag->kendaraan }}
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($ag->catatan)
                                                    <p class="text-[10px] text-amber-900 bg-amber-50/80 px-2 py-0.5 rounded border border-amber-200/80 italic line-clamp-1">
                                                        Catatan EIC: {{ $ag->catatan }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                @if($agendaLiputan->count() % 2 === 1)
                                    @php
                                        $agOdd = $agendaLiputan->first();
                                        $agTglStr = $agOdd->tanggal ? (is_string($agOdd->tanggal) ? substr($agOdd->tanggal, 0, 10) : $agOdd->tanggal->format('Y-m-d')) : '';
                                        $agCrewAll = strtolower($agOdd->reporter . ' ' . $agOdd->kamerawan . ' ' . $agOdd->driver . ' ' . $agOdd->pic);
                                        $agJenisVal = $agOdd->jenis ?: 'Berita Harian';
                                        $agPrioClass = match($agOdd->prioritas) {
                                            'Mendesak' => 'bg-rose-100 text-rose-700 border-rose-200',
                                            'Penting' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            default => 'bg-slate-100 text-slate-700 border-slate-200',
                                        };
                                        $agStatusClass = match($agOdd->status) {
                                            'Selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                            'On Air' => 'bg-rose-100 text-rose-800 border-rose-300 animate-pulse',
                                            'Proses' => 'bg-blue-100 text-blue-800 border-blue-300',
                                            default => 'bg-slate-100 text-slate-700 border-slate-200',
                                        };
                                    @endphp
                                    <div x-show="activeSlide === {{ (int) ceil($agendaLiputan->count() / 2) - 1 }}"
                                         x-transition:enter="transition-opacity duration-700 ease-out"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         x-transition:leave="transition-opacity duration-700 ease-in absolute inset-0 p-3 pointer-events-none"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         class="flex flex-col justify-between h-full gap-2">
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center space-x-1.5 flex-wrap gap-y-1">
                                                    <span class="font-mono text-[10px] font-bold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200">
                                                        {{ $agOdd->kode ?: 'LP-' . $agOdd->id }}
                                                    </span>
                                                    <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">
                                                        {{ $agOdd->waktu }} @if($agOdd->durasi_label) &bull; {{ $agOdd->durasi_label }} @endif
                                                    </span>
                                                    <span class="text-[10px] font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200">
                                                        {{ $agOdd->jenis ?: 'Berita Harian' }}
                                                    </span>
                                                    @if($agOdd->prioritas && $agOdd->prioritas !== 'Biasa')
                                                        <span class="text-[10px] font-extrabold px-2 py-0.5 rounded border {{ $agPrioClass }}">
                                                            {{ $agOdd->prioritas }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- Slide HANYA pada Tulisan Agenda & Lokasi (Halus & Profesional) -->
                                                <div class="overflow-hidden relative my-0.5 min-h-[38px]">
                                                    <div x-show="activeSlide === {{ (int) ceil($agendaLiputan->count() / 2) - 1 }}"
                                                         x-transition:enter="transition-all duration-700 ease-out transform"
                                                         x-transition:enter-start="opacity-0 translate-y-3.5"
                                                         x-transition:enter-end="opacity-100 translate-y-0"
                                                         x-transition:leave="transition-all duration-700 ease-in-out transform absolute top-0 left-0 w-full pointer-events-none"
                                                         x-transition:leave-start="opacity-100 translate-y-0"
                                                         x-transition:leave-end="opacity-0 -translate-y-3.5">
                                                        <h4 class="text-xs font-bold text-slate-900 mt-1 leading-snug">
                                                            {{ $agOdd->agenda }}
                                                        </h4>

                                                        <p class="text-[11px] text-slate-500 mt-0.5 flex items-center space-x-1">
                                                            <svg class="w-3 h-3 text-rose-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                                            @if($agOdd->link && $agOdd->link !== '-')
                                                                <a href="{{ $agOdd->link }}" target="_blank" rel="noopener noreferrer" class="hover:underline text-blue-600 font-medium truncate">
                                                                    {{ $agOdd->lokasi }}
                                                                </a>
                                                            @else
                                                                <span class="truncate">{{ $agOdd->lokasi }}</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex-shrink-0 text-right">
                                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold border {{ $agStatusClass }}">
                                                    {{ $agOdd->status }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="pt-2 border-t border-slate-200/60 flex flex-col sm:flex-row sm:items-center justify-between text-[10px] text-slate-500 gap-1.5">
                                            <div class="flex items-center space-x-1.5 flex-wrap gap-1">
                                                @if($agOdd->reporter)
                                                    <span class="bg-white px-1.5 py-0.5 rounded border border-slate-200 font-bold text-slate-700">
                                                        🎤 {{ $agOdd->reporter }}
                                                    </span>
                                                @endif
                                                @if($agOdd->kamerawan)
                                                    <span class="bg-white px-1.5 py-0.5 rounded border border-slate-200 font-bold text-slate-700">
                                                        📹 {{ $agOdd->kamerawan }}
                                                    </span>
                                                @endif
                                                @if($agOdd->driver && $agOdd->driver !== '-')
                                                    <span class="bg-white px-1.5 py-0.5 rounded border border-slate-200 font-semibold text-slate-600">
                                                        🚗 {{ $agOdd->driver }}
                                                    </span>
                                                @endif
                                                @if($agOdd->kendaraan && $agOdd->kendaraan !== '-')
                                                    <span class="bg-cyan-50 text-cyan-800 font-mono px-1.5 py-0.5 rounded border border-cyan-200 font-bold">
                                                        {{ $agOdd->kendaraan }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if($agOdd->catatan)
                                                <p class="text-[10px] text-amber-900 bg-amber-50/80 px-2 py-0.5 rounded border border-amber-200/80 italic line-clamp-1">
                                                    Catatan EIC: {{ $agOdd->catatan }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @endif
                        </div>

                        <!-- 2. MODE SEMUA / HASIL FILTER: Daftar lengkap -->
                        <div x-show="viewMode === 'all' || isFiltered()" class="space-y-2.5">
                            @foreach($agendaLiputan as $ag)
                                @php
                                    $agTglStr = $ag->tanggal ? (is_string($ag->tanggal) ? substr($ag->tanggal, 0, 10) : $ag->tanggal->format('Y-m-d')) : '';
                                    $agCrewAll = strtolower($ag->reporter . ' ' . $ag->kamerawan . ' ' . $ag->driver . ' ' . $ag->pic);
                                    $agJenisVal = $ag->jenis ?: 'Berita Harian';
                                    $agPrioClass = match($ag->prioritas) {
                                        'Mendesak' => 'bg-rose-100 text-rose-700 border-rose-200',
                                        'Penting' => 'bg-amber-100 text-amber-800 border-amber-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200',
                                    };
                                    $agStatusClass = match($ag->status) {
                                        'Selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                        'On Air' => 'bg-rose-100 text-rose-800 border-rose-300 animate-pulse',
                                        'Proses' => 'bg-blue-100 text-blue-800 border-blue-300',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200',
                                    };
                                @endphp
                                <div x-show="(!selectedHari || selectedHari === 'semua' || selectedHari === '{{ $agTglStr }}') && 
                                             (!selectedCrew || '{{ $agCrewAll }}'.includes(selectedCrew.toLowerCase())) &&
                                             (!selectedJenis || '{{ $agJenisVal }}' === selectedJenis) &&
                                             (!searchLiputan || '{{ strtolower($ag->agenda . ' ' . $ag->lokasi . ' ' . $ag->kode . ' ' . $agCrewAll) }}'.includes(searchLiputan.toLowerCase()))"
                                     class="p-3 rounded-xl border border-slate-200 hover:border-blue-300 hover:shadow-sm transition-all bg-slate-50/50 flex flex-col justify-between gap-2">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-1.5 flex-wrap gap-y-1">
                                                <span class="font-mono text-[10px] font-bold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200">
                                                    {{ $ag->kode ?: 'LP-' . $ag->id }}
                                                </span>
                                                <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">
                                                    {{ $ag->waktu }} @if($ag->durasi_label) &bull; {{ $ag->durasi_label }} @endif
                                                </span>
                                                <span class="text-[10px] font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200">
                                                    {{ $ag->jenis ?: 'Berita Harian' }}
                                                </span>
                                                @if($ag->prioritas && $ag->prioritas !== 'Biasa')
                                                    <span class="text-[10px] font-extrabold px-2 py-0.5 rounded border {{ $agPrioClass }}">
                                                        {{ $ag->prioritas }}
                                                    </span>
                                                @endif
                                            </div>

                                            <h4 class="text-xs font-bold text-slate-900 mt-1.5 leading-snug">
                                                {{ $ag->agenda }}
                                            </h4>

                                            <p class="text-[11px] text-slate-500 mt-0.5 flex items-center space-x-1">
                                                <svg class="w-3 h-3 text-rose-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                                @if($ag->link && $ag->link !== '-')
                                                    <a href="{{ $ag->link }}" target="_blank" rel="noopener noreferrer" class="hover:underline text-blue-600 font-medium truncate">
                                                        {{ $ag->lokasi }}
                                                    </a>
                                                @else
                                                    <span class="truncate">{{ $ag->lokasi }}</span>
                                                @endif
                                            </p>
                                        </div>

                                        <div class="flex-shrink-0 text-right">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold border {{ $agStatusClass }}">
                                                {{ $ag->status }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="pt-2 border-t border-slate-200/60 flex flex-col sm:flex-row sm:items-center justify-between text-[10px] text-slate-500 gap-1.5">
                                        <div class="flex items-center space-x-1.5 flex-wrap gap-1">
                                            @if($ag->reporter)
                                                <span class="bg-white px-1.5 py-0.5 rounded border border-slate-200 font-bold text-slate-700">
                                                    🎤 {{ $ag->reporter }}
                                                </span>
                                            @endif
                                            @if($ag->kamerawan)
                                                <span class="bg-white px-1.5 py-0.5 rounded border border-slate-200 font-bold text-slate-700">
                                                    📹 {{ $ag->kamerawan }}
                                                </span>
                                            @endif
                                            @if($ag->driver && $ag->driver !== '-')
                                                <span class="bg-white px-1.5 py-0.5 rounded border border-slate-200 font-semibold text-slate-600">
                                                    🚗 {{ $ag->driver }}
                                                </span>
                                            @endif
                                            @if($ag->kendaraan && $ag->kendaraan !== '-')
                                                <span class="bg-cyan-50 text-cyan-800 font-mono px-1.5 py-0.5 rounded border border-cyan-200 font-bold">
                                                    {{ $ag->kendaraan }}
                                                </span>
                                            @endif
                                        </div>
                                        @if($ag->catatan)
                                            <p class="text-[10px] text-amber-900 bg-amber-50/80 px-2 py-0.5 rounded border border-amber-200/80 italic line-clamp-1">
                                                Catatan EIC: {{ $ag->catatan }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Peringatan & Catatan Redaksi (5 Kolom) -->
        <div class="lg:col-span-5 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between"
             x-data="{
                 activeSlide: 0,
                 totalSlides: {{ max(1, (int) ceil($catatanPeringatan->count() / 2)) }},
                 autoSlide: true,
                 viewMode: 'slide',
                 timer: null,
                 init() {
                     if (this.totalSlides > 1) {
                         this.timer = setInterval(() => {
                             if (this.autoSlide && this.viewMode === 'slide') {
                                 this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
                             }
                         }, 8500);
                     }
                 },
                 pauseSlide() { this.autoSlide = false; },
                 resumeSlide() { this.autoSlide = true; },
                 nextSlide() { this.activeSlide = (this.activeSlide + 1) % this.totalSlides; },
                 prevSlide() { this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides; },
                 goToSlide(i) { this.activeSlide = i; }
             }"
             @mouseenter="pauseSlide()"
             @mouseleave="resumeSlide()">
            <div>
                <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100 flex-wrap gap-2">
                    <div class="flex items-center space-x-2">
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Peringatan &amp; Catatan Redaksi</h3>
                        <template x-if="totalSlides > 1">
                            <span class="text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full font-mono"
                                  x-text="viewMode === 'all' ? 'Semua ({{ $catatanPeringatan->count() }})' : 'Slide ' + (activeSlide + 1) + '/' + totalSlides"></span>
                        </template>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <template x-if="totalSlides > 1 && viewMode === 'slide'">
                            <div class="flex items-center space-x-1">
                                @for($s = 0; $s < max(1, (int) ceil($catatanPeringatan->count() / 2)); $s++)
                                    <button type="button" @click="goToSlide({{ $s }})" 
                                            class="h-1.5 rounded-full transition-all duration-300 cursor-pointer"
                                            :class="activeSlide === {{ $s }} ? 'w-4 bg-amber-500' : 'w-1.5 bg-slate-200 hover:bg-slate-300'"></button>
                                @endfor
                                <button type="button" @click="prevSlide()" class="p-1 text-slate-400 hover:text-slate-600 transition-colors cursor-pointer" title="Sebelumnya">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                <button type="button" @click="nextSlide()" class="p-1 text-slate-400 hover:text-slate-600 transition-colors cursor-pointer" title="Berikutnya">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </template>
                        <button type="button" @click="viewMode = (viewMode === 'slide' ? 'all' : 'slide')"
                                class="px-1.5 py-0.5 rounded text-[10px] font-bold transition-colors border cursor-pointer"
                                :class="viewMode === 'all' ? 'bg-[#00264A] text-white border-[#00264A]' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'">
                            <span x-text="viewMode === 'all' ? 'Slide' : 'Semua'"></span>
                        </button>
                        @if(!$isKepalaStasiun)
                        <a href="{{ route('tvri.berita.manage') }}?tab=catatan" class="text-xs font-bold text-blue-600 hover:underline">+ Kelola</a>
                        @endif
                    </div>
                </div>

                <!-- Slide Mode: 2 Kotak Tetap Permanen dengan Teks Slide -->
                <div x-show="viewMode === 'slide'" class="space-y-2">
                    <!-- Slot 1: Kotak tetap permanen -->
                    <div class="min-h-[48px] relative overflow-hidden rounded-xl border border-slate-200/80 bg-slate-50/50">
                        @foreach($catatanPeringatan as $cp)
                            @if($loop->index % 2 === 0)
                                <div x-show="activeSlide === {{ (int)($loop->index / 2) }}"
                                     x-transition:enter="transition-opacity duration-700 ease-out"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition-opacity duration-700 ease-in absolute inset-0 pointer-events-none"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0"
                                     class="w-full h-full flex items-start space-x-2.5 p-2.5 rounded-xl border text-xs {{ $cp->tipe === 'warning' ? 'bg-amber-50/80 border-amber-200 text-amber-900' : ($cp->tipe === 'info' ? 'bg-blue-50/80 border-blue-200 text-blue-900' : 'bg-emerald-50/80 border-emerald-200 text-emerald-900') }}">
                                    <span class="text-sm flex-shrink-0">
                                        @if($cp->tipe === 'warning') ⚠️ @elseif($cp->tipe === 'info') ℹ️ @else ✅ @endif
                                    </span>
                                    <div class="overflow-hidden relative flex-1 min-h-[36px]">
                                        <span x-show="activeSlide === {{ (int)($loop->index / 2) }}"
                                              x-transition:enter="transition-all duration-700 ease-out transform"
                                              x-transition:enter-start="opacity-0 translate-y-3.5"
                                              x-transition:enter-end="opacity-100 translate-y-0"
                                              x-transition:leave="transition-all duration-700 ease-in-out transform absolute top-0 left-0 w-full pointer-events-none"
                                              x-transition:leave-start="opacity-100 translate-y-0"
                                              x-transition:leave-end="opacity-0 -translate-y-3.5"
                                              class="font-semibold leading-relaxed block">{{ $cp->pesan }}</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Slot 2: Kotak tetap permanen -->
                    @if($catatanPeringatan->count() > 1)
                    <div class="min-h-[48px] relative overflow-hidden rounded-xl border border-slate-200/80 bg-slate-50/50">
                        @foreach($catatanPeringatan as $cp)
                            @if($loop->index % 2 === 1)
                                <div x-show="activeSlide === {{ (int)($loop->index / 2) }}"
                                     x-transition:enter="transition-opacity duration-700 ease-out"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition-opacity duration-700 ease-in absolute inset-0 pointer-events-none"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0"
                                     class="w-full h-full flex items-start space-x-2.5 p-2.5 rounded-xl border text-xs {{ $cp->tipe === 'warning' ? 'bg-amber-50/80 border-amber-200 text-amber-900' : ($cp->tipe === 'info' ? 'bg-blue-50/80 border-blue-200 text-blue-900' : 'bg-emerald-50/80 border-emerald-200 text-emerald-900') }}">
                                    <span class="text-sm flex-shrink-0">
                                        @if($cp->tipe === 'warning') ⚠️ @elseif($cp->tipe === 'info') ℹ️ @else ✅ @endif
                                    </span>
                                    <div class="overflow-hidden relative flex-1 min-h-[36px]">
                                        <span x-show="activeSlide === {{ (int)($loop->index / 2) }}"
                                              x-transition:enter="transition-all duration-700 ease-out transform"
                                              x-transition:enter-start="opacity-0 translate-y-3.5"
                                              x-transition:enter-end="opacity-100 translate-y-0"
                                              x-transition:leave="transition-all duration-700 ease-in-out transform absolute top-0 left-0 w-full pointer-events-none"
                                              x-transition:leave-start="opacity-100 translate-y-0"
                                              x-transition:leave-end="opacity-0 -translate-y-3.5"
                                              class="font-semibold leading-relaxed block">{{ $cp->pesan }}</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        @if($catatanPeringatan->count() % 2 === 1)
                            @php $cpFirst = $catatanPeringatan->first(); @endphp
                            <div x-show="activeSlide === {{ (int) ceil($catatanPeringatan->count() / 2) - 1 }}"
                                 x-transition:enter="transition-opacity duration-700 ease-out"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition-opacity duration-700 ease-in absolute inset-0 pointer-events-none"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="w-full h-full flex items-start space-x-2.5 p-2.5 rounded-xl border text-xs {{ $cpFirst->tipe === 'warning' ? 'bg-amber-50/80 border-amber-200 text-amber-900' : ($cpFirst->tipe === 'info' ? 'bg-blue-50/80 border-blue-200 text-blue-900' : 'bg-emerald-50/80 border-emerald-200 text-emerald-900') }}">
                                <span class="text-sm flex-shrink-0">
                                    @if($cpFirst->tipe === 'warning') ⚠️ @elseif($cpFirst->tipe === 'info') ℹ️ @else ✅ @endif
                                </span>
                                <div class="overflow-hidden relative flex-1 min-h-[36px]">
                                    <span x-show="activeSlide === {{ (int) ceil($catatanPeringatan->count() / 2) - 1 }}"
                                          x-transition:enter="transition-all duration-700 ease-out transform"
                                          x-transition:enter-start="opacity-0 translate-y-3.5"
                                          x-transition:enter-end="opacity-100 translate-y-0"
                                          x-transition:leave="transition-all duration-700 ease-in-out transform absolute top-0 left-0 w-full pointer-events-none"
                                          x-transition:leave-start="opacity-100 translate-y-0"
                                          x-transition:leave-end="opacity-0 -translate-y-3.5"
                                          class="font-semibold leading-relaxed block">{{ $cpFirst->pesan }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Mode Semua -->
                <div x-show="viewMode === 'all'" class="space-y-2">
                    @foreach($catatanPeringatan as $cp)
                        <div class="flex items-start space-x-2.5 p-2.5 rounded-xl border text-xs {{ $cp->tipe === 'warning' ? 'bg-amber-50/80 border-amber-200 text-amber-900' : ($cp->tipe === 'info' ? 'bg-blue-50/80 border-blue-200 text-blue-900' : 'bg-emerald-50/80 border-emerald-200 text-emerald-900') }}">
                            <span class="text-sm flex-shrink-0">
                                @if($cp->tipe === 'warning') ⚠️ @elseif($cp->tipe === 'info') ℹ️ @else ✅ @endif
                            </span>
                            <span class="font-semibold leading-relaxed">{{ $cp->pesan }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-4 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Standar Jurnalistik TVRI Maluku</span>
                <span class="text-emerald-600 font-bold">Siap Siar</span>
            </div>
        </div>
    </div>

    <!-- ===== SECTION: KOTAK MENU PRODUKSI PAKET, MONITORING & DOKUMENTASI BERITA HARIAN ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" id="kotak-menu-berita">
        <!-- 1. KOTAK MENU: PRODUKSI PAKET -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between scroll-mt-24" id="produksi-paket"
             x-data="{
                 activeSlide: 0,
                 totalSlides: {{ max(1, (int) ceil(count($produksis) / 2)) }},
                 autoSlide: true,
                 viewMode: 'slide',
                 timer: null,
                 init() {
                     if (this.totalSlides > 1) {
                         this.timer = setInterval(() => {
                             if (this.autoSlide && this.viewMode === 'slide') {
                                 this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
                             }
                         }, 8200);
                     }
                 },
                 pauseSlide() { this.autoSlide = false; },
                 resumeSlide() { this.autoSlide = true; },
                 nextSlide() { this.activeSlide = (this.activeSlide + 1) % this.totalSlides; },
                 prevSlide() { this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides; },
                 goToSlide(i) { this.activeSlide = i; }
             }"
             @mouseenter="pauseSlide()"
             @mouseleave="resumeSlide()">
            <div>
                <div class="flex items-center justify-between mb-3 pb-2.5 border-b border-slate-100 flex-wrap gap-2">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold text-sm">
                            📦
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Produksi Paket</h3>
                            <p class="text-[11px] text-slate-500 font-medium">Paket berita &amp; feature siap tayang</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <span class="text-[10px] font-black text-amber-800 bg-amber-100/80 px-2.5 py-0.5 rounded-full border border-amber-200">
                            {{ count($produksis) }} Paket
                        </span>
                        @if(!$isKepalaStasiun)
                        <a href="{{ route('tvri.berita.manage', ['tab' => 'produksi']) }}" 
                           class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-xl text-xs font-bold bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200 transition-colors">
                            <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Kelola</span>
                        </a>
                        @else
                        <span class="text-[9px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">Monitoring Pimpinan</span>
                        @endif
                    </div>
                </div>

                <!-- KPI Mini Pills Produksi Paket -->
                <div class="grid grid-cols-3 gap-2 mb-3 text-center">
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[9px] uppercase font-bold text-slate-400 block">Editing</span>
                        <span class="text-xs font-black text-amber-700">{{ $produksis->where('status', 'Editing')->count() }}</span>
                    </div>
                    <div class="p-2 rounded-xl bg-blue-50/70 border border-blue-100">
                        <span class="text-[9px] uppercase font-bold text-blue-600 block">Review EIC</span>
                        <span class="text-xs font-black text-blue-700">{{ $produksis->where('status', 'Review EIC')->count() }}</span>
                    </div>
                    <div class="p-2 rounded-xl bg-emerald-50/70 border border-emerald-100">
                        <span class="text-[9px] uppercase font-bold text-emerald-600 block">Siap Tayang</span>
                        <span class="text-xs font-black text-emerald-700">{{ $produksis->whereIn('status', ['Siap Tayang', 'Selesai'])->count() }}</span>
                    </div>
                </div>

                <!-- Slide Mini Control Bar -->
                <div class="flex items-center justify-between py-1 px-2 bg-amber-50/60 rounded-lg border border-amber-100 text-[10px] text-amber-900 mb-2.5">
                    <div class="flex items-center space-x-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500" :class="autoSlide && viewMode === 'slide' ? 'animate-pulse' : ''"></span>
                        <span class="font-bold font-mono" x-text="viewMode === 'all' ? 'Semua ({{ count($produksis) }})' : 'Slide ' + (activeSlide + 1) + '/' + totalSlides"></span>
                        <template x-if="totalSlides > 1 && viewMode === 'slide'">
                            <div class="flex items-center space-x-1 ml-1">
                                @for($s = 0; $s < max(1, (int) ceil(count($produksis) / 2)); $s++)
                                    <button type="button" @click="goToSlide({{ $s }})" 
                                            class="h-1 rounded-full transition-all duration-300 cursor-pointer"
                                            :class="activeSlide === {{ $s }} ? 'w-3.5 bg-amber-600' : 'w-1 bg-amber-300 hover:bg-amber-400'"></button>
                                @endfor
                            </div>
                        </template>
                    </div>
                    <div class="flex items-center space-x-1">
                        <template x-if="totalSlides > 1 && viewMode === 'slide'">
                            <div class="flex items-center space-x-0.5">
                                <button type="button" @click="prevSlide()" class="p-0.5 hover:text-amber-700 text-slate-400 cursor-pointer" title="Sebelumnya">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                <button type="button" @click="nextSlide()" class="p-0.5 hover:text-amber-700 text-slate-400 cursor-pointer" title="Berikutnya">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </template>
                        <button type="button" @click="viewMode = (viewMode === 'slide' ? 'all' : 'slide')" 
                                class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-white border border-amber-200 text-amber-800 hover:bg-amber-100 transition-colors cursor-pointer">
                            <span x-text="viewMode === 'all' ? 'Slide' : 'Semua'"></span>
                        </button>
                    </div>
                </div>

                <!-- List Items Produksi Paket -->
                <div class="space-y-2.5 max-h-[380px] overflow-y-auto pr-1">
                    @if(count($produksis) === 0)
                        <p class="text-xs text-slate-400 py-6 text-center">Belum ada paket produksi terdaftar.</p>
                    @else
                        <!-- Mode Slide: 2 Kotak Tetap Permanen dengan Teks yang Berganti Slide -->
                        <div x-show="viewMode === 'slide'" class="space-y-2.5">
                            <!-- Slot 1: Kotak tetap permanen -->
                            <div class="p-3 rounded-xl border border-slate-200 hover:border-amber-300 hover:shadow-sm transition-all bg-slate-50/50 min-h-[140px] relative overflow-hidden flex flex-col justify-between">
                                @foreach($produksis as $pkt)
                                    @if($loop->index % 2 === 0)
                                        <div x-show="activeSlide === {{ (int)($loop->index / 2) }}"
                                             x-transition:enter="transition-opacity duration-700 ease-out"
                                             x-transition:enter-start="opacity-0"
                                             x-transition:enter-end="opacity-100"
                                             x-transition:leave="transition-opacity duration-700 ease-in absolute inset-0 p-3 pointer-events-none"
                                             x-transition:leave-start="opacity-100"
                                             x-transition:leave-end="opacity-0"
                                             class="flex flex-col justify-between h-full gap-2">
                                            <div>
                                                <div class="flex items-center justify-between gap-1 mb-1.5 flex-wrap">
                                                    <div class="flex items-center space-x-1.5">
                                                        <span class="font-mono text-[10px] font-bold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200">
                                                            {{ $pkt->kode }}
                                                        </span>
                                                        <span class="text-[10px] font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200">
                                                            {{ $pkt->program }}
                                                        </span>
                                                    </div>
                                                    <span class="text-[10px] font-extrabold px-2 py-0.5 rounded border {{ $pkt->status === 'Siap Tayang' || $pkt->status === 'Selesai' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : ($pkt->status === 'On Air' ? 'bg-rose-100 text-rose-800 border-rose-200 animate-pulse' : 'bg-amber-100 text-amber-800 border-amber-200') }}">
                                                        {{ $pkt->status }}
                                                    </span>
                                                </div>

                                                <!-- Slide HANYA pada Tulisan Judul Paket (Halus & Profesional) -->
                                                <div class="overflow-hidden relative my-0.5 min-h-[34px]">
                                                    <div x-show="activeSlide === {{ (int)($loop->index / 2) }}"
                                                         x-transition:enter="transition-all duration-700 ease-out transform"
                                                         x-transition:enter-start="opacity-0 translate-y-3.5"
                                                         x-transition:enter-end="opacity-100 translate-y-0"
                                                         x-transition:leave="transition-all duration-700 ease-in-out transform absolute top-0 left-0 w-full pointer-events-none"
                                                         x-transition:leave-start="opacity-100 translate-y-0"
                                                         x-transition:leave-end="opacity-0 -translate-y-3.5">
                                                        <h4 class="text-xs font-bold text-slate-900 leading-snug line-clamp-2">
                                                            {{ $pkt->judul }}
                                                        </h4>
                                                    </div>
                                                </div>

                                                <div class="flex items-center space-x-2 text-[10px] text-slate-500 mt-1.5 flex-wrap gap-y-1">
                                                    <span>⏱️ {{ $pkt->durasi }}</span>
                                                    <span>&bull;</span>
                                                    <span>✍️ {{ $pkt->reporter ?? 'Tim Berita' }}</span>
                                                    @if($pkt->editor)
                                                        <span>&bull;</span>
                                                        <span>✂️ {{ $pkt->editor }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            @if($pkt->video_url)
                                                <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between">
                                                    <span class="text-[9px] text-slate-400 font-semibold">Tersedia Media Video</span>
                                                    <button type="button" onclick="openVideoPlayer('{{ $pkt->video_url }}', '{{ addslashes($pkt->judul) }}')" 
                                                            class="inline-flex items-center space-x-1 text-[10px] font-bold text-blue-600 hover:text-blue-800 cursor-pointer">
                                                        <svg class="w-3 h-3 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                        <span>Putar Paket</span>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <!-- Slot 2: Kotak tetap permanen -->
                            @if(count($produksis) > 1)
                            <div class="p-3 rounded-xl border border-slate-200 hover:border-amber-300 hover:shadow-sm transition-all bg-slate-50/50 min-h-[140px] relative overflow-hidden flex flex-col justify-between">
                                @foreach($produksis as $pkt)
                                    @if($loop->index % 2 === 1)
                                        <div x-show="activeSlide === {{ (int)($loop->index / 2) }}"
                                             x-transition:enter="transition-opacity duration-700 ease-out"
                                             x-transition:enter-start="opacity-0"
                                             x-transition:enter-end="opacity-100"
                                             x-transition:leave="transition-opacity duration-700 ease-in absolute inset-0 p-3 pointer-events-none"
                                             x-transition:leave-start="opacity-100"
                                             x-transition:leave-end="opacity-0"
                                             class="flex flex-col justify-between h-full gap-2">
                                            <div>
                                                <div class="flex items-center justify-between gap-1 mb-1.5 flex-wrap">
                                                    <div class="flex items-center space-x-1.5">
                                                        <span class="font-mono text-[10px] font-bold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200">
                                                            {{ $pkt->kode }}
                                                        </span>
                                                        <span class="text-[10px] font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200">
                                                            {{ $pkt->program }}
                                                        </span>
                                                    </div>
                                                    <span class="text-[10px] font-extrabold px-2 py-0.5 rounded border {{ $pkt->status === 'Siap Tayang' || $pkt->status === 'Selesai' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : ($pkt->status === 'On Air' ? 'bg-rose-100 text-rose-800 border-rose-200 animate-pulse' : 'bg-amber-100 text-amber-800 border-amber-200') }}">
                                                        {{ $pkt->status }}
                                                    </span>
                                                </div>

                                                <!-- Slide HANYA pada Tulisan Judul Paket (Halus & Profesional) -->
                                                <div class="overflow-hidden relative my-0.5 min-h-[34px]">
                                                    <div x-show="activeSlide === {{ (int)($loop->index / 2) }}"
                                                         x-transition:enter="transition-all duration-700 ease-out transform"
                                                         x-transition:enter-start="opacity-0 translate-y-3.5"
                                                         x-transition:enter-end="opacity-100 translate-y-0"
                                                         x-transition:leave="transition-all duration-700 ease-in-out transform absolute top-0 left-0 w-full pointer-events-none"
                                                         x-transition:leave-start="opacity-100 translate-y-0"
                                                         x-transition:leave-end="opacity-0 -translate-y-3.5">
                                                        <h4 class="text-xs font-bold text-slate-900 leading-snug line-clamp-2">
                                                            {{ $pkt->judul }}
                                                        </h4>
                                                    </div>
                                                </div>

                                                <div class="flex items-center space-x-2 text-[10px] text-slate-500 mt-1.5 flex-wrap gap-y-1">
                                                    <span>⏱️ {{ $pkt->durasi }}</span>
                                                    <span>&bull;</span>
                                                    <span>✍️ {{ $pkt->reporter ?? 'Tim Berita' }}</span>
                                                    @if($pkt->editor)
                                                        <span>&bull;</span>
                                                        <span>✂️ {{ $pkt->editor }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            @if($pkt->video_url)
                                                <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between">
                                                    <span class="text-[9px] text-slate-400 font-semibold">Tersedia Media Video</span>
                                                    <button type="button" onclick="openVideoPlayer('{{ $pkt->video_url }}', '{{ addslashes($pkt->judul) }}')" 
                                                            class="inline-flex items-center space-x-1 text-[10px] font-bold text-blue-600 hover:text-blue-800 cursor-pointer">
                                                        <svg class="w-3 h-3 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                        <span>Putar Paket</span>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                                @if(count($produksis) % 2 === 1)
                                    @php $pktFirst = $produksis->first(); @endphp
                                    <div x-show="activeSlide === {{ (int) ceil(count($produksis) / 2) - 1 }}"
                                         x-transition:enter="transition-opacity duration-700 ease-out"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         x-transition:leave="transition-opacity duration-700 ease-in absolute inset-0 p-3 pointer-events-none"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         class="flex flex-col justify-between h-full gap-2">
                                        <div>
                                            <div class="flex items-center justify-between gap-1 mb-1.5 flex-wrap">
                                                <div class="flex items-center space-x-1.5">
                                                    <span class="font-mono text-[10px] font-bold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200">
                                                        {{ $pktFirst->kode }}
                                                    </span>
                                                    <span class="text-[10px] font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200">
                                                        {{ $pktFirst->program }}
                                                    </span>
                                                </div>
                                                <span class="text-[10px] font-extrabold px-2 py-0.5 rounded border {{ $pktFirst->status === 'Siap Tayang' || $pktFirst->status === 'Selesai' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : ($pktFirst->status === 'On Air' ? 'bg-rose-100 text-rose-800 border-rose-200 animate-pulse' : 'bg-amber-100 text-amber-800 border-amber-200') }}">
                                                    {{ $pktFirst->status }}
                                                </span>
                                            </div>

                                            <!-- Slide HANYA pada Tulisan Judul Paket (Halus & Profesional) -->
                                            <div class="overflow-hidden relative my-0.5 min-h-[34px]">
                                                <div x-show="activeSlide === {{ (int) ceil(count($produksis) / 2) - 1 }}"
                                                     x-transition:enter="transition-all duration-700 ease-out transform"
                                                     x-transition:enter-start="opacity-0 translate-y-3.5"
                                                     x-transition:enter-end="opacity-100 translate-y-0"
                                                     x-transition:leave="transition-all duration-700 ease-in-out transform absolute top-0 left-0 w-full pointer-events-none"
                                                     x-transition:leave-start="opacity-100 translate-y-0"
                                                     x-transition:leave-end="opacity-0 -translate-y-3.5">
                                                    <h4 class="text-xs font-bold text-slate-900 leading-snug line-clamp-2">
                                                        {{ $pktFirst->judul }}
                                                    </h4>
                                                </div>
                                            </div>

                                            <div class="flex items-center space-x-2 text-[10px] text-slate-500 mt-1.5 flex-wrap gap-y-1">
                                                <span>⏱️ {{ $pktFirst->durasi }}</span>
                                                <span>&bull;</span>
                                                <span>✍️ {{ $pktFirst->reporter ?? 'Tim Berita' }}</span>
                                                @if($pktFirst->editor)
                                                    <span>&bull;</span>
                                                    <span>✂️ {{ $pktFirst->editor }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        @if($pktFirst->video_url)
                                            <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between">
                                                <span class="text-[9px] text-slate-400 font-semibold">Tersedia Media Video</span>
                                                <button type="button" onclick="openVideoPlayer('{{ $pktFirst->video_url }}', '{{ addslashes($pktFirst->judul) }}')" 
                                                        class="inline-flex items-center space-x-1 text-[10px] font-bold text-blue-600 hover:text-blue-800 cursor-pointer">
                                                    <svg class="w-3 h-3 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                    <span>Putar Paket</span>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            @endif
                        </div>

                        <!-- Mode Semua -->
                        <div x-show="viewMode === 'all'" class="space-y-2.5">
                            @foreach($produksis as $pkt)
                                <div class="p-3 rounded-xl border border-slate-200 hover:border-amber-300 hover:shadow-sm transition-all bg-slate-50/50 flex flex-col justify-between gap-2">
                                    <div>
                                        <div class="flex items-center justify-between gap-1 mb-1.5 flex-wrap">
                                            <div class="flex items-center space-x-1.5">
                                                <span class="font-mono text-[10px] font-bold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200">
                                                    {{ $pkt->kode }}
                                                </span>
                                                <span class="text-[10px] font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200">
                                                    {{ $pkt->program }}
                                                </span>
                                            </div>
                                            <span class="text-[10px] font-extrabold px-2 py-0.5 rounded border {{ $pkt->status === 'Siap Tayang' || $pkt->status === 'Selesai' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : ($pkt->status === 'On Air' ? 'bg-rose-100 text-rose-800 border-rose-200 animate-pulse' : 'bg-amber-100 text-amber-800 border-amber-200') }}">
                                                {{ $pkt->status }}
                                            </span>
                                        </div>
                                        <h4 class="text-xs font-bold text-slate-900 leading-snug line-clamp-2">
                                            {{ $pkt->judul }}
                                        </h4>
                                        <div class="flex items-center space-x-2 text-[10px] text-slate-500 mt-1.5 flex-wrap gap-y-1">
                                            <span>⏱️ {{ $pkt->durasi }}</span>
                                            <span>&bull;</span>
                                            <span>✍️ {{ $pkt->reporter ?? 'Tim Berita' }}</span>
                                            @if($pkt->editor)
                                                <span>&bull;</span>
                                                <span>✂️ {{ $pkt->editor }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($pkt->video_url)
                                        <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between">
                                            <span class="text-[9px] text-slate-400 font-semibold">Tersedia Media Video</span>
                                            <button type="button" onclick="openVideoPlayer('{{ $pkt->video_url }}', '{{ addslashes($pkt->judul) }}')" 
                                                    class="inline-flex items-center space-x-1 text-[10px] font-bold text-blue-600 hover:text-blue-800 cursor-pointer">
                                                <svg class="w-3 h-3 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                <span>Putar Paket</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Alur Pasca Produksi Berita</span>
                <span class="text-amber-700 font-bold">Sinkron Newsroom</span>
            </div>
        </div>

        <!-- 2. KOTAK MENU: MONITORING BERITA HARIAN -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between scroll-mt-24" id="monitoring-berita"
             x-data="{
                 activeSlide: 0,
                 totalSlides: {{ max(1, (int) ceil(count($monitorings) / 2)) }},
                 autoSlide: true,
                 viewMode: 'slide',
                 timer: null,
                 init() {
                     if (this.totalSlides > 1) {
                         this.timer = setInterval(() => {
                             if (this.autoSlide && this.viewMode === 'slide') {
                                 this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
                             }
                         }, 8700);
                     }
                 },
                 pauseSlide() { this.autoSlide = false; },
                 resumeSlide() { this.autoSlide = true; },
                 nextSlide() { this.activeSlide = (this.activeSlide + 1) % this.totalSlides; },
                 prevSlide() { this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides; },
                 goToSlide(i) { this.activeSlide = i; }
             }"
             @mouseenter="pauseSlide()"
             @mouseleave="resumeSlide()">
            <div>
                <div class="flex items-center justify-between mb-3 pb-2.5 border-b border-slate-100 flex-wrap gap-2">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-sm">
                            📡
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Monitoring Berita Harian</h3>
                            <p class="text-[11px] text-slate-500 font-medium">Pemantauan on-air &amp; siaran harian</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <span class="text-[10px] font-black text-blue-800 bg-blue-100/80 px-2.5 py-0.5 rounded-full border border-blue-200">
                            {{ count($monitorings) }} Siaran
                        </span>
                        @if(!$isKepalaStasiun)
                        <a href="{{ route('tvri.berita.manage', ['tab' => 'monitoring']) }}" 
                           class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-xl text-xs font-bold bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 transition-colors">
                            <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Kelola</span>
                        </a>
                        @else
                        <span class="text-[9px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">Monitoring Pimpinan</span>
                        @endif
                    </div>
                </div>

                <!-- KPI Mini Pills Monitoring -->
                <div class="grid grid-cols-3 gap-2 mb-3 text-center">
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[9px] uppercase font-bold text-slate-400 block">Jadwal On-Air</span>
                        <span class="text-xs font-black text-[#00264A]">{{ $monitorings->count() }} Slot</span>
                    </div>
                    <div class="p-2 rounded-xl bg-emerald-50/70 border border-emerald-100">
                        <span class="text-[9px] uppercase font-bold text-emerald-600 block">Kualitas A/V</span>
                        <span class="text-xs font-black text-emerald-700">{{ $monitorings->where('kualitas_av', 'Sangat Baik')->count() }} Prima</span>
                    </div>
                    <div class="p-2 rounded-xl bg-cyan-50/70 border border-cyan-100">
                        <span class="text-[9px] uppercase font-bold text-cyan-600 block">Status Siar</span>
                        <span class="text-xs font-black text-cyan-700">100% On Time</span>
                    </div>
                </div>

                <!-- Slide Mini Control Bar -->
                <div class="flex items-center justify-between py-1 px-2 bg-blue-50/60 rounded-lg border border-blue-100 text-[10px] text-blue-900 mb-2.5">
                    <div class="flex items-center space-x-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500" :class="autoSlide && viewMode === 'slide' ? 'animate-pulse' : ''"></span>
                        <span class="font-bold font-mono" x-text="viewMode === 'all' ? 'Semua ({{ count($monitorings) }})' : 'Slide ' + (activeSlide + 1) + '/' + totalSlides"></span>
                        <template x-if="totalSlides > 1 && viewMode === 'slide'">
                            <div class="flex items-center space-x-1 ml-1">
                                @for($s = 0; $s < max(1, (int) ceil(count($monitorings) / 2)); $s++)
                                    <button type="button" @click="goToSlide({{ $s }})" 
                                            class="h-1 rounded-full transition-all duration-300 cursor-pointer"
                                            :class="activeSlide === {{ $s }} ? 'w-3.5 bg-blue-600' : 'w-1 bg-blue-300 hover:bg-blue-400'"></button>
                                @endfor
                            </div>
                        </template>
                    </div>
                    <div class="flex items-center space-x-1">
                        <template x-if="totalSlides > 1 && viewMode === 'slide'">
                            <div class="flex items-center space-x-0.5">
                                <button type="button" @click="prevSlide()" class="p-0.5 hover:text-blue-700 text-slate-400 cursor-pointer" title="Sebelumnya">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                <button type="button" @click="nextSlide()" class="p-0.5 hover:text-blue-700 text-slate-400 cursor-pointer" title="Berikutnya">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </template>
                        <button type="button" @click="viewMode = (viewMode === 'slide' ? 'all' : 'slide')" 
                                class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-white border border-blue-200 text-blue-800 hover:bg-blue-100 transition-colors cursor-pointer">
                            <span x-text="viewMode === 'all' ? 'Slide' : 'Semua'"></span>
                        </button>
                    </div>
                </div>

                <!-- List Items Monitoring Berita -->
                <div class="space-y-2.5 max-h-[380px] overflow-y-auto pr-1">
                    @if(count($monitorings) === 0)
                        <p class="text-xs text-slate-400 py-6 text-center">Belum ada log monitoring terdaftar.</p>
                    @else
                        <!-- Mode Slide: 2 Kotak Tetap Permanen dengan Teks yang Berganti Slide -->
                        <div x-show="viewMode === 'slide'" class="space-y-2.5">
                            <!-- Slot 1: Kotak tetap permanen -->
                            <div class="p-3 rounded-xl border border-slate-200 hover:border-blue-300 hover:shadow-sm transition-all bg-slate-50/50 min-h-[140px] relative overflow-hidden flex flex-col justify-between">
                                @foreach($monitorings as $mon)
                                    @if($loop->index % 2 === 0)
                                        <div x-show="activeSlide === {{ (int)($loop->index / 2) }}"
                                             x-transition:enter="transition-opacity duration-700 ease-out"
                                             x-transition:enter-start="opacity-0"
                                             x-transition:enter-end="opacity-100"
                                             x-transition:leave="transition-opacity duration-700 ease-in absolute inset-0 p-3 pointer-events-none"
                                             x-transition:leave-start="opacity-100"
                                             x-transition:leave-end="opacity-0"
                                             class="flex flex-col justify-between h-full gap-2">
                                            <div>
                                                <div class="flex items-center justify-between gap-1 mb-1.5 flex-wrap">
                                                    <div class="flex items-center space-x-1.5">
                                                        <span class="font-mono text-[10px] font-bold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200">
                                                            {{ $mon->kode }}
                                                        </span>
                                                        <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">
                                                            {{ $mon->jam_tayang }}
                                                        </span>
                                                    </div>
                                                    <span class="text-[10px] font-extrabold px-2 py-0.5 rounded border {{ $mon->status_siaran === 'Selesai' || $mon->status_siaran === 'Sesuai Jadwal' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-rose-100 text-rose-800 border-rose-200' }}">
                                                        {{ $mon->status_siaran }}
                                                    </span>
                                                </div>

                                                <!-- Slide HANYA pada Tulisan Program & Fokus (Halus & Profesional) -->
                                                <div class="overflow-hidden relative my-0.5 min-h-[46px]">
                                                    <div x-show="activeSlide === {{ (int)($loop->index / 2) }}"
                                                         x-transition:enter="transition-all duration-700 ease-out transform"
                                                         x-transition:enter-start="opacity-0 translate-y-3.5"
                                                         x-transition:enter-end="opacity-100 translate-y-0"
                                                         x-transition:leave="transition-all duration-700 ease-in-out transform absolute top-0 left-0 w-full pointer-events-none"
                                                         x-transition:leave-start="opacity-100 translate-y-0"
                                                         x-transition:leave-end="opacity-0 -translate-y-3.5">
                                                        <h4 class="text-xs font-bold text-slate-900 leading-snug">
                                                            {{ $mon->program_siaran }}
                                                        </h4>
                                                        <p class="text-[11px] text-slate-600 mt-1 line-clamp-2 leading-relaxed">
                                                            <strong class="text-slate-800">Fokus:</strong> {{ $mon->rundown_fokus }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="flex items-center space-x-2 text-[10px] text-slate-500 mt-1.5 flex-wrap gap-y-1">
                                                    @if($mon->presenter)
                                                        <span>🎙️ Anchor: {{ $mon->presenter }}</span>
                                                        <span>&bull;</span>
                                                    @endif
                                                    <span>👤 Prod: {{ $mon->produser ?? 'Nahrum' }}</span>
                                                    <span>&bull;</span>
                                                    <span class="text-teal-700 font-bold">A/V: {{ $mon->kualitas_av }}</span>
                                                </div>
                                            </div>

                                            @if($mon->evaluasi_catatan)
                                                <div class="pt-1.5 border-t border-slate-200/60 text-[10px] text-slate-500 italic">
                                                    Catatan MCR: {{ $mon->evaluasi_catatan }}
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <!-- Slot 2: Kotak tetap permanen -->
                            @if(count($monitorings) > 1)
                            <div class="p-3 rounded-xl border border-slate-200 hover:border-blue-300 hover:shadow-sm transition-all bg-slate-50/50 min-h-[140px] relative overflow-hidden flex flex-col justify-between">
                                @foreach($monitorings as $mon)
                                    @if($loop->index % 2 === 1)
                                        <div x-show="activeSlide === {{ (int)($loop->index / 2) }}"
                                             x-transition:enter="transition-opacity duration-700 ease-out"
                                             x-transition:enter-start="opacity-0"
                                             x-transition:enter-end="opacity-100"
                                             x-transition:leave="transition-opacity duration-700 ease-in absolute inset-0 p-3 pointer-events-none"
                                             x-transition:leave-start="opacity-100"
                                             x-transition:leave-end="opacity-0"
                                             class="flex flex-col justify-between h-full gap-2">
                                            <div>
                                                <div class="flex items-center justify-between gap-1 mb-1.5 flex-wrap">
                                                    <div class="flex items-center space-x-1.5">
                                                        <span class="font-mono text-[10px] font-bold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200">
                                                            {{ $mon->kode }}
                                                        </span>
                                                        <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">
                                                            {{ $mon->jam_tayang }}
                                                        </span>
                                                    </div>
                                                    <span class="text-[10px] font-extrabold px-2 py-0.5 rounded border {{ $mon->status_siaran === 'Selesai' || $mon->status_siaran === 'Sesuai Jadwal' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-rose-100 text-rose-800 border-rose-200' }}">
                                                        {{ $mon->status_siaran }}
                                                    </span>
                                                </div>

                                                <!-- Slide HANYA pada Tulisan Program & Fokus (Halus & Profesional) -->
                                                <div class="overflow-hidden relative my-0.5 min-h-[46px]">
                                                    <div x-show="activeSlide === {{ (int)($loop->index / 2) }}"
                                                         x-transition:enter="transition-all duration-700 ease-out transform"
                                                         x-transition:enter-start="opacity-0 translate-y-3.5"
                                                         x-transition:enter-end="opacity-100 translate-y-0"
                                                         x-transition:leave="transition-all duration-700 ease-in-out transform absolute top-0 left-0 w-full pointer-events-none"
                                                         x-transition:leave-start="opacity-100 translate-y-0"
                                                         x-transition:leave-end="opacity-0 -translate-y-3.5">
                                                        <h4 class="text-xs font-bold text-slate-900 leading-snug">
                                                            {{ $mon->program_siaran }}
                                                        </h4>
                                                        <p class="text-[11px] text-slate-600 mt-1 line-clamp-2 leading-relaxed">
                                                            <strong class="text-slate-800">Fokus:</strong> {{ $mon->rundown_fokus }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="flex items-center space-x-2 text-[10px] text-slate-500 mt-1.5 flex-wrap gap-y-1">
                                                    @if($mon->presenter)
                                                        <span>🎙️ Anchor: {{ $mon->presenter }}</span>
                                                        <span>&bull;</span>
                                                    @endif
                                                    <span>👤 Prod: {{ $mon->produser ?? 'Nahrum' }}</span>
                                                    <span>&bull;</span>
                                                    <span class="text-teal-700 font-bold">A/V: {{ $mon->kualitas_av }}</span>
                                                </div>
                                            </div>

                                            @if($mon->evaluasi_catatan)
                                                <div class="pt-1.5 border-t border-slate-200/60 text-[10px] text-slate-500 italic">
                                                    Catatan MCR: {{ $mon->evaluasi_catatan }}
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                                @if(count($monitorings) % 2 === 1)
                                    @php $monFirst = $monitorings->first(); @endphp
                                    <div x-show="activeSlide === {{ (int) ceil(count($monitorings) / 2) - 1 }}"
                                         x-transition:enter="transition-opacity duration-700 ease-out"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         x-transition:leave="transition-opacity duration-700 ease-in absolute inset-0 p-3 pointer-events-none"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         class="flex flex-col justify-between h-full gap-2">
                                        <div>
                                            <div class="flex items-center justify-between gap-1 mb-1.5 flex-wrap">
                                                <div class="flex items-center space-x-1.5">
                                                    <span class="font-mono text-[10px] font-bold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200">
                                                        {{ $monFirst->kode }}
                                                    </span>
                                                    <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">
                                                        {{ $monFirst->jam_tayang }}
                                                    </span>
                                                </div>
                                                <span class="text-[10px] font-extrabold px-2 py-0.5 rounded border {{ $monFirst->status_siaran === 'Selesai' || $monFirst->status_siaran === 'Sesuai Jadwal' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-rose-100 text-rose-800 border-rose-200' }}">
                                                    {{ $monFirst->status_siaran }}
                                                </span>
                                            </div>

                                            <!-- Slide HANYA pada Tulisan Program & Fokus (Halus & Profesional) -->
                                            <div class="overflow-hidden relative my-0.5 min-h-[46px]">
                                                <div x-show="activeSlide === {{ (int) ceil(count($monitorings) / 2) - 1 }}"
                                                     x-transition:enter="transition-all duration-700 ease-out transform"
                                                     x-transition:enter-start="opacity-0 translate-y-3.5"
                                                     x-transition:enter-end="opacity-100 translate-y-0"
                                                     x-transition:leave="transition-all duration-700 ease-in-out transform absolute top-0 left-0 w-full pointer-events-none"
                                                     x-transition:leave-start="opacity-100 translate-y-0"
                                                     x-transition:leave-end="opacity-0 -translate-y-3.5">
                                                    <h4 class="text-xs font-bold text-slate-900 leading-snug">
                                                        {{ $monFirst->program_siaran }}
                                                    </h4>
                                                    <p class="text-[11px] text-slate-600 mt-1 line-clamp-2 leading-relaxed">
                                                        <strong class="text-slate-800">Fokus:</strong> {{ $monFirst->rundown_fokus }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="flex items-center space-x-2 text-[10px] text-slate-500 mt-1.5 flex-wrap gap-y-1">
                                                @if($monFirst->presenter)
                                                    <span>🎙️ Anchor: {{ $monFirst->presenter }}</span>
                                                    <span>&bull;</span>
                                                @endif
                                                <span>👤 Prod: {{ $monFirst->produser ?? 'Nahrum' }}</span>
                                                <span>&bull;</span>
                                                <span class="text-teal-700 font-bold">A/V: {{ $monFirst->kualitas_av }}</span>
                                            </div>
                                        </div>

                                        @if($monFirst->evaluasi_catatan)
                                            <div class="pt-1.5 border-t border-slate-200/60 text-[10px] text-slate-500 italic">
                                                Catatan MCR: {{ $monFirst->evaluasi_catatan }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            @endif
                        </div>

                        <!-- Mode Semua -->
                        <div x-show="viewMode === 'all'" class="space-y-2.5">
                            @foreach($monitorings as $mon)
                                <div class="p-3 rounded-xl border border-slate-200 hover:border-blue-300 hover:shadow-sm transition-all bg-slate-50/50 flex flex-col justify-between gap-2">
                                    <div>
                                        <div class="flex items-center justify-between gap-1 mb-1.5 flex-wrap">
                                            <div class="flex items-center space-x-1.5">
                                                <span class="font-mono text-[10px] font-bold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200">
                                                    {{ $mon->kode }}
                                                </span>
                                                <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">
                                                    {{ $mon->jam_tayang }}
                                                </span>
                                            </div>
                                            <span class="text-[10px] font-extrabold px-2 py-0.5 rounded border {{ $mon->status_siaran === 'Selesai' || $mon->status_siaran === 'Sesuai Jadwal' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-rose-100 text-rose-800 border-rose-200' }}">
                                                {{ $mon->status_siaran }}
                                            </span>
                                        </div>
                                        <h4 class="text-xs font-bold text-slate-900 leading-snug">
                                            {{ $mon->program_siaran }}
                                        </h4>
                                        <p class="text-[11px] text-slate-600 mt-1 line-clamp-2 leading-relaxed">
                                            <strong class="text-slate-800">Fokus:</strong> {{ $mon->rundown_fokus }}
                                        </p>
                                        <div class="flex items-center space-x-2 text-[10px] text-slate-500 mt-1.5 flex-wrap gap-y-1">
                                            @if($mon->presenter)
                                                <span>🎙️ Anchor: {{ $mon->presenter }}</span>
                                                <span>&bull;</span>
                                            @endif
                                            <span>👤 Prod: {{ $mon->produser ?? 'Nahrum' }}</span>
                                            <span>&bull;</span>
                                            <span class="text-teal-700 font-bold">A/V: {{ $mon->kualitas_av }}</span>
                                        </div>
                                    </div>

                                    @if($mon->evaluasi_catatan)
                                        <div class="pt-1.5 border-t border-slate-200/60 text-[10px] text-slate-500 italic">
                                            Catatan MCR: {{ $mon->evaluasi_catatan }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Master Control Room (MCR)</span>
                <span class="text-blue-700 font-bold">Sinkron Live Broadcast</span>
            </div>
        </div>

        <!-- 3. KOTAK MENU: DOKUMENTASI SIARAN & BERITA -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between scroll-mt-24" id="dokumentasi-berita"
             x-data="{
                 activeSlide: 0,
                 totalSlides: {{ max(1, (int) ceil(count($dokumentasis) / 2)) }},
                 autoSlide: true,
                 viewMode: 'slide',
                 timer: null,
                 init() {
                     if (this.totalSlides > 1) {
                         this.timer = setInterval(() => {
                             if (this.autoSlide && this.viewMode === 'slide') {
                                 this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
                             }
                         }, 9000);
                     }
                 },
                 pauseSlide() { this.autoSlide = false; },
                 resumeSlide() { this.autoSlide = true; },
                 nextSlide() { this.activeSlide = (this.activeSlide + 1) % this.totalSlides; },
                 prevSlide() { this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides; },
                 goToSlide(i) { this.activeSlide = i; }
             }"
             @mouseenter="pauseSlide()"
             @mouseleave="resumeSlide()">
            <span id="dokumentasi-siaran" class="scroll-mt-24"></span>
            <div>
                <div class="flex items-center justify-between mb-3 pb-2.5 border-b border-slate-100 flex-wrap gap-2">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center font-bold text-sm">
                            🗄️
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Dokumentasi Siaran & Berita</h3>
                            <p class="text-[11px] text-slate-500 font-medium">Pengarsipan audio visual &amp; footage siaran</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <span class="text-[10px] font-black text-purple-800 bg-purple-100/80 px-2.5 py-0.5 rounded-full border border-purple-200">
                            {{ count($dokumentasis) }} Arsip
                        </span>
                        @if(!$isKepalaStasiun)
                        <a href="{{ route('tvri.berita.manage', ['tab' => 'dokumentasi']) }}" 
                           class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-xl text-xs font-bold bg-purple-50 text-purple-700 hover:bg-purple-100 border border-purple-200 transition-colors">
                            <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Kelola</span>
                        </a>
                        @else
                        <span class="text-[9px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">Monitoring Pimpinan</span>
                        @endif
                    </div>
                </div>

                <!-- KPI Mini Pills Dokumentasi -->
                <div class="grid grid-cols-3 gap-2 mb-3 text-center">
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[9px] uppercase font-bold text-slate-400 block">Footage</span>
                        <span class="text-xs font-black text-purple-700">{{ $dokumentasis->where('jenis_media', 'Footage Video')->count() }} File</span>
                    </div>
                    <div class="p-2 rounded-xl bg-teal-50/70 border border-teal-100">
                        <span class="text-[9px] uppercase font-bold text-teal-600 block">Master Tape</span>
                        <span class="text-xs font-black text-teal-700">{{ $dokumentasis->where('status_arsip', 'Master Tape')->count() }} Tape</span>
                    </div>
                    <div class="p-2 rounded-xl bg-emerald-50/70 border border-emerald-100">
                        <span class="text-[9px] uppercase font-bold text-emerald-600 block">DMS Ready</span>
                        <span class="text-xs font-black text-emerald-700">100% Aman</span>
                    </div>
                </div>

                <!-- Slide Mini Control Bar -->
                <div class="flex items-center justify-between py-1 px-2 bg-purple-50/60 rounded-lg border border-purple-100 text-[10px] text-purple-900 mb-2.5">
                    <div class="flex items-center space-x-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500" :class="autoSlide && viewMode === 'slide' ? 'animate-pulse' : ''"></span>
                        <span class="font-bold font-mono" x-text="viewMode === 'all' ? 'Semua ({{ count($dokumentasis) }})' : 'Slide ' + (activeSlide + 1) + '/' + totalSlides"></span>
                        <template x-if="totalSlides > 1 && viewMode === 'slide'">
                            <div class="flex items-center space-x-1 ml-1">
                                @for($s = 0; $s < max(1, (int) ceil(count($dokumentasis) / 2)); $s++)
                                    <button type="button" @click="goToSlide({{ $s }})" 
                                            class="h-1 rounded-full transition-all duration-300 cursor-pointer"
                                            :class="activeSlide === {{ $s }} ? 'w-3.5 bg-purple-600' : 'w-1 bg-purple-300 hover:bg-purple-400'"></button>
                                @endfor
                            </div>
                        </template>
                    </div>
                    <div class="flex items-center space-x-1">
                        <template x-if="totalSlides > 1 && viewMode === 'slide'">
                            <div class="flex items-center space-x-0.5">
                                <button type="button" @click="prevSlide()" class="p-0.5 hover:text-purple-700 text-slate-400 cursor-pointer" title="Sebelumnya">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                <button type="button" @click="nextSlide()" class="p-0.5 hover:text-purple-700 text-slate-400 cursor-pointer" title="Berikutnya">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </template>
                        <button type="button" @click="viewMode = (viewMode === 'slide' ? 'all' : 'slide')" 
                                class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-white border border-purple-200 text-purple-800 hover:bg-purple-100 transition-colors cursor-pointer">
                            <span x-text="viewMode === 'all' ? 'Slide' : 'Semua'"></span>
                        </button>
                    </div>
                </div>

                <!-- List Items Dokumentasi Berita -->
                <div class="space-y-2.5 max-h-[380px] overflow-y-auto pr-1">
                    @if(count($dokumentasis) === 0)
                        <p class="text-xs text-slate-400 py-6 text-center">Belum ada dokumentasi terdaftar.</p>
                    @else
                        <!-- Mode Slide: 2 Kotak Tetap Permanen dengan Teks yang Berganti Slide -->
                        <div x-show="viewMode === 'slide'" class="space-y-2.5">
                            <!-- Slot 1: Kotak tetap permanen -->
                            <div class="p-3 rounded-xl border border-slate-200 hover:border-purple-300 hover:shadow-sm transition-all bg-slate-50/50 min-h-[140px] relative overflow-hidden flex flex-col justify-between">
                                @foreach($dokumentasis as $dok)
                                    @if($loop->index % 2 === 0)
                                        <div x-show="activeSlide === {{ (int)($loop->index / 2) }}"
                                             x-transition:enter="transition-opacity duration-700 ease-out"
                                             x-transition:enter-start="opacity-0"
                                             x-transition:enter-end="opacity-100"
                                             x-transition:leave="transition-opacity duration-700 ease-in absolute inset-0 p-3 pointer-events-none"
                                             x-transition:leave-start="opacity-100"
                                             x-transition:leave-end="opacity-0"
                                             class="flex flex-col justify-between h-full gap-2">
                                            <div>
                                                <div class="flex items-center justify-between gap-1 mb-1.5 flex-wrap">
                                                    <div class="flex items-center space-x-1.5">
                                                        <span class="font-mono text-[10px] font-bold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200">
                                                            {{ $dok->kode }}
                                                        </span>
                                                        <span class="text-[10px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded border border-purple-200">
                                                            {{ $dok->jenis_media }}
                                                        </span>
                                                    </div>
                                                    <span class="text-[10px] font-bold text-teal-800 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded">
                                                        {{ $dok->status_arsip }}
                                                    </span>
                                                </div>

                                                <!-- Slide HANYA pada Tulisan Judul Dokumentasi (Halus & Profesional) -->
                                                <div class="overflow-hidden relative my-0.5 min-h-[34px]">
                                                    <div x-show="activeSlide === {{ (int)($loop->index / 2) }}"
                                                         x-transition:enter="transition-all duration-700 ease-out transform"
                                                         x-transition:enter-start="opacity-0 translate-y-3.5"
                                                         x-transition:enter-end="opacity-100 translate-y-0"
                                                         x-transition:leave="transition-all duration-700 ease-in-out transform absolute top-0 left-0 w-full pointer-events-none"
                                                         x-transition:leave-start="opacity-100 translate-y-0"
                                                         x-transition:leave-end="opacity-0 -translate-y-3.5">
                                                        <h4 class="text-xs font-bold text-slate-900 leading-snug line-clamp-2">
                                                            {{ $dok->judul_dokumentasi }}
                                                        </h4>
                                                    </div>
                                                </div>

                                                <div class="flex items-center space-x-2 text-[10px] text-slate-500 mt-1.5 flex-wrap gap-y-1">
                                                    <span>📍 {{ $dok->lokasi }}</span>
                                                    <span>&bull;</span>
                                                    <span>💾 {{ $dok->format_file }} ({{ $dok->ukuran_file }})</span>
                                                </div>
                                                @if($dok->crew)
                                                    <p class="text-[10px] text-slate-500 mt-1">
                                                        👥 {{ $dok->crew }}
                                                    </p>
                                                @endif
                                            </div>

                                            <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between text-[10px]">
                                                <span class="text-slate-400 font-mono">{{ $dok->tanggal_liputan ? (is_string($dok->tanggal_liputan) ? substr($dok->tanggal_liputan, 0, 10) : $dok->tanggal_liputan->format('d/m/Y')) : '-' }}</span>
                                                @if($dok->link_media)
                                                    <a href="{{ $dok->link_media }}" target="_blank" 
                                                       class="inline-flex items-center space-x-1 font-bold text-purple-700 hover:text-purple-900 hover:underline">
                                                        <span>Buka Arsip</span>
                                                        <svg class="w-3 h-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                    </a>
                                                @else
                                                    <a href="{{ route('dokumen.documents.index', ['search' => $dok->kode]) }}" 
                                                       class="inline-flex items-center space-x-1 font-bold text-blue-600 hover:underline">
                                                        <span>Pangkalan Dokumen &rarr;</span>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <!-- Slot 2: Kotak tetap permanen -->
                            @if(count($dokumentasis) > 1)
                            <div class="p-3 rounded-xl border border-slate-200 hover:border-purple-300 hover:shadow-sm transition-all bg-slate-50/50 min-h-[140px] relative overflow-hidden flex flex-col justify-between">
                                @foreach($dokumentasis as $dok)
                                    @if($loop->index % 2 === 1)
                                        <div x-show="activeSlide === {{ (int)($loop->index / 2) }}"
                                             x-transition:enter="transition-opacity duration-700 ease-out"
                                             x-transition:enter-start="opacity-0"
                                             x-transition:enter-end="opacity-100"
                                             x-transition:leave="transition-opacity duration-700 ease-in absolute inset-0 p-3 pointer-events-none"
                                             x-transition:leave-start="opacity-100"
                                             x-transition:leave-end="opacity-0"
                                             class="flex flex-col justify-between h-full gap-2">
                                            <div>
                                                <div class="flex items-center justify-between gap-1 mb-1.5 flex-wrap">
                                                    <div class="flex items-center space-x-1.5">
                                                        <span class="font-mono text-[10px] font-bold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200">
                                                            {{ $dok->kode }}
                                                        </span>
                                                        <span class="text-[10px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded border border-purple-200">
                                                            {{ $dok->jenis_media }}
                                                        </span>
                                                    </div>
                                                    <span class="text-[10px] font-bold text-teal-800 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded">
                                                        {{ $dok->status_arsip }}
                                                    </span>
                                                </div>

                                                <!-- Slide HANYA pada Tulisan Judul Dokumentasi (Halus & Profesional) -->
                                                <div class="overflow-hidden relative my-0.5 min-h-[34px]">
                                                    <div x-show="activeSlide === {{ (int)($loop->index / 2) }}"
                                                         x-transition:enter="transition-all duration-700 ease-out transform"
                                                         x-transition:enter-start="opacity-0 translate-y-3.5"
                                                         x-transition:enter-end="opacity-100 translate-y-0"
                                                         x-transition:leave="transition-all duration-700 ease-in-out transform absolute top-0 left-0 w-full pointer-events-none"
                                                         x-transition:leave-start="opacity-100 translate-y-0"
                                                         x-transition:leave-end="opacity-0 -translate-y-3.5">
                                                        <h4 class="text-xs font-bold text-slate-900 leading-snug line-clamp-2">
                                                            {{ $dok->judul_dokumentasi }}
                                                        </h4>
                                                    </div>
                                                </div>

                                                <div class="flex items-center space-x-2 text-[10px] text-slate-500 mt-1.5 flex-wrap gap-y-1">
                                                    <span>📍 {{ $dok->lokasi }}</span>
                                                    <span>&bull;</span>
                                                    <span>💾 {{ $dok->format_file }} ({{ $dok->ukuran_file }})</span>
                                                </div>
                                                @if($dok->crew)
                                                    <p class="text-[10px] text-slate-500 mt-1">
                                                        👥 {{ $dok->crew }}
                                                    </p>
                                                @endif
                                            </div>

                                            <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between text-[10px]">
                                                <span class="text-slate-400 font-mono">{{ $dok->tanggal_liputan ? (is_string($dok->tanggal_liputan) ? substr($dok->tanggal_liputan, 0, 10) : $dok->tanggal_liputan->format('d/m/Y')) : '-' }}</span>
                                                @if($dok->link_media)
                                                    <a href="{{ $dok->link_media }}" target="_blank" 
                                                       class="inline-flex items-center space-x-1 font-bold text-purple-700 hover:text-purple-900 hover:underline">
                                                        <span>Buka Arsip</span>
                                                        <svg class="w-3 h-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                    </a>
                                                @else
                                                    <a href="{{ route('dokumen.documents.index', ['search' => $dok->kode]) }}" 
                                                       class="inline-flex items-center space-x-1 font-bold text-blue-600 hover:underline">
                                                        <span>Pangkalan Dokumen &rarr;</span>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                @if(count($dokumentasis) % 2 === 1)
                                    @php $dokFirst = $dokumentasis->first(); @endphp
                                    <div x-show="activeSlide === {{ (int) ceil(count($dokumentasis) / 2) - 1 }}"
                                         x-transition:enter="transition-opacity duration-700 ease-out"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         x-transition:leave="transition-opacity duration-700 ease-in absolute inset-0 p-3 pointer-events-none"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         class="flex flex-col justify-between h-full gap-2">
                                        <div>
                                            <div class="flex items-center justify-between gap-1 mb-1.5 flex-wrap">
                                                <div class="flex items-center space-x-1.5">
                                                    <span class="font-mono text-[10px] font-bold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200">
                                                        {{ $dokFirst->kode }}
                                                    </span>
                                                    <span class="text-[10px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded border border-purple-200">
                                                        {{ $dokFirst->jenis_media }}
                                                    </span>
                                                </div>
                                                <span class="text-[10px] font-bold text-teal-800 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded">
                                                    {{ $dokFirst->status_arsip }}
                                                </span>
                                            </div>

                                            <!-- Slide HANYA pada Tulisan Judul Dokumentasi (Halus & Profesional) -->
                                            <div class="overflow-hidden relative my-0.5 min-h-[34px]">
                                                <div x-show="activeSlide === {{ (int) ceil(count($dokumentasis) / 2) - 1 }}"
                                                     x-transition:enter="transition-all duration-700 ease-out transform"
                                                     x-transition:enter-start="opacity-0 translate-y-3.5"
                                                     x-transition:enter-end="opacity-100 translate-y-0"
                                                     x-transition:leave="transition-all duration-700 ease-in-out transform absolute top-0 left-0 w-full pointer-events-none"
                                                     x-transition:leave-start="opacity-100 translate-y-0"
                                                     x-transition:leave-end="opacity-0 -translate-y-3.5">
                                                    <h4 class="text-xs font-bold text-slate-900 leading-snug line-clamp-2">
                                                        {{ $dokFirst->judul_dokumentasi }}
                                                    </h4>
                                                </div>
                                            </div>

                                            <div class="flex items-center space-x-2 text-[10px] text-slate-500 mt-1.5 flex-wrap gap-y-1">
                                                <span>📍 {{ $dokFirst->lokasi }}</span>
                                                <span>&bull;</span>
                                                <span>💾 {{ $dokFirst->format_file }} ({{ $dokFirst->ukuran_file }})</span>
                                            </div>
                                            @if($dokFirst->crew)
                                                <p class="text-[10px] text-slate-500 mt-1">
                                                    👥 {{ $dokFirst->crew }}
                                                </p>
                                            @endif
                                        </div>

                                        <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between text-[10px]">
                                            <span class="text-slate-400 font-mono">{{ $dokFirst->tanggal_liputan ? (is_string($dokFirst->tanggal_liputan) ? substr($dokFirst->tanggal_liputan, 0, 10) : $dokFirst->tanggal_liputan->format('d/m/Y')) : '-' }}</span>
                                            @if($dokFirst->link_media)
                                                <a href="{{ $dokFirst->link_media }}" target="_blank" 
                                                   class="inline-flex items-center space-x-1 font-bold text-purple-700 hover:text-purple-900 hover:underline">
                                                    <span>Buka Arsip</span>
                                                    <svg class="w-3 h-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                </a>
                                            @else
                                                <a href="{{ route('dokumen.documents.index', ['search' => $dokFirst->kode]) }}" 
                                                   class="inline-flex items-center space-x-1 font-bold text-blue-600 hover:underline">
                                                    <span>Pangkalan Dokumen &rarr;</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @endif
                        </div>

                        <!-- Mode Semua -->
                        <div x-show="viewMode === 'all'" class="space-y-2.5">
                            @foreach($dokumentasis as $dok)
                                <div class="p-3 rounded-xl border border-slate-200 hover:border-purple-300 hover:shadow-sm transition-all bg-slate-50/50 flex flex-col justify-between gap-2">
                                    <div>
                                        <div class="flex items-center justify-between gap-1 mb-1.5 flex-wrap">
                                            <div class="flex items-center space-x-1.5">
                                                <span class="font-mono text-[10px] font-bold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200">
                                                    {{ $dok->kode }}
                                                </span>
                                                <span class="text-[10px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded border border-purple-200">
                                                    {{ $dok->jenis_media }}
                                                </span>
                                            </div>
                                            <span class="text-[10px] font-bold text-teal-800 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded">
                                                {{ $dok->status_arsip }}
                                            </span>
                                        </div>
                                        <h4 class="text-xs font-bold text-slate-900 leading-snug line-clamp-2">
                                            {{ $dok->judul_dokumentasi }}
                                        </h4>
                                        <div class="flex items-center space-x-2 text-[10px] text-slate-500 mt-1.5 flex-wrap gap-y-1">
                                            <span>📍 {{ $dok->lokasi }}</span>
                                            <span>&bull;</span>
                                            <span>💾 {{ $dok->format_file }} ({{ $dok->ukuran_file }})</span>
                                        </div>
                                        @if($dok->crew)
                                            <p class="text-[10px] text-slate-500 mt-1">
                                                👥 {{ $dok->crew }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between text-[10px]">
                                        <span class="text-slate-400 font-mono">{{ $dok->tanggal_liputan ? (is_string($dok->tanggal_liputan) ? substr($dok->tanggal_liputan, 0, 10) : $dok->tanggal_liputan->format('d/m/Y')) : '-' }}</span>
                                        @if($dok->link_media)
                                            <a href="{{ $dok->link_media }}" target="_blank" 
                                               class="inline-flex items-center space-x-1 font-bold text-purple-700 hover:text-purple-900 hover:underline">
                                                <span>Buka Arsip</span>
                                                <svg class="w-3 h-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        @else
                                            <a href="{{ route('dokumen.documents.index', ['search' => $dok->kode]) }}" 
                                               class="inline-flex items-center space-x-1 font-bold text-blue-600 hover:underline">
                                                <span>Pangkalan Dokumen &rarr;</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>DMS &amp; Server Audio Visual</span>
                <span class="text-purple-700 font-bold">Sinkron Pangkalan Dokumen</span>
            </div>
        </div>
    </div>

    <!-- ===== SECTION: DOKUMEN & SOP NEWSROOM TVRI MALUKU ===== -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm" id="sop">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 mb-4 border-b border-slate-100 gap-3">
            <div>
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 rounded-full bg-teal-500"></span>
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">
                        Standar Operasional Prosedur (SOP) & Dokumen Newsroom
                    </h3>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Pedoman teknis peliputan, produksi, siaran on-air, kode etik jurnalistik, dan penyiaran multiplatform TVRI Stasiun Maluku
                </p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-teal-50 text-teal-700 border border-teal-200">
                    {{ count($sops) }} Dokumen SOP Aktif
                </span>
                @if(!$isKepalaStasiun)
                <a href="{{ route('tvri.berita.manage', ['tab' => 'sop']) }}" 
                   class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-xl text-xs font-bold bg-teal-600 hover:bg-teal-700 text-white shadow-sm transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Kelola SOP Berita</span>
                </a>
                @endif
                <a href="{{ route('dokumen.documents.index') }}" 
                   class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-all">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Pangkalan Dokumen</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($sops as $sop)
                <div class="p-4 rounded-xl border border-slate-200 hover:border-teal-300 hover:shadow-md transition-all flex flex-col justify-between bg-slate-50/50">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2 py-0.5 rounded-md bg-teal-100 text-teal-800 font-mono text-[10px] font-bold">
                                {{ $sop->kode }}
                            </span>
                            <div class="flex items-center space-x-1.5">
                                <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 text-[9px] font-semibold">
                                    {{ $sop->versi }}
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-extrabold {{ $sop->status == 'Aktif' ? 'bg-emerald-100 text-emerald-700' : ($sop->status == 'Revisi' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600') }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $sop->status == 'Aktif' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                    {{ $sop->status }}
                                </span>
                            </div>
                        </div>

                        <h4 class="text-xs font-bold text-slate-900 leading-snug">
                            {{ $sop->judul }}
                        </h4>

                        <p class="text-[11px] text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                            {{ $sop->keterangan ?: 'Prosedur operasional baku penyiaran dan produksi berita TVRI Maluku.' }}
                        </p>
                    </div>

                    <div class="mt-3 pt-3 border-t border-slate-200/60 flex items-center justify-between text-[10px]">
                        <span class="font-bold text-teal-700 bg-teal-50 px-2 py-0.5 rounded">
                            {{ $sop->kategori }}
                        </span>
                        <div class="flex items-center space-x-2">
                            @if($sop->file_url || $sop->file_path)
                                <a href="{{ asset($sop->file_url ?: $sop->file_path) }}" target="_blank" class="font-bold text-emerald-600 hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-2 py-0.5 rounded border border-emerald-200 flex items-center space-x-1 transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <span>Fisik SOP</span>
                                </a>
                            @endif
                            <a href="{{ route('dokumen.documents.index', ['search' => $sop->kode]) }}" class="font-bold text-[#005B9F] hover:underline flex items-center space-x-0.5">
                                <span>Lihat Arsip</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-6 text-slate-400 text-xs">
                    Belum ada dokumen SOP terdata.
                </div>
            @endforelse
        </div>
    </div>

    <!-- ===== FOOTER SLOGAN NEWSROOM ===== -->
    <div class="bg-gradient-to-r from-[#00142A] via-[#002244] to-[#003B70] rounded-3xl p-5 text-white shadow-xl flex flex-col md:flex-row md:items-center md:justify-between gap-4 border border-cyan-400/20">
        <div class="flex flex-wrap items-center gap-2">
            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-white/10 border border-white/15">⚡ Cepat Menyampaikan Fakta</span>
            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-white/10 border border-white/15">🎙 Menguatkan Suara Maluku</span>
            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-white/10 border border-white/15">🛡 Profesional & Berintegritas</span>
            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-white/10 border border-white/15">🤝 Kolaboratif</span>
            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-white/10 border border-white/15">👥 Berorientasi Publik</span>
        </div>

        <div class="text-left md:text-right flex-shrink-0">
            <span class="text-sm font-black text-amber-300 italic tracking-wider">Maluku Bisa! Indonesia Maju!</span>
            <p class="text-[10px] text-blue-300">© 2026 TVRI Maluku</p>
        </div>
    </div>

</div>

<!-- ===== SCRIPT CHART.JS NEWSROOM ===== -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Gauge Newsroom Performance Index
    const gCtx = document.getElementById('gaugeIndexChart').getContext('2d');
    new Chart(gCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [{{ $ringkasan->performance_index }}, {{ max(0, 100 - $ringkasan->performance_index) }}],
                backgroundColor: ['#06b6d4', '#e2e8f0'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            circumference: 240,
            rotation: 240,
            cutout: '80%',
            plugins: { legend: { display: false }, tooltip: { enabled: false } }
        }
    });

    // 2. Tren Produksi & Tayang Berita (1 Sep - 10 Sep)
    const trenCtx = document.getElementById('trenBeritaChart').getContext('2d');
    new Chart(trenCtx, {
        type: 'line',
        data: {
            labels: ['1 Sep', '2 Sep', '3 Sep', '4 Sep', '5 Sep', '6 Sep', '7 Sep', '8 Sep', '9 Sep', '10 Sep'],
            datasets: [
                {
                    label: 'Rencana Liputan',
                    data: [45, 48, 52, 58, 65, 72, 78, 84, 90, 95],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    pointRadius: 3,
                    fill: false,
                    tension: 0.2
                },
                {
                    label: 'Berita Diproduksi',
                    data: [38, 41, 45, 50, 58, 64, 70, 76, 82, 86],
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2.5,
                    pointRadius: 4,
                    fill: false,
                    tension: 0.2
                },
                {
                    label: 'Berita Tayang',
                    data: [36, 40, 44, 48, 55, 62, 68, 74, 80, 85],
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    borderWidth: 2.5,
                    pointRadius: 4,
                    fill: false,
                    tension: 0.2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, max: 100, grid: { color: '#f1f5f9' } },
                x: { grid: { display: false } }
            }
        }
    });

    // 3. Kategori Berita (Donut Chart)
    const katCtx = document.getElementById('kategoriDonutChart').getContext('2d');
    new Chart(katCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_column($kategoriList, 'nama')) !!},
            datasets: [{
                data: {!! json_encode(array_column($kategoriList, 'jumlah')) !!},
                backgroundColor: {!! json_encode(array_column($kategoriList, 'color')) !!},
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: { label: ctx => ctx.label + ': ' + ctx.raw + ' Berita' }
                }
            },
            cutout: '68%'
        }
    });

    // 4. Status Liputan (Donut Chart)
    const stCtx = document.getElementById('statusLiputanChart').getContext('2d');
    new Chart(stCtx, {
        type: 'doughnut',
        data: {
            labels: ['Selesai', 'Dalam Proses', 'Terkendala', 'Batal'],
            datasets: [{
                data: [
                    {{ $statusList['Selesai']['jumlah'] }},
                    {{ $statusList['Dalam Proses']['jumlah'] }},
                    {{ $statusList['Terkendala']['jumlah'] }},
                    {{ $statusList['Batal']['jumlah'] }}
                ],
                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: { label: ctx => ctx.label + ': ' + ctx.raw + ' Liputan' }
                }
            },
            cutout: '70%'
        }
    });
});
</script>

<!-- Modal Video Player -->
<div id="videoModal" onclick="closeVideoPlayer()" style="display: none;" class="fixed inset-0 z-[99999] bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative bg-slate-900 rounded-2xl overflow-hidden border border-slate-700 shadow-2xl max-w-3xl w-full" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-4 py-3 bg-slate-800/80 border-b border-slate-700">
            <div class="flex items-center space-x-2 truncate pr-4">
                <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse shrink-0"></span>
                <h4 id="videoModalTitle" class="text-sm font-bold text-white truncate">Pemutar Video Berita TVRI Maluku</h4>
            </div>
            <button type="button" onclick="closeVideoPlayer()" class="text-slate-400 hover:text-white text-2xl font-bold leading-none p-1 cursor-pointer transition-colors">&times;</button>
        </div>
        <div class="p-2 bg-black flex justify-center items-center relative min-h-[260px]">
            <video id="videoModalElement" controls playsinline class="max-h-[70vh] w-full rounded-lg" preload="auto">
                <source id="videoModalSource" src="" type="video/mp4">
                Browser Anda tidak mendukung pemutaran video HTML5.
            </video>
            <iframe id="videoModalIframe" src="" class="hidden w-full h-[55vh] rounded-lg border-0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    </div>
</div>

<script>
function openVideoPlayer(videoUrl, title) {
    const modal = document.getElementById('videoModal');
    const player = document.getElementById('videoModalElement');
    const source = document.getElementById('videoModalSource');
    const iframe = document.getElementById('videoModalIframe');
    const titleEl = document.getElementById('videoModalTitle');
    
    if (titleEl) {
        titleEl.textContent = title || 'Pemutar Video Berita TVRI Maluku';
    }
    
    if (!videoUrl || videoUrl === 'null' || videoUrl === 'undefined' || videoUrl === '') {
        videoUrl = 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4';
    }

    // Fix host/port if localhost is used without proper port or relative storage
    if (videoUrl.includes('localhost/storage/') || videoUrl.includes('127.0.0.1/storage/')) {
        videoUrl = videoUrl.replace(/^https?:\/\/[^\/]+\/storage\//, window.location.origin + '/storage/');
    } else if (videoUrl.startsWith('/storage/')) {
        videoUrl = window.location.origin + videoUrl;
    }

    const ytMatch = videoUrl.match(/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/);
    if (ytMatch && ytMatch[1]) {
        if (player) {
            player.pause();
            player.classList.add('hidden');
            player.style.display = 'none';
        }
        if (iframe) {
            iframe.src = 'https://www.youtube.com/embed/' + ytMatch[1] + '?autoplay=1&rel=0';
            iframe.classList.remove('hidden');
            iframe.style.display = 'block';
        }
    } else {
        if (iframe) {
            iframe.src = '';
            iframe.classList.add('hidden');
            iframe.style.display = 'none';
        }
        if (player) {
            player.classList.remove('hidden');
            player.style.display = 'block';
            
            // Set error fallback in case custom video format fails or 404
            player.onerror = function() {
                const fallbackUrl = 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4';
                if (player.src !== fallbackUrl) {
                    console.warn('Playback error on custom video, switching to broadcast sample');
                    player.src = fallbackUrl;
                    if (source) source.src = fallbackUrl;
                    player.load();
                    player.play().catch(function() {});
                }
            };

            player.src = videoUrl;
            if (source) {
                source.src = videoUrl;
            }
            player.load();
            const playPromise = player.play();
            if (playPromise !== undefined) {
                playPromise.catch(function(e) {
                    console.log('Autoplay notification:', e);
                    // If blocked by browser autoplay policy, retry muted so it starts reliably
                    player.muted = true;
                    player.play().catch(function() {});
                });
            }
        }
    }
    
    if (modal) {
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
    }
}

function closeVideoPlayer() {
    const modal = document.getElementById('videoModal');
    const player = document.getElementById('videoModalElement');
    const source = document.getElementById('videoModalSource');
    const iframe = document.getElementById('videoModalIframe');
    
    if (player) {
        player.pause();
        player.removeAttribute('src');
        player.load();
    }
    if (source) {
        source.removeAttribute('src');
    }
    if (iframe) {
        iframe.src = '';
        iframe.classList.add('hidden');
        iframe.style.display = 'none';
    }
    if (modal) {
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeVideoPlayer();
    }
});

// Live Real-Time Clock WIT (Hari, Tanggal Bulan Tahun & Jam WIT)
function updateAgendaDashClock() {
    const el = document.getElementById('agendaDashRealtimeClock');
    if (!el) return;
    try {
        const now = new Date();
        const optionsDate = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', timeZone: 'Asia/Jayapura' };
        const optionsTime = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false, timeZone: 'Asia/Jayapura' };
        const dateStr = new Intl.DateTimeFormat('id-ID', optionsDate).format(now);
        const timeStr = new Intl.DateTimeFormat('id-ID', optionsTime).format(now).replace(/\./g, ':');
        el.innerHTML = `${dateStr} &bull; ${timeStr} WIT`;
    } catch (e) {}
}
setInterval(updateAgendaDashClock, 1000);
</script>

@include('tvri.partials.jadwal_vercel_modal')
@endsection
