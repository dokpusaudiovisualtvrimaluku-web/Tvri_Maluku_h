@extends('layouts.app')

@section('title', 'Dashboard Program TVRI Maluku - Perencanaan, Produksi & Penayangan')

@section('content')
<div class="space-y-6 pb-12" x-data="{
    currentWit: '{{ $meta['waktu'] }}',
    initClock() {
        const update = () => {
            const now = new Date();
            const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
            const wit = new Date(utc + (3600000 * 9));
            const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            const dayName = days[wit.getDay()];
            const dateNum = wit.getDate();
            const monthName = months[wit.getMonth()];
            const yearNum = wit.getFullYear();
            const hours = String(wit.getHours()).padStart(2, '0');
            const mins = String(wit.getMinutes()).padStart(2, '0');
            const secs = String(wit.getSeconds()).padStart(2, '0');
            this.currentWit = `${dayName}, ${dateNum} ${monthName} ${yearNum} | ${hours}:${mins}:${secs} WIT`;
        };
        update();
        setInterval(update, 1000);
    }
}" x-init="initClock()">

    <!-- ===== HEADER UTAMA ===== -->
    <div class="bg-gradient-to-r from-[#001D38] via-[#00264A] to-[#003B70] rounded-3xl p-6 sm:p-7 text-white shadow-xl relative overflow-hidden border border-purple-400/20">
        <div class="absolute -right-10 -top-16 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <div class="flex items-start sm:items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-white p-2 shadow-lg flex-shrink-0 flex items-center justify-center">
                    <img src="{{ asset('images/logo_tvri.png') }}" alt="Logo TVRI" class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-black uppercase tracking-tight leading-tight">DASHBOARD PROGRAM TVRI MALUKU</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-purple-500/20 text-purple-300 border border-purple-400/40 uppercase">PRG</span>
                    </div>
                    <p class="text-xs sm:text-sm font-extrabold text-purple-300 tracking-wider uppercase mt-0.5">PERENCANAAN &bull; PRODUKSI &bull; PENAYANGAN &bull; EVALUASI</p>
                    <p class="text-xs text-blue-200/90 font-medium mt-1">
                        {{ $meta['slogan_header'] }} &bull; <span class="italic text-amber-300 font-semibold">{{ $meta['slogan_sub'] }}</span>
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <div class="bg-white/10 backdrop-blur-md px-4 py-2 rounded-2xl border border-white/10 text-left sm:text-right">
                    <div class="flex items-center gap-1.5 justify-start sm:justify-end text-[10px] font-bold text-emerald-300 uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping inline-block"></span>
                        <span>{{ $meta['status'] }}</span>
                    </div>
                    <p class="text-xs font-black text-white font-mono mt-0.5" x-text="currentWit">{{ $meta['waktu'] }}</p>
                </div>
                @php
                    $currentUser = auth()->user();
                    $isKepalaStasiun = $currentUser && (
                        $currentUser->hasRole('KEPALA_DINAS') ||
                        $currentUser->hasRole('Kepala Stasiun') ||
                        $currentUser->username === 'kepala.stasiun' ||
                        str_contains(strtolower($currentUser->name ?? ''), 'kepala stasiun')
                    );
                @endphp
                @if(!$isKepalaStasiun)
                <a href="{{ route('tvri.program.manage') }}" 
                   class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-purple-600 to-[#005B9F] hover:from-purple-700 hover:to-[#004b85] text-white text-xs font-black shadow-lg shadow-purple-900/30 flex items-center gap-1.5 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Input Data Program</span>
                </a>
                @endif
                <a href="{{ route('tvri.ecc.dashboard') }}" 
                   class="px-3.5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-xs font-bold text-white transition-colors"
                   title="Executive Command Center">
                    &larr; Monitoring ECC
                </a>
            </div>
        </div>
    </div>

    <!-- ===== METRIK UTAMA ATAS (6 KARTU STATISTIK) ===== -->
    <div id="perencanaan" class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3">
        <!-- 1. Total Program Direncanakan -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <span class="w-9 h-9 rounded-xl bg-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </span>
                <span class="text-[10px] font-black text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full border border-purple-200 flex items-center gap-0.5">
                    ▲ {{ $metrik['diff_total'] }}
                </span>
            </div>
            <p class="text-3xl font-black text-[#00264A] mt-3">{{ $metrik['total_direncanakan'] }}</p>
            <p class="text-[11px] font-extrabold text-slate-700 uppercase tracking-wide mt-0.5">Total Direncanakan</p>
            <p class="text-[10px] text-slate-400 font-semibold">{{ $metrik['comp_total'] }}</p>
        </div>

        <!-- 2. Dalam Produksi -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <span class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </span>
                <span class="text-[10px] font-black text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200 flex items-center gap-0.5">
                    ▲ {{ $metrik['diff_produksi'] }}
                </span>
            </div>
            <p class="text-3xl font-black text-[#00264A] mt-3">{{ $metrik['dalam_produksi'] }}</p>
            <p class="text-[11px] font-extrabold text-slate-700 uppercase tracking-wide mt-0.5">Dalam Produksi</p>
            <p class="text-[10px] text-slate-400 font-semibold">Pra, taping & pasca</p>
        </div>

        <!-- 3. Selesai Produksi -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <span class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <span class="text-[10px] font-black text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-200 flex items-center gap-0.5">
                    ▲ {{ $metrik['diff_selesai'] }}
                </span>
            </div>
            <p class="text-3xl font-black text-[#00264A] mt-3">{{ $metrik['selesai_produksi'] }}</p>
            <p class="text-[11px] font-extrabold text-slate-700 uppercase tracking-wide mt-0.5">Selesai Produksi</p>
            <p class="text-[10px] text-slate-400 font-semibold">Master siap tayang & QC</p>
        </div>

        <!-- 4. Tayang -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <span class="w-9 h-9 rounded-xl bg-emerald-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <span class="text-[10px] font-black text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200 flex items-center gap-0.5">
                    ▲ {{ $metrik['diff_tayang'] }}
                </span>
            </div>
            <p class="text-3xl font-black text-[#00264A] mt-3">{{ $metrik['tayang'] }}</p>
            <p class="text-[11px] font-extrabold text-slate-700 uppercase tracking-wide mt-0.5">Tayang (On-Air)</p>
            <p class="text-[10px] text-slate-400 font-semibold">Siaran reguler & khusus</p>
        </div>

        <!-- 5. Terlambat -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <span class="w-9 h-9 rounded-xl bg-rose-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <span class="text-[10px] font-black text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200 flex items-center gap-0.5" title="Penurunan keterlambatan sangat baik">
                    ▼ {{ $metrik['diff_terlambat'] }}
                </span>
            </div>
            <p class="text-3xl font-black text-rose-600 mt-3">{{ $metrik['terlambat'] }}</p>
            <p class="text-[11px] font-extrabold text-slate-700 uppercase tracking-wide mt-0.5">Terlambat</p>
            <p class="text-[10px] text-slate-400 font-semibold">Butuh evaluasi jadwal</p>
        </div>

        <!-- 6. Schedule Compliance -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <span class="w-9 h-9 rounded-xl bg-teal-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </span>
                <span class="text-[10px] font-black text-teal-700 bg-teal-50 px-2 py-0.5 rounded-full border border-teal-200">
                    TARGET {{ $metrik['target_compliance'] }}%
                </span>
            </div>
            <p class="text-3xl font-black text-[#00264A] mt-3">{{ $metrik['schedule_compliance'] }}%</p>
            <p class="text-[11px] font-extrabold text-slate-700 uppercase tracking-wide mt-0.5">Schedule Compliance</p>
            <p class="text-[10px] text-emerald-600 font-bold">Kepatuhan Sangat Baik</p>
        </div>
    </div>

    <!-- ===== TAHAP PRODUKSI PROGRAM & PERFORMA PENAYANGAN ===== -->
    <div id="tahap-produksi" class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        <!-- TAHAP PRODUKSI PROGRAM (6 TAHAP PIPELINE) -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
                <div>
                    <h2 class="text-sm font-black uppercase text-[#00264A] tracking-wider flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                        <span>Tahap Produksi Program TVRI Maluku</span>
                    </h2>
                    <p class="text-[11px] text-slate-400 font-semibold mt-0.5">Alur tahapan dari perencanaan hingga penayangan siaran</p>
                </div>
                <span class="text-xs font-bold text-purple-700 bg-purple-50 px-3 py-1 rounded-xl border border-purple-200">
                    Total: {{ $metrik['total_direncanakan'] }} Program Terdata
                </span>
            </div>

            <!-- Pipeline Visual Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-3">
                @foreach ($tahapProduksi as $tp)
                    <div class="p-3.5 rounded-2xl border border-slate-100 bg-slate-50/70 hover:bg-purple-50/40 transition-colors text-center flex flex-col justify-between">
                        <p class="text-[11px] font-black text-slate-700 uppercase tracking-tight">{{ $tp['nama'] }}</p>
                        <div class="my-2">
                            <span class="text-2xl font-black text-[#00264A]">{{ $tp['jumlah'] }}</span>
                            <span class="text-xs text-slate-400 font-bold block mt-0.5">{{ $tp['persen'] }}%</span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full {{ $tp['color'] }} rounded-full" style="width: {{ $tp['persen'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Progress Bars Detailed -->
            <div class="space-y-3 pt-2">
                @foreach ($tahapProduksi as $tp)
                    <div>
                        <div class="flex justify-between items-center text-xs font-bold text-slate-700 mb-1">
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full {{ $tp['color'] }}"></span>
                                <span>{{ $tp['nama'] }}</span>
                            </span>
                            <span class="font-mono text-[#00264A]">{{ $tp['jumlah'] }} Program <span class="text-slate-400 font-normal">({{ $tp['persen'] }}%)</span></span>
                        </div>
                        <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full {{ $tp['color'] }} rounded-full transition-all duration-500" style="width: {{ $tp['persen'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- PERFORMA PENAYANGAN -->
        <div id="evaluasi" class="lg:col-span-4 bg-gradient-to-br from-[#001D38] to-[#00264A] rounded-3xl p-6 text-white shadow-xl border border-blue-400/20 flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center justify-between border-b border-white/10 pb-3 mb-4">
                    <div>
                        <p class="text-[10px] text-cyan-300 font-extrabold uppercase tracking-widest">Broadcast Analytics</p>
                        <h3 class="text-base font-black uppercase text-white tracking-tight">Performa Penayangan</h3>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                        {{ $meta['periode'] ?? 'September 2025' }}
                    </span>
                </div>

                <div class="space-y-3.5">
                    <!-- Total Audience -->
                    <div class="p-3 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] text-blue-200 font-bold uppercase tracking-wider">Total Audience</p>
                            <p class="text-2xl font-black text-white mt-0.5">{{ $performa['total_audience'] }}</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-xl text-[11px] font-black bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                            ▲ {{ $performa['diff_audience'] }}
                        </span>
                    </div>

                    <!-- Rating Rata-rata -->
                    <div class="p-3 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] text-blue-200 font-bold uppercase tracking-wider">Rating Rata-rata</p>
                            <p class="text-2xl font-black text-amber-300 mt-0.5">{{ $performa['rating_rata'] }}</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-xl text-[11px] font-black bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                            ▲ {{ $performa['diff_rating'] }}
                        </span>
                    </div>

                    <!-- Share Siaran -->
                    <div class="p-3 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] text-blue-200 font-bold uppercase tracking-wider">Share Penayangan</p>
                            <p class="text-2xl font-black text-cyan-300 mt-0.5">{{ $performa['share'] }}</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-xl text-[11px] font-black bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                            ▲ {{ $performa['diff_share'] }}
                        </span>
                    </div>

                    <!-- Rata-rata Durasi Tonton -->
                    <div class="p-3 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] text-blue-200 font-bold uppercase tracking-wider">Rata-rata Durasi Tonton</p>
                            <p class="text-2xl font-black text-white mt-0.5">{{ $performa['durasi_tonton'] }}</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-xl text-[11px] font-black bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                            ▲ {{ $performa['diff_durasi'] }}
                        </span>
                    </div>
                </div>
            </div>

            <p class="text-[11px] text-blue-200/80 font-medium italic pt-2 border-t border-white/10">
                Peningkatan jangkauan siaran didukung optimalisasi jaringan digital di 11 kabupaten/kota Maluku.
            </p>
        </div>
    </div>

    <!-- ===== TREN PRODUKSI & PENAYANGAN (GRAFIK BULANAN) ===== -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
            <div>
                <h2 class="text-sm font-black uppercase text-[#00264A] tracking-wider flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                    <span>TREN PRODUKSI & PENAYANGAN PROGRAM</span>
                </h2>
                <p class="text-[11px] text-slate-400 font-semibold mt-0.5">Program Direncanakan vs Program Tayang vs Realisasi % &bull; Skala Jumlah: 0 - 80 &bull; Skala Persentase: 0% - 100%</p>
            </div>
            <div class="flex items-center gap-4 text-xs font-bold">
                <span class="flex items-center gap-1.5 text-purple-700">
                    <span class="w-3 h-3 rounded bg-purple-600 inline-block"></span>
                    <span>Direncanakan</span>
                </span>
                <span class="flex items-center gap-1.5 text-emerald-700">
                    <span class="w-3 h-3 rounded bg-emerald-600 inline-block"></span>
                    <span>Tayang (Realisasi)</span>
                </span>
                <span class="flex items-center gap-1.5 text-amber-700">
                    <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                    <span>Realisasi %</span>
                </span>
            </div>
        </div>

        <!-- Tabel & Visual Bar Chart Tren Bulanan -->
        <div class="overflow-x-auto">
            <div class="min-w-[650px] space-y-3 pt-2">
                <div class="grid grid-cols-9 gap-2 text-center text-xs">
                    @foreach ($trenBulan as $tb)
                        <div class="p-3 rounded-2xl border border-slate-100 bg-slate-50/70 hover:bg-blue-50/50 transition-all flex flex-col justify-between h-48">
                            <span class="text-xs font-black text-[#00264A] uppercase">{{ $tb['bulan'] }}</span>
                            
                            <!-- Dual Bar Visual -->
                            <div class="flex items-end justify-center gap-2 h-28 my-1">
                                <!-- Bar Rencana -->
                                <div class="w-4 bg-purple-200 hover:bg-purple-600 rounded-t-lg transition-all relative group" style="height: {{ round($tb['rencana'] / 80 * 100) }}%">
                                    <span class="absolute -top-5 left-1/2 -translate-x-1/2 text-[9px] font-black text-purple-800 opacity-0 group-hover:opacity-100 transition-opacity bg-white px-1 rounded shadow">{{ $tb['rencana'] }}</span>
                                </div>
                                <!-- Bar Tayang -->
                                <div class="w-4 bg-emerald-500 hover:bg-emerald-600 rounded-t-lg transition-all relative group" style="height: {{ round($tb['tayang'] / 80 * 100) }}%">
                                    <span class="absolute -top-5 left-1/2 -translate-x-1/2 text-[9px] font-black text-emerald-800 opacity-0 group-hover:opacity-100 transition-opacity bg-white px-1 rounded shadow">{{ $tb['tayang'] }}</span>
                                </div>
                            </div>

                            <div>
                                <p class="text-[11px] font-black text-[#00264A]">{{ $tb['tayang'] }} / {{ $tb['rencana'] }}</p>
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-black bg-amber-100 text-amber-800 inline-block mt-0.5">{{ $tb['realisasi'] }}%</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- ===== DISTRIBUSI JENIS PROGRAM & STATUS PROGRAM & TOP UNGGULAN ===== -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

        <!-- 1. DISTRIBUSI JENIS PROGRAM -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-black uppercase text-[#00264A] tracking-wider flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                    <span>Distribusi Jenis Program</span>
                </h3>
                <span class="text-xs font-black text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-200">{{ $totalProgramDb > 0 ? $totalProgramDb : 51 }} Program</span>
            </div>

            <div class="space-y-3">
                @foreach ($distribusiJenis as $dj)
                    <div>
                        <div class="flex justify-between items-center text-xs font-bold text-slate-700 mb-1">
                            <span>{{ $dj['jenis'] }}</span>
                            <span class="font-mono text-[#00264A]">{{ $dj['persen'] }}% <span class="text-slate-400 font-normal">({{ $dj['jumlah'] }})</span></span>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full {{ $dj['color'] }} rounded-full" style="width: {{ $dj['persen'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 2. STATUS PROGRAM -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-black uppercase text-[#00264A] tracking-wider flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                    <span>Status Program</span>
                </h3>
                <span class="text-xs font-black text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">{{ $totalProgramDb > 0 ? $totalProgramDb : 51 }} Program</span>
            </div>

            <div class="space-y-3">
                @foreach ($statusProgram as $sp)
                    <div class="p-3 rounded-2xl border border-slate-100 bg-slate-50/70 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="w-2.5 h-2.5 rounded-full {{ $sp['status'] === 'Tayang' ? 'bg-emerald-500' : ($sp['status'] === 'Terlambat' ? 'bg-rose-500' : 'bg-blue-500') }}"></span>
                            <div>
                                <p class="text-xs font-black text-[#00264A]">{{ $sp['status'] }}</p>
                                <p class="text-[10px] text-slate-400 font-semibold">{{ $sp['jumlah'] }} Program</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-2.5 py-1 rounded-xl text-xs font-black border {{ $sp['badge'] }}">{{ $sp['persen'] }}%</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 3. PROGRAM UNGGULAN (TOP 5 TAYANG BULAN INI) -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4 md:col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-black uppercase text-[#00264A] tracking-wider flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    <span>Program Unggulan</span>
                </h3>
                <span class="text-xs font-black text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">Top 5 Tayang</span>
            </div>

            <div class="space-y-2.5">
                @foreach ($programUnggulan as $idx => $pu)
                    <div class="p-3 rounded-2xl border border-slate-100 bg-slate-50/70 hover:bg-amber-50/40 transition-colors flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="w-7 h-7 rounded-xl bg-[#00264A] text-white flex items-center justify-center font-black text-xs shadow-sm">
                                {{ $idx + 1 }}
                            </span>
                            <div>
                                <p class="text-xs font-black text-[#00264A]">{{ $pu['nama'] }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $pu['kategori'] }} &bull; Rating: {{ $pu['rating'] }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-2.5 py-1 rounded-xl text-xs font-black bg-amber-100 text-amber-800 border border-amber-200">
                                {{ $pu['tayang'] }} Tayang
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- ===== DAFTAR PROGRAM BERJALAN ===== -->
    <div id="daftar-program" class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <div>
                <h2 class="text-sm font-black uppercase text-[#00264A] tracking-wider flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                    <span>DAFTAR PROGRAM BERJALAN TVRI MALUKU</span>
                </h2>
                <p class="text-[11px] text-slate-400 font-semibold mt-0.5">Daftar judul program, kategori, tahapan produksi, target jadwal tayang, dan penanggung jawab</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-500">{{ $totalProgramDb }} Program Terdaftar</span>
                @if($totalProgramDb === 0)
                    <form action="{{ route('tvri.program.reseed') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-blue-600 text-white text-xs font-bold hover:bg-blue-700">
                            Muat 51 Program Standar
                        </button>
                    </form>
                @endif
                <a href="{{ route('tvri.program.manage') }}" 
                   class="px-3 py-1.5 rounded-xl bg-purple-50 text-purple-700 hover:bg-purple-100 border border-purple-200 text-xs font-bold transition-colors">
                    Kelola / Tambah Data &rarr;
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-[10px] font-black uppercase tracking-wider text-slate-400 bg-slate-50/50">
                        <th class="py-3 px-3">No</th>
                        <th class="py-3 px-3">Judul Program</th>
                        <th class="py-3 px-3">Kategori</th>
                        <th class="py-3 px-3">Tahap</th>
                        <th class="py-3 px-3">Target Tayang</th>
                        <th class="py-3 px-3">Status</th>
                        <th class="py-3 px-3">PIC</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($programs as $index => $p)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-3 font-bold text-slate-400">{{ $programs->firstItem() + $index }}</td>
                            <td class="py-3 px-3 font-black text-[#00264A]">{{ $p->judul }}</td>
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">
                                    {{ $p->kategori }}
                                </span>
                            </td>
                            <td class="py-3 px-3 font-semibold text-slate-600">{{ $p->tahap }}</td>
                            <td class="py-3 px-3 font-mono text-[11px] text-slate-500">
                                {{ $p->target_tayang ? (is_string($p->target_tayang) ? substr($p->target_tayang, 0, 10) : $p->target_tayang->format('d M Y')) : '-' }}
                            </td>
                            <td class="py-3 px-3">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase border
                                    {{ $p->status === 'Tayang' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 
                                       ($p->status === 'Terlambat' ? 'bg-rose-50 text-rose-700 border-rose-200' : 
                                       ($p->status === 'Dalam Produksi' ? 'bg-purple-50 text-purple-700 border-purple-200' : 'bg-blue-50 text-blue-700 border-blue-200')) }}">
                                    {{ $p->status }}
                                </span>
                            </td>
                            <td class="py-3 px-3 font-bold text-slate-600">{{ $p->pic }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                <p class="font-bold text-sm text-slate-500">Belum ada data program yang terdaftar.</p>
                                <p class="text-xs text-slate-400 mt-1">Gunakan tombol "Input Data Program" atau klik tombol muat program di bawah.</p>
                                <form action="{{ route('tvri.program.reseed') }}" method="POST" class="mt-3">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 rounded-xl bg-purple-600 text-white text-xs font-bold hover:bg-purple-700 shadow-sm">
                                        Muat Ulang 51 Program TVRI Maluku
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($programs->hasPages())
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                {{ $programs->links() }}
            </div>
        @endif
    </div>

    <!-- ===== KALENDER PENAYANGAN & AGENDA PENTING ===== -->
    <div id="kalender" class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        <!-- AGENDA PENTING -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-sm font-black uppercase text-[#00264A] tracking-wider flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                        <span>AGENDA PENTING SIARAN & PRODUKSI</span>
                    </h3>
                    <p class="text-[11px] text-slate-400 font-semibold mt-0.5">Timeline jadwal penting penayangan, syuting, dan evaluasi September 2025</p>
                </div>
                <!-- Keterangan Warna -->
                <div class="flex flex-wrap items-center gap-2 text-[10px] font-black">
                    <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800">Tayang</span>
                    <span class="px-2 py-0.5 rounded-md bg-blue-100 text-blue-800">Produksi</span>
                    <span class="px-2 py-0.5 rounded-md bg-rose-100 text-rose-800">Deadline</span>
                    <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-800">Event Khusus</span>
                </div>
            </div>

            <div class="space-y-2.5">
                @foreach ($agendaPenting as $ap)
                    <div class="p-3.5 rounded-2xl border border-slate-100 bg-slate-50/70 hover:bg-slate-100 transition-colors flex items-center justify-between">
                        <div class="flex items-center space-x-3.5">
                            <div class="px-3 py-1.5 rounded-xl bg-[#00264A] text-white text-center flex-shrink-0">
                                <span class="text-xs font-black block leading-none">{{ $ap['tanggal'] }}</span>
                            </div>
                            <p class="text-xs font-black text-[#00264A]">{{ $ap['kegiatan'] }}</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-xl text-[10px] font-black border {{ $ap['badge'] }}">
                            {{ $ap['tipe'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- PROGRAM HIGHLIGHT -->
        <div class="lg:col-span-5 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-sm font-black uppercase text-[#00264A] tracking-wider flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                    <span>PROGRAM HIGHLIGHT TVRI MALUKU</span>
                </h3>
                <p class="text-[11px] text-slate-400 font-semibold mt-0.5">Program unggulan pembentuk karakter & citra TVRI Maluku</p>
            </div>

            <div class="space-y-2.5">
                @foreach ($highlights as $hl)
                    <div class="p-3.5 rounded-2xl bg-gradient-to-r {{ $hl['color'] }} text-white shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-black tracking-wider uppercase">{{ $hl['judul'] }}</p>
                            <p class="text-[11px] text-white/90 font-medium italic mt-0.5">{{ $hl['tagline'] }}</p>
                        </div>
                        <span class="w-8 h-8 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- ===== STANDAR OPERASIONAL PROSEDUR (SOP) PROGRAM & SIARAN ===== -->
    <div id="sop" class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-sm font-black uppercase text-[#00264A] tracking-wider flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-teal-600"></span>
                    <span>STANDAR OPERASIONAL PROSEDUR (SOP) PROGRAM & SIARAN</span>
                </h3>
                <p class="text-[11px] text-slate-400 font-semibold mt-0.5">Pedoman mutu, kepatuhan on-air, tata kelola produksi, dan quality control TVRI Maluku</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-teal-700 bg-teal-50 px-2.5 py-1 rounded-xl border border-teal-200">
                    {{ count($sops) }} Dokumen SOP Aktif
                </span>
                <a href="{{ route('tvri.program.manage', ['tab' => 'sop']) }}" class="text-xs font-bold text-teal-600 hover:text-teal-800 transition-colors">
                    Kelola / Tambah SOP &rarr;
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
            @forelse ($sops as $sop)
                <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/70 hover:bg-teal-50/40 hover:border-teal-200 transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-mono text-[10px] font-black text-teal-700 bg-teal-100/70 px-2 py-0.5 rounded-md">
                                {{ $sop->kode }}
                            </span>
                            <span class="text-[10px] font-bold text-slate-400">
                                {{ $sop->revisi }}
                            </span>
                        </div>
                        <h4 class="text-xs font-black text-[#00264A] leading-snug line-clamp-2">{{ $sop->judul }}</h4>
                        @if($sop->suara_siaran)
                            <div class="mt-2 flex items-center space-x-1 text-[10px] text-cyan-800 bg-cyan-50 px-2 py-0.5 rounded-md border border-cyan-100">
                                <svg class="w-3 h-3 text-cyan-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                                <span class="truncate font-semibold">{{ $sop->suara_siaran }}</span>
                            </div>
                        @endif
                        @if($sop->keterangan)
                            <p class="text-[10px] text-slate-400 font-medium mt-1 line-clamp-2 leading-relaxed">{{ $sop->keterangan }}</p>
                        @endif
                    </div>
                    <div class="flex items-center justify-between pt-3 mt-3 border-t border-slate-200/60">
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                            {{ $sop->kategori }}
                        </span>
                        <div class="flex items-center space-x-1.5">
                            @if($sop->file_url || $sop->file_path)
                                <a href="{{ asset($sop->file_url ?: $sop->file_path) }}" target="_blank" class="px-2 py-0.5 rounded-full text-[9px] font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 flex items-center space-x-1 transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <span>Fisik SOP</span>
                                </a>
                            @endif
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold {{ $sop->status === 'Aktif' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200' }}">
                                {{ $sop->status }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-6 text-center text-slate-400 text-xs">
                    Belum ada dokumen SOP yang diinputkan.
                </div>
            @endforelse
        </div>
    </div>

    <!-- ===== DOKUMEN & LAPORAN ===== -->
    <div id="laporan" class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-sm font-black uppercase text-[#00264A] tracking-wider flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-cyan-600"></span>
                    <span>DOKUMEN & LAPORAN PROGRAM</span>
                </h3>
                <p class="text-[11px] text-slate-400 font-semibold mt-0.5">Berkas penunjang operasional penyiaran & evaluasi resmi</p>
            </div>
            <a href="{{ route('dokumen.dashboard') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800">
                Pangkalan Dokumen &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
            @foreach ($dokumenLaporan as $dl)
                <a href="{{ $dl['rute'] }}" 
                   class="p-4 rounded-2xl border border-slate-100 bg-slate-50/70 hover:bg-purple-50/50 hover:border-purple-200 transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="w-8 h-8 rounded-xl bg-[#00264A] text-white flex items-center justify-center text-xs font-black shadow-sm group-hover:bg-purple-700 transition-colors">
                                {{ $dl['badge'] }}
                            </span>
                            <span class="text-[10px] font-black text-slate-400 uppercase group-hover:text-purple-600">Buka &rarr;</span>
                        </div>
                        <p class="text-xs font-black text-[#00264A]">{{ $dl['nama'] }}</p>
                        <p class="text-[10px] text-slate-400 font-medium mt-1 leading-snug">{{ $dl['desc'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- ===== SLOGAN & FOOTER NILAI UTAMA ===== -->
    <div class="bg-gradient-to-r from-[#001D38] via-[#00264A] to-[#003B70] rounded-3xl p-6 text-white text-center shadow-lg border border-purple-400/20 space-y-2">
        <p class="text-base sm:text-lg font-black tracking-wide text-amber-300 uppercase">
            {{ $meta['footer_slogan'] }}
        </p>
        <p class="text-xs sm:text-sm font-semibold text-blue-200 max-w-3xl mx-auto">
            <span class="text-white font-bold">Nilai Utama:</span> {{ $meta['footer_nilai'] }}
        </p>
    </div>

</div>
@endsection
