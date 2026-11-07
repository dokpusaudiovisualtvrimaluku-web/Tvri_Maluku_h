@extends('layouts.app')

@section('title', 'Dashboard Konten Media Baru (KMB) - TVRI Maluku')

@section('content')
<div class="space-y-6" id="dashboard">

    <!-- ===== HEADER UTAMA ===== -->
    <div class="bg-gradient-to-r from-[#001D38] via-[#00264A] to-[#003B70] rounded-3xl p-6 lg:p-8 text-white shadow-xl relative overflow-hidden border border-blue-400/20">
        <div class="absolute -right-12 -top-16 w-80 h-80 bg-pink-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -bottom-20 w-72 h-72 bg-blue-400/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-3">
                <div class="flex items-center space-x-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-white p-2 shadow-lg flex-shrink-0 flex items-center justify-center">
                        <img src="{{ asset('images/logo_tvri.png') }}" alt="Logo TVRI" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-pink-500/20 text-pink-300 border border-pink-400/30">
                            {{ $ringkasan->periode ?? 'September 2025' }} &bull; Media Baru TVRI Maluku
                        </span>
                        <h1 class="text-xl sm:text-2xl font-black tracking-tight uppercase leading-tight mt-1">
                            {{ $ringkasan->judul ?? 'DASHBOARD KONTEN MEDIA BARU TVRI MALUKU' }}
                        </h1>
                    </div>
                </div>

                <div class="pl-0 sm:pl-15 space-y-1">
                    <p class="text-xs sm:text-sm font-black text-pink-300 tracking-wider uppercase">
                        {{ $ringkasan->tagline ?? 'SATU KONTEN, LEBIH DEKAT DENGAN MALUKU, LEBIH LUAS UNTUK INDONESIA' }}
                    </p>
                    <p class="text-[11px] text-blue-200 font-semibold tracking-wide flex flex-wrap items-center gap-2">
                        <span><strong class="text-white">Pilar:</strong> {{ $ringkasan->pilar ?? 'KREATIF • RELEVAN • BERDAMPAK • BERKELANJUTAN' }}</span>
                        <span class="hidden md:inline text-blue-400">&bull;</span>
                        <span class="text-amber-300 font-bold">&ldquo;{{ $ringkasan->slogan ?? 'Maluku Bersama di Layar Digital!' }}&rdquo;</span>
                    </p>
                    <p class="text-[11px] text-blue-200/80">
                        {{ $ringkasan->sub_judul ?? 'TVRI MALUKU: Media Pemersatu Bangsa ' }}
                    </p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row lg:flex-col items-start lg:items-end justify-between gap-3">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-bold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="global-live-datetime-pipe">{{ $ringkasan->waktu_status ?? 'Selasa, 2 September 2025 | 10:24 WIT | Live Data' }}</span>
                </div>

                <div class="flex items-center gap-2">
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
                    <a href="{{ route('tvri.mbn.manage') }}"
                       class="px-4 py-2 rounded-xl bg-pink-600 hover:bg-pink-500 text-white text-xs font-bold shadow-lg shadow-pink-600/30 transition-all flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        <span>Input & Kelola Data KMB</span>
                    </a>
                    @endif
                    <a href="{{ route('tvri.ecc.dashboard') }}"
                       class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-xs font-bold text-white transition-colors">
                        ECC Stasiun &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== INDIKATOR KINERJA UTAMA (KPI) ===== -->
    <div>
        <div class="flex items-center justify-between mb-3 px-1">
            <h2 class="text-xs font-extrabold text-[#00264A] uppercase tracking-wider flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-pink-600"></span>
                Indikator Kinerja Utama (KPI) &bull; Performa Media Baru
            </h2>
            <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                Growth Rate: +{{ $ringkasan->growth_rate ?? 35 }}% vs. bulan lalu
            </span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
            <!-- Total Konten -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="w-8 h-8 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center font-bold text-xs border border-pink-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </span>
                    <span class="text-[9px] font-extrabold text-pink-700 bg-pink-50 px-1.5 py-0.5 rounded-md border border-pink-200">TOTAL</span>
                </div>
                <p class="text-2xl font-black text-[#00264A] mt-2.5">{{ number_format($ringkasan->total_konten ?? 256) }}</p>
                <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wide">Total Konten</p>
                <p class="text-[10px] font-bold text-emerald-600 mt-1 flex items-center gap-0.5">
                    {{ $ringkasan->diff_total_konten ?? '▲ +28% dari bulan lalu' }}
                </p>
            </div>

            <!-- Total Views -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs border border-blue-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </span>
                    <span class="text-[9px] font-extrabold text-blue-700 bg-blue-50 px-1.5 py-0.5 rounded-md border border-blue-200">VIEWS</span>
                </div>
                <p class="text-2xl font-black text-[#00264A] mt-2.5">
                    @if(($ringkasan->total_views ?? 1200000) >= 1000000)
                        {{ number_format(($ringkasan->total_views ?? 1200000) / 1000000, 1) }} Juta
                    @else
                        {{ number_format(($ringkasan->total_views ?? 1200000) / 1000, 0) }} Ribu
                    @endif
                </p>
                <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wide">Total Views</p>
                <p class="text-[10px] font-bold text-emerald-600 mt-1 flex items-center gap-0.5">
                    {{ $ringkasan->diff_total_views ?? '▲ +35%' }}
                </p>
            </div>

            <!-- Total Reach -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xs border border-purple-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </span>
                    <span class="text-[9px] font-extrabold text-purple-700 bg-purple-50 px-1.5 py-0.5 rounded-md border border-purple-200">REACH</span>
                </div>
                <p class="text-2xl font-black text-[#00264A] mt-2.5">
                    @if(($ringkasan->total_reach ?? 980000) >= 1000000)
                        {{ number_format(($ringkasan->total_reach ?? 980000) / 1000000, 1) }} Juta
                    @else
                        {{ number_format(($ringkasan->total_reach ?? 980000) / 1000, 0) }} Ribu
                    @endif
                </p>
                <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wide">Total Reach</p>
                <p class="text-[10px] font-bold text-emerald-600 mt-1 flex items-center gap-0.5">
                    {{ $ringkasan->diff_total_reach ?? '▲ +42%' }}
                </p>
            </div>

            <!-- Engagement -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-xs border border-teal-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>
                    </span>
                    <span class="text-[9px] font-extrabold text-teal-700 bg-teal-50 px-1.5 py-0.5 rounded-md border border-teal-200">INTERAKSI</span>
                </div>
                <p class="text-2xl font-black text-[#00264A] mt-2.5">{{ number_format(($ringkasan->engagement ?? 84000) / 1000, 0) }} Ribu</p>
                <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wide">Engagement</p>
                <p class="text-[10px] font-bold text-emerald-600 mt-1 flex items-center gap-0.5">
                    {{ $ringkasan->diff_engagement ?? '▲ +38%' }}
                </p>
            </div>

            <!-- Watch Time -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs border border-amber-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    <span class="text-[9px] font-extrabold text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded-md border border-amber-200">DURASI</span>
                </div>
                <p class="text-2xl font-black text-[#00264A] mt-2.5">{{ number_format(($ringkasan->watch_time ?? 125000) / 1000, 0) }} Ribu Jam</p>
                <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wide">Watch Time</p>
                <p class="text-[10px] font-bold text-emerald-600 mt-1 flex items-center gap-0.5">
                    {{ $ringkasan->diff_watch_time ?? '▲ +40%' }}
                </p>
            </div>

            <!-- Follower Growth -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs border border-indigo-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </span>
                    <span class="text-[9px] font-extrabold text-indigo-700 bg-indigo-50 px-1.5 py-0.5 rounded-md border border-indigo-200">FOLLOWER</span>
                </div>
                <p class="text-2xl font-black text-[#00264A] mt-2.5">+{{ number_format(($ringkasan->follower_growth ?? 12500) / 1000, 1) }} Ribu</p>
                <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wide">Follower Growth</p>
                <p class="text-[10px] font-bold text-emerald-600 mt-1 flex items-center gap-0.5">
                    {{ $ringkasan->diff_follower_growth ?? '▲ +18%' }}
                </p>
            </div>
        </div>
    </div>

    <!-- ===== PERFORMA & DISTRIBUSI (PLATFORM + KATEGORI) ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5" id="media-sosial">
        <!-- PERFORMA PER PLATFORM (anchor #media-sosial, #live-streaming, #website) -->
        <div class="lg:col-span-6 bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-black uppercase tracking-wider text-[#00264A]">Performa Per Platform Media Digital</h3>
                    <p class="text-[11px] text-slate-500 font-medium">YouTube, Instagram, TikTok & Portal Berita Website</p>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-50 text-[#005B9F] border border-blue-200">
                    Live Platform
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <!-- YouTube -->
                <div class="p-4 rounded-2xl border border-red-100 bg-red-50/40 hover:bg-red-50/70 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <span class="inline-flex items-center gap-1.5 text-xs font-black text-red-600">
                            <span class="w-2.5 h-2.5 rounded-full bg-red-600"></span>
                            YouTube
                        </span>
                        <span class="text-[10px] font-mono font-bold text-red-700 bg-white px-2 py-0.5 rounded-md border border-red-200">
                            {{ $ringkasan->yt_subs ?? '392K' }} Subs
                        </span>
                    </div>
                    <div class="space-y-1 text-xs">
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Views</span>
                            <span class="font-bold text-[#00264A]">{{ $ringkasan->yt_views ?? '1.2M' }} ({{ $ringkasan->yt_diff_views ?? '▲ +35%' }})</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Engagement</span>
                            <span class="font-bold text-[#00264A]">{{ $ringkasan->yt_eng ?? '6.8%' }} ({{ $ringkasan->yt_diff_eng ?? '▲ +12%' }})</span>
                        </div>
                    </div>
                </div>

                <!-- Instagram -->
                <div class="p-4 rounded-2xl border border-pink-100 bg-pink-50/40 hover:bg-pink-50/70 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <span class="inline-flex items-center gap-1.5 text-xs font-black text-pink-600">
                            <span class="w-2.5 h-2.5 rounded-full bg-pink-600"></span>
                            Instagram
                        </span>
                        <span class="text-[10px] font-mono font-bold text-pink-700 bg-white px-2 py-0.5 rounded-md border border-pink-200">
                            {{ $ringkasan->ig_followers ?? '286K' }} Follower
                        </span>
                    </div>
                    <div class="space-y-1 text-xs">
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Reach</span>
                            <span class="font-bold text-[#00264A]">{{ $ringkasan->ig_reach ?? '520K' }} ({{ $ringkasan->ig_diff_reach ?? '▲ +42%' }})</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Engagement</span>
                            <span class="font-bold text-[#00264A]">{{ $ringkasan->ig_eng ?? '8.4%' }} ({{ $ringkasan->ig_diff_eng ?? '▲ +18%' }})</span>
                        </div>
                    </div>
                </div>

                <!-- TikTok -->
                <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50 hover:bg-slate-100/70 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <span class="inline-flex items-center gap-1.5 text-xs font-black text-slate-800">
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-900"></span>
                            TikTok
                        </span>
                        <span class="text-[10px] font-mono font-bold text-slate-800 bg-white px-2 py-0.5 rounded-md border border-slate-300">
                            {{ $ringkasan->tiktok_followers ?? '515K' }} Follower
                        </span>
                    </div>
                    <div class="space-y-1 text-xs">
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Views</span>
                            <span class="font-bold text-[#00264A]">{{ $ringkasan->tiktok_views ?? '480K' }} ({{ $ringkasan->tiktok_diff_views ?? '▲ +55%' }})</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Engagement</span>
                            <span class="font-bold text-[#00264A]">{{ $ringkasan->tiktok_eng ?? '12.1%' }} ({{ $ringkasan->tiktok_diff_eng ?? '▲ +28%' }})</span>
                        </div>
                    </div>
                </div>

                <!-- Website -->
                <div class="p-4 rounded-2xl border border-blue-100 bg-blue-50/40 hover:bg-blue-50/70 transition-colors" id="website">
                    <div class="flex items-center justify-between mb-2">
                        <span class="inline-flex items-center gap-1.5 text-xs font-black text-blue-600">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                            Website Portal
                        </span>
                        <span class="text-[10px] font-mono font-bold text-blue-700 bg-white px-2 py-0.5 rounded-md border border-blue-200">
                            {{ $ringkasan->web_visitors ?? '88K' }} Visitor
                        </span>
                    </div>
                    <div class="space-y-1 text-xs">
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Page Views</span>
                            <span class="font-bold text-[#00264A]">{{ $ringkasan->web_views ?? '210K' }} ({{ $ringkasan->web_diff_views ?? '▲ +20%' }})</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Avg Duration</span>
                            <span class="font-bold text-[#00264A]">{{ $ringkasan->web_avg_duration ?? '3.5 Min' }} ({{ $ringkasan->web_diff_duration ?? '▲ +15%' }})</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DISTRIBUSI KONTEN BERDASARKAN KATEGORI (anchor #konten, #publishing) -->
        <div class="lg:col-span-6 bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-sm space-y-4" id="publishing">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-black uppercase tracking-wider text-[#00264A]">Distribusi Konten Berdasarkan Kategori</h3>
                    <p class="text-[11px] text-slate-500 font-medium">Total: {{ $ringkasan->total_konten ?? 256 }} Konten Digital Terdistribusi</p>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-pink-50 text-pink-700 border border-pink-200">
                    8 Kategori
                </span>
            </div>

            <div class="grid grid-cols-2 gap-3 text-xs">
                <!-- Berita -->
                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="flex justify-between items-center font-bold text-slate-700 mb-1">
                        <span>Berita</span>
                        <span class="text-[#005B9F] font-mono">{{ number_format((float) ($ringkasan->kat_berita_pct ?? 28), 2) }}% ({{ $ringkasan->kat_berita_total ?? 72 }})</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $ringkasan->kat_berita_pct ?? 28 }}%"></div>
                    </div>
                </div>

                <!-- Budaya -->
                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="flex justify-between items-center font-bold text-slate-700 mb-1">
                        <span>Budaya</span>
                        <span class="text-pink-600 font-mono">{{ number_format((float) ($ringkasan->kat_budaya_pct ?? 18), 2) }}% ({{ $ringkasan->kat_budaya_total ?? 46 }})</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-pink-600 h-1.5 rounded-full" style="width: {{ $ringkasan->kat_budaya_pct ?? 18 }}%"></div>
                    </div>
                </div>

                <!-- Pariwisata -->
                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="flex justify-between items-center font-bold text-slate-700 mb-1">
                        <span>Pariwisata</span>
                        <span class="text-teal-600 font-mono">{{ number_format((float) ($ringkasan->kat_pariwisata_pct ?? 15), 2) }}% ({{ $ringkasan->kat_pariwisata_total ?? 38 }})</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-teal-500 h-1.5 rounded-full" style="width: {{ $ringkasan->kat_pariwisata_pct ?? 15 }}%"></div>
                    </div>
                </div>

                <!-- Pendidikan -->
                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="flex justify-between items-center font-bold text-slate-700 mb-1">
                        <span>Pendidikan</span>
                        <span class="text-purple-600 font-mono">{{ number_format((float) ($ringkasan->kat_pendidikan_pct ?? 12), 2) }}% ({{ $ringkasan->kat_pendidikan_total ?? 31 }})</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-purple-600 h-1.5 rounded-full" style="width: {{ $ringkasan->kat_pendidikan_pct ?? 12 }}%"></div>
                    </div>
                </div>

                <!-- UMKM -->
                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="flex justify-between items-center font-bold text-slate-700 mb-1">
                        <span>UMKM</span>
                        <span class="text-amber-600 font-mono">{{ number_format((float) ($ringkasan->kat_umkm_pct ?? 10), 2) }}% ({{ $ringkasan->kat_umkm_total ?? 26 }})</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-amber-500 h-1.5 rounded-full" style="width: {{ $ringkasan->kat_umkm_pct ?? 10 }}%"></div>
                    </div>
                </div>

                <!-- Talkshow -->
                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="flex justify-between items-center font-bold text-slate-700 mb-1">
                        <span>Talkshow</span>
                        <span class="text-indigo-600 font-mono">{{ number_format((float) ($ringkasan->kat_talkshow_pct ?? 8), 2) }}% ({{ $ringkasan->kat_talkshow_total ?? 21 }})</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ $ringkasan->kat_talkshow_pct ?? 8 }}%"></div>
                    </div>
                </div>

                <!-- Program Khusus -->
                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="flex justify-between items-center font-bold text-slate-700 mb-1">
                        <span>Program Khusus</span>
                        <span class="text-emerald-600 font-mono">{{ number_format((float) ($ringkasan->kat_khusus_pct ?? 6), 2) }}% ({{ $ringkasan->kat_khusus_total ?? 15 }})</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-emerald-600 h-1.5 rounded-full" style="width: {{ $ringkasan->kat_khusus_pct ?? 6 }}%"></div>
                    </div>
                </div>

                <!-- Lainnya -->
                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="flex justify-between items-center font-bold text-slate-700 mb-1">
                        <span>Lainnya</span>
                        <span class="text-slate-600 font-mono">{{ number_format((float) ($ringkasan->kat_lainnya_pct ?? 3), 2) }}% ({{ $ringkasan->kat_lainnya_total ?? 7 }})</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-slate-400 h-1.5 rounded-full" style="width: {{ $ringkasan->kat_lainnya_pct ?? 3 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== TOP 5 KONTEN (BERDASARKAN VIEWS) & CRUD LIST KONTEN ===== -->
    <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-sm space-y-4" id="konten">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-pink-600"></span>
                    Top 5 Konten Terpopuler (Berdasarkan Views)
                </h3>
                <p class="text-[11px] text-slate-500 font-medium">Konten digital dengan performa jangkauan dan penayangan tertinggi</p>
            </div>
            <a href="{{ route('tvri.mbn.manage', ['tab' => 'konten']) }}"
               class="px-3.5 py-1.5 rounded-xl bg-pink-50 hover:bg-pink-100 text-pink-700 border border-pink-200 text-xs font-bold self-start sm:self-auto transition-colors flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Kelola / Tambah Konten</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-[10px] font-black uppercase tracking-wider text-slate-400 bg-slate-50/70">
                        <th class="py-2.5 px-3 rounded-l-xl">No</th>
                        <th class="py-2.5 px-3">Judul Konten Digital</th>
                        <th class="py-2.5 px-3">Platform</th>
                        <th class="py-2.5 px-3">Kategori</th>
                        <th class="py-2.5 px-3 text-right">Views</th>
                        <th class="py-2.5 px-3 text-right">Reach</th>
                        <th class="py-2.5 px-3 text-right">Engagement</th>
                        <th class="py-2.5 px-3 text-center">Tanggal / Status</th>
                        <th class="py-2.5 px-3 text-center rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($topKonten as $idx => $tk)
                        <tr class="hover:bg-slate-50/80 transition-colors {{ $idx < 5 ? 'bg-pink-50/20' : '' }}">
                            <td class="py-2.5 px-3 font-mono font-bold text-slate-400 text-center">
                                @if($idx < 3)
                                    <span class="w-5 h-5 rounded-full inline-flex items-center justify-center text-[10px] font-black {{ $idx == 0 ? 'bg-amber-400 text-amber-950' : ($idx == 1 ? 'bg-slate-300 text-slate-800' : 'bg-amber-600 text-white') }}">
                                        {{ $idx + 1 }}
                                    </span>
                                @else
                                    {{ $idx + 1 }}
                                @endif
                            </td>
                            <td class="py-2.5 px-3 font-bold text-[#00264A] max-w-[280px]">
                                <div class="truncate">{{ $tk->judul }}</div>
                                @if($idx < 5)
                                    <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1 rounded">
                                        Top {{ $idx + 1 }} Trending
                                    </span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase
                                    {{ $tk->platform == 'YouTube' ? 'bg-red-50 text-red-700 border border-red-200' :
                                       ($tk->platform == 'Instagram' ? 'bg-pink-50 text-pink-700 border border-pink-200' :
                                       ($tk->platform == 'TikTok' ? 'bg-slate-100 text-slate-800 border border-slate-300' : 'bg-blue-50 text-blue-700 border border-blue-200')) }}">
                                    {{ $tk->platform }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 text-slate-600 font-medium text-[11px]">{{ $tk->kategori }}</td>
                            <td class="py-2.5 px-3 text-right font-mono font-black text-[#005B9F]">
                                {{ number_format($tk->views) }}
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono text-slate-600">
                                {{ number_format($tk->reach) }}
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono text-slate-600">
                                {{ number_format($tk->engagement) }}
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                <div class="text-[10px] text-slate-500 font-mono">{{ $tk->tanggal ? \Carbon\Carbon::parse($tk->tanggal)->format('d M Y') : '-' }}</div>
                                <span class="inline-block mt-0.5 px-1.5 py-0.2 rounded text-[8px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ $tk->status }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                <a href="{{ route('tvri.mbn.manage', ['tab' => 'konten']) }}" class="text-pink-600 hover:text-pink-800 font-bold text-[10px] underline">
                                    Edit di Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-4 text-center text-slate-400 text-xs">Belum ada data konten. Silakan gunakan menu kelola data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ===== AUDIENS & TARGET ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5" id="audiens">
        <!-- Demografi Audiens & Perangkat -->
        <div class="lg:col-span-4 bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-black uppercase tracking-wider text-[#00264A]">Demografi Audiens (Usia)</h3>
                <span class="text-[10px] font-bold text-slate-400">Distribusi %</span>
            </div>

            <div class="space-y-2.5">
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold text-slate-700">
                        <span>13 - 17 Tahun</span>
                        <span class="font-mono font-bold text-[#00264A]">{{ number_format((float) ($ringkasan->usia_13_17 ?? 12), 2) }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-blue-400 h-1.5 rounded-full" style="width: {{ $ringkasan->usia_13_17 ?? 12 }}%"></div>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold text-slate-700">
                        <span>18 - 24 Tahun</span>
                        <span class="font-mono font-bold text-pink-600">{{ number_format((float) ($ringkasan->usia_18_24 ?? 28), 2) }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-pink-500 h-1.5 rounded-full" style="width: {{ $ringkasan->usia_18_24 ?? 28 }}%"></div>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold text-slate-700">
                        <span>25 - 34 Tahun (Mayoritas)</span>
                        <span class="font-mono font-bold text-pink-600">{{ number_format((float) ($ringkasan->usia_25_34 ?? 32), 2) }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-pink-600 h-1.5 rounded-full" style="width: {{ $ringkasan->usia_25_34 ?? 32 }}%"></div>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold text-slate-700">
                        <span>35 - 44 Tahun</span>
                        <span class="font-mono font-bold text-[#00264A]">{{ number_format((float) ($ringkasan->usia_35_44 ?? 18), 2) }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $ringkasan->usia_35_44 ?? 18 }}%"></div>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold text-slate-700">
                        <span>45 - 54 Tahun</span>
                        <span class="font-mono font-bold text-slate-600">{{ number_format((float) ($ringkasan->usia_45_54 ?? 8), 2) }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-slate-400 h-1.5 rounded-full" style="width: {{ $ringkasan->usia_45_54 ?? 8 }}%"></div>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold text-slate-700">
                        <span>55+ Tahun</span>
                        <span class="font-mono font-bold text-slate-600">{{ number_format((float) ($ringkasan->usia_55_plus ?? 2), 2) }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-slate-300 h-1.5 rounded-full" style="width: {{ $ringkasan->usia_55_plus ?? 2 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- PERANGKAT -->
            <div class="pt-3 border-t border-slate-100 space-y-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">Perangkat yang Digunakan</span>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <span class="text-slate-600 font-semibold">Mobile</span>
                        <span class="font-mono font-bold text-pink-600">{{ number_format((float) ($ringkasan->device_mobile ?? 78), 2) }}%</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <span class="text-slate-600 font-semibold">Desktop</span>
                        <span class="font-mono font-bold text-[#00264A]">{{ number_format((float) ($ringkasan->device_desktop ?? 16), 2) }}%</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <span class="text-slate-600 font-semibold">Tablet</span>
                        <span class="font-mono font-bold text-slate-600">{{ number_format((float) ($ringkasan->device_tablet ?? 4), 2) }}%</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <span class="text-slate-600 font-semibold">Smart TV</span>
                        <span class="font-mono font-bold text-slate-600">{{ number_format((float) ($ringkasan->device_smart_tv ?? 2), 2) }}%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- PETA SEBARAN AUDIENS (anchor #analitik) -->
        <div class="lg:col-span-4 bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-sm space-y-4" id="analitik">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-black uppercase tracking-wider text-[#00264A]">Peta Sebaran Audiens</h3>
                <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-pink-50 text-pink-700 border border-pink-200">
                    {{ $ringkasan->tagline_peta ?? 'Maluku Bisa! Go Digital Go Global' }}
                </span>
            </div>

            <div class="space-y-2.5">
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold text-slate-700">
                        <span>Maluku (Pusat)</span>
                        <span class="font-mono font-bold text-pink-600">{{ number_format((float) ($ringkasan->aud_maluku ?? 38), 2) }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-pink-600 h-1.5 rounded-full" style="width: {{ $ringkasan->aud_maluku ?? 38 }}%"></div>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold text-slate-700">
                        <span>Jawa</span>
                        <span class="font-mono font-bold text-[#005B9F]">{{ number_format((float) ($ringkasan->aud_jawa ?? 18), 2) }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $ringkasan->aud_jawa ?? 18 }}%"></div>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold text-slate-700">
                        <span>Maluku Utara</span>
                        <span class="font-mono font-bold text-[#00264A]">{{ number_format((float) ($ringkasan->aud_malut ?? 12), 2) }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ $ringkasan->aud_malut ?? 12 }}%"></div>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold text-slate-700">
                        <span>Sulawesi</span>
                        <span class="font-mono font-bold text-slate-700">{{ number_format((float) ($ringkasan->aud_sulawesi ?? 10), 2) }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-purple-500 h-1.5 rounded-full" style="width: {{ $ringkasan->aud_sulawesi ?? 10 }}%"></div>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold text-slate-700">
                        <span>Papua</span>
                        <span class="font-mono font-bold text-slate-700">{{ number_format((float) ($ringkasan->aud_papua ?? 8), 2) }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-amber-500 h-1.5 rounded-full" style="width: {{ $ringkasan->aud_papua ?? 8 }}%"></div>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold text-slate-700">
                        <span>Nusa Tenggara</span>
                        <span class="font-mono font-bold text-slate-700">{{ number_format((float) ($ringkasan->aud_nusa ?? 6), 2) }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $ringkasan->aud_nusa ?? 6 }}%"></div>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold text-slate-700">
                        <span>Lainnya (Nasional & Global)</span>
                        <span class="font-mono font-bold text-slate-700">{{ number_format((float) ($ringkasan->aud_lainnya ?? 8), 2) }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-slate-400 h-1.5 rounded-full" style="width: {{ $ringkasan->aud_lainnya ?? 8 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TARGET & CAPAIAN BULAN INI (anchor #monetisasi, #kolaborasi) -->
        <div class="lg:col-span-4 bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-sm space-y-4" id="monetisasi">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-black uppercase tracking-wider text-[#00264A]">Target & Capaian Bulan Ini</h3>
                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    Target Tercapai &gt;100%
                </span>
            </div>

            <div class="space-y-3">
                <!-- Views Target -->
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-1.5">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-slate-700">Views: 1,2 M / 1 M</span>
                        <span class="font-mono font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                            {{ $ringkasan->capaian_views_pct ?? 120 }}%
                        </span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                </div>

                <!-- Engagement Target -->
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-1.5">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-slate-700">Engagement: 84 K / 70 K</span>
                        <span class="font-mono font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                            {{ $ringkasan->capaian_engagement_pct ?? 120 }}%
                        </span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                </div>

                <!-- Follower Growth Target -->
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-1.5">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-slate-700">Follower Growth: 12,5 K / 10 K</span>
                        <span class="font-mono font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                            {{ $ringkasan->capaian_follower_growth_pct ?? 125 }}%
                        </span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                </div>

                <!-- Total Konten Target -->
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-1.5">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-slate-700">Total Konten: 256 / 200</span>
                        <span class="font-mono font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                            {{ $ringkasan->capaian_konten_pct ?? 128 }}%
                        </span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== OPERASIONAL & INSIGHT (KALENDER + NOTIFIKASI) ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5" id="kalender-konten">
        <!-- KALENDER KONTEN (anchor #kalender-konten) -->
        <div class="lg:col-span-6 bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-black uppercase tracking-wider text-[#00264A]">Kalender Konten Digital</h3>
                    <p class="text-[11px] text-slate-500 font-medium">Jadwal penayangan, format produksi & status rilis</p>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-50 text-[#005B9F] border border-blue-200">
                    September 2025
                </span>
            </div>

            <div class="space-y-2.5">
                @php
                    $kalender = is_array($ringkasan->kalender_json) ? $ringkasan->kalender_json : [
                        ['tanggal' => '2 Sep Sel', 'judul' => 'Maluku Hari Ini', 'format' => 'Short Video', 'status' => 'Published'],
                        ['tanggal' => '3 Sep Rab', 'judul' => 'Tips Wisata Maluku', 'format' => 'Reels', 'status' => 'Scheduled'],
                        ['tanggal' => '4 Sep Kam', 'judul' => 'Dialog Maluku', 'format' => 'Full Video', 'status' => 'Editing'],
                        ['tanggal' => '5 Sep Jum', 'judul' => 'UMKM Maluku', 'format' => 'Short Video', 'status' => 'Draft'],
                        ['tanggal' => '6 Sep Sab', 'judul' => 'Pesona Alam Maluku', 'format' => 'Reels', 'status' => 'Planning'],
                    ];
                @endphp

                @foreach ($kalender as $cal)
                    <div class="p-3 rounded-2xl border border-slate-100 bg-slate-50/70 hover:bg-slate-50 transition-colors flex items-center justify-between gap-3">
                        <div class="flex items-center space-x-3">
                            <span class="w-14 text-center px-2 py-1 rounded-xl bg-white border border-slate-200 font-mono text-[10px] font-black text-[#00264A]">
                                {{ $cal['tanggal'] ?? '-' }}
                            </span>
                            <div>
                                <p class="text-xs font-bold text-[#00264A]">{{ $cal['judul'] ?? '-' }}</p>
                                <p class="text-[10px] text-slate-400 font-semibold">{{ $cal['format'] ?? '-' }}</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[9px] font-extrabold uppercase
                            {{ ($cal['status'] ?? '') == 'Published' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' :
                               (($cal['status'] ?? '') == 'Scheduled' ? 'bg-blue-50 text-blue-700 border border-blue-200' :
                               (($cal['status'] ?? '') == 'Editing' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-slate-200 text-slate-700')) }}">
                            {{ $cal['status'] ?? '-' }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- NOTIFIKASI & INSIGHT (anchor #laporan, #kolaborasi) -->
        <div class="lg:col-span-6 bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-sm space-y-4" id="laporan">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-black uppercase tracking-wider text-[#00264A]">Notifikasi & Insight Konten</h3>
                    <p class="text-[11px] text-slate-500 font-medium">Rekomendasi taktis untuk optimasi performa digital</p>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-pink-50 text-pink-700 border border-pink-200">
                    Live Insights
                </span>
            </div>

            <div class="space-y-2.5">
                @php
                    $notifikasi = is_array($ringkasan->notifikasi_json) ? $ringkasan->notifikasi_json : [
                        ['tipe' => 'positive', 'simbol' => '+', 'pesan' => 'Konten "Pesona Raja Ampat" trending +120%'],
                        ['tipe' => 'positive', 'simbol' => '+', 'pesan' => 'Engagement TikTok meningkat 28%'],
                        ['tipe' => 'info', 'simbol' => '!', 'pesan' => 'Waktu terbaik posting: 18.00 - 21.00 WIT'],
                        ['tipe' => 'info', 'simbol' => '!', 'pesan' => 'Pertumbuhan followers di atas target'],
                        ['tipe' => 'warning', 'simbol' => '⚠', 'pesan' => 'Perlu peningkatan variasi konten edukasi'],
                    ];
                @endphp

                @foreach ($notifikasi as $notif)
                    @php
                        $isPos = ($notif['tipe'] ?? '') === 'positive';
                        $isWarn = ($notif['tipe'] ?? '') === 'warning';
                        $bgCard = $isPos ? 'bg-emerald-50/50 border-emerald-100' : ($isWarn ? 'bg-amber-50/50 border-amber-100' : 'bg-blue-50/50 border-blue-100');
                        $badgeBg = $isPos ? 'bg-emerald-500 text-white' : ($isWarn ? 'bg-amber-500 text-white' : 'bg-blue-500 text-white');
                    @endphp
                    <div class="p-3 rounded-2xl border {{ $bgCard }} flex items-center space-x-3">
                        <span class="w-6 h-6 rounded-full {{ $badgeBg }} flex items-center justify-center font-black text-xs flex-shrink-0">
                            {{ $notif['simbol'] ?? '!' }}
                        </span>
                        <p class="text-xs font-semibold text-slate-700">{{ $notif['pesan'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- ===== SOP & REGULASI KONTEN MEDIA BARU ===== -->
    <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-sm space-y-4" id="manajemen-tim">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-pink-600"></span>
                    Standar Operasional Prosedur (SOP) & Regulasi Konten Media Baru
                </h3>
                <p class="text-[11px] text-slate-500 font-medium">Pedoman kerja resmi produksi konten, siaran langsung multiplatform, dan moderasi media sosial</p>
            </div>
            <a href="{{ route('tvri.mbn.manage', ['tab' => 'sop']) }}"
               class="px-3.5 py-1.5 rounded-xl bg-pink-50 hover:bg-pink-100 text-pink-700 border border-pink-200 text-xs font-bold self-start sm:self-auto transition-colors flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Kelola SOP KMB</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @forelse ($sops as $sop)
                <div class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-white hover:shadow-sm transition-all space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-mono font-black text-[10px] text-pink-600 bg-pink-50 px-2 py-0.5 rounded-md border border-pink-200">
                            {{ $sop->kode }}
                        </span>
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                            {{ $sop->status }} &bull; {{ $sop->versi }}
                        </span>
                    </div>
                    <h4 class="text-xs font-bold text-[#00264A] leading-snug">{{ $sop->judul }}</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed">{{ $sop->keterangan }}</p>
                    <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between text-[10px] text-slate-400">
                        <span>Kategori: <strong class="text-slate-600">{{ $sop->kategori }}</strong></span>
                        @php $fileUrl = $sop->file_url ?: $sop->file_path; @endphp
@if($fileUrl)
<a href="{{ asset($fileUrl) }}" target="_blank" class="inline-flex items-center space-x-1 px-2 py-0.5 rounded text-[10px] font-bold bg-pink-50 text-pink-700 hover:bg-pink-100 border border-pink-200 transition" title="Buka Fisik SOP">
    <svg class="w-3 h-3 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
    <span>Fisik SOP</span>
</a>
@else
<span>{{ $sop->tanggal_efektif ? \Carbon\Carbon::parse($sop->tanggal_efektif)->format('d M Y') : 'Aktif' }}</span>
@endif
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-6 text-center text-slate-400 text-xs">
                    Belum ada data SOP KMB.
                </div>
            @endforelse
        </div>
    </div>

    <!-- ===== FOOTER & VALUE PILLARS ===== -->
    <div class="bg-gradient-to-r from-[#001D38] via-[#00264A] to-[#003B70] rounded-3xl p-6 lg:p-7 text-white shadow-xl relative overflow-hidden border border-blue-400/20 text-center space-y-4">
        <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3">
            @php
                $footers = is_array($ringkasan->footer_points_json) ? $ringkasan->footer_points_json : [
                    'Konten Lokal Untuk Dunia',
                    'Audiens Lebih Dekat Maluku Lebih Dikenal',
                    'Kreativitas Tanpa Batas Inspirasi Tiada Henti',
                    'Data untuk Aksi Aksi untuk Dampak',
                    'TVRI Maluku Mendunia'
                ];
            @endphp
            @foreach ($footers as $fp)
                <span class="px-3.5 py-1.5 rounded-xl bg-white/10 hover:bg-white/15 border border-white/20 text-xs font-bold text-blue-100 transition-colors">
                    {{ $fp }}
                </span>
            @endforeach
        </div>

        <div class="pt-2 border-t border-white/10">
            <p class="text-lg sm:text-xl font-black text-amber-300 tracking-wider">
                &ldquo;{{ $ringkasan->quote_penutup ?? 'Maluku Bisa! Indonesia Maju!' }}&rdquo;
            </p>
            <p class="text-[11px] text-blue-200/80 uppercase tracking-widest mt-1">
                TVRI Maluku &bull; Kanal Konten Media Baru
            </p>
        </div>
    </div>

</div>
@endsection
