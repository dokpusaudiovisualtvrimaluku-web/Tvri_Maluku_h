@extends('layouts.app')

@section('title', 'Input & Kelola Data Program - TVRI Maluku')

@php
    $activeTab = request('tab', $tab ?? 'program');
@endphp

@section('content')
<div class="space-y-6 pb-12" x-data="{
    activeTab: '{{ $activeTab }}',
    editModal: false,
    editData: {},
    editSopModal: false,
    editSopData: {},

    // Real-time Clock WIT
    currentWitTime: '{{ $meta['waktu'] ?? 'Selasa, 2 September 2025 | 10:24:00 WIT' }}',
    initClock() {
        const update = () => {
            const now = new Date();
            // Convert to WIT (UTC+9)
            const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
            const witDate = new Date(utc + (3600000 * 9));
            
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            const dayName = days[witDate.getDay()];
            const dateNum = witDate.getDate();
            const monthName = months[witDate.getMonth()];
            const yearNum = witDate.getFullYear();
            
            const hours = String(witDate.getHours()).padStart(2, '0');
            const mins = String(witDate.getMinutes()).padStart(2, '0');
            const secs = String(witDate.getSeconds()).padStart(2, '0');
            
            this.currentWitTime = `${dayName}, ${dateNum} ${monthName} ${yearNum} | ${hours}:${mins}:${secs} WIT`;
        };
        update();
        setInterval(update, 1000);
    },

    // Checkbox Program
    selectedPrograms: [],
    pageProgramIds: {{ json_encode($programs->pluck('id')) }},
    toggleAllPrograms() {
        if (this.selectedPrograms.length === this.pageProgramIds.length) {
            this.selectedPrograms = [];
        } else {
            this.selectedPrograms = [...this.pageProgramIds];
        }
    },

    // Checkbox Agenda
    selectedAgendas: [],
    allAgendaIds: {{ json_encode($agendas->pluck('id')) }},
    toggleAllAgendas() {
        if (this.selectedAgendas.length === this.allAgendaIds.length) {
            this.selectedAgendas = [];
        } else {
            this.selectedAgendas = [...this.allAgendaIds];
        }
    },

    // Checkbox Highlight
    selectedHighlights: [],
    allHighlightIds: {{ json_encode($highlights->pluck('id')) }},
    toggleAllHighlights() {
        if (this.selectedHighlights.length === this.allHighlightIds.length) {
            this.selectedHighlights = [];
        } else {
            this.selectedHighlights = [...this.allHighlightIds];
        }
    },

    // Checkbox SOP
    selectedSops: [],
    allSopIds: {{ json_encode($sops->pluck('id')) }},
    toggleAllSops() {
        if (this.selectedSops.length === this.allSopIds.length) {
            this.selectedSops = [];
        } else {
            this.selectedSops = [...this.allSopIds];
        }
    }
}" x-init="initClock()">

    <!-- ===== HEADER MANAJEMEN PROGRAM ===== -->
    <div class="relative bg-gradient-to-r from-[#001D38] via-[#00264A] to-[#003B70] rounded-3xl p-6 sm:p-7 text-white shadow-xl overflow-hidden border border-purple-400/20">
        <div class="absolute -right-10 -top-16 w-72 h-72 bg-purple-400/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-white p-2 shadow-lg flex-shrink-0 flex items-center justify-center border border-purple-300/40">
                    <img src="{{ asset('images/logo_tvri.png') }}" alt="Logo TVRI" class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-400/20 text-purple-300 border border-purple-400/30">
                            CRUD DATA PROGRAM & SIARAN
                        </span>
                        <span class="text-xs text-purple-200 font-bold">Periode: {{ $ringkasan->periode ?? 'September 2025' }}</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white mt-1 leading-tight uppercase">
                        Input & Kelola Data Program TVRI Maluku
                    </h1>
                    <p class="text-xs text-blue-200 font-medium">
                        Pangkalan Data Perencanaan, Produksi, Penayangan, SOP & Evaluasi yang terhubung ke Executive Command Center.
                    </p>
                </div>
            </div>

            <!-- Header Action Buttons & Live Clock -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-2.5">
                <div class="bg-white/10 backdrop-blur-md px-4 py-2 rounded-2xl border border-white/10 text-left sm:text-right">
                    <div class="flex items-center gap-1.5 justify-start sm:justify-end text-[10px] font-bold text-emerald-300 uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping inline-block"></span>
                        <span>{{ $ringkasan->live_status ?? 'Live System | Periode: September 2025' }}</span>
                    </div>
                    <p class="text-xs font-black text-white font-mono mt-0.5" x-text="currentWitTime">
                        {{ $meta['waktu'] ?? 'Selasa, 2 September 2025 | 10:24 WIT' }}
                    </p>
                </div>

                <div class="flex items-center space-x-2">
                    <a href="{{ route('tvri.program.dashboard') }}" 
                       class="inline-flex items-center space-x-1.5 px-4 py-2.5 rounded-2xl text-xs font-black bg-purple-600 hover:bg-purple-700 text-white shadow-md border border-purple-400/30 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span>Dashboard Program</span>
                    </a>
                    <a href="{{ route('tvri.ecc.dashboard') }}" 
                       class="inline-flex items-center space-x-1.5 px-4 py-2.5 rounded-2xl text-xs font-black bg-white/10 hover:bg-white/20 text-white border border-white/20 transition-all">
                        <svg class="w-4 h-4 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <span>Monitoring ECC</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-sm">
            <span class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </span>
        </div>
    @endif

    <!-- Alert Warning -->
    @if(session('warning'))
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold flex items-center justify-between shadow-sm">
            <span class="flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>{{ session('warning') }}</span>
            </span>
        </div>
    @endif

    <!-- Alert Error / Validation -->
    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold shadow-sm space-y-1">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>Mohon periksa input formulir:</span>
            </div>
            <ul class="list-disc pl-5 font-normal">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- ===== TAB NAVIGASI CRUD PROGRAM ===== -->
    <div class="bg-white rounded-2xl p-2 border border-slate-200 shadow-sm flex items-center space-x-1.5 overflow-x-auto">
        <button @click="activeTab = 'program'" 
                :class="activeTab === 'program' ? 'bg-[#00264A] text-white shadow-sm font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'"
                class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap flex items-center space-x-1.5">
            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <span>Daftar Program (CRUD)</span>
            <span class="px-1.5 py-0.2 bg-purple-100 text-purple-700 rounded-full text-[10px] font-black">{{ $totalPrograms }}</span>
        </button>

        <button @click="activeTab = 'ringkasan'" 
                :class="activeTab === 'ringkasan' ? 'bg-[#00264A] text-white shadow-sm font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'"
                class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap flex items-center space-x-1.5">
            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            <span>Metrik Utama & KPI Siaran</span>
        </button>

        <button @click="activeTab = 'agenda'" 
                :class="activeTab === 'agenda' ? 'bg-[#00264A] text-white shadow-sm font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'"
                class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap flex items-center space-x-1.5">
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span>Kalender & Agenda Penayangan</span>
            <span class="px-1.5 py-0.2 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-black">{{ count($agendas) }}</span>
        </button>

        <button @click="activeTab = 'highlight'" 
                :class="activeTab === 'highlight' ? 'bg-[#00264A] text-white shadow-sm font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'"
                class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap flex items-center space-x-1.5">
            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
            <span>Program Highlight</span>
            <span class="px-1.5 py-0.2 bg-amber-100 text-amber-700 rounded-full text-[10px] font-black">{{ count($highlights) }}</span>
        </button>

        <button @click="activeTab = 'sop'" 
                :class="activeTab === 'sop' ? 'bg-[#00264A] text-white shadow-sm font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'"
                class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap flex items-center space-x-1.5">
            <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span>Data SOP Siaran</span>
            <span class="px-1.5 py-0.2 bg-teal-100 text-teal-700 rounded-full text-[10px] font-black">{{ count($sops) }}</span>
        </button>
    </div>

    <!-- ==================== TAB 1: DAFTAR PROGRAM (CRUD) ==================== -->
    <div x-show="activeTab === 'program'" class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Form Tambah Program Baru -->
        <div class="lg:col-span-4 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <h2 class="text-sm font-black uppercase text-[#00264A] flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                    <span>Tambah Program Baru</span>
                </h2>
                <span class="text-[10px] font-black text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full border border-purple-200">PRODUKSI / SIARAN</span>
            </div>

            <form action="{{ route('tvri.program.store') }}" method="POST" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul Program <span class="text-rose-500">*</span></label>
                    <input type="text" name="judul" required placeholder="Contoh: Pesona Maluku Episode Ora Beach" 
                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kategori <span class="text-rose-500">*</span></label>
                        <select name="kategori" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                            @foreach ($kategoriList as $k)
                                <option value="{{ $k }}">{{ $k }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tahap Produksi <span class="text-rose-500">*</span></label>
                        <select name="tahap" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                            @foreach ($tahapList as $t)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Status Program <span class="text-rose-500">*</span></label>
                        <select name="status" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                            @foreach ($statusList as $s)
                                <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Target Tayang</label>
                        <input type="date" name="target_tayang" value="{{ date('Y-m-d') }}"
                               class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Penanggung Jawab / PIC <span class="text-rose-500">*</span></label>
                    <input type="text" name="pic" required placeholder="Contoh: Tim Program / Produser" value="Tim Program"
                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Audience (Est)</label>
                        <input type="number" name="audience" placeholder="Contoh: 350000"
                               class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Rating (Est)</label>
                        <input type="number" step="0.1" name="rating" placeholder="Contoh: 2.8"
                               class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                    </div>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-black bg-purple-600 hover:bg-purple-700 text-white shadow-md transition-all">
                    + Simpan Program Baru
                </button>
            </form>

            <!-- Kotak Tambah Data SOP Siaran Cepat -->
            <div class="pt-4 border-t border-slate-100 space-y-2.5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black uppercase text-[#00264A] flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                        <span>Kotak Tambah Data SOP</span>
                    </span>
                    <span class="text-[10px] font-bold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-full border border-teal-200">
                        {{ count($sops) }} SOP Aktif
                    </span>
                </div>
                <p class="text-[11px] text-slate-500 leading-snug">
                    Buku panduan mutu penyiaran, kepatuhan on-air, tata kelola studio, dan standardisasi suara siaran TVRI Maluku.
                </p>
                <button type="button" @click="activeTab = 'sop'" 
                        class="w-full py-2 px-3 rounded-xl text-xs font-bold bg-teal-50 hover:bg-teal-100 text-teal-700 border border-teal-200 text-center flex items-center justify-center gap-1.5 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Tambah & Kelola Data SOP Siaran</span>
                </button>
            </div>
        </div>

        <!-- Tabel Daftar Program -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                <div>
                    <h2 class="text-sm font-black uppercase text-[#00264A]">Daftar Program Siaran TVRI Maluku</h2>
                    <p class="text-[11px] text-slate-500 font-medium">Total: {{ $totalPrograms }} program tercatat dalam pangkalan data</p>
                </div>

                <!-- Tombol Reseed & Hapus Semua Cepat -->
                <div class="flex items-center space-x-2">
                    <form action="{{ route('tvri.program.reseed') }}" method="POST" onsubmit="return confirm('Muat ulang 51 program standar TVRI Maluku ke sistem?')">
                        @csrf
                        <button type="submit" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 transition-colors" title="Muat ulang 51 data program standar">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            <span>Muat 51 Program</span>
                        </button>
                    </form>

                    @if($totalPrograms > 0)
                        <form action="{{ route('tvri.program.bulk-destroy') }}" method="POST" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin menghapus SELURUH {{ $totalPrograms }} data program di sistem?')">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="action" value="all">
                            <button type="submit" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                <span>Hapus Semua ({{ $totalPrograms }})</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Filter & Search Form -->
            <form method="GET" action="{{ route('tvri.program.manage') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-2.5">
                <input type="hidden" name="tab" value="program">
                <div class="sm:col-span-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul program atau PIC..." 
                           class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                </div>
                <div>
                    <select name="kategori" class="w-full text-xs px-2.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategoriList as $k)
                            <option value="{{ $k }}" {{ request('kategori') == $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <select name="status" class="w-full text-xs px-2.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                        <option value="">Semua Status</option>
                        @foreach ($statusList as $s)
                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-3 py-2 rounded-xl bg-purple-600 text-white text-xs font-bold hover:bg-purple-700">Filter</button>
                </div>
            </form>

            <!-- Bar Aksi Centrang Terpilih (Bulk Delete Action Bar) -->
            <div x-show="selectedPrograms.length > 0" x-cloak class="p-3 bg-rose-50 border border-rose-200 rounded-2xl flex flex-wrap items-center justify-between gap-2 transition-all">
                <div class="flex items-center space-x-2 text-xs font-bold text-rose-800">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-600 animate-pulse"></span>
                    <span><strong x-text="selectedPrograms.length"></strong> data program dicentang</span>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" @click="selectedPrograms = []" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-white text-slate-600 hover:bg-slate-100 border border-slate-200">
                        Batal
                    </button>
                    <form action="{{ route('tvri.program.bulk-destroy') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ' + selectedPrograms.length + ' data program yang dicentang?')">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="ids" :value="selectedPrograms.join(',')">
                        <button type="submit" class="px-3.5 py-1.5 rounded-xl text-xs font-black bg-rose-600 hover:bg-rose-700 text-white shadow-sm flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <span>Hapus Terpilih (<span x-text="selectedPrograms.length"></span>)</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 text-[10px] uppercase font-bold">
                            <!-- Kotak Centrang Header (Pilih Semua di Halaman) -->
                            <th class="py-2.5 px-3 text-center w-8">
                                <input type="checkbox" 
                                       @click="toggleAllPrograms()" 
                                       :checked="pageProgramIds.length > 0 && selectedPrograms.length === pageProgramIds.length"
                                       class="rounded border-slate-300 text-purple-600 focus:ring-purple-500 cursor-pointer w-4 h-4"
                                       title="Centang Semua Program di Halaman Ini">
                            </th>
                            <th class="py-2.5 px-3">Judul Program</th>
                            <th class="py-2.5 px-3">Kategori</th>
                            <th class="py-2.5 px-3">Tahap</th>
                            <th class="py-2.5 px-3">Target Tayang</th>
                            <th class="py-2.5 px-3">Status</th>
                            <th class="py-2.5 px-3">PIC</th>
                            <th class="py-2.5 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($programs as $p)
                            <tr class="hover:bg-slate-50 transition-colors" :class="selectedPrograms.includes({{ $p->id }}) ? 'bg-purple-50/50' : ''">
                                <!-- Kotak Centrang Row -->
                                <td class="py-2.5 px-3 text-center">
                                    <input type="checkbox" 
                                           :value="{{ $p->id }}" 
                                           x-model="selectedPrograms"
                                           class="rounded border-slate-300 text-purple-600 focus:ring-purple-500 cursor-pointer w-4 h-4">
                                </td>
                                <td class="py-2.5 px-3 font-bold text-[#00264A] max-w-[200px] truncate" title="{{ $p->judul }}">
                                    {{ $p->judul }}
                                </td>
                                <td class="py-2.5 px-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">{{ $p->kategori }}</span>
                                </td>
                                <td class="py-2.5 px-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">{{ $p->tahap }}</span>
                                </td>
                                <td class="py-2.5 px-3 text-slate-600 font-semibold whitespace-nowrap">
                                    {{ $p->target_tayang ? (is_string($p->target_tayang) ? substr($p->target_tayang, 0, 10) : $p->target_tayang->format('d M Y')) : '-' }}
                                </td>
                                <td class="py-2.5 px-3">
                                    @php
                                        $badgeClass = match($p->status) {
                                            'Tayang' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'On Schedule' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'Dalam Produksi', 'Produksi' => 'bg-purple-50 text-purple-700 border-purple-200',
                                            'Editing', 'Pasca Produksi' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                            'Terlambat' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            default => 'bg-slate-100 text-slate-700 border-slate-200'
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $badgeClass }} whitespace-nowrap">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 text-slate-600 whitespace-nowrap">
                                    {{ $p->pic }}
                                </td>
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center space-x-1.5">
                                        <button @click="editData = {
                                            id: '{{ $p->id }}',
                                            judul: '{{ addslashes($p->judul) }}',
                                            kategori: '{{ $p->kategori }}',
                                            tahap: '{{ $p->tahap }}',
                                            target_tayang: '{{ $p->target_tayang ? (is_string($p->target_tayang) ? substr($p->target_tayang, 0, 10) : $p->target_tayang->format('Y-m-d')) : '' }}',
                                            status: '{{ $p->status }}',
                                            pic: '{{ addslashes($p->pic) }}',
                                            audience: '{{ $p->audience }}',
                                            rating: '{{ $p->rating }}'
                                        }; editModal = true" class="p-1 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100" title="Edit">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>

                                        <form action="{{ route('tvri.program.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus program ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100" title="Hapus">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-6 text-center text-slate-400 font-medium">Tidak ada program yang sesuai kriteria filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-3 border-t border-slate-100">
                {{ $programs->links() }}
            </div>
        </div>
    </div>

    <!-- ==================== TAB 2: METRIK UTAMA & KPI SIARAN ==================== -->
    <div x-show="activeTab === 'ringkasan'" class="space-y-6">
        <form action="{{ route('tvri.program.ringkasan.update') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Card 1: 6 Metrik Utama Atas -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center space-x-2">
                            <span class="w-3 h-3 rounded-full bg-purple-600"></span>
                            <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">6 Metrik Utama Atas</h3>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400">Sinkronisasi Otomatis ke ECC</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Total Direncanakan</label>
                            <input type="number" name="total_direncanakan" value="{{ old('total_direncanakan', $ringkasan->total_direncanakan ?? 51) }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-purple-700">
                            <input type="text" name="diff_total" value="{{ old('diff_total', $ringkasan->diff_total ?? '+6%') }}" placeholder="+6%"
                                   class="w-full px-2 py-1 rounded-lg border border-slate-200 text-[10px] font-semibold text-slate-500 mt-1">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Dalam Produksi</label>
                            <input type="number" name="dalam_produksi" value="{{ old('dalam_produksi', $ringkasan->dalam_produksi ?? 18) }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-blue-700">
                            <input type="text" name="diff_produksi" value="{{ old('diff_produksi', $ringkasan->diff_produksi ?? '+20%') }}" placeholder="+20%"
                                   class="w-full px-2 py-1 rounded-lg border border-slate-200 text-[10px] font-semibold text-slate-500 mt-1">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Selesai Produksi</label>
                            <input type="number" name="selesai_produksi" value="{{ old('selesai_produksi', $ringkasan->selesai_produksi ?? 28) }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-cyan-700">
                            <input type="text" name="diff_selesai" value="{{ old('diff_selesai', $ringkasan->diff_selesai ?? '+12%') }}" placeholder="+12%"
                                   class="w-full px-2 py-1 rounded-lg border border-slate-200 text-[10px] font-semibold text-slate-500 mt-1">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Tayang</label>
                            <input type="number" name="tayang" value="{{ old('tayang', $ringkasan->tayang ?? 48) }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-emerald-700">
                            <input type="text" name="diff_tayang" value="{{ old('diff_tayang', $ringkasan->diff_tayang ?? '+14%') }}" placeholder="+14%"
                                   class="w-full px-2 py-1 rounded-lg border border-slate-200 text-[10px] font-semibold text-slate-500 mt-1">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Terlambat</label>
                            <input type="number" name="terlambat" value="{{ old('terlambat', $ringkasan->terlambat ?? 3) }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-rose-700">
                            <input type="text" name="diff_terlambat" value="{{ old('diff_terlambat', $ringkasan->diff_terlambat ?? '-50%') }}" placeholder="-50%"
                                   class="w-full px-2 py-1 rounded-lg border border-slate-200 text-[10px] font-semibold text-slate-500 mt-1">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Schedule Compliance (%)</label>
                            <input type="number" min="0" max="100" name="schedule_compliance" value="{{ old('schedule_compliance', $ringkasan->schedule_compliance ?? 94) }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-indigo-700">
                            <div class="flex items-center gap-1 mt-1">
                                <span class="text-[9px] text-slate-400 font-bold">Target:</span>
                                <input type="number" min="0" max="100" name="target_compliance" value="{{ old('target_compliance', $ringkasan->target_compliance ?? 90) }}"
                                       class="w-16 px-1.5 py-0.5 rounded border border-slate-200 text-[10px] font-bold text-slate-600">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Tahap Produksi Program -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center space-x-2">
                            <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                            <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Tahap Produksi Program (6 Alur)</h3>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200/60">
                            <p class="text-[10px] font-black uppercase text-purple-700 mb-1">1. Perencanaan</p>
                            <div class="flex gap-1.5">
                                <input type="number" name="tahap_perencanaan_count" value="{{ old('tahap_perencanaan_count', $ringkasan->tahap_perencanaan_count ?? 51) }}" class="w-1/2 px-2 py-1 rounded text-xs font-bold" placeholder="51">
                                <input type="number" name="tahap_perencanaan_pct" value="{{ old('tahap_perencanaan_pct', $ringkasan->tahap_perencanaan_pct ?? 100) }}" class="w-1/2 px-2 py-1 rounded text-xs font-bold text-purple-700" placeholder="100%">
                            </div>
                        </div>

                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200/60">
                            <p class="text-[10px] font-black uppercase text-blue-700 mb-1">2. Pra Produksi</p>
                            <div class="flex gap-1.5">
                                <input type="number" name="tahap_pra_produksi_count" value="{{ old('tahap_pra_produksi_count', $ringkasan->tahap_pra_produksi_count ?? 45) }}" class="w-1/2 px-2 py-1 rounded text-xs font-bold" placeholder="45">
                                <input type="number" name="tahap_pra_produksi_pct" value="{{ old('tahap_pra_produksi_pct', $ringkasan->tahap_pra_produksi_pct ?? 88) }}" class="w-1/2 px-2 py-1 rounded text-xs font-bold text-blue-700" placeholder="88%">
                            </div>
                        </div>

                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200/60">
                            <p class="text-[10px] font-black uppercase text-cyan-700 mb-1">3. Produksi</p>
                            <div class="flex gap-1.5">
                                <input type="number" name="tahap_produksi_count" value="{{ old('tahap_produksi_count', $ringkasan->tahap_produksi_count ?? 36) }}" class="w-1/2 px-2 py-1 rounded text-xs font-bold" placeholder="36">
                                <input type="number" name="tahap_produksi_pct" value="{{ old('tahap_produksi_pct', $ringkasan->tahap_produksi_pct ?? 71) }}" class="w-1/2 px-2 py-1 rounded text-xs font-bold text-cyan-700" placeholder="71%">
                            </div>
                        </div>

                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200/60">
                            <p class="text-[10px] font-black uppercase text-indigo-700 mb-1">4. Pasca Produksi</p>
                            <div class="flex gap-1.5">
                                <input type="number" name="tahap_pasca_produksi_count" value="{{ old('tahap_pasca_produksi_count', $ringkasan->tahap_pasca_produksi_count ?? 28) }}" class="w-1/2 px-2 py-1 rounded text-xs font-bold" placeholder="28">
                                <input type="number" name="tahap_pasca_produksi_pct" value="{{ old('tahap_pasca_produksi_pct', $ringkasan->tahap_pasca_produksi_pct ?? 55) }}" class="w-1/2 px-2 py-1 rounded text-xs font-bold text-indigo-700" placeholder="55%">
                            </div>
                        </div>

                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200/60">
                            <p class="text-[10px] font-black uppercase text-amber-700 mb-1">5. QC</p>
                            <div class="flex gap-1.5">
                                <input type="number" name="tahap_qc_count" value="{{ old('tahap_qc_count', $ringkasan->tahap_qc_count ?? 28) }}" class="w-1/2 px-2 py-1 rounded text-xs font-bold" placeholder="28">
                                <input type="number" name="tahap_qc_pct" value="{{ old('tahap_qc_pct', $ringkasan->tahap_qc_pct ?? 55) }}" class="w-1/2 px-2 py-1 rounded text-xs font-bold text-amber-700" placeholder="55%">
                            </div>
                        </div>

                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200/60">
                            <p class="text-[10px] font-black uppercase text-emerald-700 mb-1">6. Tayang</p>
                            <div class="flex gap-1.5">
                                <input type="number" name="tahap_tayang_count" value="{{ old('tahap_tayang_count', $ringkasan->tahap_tayang_count ?? 48) }}" class="w-1/2 px-2 py-1 rounded text-xs font-bold" placeholder="48">
                                <input type="number" name="tahap_tayang_pct" value="{{ old('tahap_tayang_pct', $ringkasan->tahap_tayang_pct ?? 94) }}" class="w-1/2 px-2 py-1 rounded text-xs font-bold text-emerald-700" placeholder="94%">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Performa Penayangan -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                    <div class="flex items-center space-x-2 pb-3 border-b border-slate-100">
                        <span class="w-3 h-3 rounded-full bg-cyan-600"></span>
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Performa Penayangan</h3>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Total Audience</label>
                            <input type="text" name="total_audience" value="{{ old('total_audience', $ringkasan->total_audience ?? '1,2 Juta') }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-[#00264A]">
                            <input type="text" name="diff_audience" value="{{ old('diff_audience', $ringkasan->diff_audience ?? '+18%') }}" placeholder="+18%"
                                   class="w-full px-2 py-1 rounded-lg border border-slate-200 text-[10px] font-semibold text-emerald-600 mt-1">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Rating Rata-rata</label>
                            <input type="text" name="rating_rata" value="{{ old('rating_rata', $ringkasan->rating_rata ?? '2,8') }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-[#00264A]">
                            <input type="text" name="diff_rating" value="{{ old('diff_rating', $ringkasan->diff_rating ?? '+12%') }}" placeholder="+12%"
                                   class="w-full px-2 py-1 rounded-lg border border-slate-200 text-[10px] font-semibold text-emerald-600 mt-1">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Share (%)</label>
                            <input type="text" name="share" value="{{ old('share', $ringkasan->share ?? '12%') }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-[#00264A]">
                            <input type="text" name="diff_share" value="{{ old('diff_share', $ringkasan->diff_share ?? '+20%') }}" placeholder="+20%"
                                   class="w-full px-2 py-1 rounded-lg border border-slate-200 text-[10px] font-semibold text-emerald-600 mt-1">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Durasi Tonton</label>
                            <input type="text" name="durasi_tonton" value="{{ old('durasi_tonton', $ringkasan->durasi_tonton ?? '18 Menit') }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-[#00264A]">
                            <input type="text" name="diff_durasi" value="{{ old('diff_durasi', $ringkasan->diff_durasi ?? '+15%') }}" placeholder="+15%"
                                   class="w-full px-2 py-1 rounded-lg border border-slate-200 text-[10px] font-semibold text-emerald-600 mt-1">
                        </div>
                    </div>
                </div>

                <!-- Card 4: Header & Slogan TVRI Maluku -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                    <div class="flex items-center space-x-2 pb-3 border-b border-slate-100">
                        <span class="w-3 h-3 rounded-full bg-emerald-600"></span>
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Header, Periode & Slogan</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Periode</label>
                            <input type="text" name="periode" value="{{ old('periode', $ringkasan->periode ?? 'September 2025') }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Header Waktu (Fallback)</label>
                            <input type="text" name="header_waktu" value="{{ old('header_waktu', $ringkasan->header_waktu ?? 'Selasa, 2 September 2025 | 10:24 WIT') }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Status Bar</label>
                        <input type="text" name="live_status" value="{{ old('live_status', $ringkasan->live_status ?? 'Live System | Periode: September 2025') }}"
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Slogan Header Utama</label>
                            <input type="text" name="slogan_header" value="{{ old('slogan_header', $ringkasan->slogan_header ?? 'KONTEN BERKUALITAS, MENGANGKAT POTENSI MALUKU, UNTUK INDONESIA') }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Sub Slogan</label>
                            <input type="text" name="slogan_sub" value="{{ old('slogan_sub', $ringkasan->slogan_sub ?? 'Dari Maluku untuk Indonesia') }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Slogan Footer</label>
                            <input type="text" name="footer_slogan" value="{{ old('footer_slogan', $ringkasan->footer_slogan ?? 'Maluku Bisa! Indonesia Maju!') }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Nilai Utama Footer</label>
                            <input type="text" name="footer_nilai" value="{{ old('footer_nilai', $ringkasan->footer_nilai ?? 'Profesional | Konten Lokal, Dampak Global | Kolaboratif | Inovatif | Berkelanjutan') }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                        </div>
                    </div>
                </div>

            </div>

            <!-- Submit Button -->
            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-6 py-3 rounded-2xl bg-[#00264A] hover:bg-[#003B70] text-white text-xs font-black shadow-lg transition-all flex items-center space-x-2">
                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan Metrik & Singkronkan ke Dashboard & Executive Command Center</span>
                </button>
            </div>
        </form>
    </div>

    <!-- ==================== TAB 3: KALENDER & AGENDA PENAYANGAN ==================== -->
    <div x-show="activeTab === 'agenda'" class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Form Tambah Agenda -->
        <div class="lg:col-span-4 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <h2 class="text-sm font-black uppercase text-[#00264A] flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                    <span>Tambah Agenda Penayangan</span>
                </h2>
                <span class="text-[10px] font-black text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">KALENDER</span>
            </div>

            <form action="{{ route('tvri.program.agenda.store') }}" method="POST" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal & Waktu <span class="text-rose-500">*</span></label>
                    <input type="text" name="tanggal" required placeholder="Contoh: 2 Sep 2025" 
                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kegiatan / Agenda <span class="text-rose-500">*</span></label>
                    <input type="text" name="kegiatan" required placeholder="Contoh: Tayang: Maluku Hari Ini (Live)" 
                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tipe Agenda <span class="text-rose-500">*</span></label>
                        <select name="tipe" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                            <option value="Tayang">Tayang (Hijau)</option>
                            <option value="Produksi">Produksi (Biru)</option>
                            <option value="Event Khusus">Event Khusus (Kuning)</option>
                            <option value="Deadline">Deadline (Merah)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Urutan</label>
                        <input type="number" name="urutan" value="{{ count($agendas) + 1 }}"
                               class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                    </div>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-black bg-emerald-600 hover:bg-emerald-700 text-white shadow-md transition-all">
                    + Tambah ke Agenda Kalender
                </button>
            </form>
        </div>

        <!-- Tabel Daftar Agenda -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h2 class="text-sm font-black uppercase text-[#00264A]">Agenda Penting Penayangan (September 2025)</h2>
                    <p class="text-[11px] text-slate-500 font-medium">Ditampilkan pada widget Agenda Penting & Kalender Siaran</p>
                </div>

                @if(count($agendas) > 0)
                    <form action="{{ route('tvri.program.agenda.bulk-destroy') }}" method="POST" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin menghapus SELURUH {{ count($agendas) }} agenda penayangan?')">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="action" value="all">
                        <button type="submit" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <span>Hapus Semua Agenda ({{ count($agendas) }})</span>
                        </button>
                    </form>
                @endif
            </div>

            <!-- Bar Aksi Centrang Agenda Terpilih -->
            <div x-show="selectedAgendas.length > 0" x-cloak class="p-3 bg-rose-50 border border-rose-200 rounded-2xl flex flex-wrap items-center justify-between gap-2 transition-all">
                <div class="flex items-center space-x-2 text-xs font-bold text-rose-800">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-600 animate-pulse"></span>
                    <span><strong x-text="selectedAgendas.length"></strong> agenda dicentang</span>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" @click="selectedAgendas = []" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-white text-slate-600 hover:bg-slate-100 border border-slate-200">
                        Batal
                    </button>
                    <form action="{{ route('tvri.program.agenda.bulk-destroy') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ' + selectedAgendas.length + ' agenda yang dicentang?')">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="ids" :value="selectedAgendas.join(',')">
                        <button type="submit" class="px-3.5 py-1.5 rounded-xl text-xs font-black bg-rose-600 hover:bg-rose-700 text-white shadow-sm flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <span>Hapus Terpilih (<span x-text="selectedAgendas.length"></span>)</span>
                        </button>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 text-[10px] uppercase font-bold">
                            <!-- Kotak Centrang Header Agenda -->
                            <th class="py-2.5 px-3 text-center w-8">
                                <input type="checkbox" 
                                       @click="toggleAllAgendas()" 
                                       :checked="allAgendaIds.length > 0 && selectedAgendas.length === allAgendaIds.length"
                                       class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer w-4 h-4"
                                       title="Centang Semua Agenda">
                            </th>
                            <th class="py-2.5 px-3">Urutan</th>
                            <th class="py-2.5 px-3">Tanggal</th>
                            <th class="py-2.5 px-3">Kegiatan / Agenda</th>
                            <th class="py-2.5 px-3">Tipe</th>
                            <th class="py-2.5 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($agendas as $a)
                            <tr class="hover:bg-slate-50 transition-colors" :class="selectedAgendas.includes({{ $a->id }}) ? 'bg-emerald-50/50' : ''">
                                <td class="py-2.5 px-3 text-center">
                                    <input type="checkbox" 
                                           :value="{{ $a->id }}" 
                                           x-model="selectedAgendas"
                                           class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer w-4 h-4">
                                </td>
                                <td class="py-2.5 px-3 font-bold text-slate-400">#{{ $a->urutan }}</td>
                                <td class="py-2.5 px-3 font-bold text-[#00264A] whitespace-nowrap">{{ $a->tanggal }}</td>
                                <td class="py-2.5 px-3 font-medium text-slate-700">{{ $a->kegiatan }}</td>
                                <td class="py-2.5 px-3">
                                    @php
                                        $tipeBadge = match($a->tipe) {
                                            'Tayang' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'Produksi' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'Event Khusus' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'Deadline' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            default => 'bg-slate-100 text-slate-700 border-slate-200'
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $tipeBadge }} whitespace-nowrap">
                                        {{ $a->tipe }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    <form action="{{ route('tvri.program.agenda.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Hapus agenda ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100" title="Hapus Agenda">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-slate-400 font-medium">Belum ada agenda penayangan yang dimasukkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ==================== TAB 4: PROGRAM HIGHLIGHT ==================== -->
    <div x-show="activeTab === 'highlight'" class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Form Tambah Highlight -->
        <div class="lg:col-span-4 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <h2 class="text-sm font-black uppercase text-[#00264A] flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-600"></span>
                    <span>Tambah Highlight Program</span>
                </h2>
                <span class="text-[10px] font-black text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">HIGHLIGHT</span>
            </div>

            <form action="{{ route('tvri.program.highlight.store') }}" method="POST" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul Highlight <span class="text-rose-500">*</span></label>
                    <input type="text" name="judul" required placeholder="Contoh: MALUKU HARI INI" 
                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tagline Program <span class="text-rose-500">*</span></label>
                    <input type="text" name="tagline" required placeholder="Contoh: Berita Terpercaya untuk Anda" 
                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Ikon</label>
                        <select name="icon" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                            <option value="newspaper">Newspaper (Berita)</option>
                            <option value="camera">Camera (Wisata)</option>
                            <option value="chat">Chat (Talkshow)</option>
                            <option value="sparkles">Sparkles (Budaya)</option>
                            <option value="academic">Academic (Pendidikan)</option>
                            <option value="tv">TV (Umum)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Urutan</label>
                        <input type="number" name="urutan" value="{{ count($highlights) + 1 }}"
                               class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                    </div>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-black bg-amber-600 hover:bg-amber-700 text-white shadow-md transition-all">
                    + Tambah Program Highlight
                </button>
            </form>
        </div>

        <!-- Tabel Daftar Highlight -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h2 class="text-sm font-black uppercase text-[#00264A]">Daftar Program Highlight</h2>
                    <p class="text-[11px] text-slate-500 font-medium">Ditampilkan pada carousel Program Highlight di Dashboard</p>
                </div>

                @if(count($highlights) > 0)
                    <form action="{{ route('tvri.program.highlight.bulk-destroy') }}" method="POST" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin menghapus SELURUH {{ count($highlights) }} program highlight?')">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="action" value="all">
                        <button type="submit" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <span>Hapus Semua Highlight ({{ count($highlights) }})</span>
                        </button>
                    </form>
                @endif
            </div>

            <!-- Bar Aksi Centrang Highlight Terpilih -->
            <div x-show="selectedHighlights.length > 0" x-cloak class="p-3 bg-rose-50 border border-rose-200 rounded-2xl flex flex-wrap items-center justify-between gap-2 transition-all">
                <div class="flex items-center space-x-2 text-xs font-bold text-rose-800">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-600 animate-pulse"></span>
                    <span><strong x-text="selectedHighlights.length"></strong> highlight dicentang</span>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" @click="selectedHighlights = []" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-white text-slate-600 hover:bg-slate-100 border border-slate-200">
                        Batal
                    </button>
                    <form action="{{ route('tvri.program.highlight.bulk-destroy') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ' + selectedHighlights.length + ' highlight yang dicentang?')">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="ids" :value="selectedHighlights.join(',')">
                        <button type="submit" class="px-3.5 py-1.5 rounded-xl text-xs font-black bg-rose-600 hover:bg-rose-700 text-white shadow-sm flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <span>Hapus Terpilih (<span x-text="selectedHighlights.length"></span>)</span>
                        </button>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 text-[10px] uppercase font-bold">
                            <!-- Kotak Centrang Header Highlight -->
                            <th class="py-2.5 px-3 text-center w-8">
                                <input type="checkbox" 
                                       @click="toggleAllHighlights()" 
                                       :checked="allHighlightIds.length > 0 && selectedHighlights.length === allHighlightIds.length"
                                       class="rounded border-slate-300 text-amber-600 focus:ring-amber-500 cursor-pointer w-4 h-4"
                                       title="Centang Semua Highlight">
                            </th>
                            <th class="py-2.5 px-3">Urutan</th>
                            <th class="py-2.5 px-3">Judul Program</th>
                            <th class="py-2.5 px-3">Tagline</th>
                            <th class="py-2.5 px-3">Ikon</th>
                            <th class="py-2.5 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($highlights as $h)
                            <tr class="hover:bg-slate-50 transition-colors" :class="selectedHighlights.includes({{ $h->id }}) ? 'bg-amber-50/50' : ''">
                                <td class="py-2.5 px-3 text-center">
                                    <input type="checkbox" 
                                           :value="{{ $h->id }}" 
                                           x-model="selectedHighlights"
                                           class="rounded border-slate-300 text-amber-600 focus:ring-amber-500 cursor-pointer w-4 h-4">
                                </td>
                                <td class="py-2.5 px-3 font-bold text-slate-400">#{{ $h->urutan }}</td>
                                <td class="py-2.5 px-3 font-bold text-[#00264A]">{{ $h->judul }}</td>
                                <td class="py-2.5 px-3 text-slate-600">{{ $h->tagline }}</td>
                                <td class="py-2.5 px-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">{{ $h->icon }}</span>
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    <form action="{{ route('tvri.program.highlight.destroy', $h->id) }}" method="POST" onsubmit="return confirm('Hapus highlight ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100" title="Hapus Highlight">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-slate-400 font-medium">Belum ada highlight program yang dimasukkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ==================== TAB 5: STANDAR OPERASIONAL PROSEDUR (SOP) ==================== -->
    <div x-show="activeTab === 'sop'" class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Form Tambah SOP -->
        <div class="lg:col-span-4 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <h2 class="text-sm font-black uppercase text-[#00264A] flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-teal-600"></span>
                    <span>Tambah Data SOP Siaran</span>
                </h2>
                <span class="text-[10px] font-black text-teal-700 bg-teal-50 px-2 py-0.5 rounded-full border border-teal-200">SOP RESMI</span>
            </div>

            <form action="{{ route('tvri.program.sop.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kode SOP <span class="text-rose-500">*</span></label>
                        <input type="text" name="kode" required placeholder="Contoh: SOP-PRG-009" value="SOP-PRG-00{{ count($sops) + 1 }}"
                               class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Tahap <span class="text-rose-500">*</span></label>
                        <select name="kategori" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                            @foreach ($tahapList as $t)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endforeach
                            <option value="Evaluasi">Evaluasi</option>
                            <option value="Umum Siaran">Umum Siaran</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul Standar Prosedur (SOP) <span class="text-rose-500">*</span></label>
                    <input type="text" name="judul" required placeholder="Contoh: SOP Penyiaran Khusus Siaran Langsung Pilkada" 
                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Standar Suara / Format Audio Siaran</label>
                    <input type="text" name="suara_siaran" placeholder="Contoh: Stereo EBU R128 (-23 LUFS) / 48kHz 24-bit" 
                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Revisi</label>
                        <input type="text" name="revisi" placeholder="Rev. 01 / 2025" value="Rev. 01 / 2025"
                               class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Status Prosedur <span class="text-rose-500">*</span></label>
                        <select name="status" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                            <option value="Aktif">Aktif</option>
                            <option value="Review">Review</option>
                            <option value="Draft">Draft</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Singkat / Ruang Lingkup</label>
                    <textarea name="keterangan" rows="2" placeholder="Penjelasan ringkas alur kerja dan standar kepatuhan operasional..."
                              class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Unggah Fisik SOP (PDF/Dokumen)</label>
                        <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png"
                               class="w-full text-xs px-2.5 py-1.5 rounded-xl border border-slate-200 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Urutan</label>
                        <input type="number" name="urutan" value="{{ count($sops) + 1 }}"
                               class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                    </div>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-black bg-teal-600 hover:bg-teal-700 text-white shadow-md transition-all">
                    + Simpan Data SOP
                </button>
            </form>
        </div>

        <!-- Tabel Daftar SOP -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h2 class="text-sm font-black uppercase text-[#00264A]">Pangkalan Data Standar Operasional Prosedur (SOP)</h2>
                    <p class="text-[11px] text-slate-500 font-medium">Buku panduan baku penyiaran, kepatuhan on-air, QC, dan keselamatan siaran</p>
                </div>

                @if(count($sops) > 0)
                    <form action="{{ route('tvri.program.sop.bulk-destroy') }}" method="POST" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin menghapus SELURUH {{ count($sops) }} data SOP siaran?')">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="action" value="all">
                        <button type="submit" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <span>Hapus Semua SOP ({{ count($sops) }})</span>
                        </button>
                    </form>
                @endif
            </div>

            <!-- Bar Aksi Centrang SOP Terpilih -->
            <div x-show="selectedSops.length > 0" x-cloak class="p-3 bg-rose-50 border border-rose-200 rounded-2xl flex flex-wrap items-center justify-between gap-2 transition-all">
                <div class="flex items-center space-x-2 text-xs font-bold text-rose-800">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-600 animate-pulse"></span>
                    <span><strong x-text="selectedSops.length"></strong> SOP dicentang</span>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" @click="selectedSops = []" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-white text-slate-600 hover:bg-slate-100 border border-slate-200">
                        Batal
                    </button>
                    <form action="{{ route('tvri.program.sop.bulk-destroy') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ' + selectedSops.length + ' data SOP yang dicentang?')">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="ids" :value="selectedSops.join(',')">
                        <button type="submit" class="px-3.5 py-1.5 rounded-xl text-xs font-black bg-rose-600 hover:bg-rose-700 text-white shadow-sm flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <span>Hapus Terpilih (<span x-text="selectedSops.length"></span>)</span>
                        </button>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 text-[10px] uppercase font-bold">
                            <!-- Kotak Centrang Header SOP -->
                            <th class="py-2.5 px-3 text-center w-8">
                                <input type="checkbox" 
                                       @click="toggleAllSops()" 
                                       :checked="allSopIds.length > 0 && selectedSops.length === allSopIds.length"
                                       class="rounded border-slate-300 text-teal-600 focus:ring-teal-500 cursor-pointer w-4 h-4"
                                       title="Centang Semua SOP">
                            </th>
                            <th class="py-2.5 px-3">Kode</th>
                            <th class="py-2.5 px-3">Judul Prosedur (SOP)</th>
                            <th class="py-2.5 px-3">Kategori</th>
                            <th class="py-2.5 px-3">Revisi</th>
                            <th class="py-2.5 px-3">Status</th>
                            <th class="py-2.5 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($sops as $s)
                            <tr class="hover:bg-slate-50 transition-colors" :class="selectedSops.includes({{ $s->id }}) ? 'bg-teal-50/50' : ''">
                                <td class="py-2.5 px-3 text-center">
                                    <input type="checkbox" 
                                           :value="{{ $s->id }}" 
                                           x-model="selectedSops"
                                           class="rounded border-slate-300 text-teal-600 focus:ring-teal-500 cursor-pointer w-4 h-4">
                                </td>
                                <td class="py-2.5 px-3 font-mono font-bold text-teal-700 whitespace-nowrap">{{ $s->kode }}</td>
                                <td class="py-2.5 px-3 font-bold text-[#00264A]">
                                    <p>{{ $s->judul }}</p>
                                    @if($s->suara_siaran)
                                        <p class="text-[10px] font-semibold text-cyan-700 mt-0.5 flex items-center gap-1">
                                            <svg class="w-3 h-3 text-cyan-600 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                                            <span>{{ $s->suara_siaran }}</span>
                                        </p>
                                    @endif
                                    @if($s->keterangan)
                                        <p class="text-[10px] font-normal text-slate-400 mt-0.5 line-clamp-1">{{ $s->keterangan }}</p>
                                    @endif
                                </td>
                                <td class="py-2.5 px-3 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">{{ $s->kategori }}</span>
                                </td>
                                <td class="py-2.5 px-3 font-semibold text-slate-500 whitespace-nowrap">{{ $s->revisi }}</td>
                                <td class="py-2.5 px-3 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $s->status === 'Aktif' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200' }}">
                                        {{ $s->status }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center space-x-1.5">
                                        @if($s->file_url || $s->file_path)
                                            <a href="{{ asset($s->file_url ?: $s->file_path) }}" target="_blank" class="p-1 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 inline-flex items-center space-x-1" title="Lihat Fisik SOP">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                        @endif
                                        <button @click="editSopData = {
                                            id: '{{ $s->id }}',
                                            kode: '{{ addslashes($s->kode) }}',
                                            judul: '{{ addslashes($s->judul) }}',
                                            kategori: '{{ $s->kategori }}',
                                            suara_siaran: '{{ addslashes($s->suara_siaran ?? '') }}',
                                            revisi: '{{ addslashes($s->revisi) }}',
                                            status: '{{ $s->status }}',
                                            file_url: '{{ addslashes($s->file_url ?? '') }}',
                                            keterangan: '{{ addslashes($s->keterangan ?? '') }}',
                                            urutan: '{{ $s->urutan }}'
                                        }; editSopModal = true" class="p-1 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100" title="Edit SOP">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>

                                        <form action="{{ route('tvri.program.sop.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus SOP ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100" title="Hapus SOP">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-6 text-center text-slate-400 font-medium">Belum ada data SOP yang dimasukkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ===== MODAL EDIT PROGRAM ===== -->
    <div x-show="editModal" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         @keydown.escape.window="editModal = false">
        <div class="relative w-full max-w-lg bg-white rounded-3xl p-6 shadow-2xl border border-slate-200"
             @click.away="editModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-black uppercase text-[#00264A]">Edit Data Program</h3>
                <button @click="editModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
            </div>

            <form :action="'{{ url('tvri/program') }}/' + editData.id" method="POST" class="mt-4 space-y-3.5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul Program</label>
                    <input type="text" name="judul" x-model="editData.judul" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kategori</label>
                        <select name="kategori" x-model="editData.kategori" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200">
                            @foreach ($kategoriList as $k)
                                <option value="{{ $k }}">{{ $k }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tahap Produksi</label>
                        <select name="tahap" x-model="editData.tahap" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200">
                            @foreach ($tahapList as $t)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
                        <select name="status" x-model="editData.status" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200">
                            @foreach ($statusList as $s)
                                <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Target Tayang</label>
                        <input type="date" name="target_tayang" x-model="editData.target_tayang" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Penanggung Jawab / PIC</label>
                    <input type="text" name="pic" x-model="editData.pic" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Audience (Est)</label>
                        <input type="number" name="audience" x-model="editData.audience" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Rating (Est)</label>
                        <input type="number" step="0.1" name="rating" x-model="editData.rating" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200">
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="editModal = false" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-black bg-purple-600 hover:bg-purple-700 text-white shadow-md">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===== MODAL EDIT SOP ===== -->
    <div x-show="editSopModal" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         @keydown.escape.window="editSopModal = false">
        <div class="relative w-full max-w-lg bg-white rounded-3xl p-6 shadow-2xl border border-slate-200"
             @click.away="editSopModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-black uppercase text-[#00264A]">Edit Data SOP Siaran</h3>
                <button @click="editSopModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
            </div>

            <form :action="'{{ url('tvri/program/sop') }}/' + editSopData.id" method="POST" enctype="multipart/form-data" class="mt-4 space-y-3.5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kode SOP</label>
                        <input type="text" name="kode" x-model="editSopData.kode" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Tahap</label>
                        <select name="kategori" x-model="editSopData.kategori" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200">
                            @foreach ($tahapList as $t)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endforeach
                            <option value="Evaluasi">Evaluasi</option>
                            <option value="Umum Siaran">Umum Siaran</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul SOP</label>
                    <input type="text" name="judul" x-model="editSopData.judul" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Standar Suara / Format Audio Siaran</label>
                    <input type="text" name="suara_siaran" x-model="editSopData.suara_siaran" placeholder="Contoh: Stereo EBU R128 (-23 LUFS) / 48kHz 24-bit" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Revisi</label>
                        <input type="text" name="revisi" x-model="editSopData.revisi" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Status Prosedur</label>
                        <select name="status" x-model="editSopData.status" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200">
                            <option value="Aktif">Aktif</option>
                            <option value="Review">Review</option>
                            <option value="Draft">Draft</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Singkat</label>
                    <textarea name="keterangan" x-model="editSopData.keterangan" rows="2" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Unggah Fisik SOP Baru (PDF/Dokumen)</label>
                        <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png" class="w-full text-xs px-2.5 py-1.5 rounded-xl border border-slate-200 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Urutan</label>
                        <input type="number" name="urutan" x-model="editSopData.urutan" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200">
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="editSopModal = false" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-black bg-teal-600 hover:bg-teal-700 text-white shadow-md">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
