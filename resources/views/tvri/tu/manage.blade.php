@extends('layouts.app')

@section('title', 'Input & Kelola Data Tata Usaha - TVRI Maluku')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-900 via-sky-800 to-indigo-900 text-white shadow-xl p-6 md:p-8">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center space-x-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-xs font-semibold text-blue-200 border border-white/15 mb-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Modul Input Data & SOP Terpadu</span>
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Fitur Input Data Tata Usaha</h1>
                <p class="text-sm text-blue-100/90 mt-1">Pengelolaan Surat Masuk, Surat Keluar, Agenda, Layanan Administrasi, SOP, dan Parameter Dashboard</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('tvri.tu.dashboard') }}"
                   class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold backdrop-blur-md border border-white/20 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Kembali ke Dashboard TU</span>
                </a>
                <form action="{{ route('tvri.tu.reseed') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memulihkan seluruh data default Tata Usaha sesuai data awal September 2025?')">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-amber-500/90 hover:bg-amber-600 text-white text-xs font-semibold transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Pulihkan Data Default</span>
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
                $tab = request('tab', $activeTab ?? 'surat-masuk');
            @endphp
            <a href="{{ route('tvri.tu.manage', ['tab' => 'surat-masuk']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $tab === 'surat-masuk' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8m-4-4l4 4-4 4"/></svg>
                <span>Surat Masuk ({{ $suratMasuks->count() }})</span>
            </a>
            <a href="{{ route('tvri.tu.manage', ['tab' => 'surat-keluar']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $tab === 'surat-keluar' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m8-4l4 4-4 4"/></svg>
                <span>Surat Keluar ({{ $suratKeluars->count() }})</span>
            </a>
            <a href="{{ route('tvri.tu.manage', ['tab' => 'agenda']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $tab === 'agenda' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Agenda Kegiatan ({{ $agendas->count() }})</span>
            </a>
            <a href="{{ route('tvri.tu.manage', ['tab' => 'layanan']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $tab === 'layanan' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M9 8h6m-9 12h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Layanan Adm ({{ $layanans->count() }})</span>
            </a>
            <a href="{{ route('tvri.tu.manage', ['tab' => 'sop']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $tab === 'sop' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <span>SOP & Regulasi ({{ $sops->count() }})</span>
            </a>
            <a href="{{ route('tvri.tu.manage', ['tab' => 'ringkasan']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $tab === 'ringkasan' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                <span>Parameter Ringkasan</span>
            </a>
        </div>
    </div>

    <!-- TAB 1: SURAT MASUK -->
    @if($tab === 'surat-masuk')
    <div class="space-y-6">
        <!-- Card Form Tambah Surat Masuk -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                <span>Tambah Surat Masuk Baru</span>
            </h3>
            <form action="{{ route('tvri.tu.surat-masuk.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nomor Surat</label>
                    <input type="text" name="nomor" placeholder="Contoh: B-142/KOMINFO/..." required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Pengirim / Instansi</label>
                    <input type="text" name="pengirim" placeholder="Nama instansi pengirim" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kategori</label>
                    <select name="kategori" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Kementerian / Lembaga">Kementerian / Lembaga</option>
                        <option value="LPP TVRI">LPP TVRI</option>
                        <option value="Pemerintah Daerah">Pemerintah Daerah</option>
                        <option value="Instansi Vertikal / TNI / Polri">Instansi Vertikal / TNI / Polri</option>
                        <option value="Mitra Kerja">Mitra Kerja</option>
                        <option value="Masyarakat">Masyarakat</option>
                        <option value="Rahasia">Rahasia</option>
                        <option value="Penting">Penting</option>
                        <option value="Biasa">Biasa</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Perihal</label>
                    <input type="text" name="perihal" placeholder="Isi ringkas perihal surat" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status Disposisi</label>
                    <select name="status" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Didisposisikan">Didisposisikan</option>
                        <option value="Diproses">Diproses</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Baru">Baru</option>
                    </select>
                </div>
                <div class="md:col-span-4">
                    <label class="block text-xs font-semibold text-slate-700 mb-1 flex items-center justify-between">
                        <span>Upload Berkas / File Fisik Surat (PDF / Dokumen / Scan)</span>
                        <span class="text-[10px] text-slate-400 font-normal">PDF, DOCX, XLSX, Gambar / Scan (Maks 20MB)</span>
                    </label>
                    <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg" class="w-full text-xs rounded-xl border border-slate-300 p-2 bg-slate-50/50 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#005B9F] file:text-white hover:file:bg-[#004a82] cursor-pointer">
                </div>
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#005B9F] hover:bg-[#004a82] text-white text-xs font-bold transition shadow-sm flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Simpan Surat Masuk</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Surat Masuk dengan Bulk Delete -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <form id="bulkFormSuratMasuk" action="{{ route('tvri.tu.surat-masuk.bulk-destroy') }}" method="POST" onsubmit="return confirm('Hapus semua data surat masuk yang dipilih?')">
                @csrf
                @method('DELETE')
                <div class="p-4 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3 bg-slate-50/50">
                    <div class="flex items-center space-x-3">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Daftar Surat Masuk Terdata</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700">{{ $suratMasuks->count() }} Data</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-sm flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>Hapus Terpilih (Kotak Centang)</span>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-100/75 text-slate-600 font-semibold uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="p-3 w-10 text-center">
                                    <input type="checkbox" id="checkAllSm" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                </th>
                                <th class="p-3">Tanggal</th>
                                <th class="p-3">Nomor Surat</th>
                                <th class="p-3">Pengirim</th>
                                <th class="p-3">Perihal</th>
                                <th class="p-3">Kategori</th>
                                <th class="p-3">Status</th>
                                <th class="p-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($suratMasuks as $sm)
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="p-3 text-center">
                                    <input type="checkbox" name="selected_ids[]" value="{{ $sm->id }}" class="check-item-sm rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                </td>
                                <td class="p-3 whitespace-nowrap text-slate-600 font-medium">{{ \Carbon\Carbon::parse($sm->tanggal)->format('d M Y') }}</td>
                                <td class="p-3 font-semibold text-slate-800">{{ $sm->nomor }}</td>
                                <td class="p-3 text-slate-700">{{ $sm->pengirim }}</td>
                                <td class="p-3 text-slate-600 max-w-xs truncate" title="{{ $sm->perihal }}">{{ $sm->perihal }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">{{ $sm->kategori }}</span>
                                </td>
                                <td class="p-3 whitespace-nowrap">
                                    @php
                                        $badgeColor = match($sm->status) {
                                            'Didisposisikan' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'Diproses' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'Selesai' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            default => 'bg-slate-100 text-slate-700 border-slate-200',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $badgeColor }}">{{ $sm->status }}</span>
                                </td>
                                <td class="p-3 text-right whitespace-nowrap space-x-1">
                                    @php $smFileUrl = $sm->file_url ?: $sm->file_path; @endphp
                                    @if($smFileUrl)
                                    <a href="{{ asset($smFileUrl) }}" target="_blank" class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 transition" title="Buka Dokumen Fisik Surat">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Fisik Surat</span>
                                    </a>
                                    @endif
                                    <button type="button" onclick="editSuratMasuk({{ json_encode($sm) }})" class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg transition" title="Ubah">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="p-6 text-center text-slate-400">Belum ada data surat masuk.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- TAB 2: SURAT KELUAR -->
    @if($tab === 'surat-keluar')
    <div class="space-y-6">
        <!-- Form Tambah Surat Keluar -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                <span>Tambah Surat Keluar Baru</span>
            </h3>
            <form action="{{ route('tvri.tu.surat-keluar.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nomor Surat</label>
                    <input type="text" name="nomor" placeholder="Contoh: 092/TU/TVRI-MLK/..." required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tujuan Surat</label>
                    <input type="text" name="tujuan" placeholder="Instansi / Penerima tujuan" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Perihal</label>
                    <input type="text" name="perihal" placeholder="Perihal naskah dinas keluar" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                    <select name="status" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Terkirim">Terkirim</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Diproses">Diproses</option>
                        <option value="Baru">Baru</option>
                    </select>
                </div>
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#005B9F] hover:bg-[#004a82] text-white text-xs font-bold transition shadow-sm flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Simpan Surat Keluar</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Surat Keluar dengan Bulk Delete -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <form id="bulkFormSuratKeluar" action="{{ route('tvri.tu.surat-keluar.bulk-destroy') }}" method="POST" onsubmit="return confirm('Hapus semua surat keluar yang dipilih?')">
                @csrf
                @method('DELETE')
                <div class="p-4 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3 bg-slate-50/50">
                    <div class="flex items-center space-x-3">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Daftar Surat Keluar Terdata</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700">{{ $suratKeluars->count() }} Data</span>
                    </div>
                    <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-sm flex items-center space-x-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Hapus Terpilih (Kotak Centang)</span>
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-100/75 text-slate-600 font-semibold uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="p-3 w-10 text-center">
                                    <input type="checkbox" id="checkAllSk" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                </th>
                                <th class="p-3">Tanggal</th>
                                <th class="p-3">Nomor Surat</th>
                                <th class="p-3">Tujuan</th>
                                <th class="p-3">Perihal</th>
                                <th class="p-3">Status</th>
                                <th class="p-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($suratKeluars as $sk)
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="p-3 text-center">
                                    <input type="checkbox" name="selected_ids[]" value="{{ $sk->id }}" class="check-item-sk rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                </td>
                                <td class="p-3 whitespace-nowrap text-slate-600 font-medium">{{ \Carbon\Carbon::parse($sk->tanggal)->format('d M Y') }}</td>
                                <td class="p-3 font-semibold text-slate-800">{{ $sk->nomor }}</td>
                                <td class="p-3 text-slate-700">{{ $sk->tujuan }}</td>
                                <td class="p-3 text-slate-600 max-w-xs truncate" title="{{ $sk->perihal }}">{{ $sk->perihal }}</td>
                                <td class="p-3 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border bg-indigo-100 text-indigo-700 border-indigo-200">{{ $sk->status }}</span>
                                </td>
                                <td class="p-3 text-right whitespace-nowrap">
                                    <button type="button" onclick="editSuratKeluar({{ json_encode($sk) }})" class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg transition" title="Ubah">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="p-6 text-center text-slate-400">Belum ada data surat keluar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- TAB 3: AGENDA KEGIATAN -->
    @if($tab === 'agenda')
    <div class="space-y-6">
        <!-- Form Tambah Agenda -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                <span>Tambah Agenda Kegiatan Baru</span>
            </h3>
            <form action="{{ route('tvri.tu.agenda.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Waktu</label>
                    <input type="text" name="waktu" placeholder="Contoh: 09:00 - 11:30 WIT" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Kegiatan</label>
                    <input type="text" name="kegiatan" placeholder="Agenda rapat / kegiatan dinas" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Lokasi</label>
                    <input type="text" name="lokasi" placeholder="Ruang rapat / tempat" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">PIC / Peserta</label>
                    <input type="text" name="pic" placeholder="Internal TU & Bidang / Eksternal" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Agenda</label>
                    <select name="jenis" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Hari Ini">Hari Ini</option>
                        <option value="Minggu Ini">Minggu Ini</option>
                        <option value="Internal TU & Bidang">Internal TU & Bidang</option>
                        <option value="Eksternal">Eksternal</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                    <select name="status" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Terjadwal">Terjadwal</option>
                        <option value="Mendatang">Mendatang</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Dibatalkan">Dibatalkan</option>
                    </select>
                </div>
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#005B9F] hover:bg-[#004a82] text-white text-xs font-bold transition shadow-sm flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Simpan Agenda</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Agenda dengan Bulk Delete -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <form id="bulkFormAgenda" action="{{ route('tvri.tu.agenda.bulk-destroy') }}" method="POST" onsubmit="return confirm('Hapus agenda kegiatan terpilih?')">
                @csrf
                @method('DELETE')
                <div class="p-4 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3 bg-slate-50/50">
                    <div class="flex items-center space-x-3">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Daftar Agenda Kegiatan</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">{{ $agendas->count() }} Data</span>
                    </div>
                    <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-sm flex items-center space-x-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Hapus Terpilih (Kotak Centang)</span>
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-100/75 text-slate-600 font-semibold uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="p-3 w-10 text-center">
                                    <input type="checkbox" id="checkAllAg" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                </th>
                                <th class="p-3">Tanggal & Waktu</th>
                                <th class="p-3">Kegiatan</th>
                                <th class="p-3">Lokasi</th>
                                <th class="p-3">PIC / Peserta</th>
                                <th class="p-3">Jenis</th>
                                <th class="p-3">Status</th>
                                <th class="p-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($agendas as $ag)
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="p-3 text-center">
                                    <input type="checkbox" name="selected_ids[]" value="{{ $ag->id }}" class="check-item-ag rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                </td>
                                <td class="p-3 whitespace-nowrap">
                                    <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($ag->tanggal)->format('d M Y') }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $ag->waktu }}</div>
                                </td>
                                <td class="p-3 font-semibold text-slate-800">{{ $ag->kegiatan }}</td>
                                <td class="p-3 text-slate-600">{{ $ag->lokasi }}</td>
                                <td class="p-3 text-slate-600">{{ $ag->pic }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">{{ $ag->jenis }}</span>
                                </td>
                                <td class="p-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border bg-emerald-100 text-emerald-700 border-emerald-200">{{ $ag->status }}</span>
                                </td>
                                <td class="p-3 text-right whitespace-nowrap">
                                    <button type="button" onclick="editAgenda({{ json_encode($ag) }})" class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg transition" title="Ubah">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="p-6 text-center text-slate-400">Belum ada agenda kegiatan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- TAB 4: LAYANAN ADMINISTRASI -->
    @if($tab === 'layanan')
    <div class="space-y-6">
        <!-- Form Tambah Layanan -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span>
                <span>Tambah / Perbarui Data Layanan Administrasi</span>
            </h3>
            <form action="{{ route('tvri.tu.layanan.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Layanan</label>
                    <input type="text" name="jenis" placeholder="Contoh: SK & Keputusan" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Jumlah</label>
                    <input type="number" name="jumlah" value="1" min="0" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Keterangan / Persentase Selesai</label>
                    <input type="text" name="keterangan" placeholder="Contoh: 24 Selesai (96%)" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-3 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#005B9F] hover:bg-[#004a82] text-white text-xs font-bold transition shadow-sm flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Simpan Layanan</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Layanan dengan Bulk Delete -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <form id="bulkFormLayanan" action="{{ route('tvri.tu.layanan.bulk-destroy') }}" method="POST" onsubmit="return confirm('Hapus data layanan yang dipilih?')">
                @csrf
                @method('DELETE')
                <div class="p-4 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3 bg-slate-50/50">
                    <div class="flex items-center space-x-3">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Breakdown Layanan Administrasi</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-100 text-teal-700">{{ $layanans->count() }} Kategori</span>
                    </div>
                    <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-sm flex items-center space-x-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Hapus Terpilih (Kotak Centang)</span>
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-100/75 text-slate-600 font-semibold uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="p-3 w-10 text-center">
                                    <input type="checkbox" id="checkAllLy" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                </th>
                                <th class="p-3">Jenis Layanan Administrasi</th>
                                <th class="p-3">Jumlah Berkas</th>
                                <th class="p-3">Keterangan / Capaian</th>
                                <th class="p-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($layanans as $ly)
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="p-3 text-center">
                                    <input type="checkbox" name="selected_ids[]" value="{{ $ly->id }}" class="check-item-ly rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                </td>
                                <td class="p-3 font-semibold text-slate-800">{{ $ly->jenis }}</td>
                                <td class="p-3 font-bold text-blue-700">{{ $ly->jumlah }}</td>
                                <td class="p-3 text-slate-600">{{ $ly->keterangan }}</td>
                                <td class="p-3 text-right whitespace-nowrap">
                                    <button type="button" onclick="editLayanan({{ json_encode($ly) }})" class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg transition" title="Ubah">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-slate-400">Belum ada data layanan administrasi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- TAB 5: SOP & REGULASI -->
    @if($tab === 'sop')
    <div class="space-y-6">
        <!-- Form Tambah SOP -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span>Tambah Dokumen SOP & Regulasi Tata Usaha</span>
            </h3>
            <form action="{{ route('tvri.tu.sop.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kode SOP</label>
                    <input type="text" name="kode" placeholder="Contoh: SOP-TU-07" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Judul SOP</label>
                    <input type="text" name="judul" placeholder="Nama lengkap SOP" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kategori</label>
                    <input type="text" name="kategori" placeholder="Persuratan, Disposisi, Kearsipan, dll" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Versi Dokumen</label>
                    <input type="text" name="versi" placeholder="v2.1" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Efektif</label>
                    <input type="date" name="tanggal_efektif" value="{{ date('Y-m-d') }}" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                    <select name="status" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Aktif">Aktif</option>
                        <option value="Revisi">Dalam Revisi</option>
                        <option value="Arsip">Arsip</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Urutan</label>
                    <input type="number" name="urutan" value="{{ $sops->count() + 1 }}" min="1" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-4">
                    <label class="block text-xs font-semibold text-slate-700 mb-1 flex items-center justify-between">
                        <span>Upload File Dokumen Fisik SOP (PDF / Dokumen)</span>
                        <span class="text-[10px] text-slate-400 font-normal">PDF, DOCX, XLSX, Gambar (Maks 20MB)</span>
                    </label>
                    <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg" class="w-full text-xs rounded-xl border border-slate-300 p-2 bg-slate-50/50 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#005B9F] file:text-white hover:file:bg-[#004a82] cursor-pointer">
                </div>
                <div class="md:col-span-4">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Keterangan / Ringkasan Prosedur</label>
                    <textarea name="keterangan" rows="2" placeholder="Uraian singkat tata cara atau alur SOP..." class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#005B9F] hover:bg-[#004a82] text-white text-xs font-bold transition shadow-sm flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Simpan SOP</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel SOP dengan Bulk Delete -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <form id="bulkFormSop" action="{{ route('tvri.tu.sop.bulk-destroy') }}" method="POST" onsubmit="return confirm('Hapus SOP terpilih?')">
                @csrf
                @method('DELETE')
                <div class="p-4 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3 bg-slate-50/50">
                    <div class="flex items-center space-x-3">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Daftar SOP & Standar Prosedur Operasional</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">{{ $sops->count() }} SOP</span>
                    </div>
                    <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-sm flex items-center space-x-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Hapus Terpilih (Kotak Centang)</span>
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-100/75 text-slate-600 font-semibold uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="p-3 w-10 text-center">
                                    <input type="checkbox" id="checkAllSop" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                </th>
                                <th class="p-3">Kode</th>
                                <th class="p-3">Judul SOP</th>
                                <th class="p-3">Kategori</th>
                                <th class="p-3">Versi</th>
                                <th class="p-3">Tanggal Efektif</th>
                                <th class="p-3">Status</th>
                                <th class="p-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($sops as $sop)
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="p-3 text-center">
                                    <input type="checkbox" name="selected_ids[]" value="{{ $sop->id }}" class="check-item-sop rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                </td>
                                <td class="p-3 font-mono font-bold text-blue-700">{{ $sop->kode }}</td>
                                <td class="p-3 font-semibold text-slate-800">
                                    <div>{{ $sop->judul }}</div>
                                    <div class="text-[11px] text-slate-500 font-normal line-clamp-1">{{ $sop->keterangan }}</div>
                                </td>
                                <td class="p-3 text-slate-600">{{ $sop->kategori }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">{{ $sop->versi }}</span>
                                </td>
                                <td class="p-3 text-slate-600 whitespace-nowrap">{{ $sop->tanggal_efektif ? \Carbon\Carbon::parse($sop->tanggal_efektif)->format('d M Y') : '-' }}</td>
                                <td class="p-3 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border bg-emerald-100 text-emerald-700 border-emerald-200">{{ $sop->status }}</span>
                                </td>
                                <td class="p-3 text-right whitespace-nowrap">
                                    @php $fileUrl = $sop->file_url ?: $sop->file_path; @endphp
                                    @if($fileUrl)
                                    <a href="{{ asset($fileUrl) }}" target="_blank" class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 mr-1.5 transition" title="Buka Dokumen Fisik SOP">
                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>Fisik SOP</span>
                                    </a>
                                    @endif
                                    <button type="button" onclick="editSop({{ json_encode($sop) }})" class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg transition" title="Ubah">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="p-6 text-center text-slate-400">Belum ada SOP terdata.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- TAB 6: PARAMETER RINGKASAN DASHBOARD -->
    @if($tab === 'ringkasan')
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
        <h3 class="text-base font-bold text-slate-800 mb-6 flex items-center space-x-2">
            <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
            <span>Konfigurasi Metrik & Parameter Utama Dashboard Tata Usaha</span>
        </h3>
        <form action="{{ route('tvri.tu.ringkasan.update') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Header & Identitas -->
            <div>
                <h4 class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-3">Header & Identitas</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Periode</label>
                        <input type="text" name="periode" value="{{ $ringkasan->periode }}" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Header Waktu</label>
                        <input type="text" name="header_waktu" value="{{ $ringkasan->header_waktu }}" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Status Online</label>
                        <input type="text" name="online_status" value="{{ $ringkasan->online_status }}" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Slogan Header</label>
                        <input type="text" name="slogan_header" value="{{ $ringkasan->slogan_header }}" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Slogan Sub</label>
                        <input type="text" name="slogan_sub" value="{{ $ringkasan->slogan_sub }}" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <hr class="border-slate-200">

            <!-- Metrik Atas (6 Kartu Metrik) -->
            <div>
                <h4 class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-3">Metrik Utama Atas (6 Kartu Metrik)</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Surat Masuk</label>
                        <input type="number" name="surat_masuk" value="{{ $ringkasan->surat_masuk }}" class="w-full text-xs rounded-lg border-slate-300">
                        <input type="text" name="diff_surat_masuk" value="{{ $ringkasan->diff_surat_masuk }}" placeholder="diff" class="w-full text-[10px] rounded-lg border-slate-300 mt-1">
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Surat Keluar</label>
                        <input type="number" name="surat_keluar" value="{{ $ringkasan->surat_keluar }}" class="w-full text-xs rounded-lg border-slate-300">
                        <input type="text" name="diff_surat_keluar" value="{{ $ringkasan->diff_surat_keluar }}" placeholder="diff" class="w-full text-[10px] rounded-lg border-slate-300 mt-1">
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Disposisi</label>
                        <input type="number" name="disposisi" value="{{ $ringkasan->disposisi }}" class="w-full text-xs rounded-lg border-slate-300">
                        <input type="text" name="diff_disposisi" value="{{ $ringkasan->diff_disposisi }}" placeholder="diff" class="w-full text-[10px] rounded-lg border-slate-300 mt-1">
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Agenda Kegiatan</label>
                        <input type="number" name="agenda_kegiatan" value="{{ $ringkasan->agenda_kegiatan }}" class="w-full text-xs rounded-lg border-slate-300">
                        <input type="text" name="diff_agenda" value="{{ $ringkasan->diff_agenda }}" placeholder="diff" class="w-full text-[10px] rounded-lg border-slate-300 mt-1">
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Layanan Adm</label>
                        <input type="number" name="layanan_adm" value="{{ $ringkasan->layanan_adm }}" class="w-full text-xs rounded-lg border-slate-300">
                        <input type="text" name="diff_layanan" value="{{ $ringkasan->diff_layanan }}" placeholder="diff" class="w-full text-[10px] rounded-lg border-slate-300 mt-1">
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Service Completion %</label>
                        <input type="number" name="service_completion_rate" value="{{ $ringkasan->service_completion_rate }}" class="w-full text-xs rounded-lg border-slate-300">
                        <input type="number" name="target_completion_rate" value="{{ $ringkasan->target_completion_rate }}" placeholder="Target" class="w-full text-[10px] rounded-lg border-slate-300 mt-1">
                    </div>
                </div>
            </div>

            <hr class="border-slate-200">

            <!-- Status Disposisi & Arsip -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-3">Status Disposisi (Total 110)</h4>
                    <div class="grid grid-cols-2 gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <div>
                            <label class="block text-[11px] text-slate-600 mb-1">Selesai</label>
                            <input type="number" name="disposisi_selesai" value="{{ $ringkasan->disposisi_selesai }}" class="w-full text-xs rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block text-[11px] text-slate-600 mb-1">Dalam Proses</label>
                            <input type="number" name="disposisi_proses" value="{{ $ringkasan->disposisi_proses }}" class="w-full text-xs rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block text-[11px] text-slate-600 mb-1">Menunggu</label>
                            <input type="number" name="disposisi_menunggu" value="{{ $ringkasan->disposisi_menunggu }}" class="w-full text-xs rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block text-[11px] text-slate-600 mb-1">Terlambat</label>
                            <input type="number" name="disposisi_terlambat" value="{{ $ringkasan->disposisi_terlambat }}" class="w-full text-xs rounded-lg border-slate-300">
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-3">Arsip & Dokumentasi (Total 2.462)</h4>
                    <div class="grid grid-cols-2 gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <div>
                            <label class="block text-[11px] text-slate-600 mb-1">Arsip Aktif</label>
                            <input type="number" name="arsip_aktif" value="{{ $ringkasan->arsip_aktif }}" class="w-full text-xs rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block text-[11px] text-slate-600 mb-1">Arsip Inaktif</label>
                            <input type="number" name="arsip_inaktif" value="{{ $ringkasan->arsip_inaktif }}" class="w-full text-xs rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block text-[11px] text-slate-600 mb-1">Arsip Digital</label>
                            <input type="number" name="arsip_digital" value="{{ $ringkasan->arsip_digital }}" class="w-full text-xs rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block text-[11px] text-slate-600 mb-1">Usul Retensi</label>
                            <input type="number" name="arsip_retensi" value="{{ $ringkasan->arsip_retensi }}" class="w-full text-xs rounded-lg border-slate-300">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#005B9F] hover:bg-[#004a82] text-white text-xs font-bold transition shadow-sm">
                    Simpan Seluruh Parameter Ringkasan
                </button>
            </div>
        </form>
    </div>
    @endif
</div>

<!-- Modal Edit Surat Masuk -->
<div id="modalEditSuratMasuk" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6">
        <h3 class="text-base font-bold text-slate-800 mb-4">Ubah Data Surat Masuk</h3>
        <form id="formEditSuratMasuk" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal</label>
                <input type="date" id="editSmTanggal" name="tanggal" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Nomor Surat</label>
                <input type="text" id="editSmNomor" name="nomor" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Pengirim</label>
                <input type="text" id="editSmPengirim" name="pengirim" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Perihal</label>
                <textarea id="editSmPerihal" name="perihal" rows="2" required class="w-full text-xs rounded-xl border-slate-300"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kategori</label>
                    <input type="text" id="editSmKategori" name="kategori" class="w-full text-xs rounded-xl border-slate-300">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                    <select id="editSmStatus" name="status" class="w-full text-xs rounded-xl border-slate-300">
                        <option value="Didisposisikan">Didisposisikan</option>
                        <option value="Diproses">Diproses</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Baru">Baru</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1 flex items-center justify-between">
                    <span>Upload / Ganti Berkas Surat Fisik</span>
                    <span class="text-[10px] text-slate-400 font-normal">PDF, DOCX, JPG, PNG (Maks 20MB)</span>
                </label>
                <div id="editSmFilePreview" class="hidden mb-2 p-2 bg-blue-50 rounded-xl border border-blue-200 flex items-center justify-between">
                    <span class="text-xs text-blue-800 font-medium truncate max-w-xs">Dokumen fisik tersimpan</span>
                    <a id="editSmFileLink" href="#" target="_blank" class="text-xs font-bold text-blue-700 hover:underline inline-flex items-center space-x-1">
                        <span>Buka Surat</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>
                <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg" class="w-full text-xs rounded-xl border border-slate-300 p-2 bg-slate-50/50 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#005B9F] file:text-white cursor-pointer">
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2">
                <button type="button" onclick="closeModal('modalEditSuratMasuk')" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#005B9F] text-white text-xs font-bold hover:bg-[#004a82]">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Surat Keluar -->
<div id="modalEditSuratKeluar" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6">
        <h3 class="text-base font-bold text-slate-800 mb-4">Ubah Data Surat Keluar</h3>
        <form id="formEditSuratKeluar" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal</label>
                <input type="date" id="editSkTanggal" name="tanggal" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Nomor Surat</label>
                <input type="text" id="editSkNomor" name="nomor" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Tujuan</label>
                <input type="text" id="editSkTujuan" name="tujuan" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Perihal</label>
                <textarea id="editSkPerihal" name="perihal" rows="2" required class="w-full text-xs rounded-xl border-slate-300"></textarea>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                <select id="editSkStatus" name="status" class="w-full text-xs rounded-xl border-slate-300">
                    <option value="Terkirim">Terkirim</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Diproses">Diproses</option>
                    <option value="Baru">Baru</option>
                </select>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2">
                <button type="button" onclick="closeModal('modalEditSuratKeluar')" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#005B9F] text-white text-xs font-bold hover:bg-[#004a82]">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Agenda -->
<div id="modalEditAgenda" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6">
        <h3 class="text-base font-bold text-slate-800 mb-4">Ubah Agenda Kegiatan</h3>
        <form id="formEditAgenda" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal</label>
                    <input type="date" id="editAgTanggal" name="tanggal" required class="w-full text-xs rounded-xl border-slate-300">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Waktu</label>
                    <input type="text" id="editAgWaktu" name="waktu" required class="w-full text-xs rounded-xl border-slate-300">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Kegiatan</label>
                <input type="text" id="editAgKegiatan" name="kegiatan" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Lokasi</label>
                <input type="text" id="editAgLokasi" name="lokasi" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">PIC / Peserta</label>
                <input type="text" id="editAgPic" name="pic" class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis</label>
                    <select id="editAgJenis" name="jenis" class="w-full text-xs rounded-xl border-slate-300">
                        <option value="Hari Ini">Hari Ini</option>
                        <option value="Minggu Ini">Minggu Ini</option>
                        <option value="Internal TU & Bidang">Internal TU & Bidang</option>
                        <option value="Eksternal">Eksternal</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                    <select id="editAgStatus" name="status" class="w-full text-xs rounded-xl border-slate-300">
                        <option value="Terjadwal">Terjadwal</option>
                        <option value="Mendatang">Mendatang</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Dibatalkan">Dibatalkan</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2">
                <button type="button" onclick="closeModal('modalEditAgenda')" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#005B9F] text-white text-xs font-bold hover:bg-[#004a82]">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Layanan -->
<div id="modalEditLayanan" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6">
        <h3 class="text-base font-bold text-slate-800 mb-4">Ubah Layanan Administrasi</h3>
        <form id="formEditLayanan" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Layanan</label>
                <input type="text" id="editLyJenis" name="jenis" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Jumlah</label>
                <input type="number" id="editLyJumlah" name="jumlah" min="0" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Keterangan / Capaian</label>
                <input type="text" id="editLyKeterangan" name="keterangan" class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2">
                <button type="button" onclick="closeModal('modalEditLayanan')" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#005B9F] text-white text-xs font-bold hover:bg-[#004a82]">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit SOP -->
<div id="modalEditSop" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6">
        <h3 class="text-base font-bold text-slate-800 mb-4">Ubah SOP & Regulasi</h3>
        <form id="formEditSop" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kode SOP</label>
                    <input type="text" id="editSopKode" name="kode" required class="w-full text-xs rounded-xl border-slate-300">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Versi</label>
                    <input type="text" id="editSopVersi" name="versi" required class="w-full text-xs rounded-xl border-slate-300">
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
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                    <select id="editSopStatus" name="status" class="w-full text-xs rounded-xl border-slate-300">
                        <option value="Aktif">Aktif</option>
                        <option value="Revisi">Dalam Revisi</option>
                        <option value="Arsip">Arsip</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Efektif</label>
                <input type="date" id="editSopTanggal" name="tanggal_efektif" class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Keterangan / Alur Prosedur</label>
                <textarea id="editSopKeterangan" name="keterangan" rows="2" class="w-full text-xs rounded-xl border-slate-300"></textarea>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2">
                <button type="button" onclick="closeModal('modalEditSop')" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#005B9F] text-white text-xs font-bold hover:bg-[#004a82]">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Checkbox All Helpers
    function setupCheckAll(masterId, childClass) {
        const master = document.getElementById(masterId);
        if (!master) return;
        master.addEventListener('change', function() {
            const checks = document.querySelectorAll('.' + childClass);
            checks.forEach(c => c.checked = master.checked);
        });
    }
    setupCheckAll('checkAllSm', 'check-item-sm');
    setupCheckAll('checkAllSk', 'check-item-sk');
    setupCheckAll('checkAllAg', 'check-item-ag');
    setupCheckAll('checkAllLy', 'check-item-ly');
    setupCheckAll('checkAllSop', 'check-item-sop');

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    // Modal Edit Openers
    function editSuratMasuk(item) {
        document.getElementById('formEditSuratMasuk').action = '/tvri/tu/surat-masuk/' + item.id;
        document.getElementById('editSmTanggal').value = item.tanggal ? item.tanggal.substring(0, 10) : '';
        document.getElementById('editSmNomor').value = item.nomor || '';
        document.getElementById('editSmPengirim').value = item.pengirim || '';
        document.getElementById('editSmPerihal').value = item.perihal || '';
        document.getElementById('editSmKategori').value = item.kategori || '';
        document.getElementById('editSmStatus').value = item.status || 'Didisposisikan';
        const prevSm = document.getElementById('editSmFilePreview');
        const linkSm = document.getElementById('editSmFileLink');
        const fUrlSm = item.file_url || item.file_path;
        if (fUrlSm && prevSm && linkSm) {
            linkSm.href = '/' + fUrlSm.replace(/^\/+/, '');
            prevSm.classList.remove('hidden');
        } else if (prevSm) {
            prevSm.classList.add('hidden');
        }
        document.getElementById('modalEditSuratMasuk').classList.remove('hidden');
    }

    function editSuratKeluar(item) {
        document.getElementById('formEditSuratKeluar').action = '/tvri/tu/surat-keluar/' + item.id;
        document.getElementById('editSkTanggal').value = item.tanggal ? item.tanggal.substring(0, 10) : '';
        document.getElementById('editSkNomor').value = item.nomor || '';
        document.getElementById('editSkTujuan').value = item.tujuan || '';
        document.getElementById('editSkPerihal').value = item.perihal || '';
        document.getElementById('editSkStatus').value = item.status || 'Terkirim';
        document.getElementById('modalEditSuratKeluar').classList.remove('hidden');
    }

    function editAgenda(item) {
        document.getElementById('formEditAgenda').action = '/tvri/tu/agenda/' + item.id;
        document.getElementById('editAgTanggal').value = item.tanggal ? item.tanggal.substring(0, 10) : '';
        document.getElementById('editAgWaktu').value = item.waktu || '';
        document.getElementById('editAgKegiatan').value = item.kegiatan || '';
        document.getElementById('editAgLokasi').value = item.lokasi || '';
        document.getElementById('editAgPic').value = item.pic || '';
        document.getElementById('editAgJenis').value = item.jenis || 'Hari Ini';
        document.getElementById('editAgStatus').value = item.status || 'Terjadwal';
        document.getElementById('modalEditAgenda').classList.remove('hidden');
    }

    function editLayanan(item) {
        document.getElementById('formEditLayanan').action = '/tvri/tu/layanan/' + item.id;
        document.getElementById('editLyJenis').value = item.jenis || '';
        document.getElementById('editLyJumlah').value = item.jumlah || 0;
        document.getElementById('editLyKeterangan').value = item.keterangan || '';
        document.getElementById('modalEditLayanan').classList.remove('hidden');
    }

    function editSop(item) {
        document.getElementById('formEditSop').action = '/tvri/tu/sop/' + item.id;
        document.getElementById('editSopKode').value = item.kode || '';
        document.getElementById('editSopJudul').value = item.judul || '';
        document.getElementById('editSopKategori').value = item.kategori || '';
        document.getElementById('editSopVersi').value = item.versi || 'v1.0';
        document.getElementById('editSopTanggal').value = item.tanggal_efektif ? item.tanggal_efektif.substring(0, 10) : '';
        document.getElementById('editSopStatus').value = item.status || 'Aktif';
        document.getElementById('editSopKeterangan').value = item.keterangan || '';
                const prev = document.getElementById('editSopFilePreview');
        const link = document.getElementById('editSopFileLink');
        const fUrl = item.file_url || item.file_path;
        if (fUrl && prev && link) {
            link.href = '/' + fUrl.replace(/^\/+/, '');
            prev.classList.remove('hidden');
        } else if (prev) {
            prev.classList.add('hidden');
        }
        document.getElementById('modalEditSop').classList.remove('hidden');
    }
</script>
@endsection
