@extends('layouts.app')

@section('title', 'Dashboard Kebudayaan - TVRI Maluku')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-sm text-slate-500 mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <span>Bidang Kebudayaan</span>
            </div>
            <h1 class="text-2xl font-black text-[#00264A] tracking-tight">Dashboard Kebudayaan</h1>
        </div>
        <div class="flex items-center space-x-3">
            <span class="text-sm font-medium text-slate-500">Tahun Periode:</span>
            <form action="{{ route('kebudayaan.dashboard') }}" method="GET" id="filterForm">
                <select name="tahun" onchange="document.getElementById('filterForm').submit()" class="bg-white border border-slate-200 text-slate-700 text-sm rounded-lg focus:ring-[#003B70] focus:border-[#003B70] block p-2 font-bold shadow-sm">
                    <option value="all" {{ $tahun === 'all' ? 'selected' : '' }}>Semua Tahun</option>
                    @foreach(['2026', '2025', '2024'] as $yr)
                        <option value="{{ $yr }}" {{ $tahun === $yr ? 'selected' : '' }}>{{ $yr }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <!-- 5 Main Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 lg:grid-cols-5 gap-4">
        <!-- Cagar Budaya -->
        <div class="bg-gradient-to-br from-[#005B9F] to-[#003B70] rounded-2xl p-5 shadow-lg text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-20">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"></path><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
            </div>
            <p class="text-xs font-bold text-blue-200 uppercase tracking-wider mb-1">Cagar Budaya & Museum</p>
            <p class="text-3xl font-black">{{ $metrics['total_cagar_budaya'] ?? 0 }}</p>
            <div class="flex items-center space-x-1 mt-2 text-xs">
                <span class="bg-blue-400/30 px-2 py-0.5 rounded text-white font-bold">Objek Kebudayaan</span>
            </div>
        </div>

        <!-- Seni Tradisional -->
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-5 shadow-lg text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-20">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </div>
            <p class="text-xs font-bold text-emerald-100 uppercase tracking-wider mb-1">Seni & Tari</p>
            <p class="text-3xl font-black">{{ $metrics['total_seni'] ?? 0 }}</p>
            <div class="flex items-center space-x-1 mt-2 text-xs">
                <span class="bg-emerald-400/30 px-2 py-0.5 rounded text-white font-bold">Objek Kebudayaan</span>
            </div>
        </div>

        <!-- Tradisi & Warisan -->
        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-5 shadow-lg text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-20">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="text-xs font-bold text-amber-100 uppercase tracking-wider mb-1">Tradisi & Warisan</p>
            <p class="text-3xl font-black">{{ $metrics['total_tradisi'] ?? 0 }}</p>
            <div class="flex items-center space-x-1 mt-2 text-xs">
                <span class="bg-amber-400/30 px-2 py-0.5 rounded text-white font-bold">Objek Kebudayaan</span>
            </div>
        </div>

        <!-- Tokoh & Komunitas -->
        <div class="bg-gradient-to-br from-rose-500 to-red-600 rounded-2xl p-5 shadow-lg text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-20">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <p class="text-xs font-bold text-rose-100 uppercase tracking-wider mb-1">Tokoh & Komunitas</p>
            <p class="text-3xl font-black">{{ $metrics['total_tokoh'] ?? 0 }}</p>
            <div class="flex items-center space-x-1 mt-2 text-xs">
                <span class="bg-rose-400/30 px-2 py-0.5 rounded text-white font-bold">Objek Kebudayaan</span>
            </div>
        </div>

        <!-- Event / Festival -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-5 shadow-lg text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-20">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <p class="text-xs font-bold text-purple-200 uppercase tracking-wider mb-1">Festival / Event</p>
            <p class="text-3xl font-black">{{ $metrics['total_event'] ?? 0 }}</p>
            <div class="flex items-center space-x-1 mt-2 text-xs">
                <span class="bg-purple-400/30 px-2 py-0.5 rounded text-white font-bold">Objek Kebudayaan</span>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Trend Chart -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-4 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-[#005B9F]"></span>
                <span>Trend Penetapan Kebudayaan</span>
            </h3>
            <div class="relative h-64 w-full">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Donut Chart -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-4 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Komposisi Kategori Budaya</span>
            </h3>
            <div class="relative h-64 w-full flex justify-center">
                <canvas id="komposisiChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Permohonan Layanan Kebudayaan (Verifikasi Kepala Stasiun) -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600 flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Daftar Permohonan & Verifikasi Layanan Kebudayaan</span>
                </h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Daftar permohonan masuk dari pemohon / komunitas untuk diverifikasi dan diterbitkan SK oleh Kepala Stasiun.</p>
            </div>
            <a href="{{ route('pendidikan.gtk.layanan.create') }}" class="px-3 py-1.5 bg-[#005B9F] hover:bg-[#003B70] text-white text-xs font-bold rounded-xl transition flex items-center space-x-1 w-max">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>+ Buat Permohonan Baru</span>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-4">Nomor Agenda & Tanggal</th>
                        <th class="px-4 py-4">Jenis Layanan</th>
                        <th class="px-4 py-4">Pemohon & NIP</th>
                        <th class="px-4 py-4">Unit Kerja / Sekolah</th>
                        <th class="px-4 py-4">Status & No. SK</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($permohonanList as $item)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <!-- Nomor Agenda & Tanggal -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="font-mono text-[11px] font-bold text-slate-800 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200 block w-max">
                                    {{ $item->nomor_registrasi }}
                                </span>
                                <p class="text-[10px] text-slate-400 mt-1">
                                    {{ $item->tanggal_pengajuan ? \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d M Y') : '-' }}
                                </p>
                            </td>

                            <!-- Jenis Layanan -->
                            <td class="px-4 py-4">
                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg border bg-blue-50 text-blue-800 border-blue-200 block w-max">
                                    {{ $item->jenis_layanan }}
                                </span>
                                @if($item->perihal_uraian)
                                    <p class="text-[11px] text-slate-500 line-clamp-1 mt-1">{{ $item->perihal_uraian }}</p>
                                @endif
                            </td>

                            <!-- Pemohon & NIP -->
                            <td class="px-4 py-4">
                                <a href="{{ route('pendidikan.gtk.layanan.show', $item->id) }}" class="font-bold text-slate-800 hover:text-blue-700 block">
                                    {{ $item->nama_lengkap_guru }}
                                </a>
                                <p class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $item->nip_nik ?: '-' }}</p>
                            </td>

                            <!-- Unit Kerja / Sekolah -->
                            <td class="px-4 py-4">
                                <p class="font-semibold text-slate-700">{{ $item->tempat_tugas_sekolah ?: '-' }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ $item->kabupaten_kota ?: 'Kota Ambon' }}</p>
                            </td>

                            <!-- Status & No. SK -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($item->status_layanan === 'SELESAI' || $item->status_layanan === 'DISETUJUI')
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-md border bg-emerald-50 text-emerald-800 border-emerald-200 inline-block">
                                        Selesai & SK Terbit
                                    </span>
                                @elseif($item->status_layanan === 'PROSES_VERIFIKASI')
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-md border bg-amber-50 text-amber-800 border-amber-200 inline-block">
                                        Proses Verifikasi
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-md border bg-slate-100 text-slate-800 border-slate-200 inline-block">
                                        {{ $item->status_layanan }}
                                    </span>
                                @endif

                                @if($item->nomor_sk_rekomendasi && $item->nomor_sk_rekomendasi !== '-')
                                    <p class="font-mono text-[10px] font-bold text-emerald-700 mt-1">No: {{ $item->nomor_sk_rekomendasi }}</p>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="px-5 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-1.5">
                                    <!-- Detail -->
                                    <a href="{{ route('pendidikan.gtk.layanan.show', $item->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" title="Lihat Detail Permohonan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>

                                    <!-- Verifikasi / Edit SK (Kepala Stasiun) -->
                                    <a href="{{ route('pendidikan.gtk.layanan.edit', $item->id) }}" class="p-1.5 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors" title="Verifikasi & Terbitkan SK">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>

                                    <!-- Download -->
                                    @if($item->file_hasil_sk)
                                        <a href="{{ route('pendidikan.gtk.layanan.download', ['id' => $item->id, 'type' => 'sk']) }}" class="p-1.5 text-emerald-600 hover:bg-emerald-100 rounded-lg transition-colors" title="Unduh SK Terbit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        </a>
                                    @elseif($item->file_permohonan)
                                        <a href="{{ route('pendidikan.gtk.layanan.download', ['id' => $item->id, 'type' => 'permohonan']) }}" class="p-1.5 text-slate-500 hover:bg-slate-100 rounded-lg transition-colors" title="Unduh Berkas Permohonan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">
                                Belum ada permohonan layanan kebudayaan yang masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-[#005B9F]"></span>
                <span>Sebaran Objek Budaya Per Kabupaten/Kota</span>
            </h3>
            <a href="#" class="text-xs font-bold text-[#005B9F] hover:text-[#003B70]">Export Rekap &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[10px] font-bold uppercase text-slate-500 tracking-wider">
                        <th class="px-5 py-3 border-b border-slate-200">No</th>
                        <th class="px-5 py-3 border-b border-slate-200">Kabupaten/Kota</th>
                        <th class="px-5 py-3 border-b border-slate-200">Total Objek</th>
                        <th class="px-5 py-3 border-b border-slate-200">CAGAR BUDAYA</th>
                        <th class="px-5 py-3 border-b border-slate-200">SENI TRADISIONAL</th>
                        <th class="px-5 py-3 border-b border-slate-200">TRADISI</th>
                        <th class="px-5 py-3 border-b border-slate-200">TOKOH</th>
                        <th class="px-5 py-3 border-b border-slate-200">EVENT</th>
                      </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach($kabupatenList as $idx => $kab)
                        @php
                            // Compute regional data on the fly (or you could do this in the controller)
                            $rowTotal = \App\Models\SimandatCulture::where('kabupaten_kota', $kab)->count();
                            $rowCagar = \App\Models\SimandatCulture::where('kabupaten_kota', $kab)->whereIn('kategori', ['Cagar Budaya', 'Situs Budaya', 'Museum'])->count();
                            $rowSeni = \App\Models\SimandatCulture::where('kabupaten_kota', $kab)->whereIn('kategori', ['Seni Tradisional', 'Tarian Tradisional', 'Musik Tradisional'])->count();
                            $rowTradisi = \App\Models\SimandatCulture::where('kabupaten_kota', $kab)->whereIn('kategori', ['Warisan Budaya', 'Adat Istiadat', 'Bahasa Daerah', 'Permainan Tradisional', 'Kuliner Tradisional', 'Kerajinan'])->count();
                            $rowTokoh = \App\Models\SimandatCulture::where('kabupaten_kota', $kab)->whereIn('kategori', ['Tokoh Budaya', 'Sanggar Seni', 'Komunitas Budaya'])->count();
                            $rowEvent = \App\Models\SimandatCulture::where('kabupaten_kota', $kab)->where('kategori', 'Festival/Event Budaya')->count();
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-3 border-b border-slate-100 text-slate-500">{{ $idx + 1 }}</td>
                            <td class="px-5 py-3 border-b border-slate-100 font-bold text-[#00264A]">{{ $kab }}</td>
                            <td class="px-5 py-3 border-b border-slate-100">{{ $rowTotal }}</td>
                            <td class="px-5 py-3 border-b border-slate-100">{{ $rowCagar }}</td>
                            <td class="px-5 py-3 border-b border-slate-100">{{ $rowSeni }}</td>
                            <td class="px-5 py-3 border-b border-slate-100">{{ $rowTradisi }}</td>
                            <td class="px-5 py-3 border-b border-slate-100">{{ $rowTokoh }}</td>
                            <td class="px-5 py-3 border-b border-slate-100">{{ $rowEvent }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Trend Chart
        const ctxTrend = document.getElementById('trendChart').getContext('2d');
        new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: {!! json_encode($charts['tahun_labels']) !!},
                datasets: [
                    {
                        label: 'Penetapan Kebudayaan',
                        data: {!! json_encode($charts['tahun_counts']) !!},
                        borderColor: '#005B9F',
                        backgroundColor: 'rgba(0, 91, 159, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [2, 4], color: '#f1f5f9' },
                        ticks: { font: { size: 10 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });

        // Donut Chart
        const ctxKomposisi = document.getElementById('komposisiChart').getContext('2d');
        new Chart(ctxKomposisi, {
            type: 'doughnut',
            data: {
                labels: ['Cagar Budaya', 'Seni Tradisional', 'Tradisi', 'Tokoh', 'Event'],
                datasets: [{
                    data: [{{ $metrics['total_cagar_budaya'] }}, {{ $metrics['total_seni'] }}, {{ $metrics['total_tradisi'] }}, {{ $metrics['total_tokoh'] }}, {{ $metrics['total_event'] }}],
                    backgroundColor: ['#005B9F', '#10b981', '#f59e0b', '#ef4444', '#a855f7'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right', labels: { boxWidth: 10, font: { size: 11 } } },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.raw;
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endpush


