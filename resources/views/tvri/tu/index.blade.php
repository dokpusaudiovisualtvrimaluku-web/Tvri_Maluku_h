@extends('layouts.app')

@section('title', 'Dashboard Tata Usaha - TVRI Maluku')

@section('content')
<div class="space-y-6">

    <!-- ===== HEADER UTAMA TATA USAHA ===== -->
    <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl bg-gradient-to-r from-[#001D38] via-[#002B54] to-[#004080] text-white shadow-xl sm:shadow-2xl p-4 sm:p-6 md:p-8 border border-blue-400/20">
        <div class="absolute -right-12 -top-16 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-12 -bottom-16 w-80 h-80 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex items-start md:items-center space-x-3 sm:space-x-4 min-w-0 flex-1">
                <div class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 rounded-2xl bg-white p-2 sm:p-2.5 shadow-xl flex-shrink-0 flex items-center justify-center border border-white/20">
                    <img src="{{ asset('images/logo_tvri.png') }}" alt="Logo TVRI" class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <span class="text-xs font-bold uppercase tracking-wider text-cyan-300">TVRI MALUKU</span>
                        <span class="text-xs text-blue-200/60">•</span>
                        <span class="text-xs text-blue-200">Media Pemersatu Bangsa</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse mr-1"></span>
                            Online | Periode: {{ $calMonthLabel ?? 'November 2026' }}
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-black tracking-tight uppercase text-white drop-shadow-sm break-words">
                        DASHBOARD TATA USAHA TVRI MALUKU
                    </h1>
                    <p class="text-xs md:text-sm font-semibold text-blue-100/90 mt-1">
                        {{ $ringkasan->slogan_header ?? 'TERTIB ADMINISTRASI • CEPAT LAYANAN • AKURAT DATA • MENDUKUNG KINERJA' }}
                        <span class="text-amber-300 font-bold ml-1">{{ $ringkasan->slogan_sub ?? 'Maluku Bisa!' }}</span>
                    </p>
                    <div class="flex items-center space-x-2 text-[11px] text-blue-200/80 mt-2 font-mono">
                        <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="global-live-datetime-pipe">{{ $ringkasan->header_waktu ?? 'Selasa, 2 September 2025 | 10:24 WIT' }}</span>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi Cepat -->
            <div class="flex flex-wrap items-center gap-2.5">
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
                <a href="{{ route('tvri.tu.manage') }}"
                   class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-[#005B9F] hover:bg-[#004a82] text-white text-xs font-bold shadow-lg shadow-blue-900/30 border border-blue-400/30 transition-all hover:-translate-y-0.5">
                    <svg class="w-4 h-4 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Fitur Input Data Tata Usaha</span>
                </a>
                @endif
                <a href="{{ route('tvri.ecc.dashboard') }}"
                   class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold backdrop-blur-md border border-white/20 transition-all">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Monitoring ECC</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ===== 6 METRIK UTAMA ATAS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4">
        <!-- 1. Surat Masuk -->
        <a href="#surat-masuk" class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all group">
            <div class="flex items-center justify-between mb-2">
                <span class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8m-4-4l4 4-4 4"/></svg>
                </span>
                <span class="inline-flex items-center text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200" id="tu-diff-surat-masuk">
                    ▲ {{ $diffSuratMasuk ?? '+12% dari bulan lalu' }}
                </span>
            </div>
            <p class="text-2xl md:text-3xl font-black text-slate-800" id="tu-count-surat-masuk">{{ $suratMasukCount }}</p>
            <p class="text-xs font-bold text-slate-700 mt-0.5">Surat Masuk</p>
            <p class="text-[10px] text-slate-400 font-medium" id="tu-sub-surat-masuk">Bulan {{ $calMonthLabel ?? 'November 2026' }}</p>
        </a>

        <!-- 2. Surat Keluar -->
        <a href="#surat-keluar" class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all group">
            <div class="flex items-center justify-between mb-2">
                <span class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m8-4l4 4-4 4"/></svg>
                </span>
                <span class="inline-flex items-center text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200" id="tu-diff-surat-keluar">
                    ▲ {{ $diffSuratKeluar ?? '+8%' }}
                </span>
            </div>
            <p class="text-2xl md:text-3xl font-black text-slate-800" id="tu-count-surat-keluar">{{ $suratKeluarCount }}</p>
            <p class="text-xs font-bold text-slate-700 mt-0.5">Surat Keluar</p>
            <p class="text-[10px] text-slate-400 font-medium">Naskah dinas keluar</p>
        </a>

        <!-- 3. Disposisi -->
        <a href="#status-disposisi" class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all group">
            <div class="flex items-center justify-between mb-2">
                <span class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </span>
                <span class="inline-flex items-center text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200" id="tu-diff-disposisi">
                    ▲ {{ $diffDisposisi ?? '+15%' }}
                </span>
            </div>
            <p class="text-2xl md:text-3xl font-black text-slate-800" id="tu-count-disposisi">{{ $disposisiCount }}</p>
            <p class="text-xs font-bold text-slate-700 mt-0.5">Disposisi</p>
            <p class="text-[10px] text-slate-400 font-medium" id="tu-sub-disposisi">{{ $disposisiSelesai }} selesai diproses</p>
        </a>

        <!-- 4. Agenda Kegiatan -->
        <a href="#agenda" class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all group">
            <div class="flex items-center justify-between mb-2">
                <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </span>
                <span class="inline-flex items-center text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200" id="tu-diff-agenda">
                    ▲ {{ $diffAgenda ?? '+20%' }}
                </span>
            </div>
            <p class="text-2xl md:text-3xl font-black text-slate-800" id="tu-agenda-count">{{ $agendaCount }}</p>
            <p class="text-xs font-bold text-slate-700 mt-0.5">Agenda Kegiatan</p>
            <p class="text-[10px] text-slate-400 font-medium" id="tu-sub-agenda">Bulan {{ $calMonthName ?? 'November' }}</p>
        </a>

        <!-- 5. Layanan Administrasi -->
        <a href="#layanan-adm" class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all group">
            <div class="flex items-center justify-between mb-2">
                <span class="w-8 h-8 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M9 8h6m-9 12h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </span>
                <span class="inline-flex items-center text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200" id="tu-diff-layanan">
                    ▲ {{ $diffLayanan ?? '+10%' }}
                </span>
            </div>
            <p class="text-2xl md:text-3xl font-black text-slate-800" id="tu-count-layanan">{{ $layananCount }}</p>
            <p class="text-xs font-bold text-slate-700 mt-0.5">Layanan Adm</p>
            <p class="text-[10px] text-slate-400 font-medium">SK, data & rekomendasi</p>
        </a>

        <!-- 6. Service Completion Rate -->
        <a href="#monitoring-kinerja" class="bg-white rounded-2xl p-4 border border-blue-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all group bg-gradient-to-br from-blue-50/50 to-white">
            <div class="flex items-center justify-between mb-2">
                <span class="w-8 h-8 rounded-xl bg-[#005B9F] text-white flex items-center justify-center shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-blue-100 text-blue-800" id="tu-target-completion">TARGET {{ $targetCompletionRate ?? 90 }}%</span>
            </div>
            <p class="text-2xl md:text-3xl font-black text-blue-900" id="tu-rate-completion">{{ $serviceCompletionRate ?? 94 }}%</p>
            <p class="text-xs font-bold text-blue-950 mt-0.5">Service Completion</p>
            <p class="text-[10px] font-bold {{ $serviceCompletionStatusClass ?? 'text-emerald-600' }}" id="tu-status-completion">{{ $serviceCompletionStatus ?? '▲ Sangat Memuaskan' }}</p>
        </a>
    </div>

    <!-- ===== BAGIAN TENGAH ATAS: TREN & STATUS DISPOSISI & KATEGORI ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- 1. TREN SURAT MASUK & KELUAR (Jan - Sep) -->
        <div class="lg:col-span-7 bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm" id="laporan">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                        <span>Tren Surat Masuk & Keluar</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Perkembangan surat masuk, surat keluar & disposisi (Jan - Sep 2025)</p>
                </div>
                <a href="{{ route('tvri.tu.manage', ['tab' => 'surat-masuk']) }}" class="text-xs font-bold text-[#005B9F] hover:underline flex items-center space-x-1">
                    <span>Lihat Detail</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <!-- Mini Visual Chart (Bar Chart CSS) -->
            @php
                $trenBulanan = [
                    ['bulan' => 'Jan', 'masuk' => 84, 'keluar' => 62, 'disp' => 70],
                    ['bulan' => 'Feb', 'masuk' => 90, 'keluar' => 68, 'disp' => 78],
                    ['bulan' => 'Mar', 'masuk' => 95, 'keluar' => 72, 'disp' => 82],
                    ['bulan' => 'Apr', 'masuk' => 102, 'keluar' => 76, 'disp' => 88],
                    ['bulan' => 'Mei', 'masuk' => 108, 'keluar' => 80, 'disp' => 92],
                    ['bulan' => 'Jun', 'masuk' => 112, 'keluar' => 84, 'disp' => 96],
                    ['bulan' => 'Jul', 'masuk' => 118, 'keluar' => 88, 'disp' => 102],
                    ['bulan' => 'Agu', 'masuk' => 122, 'keluar' => 90, 'disp' => 105],
                    ['bulan' => 'Sep', 'masuk' => 128, 'keluar' => 96, 'disp' => 110],
                ];
                $maxTren = 135;
            @endphp
            <div class="space-y-4 pt-2">
                <div class="flex items-center space-x-4 text-xs font-semibold text-slate-600 pb-2 border-b border-slate-100">
                    <span class="flex items-center space-x-1.5"><span class="w-3 h-3 rounded bg-[#005B9F]"></span><span>Surat Masuk</span></span>
                    <span class="flex items-center space-x-1.5"><span class="w-3 h-3 rounded bg-indigo-500"></span><span>Surat Keluar</span></span>
                    <span class="flex items-center space-x-1.5"><span class="w-3 h-3 rounded bg-amber-500"></span><span>Disposisi</span></span>
                </div>

                <div class="grid grid-cols-9 gap-2 text-center pt-2">
                    @foreach($trenBulanan as $t)
                    <div class="flex flex-col items-center">
                        <div class="w-full h-36 flex items-end justify-center space-x-1 bg-slate-50/80 rounded-xl p-1 border border-slate-100">
                            <!-- Surat Masuk Bar -->
                            <div class="w-2.5 bg-[#005B9F] rounded-t transition-all duration-500 hover:opacity-80" style="height: {{ round(($t['masuk'] / $maxTren) * 100) }}%" title="Surat Masuk {{ $t['bulan'] }}: {{ $t['masuk'] }}"></div>
                            <!-- Surat Keluar Bar -->
                            <div class="w-2.5 bg-indigo-500 rounded-t transition-all duration-500 hover:opacity-80" style="height: {{ round(($t['keluar'] / $maxTren) * 100) }}%" title="Surat Keluar {{ $t['bulan'] }}: {{ $t['keluar'] }}"></div>
                            <!-- Disposisi Bar -->
                            <div class="w-2.5 bg-amber-500 rounded-t transition-all duration-500 hover:opacity-80" style="height: {{ round(($t['disp'] / $maxTren) * 100) }}%" title="Disposisi {{ $t['bulan'] }}: {{ $t['disp'] }}"></div>
                        </div>
                        <span class="text-[11px] font-bold text-slate-700 mt-2 {{ $t['bulan'] === 'Sep' ? 'text-blue-700 font-extrabold' : '' }}">{{ $t['bulan'] }}</span>
                        <span class="text-[9px] text-slate-400 font-mono">{{ $t['masuk'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 2. STATUS DISPOSISI & KATEGORI SURAT -->
        <div class="lg:col-span-5 space-y-6">
            <!-- Card Status Disposisi -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm" id="status-disposisi">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        <span>Status Disposisi</span>
                    </h3>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">Total: {{ $ringkasan->disposisi ?? 110 }} Disposisi</span>
                </div>

                @php
                    $dispTotal = max(1, $ringkasan->disposisi ?? 110);
                    $dispSelesai = $ringkasan->disposisi_selesai ?? 78;
                    $dispProses = $ringkasan->disposisi_proses ?? 18;
                    $dispMenunggu = $ringkasan->disposisi_menunggu ?? 9;
                    $dispTerlambat = $ringkasan->disposisi_terlambat ?? 5;
                @endphp

                <!-- Progress Bar Bertingkat -->
                <div class="w-full h-3 rounded-full overflow-hidden flex bg-slate-100 my-3">
                    <div class="bg-emerald-500 h-full" style="width: {{ round(($dispSelesai / $dispTotal) * 100) }}%" title="Selesai: {{ $dispSelesai }}"></div>
                    <div class="bg-blue-500 h-full" style="width: {{ round(($dispProses / $dispTotal) * 100) }}%" title="Proses: {{ $dispProses }}"></div>
                    <div class="bg-amber-400 h-full" style="width: {{ round(($dispMenunggu / $dispTotal) * 100) }}%" title="Menunggu: {{ $dispMenunggu }}"></div>
                    <div class="bg-rose-500 h-full" style="width: {{ round(($dispTerlambat / $dispTotal) * 100) }}%" title="Terlambat: {{ $dispTerlambat }}"></div>
                </div>

                <div class="grid grid-cols-2 gap-2.5 text-xs pt-1">
                    <div class="p-2.5 rounded-xl bg-emerald-50/70 border border-emerald-100 flex items-center justify-between">
                        <div class="flex items-center space-x-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="font-semibold text-slate-700">Selesai</span>
                        </div>
                        <span class="font-black text-emerald-700">{{ $dispSelesai }} (71%)</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-blue-50/70 border border-blue-100 flex items-center justify-between">
                        <div class="flex items-center space-x-1.5">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            <span class="font-semibold text-slate-700">Proses</span>
                        </div>
                        <span class="font-black text-blue-700">{{ $dispProses }} (16%)</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-amber-50/70 border border-amber-100 flex items-center justify-between">
                        <div class="flex items-center space-x-1.5">
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                            <span class="font-semibold text-slate-700">Menunggu</span>
                        </div>
                        <span class="font-black text-amber-700">{{ $dispMenunggu }} (8%)</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-rose-50/70 border border-rose-100 flex items-center justify-between">
                        <div class="flex items-center space-x-1.5">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            <span class="font-semibold text-slate-700">Terlambat</span>
                        </div>
                        <span class="font-black text-rose-700">{{ $dispTerlambat }} (5%)</span>
                    </div>
                </div>
            </div>

            <!-- Card Kategori Surat Masuk -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm" id="surat-masuk">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                        <span>Kategori Surat Masuk</span>
                    </h3>
                    <span class="text-xs font-bold text-slate-500">Total: {{ $ringkasan->surat_masuk ?? 128 }}</span>
                </div>
                @php
                    $kategoriList = [
                        ['nama' => 'Kementerian / Lembaga', 'jumlah' => $ringkasan->kat_kementerian ?? 32, 'pct' => '25%'],
                        ['nama' => 'LPP TVRI Pusat / Stasiun', 'jumlah' => $ringkasan->kat_lpp ?? 24, 'pct' => '19%'],
                        ['nama' => 'Pemerintah Daerah (Prov/Kab/Kota)', 'jumlah' => $ringkasan->kat_pemda ?? 20, 'pct' => '16%'],
                        ['nama' => 'Instansi Vertikal / TNI / Polri', 'jumlah' => $ringkasan->kat_vertikal ?? 16, 'pct' => '13%'],
                        ['nama' => 'Mitra Kerja / BUMN / Swasta', 'jumlah' => $ringkasan->kat_mitra ?? 14, 'pct' => '11%'],
                        ['nama' => 'Masyarakat / Komunitas', 'jumlah' => $ringkasan->kat_masyarakat ?? 12, 'pct' => '9%'],
                        ['nama' => 'Lainnya', 'jumlah' => $ringkasan->kat_lainnya ?? 10, 'pct' => '8%'],
                    ];
                @endphp
                <div class="space-y-2 text-xs">
                    @foreach($kategoriList as $k)
                    <div>
                        <div class="flex justify-between font-semibold text-slate-700 mb-0.5">
                            <span class="truncate max-w-[220px]">{{ $k['nama'] }}</span>
                            <span class="font-bold text-[#005B9F]">{{ $k['jumlah'] }} <span class="text-slate-400 font-normal">({{ $k['pct'] }})</span></span>
                        </div>
                        <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-[#005B9F] rounded-full" style="width: {{ $k['pct'] }}"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- ===== BAGIAN TENGAH BAWAH: SURAT MASUK TERBARU & AGENDA & KALENDER ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- 1. DAFTAR SURAT MASUK TERBARU -->
        <div class="lg:col-span-7 bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm" id="surat-keluar">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                        <span>Daftar Surat Masuk Terbaru</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Naskah dinas masuk prioritas dan status disposisi</p>
                </div>
                <a href="{{ route('tvri.tu.manage', ['tab' => 'surat-masuk']) }}" class="text-xs font-bold text-[#005B9F] hover:underline flex items-center space-x-1">
                    <span>Kelola Semua Surat</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="space-y-3">
                @forelse($suratMasukList->take(5) as $sm)
                <div class="p-3.5 rounded-xl border border-slate-200/80 bg-slate-50/50 hover:bg-blue-50/40 transition">
                    <div class="flex flex-wrap items-center justify-between gap-2 mb-1.5">
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800">
                                {{ \Carbon\Carbon::parse($sm->tanggal)->format('d M Y') }}
                            </span>
                            <span class="text-xs font-mono font-bold text-slate-700">{{ $sm->nomor }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($sm->kategori)
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ in_array($sm->kategori, ['Rahasia', 'Penting']) ? 'bg-rose-100 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-700' }}">
                                {{ $sm->kategori }}
                            </span>
                            @endif
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $sm->status === 'Selesai' ? 'bg-emerald-100 text-emerald-700' : ($sm->status === 'Didisposisikan' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700') }}">
                                {{ $sm->status }}
                            </span>
                        </div>
                    </div>
                    <p class="text-xs font-bold text-slate-800">{{ $sm->perihal }}</p>
                    <p class="text-[11px] text-slate-500 mt-0.5 flex items-center space-x-1">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>Pengirim: <span class="font-medium text-slate-700">{{ $sm->pengirim }}</span></span>
                    </p>
                    @php $smFile = $sm->file_url ?: $sm->file_path; @endphp
                    @if($smFile)
                    <div class="mt-2 pt-1.5 border-t border-slate-200/60 flex items-center justify-between">
                        <span class="text-[10px] text-slate-400">Dokumen Fisik Surat:</span>
                        <a href="{{ asset($smFile) }}" target="_blank" class="inline-flex items-center space-x-1 px-2 py-0.5 rounded text-[10px] font-bold bg-[#005B9F] text-white hover:bg-[#004a82] transition shadow-xs">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>Buka Fisik Surat</span>
                        </a>
                    </div>
                    @endif
                </div>
                @empty
                <p class="text-xs text-slate-400 text-center py-4">Belum ada surat masuk terbaru.</p>
                @endforelse
            </div>
        </div>

        <!-- 2. AGENDA KEGIATAN & KALENDER -->
        <div class="lg:col-span-5 space-y-6" id="agenda">

            <!-- Card Agenda Hari Ini & Minggu Ini -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 flex items-center space-x-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <span>Agenda Hari Ini & Minggu Ini</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5" id="tu-today-date-sub">{{ $todayDateFormatted ?? 'Selasa, 03 November 2026' }}</p>
                    </div>
                    <a href="{{ route('tvri.tu.manage', ['tab' => 'agenda']) }}" class="text-xs font-bold text-[#005B9F] hover:underline">Kelola Agenda &rarr;</a>
                </div>

                <div class="space-y-2.5 text-xs" id="tu-agenda-list-container">
                    @forelse($agendaList->take(5) as $ag)
                    @php
                        $agDateStr = $ag->tanggal ? \Carbon\Carbon::parse($ag->tanggal)->format('Y-m-d') : '';
                        $isAgToday = ($ag->jenis === 'Hari Ini' || (isset($now) && $agDateStr === $now->format('Y-m-d')));
                    @endphp
                    <div class="p-3 rounded-xl border border-slate-100 bg-slate-50/60 hover:bg-slate-100/70 transition">
                        <div class="flex items-center justify-between mb-1">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $isAgToday ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-blue-100 text-blue-800' }}">
                                {{ $ag->waktu }}
                            </span>
                            <span class="text-[10px] font-bold text-slate-500">{{ $ag->tanggal ? \Carbon\Carbon::parse($ag->tanggal)->format('d M') : '-' }}</span>
                        </div>
                        <p class="font-bold text-slate-800">{{ $ag->kegiatan }}</p>
                        <div class="flex items-center justify-between text-[11px] text-slate-500 mt-1">
                            <span>📍 {{ $ag->lokasi }}</span>
                            <span class="text-slate-600 font-medium">{{ $ag->pic }}</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 text-center py-4">Belum ada agenda terjadwal.</p>
                    @endforelse
                </div>
            </div>

            <!-- Card Visual Kalender Kegiatan -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm" id="kalender">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#005B9F]"></span>
                        <span>Kalender Kegiatan</span>
                    </h3>
                    <span class="text-xs font-bold text-blue-800 bg-blue-50 px-2.5 py-0.5 rounded-full" id="tu-cal-month-badge">{{ $calMonthLabel ?? 'November 2026' }}</span>
                </div>

                <!-- Mini Grid Kalender -->
                <div class="grid grid-cols-7 gap-1 text-center text-xs" id="tu-calendar-grid">
                    @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $hari)
                        <div class="py-1 text-[10px] font-bold text-slate-400 uppercase">{{ $hari }}</div>
                    @endforeach
                    @for($pad = 1; $pad < ($startDayOfWeek ?? 1); $pad++)
                        <div class="py-1.5"></div>
                    @endfor
                    @for($d = 1; $d <= ($totalDaysInMonth ?? 30); $d++)
                        @php
                            $isToday = ($d == ($todayDay ?? 0));
                            $hasAgenda = in_array($d, $scheduledDays ?? []);
                        @endphp
                        @if($isToday)
                            <div class="py-1.5 rounded-lg font-black bg-[#005B9F] text-white shadow-md ring-2 ring-blue-300 relative cursor-pointer" title="{{ $hasAgenda ? 'Hari Ini (Ada Agenda)' : 'Hari Ini' }}">
                                {{ $d }}
                                @if($hasAgenda)
                                    <span class="absolute bottom-0.5 left-1/2 -translate-x-1/2 w-1.5 h-1.5 bg-amber-300 rounded-full"></span>
                                @endif
                            </div>
                        @elseif($hasAgenda)
                            <div class="py-1.5 rounded-lg text-slate-700 bg-amber-50 text-amber-800 font-bold border border-amber-200/50 hover:bg-amber-100 transition cursor-pointer" title="Agenda Terjadwal: Tgl {{ $d }}">
                                {{ $d }}
                            </div>
                        @else
                            <div class="py-1.5 rounded-lg text-slate-700 hover:bg-slate-100 transition">
                                {{ $d }}
                            </div>
                        @endif
                    @endfor
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-500 mt-3 pt-2 border-t border-slate-100">
                    <span class="flex items-center space-x-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#005B9F]"></span><span id="tu-cal-legend-today">Hari Ini ({{ $todayLegend ?? '3 Nov' }})</span></span>
                    <span class="flex items-center space-x-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span><span>Agenda Terjadwal</span></span>
                </div>
            </div>

        </div>
    </div>

    <!-- ===== BAGIAN BAWAH: LAYANAN ADM, ARSIP, MONITORING SOP ===== -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- 1. LAYANAN ADMINISTRASI & PERSURATAN -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm" id="layanan-adm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span>
                    <span>Layanan Administrasi</span>
                </h3>
                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-teal-50 text-teal-800">Total: {{ $ringkasan->layanan_adm ?? 86 }}</span>
            </div>

            @php
                $layananItems = [
                    ['label' => 'SK & Keputusan', 'selesai' => '24 Selesai', 'pct' => '96%'],
                    ['label' => 'Permintaan Data & Informasi', 'selesai' => '18 Selesai', 'pct' => '90%'],
                    ['label' => 'Surat Keterangan Internal', 'selesai' => '22 Selesai', 'pct' => '100%'],
                    ['label' => 'Layanan Tamu & Eksternal', 'selesai' => '16 Selesai', 'pct' => '94%'],
                    ['label' => 'Legalisasi Dokumen', 'selesai' => '6 Selesai', 'pct' => '83%'],
                ];
            @endphp
            <div class="space-y-3 text-xs">
                @foreach($layananItems as $li)
                <div>
                    <div class="flex justify-between font-semibold text-slate-700 mb-1">
                        <span>{{ $li['label'] }}</span>
                        <span class="font-bold text-teal-700">{{ $li['selesai'] }} <span class="text-slate-400 font-normal">({{ $li['pct'] }})</span></span>
                    </div>
                    <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-teal-500 rounded-full" style="width: {{ $li['pct'] }}"></div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-5 p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-[11px] text-slate-600 flex justify-between items-center">
                <span>Rata-rata Penyelesaian: <strong class="text-teal-700 font-bold">94%</strong></span>
                <span>Waktu Proses: <strong class="text-slate-800 font-bold">1,4 Hari</strong></span>
            </div>
        </div>

        <!-- 2. ARSIP & DOKUMENTASI -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm" id="arsip-dokumen">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                    <span>Arsip & Dokumentasi</span>
                </h3>
                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-800">2.462 Berkas</span>
            </div>

            <div class="grid grid-cols-2 gap-3 text-xs mb-4">
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70">
                    <p class="text-slate-500 font-medium">Arsip Aktif</p>
                    <p class="text-lg font-black text-slate-800">{{ number_format($ringkasan->arsip_aktif ?? 1250) }}</p>
                    <p class="text-[10px] text-blue-600 font-semibold">51% terkelola</p>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70">
                    <p class="text-slate-500 font-medium">Arsip Inaktif</p>
                    <p class="text-lg font-black text-slate-800">{{ number_format($ringkasan->arsip_inaktif ?? 320) }}</p>
                    <p class="text-[10px] text-slate-500 font-semibold">13% tersimpan</p>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70">
                    <p class="text-slate-500 font-medium">Arsip Digital</p>
                    <p class="text-lg font-black text-indigo-700">{{ number_format($ringkasan->arsip_digital ?? 850) }}</p>
                    <p class="text-[10px] text-indigo-600 font-semibold">35% digitalisasi</p>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70">
                    <p class="text-slate-500 font-medium">Usul Retensi</p>
                    <p class="text-lg font-black text-rose-600">{{ number_format($ringkasan->arsip_retensi ?? 42) }}</p>
                    <p class="text-[10px] text-rose-500 font-semibold">2% terjadwal</p>
                </div>
            </div>

            <div class="space-y-2 text-xs pt-1">
                <div>
                    <div class="flex justify-between font-semibold text-slate-700 mb-1">
                        <span>Tingkat Digitalisasi Arsip</span>
                        <span class="font-bold text-indigo-700">68% <span class="text-slate-400 font-normal">(Target 75%)</span></span>
                    </div>
                    <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-500 rounded-full" style="width: 68%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between font-semibold text-slate-700 mb-1">
                        <span>Kapasitas Ruang Arsip Utama</span>
                        <span class="font-bold text-slate-800">85% Terisi</span>
                    </div>
                    <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-amber-500 rounded-full" style="width: 85%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. MONITORING SOP & KEPATUHAN -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm" id="sop-regulasi">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <span>Monitoring SOP & Regulasi</span>
                </h3>
                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">Kepatuhan: 96%</span>
            </div>

            <div class="space-y-2 text-xs mb-4">
                <div class="flex justify-between text-slate-700 font-medium">
                    <span>SOP Persuratan & Disposisi</span>
                    <span class="font-bold text-emerald-600">98%</span>
                </div>
                <div class="flex justify-between text-slate-700 font-medium">
                    <span>SOP Pengelolaan Arsip</span>
                    <span class="font-bold text-blue-600">92%</span>
                </div>
                <div class="flex justify-between text-slate-700 font-medium">
                    <span>SOP Layanan Tamu & Administrasi</span>
                    <span class="font-bold text-emerald-600">96%</span>
                </div>
                <div class="flex justify-between text-slate-700 font-medium">
                    <span>SOP Tata Naskah Dinas</span>
                    <span class="font-bold text-emerald-600">98%</span>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-3">
                <p class="text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-2">Checklist SOP Aktif:</p>
                <div class="space-y-1.5 text-xs">
                    @forelse($sops as $sop)
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 hover:bg-slate-100/80 transition">
                        <div class="flex items-center space-x-2 truncate">
                            <svg class="w-3.5 h-3.5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="font-medium text-slate-800 truncate">{{ $sop->judul }}</span>
                        </div>
                        <div class="flex items-center space-x-2 flex-shrink-0">
                            <span class="text-[10px] font-mono px-1.5 py-0.5 bg-white border border-slate-200 rounded text-slate-600">{{ $sop->versi }}</span>
                            @php $fileUrl = $sop->file_url ?: $sop->file_path; @endphp
                            @if($fileUrl)
                            <a href="{{ asset($fileUrl) }}" target="_blank" class="inline-flex items-center space-x-1 px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 transition" title="Buka Dokumen Fisik SOP">
                                <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Fisik SOP</span>
                            </a>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-slate-400 text-center py-2">Belum ada checklist SOP.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <!-- ===== 10. NOTIFIKASI & PENGINGAT ===== -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm" id="notifikasi">
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
            <div>
                <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A] flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    <span>10. Notifikasi & Pengingat</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Pengingat tindak lanjut disposisi, agenda pimpinan, retensi arsip, dan evaluasi kepatuhan</p>
            </div>
            <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-amber-50 text-amber-800 border border-amber-200">
                5 Notifikasi Aktif
            </span>
        </div>

        @php
            $notifTable = [
                ['no' => 1, 'pesan' => '5 disposisi belum ditindaklanjuti (>3 hari)', 'waktu' => '2 Sep 2025 08:30', 'prioritas' => 'Mendesak', 'badge' => 'bg-rose-50 text-rose-700 border-rose-200'],
                ['no' => 2, 'pesan' => 'Rapat koordinasi pimpinan hari ini', 'waktu' => '2 Sep 2025 08:00', 'prioritas' => 'Hari Ini', 'badge' => 'bg-amber-50 text-amber-700 border-amber-200'],
                ['no' => 3, 'pesan' => 'Arsip retensi jatuh tempo (12 dokumen)', 'waktu' => '1 Sep 2025 16:20', 'prioritas' => 'Penting', 'badge' => 'bg-orange-50 text-orange-700 border-orange-200'],
                ['no' => 4, 'pesan' => 'Permohonan data dari unit Program', 'waktu' => '1 Sep 2025 14:10', 'prioritas' => 'Layanan', 'badge' => 'bg-blue-50 text-blue-700 border-blue-200'],
                ['no' => 5, 'pesan' => 'Evaluasi kepatuhan administrasi bulan ini', 'waktu' => '1 Sep 2025 10:00', 'prioritas' => 'Monitoring', 'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
            ];
        @endphp

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-2.5 px-3 w-12 text-center">No.</th>
                        <th class="py-2.5 px-3">Notifikasi & Tindak Lanjut</th>
                        <th class="py-2.5 px-3 w-44">Tanggal / Waktu</th>
                        <th class="py-2.5 px-3 w-28 text-center">Kategori</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($notifTable as $nt)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3 px-3 text-center font-bold text-slate-400">{{ $nt['no'] }}</td>
                        <td class="py-3 px-3 font-semibold text-slate-800">
                            <div class="flex items-center space-x-2">
                                <span class="w-1.5 h-1.5 rounded-full {{ $nt['no'] === 1 ? 'bg-rose-500' : 'bg-blue-500' }}"></span>
                                <span>{{ $nt['pesan'] }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-3 font-mono text-[11px] text-slate-600">{{ $nt['waktu'] }} WIT</td>
                        <td class="py-3 px-3 text-center">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $nt['badge'] }}">
                                {{ $nt['prioritas'] }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ===== 11. KINERJA TATA USAHA (KPI) ===== -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/90 overflow-hidden" id="monitoring-kinerja">
        <div class="p-6 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A] flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                    <span>11. Kinerja Tata Usaha (KPI)</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Indikator Kinerja Utama (IKU), Realisasi, dan Capaian Kinerja Tata Usaha Periode September 2025</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                Status Rata-Rata: On Track (97%)
            </span>
        </div>

        @php
            $kpiRows = [
                ['indikator' => 'Ketepatan Waktu Disposisi', 'target' => '95%', 'realisasi' => '92%', 'capaian' => '97%', 'status' => 'On Track'],
                ['indikator' => 'Penyelesaian Layanan Adm.', 'target' => '90%', 'realisasi' => '86%', 'capaian' => '96%', 'status' => 'On Track'],
                ['indikator' => 'Pengelolaan Arsip', 'target' => '100%', 'realisasi' => '98%', 'capaian' => '98%', 'status' => 'On Track'],
                ['indikator' => 'Kepatuhan SOP', 'target' => '100%', 'realisasi' => '96%', 'capaian' => '96%', 'status' => 'On Track'],
                ['indikator' => 'Tingkat Kepuasan Layanan', 'target' => '90%', 'realisasi' => '88%', 'capaian' => '98%', 'status' => 'On Track'],
            ];
        @endphp

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="p-3.5 w-12 text-center">No</th>
                        <th class="p-3.5">Indikator</th>
                        <th class="p-3.5 text-center w-28">Target</th>
                        <th class="p-3.5 text-center w-28">Realisasi</th>
                        <th class="p-3.5 text-center w-28">Capaian</th>
                        <th class="p-3.5 text-center w-28">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($kpiRows as $i => $kr)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="p-3.5 font-bold text-slate-400 text-center">{{ $i + 1 }}</td>
                        <td class="p-3.5 font-bold text-slate-800">{{ $kr['indikator'] }}</td>
                        <td class="p-3.5 text-center font-mono font-semibold text-slate-600">{{ $kr['target'] }}</td>
                        <td class="p-3.5 text-center font-mono font-bold text-blue-700">{{ $kr['realisasi'] }}</td>
                        <td class="p-3.5 text-center font-mono font-black text-emerald-600">{{ $kr['capaian'] }}</td>
                        <td class="p-3.5 text-center">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-700 border border-emerald-200">
                                {{ $kr['status'] }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ===== 12. DOKUMEN & LINK PENTING ===== -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm" id="kontak-link">
        <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A] mb-4 flex items-center space-x-2">
            <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
            <span>12. Dokumen & Link Penting</span>
        </h3>
        @php
            $docLinks = [
                ['nama' => '1. Peraturan Internal TVRI', 'sub' => 'Regulasi & SK Dewan Pengawas', 'ikon' => 'M9 12h6m-6 4h6M9 8h6m-9 12h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'blue'],
                ['nama' => '2. SOP Tata Usaha', 'sub' => 'Standar Operasional Baku', 'ikon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'color' => 'emerald'],
                ['nama' => '3. Format Surat', 'sub' => 'Template Naskah Dinas Resmi', 'ikon' => 'M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'amber'],
                ['nama' => '4. Daftar Kontak Penting', 'sub' => 'Direktorat, Pemda & Mitra', 'ikon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z', 'color' => 'indigo'],
                ['nama' => '5. Laporan Bulanan', 'sub' => 'Arsip Kinerja & Statistik', 'ikon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'color' => 'teal'],
                ['nama' => '6. Panduan Kearsipan', 'sub' => 'Pedoman Retensi & Dokumen', 'ikon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'color' => 'purple'],
            ];
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 text-xs">
            @foreach($docLinks as $dl)
            <a href="{{ route('dokumen.documents.index') }}" class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 hover:bg-blue-50/60 hover:border-blue-300 transition text-center group">
                <div class="w-9 h-9 mx-auto rounded-lg bg-{{ $dl['color'] }}-100 text-{{ $dl['color'] }}-700 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $dl['ikon'] }}"/></svg>
                </div>
                <span class="font-bold text-slate-800 block text-[11px] leading-tight">{{ $dl['nama'] }}</span>
                <span class="text-[9px] text-slate-400 mt-0.5 block">{{ $dl['sub'] }}</span>
            </a>
            @endforeach
        </div>
    </div>

    <!-- ===== 13. NILAI DAN BUDAYA KERJA & SLOGAN ===== -->
    <div class="p-6 md:p-8 rounded-2xl sm:rounded-3xl bg-gradient-to-r from-[#001D38] via-[#002B54] to-[#004080] text-white shadow-xl border border-blue-400/20 text-center space-y-4">
        <div>
            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-cyan-400/20 text-cyan-300 border border-cyan-400/30">
                13. Nilai dan Budaya Kerja
            </span>
            <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3 mt-3">
                @foreach(['Profesional', 'Integritas', 'Pelayanan Prima', 'Kolaboratif', 'Inovatif'] as $nilai)
                <span class="px-3.5 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-black text-xs border border-white/20 shadow-sm transition-all">
                    • {{ $nilai }}
                </span>
                @endforeach
            </div>
        </div>

        <div class="pt-2 border-t border-blue-400/20">
            <p class="text-xs sm:text-sm font-semibold tracking-wider text-blue-200 uppercase">
                Slogan:
            </p>
            <p class="text-base sm:text-lg font-black tracking-wide text-cyan-200 mt-1">
                "Dari Maluku untuk Indonesia."
            </p>
            <p class="text-xs sm:text-sm font-extrabold text-amber-300 tracking-wider uppercase mt-0.5">
                Maluku Bisa! Indonesia Maju!
            </p>
            <p class="text-[10px] text-blue-200/70 mt-2 font-medium">
                Bagian Tata Usaha • TVRI Maluku | Jl. Wolter Monginsidi, Ambon, Maluku
            </p>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const pollUrl = "{{ route('tvri.tu.dashboard') }}?realtime=1";
    let lastAgendaJson = '';

    const daysIndo = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const monthsIndo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const monthsShortIndo = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    let scheduledDaysCache = {!! json_encode($scheduledDays ?? []) !!};
    let lastRenderedDayKey = '';

    function getWitDate() {
        const now = new Date();
        const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
        return new Date(utc + (3600000 * 9)); // WIT (UTC+9)
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function renderAgendaList(agendas) {
        const container = document.getElementById('tu-agenda-list-container');
        if (!container) return;

        if (!agendas || agendas.length === 0) {
            container.innerHTML = '<p class="text-xs text-slate-400 text-center py-4">Belum ada agenda terjadwal.</p>';
            return;
        }

        let html = '';
        agendas.forEach(ag => {
            const badgeClass = ag.is_today
                ? 'bg-amber-100 text-amber-800 border border-amber-200'
                : 'bg-blue-100 text-blue-800';

            html += `
                <div class="p-3 rounded-xl border border-slate-100 bg-slate-50/60 hover:bg-slate-100/70 transition">
                    <div class="flex items-center justify-between mb-1">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold ${badgeClass}">
                            ${escapeHtml(ag.waktu)}
                        </span>
                        <span class="text-[10px] font-bold text-slate-500">${escapeHtml(ag.tanggal_formatted)}</span>
                    </div>
                    <p class="font-bold text-slate-800">${escapeHtml(ag.kegiatan)}</p>
                    <div class="flex items-center justify-between text-[11px] text-slate-500 mt-1">
                        <span>📍 ${escapeHtml(ag.lokasi)}</span>
                        <span class="text-slate-600 font-medium">${escapeHtml(ag.pic || '')}</span>
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;
    }

    function renderCalendarGrid(totalDays, startDayOfWeek, todayDay, scheduledDays) {
        const grid = document.getElementById('tu-calendar-grid');
        if (!grid) return;

        const days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        let html = '';
        days.forEach(h => {
            html += `<div class="py-1 text-[10px] font-bold text-slate-400 uppercase">${h}</div>`;
        });

        for (let pad = 1; pad < startDayOfWeek; pad++) {
            html += '<div class="py-1.5"></div>';
        }

        const scheduledSet = new Set(scheduledDays || []);
        for (let d = 1; d <= totalDays; d++) {
            const isToday = (d === todayDay);
            const hasAgenda = scheduledSet.has(d);

            if (isToday) {
                html += `
                    <div class="py-1.5 rounded-lg font-black bg-[#005B9F] text-white shadow-md ring-2 ring-blue-300 relative cursor-pointer" title="${hasAgenda ? 'Hari Ini (Ada Agenda)' : 'Hari Ini'}">
                        ${d}
                        ${hasAgenda ? '<span class="absolute bottom-0.5 left-1/2 -translate-x-1/2 w-1.5 h-1.5 bg-amber-300 rounded-full"></span>' : ''}
                    </div>
                `;
            } else if (hasAgenda) {
                html += `
                    <div class="py-1.5 rounded-lg text-slate-700 bg-amber-50 text-amber-800 font-bold border border-amber-200/50 hover:bg-amber-100 transition cursor-pointer" title="Agenda Terjadwal: Tgl ${d}">
                        ${d}
                    </div>
                `;
            } else {
                html += `
                    <div class="py-1.5 rounded-lg text-slate-700 hover:bg-slate-100 transition">
                        ${d}
                    </div>
                `;
            }
        }

        grid.innerHTML = html;
    }

    function updateLiveDateTime() {
        const wit = getWitDate();
        const dayName = daysIndo[wit.getDay()];
        const dateNum = wit.getDate();
        const datePad = String(dateNum).padStart(2, '0');
        const monthName = monthsIndo[wit.getMonth()];
        const monthShort = monthsShortIndo[wit.getMonth()];
        const yearNum = wit.getFullYear();

        const dateFormatted = `${dayName}, ${datePad} ${monthName} ${yearNum}`;
        const monthLabel = `${monthName} ${yearNum}`;
        const legendLabel = `Hari Ini (${dateNum} ${monthShort})`;

        const subDate = document.getElementById('tu-today-date-sub');
        if (subDate && subDate.textContent !== dateFormatted) {
            subDate.textContent = dateFormatted;
        }

        const calBadge = document.getElementById('tu-cal-month-badge');
        if (calBadge && calBadge.textContent !== monthLabel) {
            calBadge.textContent = monthLabel;
        }

        const legToday = document.getElementById('tu-cal-legend-today');
        if (legToday && legToday.textContent !== legendLabel) {
            legToday.textContent = legendLabel;
        }

        const dayKey = `${yearNum}-${wit.getMonth()}-${dateNum}`;
        if (dayKey !== lastRenderedDayKey) {
            lastRenderedDayKey = dayKey;
            const totalDays = new Date(yearNum, wit.getMonth() + 1, 0).getDate();
            const firstDayJs = new Date(yearNum, wit.getMonth(), 1).getDay();
            const startDayOfWeek = (firstDayJs === 0) ? 7 : firstDayJs;
            renderCalendarGrid(totalDays, startDayOfWeek, dateNum, scheduledDaysCache);
        }
    }

    function fetchRealtimeAgenda() {
        fetch(pollUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (!data || !data.success) return;

            // Update 6 Stat Cards in Real Time
            if (data.stats) {
                const s = data.stats;
                const smEl = document.getElementById('tu-count-surat-masuk');
                if (smEl && s.suratMasukCount !== undefined) smEl.textContent = s.suratMasukCount;
                const smDiff = document.getElementById('tu-diff-surat-masuk');
                if (smDiff && s.diffSuratMasuk) smDiff.textContent = `▲ ${s.diffSuratMasuk}`;

                const skEl = document.getElementById('tu-count-surat-keluar');
                if (skEl && s.suratKeluarCount !== undefined) skEl.textContent = s.suratKeluarCount;
                const skDiff = document.getElementById('tu-diff-surat-keluar');
                if (skDiff && s.diffSuratKeluar) skDiff.textContent = `▲ ${s.diffSuratKeluar}`;

                const dispEl = document.getElementById('tu-count-disposisi');
                if (dispEl && s.disposisiCount !== undefined) dispEl.textContent = s.disposisiCount;
                const dispSub = document.getElementById('tu-sub-disposisi');
                if (dispSub && s.disposisiSelesai !== undefined) dispSub.textContent = `${s.disposisiSelesai} selesai diproses`;
                const dispDiff = document.getElementById('tu-diff-disposisi');
                if (dispDiff && s.diffDisposisi) dispDiff.textContent = `▲ ${s.diffDisposisi}`;

                const agEl = document.getElementById('tu-agenda-count');
                if (agEl && s.agendaCount !== undefined) agEl.textContent = s.agendaCount;
                const agDiff = document.getElementById('tu-diff-agenda');
                if (agDiff && s.diffAgenda) agDiff.textContent = `▲ ${s.diffAgenda}`;

                const lyEl = document.getElementById('tu-count-layanan');
                if (lyEl && s.layananCount !== undefined) lyEl.textContent = s.layananCount;
                const lyDiff = document.getElementById('tu-diff-layanan');
                if (lyDiff && s.diffLayanan) lyDiff.textContent = `▲ ${s.diffLayanan}`;

                const tgtEl = document.getElementById('tu-target-completion');
                if (tgtEl && s.targetCompletionRate !== undefined) tgtEl.textContent = `TARGET ${s.targetCompletionRate}%`;
                const rateEl = document.getElementById('tu-rate-completion');
                if (rateEl && s.serviceCompletionRate !== undefined) rateEl.textContent = `${s.serviceCompletionRate}%`;
                const stEl = document.getElementById('tu-status-completion');
                if (stEl && s.serviceCompletionStatus) {
                    stEl.textContent = s.serviceCompletionStatus;
                    if (s.serviceCompletionStatusClass) {
                        stEl.className = `text-[10px] font-bold ${s.serviceCompletionStatusClass}`;
                    }
                }
            }

            if (data.scheduledDays) {
                scheduledDaysCache = data.scheduledDays;
            }

            const currentJson = JSON.stringify({
                agendas: data.agendas,
                scheduledDays: data.scheduledDays,
                count: data.agendaCount,
                stats: data.stats
            });

            if (currentJson !== lastAgendaJson) {
                lastAgendaJson = currentJson;

                // Update Agenda List
                renderAgendaList(data.agendas);

                // Update Calendar Grid
                renderCalendarGrid(data.totalDaysInMonth, data.startDayOfWeek, data.todayDay, data.scheduledDays);

                // Update Subtitle & Badge
                const subDate = document.getElementById('tu-today-date-sub');
                if (subDate && data.todayDateFormatted) subDate.textContent = data.todayDateFormatted;

                const calBadge = document.getElementById('tu-cal-month-badge');
                if (calBadge && data.calMonthLabel) calBadge.textContent = data.calMonthLabel;

                const legToday = document.getElementById('tu-cal-legend-today');
                if (legToday && data.todayLegend) legToday.textContent = `Hari Ini (${data.todayLegend})`;
            }
        })
        .catch(err => {
            console.debug('Realtime agenda sync:', err);
        });
    }

    // Run clock immediately and every second
    updateLiveDateTime();
    setInterval(updateLiveDateTime, 1000);

    // Polling interval every 6 seconds for backend database changes
    setInterval(fetchRealtimeAgenda, 6000);
});
</script>
@endpush
