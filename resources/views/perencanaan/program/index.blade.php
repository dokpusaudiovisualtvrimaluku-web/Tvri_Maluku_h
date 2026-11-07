@extends('layouts.app')

@section('title', 'Program & Perencanaan Pembangunan - TVRI Maluku')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-[#00264A] via-[#003B70] to-[#005B9F] rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 w-96 h-96 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <span class="px-3 py-1 bg-purple-400/20 text-purple-200 border border-purple-400/40 rounded-full text-xs font-bold tracking-wider uppercase">
                        Sistem Perencanaan Terpadu
                    </span>
                    <span class="text-xs text-blue-200">TVRI Maluku</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight text-white">
                    Program & Perencanaan Pembangunan
                </h1>
                <p class="text-sm text-blue-100 max-w-3xl mt-1 leading-relaxed">
                    Pusat data dokumen perencanaan, program prioritas, kegiatan strategis, Renstra, Renja, RKA, dan DPA seluruh bidang di lingkungan TVRI Maluku.
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('perencanaan.program.export-csv', request()->query()) }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-xl border border-white/20 backdrop-blur-md transition-all duration-200">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Export CSV</span>
                </a>
                <a href="{{ route('perencanaan.program.create') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold rounded-xl shadow-lg hover:shadow-purple-600/30 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>+ Tambah Dokumen Perencanaan</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold">&times;</button>
        </div>
    @endif

    <!-- 4 Financial & Performance Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Pagu Anggaran -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Pagu Anggaran</p>
                <p class="text-xl font-black text-[#00264A] mt-1">Rp {{ number_format($stats['total_pagu'], 0, ',', '.') }}</p>
                <p class="text-[10px] text-slate-500 mt-0.5">Alokasi Program & Kegiatan</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Realisasi Serapan -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Realisasi Keuangan</p>
                <p class="text-xl font-black text-emerald-600 mt-1">Rp {{ number_format($stats['total_realisasi'], 0, ',', '.') }}</p>
                <p class="text-[10px] text-emerald-600 font-bold mt-0.5">Serapan {{ $stats['serapan_persen'] }}%</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
            </div>
        </div>

        <!-- Total Program & Kegiatan -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Program & Kegiatan</p>
                <p class="text-xl font-black text-purple-700 mt-1">{{ $stats['count_program'] }} <span class="text-xs font-normal text-slate-500">Prg</span> / {{ $stats['count_kegiatan'] }} <span class="text-xs font-normal text-slate-500">Kgt</span></p>
                <p class="text-[10px] text-purple-600 font-semibold mt-0.5">{{ $stats['selesai'] }} Selesai &bull; {{ $stats['berjalan'] }} Berjalan</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
        </div>

        <!-- Dokumen Renstra / Renja / RKA / DPA -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Dokumen Perencanaan</p>
                <p class="text-xl font-black text-indigo-700 mt-1">{{ $stats['count_renstra'] + $stats['count_renja'] + $stats['count_rka'] + $stats['count_dpa'] }} <span class="text-xs font-normal text-slate-500">Dokumen</span></p>
                <p class="text-[10px] text-slate-500 mt-0.5">Renstra, Renja, RKA & DPA</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Category Tabs Navigation -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1">
        <a href="{{ route('perencanaan.program.index') }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ !request('jenis_dokumen') ? 'bg-[#00264A] text-white shadow' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Semua Dokumen ({{ $stats['total_dokumen'] }})
        </a>
        @foreach($jenisDokumenOptions as $key => $meta)
            @php
                $countKey = 'count_' . strtolower($key);
                $cnt = $stats[$countKey] ?? 0;
            @endphp
            <a href="{{ route('perencanaan.program.index', ['jenis_dokumen' => $key]) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ request('jenis_dokumen') == $key ? 'bg-[#00264A] text-white shadow' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                {{ $key }} ({{ $cnt }})
            </a>
        @endforeach
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('perencanaan.program.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
            <!-- Search Input -->
            <div class="lg:col-span-2">
                <label class="block text-xs font-bold text-slate-600 mb-1">Cari Program / Kegiatan</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Nama Program, Kode Rekening, Penanggung Jawab..."
                           class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <!-- Bidang Penanggung Jawab -->
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Bidang / Unit Kerja</label>
                <select name="bidang_penanggung_jawab" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none">
                    <option value="">-- Semua Bidang --</option>
                    @foreach($bidangOptions as $key => $label)
                        <option value="{{ $key }}" {{ request('bidang_penanggung_jawab') == $key ? 'selected' : '' }}>{{ $key }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tahun Anggaran -->
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Tahun Anggaran</label>
                <select name="tahun_anggaran" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none">
                    <option value="">-- Semua Tahun --</option>
                    @foreach([2026, 2025, 2024, 2023] as $yr)
                        <option value="{{ $yr }}" {{ request('tahun_anggaran') == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Pelaksanaan -->
            <div class="flex items-center gap-2">
                <div class="flex-1">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Status</label>
                    <select name="status_pelaksanaan" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none">
                        <option value="">-- Semua --</option>
                        @foreach($statusOptions as $k => $st)
                            <option value="{{ $k }}" {{ request('status_pelaksanaan') == $k ? 'selected' : '' }}>{{ $st['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-transparent mb-1">Aksi</label>
                    <div class="flex items-center gap-1">
                        <button type="submit" class="px-3.5 py-2 bg-[#00264A] hover:bg-[#003B70] text-white text-xs font-bold rounded-xl shadow transition-colors">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'jenis_dokumen', 'bidang_penanggung_jawab', 'tahun_anggaran', 'status_pelaksanaan']))
                            <a href="{{ route('perencanaan.program.index') }}" class="px-2.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition-colors" title="Reset Filter">
                                ↺
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <h3 class="text-sm font-bold text-[#00264A]">Pangkalan Data Program & Perencanaan</h3>
                <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 font-semibold">
                    {{ $programList->total() }} Data Terdaftar
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-600 font-semibold tracking-wider uppercase text-[10px]">
                        <th class="py-3.5 px-4">No. Registrasi & Dokumen</th>
                        <th class="py-3.5 px-4">Program / Kegiatan</th>
                        <th class="py-3.5 px-4">Bidang & Penanggung Jawab</th>
                        <th class="py-3.5 px-4 text-right">Pagu Anggaran</th>
                        <th class="py-3.5 px-4 text-right">Realisasi & Serapan</th>
                        <th class="py-3.5 px-4">Target Kinerja</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($programList as $item)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <!-- No Reg & Dokumen -->
                            <td class="py-3 px-4">
                                <span class="font-mono font-bold text-[#00264A] text-xs block">{{ $item->nomor_registrasi }}</span>
                                @php $meta = $item->dokumen_meta; @endphp
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $meta['badge'] }} inline-block mt-1">
                                    {{ $item->jenis_dokumen }}
                                </span>
                                @if($item->kode_rekening)
                                    <span class="font-mono text-[10px] text-slate-400 block mt-0.5">{{ $item->kode_rekening }}</span>
                                @endif
                            </td>

                            <!-- Nama Program / Kegiatan -->
                            <td class="py-3 px-4 max-w-xs">
                                <div class="font-bold text-slate-800 text-xs">{{ $item->nama_program_kegiatan }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5 line-clamp-1">
                                    {{ $item->uraian_kegiatan }}
                                </div>
                                <div class="text-[10px] text-slate-400 mt-0.5">
                                    TA {{ $item->tahun_anggaran }} &bull; {{ $item->periode_pelaksanaan ?? 'Tahunan' }}
                                </div>
                            </td>

                            <!-- Bidang -->
                            <td class="py-3 px-4">
                                <div class="font-semibold text-slate-700 text-xs">{{ $item->bidang_penanggung_jawab }}</div>
                                @if($item->pejabat_penanggung_jawab)
                                    <div class="text-[10px] text-slate-500 mt-0.5">
                                        PJ: {{ $item->pejabat_penanggung_jawab }}
                                    </div>
                                @endif
                            </td>

                            <!-- Pagu Anggaran -->
                            <td class="py-3 px-4 text-right">
                                <span class="font-bold text-slate-800 font-mono">{{ $item->formatted_pagu }}</span>
                            </td>

                            <!-- Realisasi & Serapan -->
                            <td class="py-3 px-4 text-right">
                                <span class="font-bold text-emerald-600 font-mono block">{{ $item->formatted_realisasi }}</span>
                                <div class="flex items-center justify-end space-x-1 mt-1">
                                    <div class="w-16 bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ min(100, $item->persentase_capaian) }}%"></div>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-600">{{ $item->persentase_capaian }}%</span>
                                </div>
                            </td>

                            <!-- Target Kinerja -->
                            <td class="py-3 px-4">
                                <div class="text-xs text-slate-700 font-semibold">{{ $item->target_kinerja ?? '-' }}</div>
                                @if($item->realisasi_kinerja)
                                    <div class="text-[10px] text-emerald-700 mt-0.5">Capai: {{ $item->realisasi_kinerja }}</div>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="py-3 px-4 text-center">
                                @php $st = $item->status_meta; @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $st['bg'] }} {{ $st['text'] }} {{ $st['border'] }} inline-block">
                                    {{ $st['label'] }}
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center space-x-1">
                                    <a href="{{ route('perencanaan.program.show', $item->id) }}" 
                                       class="p-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition-colors" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('perencanaan.program.edit', $item->id) }}" 
                                       class="p-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg transition-colors" title="Edit Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('perencanaan.program.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data dokumen perencanaan {{ $item->nomor_registrasi }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg transition-colors" title="Hapus Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    <p class="text-sm font-semibold">Belum ada data dokumen perencanaan / program</p>
                                    <p class="text-xs text-slate-400">Silakan tambahkan data dokumen baru atau sesuaikan filter pencarian.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($programList->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $programList->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
