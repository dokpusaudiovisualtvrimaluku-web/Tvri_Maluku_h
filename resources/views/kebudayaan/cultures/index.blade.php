@extends('layouts.app')

@section('title', 'Pangkalan Data Kebudayaan Provinsi Maluku')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div>
            <div class="flex items-center space-x-3">
                <span class="p-2.5 bg-amber-50 text-amber-600 rounded-xl">
                    <i class="fas fa-landmark text-xl"></i>
                </span>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Pangkalan Data Objek Kebudayaan Maluku</h1>
                    <p class="text-sm text-slate-500 mt-0.5">Kelola 16 kategori objek pemajuan kebudayaan, cagar budaya, WBTb, seni & tradisi Maluku</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('kebudayaan.cultures.index', array_merge(request()->all(), ['export' => 'csv'])) }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                <i class="fas fa-file-excel mr-2"></i> Export CSV
            </a>
            <a href="{{ route('kebudayaan.map.index') }}" class="inline-flex items-center px-4 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                <i class="fas fa-map-marked-alt mr-2"></i> Peta Budaya
            </a>
            <a href="{{ route('kebudayaan.cultures.create') }}" class="inline-flex items-center px-4 py-2.5 bg-[#003B70] hover:bg-[#002b52] text-white text-sm font-semibold rounded-xl transition shadow-sm">
                <i class="fas fa-plus mr-2"></i> Tambah Budaya
            </a>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('kebudayaan.cultures.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Pencarian</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama objek, kata kunci..." class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Kategori Budaya</label>
                <select name="kategori" class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500">
                    <option value="">Semua 16 Kategori</option>
                    @foreach($kategoriList as $kat)
                        <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Kabupaten/Kota</label>
                <select name="kabupaten_kota" class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500">
                    <option value="">Semua Wilayah</option>
                    @foreach($kabupatenList as $kab)
                        <option value="{{ $kab }}" {{ request('kabupaten_kota') == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Status Validasi</label>
                <select name="status_validasi" class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500">
                    <option value="">Semua Status</option>
                    @foreach(['DRAFT', 'SUBMITTED', 'VERIFIED', 'VALIDATED', 'REJECTED'] as $st)
                        <option value="{{ $st }}" {{ request('status_validasi') == $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="w-full py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold rounded-xl transition">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('kebudayaan.cultures.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Objek Kebudayaan</th>
                        <th class="px-6 py-4">Kategori & Subkategori</th>
                        <th class="px-6 py-4">Kabupaten/Kota</th>
                        <th class="px-6 py-4">Tahun & Status</th>
                        <th class="px-6 py-4 text-center">Media / Galeri</th>
                        <th class="px-6 py-4">Status Validasi</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($cultures as $item)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $item->foto_url }}" alt="{{ $item->nama }}" class="w-12 h-12 rounded-xl object-cover border border-slate-200 flex-shrink-0">
                                <div>
                                    <a href="{{ route('kebudayaan.cultures.show', $item->id) }}" class="font-bold text-slate-900 hover:text-amber-600 transition block">
                                        {{ $item->nama }}
                                    </a>
                                    <span class="text-xs text-slate-400">{{ Str::limit($item->lokasi ?? $item->kecamatan ?? '-', 35) }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-amber-50 text-amber-800 rounded-lg text-xs font-bold">{{ $item->kategori }}</span>
                            @if($item->subkategori)
                                <span class="text-xs text-slate-400 block mt-1">{{ $item->subkategori }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-700 font-medium">{{ $item->kabupaten_kota }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ $item->tahun }}</div>
                            <span class="text-xs text-slate-500">{{ $item->status }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-1.5 text-xs text-slate-400">
                                @if($item->foto)<i class="fas fa-image text-blue-500" title="Ada Foto"></i>@endif
                                @if($item->video_url)<i class="fas fa-video text-rose-500" title="Ada Video"></i>@endif
                                @if($item->audio_url)<i class="fas fa-volume-up text-amber-500" title="Ada Audio"></i>@endif
                                @if($item->dokumen_pendukung)<i class="fas fa-file-pdf text-emerald-500" title="Ada Dokumen PDF"></i>@endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $valColors = [
                                    'VALIDATED' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'VERIFIED' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'SUBMITTED' => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'DRAFT' => 'bg-slate-100 text-slate-700 border-slate-200',
                                    'REJECTED' => 'bg-rose-100 text-rose-800 border-rose-200',
                                ];
                            @endphp
                            <span class="px-2.5 py-1 rounded-md text-xs font-bold border {{ $valColors[$item->status_validasi] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $item->status_validasi }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('kebudayaan.cultures.show', $item->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('kebudayaan.cultures.edit', $item->id) }}" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('kebudayaan.cultures.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data objek kebudayaan ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-slate-400">
                            <i class="fas fa-landmark text-4xl mb-3 block"></i>
                            Belum ada data objek kebudayaan yang cocok.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($cultures->hasPages())
        <div class="p-6 border-t border-slate-100">
            {{ $cultures->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
