const fs = require('fs');
const path = require('path');

// Helper to ensure dir exists
function ensureDir(filePath) {
    const dir = path.dirname(filePath);
    if (!fs.existsSync(dir)) {
        fs.mkdirSync(dir, { recursive: true });
    }
}

// 1. Pendidikan Dashboard View
const dashboardView = `@extends('layouts.app')

@section('title', 'Dashboard Pendidikan - Provinsi Maluku')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-[#003B70] via-[#005B9F] to-[#00264A] rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <span class="inline-flex items-center space-x-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-[11px] font-bold text-blue-200 uppercase tracking-widest border border-white/10 mb-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Pangkalan Data Sektoral &bull; Tahap 3</span>
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                    PANGKALAN DATA PENDIDIKAN
                </h1>
                <p class="text-xs sm:text-sm text-blue-100 font-medium mt-1">
                    Dinas Pendidikan dan Kebudayaan Provinsi Maluku &bull; 11 Kabupaten/Kota
                </p>
            </div>
            
            <div class="flex items-center space-x-2">
                <a href="{{ route('pendidikan.schools.create') }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-2xl shadow-sm transition flex items-center space-x-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Tambah Sekolah</span>
                </a>
                <a href="{{ route('pendidikan.import.index') }}" class="px-4 py-2.5 bg-white/15 hover:bg-white/25 text-white text-xs font-bold rounded-2xl border border-white/20 transition flex items-center space-x-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span>Import Excel</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('pendidikan.dashboard') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Tahun Anggaran</label>
                <select name="tahun" class="w-full text-xs font-semibold rounded-xl border-slate-200 focus:border-[#005B9F] focus:ring-[#005B9F] bg-slate-50/50 py-2">
                    <option value="2026" {{ $tahun == '2026' ? 'selected' : '' }}>2026 (Tahun Berjalan)</option>
                    <option value="2025" {{ $tahun == '2025' ? 'selected' : '' }}>2025</option>
                    <option value="2024" {{ $tahun == '2024' ? 'selected' : '' }}>2024</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Kabupaten / Kota</label>
                <select name="kabupaten" class="w-full text-xs font-semibold rounded-xl border-slate-200 focus:border-[#005B9F] focus:ring-[#005B9F] bg-slate-50/50 py-2">
                    <option value="all">Semua Kabupaten/Kota</option>
                    @foreach($kabupatenList as $kab)
                        <option value="{{ $kab }}" {{ $kabupaten == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Jenjang Pendidikan</label>
                <select name="jenjang" class="w-full text-xs font-semibold rounded-xl border-slate-200 focus:border-[#005B9F] focus:ring-[#005B9F] bg-slate-50/50 py-2">
                    <option value="all">Semua Jenjang</option>
                    @foreach(['PAUD', 'TK', 'SD', 'SMP', 'SMA', 'SMK', 'SLB'] as $j)
                        <option value="{{ $j }}" {{ $jenjang == $j ? 'selected' : '' }}>{{ $j }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="w-full bg-[#003B70] hover:bg-[#00264A] text-white font-bold text-xs py-2 px-3 rounded-xl transition flex items-center justify-center space-x-1 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <span>Filter</span>
                </button>
                <a href="{{ route('pendidikan.dashboard') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs py-2 px-3 rounded-xl transition flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- 4 Key Education Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total Sekolah -->
        <a href="{{ route('pendidikan.schools.index') }}" class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md hover:border-blue-300 transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Sekolah</p>
                    <h3 class="text-3xl font-black text-[#00264A] mt-1 group-hover:text-[#005B9F] transition">{{ number_format($stats['total_sekolah']) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#003B70] flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>
            <p class="text-[11px] text-slate-400 mt-3 font-medium">Satuan pendidikan terdata &rarr;</p>
        </a>

        <!-- Total Guru -->
        <a href="{{ route('pendidikan.teachers.index') }}" class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md hover:border-teal-300 transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Guru (GTK)</p>
                    <h3 class="text-3xl font-black text-[#00264A] mt-1 group-hover:text-teal-700 transition">{{ number_format($stats['total_guru']) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
            </div>
            <p class="text-[11px] text-slate-400 mt-3 font-medium">Pendidik & tenaga kependidikan &rarr;</p>
        </a>

        <!-- Total Peserta Didik -->
        <a href="{{ route('pendidikan.students.index') }}" class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md hover:border-emerald-300 transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Peserta Didik</p>
                    <h3 class="text-3xl font-black text-emerald-700 mt-1">{{ number_format($stats['total_siswa']) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <p class="text-[11px] text-slate-400 mt-3 font-medium">Siswa terdaftar aktif &rarr;</p>
        </a>

        <!-- Total Sarpras -->
        <a href="{{ route('pendidikan.sarpras.index') }}" class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md hover:border-amber-300 transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Sarpras</p>
                    <h3 class="text-3xl font-black text-amber-700 mt-1">{{ number_format($stats['total_sarpras']) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path></svg>
                </div>
            </div>
            <p class="text-[11px] text-slate-400 mt-3 font-medium">Fasilitas & gedung sekolah &rarr;</p>
        </a>

    </div>

    <!-- 3 Education Charts: Sekolah per Kab, Guru per Kab, Siswa per Kab -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Chart 1: Sekolah per Kabupaten/Kota -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-xs font-extrabold text-[#00264A] uppercase tracking-wider">Sekolah per Wilayah</h3>
                    <p class="text-[10px] text-slate-400 font-medium">Distribusi 11 kabupaten/kota</p>
                </div>
                <span class="px-2 py-0.5 rounded bg-blue-50 text-[#003B70] text-[9px] font-bold">Sekolah</span>
            </div>
            <div class="h-60">
                <canvas id="chartSekolahPerKab"></canvas>
            </div>
        </div>

        <!-- Chart 2: Guru per Kabupaten/Kota -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-xs font-extrabold text-[#00264A] uppercase tracking-wider">Guru per Wilayah</h3>
                    <p class="text-[10px] text-slate-400 font-medium">Distribusi tenaga pendidik</p>
                </div>
                <span class="px-2 py-0.5 rounded bg-teal-50 text-teal-700 text-[9px] font-bold">GTK</span>
            </div>
            <div class="h-60">
                <canvas id="chartGuruPerKab"></canvas>
            </div>
        </div>

        <!-- Chart 3: Peserta Didik per Kabupaten/Kota -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-xs font-extrabold text-[#00264A] uppercase tracking-wider">Siswa per Wilayah</h3>
                    <p class="text-[10px] text-slate-400 font-medium">Distribusi peserta didik aktif</p>
                </div>
                <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 text-[9px] font-bold">Siswa</span>
            </div>
            <div class="h-60">
                <canvas id="chartSiswaPerKab"></canvas>
            </div>
        </div>

    </div>

    <!-- 2 Additional Visualizations: Jenjang & Akreditasi -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <h3 class="text-xs font-extrabold text-[#00264A] uppercase tracking-wider mb-4">Distribusi Jenjang Pendidikan</h3>
            <div class="h-60">
                <canvas id="chartJenjang"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <h3 class="text-xs font-extrabold text-[#00264A] uppercase tracking-wider mb-4">Status Akreditasi Sekolah</h3>
            <div class="h-60 flex items-center justify-center">
                <canvas id="chartAkreditasi"></canvas>
            </div>
        </div>

    </div>

</div>

<!-- Chart.js Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const kabLabels = {!! json_encode($charts['kabupaten_labels']) !!};

    // 1. Sekolah per Kab
    new Chart(document.getElementById('chartSekolahPerKab'), {
        type: 'bar',
        data: {
            labels: kabLabels,
            datasets: [{
                label: 'Jumlah Sekolah',
                data: {!! json_encode($charts['schools_per_kab']) !!},
                backgroundColor: '#003B70',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#F1F5F9' } },
                x: { ticks: { font: { size: 8 }, maxRotation: 45, minRotation: 45 }, grid: { display: false } }
            }
        }
    });

    // 2. Guru per Kab
    new Chart(document.getElementById('chartGuruPerKab'), {
        type: 'bar',
        data: {
            labels: kabLabels,
            datasets: [{
                label: 'Jumlah Guru',
                data: {!! json_encode($charts['teachers_per_kab']) !!},
                backgroundColor: '#0F766E',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#F1F5F9' } },
                x: { ticks: { font: { size: 8 }, maxRotation: 45, minRotation: 45 }, grid: { display: false } }
            }
        }
    });

    // 3. Siswa per Kab
    new Chart(document.getElementById('chartSiswaPerKab'), {
        type: 'bar',
        data: {
            labels: kabLabels,
            datasets: [{
                label: 'Jumlah Siswa',
                data: {!! json_encode($charts['students_per_kab']) !!},
                backgroundColor: '#059669',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#F1F5F9' } },
                x: { ticks: { font: { size: 8 }, maxRotation: 45, minRotation: 45 }, grid: { display: false } }
            }
        }
    });

    // 4. Jenjang
    new Chart(document.getElementById('chartJenjang'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($charts['jenjang_labels']) !!},
            datasets: [{
                label: 'Sekolah',
                data: {!! json_encode($charts['jenjang_counts']) !!},
                backgroundColor: '#005B9F',
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true },
                x: { grid: { display: false } }
            }
        }
    });

    // 5. Akreditasi
    new Chart(document.getElementById('chartAkreditasi'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($charts['akreditasi_labels']) !!},
            datasets: [{
                data: {!! json_encode($charts['akreditasi_counts']) !!},
                backgroundColor: ['#10B981', '#005B9F', '#F59E0B', '#94A3B8'],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'right' } }
        }
    });
});
</script>
@endsection
`;

// Write Dashboard View
ensureDir('resources/views/pendidikan/dashboard.blade.php');
fs.writeFileSync('resources/views/pendidikan/dashboard.blade.php', dashboardView, 'utf8');

console.log('Dashboard Pendidikan view generated!');
`;

fs.writeFileSync('create_views_stage3.cjs', createScript, 'utf8');
console.log('Script updated');