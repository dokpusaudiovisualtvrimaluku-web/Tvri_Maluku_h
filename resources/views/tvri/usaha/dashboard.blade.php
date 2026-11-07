@extends('layouts.app')

@section('title', 'Dashboard Pengembangan Usaha & PNBP - TVRI Maluku')

@section('content')
<div class="space-y-6">

    <!-- ===== HEADER UTAMA PENGEMBANGAN USAHA ===== -->
    <div id="beranda" class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#001D38] via-[#00264A] to-[#003B70] text-white shadow-2xl p-6 md:p-8 border border-amber-400/20">
        <div class="absolute -right-12 -top-16 w-80 h-80 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-12 -bottom-16 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex items-start md:items-center space-x-4">
                <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-white p-2.5 shadow-xl flex-shrink-0 flex items-center justify-center border border-white/20">
                    <img src="{{ asset('images/logo_tvri.png') }}" alt="Logo TVRI" class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <span class="text-xs font-extrabold uppercase tracking-wider text-amber-300">TVRI STASIUN MALUKU</span>
                        <span class="text-xs text-blue-200/60">•</span>
                        <span class="text-xs font-semibold text-blue-200">{{ $ringkasan->slogan ?? 'Maluku Lebih Baik Bersama' }}</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse mr-1.5"></span>
                            PNBP & Kemitraan
                        </span>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-black tracking-tight uppercase text-white drop-shadow-sm">
                        {{ $ringkasan->judul ?? 'DASHBOARD PENGEMBANGAN USAHA TVRI MALUKU' }}
                    </h1>
                    <p class="text-xs md:text-sm font-semibold text-blue-100/90 mt-1">
                        {{ $ringkasan->sub_judul ?? 'Sinergi Konten, Kemitraan, dan Layanan untuk Keberlanjutan' }}
                    </p>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-[11px] text-blue-200/80 mt-2">
                        <div class="flex items-center space-x-1 font-mono">
                            <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="global-live-datetime">{{ $ringkasan->waktu ?? 'Senin, 18 November 2024, 10:24 WIT' }}</span>
                        </div>
                        <div class="hidden sm:inline-block text-blue-300/40">•</div>
                        <div>
                            <span class="text-slate-300">Pilar:</span>
                            <span class="text-amber-300 font-bold ml-1">{{ $ringkasan->pilar ?? 'Kolaborasi, Inovasi, Nilai Tambah, Berdampak Nyata' }}</span>
                        </div>
                    </div>
                    <p class="text-xs italic text-blue-200/90 mt-1.5 font-serif">
                        &ldquo;{{ $ringkasan->kutipan ?? 'Menggerakkan Potensi Membangun Masa Depan' }}&rdquo;
                    </p>
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
                <a href="{{ route('tvri.usaha.manage') }}"
                   class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-900 text-xs font-black shadow-lg shadow-amber-900/30 border border-amber-300 transition-all hover:-translate-y-0.5">
                    <svg class="w-4 h-4 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Input & Kelola Data Usaha</span>
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

    <!-- ===== 5 INDIKATOR KINERJA UTAMA ===== -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 md:gap-4">
        <!-- 1. Total Pendapatan -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-2">
                <span class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <span class="inline-flex items-center text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">
                    {{ $ringkasan->diff_total_pendapatan ?? '▲ 18,6% vs periode sebelumnya' }}
                </span>
            </div>
            <p class="text-xl md:text-2xl font-black text-slate-800">Rp {{ number_format(($ringkasan->total_pendapatan ?? $pnbpRealisasi) / 1e6, 0, ',', '.') }} Jt</p>
            <p class="text-xs font-bold text-slate-700 mt-0.5 uppercase tracking-wide">Total Pendapatan</p>
            <p class="text-[10px] text-slate-400 font-medium">Rp {{ number_format($ringkasan->total_pendapatan ?? $pnbpRealisasi, 0, ',', '.') }}</p>
        </div>

        <!-- 2. Capaian Target -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-2">
                <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </span>
                <span class="inline-flex items-center text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">
                    ON TRACK
                </span>
            </div>
            <p class="text-xl md:text-2xl font-black text-slate-800">{{ $ringkasan->capaian_target ?? $pctPnbp }}%</p>
            <p class="text-xs font-bold text-slate-700 mt-0.5 uppercase tracking-wide">Capaian Target</p>
            <p class="text-[10px] text-slate-400 font-medium">Target: Rp {{ number_format(($ringkasan->target_pendapatan ?? $pnbpTarget) / 1e6, 0, ',', '.') }} Jt</p>
        </div>

        <!-- 3. Kontrak Aktif -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-2">
                <span class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
                <span class="inline-flex items-center text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">
                    {{ $ringkasan->diff_kontrak_aktif ?? '▲ 12,0%' }}
                </span>
            </div>
            <p class="text-xl md:text-2xl font-black text-slate-800">{{ $ringkasan->jumlah_kontrak_aktif ?? 28 }}</p>
            <p class="text-xs font-bold text-slate-700 mt-0.5 uppercase tracking-wide">Kontrak Aktif</p>
            <p class="text-[10px] text-slate-400 font-medium">Perjanjian Kerja Sama (PKS)</p>
        </div>

        <!-- 4. Mitra / Klien -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-2">
                <span class="w-8 h-8 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </span>
                <span class="inline-flex items-center text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">
                    {{ $ringkasan->diff_mitra ?? '▲ 26,3%' }}
                </span>
            </div>
            <p class="text-xl md:text-2xl font-black text-slate-800">{{ $ringkasan->jumlah_mitra ?? $mitraTotal }}</p>
            <p class="text-xs font-bold text-slate-700 mt-0.5 uppercase tracking-wide">Mitra / Klien</p>
            <p class="text-[10px] text-slate-400 font-medium">BUMN, Pemda & Swasta</p>
        </div>

        <!-- 5. Kegiatan Usaha -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-sm hover:shadow-md transition-all col-span-2 md:col-span-1">
            <div class="flex items-center justify-between mb-2">
                <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </span>
                <span class="inline-flex items-center text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">
                    {{ $ringkasan->diff_kegiatan ?? '▲ 50,0%' }}
                </span>
            </div>
            <p class="text-xl md:text-2xl font-black text-slate-800">{{ $ringkasan->kegiatan_usaha ?? $kegiatanTotal }}</p>
            <p class="text-xs font-bold text-slate-700 mt-0.5 uppercase tracking-wide">Event / Kegiatan</p>
            <p class="text-[10px] text-slate-400 font-medium">Agenda & Aktivasi Usaha</p>
        </div>
    </div>

    <!-- ===== SECTION 1: ANALISIS PENDAPATAN & TARGET (KOMPOSISI & TOP SUMBER) ===== -->
    <div id="pendapatan" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Komposisi Pendapatan -->
        <div class="lg:col-span-5 bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">Komposisi Pendapatan</h3>
                    <p class="text-xs text-slate-400">Total Realisasi: Rp 2,85 Miliar</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-blue-50 text-[#005B9F] border border-blue-200">
                    TA 2024
                </span>
            </div>

            <div class="space-y-3.5">
                <!-- Iklan TV & Radio -->
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-slate-700">Iklan TV & Radio</span>
                        <span class="text-[#005B9F] font-mono">{{ $ringkasan->komposisi_iklan_pct ?? 35.1 }}% (Rp 1.000 Jt)</span>
                    </div>
                    <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-600 to-cyan-500 rounded-full" style="width: {{ $ringkasan->komposisi_iklan_pct ?? 35.1 }}%"></div>
                    </div>
                </div>

                <!-- Sponsorship Program -->
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-slate-700">Sponsorship Program</span>
                        <span class="text-[#005B9F] font-mono">{{ $ringkasan->komposisi_sponsorship_pct ?? 24.6 }}% (Rp 700 Jt)</span>
                    </div>
                    <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-indigo-600 to-purple-500 rounded-full" style="width: {{ $ringkasan->komposisi_sponsorship_pct ?? 24.6 }}%"></div>
                    </div>
                </div>

                <!-- Event & Aktivasi -->
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-slate-700">Event & Aktivasi</span>
                        <span class="text-[#005B9F] font-mono">{{ $ringkasan->komposisi_event_pct ?? 18.9 }}% (Rp 540 Jt)</span>
                    </div>
                    <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-amber-500 to-orange-400 rounded-full" style="width: {{ $ringkasan->komposisi_event_pct ?? 18.9 }}%"></div>
                    </div>
                </div>

                <!-- Sewa Aset & Fasilitas -->
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-slate-700">Sewa Aset & Fasilitas</span>
                        <span class="text-[#005B9F] font-mono">{{ $ringkasan->komposisi_sewa_pct ?? 12.3 }}% (Rp 350 Jt)</span>
                    </div>
                    <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-teal-500 to-emerald-400 rounded-full" style="width: {{ $ringkasan->komposisi_sewa_pct ?? 12.3 }}%"></div>
                    </div>
                </div>

                <!-- Kerja Sama Konten -->
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-slate-700">Kerja Sama Konten</span>
                        <span class="text-[#005B9F] font-mono">{{ $ringkasan->komposisi_konten_pct ?? 6.7 }}% (Rp 190 Jt)</span>
                    </div>
                    <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-rose-500 to-pink-400 rounded-full" style="width: {{ $ringkasan->komposisi_konten_pct ?? 6.7 }}%"></div>
                    </div>
                </div>

                <!-- Lainnya -->
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-slate-700">Lainnya</span>
                        <span class="text-[#005B9F] font-mono">{{ $ringkasan->komposisi_lainnya_pct ?? 2.4 }}% (Rp 70 Jt)</span>
                    </div>
                    <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-slate-400 rounded-full" style="width: {{ $ringkasan->komposisi_lainnya_pct ?? 2.4 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top 5 Sumber Pendapatan -->
        <div class="lg:col-span-7 bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">Top 5 Sumber Pendapatan Usaha</h3>
                    <p class="text-xs text-slate-400">Penerimaan Negara Bukan Pajak (PNBP) Per Lini Bisnis</p>
                </div>
                <a href="{{ route('tvri.usaha.manage', ['tab' => 'pnbp']) }}" class="text-xs font-bold text-[#005B9F] hover:underline">Kelola &rarr;</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                            <th class="pb-2.5">Sumber Pendapatan</th>
                            <th class="pb-2.5 text-right">Nilai (Juta Rupiah)</th>
                            <th class="pb-2.5 text-right">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
                        @php
                            $topSumber = [
                                ['nama' => 'Iklan TV & Radio', 'nilai' => 1000, 'pct' => '35,1%'],
                                ['nama' => 'Sponsorship Program', 'nilai' => 700, 'pct' => '24,6%'],
                                ['nama' => 'Event & Aktivasi', 'nilai' => 540, 'pct' => '18,9%'],
                                ['nama' => 'Sewa Aset & Fasilitas', 'nilai' => 350, 'pct' => '12,3%'],
                                ['nama' => 'Kerja Sama Konten', 'nilai' => 190, 'pct' => '6,7%'],
                            ];
                        @endphp
                        @foreach($topSumber as $idx => $ts)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 flex items-center space-x-2">
                                <span class="w-5 h-5 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center text-[10px] font-bold">{{ $idx + 1 }}</span>
                                <span class="font-bold text-[#00264A]">{{ $ts['nama'] }}</span>
                            </td>
                            <td class="py-3 text-right font-mono font-bold text-[#005B9F]">Rp {{ number_format($ts['nilai']) }} Jt</td>
                            <td class="py-3 text-right">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $ts['pct'] }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ===== SECTION 2: TOP 5 MITRA & DAFTAR KEGIATAN USAHA TERDEKAT ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top 5 Mitra / Klien -->
        <div id="kemitraan" class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">Top 5 Mitra / Klien</h3>
                    <p class="text-xs text-slate-400">Kerja Sama Komersial & Kampanye Publik</p>
                </div>
                <a href="{{ route('tvri.usaha.manage', ['tab' => 'mitra']) }}" class="text-xs font-bold text-[#005B9F] hover:underline">Kelola &rarr;</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                            <th class="pb-2.5 w-8">No</th>
                            <th class="pb-2.5">Nama Mitra / Klien</th>
                            <th class="pb-2.5">Jenis Kerja Sama</th>
                            <th class="pb-2.5 text-right">Nilai (Rp)</th>
                            <th class="pb-2.5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
                        @forelse($mitraList->take(5) as $idx => $m)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 text-slate-400">{{ $idx + 1 }}</td>
                            <td class="py-3 font-bold text-[#00264A]">{{ $m->nama }}</td>
                            <td class="py-3 text-slate-600">{{ $m->jenis_kerja_sama }}</td>
                            <td class="py-3 text-right font-mono font-bold text-[#005B9F]">Rp {{ number_format($m->nilai_kontrak, 0, ',', '.') }}</td>
                            <td class="py-3 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $m->status === 'Aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $m->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-slate-400">Belum ada data mitra terdata.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Daftar Kegiatan Usaha Terdekat -->
        <div id="program-usaha" class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">Daftar Kegiatan Usaha Terdekat</h3>
                    <p class="text-xs text-slate-400">Event, Sponsorship & Kampanye Publik</p>
                </div>
                <a href="{{ route('tvri.usaha.manage', ['tab' => 'kegiatan']) }}" class="text-xs font-bold text-[#005B9F] hover:underline">Kelola &rarr;</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                            <th class="pb-2.5">Tanggal</th>
                            <th class="pb-2.5">Kegiatan</th>
                            <th class="pb-2.5">Jenis</th>
                            <th class="pb-2.5 text-right">Est. Nilai (Rp)</th>
                            <th class="pb-2.5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
                        @forelse($kegiatanList->take(5) as $k)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 font-mono text-slate-600">{{ \Carbon\Carbon::parse($k->tanggal)->format('d M Y') }}</td>
                            <td class="py-3 font-bold text-[#00264A]">{{ $k->kegiatan }}</td>
                            <td class="py-3 text-slate-600">{{ $k->jenis }}</td>
                            <td class="py-3 text-right font-mono font-bold text-[#005B9F]">Rp {{ number_format($k->estimasi_nilai, 0, ',', '.') }}</td>
                            <td class="py-3 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $k->status === 'Kontrak' ? 'bg-emerald-100 text-emerald-700' : ($k->status === 'Persiapan' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') }}">
                                    {{ $k->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-slate-400">Belum ada kegiatan terdata.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ===== SECTION 3: OPERASIONAL & KINERJA (REALISASI ANGGARAN & PIPELINE & PETA) ===== -->
    <div id="keuangan" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Realisasi Anggaran Operasional Usaha -->
        <div class="lg:col-span-5 bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">Realisasi Anggaran Operasional Usaha</h3>
                    <p class="text-xs text-slate-400">Alokasi & Serapan Beban Usaha</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                    Operasional
                </span>
            </div>

            <div class="space-y-4">
                <!-- Promosi & Pemasaran -->
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-slate-700">Promosi & Pemasaran</span>
                        <span class="text-amber-600 font-mono">{{ $ringkasan->operasional_promosi_pct ?? 75 }}% (Rp {{ number_format(($ringkasan->operasional_promosi_realisasi ?? 300000000) / 1e6) }} Jt / {{ number_format(($ringkasan->operasional_promosi_pagu ?? 400000000) / 1e6) }} Jt)</span>
                    </div>
                    <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-amber-500 rounded-full" style="width: {{ $ringkasan->operasional_promosi_pct ?? 75 }}%"></div>
                    </div>
                </div>

                <!-- Event & Aktivasi -->
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-slate-700">Event & Aktivasi</span>
                        <span class="text-blue-600 font-mono">{{ $ringkasan->operasional_event_pct ?? 68 }}% (Rp {{ number_format(($ringkasan->operasional_event_realisasi ?? 340000000) / 1e6) }} Jt / {{ number_format(($ringkasan->operasional_event_pagu ?? 500000000) / 1e6) }} Jt)</span>
                    </div>
                    <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-600 rounded-full" style="width: {{ $ringkasan->operasional_event_pct ?? 68 }}%"></div>
                    </div>
                </div>

                <!-- Pemeliharaan Aset -->
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-slate-700">Pemeliharaan Aset</span>
                        <span class="text-teal-600 font-mono">{{ $ringkasan->operasional_aset_pct ?? 55 }}% (Rp {{ number_format(($ringkasan->operasional_aset_realisasi ?? 220000000) / 1e6) }} Jt / {{ number_format(($ringkasan->operasional_aset_pagu ?? 400000000) / 1e6) }} Jt)</span>
                    </div>
                    <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-teal-600 rounded-full" style="width: {{ $ringkasan->operasional_aset_pct ?? 55 }}%"></div>
                    </div>
                </div>

                <!-- Pengembangan Konten -->
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-slate-700">Pengembangan Konten</span>
                        <span class="text-purple-600 font-mono">{{ $ringkasan->operasional_konten_pct ?? 60 }}% (Rp {{ number_format(($ringkasan->operasional_konten_realisasi ?? 180000000) / 1e6) }} Jt / {{ number_format(($ringkasan->operasional_konten_pagu ?? 300000000) / 1e6) }} Jt)</span>
                    </div>
                    <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-purple-600 rounded-full" style="width: {{ $ringkasan->operasional_konten_pct ?? 60 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pipeline Peluang Usaha & Peta Sebaran Mitra -->
        <div class="lg:col-span-7 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Pipeline Peluang Usaha -->
            <div id="manajemen-risiko" class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">Pipeline Peluang Usaha</h3>
                        <span class="text-[10px] font-bold text-slate-400">Funnel Bisnis</span>
                    </div>
                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-xs font-bold text-slate-700">Prospek</span>
                            <span class="font-mono font-black text-sm text-[#005B9F]">{{ $ringkasan->pipeline_prospek ?? 42 }}</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-xs font-bold text-slate-700">Pendekatan</span>
                            <span class="font-mono font-black text-sm text-blue-600">{{ $ringkasan->pipeline_pendekatan ?? 28 }}</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-xs font-bold text-slate-700">Proposal</span>
                            <span class="font-mono font-black text-sm text-amber-600">{{ $ringkasan->pipeline_proposal ?? 17 }}</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-xs font-bold text-slate-700">Negosiasi</span>
                            <span class="font-mono font-black text-sm text-purple-600">{{ $ringkasan->pipeline_negosiasi ?? 9 }}</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-xl bg-emerald-50 border border-emerald-200">
                            <span class="text-xs font-bold text-emerald-800">Kontrak</span>
                            <span class="font-mono font-black text-sm text-emerald-700">{{ $ringkasan->pipeline_kontrak ?? 5 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Peta Sebaran Mitra -->
            <div id="analitik-audiens" class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">Peta Sebaran Mitra</h3>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    </div>
                    <p class="text-[11px] font-bold text-amber-600 mb-4">{{ $ringkasan->tagline_peta ?? 'Dari Maluku untuk Indonesia' }}</p>

                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-500 block text-[10px]">Kota Ambon</span>
                            <span class="text-base font-black text-[#00264A]">{{ $ringkasan->mitra_ambon ?? 12 }}</span> <span class="text-[10px] text-slate-400">Mitra</span>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-500 block text-[10px]">Kota Tual</span>
                            <span class="text-base font-black text-[#00264A]">{{ $ringkasan->mitra_tual ?? 4 }}</span> <span class="text-[10px] text-slate-400">Mitra</span>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-500 block text-[10px]">Saumlaki</span>
                            <span class="text-base font-black text-[#00264A]">{{ $ringkasan->mitra_saumlaki ?? 3 }}</span> <span class="text-[10px] text-slate-400">Mitra</span>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-500 block text-[10px]">Buru</span>
                            <span class="text-base font-black text-[#00264A]">{{ $ringkasan->mitra_buru ?? 2 }}</span> <span class="text-[10px] text-slate-400">Mitra</span>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-500 block text-[10px]">Seram</span>
                            <span class="text-base font-black text-[#00264A]">{{ $ringkasan->mitra_seram ?? 2 }}</span> <span class="text-[10px] text-slate-400">Mitra</span>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-500 block text-[10px]">Lainnya</span>
                            <span class="text-base font-black text-[#00264A]">{{ $ringkasan->mitra_lainnya ?? 1 }}</span> <span class="text-[10px] text-slate-400">Mitra</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== SECTION 4: INDEKS KINERJA & STRATEGI 2024-2025 ===== -->
    <div id="rencana-kerja" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Indeks Kinerja Pengembangan Usaha -->
        <div class="lg:col-span-4 bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">Indeks Kinerja Usaha</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-emerald-100 text-emerald-800">
                        Nilai {{ $ringkasan->indeks_kinerja ?? 82 }} - {{ $ringkasan->indeks_predikat ?? 'Baik' }}
                    </span>
                </div>

                <div class="space-y-3">
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-slate-600">Pertumbuhan Pendapatan</span>
                            <span class="font-mono text-[#005B9F]">{{ $ringkasan->skor_pertumbuhan_pendapatan ?? 85 }}</span>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-600 rounded-full" style="width: {{ $ringkasan->skor_pertumbuhan_pendapatan ?? 85 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-slate-600">Diversifikasi Usaha</span>
                            <span class="font-mono text-[#005B9F]">{{ $ringkasan->skor_diversifikasi_usaha ?? 80 }}</span>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-600 rounded-full" style="width: {{ $ringkasan->skor_diversifikasi_usaha ?? 80 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-slate-600">Kemitraan Strategis</span>
                            <span class="font-mono text-[#005B9F]">{{ $ringkasan->skor_kemitraan_strategis ?? 78 }}</span>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-500 rounded-full" style="width: {{ $ringkasan->skor_kemitraan_strategis ?? 78 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-slate-600">Efisiensi Operasional</span>
                            <span class="font-mono text-[#005B9F]">{{ $ringkasan->skor_efisiensi_operasional ?? 82 }}</span>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-teal-500 rounded-full" style="width: {{ $ringkasan->skor_efisiensi_operasional ?? 82 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-slate-600">Inovasi Produk</span>
                            <span class="font-mono text-[#005B9F]">{{ $ringkasan->skor_inovasi_produk ?? 85 }}</span>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-purple-600 rounded-full" style="width: {{ $ringkasan->skor_inovasi_produk ?? 85 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Strategi Pengembangan Usaha 2024-2025 -->
        <div class="lg:col-span-8 bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">Strategi Pengembangan Usaha 2024-2025</h3>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-800">ROADMAP</span>
                </div>
                <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                    <strong class="text-slate-800">Fokus Utama:</strong> {{ $ringkasan->fokus_utama ?? 'Ekspansi Kemitraan Lintas Sektor, Optimalisasi Aset & Facility, Pengembangan Event dan Aktivasi, Inovasi Konten Berbasis Potensi Lokal, Digitalisasi Layanan Usaha' }}
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Sudah Berjalan -->
                    <div class="p-4 rounded-xl bg-emerald-50/60 border border-emerald-100">
                        <div class="flex items-center space-x-2 mb-2.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <h4 class="text-xs font-black uppercase tracking-wider text-emerald-800">Sudah Berjalan</h4>
                        </div>
                        <ul class="text-xs text-slate-700 space-y-1.5 list-disc list-inside">
                            @php
                                $sudah = $ringkasan->sudah_berjalan_json ?? ['Penguatan tim pemasaran', 'Rebranding proposal usaha', 'Penawaran paket integrated marketing'];
                            @endphp
                            @foreach($sudah as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Dalam Proses -->
                    <div class="p-4 rounded-xl bg-amber-50/60 border border-amber-100">
                        <div class="flex items-center space-x-2 mb-2.5">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            <h4 class="text-xs font-black uppercase tracking-wider text-amber-800">Dalam Proses</h4>
                        </div>
                        <ul class="text-xs text-slate-700 space-y-1.5 list-disc list-inside">
                            @php
                                $proses = $ringkasan->dalam_proses_json ?? ['Pengembangan e-catalog usaha', 'Kerja sama dengan Pemda & BUMN', 'Persiapan event besar 2025'];
                            @endphp
                            @foreach($proses as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Rencana Selanjutnya -->
                    <div class="p-4 rounded-xl bg-blue-50/60 border border-blue-100">
                        <div class="flex items-center space-x-2 mb-2.5">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            <h4 class="text-xs font-black uppercase tracking-wider text-blue-800">Rencana Selanjutnya</h4>
                        </div>
                        <ul class="text-xs text-slate-700 space-y-1.5 list-disc list-inside">
                            @php
                                $rencana = $ringkasan->rencana_selanjutnya_json ?? ['Ekspansi pasar regional', 'Inovasi layanan digital', 'Peningkatan pendapatan 30% di 2025'];
                            @endphp
                            @foreach($rencana as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== SECTION 5: SOP & REGULASI PENGEMBANGAN USAHA ===== -->
    <div id="sop-regulasi" class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">SOP & Regulasi Pengembangan Usaha</h3>
                <p class="text-xs text-slate-400">Standar Operasional Prosedur Kemitraan, Penyiaran Iklan, Event & PNBP</p>
            </div>
            <a href="{{ route('tvri.usaha.manage', ['tab' => 'sop']) }}" class="text-xs font-bold text-[#005B9F] hover:underline">Kelola & Unggah &rarr;</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($sops as $sop)
            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-50 transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-blue-100 text-[#005B9F]">
                            {{ $sop->kode }}
                        </span>
                        <span class="text-[10px] font-bold text-slate-400 font-mono">{{ $sop->versi }}</span>
                    </div>
                    <h4 class="text-xs font-bold text-slate-900 mb-1 leading-snug">{{ $sop->judul }}</h4>
                    <p class="text-[11px] text-slate-500 line-clamp-2 mb-3">{{ $sop->keterangan }}</p>
                </div>
                <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between text-[10px]">
                    <span class="text-slate-400">{{ $sop->kategori }}</span>
                    <span class="px-2 py-0.5 rounded-full font-bold uppercase {{ $sop->status === 'Aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $sop->status }}
                    </span>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-6 text-slate-400 text-xs">
                Belum ada dokumen SOP. Klik "Kelola & Unggah" untuk menambahkan SOP.
            </div>
            @endforelse
        </div>
    </div>

    <!-- ===== NILAI DASAR TAMBAHAN & PENUTUP ===== -->
    <div id="laporan" class="rounded-2xl p-5 bg-gradient-to-r from-slate-900 to-[#001D38] text-white flex flex-col md:flex-row md:items-center justify-between gap-4 border border-blue-900/40">
        <div>
            <p class="text-[10px] font-black uppercase tracking-widest text-amber-300">Nilai Dasar Pengembangan Usaha</p>
            <p class="text-sm font-bold text-white mt-0.5">{{ $ringkasan->nilai_dasar ?? 'Integritas, Profesional, Kolaboratif, Inovatif, Berdampak' }}</p>
        </div>
        <div class="text-right">
            <p class="text-xs font-serif italic text-blue-200">&ldquo;{{ $ringkasan->quote_penutup ?? 'Media untuk Masa Depan yang Lebih Baik' }}&rdquo;</p>
            <p class="text-[10px] text-slate-400 font-mono mt-0.5">TVRI Stasiun Maluku &bull; Saluran Pemersatu Bangsa</p>
        </div>
    </div>

</div>
@endsection
