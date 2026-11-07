@extends('layouts.app')

@section('title', 'Sekretariat Dinas - TVRI Maluku')

@section('content')
<div class="space-y-6">

    <!-- Header & Banner -->
    <div class="bg-gradient-to-r from-[#00264A] via-[#003B70] to-[#005B9F] rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 w-96 h-96 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 rounded-full text-xs font-bold tracking-wider uppercase">
                        Sekretariat Dinas
                    </span>
                    <span class="text-xs text-blue-200">Koordinasi, Pembinaan & Pengendalian</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight text-white">
                    Pangkalan Data Sekretariat Dinas
                </h1>
                <p class="text-sm text-blue-100 max-w-3xl mt-1 leading-relaxed">
                    Membantu Kepala Stasiun dalam koordinasi administrasi umum, kepegawaian, perencanaan, keuangan, program, pelaporan evaluasi, pengelolaan BMD serta pelayanan internal di lingkungan TVRI Maluku.
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-xl border border-white/20 backdrop-blur-md transition-all duration-200">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Export CSV</span>
                </a>
                <a href="{{ route('sekretariat.data.create') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-lg hover:shadow-emerald-600/30 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>+ Tambah Data Agenda / Fungsi</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 font-bold">&times;</button>
        </div>
    @endif

    <!-- Metric Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Total Agenda -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Agenda / Data</p>
                <p class="text-2xl font-black text-[#00264A] mt-1">{{ number_format($totalKegiatan, 0, ',', '.') }}</p>
                <p class="text-[10px] text-slate-500 mt-0.5">8 Fungsi Sekretariat</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-[#003B70] flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
        </div>

        <!-- Berjalan / Proses -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sedang Berjalan</p>
                <p class="text-2xl font-black text-amber-600 mt-1">{{ number_format($totalBerjalan, 0, ',', '.') }}</p>
                <p class="text-[10px] text-amber-600 font-semibold mt-0.5">Dalam Pemantauan</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Selesai -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Selesai / Tuntas</p>
                <p class="text-2xl font-black text-emerald-600 mt-1">{{ number_format($totalSelesai, 0, ',', '.') }}</p>
                <p class="text-[10px] text-emerald-600 font-semibold mt-0.5">Tercapai 100%</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Alokasi Anggaran -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Alokasi Anggaran</p>
                <p class="text-lg font-black text-slate-800 mt-1">Rp {{ number_format($totalAlokasi / 1000000, 1, ',', '.') }} Jt</p>
                <p class="text-[10px] text-slate-500 mt-0.5">Pagu Terkelola</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        <!-- Capaian Rata-Rata -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center justify-between col-span-2 lg:col-span-1">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Rata-rata Capaian</p>
                <p class="text-2xl font-black text-cyan-600 mt-1">{{ $avgCapaian }}%</p>
                <p class="text-[10px] text-cyan-600 font-semibold mt-0.5">Realisasi: Rp {{ number_format($totalRealisasi / 1000000, 1, ',', '.') }} Jt</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
        </div>
    </div>

    @php
        $chartData = \App\Models\SimandatSekretariat::select('status_pelaksanaan', \DB::raw('count(*) as total'))->groupBy('status_pelaksanaan')->get();
        $statusLabels = $chartData->pluck('status_pelaksanaan');
        $statusCounts = $chartData->pluck('total');
        
        // Setup colors for status
        $statusColors = [
            'Direncanakan' => '#cbd5e1',
            'Sedang Berjalan' => '#3b82f6',
            'Menunggu Telaah' => '#f59e0b',
            'Selesai' => '#10b981',
            'Perlu Evaluasi' => '#8b5cf6',
            'Tertunda' => '#f43f5e',
        ];
        $chartBgColors = $statusLabels->map(function($status) use ($statusColors) {
            return $statusColors[$status] ?? '#94a3b8';
        });

        $budgetData = \App\Models\SimandatSekretariat::select('kategori_fungsi', \DB::raw('SUM(alokasi_anggaran) as alokasi'), \DB::raw('SUM(realisasi_anggaran) as realisasi'))->groupBy('kategori_fungsi')->get();
        $budgetLabels = [];
        $alokasiData = [];
        $realisasiData = [];
        foreach($budgetData as $item) {
            $budgetLabels[] = \App\Models\SimandatSekretariat::FUNGSI_LIST[$item->kategori_fungsi]['label'] ?? $item->kategori_fungsi;
            $alokasiData[] = (float) $item->alokasi;
            $realisasiData[] = (float) $item->realisasi;
        }
    @endphp

    <!-- Infografis Dashboard -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
        <!-- Chart Status -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 lg:col-span-1">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-4 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                <span>Distribusi Status Agenda</span>
            </h3>
            <div class="relative h-48 w-full flex justify-center items-center">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Chart Anggaran -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 lg:col-span-2">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-4 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span>Alokasi vs Realisasi Anggaran per Fungsi</span>
            </h3>
            <div class="relative h-48 w-full">
                <canvas id="budgetChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Status Chart
            const ctxStatus = document.getElementById('statusChart').getContext('2d');
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($statusLabels) !!},
                    datasets: [{
                        data: {!! json_encode($statusCounts) !!},
                        backgroundColor: {!! json_encode($chartBgColors) !!},
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { boxWidth: 10, font: { size: 10 } } }
                    },
                    cutout: '70%'
                }
            });

            // Budget Chart
            const ctxBudget = document.getElementById('budgetChart').getContext('2d');
            new Chart(ctxBudget, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($budgetLabels) !!},
                    datasets: [
                        {
                            label: 'Alokasi (Rp)',
                            data: {!! json_encode($alokasiData) !!},
                            backgroundColor: '#94a3b8',
                            borderRadius: 4,
                            barPercentage: 0.6
                        },
                        {
                            label: 'Realisasi (Rp)',
                            data: {!! json_encode($realisasiData) !!},
                            backgroundColor: '#10b981',
                            borderRadius: 4,
                            barPercentage: 0.6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { boxWidth: 10, font: { size: 11 } } },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let value = context.raw;
                                    return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [2, 4], color: '#f1f5f9' },
                            ticks: {
                                font: { size: 10 },
                                callback: function(value) {
                                    return value >= 1000000 ? (value / 1000000) + ' Jt' : value;
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 9 },
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    }
                }
            });
        });
    </script>

    <!-- 8 Fungsi Quick Category Navigation Bar -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-600 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-[#003B70]"></span>
                <span>8 Fungsi Tugas Pokok Sekretaris Stasiun</span>
            </h2>
            <span class="text-[11px] text-slate-400">Klik tab untuk filter kategori fungsi</span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-2.5">
            @php
                $activeFungsi = request('kategori_fungsi', 'all');
            @endphp
            
            <a href="{{ route('sekretariat.data.index') }}" 
               class="p-3 rounded-xl border text-left transition-all duration-200 {{ $activeFungsi === 'all' ? 'bg-[#003B70] text-white border-[#003B70] shadow-md' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200' }}">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold truncate">Semua Fungsi (8 Bidang)</span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full {{ $activeFungsi === 'all' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }} font-bold">
                        {{ $totalKegiatan }}
                    </span>
                </div>
                <p class="text-[10px] {{ $activeFungsi === 'all' ? 'text-blue-100' : 'text-slate-400' }} mt-1 truncate">Seluruh portofolio sekretariat</p>
            </a>

            @foreach($allFungsi as $key => $meta)
                @php
                    $isSelected = $activeFungsi === $key;
                    $count = $fungsiCounts[$key] ?? 0;
                @endphp
                <a href="{{ route('sekretariat.data.index', array_merge(request()->except(['kategori_fungsi', 'page']), ['kategori_fungsi' => $key])) }}" 
                   class="p-3 rounded-xl border text-left transition-all duration-200 {{ $isSelected ? 'bg-[#003B70] text-white border-[#003B70] shadow-md' : 'bg-white hover:bg-blue-50/50 text-slate-800 border-slate-200 hover:border-blue-300' }}">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold truncate" title="{{ $meta['label'] }}">{{ $meta['label'] }}</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-full {{ $isSelected ? 'bg-white/20 text-white' : 'bg-blue-50 text-blue-800 border border-blue-200' }} font-bold">
                            {{ $count }}
                        </span>
                    </div>
                    <p class="text-[10px] {{ $isSelected ? 'text-blue-100' : 'text-slate-400' }} mt-1 truncate" title="{{ $meta['deskripsi'] }}">
                        {{ count($meta['sub_kegiatan']) }} Sub-Tugas Pokok
                    </p>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('sekretariat.data.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
            @if(request()->filled('kategori_fungsi'))
                <input type="hidden" name="kategori_fungsi" value="{{ request('kategori_fungsi') }}">
            @endif

            <!-- Search -->
            <div class="md:col-span-4">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Pencarian Kata Kunci</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, nomor agenda, sub kegiatan, PIC..." 
                           class="w-full text-xs pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <!-- Status Pelaksanaan -->
            <div class="md:col-span-3">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Status Pelaksanaan</label>
                <select name="status_pelaksanaan" class="w-full text-xs py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70]">
                    <option value="all">Semua Status</option>
                    @foreach($statusOptions as $st => $stBadge)
                        <option value="{{ $st }}" {{ request('status_pelaksanaan') === $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Prioritas -->
            <div class="md:col-span-2">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Prioritas</label>
                <select name="tingkat_prioritas" class="w-full text-xs py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70]">
                    <option value="all">Semua Prioritas</option>
                    @foreach($prioritasOptions as $pr => $prBadge)
                        <option value="{{ $pr }}" {{ request('tingkat_prioritas') === $pr ? 'selected' : '' }}>{{ $pr }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tahun Anggaran -->
            <div class="md:col-span-1">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Tahun</label>
                <select name="tahun" class="w-full text-xs py-2 px-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70]">
                    <option value="">Semua</option>
                    @for($y = date('Y') + 1; $y >= 2023; $y--)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <!-- Actions -->
            <div class="md:col-span-2 flex items-end space-x-2">
                <button type="submit" class="w-full py-2 px-3 bg-[#003B70] hover:bg-[#00264A] text-white text-xs font-bold rounded-xl shadow transition-all">
                    Filter Data
                </button>
                <a href="{{ route('sekretariat.data.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all" title="Reset Filter">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Daftar Agenda & Rekam Fungsi Sekretaris Stasiun</h3>
                <p class="text-xs text-slate-500">Menampilkan {{ $agendaList->firstItem() ?? 0 }} - {{ $agendaList->lastItem() ?? 0 }} dari total {{ $agendaList->total() }} data</p>
            </div>
            @if(request()->filled('kategori_fungsi') && request('kategori_fungsi') !== 'all')
                <div class="flex items-center space-x-2">
                    <span class="text-xs font-medium text-slate-500">Filter Aktif:</span>
                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg {{ $allFungsi[request('kategori_fungsi')]['badge'] ?? 'bg-blue-100 text-blue-800' }}">
                        {{ $allFungsi[request('kategori_fungsi')]['label'] ?? request('kategori_fungsi') }}
                    </span>
                </div>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3.5">No. Agenda & Fungsi</th>
                        <th class="px-4 py-3.5">Judul Kegiatan & Sub Tugas</th>
                        <th class="px-4 py-3.5">PIC / Bidang Terkait</th>
                        <th class="px-4 py-3.5">Waktu & Tahun</th>
                        <th class="px-4 py-3.5 text-right">Alokasi / Realisasi</th>
                        <th class="px-4 py-3.5 text-center">Capaian</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5 text-center">Berkas</th>
                        <th class="px-4 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($agendaList as $item)
                        @php
                            $meta = $item->fungsi_meta;
                            $statusBadge = $statusOptions[$item->status_pelaksanaan] ?? 'bg-gray-100 text-gray-800 border-gray-300';
                            $prioritasBadge = $prioritasOptions[$item->tingkat_prioritas] ?? 'bg-gray-50 text-gray-700';
                        @endphp
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <!-- Nomor Agenda & Fungsi -->
                            <td class="px-4 py-3">
                                <div class="font-mono font-bold text-[#003B70] text-xs">{{ $item->nomor_agenda }}</div>
                                <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold border {{ $meta['badge'] }}">
                                    {{ $meta['label'] }}
                                </span>
                            </td>

                            <!-- Judul Kegiatan & Sub Tugas -->
                            <td class="px-4 py-3 max-w-xs">
                                <a href="{{ route('sekretariat.data.show', $item->id) }}" class="font-bold text-slate-800 hover:text-[#003B70] line-clamp-1">
                                    {{ $item->judul_kegiatan }}
                                </a>
                                <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5 font-medium">
                                    • {{ $item->sub_kegiatan }}
                                </p>
                                @if($item->catatan_arahan_sekretaris)
                                    <div class="mt-1 flex items-center space-x-1 text-[10px] text-amber-700 font-semibold bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200/60 max-w-fit">
                                        <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                        <span class="truncate max-w-[200px]" title="{{ $item->catatan_arahan_sekretaris }}">Arahan: {{ $item->catatan_arahan_sekretaris }}</span>
                                    </div>
                                @endif
                            </td>

                            <!-- PIC / Bidang Terkait -->
                            <td class="px-4 py-3 text-slate-700">
                                <div class="font-semibold text-slate-800">{{ $item->penanggung_jawab }}</div>
                                <div class="text-[11px] text-slate-500">{{ $item->bidang_unit_terkait ?? 'Sekretariat Dinas' }}</div>
                            </td>

                            <!-- Waktu & Tahun -->
                            <td class="px-4 py-3 text-slate-600 whitespace-nowrap">
                                <div>{{ $item->tanggal_kegiatan ? $item->tanggal_kegiatan->format('d/m/Y') : '-' }}</div>
                                <div class="text-[10px] text-slate-400 font-medium">TA {{ $item->tahun_anggaran }}</div>
                            </td>

                            <!-- Alokasi / Realisasi -->
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                @if($item->alokasi_anggaran > 0)
                                    <div class="font-bold text-slate-800">Rp {{ number_format($item->alokasi_anggaran, 0, ',', '.') }}</div>
                                    <div class="text-[11px] text-emerald-600 font-semibold">Real: Rp {{ number_format($item->realisasi_anggaran, 0, ',', '.') }}</div>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Non-Budget</span>
                                @endif
                            </td>

                            <!-- Capaian Progres Bar -->
                            <td class="px-4 py-3 text-center">
                                <div class="w-16 mx-auto">
                                    <div class="flex items-center justify-between text-[10px] font-bold text-slate-700 mb-0.5">
                                        <span>{{ $item->persentase_capaian }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                        <div class="h-1.5 rounded-full {{ $item->persentase_capaian >= 100 ? 'bg-emerald-500' : ($item->persentase_capaian >= 50 ? 'bg-blue-500' : 'bg-amber-500') }}" 
                                             style="width: {{ min(100, $item->persentase_capaian) }}%"></div>
                                    </div>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg border {{ $statusBadge }}">
                                    {{ $item->status_pelaksanaan }}
                                </span>
                                <div class="mt-1">
                                    <span class="px-1.5 py-0.2 text-[9px] font-semibold rounded border {{ $prioritasBadge }}">
                                        {{ $item->tingkat_prioritas }}
                                    </span>
                                </div>
                            </td>

                            <!-- Berkas -->
                            <td class="px-4 py-3 text-center">
                                @if($item->file_dokumen)
                                    <a href="{{ route('sekretariat.data.download', $item->id) }}" class="inline-flex items-center space-x-1 px-2 py-1 bg-blue-50 hover:bg-blue-100 text-[#003B70] rounded-lg border border-blue-200 text-[11px] font-bold transition-all" title="{{ $item->file_nama_asli }}">
                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        <span>Unduh</span>
                                    </a>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center space-x-1">
                                    <!-- View -->
                                    <a href="{{ route('sekretariat.data.show', $item->id) }}" class="p-1.5 bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-[#003B70] rounded-lg transition-colors" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>

                                    <!-- Edit -->
                                    <a href="{{ route('sekretariat.data.edit', $item->id) }}" class="p-1.5 bg-slate-100 hover:bg-amber-100 text-slate-600 hover:text-amber-800 rounded-lg transition-colors" title="Edit Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>

                                    <!-- Delete -->
                                    <form action="{{ route('sekretariat.data.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data agenda ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-slate-100 hover:bg-rose-100 text-slate-600 hover:text-rose-700 rounded-lg transition-colors" title="Hapus Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-12 text-center text-slate-400">
                                <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <p class="text-sm font-bold text-slate-600">Belum ada data agenda atau tugas fungsi tercatat</p>
                                <p class="text-xs text-slate-400 mt-1">Gunakan tombol "+ Tambah Data Agenda / Fungsi" di atas untuk menambahkan data baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($agendaList->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $agendaList->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
