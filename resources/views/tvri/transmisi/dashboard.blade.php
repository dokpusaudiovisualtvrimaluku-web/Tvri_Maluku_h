@extends('layouts.app')

@section('title', 'DASHBOARD TRANSMISI TVRI MALUKU')

@section('content')
<div class="space-y-6">

    <!-- ===== HEADER & INFORMASI UTAMA ===== -->
    <div class="bg-gradient-to-r from-[#001D38] via-[#00264A] to-[#003B70] rounded-3xl p-6 md:p-8 text-white shadow-2xl relative overflow-hidden border border-cyan-400/20">
        <div class="absolute -right-10 -top-16 w-72 h-72 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-10 -bottom-16 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="flex items-start md:items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-white p-2.5 shadow-xl flex-shrink-0 border border-blue-200/50">
                    <img src="{{ asset('images/logo_tvri.png') }}" alt="Logo TVRI" class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-cyan-500/20 text-cyan-200 border border-cyan-400/30">
                            {{ $ringkasan->periode }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-xs text-blue-200/90 font-mono">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="global-live-datetime-pipe">{{ $ringkasan->waktu_status }}</span>
                        </span>
                    </div>
                    <h1 class="text-xl md:text-2xl font-black tracking-tight uppercase leading-tight">{{ $ringkasan->judul }}</h1>
                    <p class="text-xs md:text-sm font-extrabold text-cyan-300 tracking-wider mt-1">{{ $ringkasan->slogan }}</p>
                    <p class="text-xs font-semibold text-amber-300/90 mt-0.5">&bull; {{ $ringkasan->slogan_tambahan }}</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                @php
                    $currentUser = auth()->user();
                    $isKetuaTimTransmisi = $currentUser && (
                        in_array($currentUser->username, ['transmisi', 'teknik.transmisi']) ||
                        $currentUser->email === 'transmisi@tvri.go.id' ||
                        (method_exists($currentUser, 'hasRole') && $currentUser->hasRole('Teknik Transmisi')) ||
                        str_contains(strtolower($currentUser->name ?? ''), 'transmisi')
                    );
                @endphp
                @if($isKetuaTimTransmisi)
                <a href="{{ route('tvri.transmisi.manage') }}" class="px-4 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold transition-all shadow-md shadow-cyan-950/30 flex items-center gap-2 border border-cyan-400/40">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Input & Kelola Data Transmisi</span>
                </a>
                @endif
                <a href="{{ route('tvri.ecc.dashboard') }}" class="px-4 py-2.5 rounded-xl bg-white/10 border border-white/20 text-xs font-bold hover:bg-white/20 transition-all text-white backdrop-blur-md">
                    &larr; ECC Kepala Stasiun
                </a>
            </div>
        </div>
    </div>

    <!-- ===== KEY PERFORMANCE INDICATORS (KPI) ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
        <!-- Total Site Pemancar -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="w-8 h-8 rounded-xl bg-cyan-600 flex items-center justify-center text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                </span>
                <span class="text-[9px] font-extrabold text-cyan-700 bg-cyan-50 px-2 py-0.5 rounded-full border border-cyan-200">SITE</span>
            </div>
            <div class="mt-2.5">
                <p class="text-2xl font-black text-[#00264A]">{{ $siteTotal }}</p>
                <p class="text-[11px] font-bold text-slate-700 leading-tight">Total Site Pemancar</p>
                <p class="text-[10px] text-emerald-600 font-bold mt-0.5">{{ $ringkasan->total_site_diff }}</p>
            </div>
        </div>

        <!-- Siaran On-Air -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="w-8 h-8 rounded-xl bg-emerald-600 flex items-center justify-center text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <span class="text-[9px] font-extrabold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">ON-AIR</span>
            </div>
            <div class="mt-2.5">
                <p class="text-2xl font-black text-[#00264A]">{{ $ringkasan->on_air_pct }}%</p>
                <p class="text-[11px] font-bold text-slate-700 leading-tight">Siaran On-Air</p>
                <p class="text-[10px] text-slate-500 font-semibold mt-0.5">Target {{ $ringkasan->on_air_target }}%</p>
            </div>
        </div>

        <!-- Cakupan Wilayah -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="w-8 h-8 rounded-xl bg-blue-600 flex items-center justify-center text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
                </span>
                <span class="text-[9px] font-extrabold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200">COVERAGE</span>
            </div>
            <div class="mt-2.5">
                <p class="text-2xl font-black text-[#00264A]">{{ $ringkasan->cakupan_wilayah_pct }}%</p>
                <p class="text-[11px] font-bold text-slate-700 leading-tight">Cakupan Wilayah</p>
                <p class="text-[10px] text-emerald-600 font-bold mt-0.5">{{ $ringkasan->cakupan_diff }}</p>
            </div>
        </div>

        <!-- Kualitas Rata-rata -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="w-8 h-8 rounded-xl bg-indigo-600 flex items-center justify-center text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </span>
                <span class="text-[9px] font-extrabold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-200">SKOR</span>
            </div>
            <div class="mt-2.5">
                <p class="text-2xl font-black text-[#00264A]">{{ number_format($kualitasRata, 1) }} <span class="text-xs text-slate-400 font-semibold">/ 5</span></p>
                <p class="text-[11px] font-bold text-slate-700 leading-tight">Kualitas Rata-rata</p>
                <p class="text-[10px] text-emerald-600 font-bold mt-0.5">{{ $ringkasan->kualitas_diff }}</p>
            </div>
        </div>

        <!-- Gangguan Aktif -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="w-8 h-8 rounded-xl bg-rose-600 flex items-center justify-center text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </span>
                <span class="text-[9px] font-extrabold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-200">ALERT</span>
            </div>
            <div class="mt-2.5">
                <p class="text-2xl font-black text-[#00264A]">{{ $gangguanAktif }}</p>
                <p class="text-[11px] font-bold text-slate-700 leading-tight">Gangguan Aktif</p>
                <p class="text-[10px] text-emerald-600 font-bold mt-0.5">{{ $ringkasan->gangguan_diff }}</p>
            </div>
        </div>

        <!-- Pemeliharaan Berjalan -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="w-8 h-8 rounded-xl bg-amber-500 flex items-center justify-center text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </span>
                <span class="text-[9px] font-extrabold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">MAINT</span>
            </div>
            <div class="mt-2.5">
                <p class="text-2xl font-black text-[#00264A]">{{ $ringkasan->pemeliharaan_berjalan }}</p>
                <p class="text-[11px] font-bold text-slate-700 leading-tight">Pemeliharaan</p>
                <p class="text-[10px] text-emerald-600 font-bold mt-0.5">{{ $ringkasan->pemeliharaan_diff }}</p>
            </div>
        </div>

        <!-- Efisiensi Daya -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="w-8 h-8 rounded-xl bg-teal-600 flex items-center justify-center text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </span>
                <span class="text-[9px] font-extrabold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-full border border-teal-200">POWER</span>
            </div>
            <div class="mt-2.5">
                <p class="text-2xl font-black text-[#00264A]">{{ $ringkasan->efisiensi_daya_pct }}%</p>
                <p class="text-[11px] font-bold text-slate-700 leading-tight">Efisiensi Daya</p>
                <p class="text-[10px] text-emerald-600 font-bold mt-0.5">{{ $ringkasan->efisiensi_diff }}</p>
            </div>
        </div>
    </div>

    <!-- ===== STATUS SIARAN PER PLATFORM & KUALITAS SINYAL ===== -->
    <div id="monitoring-siaran" class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <!-- Status Siaran per Platform (7 Cols) -->
        <div class="lg:col-span-7 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-sm font-extrabold uppercase tracking-wider text-[#00264A]">Status Siaran per Platform</h2>
                    <p class="text-xs text-slate-500 font-semibold">Distribusi siaran multiplatform TVRI Stasiun Maluku</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-700 border border-emerald-300">
                    100% Mengudara
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                @php
                    $platforms = $ringkasan->platform_siaran_json ?: [
                        ['platform' => 'TV Digital DVB-T2', 'status' => 'On-Air 100%', 'badge' => 'bg-emerald-500/20 text-emerald-300 border-emerald-400/30', 'ikon' => 'tv', 'keterangan' => '28 Site Pemancar Aktif Terestrial'],
                        ['platform' => 'Satelit Telkom-4', 'status' => 'On-Air 100%', 'badge' => 'bg-emerald-500/20 text-emerald-300 border-emerald-400/30', 'ikon' => 'satellite', 'keterangan' => 'Transponder 3900 V 3000'],
                        ['platform' => 'IPTV/Streaming TVRI Live', 'status' => 'On-Air 100%', 'badge' => 'bg-emerald-500/20 text-emerald-300 border-emerald-400/30', 'ikon' => 'wifi', 'keterangan' => 'TVRI Klik & OTT Platforms'],
                        ['platform' => 'Radio Visual (Backhaul)', 'status' => 'Normal 100%', 'badge' => 'bg-emerald-500/20 text-emerald-300 border-emerald-400/30', 'ikon' => 'radio', 'keterangan' => 'Link Mikrowave Antar Site'],
                    ];
                @endphp
                @foreach($platforms as $p)
                <div class="p-4 rounded-xl border border-slate-200/90 bg-gradient-to-br from-slate-50 to-white flex flex-col justify-between hover:border-cyan-400 transition-all">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[#00264A]">{{ $p['platform'] }}</span>
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-emerald-100 text-emerald-800 border border-emerald-200">
                            {{ $p['status'] }}
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500 font-medium mt-2">{{ $p['keterangan'] ?? 'Operasional Normal' }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Kualitas Sinyal Nasional & Regional (5 Cols) -->
        <div id="kualitas-sinyal" class="lg:col-span-5 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-extrabold uppercase tracking-wider text-[#00264A]">Kualitas Sinyal Nasional & Regional</h2>
                    <span class="text-xs font-black text-cyan-700 bg-cyan-50 px-2.5 py-0.5 rounded-full border border-cyan-200">{{ $ringkasan->kualitas_sinyal_json['predikat'] ?? 'Sangat Baik' }}</span>
                </div>
                <div class="flex items-center space-x-3 mb-4">
                    <span class="text-4xl font-black text-[#00264A]">{{ $ringkasan->kualitas_sinyal_json['skor'] ?? $ringkasan->kualitas_rata ?? '4.6' }}</span>
                    <div>
                        @php
                            $skorVal = (float) ($ringkasan->kualitas_sinyal_json['skor'] ?? $ringkasan->kualitas_rata ?? 4.6);
                            $bintangPenuh = round($skorVal);
                        @endphp
                        <div class="flex text-amber-400 text-sm">
                            @for($si = 1; $si <= 5; $si++)
                                <span class="{{ $si <= $bintangPenuh ? 'text-amber-400' : 'text-slate-300' }}">&#9733;</span>
                            @endfor
                        </div>
                        <p class="text-[10px] text-slate-500 font-semibold">Skor rata-rata pengukuran lapangan</p>
                    </div>
                </div>

                @php
                    $breakdown = $ringkasan->kualitas_sinyal_json['breakdown'] ?? [
                        ['label' => '5 Excellent', 'persen' => 52, 'color' => 'bg-emerald-500'],
                        ['label' => '4 Good', 'persen' => 36, 'color' => 'bg-cyan-500'],
                        ['label' => '3 Fair', 'persen' => 9, 'color' => 'bg-amber-500'],
                        ['label' => '2 Poor', 'persen' => 2, 'color' => 'bg-orange-500'],
                        ['label' => '1 Bad', 'persen' => 1, 'color' => 'bg-rose-500'],
                    ];
                @endphp
                <div class="space-y-2">
                    @foreach($breakdown as $b)
                    <div>
                        <div class="flex justify-between text-[11px] font-bold text-slate-600 mb-0.5">
                            <span>{{ $b['label'] }}</span>
                            <span>{{ $b['persen'] }}%</span>
                        </div>
                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full {{ $b['color'] }} rounded-full" style="width: {{ $b['persen'] }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- ===== PARAMETER TEKNIS REAL-TIME & KETERSEDIAAN INFRASTRUKTUR ===== -->
    <div id="daya-energi" class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <!-- Parameter Teknis (Real-Time) (7 Cols) -->
        <div class="lg:col-span-7 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-sm font-extrabold uppercase tracking-wider text-[#00264A]">Parameter Teknis (Real-Time)</h2>
                    <p class="text-xs text-slate-500 font-semibold">Telemetri & status instrumen pemancar transmisi</p>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs text-cyan-700 bg-cyan-50 px-2.5 py-0.5 rounded-full font-bold border border-cyan-200">
                    <span class="w-2 h-2 rounded-full bg-cyan-500 animate-pulse"></span>
                    Live Telemetry
                </span>
            </div>

            @php
                $telemetry = $ringkasan->parameter_teknis_json ?: [
                    ['param' => 'Level Sinyal', 'nilai' => '-48 dBm', 'status' => 'Baik'],
                    ['param' => 'SNR (dB)', 'nilai' => '32 dB', 'status' => 'Baik'],
                    ['param' => 'Bit Error Rate (BER)', 'nilai' => '1.0E-6', 'status' => 'Baik'],
                    ['param' => 'Modulasi', 'nilai' => '64QAM', 'status' => 'Normal'],
                    ['param' => 'Bandwidth', 'nilai' => '8 MHz', 'status' => 'Normal'],
                    ['param' => 'Suhu Pemancar', 'nilai' => '42 °C', 'status' => 'Normal'],
                    ['param' => 'Tegangan', 'nilai' => '220 V', 'status' => 'Normal'],
                    ['param' => 'Arus', 'nilai' => '12 A', 'status' => 'Normal'],
                    ['param' => 'Daya Pancar (ERP)', 'nilai' => '5 kW', 'status' => 'Normal'],
                ];
            @endphp
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($telemetry as $t)
                <div class="p-3 rounded-xl border border-slate-100 bg-slate-50/80">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $t['param'] }}</p>
                    <p class="text-sm font-black text-[#00264A] mt-1">{{ $t['nilai'] }}</p>
                    <span class="inline-block px-1.5 py-0.5 text-[9px] font-extrabold rounded bg-emerald-100 text-emerald-800 mt-1">
                        {{ $t['status'] }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Ketersediaan Infrastruktur (5 Cols) -->
        <div id="infrastruktur" class="lg:col-span-5 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-extrabold uppercase tracking-wider text-[#00264A]">Ketersediaan Infrastruktur</h2>
                    <span class="text-xs font-black text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">Rata-rata {{ !empty($ringkasan->infrastruktur_json) ? round(collect($ringkasan->infrastruktur_json)->avg('persen'), 1) : '95.8' }}%</span>
                </div>

                @php
                    $infra = $ringkasan->infrastruktur_json ?: [
                        ['item' => 'Menara', 'persen' => 96, 'color' => 'bg-cyan-500'],
                        ['item' => 'Antena', 'persen' => 98, 'color' => 'bg-emerald-500'],
                        ['item' => 'Perangkat TX', 'persen' => 95, 'color' => 'bg-blue-500'],
                        ['item' => 'Genset', 'persen' => 93, 'color' => 'bg-amber-500'],
                        ['item' => 'Catu Daya', 'persen' => 97, 'color' => 'bg-indigo-500'],
                    ];
                @endphp
                <div class="space-y-3.5">
                    @foreach($infra as $i)
                    <div>
                        <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                            <span>{{ $i['item'] }}</span>
                            <span class="font-mono text-slate-900">{{ $i['persen'] }}%</span>
                        </div>
                        <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full {{ $i['color'] }} rounded-full transition-all duration-500" style="width: {{ $i['persen'] }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- ===== DAFTAR 28 STASIUN PEMANCAR & ALARM GANGGUAN ===== -->
    <div id="stasiun-pemancar" class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <!-- Tabel Stasiun Pemancar (8 Cols) -->
        <div class="lg:col-span-8 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-sm font-extrabold uppercase tracking-wider text-[#00264A]">Jaringan Stasiun Pemancar DVB-T2</h2>
                    <p class="text-xs text-slate-500 font-semibold">{{ $siteTotal }} Site Pemancar tersebar di Maluku & Maluku Utara</p>
                </div>
                @if($isKetuaTimTransmisi)
                <a href="{{ route('tvri.transmisi.manage', ['tab' => 'pemancar']) }}" class="text-xs font-bold text-cyan-600 hover:text-cyan-800">
                    + Kelola Stasiun &rarr;
                </a>
                @endif
            </div>

            <div class="overflow-x-auto max-h-[460px] overflow-y-auto">
                <table class="w-full text-left text-xs">
                    <thead class="sticky top-0 bg-white">
                        <tr class="border-b border-slate-200 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                            <th class="pb-2.5">Nama Site / Lokasi</th>
                            <th class="pb-2.5">Kanal / Frekuensi</th>
                            <th class="pb-2.5 text-center">Daya (kW)</th>
                            <th class="pb-2.5 text-center">Cakupan</th>
                            <th class="pb-2.5 text-center">Kualitas</th>
                            <th class="pb-2.5 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($sites as $st)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-2.5">
                                    <p class="font-bold text-[#00264A]">{{ $st->nama }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $st->lokasi }}</p>
                                </td>
                                <td class="py-2.5 font-mono text-slate-600 text-[11px]">
                                    <span class="font-bold text-cyan-700">{{ $st->kanal }}</span> ({{ $st->frekuensi }})
                                </td>
                                <td class="py-2.5 text-center font-mono font-bold text-slate-700">{{ $st->daya_kw }} kW</td>
                                <td class="py-2.5 text-center text-[10px] text-slate-500 font-medium">{{ $st->coverage ?? 'Wilayah Layanan' }}</td>
                                <td class="py-2.5 text-center">
                                    <span class="font-bold text-emerald-600">{{ number_format($st->kualitas, 1) }}</span> / 5.0
                                </td>
                                <td class="py-2.5 text-right">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase
                                        {{ $st->status === 'On-Air' ? 'bg-emerald-100 text-emerald-700' : ($st->status === 'Gangguan' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
                                        {{ $st->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Alarm & Gangguan Terkini (4 Cols) -->
        <div id="alarm-gangguan" class="lg:col-span-4 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-extrabold uppercase tracking-wider text-[#00264A]">Alarm & Gangguan Terkini</h2>
                        <p class="text-[11px] text-slate-400 font-semibold">{{ $gangguanAktif }} Dalam Proses &bull; {{ $gangguanSelesai }} Selesai</p>
                    </div>
                    @if($isKetuaTimTransmisi)
                    <a href="{{ route('tvri.transmisi.manage', ['tab' => 'gangguan']) }}" class="text-xs font-bold text-rose-600 hover:text-rose-800">
                        + Kelola &rarr;
                    </a>
                    @endif
                </div>

                <div class="space-y-3 max-h-[420px] overflow-y-auto">
                    @forelse ($gangguans as $g)
                        <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/80 hover:border-slate-300 transition-all">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-[#00264A] text-xs">{{ $g->site }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase
                                    {{ $g->status === 'Selesai' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $g->status }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-800 font-bold mt-1">{{ $g->jenis }}</p>
                            <p class="text-[10px] text-slate-500 mt-1">{{ $g->keterangan }}</p>
                            <p class="text-[9px] text-slate-400 font-mono mt-1">&bull; {{ $g->tanggal }}</p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-6 text-center">Seluruh stasiun pemancar dalam kondisi normal.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- ===== PEMELIHARAAN TERJADWAL & LOG AKTIVITAS TERKINI ===== -->
    <div id="pemeliharaan" class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <!-- Pemeliharaan Terjadwal (6 Cols) -->
        <div class="lg:col-span-6 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-sm font-extrabold uppercase tracking-wider text-[#00264A]">Pemeliharaan Terjadwal</h2>
                    <p class="text-xs text-slate-500 font-semibold">Jadwal perawatan preventif dan kalibrasi transmisi</p>
                </div>
                @if($isKetuaTimTransmisi)
                <a href="{{ route('tvri.transmisi.manage', ['tab' => 'pemeliharaan']) }}" class="text-xs font-bold text-amber-600 hover:text-amber-800">
                    + Kelola &rarr;
                </a>
                @endif
            </div>

            <div class="space-y-3">
                @forelse($pemeliharaans as $p)
                <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/80 flex items-center justify-between">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="text-xs font-bold text-[#00264A]">{{ $p->site }}</span>
                            <span class="text-[10px] font-mono text-cyan-700 bg-cyan-50 px-2 py-0.5 rounded border border-cyan-200">{{ $p->tanggal }}</span>
                        </div>
                        <p class="text-xs text-slate-700 font-semibold mt-1">{{ $p->kegiatan }}</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $p->keterangan }}</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[9px] font-extrabold uppercase bg-amber-100 text-amber-800">
                        {{ $p->status }}
                    </span>
                </div>
                @empty
                <p class="text-xs text-slate-400 py-6 text-center">Belum ada jadwal pemeliharaan.</p>
                @endforelse
            </div>
        </div>

        <!-- Log Aktivitas Terkini (6 Cols) -->
        <div id="log-aktivitas" class="lg:col-span-6 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-sm font-extrabold uppercase tracking-wider text-[#00264A]">Log Aktivitas Terkini</h2>
                    <p class="text-xs text-slate-500 font-semibold">Catatan aktivitas teknis dan operasional transmisi</p>
                </div>
                @if($isKetuaTimTransmisi)
                <a href="{{ route('tvri.transmisi.manage', ['tab' => 'log']) }}" class="text-xs font-bold text-blue-600 hover:text-blue-800">
                    + Kelola &rarr;
                </a>
                @endif
            </div>

            <div class="space-y-2.5">
                @forelse($logs as $l)
                <div class="p-3 rounded-xl border border-slate-100 bg-slate-50/80 flex items-center justify-between text-xs">
                    <div class="flex items-center space-x-3">
                        <span class="font-mono text-[10px] text-slate-400 bg-white px-2 py-1 rounded border border-slate-200">{{ $l->waktu }}</span>
                        <div>
                            <span class="font-bold text-[#00264A]">{{ $l->aktivitas }}</span>
                            @if($l->site)
                            <span class="text-[10px] text-slate-400 ml-1">({{ $l->site }})</span>
                            @endif
                        </div>
                    </div>
                    <span class="font-mono text-[10px] font-bold text-cyan-800 bg-cyan-50 px-2 py-0.5 rounded border border-cyan-200">
                        {{ $l->user }}
                    </span>
                </div>
                @empty
                <p class="text-xs text-slate-400 py-6 text-center">Belum ada log aktivitas.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ===== DOKUMEN & SOP TRANSMISI ===== -->
    <div id="dokumen-laporan" class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-sm font-extrabold uppercase tracking-wider text-[#00264A] flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-cyan-600"></span>
                    <span>Dokumen Laporan & Standar Operasional Prosedur (SOP) Transmisi</span>
                </h2>
                <p class="text-xs text-slate-500 font-semibold mt-0.5">Laporan Harian, Laporan Bulanan, Manual Peralatan, Dokumen Teknis, dan SOP Resmi Transmisi</p>
            </div>
            @if(isset($isKetuaTimTransmisi) && $isKetuaTimTransmisi)
            <a href="{{ route('tvri.transmisi.manage', ['tab' => 'sop']) }}" class="text-xs font-bold text-cyan-600 hover:text-cyan-800">
                + Kelola SOP &rarr;
            </a>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3.5">
            @forelse($sops as $sp)
            <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/80 hover:border-cyan-400 hover:bg-white hover:shadow-sm transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-[9px] font-mono font-bold text-cyan-700 bg-cyan-50 px-1.5 py-0.5 rounded border border-cyan-200">{{ $sp->kode ?? 'SOP' }}</span>
                        <span class="text-[9px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded">{{ $sp->versi ?? 'v1.0' }}</span>
@php $fileUrl = $sp->file_url ?: $sp->file_path; @endphp
@if($fileUrl)
<a href="{{ asset($fileUrl) }}" target="_blank" class="inline-flex items-center space-x-1 px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-50 text-cyan-700 hover:bg-cyan-100 border border-cyan-200 transition" title="Buka Dokumen / Berkas">
    <svg class="w-3 h-3 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
    <span>{{ !empty($sp->is_laporan) ? 'Dokumen' : 'Fisik SOP' }}</span>
</a>
@endif
                    </div>
                    <h3 class="text-xs font-bold text-[#00264A] leading-snug">{{ $sp->judul }}</h3>
                    <p class="text-[10px] text-slate-500 mt-1 line-clamp-2">{{ $sp->keterangan }}</p>
                </div>
                <div class="mt-3 pt-2 border-t border-slate-200/60 flex items-center justify-between text-[10px]">
                    <span class="text-slate-400 font-semibold truncate mr-2">{{ $sp->kategori }}</span>
                    <span class="font-bold text-cyan-600 flex-shrink-0">{{ $sp->status }}</span>
                </div>
            </div>
            @empty
            <p class="text-xs text-slate-400 py-6 col-span-4 text-center">Belum ada Dokumen Laporan atau SOP terdaftar.</p>
            @endforelse
        </div>
    </div>

    <!-- ===== FOOTER ===== -->
    <div class="text-center py-4 border-t border-slate-200/60 text-xs text-slate-500 space-y-1">
        <p class="font-semibold">{{ $ringkasan->footer_slogan }}</p>
        <p class="font-bold text-[#00264A]">{{ $ringkasan->footer_quote }}</p>
    </div>

</div>
@endsection
