@extends('layouts.app')

@section('title', 'Dashboard Ketenagaan - TVRI Maluku')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-sm text-slate-500 mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <span>Bidang Pembinaan Guru & Tenaga Kependidikan</span>
            </div>
            <h1 class="text-2xl font-black text-[#00264A] tracking-tight">Dashboard Ketenagaan</h1>
        </div>
        <div class="flex items-center space-x-3">
            <span class="text-sm font-medium text-slate-500">Tahun Periode:</span>
            <select class="bg-white border border-slate-200 text-slate-700 text-sm rounded-lg focus:ring-[#003B70] focus:border-[#003B70] block p-2 font-bold shadow-sm">
                <option>2026</option>
                <option>2025</option>
            </select>
        </div>
    </div>

    <!-- 4 Main Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Sertifikasi Pendidik -->
        <div class="bg-gradient-to-br from-[#005B9F] to-[#003B70] rounded-2xl p-5 shadow-lg text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-20">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"></path><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
            </div>
            <p class="text-xs font-bold text-blue-200 uppercase tracking-wider mb-1">Sertifikasi Pendidik</p>
            <p class="text-3xl font-black">4.850</p>
            <div class="flex items-center space-x-1 mt-2 text-xs">
                <span class="bg-blue-400/30 px-2 py-0.5 rounded text-white font-bold">85% Kuota</span>
            </div>
        </div>

        <!-- NUPTK -->
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-5 shadow-lg text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-20">
                <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
            </div>
            <p class="text-xs font-bold text-emerald-100 uppercase tracking-wider mb-1">Penerbitan NUPTK</p>
            <p class="text-3xl font-black">1.240</p>
            <div class="flex items-center space-x-1 mt-2 text-xs">
                <span class="bg-emerald-400/30 px-2 py-0.5 rounded text-white font-bold">+120 Tahun Ini</span>
            </div>
        </div>

        <!-- Tunjangan Pendidik -->
        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-5 shadow-lg text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-20">
                <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="text-xs font-bold text-amber-100 uppercase tracking-wider mb-1">Tunjangan Pendidik</p>
            <p class="text-3xl font-black">Rp 85,2 M</p>
            <div class="flex items-center space-x-1 mt-2 text-xs">
                <span class="bg-amber-400/30 px-2 py-0.5 rounded text-white font-bold">92% Realisasi</span>
            </div>
        </div>

        <!-- SIMPKB & PKB -->
        <div class="bg-gradient-to-br from-rose-500 to-red-600 rounded-2xl p-5 shadow-lg text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-20">
                <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <p class="text-xs font-bold text-rose-100 uppercase tracking-wider mb-1">Data SIMPKB & PKB</p>
            <p class="text-3xl font-black">6.720</p>
            <div class="flex items-center space-x-1 mt-2 text-xs">
                <span class="bg-rose-400/30 px-2 py-0.5 rounded text-white font-bold">Guru Aktif Tersinkronisasi</span>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Trend Chart -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-4 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-[#005B9F]"></span>
                <span>Trend Penerbitan NUPTK & Sertifikasi</span>
            </h3>
            <div class="relative h-64 w-full">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Donut Chart -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-4 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                <span>Komposisi Penyaluran Tunjangan</span>
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
                <span>Status Sinkronisasi SIMPKB per Kabupaten/Kota</span>
            </h3>
            <button class="text-xs font-bold text-blue-600 hover:text-blue-800">Export Rekap &rarr;</button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[10px] font-bold uppercase text-slate-500 tracking-wider">
                        <th class="px-5 py-3 border-b border-slate-200">No</th>
                        <th class="px-5 py-3 border-b border-slate-200">Kabupaten/Kota</th>
                        <th class="px-5 py-3 border-b border-slate-200">Total Guru</th>
                        <th class="px-5 py-3 border-b border-slate-200">Tersertifikasi</th>
                        <th class="px-5 py-3 border-b border-slate-200">Memiliki NUPTK</th>
                        <th class="px-5 py-3 border-b border-slate-200">Aktif PKB</th>
                        <th class="px-5 py-3 border-b border-slate-200">Status Capaian</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-3 border-b border-slate-100 text-slate-500">1</td>
                        <td class="px-5 py-3 border-b border-slate-100 font-bold text-[#00264A]">Kota Ambon</td>
                        <td class="px-5 py-3 border-b border-slate-100">1.250</td>
                        <td class="px-5 py-3 border-b border-slate-100">1.050 (84%)</td>
                        <td class="px-5 py-3 border-b border-slate-100">1.100 (88%)</td>
                        <td class="px-5 py-3 border-b border-slate-100">950 (76%)</td>
                        <td class="px-5 py-3 border-b border-slate-100"><span class="px-2 py-1 bg-emerald-100 text-emerald-700 font-bold text-[10px] rounded">Optimal</span></td>
                    </tr>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-3 border-b border-slate-100 text-slate-500">2</td>
                        <td class="px-5 py-3 border-b border-slate-100 font-bold text-[#00264A]">Maluku Tengah</td>
                        <td class="px-5 py-3 border-b border-slate-100">1.840</td>
                        <td class="px-5 py-3 border-b border-slate-100">1.450 (78%)</td>
                        <td class="px-5 py-3 border-b border-slate-100">1.600 (86%)</td>
                        <td class="px-5 py-3 border-b border-slate-100">1.200 (65%)</td>
                        <td class="px-5 py-3 border-b border-slate-100"><span class="px-2 py-1 bg-emerald-100 text-emerald-700 font-bold text-[10px] rounded">Optimal</span></td>
                    </tr>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-3 border-b border-slate-100 text-slate-500">3</td>
                        <td class="px-5 py-3 border-b border-slate-100 font-bold text-[#00264A]">BurSel</td>
                        <td class="px-5 py-3 border-b border-slate-100">450</td>
                        <td class="px-5 py-3 border-b border-slate-100">250 (55%)</td>
                        <td class="px-5 py-3 border-b border-slate-100">320 (71%)</td>
                        <td class="px-5 py-3 border-b border-slate-100">150 (33%)</td>
                        <td class="px-5 py-3 border-b border-slate-100"><span class="px-2 py-1 bg-rose-100 text-rose-700 font-bold text-[10px] rounded">Perlu Atensi</span></td>
                    </tr>
                    <tr class="hover:bg-slate-50/50 transition-colors bg-slate-50 font-bold">
                        <td class="px-5 py-3 text-slate-500" colspan="2">TOTAL KESELURUHAN</td>
                        <td class="px-5 py-3 text-slate-700">8.950</td>
                        <td class="px-5 py-3 text-slate-700">4.850</td>
                        <td class="px-5 py-3 text-slate-700">7.240</td>
                        <td class="px-5 py-3 text-slate-700">6.720</td>
                        <td class="px-5 py-3"></td>
                    </tr>
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
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [
                    {
                        label: 'Sertifikasi',
                        data: [120, 150, 180, 220, 250, 290, 310, 340, 390, 420, 460, 485],
                        borderColor: '#005B9F',
                        backgroundColor: 'rgba(0, 91, 159, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        pointRadius: 3
                    },
                    {
                        label: 'NUPTK Baru',
                        data: [30, 45, 60, 65, 80, 95, 100, 105, 110, 115, 118, 124],
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
                labels: ['Tunjangan Profesi Guru', 'Tunjangan Khusus (Terpencil)', 'Tambahan Penghasilan'],
                datasets: [{
                    data: [65.4, 22.1, 12.5],
                    backgroundColor: ['#005B9F', '#f59e0b', '#10b981'],
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
                                return context.label + ': ' + context.raw + ' Miliar';
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
