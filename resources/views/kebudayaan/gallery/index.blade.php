@extends('layouts.app')

@section('title', 'Galeri Multimedia Kebudayaan Maluku')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div>
            <div class="flex items-center space-x-3">
                <span class="p-2.5 bg-purple-50 text-purple-600 rounded-xl">
                    <i class="fas fa-photo-video text-xl"></i>
                </span>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Galeri Multimedia Kebudayaan Maluku</h1>
                    <p class="text-sm text-slate-500 mt-0.5">Eksplorasi visual foto, rekaman audio, dokumentasi video, dan naskah warisan budaya</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('kebudayaan.cultures.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                <i class="fas fa-list mr-1"></i> Data Objek
            </a>
            <a href="{{ route('kebudayaan.cultures.create') }}" class="px-4 py-2.5 bg-[#003B70] hover:bg-[#002b52] text-white text-sm font-semibold rounded-xl transition shadow-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Berkas
            </a>
        </div>
    </div>

    <!-- Media Filter Tabs & Category Filter -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <!-- Media Type Tabs -->
        <div class="flex items-center space-x-2 overflow-x-auto pb-1 md:pb-0">
            <a href="{{ route('kebudayaan.gallery.index', ['type' => 'all', 'kategori' => $selectedKategori]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $selectedType === 'all' ? 'bg-[#003B70] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua Media
            </a>
            <a href="{{ route('kebudayaan.gallery.index', ['type' => 'photo', 'kategori' => $selectedKategori]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ $selectedType === 'photo' ? 'bg-[#003B70] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <i class="fas fa-image"></i>
                <span>Foto ({{ $stats['total_foto'] }})</span>
            </a>
            <a href="{{ route('kebudayaan.gallery.index', ['type' => 'video', 'kategori' => $selectedKategori]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ $selectedType === 'video' ? 'bg-[#003B70] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <i class="fas fa-video"></i>
                <span>Video ({{ $stats['total_video'] }})</span>
            </a>
            <a href="{{ route('kebudayaan.gallery.index', ['type' => 'audio', 'kategori' => $selectedKategori]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ $selectedType === 'audio' ? 'bg-[#003B70] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <i class="fas fa-volume-up"></i>
                <span>Audio ({{ $stats['total_audio'] }})</span>
            </a>
            <a href="{{ route('kebudayaan.gallery.index', ['type' => 'pdf', 'kategori' => $selectedKategori]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ $selectedType === 'pdf' ? 'bg-[#003B70] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <i class="fas fa-file-pdf"></i>
                <span>Dokumen ({{ $stats['total_dokumen'] }})</span>
            </a>
        </div>

        <!-- Category Dropdown Filter -->
        <form method="GET" action="{{ route('kebudayaan.gallery.index') }}" class="flex items-center space-x-2">
            <input type="hidden" name="type" value="{{ $selectedType }}">
            <select name="kategori" onchange="this.form.submit()" class="text-xs rounded-xl border border-slate-200 px-3 py-2 font-semibold text-slate-700 focus:ring-2 focus:ring-purple-500">
                <option value="all">Semua Kategori Budaya</option>
                @foreach($kategoriList as $kat)
                    <option value="{{ $kat }}" {{ $selectedKategori === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Multimedia Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($cultures as $item)
        <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-md transition group flex flex-col justify-between">
            <div class="relative aspect-video w-full overflow-hidden bg-slate-100">
                <img src="{{ $item->foto_url }}" alt="{{ $item->nama }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <span class="absolute top-3 left-3 px-2.5 py-1 bg-slate-900/70 backdrop-blur-md text-amber-300 text-[10px] font-bold rounded-lg uppercase tracking-wider">
                    {{ $item->kategori }}
                </span>
                
                <!-- Media Indicators -->
                <div class="absolute bottom-3 right-3 flex items-center space-x-1.5">
                    @if($item->video_url)
                        <span class="w-6 h-6 rounded-full bg-rose-600 text-white flex items-center justify-center text-[10px] shadow" title="Ada Video">
                            <i class="fas fa-play"></i>
                        </span>
                    @endif
                    @if($item->audio_url)
                        <span class="w-6 h-6 rounded-full bg-purple-600 text-white flex items-center justify-center text-[10px] shadow" title="Ada Audio">
                            <i class="fas fa-volume-up"></i>
                        </span>
                    @endif
                    @if($item->dokumen_pendukung)
                        <span class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px] shadow" title="Ada PDF">
                            <i class="fas fa-file-pdf"></i>
                        </span>
                    @endif
                </div>
            </div>

            <div class="p-5 flex-1 flex flex-col justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 group-hover:text-amber-600 transition line-clamp-1">
                        {{ $item->nama }}
                    </h3>
                    <p class="text-xs text-slate-400 mt-1 flex items-center">
                        <i class="fas fa-map-marker-alt text-rose-400 mr-1 text-[10px]"></i>
                        {{ $item->kabupaten_kota }}
                    </p>
                    <p class="text-xs text-slate-600 mt-2.5 line-clamp-2 leading-relaxed">
                        {{ $item->deskripsi ?: 'Tidak ada deskripsi singkat.' }}
                    </p>
                </div>

                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-slate-400">{{ $item->tahun }} &bull; {{ $item->status }}</span>
                    <a href="{{ route('kebudayaan.cultures.show', $item->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl transition text-xs font-bold flex items-center">
                        Detail <i class="fas fa-chevron-right ml-1 text-[10px]"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 text-center text-slate-400 bg-white rounded-3xl border border-slate-100">
            <i class="fas fa-photo-video text-5xl mb-3 block"></i>
            <p class="text-sm font-semibold">Tidak ditemukan berkas media pada filter ini.</p>
        </div>
        @endforelse
    </div>

    @if($cultures->hasPages())
    <div class="p-6 bg-white rounded-2xl border border-slate-100 shadow-sm">
        {{ $cultures->links() }}
    </div>
    @endif
</div>
@endsection
