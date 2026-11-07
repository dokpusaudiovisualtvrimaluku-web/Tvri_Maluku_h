@extends('layouts.app')

@section('title', 'Dashboard Umum TVRI Maluku - Layanan, Aset & Sarpras')

@section('content')
<div class="space-y-6 pb-12" x-data="{
    currentWit: '{{ $ringkasan->header_waktu ?? 'Selasa, 2 September 2025 | 10:24 WIT' }}',
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
    <div class="bg-gradient-to-r from-[#001D38] via-[#00264A] to-[#003B70] rounded-3xl p-6 sm:p-7 text-white shadow-xl relative overflow-hidden border border-emerald-400/20">
        <div class="absolute -right-10 -top-16 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <div class="flex items-start sm:items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-white p-2 shadow-lg flex-shrink-0 flex items-center justify-center">
                    <img src="{{ asset('images/logo_tvri.png') }}" alt="Logo TVRI" class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-black uppercase tracking-tight leading-tight">DASHBOARD UMUM TVRI MALUKU</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-500/20 text-emerald-300 border border-emerald-400/40 uppercase">UMM</span>
                    </div>
                    <p class="text-xs sm:text-sm font-extrabold text-emerald-300 tracking-wider uppercase mt-0.5">LAYANAN OPERASIONAL &bull; SARANA PRASARANA &bull; ASET &bull; KEAMANAN &bull; KENYAMANAN</p>
                    <p class="text-xs text-blue-200/90 font-medium mt-1">
                        "Lingkungan Kerja yang Aman, Nyaman dan Produktif untuk Siaran yang Lebih Bermakna" &bull; <span class="italic text-amber-300 font-semibold">Maluku Bisa Lebih Maju!</span>
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <div class="bg-white/10 backdrop-blur-md px-4 py-2 rounded-2xl border border-white/10 text-left sm:text-right">
                    <div class="text-[10px] uppercase font-bold text-emerald-300 tracking-wider flex items-center gap-1.5 sm:justify-end">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>{{ $ringkasan->live_status ?? 'Live System | Periode: September 2025' }}</span>
                    </div>
                    <div class="text-xs font-black text-white mt-0.5 font-mono" x-text="currentWit">
                        Selasa, 2 September 2025 | 10:24 WIT
                    </div>
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
                @if(auth()->user() && in_array(auth()->user()->username, ['umum', 'admin', 'superadmin']) && !$isKepalaStasiun)
                <a href="{{ route('tvri.umum.manage') }}"
                   class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Input Data Umum</span>
                </a>
                @endif

                <a href="{{ route('tvri.ecc.dashboard') }}"
                   class="inline-flex items-center space-x-1.5 px-3.5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition-all">
                    <span>&larr; Monitoring ECC</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ===== METRIK UTAMA ATAS (7 KPI CARDS) ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
        <!-- 1. Total Aset -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-black uppercase tracking-wider text-slate-500">Total Aset</span>
                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">BMN</span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl font-black text-[#00264A]">{{ number_format($totalAset) }}</span>
            </div>
            <div class="mt-1 flex items-center text-[10px] font-bold text-emerald-600">
                <span class="mr-1">&Delta;</span> {{ $ringkasan->diff_aset ?? '+3% dari bulan lalu' }}
            </div>
        </div>

        <!-- 2. Kendaraan Dinas -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-black uppercase tracking-wider text-slate-500">Kendaraan Dinas</span>
                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-blue-50 text-blue-600 border border-blue-200">ARMADA</span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl font-black text-[#00264A]">{{ $kendaraanTotal }}</span>
            </div>
            <div class="mt-1 text-[10px] font-bold text-blue-600">
                {{ $kendaraanSiapPct }}% Siap (▲ {{ $ringkasan->diff_kendaraan ?? '+4%' }})
            </div>
        </div>

        <!-- 3. Sarana Prasarana -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-black uppercase tracking-wider text-slate-500">Sarana Prasarana</span>
                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-purple-50 text-purple-600 border border-purple-200">SARPRAS</span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl font-black text-[#00264A]">{{ $sarprasTotal }}</span>
            </div>
            <div class="mt-1 text-[10px] font-bold text-purple-600">
                Baik: {{ $sarprasBaikPct }}% (▲ {{ $ringkasan->diff_sarpras ?? '+5%' }})
            </div>
        </div>

        <!-- 4. Permintaan Layanan -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-black uppercase tracking-wider text-slate-500">Permintaan Layanan</span>
                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-cyan-50 text-cyan-600 border border-cyan-200">TIKET</span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl font-black text-[#00264A]">{{ $permintaanTotal }}</span>
            </div>
            <div class="mt-1 text-[10px] font-bold text-cyan-600">
                Selesai: {{ $permintaanSelesaiPct }}% (▲ {{ $ringkasan->diff_permintaan ?? '+12%' }})
            </div>
        </div>

        <!-- 5. Insiden Keamanan -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-black uppercase tracking-wider text-slate-500">Insiden Keamanan</span>
                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-50 text-amber-600 border border-amber-200">K3</span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl font-black text-amber-700">{{ $ringkasan->insiden_keamanan }}</span>
            </div>
            <div class="mt-1 text-[10px] font-bold text-emerald-600">
                &nabla; {{ $ringkasan->diff_insiden ?? '-50% dari bulan lalu' }}
            </div>
        </div>

        <!-- 6. Indeks Kenyamanan -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-black uppercase tracking-wider text-slate-500">Indeks Kenyamanan</span>
                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-teal-50 text-teal-600 border border-teal-200">IKK</span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl font-black text-teal-700">{{ $ringkasan->indeks_kenyamanan }}%</span>
            </div>
            <div class="mt-1 text-[10px] font-bold text-slate-500">
                Target: {{ $ringkasan->target_kenyamanan ?? 90 }}%
            </div>
        </div>

        <!-- 7. Operational Readiness -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow col-span-2 sm:col-span-1">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-black uppercase tracking-wider text-slate-500">Readiness</span>
                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-300">SIAP</span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl font-black text-emerald-700">{{ $ringkasan->operational_readiness }}%</span>
            </div>
            <div class="mt-1 text-[10px] font-bold text-emerald-600">
                Target {{ $ringkasan->target_readiness ?? 90 }}% (Sangat Baik)
            </div>
        </div>
    </div>

    <!-- ===== SECTION 1: KONDISI ASET & STATUS KENDARAAN DINAS ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <!-- Kondisi Aset (7 Cols) -->
        <div id="manajemen-aset" class="lg:col-span-7 bg-white rounded-3xl p-5 sm:p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">KONDISI ASET BMN</h3>
                    <p class="text-xs text-slate-500 font-semibold">Total Aset: <span class="font-bold text-[#00264A]">{{ number_format($totalAset) }} Unit</span> tercatat di SIMAK-BMN</p>
                </div>
                <span class="px-2.5 py-1 text-[10px] font-black rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">
                    {{ $pctAsetBaik }}% SIAP DIGUNAKAN
                </span>
            </div>

            <div class="space-y-3.5">
                <!-- Baik -->
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-emerald-700 flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Baik
                        </span>
                        <span class="font-mono text-slate-700">{{ number_format($asetBaik) }} Unit ({{ $pctAsetBaik }}%)</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                        <div class="bg-emerald-500 h-3 rounded-full" style="width: {{ $pctAsetBaik }}%"></div>
                    </div>
                </div>

                <!-- Rusak Ringan -->
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-amber-700 flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Rusak Ringan
                        </span>
                        <span class="font-mono text-slate-700">{{ number_format($asetRusakRingan) }} Unit ({{ $pctAsetRusakRingan }}%)</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                        <div class="bg-amber-500 h-3 rounded-full" style="width: {{ $pctAsetRusakRingan }}%"></div>
                    </div>
                </div>

                <!-- Dalam Perbaikan -->
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-blue-700 flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> Dalam Perbaikan
                        </span>
                        <span class="font-mono text-slate-700">{{ number_format($asetDalamPerbaikan) }} Unit ({{ $pctAsetDalamPerbaikan }}%)</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                        <div class="bg-blue-500 h-3 rounded-full" style="width: {{ $pctAsetDalamPerbaikan }}%"></div>
                    </div>
                </div>

                <!-- Rusak Berat -->
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-rose-700 flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Rusak Berat
                        </span>
                        <span class="font-mono text-slate-700">{{ number_format($asetRusakBerat) }} Unit ({{ $pctAsetRusakBerat }}%)</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                        <div class="bg-rose-500 h-3 rounded-full" style="width: {{ $pctAsetRusakBerat }}%"></div>
                    </div>
                </div>

                <!-- Tidak Aktif -->
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-slate-600 flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span> Tidak Aktif
                        </span>
                        <span class="font-mono text-slate-700">{{ number_format($asetTidakAktif) }} Unit ({{ $pctAsetTidakAktif }}%)</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                        <div class="bg-slate-400 h-3 rounded-full" style="width: {{ $pctAsetTidakAktif }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Mini stats footer -->
            <div class="grid grid-cols-3 gap-2 mt-5 pt-4 border-t border-slate-100 text-center">
                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                    <p class="text-[10px] uppercase font-bold text-slate-400">Nilai Perolehan</p>
                    <p class="text-xs font-black text-[#00264A] mt-0.5 font-mono">Rp {{ number_format($nilaiAset / 1e9, 1, ',', '.') }} M</p>
                </div>
                <div class="p-2.5 rounded-xl bg-emerald-50/60 border border-emerald-100">
                    <p class="text-[10px] uppercase font-bold text-emerald-700">Aset Produktif</p>
                    <p class="text-xs font-black text-emerald-700 mt-0.5 font-mono">{{ number_format($asetProduktif) }} Unit</p>
                </div>
                <div class="p-2.5 rounded-xl bg-amber-50/60 border border-amber-100">
                    <p class="text-[10px] uppercase font-bold text-amber-700">Butuh Tindak Lanjut</p>
                    <p class="text-xs font-black text-amber-700 mt-0.5 font-mono">{{ number_format($asetTindakLanjut) }} Unit</p>
                </div>
            </div>
        </div>

        <!-- Status Kendaraan Dinas (5 Cols) -->
        <div id="kendaraan-dinas" class="lg:col-span-5 bg-white rounded-3xl p-5 sm:p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">STATUS KENDARAAN DINAS</h3>
                        <p class="text-xs text-slate-500 font-semibold">{{ $kendaraanTotal }} Unit Armada Operasional TVRI</p>
                    </div>
                    <span class="px-2.5 py-1 text-[10px] font-black rounded-lg bg-blue-50 text-blue-700 border border-blue-200 uppercase">
                        {{ $kendaraanSiap }} SIAP ({{ $kendaraanSiapPct }}%)
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-2.5 mb-4">
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-700">{{ $countMobil }} Mobil</span>
                            <span class="px-1.5 py-0.5 text-[9px] font-black rounded bg-emerald-100 text-emerald-700">Siap</span>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1 font-medium">Pool Kastasiun, Kru & Liputan</p>
                    </div>
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-700">{{ $countMotor }} Motor</span>
                            <span class="px-1.5 py-0.5 text-[9px] font-black rounded bg-emerald-100 text-emerald-700">Siap</span>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1 font-medium">Kurir Berita, Umum & Teknisi</p>
                    </div>
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-700">{{ $countBus }} Bus</span>
                            <span class="px-1.5 py-0.5 text-[9px] font-black rounded bg-emerald-100 text-emerald-700">Siap</span>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1 font-medium">OB Van & Microbus Kru</p>
                    </div>
                    <div class="p-3 rounded-2xl bg-amber-50/60 border border-amber-200">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-amber-900">{{ $countTruk }} Truk</span>
                            <span class="px-1.5 py-0.5 text-[9px] font-black rounded bg-amber-200 text-amber-800">Perbaikan</span>
                        </div>
                        <p class="text-[10px] text-amber-700 mt-1 font-medium">Truk Genset & Angkut</p>
                    </div>
                </div>

                <div class="p-3.5 rounded-2xl bg-[#001D38] text-white">
                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="font-bold">Kesiapan Armada Keseluruhan</span>
                        <span class="font-mono font-black text-emerald-400">{{ $kendaraanSiapPct }}%</span>
                    </div>
                    <div class="w-full bg-white/20 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-400 to-cyan-400 h-2.5 rounded-full" style="width: {{ $kendaraanSiapPct }}%"></div>
                    </div>
                    <div class="flex items-center justify-between text-[10px] text-blue-200 font-medium mt-2">
                        <span>{{ $kendaraanSiap }} ({{ $kendaraanSiapPct }}%) Siap Operasi</span>
                        <span>{{ $kendaraanPerbaikan }} ({{ $kendaraanPerbaikanPct }}%) Dalam Perbaikan</span>
                        <span>{{ $kendaraanTidakOperasi }} ({{ $kendaraanTidakOperasiPct }}%) Tidak Operasi</span>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500 font-semibold">Cek Detail Armada Kendaraan</span>
                <a href="{{ route('tvri.umum.manage') }}?tab=kendaraan" class="text-blue-600 font-bold hover:underline">Kelola Armada &rarr;</a>
            </div>
        </div>
    </div>

    <!-- ===== DAFTAR ASET BMN & ARMADA KENDARAAN OPERASIONAL ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <!-- Daftar Aset BMN -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-5 sm:p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">Inventaris Aset BMN Tercatat</h3>
                    <p class="text-xs text-slate-500 font-semibold">Daftar aset BMN yang aktif tercatat di SIMAK-BMN TVRI Maluku</p>
                </div>
                <span class="px-2.5 py-1 text-[10px] font-black rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">
                    {{ count($asets) }} ITEM TERDATA
                </span>
            </div>

            <div class="overflow-x-auto max-h-[360px] overflow-y-auto">
                <table class="w-full text-left text-xs">
                    <thead class="sticky top-0 bg-slate-50 z-10">
                        <tr class="border-b border-slate-100 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                            <th class="py-2.5 px-2">Nama Aset</th>
                            <th class="py-2.5 px-2">Kategori</th>
                            <th class="py-2.5 px-2 text-center">Jumlah</th>
                            <th class="py-2.5 px-2 text-center">Kondisi</th>
                            <th class="py-2.5 px-2 text-right">Nilai Perolehan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($asets as $a)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-2.5 px-2 font-bold text-[#00264A]">
                                <div>{{ $a->nama }}</div>
                                <div class="text-[10px] font-mono text-slate-400">{{ $a->kode }} &bull; {{ $a->lokasi }}</div>
                            </td>
                            <td class="py-2.5 px-2 text-slate-500 text-[11px]">{{ $a->kategori }}</td>
                            <td class="py-2.5 px-2 text-center font-bold text-slate-700">{{ $a->jumlah }}</td>
                            <td class="py-2.5 px-2 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase
                                    {{ $a->kondisi === 'Baik' ? 'bg-emerald-100 text-emerald-700' : ($a->kondisi === 'Rusak Ringan' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                    {{ $a->kondisi }}
                                </span>
                            </td>
                            <td class="py-2.5 px-2 text-right font-mono font-bold text-slate-800 text-[11px]">Rp {{ number_format($a->nilai) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-slate-400 text-xs">Belum ada inventaris aset.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Daftar Armada Kendaraan -->
        <div class="lg:col-span-5 bg-white rounded-3xl p-5 sm:p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">Daftar Armada Kendaraan Dinas</h3>
                    <p class="text-xs text-slate-500 font-semibold">{{ $kendaraanTotal }} Unit Kendaraan Operasional & Liputan</p>
                </div>
                <span class="px-2.5 py-1 text-[10px] font-black rounded-lg bg-blue-50 text-blue-700 border border-blue-200 uppercase">
                    {{ count($kendaraans) }} UNIT
                </span>
            </div>

            <div class="space-y-2 max-h-[360px] overflow-y-auto pr-1">
                @forelse($kendaraans as $k)
                <div class="p-3 rounded-2xl border border-slate-100 bg-slate-50 flex items-center justify-between hover:bg-slate-100/60 transition-all">
                    <div>
                        <p class="font-bold text-[#00264A] text-xs">{{ $k->nama }}</p>
                        <p class="text-[10px] font-mono font-bold text-slate-500 mt-0.5">{{ $k->plat_nomor }} &bull; {{ $k->jenis }}</p>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase
                        {{ $k->status === 'Siap Operasi' ? 'bg-emerald-100 text-emerald-700' : ($k->status === 'Dalam Perbaikan' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                        {{ $k->status }}
                    </span>
                </div>
                @empty
                <p class="text-xs text-slate-400 text-center py-4">Belum ada data kendaraan dinas.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ===== SECTION 2: KONDISI SARPRAS & PERMINTAAN LAYANAN ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <!-- Kondisi Sarana Prasarana (6 Cols) -->
        <div id="sarana-prasarana" class="lg:col-span-6 bg-white rounded-3xl p-5 sm:p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">KONDISI SARANA PRASARANA</h3>
                    <p class="text-xs text-slate-500 font-semibold">Total {{ $sarprasTotal }} Unit Sarpras &bull; Rata-rata Baik: {{ $sarprasBaikPct }}%</p>
                </div>
                <span class="px-2.5 py-1 text-[10px] font-black rounded-lg bg-purple-50 text-purple-700 border border-purple-200 uppercase">
                    {{ $sarprasTotal }} UNIT
                </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($sarpras as $sp)
                @php
                    $pctVal = (int) filter_var($sp->kondisi_baik, FILTER_SANITIZE_NUMBER_INT);
                    if ($pctVal <= 0) $pctVal = 85;
                @endphp
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 hover:bg-white hover:shadow transition-all">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-extrabold text-[#00264A]">{{ $sp->nama }}</span>
                        <span class="text-[10px] font-bold text-slate-500 font-mono">{{ $sp->jumlah_unit }} Unit</span>
                    </div>
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="text-[10px] text-slate-400 font-semibold">Kondisi Baik</span>
                        <span class="font-black font-mono text-emerald-600 text-xs">{{ $sp->kondisi_baik }}</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $pctVal }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4 p-3 rounded-2xl bg-blue-50/70 border border-blue-100 flex items-center justify-between text-xs">
                <span class="text-blue-900 font-medium">Optimalisasi pemeliharaan rutin sarana kerja & infrastruktur transmisi di 11 kabupaten/kota.</span>
            </div>
        </div>

        <!-- Permintaan Layanan & Perbaikan (6 Cols) -->
        <div id="monitoring-layanan" class="lg:col-span-6 bg-white rounded-3xl p-5 sm:p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">PERMINTAAN LAYANAN & PERBAIKAN</h3>
                    <p class="text-xs text-slate-500 font-semibold">Total Permintaan: <span class="font-bold text-[#00264A]">{{ $permintaanTotal }} Tiket</span></p>
                </div>
                <span class="px-2.5 py-1 text-[10px] font-black rounded-lg bg-cyan-50 text-cyan-700 border border-cyan-200 uppercase">
                    {{ $permintaanSelesaiPct }}% SELESAI
                </span>
            </div>

            <!-- Status Breakdown Bar -->
            <div class="grid grid-cols-4 gap-2 mb-4 text-center">
                <div class="p-2.5 rounded-2xl bg-emerald-50 border border-emerald-100">
                    <p class="text-[9px] font-extrabold uppercase text-emerald-700">Selesai</p>
                    <p class="text-lg font-black text-emerald-700 mt-0.5">{{ $permintaanSelesai }}</p>
                    <p class="text-[10px] font-bold text-emerald-600">{{ $permintaanSelesaiPct }}%</p>
                </div>
                <div class="p-2.5 rounded-2xl bg-blue-50 border border-blue-100">
                    <p class="text-[9px] font-extrabold uppercase text-blue-700">Proses</p>
                    <p class="text-lg font-black text-blue-700 mt-0.5">{{ $permintaanProses }}</p>
                    <p class="text-[10px] font-bold text-blue-600">{{ $permintaanProsesPct }}%</p>
                </div>
                <div class="p-2.5 rounded-2xl bg-amber-50 border border-amber-100">
                    <p class="text-[9px] font-extrabold uppercase text-amber-700">Tertunda</p>
                    <p class="text-lg font-black text-amber-700 mt-0.5">{{ $permintaanTertunda }}</p>
                    <p class="text-[10px] font-bold text-amber-600">{{ $permintaanTertundaPct }}%</p>
                </div>
                <div class="p-2.5 rounded-2xl bg-rose-50 border border-rose-100">
                    <p class="text-[9px] font-extrabold uppercase text-rose-700">Dibatalkan</p>
                    <p class="text-lg font-black text-rose-700 mt-0.5">{{ $permintaanDibatalkan }}</p>
                    <p class="text-[10px] font-bold text-rose-600">{{ $permintaanDibatalkanPct }}%</p>
                </div>
            </div>

            <!-- Latest Active Tickets -->
            <div class="space-y-2">
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Tiket Aktif Terbaru</p>
                <div class="space-y-2">
                    @forelse($tiketTerbaru as $t)
                    <div class="p-2.5 rounded-xl border border-slate-100 bg-slate-50 flex items-center justify-between text-xs">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-[#00264A]">{{ $t->nomor }}</span>
                                <span class="px-1.5 py-0.2 rounded text-[9px] font-bold
                                    {{ $t->status === 'Selesai' ? 'bg-emerald-100 text-emerald-700' : ($t->status === 'Tertunda' ? 'bg-amber-100 text-amber-700' : ($t->status === 'Dibatalkan' ? 'bg-rose-100 text-rose-700' : 'bg-blue-100 text-blue-700')) }}">
                                    {{ $t->status }}
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-600 mt-0.5">{{ $t->jenis }} &bull; {{ $t->unit }}</p>
                        </div>
                        <span class="text-[10px] font-mono font-bold text-slate-400">{{ date('d M', strtotime($t->tanggal)) }}</span>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400">Belum ada tiket permintaan layanan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- ===== SECTION 3: JADWAL PEMELIHARAAN & STOK LOGISTIK ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <!-- Jadwal Pemeliharaan Minggu Ini (6 Cols) -->
        <div id="pemeliharaan" class="lg:col-span-6 bg-white rounded-3xl p-5 sm:p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">JADWAL PEMELIHARAAN MINGGU INI</h3>
                    <p class="text-xs text-slate-500 font-semibold">Preventive Maintenance & Servis Berkala Fasilitas</p>
                </div>
                <span class="px-2.5 py-1 text-[10px] font-black rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">
                    {{ count($pemeliharaans) }} AGENDA
                </span>
            </div>

            <div class="space-y-3">
                @forelse($pemeliharaans as $pm)
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between hover:bg-slate-100/60 transition-all">
                    <div class="flex items-center space-x-3">
                        <span class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs flex-shrink-0">
                            {{ date('d M', strtotime($pm->tanggal)) }}
                        </span>
                        <div>
                            <p class="font-black text-xs text-[#00264A]">{{ $pm->objek }}</p>
                            <p class="text-[11px] text-slate-500 font-medium">{{ $pm->jenis }} &bull; {{ $pm->pic }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 text-[9px] font-black uppercase rounded-full
                        {{ $pm->status === 'Selesai' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                        {{ $pm->status }}
                    </span>
                </div>
                @empty
                <p class="text-xs text-slate-400">Belum ada agenda pemeliharaan.</p>
                @endforelse
            </div>
        </div>

        <!-- Stok Logistik & ATK Kritis (6 Cols) -->
        <div id="pengadaan-logistik" class="lg:col-span-6 bg-white rounded-3xl p-5 sm:p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">STOK LOGISTIK & ATK KRITIS</h3>
                    <p class="text-xs text-slate-500 font-semibold">Monitoring Persediaan & Titik Kritis Pengadaan</p>
                </div>
                <span class="px-2.5 py-1 text-[10px] font-black rounded-lg bg-amber-50 text-amber-700 border border-amber-200 uppercase">
                    {{ $stokPerluPengadaan }} PERLU RE-ORDER
                </span>
            </div>

            <div class="space-y-3">
                @forelse($stokBarangs as $sb)
                @php
                    $isAman = in_array(strtolower($sb->status_stok), ['aman', 'cukup']);
                    $isWarning = in_array(strtolower($sb->status_stok), ['menipis']);
                    $boxColor = $isAman ? 'bg-emerald-50/50 border-emerald-200/80' : ($isWarning ? 'bg-amber-50/50 border-amber-200/80' : 'bg-rose-50/50 border-rose-200/80');
                    $badgeColor = $isAman ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : ($isWarning ? 'bg-amber-100 text-amber-800 border-amber-300' : 'bg-rose-100 text-rose-800 border-rose-300');
                    $badgeIcon = $isAman ? '✅' : '⚠️';
                @endphp
                <div class="p-3.5 rounded-2xl {{ $boxColor }} border flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-black text-xs text-[#00264A]">{{ $sb->nama }}</span>
                            <span class="text-xs font-mono font-bold text-slate-800">{{ $sb->jumlah }}</span>
                        </div>
                        <p class="text-[11px] text-slate-500 font-medium mt-0.5">{{ $sb->satuan }}</p>
                    </div>
                    <span class="px-2.5 py-1 text-[10px] font-black rounded-lg {{ $badgeColor }} border">
                        {{ $badgeIcon }} {{ $sb->status_stok }}
                    </span>
                </div>
                @empty
                <p class="text-xs text-slate-400">Belum ada data persediaan stok.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ===== SECTION 4: KEAMANAN & K3 DAN KEBERSIHAN & LINGKUNGAN ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <!-- Status Keamanan & K3 (6 Cols) -->
        <div id="keamanan-k3" class="lg:col-span-6 bg-white rounded-3xl p-5 sm:p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">STATUS KEAMANAN & K3</h3>
                    <p class="text-xs text-slate-500 font-semibold">Protokol Keselamatan & Pengamanan Kompleks TVRI</p>
                </div>
                <span class="px-2.5 py-1 text-[10px] font-black rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">
                    100% AMAN
                </span>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-[10px] font-extrabold uppercase text-slate-400">Pos Keamanan</p>
                    <p class="text-xl font-black text-[#00264A] mt-1">{{ $ringkasan->pos_keamanan ?? 3 }} Pos</p>
                    <p class="text-xs font-bold text-emerald-600 mt-0.5">100% Aktif 24 Jam</p>
                </div>
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-[10px] font-extrabold uppercase text-slate-400">Kamera CCTV</p>
                    <p class="text-xl font-black text-[#00264A] mt-1">{{ $ringkasan->cctv_total ?? 28 }} Titik</p>
                    <p class="text-xs font-bold text-blue-600 mt-0.5">{{ $ringkasan->cctv_aktif ?? 26 }} Aktif &bull; {{ $ringkasan->cctv_perbaikan ?? 2 }} Perbaikan</p>
                </div>
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-[10px] font-extrabold uppercase text-slate-400">APAR / Damkar</p>
                    <p class="text-xl font-black text-[#00264A] mt-1">{{ $ringkasan->apar_total ?? 32 }} Titik</p>
                    <p class="text-xs font-bold text-emerald-600 mt-0.5">100% Terverifikasi</p>
                </div>
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                    <p class="text-[10px] font-extrabold uppercase text-slate-400">Petugas Keamanan</p>
                    <p class="text-xl font-black text-[#00264A] mt-1">{{ $ringkasan->personel_security ?? 12 }} Personil</p>
                    <p class="text-xs font-bold text-emerald-600 mt-0.5">Hadir Lengkap (3 Shift)</p>
                </div>
            </div>
        </div>

        <!-- Kebersihan & Lingkungan (6 Cols) -->
        <div id="kebersihan-lingkungan" class="lg:col-span-6 bg-white rounded-3xl p-5 sm:p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">KEBERSIHAN & LINGKUNGAN</h3>
                    <p class="text-xs text-slate-500 font-semibold">Standar Higienitas, Sanitasi & Kenyamanan Ruang</p>
                </div>
                <span class="px-2.5 py-1 text-[10px] font-black rounded-lg bg-teal-50 text-teal-700 border border-teal-200 uppercase">
                    RATA-RATA: {{ round((($ringkasan->nilai_kebersihan_kantor ?? 94) + ($ringkasan->nilai_kebersihan_studio ?? 96) + ($ringkasan->nilai_kebersihan_halaman ?? 90) + ($ringkasan->nilai_kebersihan_toilet ?? 88)) / 4) }}
                </span>
            </div>

            <div class="space-y-3">
                <!-- Area Kantor -->
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-slate-700">Area Kantor</span>
                        <span class="font-mono text-teal-700 font-black">Bersih (Nilai: {{ $ringkasan->nilai_kebersihan_kantor ?? 94 }})</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-teal-500 h-2.5 rounded-full" style="width: {{ $ringkasan->nilai_kebersihan_kantor ?? 94 }}%"></div>
                    </div>
                </div>

                <!-- Area Studio -->
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-slate-700">Area Studio</span>
                        <span class="font-mono text-emerald-700 font-black">Bersih (Nilai: {{ $ringkasan->nilai_kebersihan_studio ?? 96 }})</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-emerald-500 h-2.5 rounded-full" style="width: {{ $ringkasan->nilai_kebersihan_studio ?? 96 }}%"></div>
                    </div>
                </div>

                <!-- Halaman & Parkir -->
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-slate-700">Halaman & Parkir</span>
                        <span class="font-mono text-cyan-700 font-black">Bersih (Nilai: {{ $ringkasan->nilai_kebersihan_halaman ?? 90 }})</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-cyan-500 h-2.5 rounded-full" style="width: {{ $ringkasan->nilai_kebersihan_halaman ?? 90 }}%"></div>
                    </div>
                </div>

                <!-- Toilet & Sanitasi -->
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-slate-700">Toilet & Sanitasi</span>
                        <span class="font-mono text-blue-700 font-black">Bersih (Nilai: {{ $ringkasan->nilai_kebersihan_toilet ?? 88 }})</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-blue-500 h-2.5 rounded-full" style="width: {{ $ringkasan->nilai_kebersihan_toilet ?? 88 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== SECTION 5: ANGGARAN OPERASIONAL & NOTIFIKASI PERINGATAN ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <!-- Anggaran Operasional Umum (6 Cols) -->
        <div id="laporan" class="lg:col-span-6 bg-white rounded-3xl p-5 sm:p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">ANGGARAN OPERASIONAL UMUM</h3>
                    <p class="text-xs text-slate-500 font-semibold">Realisasi s/d {{ $ringkasan->periode ?? 'September 2025' }}</p>
                </div>
                <span class="px-2.5 py-1 text-[10px] font-black rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">
                    {{ $ringkasan->anggaran_persen }}% TERSERAP
                </span>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-3 gap-2.5 text-center">
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <p class="text-[10px] font-bold uppercase text-slate-400">Total Pagu</p>
                        <p class="text-xs sm:text-sm font-black text-[#00264A] mt-1 font-mono">Rp {{ number_format($ringkasan->anggaran_pagu / 1e6, 0, ',', '.') }} M</p>
                    </div>
                    <div class="p-3 rounded-2xl bg-emerald-50 border border-emerald-100">
                        <p class="text-[10px] font-bold uppercase text-emerald-700">Realisasi ({{ $ringkasan->anggaran_persen }}%)</p>
                        <p class="text-xs sm:text-sm font-black text-emerald-700 mt-1 font-mono">Rp {{ number_format($ringkasan->anggaran_terpakai / 1e6, 1, ',', '.') }} M</p>
                    </div>
                    <div class="p-3 rounded-2xl bg-blue-50 border border-blue-100">
                        <p class="text-[10px] font-bold uppercase text-blue-700">Sisa Pagu ({{ 100 - (int)$ringkasan->anggaran_persen }}%)</p>
                        <p class="text-xs sm:text-sm font-black text-blue-700 mt-1 font-mono">Rp {{ number_format($ringkasan->anggaran_sisa / 1e6, 1, ',', '.') }} Jt</p>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-slate-600">Progres Penyerapan Anggaran</span>
                        <span class="font-mono text-emerald-600">{{ $ringkasan->anggaran_persen }}% (On Track)</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-3 rounded-full" style="width: {{ $ringkasan->anggaran_persen }}%"></div>
                    </div>
                </div>

                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-600 space-y-1">
                    <div class="flex justify-between">
                        <span>Pagu Anggaran Tahun 2025:</span>
                        <span class="font-mono font-bold text-slate-800">Rp {{ number_format($ringkasan->anggaran_pagu) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Realisasi Belanja s/d Sept:</span>
                        <span class="font-mono font-bold text-emerald-700">Rp {{ number_format($ringkasan->anggaran_terpakai) }}</span>
                    </div>
                    <div class="flex justify-between border-t border-slate-200 pt-1">
                        <span>Sisa Anggaran Tersedia:</span>
                        <span class="font-mono font-bold text-blue-700">Rp {{ number_format($ringkasan->anggaran_sisa) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifikasi & Peringatan Penting (6 Cols) -->
        <div id="layanan-umum" class="lg:col-span-6 bg-white rounded-3xl p-5 sm:p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">NOTIFIKASI & PERINGATAN PENTING</h3>
                    <p class="text-xs text-slate-500 font-semibold">Tindak Cepat & Peringatan Dini Operasional</p>
                </div>
                <span class="px-2.5 py-1 text-[10px] font-black rounded-lg bg-rose-50 text-rose-700 border border-rose-200 uppercase">
                    ALERT SYSTEM
                </span>
            </div>

            <div class="space-y-2.5">
                <div class="p-3 rounded-2xl bg-rose-50/70 border border-rose-200 flex items-start space-x-3">
                    <span class="text-base flex-shrink-0 mt-0.5">⚠️</span>
                    <div class="text-xs">
                        <p class="font-bold text-rose-900">Stok Solar Genset di bawah batas minimal</p>
                        <p class="text-rose-700 text-[11px] mt-0.5">Volume saat ini 850L (Batas aman: 1.000L). Perlu segera proses PO BBM.</p>
                    </div>
                </div>

                <div class="p-3 rounded-2xl bg-amber-50/70 border border-amber-200 flex items-start space-x-3">
                    <span class="text-base flex-shrink-0 mt-0.5">⚠️</span>
                    <div class="text-xs">
                        <p class="font-bold text-amber-900">Uji Berkala APAR berakhir dalam 15 hari</p>
                        <p class="text-amber-700 text-[11px] mt-0.5">Koordinasikan dengan dinas damkar setempat untuk sertifikasi ulang 32 titik APAR.</p>
                    </div>
                </div>

                <div class="p-3 rounded-2xl bg-amber-50/70 border border-amber-200 flex items-start space-x-3">
                    <span class="text-base flex-shrink-0 mt-0.5">⚠️</span>
                    <div class="text-xs">
                        <p class="font-bold text-amber-900">2 Kendaraan Dinas jatuh tempo pajak bulan ini</p>
                        <p class="text-amber-700 text-[11px] mt-0.5">Kendaraan DE 1003 AM & DE 5002 AM memerlukan perpanjangan STNK Samsat.</p>
                    </div>
                </div>

                <div class="p-3 rounded-2xl bg-blue-50/70 border border-blue-200 flex items-start space-x-3">
                    <span class="text-base flex-shrink-0 mt-0.5">ℹ️</span>
                    <div class="text-xs">
                        <p class="font-bold text-blue-900">Laporan Aset BMN Triwulan III siap untuk ditinjau</p>
                        <p class="text-blue-700 text-[11px] mt-0.5">Dokumen rekapitulasi inventarisasi BMN siap diserahkan kepada Kepala Stasiun.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== SECTION 6: AGENDA KEGIATAN & KONTAK CEPAT PIC ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <!-- Agenda & Kegiatan Umum Minggu Ini (6 Cols) -->
        <div id="kalender-kegiatan" class="lg:col-span-6 bg-white rounded-3xl p-5 sm:p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">AGENDA & KEGIATAN UMUM MINGGU INI</h3>
                    <p class="text-xs text-slate-500 font-semibold">Jadwal Koordinasi, Pelatihan & Kerja Bakti</p>
                </div>
                <span class="px-2.5 py-1 text-[10px] font-black rounded-lg bg-blue-50 text-blue-700 border border-blue-200 uppercase">
                    MINGGU I SEP
                </span>
            </div>

            <div class="space-y-3">
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-start space-x-3">
                    <span class="px-2.5 py-1.5 rounded-xl bg-blue-100 text-blue-800 text-xs font-mono font-bold flex-shrink-0">
                        03 Sep &bull; 09:00
                    </span>
                    <div>
                        <p class="font-black text-xs text-[#00264A]">Pengadaan ATK Triwulan IV (Rapat Evaluasi)</p>
                        <p class="text-[11px] text-slate-500 font-medium">PIC: Tim Pengadaan &bull; Ruang Rapat Pimpinan</p>
                    </div>
                </div>

                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-start space-x-3">
                    <span class="px-2.5 py-1.5 rounded-xl bg-purple-100 text-purple-800 text-xs font-mono font-bold flex-shrink-0">
                        04 Sep &bull; 14:00
                    </span>
                    <div>
                        <p class="font-black text-xs text-[#00264A]">Simulasi Kebakaran & Evakuasi Bencana</p>
                        <p class="text-[11px] text-slate-500 font-medium">Peserta: Seluruh Karyawan TVRI Stasiun Maluku</p>
                    </div>
                </div>

                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-start space-x-3">
                    <span class="px-2.5 py-1.5 rounded-xl bg-emerald-100 text-emerald-800 text-xs font-mono font-bold flex-shrink-0">
                        05 Sep &bull; 08:00
                    </span>
                    <div>
                        <p class="font-black text-xs text-[#00264A]">Kerja Bakti Lingkungan Kantor & Halaman</p>
                        <p class="text-[11px] text-slate-500 font-medium">Peserta: Seluruh Staf & Tenaga Outsourcing TVRI Maluku</p>
                    </div>
                </div>

                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-start space-x-3">
                    <span class="px-2.5 py-1.5 rounded-xl bg-amber-100 text-amber-800 text-xs font-mono font-bold flex-shrink-0">
                        06 Sep &bull; 10:00
                    </span>
                    <div>
                        <p class="font-black text-xs text-[#00264A]">Koordinasi Pengamanan Acara Khusus Siaran Langsung</p>
                        <p class="text-[11px] text-slate-500 font-medium">Mitra: Polsek Nusaniwe & Babinsa</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kontak Cepat / PIC Layanan Umum (6 Cols) -->
        <div id="kontak-vendor" class="lg:col-span-6 bg-white rounded-3xl p-5 sm:p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">KONTAK CEPAT / PIC LAYANAN UMUM</h3>
                    <p class="text-xs text-slate-500 font-semibold">Saluran Bantuan Operasional, Darurat & Vendor</p>
                </div>
                <span class="px-2.5 py-1 text-[10px] font-black rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">
                    HOTLINE 24 JAM
                </span>
            </div>

            <div class="space-y-2.5">
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-[#00264A]">Ka. Subbag Umum</p>
                        <p class="text-[11px] text-slate-600 font-semibold">Bpk. J. Wattimena</p>
                    </div>
                    <span class="px-2.5 py-1 text-xs font-mono font-bold rounded-lg bg-white border border-slate-200 text-slate-700">
                        Ext. 104 / 0812-XXXX-XXXX
                    </span>
                </div>

                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-[#00264A]">Koordinator Keamanan & K3</p>
                        <p class="text-[11px] text-slate-600 font-semibold">Bpk. M. Latuconsina</p>
                    </div>
                    <span class="px-2.5 py-1 text-xs font-mono font-bold rounded-lg bg-white border border-slate-200 text-slate-700">
                        Ext. 108 / 0813-XXXX-XXXX
                    </span>
                </div>

                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-[#00264A]">Koordinator Sarpras & Aset</p>
                        <p class="text-[11px] text-slate-600 font-semibold">Bpk. R. Sahureka</p>
                    </div>
                    <span class="px-2.5 py-1 text-xs font-mono font-bold rounded-lg bg-white border border-slate-200 text-slate-700">
                        Ext. 110 / 0821-XXXX-XXXX
                    </span>
                </div>

                <div class="p-3.5 rounded-2xl bg-[#001D38] text-white flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-amber-300 uppercase tracking-wider">Call Center Layanan Darurat</p>
                        <p class="text-[11px] text-blue-200">Siaga 24 Jam Stasiun TVRI Maluku</p>
                    </div>
                    <span class="px-3 py-1.5 text-xs font-mono font-black rounded-xl bg-red-600 text-white shadow">
                        (0911) 341851
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== FOOTER MOTTO ===== -->
    <div class="bg-gradient-to-r from-[#001D38] via-[#00264A] to-[#003B70] rounded-3xl p-6 text-white text-center shadow-lg border border-blue-400/20">
        <p class="text-xs sm:text-sm font-black tracking-widest uppercase text-emerald-300">
            KONTEN BERKUALITAS, MENGANGKAT POTENSI MALUKU, UNTUK INDONESIA
        </p>
        <p class="text-xs text-blue-200 mt-1 font-semibold italic">
            Dari Maluku untuk Indonesia
        </p>
    </div>

</div>
@endsection
