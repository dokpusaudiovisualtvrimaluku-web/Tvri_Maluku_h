@extends('layouts.app')

@section('title', 'DASHBOARD TEKNIK TVRI MALUKU')

@section('content')
<div class="space-y-6 pb-12" id="dashboard">

    <!-- ===== HEADER UTAMA DASHBOARD TEKNIK TVRI MALUKU ===== -->
    <div class="relative bg-gradient-to-r from-[#00142A] via-[#002244] to-[#003B70] rounded-3xl p-6 sm:p-8 text-white shadow-xl overflow-hidden border border-cyan-400/20">
        <!-- Glow accents -->
        <div class="absolute -right-12 -top-16 w-80 h-80 bg-cyan-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-12 -bottom-16 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-6">
            <!-- Brand & Info -->
            <div class="flex items-start sm:items-center space-x-4">
                <div class="w-16 h-16 rounded-2xl bg-white p-2 shadow-xl flex-shrink-0 flex items-center justify-center border-2 border-cyan-300/40">
                    <img src="{{ asset('images/logo_tvri.png') }}" alt="Logo TVRI" class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-cyan-400/20 text-cyan-300 border border-cyan-400/30">
                            {{ $ringkasan->tagline ?? 'Maluku Bisa Lebih Jauh!' }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> STATUS: {{ strtoupper($meta['status'] ?? 'ONLINE') }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/10 text-cyan-200 border border-white/15">
                            Periode: {{ $ringkasan->periode ?? 'September 2025' }}
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white mt-1 leading-tight uppercase">
                        {{ $ringkasan->judul ?? 'DASHBOARD TEKNIK TVRI MALUKU' }}
                    </h1>
                    <p class="text-[11px] sm:text-xs text-blue-200 font-semibold tracking-wide uppercase mt-0.5">
                        {{ $ringkasan->sub_judul ?? 'TVRI MALUKU Media Pemersatu Bangsa ' }}
                    </p>
                    <p class="text-xs sm:text-sm font-extrabold text-cyan-200 tracking-wider uppercase mt-0.5">
                        {{ $ringkasan->slogan ?? 'SIARAN ANDAL • TEKNOLOGI TERDEPAN • LAYANAN TANPA HENTI' }}
                    </p>
                    <p class="text-xs text-blue-200/90 font-medium italic mt-1">
                        &ldquo;{{ $ringkasan->kutipan ?? 'Infrastruktur Kuat, Siaran Berkualitas, untuk Maluku dan Indonesia' }}&rdquo;
                    </p>
                </div>
            </div>

            <!-- Meta Waktu & Link ECC Kepala Stasiun -->
            <div class="flex flex-col sm:flex-row xl:flex-col items-start xl:items-end justify-between gap-3">
                <div class="bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-white/15 text-left xl:text-right">
                    <div class="flex items-center xl:justify-end space-x-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-[10px] text-emerald-300 font-black uppercase tracking-wider">Live Broadcast Control</span>
                    </div>
                    <p class="text-xs font-black text-white mt-0.5 global-live-datetime-bullet" id="liveClockWidget">{{ $ringkasan->waktu_status ?? 'Selasa, 2 September 2025 | 10:24 WIT | Online' }}</p>
                </div>

                <div class="flex items-center space-x-2">
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
                    <a href="{{ route('tvri.teknik.manage') }}" 
                       class="inline-flex items-center space-x-1.5 px-4 py-2.5 rounded-xl text-xs font-black bg-cyan-600 hover:bg-cyan-500 text-white shadow-lg shadow-cyan-900/40 border border-cyan-300/30 transition-all hover:scale-[1.02]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        <span>Input & Kelola Data Teknik</span>
                    </a>
                    @endif
                    <a href="{{ route('tvri.ecc.dashboard') }}" 
                       class="inline-flex items-center space-x-1.5 px-3.5 py-2.5 rounded-xl text-xs font-bold bg-white/10 hover:bg-white/20 text-white border border-white/15 transition-all">
                        ECC Stasiun &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== 1. RINGKASAN KINERJA UTAMA (KPI) ===== -->
    <div>
        <div class="flex items-center justify-between mb-3 px-1">
            <h2 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-cyan-600"></span>
                <span>Indikator Kinerja (KPI) &bull; Teknik Studio & Penyiaran</span>
            </h2>
            <span class="text-[11px] font-bold text-slate-500">Kesiapan Operasional 24/7 TVRI Maluku</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-3">
            <!-- KPI 1: On-Air -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">On-Air</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                </div>
                <div class="my-2">
                    <p class="text-2xl font-black text-emerald-600">{{ $kpi['on_air'] }}%</p>
                    <p class="text-[11px] font-extrabold text-[#00264A]">Siaran Aktif</p>
                </div>
                <div class="text-[10px] text-slate-500 font-semibold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-lg border border-emerald-100 text-center">
                    Transmisi Stabil
                </div>
            </div>

            <!-- KPI 2: Transmitter -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Transmitter</span>
                    <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div class="my-2">
                    <p class="text-2xl font-black text-[#00264A]">{{ $kpi['transmitter_on'] }}/{{ $kpi['transmitter_total'] }}</p>
                    <p class="text-[11px] font-extrabold text-[#00264A]">Beroperasi</p>
                </div>
                <div class="text-[10px] text-slate-500 font-semibold bg-cyan-50 text-cyan-700 px-2 py-0.5 rounded-lg border border-cyan-100 text-center">
                    8/8 Transmitter On
                </div>
            </div>

            <!-- KPI 3: Studio -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Studio</span>
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                </div>
                <div class="my-2">
                    <p class="text-2xl font-black text-[#00264A]">{{ $kpi['studio_ready'] }}/{{ $kpi['studio_total'] }}</p>
                    <p class="text-[11px] font-extrabold text-[#00264A]">Siap Produksi</p>
                </div>
                <div class="text-[10px] text-slate-500 font-semibold bg-purple-50 text-purple-700 px-2 py-0.5 rounded-lg border border-purple-100 text-center">
                    4 Studio Terdata
                </div>
            </div>

            <!-- KPI 4: Peralatan Kritis -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Peralatan Kritis</span>
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="my-2">
                    <p class="text-2xl font-black text-emerald-600">{{ $kpi['peralatan_kritis_pct'] }}%</p>
                    <p class="text-[11px] font-extrabold text-[#00264A]">Berfungsi Normal</p>
                </div>
                <div class="text-[10px] text-slate-500 font-semibold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-lg border border-emerald-100 text-center">
                    High Reliability
                </div>
            </div>

            <!-- KPI 5: Insiden Hari Ini -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Insiden Hari Ini</span>
                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-100 text-amber-800">1</span>
                </div>
                <div class="my-2">
                    <p class="text-2xl font-black text-amber-600">{{ $kpi['insiden_hari_ini'] }}</p>
                    <p class="text-[11px] font-extrabold text-[#00264A]">Studio 4 Audio</p>
                </div>
                <div class="text-[10px] text-slate-500 font-bold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-lg border border-emerald-100 text-center">
                    {{ $kpi['insiden_diff'] }}% dari hari lalu
                </div>
            </div>

            <!-- KPI 6: Maintenance On Schedule -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Maintenance</span>
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div class="my-2">
                    <p class="text-2xl font-black text-[#00264A]">{{ $kpi['maintenance_pct'] }}%</p>
                    <p class="text-[11px] font-extrabold text-[#00264A]">On Schedule</p>
                </div>
                <div class="text-[10px] text-slate-500 font-semibold bg-blue-50 text-blue-700 px-2 py-0.5 rounded-lg border border-blue-100 text-center">
                    Target {{ $kpi['maintenance_target'] }}%
                </div>
            </div>

            <!-- KPI 7: Efisiensi Energi -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Efisiensi Energi</span>
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div class="my-2">
                    <p class="text-2xl font-black text-[#00264A]">{{ $kpi['efisiensi_energi_pct'] }}%</p>
                    <p class="text-[11px] font-extrabold text-[#00264A]">Genset & PLN</p>
                </div>
                <div class="text-[10px] text-slate-500 font-semibold bg-slate-100 text-slate-700 px-2 py-0.5 rounded-lg border border-slate-200 text-center">
                    {{ $kpi['efisiensi_diff'] }}% dr bulan lalu
                </div>
            </div>
        </div>
    </div>

    <!-- ===== 2. STATUS SIARAN SAAT INI & 3. TECHNICAL READINESS INDEX ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5" id="status-siaran">

        <!-- 2. STATUS SIARAN SAAT INI (LIVE ON-AIR CARD) -->
        <div class="lg:col-span-5 bg-gradient-to-br from-[#001D38] via-[#00264A] to-[#003B70] rounded-3xl p-6 text-white shadow-lg border border-cyan-400/30 relative overflow-hidden flex flex-col justify-between">
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-rose-500/10 rounded-full blur-2xl pointer-events-none"></div>

            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-rose-600 text-white shadow-lg shadow-rose-900/40 animate-pulse">
                            <span class="w-2 h-2 rounded-full bg-white"></span> LIVE
                        </span>
                        <span class="text-xs font-extrabold uppercase tracking-wider text-cyan-300">Status Siaran Saat Ini</span>
                    </div>
                    <span class="px-2.5 py-1 rounded-xl text-[10px] font-mono font-bold bg-white/10 text-white border border-white/15">
                        {{ $siaranSaatIni['durasi'] }}
                    </span>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-md mb-3">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-cyan-300">Program Berjalan</p>
                    <h3 class="text-xl sm:text-2xl font-black text-white mt-1 leading-tight tracking-tight">
                        {{ $siaranSaatIni['program'] }}
                    </h3>
                    <p class="text-xs font-extrabold text-blue-200 mt-0.5">{{ $siaranSaatIni['tagline'] }}</p>
                </div>

                <!-- Running text marquee -->
                <div class="bg-blue-950/60 border border-blue-400/20 rounded-xl px-3 py-1.5 mb-4 overflow-hidden">
                    <p class="text-[10px] font-mono font-bold text-amber-300 truncate">
                        {{ $siaranSaatIni['running_text'] ?? 'TVRI 10:24 WIT MALUKU: POTENSI MARITIM UNTUK MASA DEPAN >>' }}
                    </p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                    <div class="bg-white/10 rounded-xl p-2.5 border border-white/10">
                        <p class="text-[9px] font-bold uppercase text-slate-300">Sumber</p>
                        <p class="text-xs font-black text-white mt-0.5">{{ $siaranSaatIni['sumber'] }}</p>
                    </div>
                    <div class="bg-white/10 rounded-xl p-2.5 border border-white/10">
                        <p class="text-[9px] font-bold uppercase text-slate-300">Resolusi</p>
                        <p class="text-xs font-black text-cyan-300 mt-0.5">{{ $siaranSaatIni['resolusi'] }}</p>
                    </div>
                    <div class="bg-white/10 rounded-xl p-2.5 border border-white/10">
                        <p class="text-[9px] font-bold uppercase text-slate-300">Audio</p>
                        <p class="text-xs font-black text-white mt-0.5">{{ $siaranSaatIni['audio'] }}</p>
                    </div>
                    <div class="bg-white/10 rounded-xl p-2.5 border border-white/10">
                        <p class="text-[9px] font-bold uppercase text-slate-300">Bitrate</p>
                        <p class="text-xs font-black text-white mt-0.5">{{ $siaranSaatIni['bitrate'] }}</p>
                    </div>
                    <div class="bg-white/10 rounded-xl p-2.5 border border-white/10">
                        <p class="text-[9px] font-bold uppercase text-slate-300">Durasi</p>
                        <p class="text-xs font-black text-white mt-0.5">{{ $siaranSaatIni['durasi'] }}</p>
                    </div>
                    <div class="bg-white/10 rounded-xl p-2.5 border border-white/10">
                        <p class="text-[9px] font-bold uppercase text-slate-300">Status</p>
                        <p class="text-xs font-black text-emerald-400 mt-0.5">{{ $siaranSaatIni['status'] }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-[11px] text-blue-200 font-semibold">
                <span>Playout Master Control Room</span>
                <span class="text-emerald-300 font-black flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Signal Locked
                </span>
            </div>
        </div>

        <!-- 3. TECHNICAL READINESS INDEX -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-between" id="readiness">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center space-x-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <span>Technical Readiness Index</span>
                        </h2>
                        <p class="text-[11px] font-semibold text-slate-500 mt-0.5">Indeks Kesiapan 6 Aspek Fasilitas Teknik Penyiaran</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-black text-emerald-600 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-xl">
                            Overall Score: <strong class="text-sm font-black text-emerald-700">{{ $readiness['overall'] }}%</strong> ({{ $readiness['predikat'] }})
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    @foreach($readiness['items'] as $item)
                    <div class="p-3 rounded-2xl border border-slate-100 bg-slate-50/70 hover:bg-white hover:border-blue-200 transition-all">
                        <div class="flex items-center justify-between text-xs mb-1.5">
                            <span class="font-extrabold text-[#00264A]">{{ $item['aspek'] }}</span>
                            <span class="font-mono font-black text-slate-800">{{ $item['skor'] }}%</span>
                        </div>
                        <div class="h-2 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full {{ $item['color'] }} rounded-full transition-all duration-500" style="width: {{ $item['skor'] }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 font-semibold">
                <span>Standar Mutu Penyiaran LPP TVRI Pusat</span>
                <span class="text-emerald-600 font-bold">Ambang Batas Minimum: 85% &bull; Status: Memenuhi Standar</span>
            </div>
        </div>

    </div>

    <!-- ===== PETA JANGKAUAN & MONITORING SISTEM ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5" id="peta-transmisi">
        <!-- PETA JANGKAUAN & TRANSMISI -->
        <div class="lg:col-span-6 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-cyan-600"></span>
                        <span>Peta Jangkauan & Transmisi</span>
                    </h2>
                    <p class="text-[11px] font-semibold text-slate-500 mt-0.5">Lokasi: {{ $ringkasan->tx_locations_text ?? 'Ternate, Sanana, Namlea, Ambon, Masohi, Bula, Saumlaki' }}</p>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-cyan-50 text-cyan-800 border border-cyan-200">
                    8 Lokasi Tx
                </span>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                    <p class="text-xl font-black text-[#00264A]">{{ $ringkasan->transmitter_total ?? 8 }}</p>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mt-0.5">Total Transmitter</p>
                </div>
                <div class="p-3.5 rounded-2xl bg-cyan-50/50 border border-cyan-100 text-center">
                    <p class="text-xl font-black text-cyan-700">{{ $ringkasan->coverage_area_pct ?? 92 }}%</p>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mt-0.5">Coverage Area</p>
                </div>
                <div class="p-3.5 rounded-2xl bg-emerald-50/50 border border-emerald-100 text-center">
                    <p class="text-xl font-black text-emerald-700">1,2 Juta</p>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mt-0.5">Estimasi Pemirsa</p>
                </div>
            </div>

            <div class="p-3 rounded-2xl bg-slate-50/70 border border-slate-200/60 flex items-center justify-around text-xs">
                <span class="inline-flex items-center gap-1.5 font-bold text-slate-700">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Transmitter Aktif (7)
                </span>
                <span class="inline-flex items-center gap-1.5 font-bold text-slate-700">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Transmitter Siaga (1)
                </span>
                <span class="inline-flex items-center gap-1.5 font-bold text-slate-700">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Transmitter Off (0)
                </span>
            </div>
        </div>

        <!-- MONITORING SISTEM SIARAN -->
        <div class="lg:col-span-6 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4" id="monitoring-sistem">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                        <span>Monitoring Sistem Siaran</span>
                    </h2>
                    <p class="text-[11px] font-semibold text-slate-500 mt-0.5">Status Saluran Sinyal & Kestabilan Feed</p>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    {{ $ringkasan->sinyal_trend ?? 'Sinyal Stabil' }}
                </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <span class="font-bold text-slate-700">Video</span>
                    <span class="font-mono font-black text-emerald-600">{{ $ringkasan->video_pct ?? 100 }}%</span>
                </div>
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <span class="font-bold text-slate-700">Audio</span>
                    <span class="font-mono font-black text-emerald-600">{{ $ringkasan->audio_pct ?? 100 }}%</span>
                </div>
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <span class="font-bold text-slate-700">Playout</span>
                    <span class="font-mono font-black text-emerald-600">{{ $ringkasan->playout_pct ?? 100 }}%</span>
                </div>
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <span class="font-bold text-slate-700">Streaming</span>
                    <span class="font-mono font-black text-emerald-600">{{ $ringkasan->streaming_pct ?? 99 }}%</span>
                </div>
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <span class="font-bold text-slate-700">Uplink</span>
                    <span class="font-mono font-black text-emerald-600">{{ $ringkasan->uplink_pct ?? 100 }}%</span>
                </div>
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <span class="font-bold text-slate-700">Network</span>
                    <span class="font-mono font-black text-cyan-600">{{ $ringkasan->network_pct ?? 96 }}%</span>
                </div>
            </div>

            <p class="text-[11px] text-slate-500 font-medium">
                TREND KUALITAS SINYAL (24 JAM): <strong class="text-emerald-600">Sinyal Stabil & Kuat</strong> di seluruh stasiun transmisi digital TVRI Maluku.
            </p>
        </div>
    </div>

    <!-- ===== STATUS STUDIO & STATUS TRANSMITTER ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5" id="status-studio">

        <!-- STATUS STUDIO -->
        <div class="lg:col-span-5 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                    <span>Status Studio Penyiaran</span>
                </h2>
                <span class="text-[11px] font-bold text-slate-400">4 Studio Terintegrasi</span>
            </div>

            <div class="space-y-2.5">
                @foreach($statusStudio as $st)
                <div class="p-3.5 rounded-2xl border border-slate-100 bg-slate-50/80 hover:bg-slate-50 transition-colors flex items-center justify-between gap-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center font-black text-xs text-[#00264A] shadow-sm">
                            {{ substr($st['nama'], 0, 1) }}{{ substr($st['nama'], -1) }}
                        </div>
                        <div>
                            <p class="font-black text-sm text-[#00264A]">{{ $st['nama'] }}</p>
                            <p class="text-[11px] text-slate-500 font-semibold">{{ $st['keterangan'] ?? '' }}</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-xl text-[10px] font-black uppercase whitespace-nowrap {{ $st['badge'] ?? 'bg-slate-100 text-slate-700' }}">
                        {{ $st['status'] }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- STATUS TRANSMITTER (TABEL) -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4" id="status-transmitter">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-cyan-600"></span>
                        <span>Status Transmitter Digital</span>
                    </h2>
                    <p class="text-[11px] font-semibold text-slate-500 mt-0.5">Monitoring 8 Stasiun Pemancar TVRI Maluku</p>
                </div>
                <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold bg-cyan-50 text-cyan-800 border border-cyan-200">
                    8 Stasiun
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-[10px] font-black uppercase tracking-wider text-slate-400 bg-slate-50/70">
                            <th class="py-2.5 px-3 rounded-l-xl">Lokasi</th>
                            <th class="py-2.5 px-3">Frekuensi</th>
                            <th class="py-2.5 px-3">Daya (Power)</th>
                            <th class="py-2.5 px-3">Status</th>
                            <th class="py-2.5 px-3 text-right rounded-r-xl">Sinyal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($transmitters as $tx)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-2.5 px-3 font-bold text-[#00264A]">{{ $tx->lokasi }}</td>
                            <td class="py-2.5 px-3 font-mono text-slate-600">{{ $tx->frekuensi }}</td>
                            <td class="py-2.5 px-3 font-mono text-slate-600">{{ $tx->daya }}</td>
                            <td class="py-2.5 px-3">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase
                                    {{ $tx->status === 'Aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    {{ $tx->status }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 text-right">
                                <span class="inline-flex items-center gap-1 font-bold text-[10px] {{ $tx->sinyal_warna === 'Hijau' ? 'text-emerald-600' : 'text-amber-600' }}">
                                    <span class="w-2 h-2 rounded-full {{ $tx->sinyal_warna === 'Hijau' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                    {{ $tx->sinyal_warna }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- ===== JADWAL PRODUKSI & MAINTENANCE PERALATAN ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5" id="jadwal-produksi">

        <!-- JADWAL PRODUKSI & TEKNIK HARI INI -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                        <span>Jadwal Produksi & Teknik Hari Ini</span>
                    </h2>
                    <p class="text-[11px] font-semibold text-slate-500 mt-0.5">Alokasi Studio, Peralatan & Tim Operasional</p>
                </div>
                <a href="{{ route('tvri.teknik.manage', ['tab' => 'jadwal']) }}" class="text-[10px] font-bold text-blue-600 hover:text-blue-800 bg-blue-50 px-2 py-1 rounded-lg border border-blue-100">
                    + Kelola Jadwal
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-[10px] font-black uppercase tracking-wider text-slate-400">
                            <th class="pb-2.5 font-bold">Waktu</th>
                            <th class="pb-2.5 font-bold">Program / Kegiatan</th>
                            <th class="pb-2.5 font-bold">Lokasi</th>
                            <th class="pb-2.5 font-bold">Kebutuhan Teknik</th>
                            <th class="pb-2.5 font-bold text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($jadwals as $j)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-2.5 font-mono font-bold text-cyan-700 whitespace-nowrap">{{ $j->waktu }}</td>
                            <td class="py-2.5 font-black text-[#00264A]">{{ $j->program }}</td>
                            <td class="py-2.5 text-slate-600 font-semibold">{{ $j->lokasi }}</td>
                            <td class="py-2.5 text-slate-500 font-medium">{{ $j->kebutuhan }}</td>
                            <td class="py-2.5 text-right whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $j->badge }}">
                                    {{ $j->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MAINTENANCE & SERVIS PERALATAN -->
        <div class="lg:col-span-5 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4" id="maintenance-servis">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-cyan-600"></span>
                        <span>Maintenance & Servis Peralatan</span>
                    </h2>
                    <p class="text-[11px] font-semibold text-slate-500 mt-0.5">Jadwal Perawatan Preventif & Servis</p>
                </div>
                <a href="{{ route('tvri.teknik.manage', ['tab' => 'maintenance']) }}" class="text-[10px] font-bold text-cyan-700 hover:text-cyan-900 bg-cyan-50 px-2 py-1 rounded-lg border border-cyan-100">
                    + Kelola Servis
                </a>
            </div>

            <div class="space-y-2.5">
                @foreach($maintenances as $m)
                <div class="p-3 rounded-2xl border border-slate-100 bg-slate-50/70 hover:bg-white hover:border-slate-200 transition-all flex items-center justify-between gap-3 text-xs">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="font-mono text-[10px] font-bold text-cyan-800">{{ $m->tanggal }}</span>
                            <span class="text-slate-300">&bull;</span>
                            <span class="font-black text-[#00264A]">{{ $m->peralatan }}</span>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-0.5">{{ $m->jenis }} &bull; PIC: <strong class="text-slate-700">{{ $m->pic }}</strong></p>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase whitespace-nowrap
                        {{ $m->status === 'Selesai' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' :
                           ($m->status === 'On Progress' ? 'bg-amber-50 text-amber-700 border border-amber-200 animate-pulse' : 'bg-slate-100 text-slate-700') }}">
                        {{ $m->status }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- ===== INSIDEN GANGGUAN & INVENTARIS PERALATAN ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5" id="insiden-gangguan">

        <!-- INSIDEN & GANGGUAN -->
        <div class="lg:col-span-5 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        <span>Insiden & Gangguan Teknik</span>
                    </h2>
                    <p class="text-[11px] font-semibold text-slate-500 mt-0.5">Monitoring & Riwayat Penanganan Isu</p>
                </div>
                <a href="{{ route('tvri.teknik.manage', ['tab' => 'insiden']) }}" class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-1 rounded-lg border border-amber-100">
                    + Catat Gangguan
                </a>
            </div>

            <div class="space-y-2.5">
                @foreach($insidens as $ins)
                <div class="p-3 rounded-2xl border border-slate-100 bg-slate-50/80 hover:bg-white hover:border-slate-300 transition-all">
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="font-mono font-black text-cyan-800 text-[11px]">{{ $ins->waktu }}</span>
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase border {{ $ins->badge }}">
                            {{ $ins->status }}
                        </span>
                    </div>
                    <p class="text-xs font-black text-[#00264A]">{{ $ins->lokasi }}</p>
                    <p class="text-[11px] text-slate-600 font-medium mt-0.5">{{ $ins->deskripsi }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- INVENTARIS PERALATAN UTAMA -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4" id="inventaris">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-cyan-600"></span>
                        <span>Inventaris Peralatan Utama</span>
                    </h2>
                    <p class="text-[11px] font-semibold text-slate-500 mt-0.5">Kamera, Editing, Server, Transmisi, Genset & Software</p>
                </div>
                <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold bg-cyan-50 text-cyan-800 border border-cyan-200">
                    71 Baik (91%)
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                @foreach($peralatans as $alt)
                <div class="p-3 rounded-2xl border border-slate-200/80 bg-slate-50/60 hover:bg-white hover:border-[#005B9F] transition-all">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-black text-[#00264A]">{{ $alt['nama'] }}</p>
                        <span class="text-[10px] font-mono font-bold text-slate-500">{{ $alt['total'] }} unit</span>
                    </div>
                    <p class="text-[10px] text-slate-400 font-semibold">{{ $alt['kategori'] }}</p>

                    <div class="mt-2.5 flex items-center justify-between text-[11px]">
                        <span class="font-extrabold text-emerald-600">{{ $alt['status_label'] }}</span>
                        <span class="font-mono font-black text-slate-700">{{ $alt['persen'] }}%</span>
                    </div>
                    <div class="h-1.5 bg-slate-200 rounded-full overflow-hidden mt-1">
                        <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $alt['persen'] }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- ===== DOKUMEN & SOP TEKNIK ===== -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4" id="sop-dokumen">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-600"></span>
                    <span>Dokumen & Standar Operasional Prosedur (SOP) Teknik</span>
                </h2>
                <p class="text-[11px] font-semibold text-slate-500 mt-0.5">SOP Operasional Siaran, Transmisi, Studio, Maintenance, Manual Peralatan, & Formulir Laporan Gangguan</p>
            </div>
            <a href="{{ route('tvri.teknik.manage', ['tab' => 'sop']) }}"
               class="px-3.5 py-1.5 rounded-xl bg-cyan-50 hover:bg-cyan-100 text-cyan-800 border border-cyan-200 text-xs font-bold transition-colors flex items-center gap-1.5 self-start sm:self-auto">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Kelola SOP Teknik</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @forelse($sops as $sop)
            <div class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-white hover:shadow-sm transition-all space-y-2">
                <div class="flex items-center justify-between">
                    <span class="font-mono font-black text-[10px] text-cyan-700 bg-cyan-50 px-2 py-0.5 rounded-md border border-cyan-200">
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
<a href="{{ asset($fileUrl) }}" target="_blank" class="inline-flex items-center space-x-1 px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-50 text-cyan-700 hover:bg-cyan-100 border border-cyan-200 transition" title="Buka Dokumen Fisik SOP">
    <svg class="w-3 h-3 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
    <span>Fisik SOP</span>
</a>
@else
<span>{{ $sop->tanggal_efektif ? \Carbon\Carbon::parse($sop->tanggal_efektif)->format('d M Y') : 'Aktif' }}</span>
@endif
                </div>
            </div>
            @empty
            <div class="col-span-3 py-6 text-center text-slate-400 text-xs">Belum ada dokumen SOP teknik.</div>
            @endforelse
        </div>
    </div>

    <!-- ===== FOOTER & VALUE PILLARS TEKNIK ===== -->
    <div class="bg-gradient-to-r from-[#00142A] via-[#002244] to-[#003B70] rounded-3xl p-6 lg:p-7 text-white shadow-xl relative overflow-hidden border border-cyan-400/20 text-center space-y-4">
        <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3">
            @php
                $footers = is_array($ringkasan->footer_points_json) ? $ringkasan->footer_points_json : [
                    'Siap Siar, Siap Melayani',
                    'Teknologi untuk Negeri',
                    'Andal, Profesional, Kolaboratif',
                    'Siaran Berkualitas, Masyarakat Cerdas',
                    'Maluku Bersiaran Untuk Indonesia'
                ];
            @endphp
            @foreach ($footers as $fp)
                <span class="px-3.5 py-1.5 rounded-xl bg-white/10 hover:bg-white/15 border border-white/20 text-xs font-bold text-cyan-200 transition-colors">
                    {{ $fp }}
                </span>
            @endforeach
        </div>

        <div class="pt-2 border-t border-white/10">
            <p class="text-lg sm:text-xl font-black text-amber-300 tracking-wider">
                &ldquo;{{ $ringkasan->quote_penutup ?? 'Maluku Bisa! Indonesia Maju!' }}&rdquo;
            </p>
            <p class="text-[11px] text-blue-200/80 uppercase tracking-widest mt-1">
                TVRI Maluku &bull; Bidang Teknik Studio & Fasilitas
            </p>
        </div>
    </div>

</div>
@endsection
