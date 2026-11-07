@extends('layouts.app')

@section('title', 'Data Laporan - Admin TU')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-sm text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-[#005B9F] transition-colors">Utama</a>
                <span>/</span>
                <span class="text-slate-700 font-medium">Layanan Tata Usaha</span>
            </div>
            <h1 class="text-2xl font-black text-[#00264A] tracking-tight">Manajemen Data Laporan</h1>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin-tu.laporan.create') }}" class="bg-[#003B70] hover:bg-[#00264A] text-white px-4 py-2 rounded-xl text-sm font-bold transition-all shadow-md hover:shadow-lg flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Buat Laporan Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
        <form action="{{ route('admin-tu.laporan') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-[#003B70] focus:border-[#003B70]" placeholder="Cari nomor, judul, pembuat...">
            </div>
            
            <div class="w-full md:w-48">
                <select name="kategori_laporan" class="block w-full py-2 px-3 border border-slate-200 bg-white rounded-xl text-sm focus:ring-[#003B70] focus:border-[#003B70]">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriOptions as $opt)
                        <option value="{{ $opt }}" {{ request('kategori_laporan') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="w-full md:w-48">
                <select name="status" class="block w-full py-2 px-3 border border-slate-200 bg-white rounded-xl text-sm focus:ring-[#003B70] focus:border-[#003B70]">
                    <option value="">Semua Status</option>
                    @foreach($statusOptions as $opt)
                        <option value="{{ $opt }}" {{ request('status') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex space-x-2">
                <button type="submit" class="bg-blue-50 text-[#005B9F] hover:bg-blue-100 px-4 py-2 rounded-xl text-sm font-bold transition-colors border border-blue-200">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'kategori_laporan', 'status']))
                    <a href="{{ route('admin-tu.laporan') }}" class="bg-slate-50 text-slate-500 hover:bg-slate-100 px-4 py-2 rounded-xl text-sm font-bold transition-colors border border-slate-200">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center justify-between" role="alert">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold">&times;</button>
        </div>
    @endif

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-[#00264A]">Daftar Dokumen Laporan</h3>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 font-semibold">
                {{ $laporanList->total() }} Data
            </span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 text-[10px] uppercase tracking-wider text-slate-500 font-bold border-b border-slate-100">
                        <th class="py-3.5 px-4">No. Laporan & Tanggal</th>
                        <th class="py-3.5 px-4">Kategori & Judul</th>
                        <th class="py-3.5 px-4">Pembuat & Wilayah</th>
                        <th class="py-3.5 px-4">Status & Keterangan</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    @forelse($laporanList as $laporan)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 px-4">
                                <div class="font-bold text-[#00264A] text-xs">{{ $laporan->nomor_laporan }}</div>
                                <div class="text-[10px] text-slate-400 font-medium mt-0.5">{{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->translatedFormat('d F Y') }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-200 mb-1">
                                    {{ $laporan->kategori_laporan }}
                                </span>
                                <div class="font-bold text-slate-700 text-xs">{{ $laporan->judul_laporan }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-xs font-bold text-slate-700">{{ $laporan->pembuat_laporan ?? '-' }}</div>
                                <div class="text-[10px] text-slate-500 mt-0.5">{{ $laporan->wilayah ?? '-' }}</div>
                            </td>
                            <td class="py-3 px-4">
                                @php
                                    $statusColor = match($laporan->status) {
                                        'Draft' => 'bg-slate-100 text-slate-600 border-slate-200',
                                        'Terkirim' => 'bg-blue-50 text-blue-600 border-blue-200',
                                        'Selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                        'Revisi' => 'bg-rose-50 text-rose-600 border-rose-200',
                                        default => 'bg-slate-100 text-slate-600 border-slate-200'
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $statusColor }}">
                                    {{ $laporan->status }}
                                </span>
                                @if($laporan->keterangan)
                                    <div class="text-[10px] text-slate-400 mt-1 truncate max-w-[150px]">{{ $laporan->keterangan }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin-tu.laporan.show', $laporan->id) }}" class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin-tu.laporan.edit', $laporan->id) }}" class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin-tu.laporan.destroy', $laporan->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 text-sm">
                                <svg class="w-12 h-12 mx-auto mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                                Tidak ada data laporan yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($laporanList->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $laporanList->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
