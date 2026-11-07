@extends('layouts.app')

@section('title', 'Statistik & Analisis Data - TVRI Maluku')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs text-slate-500">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 font-medium">Dashboard</a>
                <span>/</span>
                <span class="text-slate-800 font-bold">Statistik & Analisis</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-[#00264A] tracking-tight mt-1">
                Statistik & Analisis Komparatif
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Visualisasi data spasial & sektoral di 11 Kabupaten / Kota Provinsi Maluku.
            </p>
        </div>

        <!-- Filter & Print -->
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak Grafik</span>
            </button>
            <a href="{{ route('laporan.terpadu') }}" class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-[#00264A] hover:bg-[#003B70] text-white text-xs font-bold shadow transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Lihat Laporan Terpadu</span>
            </a>
        </div>
    </div>

    <!-- 4 Key Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Satuan Pendidikan -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Satuan Pendidikan</span>
                <div class="text-2xl font-black text-[#00264A] mt-1">{{ number_format($totalSekolah) }}</div>
                <div class="text-[11px] text-blue-600 font-semibold mt-0.5">SMA, SMK, SLB Tersebar</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#005B9F] flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
        </div>

        <!-- Card 2: Peserta Didik -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Peserta Didik</span>
                <div class="text-2xl font-black text-purple-950 mt-1">{{ number_format($totalSiswa) }}</div>
                <div class="text-[11px] text-purple-600 font-semibold mt-0.5">{{ number_format($totalGuru) }} Guru & Tenaga Pendidik</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>

        <!-- Card 3: Layanan Administrasi -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Volume Pelayanan</span>
                <div class="text-2xl font-black text-emerald-950 mt-1">{{ number_format($totalLayanan) }}</div>
                <div class="text-[11px] text-emerald-600 font-semibold mt-0.5">6 Unit & Bidang Kerja</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Card 4: Anggaran & Realisasi -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Serapan Anggaran</span>
                <div class="text-2xl font-black text-amber-950 mt-1">{{ $persenRealisasi }}%</div>
                <div class="text-[11px] text-amber-600 font-semibold mt-0.5 truncate max-w-[140px]">
                    Rp {{ number_format($totalRealisasi, 0, ',', '.') }}
                </div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Charts Grid Row 1 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Chart 1: Distribusi Sekolah per Kabupaten/Kota -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h2 class="text-sm font-bold text-[#00264A]">Distribusi Satuan Pendidikan</h2>
                    <p class="text-[11px] text-slate-500">Sebaran SMA, SMK, dan SLB di 11 Kabupaten/Kota</p>
                </div>
                <span class="px-2 py-0.5 bg-blue-50 text-blue-700 font-bold text-[10px] rounded">11 Wilayah</span>
            </div>
            <div class="relative h-72">
                <canvas id="chartSekolahKab"></canvas>
            </div>
        </div>

        <!-- Chart 2: Komposisi Siswa per Kabupaten/Kota -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h2 class="text-sm font-bold text-[#00264A]">Sebaran Jumlah Peserta Didik</h2>
                    <p class="text-[11px] text-slate-500">Akumulasi siswa aktif berdasarkan wilayah cabang dinas</p>
                </div>
                <span class="px-2 py-0.5 bg-purple-50 text-purple-700 font-bold text-[10px] rounded">Data Pokok</span>
            </div>
            <div class="relative h-72">
                <canvas id="chartSiswaKab"></canvas>
            </div>
        </div>

    </div>

    <!-- Charts Grid Row 2 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Chart 3: Proporsi Volume Pelayanan Administrasi -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h2 class="text-sm font-bold text-[#00264A]">Proporsi Layanan Antar Bidang</h2>
                    <p class="text-[11px] text-slate-500">Komposisi dokumen dan berkas layanan publik terdaftar</p>
                </div>
                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 font-bold text-[10px] rounded">6 Unit Kerja</span>
            </div>
            <div class="relative h-72 flex items-center justify-center">
                <canvas id="chartLayananBidang"></canvas>
            </div>
        </div>

        <!-- Chart 4: Alokasi & Realisasi Anggaran per Bidang (Juta Rp) -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h2 class="text-sm font-bold text-[#00264A]">Pagu vs Realisasi Anggaran</h2>
                    <p class="text-[11px] text-slate-500">Perbandingan alokasi pagu dan serapan anggaran (Juta Rp)</p>
                </div>
                <span class="px-2 py-0.5 bg-amber-50 text-amber-700 font-bold text-[10px] rounded">SIPD Data</span>
            </div>
            <div class="relative h-72">
                <canvas id="chartAnggaranBidang"></canvas>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const kabLabels = {!! json_encode($kabupatens) !!};
    const sekolahData = {!! json_encode($sekolahPerKab) !!};
    const siswaData = {!! json_encode($siswaPerKab) !!};
    const layananLabels = {!! json_encode($layananLabels) !!};
    const layananCounts = {!! json_encode($layananCounts) !!};
    const bidangList = {!! json_encode($bidangList) !!};
    const paguData = {!! json_encode($paguPerBidang) !!};
    const realisasiData = {!! json_encode($realisasiPerBidang) !!};

    // Chart 1: Sekolah per Kab
    const ctxSekolah = document.getElementById('chartSekolahKab');
    if (ctxSekolah) {
        new Chart(ctxSekolah, {
            type: 'bar',
            data: {
                labels: kabLabels,
                datasets: [{
                    label: 'Jumlah Sekolah',
                    data: sekolahData,
                    backgroundColor: '#005B9F',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#F1F5F9' } },
                    x: { ticks: { maxRotation: 45, minRotation: 30, font: { size: 9 } }, grid: { display: false } }
                }
            }
        });
    }

    // Chart 2: Siswa per Kab
    const ctxSiswa = document.getElementById('chartSiswaKab');
    if (ctxSiswa) {
        new Chart(ctxSiswa, {
            type: 'line',
            data: {
                labels: kabLabels,
                datasets: [{
                    label: 'Jumlah Peserta Didik',
                    data: siswaData,
                    borderColor: '#7C3AED',
                    backgroundColor: 'rgba(124, 58, 237, 0.1)',
                    fill: true,
                    tension: 0.3,
                    borderWidth: 2,
                    pointBackgroundColor: '#7C3AED'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#F1F5F9' } },
                    x: { ticks: { maxRotation: 45, minRotation: 30, font: { size: 9 } }, grid: { display: false } }
                }
            }
        });
    }

    // Chart 3: Layanan Bidang
    const ctxLayanan = document.getElementById('chartLayananBidang');
    if (ctxLayanan) {
        new Chart(ctxLayanan, {
            type: 'doughnut',
            data: {
                labels: layananLabels,
                datasets: [{
                    data: layananCounts,
                    backgroundColor: [
                        '#005B9F',
                        '#06B6D4',
                        '#F59E0B',
                        '#6366F1',
                        '#10B981',
                        '#8B5CF6'
                    ],
                    borderWidth: 2,
                    borderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10, weight: 'bold' } } }
                }
            }
        });
    }

    // Chart 4: Anggaran Bidang
    const ctxAnggaran = document.getElementById('chartAnggaranBidang');
    if (ctxAnggaran) {
        new Chart(ctxAnggaran, {
            type: 'bar',
            data: {
                labels: bidangList,
                datasets: [
                    {
                        label: 'Pagu (Juta Rp)',
                        data: paguData,
                        backgroundColor: '#00264A',
                        borderRadius: 4
                    },
                    {
                        label: 'Realisasi (Juta Rp)',
                        data: realisasiData,
                        backgroundColor: '#10B981',
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { boxWidth: 12, font: { size: 10, weight: 'bold' } } }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#F1F5F9' } },
                    x: { ticks: { maxRotation: 45, minRotation: 30, font: { size: 9 } }, grid: { display: false } }
                }
            }
        });
    }
});
</script>
@endpush
