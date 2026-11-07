@extends('layouts.app')

@section('title', 'Katalog & Standar Layanan Kebudayaan - TVRI Maluku')

@section('content')
<div class="space-y-6">

    <!-- Header & Welcome Banner -->
    <div class="bg-gradient-to-r from-[#00264A] via-[#003B70] to-[#005B9F] rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 w-96 h-96 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <span class="px-3 py-1 bg-amber-400/20 text-amber-200 border border-amber-400/40 rounded-full text-xs font-bold tracking-wider uppercase">
                        Standar Layanan Kebudayaan
                    </span>
                    <span class="text-xs text-blue-200">Bidang Kebudayaan Provinsi Maluku</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-black tracking-tight text-white">
                    Katalog & Standar Layanan Kebudayaan
                </h1>
                <p class="text-sm text-blue-100 max-w-3xl mt-1 leading-relaxed">
                    Pangkalan Data Dinas PUPR Maluku & Sistem Administrasi Dokumen Resmi Layanan Bidang Kebudayaan TVRI Maluku: Cagar Budaya & Permuseuman, Warisan Budaya Takbenda (WBTb), Kesenian & Event, serta Komunitas & Lembaga Adat.
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('pendidikan.gtk.layanan.export-csv', request()->query()) }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-xl border border-white/20 backdrop-blur-md transition-all duration-200">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Export CSV</span>
                </a>
                <a href="{{ route('pendidikan.gtk.layanan.create') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-900 text-xs font-bold rounded-xl shadow-lg hover:shadow-amber-500/30 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>+ Buat Permohonan Layanan GTK</span>
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

    <!-- 4 Main Layanan Highlight Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Sertifikasi Pendidik -->
        <a href="{{ route('pendidikan.gtk.layanan.index', ['jenis_layanan' => 'Sertifikasi Pendidik']) }}" 
           class="group bg-white p-5 rounded-2xl border transition-all duration-200 hover:shadow-lg relative overflow-hidden {{ request('jenis_layanan') === 'Sertifikasi Pendidik' ? 'border-amber-500 ring-2 ring-amber-400/30' : 'border-slate-100 hover:border-amber-300' }}">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 group-hover:bg-amber-100 text-amber-600 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                </div>
                <span class="text-2xl font-black text-[#00264A]">{{ number_format($stats['sertifikasi'], 0, ',', '.') }}</span>
            </div>
            <h3 class="text-sm font-bold text-slate-800 group-hover:text-amber-600 transition-colors">Sertifikasi Pendidik</h3>
            <p class="text-[11px] text-slate-500 mt-1 line-clamp-2">Pemberkasan PPG, NRG, & Validasi Sertifikat Pendidik</p>
        </a>

        <!-- 2. NUPTK -->
        <a href="{{ route('pendidikan.gtk.layanan.index', ['jenis_layanan' => 'NUPTK']) }}" 
           class="group bg-white p-5 rounded-2xl border transition-all duration-200 hover:shadow-lg relative overflow-hidden {{ request('jenis_layanan') === 'NUPTK' ? 'border-blue-500 ring-2 ring-blue-400/30' : 'border-slate-100 hover:border-blue-300' }}">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 group-hover:bg-blue-100 text-blue-600 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                </div>
                <span class="text-2xl font-black text-[#00264A]">{{ number_format($stats['nuptk'], 0, ',', '.') }}</span>
            </div>
            <h3 class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition-colors">NUPTK</h3>
            <p class="text-[11px] text-slate-500 mt-1 line-clamp-2">Penerbitan, Verval, & Perbaikan Data Nomor Unik PTK</p>
        </a>

        <!-- 3. Tunjangan Pendidik -->
        <a href="{{ route('pendidikan.gtk.layanan.index', ['jenis_layanan' => 'Tunjangan Pendidik']) }}" 
           class="group bg-white p-5 rounded-2xl border transition-all duration-200 hover:shadow-lg relative overflow-hidden {{ request('jenis_layanan') === 'Tunjangan Pendidik' ? 'border-emerald-500 ring-2 ring-emerald-400/30' : 'border-slate-100 hover:border-emerald-300' }}">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 group-hover:bg-emerald-100 text-emerald-600 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-2xl font-black text-[#00264A]">{{ number_format($stats['tunjangan'], 0, ',', '.') }}</span>
            </div>
            <h3 class="text-sm font-bold text-slate-800 group-hover:text-emerald-600 transition-colors">Tunjangan Pendidik</h3>
            <p class="text-[11px] text-slate-500 mt-1 line-clamp-2">TPG (Profesi), TKG (Khusus 3T), & Tambahan Penghasilan</p>
        </a>

        <!-- 4. SIMPKB / PKB -->
        <a href="{{ route('pendidikan.gtk.layanan.index', ['jenis_layanan' => 'SIMPKB / PKB']) }}" 
           class="group bg-white p-5 rounded-2xl border transition-all duration-200 hover:shadow-lg relative overflow-hidden {{ request('jenis_layanan') === 'SIMPKB / PKB' ? 'border-purple-500 ring-2 ring-purple-400/30' : 'border-slate-100 hover:border-purple-300' }}">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-purple-50 group-hover:bg-purple-100 text-purple-600 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <span class="text-2xl font-black text-[#00264A]">{{ number_format($stats['simpkb'], 0, ',', '.') }}</span>
            </div>
            <h3 class="text-sm font-bold text-slate-800 group-hover:text-purple-600 transition-colors">SIMPKB / PKB</h3>
            <p class="text-[11px] text-slate-500 mt-1 line-clamp-2">Akun Komunitas, Guru Penggerak, PPG Daljab & PKB</p>
        </a>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('pendidikan.gtk.layanan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
            <!-- Search Input -->
            <div class="lg:col-span-2">
                <label class="block text-xs font-bold text-slate-600 mb-1">Cari Dokumen / GTK</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="No Reg, Nama Guru, NUPTK, NIP, Satker..."
                           class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <!-- Jenis Layanan -->
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Jenis Layanan</label>
                <select name="jenis_layanan" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                    <option value="">-- Semua Layanan --</option>
                    @foreach($jenisLayananOptions as $key => $meta)
                        <option value="{{ $key }}" {{ request('jenis_layanan') == $key ? 'selected' : '' }}>{{ $key }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Kabupaten / Kota -->
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Kabupaten / Kota</label>
                <select name="kabupaten_kota" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                    <option value="">-- Semua Wilayah --</option>
                    @foreach($kabupatenKotaList as $kab)
                        <option value="{{ $kab }}" {{ request('kabupaten_kota') == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter & Submit -->
            <div class="flex items-center gap-2">
                <div class="flex-1">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Status</label>
                    <select name="status_layanan" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                        <option value="">-- Semua --</option>
                        @foreach($statusOptions as $k => $st)
                            <option value="{{ $k }}" {{ request('status_layanan') == $k ? 'selected' : '' }}>{{ $st['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-transparent mb-1">Aksi</label>
                    <div class="flex items-center gap-1">
                        <button type="submit" class="px-3.5 py-2 bg-[#00264A] hover:bg-[#003B70] text-white text-xs font-bold rounded-xl shadow transition-colors">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'jenis_layanan', 'kabupaten_kota', 'status_layanan']))
                            <a href="{{ route('pendidikan.gtk.layanan.index') }}" class="px-2.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition-colors" title="Reset Filter">
                                ↺
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <h3 class="text-sm font-bold text-[#00264A]">Daftar Registrasi Dokumen Layanan GTK</h3>
                <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 font-semibold">
                    {{ $layananList->total() }} Data
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-600 font-semibold tracking-wider uppercase text-[10px]">
                        <th class="py-3.5 px-4">No. Registrasi & Tanggal</th>
                        <th class="py-3.5 px-4">Jenis Layanan</th>
                        <th class="py-3.5 px-4">Data Guru / PTK</th>
                        <th class="py-3.5 px-4">Sekolah / Wilayah</th>
                        <th class="py-3.5 px-4">Status & Keterangan</th>
                        <th class="py-3.5 px-4">Dokumen</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($layananList as $item)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <!-- No Registrasi & Tanggal -->
                            <td class="py-3 px-4">
                                <span class="font-mono font-bold text-[#00264A] text-xs block">{{ $item->nomor_registrasi }}</span>
                                <span class="text-[10px] text-slate-400 mt-0.5 block">Tgl: {{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d/m/Y') : $item->created_at->format('d/m/Y') }}</span>
                            </td>

                            <!-- Jenis Layanan -->
                            <td class="py-3 px-4">
                                @php
                                    $meta = $item->layanan_meta;
                                @endphp
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-bold border {{ $meta['badge'] }} inline-block">
                                    {{ $item->jenis_layanan }}
                                </span>
                            </td>

                            <!-- Data Guru -->
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-800 text-xs">{{ $item->nama_lengkap_guru }}</div>
                                <div class="text-[10px] text-slate-500 mt-0.5 space-x-1">
                                    <span>NIP/NIK: <strong>{{ $item->nip_nik ?? '-' }}</strong></span>
                                    @if($item->nuptk)
                                        <span>• NUPTK: <span class="font-mono font-semibold">{{ $item->nuptk }}</span></span>
                                    @endif
                                </div>
                                <div class="text-[10px] text-slate-400 mt-0.5">
                                    Status: <span class="font-semibold text-slate-600">{{ $item->status_kepegawaian }}</span>
                                </div>
                            </td>

                            <!-- Sekolah & Wilayah -->
                            <td class="py-3 px-4">
                                <div class="font-semibold text-slate-700 text-xs">{{ $item->tempat_tugas_sekolah }}</div>
                                <div class="text-[10px] text-slate-500 mt-0.5">
                                    {{ $item->jenjang_sekolah }} &bull; {{ $item->kabupaten_kota }}
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="py-3 px-4">
                                @php
                                    $st = $item->status_meta;
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $st['bg'] }} {{ $st['text'] }} {{ $st['border'] }} inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $item->status_layanan === 'SELESAI' ? 'bg-emerald-500' : ($item->status_layanan === 'DITOLAK' ? 'bg-rose-500' : 'bg-amber-500') }}"></span>
                                    {{ $st['label'] }}
                                </span>
                                @if($item->catatan_verifikator)
                                    <p class="text-[10px] text-slate-500 mt-1 line-clamp-1 italic max-w-xs">
                                        "{{ $item->catatan_verifikator }}"
                                    </p>
                                @endif
                            </td>

                            <!-- Dokumen Links -->
                            <td class="py-3 px-4">
                                <div class="flex flex-col space-y-1">
                                    @if($item->file_permohonan)
                                        <a href="{{ route('pendidikan.gtk.layanan.download', ['id' => $item->id, 'type' => 'permohonan']) }}" 
                                           class="inline-flex items-center space-x-1 text-[11px] text-blue-600 hover:text-blue-800 font-semibold">
                                            <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <span>Berkas Syarat</span>
                                        </a>
                                    @else
                                        <span class="text-[10px] text-slate-400 italic">- Syarat Kosong -</span>
                                    @endif

                                    @if($item->file_hasil_sk)
                                        <a href="{{ route('pendidikan.gtk.layanan.download', ['id' => $item->id, 'type' => 'sk']) }}" 
                                           class="inline-flex items-center space-x-1 text-[11px] text-emerald-600 hover:text-emerald-800 font-bold">
                                            <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span>SK / Hasil</span>
                                        </a>
                                    @endif
                                </div>
                            </td>

                            <!-- Aksi -->
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center space-x-1">
                                    <a href="{{ route('pendidikan.gtk.layanan.show', $item->id) }}" 
                                       class="p-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition-colors" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('pendidikan.gtk.layanan.edit', $item->id) }}" 
                                       class="p-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg transition-colors" title="Edit / Update Status">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('pendidikan.gtk.layanan.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data permohonan GTK {{ $item->nomor_registrasi }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg transition-colors" title="Hapus Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="text-sm font-semibold">Belum ada data dokumen layanan GTK</p>
                                    <p class="text-xs text-slate-400">Silakan buat permohonan baru atau sesuaikan filter pencarian.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($layananList->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $layananList->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

