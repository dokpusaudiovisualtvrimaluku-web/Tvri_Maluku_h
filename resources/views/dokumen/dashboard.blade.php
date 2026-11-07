@extends('layouts.app')

@section('title', 'Dashboard Dokumen & Arsip Digital - TVRI Maluku')

@section('content')
<div class="space-y-6">

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center justify-between text-emerald-800 text-xs font-bold shadow-sm">
            <div class="flex items-center space-x-2.5">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
            <span class="text-[11px] text-emerald-600 font-mono">Tersinkronisasi Real-Time</span>
        </div>
    @endif

    <!-- Header & Welcome Banner -->
    <div class="bg-gradient-to-r from-[#00264A] via-[#003B70] to-[#005B9F] rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 w-96 h-96 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <span class="px-3 py-1 bg-rose-500/20 text-rose-300 border border-rose-500/40 rounded-full text-xs font-bold tracking-wider uppercase">
                        Tahap 5 • Document Management System (DMS)
                    </span>
                    <span class="text-xs text-blue-200">Satu Data Terintegrasi</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight text-white">
                    Pusat Dokumen & Arsip Digital Terpadu
                </h1>
                <p class="text-sm text-blue-100 max-w-2xl mt-1">
                    Repositori naskah dinas, SK, regulasi hukum, kajian cagar budaya, dan data pokok terverifikasi dengan QR Code & Versioning System.
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('dokumen.documents.create') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-lg hover:shadow-emerald-600/30 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Unggah Dokumen</span>
                </a>
                <a href="{{ route('dokumen.folders.index') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-xl border border-white/20 backdrop-blur-md transition-all duration-200">
                    <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                    <span>Folder Explorer</span>
                </a>
                <a href="{{ route('dokumen.documents.index') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-xl border border-white/20 backdrop-blur-md transition-all duration-200">
                    <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    <span>Daftar Dokumen</span>
                </a>
                <a href="#sop" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-teal-500/20 hover:bg-teal-500/30 text-teal-200 text-xs font-bold rounded-xl border border-teal-400/30 backdrop-blur-md transition-all duration-200">
                    <svg class="w-4 h-4 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Dokumen SOP ({{ count($sops) }})</span>
                </a>
            </div>
        </div>

        <!-- Global Filter Bar -->
        <form method="GET" action="{{ route('dokumen.dashboard') }}" class="mt-6 pt-5 border-t border-white/15 grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label class="block text-[11px] font-semibold text-blue-200 mb-1">Filter Tahun</label>
                <select name="year" onchange="this.form.submit()" class="w-full bg-[#001D38]/80 text-white text-xs rounded-xl border border-blue-400/30 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="">Semua Tahun (Histori Lengkap)</option>
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $yearFilter == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-blue-200 mb-1">Filter Bidang / Unit Kerja</label>
                <select name="division" onchange="this.form.submit()" class="w-full bg-[#001D38]/80 text-white text-xs rounded-xl border border-blue-400/30 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="">Semua Bidang & Sekretariat</option>
                    @foreach($availableDivisions as $div)
                        <option value="{{ $div }}" {{ $divisionFilter == $div ? 'selected' : '' }}>{{ $div }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <a href="{{ route('dokumen.dashboard') }}" class="w-full text-center px-3 py-2 bg-blue-900/40 hover:bg-blue-900/70 border border-blue-400/30 text-xs font-semibold rounded-xl text-blue-200 transition-colors">
                    Reset Filter
                </a>
            </div>
        </form>
    </div>

    @if(auth()->user() && (auth()->user()->hasRole('ADMIN') || auth()->user()->hasRole('Super Admin') || in_array(auth()->user()->username, ['admin', 'superadmin'])))
    <!-- Panel Khusus Super Admin: Pengaturan & Sinkronisasi Database Statistik Pengunjung TVRI Maluku Digital Hub -->
    <div x-data="{ openForm: true }" class="bg-gradient-to-br from-slate-900 via-[#001D38] to-[#00264A] rounded-2xl p-6 text-white shadow-xl border border-blue-500/30 relative overflow-hidden">
        <div class="absolute -right-12 -top-12 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-5 border-b border-white/10">
                <div class="flex items-start sm:items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-amber-400/20 border border-amber-400/40 text-amber-300 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-400/20 text-amber-300 border border-amber-400/40">
                                Super Admin Control
                            </span>
                            <span class="text-[11px] text-emerald-400 flex items-center gap-1 font-semibold">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                Live Real-Time Sync
                            </span>
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-white mt-0.5">
                            Kelola Database Statistik Pengunjung TVRI Maluku Digital Hub
                        </h2>
                        <p class="text-xs text-blue-200/90 mt-0.5">
                            Pembaruan data pada panel ini tersimpan langsung di database dan otomatis memperbarui tampilan kartu statistik footer di portal publik secara real-time.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="#katalog-data-operasional" class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-600/30 hover:bg-emerald-600/50 border border-emerald-400/40 rounded-xl text-xs font-bold text-emerald-200 hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span>Kotak Input & Edit Katalog</span>
                    </a>
                    <a href="{{ url('/') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-600/30 hover:bg-blue-600/50 border border-blue-400/30 rounded-xl text-xs font-bold text-blue-200 hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        <span>Lihat Portal Publik</span>
                    </a>
                    <button type="button" @click="openForm = !openForm" class="px-3 py-2 bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl text-xs font-bold text-white transition-colors">
                        <span x-text="openForm ? 'Sembunyikan Form' : 'Buka Form Edit'"></span>
                    </button>
                </div>
            </div>

            <!-- Current Real-Time Metrics Display -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 my-5">
                <!-- Hari Ini -->
                <div class="bg-black/40 backdrop-blur-md rounded-xl p-4 border border-white/10">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-blue-300">Hari Ini</span>
                        <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-blue-500/20 text-blue-200 border border-blue-400/30">Harian</span>
                    </div>
                    <div class="flex items-baseline justify-between">
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-white font-mono leading-none">
                                {{ number_format($visitorStat->hari_ini_kunjungan, 0, '.', ',') }}
                            </div>
                            <div class="text-[10px] text-blue-200/70 mt-1">Total Kunjungan</div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg sm:text-xl font-bold text-amber-300 font-mono leading-none">
                                {{ number_format($visitorStat->hari_ini_unik, 0, '.', ',') }}
                            </div>
                            <div class="text-[10px] text-amber-200/70 mt-1">Pengunjung Unik</div>
                        </div>
                    </div>
                </div>

                <!-- Bulan Ini -->
                <div class="bg-black/40 backdrop-blur-md rounded-xl p-4 border border-white/10">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-300">Bulan Ini</span>
                        <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-500/20 text-emerald-200 border border-emerald-400/30">Bulanan</span>
                    </div>
                    <div class="flex items-baseline justify-between">
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-white font-mono leading-none">
                                {{ number_format($visitorStat->bulan_ini_kunjungan, 0, '.', ',') }}
                            </div>
                            <div class="text-[10px] text-blue-200/70 mt-1">Total Kunjungan</div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg sm:text-xl font-bold text-amber-300 font-mono leading-none">
                                {{ number_format($visitorStat->bulan_ini_unik, 0, '.', ',') }}
                            </div>
                            <div class="text-[10px] text-amber-200/70 mt-1">Pengunjung Unik</div>
                        </div>
                    </div>
                </div>

                <!-- Total Kunjungan -->
                <div class="bg-black/40 backdrop-blur-md rounded-xl p-4 border border-white/10">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-purple-300">Total Akumulasi</span>
                        <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-purple-500/20 text-purple-200 border border-purple-400/30">Sepanjang Waktu</span>
                    </div>
                    <div class="flex items-baseline justify-between">
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-white font-mono leading-none">
                                {{ number_format($visitorStat->total_kunjungan, 0, '.', ',') }}
                            </div>
                            <div class="text-[10px] text-blue-200/70 mt-1">Total Kunjungan</div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg sm:text-xl font-bold text-amber-300 font-mono leading-none">
                                {{ number_format($visitorStat->total_unik, 0, '.', ',') }}
                            </div>
                            <div class="text-[10px] text-amber-200/70 mt-1">Pengunjung Unik</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Edit & Update Database -->
            <form x-show="openForm" action="{{ route('dokumen.visitor-stats.update') }}" method="POST" class="pt-4 border-t border-white/10">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Hari Ini Kunjungan & Unik -->
                    <div class="bg-white/5 rounded-xl p-3.5 border border-white/10 space-y-2.5">
                        <div class="text-xs font-bold text-blue-200 flex items-center justify-between">
                            <span>Statistik Hari Ini</span>
                            <span class="text-[10px] text-slate-400 font-mono">Patokan: 248 / 236</span>
                        </div>
                        <div class="space-y-2">
                            <div>
                                <label class="block text-[11px] text-slate-300 mb-1">Total Kunjungan Hari Ini</label>
                                <input type="number" name="hari_ini_kunjungan" value="{{ $visitorStat->hari_ini_kunjungan }}" required min="0"
                                       class="w-full bg-[#001428] text-white font-mono text-sm px-3 py-2 rounded-lg border border-blue-400/30 focus:border-amber-400 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-[11px] text-slate-300 mb-1">Pengunjung Unik Hari Ini</label>
                                <input type="number" name="hari_ini_unik" value="{{ $visitorStat->hari_ini_unik }}" required min="0"
                                       class="w-full bg-[#001428] text-white font-mono text-sm px-3 py-2 rounded-lg border border-blue-400/30 focus:border-amber-400 focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- Bulan Ini Kunjungan & Unik -->
                    <div class="bg-white/5 rounded-xl p-3.5 border border-white/10 space-y-2.5">
                        <div class="text-xs font-bold text-emerald-200 flex items-center justify-between">
                            <span>Statistik Bulan Ini</span>
                            <span class="text-[10px] text-slate-400 font-mono">Patokan: 5,420 / 4,890</span>
                        </div>
                        <div class="space-y-2">
                            <div>
                                <label class="block text-[11px] text-slate-300 mb-1">Total Kunjungan Bulan Ini</label>
                                <input type="number" name="bulan_ini_kunjungan" value="{{ $visitorStat->bulan_ini_kunjungan }}" required min="0"
                                       class="w-full bg-[#001428] text-white font-mono text-sm px-3 py-2 rounded-lg border border-blue-400/30 focus:border-amber-400 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-[11px] text-slate-300 mb-1">Pengunjung Unik Bulan Ini</label>
                                <input type="number" name="bulan_ini_unik" value="{{ $visitorStat->bulan_ini_unik }}" required min="0"
                                       class="w-full bg-[#001428] text-white font-mono text-sm px-3 py-2 rounded-lg border border-blue-400/30 focus:border-amber-400 focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- Total Kunjungan & Unik -->
                    <div class="bg-white/5 rounded-xl p-3.5 border border-white/10 space-y-2.5">
                        <div class="text-xs font-bold text-purple-200 flex items-center justify-between">
                            <span>Total Akumulasi</span>
                            <span class="text-[10px] text-slate-400 font-mono">Patokan: 168,940 / 142,300</span>
                        </div>
                        <div class="space-y-2">
                            <div>
                                <label class="block text-[11px] text-slate-300 mb-1">Total Seluruh Kunjungan</label>
                                <input type="number" name="total_kunjungan" value="{{ $visitorStat->total_kunjungan }}" required min="0"
                                       class="w-full bg-[#001428] text-white font-mono text-sm px-3 py-2 rounded-lg border border-blue-400/30 focus:border-amber-400 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-[11px] text-slate-300 mb-1">Total Seluruh Pengunjung Unik</label>
                                <input type="number" name="total_unik" value="{{ $visitorStat->total_unik }}" required min="0"
                                       class="w-full bg-[#001428] text-white font-mono text-sm px-3 py-2 rounded-lg border border-blue-400/30 focus:border-amber-400 focus:outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-4 mt-4 border-t border-white/10">
                    <div class="text-[11px] text-slate-300">
                        <span>Pembaruan terakhir: <strong class="text-amber-300">{{ $visitorStat->updated_at ? $visitorStat->updated_at->format('d/m/Y H:i:s') : 'Baru saja' }}</strong></span>
                        @if($visitorStat->updated_by)
                            <span class="ml-2">• Oleh: <strong class="text-blue-300">{{ $visitorStat->updated_by }}</strong></span>
                        @endif
                    </div>
                    <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-xs font-black rounded-xl shadow-lg hover:shadow-emerald-600/30 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Quick Action: Hapus Data / Reset ke 0 & Kembalikan Patokan -->
            <div class="pt-4 mt-4 border-t border-white/10 flex flex-wrap items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-2 text-slate-300 text-[11px]">
                    <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Aksi Cepat Super Admin: Kosongkan untuk memulai hitungan dari 0 atau kembalikan ke patokan awal.</span>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <form action="{{ route('dokumen.visitor-stats.reset') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan / mereset semua data pengunjung ke 0? Real-time tracker akan mulai menghitung kunjungan baru yang masuk.');">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-600/30 hover:bg-rose-600/60 border border-rose-500/40 text-rose-200 hover:text-white rounded-lg text-xs font-bold transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>Hapus / Kosongkan Data (Mulai dari 0)</span>
                        </button>
                    </form>

                    <form action="{{ route('dokumen.visitor-stats.patokan') }}" method="POST" onsubmit="return confirm('Kembalikan ke data patokan TVRI Maluku (248 kunjungan hari ini / 5,420 bulan ini / 168,940 total)?');">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-500/20 hover:bg-amber-500/40 border border-amber-400/30 text-amber-200 hover:text-white rounded-lg text-xs font-bold transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span>Terapkan Patokan TVRI</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Master Database: Katalog Data Operasional TVRI Maluku -->
    <div id="katalog-data-operasional" class="bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-slate-100" x-data="datasetManager({{ Js::from($katalogDatasets) }})">
        <!-- Hidden Form for Bulk Updating Inline Table Inputs -->
        <form id="bulkUpdateDatasetForm" action="{{ Route::has('dokumen.datasets.bulk-update') ? route('dokumen.datasets.bulk-update') : url('dokumen/datasets/bulk-update') }}" method="POST">
            @csrf
        </form>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5 pb-4 border-b border-slate-100">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <h2 class="text-base font-black text-[#00264A]">Katalog Data Operasional TVRI Maluku</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 font-mono">
                            {{ count($katalogDatasets) }} Dataset Terverifikasi
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Database master dataset operasional LPP TVRI Stasiun Maluku yang disajikan pada portal publik terintegrasi.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <!-- Simpan Semua Perubahan (Kotak Input Inline) -->
                <button type="submit" form="bulkUpdateDatasetForm" class="inline-flex items-center space-x-1.5 px-3.5 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-xs font-black rounded-xl shadow-md hover:shadow-emerald-600/30 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan Perubahan</span>
                </button>

                <!-- Tombol Buka/Tutup Kotak Input Data Baru -->
                <button type="button" @click="showAddBox = !showAddBox" class="inline-flex items-center space-x-1.5 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span x-text="showAddBox ? 'Tutup Kotak Input' : '+ Kotak Input Data Baru'"></span>
                </button>

                <!-- Reset ke Master Bawaan -->
                <form action="{{ Route::has('dokumen.datasets.reseed') ? route('dokumen.datasets.reseed') : url('dokumen/datasets/reseed') }}" method="POST" onsubmit="return confirm('Kembalikan seluruh 6 dataset ke data master bawaan TVRI Maluku? Seluruh perubahan kustom akan digantikan dengan data patokan.');" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center space-x-1.5 px-3 py-2 bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-slate-900 text-xs font-semibold rounded-xl transition border border-slate-200" title="Pulihkan 6 dataset master bawaan">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Reset Master</span>
                    </button>
                </form>

                <a href="{{ url('/') }}" target="_blank" class="inline-flex items-center space-x-1.5 px-3.5 py-2 bg-blue-50 hover:bg-blue-100 text-[#003B70] text-xs font-bold rounded-xl transition border border-blue-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>Buka Katalog di Portal Publik</span>
                </a>
            </div>
        </div>

        <!-- ==================== PANEL KOTAK INPUT DATA BARU ==================== -->
        <div x-show="showAddBox" x-cloak class="mb-5 p-5 bg-gradient-to-br from-blue-50/70 via-white to-slate-50 border border-blue-200 rounded-2xl shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-blue-100">
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600 animate-pulse"></span>
                    <h3 class="text-sm font-extrabold text-[#00264A] uppercase tracking-wider">Kotak Input Data Baru • Katalog Operasional TVRI</h3>
                </div>
                <button type="button" @click="showAddBox = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold px-2">&times;</button>
            </div>

            <form action="{{ Route::has('dokumen.datasets.store') ? route('dokumen.datasets.store') : url('dokumen/datasets') }}" method="POST" class="space-y-3.5">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3.5">
                    <div class="sm:col-span-8">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Judul Dataset Operasional <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required placeholder="Contoh: Jumlah Sarana Pemancar Digital di Wilayah Kepulauan Maluku"
                               class="w-full text-xs px-3 py-2 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-medium">
                    </div>

                    <div class="sm:col-span-4">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Bidang / Urusan Terkait <span class="text-rose-500">*</span></label>
                        <select name="urusan_id" required class="w-full text-xs px-3 py-2 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-medium">
                            @foreach($urusans ?? [] as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                            @if(empty($urusans) || count($urusans) === 0)
                                <option value="3">Urusan Pemerintahan Bidang Pendidikan</option>
                                <option value="23">Urusan Pemerintahan Bidang Kebudayaan</option>
                                <option value="1">Lembaga Penyiaran Publik</option>
                            @endif
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3.5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Update <span class="text-rose-500">*</span></label>
                        <input type="text" name="date" required value="{{ date('j F Y') }}" placeholder="Contoh: 28 Maret 2026"
                               class="w-full text-xs px-3 py-2 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Slug URL (Opsional)</label>
                        <input type="text" name="slug" placeholder="Otomatis dari judul jika kosong"
                               class="w-full text-xs px-3 py-2 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-mono text-[11px]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Awal Dilihat</label>
                        <input type="number" name="views" value="100" min="0"
                               class="w-full text-xs px-3 py-2 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Awal Diunduh</label>
                        <input type="number" name="downloads" value="25" min="0"
                               class="w-full text-xs px-3 py-2 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3.5">
                    <div class="sm:col-span-6">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tags / Label Operasional</label>
                        <input type="text" name="tags" placeholder="Contoh: operasional, transmisi, dapodik"
                               class="w-full text-xs px-3 py-2 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>

                    <div class="sm:col-span-6">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Singkat Dataset <span class="text-rose-500">*</span></label>
                        <input type="text" name="description" required placeholder="Deskripsi ringkas mengenai data..."
                               class="w-full text-xs px-3 py-2 bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Kotak Input Data Nilai CSV / Spreadsheet -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-bold text-slate-700">Kotak Input Nilai Baris Data (Salin / Tempel dari Excel atau CSV)</label>
                        <span class="text-[10px] text-blue-600 font-mono">Pisahkan dengan koma (,) atau titik-koma (;)</span>
                    </div>
                    <textarea name="csv_content" rows="3" placeholder="Kabupaten / Kota,Jumlah Sekolah,Jumlah Guru&#10;Kota Ambon,56,412&#10;Kab. Maluku Tengah,100,680"
                              class="w-full text-xs p-2.5 bg-white border border-slate-200 rounded-xl font-mono focus:border-blue-500 focus:ring-1 focus:ring-blue-500"></textarea>
                    <p class="text-[10px] text-slate-400 mt-1">Baris pertama adalah nama kolom. Anda juga dapat mengosongkannya dan mengedit baris tabel nanti via tombol Edit.</p>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-blue-100">
                    <button type="button" @click="showAddBox = false" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-black bg-blue-600 hover:bg-blue-700 text-white shadow-md transition flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>+ Simpan & Terbitkan Dataset</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- ==================== TABEL KATALOG DATA OPERASIONAL ==================== -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[11px] font-extrabold uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-3.5 rounded-l-xl">Dataset & Topik Operasional</th>
                        <th class="py-3 px-3.5">Bidang / Urusan</th>
                        <th class="py-3 px-3.5 text-center">Baris Data</th>
                        <th class="py-3 px-3.5 text-center">Dilihat</th>
                        <th class="py-3 px-3.5 text-center">Diunduh</th>
                        <th class="py-3 px-3.5">Tanggal Update</th>
                        <th class="py-3 px-3.5 text-center">Status Database</th>
                        <th class="py-3 px-3.5 rounded-r-xl text-center">Aksi & Kelola Data</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($katalogDatasets as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <!-- Kolom 1: Kotak Input Judul & Info -->
                            <td class="py-2.5 px-3 min-w-[280px]">
                                <input form="bulkUpdateDatasetForm" 
                                       type="text" 
                                       name="datasets[{{ $item->id }}][title]" 
                                       value="{{ $item->title }}" 
                                       class="font-bold text-[#00264A] text-xs w-full bg-slate-50/60 hover:bg-white focus:bg-white border border-slate-200/80 focus:border-blue-500 rounded-lg px-2.5 py-1.5 focus:ring-1 focus:ring-blue-500 transition shadow-xs" 
                                       title="Kotak Input Judul Dataset">
                                <div class="flex items-center gap-2 mt-1 px-1">
                                    <span class="text-[10px] text-slate-400 font-mono">slug: {{ $item->slug }}</span>
                                    @if(!empty($item->tags))
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($item->tags as $t)
                                                <span class="px-1.5 py-0.5 text-[9px] font-mono bg-slate-100 text-slate-600 rounded">#{{ $t }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Kolom 2: Kotak Input Dropdown Urusan / Bidang -->
                            <td class="py-2.5 px-3 whitespace-nowrap">
                                <select form="bulkUpdateDatasetForm" 
                                        name="datasets[{{ $item->id }}][urusan_id]" 
                                        class="px-2 py-1.5 rounded-lg text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 hover:border-blue-400 focus:bg-white focus:border-blue-600 transition outline-none cursor-pointer">
                                    @foreach($urusans ?? [] as $u)
                                        <option value="{{ $u->id }}" {{ ($item->urusan_id == $u->id) ? 'selected' : '' }}>
                                            {{ $u->name }}
                                        </option>
                                    @endforeach
                                    @if(empty($urusans) || count($urusans) === 0)
                                        <option value="3" {{ ($item->urusan_id == 3) ? 'selected' : '' }}>Urusan Pemerintahan Bidang Pendidikan</option>
                                        <option value="23" {{ ($item->urusan_id == 23) ? 'selected' : '' }}>Urusan Pemerintahan Bidang Kebudayaan</option>
                                        <option value="1" {{ ($item->urusan_id == 1) ? 'selected' : '' }}>Lembaga Penyiaran Publik</option>
                                    @endif
                                </select>
                            </td>

                            <!-- Kolom 3: Baris Data Info -->
                            <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-mono font-bold bg-slate-100 text-slate-700 border border-slate-200 inline-block">
                                    {{ is_array($item->rows) ? count($item->rows) : 0 }} baris
                                </span>
                            </td>

                            <!-- Kolom 4: Kotak Input Jumlah Dilihat -->
                            <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                <input form="bulkUpdateDatasetForm" 
                                       type="number" 
                                       name="datasets[{{ $item->id }}][views]" 
                                       value="{{ $item->views }}" 
                                       min="0"
                                       class="w-20 text-center font-mono font-bold text-emerald-600 bg-emerald-50/50 hover:bg-white focus:bg-white border border-emerald-200/90 focus:border-emerald-500 rounded-lg py-1 px-1.5 focus:ring-1 focus:ring-emerald-500 text-xs shadow-xs transition" 
                                       title="Kotak Input Jumlah Dilihat">
                            </td>

                            <!-- Kolom 5: Kotak Input Jumlah Diunduh -->
                            <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                <input form="bulkUpdateDatasetForm" 
                                       type="number" 
                                       name="datasets[{{ $item->id }}][downloads]" 
                                       value="{{ $item->downloads }}" 
                                       min="0"
                                       class="w-20 text-center font-mono font-bold text-blue-600 bg-blue-50/50 hover:bg-white focus:bg-white border border-blue-200/90 focus:border-blue-500 rounded-lg py-1 px-1.5 focus:ring-1 focus:ring-blue-500 text-xs shadow-xs transition" 
                                       title="Kotak Input Jumlah Diunduh">
                            </td>

                            <!-- Kolom 6: Kotak Input Tanggal Update -->
                            <td class="py-2.5 px-3 whitespace-nowrap">
                                <input form="bulkUpdateDatasetForm" 
                                       type="text" 
                                       name="datasets[{{ $item->id }}][date]" 
                                       value="{{ $item->date }}" 
                                       class="w-32 text-slate-700 text-xs font-medium bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200 focus:border-blue-500 rounded-lg py-1 px-2 focus:ring-1 focus:ring-blue-500 shadow-xs transition" 
                                       title="Kotak Input Tanggal Update">
                            </td>

                            <!-- Kolom 7: Status Database -->
                            <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1 animate-pulse"></span>
                                    Aktif di Portal
                                </span>
                            </td>

                            <!-- Kolom 8: Aksi & Kelola Data -->
                            <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- Tombol Edit Nilai Baris & Impor Data -->
                                    <button type="button" 
                                            @click="openEditById({{ $item->id }})" 
                                            class="inline-flex items-center space-x-1 px-2.5 py-1.5 bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white rounded-lg text-xs font-bold transition border border-blue-200 hover:border-blue-600 shadow-xs"
                                            title="Edit Dataset dan Nilai Tabel">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Edit</span>
                                    </button>

                                    <!-- Tombol Preview Data Baris -->
                                    <button type="button" 
                                            @click="openPreviewById({{ $item->id }})" 
                                            class="p-1.5 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition border border-slate-200" 
                                            title="Lihat Pratinjau Tabel Data">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ Route::has('dokumen.datasets.destroy') ? route('dokumen.datasets.destroy', $item->id) : url('dokumen/datasets/' . $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dataset \'{{ addslashes($item->title) }}\' ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center space-x-1 px-2.5 py-1.5 text-rose-600 hover:text-white hover:bg-rose-600 bg-rose-50 rounded-lg transition border border-rose-200 hover:border-rose-600 text-xs font-bold shadow-xs" title="Hapus Dataset">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-6 text-center text-slate-400">
                                Data katalog operasional belum tersedia. Jalankan Seeder Dataset untuk mengisinya.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- ==================== MODAL EDIT & GANTI DATASET ==================== -->
        <div x-show="editModalOpen" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             @keydown.escape.window="editModalOpen = false">
            <div class="relative w-full max-w-4xl bg-white rounded-3xl p-6 shadow-2xl border border-slate-200 space-y-4 my-8"
                 @click.away="editModalOpen = false">
                
                <div class="flex items-start justify-between pb-3 border-b border-slate-100">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-blue-100 text-blue-800">
                                ID: <span x-text="form.id"></span>
                            </span>
                            <h3 class="text-base font-black text-[#00264A]">Edit & Ganti Data Operasional TVRI</h3>
                        </div>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-1" x-text="form.title"></p>
                    </div>
                    <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                </div>

                <!-- Tab Buttons -->
                <div class="flex border-b border-slate-200 text-xs font-bold gap-2">
                    <button type="button" 
                            @click="activeTab = 'meta'" 
                            :class="activeTab === 'meta' ? 'border-b-2 border-blue-600 text-blue-700 bg-blue-50/50' : 'text-slate-500 hover:text-slate-700'"
                            class="px-4 py-2.5 rounded-t-xl transition flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>1. Informasi & Parameter</span>
                    </button>
                    <button type="button" 
                            @click="activeTab = 'table'" 
                            :class="activeTab === 'table' ? 'border-b-2 border-blue-600 text-blue-700 bg-blue-50/50' : 'text-slate-500 hover:text-slate-700'"
                            class="px-4 py-2.5 rounded-t-xl transition flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <span>2. Edit Baris Data (<span x-text="form.rows.length"></span> Baris)</span>
                    </button>
                    <button type="button" 
                            @click="activeTab = 'csv_json'" 
                            :class="activeTab === 'csv_json' ? 'border-b-2 border-blue-600 text-blue-700 bg-blue-50/50' : 'text-slate-500 hover:text-slate-700'"
                            class="px-4 py-2.5 rounded-t-xl transition flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span>3. Ganti Cepat via CSV / JSON</span>
                    </button>
                </div>

                <form :action="'{{ url('dokumen/datasets') }}/' + form.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="columns" :value="form.columns_json">
                    <input type="hidden" name="rows" :value="form.rows_json">

                    <!-- TAB 1: METADATA & PARAMETER -->
                    <div x-show="activeTab === 'meta'" class="space-y-3.5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Judul Dataset Operasional <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" x-model="form.title" required
                                   class="w-full text-xs px-3.5 py-2.5 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-medium">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Bidang / Urusan Terkait <span class="text-rose-500">*</span></label>
                                <select name="urusan_id" x-model="form.urusan_id" required
                                        class="w-full text-xs px-3.5 py-2.5 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-white font-medium">
                                    @foreach($urusans ?? [] as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                    @if(empty($urusans) || count($urusans) === 0)
                                        <option value="3">Urusan Pemerintahan Bidang Pendidikan</option>
                                        <option value="23">Urusan Pemerintahan Bidang Kebudayaan</option>
                                        <option value="1">Lembaga Penyiaran Publik</option>
                                    @endif
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Update <span class="text-rose-500">*</span></label>
                                <input type="text" name="date" x-model="form.date" required placeholder="Contoh: 28 Maret 2026"
                                       class="w-full text-xs px-3.5 py-2.5 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-medium">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Slug URL</label>
                                <input type="text" name="slug" x-model="form.slug" placeholder="Biarkan otomatis atau ubah"
                                       class="w-full text-xs px-3.5 py-2.5 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-mono text-[11px]">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Total Dilihat (Views)</label>
                                <input type="number" name="views" x-model="form.views" min="0"
                                       class="w-full text-xs px-3.5 py-2.5 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-mono">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Total Diunduh (Downloads)</label>
                                <input type="number" name="downloads" x-model="form.downloads" min="0"
                                       class="w-full text-xs px-3.5 py-2.5 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-mono">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tags / Label Operasional</label>
                            <input type="text" name="tags" x-model="form.tags" placeholder="Pisahkan dengan koma, contoh: dapodik, sekolah, sma-smk, slb"
                                   class="w-full text-xs px-3.5 py-2.5 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <p class="text-[10px] text-slate-400 mt-0.5">Tags akan digunakan sebagai kata kunci filter di portal publik TVRI Hub.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi & Ruang Lingkup Dataset</label>
                            <textarea name="description" x-model="form.description" rows="3" required
                                      class="w-full text-xs p-3 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-normal leading-relaxed"></textarea>
                        </div>
                    </div>

                    <!-- TAB 2: EDIT NILAI BARIS DATA (INTERACTIVE TABLE) -->
                    <div x-show="activeTab === 'table'" class="space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 p-3 bg-blue-50/60 rounded-xl border border-blue-100 text-xs">
                            <div class="text-blue-900 font-medium">
                                Ubah angka atau teks pada baris tabel di bawah ini secara langsung. Semua perubahan otomatis tersimpan saat Anda menekan tombol simpan.
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <button type="button" @click="addColumn()" class="px-2.5 py-1.5 bg-white hover:bg-slate-100 border border-blue-200 text-blue-700 rounded-lg text-xs font-bold shadow-sm transition">
                                    + Tambah Kolom
                                </button>
                                <button type="button" @click="addRow()" class="px-2.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold shadow-sm transition">
                                    + Tambah Baris Data
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto max-h-80 border border-slate-200 rounded-xl">
                            <table class="w-full text-left text-xs text-slate-700 border-collapse">
                                <thead class="bg-slate-100 sticky top-0 z-10 text-[11px] font-bold text-slate-600 border-b border-slate-200">
                                    <tr>
                                        <th class="py-2 px-2.5 w-10 text-center">#</th>
                                        <template x-for="(col, cIdx) in form.columns" :key="col">
                                            <th class="py-2 px-2.5 min-w-[130px] border-r border-slate-200">
                                                <div class="flex items-center justify-between gap-1">
                                                    <span class="truncate" x-text="col"></span>
                                                    <button type="button" @click="removeColumn(col)" class="text-slate-400 hover:text-rose-600 font-bold px-1" title="Hapus Kolom">&times;</button>
                                                </div>
                                            </th>
                                        </template>
                                        <th class="py-2 px-2.5 w-12 text-center">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <template x-for="(row, rIdx) in form.rows" :key="rIdx">
                                        <tr class="hover:bg-slate-50">
                                            <td class="py-1.5 px-2 text-center text-slate-400 font-mono text-[10px]" x-text="rIdx + 1"></td>
                                            <template x-for="col in form.columns" :key="col">
                                                <td class="py-1 px-1.5 border-r border-slate-100">
                                                    <input type="text" 
                                                           x-model="form.rows[rIdx][col]" 
                                                           @input="syncJson()"
                                                           class="w-full text-xs px-2 py-1 bg-white border border-slate-200 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-mono">
                                                </td>
                                            </template>
                                            <td class="py-1 px-1.5 text-center">
                                                <button type="button" @click="removeRow(rIdx)" class="p-1 text-slate-400 hover:text-rose-600 rounded transition" title="Hapus Baris Ini">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 3: GANTI CEPAT VIA CSV / JSON -->
                    <div x-show="activeTab === 'csv_json'" class="space-y-4">
                        <!-- Importer CSV -->
                        <div class="p-4 rounded-xl border border-blue-200 bg-blue-50/40 space-y-2.5">
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-bold text-blue-950 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>Ganti Cepat via CSV (Excel / Spreadsheet)</span>
                                </label>
                                <span class="text-[10px] text-blue-600 font-mono">Format: Koma (,) atau Titik-Koma (;)</span>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-relaxed">
                                Salin (copy) tabel dari Excel atau file .csv, lalu tempel di kotak teks ini. Baris pertama akan otomatis menjadi nama kolom tabel.
                            </p>
                            <textarea x-model="form.csv_content" rows="4" placeholder="Kabupaten / Kota,Jumlah Sekolah,Jumlah Guru&#10;Kota Ambon,56,412&#10;Kab. Maluku Tengah,100,680"
                                      class="w-full text-xs p-2.5 border border-slate-300 rounded-xl font-mono bg-white"></textarea>
                            <div class="flex justify-end">
                                <button type="button" @click="applyCsv()" class="px-3.5 py-1.5 bg-blue-700 hover:bg-blue-800 text-white rounded-lg text-xs font-bold shadow-sm transition">
                                    Terapkan CSV ke Tabel &rarr;
                                </button>
                            </div>
                        </div>

                        <!-- Editor Raw JSON -->
                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-2.5">
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                    <span>Editor Raw JSON Baris Data</span>
                                </label>
                                <button type="button" @click="applyJson()" class="px-3 py-1 bg-slate-200 hover:bg-slate-300 text-slate-800 rounded-lg text-xs font-bold transition">
                                    Format & Muat JSON
                                </button>
                            </div>
                            <textarea x-model="form.rows_json" rows="5"
                                      class="w-full text-xs p-2.5 border border-slate-300 rounded-xl font-mono bg-slate-900 text-emerald-400"></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-black bg-blue-600 hover:bg-blue-700 text-white shadow-md transition flex items-center space-x-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Simpan Perubahan Dataset</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <!-- ==================== MODAL TAMBAH DATASET BARU ==================== -->
        <div x-show="createModalOpen" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             @keydown.escape.window="createModalOpen = false">
            <div class="relative w-full max-w-4xl bg-white rounded-3xl p-6 shadow-2xl border border-slate-200 space-y-4 my-8"
                 @click.away="createModalOpen = false">
                
                <div class="flex items-start justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-black text-[#00264A]">Tambah Dataset Operasional Baru</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Daftarkan dataset master baru ke katalog operasional TVRI Maluku.</p>
                    </div>
                    <button type="button" @click="createModalOpen = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                </div>

                <!-- Tab Buttons -->
                <div class="flex border-b border-slate-200 text-xs font-bold gap-2">
                    <button type="button" 
                            @click="activeTab = 'meta'" 
                            :class="activeTab === 'meta' ? 'border-b-2 border-blue-600 text-blue-700 bg-blue-50/50' : 'text-slate-500 hover:text-slate-700'"
                            class="px-4 py-2.5 rounded-t-xl transition flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>1. Informasi & Parameter</span>
                    </button>
                    <button type="button" 
                            @click="activeTab = 'table'" 
                            :class="activeTab === 'table' ? 'border-b-2 border-blue-600 text-blue-700 bg-blue-50/50' : 'text-slate-500 hover:text-slate-700'"
                            class="px-4 py-2.5 rounded-t-xl transition flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <span>2. Baris Data (<span x-text="form.rows.length"></span> Baris)</span>
                    </button>
                    <button type="button" 
                            @click="activeTab = 'csv_json'" 
                            :class="activeTab === 'csv_json' ? 'border-b-2 border-blue-600 text-blue-700 bg-blue-50/50' : 'text-slate-500 hover:text-slate-700'"
                            class="px-4 py-2.5 rounded-t-xl transition flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span>3. Impor CSV / JSON</span>
                    </button>
                </div>

                <form action="{{ Route::has('dokumen.datasets.store') ? route('dokumen.datasets.store') : url('dokumen/datasets') }}" method="POST" class="space-y-4">
                    @csrf

                    <input type="hidden" name="columns" :value="form.columns_json">
                    <input type="hidden" name="rows" :value="form.rows_json">

                    <!-- TAB 1: METADATA & PARAMETER -->
                    <div x-show="activeTab === 'meta'" class="space-y-3.5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Judul Dataset Operasional <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" x-model="form.title" required placeholder="Contoh: Jumlah Sarana Pemancar Digital di Wilayah Kepulauan Maluku"
                                   class="w-full text-xs px-3.5 py-2.5 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-medium">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Bidang / Urusan Terkait <span class="text-rose-500">*</span></label>
                                <select name="urusan_id" x-model="form.urusan_id" required
                                        class="w-full text-xs px-3.5 py-2.5 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-white font-medium">
                                    @foreach($urusans ?? [] as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                    @if(empty($urusans) || count($urusans) === 0)
                                        <option value="3">Urusan Pemerintahan Bidang Pendidikan</option>
                                        <option value="23">Urusan Pemerintahan Bidang Kebudayaan</option>
                                        <option value="1">Lembaga Penyiaran Publik</option>
                                    @endif
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Update <span class="text-rose-500">*</span></label>
                                <input type="text" name="date" x-model="form.date" required placeholder="Contoh: 28 Maret 2026"
                                       class="w-full text-xs px-3.5 py-2.5 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-medium">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Slug URL (Opsional)</label>
                                <input type="text" name="slug" x-model="form.slug" placeholder="Otomatis dari judul jika dikosongkan"
                                       class="w-full text-xs px-3.5 py-2.5 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-mono text-[11px]">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Awal Dilihat</label>
                                <input type="number" name="views" x-model="form.views" min="0"
                                       class="w-full text-xs px-3.5 py-2.5 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-mono">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Awal Diunduh</label>
                                <input type="number" name="downloads" x-model="form.downloads" min="0"
                                       class="w-full text-xs px-3.5 py-2.5 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-mono">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tags / Label Operasional</label>
                            <input type="text" name="tags" x-model="form.tags" placeholder="Pisahkan dengan koma, contoh: operasional, transmisi, digital"
                                   class="w-full text-xs px-3.5 py-2.5 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi & Ruang Lingkup Dataset <span class="text-rose-500">*</span></label>
                            <textarea name="description" x-model="form.description" rows="3" required placeholder="Deskripsikan data operasional ini secara rinci..."
                                      class="w-full text-xs p-3 border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-normal leading-relaxed"></textarea>
                        </div>
                    </div>

                    <!-- TAB 2: DATA TABLE -->
                    <div x-show="activeTab === 'table'" class="space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 p-3 bg-blue-50/60 rounded-xl border border-blue-100 text-xs">
                            <div class="text-blue-900 font-medium">
                                Tambahkan baris dan kolom yang merepresentasikan struktur data operasional ini.
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <button type="button" @click="addColumn()" class="px-2.5 py-1.5 bg-white hover:bg-slate-100 border border-blue-200 text-blue-700 rounded-lg text-xs font-bold shadow-sm transition">
                                    + Tambah Kolom
                                </button>
                                <button type="button" @click="addRow()" class="px-2.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold shadow-sm transition">
                                    + Tambah Baris
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto max-h-80 border border-slate-200 rounded-xl">
                            <table class="w-full text-left text-xs text-slate-700 border-collapse">
                                <thead class="bg-slate-100 sticky top-0 z-10 text-[11px] font-bold text-slate-600 border-b border-slate-200">
                                    <tr>
                                        <th class="py-2 px-2.5 w-10 text-center">#</th>
                                        <template x-for="(col, cIdx) in form.columns" :key="col">
                                            <th class="py-2 px-2.5 min-w-[130px] border-r border-slate-200">
                                                <div class="flex items-center justify-between gap-1">
                                                    <span class="truncate" x-text="col"></span>
                                                    <button type="button" @click="removeColumn(col)" class="text-slate-400 hover:text-rose-600 font-bold px-1" title="Hapus Kolom">&times;</button>
                                                </div>
                                            </th>
                                        </template>
                                        <th class="py-2 px-2.5 w-12 text-center">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <template x-for="(row, rIdx) in form.rows" :key="rIdx">
                                        <tr class="hover:bg-slate-50">
                                            <td class="py-1.5 px-2 text-center text-slate-400 font-mono text-[10px]" x-text="rIdx + 1"></td>
                                            <template x-for="col in form.columns" :key="col">
                                                <td class="py-1 px-1.5 border-r border-slate-100">
                                                    <input type="text" 
                                                           x-model="form.rows[rIdx][col]" 
                                                           @input="syncJson()"
                                                           class="w-full text-xs px-2 py-1 bg-white border border-slate-200 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-mono">
                                                </td>
                                            </template>
                                            <td class="py-1 px-1.5 text-center">
                                                <button type="button" @click="removeRow(rIdx)" class="p-1 text-slate-400 hover:text-rose-600 rounded transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 3: CSV IMPORT -->
                    <div x-show="activeTab === 'csv_json'" class="space-y-4">
                        <div class="p-4 rounded-xl border border-blue-200 bg-blue-50/40 space-y-2.5">
                            <label class="text-xs font-bold text-blue-950 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Impor dari Salinan CSV / Excel</span>
                            </label>
                            <textarea x-model="form.csv_content" rows="4" placeholder="Wilayah,Nilai 1,Nilai 2&#10;Ambon,10,20&#10;Maluku Tengah,15,30"
                                      class="w-full text-xs p-2.5 border border-slate-300 rounded-xl font-mono bg-white"></textarea>
                            <div class="flex justify-end">
                                <button type="button" @click="applyCsv()" class="px-3.5 py-1.5 bg-blue-700 hover:bg-blue-800 text-white rounded-lg text-xs font-bold shadow-sm transition">
                                    Terapkan CSV ke Tabel &rarr;
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <button type="button" @click="createModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-black bg-blue-600 hover:bg-blue-700 text-white shadow-md transition flex items-center space-x-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>+ Tambah & Terbitkan Dataset</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <!-- ==================== MODAL PREVIEW DATASET ==================== -->
        <div x-show="previewModalOpen" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             @keydown.escape.window="previewModalOpen = false">
            <div class="relative w-full max-w-4xl bg-white rounded-3xl p-6 shadow-2xl border border-slate-200 space-y-4 my-8"
                 @click.away="previewModalOpen = false">
                
                <div class="flex items-start justify-between pb-3 border-b border-slate-100">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200" x-text="previewItem.urusan"></span>
                            <span class="text-xs text-slate-400 font-mono" x-text="'slug: ' + previewItem.slug"></span>
                        </div>
                        <h3 class="text-base font-black text-[#00264A] mt-1" x-text="previewItem.title"></h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed" x-text="previewItem.description"></p>
                    </div>
                    <button type="button" @click="previewModalOpen = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                </div>

                <div class="flex items-center gap-4 text-xs font-medium text-slate-500 py-1 border-b border-slate-100">
                    <div>Update: <span class="font-bold text-slate-700" x-text="previewItem.date"></span></div>
                    <div>Dilihat: <span class="font-bold text-emerald-600" x-text="previewItem.views"></span> kali</div>
                    <div>Diunduh: <span class="font-bold text-blue-600" x-text="previewItem.downloads"></span> kali</div>
                    <div>Total: <span class="font-bold text-[#00264A]" x-text="previewItem.rows.length + ' baris data'"></span></div>
                </div>

                <!-- Preview Table -->
                <div class="overflow-x-auto max-h-96 border border-slate-200 rounded-xl">
                    <table class="w-full text-left text-xs text-slate-700 border-collapse">
                        <thead class="bg-slate-100 sticky top-0 z-10 text-[11px] font-bold text-slate-600 border-b border-slate-200">
                            <tr>
                                <th class="py-2.5 px-3 w-10 text-center">#</th>
                                <template x-for="col in previewItem.columns" :key="col">
                                    <th class="py-2.5 px-3 border-r border-slate-200" x-text="col"></th>
                                </template>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="(row, idx) in previewItem.rows" :key="idx">
                                <tr class="hover:bg-slate-50">
                                    <td class="py-2 px-3 text-center text-slate-400 font-mono text-[10px]" x-text="idx + 1"></td>
                                    <template x-for="col in previewItem.columns" :key="col">
                                        <td class="py-2 px-3 border-r border-slate-100 font-mono text-slate-800" x-text="row[col] !== undefined ? row[col] : '-'"></td>
                                    </template>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-end pt-3 border-t border-slate-100">
                    <button type="button" @click="previewModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

    </div>
    @endif

    <!-- 6 Executive Metric Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <!-- Card 1: Total Dokumen -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Dokumen</span>
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
            <div>
                <p class="text-2xl font-black text-[#00264A]">{{ number_format($totalDocuments, 0, ',', '.') }}</p>
                <p class="text-[10px] text-emerald-600 font-semibold mt-0.5">Tersimpan di Database</p>
            </div>
        </div>

        <!-- Card 2: Total Folder -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Folder Kategori</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                </div>
            </div>
            <div>
                <p class="text-2xl font-black text-amber-700">{{ number_format($totalFolders, 0, ',', '.') }}</p>
                <p class="text-[10px] text-slate-500 font-semibold mt-0.5">Struktur Hierarki</p>
            </div>
        </div>

        <!-- Card 3: Dokumen Publik -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Akses Publik</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div>
                <p class="text-2xl font-black text-emerald-600">{{ number_format($publicDocuments, 0, ',', '.') }}</p>
                <p class="text-[10px] text-emerald-600 font-semibold mt-0.5">Dapat Diakses Umum</p>
            </div>
        </div>

        <!-- Card 4: Dokumen Rahasia / Terbatas -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Rahasia/Terbatas</span>
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-700 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
            </div>
            <div>
                <p class="text-2xl font-black text-rose-600">{{ number_format($confidentialDocuments, 0, ',', '.') }}</p>
                <p class="text-[10px] text-rose-500 font-semibold mt-0.5">Proteksi Khusus</p>
            </div>
        </div>

        <!-- Card 5: Versi Tersimpan -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Histori Versi</span>
                <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div>
                <p class="text-2xl font-black text-purple-700">{{ number_format($totalVersions, 0, ',', '.') }}</p>
                <p class="text-[10px] text-purple-600 font-semibold mt-0.5">Arsip Revisi Lengkap</p>
            </div>
        </div>

        <!-- Card 6: Total Unduhan -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Unduhan</span>
                <div class="w-8 h-8 rounded-xl bg-cyan-50 text-cyan-700 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </div>
            </div>
            <div>
                <p class="text-2xl font-black text-cyan-700">{{ number_format($totalDownloads, 0, ',', '.') }}</p>
                <p class="text-[10px] text-slate-500 font-semibold mt-0.5">{{ number_format($totalViews, 0, ',', '.') }} Dilihat</p>
            </div>
        </div>
    </div>

    <!-- Visualisasi Grafik Chart.js (3 Kolom) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Chart 1: Distribusi Dokumen per Bidang / Bagian -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-[#00264A]">Distribusi Dokumen per Bidang</h3>
                    <p class="text-xs text-slate-500">Jumlah arsip menurut unit kerja</p>
                </div>
                <span class="px-2 py-0.5 text-[10px] font-bold bg-blue-100 text-blue-700 rounded-lg">Bidang</span>
            </div>
            <div class="relative flex-1 min-h-[260px] flex items-center justify-center">
                <canvas id="divisionChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Proporsi Format Berkas (PDF, DOCX, XLSX, dll) -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-[#00264A]">Komposisi Format Berkas</h3>
                    <p class="text-xs text-slate-500">PDF, Word, Excel, PPTX & Multimedia</p>
                </div>
                <span class="px-2 py-0.5 text-[10px] font-bold bg-purple-100 text-purple-700 rounded-lg">Format</span>
            </div>
            <div class="relative flex-1 min-h-[260px] flex items-center justify-center">
                <canvas id="formatChart"></canvas>
            </div>
        </div>

        <!-- Chart 3: Tingkat Kerahasiaan & Akses Dokumen -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-[#00264A]">Tingkat Kerahasiaan</h3>
                    <p class="text-xs text-slate-500">Publik, Internal, Terbatas, Rahasia</p>
                </div>
                <span class="px-2 py-0.5 text-[10px] font-bold bg-amber-100 text-amber-700 rounded-lg">Security</span>
            </div>
            <div class="relative flex-1 min-h-[260px] flex items-center justify-center">
                <canvas id="confChart"></canvas>
            </div>
        </div>
    </div>

    <!-- 2 Kolom: Dokumen Terbaru & Log Aktivitas Kearsipan -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Dokumen Terbaru -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-[#00264A]">Dokumen & SK Terbaru</h3>
                        <p class="text-xs text-slate-500">Naskah yang baru saja diunggah ke repositori</p>
                    </div>
                </div>
                <a href="{{ route('dokumen.documents.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800">Lihat Semua →</a>
            </div>

            <div class="space-y-3">
                @forelse($recentDocuments as $doc)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-blue-50/50 border border-slate-100 transition-colors">
                        <div class="flex items-center space-x-3 min-w-0">
                            <span class="px-2 py-1 text-[10px] font-black rounded-lg border {{ $doc->format_badge_color }}">
                                {{ $doc->file_type }}
                            </span>
                            <div class="min-w-0">
                                <a href="{{ route('dokumen.documents.show', $doc->id) }}" class="text-xs font-bold text-slate-800 hover:text-blue-700 truncate block">
                                    {{ $doc->title }}
                                </a>
                                <p class="text-[11px] text-slate-500 flex items-center space-x-2 mt-0.5">
                                    <span>{{ $doc->document_number ?: 'Tanpa No. SK' }}</span>
                                    <span>•</span>
                                    <span>{{ $doc->division }}</span>
                                    <span>•</span>
                                    <span class="font-semibold text-blue-600">{{ $doc->current_version }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 flex-shrink-0 ml-3">
                            <a href="{{ route('dokumen.documents.show', $doc->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" title="Lihat Detail & QR">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            <a href="{{ route('dokumen.documents.download', $doc->id) }}" class="p-1.5 text-emerald-600 hover:bg-emerald-100 rounded-lg transition-colors" title="Unduh">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            </a>
                            <form action="{{ route('dokumen.documents.destroy', $doc->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini dari sistem?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-100 rounded-lg transition-colors" title="Hapus Dokumen">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 text-center py-6">Belum ada dokumen yang diunggah.</p>
                @endforelse
            </div>
        </div>

        <!-- Log Aktivitas Audit Kearsipan -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-[#00264A]">Audit Trail Aktivitas Kearsipan</h3>
                        <p class="text-xs text-slate-500">Pencatatan real-time aksi upload, revisi & download</p>
                    </div>
                </div>
                @if(auth()->user()->hasRole('ADMIN'))
                    <a href="{{ route('admin.activity-logs.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-800">Semua Log →</a>
                @endif
            </div>

            <div class="space-y-3">
                @forelse($recentLogs as $log)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded {{ str_contains($log->action, 'UPLOAD') ? 'bg-emerald-100 text-emerald-800' : (str_contains($log->action, 'DOWNLOAD') ? 'bg-cyan-100 text-cyan-800' : 'bg-purple-100 text-purple-800') }}">
                                {{ $log->action }}
                            </span>
                            <span class="text-[10px] text-slate-400">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs font-medium text-slate-800">{{ $log->description }}</p>
                        <p class="text-[10px] text-slate-500 mt-1">Oleh: <span class="font-semibold text-slate-700">{{ $log->user ? $log->user->name : 'Sistem' }}</span></p>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 text-center py-6">Belum ada aktivitas audit kearsipan.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- STANDAR OPERASIONAL PROSEDUR (SOP) TVRI MALUKU - FITUR KEARSIPAN INTERAKTIF -->
    <div id="sop" 
         x-data="{
             sopSearch: '',
             selectedCategory: 'Semua',
             sopDetailModal: false,
             selectedSop: {},
             createSopModal: false,
             sops: {{ json_encode($sops) }},
             get filteredSops() {
                 return this.sops.filter(s => {
                     const matchCat = this.selectedCategory === 'Semua' || s.kategori === this.selectedCategory;
                     const query = (this.sopSearch || '').toLowerCase().trim();
                     if (!query) return matchCat;
                     const kode = (s.kode || '').toLowerCase();
                     const judul = (s.judul || '').toLowerCase();
                     const kat = (s.kategori || '').toLowerCase();
                     const suara = (s.suara_siaran || '').toLowerCase();
                     const ket = (s.keterangan || '').toLowerCase();
                     return matchCat && (kode.includes(query) || judul.includes(query) || kat.includes(query) || suara.includes(query) || ket.includes(query));
                 });
             },
             openDetail(sop) {
                 this.selectedSop = sop;
                 this.sopDetailModal = true;
             }
         }" 
         class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm space-y-5">
        
        <!-- Header Fitur SOP -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-black text-[#00264A]">STANDAR OPERASIONAL PROSEDUR (SOP) TVRI MALUKU</h2>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-teal-50 text-teal-700 border border-teal-200 uppercase">Resmi LPP TVRI</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">Pusat repositori mutu penyiaran, kepatuhan on-air, tata kelola studio, standar audio siaran & quality control</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button type="button" 
                        @click="createSopModal = true"
                        class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-teal-600 hover:bg-teal-700 text-white shadow-sm transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Tambah Dokumen SOP</span>
                </button>
                <a href="{{ route('tvri.program.manage', ['tab' => 'sop']) }}" 
                   class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>Kelola di Menu Program</span>
                </a>
            </div>
        </div>

        <!-- Mini KPI Bar Fitur SOP -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="p-3 bg-teal-50/70 border border-teal-100 rounded-xl">
                <span class="text-[10px] font-bold text-teal-700 uppercase tracking-wider block">Total SOP Aktif</span>
                <p class="text-xl font-black text-teal-800 mt-0.5">{{ $sops->where('status', 'Aktif')->count() }} Dokumen</p>
            </div>
            <div class="p-3 bg-blue-50/70 border border-blue-100 rounded-xl">
                <span class="text-[10px] font-bold text-blue-700 uppercase tracking-wider block">Pra & Produksi Studio</span>
                <p class="text-xl font-black text-blue-800 mt-0.5">{{ $sops->whereIn('kategori', ['Pra Produksi', 'Produksi'])->count() }} Prosedur</p>
            </div>
            <div class="p-3 bg-purple-50/70 border border-purple-100 rounded-xl">
                <span class="text-[10px] font-bold text-purple-700 uppercase tracking-wider block">Pasca & Quality Control</span>
                <p class="text-xl font-black text-purple-800 mt-0.5">{{ $sops->whereIn('kategori', ['Pasca Produksi', 'Quality Control'])->count() }} Prosedur</p>
            </div>
            <div class="p-3 bg-emerald-50/70 border border-emerald-100 rounded-xl">
                <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider block">Penayangan & Evaluasi</span>
                <p class="text-xl font-black text-emerald-800 mt-0.5">{{ $sops->whereIn('kategori', ['Penayangan', 'Monitoring', 'Evaluasi'])->count() }} Prosedur</p>
            </div>
        </div>

        <!-- Filter Interaktif & Pencarian Real-Time SOP -->
        <div class="space-y-3 pt-1">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="relative flex-1">
                    <input type="text" 
                           x-model="sopSearch" 
                           placeholder="Cari kode SOP (misal: SOP-PRG-001), judul, standar suara siaran, atau kata kunci..." 
                           class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-600">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <div class="flex items-center gap-1.5 text-xs text-slate-500 flex-shrink-0">
                    <span>Ditemukan:</span>
                    <span class="font-bold text-[#00264A] font-mono px-2 py-0.5 bg-slate-100 rounded-md" x-text="filteredSops.length + ' SOP'"></span>
                </div>
            </div>

            <!-- Category Pills Filter -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs">
                @php
                    $kategoriFilterList = ['Semua', 'Pra Produksi', 'Produksi', 'Pasca Produksi', 'Quality Control', 'Penayangan', 'Monitoring', 'Evaluasi'];
                @endphp
                @foreach($kategoriFilterList as $kf)
                    <button type="button" 
                            @click="selectedCategory = '{{ $kf }}'"
                            :class="selectedCategory === '{{ $kf }}' ? 'bg-[#00264A] text-white font-bold shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                            class="px-3 py-1.5 rounded-lg whitespace-nowrap transition-colors text-[11px]">
                        {{ $kf }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Grid Kartu SOP Interaktif -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-1">
            <template x-for="sop in filteredSops" :key="sop.id">
                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/70 hover:bg-teal-50/40 hover:border-teal-200 transition-all flex flex-col justify-between group cursor-pointer"
                     @click="openDetail(sop)">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-mono text-[10px] font-black text-teal-700 bg-teal-100/70 px-2 py-0.5 rounded-md" x-text="sop.kode"></span>
                            <span class="text-[10px] font-bold text-slate-400" x-text="sop.revisi || 'Rev. 01 / 2025'"></span>
                        </div>
                        <h4 class="text-xs font-black text-[#00264A] leading-snug line-clamp-2 group-hover:text-teal-800 transition-colors" x-text="sop.judul"></h4>
                        
                        <!-- Standar Suara Siaran Badge -->
                        <template x-if="sop.suara_siaran">
                            <div class="mt-2 flex items-center space-x-1 text-[10px] text-cyan-800 bg-cyan-50 px-2 py-0.5 rounded-md border border-cyan-100">
                                <svg class="w-3 h-3 text-cyan-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                                <span class="truncate font-semibold" x-text="sop.suara_siaran"></span>
                            </div>
                        </template>

                        <template x-if="sop.keterangan">
                            <p class="text-[11px] text-slate-500 font-medium mt-1.5 line-clamp-2 leading-relaxed" x-text="sop.keterangan"></p>
                        </template>
                    </div>

                    <div class="flex items-center justify-between pt-3 mt-3 border-t border-slate-200/60">
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-blue-50 text-blue-700 border border-blue-200" x-text="sop.kategori"></span>
                        <span class="text-[10px] font-bold text-teal-600 group-hover:text-teal-800 flex items-center gap-0.5">
                            <span>Detail</span>
                            <span>&rarr;</span>
                        </span>
                    </div>
                </div>
            </template>
        </div>

        <!-- State Kosong jika tidak ada pencarian yang cocok -->
        <div x-show="filteredSops.length === 0" x-cloak class="py-10 text-center text-slate-400 text-xs">
            <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="font-semibold text-slate-600">Tidak ada dokumen SOP yang sesuai pencarian atau filter.</p>
            <p class="text-[11px] text-slate-400 mt-0.5">Coba gunakan kata kunci lain atau pilih kategori "Semua".</p>
        </div>

        <!-- ==================== MODAL DETAIL SOP ==================== -->
        <div x-show="sopDetailModal" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             @keydown.escape.window="sopDetailModal = false">
            <div class="relative w-full max-w-lg bg-white rounded-3xl p-6 shadow-2xl border border-slate-200 space-y-4"
                 @click.away="sopDetailModal = false">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center space-x-2">
                        <span class="font-mono text-xs font-black text-teal-700 bg-teal-100 px-2.5 py-0.5 rounded-md" x-text="selectedSop.kode"></span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200" x-text="selectedSop.status || 'Aktif'"></span>
                    </div>
                    <button @click="sopDetailModal = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                </div>

                <div class="space-y-3">
                    <div>
                        <h3 class="text-base font-black text-[#00264A] leading-snug" x-text="selectedSop.judul"></h3>
                        <p class="text-[11px] text-slate-400 font-semibold mt-1 flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-bold" x-text="selectedSop.kategori"></span>
                            <span>&bull;</span>
                            <span x-text="selectedSop.revisi || 'Rev. 01 / 2025'"></span>
                        </p>
                    </div>

                    <!-- Standar Suara Siaran Detail -->
                    <template x-if="selectedSop.suara_siaran">
                        <div class="p-3 rounded-xl bg-cyan-50/80 border border-cyan-200/80">
                            <span class="text-[10px] font-bold text-cyan-800 uppercase tracking-wider block mb-0.5">Standar Suara / Audio Siaran:</span>
                            <p class="text-xs font-black text-cyan-900 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                                <span x-text="selectedSop.suara_siaran"></span>
                            </p>
                        </div>
                    </template>

                    <!-- Deskripsi & Ruang Lingkup -->
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Ruang Lingkup & Deskripsi:</span>
                        <p class="text-xs text-slate-700 leading-relaxed font-medium" x-text="selectedSop.keterangan || 'Standar Operasional Prosedur resmi Lembaga Penyiaran Publik TVRI Stasiun Maluku.'"></p>
                    </div>

                    <!-- Berkas Dokumen -->
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Dokumen Berkas Digital</span>
                            <p class="text-xs font-bold text-slate-700 mt-0.5" x-text="selectedSop.file_url || 'Arsip Resmi Terverifikasi LPP TVRI'"></p>
                        </div>
                        <template x-if="selectedSop.file_url">
                            <a :href="selectedSop.file_url" target="_blank" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-[#005B9F] hover:bg-[#003B70] text-white">
                                Unduh SOP
                            </a>
                        </template>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <a href="{{ route('tvri.program.manage', ['tab' => 'sop']) }}" class="text-xs font-bold text-teal-600 hover:text-teal-800">
                        Buka di Halaman Manajemen &rarr;
                    </a>
                    <button type="button" @click="sopDetailModal = false" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- ==================== MODAL TAMBAH SOP BARU ==================== -->
        <div x-show="createSopModal" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             @keydown.escape.window="createSopModal = false">
            <div class="relative w-full max-w-lg bg-white rounded-3xl p-6 shadow-2xl border border-slate-200"
                 @click.away="createSopModal = false">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-black uppercase text-[#00264A]">Tambah Dokumen SOP Baru</h3>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">Terbitkan pedoman mutu siaran baru ke repositori kearsipan</p>
                    </div>
                    <button @click="createSopModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
                </div>

                <form action="{{ route('tvri.program.sop.store') }}" method="POST" class="mt-4 space-y-3.5">
                    @csrf
                    <input type="hidden" name="redirect_to" value="dokumen.dashboard">

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kode SOP <span class="text-rose-500">*</span></label>
                            <input type="text" name="kode" required placeholder="Contoh: SOP-PRG-009" value="SOP-PRG-00{{ count($sops) + 1 }}"
                                   class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-teal-600">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Tahap <span class="text-rose-500">*</span></label>
                            <select name="kategori" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-teal-600">
                                <option value="Pra Produksi">Pra Produksi</option>
                                <option value="Produksi">Produksi</option>
                                <option value="Pasca Produksi">Pasca Produksi</option>
                                <option value="Quality Control">Quality Control</option>
                                <option value="Penayangan">Penayangan</option>
                                <option value="Monitoring">Monitoring</option>
                                <option value="Evaluasi">Evaluasi</option>
                                <option value="Umum Siaran">Umum Siaran</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Judul Dokumen SOP <span class="text-rose-500">*</span></label>
                        <input type="text" name="judul" required placeholder="Contoh: SOP Penyiaran Siaran Langsung Debat Publik Pilkada" 
                               class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-teal-600">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Standar Suara / Format Audio Siaran</label>
                        <input type="text" name="suara_siaran" placeholder="Contoh: Stereo EBU R128 (-23 LUFS) / 48kHz 24-bit" 
                               class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-teal-600">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Revisi</label>
                            <input type="text" name="revisi" placeholder="Rev. 01 / 2025" value="Rev. 01 / 2025"
                                   class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-teal-600">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Status Prosedur <span class="text-rose-500">*</span></label>
                            <select name="status" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-teal-600">
                                <option value="Aktif">Aktif</option>
                                <option value="Review">Review</option>
                                <option value="Draft">Draft</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Singkat / Ruang Lingkup</label>
                        <textarea name="keterangan" rows="2" placeholder="Penjelasan ringkas alur kerja, protokol kepatuhan mutu, dan penanggung jawab..."
                                  class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-teal-600"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tautan File Dokumen</label>
                            <input type="text" name="file_url" placeholder="Contoh: /dokumen/sop/sop-009.pdf"
                                   class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-teal-600">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Urutan</label>
                            <input type="number" name="urutan" value="{{ count($sops) + 1 }}"
                                   class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-teal-600">
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-100">
                        <button type="button" @click="createSopModal = false" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-black bg-teal-600 hover:bg-teal-700 text-white shadow-md">+ Simpan & Terbitkan SOP</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>

<!-- Chart.js CDN & Init Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
window.datasetManager = function(initialDatasets = []) {
    return {
        datasets: initialDatasets || [],
        showAddBox: true,
        createModalOpen: false,
        editModalOpen: false,
        previewModalOpen: false,
        activeTab: 'meta', // 'meta' | 'table' | 'csv_json'
        
        form: {
            id: '',
            title: '',
            slug: '',
            urusan_id: 3,
            date: '',
            views: 0,
            downloads: 0,
            tags: '',
            description: '',
            columns: [],
            rows: [],
            csv_content: '',
            columns_json: '[]',
            rows_json: '[]'
        },

        previewItem: {
            title: '',
            slug: '',
            urusan: '',
            date: '',
            views: 0,
            downloads: 0,
            description: '',
            tags: [],
            columns: [],
            rows: []
        },

        openCreate() {
            const today = new Date();
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            const dateStr = today.getDate() + ' ' + months[today.getMonth()] + ' ' + today.getFullYear();

            this.form = {
                id: '',
                title: '',
                slug: '',
                urusan_id: 3,
                date: dateStr,
                views: 120,
                downloads: 45,
                tags: 'operasional, tvri, maluku',
                description: '',
                columns: ['Kabupaten / Kota', 'Capaian'],
                rows: [
                    { 'Kabupaten / Kota': 'Kota Ambon', 'Capaian': 20 },
                    { 'Kabupaten / Kota': 'Kab. Maluku Tengah', 'Capaian': 35 }
                ],
                csv_content: '',
                columns_json: '',
                rows_json: ''
            };
            this.syncJson();
            this.activeTab = 'meta';
            this.createModalOpen = true;
        },

        openEditById(id) {
            const item = this.datasets.find(d => d.id === id);
            if (!item) return;

            this.form = {
                id: item.id,
                title: item.title,
                slug: item.slug,
                urusan_id: item.urusan_id || 3,
                date: item.date,
                views: item.views,
                downloads: item.downloads,
                tags: Array.isArray(item.tags) ? item.tags.join(', ') : (item.tags || ''),
                description: item.description || '',
                columns: Array.isArray(item.columns) ? JSON.parse(JSON.stringify(item.columns)) : [],
                rows: Array.isArray(item.rows) ? JSON.parse(JSON.stringify(item.rows)) : [],
                csv_content: '',
                columns_json: '',
                rows_json: ''
            };
            this.syncJson();
            this.activeTab = 'meta';
            this.editModalOpen = true;
        },

        openPreviewById(id) {
            const item = this.datasets.find(d => d.id === id);
            if (!item) return;

            this.previewItem = {
                title: item.title,
                slug: item.slug,
                urusan: item.urusan ? item.urusan.name : 'Lembaga Penyiaran Publik',
                date: item.date,
                views: item.views,
                downloads: item.downloads,
                description: item.description,
                tags: item.tags || [],
                columns: item.columns || [],
                rows: item.rows || []
            };
            this.previewModalOpen = true;
        },

        addRow() {
            const newRow = {};
            this.form.columns.forEach(col => {
                newRow[col] = '';
            });
            this.form.rows.push(newRow);
            this.syncJson();
        },

        removeRow(index) {
            this.form.rows.splice(index, 1);
            this.syncJson();
        },

        addColumn() {
            const name = prompt('Masukkan nama kolom baru:');
            if (name && name.trim()) {
                const trimmed = name.trim();
                if (!this.form.columns.includes(trimmed)) {
                    this.form.columns.push(trimmed);
                    this.form.rows.forEach(r => {
                        if (r[trimmed] === undefined) r[trimmed] = 0;
                    });
                    this.syncJson();
                }
            }
        },

        removeColumn(colName) {
            if (this.form.columns.length <= 1) {
                alert('Dataset minimal harus memiliki 1 kolom.');
                return;
            }
            if (confirm('Hapus kolom "' + colName + '" beserta seluruh nilainya?')) {
                this.form.columns = this.form.columns.filter(c => c !== colName);
                this.form.rows.forEach(r => {
                    delete r[colName];
                });
                this.syncJson();
            }
        },

        syncJson() {
            this.form.columns_json = JSON.stringify(this.form.columns);
            this.form.rows_json = JSON.stringify(this.form.rows, null, 2);
        },

        applyCsv() {
            if (!this.form.csv_content || !this.form.csv_content.trim()) {
                alert('Silakan tempel (paste) data teks CSV terlebih dahulu.');
                return;
            }
            const lines = this.form.csv_content.trim().split(/\r\n|\r|\n/).filter(l => l.trim().length > 0);
            if (lines.length < 2) {
                alert('Data CSV minimal harus memiliki 1 baris judul kolom dan 1 baris nilai data.');
                return;
            }
            const delimiter = lines[0].includes(';') ? ';' : ',';
            const cols = lines[0].split(delimiter).map(c => c.trim().replace(/^["']|["']$/g, '')).filter(c => c !== '');
            const newRows = [];
            for (let i = 1; i < lines.length; i++) {
                const vals = lines[i].split(delimiter).map(v => v.trim().replace(/^["']|["']$/g, ''));
                const rowObj = {};
                cols.forEach((col, idx) => {
                    let val = vals[idx] !== undefined ? vals[idx] : '';
                    if (val !== '' && !isNaN(val)) {
                        val = Number(val);
                    }
                    rowObj[col] = val;
                });
                newRows.push(rowObj);
            }
            this.form.columns = cols;
            this.form.rows = newRows;
            this.syncJson();
            alert('Sukses! Berhasil memuat ' + newRows.length + ' baris dan ' + cols.length + ' kolom dari CSV. Silakan periksa di Tab "Edit Baris Data".');
            this.activeTab = 'table';
        },

        applyJson() {
            try {
                const parsed = JSON.parse(this.form.rows_json);
                if (Array.isArray(parsed) && parsed.length > 0) {
                    this.form.rows = parsed;
                    if (typeof parsed[0] === 'object' && parsed[0] !== null) {
                        this.form.columns = Object.keys(parsed[0]);
                    }
                    this.syncJson();
                    alert('Sukses! Berhasil memuat ' + parsed.length + ' baris dari JSON. Silakan periksa di Tab "Edit Baris Data".');
                    this.activeTab = 'table';
                } else {
                    alert('Format JSON harus berupa array objek data, contoh: [{"Kabupaten": "Ambon", "Jumlah": 10}]');
                }
            } catch (e) {
                alert('Format JSON tidak valid: ' + e.message);
            }
        }
    };
};

document.addEventListener('DOMContentLoaded', function() {
    // 1. Division Chart
    const divCtx = document.getElementById('divisionChart').getContext('2d');
    new Chart(divCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($divisionLabels) !!},
            datasets: [{
                label: 'Jumlah Dokumen',
                data: {!! json_encode($divisionCounts) !!},
                backgroundColor: '#005B9F',
                borderRadius: 6,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });

    // 2. Format Chart (Doughnut)
    const fmtCtx = document.getElementById('formatChart').getContext('2d');
    new Chart(fmtCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($formatLabels) !!},
            datasets: [{
                data: {!! json_encode($formatCounts) !!},
                backgroundColor: [
                    '#E11D48', // PDF - Rose
                    '#2563EB', // DOCX - Blue
                    '#059669', // XLSX - Emerald
                    '#D97706', // PPTX - Amber
                    '#7C3AED', // Media - Purple
                    '#475569', // Zip/Other - Slate
                ],
                borderWidth: 2,
                borderColor: '#FFFFFF'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
            },
            cutout: '60%'
        }
    });

    // 3. Confidentiality Chart (Pie)
    const confCtx = document.getElementById('confChart').getContext('2d');
    new Chart(confCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($confLabels) !!},
            datasets: [{
                data: {!! json_encode($confCounts) !!},
                backgroundColor: [
                    '#10B981', // Publik - Emerald
                    '#3B82F6', // Internal - Blue
                    '#F59E0B', // Terbatas - Amber
                    '#EF4444', // Rahasia - Rose
                ],
                borderWidth: 2,
                borderColor: '#FFFFFF'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
            }
        }
    });
});
</script>
@endsection
