@extends('layouts.app')

@section('title', 'Input Data & SOP Pengembangan Usaha - TVRI Maluku')

@section('content')
<div class="space-y-6">

    <!-- ===== HEADER MANAGE PENGEMBANGAN USAHA ===== -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#001D38] via-[#00264A] to-[#003B70] text-white shadow-2xl p-6 md:p-8 border border-amber-400/20">
        <div class="absolute -right-12 -top-16 w-80 h-80 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-12 -bottom-16 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center space-x-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-xs font-semibold text-amber-200 border border-white/15 mb-3">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    <span>Modul Input Data & SOP Pengembangan Usaha</span>
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Kelola Data Pengembangan Usaha & PNBP</h1>
                <p class="text-sm text-blue-100/90 mt-1">Pengelolaan Mitra / Klien, Pipeline Kegiatan Usaha, PNBP, SOP Terpadu, dan Sinkronisasi Dashboard</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('tvri.usaha.dashboard') }}"
                   class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold backdrop-blur-md border border-white/20 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Kembali ke Dashboard Usaha</span>
                </a>
                <form action="{{ route('tvri.usaha.reseed') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyinkronkan seluruh data default Pengembangan Usaha sesuai file spesifikasi?')">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-amber-500/90 hover:bg-amber-600 text-white text-xs font-semibold transition-all shadow-sm">
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
                $activeTab = request('tab', $tab ?? 'mitra');
            @endphp
            <a href="{{ route('tvri.usaha.manage', ['tab' => 'mitra']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $activeTab === 'mitra' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>Mitra & Klien ({{ $mitras->count() }})</span>
            </a>
            <a href="{{ route('tvri.usaha.manage', ['tab' => 'kegiatan']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $activeTab === 'kegiatan' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Kegiatan Usaha ({{ $kegiatans->count() }})</span>
            </a>
            <a href="{{ route('tvri.usaha.manage', ['tab' => 'pnbp']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $activeTab === 'pnbp' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Sumber Pendapatan & PNBP ({{ $pnbps->count() }})</span>
            </a>
            <a href="{{ route('tvri.usaha.manage', ['tab' => 'sop']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $activeTab === 'sop' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <span>SOP & Regulasi ({{ $sops->count() }})</span>
            </a>
            <a href="{{ route('tvri.usaha.manage', ['tab' => 'ringkasan']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $activeTab === 'ringkasan' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                <span>Parameter & Strategi Usaha</span>
            </a>
        </div>
    </div>

    <!-- TAB 1: MITRA / KLIEN -->
    @if($activeTab === 'mitra')
    <div class="space-y-6">
        <!-- Card Form Tambah Mitra -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                <span>Tambah Mitra / Klien Baru</span>
            </h3>
            <form action="{{ route('tvri.usaha.mitra.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Mitra / Klien</label>
                    <input type="text" name="nama" placeholder="Contoh: Bank Maluku Malut" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Bentuk / Jenis Kerja Sama</label>
                    <input type="text" name="jenis_kerja_sama" placeholder="Contoh: Iklan & Sponsorship" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nilai Kontrak (Rp)</label>
                    <input type="number" name="nilai_kontrak" placeholder="600000000" min="0" step="1000" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status Kontrak</label>
                    <select name="status" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Aktif">Aktif</option>
                        <option value="Proses">Proses</option>
                        <option value="Negosiasi">Negosiasi</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Wilayah / Lokasi</label>
                    <select name="lokasi" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Ambon">Kota Ambon</option>
                        <option value="Tual">Kota Tual / Malra</option>
                        <option value="Saumlaki">Saumlaki (Kep. Tanimbar)</option>
                        <option value="Buru">Kabupaten Buru</option>
                        <option value="Seram">Seram (SBB / SBT / Malteng)</option>
                        <option value="Lainnya">Lainnya / Nasional</option>
                    </select>
                </div>
                <div class="md:col-span-3 flex items-end">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#005B9F] hover:bg-[#004a82] text-white text-xs font-bold shadow-md transition-all">
                        Simpan Data Mitra
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Mitra -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Daftar Mitra & Klien Pengembangan Usaha</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Total {{ $mitras->count() }} mitra terdata</p>
                </div>
                <form id="formBulkMitra" action="{{ route('tvri.usaha.mitra.bulk-destroy') }}" method="POST" onsubmit="return confirm('Hapus mitra yang dipilih?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="btnBulkMitra" disabled class="px-3 py-1.5 rounded-lg bg-rose-600 text-white text-xs font-semibold disabled:opacity-40 disabled:cursor-not-allowed">
                        Hapus Terpilih
                    </button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <tr>
                            <th class="p-3.5 w-10 text-center">
                                <input type="checkbox" id="checkAllMitra" class="rounded border-slate-300">
                            </th>
                            <th class="p-3.5">Nama Mitra</th>
                            <th class="p-3.5">Bentuk Kerja Sama</th>
                            <th class="p-3.5">Lokasi</th>
                            <th class="p-3.5 text-right">Nilai Kontrak</th>
                            <th class="p-3.5 text-center">Status</th>
                            <th class="p-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($mitras as $m)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-3.5 text-center">
                                <input type="checkbox" name="ids[]" value="{{ $m->id }}" form="formBulkMitra" class="check-item-mitra rounded border-slate-300">
                            </td>
                            <td class="p-3.5 font-bold text-slate-900">{{ $m->nama }}</td>
                            <td class="p-3.5">{{ $m->jenis_kerja_sama }}</td>
                            <td class="p-3.5">{{ $m->lokasi ?? 'Ambon' }}</td>
                            <td class="p-3.5 text-right font-mono font-bold text-[#005B9F]">Rp {{ number_format($m->nilai_kontrak, 0, ',', '.') }}</td>
                            <td class="p-3.5 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $m->status === 'Aktif' ? 'bg-emerald-100 text-emerald-700' : ($m->status === 'Proses' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700') }}">
                                    {{ $m->status }}
                                </span>
                            </td>
                            <td class="p-3.5 text-right">
                                <div class="inline-flex items-center space-x-1.5">
                                    <button type="button" onclick="editMitra({{ json_encode($m) }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form action="{{ route('tvri.usaha.mitra.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus data mitra ini?')">
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
                            <td colspan="7" class="p-8 text-center text-slate-400">Belum ada data mitra terdata.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- TAB 2: KEGIATAN USAHA -->
    @if($activeTab === 'kegiatan')
    <div class="space-y-6">
        <!-- Card Form Tambah Kegiatan Usaha -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span>Tambah Kegiatan Usaha Terdekat</span>
            </h3>
            <form action="{{ route('tvri.usaha.kegiatan.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Kegiatan</label>
                    <input type="text" name="kegiatan" placeholder="Contoh: Maluku Expo 2024" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Usaha</label>
                    <select name="jenis" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Event">Event</option>
                        <option value="Sponsorship">Sponsorship</option>
                        <option value="Iklan">Iklan</option>
                        <option value="Event & Aktivasi">Event & Aktivasi</option>
                        <option value="Iklan Layanan">Iklan Layanan</option>
                        <option value="Sewa Fasilitas">Sewa Fasilitas</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Estimasi Nilai (Rp)</label>
                    <input type="number" name="estimasi_nilai" placeholder="150000000" min="0" step="1000" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status Progres</label>
                    <select name="status" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Persiapan">Persiapan</option>
                        <option value="Kontrak">Kontrak</option>
                        <option value="Negosiasi">Negosiasi</option>
                        <option value="Berjalan">Berjalan</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                </div>
                <div class="md:col-span-3 flex items-end">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md transition-all">
                        Simpan Kegiatan Usaha
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Kegiatan Usaha -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Daftar Kegiatan Usaha Terdekat & Event</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Total {{ $kegiatans->count() }} kegiatan usaha terdata</p>
                </div>
                <form id="formBulkKegiatan" action="{{ route('tvri.usaha.kegiatan.bulk-destroy') }}" method="POST" onsubmit="return confirm('Hapus kegiatan terpilih?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="btnBulkKegiatan" disabled class="px-3 py-1.5 rounded-lg bg-rose-600 text-white text-xs font-semibold disabled:opacity-40 disabled:cursor-not-allowed">
                        Hapus Terpilih
                    </button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <tr>
                            <th class="p-3.5 w-10 text-center">
                                <input type="checkbox" id="checkAllKegiatan" class="rounded border-slate-300">
                            </th>
                            <th class="p-3.5">Tanggal</th>
                            <th class="p-3.5">Nama Kegiatan</th>
                            <th class="p-3.5">Jenis</th>
                            <th class="p-3.5 text-right">Est. Nilai (Rp)</th>
                            <th class="p-3.5 text-center">Status</th>
                            <th class="p-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kegiatans as $k)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-3.5 text-center">
                                <input type="checkbox" name="ids[]" value="{{ $k->id }}" form="formBulkKegiatan" class="check-item-kegiatan rounded border-slate-300">
                            </td>
                            <td class="p-3.5 font-mono text-slate-700">{{ $k->tanggal }}</td>
                            <td class="p-3.5 font-bold text-slate-900">{{ $k->kegiatan }}</td>
                            <td class="p-3.5">{{ $k->jenis }}</td>
                            <td class="p-3.5 text-right font-mono font-bold text-[#005B9F]">Rp {{ number_format($k->estimasi_nilai, 0, ',', '.') }}</td>
                            <td class="p-3.5 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $k->status === 'Kontrak' ? 'bg-emerald-100 text-emerald-700' : ($k->status === 'Persiapan' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') }}">
                                    {{ $k->status }}
                                </span>
                            </td>
                            <td class="p-3.5 text-right">
                                <div class="inline-flex items-center space-x-1.5">
                                    <button type="button" onclick="editKegiatan({{ json_encode($k) }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form action="{{ route('tvri.usaha.kegiatan.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus kegiatan ini?')">
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
                            <td colspan="7" class="p-8 text-center text-slate-400">Belum ada kegiatan usaha terdata.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- TAB 3: SUMBER PENDAPATAN & PNBP -->
    @if($activeTab === 'pnbp')
    <div class="space-y-6">
        <!-- Card Form Tambah PNBP -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                <span>Tambah Sumber Pendapatan / PNBP</span>
            </h3>
            <form action="{{ route('tvri.usaha.pnbp.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Sumber Pendapatan</label>
                    <input type="text" name="jenis" placeholder="Contoh: Iklan TV & Radio" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Target (Rp)</label>
                    <input type="number" name="target" placeholder="1200000000" min="0" step="1000" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Realisasi (Rp)</label>
                    <input type="number" name="realisasi" placeholder="1000000000" min="0" step="1000" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tahun Anggaran</label>
                    <input type="number" name="tahun" value="2024" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-4 flex items-end">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-md transition-all">
                        Simpan Sumber Pendapatan
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Sumber Pendapatan -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Top Sumber Pendapatan & PNBP</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Total {{ $pnbps->count() }} mata anggaran usaha</p>
                </div>
                <form id="formBulkPnbp" action="{{ route('tvri.usaha.pnbp.bulk-destroy') }}" method="POST" onsubmit="return confirm('Hapus data terpilih?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="btnBulkPnbp" disabled class="px-3 py-1.5 rounded-lg bg-rose-600 text-white text-xs font-semibold disabled:opacity-40 disabled:cursor-not-allowed">
                        Hapus Terpilih
                    </button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <tr>
                            <th class="p-3.5 w-10 text-center">
                                <input type="checkbox" id="checkAllPnbp" class="rounded border-slate-300">
                            </th>
                            <th class="p-3.5">Sumber Pendapatan</th>
                            <th class="p-3.5 text-right">Target (Rp)</th>
                            <th class="p-3.5 text-right">Realisasi (Rp)</th>
                            <th class="p-3.5 text-center">Capaian (%)</th>
                            <th class="p-3.5 text-center">Tahun</th>
                            <th class="p-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pnbps as $p)
                        @php
                            $pct = $p->target > 0 ? round($p->realisasi / $p->target * 100, 1) : 0;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-3.5 text-center">
                                <input type="checkbox" name="ids[]" value="{{ $p->id }}" form="formBulkPnbp" class="check-item-pnbp rounded border-slate-300">
                            </td>
                            <td class="p-3.5 font-bold text-slate-900">{{ $p->jenis }}</td>
                            <td class="p-3.5 text-right font-mono">Rp {{ number_format($p->target, 0, ',', '.') }}</td>
                            <td class="p-3.5 text-right font-mono font-bold text-[#005B9F]">Rp {{ number_format($p->realisasi, 0, ',', '.') }}</td>
                            <td class="p-3.5 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $pct >= 80 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $pct }}%
                                </span>
                            </td>
                            <td class="p-3.5 text-center font-mono">{{ $p->tahun ?? 2024 }}</td>
                            <td class="p-3.5 text-right">
                                <div class="inline-flex items-center space-x-1.5">
                                    <button type="button" onclick="editPnbp({{ json_encode($p) }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form action="{{ route('tvri.usaha.pnbp.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus data sumber pendapatan ini?')">
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
                            <td colspan="7" class="p-8 text-center text-slate-400">Belum ada data pendapatan / PNBP.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- TAB 4: SOP & REGULASI PENGEMBANGAN USAHA -->
    @if($activeTab === 'sop')
    <div class="space-y-6">
        <!-- Card Form Tambah SOP -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                <span>Tambah Dokumen SOP & Regulasi Usaha</span>
            </h3>
            <form action="{{ route('tvri.usaha.sop.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kode SOP</label>
                    <input type="text" name="kode" placeholder="Contoh: SOP-PU-06" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Judul SOP</label>
                    <input type="text" name="judul" placeholder="Nama lengkap SOP / Prosedur" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kategori</label>
                    <input type="text" name="kategori" placeholder="Contoh: Kemitraan / PNBP" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
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
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Urutan Tampil</label>
                    <input type="number" name="urutan" value="1" min="1" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-4">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Keterangan / Deskripsi Alur Prosedur</label>
                    <textarea name="keterangan" rows="2" placeholder="Uraian ringkas langkah kerja atau dasar hukum pelaksanaan..." class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="md:col-span-4 flex items-end">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md transition-all">
                        Simpan SOP Baru
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel SOP -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Daftar Dokumen SOP & Regulasi Usaha</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Total {{ $sops->count() }} SOP terintegrasi</p>
                </div>
                <form id="formBulkSop" action="{{ route('tvri.usaha.sop.bulk-destroy') }}" method="POST" onsubmit="return confirm('Hapus SOP terpilih?')">
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
                            <td class="p-3.5 font-mono font-bold text-[#005B9F]">{{ $sop->kode }}</td>
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
                                    <form action="{{ route('tvri.usaha.sop.destroy', $sop->id) }}" method="POST" onsubmit="return confirm('Hapus SOP ini?')">
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

    <!-- TAB 5: PARAMETER & STRATEGI PENGEMBANGAN USAHA -->
    @if($activeTab === 'ringkasan')
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
        <div class="border-b border-slate-200 pb-4 mb-6">
            <h3 class="text-lg font-black text-slate-800">Sinkronisasi Parameter & Strategi Pengembangan Usaha</h3>
            <p class="text-xs text-slate-500 mt-1">Seluruh perubahan pada form ini otomatis tersinkronkan dengan Dashboard Pengembangan Usaha dan Executive Command Center (ECC) Kepala Stasiun.</p>
        </div>

        <form action="{{ route('tvri.usaha.ringkasan.update') }}" method="POST" class="space-y-8">
            @csrf

            <!-- Identitas & Tagline -->
            <div>
                <h4 class="text-xs font-black uppercase tracking-wider text-[#005B9F] mb-3">1. Identitas, Slogan & Kutipan</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Judul Dashboard</label>
                        <input type="text" name="judul" value="{{ old('judul', $ringkasan->judul) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Sub Judul / Tema</label>
                        <input type="text" name="sub_judul" value="{{ old('sub_judul', $ringkasan->sub_judul) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Slogan</label>
                        <input type="text" name="slogan" value="{{ old('slogan', $ringkasan->slogan) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Pilar</label>
                        <input type="text" name="pilar" value="{{ old('pilar', $ringkasan->pilar) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Kutipan</label>
                        <input type="text" name="kutipan" value="{{ old('kutipan', $ringkasan->kutipan) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Waktu Update / Header</label>
                        <input type="text" name="waktu" value="{{ old('waktu', $ringkasan->waktu) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Tagline Peta</label>
                        <input type="text" name="tagline_peta" value="{{ old('tagline_peta', $ringkasan->tagline_peta) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nilai Dasar</label>
                        <input type="text" name="nilai_dasar" value="{{ old('nilai_dasar', $ringkasan->nilai_dasar) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Quote Penutup</label>
                        <input type="text" name="quote_penutup" value="{{ old('quote_penutup', $ringkasan->quote_penutup) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                </div>
            </div>

            <!-- Indikator Kinerja Utama -->
            <div>
                <h4 class="text-xs font-black uppercase tracking-wider text-[#005B9F] mb-3">2. Indikator Kinerja Utama (IKU)</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Total Pendapatan Realisasi (Rp)</label>
                        <input type="number" name="total_pendapatan" value="{{ old('total_pendapatan', $ringkasan->total_pendapatan) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Diff Pendapatan (Tren)</label>
                        <input type="text" name="diff_total_pendapatan" value="{{ old('diff_total_pendapatan', $ringkasan->diff_total_pendapatan) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Target Pendapatan (Rp)</label>
                        <input type="number" name="target_pendapatan" value="{{ old('target_pendapatan', $ringkasan->target_pendapatan) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Capaian Target (%)</label>
                        <input type="number" step="0.1" name="capaian_target" value="{{ old('capaian_target', $ringkasan->capaian_target) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Jumlah Kontrak Aktif</label>
                        <input type="number" name="jumlah_kontrak_aktif" value="{{ old('jumlah_kontrak_aktif', $ringkasan->jumlah_kontrak_aktif) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Diff Kontrak (Tren)</label>
                        <input type="text" name="diff_kontrak_aktif" value="{{ old('diff_kontrak_aktif', $ringkasan->diff_kontrak_aktif) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Jumlah Mitra / Klien</label>
                        <input type="number" name="jumlah_mitra" value="{{ old('jumlah_mitra', $ringkasan->jumlah_mitra) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Event / Kegiatan Usaha</label>
                        <input type="number" name="kegiatan_usaha" value="{{ old('kegiatan_usaha', $ringkasan->kegiatan_usaha) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                </div>
            </div>

            <!-- Komposisi Pendapatan -->
            <div>
                <h4 class="text-xs font-black uppercase tracking-wider text-[#005B9F] mb-3">3. Komposisi Pendapatan (%)</h4>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Iklan TV & Radio (%)</label>
                        <input type="number" step="0.1" name="komposisi_iklan_pct" value="{{ old('komposisi_iklan_pct', $ringkasan->komposisi_iklan_pct) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Sponsorship (%)</label>
                        <input type="number" step="0.1" name="komposisi_sponsorship_pct" value="{{ old('komposisi_sponsorship_pct', $ringkasan->komposisi_sponsorship_pct) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Event & Aktivasi (%)</label>
                        <input type="number" step="0.1" name="komposisi_event_pct" value="{{ old('komposisi_event_pct', $ringkasan->komposisi_event_pct) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Sewa Aset (%)</label>
                        <input type="number" step="0.1" name="komposisi_sewa_pct" value="{{ old('komposisi_sewa_pct', $ringkasan->komposisi_sewa_pct) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Kerja Sama Konten (%)</label>
                        <input type="number" step="0.1" name="komposisi_konten_pct" value="{{ old('komposisi_konten_pct', $ringkasan->komposisi_konten_pct) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Lainnya (%)</label>
                        <input type="number" step="0.1" name="komposisi_lainnya_pct" value="{{ old('komposisi_lainnya_pct', $ringkasan->komposisi_lainnya_pct) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                </div>
            </div>

            <!-- Realisasi Anggaran Operasional Usaha -->
            <div>
                <h4 class="text-xs font-black uppercase tracking-wider text-[#005B9F] mb-3">4. Realisasi Anggaran Operasional Usaha</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50">
                        <p class="text-xs font-bold text-slate-800 mb-2">Promosi & Pemasaran</p>
                        <label class="block text-[11px] text-slate-500">Realisasi (Rp)</label>
                        <input type="number" name="operasional_promosi_realisasi" value="{{ old('operasional_promosi_realisasi', $ringkasan->operasional_promosi_realisasi) }}" class="w-full text-xs rounded-xl border-slate-300 mb-2">
                        <label class="block text-[11px] text-slate-500">Pagu (Rp)</label>
                        <input type="number" name="operasional_promosi_pagu" value="{{ old('operasional_promosi_pagu', $ringkasan->operasional_promosi_pagu) }}" class="w-full text-xs rounded-xl border-slate-300 mb-2">
                        <label class="block text-[11px] text-slate-500">Persentase (%)</label>
                        <input type="number" name="operasional_promosi_pct" value="{{ old('operasional_promosi_pct', $ringkasan->operasional_promosi_pct) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>

                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50">
                        <p class="text-xs font-bold text-slate-800 mb-2">Event & Aktivasi</p>
                        <label class="block text-[11px] text-slate-500">Realisasi (Rp)</label>
                        <input type="number" name="operasional_event_realisasi" value="{{ old('operasional_event_realisasi', $ringkasan->operasional_event_realisasi) }}" class="w-full text-xs rounded-xl border-slate-300 mb-2">
                        <label class="block text-[11px] text-slate-500">Pagu (Rp)</label>
                        <input type="number" name="operasional_event_pagu" value="{{ old('operasional_event_pagu', $ringkasan->operasional_event_pagu) }}" class="w-full text-xs rounded-xl border-slate-300 mb-2">
                        <label class="block text-[11px] text-slate-500">Persentase (%)</label>
                        <input type="number" name="operasional_event_pct" value="{{ old('operasional_event_pct', $ringkasan->operasional_event_pct) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>

                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50">
                        <p class="text-xs font-bold text-slate-800 mb-2">Pemeliharaan Aset</p>
                        <label class="block text-[11px] text-slate-500">Realisasi (Rp)</label>
                        <input type="number" name="operasional_aset_realisasi" value="{{ old('operasional_aset_realisasi', $ringkasan->operasional_aset_realisasi) }}" class="w-full text-xs rounded-xl border-slate-300 mb-2">
                        <label class="block text-[11px] text-slate-500">Pagu (Rp)</label>
                        <input type="number" name="operasional_aset_pagu" value="{{ old('operasional_aset_pagu', $ringkasan->operasional_aset_pagu) }}" class="w-full text-xs rounded-xl border-slate-300 mb-2">
                        <label class="block text-[11px] text-slate-500">Persentase (%)</label>
                        <input type="number" name="operasional_aset_pct" value="{{ old('operasional_aset_pct', $ringkasan->operasional_aset_pct) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>

                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50">
                        <p class="text-xs font-bold text-slate-800 mb-2">Pengembangan Konten</p>
                        <label class="block text-[11px] text-slate-500">Realisasi (Rp)</label>
                        <input type="number" name="operasional_konten_realisasi" value="{{ old('operasional_konten_realisasi', $ringkasan->operasional_konten_realisasi) }}" class="w-full text-xs rounded-xl border-slate-300 mb-2">
                        <label class="block text-[11px] text-slate-500">Pagu (Rp)</label>
                        <input type="number" name="operasional_konten_pagu" value="{{ old('operasional_konten_pagu', $ringkasan->operasional_konten_pagu) }}" class="w-full text-xs rounded-xl border-slate-300 mb-2">
                        <label class="block text-[11px] text-slate-500">Persentase (%)</label>
                        <input type="number" name="operasional_konten_pct" value="{{ old('operasional_konten_pct', $ringkasan->operasional_konten_pct) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                </div>
            </div>

            <!-- Pipeline Peluang Usaha & Peta Sebaran -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-[#005B9F] mb-3">5. Pipeline Peluang Usaha</h4>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Prospek</label>
                            <input type="number" name="pipeline_prospek" value="{{ old('pipeline_prospek', $ringkasan->pipeline_prospek) }}" class="w-full text-xs rounded-xl border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Pendekatan</label>
                            <input type="number" name="pipeline_pendekatan" value="{{ old('pipeline_pendekatan', $ringkasan->pipeline_pendekatan) }}" class="w-full text-xs rounded-xl border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Proposal</label>
                            <input type="number" name="pipeline_proposal" value="{{ old('pipeline_proposal', $ringkasan->pipeline_proposal) }}" class="w-full text-xs rounded-xl border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Negosiasi</label>
                            <input type="number" name="pipeline_negosiasi" value="{{ old('pipeline_negosiasi', $ringkasan->pipeline_negosiasi) }}" class="w-full text-xs rounded-xl border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Kontrak</label>
                            <input type="number" name="pipeline_kontrak" value="{{ old('pipeline_kontrak', $ringkasan->pipeline_kontrak) }}" class="w-full text-xs rounded-xl border-slate-300">
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-[#005B9F] mb-3">6. Peta Sebaran Mitra Regional</h4>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Kota Ambon</label>
                            <input type="number" name="mitra_ambon" value="{{ old('mitra_ambon', $ringkasan->mitra_ambon) }}" class="w-full text-xs rounded-xl border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Tual & Malra</label>
                            <input type="number" name="mitra_tual" value="{{ old('mitra_tual', $ringkasan->mitra_tual) }}" class="w-full text-xs rounded-xl border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Saumlaki (Tanimbar)</label>
                            <input type="number" name="mitra_saumlaki" value="{{ old('mitra_saumlaki', $ringkasan->mitra_saumlaki) }}" class="w-full text-xs rounded-xl border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Kab. Buru</label>
                            <input type="number" name="mitra_buru" value="{{ old('mitra_buru', $ringkasan->mitra_buru) }}" class="w-full text-xs rounded-xl border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">P. Seram</label>
                            <input type="number" name="mitra_seram" value="{{ old('mitra_seram', $ringkasan->mitra_seram) }}" class="w-full text-xs rounded-xl border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Lainnya</label>
                            <input type="number" name="mitra_lainnya" value="{{ old('mitra_lainnya', $ringkasan->mitra_lainnya) }}" class="w-full text-xs rounded-xl border-slate-300">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Indeks Kinerja Usaha -->
            <div>
                <h4 class="text-xs font-black uppercase tracking-wider text-[#005B9F] mb-3">7. Indeks Kinerja Pengembangan Usaha (Skor & Pilar)</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Indeks Kinerja</label>
                        <input type="number" name="indeks_kinerja" value="{{ old('indeks_kinerja', $ringkasan->indeks_kinerja) }}" class="w-full text-xs rounded-xl border-slate-300 font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Predikat</label>
                        <input type="text" name="indeks_predikat" value="{{ old('indeks_predikat', $ringkasan->indeks_predikat) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Pertumbuhan Pendapatan</label>
                        <input type="number" name="skor_pertumbuhan_pendapatan" value="{{ old('skor_pertumbuhan_pendapatan', $ringkasan->skor_pertumbuhan_pendapatan) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Diversifikasi Usaha</label>
                        <input type="number" name="skor_diversifikasi_usaha" value="{{ old('skor_diversifikasi_usaha', $ringkasan->skor_diversifikasi_usaha) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Kemitraan Strategis</label>
                        <input type="number" name="skor_kemitraan_strategis" value="{{ old('skor_kemitraan_strategis', $ringkasan->skor_kemitraan_strategis) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Efisiensi Operasional</label>
                        <input type="number" name="skor_efisiensi_operasional" value="{{ old('skor_efisiensi_operasional', $ringkasan->skor_efisiensi_operasional) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Inovasi Produk</label>
                        <input type="number" name="skor_inovasi_produk" value="{{ old('skor_inovasi_produk', $ringkasan->skor_inovasi_produk) }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                </div>
            </div>

            <!-- Strategi Usaha 2024-2025 -->
            <div>
                <h4 class="text-xs font-black uppercase tracking-wider text-[#005B9F] mb-3">8. Strategi Pengembangan Usaha 2024-2025</h4>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Fokus Utama Strategis</label>
                        <textarea name="fokus_utama" rows="2" class="w-full text-xs rounded-xl border-slate-300">{{ old('fokus_utama', $ringkasan->fokus_utama) }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Sudah Berjalan (1 baris per poin)</label>
                            <textarea name="sudah_berjalan_text" rows="4" class="w-full text-xs rounded-xl border-slate-300 font-mono">{{ is_array($ringkasan->sudah_berjalan_json) ? implode("\n", $ringkasan->sudah_berjalan_json) : $ringkasan->sudah_berjalan_json }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Dalam Proses (1 baris per poin)</label>
                            <textarea name="dalam_proses_text" rows="4" class="w-full text-xs rounded-xl border-slate-300 font-mono">{{ is_array($ringkasan->dalam_proses_json) ? implode("\n", $ringkasan->dalam_proses_json) : $ringkasan->dalam_proses_json }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Rencana Selanjutnya (1 baris per poin)</label>
                            <textarea name="rencana_selanjutnya_text" rows="4" class="w-full text-xs rounded-xl border-slate-300 font-mono">{{ is_array($ringkasan->rencana_selanjutnya_json) ? implode("\n", $ringkasan->rencana_selanjutnya_json) : $ringkasan->rencana_selanjutnya_json }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 flex items-center justify-end space-x-3">
                <a href="{{ route('tvri.usaha.dashboard') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#005B9F] hover:bg-[#004a82] text-white text-xs font-bold shadow-lg transition-all">
                    Simpan Semua Parameter
                </button>
            </div>
        </form>
    </div>
    @endif

</div>

<!-- Modal Edit Mitra -->
<div id="modalEditMitra" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200">
        <h3 class="text-base font-bold text-slate-800 mb-4">Edit Data Mitra / Klien</h3>
        <form id="formEditMitra" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Mitra</label>
                <input type="text" id="editMitraNama" name="nama" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Bentuk Kerja Sama</label>
                <input type="text" id="editMitraJenis" name="jenis_kerja_sama" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nilai Kontrak (Rp)</label>
                    <input type="number" id="editMitraNilai" name="nilai_kontrak" required class="w-full text-xs rounded-xl border-slate-300">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                    <select id="editMitraStatus" name="status" class="w-full text-xs rounded-xl border-slate-300">
                        <option value="Aktif">Aktif</option>
                        <option value="Proses">Proses</option>
                        <option value="Negosiasi">Negosiasi</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Wilayah / Lokasi</label>
                <select id="editMitraLokasi" name="lokasi" class="w-full text-xs rounded-xl border-slate-300">
                    <option value="Ambon">Kota Ambon</option>
                    <option value="Tual">Kota Tual / Malra</option>
                    <option value="Saumlaki">Saumlaki (Kep. Tanimbar)</option>
                    <option value="Buru">Kabupaten Buru</option>
                    <option value="Seram">Seram (SBB / SBT / Malteng)</option>
                    <option value="Lainnya">Lainnya / Nasional</option>
                </select>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2">
                <button type="button" onclick="closeModal('modalEditMitra')" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#005B9F] text-white text-xs font-bold hover:bg-[#004a82]">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kegiatan -->
<div id="modalEditKegiatan" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200">
        <h3 class="text-base font-bold text-slate-800 mb-4">Edit Kegiatan Usaha</h3>
        <form id="formEditKegiatan" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal</label>
                    <input type="date" id="editKegiatanTanggal" name="tanggal" required class="w-full text-xs rounded-xl border-slate-300">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis</label>
                    <select id="editKegiatanJenis" name="jenis" class="w-full text-xs rounded-xl border-slate-300">
                        <option value="Event">Event</option>
                        <option value="Sponsorship">Sponsorship</option>
                        <option value="Iklan">Iklan</option>
                        <option value="Event & Aktivasi">Event & Aktivasi</option>
                        <option value="Iklan Layanan">Iklan Layanan</option>
                        <option value="Sewa Fasilitas">Sewa Fasilitas</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Kegiatan</label>
                <input type="text" id="editKegiatanNama" name="kegiatan" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Est. Nilai (Rp)</label>
                    <input type="number" id="editKegiatanNilai" name="estimasi_nilai" required class="w-full text-xs rounded-xl border-slate-300">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                    <select id="editKegiatanStatus" name="status" class="w-full text-xs rounded-xl border-slate-300">
                        <option value="Persiapan">Persiapan</option>
                        <option value="Kontrak">Kontrak</option>
                        <option value="Negosiasi">Negosiasi</option>
                        <option value="Berjalan">Berjalan</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2">
                <button type="button" onclick="closeModal('modalEditKegiatan')" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit PNBP -->
<div id="modalEditPnbp" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200">
        <h3 class="text-base font-bold text-slate-800 mb-4">Edit Sumber Pendapatan / PNBP</h3>
        <form id="formEditPnbp" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Sumber Pendapatan</label>
                <input type="text" id="editPnbpJenis" name="jenis" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Target (Rp)</label>
                    <input type="number" id="editPnbpTarget" name="target" required class="w-full text-xs rounded-xl border-slate-300">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Realisasi (Rp)</label>
                    <input type="number" id="editPnbpRealisasi" name="realisasi" required class="w-full text-xs rounded-xl border-slate-300">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Tahun</label>
                <input type="number" id="editPnbpTahun" name="tahun" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2">
                <button type="button" onclick="closeModal('modalEditPnbp')" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-amber-600 text-white text-xs font-bold hover:bg-amber-700">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit SOP -->
<div id="modalEditSop" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200">
        <h3 class="text-base font-bold text-slate-800 mb-4">Edit Dokumen SOP Usaha</h3>
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
                <label class="block text-xs font-semibold text-slate-600 mb-1">Keterangan / Alur</label>
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

    setupCheckAll('checkAllMitra', 'check-item-mitra', 'btnBulkMitra');
    setupCheckAll('checkAllKegiatan', 'check-item-kegiatan', 'btnBulkKegiatan');
    setupCheckAll('checkAllPnbp', 'check-item-pnbp', 'btnBulkPnbp');
    setupCheckAll('checkAllSop', 'check-item-sop', 'btnBulkSop');

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function editMitra(m) {
        document.getElementById('formEditMitra').action = '/tvri/usaha/mitra/' + m.id;
        document.getElementById('editMitraNama').value = m.nama || '';
        document.getElementById('editMitraJenis').value = m.jenis_kerja_sama || '';
        document.getElementById('editMitraNilai').value = m.nilai_kontrak || 0;
        document.getElementById('editMitraStatus').value = m.status || 'Aktif';
        document.getElementById('editMitraLokasi').value = m.lokasi || 'Ambon';
        document.getElementById('modalEditMitra').classList.remove('hidden');
    }

    function editKegiatan(k) {
        document.getElementById('formEditKegiatan').action = '/tvri/usaha/kegiatan/' + k.id;
        document.getElementById('editKegiatanTanggal').value = k.tanggal ? k.tanggal.substring(0, 10) : '';
        document.getElementById('editKegiatanNama').value = k.kegiatan || '';
        document.getElementById('editKegiatanJenis').value = k.jenis || 'Event';
        document.getElementById('editKegiatanNilai').value = k.estimasi_nilai || 0;
        document.getElementById('editKegiatanStatus').value = k.status || 'Persiapan';
        document.getElementById('modalEditKegiatan').classList.remove('hidden');
    }

    function editPnbp(p) {
        document.getElementById('formEditPnbp').action = '/tvri/usaha/pnbp/' + p.id;
        document.getElementById('editPnbpJenis').value = p.jenis || '';
        document.getElementById('editPnbpTarget').value = p.target || 0;
        document.getElementById('editPnbpRealisasi').value = p.realisasi || 0;
        document.getElementById('editPnbpTahun').value = p.tahun || 2024;
        document.getElementById('modalEditPnbp').classList.remove('hidden');
    }

    function editSop(s) {
        document.getElementById('formEditSop').action = '/tvri/usaha/sop/' + s.id;
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
