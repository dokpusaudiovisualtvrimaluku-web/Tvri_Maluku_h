@extends('layouts.app')

@section('title', 'DASBOAR UNIT KERJA PROMO - TVRI MALUKU')

@section('content')
<div class="space-y-6 pb-12">

    <!-- ===== HEADER UTAMA DASHBOARD PROMO TVRI MALUKU ===== -->
    <div class="relative bg-gradient-to-r from-[#00142A] via-[#002244] to-[#003B70] rounded-3xl p-6 sm:p-8 text-white shadow-xl overflow-hidden border border-indigo-400/20">
        <!-- Glow accents -->
        <div class="absolute -right-12 -top-16 w-80 h-80 bg-indigo-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-12 -bottom-16 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-6">
            <!-- Brand & Info -->
            <div class="flex items-start sm:items-center space-x-4">
                <div class="w-16 h-16 rounded-2xl bg-white p-2 shadow-xl flex-shrink-0 flex items-center justify-center border-2 border-indigo-300/40">
                    <img src="{{ asset('images/logo_tvri.png') }}" alt="Logo TVRI" class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-indigo-400/20 text-indigo-300 border border-indigo-400/30">
                            DASBOAR UNIT KERJA PROMO
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> STATUS: {{ strtoupper($meta['status']) }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/10 text-cyan-200 border border-white/15">
                            Periode: {{ $meta['periode'] }}
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white mt-1 leading-tight uppercase">
                        DASBOAR UNIT KERJA PROMO
                    </h1>
                    <p class="text-xs sm:text-sm font-extrabold text-indigo-200 tracking-wider uppercase mt-0.5">
                        KREASI &bull; KOMUNIKASI &bull; KOLABORASI &bull; JANGKAUAN LEBIH LUAS
                    </p>
                    <p class="text-xs text-blue-200/90 font-medium italic mt-1">
                        &ldquo;Menguatkan Identitas, Meningkatkan Audiens, Mendukung Program TVRI Maluku &bull; Media Lokal, Dampak Global&rdquo;
                    </p>
                </div>
            </div>

            <!-- Meta Waktu & Action Buttons -->
            <div class="flex flex-col sm:flex-row xl:flex-col items-start xl:items-end justify-between gap-3">
                <div class="bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-white/15 text-left xl:text-right">
                    <div class="flex items-center xl:justify-end space-x-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-[10px] text-emerald-300 font-black uppercase tracking-wider">Promo Operations</span>
                    </div>
                    <p class="text-xs font-black text-white mt-0.5 global-live-datetime" id="liveDateTimePromo">{{ $meta['waktu'] }}</p>
                </div>

                <div class="flex items-center space-x-2">
                    @php
                        $user = auth()->user();
                        $isKepalaStasiun = $user && ($user->hasRole('KEPALA_DINAS') || $user->hasRole('Kepala Stasiun') || $user->username === 'kepala.stasiun' || str_contains(strtolower($user->name ?? ''), 'kepala stasiun'));
                    @endphp
                    @if(!$isKepalaStasiun)
                    <a href="{{ route('tvri.promo.manage') }}" 
                       class="inline-flex items-center space-x-1.5 px-4 py-2.5 rounded-xl text-xs font-black bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white shadow-md border border-amber-300/30 transition-all hover:scale-[1.02]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        <span>Input Data Promo</span>
                    </a>
                    @endif
                    <a href="{{ route('tvri.ecc.dashboard') }}" 
                       class="inline-flex items-center space-x-1.5 px-3.5 py-2.5 rounded-xl text-xs font-bold bg-white/10 hover:bg-white/20 text-white border border-white/15 transition-all">
                        <svg class="w-4 h-4 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span>ECC Kepala Stasiun</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== 1. RINGKASAN METRIK UTAMA ===== -->
    <div>
        <div class="flex items-center justify-between mb-3 px-1">
            <h2 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                <span>1. Ringkasan Metrik Utama</span>
            </h2>
            <span class="text-[11px] font-bold text-slate-500">Kinerja Kampanye & Promosi Lembaga</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <!-- 1. Total Kampanye -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Total Kampanye</span>
                    <span class="text-[9px] font-black px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-100">{{ $metrik['diff_kampanye'] }}</span>
                </div>
                <div class="my-2">
                    <p class="text-3xl font-black text-[#00264A]">{{ $metrik['total_kampanye'] }}</p>
                    <p class="text-[11px] font-extrabold text-slate-600">Kampanye Aktif</p>
                </div>
                <p class="text-[10px] text-slate-400 font-semibold">dari bulan lalu</p>
            </div>

            <!-- 2. Materi Diproduksi -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Materi Diproduksi</span>
                    <span class="text-[9px] font-black px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-100">{{ $metrik['diff_materi'] }}</span>
                </div>
                <div class="my-2">
                    <p class="text-3xl font-black text-indigo-600">{{ $metrik['materi_diproduksi'] }}</p>
                    <p class="text-[11px] font-extrabold text-slate-600">Video & Bumper</p>
                </div>
                <p class="text-[10px] text-slate-400 font-semibold">siap & proses</p>
            </div>

            <!-- 3. Penayangan On-Air -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Penayangan On-Air</span>
                    <span class="text-[9px] font-black px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-100">{{ $metrik['diff_onair'] }}</span>
                </div>
                <div class="my-2">
                    <p class="text-2xl font-black text-[#00264A]">{{ $metrik['penayangan_onair'] }}</p>
                    <p class="text-[11px] font-extrabold text-slate-600">Spot Siaran TV</p>
                </div>
                <p class="text-[10px] text-slate-400 font-semibold">frekuensi tinggi</p>
            </div>

            <!-- 4. Reach Total -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Reach Total</span>
                    <span class="text-[9px] font-black px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-100">{{ $metrik['diff_reach'] }}</span>
                </div>
                <div class="my-2">
                    <p class="text-3xl font-black text-cyan-600">{{ $metrik['reach_total'] }}</p>
                    <p class="text-[11px] font-extrabold text-slate-600">Jangkauan Pemirsa</p>
                </div>
                <p class="text-[10px] text-slate-400 font-semibold">multi-platform</p>
            </div>

            <!-- 5. Engagement Digital -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Engagement</span>
                    <span class="text-[9px] font-black px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-100">{{ $metrik['diff_engagement'] }}</span>
                </div>
                <div class="my-2">
                    <p class="text-2xl font-black text-purple-600">{{ $metrik['engagement_digital'] }}</p>
                    <p class="text-[11px] font-extrabold text-slate-600">Interaksi Publik</p>
                </div>
                <p class="text-[10px] text-slate-400 font-semibold">sosmed & web</p>
            </div>

            <!-- 6. Tingkat Capaian -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Tingkat Capaian</span>
                    <span class="text-[9px] font-black px-1.5 py-0.5 rounded bg-indigo-50 text-indigo-700 border border-indigo-100">Target {{ $metrik['target_capaian'] }}%</span>
                </div>
                <div class="my-2">
                    <p class="text-3xl font-black text-emerald-600">{{ $metrik['tingkat_capaian'] }}%</p>
                    <p class="text-[11px] font-extrabold text-slate-600">Melampaui Target</p>
                </div>
                <p class="text-[10px] text-emerald-600 font-bold">Kinerja Sangat Baik</p>
            </div>
        </div>
    </div>

    <!-- ===== 2. DISTRIBUSI MEDIA PROMO & 3. CAPAIAN KAMPANYE ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        <!-- 2. DISTRIBUSI MEDIA PROMO -->
        <div class="lg:col-span-5 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                        <span>2. Distribusi Media Promo</span>
                    </h2>
                    <p class="text-[11px] font-semibold text-slate-500 mt-0.5">Total Penayangan: 1.230 Spot Multi-Channel</p>
                </div>
                <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-100">
                    6 Media
                </span>
            </div>

            <div class="space-y-3">
                @foreach($distribusiMedia as $dm)
                <div class="p-3 rounded-2xl border border-slate-100 bg-slate-50/70">
                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="font-extrabold text-[#00264A]">{{ $dm['media'] }}</span>
                        <div class="flex items-center space-x-2">
                            <span class="text-[11px] font-semibold text-slate-500 font-mono">({{ $dm['jumlah'] }} spot)</span>
                            <span class="font-mono font-black {{ $dm['textColor'] }} text-xs">{{ $dm['persen'] }}%</span>
                        </div>
                    </div>
                    <div class="h-2 bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full {{ $dm['color'] }} rounded-full" style="width: {{ $dm['persen'] }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- 3. CAPAIAN KAMPANYE -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-cyan-600"></span>
                        <span>3. Capaian Kampanye</span>
                    </h2>
                    <p class="text-[11px] font-semibold text-slate-500 mt-0.5">Persentase Target Capaian per Kategori Kampanye</p>
                </div>
                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-100">
                    Target Rata-rata 90%
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                @foreach($capaianKampanye as $ck)
                <div class="p-3.5 rounded-2xl border border-slate-100 bg-slate-50/80 hover:bg-white hover:border-blue-200 transition-all">
                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="font-extrabold text-[#00264A]">{{ $ck['kategori'] }}</span>
                        <span class="font-mono font-black text-sm text-[#00264A]">{{ $ck['persen'] }}%</span>
                    </div>
                    <div class="h-2 bg-slate-200 rounded-full overflow-hidden mb-1.5">
                        <div class="h-full {{ $ck['color'] }} rounded-full" style="width: {{ min(100, $ck['persen']) }}%"></div>
                    </div>
                    <div class="flex items-center justify-between text-[10px]">
                        <span class="text-slate-400 font-medium">Status</span>
                        <span class="font-bold {{ $ck['persen'] >= 100 ? 'text-purple-600' : ($ck['persen'] >= 90 ? 'text-emerald-600' : 'text-amber-600') }}">
                            {{ $ck['status'] }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- ===== 4. TOP KAMPANYE & 5. PERFORMA PLATFORM DIGITAL ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        <!-- 4. TOP KAMPANYE (BERDASARKAN ENGAGEMENT) -->
        <div class="lg:col-span-6 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        <span>4. Top Kampanye (Berdasarkan Engagement)</span>
                    </h2>
                    <p class="text-[11px] font-semibold text-slate-500 mt-0.5">5 Kampanye dengan Respon Publik Tertinggi</p>
                </div>
                <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-100">
                    Leaderboard
                </span>
            </div>

            <div class="space-y-2.5">
                @foreach($topKampanye as $tk)
                <div class="p-3.5 rounded-2xl border border-slate-100 bg-slate-50/80 hover:bg-slate-50 transition-colors flex items-center justify-between gap-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-[#005B9F] flex items-center justify-center text-white font-black text-xs shadow-sm">
                            #{{ $tk['peringkat'] }}
                        </div>
                        <div>
                            <p class="font-black text-sm text-[#00264A]">{{ $tk['nama'] }}</p>
                            <p class="text-[11px] text-slate-500 font-semibold">{{ $tk['reach'] }} &bull; {{ $tk['engagement'] }}</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-xl text-[10px] font-black uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                        {{ $tk['pertumbuhan'] }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- 5. PERFORMA PLATFORM DIGITAL -->
        <div class="lg:col-span-6 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                        <span>5. Performa Platform Digital</span>
                    </h2>
                    <p class="text-[11px] font-semibold text-slate-500 mt-0.5">Statistik Akun & Kanal Distribusi Digital TVRI Maluku</p>
                </div>
                <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-lg border border-blue-100">
                    Digital Reach
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                @foreach($platformDigital as $pd)
                <div class="p-4 rounded-2xl border {{ $pd['bg'] }} flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-black text-sm {{ $pd['color'] }}">{{ $pd['platform'] }}</span>
                            <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100">
                                {{ $pd['aud_growth'] }}
                            </span>
                        </div>
                        <p class="text-lg font-black text-[#00264A]">{{ $pd['audience'] }}</p>
                    </div>
                    <div class="mt-3 pt-2.5 border-t border-slate-200/60 flex items-center justify-between text-xs">
                        <span class="font-extrabold text-slate-700">{{ $pd['metric'] }}</span>
                        <span class="font-mono font-bold text-emerald-700 text-[11px]">{{ $pd['metric_growth'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- ===== 6. STATUS PRODUKSI MATERI & 7. MATERI PROMO TERBARU ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        <!-- 6. STATUS PRODUKSI MATERI (TOTAL: 56) -->
        <div class="lg:col-span-5 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                        <span>6. Status Produksi Materi</span>
                    </h2>
                    <p class="text-[11px] font-semibold text-slate-500 mt-0.5">Total Materi: {{ $statusProduksi['total'] }} Item</p>
                </div>
                <span class="text-[10px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-lg border border-purple-100">
                    Workflow QC
                </span>
            </div>

            <div class="space-y-3">
                @foreach($statusProduksi['items'] as $sp)
                <div class="p-3 rounded-2xl border border-slate-100 bg-slate-50/70">
                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="font-extrabold text-[#00264A]">{{ $sp['status'] }}</span>
                        <div class="flex items-center space-x-2">
                            <span class="font-mono font-black text-[#00264A]">{{ $sp['jumlah'] }} materi</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $sp['badge'] }}">
                                {{ $sp['persen'] }}%
                            </span>
                        </div>
                    </div>
                    <div class="h-2 bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full {{ $sp['bar'] }} rounded-full" style="width: {{ $sp['persen'] }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- 7. MATERI PROMO TERBARU -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-teal-600"></span>
                        <span>7. Materi Promo Terbaru</span>
                    </h2>
                    <p class="text-[11px] font-semibold text-slate-500 mt-0.5">Materi Promosi & Bumper Program Aktif</p>
                </div>
                @if(!$isKepalaStasiun)
                <a href="{{ route('tvri.promo.manage') }}" class="text-[11px] font-bold text-[#005B9F] hover:text-[#003B70]">
                    + Tambah Materi &rarr;
                </a>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-[10px] font-black uppercase tracking-wider text-slate-400">
                            <th class="pb-2.5 font-bold">Nama Materi</th>
                            <th class="pb-2.5 font-bold">Jenis</th>
                            <th class="pb-2.5 font-bold">Durasi</th>
                            <th class="pb-2.5 font-bold text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($materiTerbaru as $mt)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-2.5 font-black text-[#00264A]">{{ $mt['nama'] }}</td>
                            <td class="py-2.5 text-slate-600 font-semibold">{{ $mt['jenis'] }}</td>
                            <td class="py-2.5 font-mono text-cyan-700 font-bold">{{ $mt['durasi'] }}</td>
                            <td class="py-2.5 text-right whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ $mt['status'] }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- ===== 8. JADWAL PENAYANGAN HARI INI & 9. AGENDA PROMO & EVENT ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        <!-- 8. JADWAL PENAYANGAN HARI INI -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                        <span>8. Jadwal Penayangan Hari Ini</span>
                    </h2>
                    <p class="text-[11px] font-semibold text-slate-500 mt-0.5">Alokasi Spot Siaran On-Air TVRI Maluku</p>
                </div>
                <span class="text-[10px] font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-100">
                    {{ count($jadwalTayang) }} Jadwal Spot
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-[10px] font-black uppercase tracking-wider text-slate-400">
                            <th class="pb-2.5 font-bold">Waktu</th>
                            <th class="pb-2.5 font-bold">Program / Kampanye</th>
                            <th class="pb-2.5 font-bold">Media</th>
                            <th class="pb-2.5 font-bold">Durasi</th>
                            <th class="pb-2.5 font-bold text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($jadwalTayang as $jt)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-2.5 font-mono font-bold text-cyan-700 whitespace-nowrap">{{ $jt['waktu'] }}</td>
                            <td class="py-2.5 font-black text-[#00264A]">{{ $jt['program'] }}</td>
                            <td class="py-2.5 text-slate-600 font-semibold">{{ $jt['media'] }}</td>
                            <td class="py-2.5 font-mono text-slate-500 font-semibold">{{ $jt['durasi'] }}s</td>
                            <td class="py-2.5 text-right whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $jt['badge'] }}">
                                    {{ $jt['status'] }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 9. AGENDA PROMO & EVENT -->
        <div class="lg:col-span-5 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        <span>9. Agenda Promo & Event</span>
                    </h2>
                    <p class="text-[11px] font-semibold text-slate-500 mt-0.5">Rencana Kampanye Strategis & Aktivasi</p>
                </div>
                <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-100">
                    Kalender Kegiatan
                </span>
            </div>

            <div class="space-y-2.5">
                @foreach($agendaPromo as $ap)
                <div class="p-3 rounded-2xl border border-slate-100 bg-slate-50/80 hover:bg-white hover:border-slate-300 transition-all flex items-center justify-between gap-3">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="font-mono font-black text-cyan-800 text-[11px]">{{ $ap['tanggal'] }}</span>
                            <span class="text-[10px] text-slate-400 font-medium">&bull; PIC: {{ $ap['pic'] }}</span>
                        </div>
                        <p class="text-xs font-black text-[#00264A] mt-0.5">{{ $ap['kegiatan'] }}</p>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $ap['badge'] }} whitespace-nowrap">
                        {{ $ap['status'] }}
                    </span>
                </div>
                @endforeach
            </div>

            <div class="pt-2 text-right">
                <a href="{{ route('tvri.ecc.dashboard') }}" class="text-[11px] font-bold text-[#005B9F] hover:text-[#003B70]">
                    Lihat Monitoring di Executive Command Center &rarr;
                </a>
            </div>
        </div>

    </div>

    <!-- ===== 10. SOP & REGULASI UNIT KERJA PROMO ===== -->
    <div id="sop-regulasi" class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
            <div>
                <h2 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                    <span>10. Standar Operasional Prosedur (SOP) & Regulasi Unit Kerja Promo</span>
                </h2>
                <p class="text-[11px] font-semibold text-slate-500 mt-0.5">Panduan Baku Produksi Materi Promosi, Penyiaran On-Air & Distribusi Konten Digital</p>
            </div>
            <a href="{{ route('tvri.promo.manage', ['tab' => 'sop']) }}" class="inline-flex items-center text-xs font-bold text-[#005B9F] hover:text-[#003B70] gap-1">
                <span>Kelola & Tambah SOP</span>
                <span>&rarr;</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse($sops as $sop)
            <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50/50 hover:bg-white hover:border-indigo-300 hover:shadow-sm transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-indigo-100 text-indigo-800">
                            {{ $sop->kode ?? 'SOP-PRM' }}
                        </span>
                        <span class="text-[10px] font-bold text-slate-400 font-mono">{{ $sop->versi ?? 'v1.0' }}</span>
                    </div>
                    <h4 class="text-xs font-black text-slate-900 mb-1 leading-snug">{{ $sop->judul }}</h4>
                    <p class="text-[11px] text-slate-500 line-clamp-2 mb-3">{{ $sop->keterangan ?? '-' }}</p>
                </div>
                <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between text-[10px]">
                    <span class="text-slate-500 font-semibold truncate max-w-[120px]">{{ $sop->kategori }}</span>
                    <span class="px-2 py-0.5 rounded-full font-bold uppercase {{ $sop->status === 'Aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $sop->status }}
                    </span>
                </div>
            </div>
            @empty
            <div class="col-span-4 text-center py-8 text-slate-400 text-xs">
                Belum ada dokumen SOP. Klik <a href="{{ route('tvri.promo.manage', ['tab' => 'sop']) }}" class="font-bold text-indigo-600 hover:underline">Kelola & Tambah SOP</a> untuk menambahkan SOP baru.
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
