@extends('layouts.app')

@section('title', 'Detail Laporan - Admin TU')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin-tu.laporan') }}" class="p-2 bg-white rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors">
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-[#00264A] tracking-tight">Detail Laporan</h1>
                <p class="text-sm text-slate-500 mt-0.5">Informasi lengkap data laporan</p>
            </div>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin-tu.laporan.edit', $laporan->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-xl text-sm font-bold transition-all shadow-md flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <span>Edit</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 md:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                <div class="md:col-span-2 border-b border-slate-100 pb-6 mb-2">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-600 border border-amber-200 mb-3">
                        {{ $laporan->kategori_laporan }}
                    </span>
                    <h2 class="text-2xl font-black text-slate-800">{{ $laporan->judul_laporan }}</h2>
                    <div class="flex items-center text-sm text-slate-500 mt-2 space-x-4">
                        <span class="flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                            <span>{{ $laporan->nomor_laporan }}</span>
                        </span>
                        <span class="flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span>{{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->translatedFormat('d F Y') }}</span>
                        </span>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Pembuat Laporan</label>
                    <p class="text-sm font-bold text-slate-800">{{ $laporan->pembuat_laporan ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Wilayah / Satuan Kerja</label>
                    <p class="text-sm font-bold text-slate-800">{{ $laporan->wilayah ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status Laporan</label>
                    @php
                        $statusColor = match($laporan->status) {
                            'Draft' => 'bg-slate-100 text-slate-600 border-slate-200',
                            'Terkirim' => 'bg-blue-50 text-blue-600 border-blue-200',
                            'Selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                            'Revisi' => 'bg-rose-50 text-rose-600 border-rose-200',
                            default => 'bg-slate-100 text-slate-600 border-slate-200'
                        };
                    @endphp
                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $statusColor }}">
                        {{ $laporan->status }}
                    </span>
                </div>
                
                <div class="md:col-span-2 mt-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Keterangan Tambahan</label>
                    <p class="text-sm text-slate-700 leading-relaxed">{{ $laporan->keterangan ?: 'Tidak ada keterangan tambahan.' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
