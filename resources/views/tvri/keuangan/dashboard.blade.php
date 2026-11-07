@extends('layouts.app')

@section('title', 'Dashboard Keuangan - TVRI Stasiun Maluku')

@php
    $formatRp = fn($val) => 'Rp ' . number_format($val, 0, ',', '.');
    $formatM = fn($val) => 'Rp ' . number_format($val / 1e9, 2, ',', '.') . ' M';
    $formatJt = fn($val) => 'Rp ' . number_format($val / 1e6, 0, ',', '.') . ' Jt';
@endphp

@section('content')
<div class="space-y-6 pb-12 scroll-mt-24" id="beranda">

    <!-- ===== HEADER INFOGRAFIS DASHBOARD KEUANGAN ===== -->
    <div class="relative bg-gradient-to-r from-[#001830] via-[#002B54] to-[#003B70] rounded-3xl p-6 sm:p-8 text-white shadow-xl overflow-hidden border border-blue-400/20">
        <!-- Glow accents -->
        <div class="absolute -right-12 -top-16 w-80 h-80 bg-teal-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-12 -bottom-16 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-6">
            <!-- Brand & Identity -->
            <div class="flex items-start sm:items-center space-x-4">
                <div class="w-16 h-16 rounded-2xl bg-white p-2 shadow-xl flex-shrink-0 flex items-center justify-center border-2 border-blue-300/40">
                    <img src="{{ asset('images/logo_tvri.png') }}" alt="Logo TVRI" class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-400/20 text-amber-300 border border-amber-400/30">
                            DARI MALUKU UNTUK INDONESIA
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-teal-400/20 text-teal-300 border border-teal-400/30">
                            BIDANG KEUANGAN & PNBP
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> SINKRON PANGKALAN DOKUMEN
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white mt-1 leading-tight uppercase">
                        DASHBOARD KEUANGAN TVRI STASIUN MALUKU
                    </h1>
                    <p class="text-xs text-blue-300/90 font-medium mt-1">
                        Transparan, Akuntabel, Efektif, untuk Layanan Publik yang Lebih Baik
                    </p>
                </div>
            </div>

            <!-- Core Values Pills & Meta Info -->
            <div class="flex flex-col sm:flex-row xl:flex-col items-start xl:items-end justify-between gap-4">
                <!-- Core values pills -->
                <div class="flex flex-wrap items-center gap-1.5">
                    @foreach(['Integritas', 'Profesional', 'Kolaboratif', 'Inovatif', 'Berdampak'] as $pillar)
                        <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-white/10 hover:bg-white/20 text-blue-100 border border-white/15 transition-colors shadow-sm">
                            {{ $pillar }}
                        </span>
                    @endforeach
                </div>

                <!-- Live Date & Dokumen Fast Link -->
                <div class="flex items-center space-x-3">
                    <div class="bg-white/10 backdrop-blur-md px-4 py-2 rounded-2xl border border-white/15 text-left xl:text-right">
                        <div class="flex items-center space-x-1.5 justify-start xl:justify-end">
                            <span class="text-[10px] text-teal-200 font-bold uppercase tracking-wider">Tahun Anggaran</span>
                            <select onchange="window.location.href='{{ route('tvri.keuangan.dashboard') }}?tahun=' + this.value" 
                                    class="bg-white/15 hover:bg-white/25 text-white font-extrabold text-[10px] border border-white/25 rounded-md px-1.5 py-0.5 focus:bg-[#002B54] focus:outline-none cursor-pointer transition-colors shadow-sm">
                                @foreach(range(date('Y') + 1, 2022) as $yr)
                                    <option value="{{ $yr }}" class="bg-[#002B54] text-white" {{ $tahun == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                                @endforeach
                            </select>
                        </div>
                        <p class="text-xs font-black text-white mt-0.5 global-live-datetime-bullet" id="liveClockWidget">{{ now()->isoFormat('dddd, D MMMM Y') }} &bull; {{ now()->format('H:i:s') }} WIT</p>
                    </div>

                    <a href="{{ route('dokumen.documents.index') }}" 
                       class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-2xl text-xs font-black bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white shadow-lg shadow-emerald-900/30 border border-emerald-300/30 transition-all hover:scale-[1.02]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Pangkalan Dokumen</span>
                    </a>

                    @php
                        $user = auth()->user();
                        $isKepalaStasiun = $user && ($user->hasRole('KEPALA_DINAS') || $user->hasRole('Kepala Stasiun') || $user->username === 'kepala.stasiun' || str_contains(strtolower($user->name ?? ''), 'kepala stasiun'));
                    @endphp
                    @if(!$isKepalaStasiun)
                    <a href="{{ route('tvri.keuangan.manage') }}" 
                       class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-2xl text-xs font-black bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white shadow-lg shadow-amber-900/30 border border-amber-300/30 transition-all hover:scale-[1.02]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        <span>Input & Kelola Data</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ===== MENU NAVIGASI KEUANGAN & SINKRONISASI PANGKALAN DOKUMEN ===== -->
    <div class="bg-white rounded-2xl p-2.5 border border-slate-200 shadow-sm flex items-center justify-between overflow-x-auto gap-2 sticky top-2 z-20 backdrop-blur-md bg-white/95">
        <div class="flex items-center space-x-1.5 flex-nowrap">
            <a href="#beranda" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-[#00264A] transition-colors whitespace-nowrap">
                Beranda
            </a>
            <a href="#realisasi-anggaran" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-[#00264A] transition-colors whitespace-nowrap">
                Realisasi Anggaran
            </a>
            <a href="#realisasi-pnbp" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-[#00264A] transition-colors whitespace-nowrap">
                Realisasi PNBP
            </a>
            <a href="#perencanaan-anggaran" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-[#00264A] transition-colors whitespace-nowrap">
                Perencanaan Anggaran
            </a>
            <a href="#pembayaran" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-[#00264A] transition-colors whitespace-nowrap">
                Pembayaran
            </a>
            <a href="#piutang-tagihan" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-[#00264A] transition-colors whitespace-nowrap">
                Piutang & Tagihan
            </a>
            <a href="#posisi-aset" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-[#00264A] transition-colors whitespace-nowrap">
                Aset & BMN
            </a>
            <a href="#laporan-keuangan" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-[#00264A] transition-colors whitespace-nowrap">
                Laporan Keuangan
            </a>
            <a href="#kinerja-ikpa" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-[#00264A] transition-colors whitespace-nowrap">
                Monitoring IKPA
            </a>
            <a href="#analisis-keuangan" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-[#00264A] transition-colors whitespace-nowrap">
                Analisis Keuangan
            </a>
            <a href="#manajemen-risiko" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-[#00264A] transition-colors whitespace-nowrap">
                Manajemen Risiko
            </a>
            <a href="#dokumen-sop" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-[#00264A] transition-colors whitespace-nowrap">
                Dokumen & SOP
            </a>
        </div>

        <div class="flex items-center space-x-2 pl-3 border-l border-slate-200 flex-shrink-0">
            @if(!$isKepalaStasiun)
            <a href="{{ route('tvri.keuangan.manage') }}" 
               class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-amber-50 text-amber-800 hover:bg-amber-100 transition-colors whitespace-nowrap border border-amber-200">
                <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <span>Input Data Keuangan</span>
            </a>
            @endif
            <a href="{{ route('dokumen.documents.index') }}" 
               class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors whitespace-nowrap">
                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                <span>Pangkalan Dokumen Keuangan</span>
            </a>
            <a href="{{ route('dokumen.documents.create') }}" 
               class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-xl text-xs font-bold bg-[#005B9F] text-white hover:bg-[#004780] transition-colors whitespace-nowrap shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Upload SPJ/DIPA</span>
            </a>
        </div>
    </div>

    <!-- ===== 6 KPI CARDS UTAMA ===== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 scroll-mt-24" id="perencanaan-anggaran">
        <!-- Card 1: Pagu Anggaran -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">Pagu Anggaran</span>
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#005B9F] flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <p class="text-xl xl:text-lg 2xl:text-xl font-black text-[#00264A] tracking-tight">
                    {{ $formatRp($paguAnggaran) }}
                </p>
                <div class="flex items-center justify-between mt-1 text-[11px]">
                    <span class="text-slate-500 font-semibold">Tahun Anggaran {{ $tahun }}</span>
                    <span class="px-1.5 py-0.5 rounded bg-blue-100 text-blue-700 font-bold text-[10px]">DIPA</span>
                </div>
            </div>
            <div class="mt-3 w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-blue-600 h-full rounded-full w-full"></div>
            </div>
        </div>

        <!-- Card 2: Realisasi Anggaran -->
        <div class="bg-white rounded-2xl p-4 border border-emerald-200/90 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-black uppercase tracking-wider text-emerald-700">Realisasi Anggaran</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <p class="text-xl xl:text-lg 2xl:text-xl font-black text-emerald-700 tracking-tight">
                    {{ $formatRp($realisasiAnggaran) }}
                </p>
                <div class="flex items-center justify-between mt-1 text-[11px]">
                    <span class="text-slate-500 font-semibold">Capaian Serapan</span>
                    <span class="px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800 font-extrabold text-[10px]">{{ $persenRealisasiAnggaran }}%</span>
                </div>
            </div>
            <div class="mt-3 w-full bg-emerald-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-emerald-500 h-full rounded-full" style="width: {{ $persenRealisasiAnggaran }}%"></div>
            </div>
        </div>

        <!-- Card 3: Sisa Anggaran -->
        <div class="bg-white rounded-2xl p-4 border border-amber-200/90 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-black uppercase tracking-wider text-amber-700">Sisa Anggaran</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <p class="text-xl xl:text-lg 2xl:text-xl font-black text-amber-700 tracking-tight">
                    {{ $formatRp($sisaAnggaran) }}
                </p>
                <div class="flex items-center justify-between mt-1 text-[11px]">
                    <span class="text-slate-500 font-semibold">Sisa Alokasi</span>
                    <span class="px-1.5 py-0.5 rounded bg-amber-100 text-amber-800 font-extrabold text-[10px]">{{ $persenSisaAnggaran }}%</span>
                </div>
            </div>
            <div class="mt-3 w-full bg-amber-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-amber-500 h-full rounded-full" style="width: {{ $persenSisaAnggaran }}%"></div>
            </div>
        </div>

        <!-- Card 4: Target PNBP -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-black uppercase tracking-wider text-indigo-700">Target PNBP</span>
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <p class="text-xl xl:text-lg 2xl:text-xl font-black text-indigo-900 tracking-tight">
                    {{ $formatRp($targetPnbp) }}
                </p>
                <div class="flex items-center justify-between mt-1 text-[11px]">
                    <span class="text-slate-500 font-semibold">Target Penerimaan</span>
                    <span class="px-1.5 py-0.5 rounded bg-indigo-100 text-indigo-700 font-bold text-[10px]">{{ $tahun }}</span>
                </div>
            </div>
            <div class="mt-3 w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-indigo-600 h-full rounded-full w-full"></div>
            </div>
        </div>

        <!-- Card 5: Realisasi PNBP -->
        <div class="bg-white rounded-2xl p-4 border border-teal-200/90 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-black uppercase tracking-wider text-teal-700">Realisasi PNBP</span>
                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <p class="text-xl xl:text-lg 2xl:text-xl font-black text-teal-700 tracking-tight">
                    {{ $formatRp($realisasiPnbp) }}
                </p>
                <div class="flex items-center justify-between mt-1 text-[11px]">
                    <span class="text-slate-500 font-semibold">Capaian PNBP</span>
                    <span class="px-1.5 py-0.5 rounded bg-teal-100 text-teal-800 font-extrabold text-[10px]">{{ $persenRealisasiPnbp }}%</span>
                </div>
            </div>
            <div class="mt-3 w-full bg-teal-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-teal-500 h-full rounded-full" style="width: {{ $persenRealisasiPnbp }}%"></div>
            </div>
        </div>

        <!-- Card 6: Piutang / Tagihan -->
        <div class="bg-white rounded-2xl p-4 border border-rose-200/90 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-black uppercase tracking-wider text-rose-700">Piutang / Tagihan</span>
                <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <p class="text-xl xl:text-lg 2xl:text-xl font-black text-rose-600 tracking-tight">
                    {{ $formatRp($piutangTagihan) }}
                </p>
                <div class="flex items-center justify-between mt-1 text-[11px]">
                    <span class="text-slate-500 font-semibold">Outstanding Tagihan</span>
                    <span class="px-1.5 py-0.5 rounded bg-rose-100 text-rose-800 font-extrabold text-[10px]">{{ count($piutangList) }} Klien</span>
                </div>
            </div>
            <div class="mt-3 w-full bg-rose-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-rose-500 h-full rounded-full w-2/5"></div>
            </div>
        </div>
    </div>

    <!-- ===== SECTION 1: TREND REALISASI & 2 KOMPOSISI CHARTS ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 scroll-mt-24" id="realisasi-anggaran">
        <!-- Trend Realisasi Anggaran dan PNBP {{ $tahun }} (Line Chart - 6 Kolom) -->
        <div class="lg:col-span-6 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">
                        Trend Realisasi Anggaran dan PNBP {{ $tahun }}
                    </h3>
                    <p class="text-xs text-slate-500">Pergerakan kumulatif per bulan (Jan - Des {{ $tahun }} dalam Miliar Rupiah)</p>
                </div>
                <span class="px-2.5 py-1 text-[10px] font-extrabold bg-blue-50 text-blue-700 rounded-lg border border-blue-200">
                    KUMULATIF
                </span>
            </div>
            <div class="relative w-full h-[280px]">
                <canvas id="trendChart"></canvas>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-center space-x-6 text-xs font-semibold text-slate-600">
                <div class="flex items-center space-x-1.5">
                    <span class="w-3 h-3 rounded-full bg-slate-400"></span>
                    <span>Pagu Kumulatif</span>
                </div>
                <div class="flex items-center space-x-1.5">
                    <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                    <span>Realisasi Anggaran</span>
                </div>
                <div class="flex items-center space-x-1.5">
                    <span class="w-3 h-3 rounded-full bg-teal-500"></span>
                    <span>Realisasi PNBP</span>
                </div>
            </div>
        </div>

        <!-- Komposisi Realisasi Anggaran (Donut Chart - 3 Kolom) -->
        <div class="lg:col-span-3 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="mb-2">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Komposisi Anggaran</h3>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">{{ number_format($persenRealisasiAnggaran, 1, ',', '.') }}%</span>
                </div>
                <p class="text-xs text-slate-500">Proporsi serapan {{ $formatM($realisasiAnggaran) }}</p>
            </div>
            <div class="relative w-full h-[220px] flex items-center justify-center">
                <canvas id="komposisiAnggaranChart"></canvas>
            </div>
            <div class="mt-2 space-y-1 text-xs">
                @foreach($rincianAnggaran as $item)
                    <div class="flex items-center justify-between py-0.5">
                        <div class="flex items-center space-x-1.5 truncate">
                            <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: {{ $item['color'] }}"></span>
                            <span class="text-slate-600 truncate">{{ $item['uraian'] }}</span>
                        </div>
                        <span class="font-bold text-slate-800">{{ $item['proporsi'] }}%</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Komposisi Realisasi PNBP (Donut Chart - 3 Kolom) -->
        <div class="lg:col-span-3 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between scroll-mt-24" id="realisasi-pnbp">
            <div class="mb-2">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Komposisi PNBP</h3>
                    <div class="flex items-center space-x-1.5">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-teal-100 text-teal-800">{{ number_format($persenRealisasiPnbp, 2, ',', '.') }}%</span>
                        @if(!$isKepalaStasiun)
                        <a href="{{ route('tvri.keuangan.manage', ['tahun' => $tahun, 'tab' => 'pnbp']) }}" 
                           class="text-[10px] font-bold text-teal-700 hover:text-teal-900 bg-teal-50 hover:bg-teal-100 px-2 py-0.5 rounded border border-teal-200 flex items-center space-x-1 transition-colors"
                           title="Kelola Data PNBP">
                            <span>Kelola</span>
                        </a>
                        @endif
                    </div>
                </div>
                <p class="text-xs text-slate-500">Penerimaan {{ $formatM($realisasiPnbp) }}</p>
            </div>
            <div class="relative w-full h-[220px] flex items-center justify-center">
                <canvas id="komposisiPnbpChart"></canvas>
            </div>
            <div class="mt-2 space-y-1 text-xs">
                @foreach($rincianPnbp as $item)
                    <div class="flex items-center justify-between py-0.5">
                        <div class="flex items-center space-x-1.5 truncate">
                            <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: {{ $item['color'] }}"></span>
                            <span class="text-slate-600 truncate">{{ $item['jenis'] }}</span>
                        </div>
                        <span class="font-bold text-slate-800">{{ $item['proporsi'] }}%</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- ===== SECTION 2: BAR CHARTS & IKPA SECTION ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 scroll-mt-24" id="kinerja-ikpa">
        <span id="monitoring-ikpa" class="sr-only"></span>
        <!-- Realisasi Anggaran per Jenis Belanja (Bar Chart - 4 Kolom) -->
        <div class="lg:col-span-4 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="mb-3">
                <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">
                    Realisasi per Jenis Belanja
                </h3>
                <p class="text-xs text-slate-500">Persentase capaian serapan anggaran</p>
            </div>
            <div class="relative w-full h-[250px]">
                <canvas id="belanjaBarChart"></canvas>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>Rata-rata Penyerapan Satker</span>
                <span class="font-bold text-emerald-600">{{ number_format($persenRealisasiAnggaran, 2, ',', '.') }}%</span>
            </div>
        </div>

        <!-- Target vs Realisasi PNBP (Grouped Bar - 4 Kolom) -->
        <div class="lg:col-span-4 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="mb-3">
                <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">
                    Target vs Realisasi PNBP
                </h3>
                <p class="text-xs text-slate-500">Perbandingan per kategori penerimaan (Juta Rupiah)</p>
            </div>
            <div class="relative w-full h-[250px]">
                <canvas id="pnbpCompareBarChart"></canvas>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-center space-x-6 text-xs text-slate-600 font-semibold">
                <div class="flex items-center space-x-1.5">
                    <span class="w-3 h-3 rounded bg-indigo-200"></span>
                    <span>Target</span>
                </div>
                <div class="flex items-center space-x-1.5">
                    <span class="w-3 h-3 rounded bg-teal-600"></span>
                    <span>Realisasi</span>
                </div>
            </div>
        </div>

        <!-- Kinerja IKPA (Gauge Donut & Tabel Indikator - 4 Kolom) -->
        <div class="lg:col-span-4 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">
                        Kinerja IKPA
                    </h3>
                    <p class="text-xs text-slate-500">Indikator Kinerja Pelaksanaan Anggaran</p>
                </div>
                <div class="flex items-center space-x-1.5">
                    <span class="px-2 py-0.5 rounded text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                        {{ $ikpaPredikat }}
                    </span>
                    @if(!$isKepalaStasiun)
                    <a href="{{ route('tvri.keuangan.manage', ['tahun' => $tahun, 'tab' => 'ringkasan']) }}" 
                       class="text-[10px] font-bold text-emerald-700 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100 px-2 py-0.5 rounded border border-emerald-200 flex items-center space-x-1 transition-colors"
                       title="Kelola Indikator IKPA">
                        <span>Kelola</span>
                    </a>
                    @endif
                </div>
            </div>

            <div class="flex items-center justify-center py-2">
                <div class="relative w-36 h-36 flex items-center justify-center">
                    <canvas id="ikpaGaugeChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                        <span class="text-2xl font-black text-[#00264A] tracking-tight">{{ $ikpaSkor }}</span>
                        <span class="text-[10px] font-bold text-emerald-600 uppercase">{{ $ikpaPredikat }}</span>
                    </div>
                </div>
            </div>

            <!-- Mini Indikator IKPA -->
            <div class="space-y-1.5 mt-2 border-t border-slate-100 pt-2 text-[11px]">
                <div class="flex items-center justify-between font-bold text-slate-400 text-[10px] uppercase">
                    <span>Indikator</span>
                    <span>Bobot / Skor</span>
                </div>
                @foreach($ikpaIndicators as $ind)
                    <div class="flex items-center justify-between py-0.5 border-b border-slate-50">
                        <span class="text-slate-700 truncate mr-2">{{ $ind['indikator'] }}</span>
                        <div class="flex items-center space-x-2 flex-shrink-0">
                            <span class="text-slate-400 text-[10px]">({{ $ind['bobot'] }})</span>
                            <span class="font-bold text-[#00264A]">{{ $ind['skor'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- ===== SECTION 3: TABEL RINCIAN REALISASI & PIUTANG ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 scroll-mt-24" id="piutang-tagihan">
        <div id="pembayaran" class="scroll-mt-24"></div>
        <!-- Tabel Rincian Realisasi Anggaran (6 Kolom) -->
        <div class="lg:col-span-6 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">
                            Rincian Realisasi Anggaran
                        </h3>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-xs text-slate-400 font-semibold">T.A. {{ $tahun }}</span>
                        @if(!$isKepalaStasiun)
                        <a href="{{ route('tvri.keuangan.manage', ['tahun' => $tahun, 'tab' => 'anggaran']) }}" 
                           class="text-[11px] font-bold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-2 py-0.5 rounded border border-blue-200 flex items-center space-x-1 transition-colors"
                           title="Kelola Data Anggaran">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <span>Kelola Anggaran</span>
                        </a>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-400 text-[10px] uppercase font-bold">
                                <th class="py-2">Kode</th>
                                <th class="py-2">Jenis Belanja</th>
                                <th class="py-2 text-right">Pagu</th>
                                <th class="py-2 text-right">Realisasi</th>
                                <th class="py-2 text-right">Capaian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($rincianAnggaran as $ang)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-2.5 font-bold text-slate-600">{{ $ang['kode'] }}</td>
                                    <td class="py-2.5 font-bold text-slate-800">{{ $ang['uraian'] }}</td>
                                    <td class="py-2.5 text-right text-slate-600">{{ $formatM($ang['pagu']) }}</td>
                                    <td class="py-2.5 text-right font-black text-[#00264A]">{{ $formatM($ang['realisasi']) }}</td>
                                    <td class="py-2.5 text-right">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-blue-50 text-blue-700">
                                            {{ $ang['persen'] }}%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="bg-blue-50/60 font-black text-slate-900 border-t-2 border-blue-200">
                                <td class="py-3" colspan="2">TOTAL KESELURUHAN</td>
                                <td class="py-3 text-right">{{ $formatM($paguAnggaran) }}</td>
                                <td class="py-3 text-right text-[#00264A]">{{ $formatM($realisasiAnggaran) }}</td>
                                <td class="py-3 text-right">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-emerald-100 text-emerald-800">
                                        {{ $persenRealisasiAnggaran }}%
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Status Serapan: <strong>Sesuai Target Triwulan IV</strong></span>
                <a href="{{ route('dokumen.documents.index') }}" class="text-blue-600 hover:text-blue-800 font-bold flex items-center space-x-1">
                    <span>Lihat LRA di Dokumen</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>

        <!-- Tabel Rincian PNBP & Piutang (6 Kolom) -->
        <div class="lg:col-span-6 space-y-6">
            <!-- Tabel PNBP -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span>
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">
                            Rincian Realisasi PNBP
                        </h3>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-xs text-slate-400 font-semibold">5 Sumber Pendapatan</span>
                        @if(!$isKepalaStasiun)
                        <a href="{{ route('tvri.keuangan.manage', ['tahun' => $tahun, 'tab' => 'pnbp']) }}" 
                           class="text-[11px] font-bold text-teal-700 hover:text-teal-900 bg-teal-50 hover:bg-teal-100 px-2 py-0.5 rounded border border-teal-200 flex items-center space-x-1 transition-colors"
                           title="Kelola Data PNBP">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <span>Kelola PNBP</span>
                        </a>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-400 text-[10px] uppercase font-bold">
                                <th class="py-2">Kategori PNBP</th>
                                <th class="py-2 text-right">Target</th>
                                <th class="py-2 text-right">Realisasi</th>
                                <th class="py-2 text-right">Capaian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($rincianPnbp as $pnbp)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-2 font-bold text-slate-800">{{ $pnbp['jenis'] }}</td>
                                    <td class="py-2 text-right text-slate-600">{{ $formatM($pnbp['target']) }}</td>
                                    <td class="py-2 text-right font-black text-teal-700">{{ $formatM($pnbp['realisasi']) }}</td>
                                    <td class="py-2 text-right">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-teal-50 text-teal-700">
                                            {{ $pnbp['persen'] }}%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabel Piutang / Tagihan -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">
                            Daftar Piutang / Tagihan
                        </h3>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-xs font-black text-rose-600">Total: {{ $formatJt($piutangTagihan) }}</span>
                        @if(!$isKepalaStasiun)
                        <a href="{{ route('tvri.keuangan.manage', ['tahun' => $tahun, 'tab' => 'piutang']) }}" 
                           class="text-[11px] font-bold text-rose-700 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 px-2 py-0.5 rounded border border-rose-200 flex items-center space-x-1 transition-colors"
                           title="Kelola Data Piutang">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <span>Kelola Piutang</span>
                        </a>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-400 text-[10px] uppercase font-bold">
                                <th class="py-2">Mitra / Klien</th>
                                <th class="py-2 text-right">Nilai Tagihan</th>
                                <th class="py-2">Jatuh Tempo</th>
                                <th class="py-2 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($piutangList as $piu)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-2 font-bold text-slate-800">{{ $piu['klien'] }}</td>
                                    <td class="py-2 text-right font-black text-slate-900">{{ $formatJt($piu['nilai']) }}</td>
                                    <td class="py-2 text-slate-500 font-semibold">{{ $piu['jatuh_tempo'] }}</td>
                                    <td class="py-2 text-center">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $piu['badge'] === 'danger' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700' }}">
                                            {{ $piu['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== SECTION 4: ARUS KAS & POSISI ASET ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 scroll-mt-24" id="arus-kas">
        <div id="laporan-keuangan" class="scroll-mt-24"></div>
        <!-- Arus Kas Bulanan Jan - Des (Bar Chart - 6 Kolom) -->
        <div class="lg:col-span-6 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">
                        Arus Kas (Cash Flow) {{ $tahun }}
                    </h3>
                    <p class="text-xs text-slate-500">Penerimaan vs Pengeluaran per Bulan (Miliar Rupiah)</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                        SURPLUS
                    </span>
                    @if(!$isKepalaStasiun)
                    <a href="{{ route('tvri.keuangan.manage', ['tahun' => $tahun, 'tab' => 'aruskas']) }}" 
                       class="text-[11px] font-bold text-emerald-700 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100 px-2 py-0.5 rounded border border-emerald-200 flex items-center space-x-1 transition-colors"
                       title="Kelola Data Arus Kas">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        <span>Kelola Arus Kas</span>
                    </a>
                    @endif
                </div>
            </div>
            <div class="relative w-full h-[260px]">
                <canvas id="cashFlowChart"></canvas>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-center space-x-6 text-xs text-slate-600 font-semibold">
                <div class="flex items-center space-x-1.5">
                    <span class="w-3 h-3 rounded bg-emerald-500"></span>
                    <span>Penerimaan</span>
                </div>
                <div class="flex items-center space-x-1.5">
                    <span class="w-3 h-3 rounded bg-rose-400"></span>
                    <span>Pengeluaran</span>
                </div>
                <div class="flex items-center space-x-1.5">
                    <span class="w-3 h-3 rounded bg-blue-600"></span>
                    <span>Saldo Akhir</span>
                </div>
            </div>
        </div>

        <!-- Posisi Aset & Kewajiban (6 Kolom) -->
        <div class="lg:col-span-6 grid grid-cols-1 sm:grid-cols-2 gap-4 scroll-mt-24" id="posisi-aset">
            <!-- Card Posisi Aset -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                        <div class="flex items-center space-x-2">
                            <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Posisi Aset</h3>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-[10px] font-bold text-slate-400">BMN</span>
                            @if(!$isKepalaStasiun)
                            <a href="{{ route('tvri.keuangan.manage', ['tahun' => $tahun, 'tab' => 'ringkasan']) }}" 
                               class="text-[11px] font-bold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-2 py-0.5 rounded border border-blue-200 flex items-center space-x-1 transition-colors"
                               title="Kelola Posisi Aset">
                                <span>Kelola Aset</span>
                            </a>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-600">Aset Lancar</span>
                            <span class="font-bold text-slate-800">Rp {{ $posisiAset['lancar'] }} M</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-600">Aset Tetap (BMN)</span>
                            <span class="font-bold text-slate-800">Rp {{ $posisiAset['tetap'] }} M</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-600">Aset Lainnya</span>
                            <span class="font-bold text-slate-800">Rp {{ $posisiAset['lainnya'] }} M</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t-2 border-blue-100 bg-blue-50/50 -mx-5 -mb-5 p-4 rounded-b-2xl flex items-center justify-between">
                    <span class="text-xs font-black text-[#00264A] uppercase">TOTAL ASET</span>
                    <span class="text-base font-black text-blue-700">Rp {{ $posisiAset['total'] }} M</span>
                </div>
            </div>

            <!-- Card Kewajiban & Ekuitas -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                        <div class="flex items-center space-x-2">
                            <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Kewajiban & Pasiva</h3>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400">PASIVA</span>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-600">Kewajiban Jk Pendek</span>
                            <span class="font-bold text-slate-800">Rp {{ $kewajibanEkuitas['kewajiban_lancar'] }} M</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-600">Kewajiban Jk Panjang</span>
                            <span class="font-bold text-slate-800">Rp {{ $kewajibanEkuitas['kewajiban_panjang'] }} M</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-600">Ekuitas Pemerintah</span>
                            <span class="font-bold text-slate-800">Rp {{ $kewajibanEkuitas['ekuitas'] }} M</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t-2 border-teal-100 bg-teal-50/50 -mx-5 -mb-5 p-4 rounded-b-2xl flex items-center justify-between">
                    <span class="text-xs font-black text-[#00264A] uppercase">TOTAL PASIVA</span>
                    <span class="text-base font-black text-teal-700">Rp {{ $kewajibanEkuitas['total_pasiva'] }} M</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== SECTION 5: RENCANA KEGIATAN & SINKRONISASI PANGKALAN DOKUMEN ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 scroll-mt-24" id="rencana-kegiatan">
        <!-- Rencana & Realisasi Kegiatan Keuangan (7 Kolom) -->
        <div class="lg:col-span-7 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">
                            Rencana & Realisasi Kegiatan Keuangan
                        </h3>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-xs text-slate-400 font-semibold">Tahun {{ $tahun }}</span>
                        @if(!$isKepalaStasiun)
                        <a href="{{ route('tvri.keuangan.manage', ['tahun' => $tahun, 'tab' => 'kegiatan']) }}" 
                           class="text-[11px] font-bold text-indigo-700 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-2 py-0.5 rounded border border-indigo-200 flex items-center space-x-1 transition-colors"
                           title="Kelola Kegiatan">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <span>Kelola Kegiatan</span>
                        </a>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-400 text-[10px] uppercase font-bold">
                                <th class="py-2 w-8">No</th>
                                <th class="py-2">Kegiatan Strategis</th>
                                <th class="py-2">Target</th>
                                <th class="py-2">Realisasi</th>
                                <th class="py-2 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($rencanaKegiatan as $act)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-2.5 font-bold text-slate-400">{{ $act['no'] }}</td>
                                    <td class="py-2.5 font-bold text-slate-800">{{ $act['kegiatan'] }}</td>
                                    <td class="py-2.5 text-slate-600 font-semibold">{{ $act['target'] }}</td>
                                    <td class="py-2.5 font-semibold text-slate-700">{{ $act['realisasi'] }}</td>
                                    <td class="py-2.5 text-center">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold {{ $act['badge'] === 'success' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $act['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Seluruh agenda berjalan tepat waktu untuk tutup buku akhir tahun.</span>
                <span class="text-emerald-600 font-bold">{{ count(array_filter($rencanaKegiatan, fn($k) => str_contains(strtolower($k['status']), 'track'))) }}/{{ count($rencanaKegiatan) }} On Track</span>
            </div>
        </div>

        <!-- SINKRONISASI PANGKALAN DOKUMEN KE KEUANGAN (5 Kolom) -->
        <div class="lg:col-span-5 bg-gradient-to-br from-slate-900 to-[#00264A] text-white rounded-2xl p-5 border border-blue-400/30 shadow-md flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-8 -bottom-8 w-44 h-44 bg-teal-500/10 rounded-full blur-2xl pointer-events-none"></div>

            <div>
                <div class="flex items-center justify-between mb-3 pb-3 border-b border-white/10">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-lg bg-teal-400/20 text-teal-300 flex items-center justify-center border border-teal-400/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white leading-tight">Pangkalan Dokumen Keuangan</h3>
                            <p class="text-[10px] text-teal-300 font-semibold">Tersinkronisasi Real-Time dengan DMS</p>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-black bg-teal-500/30 text-teal-200 border border-teal-400/30">
                        DMS TVRI
                    </span>
                </div>

                <p class="text-xs text-blue-200 leading-relaxed mb-3">
                    Akses langsung arsip bukti kas, SPJ, DIPA, LRA, LPJ, dan berkas kepatuhan BPK/Inspektorat melalui pangkalan dokumen terintegrasi:
                </p>

                <!-- List Berkas Dokumen Keuangan Terakhir -->
                <div class="space-y-2 mb-4">
                    @forelse($dokumenKeuangan as $doc)
                        <a href="{{ route('dokumen.documents.show', $doc->id) }}" class="flex items-center justify-between p-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition-colors text-xs">
                            <div class="flex items-center space-x-2 truncate">
                                <span class="text-teal-300 font-bold">📄</span>
                                <span class="text-white truncate font-medium">{{ $doc->title }}</span>
                            </div>
                            <span class="text-[10px] text-blue-300 font-semibold flex-shrink-0 ml-2">{{ $doc->created_at ? $doc->created_at->format('d M') : 'Baru' }}</span>
                        </a>
                    @empty
                        <div class="p-2.5 rounded-xl bg-white/5 border border-white/10 text-xs text-blue-300 text-center">
                            DIPA {{ $tahun }}, LRA TW-III, dan LPJ Operasional siap diakses.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Action Buttons Menuju Pangkalan Dokumen -->
            <div class="space-y-2 pt-2 border-t border-white/10">
                <a href="{{ route('dokumen.documents.index') }}" 
                   class="w-full inline-flex items-center justify-center space-x-2 px-4 py-2.5 rounded-xl text-xs font-black bg-[#005B9F] hover:bg-[#004780] text-white transition-all shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <span>Jelajahi Seluruh Pangkalan Dokumen</span>
                </a>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('dokumen.folders.index') }}" 
                       class="inline-flex items-center justify-center space-x-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-white/10 hover:bg-white/20 text-white border border-white/15 transition-colors">
                        <svg class="w-3.5 h-3.5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                        <span>Folder Explorer</span>
                    </a>
                    <a href="{{ route('dokumen.documents.create') }}" 
                       class="inline-flex items-center justify-center space-x-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-emerald-600/30 hover:bg-emerald-600/50 text-emerald-200 border border-emerald-400/30 transition-colors">
                        <svg class="w-3.5 h-3.5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span>Upload Berkas</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== SECTION: ANALISIS KEUANGAN & MANAJEMEN RISIKO ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 scroll-mt-24" id="analisis-keuangan">
        <!-- Analisis Keuangan (6 Kolom) -->
        <div class="lg:col-span-6 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                    <div class="flex items-center space-x-2">
                        <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Analisis Keuangan & Rasio Kinerja</h3>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-teal-100 text-teal-800">Sehat & Stabil</span>
                </div>

                <div class="space-y-3">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-800">Rasio Likuiditas Kas</p>
                            <p class="text-[10px] text-slate-500">Kemampuan aset lancar menjamin kewajiban jangka pendek</p>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-black text-emerald-600">328,9%</span>
                            <span class="block text-[9px] font-semibold text-slate-400">Sangat Likuid</span>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-800">Efektivitas Belanja Operasional</p>
                            <p class="text-[10px] text-slate-500">Realisasi belanja barang & modal pendukung penyiaran</p>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-black text-blue-600">76,19%</span>
                            <span class="block text-[9px] font-semibold text-slate-400">On Track Q4</span>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-800">Rasio Solvabilitas (Ekuitas / Pasiva)</p>
                            <p class="text-[10px] text-slate-500">Ketahanan modal bersih terhadap seluruh kewajiban</p>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-black text-teal-600">94,18%</span>
                            <span class="block text-[9px] font-semibold text-slate-400">Sangat Kuat</span>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-800">Rasio Kolektibilitas Tagihan</p>
                            <p class="text-[10px] text-slate-500">Persentase piutang mitra lancar terhadap target PNBP</p>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-black text-amber-600">89,0%</span>
                            <span class="block text-[9px] font-semibold text-slate-400">Terkendali</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Rekomendasi: <strong>Prioritaskan percepatan SPJ belanja modal di akhir tahun</strong></span>
            </div>
        </div>

        <!-- Manajemen Risiko Keuangan (6 Kolom) -->
        <div class="lg:col-span-6 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between scroll-mt-24" id="manajemen-risiko">
            <div>
                <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                    <div class="flex items-center space-x-2">
                        <div class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Manajemen Risiko Keuangan</h3>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">Mitigasi Aktif</span>
                </div>

                <div class="space-y-2.5">
                    <div class="p-2.5 rounded-xl border border-slate-200 flex items-start space-x-3">
                        <span class="px-2 py-0.5 rounded text-[9px] font-black bg-rose-100 text-rose-700 flex-shrink-0 mt-0.5">TINGGI</span>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-slate-800">Deviasi Halaman III DIPA</p>
                            <p class="text-[10px] text-slate-500">Risiko ketidaksesuaian jadwal penarikan dana dengan RPD bulanan KPPN.</p>
                            <p class="text-[10px] text-teal-700 font-semibold mt-0.5">Mitigasi: Pemutakhiran RPD tiap awal triwulan via aplikasi SAKTI.</p>
                        </div>
                    </div>

                    <div class="p-2.5 rounded-xl border border-slate-200 flex items-start space-x-3">
                        <span class="px-2 py-0.5 rounded text-[9px] font-black bg-amber-100 text-amber-700 flex-shrink-0 mt-0.5">SEDANG</span>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-slate-800">Outstanding Piutang Iklan & Sewa Aset</p>
                            <p class="text-[10px] text-slate-500">Keterlambatan pembayaran termin oleh mitra kerja sama daerah.</p>
                            <p class="text-[10px] text-teal-700 font-semibold mt-0.5">Mitigasi: Penagihan terjadwal & rekonsiliasi berkala dengan Bidang Usaha.</p>
                        </div>
                    </div>

                    <div class="p-2.5 rounded-xl border border-slate-200 flex items-start space-x-3">
                        <span class="px-2 py-0.5 rounded text-[9px] font-black bg-emerald-100 text-emerald-700 flex-shrink-0 mt-0.5">RENDAH</span>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-slate-800">Kepatuhan Rekonsiliasi BMN & Neraca</p>
                            <p class="text-[10px] text-slate-500">Perbedaan pencatatan mutasi aset tetap dan persediaan modul GLP.</p>
                            <p class="text-[10px] text-teal-700 font-semibold mt-0.5">Mitigasi: Rekon internal bulanan antara Bendahara Material & Akuntansi.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Seluruh risiko terpantau dalam SPIP & WBK TVRI Stasiun Maluku</span>
            </div>
        </div>
    </div>

    <!-- ===== SECTION: DOKUMEN & SOP BIDANG KEUANGAN ===== -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4 scroll-mt-24" id="dokumen-sop">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-slate-100">
            <div>
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 rounded-full bg-teal-600"></span>
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">
                        Dokumen & Prosedur Operasional Standar (SOP) Bidang Keuangan
                    </h3>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Standar operasional pengelolaan anggaran, PNBP, perbendaharaan, rekonsiliasi, dan pelaporan keuangan TVRI Stasiun Maluku
                </p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-teal-50 text-teal-700 border border-teal-200">
                    {{ count($sops ?? []) }} SOP Aktif
                </span>
                @if(!$isKepalaStasiun)
                <a href="{{ route('tvri.keuangan.manage') }}?tab=sop" 
                   class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-teal-600 hover:bg-teal-700 text-white shadow-sm transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Kelola SOP</span>
                </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($sops ?? [] as $sop)
                <div class="p-4 rounded-2xl bg-gradient-to-br from-slate-50 to-teal-50/20 border border-slate-200/90 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-mono font-black bg-teal-100 text-teal-800 border border-teal-200">
                                {{ $sop->kode }}
                            </span>
                            <div class="flex items-center space-x-1.5">
                                <span class="text-[9px] font-mono text-slate-400 font-bold">{{ $sop->versi }}</span>
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-100 text-emerald-800">
                                    {{ $sop->status }}
                                </span>
                            </div>
                        </div>
                        <h4 class="text-xs font-bold text-slate-900 line-clamp-2 leading-snug">
                            {{ $sop->judul }}
                        </h4>
                        <p class="text-[10px] text-slate-500 mt-1 line-clamp-3 leading-relaxed">
                            {{ $sop->keterangan ?? 'Dokumen SOP resmi tata kelola keuangan satker TVRI Stasiun Maluku.' }}
                        </p>
                    </div>

                    <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[10px]">
                        <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-semibold truncate max-w-[150px]">
                            {{ $sop->kategori }}
                        </span>
                        <div class="flex items-center space-x-2">
                            @if($sop->file_url || $sop->file_path)
                                <a href="{{ asset($sop->file_url ?: $sop->file_path) }}" target="_blank" class="font-bold text-emerald-600 hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-2 py-0.5 rounded border border-emerald-200 flex items-center space-x-1 transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <span>Fisik SOP</span>
                                </a>
                            @endif
                        @if(!$isKepalaStasiun)
                        <a href="{{ route('tvri.keuangan.manage') }}?tab=sop" class="text-teal-700 hover:text-teal-900 font-bold flex items-center space-x-1">
                            <span>Kelola</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                        @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-6 text-center text-slate-400">
                    <p class="text-xs font-bold">Belum ada SOP yang dimuat.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- ===== SECTION 6: ZONA INTEGRITAS BANNER ===== -->
    <div class="bg-gradient-to-r from-[#001D38] via-[#00264A] to-[#003B70] rounded-3xl p-6 sm:p-7 text-white shadow-xl relative overflow-hidden border border-blue-400/20" id="zona-integritas">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-5">
            <div>
                <div class="flex items-center space-x-2.5">
                    <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-amber-400/20 text-amber-300 border border-amber-400/30">
                        ZONA INTEGRITAS
                    </span>
                    <span class="text-xs font-black tracking-widest text-blue-200 uppercase">
                        Menuju Wilayah Bebas dari Korupsi (WBK)
                    </span>
                </div>
                <h3 class="text-lg font-black text-white mt-1.5">
                    Keuangan yang Sehat, untuk Layanan Publik yang Kuat
                </h3>
                <p class="text-xs text-blue-200/90 font-medium max-w-2xl mt-0.5">
                    Komitmen penuh TVRI Stasiun Maluku dalam mewujudkan akuntabilitas perbendaharaan, tertib administrasi PNBP, dan transparansi anggaran berbasis integritas.
                </p>

                <!-- 5 Pillars -->
                <div class="flex flex-wrap items-center gap-2 mt-3">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/10 text-white border border-white/15">1. Transparansi</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/10 text-white border border-white/15">2. Akuntabilitas</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/10 text-white border border-white/15">3. Efisiensi</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/10 text-white border border-white/15">4. Pelayanan Prima</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/10 text-white border border-white/15">5. Kepatuhan Regulasi</span>
                </div>
            </div>

            <!-- Maluku Slogan Tagline -->
            <div class="text-left md:text-right flex-shrink-0">
                <p class="text-xs font-bold text-teal-300 tracking-wider uppercase">DARI MALUKU UNTUK INDONESIA</p>
                <p class="text-sm font-black text-white mt-0.5">Maluku Lebih Baik Bersama</p>
                <p class="text-[10px] text-blue-300 mt-0.5">© 2026 TVRI Stasiun Maluku</p>
            </div>
        </div>
    </div>

</div>

<!-- ===== SCRIPT CHART.JS ===== -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Trend Realisasi Anggaran dan PNBP {{ $tahun }}
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
    
    // Anggaran Kumulatif Dinamis (Miliar)
    const paguKumulatif = {!! json_encode($trendPaguKumulatif ?? [3.8, 7.6, 11.4, 15.2, 19.0, 22.8, 26.6, 30.5, 34.3, 38.1, 41.9, 45.75]) !!};
    const realisasiAnggaranKum = {!! json_encode($trendAnggaranKumulatif ?? [2.5, 5.2, 8.8, 12.1, 15.6, 19.4, 23.0, 26.8, 29.5, 32.2, 34.36, null]) !!};
    const realisasiPnbpKum = {!! json_encode($trendPnbpKumulatif ?? [0.2, 0.45, 0.72, 1.05, 1.35, 1.70, 2.01, 2.25, 2.50, 2.70, 2.85, null]) !!};

    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Pagu Anggaran Kumulatif',
                    data: paguKumulatif,
                    borderColor: '#94a3b8',
                    borderDash: [5, 5],
                    borderWidth: 2,
                    pointRadius: 2,
                    tension: 0.2,
                    fill: false
                },
                {
                    label: 'Realisasi Anggaran',
                    data: realisasiAnggaranKum,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.08)',
                    borderWidth: 3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Realisasi PNBP',
                    data: realisasiPnbpKum,
                    borderColor: '#0d9488',
                    backgroundColor: 'rgba(13, 148, 136, 0.08)',
                    borderWidth: 2.5,
                    pointRadius: 3,
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            return ctx.dataset.label + ': Rp ' + ctx.raw + ' Miliar';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: val => 'Rp ' + val + ' M'
                    },
                    grid: { color: '#f1f5f9' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // 2. Komposisi Realisasi Anggaran (Donut Chart)
    const kompAngCtx = document.getElementById('komposisiAnggaranChart').getContext('2d');
    new Chart(kompAngCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_column($rincianAnggaran, 'uraian')) !!},
            datasets: [{
                data: {!! json_encode(array_column($rincianAnggaran, 'proporsi')) !!},
                backgroundColor: {!! json_encode(array_column($rincianAnggaran, 'color')) !!},
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
                    callbacks: {
                        label: ctx => ctx.label + ': ' + ctx.raw + '%'
                    }
                }
            },
            cutout: '72%'
        }
    });

    // 3. Komposisi Realisasi PNBP (Donut Chart)
    const kompPnbpCtx = document.getElementById('komposisiPnbpChart').getContext('2d');
    new Chart(kompPnbpCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_column($rincianPnbp, 'jenis')) !!},
            datasets: [{
                data: {!! json_encode(array_column($rincianPnbp, 'proporsi')) !!},
                backgroundColor: {!! json_encode(array_column($rincianPnbp, 'color')) !!},
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
                    callbacks: {
                        label: ctx => ctx.label + ': ' + ctx.raw + '%'
                    }
                }
            },
            cutout: '72%'
        }
    });

    // 4. Realisasi Anggaran per Jenis Belanja (Horizontal Bar)
    const belanjaCtx = document.getElementById('belanjaBarChart').getContext('2d');
    new Chart(belanjaCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_column($rincianAnggaran, 'uraian')) !!},
            datasets: [{
                label: 'Persentase Capaian (%)',
                data: {!! json_encode(array_column($rincianAnggaran, 'persen')) !!},
                backgroundColor: {!! json_encode(array_column($rincianAnggaran, 'color')) !!},
                borderRadius: 6
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.raw + '%'
                    }
                }
            },
            scales: {
                x: {
                    max: 100,
                    beginAtZero: true,
                    ticks: { callback: val => val + '%' },
                    grid: { color: '#f1f5f9' }
                },
                y: { grid: { display: false } }
            }
        }
    });

    // 5. Target vs Realisasi PNBP (Grouped Bar Chart)
    const pnbpCtx = document.getElementById('pnbpCompareBarChart').getContext('2d');
    new Chart(pnbpCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_map(fn($p) => Str::limit($p['jenis'], 14), $rincianPnbp)) !!},
            datasets: [
                {
                    label: 'Target (Jt)',
                    data: {!! json_encode(array_map(fn($p) => round($p['target'] / 1e6, 0), $rincianPnbp)) !!},
                    backgroundColor: '#c7d2fe',
                    borderRadius: 4
                },
                {
                    label: 'Realisasi (Jt)',
                    data: {!! json_encode(array_map(fn($p) => round($p['realisasi'] / 1e6, 0), $rincianPnbp)) !!},
                    backgroundColor: '#0d9488',
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.dataset.label + ': Rp ' + ctx.raw + ' Jt'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: val => val + ' Jt' },
                    grid: { color: '#f1f5f9' }
                },
                x: { grid: { display: false } }
            }
        }
    });

    // 6. IKPA Gauge Chart
    const ikpaCtx = document.getElementById('ikpaGaugeChart').getContext('2d');
    new Chart(ikpaCtx, {
        type: 'doughnut',
        data: {
            labels: ['Skor IKPA', 'Sisa'],
            datasets: [{
                data: [{{ $ikpaSkor }}, {{ max(0, 100 - $ikpaSkor) }}],
                backgroundColor: ['#10b981', '#e2e8f0'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            circumference: 240,
            rotation: 240,
            cutout: '80%',
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            }
        }
    });

    // 7. Arus Kas (Cash Flow) Bar Chart
    const cashCtx = document.getElementById('cashFlowChart').getContext('2d');
    const cashData = @json($cashFlow);
    new Chart(cashCtx, {
        type: 'bar',
        data: {
            labels: cashData.map(c => c.bulan),
            datasets: [
                {
                    label: 'Penerimaan (M)',
                    data: cashData.map(c => c.masuk),
                    backgroundColor: '#10b981',
                    borderRadius: 3
                },
                {
                    label: 'Pengeluaran (M)',
                    data: cashData.map(c => c.keluar),
                    backgroundColor: '#fb7185',
                    borderRadius: 3
                },
                {
                    type: 'line',
                    label: 'Saldo (M)',
                    data: cashData.map(c => c.saldo),
                    borderColor: '#2563eb',
                    backgroundColor: '#2563eb',
                    borderWidth: 2,
                    pointRadius: 2,
                    tension: 0.2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.dataset.label + ': Rp ' + ctx.raw + ' M'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: v => v + ' M' },
                    grid: { color: '#f1f5f9' }
                },
                x: { grid: { display: false } }
            }
        }
    });

    // Real-Time Live Clock Widget (WIT - UTC+9)
    function updateLiveClock() {
        const el = document.getElementById('liveClockWidget');
        if (!el) return;
        const now = new Date();
        const optionsDate = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', timeZone: 'Asia/Jayapura' };
        const optionsTime = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false, timeZone: 'Asia/Jayapura' };
        try {
            const dateStr = new Intl.DateTimeFormat('id-ID', optionsDate).format(now);
            const timeStr = new Intl.DateTimeFormat('id-ID', optionsTime).format(now).replace(/\./g, ':');
            el.innerHTML = `${dateStr} &bull; ${timeStr} WIT`;
        } catch (e) {
            el.innerHTML = now.toLocaleString('id-ID') + ' WIT';
        }
    }
    setInterval(updateLiveClock, 1000);
    updateLiveClock();
});
</script>
@endsection
