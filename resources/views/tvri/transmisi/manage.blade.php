@extends('layouts.app')

@section('title', 'Input Data & SOP Bidang Teknik Transmisi - TVRI Maluku')

@section('content')
<div class="space-y-6">

    <!-- ===== HEADER MANAGE TEKNIK TRANSMISI ===== -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#001D38] via-[#00264A] to-[#003B70] text-white shadow-2xl p-6 md:p-8 border border-cyan-400/20">
        <div class="absolute -right-12 -top-16 w-80 h-80 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-12 -bottom-16 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center space-x-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-xs font-semibold text-cyan-200 border border-white/15 mb-3">
                    <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                    <span>Modul Input Data & SOP Bidang Teknik Transmisi</span>
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Kelola Data Transmisi & Jaringan Pemancar</h1>
                <p class="text-sm text-blue-100/90 mt-1">Pengelolaan Stasiun Pemancar DVB-T2, Alarm & Gangguan, Pemeliharaan Terjadwal, Log Aktivitas, Dokumen SOP, dan KPI Transmisi</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('tvri.transmisi.dashboard') }}"
                   class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold backdrop-blur-md border border-white/20 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Kembali ke Dashboard Transmisi</span>
                </a>
                <form action="{{ route('tvri.transmisi.reseed') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memulihkan / menyinkronkan seluruh data Teknik Transmisi sesuai data spesifikasi resmi TVRI Maluku?')">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-semibold transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Pulihkan / Sinkronkan Data</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Alert Notifications -->
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
            @php $activeTab = request('tab', $tab ?? 'pemancar'); @endphp
            <a href="{{ route('tvri.transmisi.manage', ['tab' => 'pemancar']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $activeTab === 'pemancar' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                <span>Stasiun Pemancar ({{ $sites->count() }})</span>
            </a>
            <a href="{{ route('tvri.transmisi.manage', ['tab' => 'gangguan']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $activeTab === 'gangguan' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>Alarm & Gangguan ({{ $gangguans->count() }})</span>
            </a>
            <a href="{{ route('tvri.transmisi.manage', ['tab' => 'pemeliharaan']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $activeTab === 'pemeliharaan' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Pemeliharaan Terjadwal ({{ $pemeliharaans->count() }})</span>
            </a>
            <a href="{{ route('tvri.transmisi.manage', ['tab' => 'log']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $activeTab === 'log' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Log Aktivitas ({{ $logs->count() }})</span>
            </a>
            <a href="{{ route('tvri.transmisi.manage', ['tab' => 'sop']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $activeTab === 'sop' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>SOP & Dokumen Transmisi ({{ $sops->count() }})</span>
            </a>
            <a href="{{ route('tvri.transmisi.manage', ['tab' => 'ringkasan']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $activeTab === 'ringkasan' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                <span>Parameter & KPI Transmisi</span>
            </a>
        </div>
    </div>

    <!-- TAB 1: STASIUN PEMANCAR -->
    @if($activeTab === 'pemancar')
    <div class="space-y-6">
        <!-- Form Tambah Stasiun Pemancar -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-cyan-600"></span>
                <span>Tambah Stasiun Pemancar Baru</span>
            </h3>
            <form action="{{ route('tvri.transmisi.pemancar.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Nama Site</label>
                    <input type="text" name="nama" required placeholder="Contoh: Ambon" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Lokasi / Wilayah</label>
                    <input type="text" name="lokasi" required placeholder="Contoh: Kota + Kab. Ambon" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Kanal Siaran</label>
                    <input type="text" name="kanal" required placeholder="Contoh: CH 28" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Frekuensi</label>
                    <input type="text" name="frekuensi" required placeholder="Contoh: 530 MHz" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Daya Pancar (kW)</label>
                    <input type="number" step="0.1" name="daya_kw" required placeholder="5.0" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Cakupan Wilayah</label>
                    <input type="text" name="coverage" placeholder="Contoh: Kota + Kab" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Status Operasional</label>
                    <select name="status" required class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                        <option value="On-Air">On-Air</option>
                        <option value="Maintenance">Maintenance</option>
                        <option value="Gangguan">Gangguan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Skor Kualitas (1 - 5)</label>
                    <input type="number" step="0.1" min="1" max="5" name="kualitas" value="4.6" required class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white rounded-xl text-xs font-bold shadow-md transition-all">
                        Simpan Stasiun Pemancar
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Stasiun Pemancar -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-slate-800">Daftar Stasiun Pemancar Terdaftar ({{ $sites->count() }})</h3>
                <form id="formBulkPemancar" action="{{ route('tvri.transmisi.pemancar.bulk-destroy') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus stasiun pemancar yang dipilih?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="btnBulkPemancar" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                        Hapus Terpilih
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase text-[10px]">
                            <th class="p-2.5 w-8">
                                <input type="checkbox" id="checkAllPemancar" class="rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
                            </th>
                            <th class="p-2.5">Nama Site</th>
                            <th class="p-2.5">Lokasi</th>
                            <th class="p-2.5">Kanal / Freq</th>
                            <th class="p-2.5 text-center">Daya</th>
                            <th class="p-2.5 text-center">Kualitas</th>
                            <th class="p-2.5 text-center">Status</th>
                            <th class="p-2.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($sites as $st)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-2.5">
                                <input type="checkbox" name="ids[]" value="{{ $st->id }}" form="formBulkPemancar" class="site-cb rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
                            </td>
                            <td class="p-2.5 font-bold text-[#00264A]">{{ $st->nama }}</td>
                            <td class="p-2.5 text-slate-600">{{ $st->lokasi }}</td>
                            <td class="p-2.5 font-mono text-slate-500">{{ $st->kanal }} ({{ $st->frekuensi }})</td>
                            <td class="p-2.5 text-center font-bold text-slate-700">{{ $st->daya_kw }} kW</td>
                            <td class="p-2.5 text-center font-bold text-emerald-600">{{ number_format($st->kualitas, 1) }} / 5.0</td>
                            <td class="p-2.5 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase
                                    {{ $st->status === 'On-Air' ? 'bg-emerald-100 text-emerald-700' : ($st->status === 'Gangguan' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
                                    {{ $st->status }}
                                </span>
                            </td>
                            <td class="p-2.5 text-right space-x-1 whitespace-nowrap">
                                <button type="button" onclick="openEditPemancarModal({{ json_encode($st) }})" class="p-1.5 text-cyan-600 hover:bg-cyan-50 rounded-lg inline-flex items-center justify-center" title="Ubah Data">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form action="{{ route('tvri.transmisi.pemancar.destroy', $st->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus stasiun pemancar {{ $st->nama }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg inline-flex items-center justify-center" title="Hapus Pemancar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center py-6 text-slate-400">Belum ada stasiun pemancar terdaftar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- TAB 2: ALARM & GANGGUAN -->
    @if($activeTab === 'gangguan')
    <div class="space-y-6">
        <!-- Form Tambah Gangguan -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-600"></span>
                <span>Catat Alarm / Gangguan Pemancar</span>
            </h3>
            <form action="{{ route('tvri.transmisi.gangguan.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Tanggal & Waktu</label>
                    <input type="text" name="tanggal" required value="{{ date('Y-m-d') }}" placeholder="2025-09-02" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Lokasi Site</label>
                    <input type="text" name="site" required placeholder="Contoh: Sanana" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Jenis Gangguan</label>
                    <input type="text" name="jenis" required placeholder="Contoh: Loss of Signal / Power Supply" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Status Penanganan</label>
                    <select name="status" required class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                        <option value="Proses">Dalam Proses</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                </div>
                <div class="md:col-span-4">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Keterangan / Tindak Lanjut</label>
                    <input type="text" name="keterangan" placeholder="Contoh: Gangguan feeder akibat angin kencang (08:15 WIT)" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-md transition-all">
                        Simpan Catatan Gangguan
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Gangguan -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-slate-800">Daftar Alarm & Riwayat Gangguan ({{ $gangguans->count() }})</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase text-[10px]">
                            <th class="p-2.5">Tanggal</th>
                            <th class="p-2.5">Site</th>
                            <th class="p-2.5">Jenis Gangguan</th>
                            <th class="p-2.5">Keterangan</th>
                            <th class="p-2.5 text-center">Status</th>
                            <th class="p-2.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($gangguans as $g)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-2.5 text-slate-500">{{ $g->tanggal ? (\Carbon\Carbon::parse($g->tanggal)->format('d M Y')) : '-' }}</td>
                            <td class="p-2.5 font-bold text-[#00264A]">{{ $g->site }}</td>
                            <td class="p-2.5 text-slate-700 font-semibold">{{ $g->jenis }}</td>
                            <td class="p-2.5 text-slate-600">{{ $g->keterangan }}</td>
                            <td class="p-2.5 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase
                                    {{ $g->status === 'Selesai' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $g->status }}
                                </span>
                            </td>
                            <td class="p-2.5 text-right">
                                <form action="{{ route('tvri.transmisi.gangguan.destroy', $g->id) }}" method="POST" onsubmit="return confirm('Hapus data gangguan ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-6 text-slate-400">Tidak ada gangguan aktif.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- TAB 3: PEMELIHARAAN TERJADWAL -->
    @if($activeTab === 'pemeliharaan')
    <div class="space-y-6">
        <!-- Form Tambah Pemeliharaan -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                <span>Tambah Jadwal Pemeliharaan Pemancar</span>
            </h3>
            <form action="{{ route('tvri.transmisi.pemeliharaan.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Tanggal</label>
                    <input type="text" name="tanggal" required placeholder="3 Sep 2025" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Site / Lokasi</label>
                    <input type="text" name="site" required placeholder="Contoh: Masohi" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Jenis Kegiatan Pemeliharaan</label>
                    <input type="text" name="kegiatan" required placeholder="Contoh: Pembersihan Antena / Kalibrasi Peralatan" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Status</label>
                    <select name="status" required class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                        <option value="Terjadwal">Terjadwal</option>
                        <option value="Dalam Proses">Dalam Proses</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                </div>
                <div class="md:col-span-4">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Keterangan Teknis</label>
                    <input type="text" name="keterangan" placeholder="Contoh: Inspeksi konektor heliax dan pembersihan elemen antena" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold shadow-md transition-all">
                        Simpan Jadwal Pemeliharaan
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Pemeliharaan -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4">Agenda Pemeliharaan Terjadwal ({{ $pemeliharaans->count() }})</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase text-[10px]">
                            <th class="p-2.5">Tanggal</th>
                            <th class="p-2.5">Site</th>
                            <th class="p-2.5">Kegiatan</th>
                            <th class="p-2.5">Keterangan</th>
                            <th class="p-2.5 text-center">Status</th>
                            <th class="p-2.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pemeliharaans as $p)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-2.5 font-bold text-slate-700">{{ $p->tanggal }}</td>
                            <td class="p-2.5 font-bold text-[#00264A]">{{ $p->site }}</td>
                            <td class="p-2.5 text-slate-800 font-semibold">{{ $p->kegiatan }}</td>
                            <td class="p-2.5 text-slate-600">{{ $p->keterangan }}</td>
                            <td class="p-2.5 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-amber-100 text-amber-800">
                                    {{ $p->status }}
                                </span>
                            </td>
                            <td class="p-2.5 text-right">
                                <form action="{{ route('tvri.transmisi.pemeliharaan.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-6 text-slate-400">Belum ada agenda pemeliharaan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- TAB 4: LOG AKTIVITAS -->
    @if($activeTab === 'log')
    <div class="space-y-6">
        <!-- Form Tambah Log -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                <span>Catat Log Aktivitas Transmisi</span>
            </h3>
            <form action="{{ route('tvri.transmisi.log.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Waktu</label>
                    <input type="text" name="waktu" required placeholder="2 Sep 10:23" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Site / Objek</label>
                    <input type="text" name="site" placeholder="Contoh: Ambon / All Sites" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Aktivitas / Kegiatan</label>
                    <input type="text" name="aktivitas" required placeholder="Contoh: Cek status site Ambon" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Operator / Teknisi</label>
                    <input type="text" name="user" required placeholder="admin / teknisi1 / operator" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md transition-all">
                        Simpan Log Aktivitas
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Log -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4">Log Aktivitas Terkini ({{ $logs->count() }})</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase text-[10px]">
                            <th class="p-2.5">Waktu</th>
                            <th class="p-2.5">Site</th>
                            <th class="p-2.5">Aktivitas</th>
                            <th class="p-2.5">Petugas</th>
                            <th class="p-2.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($logs as $l)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-2.5 font-mono text-slate-500">{{ $l->waktu }}</td>
                            <td class="p-2.5 font-bold text-[#00264A]">{{ $l->site ?? '-' }}</td>
                            <td class="p-2.5 text-slate-800 font-semibold">{{ $l->aktivitas }}</td>
                            <td class="p-2.5"><span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-slate-100 text-slate-700 font-mono">{{ $l->user }}</span></td>
                            <td class="p-2.5 text-right">
                                <form action="{{ route('tvri.transmisi.log.destroy', $l->id) }}" method="POST" onsubmit="return confirm('Hapus log ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-6 text-slate-400">Belum ada log aktivitas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- TAB 5: SOP & DOKUMEN TRANSMISI -->
    @if($activeTab === 'sop')
    <div class="space-y-6">
        <!-- Form Tambah SOP -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-cyan-600"></span>
                <span>Tambah Dokumen & SOP Transmisi</span>
            </h3>
            <form action="{{ route('tvri.transmisi.sop.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Kode Dokumen</label>
                    <input type="text" name="kode" placeholder="Contoh: SOP-TX-04" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Judul SOP / Laporan</label>
                    <input type="text" name="judul" required placeholder="Contoh: SOP Pengoperasian Transmitter" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Kategori Dokumen</label>
                    <select name="kategori" required class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                        <option value="SOP Transmisi">SOP Transmisi</option>
                        <option value="Laporan Harian">Laporan Harian</option>
                        <option value="Laporan Bulanan">Laporan Bulanan</option>
                        <option value="Manual Peralatan">Manual Peralatan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Versi</label>
                    <input type="text" name="versi" value="v1.0" required class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Keterangan / Deskripsi Singkat</label>
                    <input type="text" name="keterangan" placeholder="Contoh: Prosedur operasional standar penanganan gangguan..." class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Status</label>
                    <select name="status" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                        <option value="Aktif">Aktif</option>
                        <option value="Draft">Draft</option>
                        <option value="Revisi">Revisi</option>
                    </select>
                </div>
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white rounded-xl text-xs font-bold shadow-md transition-all">
                        Simpan SOP Transmisi
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel SOP -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4">Daftar Dokumen & Standar Operasional Prosedur ({{ $sops->count() }})</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase text-[10px]">
                            <th class="p-2.5">Kode</th>
                            <th class="p-2.5">Judul SOP / Dokumen</th>
                            <th class="p-2.5">Kategori</th>
                            <th class="p-2.5">Versi</th>
                            <th class="p-2.5 text-center">Status</th>
                            <th class="p-2.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($sops as $sp)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-2.5 font-mono text-cyan-600 font-bold">{{ $sp->kode ?? '-' }}</td>
                            <td class="p-2.5">
                                <p class="font-bold text-[#00264A]">{{ $sp->judul }}</p>
                                <p class="text-[10px] text-slate-400">{{ $sp->keterangan }}</p>
                            </td>
                            <td class="p-2.5"><span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-cyan-50 text-cyan-700 border border-cyan-200">{{ $sp->kategori }}</span></td>
                            <td class="p-2.5 font-mono text-slate-600">{{ $sp->versi }}</td>
                            <td class="p-2.5 text-center"><span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-100 text-emerald-700">{{ $sp->status }}</span></td>
                            <td class="p-2.5 text-right">
                                <form action="{{ route('tvri.transmisi.sop.destroy', $sp->id) }}" method="POST" onsubmit="return confirm('Hapus SOP ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-6 text-slate-400">Belum ada SOP terdaftar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- TAB 6: PARAMETER & KPI TRANSMISI -->
    @if($activeTab === 'ringkasan')
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center space-x-2">
            <span class="w-2.5 h-2.5 rounded-full bg-cyan-600"></span>
            <span>Ubah Parameter Utama, Slogan & Key Performance Indicators (KPI)</span>
        </h3>
        <form action="{{ route('tvri.transmisi.ringkasan.update') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Informasi Header -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-cyan-700 mb-3 pb-1 border-b border-slate-100">Informasi Header & Slogan</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Judul Dashboard</label>
                        <input type="text" name="judul" value="{{ old('judul', $ringkasan->judul) }}" required class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Periode Operasional</label>
                        <input type="text" name="periode" value="{{ old('periode', $ringkasan->periode) }}" required class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-600 mb-1">Slogan Utama</label>
                        <input type="text" name="slogan" value="{{ old('slogan', $ringkasan->slogan) }}" required class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Slogan Tambahan</label>
                        <input type="text" name="slogan_tambahan" value="{{ old('slogan_tambahan', $ringkasan->slogan_tambahan) }}" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Waktu & Status Monitoring</label>
                        <input type="text" name="waktu_status" value="{{ old('waktu_status', $ringkasan->waktu_status) }}" required class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                    </div>
                </div>
            </div>

            <!-- Key Performance Indicators (KPI) -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-cyan-700 mb-3 pb-1 border-b border-slate-100">Key Performance Indicators (KPI)</h4>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Total Site Pemancar</label>
                        <input type="number" name="total_site" value="{{ old('total_site', $ringkasan->total_site) }}" required class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Diff Total Site</label>
                        <input type="text" name="total_site_diff" value="{{ old('total_site_diff', $ringkasan->total_site_diff) }}" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Siaran On-Air (%)</label>
                        <input type="number" name="on_air_pct" value="{{ old('on_air_pct', $ringkasan->on_air_pct) }}" required class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Target On-Air (%)</label>
                        <input type="number" name="on_air_target" value="{{ old('on_air_target', $ringkasan->on_air_target) }}" required class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Cakupan Wilayah (%)</label>
                        <input type="number" name="cakupan_wilayah_pct" value="{{ old('cakupan_wilayah_pct', $ringkasan->cakupan_wilayah_pct) }}" required class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Diff Cakupan</label>
                        <input type="text" name="cakupan_diff" value="{{ old('cakupan_diff', $ringkasan->cakupan_diff) }}" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Kualitas Rata-rata (1-5)</label>
                        <input type="number" step="0.1" name="kualitas_rata" value="{{ old('kualitas_rata', $ringkasan->kualitas_rata) }}" required class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Diff Kualitas</label>
                        <input type="text" name="kualitas_diff" value="{{ old('kualitas_diff', $ringkasan->kualitas_diff) }}" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Gangguan Aktif</label>
                        <input type="number" name="gangguan_aktif" value="{{ old('gangguan_aktif', $ringkasan->gangguan_aktif) }}" required class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Diff Gangguan</label>
                        <input type="text" name="gangguan_diff" value="{{ old('gangguan_diff', $ringkasan->gangguan_diff) }}" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Pemeliharaan Berjalan</label>
                        <input type="number" name="pemeliharaan_berjalan" value="{{ old('pemeliharaan_berjalan', $ringkasan->pemeliharaan_berjalan) }}" required class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Efisiensi Daya (%)</label>
                        <input type="number" name="efisiensi_daya_pct" value="{{ old('efisiensi_daya_pct', $ringkasan->efisiensi_daya_pct) }}" required class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                    </div>
                </div>
            </div>

            @php
                $curPlatforms = $ringkasan->platform_siaran_json ?: [
                    ['platform' => 'TV Digital DVB-T2', 'status' => 'On-Air 100%', 'keterangan' => '28 Site Pemancar Aktif Terestrial', 'ikon' => 'tv'],
                    ['platform' => 'Satelit Telkom-4', 'status' => 'On-Air 100%', 'keterangan' => 'Transponder 3900 V 3000', 'ikon' => 'satellite'],
                    ['platform' => 'IPTV/Streaming TVRI Live', 'status' => 'On-Air 100%', 'keterangan' => 'TVRI Klik & OTT Platforms', 'ikon' => 'wifi'],
                    ['platform' => 'Radio Visual (Backhaul)', 'status' => 'Normal 100%', 'keterangan' => 'Link Mikrowave Antar Site', 'ikon' => 'radio'],
                ];
                $curKs = $ringkasan->kualitas_sinyal_json ?: [
                    'skor' => 4.6,
                    'predikat' => 'Sangat Baik',
                    'breakdown' => [
                        ['bintang' => 5, 'persen' => 52],
                        ['bintang' => 4, 'persen' => 36],
                        ['bintang' => 3, 'persen' => 9],
                        ['bintang' => 2, 'persen' => 2],
                        ['bintang' => 1, 'persen' => 1],
                    ]
                ];
                $bMap = [];
                if (!empty($curKs['breakdown'])) {
                    foreach ($curKs['breakdown'] as $b) {
                        $bMap[$b['bintang'] ?? 0] = $b['persen'] ?? 0;
                    }
                }
                $curParams = $ringkasan->parameter_teknis_json ?: [
                    ['param' => 'Level Sinyal', 'nilai' => '-48 dBm', 'status' => 'Baik'],
                    ['param' => 'SNR (Signal to Noise)', 'nilai' => '32 dB', 'status' => 'Baik'],
                    ['param' => 'Bit Error Rate (BER)', 'nilai' => '1.0E-6', 'status' => 'Baik'],
                    ['param' => 'Modulasi Digital', 'nilai' => '64QAM', 'status' => 'Normal'],
                    ['param' => 'Bandwidth Kanal', 'nilai' => '8 MHz', 'status' => 'Normal'],
                    ['param' => 'Suhu Pemancar TX', 'nilai' => '42 °C', 'status' => 'Normal'],
                    ['param' => 'Tegangan Listrik', 'nilai' => '220 V', 'status' => 'Normal'],
                    ['param' => 'Arus Kerja', 'nilai' => '12 A', 'status' => 'Normal'],
                    ['param' => 'Daya Pancar (ERP)', 'nilai' => '5 kW', 'status' => 'Normal'],
                ];
                $curInfraMap = [];
                if (!empty($ringkasan->infrastruktur_json)) {
                    foreach ($ringkasan->infrastruktur_json as $infItem) {
                        $curInfraMap[$infItem['item']] = $infItem['persen'];
                    }
                }
            @endphp

            <!-- Status Siaran per Platform -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-cyan-700 mb-3 pb-1 border-b border-slate-100 flex items-center justify-between">
                    <span>Status Siaran per Platform (Distribusi Multiplatform)</span>
                    <span class="text-[10px] text-slate-400 font-normal">4 Platform Siaran Utama</span>
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($curPlatforms as $idx => $pf)
                    <div class="p-3 bg-slate-50/80 rounded-xl border border-slate-200/80">
                        <input type="hidden" name="platforms[{{ $idx }}][platform]" value="{{ $pf['platform'] }}">
                        <input type="hidden" name="platforms[{{ $idx }}][ikon]" value="{{ $pf['ikon'] ?? 'tv' }}">
                        <p class="text-xs font-bold text-[#00264A] mb-2">{{ $pf['platform'] }}</p>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 mb-0.5">Status Siaran</label>
                                <input type="text" name="platforms[{{ $idx }}][status]" value="{{ $pf['status'] }}" class="w-full text-xs rounded-lg border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 mb-0.5">Keterangan / Detil</label>
                                <input type="text" name="platforms[{{ $idx }}][keterangan]" value="{{ $pf['keterangan'] ?? '' }}" class="w-full text-xs rounded-lg border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Kualitas Sinyal & Infrastruktur -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kualitas Sinyal -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-cyan-700 mb-3 pb-1 border-b border-slate-100">Kualitas Sinyal Nasional & Regional</h4>
                    <div class="p-3 bg-slate-50/80 rounded-xl border border-slate-200/80 space-y-3">
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 mb-0.5">Predikat Kualitas</label>
                                <input type="text" name="kualitas_sinyal[predikat]" value="{{ $curKs['predikat'] ?? 'Sangat Baik' }}" class="w-full text-xs rounded-lg border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 mb-0.5">Skor (1-5)</label>
                                <input type="number" step="0.1" name="kualitas_sinyal[skor]" value="{{ $curKs['skor'] ?? 4.6 }}" class="w-full text-xs rounded-lg border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-500 mb-1">Persentase Hasil Pengukuran (%)</p>
                            <div class="grid grid-cols-5 gap-1.5">
                                <div>
                                    <label class="block text-[9px] text-slate-400 font-bold">5 Excellent</label>
                                    <input type="number" name="kualitas_sinyal[b5]" value="{{ $bMap[5] ?? 52 }}" class="w-full text-xs rounded-lg border-slate-200 text-center">
                                </div>
                                <div>
                                    <label class="block text-[9px] text-slate-400 font-bold">4 Good</label>
                                    <input type="number" name="kualitas_sinyal[b4]" value="{{ $bMap[4] ?? 36 }}" class="w-full text-xs rounded-lg border-slate-200 text-center">
                                </div>
                                <div>
                                    <label class="block text-[9px] text-slate-400 font-bold">3 Fair</label>
                                    <input type="number" name="kualitas_sinyal[b3]" value="{{ $bMap[3] ?? 9 }}" class="w-full text-xs rounded-lg border-slate-200 text-center">
                                </div>
                                <div>
                                    <label class="block text-[9px] text-slate-400 font-bold">2 Poor</label>
                                    <input type="number" name="kualitas_sinyal[b2]" value="{{ $bMap[2] ?? 2 }}" class="w-full text-xs rounded-lg border-slate-200 text-center">
                                </div>
                                <div>
                                    <label class="block text-[9px] text-slate-400 font-bold">1 Bad</label>
                                    <input type="number" name="kualitas_sinyal[b1]" value="{{ $bMap[1] ?? 1 }}" class="w-full text-xs rounded-lg border-slate-200 text-center">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ketersediaan Infrastruktur -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-cyan-700 mb-3 pb-1 border-b border-slate-100">Ketersediaan Infrastruktur (%)</h4>
                    <div class="p-3 bg-slate-50/80 rounded-xl border border-slate-200/80 space-y-2">
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 mb-0.5">Menara Pemancar (%)</label>
                                <input type="number" name="infrastruktur[menara]" value="{{ $curInfraMap['Menara Pemancar'] ?? 96 }}" class="w-full text-xs rounded-lg border-slate-200">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 mb-0.5">Antena Pemancar (%)</label>
                                <input type="number" name="infrastruktur[antena]" value="{{ $curInfraMap['Antena Pemancar'] ?? 98 }}" class="w-full text-xs rounded-lg border-slate-200">
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 mb-0.5">Perangkat TX (%)</label>
                                <input type="number" name="infrastruktur[tx]" value="{{ $curInfraMap['Perangkat TX'] ?? 95 }}" class="w-full text-xs rounded-lg border-slate-200">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 mb-0.5">Genset Cadangan (%)</label>
                                <input type="number" name="infrastruktur[genset]" value="{{ $curInfraMap['Genset & Power Cadangan'] ?? 93 }}" class="w-full text-xs rounded-lg border-slate-200">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 mb-0.5">Catu Daya (UPS) (%)</label>
                                <input type="number" name="infrastruktur[catu]" value="{{ $curInfraMap['Catu Daya (UPS & PLN)'] ?? 97 }}" class="w-full text-xs rounded-lg border-slate-200">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Parameter Teknis (Real-Time Live Telemetry) -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-cyan-700 mb-3 pb-1 border-b border-slate-100 flex items-center justify-between">
                    <span>Parameter Teknis (Live Telemetry Instrumen TX)</span>
                    <span class="text-[10px] text-slate-400 font-normal">9 Parameter Teknis Instrumen Pemancar</span>
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($curParams as $pIdx => $paramItem)
                    <div class="p-3 bg-slate-50/80 rounded-xl border border-slate-200/80">
                        <input type="hidden" name="params_teknis[{{ $pIdx }}][param]" value="{{ $paramItem['param'] }}">
                        <p class="text-[11px] font-bold text-[#00264A] mb-1.5">{{ $paramItem['param'] }}</p>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[9px] font-bold text-slate-400 mb-0.5">Nilai Pengukuran</label>
                                <input type="text" name="params_teknis[{{ $pIdx }}][nilai]" value="{{ $paramItem['nilai'] }}" class="w-full text-xs rounded-lg border-slate-200">
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-slate-400 mb-0.5">Status</label>
                                <select name="params_teknis[{{ $pIdx }}][status]" class="w-full text-xs rounded-lg border-slate-200">
                                    <option value="Baik" {{ ($paramItem['status'] ?? '') === 'Baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="Normal" {{ ($paramItem['status'] ?? '') === 'Normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="Perhatian" {{ ($paramItem['status'] ?? '') === 'Perhatian' ? 'selected' : '' }}>Perhatian</option>
                                    <option value="Kritis" {{ ($paramItem['status'] ?? '') === 'Kritis' ? 'selected' : '' }}>Kritis</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Footer & Quotes -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-cyan-700 mb-3 pb-1 border-b border-slate-100">Informasi Footer</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Slogan Footer</label>
                        <input type="text" name="footer_slogan" value="{{ old('footer_slogan', $ringkasan->footer_slogan) }}" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Quote Penutup</label>
                        <input type="text" name="footer_quote" value="{{ old('footer_quote', $ringkasan->footer_quote) }}" class="w-full text-xs rounded-xl border-slate-200 focus:ring-cyan-500 focus:border-cyan-500">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white rounded-xl text-xs font-bold shadow-md transition-all">
                    Simpan Perubahan KPI & Parameter Transmisi
                </button>
            </div>
        </form>
    </div>
    @endif

</div>

<!-- Modal Edit Pemancar -->
<div id="editPemancarModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <h3 class="text-sm font-bold text-[#00264A]">Ubah Data Stasiun Pemancar</h3>
            <button type="button" onclick="closeEditPemancarModal()" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>
        <form id="editPemancarForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Nama Site</label>
                    <input type="text" id="edit_nama" name="nama" required class="w-full text-xs rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Lokasi</label>
                    <input type="text" id="edit_lokasi" name="lokasi" required class="w-full text-xs rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Kanal</label>
                    <input type="text" id="edit_kanal" name="kanal" required class="w-full text-xs rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Frekuensi</label>
                    <input type="text" id="edit_frekuensi" name="frekuensi" required class="w-full text-xs rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Daya (kW)</label>
                    <input type="number" step="0.1" id="edit_daya_kw" name="daya_kw" required class="w-full text-xs rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Kualitas (1-5)</label>
                    <input type="number" step="0.1" min="1" max="5" id="edit_kualitas" name="kualitas" required class="w-full text-xs rounded-xl border-slate-200">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Status</label>
                    <select id="edit_status" name="status" required class="w-full text-xs rounded-xl border-slate-200">
                        <option value="On-Air">On-Air</option>
                        <option value="Maintenance">Maintenance</option>
                        <option value="Gangguan">Gangguan</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeEditPemancarModal()" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold">Batal</button>
                <button type="submit" class="px-5 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-xl text-xs font-bold">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditPemancarModal(st) {
        document.getElementById('editPemancarForm').action = '/tvri/transmisi/pemancar/' + st.id;
        document.getElementById('edit_nama').value = st.nama;
        document.getElementById('edit_lokasi').value = st.lokasi;
        document.getElementById('edit_kanal').value = st.kanal;
        document.getElementById('edit_frekuensi').value = st.frekuensi;
        document.getElementById('edit_daya_kw').value = st.daya_kw;
        document.getElementById('edit_kualitas').value = st.kualitas;
        document.getElementById('edit_status').value = st.status;
        document.getElementById('editPemancarModal').classList.remove('hidden');
    }
    function closeEditPemancarModal() {
        document.getElementById('editPemancarModal').classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const checkAllPemancar = document.getElementById('checkAllPemancar');
        const btnBulkPemancar = document.getElementById('btnBulkPemancar');
        const formBulkPemancar = document.getElementById('formBulkPemancar');

        function updateBulkPemancarBtn() {
            const checked = document.querySelectorAll('.site-cb:checked').length;
            if (btnBulkPemancar) {
                if (checked > 0) {
                    btnBulkPemancar.innerText = 'Hapus Terpilih (' + checked + ')';
                } else {
                    btnBulkPemancar.innerText = 'Hapus Terpilih';
                }
            }
        }

        if (checkAllPemancar) {
            checkAllPemancar.addEventListener('change', function() {
                document.querySelectorAll('.site-cb').forEach(cb => cb.checked = checkAllPemancar.checked);
                updateBulkPemancarBtn();
            });
        }

        document.querySelectorAll('.site-cb').forEach(cb => {
            cb.addEventListener('change', updateBulkPemancarBtn);
        });

        if (formBulkPemancar) {
            formBulkPemancar.addEventListener('submit', function(e) {
                const checked = document.querySelectorAll('.site-cb:checked').length;
                if (checked === 0) {
                    e.preventDefault();
                    alert('Silakan pilih setidaknya satu stasiun pemancar untuk dihapus.');
                    return false;
                }
            });
        }
    });
</script>
@endsection
