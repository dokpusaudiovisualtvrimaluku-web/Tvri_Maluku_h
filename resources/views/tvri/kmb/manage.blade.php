@extends('layouts.app')

@section('title', 'Input Data & SOP Konten Media Baru - TVRI Maluku')

@section('content')
<div class="space-y-6">

    <!-- ===== HEADER MANAGE KONTEN MEDIA BARU ===== -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#001D38] via-[#00264A] to-[#003B70] text-white shadow-2xl p-6 md:p-8 border border-pink-400/20">
        <div class="absolute -right-12 -top-16 w-80 h-80 bg-pink-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-12 -bottom-16 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center space-x-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-xs font-semibold text-pink-200 border border-white/15 mb-3">
                    <span class="w-2 h-2 rounded-full bg-pink-400 animate-pulse"></span>
                    <span>Modul Input Data & SOP Konten Media Baru (KMB)</span>
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Kelola Data Konten Media Baru & Digital</h1>
                <p class="text-sm text-blue-100/90 mt-1">Pengelolaan Konten Digital, Analitik Platform, SOP Media Baru, dan Sinkronisasi Dashboard</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('tvri.mbn.dashboard') }}"
                   class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold backdrop-blur-md border border-white/20 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Kembali ke Dashboard KMB</span>
                </a>
                <form action="{{ route('tvri.mbn.reseed') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memulihkan / menyinkronkan seluruh data Konten Media Baru sesuai data spesifikasi?')">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-pink-600 hover:bg-pink-700 text-white text-xs font-semibold transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Pulihkan / Sinkronkan Data</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Alert Success / Error -->
    @if(session('success'))
    <div class="flex items-center p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
        <svg class="w-5 h-5 mr-3 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-center p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
        <svg class="w-5 h-5 mr-3 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Navigation Tabs -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-2">
        <div class="flex flex-wrap gap-2">
            @php
                $activeTab = request('tab', $tab ?? 'konten');
            @endphp
            <a href="{{ route('tvri.mbn.manage', ['tab' => 'konten']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $activeTab === 'konten' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                <span>Konten Digital ({{ $kontens->count() }})</span>
            </a>
            <a href="{{ route('tvri.mbn.manage', ['tab' => 'sop']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $activeTab === 'sop' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <span>SOP & Regulasi KMB ({{ $sops->count() }})</span>
            </a>
            <a href="{{ route('tvri.mbn.manage', ['tab' => 'ringkasan']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $activeTab === 'ringkasan' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                <span>Parameter & Analitik Dashboard</span>
            </a>
        </div>
    </div>

    <!-- TAB 1: KONTEN DIGITAL -->
    @if($activeTab === 'konten')
    <div class="space-y-6">
        <!-- Form Tambah Konten -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-pink-500"></span>
                <span>Tambah Konten Digital Baru</span>
            </h3>
            <form action="{{ route('tvri.mbn.konten.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Judul Konten</label>
                    <input type="text" name="judul" placeholder="Contoh: Pesona Raja Ampat dari Maluku" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Platform</label>
                    <select name="platform" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="YouTube">YouTube</option>
                        <option value="Instagram">Instagram</option>
                        <option value="TikTok">TikTok</option>
                        <option value="Website">Website</option>
                        <option value="Facebook">Facebook</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kategori</label>
                    <select name="kategori" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Berita">Berita</option>
                        <option value="Budaya">Budaya</option>
                        <option value="Pariwisata">Pariwisata</option>
                        <option value="Pendidikan">Pendidikan</option>
                        <option value="UMKM">UMKM</option>
                        <option value="Talkshow">Talkshow</option>
                        <option value="Program Khusus">Program Khusus</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Views (Tayangan)</label>
                    <input type="number" name="views" placeholder="328000" min="0" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Reach (Jangkauan)</label>
                    <input type="number" name="reach" placeholder="290000" min="0" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Engagement (Interaksi)</label>
                    <input type="number" name="engagement" placeholder="24500" min="0" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Watch Time (Jam)</label>
                    <input type="number" name="watch_time" placeholder="38000" min="0" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Terbit</label>
                    <input type="date" name="tanggal" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status Publikasi</label>
                    <select name="status" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Published">Published</option>
                        <option value="Scheduled">Scheduled</option>
                        <option value="Editing">Editing</option>
                        <option value="Draft">Draft</option>
                        <option value="Planning">Planning</option>
                    </select>
                </div>
                <div class="md:col-span-2 flex items-end">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-pink-600 hover:bg-pink-700 text-white text-xs font-bold shadow-md transition-all">
                        Simpan Konten Digital
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Konten -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Daftar Konten Digital Terdata</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Total {{ $kontens->count() }} konten dipublikasikan & dijadwalkan</p>
                </div>
                <form id="formBulkKonten" action="{{ route('tvri.mbn.konten.bulk-destroy') }}" method="POST" onsubmit="return confirm('Hapus konten yang dipilih?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="btnBulkKonten" disabled class="px-3 py-1.5 rounded-lg bg-rose-600 text-white text-xs font-semibold disabled:opacity-40 disabled:cursor-not-allowed">
                        Hapus Terpilih
                    </button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <tr>
                            <th class="p-3.5 w-10 text-center">
                                <input type="checkbox" id="checkAllKonten" class="rounded border-slate-300">
                            </th>
                            <th class="p-3.5">Judul Konten</th>
                            <th class="p-3.5">Platform</th>
                            <th class="p-3.5">Kategori</th>
                            <th class="p-3.5 text-right">Views</th>
                            <th class="p-3.5 text-right">Reach</th>
                            <th class="p-3.5 text-right">Engagement</th>
                            <th class="p-3.5 text-center">Status</th>
                            <th class="p-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kontens as $k)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-3.5 text-center">
                                <input type="checkbox" name="ids[]" value="{{ $k->id }}" form="formBulkKonten" class="check-item-konten rounded border-slate-300">
                            </td>
                            <td class="p-3.5 font-bold text-slate-900 max-w-xs truncate">{{ $k->judul }}</td>
                            <td class="p-3.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                    {{ $k->platform === 'YouTube' ? 'bg-red-50 text-red-600 border border-red-200' :
                                       ($k->platform === 'Instagram' ? 'bg-pink-50 text-pink-600 border border-pink-200' :
                                       ($k->platform === 'TikTok' ? 'bg-slate-100 text-slate-800 border border-slate-300' : 'bg-blue-50 text-blue-600 border border-blue-200')) }}">
                                    {{ $k->platform }}
                                </span>
                            </td>
                            <td class="p-3.5 text-slate-600">{{ $k->kategori }}</td>
                            <td class="p-3.5 text-right font-mono font-bold text-[#005B9F]">{{ number_format($k->views) }}</td>
                            <td class="p-3.5 text-right font-mono">{{ number_format($k->reach ?? 0) }}</td>
                            <td class="p-3.5 text-right font-mono text-emerald-600">{{ number_format($k->engagement ?? 0) }}</td>
                            <td class="p-3.5 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $k->status === 'Published' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $k->status }}
                                </span>
                            </td>
                            <td class="p-3.5 text-right">
                                <div class="inline-flex items-center space-x-1.5">
                                    <button type="button" onclick="editKonten({{ json_encode($k) }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form action="{{ route('tvri.mbn.konten.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus konten ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-slate-400">Belum ada konten digital terdata.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- TAB 2: SOP & REGULASI KMB -->
    @if($activeTab === 'sop')
    <div class="space-y-6">
        <!-- Form Tambah SOP -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                <span>Tambah Dokumen SOP Konten Media Baru</span>
            </h3>
            <form action="{{ route('tvri.mbn.sop.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kode SOP</label>
                    <input type="text" name="kode" placeholder="Contoh: SOP-KMB-06" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Judul SOP</label>
                    <input type="text" name="judul" placeholder="Nama lengkap SOP / Prosedur Digital" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kategori</label>
                    <input type="text" name="kategori" placeholder="Contoh: Produksi Digital" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Versi</label>
                    <input type="text" name="versi" value="v1.0" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Efektif</label>
                    <input type="date" name="tanggal_efektif" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                    <select name="status" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Aktif">Aktif</option>
                        <option value="Revisi">Dalam Revisi</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Urutan</label>
                    <input type="number" name="urutan" value="1" min="1" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-4">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Keterangan / Deskripsi Alur SOP</label>
                    <textarea name="keterangan" rows="2" placeholder="Uraian prosedur, resolusi, format berkas, serta langkah kerja tim..." class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="md:col-span-4 flex items-end">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md transition-all">
                        Simpan SOP KMB
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel SOP -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Daftar Dokumen SOP & Regulasi Konten Media Baru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Total {{ $sops->count() }} SOP terintegrasi</p>
                </div>
                <form id="formBulkSop" action="{{ route('tvri.mbn.sop.bulk-destroy') }}" method="POST" onsubmit="return confirm('Hapus SOP terpilih?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="btnBulkSop" disabled class="px-3 py-1.5 rounded-lg bg-rose-600 text-white text-xs font-semibold disabled:opacity-40 disabled:cursor-not-allowed">
                        Hapus Terpilih
                    </button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <tr>
                            <th class="p-3.5 w-10 text-center">
                                <input type="checkbox" id="checkAllSop" class="rounded border-slate-300">
                            </th>
                            <th class="p-3.5">Kode</th>
                            <th class="p-3.5">Judul SOP</th>
                            <th class="p-3.5">Kategori</th>
                            <th class="p-3.5 text-center">Versi</th>
                            <th class="p-3.5">Tanggal</th>
                            <th class="p-3.5 text-center">Status</th>
                            <th class="p-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($sops as $sop)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-3.5 text-center">
                                <input type="checkbox" name="ids[]" value="{{ $sop->id }}" form="formBulkSop" class="check-item-sop rounded border-slate-300">
                            </td>
                            <td class="p-3.5 font-mono font-bold text-pink-600">{{ $sop->kode }}</td>
                            <td class="p-3.5">
                                <p class="font-bold text-slate-900">{{ $sop->judul }}</p>
                                @if($sop->keterangan)
                                <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-1">{{ $sop->keterangan }}</p>
                                @endif
                            </td>
                            <td class="p-3.5">{{ $sop->kategori }}</td>
                            <td class="p-3.5 text-center font-mono">{{ $sop->versi }}</td>
                            <td class="p-3.5 font-mono">{{ $sop->tanggal_efektif ? \Carbon\Carbon::parse($sop->tanggal_efektif)->format('d M Y') : '-' }}</td>
                            <td class="p-3.5 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $sop->status === 'Aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $sop->status }}
                                </span>
                            </td>
                            <td class="p-3.5 text-right">
                                <div class="inline-flex items-center space-x-1.5">
                                    @php $fileUrl = $sop->file_url ?: $sop->file_path; @endphp
@if($fileUrl)
<a href="{{ asset($fileUrl) }}" target="_blank" class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 mr-1.5 transition" title="Lihat Fisik SOP">
    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
    <span>Fisik SOP</span>
</a>
@endif
<button type="button" onclick="editSop({{ json_encode($sop) }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form action="{{ route('tvri.mbn.sop.destroy', $sop->id) }}" method="POST" onsubmit="return confirm('Hapus SOP ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400">Belum ada dokumen SOP terdata.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- TAB 3: PARAMETER & ANALITIK DASHBOARD KMB -->
    @if($activeTab === 'ringkasan')
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
        <div class="border-b border-slate-200 pb-4 mb-6">
            <h3 class="text-lg font-black text-slate-800">Sinkronisasi Parameter & Metrik Konten Media Baru</h3>
            <p class="text-xs text-slate-500 mt-1">Data form ini tersinkronisasi otomatis dengan Dashboard KMB dan Executive Command Center (ECC) Kepala Stasiun.</p>
        </div>

        <form action="{{ route('tvri.mbn.ringkasan.update') }}" method="POST" class="space-y-8">
            @csrf

            <!-- Identitas & Tagline -->
            <div>
                <h4 class="text-xs font-black uppercase tracking-wider text-pink-600 mb-3">1. Identitas & Slogan</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Judul Dashboard</label>
                        <input type="text" name="judul" value="{{ old('judul', $ringkasan->judul) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Tagline</label>
                        <input type="text" name="tagline" value="{{ old('tagline', $ringkasan->tagline) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Pilar</label>
                        <input type="text" name="pilar" value="{{ old('pilar', $ringkasan->pilar) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Slogan</label>
                        <input type="text" name="slogan" value="{{ old('slogan', $ringkasan->slogan) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Waktu & Status</label>
                        <input type="text" name="waktu_status" value="{{ old('waktu_status', $ringkasan->waktu_status) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Periode</label>
                        <input type="text" name="periode" value="{{ old('periode', $ringkasan->periode) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Tagline Peta</label>
                        <input type="text" name="tagline_peta" value="{{ old('tagline_peta', $ringkasan->tagline_peta) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Quote Penutup</label>
                        <input type="text" name="quote_penutup" value="{{ old('quote_penutup', $ringkasan->quote_penutup) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                </div>
            </div>

            <!-- Indikator Kinerja Utama (KPI) -->
            <div>
                <h4 class="text-xs font-black uppercase tracking-wider text-pink-600 mb-3">2. Indikator Kinerja Utama (KPI)</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Total Konten</label>
                        <input type="number" name="total_konten" value="{{ old('total_konten', $ringkasan->total_konten) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Diff Konten</label>
                        <input type="text" name="diff_total_konten" value="{{ old('diff_total_konten', $ringkasan->diff_total_konten) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Total Views</label>
                        <input type="number" name="total_views" value="{{ old('total_views', $ringkasan->total_views) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Diff Views</label>
                        <input type="text" name="diff_total_views" value="{{ old('diff_total_views', $ringkasan->diff_total_views) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Total Reach</label>
                        <input type="number" name="total_reach" value="{{ old('total_reach', $ringkasan->total_reach) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Engagement</label>
                        <input type="number" name="engagement" value="{{ old('engagement', $ringkasan->engagement) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Watch Time (Jam)</label>
                        <input type="number" name="watch_time" value="{{ old('watch_time', $ringkasan->watch_time) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Follower Growth</label>
                        <input type="number" name="follower_growth" value="{{ old('follower_growth', $ringkasan->follower_growth) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                </div>
            </div>

            <!-- Performa Per Platform -->
            <div>
                <h4 class="text-xs font-black uppercase tracking-wider text-pink-600 mb-3">3. Performa Per Platform</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- YouTube -->
                    <div class="p-4 rounded-xl border border-red-200 bg-red-50/40">
                        <p class="text-xs font-bold text-red-800 mb-2">YouTube</p>
                        <label class="block text-[11px] text-slate-500">Subscribers</label>
                        <input type="text" name="yt_subs" value="{{ old('yt_subs', $ringkasan->yt_subs) }}" class="w-full text-xs rounded-xl border-slate-300 mb-2">
                        <label class="block text-[11px] text-slate-500">Views</label>
                        <input type="text" name="yt_views" value="{{ old('yt_views', $ringkasan->yt_views) }}" class="w-full text-xs rounded-xl border-slate-300 mb-2">
                        <label class="block text-[11px] text-slate-500">Engagement</label>
                        <input type="text" name="yt_eng" value="{{ old('yt_eng', $ringkasan->yt_eng) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>

                    <!-- Instagram -->
                    <div class="p-4 rounded-xl border border-pink-200 bg-pink-50/40">
                        <p class="text-xs font-bold text-pink-800 mb-2">Instagram</p>
                        <label class="block text-[11px] text-slate-500">Followers</label>
                        <input type="text" name="ig_followers" value="{{ old('ig_followers', $ringkasan->ig_followers) }}" class="w-full text-xs rounded-xl border-slate-300 mb-2">
                        <label class="block text-[11px] text-slate-500">Reach</label>
                        <input type="text" name="ig_reach" value="{{ old('ig_reach', $ringkasan->ig_reach) }}" class="w-full text-xs rounded-xl border-slate-300 mb-2">
                        <label class="block text-[11px] text-slate-500">Engagement</label>
                        <input type="text" name="ig_eng" value="{{ old('ig_eng', $ringkasan->ig_eng) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>

                    <!-- TikTok -->
                    <div class="p-4 rounded-xl border border-slate-300 bg-slate-50">
                        <p class="text-xs font-bold text-slate-800 mb-2">TikTok</p>
                        <label class="block text-[11px] text-slate-500">Followers</label>
                        <input type="text" name="tiktok_followers" value="{{ old('tiktok_followers', $ringkasan->tiktok_followers) }}" class="w-full text-xs rounded-xl border-slate-300 mb-2">
                        <label class="block text-[11px] text-slate-500">Views</label>
                        <input type="text" name="tiktok_views" value="{{ old('tiktok_views', $ringkasan->tiktok_views) }}" class="w-full text-xs rounded-xl border-slate-300 mb-2">
                        <label class="block text-[11px] text-slate-500">Engagement</label>
                        <input type="text" name="tiktok_eng" value="{{ old('tiktok_eng', $ringkasan->tiktok_eng) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>

                    <!-- Website -->
                    <div class="p-4 rounded-xl border border-blue-200 bg-blue-50/40">
                        <p class="text-xs font-bold text-blue-800 mb-2">Website</p>
                        <label class="block text-[11px] text-slate-500">Visitors</label>
                        <input type="text" name="web_visitors" value="{{ old('web_visitors', $ringkasan->web_visitors) }}" class="w-full text-xs rounded-xl border-slate-300 mb-2">
                        <label class="block text-[11px] text-slate-500">Page Views</label>
                        <input type="text" name="web_views" value="{{ old('web_views', $ringkasan->web_views) }}" class="w-full text-xs rounded-xl border-slate-300 mb-2">
                        <label class="block text-[11px] text-slate-500">Avg. Duration</label>
                        <input type="text" name="web_avg_duration" value="{{ old('web_avg_duration', $ringkasan->web_avg_duration) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                </div>
            </div>

            <!-- Target & Capaian Bulan Ini -->
            <div>
                <h4 class="text-xs font-black uppercase tracking-wider text-pink-600 mb-3">4. Target & Capaian Bulan Ini</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Target Views</label>
                        <input type="number" name="target_views" value="{{ old('target_views', $ringkasan->target_views) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Target Engagement</label>
                        <input type="number" name="target_engagement" value="{{ old('target_engagement', $ringkasan->target_engagement) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Target Follower Growth</label>
                        <input type="number" name="target_follower_growth" value="{{ old('target_follower_growth', $ringkasan->target_follower_growth) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Target Konten</label>
                        <input type="number" name="target_konten" value="{{ old('target_konten', $ringkasan->target_konten) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                </div>
            </div>

            <!-- Demografi Audiens & Peta Sebaran -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-pink-600 mb-3">5. Demografi Usia Audiens (%)</h4>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">13-17 (%)</label>
                            <input type="number" step="0.1" name="usia_13_17" value="{{ old('usia_13_17', $ringkasan->usia_13_17) }}" class="w-full text-xs rounded-xl border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">18-24 (%)</label>
                            <input type="number" step="0.1" name="usia_18_24" value="{{ old('usia_18_24', $ringkasan->usia_18_24) }}" class="w-full text-xs rounded-xl border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">25-34 (%)</label>
                            <input type="number" step="0.1" name="usia_25_34" value="{{ old('usia_25_34', $ringkasan->usia_25_34) }}" class="w-full text-xs rounded-xl border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">35-44 (%)</label>
                            <input type="number" step="0.1" name="usia_35_44" value="{{ old('usia_35_44', $ringkasan->usia_35_44) }}" class="w-full text-xs rounded-xl border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">45-54 (%)</label>
                            <input type="number" step="0.1" name="usia_45_54" value="{{ old('usia_45_54', $ringkasan->usia_45_54) }}" class="w-full text-xs rounded-xl border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">55+ (%)</label>
                            <input type="number" step="0.1" name="usia_55_plus" value="{{ old('usia_55_plus', $ringkasan->usia_55_plus) }}" class="w-full text-xs rounded-xl border-slate-300">
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-pink-600 mb-3">6. Peta Sebaran Audiens (%)</h4>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Maluku (%)</label>
                            <input type="number" step="0.1" name="aud_maluku" value="{{ old('aud_maluku', $ringkasan->aud_maluku) }}" class="w-full text-xs rounded-xl border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Maluku Utara (%)</label>
                            <input type="number" step="0.1" name="aud_malut" value="{{ old('aud_malut', $ringkasan->aud_malut) }}" class="w-full text-xs rounded-xl border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Jawa (%)</label>
                            <input type="number" step="0.1" name="aud_jawa" value="{{ old('aud_jawa', $ringkasan->aud_jawa) }}" class="w-full text-xs rounded-xl border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Sulawesi (%)</label>
                            <input type="number" step="0.1" name="aud_sulawesi" value="{{ old('aud_sulawesi', $ringkasan->aud_sulawesi) }}" class="w-full text-xs rounded-xl border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Papua (%)</label>
                            <input type="number" step="0.1" name="aud_papua" value="{{ old('aud_papua', $ringkasan->aud_papua) }}" class="w-full text-xs rounded-xl border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Nusa Tenggara (%)</label>
                            <input type="number" step="0.1" name="aud_nusa" value="{{ old('aud_nusa', $ringkasan->aud_nusa) }}" class="w-full text-xs rounded-xl border-slate-300">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Points -->
            <div>
                <h4 class="text-xs font-black uppercase tracking-wider text-pink-600 mb-3">7. Poin Pesan Footer (1 baris per poin)</h4>
                <textarea name="footer_points_text" rows="5" class="w-full text-xs rounded-xl border-slate-300 font-mono">{{ is_array($ringkasan->footer_points_json) ? implode("\n", $ringkasan->footer_points_json) : $ringkasan->footer_points_json }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-200 flex items-center justify-end space-x-3">
                <a href="{{ route('tvri.mbn.dashboard') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-pink-600 hover:bg-pink-700 text-white text-xs font-bold shadow-lg transition-all">
                    Simpan Semua Parameter
                </button>
            </div>
        </form>
    </div>
    @endif

</div>

<!-- Modal Edit Konten -->
<div id="modalEditKonten" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200">
        <h3 class="text-base font-bold text-slate-800 mb-4">Edit Konten Digital</h3>
        <form id="formEditKonten" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Judul Konten</label>
                <input type="text" id="editKontenJudul" name="judul" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Platform</label>
                    <select id="editKontenPlatform" name="platform" class="w-full text-xs rounded-xl border-slate-300">
                        <option value="YouTube">YouTube</option>
                        <option value="Instagram">Instagram</option>
                        <option value="TikTok">TikTok</option>
                        <option value="Website">Website</option>
                        <option value="Facebook">Facebook</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kategori</label>
                    <select id="editKontenKategori" name="kategori" class="w-full text-xs rounded-xl border-slate-300">
                        <option value="Berita">Berita</option>
                        <option value="Budaya">Budaya</option>
                        <option value="Pariwisata">Pariwisata</option>
                        <option value="Pendidikan">Pendidikan</option>
                        <option value="UMKM">UMKM</option>
                        <option value="Talkshow">Talkshow</option>
                        <option value="Program Khusus">Program Khusus</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Views</label>
                    <input type="number" id="editKontenViews" name="views" required class="w-full text-xs rounded-xl border-slate-300">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Reach</label>
                    <input type="number" id="editKontenReach" name="reach" class="w-full text-xs rounded-xl border-slate-300">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Engagement</label>
                    <input type="number" id="editKontenEngagement" name="engagement" class="w-full text-xs rounded-xl border-slate-300">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal</label>
                    <input type="date" id="editKontenTanggal" name="tanggal" class="w-full text-xs rounded-xl border-slate-300">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                    <select id="editKontenStatus" name="status" class="w-full text-xs rounded-xl border-slate-300">
                        <option value="Published">Published</option>
                        <option value="Scheduled">Scheduled</option>
                        <option value="Editing">Editing</option>
                        <option value="Draft">Draft</option>
                        <option value="Planning">Planning</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2">
                <button type="button" onclick="closeModal('modalEditKonten')" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-pink-600 text-white text-xs font-bold hover:bg-pink-700">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit SOP -->
<div id="modalEditSop" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200">
        <h3 class="text-base font-bold text-slate-800 mb-4">Edit Dokumen SOP KMB</h3>
        <form id="formEditSop" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kode SOP</label>
                    <input type="text" id="editSopKode" name="kode" required class="w-full text-xs rounded-xl border-slate-300">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Versi</label>
                    <input type="text" id="editSopVersi" name="versi" required class="w-full text-xs rounded-xl border-slate-300">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                    <select id="editSopStatus" name="status" class="w-full text-xs rounded-xl border-slate-300">
                        <option value="Aktif">Aktif</option>
                        <option value="Revisi">Dalam Revisi</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Judul SOP</label>
                <input type="text" id="editSopJudul" name="judul" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kategori</label>
                    <input type="text" id="editSopKategori" name="kategori" required class="w-full text-xs rounded-xl border-slate-300">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Efektif</label>
                    <input type="date" id="editSopTanggal" name="tanggal_efektif" class="w-full text-xs rounded-xl border-slate-300">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Keterangan / Alur Prosedur</label>
                            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Ganti File Fisik SOP (Opsional)</label>
                <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg" class="w-full text-xs rounded-xl border border-slate-300 p-2 bg-slate-50/50 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#005B9F] file:text-white cursor-pointer">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Keterangan</label>
                <textarea id="editSopKeterangan" name="keterangan" rows="3" class="w-full text-xs rounded-xl border-slate-300"></textarea>
            </div>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2">
                <button type="button" onclick="closeModal('modalEditSop')" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<script>
    function setupCheckAll(masterId, itemClass, btnId) {
        const master = document.getElementById(masterId);
        if (!master) return;
        const items = document.querySelectorAll('.' + itemClass);
        const btn = document.getElementById(btnId);

        master.addEventListener('change', function() {
            items.forEach(cb => cb.checked = master.checked);
            updateBtn();
        });

        items.forEach(cb => {
            cb.addEventListener('change', function() {
                master.checked = Array.from(items).every(i => i.checked);
                updateBtn();
            });
        });

        function updateBtn() {
            if (!btn) return;
            const anyChecked = Array.from(items).some(i => i.checked);
            btn.disabled = !anyChecked;
        }
    }

    setupCheckAll('checkAllKonten', 'check-item-konten', 'btnBulkKonten');
    setupCheckAll('checkAllSop', 'check-item-sop', 'btnBulkSop');

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function editKonten(k) {
        document.getElementById('formEditKonten').action = '/tvri/mbn/konten/' + k.id;
        document.getElementById('editKontenJudul').value = k.judul || '';
        document.getElementById('editKontenPlatform').value = k.platform || 'YouTube';
        document.getElementById('editKontenKategori').value = k.kategori || 'Berita';
        document.getElementById('editKontenViews').value = k.views || 0;
        document.getElementById('editKontenReach').value = k.reach || 0;
        document.getElementById('editKontenEngagement').value = k.engagement || 0;
        document.getElementById('editKontenTanggal').value = k.tanggal ? k.tanggal.substring(0, 10) : '';
        document.getElementById('editKontenStatus').value = k.status || 'Published';
        document.getElementById('modalEditKonten').classList.remove('hidden');
    }

    function editSop(s) {
        document.getElementById('formEditSop').action = '/tvri/mbn/sop/' + s.id;
        document.getElementById('editSopKode').value = s.kode || '';
        document.getElementById('editSopJudul').value = s.judul || '';
        document.getElementById('editSopKategori').value = s.kategori || '';
        document.getElementById('editSopVersi').value = s.versi || 'v1.0';
        document.getElementById('editSopTanggal').value = s.tanggal_efektif ? s.tanggal_efektif.substring(0, 10) : '';
        document.getElementById('editSopStatus').value = s.status || 'Aktif';
        document.getElementById('editSopKeterangan').value = s.keterangan || '';
        document.getElementById('modalEditSop').classList.remove('hidden');
    }
</script>
@endsection
