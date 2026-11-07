@extends('layouts.app')
@section('title', 'Sistem Informasi Dokumen Layanan')

@section('content')
<div class="space-y-6">

    <!-- Header & Welcome Banner -->
    <div class="bg-gradient-to-r from-[#00264A] via-[#003B70] to-[#005B9F] rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 w-96 h-96 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <span class="px-3 py-1 bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 rounded-full text-xs font-bold tracking-wider uppercase">
                        Manajemen Dokumen
                    </span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight text-white">
                    Sistem Informasi Dokumen Layanan
                </h1>
                <p class="text-sm text-blue-100 max-w-2xl mt-1">
                    Pusat penyimpanan, pencarian, dan pengelolaan dokumen resmi Layanan Tata Usaha dan Umum.
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('admin-tu.dokumen-layanan.create') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-lg transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>+ Upload Dokumen Baru</span>
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
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Total -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Dokumen</p>
                <p class="text-2xl font-black text-[#00264A] mt-1">{{ number_format($totalDokumen, 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#003B70] flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>
        
        <!-- Kepegawaian -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Kepegawaian</p>
                <p class="text-2xl font-black text-emerald-600 mt-1">{{ number_format($totalKepegawaian, 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        <!-- Surat -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Surat (Masuk/Keluar)</p>
                <p class="text-2xl font-black text-amber-600 mt-1">{{ number_format($totalSurat, 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
        <form method="GET" action="{{ route('admin-tu.dokumen-layanan.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div class="md:col-span-2 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama dokumen, keterangan, atau petugas..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            <div>
                <select name="kategori" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $kat)
                        <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center space-x-2">
                <button type="submit" class="flex-1 py-2 px-3 bg-[#003B70] hover:bg-[#00264A] text-white text-xs font-bold rounded-xl transition-colors">
                    Filter
                </button>
                <a href="{{ route('admin-tu.dokumen-layanan.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-4">Dokumen & Kategori</th>
                        <th class="px-4 py-4">Informasi File</th>
                        <th class="px-4 py-4">Tanggal & Petugas</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($documents as $doc)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="px-5 py-4">
                                <p class="font-bold text-slate-800">{{ $doc->nama_dokumen }}</p>
                                <span class="mt-1 inline-block px-2.5 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-bold rounded-md border border-blue-100">
                                    {{ $doc->kategori }}
                                </span>
                                @if($doc->keterangan)
                                    <p class="text-[10px] text-slate-500 mt-1 line-clamp-2 max-w-xs">{{ $doc->keterangan }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-mono text-xs font-semibold uppercase text-slate-700">{{ $doc->file_type }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ number_format($doc->file_size / 1024, 2) }} KB</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-semibold text-slate-700">{{ $doc->created_at->format('d M Y, H:i') }}</p>
                                <p class="text-[10px] text-slate-500 mt-0.5">{{ $doc->petugas_nama }}</p>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-1.5">
                                    <a href="{{ route('admin-tu.dokumen-layanan.download', $doc->id) }}" class="p-1.5 text-emerald-600 hover:bg-emerald-100 rounded-lg transition-colors" title="Unduh File">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    </a>

                                    <form action="{{ route('admin-tu.dokumen-layanan.destroy', $doc->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-100 rounded-lg transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-xs text-slate-500">
                                <div class="max-w-xs mx-auto text-center space-y-3">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <p class="text-sm font-bold text-slate-700">Belum ada dokumen</p>
                                    <p class="text-xs text-slate-400">Silakan unggah dokumen pertama Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($documents->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $documents->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
