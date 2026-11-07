@extends('layouts.app')

@section('title', $culture->nama . ' - Pangkalan Data Kebudayaan')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm relative overflow-hidden">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-5">
                <img src="{{ $culture->foto_url }}" alt="{{ $culture->nama }}" class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover ring-4 ring-amber-50 border border-slate-200 flex-shrink-0 shadow-md">
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="px-3 py-1 bg-amber-50 text-amber-800 rounded-full text-xs font-bold border border-amber-200">
                            {{ $culture->kategori }}
                        </span>
                        @if($culture->subkategori)
                            <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-semibold">
                                {{ $culture->subkategori }}
                            </span>
                        @endif
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-800 rounded-full text-xs font-bold border border-emerald-200">
                            {{ $culture->status }} ({{ $culture->tahun }})
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $culture->nama }}</h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 flex items-center">
                        <i class="fas fa-map-marker-alt text-rose-500 mr-1.5"></i>
                        {{ $culture->lokasi ?? $culture->desa_kelurahan ?? '-' }}, {{ $culture->kecamatan ?? '-' }}, {{ $culture->kabupaten_kota }}
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-2.5">
                <a href="{{ route('kebudayaan.cultures.edit', $culture->id) }}" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl transition shadow-sm flex items-center space-x-1.5">
                    <i class="fas fa-edit"></i>
                    <span>Edit Data</span>
                </a>
                <a href="{{ route('kebudayaan.cultures.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content: Details + Multimedia + Map -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2 Cols: Description & Multimedia -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Deskripsi & Sejarah -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 mb-3 flex items-center">
                    <i class="fas fa-book-open text-amber-600 mr-2"></i> Deskripsi, Sejarah & Nilai Filosofis
                </h3>
                <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                    {{ $culture->deskripsi ?: 'Belum ada deskripsi naratif untuk objek budaya ini.' }}
                </div>
                
                @if($culture->kata_kunci)
                    <div class="mt-4 pt-4 border-t border-slate-100 flex flex-wrap gap-1.5 items-center">
                        <span class="text-xs font-semibold text-slate-400 mr-2"><i class="fas fa-tags"></i> Tags:</span>
                        @foreach(explode(',', $culture->kata_kunci) as $tag)
                            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-medium">#{{ trim($tag) }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Multimedia Players (Audio / Video / PDF) -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 flex items-center">
                    <i class="fas fa-photo-video text-amber-600 mr-2"></i> Berkas Multimedia & Arsip Pendukung
                </h3>

                <!-- Audio Player -->
                @if($culture->audio_url)
                    <div class="p-4 bg-purple-50 rounded-2xl border border-purple-100 space-y-2">
                        <p class="text-xs font-bold text-purple-900 flex items-center">
                            <i class="fas fa-volume-up text-purple-600 mr-2"></i> Rekaman Suara / Instrumen Tradisional / Dialek
                        </p>
                        <audio controls class="w-full">
                            <source src="{{ str_starts_with($culture->audio_url, 'http') ? $culture->audio_url : asset('storage/' . $culture->audio_url) }}">
                            Browser Anda tidak mendukung pemutar audio.
                        </audio>
                    </div>
                @endif

                <!-- Video Embed / Link -->
                @if($culture->video_url)
                    <div class="p-4 bg-rose-50 rounded-2xl border border-rose-100 space-y-2">
                        <p class="text-xs font-bold text-rose-900 flex items-center justify-between">
                            <span class="flex items-center"><i class="fas fa-video text-rose-600 mr-2"></i> Dokumentasi Video</span>
                            <a href="{{ $culture->video_url }}" target="_blank" class="text-xs text-rose-700 hover:underline">Buka di Tab Baru &rarr;</a>
                        </p>
                        @php
                            $youtubeId = null;
                            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $culture->video_url, $match)) {
                                $youtubeId = $match[1];
                            }
                        @endphp
                        @if($youtubeId)
                            <div class="aspect-video w-full rounded-xl overflow-hidden shadow-inner">
                                <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $youtubeId }}" frameborder="0" allowfullscreen></iframe>
                            </div>
                        @else
                            <a href="{{ $culture->video_url }}" target="_blank" class="block p-4 bg-white rounded-xl border border-rose-200 text-sm font-semibold text-rose-800 hover:bg-rose-50">
                                <i class="fas fa-play-circle text-rose-600 mr-2"></i> Tonton Video: {{ $culture->video_url }}
                            </a>
                        @endif
                    </div>
                @endif

                <!-- Dokumen PDF Pendukung -->
                @if($culture->dokumen_pendukung)
                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="p-2.5 bg-emerald-100 text-emerald-700 rounded-xl">
                                <i class="fas fa-file-pdf text-xl"></i>
                            </span>
                            <div>
                                <p class="text-xs font-bold text-emerald-900">Dokumen Kajian / Naskah Warisan Budaya</p>
                                <p class="text-[11px] text-emerald-700">Format PDF Resmi TVRI Maluku</p>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $culture->dokumen_pendukung) }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                            <i class="fas fa-download mr-1"></i> Unduh / Buka PDF
                        </a>
                    </div>
                @endif

                @if(!$culture->audio_url && !$culture->video_url && !$culture->dokumen_pendukung)
                    <div class="p-6 text-center text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                        <i class="fas fa-file-alt text-3xl mb-2 block"></i>
                        Belum ada berkas multimedia tambahan. Anda dapat mengunggahnya pada menu Edit.
                    </div>
                @endif
            </div>

        </div>

        <!-- Right Col: Metadata, Map & External Links -->
        <div class="space-y-6">
            
            <!-- Metadata Card -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 pb-3 border-b border-slate-100">
                    Informasi Objek Budaya
                </h3>
                <dl class="space-y-3 text-xs">
                    <div>
                        <dt class="text-slate-400 font-medium">Kabupaten/Kota</dt>
                        <dd class="text-slate-800 font-bold mt-0.5">{{ $culture->kabupaten_kota }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Kecamatan</dt>
                        <dd class="text-slate-800 font-bold mt-0.5">{{ $culture->kecamatan ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Desa / Negeri</dt>
                        <dd class="text-slate-800 font-bold mt-0.5">{{ $culture->desa_kelurahan ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Tahun Pencatatan</dt>
                        <dd class="text-slate-800 font-bold mt-0.5">{{ $culture->tahun }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 font-medium">Status Validasi</dt>
                        <dd class="mt-1">
                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-lg">
                                {{ $culture->status_validasi }}
                            </span>
                        </dd>
                    </div>
                    @if($culture->link_eksternal)
                    <div class="pt-2">
                        <dt class="text-slate-400 font-medium">Portal / Referensi Resmi</dt>
                        <dd class="mt-1">
                            <a href="{{ $culture->link_eksternal }}" target="_blank" class="text-blue-600 hover:underline font-semibold block truncate">
                                <i class="fas fa-external-link-alt mr-1"></i> Buka Tautan Referensi
                            </a>
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Mini Map Preview -->
            @if($culture->latitude && $culture->longitude)
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center justify-between">
                    <span>Titik Lokasi Geografis</span>
                    <a href="{{ route('kebudayaan.map.index', ['kabupaten' => $culture->kabupaten_kota]) }}" class="text-[10px] text-cyan-600 font-bold hover:underline">Peta Penuh &rarr;</a>
                </h3>
                <div id="miniMap" class="w-full h-48 rounded-xl overflow-hidden border border-slate-200 z-0"></div>
                <p class="text-[11px] text-slate-400 font-mono text-center">
                    Lat: {{ $culture->latitude }}, Lng: {{ $culture->longitude }}
                </p>
            </div>

            <!-- Leaflet CSS & JS -->
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const map = L.map('miniMap').setView([{{ $culture->latitude }}, {{ $culture->longitude }}], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(map);
                L.marker([{{ $culture->latitude }}, {{ $culture->longitude }}]).addTo(map)
                    .bindPopup("<b>{{ $culture->nama }}</b><br>{{ $culture->kategori }}").openPopup();
            });
            </script>
            @endif

        </div>

    </div>
</div>
@endsection
