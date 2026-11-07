@extends('layouts.app')

@section('title', 'Dashboard SMK - TVRI Maluku')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-sm text-slate-500 mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <span>Bidang Pembinaan SMK</span>
            </div>
            <h1 class="text-2xl font-black text-[#00264A] tracking-tight">Dashboard SMK</h1>
        </div>
        <div class="flex items-center space-x-3">
            <span class="text-sm font-medium text-slate-500">Tahun Periode:</span>
            <select class="bg-white border border-slate-200 text-slate-700 text-sm rounded-lg focus:ring-[#003B70] focus:border-[#003B70] block p-2 font-bold shadow-sm">
                <option>2026</option>
                <option>2025</option>
            </select>
        </div>
    </div>

    <!-- 5 Main Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 lg:grid-cols-5 gap-4">
        <!-- Kelembagaan -->
        <div class="bg-gradient-to-br from-[#005B9F] to-[#003B70] rounded-2xl p-5 shadow-lg text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-20">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"></path><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
            </div>
            <p class="text-xs font-bold text-blue-200 uppercase tracking-wider mb-1">Kelembagaan & Perizinan</p>
            <p class="text-3xl font-black">{{ $cards['kelembagaan'] ?? 0 }}</p>
            <div class="flex items-center space-x-1 mt-2 text-xs">
                <span class="bg-blue-400/30 px-2 py-0.5 rounded text-white font-bold">Layanan SMK</span>
            </div>
        </div>

        <!-- Kesiswaan -->
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-5 shadow-lg text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-20">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </div>
            <p class="text-xs font-bold text-emerald-100 uppercase tracking-wider mb-1">Kesiswaan & Mutasi</p>
            <p class="text-3xl font-black">{{ $cards['kesiswaan'] ?? 0 }}</p>
            <div class="flex items-center space-x-1 mt-2 text-xs">
                <span class="bg-emerald-400/30 px-2 py-0.5 rounded text-white font-bold">Layanan SMK</span>
            </div>
        </div>

        <!-- Pendanaan -->
        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-5 shadow-lg text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-20">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="text-xs font-bold text-amber-100 uppercase tracking-wider mb-1">Pemerataan & Pendanaan</p>
            <p class="text-3xl font-black">{{ $cards['pendanaan'] ?? 0 }}</p>
            <div class="flex items-center space-x-1 mt-2 text-xs">
                <span class="bg-amber-400/30 px-2 py-0.5 rounded text-white font-bold">Layanan SMK</span>
            </div>
        </div>

        <!-- Legalitas -->
        <div class="bg-gradient-to-br from-rose-500 to-red-600 rounded-2xl p-5 shadow-lg text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-20">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <p class="text-xs font-bold text-rose-100 uppercase tracking-wider mb-1">Dokumen & Legalitas</p>
            <p class="text-3xl font-black">{{ $cards['legalitas'] ?? 0 }}</p>
            <div class="flex items-center space-x-1 mt-2 text-xs">
                <span class="bg-rose-400/30 px-2 py-0.5 rounded text-white font-bold">Layanan SMK</span>
            </div>
        </div>

        <!-- Kurikulum -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-5 shadow-lg text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-20">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <p class="text-xs font-bold text-purple-200 uppercase tracking-wider mb-1">Kurikulum & Vokasi</p>
            <p class="text-3xl font-black">{{ $cards['kurikulum'] ?? 0 }}</p>
            <div class="flex items-center space-x-1 mt-2 text-xs">
                <span class="bg-purple-400/30 px-2 py-0.5 rounded text-white font-bold">Layanan SMK</span>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Trend Chart -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-4 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-[#005B9F]"></span>
                <span>Trend Layanan SMK</span>
            </h3>
            <div class="relative h-64 w-full">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Donut Chart -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-4 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                <span>Komposisi Layanan SMK</span>
            </h3>
            <div class="relative h-64 w-full flex justify-center items-center">
                <canvas id="tunjanganChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Status Layanan SMK per Kabupaten/Kota</span>
            </h3>
            <button class="text-xs font-bold text-blue-600 hover:text-blue-800">Export Rekap &rarr;</button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[10px] font-bold uppercase text-slate-500 tracking-wider">
                        <th class="px-5 py-3 border-b border-slate-200">No</th>
                        <th class="px-5 py-3 border-b border-slate-200">Kabupaten/Kota</th>
                        <th class="px-5 py-3 border-b border-slate-200">Total Layanan</th>
                        <th class="px-5 py-3 border-b border-slate-200">KESISWAAN</th>
                        <th class="px-5 py-3 border-b border-slate-200">PENDANAAN</th>
                        <th class="px-5 py-3 border-b border-slate-200">LEGALITAS</th>
                        <th class="px-5 py-3 border-b border-slate-200">KURIKULUM</th>
                        <th class="px-5 py-3 border-b border-slate-200">KELEMBAGAAN</th>
                      </tr>
                </thead>
                <tbody class="text-sm">
                        @foreach($tableData as $idx => $row)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-3 border-b border-slate-100 text-slate-500">{{ $idx + 1 }}</td>
                            <td class="px-5 py-3 border-b border-slate-100 font-bold text-[#00264A]">{{ $row['kabupaten'] }}</td>
                            <td class="px-5 py-3 border-b border-slate-100">{{ $row['total'] }}</td>
                            <td class="px-5 py-3 border-b border-slate-100">{{ $row['kesiswaan'] ?? 0 }}</td>
                            <td class="px-5 py-3 border-b border-slate-100">{{ $row['pendanaan'] ?? 0 }}</td>
                            <td class="px-5 py-3 border-b border-slate-100">{{ $row['legalitas'] ?? 0 }}</td>
                            <td class="px-5 py-3 border-b border-slate-100">{{ $row['kurikulum'] ?? 0 }}</td>
                            <td class="px-5 py-3 border-b border-slate-100">{{ $row['kelembagaan'] ?? 0 }}</td>
                            <td class="px-5 py-3 border-b border-slate-100">
                                @if($row['total'] > 0)
                                    <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-[10px] font-bold">Aktif</span>
                                @else
                                    <span class="px-2 py-1 bg-slate-100 text-slate-500 rounded text-[10px] font-bold">-</span>
                                @endif
                            </td>
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
        const lineOptions = {
            series: [{
                name: 'Total Layanan',
                data: {!! json_encode($trend) !!}
            }],
            chart: {
                type: 'line',
                height: 300,
                toolbar: { show: false },
                fontFamily: 'inherit'
            },
            colors: ['#005B9F'],
            stroke: { curve: 'smooth', width: 3 },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                labels: { style: { colors: '#64748b' } }
            },
            yaxis: {
                labels: { style: { colors: '#64748b' } }
            },
            grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
            markers: { size: 4, colors: ['#ffffff'], strokeColors: '#005B9F', strokeWidth: 2 }
        };

        const lineChart = new ApexCharts(document.querySelector("#lineChart"), lineOptions);
        lineChart.render();

        const pieOptions = {
            series: [{{ $cards['kesiswaan'] ?? 0 }}, {{ $cards['pendanaan'] ?? 0 }}, {{ $cards['legalitas'] ?? 0 }}, {{ $cards['kurikulum'] ?? 0 }}, {{ $cards['kelembagaan'] ?? 0 }}],
            labels: ['Kesiswaan', 'Pendanaan', 'Legalitas', 'Kurikulum', 'Kelembagaan'],
            chart: {
                type: 'donut',
                height: 300,
                fontFamily: 'inherit'
            },
            colors: ['#005B9F', '#f59e0b', '#10b981', '#f43f5e'],
            plotOptions: {
                pie: { donut: { size: '65%' } }
            },
            dataLabels: { enabled: false },
            legend: { position: 'right', offsetY: 0, height: 230 }
        };

        const pieChart = new ApexCharts(document.querySelector("#pieChart"), pieOptions);
        pieChart.render();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Trend Chart
        const ctxTrend = document.getElementById('trendChart').getContext('2d');
        new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [
                    {
                        label: 'Layanan SMK',
                        data: {!! json_encode($trend) !!},
                        borderColor: '#005B9F',
                        backgroundColor: 'rgba(0, 91, 159, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        pointRadius: 3
                    },
                    {
                        label: 'NUPTK', hidden: true,
                        data: [], // Removed second dataset
                        borderColor: '#10b981',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        tension: 0.3,
                        pointRadius: 3
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
        const ctxTunjangan = document.getElementById('tunjanganChart').getContext('2d');
        new Chart(ctxTunjangan, {
            type: 'doughnut',
            data: {
                labels: ['Kesiswaan', 'Pendanaan', 'Legalitas', 'Kurikulum', 'Kelembagaan'],
                datasets: [{
                    data: [{{ $cards['kesiswaan'] }}, {{ $cards['pendanaan'] }}, {{ $cards['legalitas'] }}, {{ $cards['kurikulum'] }}, {{ $cards['kelembagaan'] }}],
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#a855f7', '#005B9F'],
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










