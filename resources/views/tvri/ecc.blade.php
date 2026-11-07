@extends('layouts.app')

@section('title', 'Executive Command Center - TVRI Maluku')

@php
    $idr = fn ($v) => 'Rp ' . number_format($v / 1e6, 1) . ' Jt';
    $ringColor = fn ($s) => $s >= 90 ? '#22c55e' : ($s >= 75 ? '#eab308' : '#ef4444');
    $unitColor = [
        'blue' => 'bg-blue-500', 'emerald' => 'bg-emerald-500', 'cyan' => 'bg-cyan-500', 'amber' => 'bg-amber-500',
        'teal' => 'bg-teal-500', 'orange' => 'bg-orange-500', 'purple' => 'bg-purple-500', 'rose' => 'bg-rose-500',
        'pink' => 'bg-pink-500', 'indigo' => 'bg-indigo-500',
    ];
@endphp

@section('content')
<div class="space-y-5">

    <!-- ===== HEADER ECC ===== -->
    <div class="bg-gradient-to-r from-[#001D38] via-[#00264A] to-[#003B70] rounded-3xl p-6 sm:p-7 text-white shadow-xl relative overflow-hidden border border-blue-400/20">
        <div class="absolute -right-10 -top-16 w-72 h-72 bg-cyan-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-white p-2 shadow-lg flex-shrink-0">
                    <img src="{{ asset('images/logo_tvri.png') }}" alt="Logo TVRI" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-black tracking-tight uppercase leading-tight">Executive Command Center</h1>
                    <p class="text-sm font-extrabold text-cyan-300 tracking-widest uppercase">TVRI Maluku</p>
                    <p class="text-[11px] text-blue-200 font-semibold mt-1">SATU DATA, SATU ARAH, UNTUK PENYIARAN YANG LEBIH KUAT</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <div class="bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-white/10 text-right">
                    <p class="text-[10px] text-blue-200 font-bold uppercase tracking-wider">Hari Ini</p>
                    <p id="ecc-clock" class="text-xs font-extrabold text-white global-live-datetime-bullet">{{ now()->isoFormat('dddd, D MMMM Y') }} &bull; {{ now()->format('H:i:s') }} WIT</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-full bg-emerald-500/20 border border-emerald-400/40 text-[11px] font-bold text-emerald-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> LIVE DATA
                </span>
            </div>
        </div>
    </div>

    <!-- ===== KPI STRIP ===== -->
    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-5 gap-3">
        <div class="bg-[#00264A] rounded-2xl p-4 text-white shadow-lg col-span-2 sm:col-span-1 flex items-center justify-between border border-blue-400/20">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-cyan-300">TVRI Maluku Performance Index</p>
                <p id="ecc-performance-num" class="text-3xl font-black mt-1">{{ $performanceIndex }}<span class="text-sm font-bold text-blue-300"> / 100</span></p>
                <p id="ecc-performance-badge" class="text-[10px] font-semibold {{ $performanceIndex >= 90 ? 'text-emerald-400' : 'text-amber-400' }} mt-0.5">{{ $performanceIndex >= 90 ? 'Sangat Baik' : 'Baik' }} &bull; On Track</p>
            </div>
            <div id="ecc-performance-ring" class="w-14 h-14 rounded-full flex items-center justify-center" style="background: conic-gradient({{ $ringColor($performanceIndex) }} {{ $performanceIndex * 3.6 }}deg, rgba(255,255,255,.12) 0deg);">
                <div class="w-10 h-10 rounded-full bg-[#00264A] flex items-center justify-center text-[11px] font-black">{{ $performanceIndex }}</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">PNBP</p>
            <p id="ecc-pnbp-pct" class="text-2xl font-black text-[#00264A] mt-1">{{ $pctPnbp }}%</p>
            <p id="ecc-pnbp-sub" class="text-[11px] text-slate-500 font-semibold">{{ $idr($pnbpRealisasi) }} / target {{ $idr($pnbpTarget) }}</p>
            <span id="ecc-pnbp-badge" class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-[9px] font-extrabold {{ $pctPnbp >= 90 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ $pctPnbp >= 90 ? 'ON TRACK' : 'PERLU PERHATIAN' }}</span>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Program & Siaran</p>
            <p id="ecc-program-pct" class="text-2xl font-black text-[#00264A] mt-1">{{ $pctProgram }}%</p>
            <p id="ecc-program-sub" class="text-[11px] text-slate-500 font-semibold">{{ $programTayang }} / {{ $programTotal }} Program</p>
            <span class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-emerald-100 text-emerald-700">ON TRACK</span>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Audiens & Digital</p>
            <p id="ecc-audiens-views" class="text-2xl font-black text-[#00264A] mt-1">{{ number_format($kontenViews / 1e6, 1) }} Jt</p>
            <p id="ecc-audiens-sub" class="text-[11px] text-slate-500 font-semibold">Views &bull; Reach {{ number_format($kontenReach / 1e6, 1) }} Jt</p>
            <span class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-emerald-100 text-emerald-700">ON TRACK</span>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Keuangan</p>
            <p id="ecc-keuangan-pct" class="text-2xl font-black text-[#00264A] mt-1">{{ $pctAnggaran }}%</p>
            <p id="ecc-keuangan-sub" class="text-[11px] text-slate-500 font-semibold">Serapan {{ $idr($realisasiAnggaran) }} / {{ $idr($pagu) }}</p>
            <span id="ecc-keuangan-badge" class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-[9px] font-extrabold {{ $pctAnggaran >= 60 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ $pctAnggaran >= 60 ? 'ON TRACK' : 'PERLU PERHATIAN' }}</span>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Technical Readiness</p>
            <p id="ecc-teknik-pct" class="text-2xl font-black text-[#00264A] mt-1">{{ round($alatBaik / max(1, $alatTotal) * 100) }}%</p>
            <p class="text-[11px] text-slate-500 font-semibold">Sistem Siap Siar</p>
            <span class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-emerald-100 text-emerald-700">ON TRACK</span>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Operasional & Aset</p>
            <p id="ecc-operasional-pct" class="text-2xl font-black text-[#00264A] mt-1">{{ round($asetBaik / max(1, $totalAset) * 100) }}%</p>
            <p class="text-[11px] text-slate-500 font-semibold">Fasilitas & Layanan</p>
            <span class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-emerald-100 text-emerald-700">ON TRACK</span>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tata Kelola & Administrasi</p>
            <p id="ecc-tatakelola-num" class="text-2xl font-black text-[#00264A] mt-1">{{ $suratMasuk + $suratKeluar }}</p>
            <p id="ecc-tatakelola-sub" class="text-[11px] text-slate-500 font-semibold">Surat Terkelola &bull; {{ $layananAdm }} Layanan</p>
            <span class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-emerald-100 text-emerald-700">ON TRACK</span>
        </div>

        <div class="bg-white rounded-2xl p-4 border {{ $criticalCount > 0 ? 'border-rose-300 bg-rose-50/50' : 'border-slate-200/80' }} shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Critical Issues</p>
            <p id="ecc-critical-num" class="text-2xl font-black mt-1 {{ $criticalCount > 0 ? 'text-rose-600' : 'text-emerald-600' }}">{{ $criticalCount }}</p>
            <p class="text-[11px] text-slate-500 font-semibold">Perlu Tindak Lanjut</p>
            <span id="ecc-critical-badge" class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-[9px] font-extrabold {{ $criticalCount > 0 ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">{{ $criticalCount > 0 ? 'CRITICAL' : 'NORMAL' }}</span>
        </div>
    </div>

    <!-- ===== KINERJA UNIT + SIDE PANELS ===== -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        <!-- KINERJA SETIAP UNIT KERJA -->
        <div class="xl:col-span-2 bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 bg-gradient-to-r from-[#00264A] to-[#005B9F] flex items-center justify-between">
                <h3 class="text-xs font-extrabold uppercase tracking-widest text-white">Kinerja Setiap Unit Kerja</h3>
                <span class="text-[10px] font-bold text-blue-200">{{ count($units) }} Bidang &bull; Real-time</span>
            </div>
            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($units as $u)
                <div class="group border border-slate-200 rounded-2xl p-3.5 hover:border-[#005B9F] hover:shadow-md transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <a href="{{ route('tvri.' . $u['key'] . '.dashboard') }}" class="text-[11px] font-extrabold uppercase text-[#00264A] hover:text-[#005B9F] transition">{{ $u['nama'] }}</a>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-black {{ $u['skor'] >= 90 ? 'bg-emerald-100 text-emerald-700' : ($u['skor'] >= 75 ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $u['skor'] >= 90 ? 'bg-emerald-500' : ($u['skor'] >= 75 ? 'bg-amber-500' : 'bg-rose-500') }}"></span>{{ $u['skor'] }}%
                            </span>
                        </div>
                        <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden mb-2.5">
                            <div class="h-full {{ $unitColor[$u['warna']] ?? 'bg-blue-500' }} rounded-full" style="width: {{ min(100, $u['skor']) }}%"></div>
                        </div>
                        <div class="grid grid-cols-3 gap-1.5">
                            @foreach($u['metrics'] as $m)
                            <div class="bg-slate-50 rounded-lg px-1.5 py-1.5 text-center">
                                <p class="text-[9px] font-semibold text-slate-400 uppercase leading-tight">{{ $m['label'] }}</p>
                                <p class="text-[11px] font-black text-[#00264A] mt-0.5">{{ $m['value'] }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-100">
                        @if($u['key'] === 'berita')
                        <button type="button" onclick="openJadwalVercelModal()" class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-50 text-cyan-700 hover:bg-cyan-100 border border-cyan-200 transition" title="Buka Portal Jadwal Liputan Vercel">
                            <svg class="w-3 h-3 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Jadwal Liputan</span>
                        </button>
                        @else
                        <span></span>
                        @endif
                        <a href="{{ route('tvri.' . $u['key'] . '.dashboard') }}" class="text-[10px] font-bold text-[#005B9F] group-hover:text-[#003B70] hover:underline">Lihat Dashboard &rarr;</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- PANEL KANAN -->
        <div class="space-y-5">
            <!-- CAPAIAN PNBP -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 bg-gradient-to-r from-[#00264A] to-[#005B9F]">
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-white">Capaian PNBP Tahun Berjalan</h3>
                </div>
                <div class="p-5">
                    <p id="ecc-side-pnbp-realisasi" class="text-2xl font-black text-[#00264A]">{{ $idr($pnbpRealisasi) }}</p>
                    <p id="ecc-side-pnbp-target" class="text-[11px] font-semibold text-slate-500 mb-3">dari Target {{ $idr($pnbpTarget) }}</p>
                    <div class="h-3 bg-slate-100 rounded-full overflow-hidden">
                        <div id="ecc-side-pnbp-bar" class="h-full bg-gradient-to-r from-[#005B9F] to-cyan-400 rounded-full" style="width: {{ min(100, $pctPnbp) }}%"></div>
                    </div>
                    <p id="ecc-side-pnbp-pct" class="text-[10px] font-bold text-slate-500 mt-1.5 text-right">{{ $pctPnbp }}%</p>
                </div>
            </div>

            <!-- REALISASI ANGGARAN -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 bg-gradient-to-r from-[#00264A] to-[#005B9F]">
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-white">Realisasi Anggaran</h3>
                </div>
                <div class="p-5">
                    <p id="ecc-side-keu-realisasi" class="text-2xl font-black text-[#00264A]">{{ $idr($realisasiAnggaran) }}</p>
                    <p id="ecc-side-keu-target" class="text-[11px] font-semibold text-slate-500 mb-3">dari Pagu {{ $idr($pagu) }} &bull; Sisa {{ $idr($pagu - $realisasiAnggaran) }}</p>
                    <div class="h-3 bg-slate-100 rounded-full overflow-hidden">
                        <div id="ecc-side-keu-bar" class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full" style="width: {{ min(100, $pctAnggaran) }}%"></div>
                    </div>
                    <p id="ecc-side-keu-pct" class="text-[10px] font-bold text-slate-500 mt-1.5 text-right">{{ $pctAnggaran }}%</p>
                </div>
            </div>

            <!-- STATUS OPERASIONAL -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 bg-gradient-to-r from-[#00264A] to-[#005B9F]">
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-white">Status Operasional</h3>
                </div>
                <div class="p-5 grid grid-cols-4 gap-3 text-center">
                    @php
                        $op = [
                            ['label' => 'Siapan Studio', 'pct' => round($alatBaik / max(1, $alatTotal) * 100)],
                            ['label' => 'Transmisi Stabil', 'pct' => round($siteOnAir / max(1, $siteTotal) * 100)],
                            ['label' => 'Fasilitas Layak', 'pct' => round($asetBaik / max(1, $totalAset) * 100)],
                            ['label' => 'Kendaraan Siap', 'pct' => round($kendaraanSiap / max(1, $kendaraanTotal) * 100)],
                        ];
                    @endphp
                    @foreach($op as $o)
                    <div class="flex flex-col items-center">
                        <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background: conic-gradient({{ $ringColor($o['pct']) }} {{ $o['pct'] * 3.6 }}deg, #e2e8f0 0deg);">
                            <div class="w-11 h-11 rounded-full bg-white flex items-center justify-center text-[11px] font-black text-[#00264A]">{{ $o['pct'] }}%</div>
                        </div>
                        <p class="text-[9px] font-bold text-slate-500 uppercase mt-1.5 leading-tight">{{ $o['label'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- ===== ALERTS + ACTION REQUIRED ===== -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 bg-gradient-to-r from-rose-700 to-[#005B9F] flex items-center justify-between">
                <h3 class="text-xs font-extrabold uppercase tracking-widest text-white">Top Executive Alerts</h3>
                <span id="ecc-alerts-header-badge" class="text-[10px] font-bold bg-white/20 text-white px-2 py-0.5 rounded-full">{{ $criticalCount }} Kritis &bull; {{ count($alerts) - $criticalCount }} Perhatian</span>
            </div>
            <div id="ecc-alerts-container" class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                @forelse($alerts as $a)
                <div class="px-5 py-3 flex items-start gap-3">
                    <span class="mt-1 w-2.5 h-2.5 rounded-full flex-shrink-0 {{ $a['prioritas'] === 'Kritis' ? 'bg-rose-500' : 'bg-amber-400' }}"></span>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-800 truncate">{{ $a['isu'] }}</p>
                        <p class="text-[10px] text-slate-500 font-semibold">Unit: {{ $a['unit'] }} &bull; Dampak: {{ $a['dampak'] }}</p>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold flex-shrink-0 {{ $a['prioritas'] === 'Kritis' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700' }}">{{ strtoupper($a['prioritas']) }}</span>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-xs font-semibold text-slate-400">Tidak ada alert. Seluruh unit dalam kondisi normal.</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 bg-gradient-to-r from-[#00264A] to-[#005B9F] flex items-center justify-between">
                <h3 class="text-xs font-extrabold uppercase tracking-widest text-white">Agenda & Aktivitas Terkini</h3>
                <button type="button" onclick="openJadwalVercelModal()" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-white/15 hover:bg-white/25 text-white border border-white/20 transition-all shadow-sm" title="Buka Portal Jadwal Liputan TVRI Maluku">
                    <svg class="w-3.5 h-3.5 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Jadwal Liputan Live</span>
                </button>
            </div>
            <div class="p-5 space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-50 rounded-xl p-3">
                        <p class="text-[9px] font-bold uppercase text-slate-400">Agenda Hari Ini</p>
                        <p id="ecc-summary-agenda" class="text-lg font-black text-[#00264A]">{{ $agendaHariIni }} Kegiatan</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3">
                        <p class="text-[9px] font-bold uppercase text-slate-400">Layanan Administrasi</p>
                        <p id="ecc-summary-layanan" class="text-lg font-black text-[#00264A]">{{ $layananAdm }} Permohonan</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3">
                        <p class="text-[9px] font-bold uppercase text-slate-400">Mitra / Klien Aktif</p>
                        <p id="ecc-summary-mitra" class="text-lg font-black text-[#00264A]">{{ $mitraAktif }} Mitra</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3">
                        <p class="text-[9px] font-bold uppercase text-slate-400">Kampanye Promo</p>
                        <p id="ecc-summary-kampanye" class="text-lg font-black text-[#00264A]">{{ $kampanyeTotal }} Aktif</p>
                    </div>
                </div>
                <div class="border border-slate-100 rounded-xl p-3.5">
                    <p class="text-[10px] font-extrabold uppercase text-slate-400 mb-2">Ringkasan Siaran</p>
                    <div class="flex items-center justify-between text-center">
                        <div><p id="ecc-summary-site-onair" class="text-sm font-black text-emerald-600">{{ $siteOnAir }}</p><p class="text-[9px] font-bold text-slate-400 uppercase">Site On-Air</p></div>
                        <div><p id="ecc-summary-site-gangguan" class="text-sm font-black text-amber-600">{{ $siteGangguan }}</p><p class="text-[9px] font-bold text-slate-400 uppercase">Gangguan</p></div>
                        <div><p id="ecc-summary-kualitas" class="text-sm font-black text-[#00264A]">{{ number_format((float) $kualitasRata, 1) }}</p><p class="text-[9px] font-bold text-slate-400 uppercase">Kualitas Sinyal</p></div>
                        <div><p id="ecc-summary-berita-tayang" class="text-sm font-black text-[#00264A]">{{ $beritaTayang }}</p><p class="text-[9px] font-bold text-slate-400 uppercase">Berita Tayang</p></div>
                        <div><p id="ecc-summary-program-tayang" class="text-sm font-black text-[#00264A]">{{ $programTayang }}</p><p class="text-[9px] font-bold text-slate-400 uppercase">Program Tayang</p></div>
                    </div>
                </div>
                <!-- DOKUMEN & SOP TRANSMISI TERBARU -->
                <div class="border border-slate-100 rounded-xl p-3.5 bg-slate-50/60">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-[10px] font-extrabold uppercase text-[#00264A] flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-cyan-600 animate-pulse"></span>
                            <span>Dokumen & SOP Transmisi Terverifikasi</span>
                        </p>
                        <span id="ecc-doc-count" class="text-[9px] font-mono font-bold text-cyan-800 bg-cyan-100/70 px-2 py-0.5 rounded-full">{{ $transmisiSops->count() }} Dokumen</span>
                    </div>
                    <div class="space-y-1.5 max-h-36 overflow-y-auto">
                        @forelse($transmisiSops as $sopTx)
                        <div class="flex items-center justify-between p-2 rounded-lg bg-white border border-slate-100 text-xs hover:border-cyan-300 transition-all">
                            <div class="flex items-center space-x-2 truncate mr-2">
                                <span class="font-mono text-[9px] font-bold text-cyan-700 bg-cyan-50 px-1.5 py-0.5 rounded border border-cyan-200">{{ $sopTx->kode ?? 'SOP' }}</span>
                                <span class="font-bold text-[#00264A] truncate">{{ $sopTx->judul }}</span>
                                <span class="text-[9px] text-slate-400 font-mono">{{ $sopTx->versi }}</span>
                            </div>
                            @php $fileUrl = $sopTx->file_url ?: $sopTx->file_path; @endphp
                            @if($fileUrl)
                            <a href="{{ asset($fileUrl) }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-50 text-cyan-700 hover:bg-cyan-100 border border-cyan-200 transition flex-shrink-0" title="Buka Dokumen Fisik SOP">
                                <svg class="w-3 h-3 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Fisik SOP</span>
                            </a>
                            @else
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 flex-shrink-0">{{ $sopTx->status }}</span>
                            @endif
                        </div>
                        @empty
                        <p class="text-xs text-slate-400 py-2 text-center">Belum ada dokumen SOP transmisi terdaftar.</p>
                        @endforelse
                    </div>
                </div>
                <p class="text-[10px] text-slate-400 font-semibold text-center">Data setiap unit bersumber dari dashboard bidang masing-masing dan tervalidasi otomatis ke Command Center.</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Live ticking clock WIT (UTC+9)
    function updateClock() {
        const el = document.getElementById('ecc-clock');
        if (!el) return;
        const now = new Date();
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        // Time in WIT (+9)
        const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
        const witDate = new Date(utc + (3600000 * 9));
        
        const dayName = days[witDate.getDay()];
        const dayNum = witDate.getDate();
        const monthName = months[witDate.getMonth()];
        const year = witDate.getFullYear();
        
        const h = String(witDate.getHours()).padStart(2, '0');
        const m = String(witDate.getMinutes()).padStart(2, '0');
        const s = String(witDate.getSeconds()).padStart(2, '0');
        
        el.innerHTML = dayName + ', ' + dayNum + ' ' + monthName + ' ' + year + ' &bull; ' + h + ':' + m + ':' + s + ' WIT';
    }
    updateClock();
    setInterval(updateClock, 1000);

    // 2. Real-time background sync dari setiap unit kerja
    let isFetching = false;
    async function syncEccData() {
        if (isFetching) return;
        isFetching = true;
        try {
            const res = await fetch('{{ route("tvri.ecc.dashboard") }}?realtime=1', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (res.ok) {
                const data = await res.json();
                
                // Update Performance Index
                const perfNum = document.getElementById('ecc-performance-num');
                if (perfNum) perfNum.innerHTML = data.performanceIndex + '<span class="text-sm font-bold text-blue-300"> / 100</span>';
                const perfBadge = document.getElementById('ecc-performance-badge');
                if (perfBadge) perfBadge.textContent = data.performanceLabel;
                const perfRing = document.getElementById('ecc-performance-ring');
                if (perfRing) {
                    const ringColor = data.performanceIndex >= 90 ? '#22c55e' : (data.performanceIndex >= 75 ? '#eab308' : '#ef4444');
                    perfRing.style.background = 'conic-gradient(' + ringColor + ' ' + (data.performanceIndex * 3.6) + 'deg, rgba(255,255,255,.12) 0deg)';
                    const inner = perfRing.querySelector('div');
                    if (inner) inner.textContent = data.performanceIndex;
                }

                // PNBP
                const pnbpPct = document.getElementById('ecc-pnbp-pct');
                if (pnbpPct) pnbpPct.textContent = data.pnbp.pct + '%';
                const pnbpSub = document.getElementById('ecc-pnbp-sub');
                if (pnbpSub) pnbpSub.textContent = data.pnbp.realisasiLabel + ' / target ' + data.pnbp.targetLabel;
                const pnbpBadge = document.getElementById('ecc-pnbp-badge');
                if (pnbpBadge) {
                    pnbpBadge.textContent = data.pnbp.status;
                    pnbpBadge.className = 'inline-block mt-1.5 px-2 py-0.5 rounded-full text-[9px] font-extrabold ' + (data.pnbp.pct >= 90 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700');
                }

                // Program & Siaran
                const progPct = document.getElementById('ecc-program-pct');
                if (progPct) progPct.textContent = data.program.pct + '%';
                const progSub = document.getElementById('ecc-program-sub');
                if (progSub) progSub.textContent = data.program.tayang + ' / ' + data.program.total + ' Program';

                // Audiens & Digital
                const audViews = document.getElementById('ecc-audiens-views');
                if (audViews) audViews.textContent = data.audiens.views;
                const audSub = document.getElementById('ecc-audiens-sub');
                if (audSub) audSub.innerHTML = 'Views &bull; Reach ' + data.audiens.reach;

                // Keuangan
                const keuPct = document.getElementById('ecc-keuangan-pct');
                if (keuPct) keuPct.textContent = data.keuangan.pct + '%';
                const keuSub = document.getElementById('ecc-keuangan-sub');
                if (keuSub) keuSub.textContent = 'Serapan ' + data.keuangan.realisasiLabel + ' / ' + data.keuangan.paguLabel;
                const keuBadge = document.getElementById('ecc-keuangan-badge');
                if (keuBadge) {
                    keuBadge.textContent = data.keuangan.status;
                    keuBadge.className = 'inline-block mt-1.5 px-2 py-0.5 rounded-full text-[9px] font-extrabold ' + (data.keuangan.pct >= 60 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700');
                }

                // Technical Readiness
                const tekPct = document.getElementById('ecc-teknik-pct');
                if (tekPct) tekPct.textContent = data.teknik.readiness + '%';

                // Operasional & Aset
                const opsPct = document.getElementById('ecc-operasional-pct');
                if (opsPct) opsPct.textContent = data.operasional.readiness + '%';

                // Tata Kelola & Administrasi
                const tuNum = document.getElementById('ecc-tatakelola-num');
                if (tuNum) tuNum.textContent = data.tataKelola.totalSurat;
                const tuSub = document.getElementById('ecc-tatakelola-sub');
                if (tuSub) tuSub.innerHTML = 'Surat Terkelola &bull; ' + data.tataKelola.layananAdm + ' Layanan';

                // Critical Issues
                const critNum = document.getElementById('ecc-critical-num');
                if (critNum) critNum.textContent = data.critical.count;

                // Side panels
                const sidePnbpRealisasi = document.getElementById('ecc-side-pnbp-realisasi');
                if (sidePnbpRealisasi) sidePnbpRealisasi.textContent = data.pnbp.realisasiLabel;
                const sidePnbpTarget = document.getElementById('ecc-side-pnbp-target');
                if (sidePnbpTarget) sidePnbpTarget.textContent = 'dari Target ' + data.pnbp.targetLabel;
                const sidePnbpPct = document.getElementById('ecc-side-pnbp-pct');
                if (sidePnbpPct) sidePnbpPct.textContent = data.pnbp.pct + '%';
                const sidePnbpBar = document.getElementById('ecc-side-pnbp-bar');
                if (sidePnbpBar) sidePnbpBar.style.width = Math.min(100, data.pnbp.pct) + '%';

                const sideKeuRealisasi = document.getElementById('ecc-side-keu-realisasi');
                if (sideKeuRealisasi) sideKeuRealisasi.textContent = data.keuangan.realisasiLabel;
                const sideKeuPct = document.getElementById('ecc-side-keu-pct');
                if (sideKeuPct) sideKeuPct.textContent = data.keuangan.pct + '%';
                const sideKeuBar = document.getElementById('ecc-side-keu-bar');
                if (sideKeuBar) sideKeuBar.style.width = Math.min(100, data.keuangan.pct) + '%';

                // Quick summary numbers
                const sumAgenda = document.getElementById('ecc-summary-agenda');
                if (sumAgenda && data.agendaHariIni) sumAgenda.textContent = data.agendaHariIni + ' Kegiatan';
                const sumLayanan = document.getElementById('ecc-summary-layanan');
                if (sumLayanan) sumLayanan.textContent = data.tataKelola.layananAdm + ' Permohonan';
                const sumSiteOnair = document.getElementById('ecc-summary-site-onair');
                if (sumSiteOnair) sumSiteOnair.textContent = data.siteOnAir;
                const sumSiteGangguan = document.getElementById('ecc-summary-site-gangguan');
                if (sumSiteGangguan) sumSiteGangguan.textContent = data.siteGangguan;
                const sumKualitas = document.getElementById('ecc-summary-kualitas');
                if (sumKualitas) sumKualitas.textContent = data.kualitasRata;
                const sumBeritaTayang = document.getElementById('ecc-summary-berita-tayang');
                if (sumBeritaTayang) sumBeritaTayang.textContent = data.beritaTayang;
                const sumProgramTayang = document.getElementById('ecc-summary-program-tayang');
                if (sumProgramTayang) sumProgramTayang.textContent = data.program.tayang;
                const docCount = document.getElementById('ecc-doc-count');
                if (docCount && data.totalDokumenTransmisi !== undefined) docCount.textContent = data.totalDokumenTransmisi + ' Dokumen';
            }
        } catch (e) {
            // Non-blocking
        } finally {
            isFetching = false;
        }
    }

    // Interval polling background setiap 8 detik
    setInterval(syncEccData, 8000);
});
</script>

@include('tvri.partials.jadwal_vercel_modal')
@endsection
