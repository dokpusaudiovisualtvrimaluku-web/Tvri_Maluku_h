@extends('layouts.app')

@section('title', 'Peta Geografis Sebaran Kebudayaan Provinsi Maluku')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div>
            <div class="flex items-center space-x-3">
                <span class="p-2.5 bg-cyan-50 text-cyan-600 rounded-xl">
                    <i class="fas fa-map-marked-alt text-xl"></i>
                </span>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Peta Persebaran Kebudayaan Maluku</h1>
                    <p class="text-sm text-slate-500 mt-0.5">Pemetaan geografis interaktif titik cagar budaya, situs, sanggar, dan objek budaya di 11 Kab/Kota</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <span class="px-3 py-1.5 bg-cyan-100 text-cyan-800 rounded-xl text-xs font-bold border border-cyan-200">
                <i class="fas fa-map-pin mr-1"></i> {{ $totalMarkers }} Titik Terpetakan
            </span>
            <a href="{{ route('kebudayaan.cultures.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                <i class="fas fa-list mr-2"></i> Tabel Data
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('kebudayaan.map.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Pilih Kabupaten / Kota</label>
                <select name="kabupaten" class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-cyan-500">
                    <option value="all">Semua Kabupaten/Kota di Maluku</option>
                    @foreach($kabupatenList as $kab)
                        <option value="{{ $kab }}" {{ $kabupaten == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Pilih Kategori Budaya</label>
                <select name="kategori" class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-cyan-500">
                    <option value="all">Semua 16 Kategori</option>
                    @foreach($kategoriList as $kat)
                        <option value="{{ $kat }}" {{ $kategori == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="w-full py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-semibold rounded-xl transition">
                    <i class="fas fa-filter mr-1"></i> Terapkan Filter Peta
                </button>
                <a href="{{ route('kebudayaan.map.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Map Canvas Container -->
    <div class="bg-white p-4 rounded-3xl shadow-sm border border-slate-100">
        <div id="malukuCultureMap" class="w-full h-[600px] rounded-2xl overflow-hidden z-0 border border-slate-200"></div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const markersData = {!! json_encode($markers) !!};

    // Center on Maluku (approx lat -3.7, lng 128.8)
    const map = L.map('malukuCultureMap').setView([-3.70, 128.80], 7);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© OpenStreetMap contributors | TVRI Maluku'
    }).addTo(map);

    const bounds = [];

    markersData.forEach(function(m) {
        if (m.lat && m.lng) {
            const popupContent = `
                <div style="font-family: sans-serif; width: 200px;">
                    <img src="${m.foto}" style="width: 100%; height: 110px; object-fit: cover; border-radius: 8px; margin-bottom: 8px;" />
                    <span style="font-size: 10px; font-weight: bold; background: #FEF3C7; color: #92400E; padding: 2px 6px; border-radius: 4px;">${m.kategori}</span>
                    <h4 style="font-size: 13px; font-weight: bold; color: #0F172A; margin: 4px 0;">${m.nama}</h4>
                    <p style="font-size: 11px; color: #64748B; margin: 0 0 8px 0;">📍 ${m.kabupaten_kota}</p>
                    <a href="${m.url}" style="display: block; text-align: center; background: #003B70; color: white; padding: 5px; font-size: 11px; font-weight: bold; border-radius: 6px; text-decoration: none;">Lihat Detail &rarr;</a>
                </div>
            `;

            const marker = L.marker([m.lat, m.lng]).addTo(map).bindPopup(popupContent);
            bounds.push([m.lat, m.lng]);
        }
    });

    if (bounds.length > 0) {
        map.fitBounds(bounds, { padding: [40, 40] });
    }
});
</script>
@endsection
