@extends('layouts.app')

@section('title', 'Dashboard Verifikasi SLB - TVRI Maluku')

@section('content')
<div class="space-y-6">

    <!-- Welcome Hero Banner -->
    <div class="bg-gradient-to-r from-[#003B70] via-[#005B9F] to-[#00264A] rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="inline-flex items-center space-x-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-[11px] font-bold text-blue-200 uppercase tracking-widest border border-white/10 mb-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Bidang Pembinaan SLB &bull; Maluku</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Dashboard Verifikasi Kepala Stasiun
                </h1>
                <p class="text-xs sm:text-sm text-blue-100 font-medium mt-1 max-w-2xl">
                    TINDAK LANJUT & VERIFIKASI PERMOHONAN LAYANAN PENDIDIKAN KHUSUS (SLB)
                </p>
            </div>
            
            <div class="flex items-center space-x-3">
                <div class="bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-white/15 text-right">
                    <p class="text-[10px] text-blue-200 font-bold uppercase tracking-wider">Tanggal Sistem</p>
                    <p class="text-xs font-bold text-white">{{ date('d F Y') }}</p>
                </div>
                <a href="{{ route('pendidikan.slb.layanan.create') }}" class="hidden sm:inline-flex items-center space-x-2 px-3.5 py-2.5 bg-emerald-600 hover:bg-emerald-500 rounded-2xl text-xs font-bold text-white transition shadow-lg shadow-emerald-900/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Ajukan Layanan</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 4 Main Real Metric Cards (Database-driven) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        
        <!-- 1. Kelembagaan & Perizinan -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Kelembagaan & Perizinan</p>
                    <h3 class="text-2xl font-black text-[#00264A] mt-1">{{ number_format($stats['pendirian']) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#003B70] flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 font-medium">Rekomendasi Pendirian SLB</p>
        </div>

        <!-- 2. Kesiswaan & Mutasi -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Kesiswaan & Mutasi</p>
                    <h3 class="text-2xl font-black text-[#00264A] mt-1">{{ number_format($stats['mutasi']) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-sky-50 text-[#005B9F] flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 font-medium">Perpindahan Siswa Antar Wilayah</p>
        </div>

        <!-- 3. Pemerataan & Pendanaan -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pemerataan & Pendanaan</p>
                    <h3 class="text-2xl font-black text-emerald-700 mt-1">{{ number_format($stats['bosp']) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 font-medium">Bantuan Operasional SLB</p>
        </div>

        <!-- 4. Dokumen & Legalitas -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Dokumen & Legalitas</p>
                    <h3 class="text-2xl font-black text-amber-700 mt-1">{{ number_format($stats['legalisir']) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 font-medium">Legalisir Ijazah & SKL</p>
        </div>

    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Data Table -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-extrabold text-[#00264A]">Direktori Layanan Terbaru</h3>
                    <a href="#" class="text-xs font-bold text-[#005B9F] hover:underline">Lihat Semua &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-[10px] uppercase tracking-wider text-slate-500">
                                <th class="py-3 px-3 font-bold rounded-tl-xl">Nomor Registrasi</th>
                                <th class="py-3 px-3 font-bold">Pemohon</th>
                                <th class="py-3 px-3 font-bold">Status</th>
                                <th class="py-3 px-3 font-bold text-center rounded-tr-xl">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs divide-y divide-slate-100">
                            @forelse($layananList as $item)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="py-3 px-3">
                                        <div class="font-bold text-[#003B70]">{{ $item->nomor_registrasi }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $item->created_at ? $item->created_at->format('d M Y') : date('d M Y') }}</div>
                                    </td>
                                    <td class="py-3 px-3">
                                        <div class="font-semibold text-slate-700">{{ $item->nama_pemohon }}</div>
                                        <div class="text-[10px] text-slate-500">{{ $item->nama_satuan_pendidikan }}</div>
                                    </td>
                                    <td class="py-3 px-3">
                                        @php $st = $item->status_meta; @endphp
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $st['bg'] }} {{ $st['text'] }} {{ $st['border'] }}">
                                            {{ $st['label'] }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <a href="{{ route('pendidikan.slb.layanan.edit', $item->id) }}" class="text-[#005B9F] hover:underline font-semibold text-[11px] bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100">Proses Verifikasi &rarr;</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-8 text-center text-slate-400 italic">Belum ada pengajuan layanan</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Status & Progress -->
        <div class="space-y-6">
            <!-- Progress Status -->
            <div class="bg-gradient-to-br from-[#003B70] to-[#001D38] rounded-2xl p-5 border border-[#004B8D] shadow-lg text-white">
                <h3 class="text-xs font-bold uppercase tracking-wider text-blue-200 mb-4">Statistik Proses Layanan</h3>
                
                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-semibold">Selesai Diproses</span>
                            <span class="font-bold text-emerald-400">{{ $stats['selesai'] }}</span>
                        </div>
                        <div class="w-full bg-[#00264A] rounded-full h-1.5">
                            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $stats['total'] > 0 ? ($stats['selesai'] / $stats['total']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-semibold">Dalam Verifikasi</span>
                            <span class="font-bold text-amber-400">{{ $stats['proses'] }}</span>
                        </div>
                        <div class="w-full bg-[#00264A] rounded-full h-1.5">
                            <div class="bg-amber-500 h-1.5 rounded-full" style="width: {{ $stats['total'] > 0 ? ($stats['proses'] / $stats['total']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-white/10">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] text-blue-200">Total Pengajuan</span>
                        <span class="text-sm font-black">{{ $stats['total'] }} Dokumen</span>
                    </div>
                </div>
            </div>
            
        </div>

    </div>

</div>
@endsection
