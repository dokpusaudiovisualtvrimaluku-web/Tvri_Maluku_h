@extends('layouts.app')

@section('title', 'Kelola & Input Data Berita (Newsroom) - TVRI Maluku')

@php
    $currentTab = request('tab', $tab ?? 'berita');
    use App\Http\Controllers\Tvri\BeritaController;
@endphp

@section('content')
<div class="space-y-6 pb-12" x-data="{ 
    activeTab: '{{ $currentTab }}', 
    editBerita: null,
    editAgendaModal: false,
    editAgendaData: {},
    agendaFilterHari: '',
    agendaFilterCrew: '',
    agendaFilterJenis: '',
    agendaSearch: '',
    openEditAgenda(ag) {
        this.editAgendaData = Object.assign({}, ag);
        this.editAgendaModal = true;
    },
    editProduksiModal: false,
    editProduksiData: {},
    openEditProduksi(pkt) {
        this.editProduksiData = Object.assign({}, pkt);
        this.editProduksiModal = true;
    },
    editMonitoringModal: false,
    editMonitoringData: {},
    openEditMonitoring(mon) {
        this.editMonitoringData = Object.assign({}, mon);
        this.editMonitoringModal = true;
    },
    editDokumentasiModal: false,
    editDokumentasiData: {},
    openEditDokumentasi(dok) {
        this.editDokumentasiData = Object.assign({}, dok);
        this.editDokumentasiModal = true;
    }
}">

    <!-- ===== HEADER MANAJEMEN BERITA ===== -->
    <div class="relative bg-gradient-to-r from-[#00142A] via-[#002244] to-[#003B70] rounded-3xl p-6 sm:p-7 text-white shadow-xl overflow-hidden border border-cyan-400/20">
        <div class="absolute -right-10 -top-16 w-72 h-72 bg-cyan-400/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-white p-2 shadow-lg flex-shrink-0 flex items-center justify-center border border-cyan-300/40">
                    <img src="{{ asset('images/logo_tvri.png') }}" alt="Logo TVRI" class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-cyan-400/20 text-cyan-300 border border-cyan-400/30">
                            CRUD DATA NEWSROOM
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white mt-1 leading-tight uppercase">
                        Kelola & Input Data Berita
                    </h1>
                    <p class="text-xs text-blue-300 font-medium">
                        Input liputan, produksi berita, real time feeds, agenda liputan, dan peringatan redaksi yang terhubung ke Dashboard Berita.
                    </p>
                </div>
            </div>

            <!-- Header Jam Real-Time & Action Buttons -->
            <div class="flex items-center space-x-2.5 flex-wrap gap-2">
                <!-- Jam, Hari, Tanggal, Tahun Real-Time Widget -->
                <div class="bg-black/35 backdrop-blur-md px-3.5 py-2 rounded-2xl border border-cyan-400/30 shadow-inner flex items-center space-x-2.5">
                    <div class="relative flex items-center justify-center">
                        <span class="animate-ping absolute inline-flex h-2.5 w-2.5 rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-cyan-300 leading-tight flex items-center space-x-1">
                            <svg class="w-3 h-3 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span id="realtimeDayDate">Memuat Waktu...</span>
                        </div>
                        <div class="text-xs sm:text-sm font-black font-mono tracking-wider text-white mt-0.5 leading-none flex items-center space-x-1">
                            <svg class="w-3 h-3 text-amber-300 animate-spin" style="animation-duration: 8s;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span id="realtimeClock">--:--:-- WIT</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('tvri.berita.dashboard') }}" 
                   class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-2xl text-xs font-black bg-cyan-600 hover:bg-cyan-700 text-white shadow-md border border-cyan-400/30 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Dashboard Berita</span>
                </a>
                <a href="{{ route('dokumen.documents.index') }}" 
                   class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-2xl text-xs font-black bg-white/10 hover:bg-white/20 text-white border border-white/20 transition-all">
                    <svg class="w-4 h-4 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Pangkalan Dokumen</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ===== TAB NAVIGASI CRUD BERITA ===== -->
    <div class="bg-white rounded-2xl p-2 border border-slate-200 shadow-sm flex items-center space-x-1.5 overflow-x-auto">
        <button @click="activeTab = 'berita'" 
                :class="activeTab === 'berita' ? 'bg-[#005B9F] text-white shadow-sm font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'"
                class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap flex items-center space-x-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            <span>Daftar Berita (CRUD)</span>
        </button>

        <button @click="activeTab = 'ringkasan'" 
                :class="activeTab === 'ringkasan' ? 'bg-[#005B9F] text-white shadow-sm font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'"
                class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap flex items-center space-x-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            <span>Metrik & Ringkasan Newsroom</span>
        </button>

        <button @click="activeTab = 'realtime'" 
                :class="activeTab === 'realtime' ? 'bg-[#005B9F] text-white shadow-sm font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'"
                class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap flex items-center space-x-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            <span>Real Time Feeds (• LIVE)</span>
        </button>

        <button @click="activeTab = 'agenda'" 
                :class="activeTab === 'agenda' ? 'bg-[#005B9F] text-white shadow-sm font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'"
                class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap flex items-center space-x-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span>Agenda Liputan</span>
        </button>

        <button @click="activeTab = 'produksi'" 
                :class="activeTab === 'produksi' ? 'bg-[#005B9F] text-white shadow-sm font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'"
                class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap flex items-center space-x-1.5">
            <span>📦 Produksi Paket</span>
            <span class="text-[10px] bg-white/20 px-1.5 py-0.5 rounded-full font-bold">{{ count($produksis) }}</span>
        </button>

        <button @click="activeTab = 'monitoring'" 
                :class="activeTab === 'monitoring' ? 'bg-[#005B9F] text-white shadow-sm font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'"
                class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap flex items-center space-x-1.5">
            <span>📡 Monitoring Berita</span>
            <span class="text-[10px] bg-white/20 px-1.5 py-0.5 rounded-full font-bold">{{ count($monitorings) }}</span>
        </button>

        <button @click="activeTab = 'dokumentasi'" 
                :class="activeTab === 'dokumentasi' ? 'bg-[#005B9F] text-white shadow-sm font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'"
                class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap flex items-center space-x-1.5">
            <span>🗄️ Dokumentasi Siaran</span>
            <span class="text-[10px] bg-white/20 px-1.5 py-0.5 rounded-full font-bold">{{ count($dokumentasis) }}</span>
        </button>

        <button @click="activeTab = 'catatan'" 
                :class="activeTab === 'catatan' ? 'bg-[#005B9F] text-white shadow-sm font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'"
                class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap flex items-center space-x-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <span>Peringatan & Catatan</span>
        </button>

        <button @click="activeTab = 'sop'" 
                :class="activeTab === 'sop' ? 'bg-[#005B9F] text-white shadow-sm font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'"
                class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap flex items-center space-x-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span>Dokumen & SOP Berita</span>
        </button>
    </div>

    <!-- ========================================== -->
    <!-- TAB 1: DAFTAR BERITA (CRUD LENGKAP)        -->
    <!-- ========================================== -->
    <div x-show="activeTab === 'berita'" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Form Tambah Berita Baru -->
            <div class="lg:col-span-4 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm h-fit">
                <div class="flex items-center space-x-2 pb-3 mb-4 border-b border-slate-100">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Tambah Berita Baru</h3>
                </div>

                <form action="{{ route('tvri.berita.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Judul Berita</label>
                        <input type="text" name="judul" required placeholder="Festival Budaya Pela Gandong di Ambon"
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold">
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kategori</label>
                            <select name="kategori" required class="w-full px-2.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold">
                                @foreach(BeritaController::KATEGORI as $kat)
                                    <option value="{{ $kat }}">{{ $kat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Format Penyiaran</label>
                            <select name="jenis" required class="w-full px-2.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold">
                                @foreach(BeritaController::JENIS as $j)
                                    <option value="{{ $j }}">{{ $j }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Lokasi Liputan</label>
                            <select name="lokasi" class="w-full px-2.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold">
                                @foreach(BeritaController::LOKASI as $lok)
                                    <option value="{{ $lok }}">{{ $lok }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
                            <select name="status" required class="w-full px-2.5 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                                @foreach(BeritaController::STATUS as $st)
                                    <option value="{{ $st }}">{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Reporter / Tim</label>
                            <input type="text" name="reporter" placeholder="Tim 1"
                                   class="w-full px-2.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Durasi Video</label>
                            <input type="text" name="durasi" placeholder="02:35"
                                   class="w-full px-2.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Views Digital</label>
                            <input type="number" name="views" value="0"
                                   class="w-full px-2.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tgl Rencana</label>
                            <input type="date" name="tanggal_rencana" required value="{{ date('Y-m-d') }}"
                                   class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tgl Tayang</label>
                            <input type="date" name="tanggal_tayang"
                                   class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs">
                        </div>
                    </div>

                    <!-- Kotak Upload Video (Maks 500 MB) -->
                    <div class="p-3 bg-blue-50/80 rounded-xl border border-blue-200">
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-bold text-[#00264A] flex items-center space-x-1">
                                <span>🎬 Upload Video Berita (Maks 500 MB)</span>
                            </label>
                            <span class="px-1.5 py-0.5 rounded bg-blue-200 text-blue-900 text-[9px] font-black uppercase">Maks 500 MB</span>
                        </div>
                        <input type="file" name="video_file" accept="video/mp4,video/webm,video/ogg,video/quicktime,video/x-matroska,video/avi"
                               onchange="if(this.files[0] && this.files[0].size > 524288000){ alert('Ukuran video ' + (this.files[0].size/1048576).toFixed(2) + ' MB melebihi batas maksimal 500 MB. Silakan kompres atau pilih video lain.'); this.value=''; }"
                               class="w-full text-xs text-slate-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                        <p class="text-[10px] text-blue-600 font-medium mt-1">
                            Format didukung: MP4, WebM, MOV, MKV. Resolusi/ukuran file maksimal 500 MB.
                        </p>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-black text-xs shadow-md transition-all">
                        + Tambah Berita & Upload Video
                    </button>
                </form>
            </div>

            <!-- Tabel Daftar Berita -->
            <div class="lg:col-span-8 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100 flex-wrap gap-2">
                    <div>
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">
                            Daftar Berita Terbaru & Liputan
                        </h3>
                        <p class="text-xs text-slate-500">Mempengaruhi tayangan Berita Terbaru Siap Siar, video cards, dan status grafik</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button type="button" id="btnBulkDeleteBerita" onclick="submitBulkDeleteBerita()" class="hidden px-3 py-1.5 rounded-xl bg-rose-600 text-white font-black text-xs hover:bg-rose-700 shadow-md transition-all flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <span id="btnBulkDeleteBeritaText">Hapus File/Berita Terpilih</span>
                        </button>
                    </div>
                </div>

                <!-- Bulk Delete Form for Berita (Kotak Central) -->
                <form id="formBulkDeleteBerita" action="{{ route('tvri.berita.bulk-destroy') }}" method="POST" class="hidden">
                    @csrf
                    <input type="hidden" name="tab" value="berita">
                    <div id="bulkDeleteBeritaContainer"></div>
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-400 text-[10px] uppercase font-bold">
                                <th class="py-2.5 w-8 text-center">
                                    <input type="checkbox" id="checkAllBerita" class="rounded border-slate-300 text-blue-600 w-4 h-4 cursor-pointer" title="Kotak Central: Centang Semua untuk Hapus File/Berita">
                                </th>
                                <th class="py-2.5">Judul & File Video</th>
                                <th class="py-2.5">Kategori / Format</th>
                                <th class="py-2.5">Lokasi</th>
                                <th class="py-2.5 text-center">Status</th>
                                <th class="py-2.5 text-center">Aksi & File</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($beritas as $b)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <form id="formRowBerita{{ $b->id }}" action="{{ route('tvri.berita.update', $b->id) }}" method="POST" enctype="multipart/form-data" class="hidden">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="tanggal_rencana" value="{{ $b->tanggal_rencana ? (is_string($b->tanggal_rencana) ? substr($b->tanggal_rencana, 0, 10) : $b->tanggal_rencana->format('Y-m-d')) : date('Y-m-d') }}">
                                    </form>

                                    <td class="py-2 text-center">
                                        <input type="checkbox" value="{{ $b->id }}" class="berita-checkbox rounded border-slate-300 text-blue-600 cursor-pointer">
                                    </td>
                                    <td class="py-2 max-w-[200px]">
                                        <input type="text" form="formRowBerita{{ $b->id }}" name="judul" value="{{ $b->judul }}" class="w-full px-2 py-1 rounded border border-slate-200 text-xs font-bold text-slate-800">
                                        <div class="flex items-center space-x-1.5 mt-0.5 text-[10px] text-slate-400 flex-wrap">
                                            <span>{{ $b->reporter ?? 'Tim Redaksi' }}</span>
                                            <span>&bull;</span>
                                            <input type="text" form="formRowBerita{{ $b->id }}" name="durasi" value="{{ $b->durasi ?? '02:00' }}" class="w-12 px-1 py-0.5 rounded border border-slate-200 text-[10px] text-slate-600 font-mono text-center" title="Durasi Video">
                                            <span>&bull;</span>
                                            <span>{{ number_format($b->views) }} views</span>
                                        </div>
                                        @if($b->video_file)
                                            <div class="mt-1 flex items-center space-x-1.5">
                                                <a href="{{ $b->video_url }}" onclick="openVideoPlayer('{{ $b->video_url }}', '{{ addslashes($b->judul) }}'); return false;" class="inline-flex items-center space-x-1 px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[9px] font-extrabold hover:bg-emerald-200 cursor-pointer" title="Klik untuk memutar video siaran">
                                                    <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 2h8v8H6V6z"/></svg>
                                                    <span>🎬 Video Siaran</span>
                                                </a>
                                                <button type="button" onclick="if(confirm('Hapus file video pada berita ini?')) document.getElementById('formDeleteVideo{{ $b->id }}').submit()" class="text-rose-600 hover:text-rose-800 text-[9px] font-bold underline" title="Hapus hanya file video">
                                                    Hapus File
                                                </button>
                                            </div>
                                        @else
                                            <div class="mt-1 flex items-center space-x-1">
                                                <input type="file" form="formRowBerita{{ $b->id }}" name="video_file" accept="video/*" class="text-[9px] text-slate-400 file:py-0.5 file:px-1.5 file:rounded file:border-0 file:text-[9px] file:bg-slate-100 file:text-slate-600 cursor-pointer" title="Upload Video (Maks 500 MB)">
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-2">
                                        <select form="formRowBerita{{ $b->id }}" name="kategori" class="w-full px-1.5 py-1 rounded border border-slate-200 text-[10px] font-semibold">
                                            @foreach(BeritaController::KATEGORI as $kat)
                                                <option value="{{ $kat }}" {{ $b->kategori == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                            @endforeach
                                        </select>
                                        <select form="formRowBerita{{ $b->id }}" name="jenis" class="w-full px-1.5 py-1 rounded border border-slate-200 text-[10px] text-slate-500 mt-1">
                                            @foreach(BeritaController::JENIS as $j)
                                                <option value="{{ $j }}" {{ $b->jenis == $j ? 'selected' : '' }}>{{ $j }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-2">
                                        <select form="formRowBerita{{ $b->id }}" name="lokasi" class="w-24 px-1.5 py-1 rounded border border-slate-200 text-[10px]">
                                            @foreach(BeritaController::LOKASI as $lok)
                                                <option value="{{ $lok }}" {{ $b->lokasi == $lok ? 'selected' : '' }}>{{ $lok }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-2 text-center">
                                        <select form="formRowBerita{{ $b->id }}" name="status" class="px-2 py-1 rounded border border-slate-200 text-[10px] font-bold">
                                            @foreach(BeritaController::STATUS as $st)
                                                <option value="{{ $st }}" {{ $b->status == $st ? 'selected' : '' }}>{{ $st }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-2 text-center">
                                        <div class="flex items-center justify-center space-x-1">
                                            <button type="submit" form="formRowBerita{{ $b->id }}" class="p-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="Simpan Perubahan & File">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                            <form action="{{ route('tvri.berita.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Hapus berita dan file video terkait?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors" title="Hapus Berita & File">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>

                                        @if($b->video_file)
                                            <form id="formDeleteVideo{{ $b->id }}" action="{{ route('tvri.berita.delete-video', $b->id) }}" method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-slate-400">Belum ada data berita.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100">
                    {{ $beritas->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 2: METRIK & RINGKASAN NEWSROOM         -->
    <!-- ========================================== -->
    <div x-show="activeTab === 'ringkasan'" class="space-y-6">
        <form action="{{ route('tvri.berita.ringkasan.update') }}" method="POST">
            @csrf
            <input type="hidden" name="periode" value="{{ $periode }}">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Card 1: 6 KPI Cards Utama -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
                    <div class="flex items-center space-x-2 pb-3 border-b border-slate-100">
                        <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">6 KPI Cards Utama</h3>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Rencana Liputan</label>
                            <input type="number" name="rencana_liputan" value="{{ old('rencana_liputan', $ringkasan->rencana_liputan) }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-blue-700">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Liputan Dilakukan</label>
                            <input type="number" name="liputan_dilakukan" value="{{ old('liputan_dilakukan', $ringkasan->liputan_dilakukan) }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-emerald-700">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Berita Diproduksi</label>
                            <input type="number" name="berita_diproduksi" value="{{ old('berita_diproduksi', $ringkasan->berita_diproduksi) }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-amber-700">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Berita Tayang</label>
                            <input type="number" name="berita_tayang" value="{{ old('berita_tayang', $ringkasan->berita_tayang) }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-rose-700">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Views Digital</label>
                            <input type="number" name="views_digital" value="{{ old('views_digital', $ringkasan->views_digital) }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-purple-700">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Performance Index (%)</label>
                            <input type="number" min="0" max="100" name="performance_index" value="{{ old('performance_index', $ringkasan->performance_index) }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-cyan-700">
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100">
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Kecepatan Publikasi Rata-rata (Menit)</label>
                        <input type="number" name="kecepatan_publikasi_menit" value="{{ old('kecepatan_publikasi_menit', $ringkasan->kecepatan_publikasi_menit) }}"
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                    </div>
                </div>

                <!-- Card 2: Distribusi Media & Checklist Kualitas -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
                    <div class="flex items-center space-x-2 pb-3 border-b border-slate-100">
                        <span class="w-3 h-3 rounded-full bg-cyan-500"></span>
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Distribusi Media & Kualitas</h3>
                    </div>

                    <p class="text-xs font-black text-slate-400 uppercase tracking-wider">Jumlah Tayang per Media</p>
                    <div class="grid grid-cols-5 gap-2">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 mb-1">TV</label>
                            <input type="number" name="dist_tv" value="{{ $ringkasan->dist_tv }}" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs text-center font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 mb-1">Website</label>
                            <input type="number" name="dist_website" value="{{ $ringkasan->dist_website }}" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs text-center font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 mb-1">YouTube</label>
                            <input type="number" name="dist_youtube" value="{{ $ringkasan->dist_youtube }}" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs text-center font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 mb-1">Instagram</label>
                            <input type="number" name="dist_instagram" value="{{ $ringkasan->dist_instagram }}" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs text-center font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 mb-1">TikTok</label>
                            <input type="number" name="dist_tiktok" value="{{ $ringkasan->dist_tiktok }}" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs text-center font-bold">
                        </div>
                    </div>

                    <p class="text-xs font-black text-slate-400 uppercase tracking-wider pt-2 border-t border-slate-100">Kepatuhan Kualitas Berita (%)</p>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <label class="text-[10px] text-slate-600 font-bold block">Fakta Terverifikasi</label>
                            <input type="number" min="0" max="100" name="kualitas_fakta" value="{{ $ringkasan->kualitas_fakta }}" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 font-bold">
                        </div>
                        <div>
                            <label class="text-[10px] text-slate-600 font-bold block">Kode Etik Jurnalistik</label>
                            <input type="number" min="0" max="100" name="kualitas_etik" value="{{ $ringkasan->kualitas_etik }}" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 font-bold">
                        </div>
                        <div>
                            <label class="text-[10px] text-slate-600 font-bold block">Akurasi Data</label>
                            <input type="number" min="0" max="100" name="kualitas_akurasi" value="{{ $ringkasan->kualitas_akurasi }}" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 font-bold">
                        </div>
                        <div>
                            <label class="text-[10px] text-slate-600 font-bold block">Penggunaan Visual</label>
                            <input type="number" min="0" max="100" name="kualitas_visual" value="{{ $ringkasan->kualitas_visual }}" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 font-bold">
                        </div>
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white font-black text-xs shadow-md transition-all">
                            Simpan Seluruh Metrik Newsroom
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- ========================================== -->
    <!-- TAB 3: REAL TIME NEWSROOM FEEDS            -->
    <!-- ========================================== -->
    <div x-show="activeTab === 'realtime'" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Form Tambah Feed -->
            <div class="lg:col-span-4 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm h-fit">
                <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide pb-3 mb-4 border-b border-slate-100">+ Tambah Feed Live Newsroom</h3>
                <form action="{{ route('tvri.berita.realtime.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Waktu (Contoh: 10:24)</label>
                        <input type="text" name="waktu" required placeholder="10:24" value="{{ now()->format('H:i') }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Aktivitas / Kegiatan Redaksi</label>
                        <textarea name="kegiatan" required rows="3" placeholder="Tim liputan di Ambon kirim footage - Festival Budaya" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold"></textarea>
                    </div>
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md">
                        + Tambah Feed Real Time
                    </button>
                </form>
            </div>

            <!-- Tabel Feed -->
            <div class="lg:col-span-8 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100 flex-wrap gap-2">
                    <div class="flex items-center space-x-2.5">
                        <input type="checkbox" id="checkAllRealtime" class="rounded border-slate-300 text-blue-600 w-4 h-4 cursor-pointer" title="Kotak Central: Centang Semua Feed untuk Dihapus">
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Daftar Feed Live Newsroom</h3>
                    </div>
                    <button type="button" id="btnBulkDeleteRealtime" onclick="submitBulkDeleteRealtime()" class="hidden px-3 py-1.5 rounded-xl bg-rose-600 text-white font-black text-xs hover:bg-rose-700 shadow-md transition-all flex items-center space-x-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        <span id="btnBulkDeleteRealtimeText">Hapus Feed Terpilih</span>
                    </button>
                </div>

                <!-- Bulk Delete Form for Realtime (Kotak Central) -->
                <form id="formBulkDeleteRealtime" action="{{ route('tvri.berita.realtime.bulk-destroy') }}" method="POST" class="hidden">
                    @csrf
                    <div id="bulkDeleteRealtimeContainer"></div>
                </form>

                <div class="space-y-2">
                    @forelse($realtimeFeeds as $feed)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs gap-2">
                            <input type="checkbox" value="{{ $feed->id }}" class="realtime-checkbox rounded border-slate-300 text-blue-600 w-4 h-4 cursor-pointer flex-shrink-0" title="Pilih feed ini">
                            <form action="{{ route('tvri.berita.realtime.update', $feed->id) }}" method="POST" class="flex-1 flex items-center space-x-2 mr-2">
                                @csrf
                                @method('PUT')
                                <input type="text" name="waktu" value="{{ $feed->waktu }}" class="w-16 px-2 py-1 rounded border border-slate-200 font-bold text-blue-700 text-center">
                                <input type="text" name="kegiatan" value="{{ $feed->kegiatan }}" class="flex-1 px-2 py-1 rounded border border-slate-200 font-semibold">
                                <button type="submit" class="p-1 rounded bg-blue-100 text-blue-700 hover:bg-blue-200" title="Simpan">✓</button>
                            </form>
                            <form action="{{ route('tvri.berita.realtime.destroy', $feed->id) }}" method="POST" onsubmit="return confirm('Hapus feed ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 rounded bg-rose-100 text-rose-700 hover:bg-rose-200" title="Hapus">✕</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-4">Belum ada feed live.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 4: AGENDA LIPUTAN (CRUD JADWAL BERITA) -->
    <!-- ========================================== -->
    <div x-show="activeTab === 'agenda'" class="space-y-6">

        <!-- KPI Ringkasan Peliputan -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase text-slate-400">Total Liputan</p>
                    <p class="text-2xl font-black text-[#00264A] mt-0.5">{{ $totalLiputan }}</p>
                    <span class="text-[10px] font-semibold text-blue-600">Minggu 9 - 14 Nov 2026</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">📋</div>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase text-slate-400">Siaran Langsung</p>
                    <p class="text-2xl font-black text-rose-600 mt-0.5">{{ $siaranLangsung }}</p>
                    <span class="text-[10px] font-semibold text-rose-500">Live Studio &amp; SNG</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-lg">📡</div>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase text-slate-400">Penting / Mendesak</p>
                    <p class="text-2xl font-black text-amber-600 mt-0.5">{{ $prioritasPenting }}</p>
                    <span class="text-[10px] font-semibold text-amber-600">Prioritas Redaksi</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg">⚡</div>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase text-slate-400">Crew Bertugas</p>
                    <p class="text-2xl font-black text-emerald-600 mt-0.5">{{ $crewBertugas }}</p>
                    <span class="text-[10px] font-semibold text-emerald-600">Kru Lapangan</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">👥</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Form Tambah Agenda Liputan Baru -->
            <div class="lg:col-span-5 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm h-fit">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-2">
                        <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Tambah Agenda Liputan</h3>
                    </div>
                    <span class="text-[10px] font-mono text-slate-400">Auto Kode LP</span>
                </div>

                <form action="{{ route('tvri.berita.agenda.store') }}" method="POST" class="space-y-3">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Agenda / Topik Liputan</label>
                        <textarea name="agenda" required rows="2" placeholder="Contoh: Rapat Paripurna DPRD Provinsi Maluku: pembahasan KUA-PPAS APBD 2027"
                                  class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:border-blue-500"></textarea>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal</label>
                            <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}"
                                   class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Jam Mulai</label>
                            <input type="text" name="mulai" placeholder="08:00" value="08:00"
                                   class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-mono font-bold text-center">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Jam Selesai</label>
                            <input type="text" name="selesai" placeholder="10:00" value="10:00"
                                   class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-mono font-bold text-center">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Liputan</label>
                            <select name="jenis" required class="w-full px-2.5 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                                @foreach(['Berita Harian', 'Ekonomi', 'Budaya', 'Live', 'Feature', 'Sosial', 'Investigasi', 'Olahraga', 'Lainnya'] as $j)
                                    <option value="{{ $j }}">{{ $j }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Bidang</label>
                            <select name="kategori" class="w-full px-2.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold">
                                @foreach(BeritaController::KATEGORI as $kat)
                                    <option value="{{ $kat }}">{{ $kat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Prioritas</label>
                            <select name="prioritas" class="w-full px-2.5 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                                <option value="Biasa">Biasa</option>
                                <option value="Penting" selected>Penting</option>
                                <option value="Mendesak">Mendesak</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
                            <select name="status" class="w-full px-2.5 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                                <option value="Terjadwal" selected>Terjadwal</option>
                                <option value="Proses">Proses</option>
                                <option value="On Air">On Air</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Lokasi Liputan</label>
                            <input type="text" name="lokasi" required placeholder="Gedung DPRD, Karang Panjang"
                                   class="w-full px-2.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Link Google Maps (Opsional)</label>
                            <input type="text" name="link" placeholder="https://maps.google.com/..."
                                   class="w-full px-2.5 py-2 rounded-xl border border-slate-200 text-xs">
                        </div>
                    </div>

                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-200/80 space-y-2">
                        <p class="text-[10px] font-black uppercase text-slate-500 tracking-wider">Penugasan Crew &amp; Armada Lapangan</p>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-0.5">Reporter</label>
                                <input type="text" name="reporter" list="listReporter" placeholder="Sarles Bolaman"
                                       class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold">
                                <datalist id="listReporter">
                                    <option value="Sarles Bolaman">
                                    <option value="Yaser Tuarita">
                                    <option value="Christine Sipahelut">
                                    <option value="Novita Bonara">
                                    <option value="Monica Sipahelut">
                                    <option value="Devy Bin Umar">
                                    <option value="Yohanis Limba">
                                    <option value="Shinta">
                                    <option value="Peggy Nahumury">
                                </datalist>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-0.5">Kamerawan</label>
                                <input type="text" name="kamerawan" list="listKamerawan" placeholder="Minggus Noya"
                                       class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold">
                                <datalist id="listKamerawan">
                                    <option value="Minggus Noya">
                                    <option value="Junaedi Asril">
                                    <option value="Fernanda Rasyid">
                                    <option value="Andre Uktolseja">
                                    <option value="Kurniawan Suwarno">
                                </datalist>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-0.5">Driver</label>
                                <input type="text" name="driver" list="listDriver" placeholder="Idrus"
                                       class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold">
                                <datalist id="listDriver">
                                    <option value="Idrus">
                                    <option value="Max Appono">
                                    <option value="Della">
                                    <option value="-">
                                </datalist>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-0.5">Kendaraan</label>
                                <input type="text" name="kendaraan" list="listKendaraan" placeholder="DE 1201 AB (Innova)"
                                       class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-mono font-bold">
                                <datalist id="listKendaraan">
                                    <option value="DE 1201 AB (Innova)">
                                    <option value="DE 1305 AB (SNG Live)">
                                    <option value="DE 1422 AB (Avanza)">
                                    <option value="DE 5310 AC (Motor)">
                                    <option value="-">
                                </datalist>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Redaksi / EIC</label>
                        <input type="text" name="catatan" placeholder="Contoh: Ambil soundbite Ketua DPRD dan perwakilan fraksi."
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium">
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-[#003B70] hover:from-blue-700 hover:to-[#00264A] text-white font-black text-xs shadow-md transition-all">
                        + Simpan Agenda Liputan &amp; Sinkronkan
                    </button>
                </form>
            </div>

            <!-- Tabel & Filter Agenda Liputan -->
            <div class="lg:col-span-7 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100 flex-wrap gap-2">
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Daftar Jadwal Peliputan Berita</h3>
                                <span class="px-2 py-0.5 rounded-full bg-blue-50 text-[#005B9F] border border-blue-200 text-[10px] font-extrabold font-mono">Live Real-Time</span>
                            </div>
                            <p class="text-[11px] text-slate-500 font-medium mt-1 flex items-center space-x-1.5 flex-wrap">
                                <span class="text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 font-mono text-[10px] font-bold" id="agendaManageRealtimeClock">
                                    {{ now()->isoFormat('dddd, D MMMM Y') }} &bull; {{ now()->format('H:i:s') }} WIT
                                </span>
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="openJadwalVercelModal()" 
                                    class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-blue-50 text-[#005B9F] hover:bg-blue-100 border border-blue-200 transition-colors cursor-pointer shadow-xs">
                                <span class="w-2 h-2 rounded-full bg-[#005B9F] animate-pulse"></span>
                                <span>Portal Jadwal Liputan</span>
                            </button>
                            <button type="button" id="btnBulkDeleteAgenda" onclick="submitBulkDeleteAgenda()" class="hidden px-3 py-1.5 rounded-xl bg-rose-600 text-white font-black text-xs hover:bg-rose-700 shadow-md transition-all flex items-center space-x-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                <span id="btnBulkDeleteAgendaText">Hapus Terpilih</span>
                            </button>
                        </div>
                    </div>

                    <!-- Filter Bar & Search (Real-Time Berdasarkan Hari, Bulan & Tahun) -->
                    <div class="space-y-2 mb-3">
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2">
                            <div class="sm:col-span-4">
                                <input type="text" x-model="agendaSearch" placeholder="Cari liputan / lokasi..."
                                       class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-medium">
                            </div>
                            <div class="sm:col-span-3">
                                <div class="relative flex items-center">
                                    <input type="date" x-model="agendaFilterHari" title="Filter Real-Time Berdasarkan Hari, Bulan &amp; Tahun"
                                           class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 bg-white">
                                    <button type="button" x-show="agendaFilterHari" 
                                            @click="agendaFilterHari = ''" 
                                            class="absolute right-2 text-slate-400 hover:text-slate-600 text-xs font-bold" title="Tampilkan Semua Tanggal">✕</button>
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                @php
                                    $manageCrewOptions = collect(['Sarles Bolaman', 'Yaser Tuarita', 'Christine Sipahelut', 'Novita Bonara', 'Monica Sipahelut', 'Devy Bin Umar', 'Yohanis Limba', 'Shinta', 'Peggy Nahumury', 'Minggus Noya', 'Junaedi Asril', 'Fernanda Rasyid', 'Andre Uktolseja', 'Kurniawan Suwarno', 'Idrus', 'Max Appono', 'Della']);
                                    foreach($agendaLiputan as $a) {
                                        if(!empty($a->reporter)) $manageCrewOptions->push($a->reporter);
                                        if(!empty($a->kamerawan)) $manageCrewOptions->push($a->kamerawan);
                                        if(!empty($a->driver) && $a->driver !== '-') $manageCrewOptions->push($a->driver);
                                    }
                                    $manageCrewOptions = $manageCrewOptions->filter()->unique()->values();
                                @endphp
                                <select x-model="agendaFilterCrew" class="w-full px-2 py-1.5 rounded-xl border border-slate-200 text-xs">
                                    <option value="">Semua Crew</option>
                                    @foreach($manageCrewOptions as $c)
                                        <option value="{{ $c }}">{{ $c }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <select x-model="agendaFilterJenis" class="w-full px-2 py-1.5 rounded-xl border border-slate-200 text-xs">
                                    <option value="">Semua Jenis</option>
                                    @foreach(['Berita Harian', 'Ekonomi', 'Budaya', 'Live', 'Feature', 'Sosial', 'Investigasi', 'Olahraga', 'Lainnya'] as $j)
                                        <option value="{{ $j }}">{{ $j }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Bulk Delete Form for Agenda (Kotak Central) -->
                    <form id="formBulkDeleteAgenda" action="{{ route('tvri.berita.agenda.bulk-destroy') }}" method="POST" class="hidden">
                        @csrf
                        <div id="bulkDeleteAgendaContainer"></div>
                    </form>

                    <!-- Daftar Agenda Liputan Lengkap -->
                    <div class="space-y-2.5 max-h-[580px] overflow-y-auto pr-1">
                        @forelse($agendaLiputan as $ag)
                            @php
                                $agTglStr = $ag->tanggal ? (is_string($ag->tanggal) ? substr($ag->tanggal, 0, 10) : $ag->tanggal->format('Y-m-d')) : '';
                                $agCrewAll = strtolower($ag->reporter . ' ' . $ag->kamerawan . ' ' . $ag->driver . ' ' . $ag->pic);
                                $agJenisVal = $ag->jenis ?: 'Berita Harian';
                                $agPrioClass = match($ag->prioritas) {
                                    'Mendesak' => 'bg-rose-100 text-rose-700 border-rose-200',
                                    'Penting' => 'bg-amber-100 text-amber-800 border-amber-200',
                                    default => 'bg-slate-100 text-slate-700 border-slate-200',
                                };
                                $agStatusClass = match($ag->status) {
                                    'Selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                    'On Air' => 'bg-rose-100 text-rose-800 border-rose-300 animate-pulse',
                                    'Proses' => 'bg-blue-100 text-blue-800 border-blue-300',
                                    default => 'bg-slate-100 text-slate-700 border-slate-200',
                                };
                            @endphp
                            <div x-show="(!agendaFilterHari || agendaFilterHari === 'semua' || agendaFilterHari === '{{ $agTglStr }}') && 
                                         (!agendaFilterCrew || '{{ $agCrewAll }}'.includes(agendaFilterCrew.toLowerCase())) &&
                                         (!agendaFilterJenis || '{{ $agJenisVal }}' === agendaFilterJenis) &&
                                         (!agendaSearch || '{{ strtolower($ag->agenda . ' ' . $ag->lokasi . ' ' . $ag->kode . ' ' . $agCrewAll) }}'.includes(agendaSearch.toLowerCase()))"
                                 class="p-3 rounded-xl border border-slate-200 hover:border-blue-300 hover:shadow-sm transition-all bg-slate-50/40 flex flex-col justify-between gap-2">
                                
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex items-start space-x-2.5">
                                        <input type="checkbox" value="{{ $ag->id }}" class="agenda-checkbox mt-1 rounded border-slate-300 text-blue-600 cursor-pointer">
                                        <div>
                                            <div class="flex items-center space-x-1.5 flex-wrap gap-y-1">
                                                <span class="font-mono text-[10px] font-bold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200">
                                                    {{ $ag->kode ?: 'LP-' . $ag->id }}
                                                </span>
                                                <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">
                                                    {{ $ag->waktu }} @if($ag->durasi_label) &bull; {{ $ag->durasi_label }} @endif
                                                </span>
                                                <span class="text-[10px] font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200">
                                                    {{ $ag->jenis ?: 'Berita Harian' }}
                                                </span>
                                                @if($ag->prioritas && $ag->prioritas !== 'Biasa')
                                                    <span class="text-[10px] font-extrabold px-2 py-0.5 rounded border {{ $agPrioClass }}">
                                                        {{ $ag->prioritas }}
                                                    </span>
                                                @endif
                                                <span class="text-[10px] font-extrabold px-2 py-0.5 rounded border {{ $agStatusClass }}">
                                                    {{ $ag->status }}
                                                </span>
                                            </div>

                                            <h4 class="text-xs font-bold text-slate-900 mt-1.5 leading-snug">
                                                {{ $ag->agenda }}
                                            </h4>

                                            <p class="text-[11px] text-slate-500 mt-0.5 flex items-center space-x-1">
                                                <svg class="w-3 h-3 text-rose-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                                @if($ag->link && $ag->link !== '-')
                                                    <a href="{{ $ag->link }}" target="_blank" rel="noopener noreferrer" class="hover:underline text-blue-600 font-medium">
                                                        {{ $ag->lokasi }}
                                                    </a>
                                                @else
                                                    <span>{{ $ag->lokasi }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Tombol Aksi -->
                                    <div class="flex items-center space-x-1 flex-shrink-0">
                                        <button type="button" 
                                                @click="openEditAgenda({{ json_encode($ag) }})" 
                                                class="px-2 py-1 rounded bg-blue-50 text-blue-700 hover:bg-blue-100 text-[10px] font-bold border border-blue-200 transition" 
                                                title="Edit Agenda">
                                            Edit
                                        </button>
                                        <form action="{{ route('tvri.berita.agenda.destroy', $ag->id) }}" method="POST" onsubmit="return confirm('Hapus agenda liputan [{{ $ag->kode }}] ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 rounded bg-rose-50 text-rose-700 hover:bg-rose-100 text-[10px] font-bold border border-rose-200 transition" title="Hapus Agenda">
                                                ✕
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Detail Kru & Catatan EIC -->
                                <div class="pt-2 border-t border-slate-200/60 flex flex-col sm:flex-row sm:items-center justify-between text-[10px] text-slate-500 gap-1.5">
                                    <div class="flex items-center space-x-1.5 flex-wrap gap-1">
                                        @if($ag->reporter)
                                            <span class="bg-white px-1.5 py-0.5 rounded border border-slate-200 font-bold text-slate-700">
                                                🎤 {{ $ag->reporter }}
                                            </span>
                                        @endif
                                        @if($ag->kamerawan)
                                            <span class="bg-white px-1.5 py-0.5 rounded border border-slate-200 font-bold text-slate-700">
                                                📹 {{ $ag->kamerawan }}
                                            </span>
                                        @endif
                                        @if($ag->driver && $ag->driver !== '-')
                                            <span class="bg-white px-1.5 py-0.5 rounded border border-slate-200 font-semibold text-slate-600">
                                                🚗 {{ $ag->driver }}
                                            </span>
                                        @endif
                                        @if($ag->kendaraan && $ag->kendaraan !== '-')
                                            <span class="bg-cyan-50 text-cyan-800 font-mono px-1.5 py-0.5 rounded border border-cyan-200 font-bold">
                                                {{ $ag->kendaraan }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($ag->catatan)
                                        <p class="text-[10px] text-amber-900 bg-amber-50/80 px-2 py-0.5 rounded border border-amber-200/80 italic line-clamp-1">
                                            Catatan EIC: {{ $ag->catatan }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 py-6 text-center">Belum ada agenda liputan terdaftar.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== MODAL EDIT AGENDA LIPUTAN ===== -->
    <div x-show="editAgendaModal" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-5 bg-slate-900/70 backdrop-blur-sm"
         @keydown.escape.window="editAgendaModal = false">
        <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200 p-6 animate-in fade-in zoom-in-95 duration-150 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">
                        Edit Agenda Liputan: <span x-text="editAgendaData.kode"></span>
                    </h3>
                </div>
                <button type="button" @click="editAgendaModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg">✕</button>
            </div>

            <form :action="'{{ url('/tvri/berita/agenda') }}/' + editAgendaData.id" method="POST" class="space-y-3">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Agenda / Topik Liputan</label>
                    <textarea name="agenda" x-model="editAgendaData.agenda" required rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold"></textarea>
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" x-model="editAgendaData.tanggal ? (editAgendaData.tanggal.substring ? editAgendaData.tanggal.substring(0, 10) : editAgendaData.tanggal) : ''" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Waktu Rentang</label>
                        <input type="text" name="waktu" x-model="editAgendaData.waktu" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-mono font-bold text-center">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Durasi</label>
                        <input type="text" name="durasi_label" x-model="editAgendaData.durasi_label" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs text-center font-bold">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Liputan</label>
                        <select name="jenis" x-model="editAgendaData.jenis" class="w-full px-2.5 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                            @foreach(['Berita Harian', 'Ekonomi', 'Budaya', 'Live', 'Feature', 'Sosial', 'Investigasi', 'Olahraga', 'Lainnya'] as $j)
                                <option value="{{ $j }}">{{ $j }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Prioritas</label>
                        <select name="prioritas" x-model="editAgendaData.prioritas" class="w-full px-2.5 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                            <option value="Biasa">Biasa</option>
                            <option value="Penting">Penting</option>
                            <option value="Mendesak">Mendesak</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Lokasi Liputan</label>
                        <input type="text" name="lokasi" x-model="editAgendaData.lokasi" required class="w-full px-2.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
                        <select name="status" x-model="editAgendaData.status" class="w-full px-2.5 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                            <option value="Terjadwal">Terjadwal</option>
                            <option value="Proses">Proses</option>
                            <option value="On Air">On Air</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Reporter</label>
                        <input type="text" name="reporter" x-model="editAgendaData.reporter" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Kamerawan</label>
                        <input type="text" name="kamerawan" x-model="editAgendaData.kamerawan" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Driver</label>
                        <input type="text" name="driver" list="listDriver" x-model="editAgendaData.driver" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Kendaraan</label>
                        <input type="text" name="kendaraan" list="listKendaraan" x-model="editAgendaData.kendaraan" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-mono font-bold">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Redaksi / EIC</label>
                    <textarea name="catatan" x-model="editAgendaData.catatan" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs"></textarea>
                </div>

                <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="editAgendaModal = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold text-xs hover:bg-slate-200 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-black text-xs shadow-md transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 5: PERINGATAN & CATATAN REDAKSI        -->
    <!-- ========================================== -->
    <div x-show="activeTab === 'catatan'" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Form Tambah Catatan -->
            <div class="lg:col-span-4 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm h-fit">
                <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide pb-3 mb-4 border-b border-slate-100">+ Tambah Peringatan / Catatan</h3>
                <form action="{{ route('tvri.berita.catatan.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tipe Catatan</label>
                        <select name="tipe" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                            <option value="warning">⚠️ Peringatan (Warning)</option>
                            <option value="info">ℹ️ Informasi (Info)</option>
                            <option value="success">✅ Kepatuhan (Success)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Pesan / Catatan Redaksi</label>
                        <textarea name="pesan" required rows="3" placeholder="Footage dari SBT belum masuk" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold"></textarea>
                    </div>
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md">
                        + Tambah Catatan
                    </button>
                </form>
            </div>

            <!-- Tabel Catatan -->
            <div class="lg:col-span-8 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100 flex-wrap gap-2">
                    <div class="flex items-center space-x-2.5">
                        <input type="checkbox" id="checkAllCatatan" class="rounded border-slate-300 text-blue-600 w-4 h-4 cursor-pointer" title="Kotak Central: Centang Semua Catatan untuk Dihapus">
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Daftar Peringatan & Catatan Redaksi</h3>
                    </div>
                    <button type="button" id="btnBulkDeleteCatatan" onclick="submitBulkDeleteCatatan()" class="hidden px-3 py-1.5 rounded-xl bg-rose-600 text-white font-black text-xs hover:bg-rose-700 shadow-md transition-all flex items-center space-x-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        <span id="btnBulkDeleteCatatanText">Hapus Catatan Terpilih</span>
                    </button>
                </div>

                <!-- Bulk Delete Form for Catatan (Kotak Central) -->
                <form id="formBulkDeleteCatatan" action="{{ route('tvri.berita.catatan.bulk-destroy') }}" method="POST" class="hidden">
                    @csrf
                    <div id="bulkDeleteCatatanContainer"></div>
                </form>

                <div class="space-y-2">
                    @forelse($catatans as $c)
                        <div class="flex items-center justify-between p-3 rounded-xl border text-xs gap-2 {{ $c->tipe === 'warning' ? 'bg-amber-50 border-amber-200 text-amber-900' : ($c->tipe === 'info' ? 'bg-blue-50 border-blue-200 text-blue-900' : 'bg-emerald-50 border-emerald-200 text-emerald-900') }}">
                            <input type="checkbox" value="{{ $c->id }}" class="catatan-checkbox rounded border-slate-300 text-blue-600 w-4 h-4 cursor-pointer flex-shrink-0" title="Pilih catatan ini">
                            <form action="{{ route('tvri.berita.catatan.update', $c->id) }}" method="POST" class="flex-1 flex items-center space-x-2 mr-2">
                                @csrf
                                @method('PUT')
                                <select name="tipe" class="px-2 py-1 rounded border border-slate-200 text-[10px] font-bold">
                                    <option value="warning" {{ $c->tipe == 'warning' ? 'selected' : '' }}>⚠️ Warning</option>
                                    <option value="info" {{ $c->tipe == 'info' ? 'selected' : '' }}>ℹ️ Info</option>
                                    <option value="success" {{ $c->tipe == 'success' ? 'selected' : '' }}>✅ Success</option>
                                </select>
                                <input type="text" name="pesan" value="{{ $c->pesan }}" class="flex-1 px-2 py-1 rounded border border-slate-200 font-semibold text-slate-800">
                                <button type="submit" class="p-1 rounded bg-white shadow-sm" title="Simpan">✓</button>
                            </form>
                            <form action="{{ route('tvri.berita.catatan.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Hapus catatan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 rounded bg-rose-100 text-rose-700" title="Hapus">✕</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-4">Belum ada catatan redaksi.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 6: DOKUMEN & SOP BERITA (CRUD)         -->
    <!-- ========================================== -->
    <div x-show="activeTab === 'sop'" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Form Tambah SOP Berita (4 Kolom) -->
            <div class="lg:col-span-4 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm h-fit">
                <div class="flex items-center space-x-2 pb-3 mb-4 border-b border-slate-100">
                    <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Tambah SOP Berita</h3>
                </div>

                <form action="{{ route('tvri.berita.sop.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5">
                    @csrf
                    <input type="hidden" name="periode" value="{{ $periode }}">

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kode SOP</label>
                        <input type="text" name="kode" required placeholder="SOP-BRT-07"
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-mono font-bold text-teal-700 focus:ring-2 focus:ring-teal-500">
                        <p class="text-[10px] text-slate-400 mt-0.5">Contoh: SOP-BRT-01, SOP-BRT-02</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Judul SOP / Prosedur</label>
                        <input type="text" name="judul" required placeholder="SOP Liputan Investigasi & Cek Fakta..."
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-teal-500">
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kategori</label>
                            <select name="kategori" required class="w-full px-2.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold">
                                <option value="Peliputan">Peliputan</option>
                                <option value="Produksi">Produksi</option>
                                <option value="Penyiaran">Penyiaran</option>
                                <option value="Penyiaran Khusus">Penyiaran Khusus</option>
                                <option value="Distribusi Digital">Distribusi Digital</option>
                                <option value="Kode Etik & Regulasi">Kode Etik & Regulasi</option>
                                <option value="Manajemen Redaksi">Manajemen Redaksi</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Versi Dokumen</label>
                            <input type="text" name="versi" value="v1.0" placeholder="v1.0"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Status SOP</label>
                            <select name="status" required class="w-full px-2.5 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                                <option value="Aktif">Aktif</option>
                                <option value="Revisi">Revisi</option>
                                <option value="Nonaktif">Nonaktif</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Urutan Tampil</label>
                            <input type="number" name="urutan" value="{{ count($sops) + 1 }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Uraian / Ringkasan Prosedur</label>
                        <textarea name="keterangan" rows="3" placeholder="Uraian ringkas langkah kerja dan dasar hukum SOP..."
                                  class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Unggah Fisik SOP (PDF / Dokumen / Gambar)</label>
                        <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png"
                               class="w-full px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-medium text-slate-700 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 cursor-pointer">
                        <p class="text-[10px] text-slate-400 mt-0.5">Format: PDF, Word, Excel, JPG, PNG (Maks 20MB)</p>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-black text-xs shadow-md transition-all">
                        Simpan SOP Berita
                    </button>
                </form>
            </div>

            <!-- Tabel Daftar SOP Berita (8 Kolom) -->
            <div class="lg:col-span-8 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100 flex-wrap gap-2">
                    <div>
                        <h3 class="text-sm font-bold text-[#00264A] uppercase tracking-wide">Daftar Dokumen & SOP Newsroom</h3>
                        <p class="text-xs text-slate-500">Standar operasional liputan, produksi, penyiaran, kode etik jurnalistik, dan distribusi digital TVRI Maluku</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-teal-50 text-teal-700 border border-teal-200">
                            {{ count($sops) }} Dokumen SOP
                        </span>
                        <button type="button" id="btnBulkDeleteSop" onclick="submitBulkDeleteSop()" class="hidden px-2.5 py-1 rounded-xl bg-rose-600 text-white font-bold text-[10px] hover:bg-rose-700 shadow-sm transition-all">
                            Hapus Terpilih
                        </button>
                    </div>
                </div>

                <form id="formBulkDeleteSop" action="{{ route('tvri.berita.sop.bulk-destroy') }}" method="POST" class="hidden">
                    @csrf
                    <input type="hidden" name="periode" value="{{ $periode }}">
                    <div id="bulkDeleteSopContainer"></div>
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-400 text-[10px] uppercase font-bold">
                                <th class="py-2.5 w-8 text-center">
                                    <input type="checkbox" id="checkAllSop" class="rounded border-slate-300 text-teal-600 cursor-pointer" title="Centang Semua SOP">
                                </th>
                                <th class="py-2.5 w-12 text-center">No</th>
                                <th class="py-2.5 w-24">Kode</th>
                                <th class="py-2.5">Judul SOP & Keterangan</th>
                                <th class="py-2.5 w-28">Kategori</th>
                                <th class="py-2.5 w-16 text-center">Versi</th>
                                <th class="py-2.5 w-20 text-center">Status</th>
                                <th class="py-2.5 w-20 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($sops as $sop)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <form id="formRowSop{{ $sop->id }}" action="{{ route('tvri.berita.sop.update', $sop->id) }}" method="POST" enctype="multipart/form-data" class="hidden">
                                        @csrf
                                        <input type="hidden" name="periode" value="{{ $periode }}">
                                    </form>

                                    <td class="py-2.5 text-center">
                                        <input type="checkbox" value="{{ $sop->id }}" class="sop-checkbox rounded border-slate-300 text-teal-600 cursor-pointer">
                                    </td>
                                    <td class="py-2.5 text-center">
                                        <input type="number" form="formRowSop{{ $sop->id }}" name="urutan" value="{{ $sop->urutan }}" class="w-10 px-1 py-1 rounded border border-slate-200 text-xs font-bold text-center">
                                    </td>
                                    <td class="py-2.5">
                                        <input type="text" form="formRowSop{{ $sop->id }}" name="kode" value="{{ $sop->kode }}" class="w-24 px-2 py-1 rounded border border-slate-200 text-xs font-mono font-bold text-teal-700">
                                    </td>
                                    <td class="py-2.5">
                                        <input type="text" form="formRowSop{{ $sop->id }}" name="judul" value="{{ $sop->judul }}" class="w-full px-2 py-1 rounded border border-slate-200 text-xs font-semibold text-slate-800">
                                        <input type="text" form="formRowSop{{ $sop->id }}" name="keterangan" value="{{ $sop->keterangan }}" placeholder="Keterangan SOP..." class="w-full px-2 py-0.5 mt-1 rounded border border-slate-100 text-[10px] text-slate-500">
                                    </td>
                                    <td class="py-2.5">
                                        <select form="formRowSop{{ $sop->id }}" name="kategori" class="w-full px-1.5 py-1 rounded border border-slate-200 text-[10px] font-semibold">
                                            @foreach(['Peliputan', 'Produksi', 'Penyiaran', 'Penyiaran Khusus', 'Distribusi Digital', 'Kode Etik & Regulasi', 'Manajemen Redaksi'] as $kat)
                                                <option value="{{ $kat }}" {{ $sop->kategori == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-2.5 text-center">
                                        <input type="text" form="formRowSop{{ $sop->id }}" name="versi" value="{{ $sop->versi }}" class="w-12 px-1 py-0.5 rounded border border-slate-200 text-[9px] inline">
                                    </td>
                                    <td class="py-2.5 text-center">
                                        <select form="formRowSop{{ $sop->id }}" name="status" class="px-1.5 py-0.5 rounded border border-slate-200 text-[9px] font-bold {{ $sop->status == 'Aktif' ? 'text-emerald-700' : ($sop->status == 'Revisi' ? 'text-amber-700' : 'text-slate-500') }}">
                                            <option value="Aktif" {{ $sop->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="Revisi" {{ $sop->status == 'Revisi' ? 'selected' : '' }}>Revisi</option>
                                            <option value="Nonaktif" {{ $sop->status == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                        </select>
                                    </td>
                                    <td class="py-2.5 text-center">
                                        <div class="flex items-center justify-center space-x-1">
                                            @if($sop->file_url || $sop->file_path)
                                                <a href="{{ asset($sop->file_url ?: $sop->file_path) }}" target="_blank" class="p-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors inline-flex items-center space-x-1" title="Lihat Fisik SOP">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                </a>
                                            @endif
                                            <button type="submit" form="formRowSop{{ $sop->id }}" class="p-1.5 rounded-lg bg-teal-50 text-teal-700 hover:bg-teal-100 transition-colors" title="Simpan Perubahan">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                            <form action="{{ route('tvri.berita.sop.destroy', $sop->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SOP {{ $sop->kode }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="periode" value="{{ $periode }}">
                                                <button type="submit" class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors" title="Hapus SOP">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-8 text-center text-slate-400">Belum ada Dokumen SOP Berita.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 7: PRODUKSI PAKET (CRUD LENGKAP)       -->
    <!-- ========================================== -->
    <div x-show="activeTab === 'produksi'" class="space-y-6"
         x-data="{ produksiSearch: '', filterStatusProduksi: '', filterProgramProduksi: '' }">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Form Tambah Paket Berita Baru -->
            <div class="lg:col-span-5 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm h-fit">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-2">
                        <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                            📦
                        </div>
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Tambah Paket Produksi</h3>
                    </div>
                    <span class="text-[10px] font-mono text-slate-400">Auto Kode PKT</span>
                </div>

                <form action="{{ route('tvri.berita.produksi.store') }}" method="POST" class="space-y-3">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Judul / Topik Paket Berita</label>
                        <textarea name="judul" required rows="2" placeholder="Contoh: Paket Khusus Upacara & Tabur Bunga Hari Pahlawan di Teluk Ambon"
                                  class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:border-amber-500"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Program Acara</label>
                            <input type="text" name="program" list="listProgramProduksi" value="Maluku Hari Ini Pagi" placeholder="Contoh: Maluku Hari Ini Pagi" required
                                   class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-amber-500 focus:outline-none">
                            <datalist id="listProgramProduksi">
                                @foreach(['Maluku Hari Ini Pagi', 'Maluku Hari Ini Sore', 'Berita Daerah', 'Panorama Maluku', 'Pesona Budaya Maluku', 'Warta Nusantara', 'Lainnya'] as $prog)
                                    <option value="{{ $prog }}">{{ $prog }}</option>
                                @endforeach
                            </datalist>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kategori</label>
                            <input type="text" name="kategori" list="listKategoriProduksi" value="Pemerintahan" placeholder="Contoh: Pemerintahan"
                                   class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-amber-500 focus:outline-none">
                            <datalist id="listKategoriProduksi">
                                @foreach(BeritaController::KATEGORI as $kat)
                                    <option value="{{ $kat }}">{{ $kat }}</option>
                                @endforeach
                            </datalist>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-0.5">Reporter</label>
                            <input type="text" name="reporter" placeholder="Monica Sipahelut"
                                   class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-0.5">Kamerawan</label>
                            <input type="text" name="kamerawan" placeholder="Kurniawan Suwarno"
                                   class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-0.5">Editor</label>
                            <input type="text" name="editor" placeholder="Hendrik Latuputty"
                                   class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Durasi</label>
                            <input type="text" name="durasi" placeholder="03:45" value="02:30"
                                   class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-mono font-bold text-center">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tgl Produksi</label>
                            <input type="date" name="tanggal_produksi" value="{{ date('Y-m-d') }}"
                                   class="w-full px-2 py-1.5 rounded-xl border border-slate-200 text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Target Tayang</label>
                            <input type="date" name="target_tayang" value="{{ date('Y-m-d') }}"
                                   class="w-full px-2 py-1.5 rounded-xl border border-slate-200 text-xs font-bold">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Status Produksi</label>
                            <select name="status" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold">
                                <option value="Editing">Editing</option>
                                <option value="Review EIC">Review EIC</option>
                                <option value="Siap Tayang">Siap Tayang</option>
                                <option value="On Air">On Air</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Prioritas</label>
                            <select name="prioritas" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold">
                                <option value="Biasa">Biasa</option>
                                <option value="Penting">Penting</option>
                                <option value="Mendesak">Mendesak</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">URL Media Video (Opsional)</label>
                        <input type="url" name="video_url" placeholder="https://..."
                               class="w-full px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Redaksi / Produksi</label>
                        <input type="text" name="catatan" placeholder="Contoh: Gabungkan visual drone Teluk Ambon."
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium">
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 text-white font-black text-xs shadow-md transition-all">
                        + Simpan Paket Produksi
                    </button>
                </form>
            </div>

            <!-- Tabel & Filter Produksi Paket Berita -->
            <div class="lg:col-span-7 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100 flex-wrap gap-2">
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                                <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Daftar Paket Berita TV</h3>
                                <span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-[10px] font-extrabold font-mono">{{ count($produksis) }} Paket</span>
                            </div>
                            <p class="text-[11px] text-slate-500 font-medium mt-1">
                                Pantauan alur pra-produksi, editing, review redaksi, hingga siap tayang.
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="button" id="btnBulkDeleteProduksi" onclick="submitBulkDeleteProduksi()" class="hidden px-3 py-1.5 rounded-xl bg-rose-600 text-white font-black text-xs hover:bg-rose-700 shadow-md transition-all flex items-center space-x-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                <span id="btnBulkDeleteProduksiText">Hapus Terpilih</span>
                            </button>
                        </div>
                    </div>

                    <!-- Filter Bar & Search -->
                    <div class="space-y-2 mb-3">
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2">
                            <div class="sm:col-span-6">
                                <input type="text" x-model="produksiSearch" placeholder="Cari judul / kru / kode..."
                                       class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-medium">
                            </div>
                            <div class="sm:col-span-3">
                                <select x-model="filterStatusProduksi" class="w-full px-2 py-1.5 rounded-xl border border-slate-200 text-xs">
                                    <option value="">Semua Status</option>
                                    <option value="Editing">Editing</option>
                                    <option value="Review EIC">Review EIC</option>
                                    <option value="Siap Tayang">Siap Tayang</option>
                                    <option value="On Air">On Air</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                            </div>
                            <div class="sm:col-span-3">
                                <select x-model="filterProgramProduksi" class="w-full px-2 py-1.5 rounded-xl border border-slate-200 text-xs">
                                    <option value="">Semua Program</option>
                                    <option value="Maluku Hari Ini">Maluku Hari Ini</option>
                                    <option value="Berita Daerah">Berita Daerah</option>
                                    <option value="Panorama Maluku">Panorama Maluku</option>
                                    <option value="Pesona Budaya Maluku">Pesona Budaya</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Bulk Delete Form -->
                    <form id="formBulkDeleteProduksi" action="{{ route('tvri.berita.produksi.bulk-destroy') }}" method="POST" class="hidden">
                        @csrf
                        <div id="bulkDeleteProduksiContainer"></div>
                    </form>

                    <!-- Daftar Card Paket -->
                    <div class="space-y-2.5 max-h-[580px] overflow-y-auto pr-1">
                        @forelse($produksis as $pkt)
                            <div x-show="(!produksiSearch || '{{ strtolower($pkt->judul . ' ' . $pkt->kode . ' ' . $pkt->reporter . ' ' . $pkt->editor) }}'.includes(produksiSearch.toLowerCase())) &&
                                         (!filterStatusProduksi || '{{ $pkt->status }}' === filterStatusProduksi) &&
                                         (!filterProgramProduksi || '{{ $pkt->program }}'.includes(filterProgramProduksi))"
                                 class="p-3 rounded-xl border border-slate-200 hover:border-amber-300 hover:shadow-sm transition-all bg-slate-50/50 flex flex-col justify-between gap-2">
                                
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex items-center space-x-2 pt-0.5">
                                        <input type="checkbox" value="{{ $pkt->id }}" class="produksi-checkbox rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                                        <span class="font-mono text-[10px] font-bold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200">
                                            {{ $pkt->kode }}
                                        </span>
                                        <span class="text-[10px] font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200">
                                            {{ $pkt->program }}
                                        </span>
                                        <span class="text-[10px] font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">
                                            ⏱️ {{ $pkt->durasi }}
                                        </span>
                                        <span class="text-[10px] font-extrabold px-2 py-0.5 rounded border {{ $pkt->status === 'Siap Tayang' || $pkt->status === 'Selesai' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : ($pkt->status === 'On Air' ? 'bg-rose-100 text-rose-800 border-rose-200 animate-pulse' : 'bg-amber-100 text-amber-800 border-amber-200') }}">
                                            {{ $pkt->status }}
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        <button type="button" @click="openEditProduksi({{ json_encode($pkt) }})" 
                                                class="p-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="Edit Paket">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </button>
                                        <form action="{{ route('tvri.berita.produksi.destroy', $pkt->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket {{ $pkt->kode }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors" title="Hapus Paket">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-xs font-bold text-slate-900 leading-snug">
                                        {{ $pkt->judul }}
                                    </h4>
                                    <div class="flex items-center space-x-2 text-[10px] text-slate-500 mt-1 flex-wrap gap-y-0.5">
                                        <span>✍️ Rep: <strong class="text-slate-700">{{ $pkt->reporter ?? '-' }}</strong></span>
                                        <span>&bull;</span>
                                        <span>📹 Cam: <strong class="text-slate-700">{{ $pkt->kamerawan ?? '-' }}</strong></span>
                                        <span>&bull;</span>
                                        <span>✂️ Ed: <strong class="text-slate-700">{{ $pkt->editor ?? '-' }}</strong></span>
                                        @if($pkt->tanggal_produksi)
                                            <span>&bull;</span>
                                            <span>Tgl Prod: {{ $pkt->tanggal_produksi ? (is_string($pkt->tanggal_produksi) ? substr($pkt->tanggal_produksi, 0, 10) : $pkt->tanggal_produksi->format('d/m/Y')) : '-' }}</span>
                                        @endif
                                    </div>
                                    @if($pkt->catatan)
                                        <p class="text-[10px] text-amber-900 bg-amber-50/70 px-2 py-0.5 rounded border border-amber-200/80 italic mt-1 line-clamp-1">
                                            Catatan: {{ $pkt->catatan }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 py-6 text-center">Belum ada paket produksi terdaftar.</p>
                        @endforelse
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Modul Produksi Paket Berita Terintegrasi</span>
                    <span class="text-amber-700 font-bold">TVRI Maluku</span>
                </div>
            </div>
        </div>

        <!-- Modal Edit Produksi Paket -->
        <div x-show="editProduksiModal" class="fixed inset-0 z-50 overflow-y-auto bg-black/60 flex items-center justify-center p-4" style="display: none;">
            <div class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl border border-slate-200" @click.away="editProduksiModal = false">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Edit Paket Produksi</h3>
                    <button type="button" @click="editProduksiModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg">&times;</button>
                </div>
                <form :action="'{{ url('tvri/berita/produksi') }}/' + editProduksiData.id" method="POST" class="space-y-3">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Judul / Topik Paket Berita</label>
                        <textarea name="judul" x-model="editProduksiData.judul" required rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold"></textarea>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Program Acara</label>
                            <input type="text" name="program" list="listProgramProduksi" x-model="editProduksiData.program" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kategori</label>
                            <input type="text" name="kategori" list="listKategoriProduksi" x-model="editProduksiData.kategori" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Durasi</label>
                            <input type="text" name="durasi" x-model="editProduksiData.durasi" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-mono font-bold text-center">
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-0.5">Reporter</label>
                            <input type="text" name="reporter" x-model="editProduksiData.reporter" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-0.5">Kamerawan</label>
                            <input type="text" name="kamerawan" x-model="editProduksiData.kamerawan" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-0.5">Editor</label>
                            <input type="text" name="editor" x-model="editProduksiData.editor" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Status Produksi</label>
                            <select name="status" x-model="editProduksiData.status" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold">
                                <option value="Editing">Editing</option>
                                <option value="Review EIC">Review EIC</option>
                                <option value="Siap Tayang">Siap Tayang</option>
                                <option value="On Air">On Air</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Prioritas</label>
                            <select name="prioritas" x-model="editProduksiData.prioritas" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold">
                                <option value="Biasa">Biasa</option>
                                <option value="Penting">Penting</option>
                                <option value="Mendesak">Mendesak</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Redaksi</label>
                        <input type="text" name="catatan" x-model="editProduksiData.catatan" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium">
                    </div>
                    <div class="flex items-center justify-end space-x-2 pt-2">
                        <button type="button" @click="editProduksiModal = false" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-black bg-amber-600 hover:bg-amber-700 text-white shadow-md">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 8: MONITORING BERITA HARIAN (CRUD)     -->
    <!-- ========================================== -->
    <div x-show="activeTab === 'monitoring'" class="space-y-6"
         x-data="{ monSearch: '', filterStatusMon: '' }">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Form Tambah Log Monitoring -->
            <div class="lg:col-span-5 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm h-fit">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-2">
                        <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                            📡
                        </div>
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Input Log Monitoring Siaran</h3>
                    </div>
                    <span class="text-[10px] font-mono text-slate-400">Auto Kode MON</span>
                </div>

                <form action="{{ route('tvri.berita.monitoring.store') }}" method="POST" class="space-y-3">
                    @csrf

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Siaran</label>
                            <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}"
                                   class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Jam Tayang (WIT)</label>
                            <input type="text" name="jam_tayang" required placeholder="07:00 - 07:30 WIT" value="17:00 - 17:30 WIT"
                                   class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-mono font-bold text-center">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Program Siaran Berita</label>
                        <input type="text" name="program_siaran" list="listProgramSiaranMonitoring" value="Maluku Hari Ini Pagi" placeholder="Contoh: Maluku Hari Ini Pagi" required
                               class="w-full px-2.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:border-blue-500 focus:outline-none">
                        <datalist id="listProgramSiaranMonitoring">
                            <option value="Maluku Hari Ini Pagi">Maluku Hari Ini Pagi (07:00 WIT)</option>
                            <option value="Warta Nusantara (Relai Nasional)">Warta Nusantara (Relai Nasional, 12:00 WIT)</option>
                            <option value="Maluku Hari Ini Sore">Maluku Hari Ini Sore (17:00 WIT)</option>
                            <option value="Berita Daerah">Berita Daerah (19:00 WIT)</option>
                            <option value="Breaking News &amp; LIVE Upacara">Breaking News &amp; LIVE Upacara</option>
                            <option value="Dialog LIVE &quot;Maluku Membangun&quot;">Dialog LIVE "Maluku Membangun"</option>
                            <option value="Panorama Maluku">Panorama Maluku</option>
                        </datalist>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-0.5">Presenter / Anchor</label>
                            <input type="text" name="presenter" placeholder="Clara Lekatompessy"
                                   class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-0.5">Produser Berita</label>
                            <input type="text" name="produser" placeholder="Nahrum" value="Nahrum"
                                   class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Rundown Berita Utama / Fokus Siaran</label>
                        <textarea name="rundown_fokus" required rows="2" placeholder="Contoh: Pembahasan KUA-PPAS APBD 2027 di DPRD Maluku & Pantauan Harga Pangan"
                                  class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:border-blue-500"></textarea>
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-0.5">Jumlah Berita</label>
                            <input type="number" name="jumlah_berita" value="8" min="1"
                                   class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-center">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-0.5">Status Siaran</label>
                            <select name="status_siaran" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold">
                                <option value="Sesuai Jadwal">Sesuai Jadwal</option>
                                <option value="On Air">On Air</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Delay">Delay</option>
                                <option value="Kendala Teknis">Kendala Teknis</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-0.5">Kualitas A/V</label>
                            <select name="kualitas_av" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold">
                                <option value="Sangat Baik">Sangat Baik</option>
                                <option value="Baik">Baik</option>
                                <option value="Cukup">Cukup</option>
                                <option value="Gangguan">Gangguan</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Evaluasi Siaran / Catatan MCR</label>
                        <input type="text" name="evaluasi_catatan" placeholder="Contoh: Transisi video server lancar, audio narasumber jernih."
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium">
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-[#003B70] hover:from-blue-700 hover:to-[#00264A] text-white font-black text-xs shadow-md transition-all">
                        + Simpan Log Monitoring Siaran
                    </button>
                </form>
            </div>

            <!-- Tabel Log Monitoring Berita -->
            <div class="lg:col-span-7 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100 flex-wrap gap-2">
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-pulse"></span>
                                <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Monitoring Penayangan Berita</h3>
                                <span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200 text-[10px] font-extrabold font-mono">{{ count($monitorings) }} Siaran</span>
                            </div>
                            <p class="text-[11px] text-slate-500 font-medium mt-1">
                                Rekap monitoring tayang on-air, kualitas audio visual, dan evaluasi harian Master Control Room.
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="button" id="btnBulkDeleteMonitoring" onclick="submitBulkDeleteMonitoring()" class="hidden px-3 py-1.5 rounded-xl bg-rose-600 text-white font-black text-xs hover:bg-rose-700 shadow-md transition-all flex items-center space-x-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                <span id="btnBulkDeleteMonitoringText">Hapus Terpilih</span>
                            </button>
                        </div>
                    </div>

                    <!-- Filter Bar -->
                    <div class="space-y-2 mb-3">
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2">
                            <div class="sm:col-span-8">
                                <input type="text" x-model="monSearch" placeholder="Cari program / presenter / fokus..."
                                       class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-medium">
                            </div>
                            <div class="sm:col-span-4">
                                <select x-model="filterStatusMon" class="w-full px-2 py-1.5 rounded-xl border border-slate-200 text-xs">
                                    <option value="">Semua Status Siaran</option>
                                    <option value="Sesuai Jadwal">Sesuai Jadwal</option>
                                    <option value="On Air">On Air</option>
                                    <option value="Selesai">Selesai</option>
                                    <option value="Kendala Teknis">Kendala Teknis</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Bulk Delete Form -->
                    <form id="formBulkDeleteMonitoring" action="{{ route('tvri.berita.monitoring.bulk-destroy') }}" method="POST" class="hidden">
                        @csrf
                        <div id="bulkDeleteMonitoringContainer"></div>
                    </form>

                    <!-- Daftar Card Monitoring -->
                    <div class="space-y-2.5 max-h-[580px] overflow-y-auto pr-1">
                        @forelse($monitorings as $mon)
                            <div x-show="(!monSearch || '{{ strtolower($mon->program_siaran . ' ' . $mon->rundown_fokus . ' ' . $mon->presenter) }}'.includes(monSearch.toLowerCase())) &&
                                         (!filterStatusMon || '{{ $mon->status_siaran }}' === filterStatusMon)"
                                 class="p-3 rounded-xl border border-slate-200 hover:border-blue-300 hover:shadow-sm transition-all bg-slate-50/50 flex flex-col justify-between gap-2">
                                
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex items-center space-x-2 pt-0.5">
                                        <input type="checkbox" value="{{ $mon->id }}" class="monitoring-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                        <span class="font-mono text-[10px] font-bold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200">
                                            {{ $mon->kode }}
                                        </span>
                                        <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">
                                            {{ $mon->jam_tayang }}
                                        </span>
                                        <span class="text-[10px] font-extrabold px-2 py-0.5 rounded border {{ $mon->status_siaran === 'Selesai' || $mon->status_siaran === 'Sesuai Jadwal' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-rose-100 text-rose-800 border-rose-200' }}">
                                            {{ $mon->status_siaran }}
                                        </span>
                                        <span class="text-[10px] font-bold text-teal-800 bg-teal-50 px-2 py-0.5 rounded border border-teal-200">
                                            A/V: {{ $mon->kualitas_av }}
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        <button type="button" @click="openEditMonitoring({{ json_encode($mon) }})" 
                                                class="p-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="Edit Log">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </button>
                                        <form action="{{ route('tvri.berita.monitoring.destroy', $mon->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus log {{ $mon->kode }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors" title="Hapus Log">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-xs font-bold text-slate-900 leading-snug">
                                        {{ $mon->program_siaran }}
                                    </h4>
                                    <p class="text-[11px] text-slate-600 mt-0.5 line-clamp-2">
                                        <strong class="text-slate-800">Fokus:</strong> {{ $mon->rundown_fokus }}
                                    </p>
                                    <div class="flex items-center space-x-2 text-[10px] text-slate-500 mt-1 flex-wrap gap-y-0.5">
                                        <span>📅 {{ $mon->tanggal ? (is_string($mon->tanggal) ? substr($mon->tanggal, 0, 10) : $mon->tanggal->format('d/m/Y')) : '-' }}</span>
                                        <span>&bull;</span>
                                        <span>🎙️ Anchor: <strong class="text-slate-700">{{ $mon->presenter ?? '-' }}</strong></span>
                                        <span>&bull;</span>
                                        <span>👤 Prod: <strong class="text-slate-700">{{ $mon->produser ?? 'Nahrum' }}</strong></span>
                                    </div>
                                    @if($mon->evaluasi_catatan)
                                        <div class="pt-1 text-[10px] text-slate-500 italic">
                                            Catatan MCR: {{ $mon->evaluasi_catatan }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 py-6 text-center">Belum ada log monitoring terdaftar.</p>
                        @endforelse
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Sistem Pemantauan On-Air Siaran TVRI Maluku</span>
                    <span class="text-blue-700 font-bold">MCR &amp; Newsroom</span>
                </div>
            </div>
        </div>

        <!-- Modal Edit Monitoring -->
        <div x-show="editMonitoringModal" class="fixed inset-0 z-50 overflow-y-auto bg-black/60 flex items-center justify-center p-4" style="display: none;">
            <div class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl border border-slate-200" @click.away="editMonitoringModal = false">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Edit Log Monitoring Siaran</h3>
                    <button type="button" @click="editMonitoringModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg">&times;</button>
                </div>
                <form :action="'{{ url('tvri/berita/monitoring') }}/' + editMonitoringData.id" method="POST" class="space-y-3">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Siaran</label>
                            <input type="date" name="tanggal" x-model="editMonitoringData.tanggal" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Jam Tayang</label>
                            <input type="text" name="jam_tayang" x-model="editMonitoringData.jam_tayang" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-mono font-bold text-center">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Program Siaran Berita</label>
                        <input type="text" name="program_siaran" list="listProgramSiaranMonitoring" x-model="editMonitoringData.program_siaran" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Fokus Berita Utama</label>
                        <textarea name="rundown_fokus" x-model="editMonitoringData.rundown_fokus" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Status Siaran</label>
                            <select name="status_siaran" x-model="editMonitoringData.status_siaran" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold">
                                <option value="Sesuai Jadwal">Sesuai Jadwal</option>
                                <option value="On Air">On Air</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Delay">Delay</option>
                                <option value="Kendala Teknis">Kendala Teknis</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kualitas A/V</label>
                            <select name="kualitas_av" x-model="editMonitoringData.kualitas_av" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold">
                                <option value="Sangat Baik">Sangat Baik</option>
                                <option value="Baik">Baik</option>
                                <option value="Cukup">Cukup</option>
                                <option value="Gangguan">Gangguan</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Evaluasi / Catatan MCR</label>
                        <input type="text" name="evaluasi_catatan" x-model="editMonitoringData.evaluasi_catatan" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium">
                    </div>
                    <div class="flex items-center justify-end space-x-2 pt-2">
                        <button type="button" @click="editMonitoringModal = false" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-black bg-blue-600 hover:bg-blue-700 text-white shadow-md">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 9: DOKUMENTASI BERITA HARIAN (CRUD)    -->
    <!-- ========================================== -->
    <div x-show="activeTab === 'dokumentasi'" class="space-y-6"
         x-data="{ dokSearch: '', filterMediaDok: '', filterArsipDok: '' }">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Form Tambah Dokumentasi Baru -->
            <div class="lg:col-span-5 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm h-fit">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-2">
                        <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                            🗄️
                        </div>
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Input Dokumentasi Siaran</h3>
                    </div>
                    <span class="text-[10px] font-mono text-slate-400">Auto Kode DOK</span>
                </div>

                <form action="{{ route('tvri.berita.dokumentasi.store') }}" method="POST" class="space-y-3">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Judul Dokumentasi / Arsip Liputan</label>
                        <textarea name="judul_dokumentasi" required rows="2" placeholder="Contoh: Master Footage Rapat Paripurna KUA-PPAS APBD 2027 DPRD Maluku"
                                  class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:border-purple-500"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Liputan</label>
                            <input type="date" name="tanggal_liputan" required value="{{ date('Y-m-d') }}"
                                   class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Lokasi Liputan</label>
                            <input type="text" name="lokasi" placeholder="Ambon" value="Ambon"
                                   class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-0.5">Crew Lapangan (Reporter / Cam)</label>
                        <input type="text" name="crew" placeholder="Sarles Bolaman (Rep) & Minggus Noya (Cam)"
                               class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold">
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Media</label>
                            <select name="jenis_media" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold">
                                <option value="Footage Video">Footage Video</option>
                                <option value="Master Siaran">Master Siaran</option>
                                <option value="Audio VO">Audio VO</option>
                                <option value="Foto Lapangan">Foto Lapangan</option>
                                <option value="Naskah Berita">Naskah Berita</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Status Arsip</label>
                            <select name="status_arsip" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold">
                                <option value="Tersimpan DMS">Tersimpan DMS</option>
                                <option value="Terdistribusi Digital">Terdistribusi Digital</option>
                                <option value="Master Tape">Master Tape</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Format File</label>
                            <input type="text" name="format_file" placeholder="MP4 1080p / XDCAM / RAW" value="MP4 1080p"
                                   class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-mono font-bold text-center">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Ukuran File</label>
                            <input type="text" name="ukuran_file" placeholder="1.5 GB" value="1.2 GB"
                                   class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-mono font-bold text-center">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Link Media / File Arsip (Opsional)</label>
                        <input type="text" name="link_media" placeholder="https://..."
                               class="w-full px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan Tambahan</label>
                        <input type="text" name="keterangan" placeholder="Contoh: Arsip mentah 2 jam wawancara."
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium">
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-700 hover:from-purple-700 hover:to-indigo-800 text-white font-black text-xs shadow-md transition-all">
                        + Simpan Dokumentasi &amp; Arsipkan
                    </button>
                </form>
            </div>

            <!-- Tabel Dokumentasi Berita -->
            <div class="lg:col-span-7 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100 flex-wrap gap-2">
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-purple-500 animate-pulse"></span>
                                <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Pangkalan Dokumentasi Berita</h3>
                                <span class="px-2 py-0.5 rounded-full bg-purple-50 text-purple-700 border border-purple-200 text-[10px] font-extrabold font-mono">{{ count($dokumentasis) }} Arsip</span>
                            </div>
                            <p class="text-[11px] text-slate-500 font-medium mt-1">
                                Master audio-visual, footage lapangan, foto dokumentasi, dan aset naskah berita TVRI Maluku.
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="button" id="btnBulkDeleteDokumentasi" onclick="submitBulkDeleteDokumentasi()" class="hidden px-3 py-1.5 rounded-xl bg-rose-600 text-white font-black text-xs hover:bg-rose-700 shadow-md transition-all flex items-center space-x-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                <span id="btnBulkDeleteDokumentasiText">Hapus Terpilih</span>
                            </button>
                        </div>
                    </div>

                    <!-- Filter Bar -->
                    <div class="space-y-2 mb-3">
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2">
                            <div class="sm:col-span-6">
                                <input type="text" x-model="dokSearch" placeholder="Cari judul / lokasi / kru..."
                                       class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-medium">
                            </div>
                            <div class="sm:col-span-3">
                                <select x-model="filterMediaDok" class="w-full px-2 py-1.5 rounded-xl border border-slate-200 text-xs">
                                    <option value="">Semua Media</option>
                                    <option value="Footage Video">Footage Video</option>
                                    <option value="Master Siaran">Master Siaran</option>
                                    <option value="Audio VO">Audio VO</option>
                                    <option value="Foto Lapangan">Foto Lapangan</option>
                                    <option value="Naskah Berita">Naskah Berita</option>
                                </select>
                            </div>
                            <div class="sm:col-span-3">
                                <select x-model="filterArsipDok" class="w-full px-2 py-1.5 rounded-xl border border-slate-200 text-xs">
                                    <option value="">Semua Status</option>
                                    <option value="Tersimpan DMS">Tersimpan DMS</option>
                                    <option value="Terdistribusi Digital">Terdistribusi</option>
                                    <option value="Master Tape">Master Tape</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Bulk Delete Form -->
                    <form id="formBulkDeleteDokumentasi" action="{{ route('tvri.berita.dokumentasi.bulk-destroy') }}" method="POST" class="hidden">
                        @csrf
                        <div id="bulkDeleteDokumentasiContainer"></div>
                    </form>

                    <!-- Daftar Card Dokumentasi -->
                    <div class="space-y-2.5 max-h-[580px] overflow-y-auto pr-1">
                        @forelse($dokumentasis as $dok)
                            <div x-show="(!dokSearch || '{{ strtolower($dok->judul_dokumentasi . ' ' . $dok->lokasi . ' ' . $dok->crew) }}'.includes(dokSearch.toLowerCase())) &&
                                         (!filterMediaDok || '{{ $dok->jenis_media }}' === filterMediaDok) &&
                                         (!filterArsipDok || '{{ $dok->status_arsip }}' === filterArsipDok)"
                                 class="p-3 rounded-xl border border-slate-200 hover:border-purple-300 hover:shadow-sm transition-all bg-slate-50/50 flex flex-col justify-between gap-2">
                                
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex items-center space-x-2 pt-0.5">
                                        <input type="checkbox" value="{{ $dok->id }}" class="dokumentasi-checkbox rounded border-slate-300 text-purple-600 focus:ring-purple-500">
                                        <span class="font-mono text-[10px] font-bold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200">
                                            {{ $dok->kode }}
                                        </span>
                                        <span class="text-[10px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded border border-purple-200">
                                            {{ $dok->jenis_media }}
                                        </span>
                                        <span class="text-[10px] font-bold text-teal-800 bg-teal-50 px-2 py-0.5 rounded border border-teal-200">
                                            {{ $dok->status_arsip }}
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        <button type="button" @click="openEditDokumentasi({{ json_encode($dok) }})" 
                                                class="p-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="Edit Dokumentasi">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </button>
                                        <form action="{{ route('tvri.berita.dokumentasi.destroy', $dok->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus arsip {{ $dok->kode }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors" title="Hapus Dokumentasi">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-xs font-bold text-slate-900 leading-snug">
                                        {{ $dok->judul_dokumentasi }}
                                    </h4>
                                    <div class="flex items-center space-x-2 text-[10px] text-slate-500 mt-1 flex-wrap gap-y-0.5">
                                        <span>📅 {{ $dok->tanggal_liputan ? (is_string($dok->tanggal_liputan) ? substr($dok->tanggal_liputan, 0, 10) : $dok->tanggal_liputan->format('d/m/Y')) : '-' }}</span>
                                        <span>&bull;</span>
                                        <span>📍 {{ $dok->lokasi }}</span>
                                        <span>&bull;</span>
                                        <span>💾 {{ $dok->format_file }} ({{ $dok->ukuran_file }})</span>
                                    </div>
                                    @if($dok->crew)
                                        <p class="text-[10px] text-slate-500 mt-0.5">
                                            👥 {{ $dok->crew }}
                                        </p>
                                    @endif
                                </div>

                                <div class="pt-1.5 border-t border-slate-200/60 flex items-center justify-between text-[10px]">
                                    <span class="text-slate-400 italic">{{ $dok->keterangan ?? 'Tersimpan aman di DMS' }}</span>
                                    @if($dok->link_media)
                                        <a href="{{ $dok->link_media }}" target="_blank" class="font-bold text-purple-600 hover:text-purple-800 hover:underline inline-flex items-center space-x-1">
                                            <span>Buka Media</span>
                                            <svg class="w-3 h-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 py-6 text-center">Belum ada arsip dokumentasi terdaftar.</p>
                        @endforelse
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Arsip &amp; Dokumentasi Terpadu Digital</span>
                    <span class="text-purple-700 font-bold">Pangkalan Dokumen</span>
                </div>
            </div>
        </div>

        <!-- Modal Edit Dokumentasi -->
        <div x-show="editDokumentasiModal" class="fixed inset-0 z-50 overflow-y-auto bg-black/60 flex items-center justify-center p-4" style="display: none;">
            <div class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl border border-slate-200" @click.away="editDokumentasiModal = false">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Edit Dokumentasi Berita</h3>
                    <button type="button" @click="editDokumentasiModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg">&times;</button>
                </div>
                <form :action="'{{ url('tvri/berita/dokumentasi') }}/' + editDokumentasiData.id" method="POST" class="space-y-3">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Judul Dokumentasi</label>
                        <textarea name="judul_dokumentasi" x-model="editDokumentasiData.judul_dokumentasi" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Liputan</label>
                            <input type="date" name="tanggal_liputan" x-model="editDokumentasiData.tanggal_liputan" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Lokasi Liputan</label>
                            <input type="text" name="lokasi" x-model="editDokumentasiData.lokasi" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-0.5">Crew Lapangan</label>
                        <input type="text" name="crew" x-model="editDokumentasiData.crew" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Media</label>
                            <select name="jenis_media" x-model="editDokumentasiData.jenis_media" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold">
                                <option value="Footage Video">Footage Video</option>
                                <option value="Master Siaran">Master Siaran</option>
                                <option value="Audio VO">Audio VO</option>
                                <option value="Foto Lapangan">Foto Lapangan</option>
                                <option value="Naskah Berita">Naskah Berita</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Status Arsip</label>
                            <select name="status_arsip" x-model="editDokumentasiData.status_arsip" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold">
                                <option value="Tersimpan DMS">Tersimpan DMS</option>
                                <option value="Terdistribusi Digital">Terdistribusi Digital</option>
                                <option value="Master Tape">Master Tape</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Format File</label>
                            <input type="text" name="format_file" x-model="editDokumentasiData.format_file" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-mono font-bold text-center">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Ukuran File</label>
                            <input type="text" name="ukuran_file" x-model="editDokumentasiData.ukuran_file" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-mono font-bold text-center">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Link Media / File URL</label>
                        <input type="text" name="link_media" x-model="editDokumentasiData.link_media" class="w-full px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-mono">
                    </div>
                    <div class="flex items-center justify-end space-x-2 pt-2">
                        <button type="button" @click="editDokumentasiModal = false" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-black bg-purple-600 hover:bg-purple-700 text-white shadow-md">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== REAL TIME CLOCK & DATE (Jam, Hari, Tanggal, Tahun) =====
    function updateClock() {
        const now = new Date();
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        const dayName = days[now.getDay()];
        const dateNum = now.getDate();
        const monthName = months[now.getMonth()];
        const yearNum = now.getFullYear();

        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');

        const dayDateEl = document.getElementById('realtimeDayDate');
        const clockEl = document.getElementById('realtimeClock');

        if (dayDateEl) {
            dayDateEl.textContent = `${dayName}, ${dateNum} ${monthName} ${yearNum}`;
        }
        if (clockEl) {
            clockEl.textContent = `${hours}:${minutes}:${seconds} WIT`;
        }
    }
    updateClock();
    setInterval(updateClock, 1000);

    const checkAllSop = document.getElementById('checkAllSop');
    const sopCheckboxes = document.querySelectorAll('.sop-checkbox');
    const btnBulkDeleteSop = document.getElementById('btnBulkDeleteSop');

    function updateBulkBtn() {
        const checkedCount = document.querySelectorAll('.sop-checkbox:checked').length;
        if (btnBulkDeleteSop) {
            if (checkedCount > 0) {
                btnBulkDeleteSop.classList.remove('hidden');
                btnBulkDeleteSop.innerText = `Hapus (${checkedCount}) Terpilih`;
            } else {
                btnBulkDeleteSop.classList.add('hidden');
            }
        }
    }

    if (checkAllSop) {
        checkAllSop.addEventListener('change', function() {
            sopCheckboxes.forEach(cb => cb.checked = checkAllSop.checked);
            updateBulkBtn();
        });
    }

    sopCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkBtn);
    });

    // Kotak Central: Select All & Bulk Delete Berita / File Video
    const checkAllBerita = document.getElementById('checkAllBerita');
    const beritaCheckboxes = document.querySelectorAll('.berita-checkbox');
    const btnBulkDeleteBerita = document.getElementById('btnBulkDeleteBerita');
    const btnBulkDeleteBeritaText = document.getElementById('btnBulkDeleteBeritaText');

    function updateBulkBeritaBtn() {
        const checkedCount = document.querySelectorAll('.berita-checkbox:checked').length;
        if (btnBulkDeleteBerita) {
            if (checkedCount > 0) {
                btnBulkDeleteBerita.classList.remove('hidden');
                if (btnBulkDeleteBeritaText) {
                    btnBulkDeleteBeritaText.innerText = `Hapus (${checkedCount}) File/Berita`;
                }
            } else {
                btnBulkDeleteBerita.classList.add('hidden');
            }
        }
    }

    if (checkAllBerita) {
        checkAllBerita.addEventListener('change', function() {
            beritaCheckboxes.forEach(cb => cb.checked = checkAllBerita.checked);
            updateBulkBeritaBtn();
        });
    }

    beritaCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkBeritaBtn);
    });

    // Helper Generic Setup Bulk
    function setupGenericBulk(checkAllId, itemClass, btnId, btnTextId) {
        const checkAll = document.getElementById(checkAllId);
        const btn = document.getElementById(btnId);
        const btnText = btnTextId ? document.getElementById(btnTextId) : null;

        function updateState() {
            const checkedCount = document.querySelectorAll('.' + itemClass + ':checked').length;
            if (btn) {
                if (checkedCount > 0) {
                    btn.classList.remove('hidden');
                    if (btnText) {
                        btnText.innerText = `Hapus (${checkedCount}) Terpilih`;
                    }
                } else {
                    btn.classList.add('hidden');
                }
            }
        }

        if (checkAll) {
            checkAll.addEventListener('change', function() {
                document.querySelectorAll('.' + itemClass).forEach(cb => cb.checked = checkAll.checked);
                updateState();
            });
        }

        document.querySelectorAll('.' + itemClass).forEach(cb => {
            cb.addEventListener('change', updateState);
        });
    }

    // Kotak Central: Realtime Feeds, Agenda Liputan, Catatan
    setupGenericBulk('checkAllRealtime', 'realtime-checkbox', 'btnBulkDeleteRealtime', 'btnBulkDeleteRealtimeText');
    setupGenericBulk('checkAllAgenda', 'agenda-checkbox', 'btnBulkDeleteAgenda', 'btnBulkDeleteAgendaText');
    setupGenericBulk('checkAllCatatan', 'catatan-checkbox', 'btnBulkDeleteCatatan', 'btnBulkDeleteCatatanText');
    setupGenericBulk('checkAllProduksi', 'produksi-checkbox', 'btnBulkDeleteProduksi', 'btnBulkDeleteProduksiText');
    setupGenericBulk('checkAllMonitoring', 'monitoring-checkbox', 'btnBulkDeleteMonitoring', 'btnBulkDeleteMonitoringText');
    setupGenericBulk('checkAllDokumentasi', 'dokumentasi-checkbox', 'btnBulkDeleteDokumentasi', 'btnBulkDeleteDokumentasiText');
});

window.submitBulkDeleteBerita = function() {
    const checked = document.querySelectorAll('.berita-checkbox:checked');
    if (checked.length === 0) return;
    if (confirm(`Apakah Anda yakin ingin menghapus ${checked.length} data berita beserta file video terkait? Tindakan ini tidak dapat dibatalkan.`)) {
        const form = document.getElementById('formBulkDeleteBerita');
        const container = document.getElementById('bulkDeleteBeritaContainer');
        if (container) {
            container.innerHTML = '';
            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                container.appendChild(input);
            });
        }
        form.submit();
    }
};

window.submitBulkDeleteRealtime = function() {
    const checked = document.querySelectorAll('.realtime-checkbox:checked');
    if (checked.length === 0) return;
    if (confirm(`Apakah Anda yakin ingin menghapus ${checked.length} feed live newsroom terpilih?`)) {
        const form = document.getElementById('formBulkDeleteRealtime');
        const container = document.getElementById('bulkDeleteRealtimeContainer');
        if (container) {
            container.innerHTML = '';
            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                container.appendChild(input);
            });
        }
        form.submit();
    }
};

window.submitBulkDeleteAgenda = function() {
    const checked = document.querySelectorAll('.agenda-checkbox:checked');
    if (checked.length === 0) return;
    if (confirm(`Apakah Anda yakin ingin menghapus ${checked.length} agenda liputan terpilih?`)) {
        const form = document.getElementById('formBulkDeleteAgenda');
        const container = document.getElementById('bulkDeleteAgendaContainer');
        if (container) {
            container.innerHTML = '';
            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                container.appendChild(input);
            });
        }
        form.submit();
    }
};

window.submitBulkDeleteCatatan = function() {
    const checked = document.querySelectorAll('.catatan-checkbox:checked');
    if (checked.length === 0) return;
    if (confirm(`Apakah Anda yakin ingin menghapus ${checked.length} catatan / peringatan redaksi terpilih?`)) {
        const form = document.getElementById('formBulkDeleteCatatan');
        const container = document.getElementById('bulkDeleteCatatanContainer');
        if (container) {
            container.innerHTML = '';
            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                container.appendChild(input);
            });
        }
        form.submit();
    }
};


window.submitBulkDeleteProduksi = function() {
    const checked = document.querySelectorAll('.produksi-checkbox:checked');
    if (checked.length === 0) return;
    if (confirm(`Apakah Anda yakin ingin menghapus ${checked.length} paket produksi berita terpilih?`)) {
        const form = document.getElementById('formBulkDeleteProduksi');
        const container = document.getElementById('bulkDeleteProduksiContainer');
        if (container) {
            container.innerHTML = '';
            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                container.appendChild(input);
            });
        }
        form.submit();
    }
};

window.submitBulkDeleteMonitoring = function() {
    const checked = document.querySelectorAll('.monitoring-checkbox:checked');
    if (checked.length === 0) return;
    if (confirm(`Apakah Anda yakin ingin menghapus ${checked.length} log monitoring siaran terpilih?`)) {
        const form = document.getElementById('formBulkDeleteMonitoring');
        const container = document.getElementById('bulkDeleteMonitoringContainer');
        if (container) {
            container.innerHTML = '';
            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                container.appendChild(input);
            });
        }
        form.submit();
    }
};

window.submitBulkDeleteDokumentasi = function() {
    const checked = document.querySelectorAll('.dokumentasi-checkbox:checked');
    if (checked.length === 0) return;
    if (confirm(`Apakah Anda yakin ingin menghapus ${checked.length} arsip dokumentasi berita terpilih?`)) {
        const form = document.getElementById('formBulkDeleteDokumentasi');
        const container = document.getElementById('bulkDeleteDokumentasiContainer');
        if (container) {
            container.innerHTML = '';
            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                container.appendChild(input);
            });
        }
        form.submit();
    }
};

function submitBulkDeleteSop() {
    const checked = document.querySelectorAll('.sop-checkbox:checked');
    if (checked.length === 0) return;
    if (confirm(`Apakah Anda yakin ingin menghapus ${checked.length} SOP Berita terpilih?`)) {
        const form = document.getElementById('formBulkDeleteSop');
        const container = document.getElementById('bulkDeleteSopContainer');
        if (container) {
            container.innerHTML = '';
            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                container.appendChild(input);
            });
        }
        form.submit();
    }
}

// Live Real-Time Clock WIT (Hari, Tanggal Bulan Tahun & Jam WIT)
function updateAgendaManageClock() {
    const el = document.getElementById('agendaManageRealtimeClock');
    if (!el) return;
    try {
        const now = new Date();
        const optionsDate = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', timeZone: 'Asia/Jayapura' };
        const optionsTime = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false, timeZone: 'Asia/Jayapura' };
        const dateStr = new Intl.DateTimeFormat('id-ID', optionsDate).format(now);
        const timeStr = new Intl.DateTimeFormat('id-ID', optionsTime).format(now).replace(/\./g, ':');
        el.innerHTML = `${dateStr} &bull; ${timeStr} WIT`;
    } catch (e) {}
}
setInterval(updateAgendaManageClock, 1000);

// Video Player Modal Controller
function openVideoPlayer(videoUrl, title) {
    const modal = document.getElementById('videoModal');
    const player = document.getElementById('videoModalElement');
    const source = document.getElementById('videoModalSource');
    const iframe = document.getElementById('videoModalIframe');
    const titleEl = document.getElementById('videoModalTitle');
    
    if (titleEl) {
        titleEl.textContent = title || 'Pemutar Video Berita TVRI Maluku';
    }
    
    if (!videoUrl || videoUrl === 'null' || videoUrl === 'undefined' || videoUrl === '') {
        videoUrl = 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4';
    }

    if (videoUrl.includes('localhost/storage/') || videoUrl.includes('127.0.0.1/storage/')) {
        videoUrl = videoUrl.replace(/^https?:\/\/[^\/]+\/storage\//, window.location.origin + '/storage/');
    } else if (videoUrl.startsWith('/storage/')) {
        videoUrl = window.location.origin + videoUrl;
    }

    const ytMatch = videoUrl.match(/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/);
    if (ytMatch && ytMatch[1]) {
        if (player) {
            player.pause();
            player.classList.add('hidden');
            player.style.display = 'none';
        }
        if (iframe) {
            iframe.src = 'https://www.youtube.com/embed/' + ytMatch[1] + '?autoplay=1&rel=0';
            iframe.classList.remove('hidden');
            iframe.style.display = 'block';
        }
    } else {
        if (iframe) {
            iframe.src = '';
            iframe.classList.add('hidden');
            iframe.style.display = 'none';
        }
        if (player) {
            player.classList.remove('hidden');
            player.style.display = 'block';
            
            player.onerror = function() {
                const fallbackUrl = 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4';
                if (player.src !== fallbackUrl) {
                    player.src = fallbackUrl;
                    if (source) source.src = fallbackUrl;
                    player.load();
                    player.play().catch(function() {});
                }
            };

            player.src = videoUrl;
            if (source) {
                source.src = videoUrl;
            }
            player.load();
            const playPromise = player.play();
            if (playPromise !== undefined) {
                playPromise.catch(function(e) {
                    player.muted = true;
                    player.play().catch(function() {});
                });
            }
        }
    }
    
    if (modal) {
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
    }
}

function closeVideoPlayer() {
    const modal = document.getElementById('videoModal');
    const player = document.getElementById('videoModalElement');
    const source = document.getElementById('videoModalSource');
    const iframe = document.getElementById('videoModalIframe');
    
    if (player) {
        player.pause();
        player.removeAttribute('src');
        player.load();
    }
    if (source) {
        source.removeAttribute('src');
    }
    if (iframe) {
        iframe.src = '';
        iframe.classList.add('hidden');
        iframe.style.display = 'none';
    }
    if (modal) {
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeVideoPlayer();
    }
});
</script>

<!-- Modal Video Player -->
<div id="videoModal" onclick="closeVideoPlayer()" style="display: none;" class="fixed inset-0 z-[99999] bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative bg-slate-900 rounded-2xl overflow-hidden border border-slate-700 shadow-2xl max-w-3xl w-full" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-4 py-3 bg-slate-800/80 border-b border-slate-700">
            <div class="flex items-center space-x-2 truncate pr-4">
                <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse shrink-0"></span>
                <h4 id="videoModalTitle" class="text-sm font-bold text-white truncate">Pemutar Video Berita TVRI Maluku</h4>
            </div>
            <button type="button" onclick="closeVideoPlayer()" class="text-slate-400 hover:text-white text-2xl font-bold leading-none p-1 cursor-pointer transition-colors">&times;</button>
        </div>
        <div class="p-2 bg-black flex justify-center items-center relative min-h-[260px]">
            <video id="videoModalElement" controls playsinline class="max-h-[70vh] w-full rounded-lg" preload="auto">
                <source id="videoModalSource" src="" type="video/mp4">
                Browser Anda tidak mendukung pemutaran video HTML5.
            </video>
            <iframe id="videoModalIframe" src="" class="hidden w-full h-[55vh] rounded-lg border-0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    </div>
</div>

@include('tvri.partials.jadwal_vercel_modal')
@endsection
