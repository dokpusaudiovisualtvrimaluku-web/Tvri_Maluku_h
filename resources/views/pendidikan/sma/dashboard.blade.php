@extends('layouts.app')

@section('title', 'Dashboard Pembinaan SMA - TVRI Maluku')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-sm text-slate-500 mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <span>Bidang Pembinaan SMA</span>
            </div>
            <h1 class="text-2xl font-black text-[#00264A] tracking-tight">Dashboard SMA</h1>
        </div>
        <div class="flex items-center space-x-3">
            <span class="text-sm font-medium text-slate-500">Tahun Periode:</span>
            <select class="bg-white border border-slate-200 text-slate-700 text-sm rounded-lg focus:ring-[#003B70] focus:border-[#003B70] block p-2 font-bold shadow-sm">
                <option>2026</option>
                <option>2025</option>
            </select>
        </div>
    </div>

    @php
        $userId = auth()->id();
        $totalLayanan = \App\Models\SimandatSmaLayanan::where('user_id', $userId)->count();
        $menunggu = \App\Models\SimandatSmaLayanan::where('user_id', $userId)->where('status_layanan', 'Menunggu Verifikasi')->count();
        $diproses = \App\Models\SimandatSmaLayanan::where('user_id', $userId)->where('status_layanan', 'Diproses / Draf SK')->count();
        $selesai = \App\Models\SimandatSmaLayanan::where('user_id', $userId)->where('status_layanan', 'Selesai / Dicetak')->count();

        // Chart Data: Jenis Layanan
        $chartDataRaw = \App\Models\SimandatSmaLayanan::where('user_id', $userId)
            ->selectRaw('jenis_layanan, COUNT(*) as count')
            ->groupBy('jenis_layanan')
            ->orderBy('count', 'desc')
            ->get();
            
        $chartLabels = $chartDataRaw->pluck('jenis_layanan')->toJson();
        $chartData = $chartDataRaw->pluck('count')->toJson();

        // Chart Data: Wilayah Kabupaten/Kota
        $chartKabupatenRaw = \App\Models\SimandatSmaLayanan::where('user_id', $userId)
            ->selectRaw('kabupaten_kota, COUNT(*) as count')
            ->groupBy('kabupaten_kota')
            ->orderBy('count', 'desc')
            ->get();
            
        $chartKabupatenLabels = $chartKabupatenRaw->pluck('kabupaten_kota')->toJson();
        $chartKabupatenData = $chartKabupatenRaw->pluck('count')->toJson();

        // Fitur Tambahan: 5 Pengajuan Terakhir
        $latestLayanan = \App\Models\SimandatSmaLayanan::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();
    @endphp

    <!-- 4 Main Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Data -->
        <div class="bg-gradient-to-br from-[#005B9F] to-[#003B70] rounded-2xl p-5 shadow-lg text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-20">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"></path><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
            </div>
            <p class="text-xs font-bold text-blue-200 uppercase tracking-wider mb-1">Total Permohonan</p>
            <p class="text-3xl font-black">{{ number_format($totalLayanan) }}</p>
            <div class="flex items-center space-x-1 mt-2 text-xs">
                <span class="bg-blue-400/30 px-2 py-0.5 rounded text-white font-bold">Layanan SMA</span>
            </div>
        </div>

        <!-- Menunggu -->
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl p-5 shadow-lg text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-20">
                <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="text-xs font-bold text-amber-200 uppercase tracking-wider mb-1">Menunggu Verifikasi</p>
            <p class="text-3xl font-black">{{ number_format($menunggu) }}</p>
            <div class="flex items-center space-x-1 mt-2 text-xs">
                <span class="bg-amber-400/30 px-2 py-0.5 rounded text-white font-bold">Harus Diperiksa</span>
            </div>
        </div>

        <!-- Diproses -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-5 shadow-lg text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-20">
                <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <p class="text-xs font-bold text-blue-200 uppercase tracking-wider mb-1">Sedang Diproses</p>
            <p class="text-3xl font-black">{{ number_format($diproses) }}</p>
            <div class="flex items-center space-x-1 mt-2 text-xs">
                <span class="bg-blue-400/30 px-2 py-0.5 rounded text-white font-bold">Penyusunan SK</span>
            </div>
        </div>

        <!-- Selesai -->
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-5 shadow-lg text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-20">
                <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="text-xs font-bold text-emerald-200 uppercase tracking-wider mb-1">Selesai / Dicetak</p>
            <p class="text-3xl font-black">{{ number_format($selesai) }}</p>
            <div class="flex items-center space-x-1 mt-2 text-xs">
                <span class="bg-emerald-400/30 px-2 py-0.5 rounded text-white font-bold">Selesai 100%</span>
            </div>
        </div>
    </div>

    <!-- Grid Rekapitulasi per Jenis Layanan -->
    <div class="mt-8">
        <h2 class="text-lg font-black text-[#00264A] tracking-tight mb-4 flex items-center space-x-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            <span>Rekapitulasi Data per Jenis Layanan</span>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @php
                $layananList = \App\Models\SimandatSmaLayanan::getJenisLayananOptions();
                // Convert chartDataRaw to key-value array for easy lookup
                $countsByJenis = $chartDataRaw->pluck('count', 'jenis_layanan')->toArray();
            @endphp
            
            @foreach($layananList as $title => $meta)
                @php
                    $count = $countsByJenis[$title] ?? 0;
                    $url = route('pendidikan.sma.layanan.index', ['search' => $title]);
                @endphp
                <a href="{{ $url }}" class="group bg-white rounded-xl p-4 shadow-sm border border-slate-200 hover:border-blue-400 hover:shadow-md transition-all duration-200 flex flex-col justify-between h-full relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                        <svg class="w-24 h-24 text-[#005B9F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <div class="flex items-start justify-between mb-2">
                            <span class="px-2 py-1 bg-slate-100 text-slate-600 font-bold text-[10px] rounded-md">{{ $meta['prefix'] ?? 'SMA' }}</span>
                            <span class="text-2xl font-black {{ $count > 0 ? 'text-[#005B9F]' : 'text-slate-300' }}">{{ $count }}</span>
                        </div>
                        <h3 class="text-sm font-bold text-slate-700 leading-tight mb-2 group-hover:text-blue-700 transition-colors">{{ $title }}</h3>
                    </div>
                    <div class="mt-2 pt-3 border-t border-slate-100 flex items-center space-x-1.5 text-[10px] text-slate-500 font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="truncate">{{ $meta['seksi'] ?? 'Bidang SMA' }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Bar Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-4 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-[#005B9F]"></span>
                <span>Distribusi Layanan Pembinaan SMA</span>
            </h3>
            <div class="relative h-64 w-full">
                <canvas id="jenisLayananChart"></canvas>
            </div>
        </div>

        <!-- Donut Chart -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-4 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                <span>Status Pemrosesan Layanan</span>
            </h3>
            <div class="relative h-64 w-full flex justify-center items-center">
                <canvas id="statusLayananChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Extended Features Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <!-- Polar Chart (Wilayah) -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-4 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                <span>Sebaran Wilayah (Kabupaten/Kota)</span>
            </h3>
            <div class="relative h-64 w-full flex justify-center items-center">
                <canvas id="kabupatenChart"></canvas>
            </div>
        </div>

        <!-- Latest Table -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600 flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Pengajuan Layanan Terbaru</span>
                </h3>
                <a href="{{ route('pendidikan.sma.layanan.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800">Lihat Semua &rarr;</a>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] font-bold uppercase text-slate-500 tracking-wider">
                            <th class="px-5 py-3 border-b border-slate-200">No. Registrasi</th>
                            <th class="px-5 py-3 border-b border-slate-200">Sekolah Asal</th>
                            <th class="px-5 py-3 border-b border-slate-200">Jenis Layanan</th>
                            <th class="px-5 py-3 border-b border-slate-200">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($latestLayanan as $item)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-3 border-b border-slate-100 text-slate-500 font-mono text-xs">{{ $item->nomor_registrasi }}</td>
                            <td class="px-5 py-3 border-b border-slate-100 font-bold text-[#00264A]">{{ $item->nama_sekolah_asal }}</td>
                            <td class="px-5 py-3 border-b border-slate-100 text-xs truncate max-w-[150px]">{{ $item->jenis_layanan }}</td>
                            <td class="px-5 py-3 border-b border-slate-100">
                                @if($item->status_layanan === 'Menunggu Verifikasi')
                                    <span class="px-2 py-1 bg-amber-100 text-amber-700 font-bold text-[10px] rounded">Menunggu</span>
                                @elseif($item->status_layanan === 'Diproses / Draf SK')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 font-bold text-[10px] rounded">Diproses</span>
                                @else
                                    <span class="px-2 py-1 bg-emerald-100 text-emerald-700 font-bold text-[10px] rounded">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-10 text-center text-slate-400 text-sm italic">Belum ada data pengajuan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Bar Chart (Jenis Layanan)
        const ctxJenis = document.getElementById('jenisLayananChart').getContext('2d');
        new Chart(ctxJenis, {
            type: 'bar',
            data: {
                labels: {!! $chartLabels !!},
                datasets: [{
                    label: 'Jumlah Pengajuan',
                    data: {!! $chartData !!},
                    backgroundColor: 'rgba(0, 91, 159, 0.8)',
                    borderColor: '#005B9F',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, font: { size: 10 } },
                        grid: { borderDash: [2, 4], color: '#f1f5f9' }
                    },
                    x: {
                        ticks: { 
                            font: { size: 9 },
                            maxRotation: 45,
                            minRotation: 45
                        },
                        grid: { display: false }
                    }
                }
            }
        });

        // Donut Chart (Status Layanan)
        const ctxStatus = document.getElementById('statusLayananChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Menunggu Verifikasi', 'Diproses / Draf SK', 'Selesai / Dicetak'],
                datasets: [{
                    data: [{{ $menunggu }}, {{ $diproses }}, {{ $selesai }}],
                    backgroundColor: ['#f59e0b', '#3b82f6', '#10b981'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } },
                },
                cutout: '65%'
            }
        });

        // Polar Area Chart (Kabupaten/Kota)
        const ctxKabupaten = document.getElementById('kabupatenChart').getContext('2d');
        new Chart(ctxKabupaten, {
            type: 'polarArea',
            data: {
                labels: {!! $chartKabupatenLabels !!},
                datasets: [{
                    data: {!! $chartKabupatenData !!},
                    backgroundColor: [
                        'rgba(99, 102, 241, 0.7)',
                        'rgba(236, 72, 153, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(245, 158, 11, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(139, 92, 246, 0.7)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right', labels: { boxWidth: 10, font: { size: 10 } } }
                }
            }
        });
    });
</script>
@endpush
