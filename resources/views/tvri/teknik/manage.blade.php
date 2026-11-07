@extends('layouts.app')

@section('title', 'Input Data & SOP Bidang Teknik Studio - TVRI Maluku')

@section('content')
<div class="space-y-6">

    <!-- ===== HEADER MANAGE TEKNIK STUDIO ===== -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#00142A] via-[#002244] to-[#003B70] text-white shadow-2xl p-6 md:p-8 border border-cyan-400/20">
        <div class="absolute -right-12 -top-16 w-80 h-80 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-12 -bottom-16 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center space-x-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-xs font-semibold text-cyan-200 border border-white/15 mb-3">
                    <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                    <span>Modul Input Data & SOP Bidang Teknik Studio & Fasilitas</span>
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Kelola Data Teknik Studio & Penyiaran</h1>
                <p class="text-sm text-blue-100/90 mt-1">Pengelolaan Jadwal Produksi, Maintenance Peralatan, Insiden Siaran, Dokumen SOP, dan Parameter Dashboard</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('tvri.teknik.dashboard') }}"
                   class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold backdrop-blur-md border border-white/20 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Kembali ke Dashboard Teknik</span>
                </a>
                <form action="{{ route('tvri.teknik.reseed') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memulihkan / menyinkronkan seluruh data Teknik Studio sesuai data spesifikasi resmi?')">
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
            @php $activeTab = request('tab', $tab ?? 'jadwal'); @endphp
            <a href="{{ route('tvri.teknik.manage', ['tab' => 'jadwal']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $activeTab === 'jadwal' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Jadwal Produksi ({{ $jadwals->count() }})</span>
            </a>
            <a href="{{ route('tvri.teknik.manage', ['tab' => 'maintenance']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $activeTab === 'maintenance' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Maintenance & Servis ({{ $maintenances->count() }})</span>
            </a>
            <a href="{{ route('tvri.teknik.manage', ['tab' => 'insiden']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $activeTab === 'insiden' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>Insiden & Gangguan ({{ $insidens->count() }})</span>
            </a>
            <a href="{{ route('tvri.teknik.manage', ['tab' => 'sop']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $activeTab === 'sop' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>SOP & Regulasi Teknik ({{ $sops->count() }})</span>
            </a>
            <a href="{{ route('tvri.teknik.manage', ['tab' => 'transmitter']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $activeTab === 'transmitter' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                <span>Stasiun Pemancar / Tx ({{ $transmitters->count() }})</span>
            </a>
            <a href="{{ route('tvri.teknik.manage', ['tab' => 'ringkasan']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $activeTab === 'ringkasan' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                <span>Parameter & KPI Teknik</span>
            </a>
        </div>
    </div>

    <!-- TAB 1: JADWAL PRODUKSI -->
    @if($activeTab === 'jadwal')
    <div class="space-y-6">
        <!-- Form Tambah Jadwal -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                <span>Tambah Jadwal Produksi & Teknik</span>
            </h3>
            <form action="{{ route('tvri.teknik.jadwal.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Waktu</label>
                    <input type="text" name="waktu" placeholder="Contoh: 09:00" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Program / Kegiatan</label>
                    <input type="text" name="program" placeholder="Contoh: Dialog Maluku" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Lokasi</label>
                    <input type="text" name="lokasi" placeholder="Studio 1 / Studio 2 / OB Van" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                    <select name="status" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Selesai">Selesai</option>
                        <option value="Sedang Berjalan">Sedang Berjalan</option>
                        <option value="Siap">Siap</option>
                        <option value="Terjadwal" selected>Terjadwal</option>
                    </select>
                </div>
                <div class="md:col-span-4">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kebutuhan Teknik</label>
                    <input type="text" name="kebutuhan" placeholder="Kamera, Audio, CG, LiveU, Playout" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md transition-all">
                        Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Jadwal -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Daftar Jadwal Produksi & Penyiaran</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Total {{ $jadwals->count() }} agenda produksi terjadwal</p>
                </div>
                <form id="formBulkJadwal" action="{{ route('tvri.teknik.jadwal.bulk-destroy') }}" method="POST" onsubmit="return confirm('Hapus jadwal yang dipilih?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="btnBulkJadwal" disabled class="px-3 py-1.5 rounded-lg bg-rose-600 text-white text-xs font-semibold disabled:opacity-40 disabled:cursor-not-allowed">
                        Hapus Terpilih
                    </button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <tr>
                            <th class="p-3.5 w-10 text-center"><input type="checkbox" id="checkAllJadwal" class="rounded border-slate-300"></th>
                            <th class="p-3.5">Waktu</th>
                            <th class="p-3.5">Program / Kegiatan</th>
                            <th class="p-3.5">Lokasi</th>
                            <th class="p-3.5">Kebutuhan Teknik</th>
                            <th class="p-3.5 text-center">Status</th>
                            <th class="p-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($jadwals as $j)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-3.5 text-center">
                                <input type="checkbox" name="ids[]" value="{{ $j->id }}" form="formBulkJadwal" class="check-item-jadwal rounded border-slate-300">
                            </td>
                            <td class="p-3.5 font-mono font-bold text-cyan-800">{{ $j->waktu }}</td>
                            <td class="p-3.5 font-bold text-slate-900">{{ $j->program }}</td>
                            <td class="p-3.5">{{ $j->lokasi }}</td>
                            <td class="p-3.5 text-slate-500">{{ $j->kebutuhan }}</td>
                            <td class="p-3.5 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $j->badge }}">
                                    {{ $j->status }}
                                </span>
                            </td>
                            <td class="p-3.5 text-right space-x-1">
                                <button type="button" onclick="openEditJadwal({{ json_encode($j) }})" class="p-1 rounded-lg text-blue-600 hover:bg-blue-50">Edit</button>
                                <form action="{{ route('tvri.teknik.jadwal.destroy', $j->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 rounded-lg text-rose-600 hover:bg-rose-50">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="p-6 text-center text-slate-400">Belum ada jadwal produksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- TAB 2: MAINTENANCE & SERVIS -->
    @if($activeTab === 'maintenance')
    <div class="space-y-6">
        <!-- Form Tambah Maintenance -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-cyan-600"></span>
                <span>Catat Maintenance & Servis Peralatan</span>
            </h3>
            <form action="{{ route('tvri.teknik.maintenance.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal</label>
                    <input type="text" name="tanggal" placeholder="Contoh: 5 Sep 2025" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Peralatan</label>
                    <input type="text" name="peralatan" placeholder="Contoh: Kamera Studio 1 / Genset / Transmitter" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Maintenance</label>
                    <input type="text" name="jenis" placeholder="Preventive / Servis / Cleaning" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">PIC / Tim</label>
                    <input type="text" name="pic" placeholder="Tim RF / Tim Studio / Tim Power" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                    <select name="status" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Selesai">Selesai</option>
                        <option value="On Progress">On Progress</option>
                        <option value="Terjadwal" selected>Terjadwal</option>
                    </select>
                </div>
                <div class="md:col-span-4 flex items-end">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-bold shadow-md transition-all">
                        Simpan Maintenance
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Maintenance -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Daftar Maintenance & Servis Peralatan</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Total {{ $maintenances->count() }} log pemeliharaan terdata</p>
                </div>
                <form id="formBulkMaint" action="{{ route('tvri.teknik.maintenance.bulk-destroy') }}" method="POST" onsubmit="return confirm('Hapus maintenance yang dipilih?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="btnBulkMaint" disabled class="px-3 py-1.5 rounded-lg bg-rose-600 text-white text-xs font-semibold disabled:opacity-40 disabled:cursor-not-allowed">
                        Hapus Terpilih
                    </button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <tr>
                            <th class="p-3.5 w-10 text-center"><input type="checkbox" id="checkAllMaint" class="rounded border-slate-300"></th>
                            <th class="p-3.5">Tanggal</th>
                            <th class="p-3.5">Peralatan</th>
                            <th class="p-3.5">Jenis</th>
                            <th class="p-3.5">PIC</th>
                            <th class="p-3.5 text-center">Status</th>
                            <th class="p-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($maintenances as $m)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-3.5 text-center">
                                <input type="checkbox" name="ids[]" value="{{ $m->id }}" form="formBulkMaint" class="check-item-maint rounded border-slate-300">
                            </td>
                            <td class="p-3.5 font-mono font-bold text-slate-800">{{ $m->tanggal }}</td>
                            <td class="p-3.5 font-bold text-[#00264A]">{{ $m->peralatan }}</td>
                            <td class="p-3.5">{{ $m->jenis }}</td>
                            <td class="p-3.5 text-slate-500">{{ $m->pic }}</td>
                            <td class="p-3.5 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold
                                    {{ $m->status === 'Selesai' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' :
                                       ($m->status === 'On Progress' ? 'bg-amber-50 text-amber-700 border border-amber-200 animate-pulse' : 'bg-slate-100 text-slate-700') }}">
                                    {{ $m->status }}
                                </span>
                            </td>
                            <td class="p-3.5 text-right space-x-1">
                                <button type="button" onclick="openEditMaint({{ json_encode($m) }})" class="p-1 rounded-lg text-blue-600 hover:bg-blue-50 font-semibold">Edit</button>
                                <form action="{{ route('tvri.teknik.maintenance.destroy', $m->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data maintenance ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 rounded-lg text-rose-600 hover:bg-rose-50 font-semibold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="p-6 text-center text-slate-400">Belum ada data maintenance.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- TAB 3: INSIDEN & GANGGUAN -->
    @if($activeTab === 'insiden')
    <div class="space-y-6">
        <!-- Form Tambah Insiden -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                <span>Catat Insiden & Gangguan Teknik</span>
            </h3>
            <form action="{{ route('tvri.teknik.insiden.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Waktu Kejadian</label>
                    <input type="text" name="waktu" placeholder="Contoh: 2 Sep 08:15" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Lokasi / Sistem</label>
                    <input type="text" name="lokasi" placeholder="Studio 4 / Transmitter Dobo" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status Penanganan</label>
                    <select name="status" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Dalam Perbaikan">Dalam Perbaikan</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Deskripsi Gangguan</label>
                    <input type="text" name="deskripsi" placeholder="Deskripsikan isu teknis, dampak, dan tindakan perbaikan" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-md transition-all">
                        Simpan Insiden
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Insiden -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Log Insiden & Gangguan Teknik</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Total {{ $insidens->count() }} kejadian dicatat</p>
                </div>
                <form id="formBulkInsiden" action="{{ route('tvri.teknik.insiden.bulk-destroy') }}" method="POST" onsubmit="return confirm('Hapus insiden yang dipilih?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="btnBulkInsiden" disabled class="px-3 py-1.5 rounded-lg bg-rose-600 text-white text-xs font-semibold disabled:opacity-40 disabled:cursor-not-allowed">
                        Hapus Terpilih
                    </button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <tr>
                            <th class="p-3.5 w-10 text-center"><input type="checkbox" id="checkAllInsiden" class="rounded border-slate-300"></th>
                            <th class="p-3.5">Waktu</th>
                            <th class="p-3.5">Lokasi / Sistem</th>
                            <th class="p-3.5">Deskripsi</th>
                            <th class="p-3.5 text-center">Status</th>
                            <th class="p-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($insidens as $ins)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-3.5 text-center">
                                <input type="checkbox" name="ids[]" value="{{ $ins->id }}" form="formBulkInsiden" class="check-item-insiden rounded border-slate-300">
                            </td>
                            <td class="p-3.5 font-mono font-bold text-cyan-800 whitespace-nowrap">{{ $ins->waktu }}</td>
                            <td class="p-3.5 font-bold text-[#00264A]">{{ $ins->lokasi }}</td>
                            <td class="p-3.5 text-slate-600">{{ $ins->deskripsi }}</td>
                            <td class="p-3.5 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $ins->badge }}">
                                    {{ $ins->status }}
                                </span>
                            </td>
                            <td class="p-3.5 text-right space-x-1">
                                <button type="button" onclick="openEditInsiden({{ json_encode($ins) }})" class="p-1 rounded-lg text-blue-600 hover:bg-blue-50 font-semibold">Edit</button>
                                <form action="{{ route('tvri.teknik.insiden.destroy', $ins->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus catatan insiden ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 rounded-lg text-rose-600 hover:bg-rose-50 font-semibold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="p-6 text-center text-slate-400">Tidak ada log insiden.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- TAB 4: SOP & REGULASI TEKNIK -->
    @if($activeTab === 'sop')
    <div class="space-y-6">
        <!-- Form Tambah SOP -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-cyan-600"></span>
                <span>Tambah Dokumen SOP Teknik Studio</span>
            </h3>
            <form action="{{ route('tvri.teknik.sop.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kode Dokumen</label>
                    <input type="text" name="kode" placeholder="Contoh: SOP-TEK-06" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Judul SOP / Prosedur</label>
                    <input type="text" name="judul" placeholder="Contoh: SOP Uji Emisi & Monitoring Modulasi Pemancar" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kategori</label>
                    <select name="kategori" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Operasional Siaran">Operasional Siaran</option>
                        <option value="Studio & Fasilitas">Studio & Fasilitas</option>
                        <option value="Transmisi & RF">Transmisi & RF</option>
                        <option value="Power & Utility">Power & Utility</option>
                        <option value="Emergency & Recovery">Emergency & Recovery</option>
                    </select>
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
                        <option value="Aktif" selected>Aktif</option>
                        <option value="Revisi">Revisi</option>
                        <option value="Arsip">Arsip</option>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Ringkasan Ketentuan SOP</label>
                    <input type="text" name="keterangan" placeholder="Penjelasan ringkas instruksi kerja dan standar operasional" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-bold shadow-md transition-all">
                        Simpan SOP
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel SOP -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Daftar Dokumen SOP Teknik Studio & Fasilitas</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Total {{ $sops->count() }} dokumen standar operasional terdata</p>
                </div>
                <form id="formBulkSop" action="{{ route('tvri.teknik.sop.bulk-destroy') }}" method="POST" onsubmit="return confirm('Hapus dokumen SOP yang dipilih?')">
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
                            <th class="p-3.5 w-10 text-center"><input type="checkbox" id="checkAllSop" class="rounded border-slate-300"></th>
                            <th class="p-3.5">Kode</th>
                            <th class="p-3.5">Judul Dokumen SOP</th>
                            <th class="p-3.5">Kategori</th>
                            <th class="p-3.5">Versi</th>
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
                            <td class="p-3.5 font-mono font-bold text-cyan-800">{{ $sop->kode }}</td>
                            <td class="p-3.5 font-bold text-[#00264A]">
                                <div>{{ $sop->judul }}</div>
                                <div class="text-[11px] text-slate-400 font-normal mt-0.5">{{ $sop->keterangan }}</div>
                            </td>
                            <td class="p-3.5">{{ $sop->kategori }}</td>
                            <td class="p-3.5 font-mono text-slate-500">{{ $sop->versi }}</td>
                            <td class="p-3.5 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ $sop->status }}
                                </span>
                            </td>
                            <td class="p-3.5 text-right space-x-1">
                                <button type="button" onclick="openEditSop({{ json_encode($sop) }})" class="p-1 rounded-lg text-blue-600 hover:bg-blue-50 font-semibold">Edit</button>
                                <form action="{{ route('tvri.teknik.sop.destroy', $sop->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus dokumen SOP ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 rounded-lg text-rose-600 hover:bg-rose-50 font-semibold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="p-6 text-center text-slate-400">Belum ada dokumen SOP.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- TAB 5: TRANSMITTER DIGITAL -->
    @if($activeTab === 'transmitter')
    <div class="space-y-6">
        <!-- Form Tambah Transmitter -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-cyan-600"></span>
                <span>Tambah Stasiun Pemancar (Transmitter Digital)</span>
            </h3>
            <form action="{{ route('tvri.teknik.transmitter.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Lokasi Stasiun</label>
                    <input type="text" name="lokasi" placeholder="Contoh: Ambon" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Frekuensi</label>
                    <input type="text" name="frekuensi" placeholder="Contoh: 528 MHz" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Daya (Power)</label>
                    <input type="text" name="daya" placeholder="Contoh: 5 kW" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-cyan-500 focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status Operasi</label>
                    <select name="status" class="w-full text-xs rounded-xl border-slate-300 focus:ring-cyan-500 focus:border-cyan-500">
                        <option value="Aktif">Aktif</option>
                        <option value="Siaga">Siaga</option>
                        <option value="Maintenance">Maintenance</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Indikator Sinyal</label>
                    <select name="sinyal_warna" class="w-full text-xs rounded-xl border-slate-300 focus:ring-cyan-500 focus:border-cyan-500">
                        <option value="Hijau">Hijau (Stabil / Kuat)</option>
                        <option value="Kuning">Kuning (Siaga / Warning)</option>
                        <option value="Merah">Merah (Gangguan)</option>
                    </select>
                </div>
                <div class="md:col-span-5 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-bold shadow-md transition-all">
                        + Simpan Stasiun Transmitter
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Daftar Transmitter -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Daftar Stasiun Pemancar Digital ({{ $transmitters->count() }} Stasiun)</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Monitoring frekuensi, daya pancar, status operasional, dan kualitas sinyal transmisi</p>
                </div>
                <form id="formBulkTx" action="{{ route('tvri.teknik.transmitter.bulk-destroy') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus stasiun transmitter yang dipilih?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="btnBulkTx" disabled class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 disabled:opacity-50 transition-all">
                        Hapus Terpilih
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-[10px] font-black uppercase tracking-wider text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="p-3 w-10 text-center">
                                <input type="checkbox" id="checkAllTx" class="rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
                            </th>
                            <th class="p-3">Lokasi</th>
                            <th class="p-3">Frekuensi</th>
                            <th class="p-3">Daya (Power)</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Sinyal</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transmitters as $tx)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="p-3 text-center">
                                <input type="checkbox" name="ids[]" value="{{ $tx->id }}" form="formBulkTx" class="check-item-tx rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
                            </td>
                            <td class="p-3 font-bold text-slate-900">{{ $tx->lokasi }}</td>
                            <td class="p-3 font-mono text-cyan-700 font-bold">{{ $tx->frekuensi }}</td>
                            <td class="p-3 font-mono text-slate-600">{{ $tx->daya }}</td>
                            <td class="p-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                                    {{ $tx->status === 'Aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' :
                                       ($tx->status === 'Siaga' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200') }}">
                                    {{ $tx->status }}
                                </span>
                            </td>
                            <td class="p-3">
                                <span class="inline-flex items-center gap-1.5 font-bold text-[11px] {{ $tx->sinyal_warna === 'Hijau' ? 'text-emerald-600' : ($tx->sinyal_warna === 'Kuning' ? 'text-amber-600' : 'text-rose-600') }}">
                                    <span class="w-2.5 h-2.5 rounded-full {{ $tx->sinyal_warna === 'Hijau' ? 'bg-emerald-500' : ($tx->sinyal_warna === 'Kuning' ? 'bg-amber-500' : 'bg-rose-500') }}"></span>
                                    {{ $tx->sinyal_warna }}
                                </span>
                            </td>
                            <td class="p-3 text-right space-x-2">
                                <button type="button" onclick="openEditTx({{ json_encode($tx) }})" class="p-1 rounded-lg text-blue-600 hover:bg-blue-50 font-semibold">Edit</button>
                                <form action="{{ route('tvri.teknik.transmitter.destroy', $tx->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus pemancar {{ $tx->lokasi }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 rounded-lg text-rose-600 hover:bg-rose-50 font-semibold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="p-6 text-center text-slate-400">Belum ada data stasiun pemancar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- TAB 6: PARAMETER & KPI RINGKASAN -->
    @if($activeTab === 'ringkasan')
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-6">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-800">Parameter KPI, Sistem & Fasilitas Teknik TVRI Maluku</h3>
                <p class="text-xs text-slate-500 mt-0.5">Pengaturan ringkasan indikator kinerja utama, status live broadcast, indeks kesiapan 6 aspek, status studio, dan inventaris peralatan</p>
            </div>
        </div>

        <form action="{{ route('tvri.teknik.ringkasan.update') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Section 1: Header, Identitas & Slogan -->
            <div>
                <h4 class="text-xs font-black uppercase tracking-wider text-cyan-800 mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-cyan-600"></span> Identitas & Slogan Dashboard
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Judul Dashboard</label>
                        <input type="text" name="judul" value="{{ $ringkasan->judul }}" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Sub Judul / Lembaga</label>
                        <input type="text" name="sub_judul" value="{{ $ringkasan->sub_judul }}" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Tagline Wilayah</label>
                        <input type="text" name="tagline" value="{{ $ringkasan->tagline }}" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Slogan Teknik</label>
                        <input type="text" name="slogan" value="{{ $ringkasan->slogan }}" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Periode</label>
                        <input type="text" name="periode" value="{{ $ringkasan->periode }}" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Status Waktu & Jam</label>
                        <input type="text" name="waktu_status" value="{{ $ringkasan->waktu_status }}" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Kutipan / Filosofi</label>
                        <input type="text" name="kutipan" value="{{ $ringkasan->kutipan }}" class="w-full text-xs rounded-xl border-slate-300 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Section 2: Indikator Kinerja Utama (KPI) -->
            <div class="pt-4 border-t border-slate-100">
                <h4 class="text-xs font-black uppercase tracking-wider text-cyan-800 mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-cyan-600"></span> Nilai Indikator Kinerja Utama (KPI)
                </h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">On-Air (%)</label>
                        <input type="number" name="on_air_pct" value="{{ $ringkasan->on_air_pct }}" min="0" max="100" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Transmitter Aktif (x/8)</label>
                        <input type="number" name="transmitter_on" value="{{ $ringkasan->transmitter_on }}" min="0" max="8" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Studio Siap (x/4)</label>
                        <input type="number" name="studio_ready" value="{{ $ringkasan->studio_ready }}" min="0" max="4" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Peralatan Kritis (%)</label>
                        <input type="number" name="peralatan_kritis_pct" value="{{ $ringkasan->peralatan_kritis_pct }}" min="0" max="100" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Insiden Hari Ini</label>
                        <input type="number" name="insiden_hari_ini" value="{{ $ringkasan->insiden_hari_ini }}" min="0" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Insiden Diff (%) (vs Kemarin)</label>
                        <input type="number" name="insiden_diff" value="{{ $ringkasan->insiden_diff }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Maintenance on Schedule (%)</label>
                        <input type="number" name="maintenance_pct" value="{{ $ringkasan->maintenance_pct }}" min="0" max="100" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Maintenance Target (%)</label>
                        <input type="number" name="maintenance_target" value="{{ $ringkasan->maintenance_target }}" min="0" max="100" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Efisiensi Energi (%)</label>
                        <input type="number" name="efisiensi_energi_pct" value="{{ $ringkasan->efisiensi_energi_pct }}" min="0" max="100" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Efisiensi Diff (%) (vs Bulan Lalu)</label>
                        <input type="number" name="efisiensi_diff" value="{{ $ringkasan->efisiensi_diff }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                </div>
            </div>

            <!-- Section 3: Status Siaran Saat Ini (LIVE ON-AIR) -->
            <div class="pt-4 border-t border-slate-100">
                <h4 class="text-xs font-black uppercase tracking-wider text-cyan-800 mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-cyan-600"></span> Status Siaran Saat Ini (LIVE ON-AIR)
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Program Siaran</label>
                        <input type="text" name="siaran_program" value="{{ $ringkasan->siaran_program }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Tagline Program</label>
                        <input type="text" name="siaran_tagline" value="{{ $ringkasan->siaran_tagline }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Sumber Studio</label>
                        <input type="text" name="siaran_sumber" value="{{ $ringkasan->siaran_sumber }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Running Text Siaran</label>
                        <input type="text" name="running_text" value="{{ $ringkasan->running_text }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Resolusi</label>
                        <input type="text" name="siaran_resolusi" value="{{ $ringkasan->siaran_resolusi }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Audio</label>
                        <input type="text" name="siaran_audio" value="{{ $ringkasan->siaran_audio }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Bitrate</label>
                        <input type="text" name="siaran_bitrate" value="{{ $ringkasan->siaran_bitrate }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Durasi Siaran</label>
                        <input type="text" name="siaran_durasi" value="{{ $ringkasan->siaran_durasi }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Status Playout</label>
                        <input type="text" name="siaran_status" value="{{ $ringkasan->siaran_status }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                </div>
            </div>

            <!-- Section 4: Technical Readiness Index (6 Aspek) -->
            @php
                $readinessMap = collect($ringkasan->readiness_items_json ?? [])->keyBy('aspek');
            @endphp
            <div class="pt-4 border-t border-slate-100">
                <h4 class="text-xs font-black uppercase tracking-wider text-cyan-800 mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-cyan-600"></span> Technical Readiness Index (Indeks Kesiapan 6 Aspek)
                </h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Overall Score (%)</label>
                        <input type="number" name="readiness_overall" value="{{ $ringkasan->readiness_overall }}" min="0" max="100" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Predikat</label>
                        <input type="text" name="readiness_predikat" value="{{ $ringkasan->readiness_predikat }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Transmisi (%)</label>
                        <input type="number" name="readiness_transmisi" value="{{ $readinessMap->get('Transmisi')['skor'] ?? 96 }}" min="0" max="100" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Studio (%)</label>
                        <input type="number" name="readiness_studio" value="{{ $readinessMap->get('Studio')['skor'] ?? 100 }}" min="0" max="100" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">IT & Network (%)</label>
                        <input type="number" name="readiness_it" value="{{ $readinessMap->get('IT & Network')['skor'] ?? 92 }}" min="0" max="100" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Power & Genset (%)</label>
                        <input type="number" name="readiness_power" value="{{ $readinessMap->get('Power & Genset')['skor'] ?? 90 }}" min="0" max="100" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Peralatan Produksi (%)</label>
                        <input type="number" name="readiness_peralatan" value="{{ $readinessMap->get('Peralatan Produksi')['skor'] ?? 93 }}" min="0" max="100" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Infrastruktur (%)</label>
                        <input type="number" name="readiness_infrastruktur" value="{{ $readinessMap->get('Infrastruktur')['skor'] ?? 95 }}" min="0" max="100" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                </div>
            </div>

            <!-- Section 5: Peta Jangkauan & Pemancar -->
            <div class="pt-4 border-t border-slate-100">
                <h4 class="text-xs font-black uppercase tracking-wider text-cyan-800 mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-cyan-600"></span> Peta Jangkauan & Estimasi Pemirsa
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Daftar Lokasi Tx (Teks Peta)</label>
                        <input type="text" name="tx_locations_text" value="{{ $ringkasan->tx_locations_text }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Coverage Area (%)</label>
                        <input type="number" name="coverage_area_pct" value="{{ $ringkasan->coverage_area_pct }}" min="0" max="100" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Estimasi Pemirsa (Teks)</label>
                        <input type="text" name="estimasi_pemirsa" value="{{ $ringkasan->estimasi_pemirsa }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                </div>
            </div>

            <!-- Section 6: Monitoring Sistem Siaran -->
            <div class="pt-4 border-t border-slate-100">
                <h4 class="text-xs font-black uppercase tracking-wider text-cyan-800 mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-cyan-600"></span> Monitoring Sistem Siaran (%)
                </h4>
                <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Video (%)</label>
                        <input type="number" name="video_pct" value="{{ $ringkasan->video_pct }}" min="0" max="100" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Audio (%)</label>
                        <input type="number" name="audio_pct" value="{{ $ringkasan->audio_pct }}" min="0" max="100" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Playout (%)</label>
                        <input type="number" name="playout_pct" value="{{ $ringkasan->playout_pct }}" min="0" max="100" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Streaming (%)</label>
                        <input type="number" name="streaming_pct" value="{{ $ringkasan->streaming_pct }}" min="0" max="100" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Uplink (%)</label>
                        <input type="number" name="uplink_pct" value="{{ $ringkasan->uplink_pct }}" min="0" max="100" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Network (%)</label>
                        <input type="number" name="network_pct" value="{{ $ringkasan->network_pct }}" min="0" max="100" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div class="md:col-span-6">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Tren Sinyal 24 Jam</label>
                        <input type="text" name="sinyal_trend" value="{{ $ringkasan->sinyal_trend }}" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                </div>
            </div>

            <!-- Section 7: Status Studio Penyiaran -->
            @php
                $studioMap = collect($ringkasan->status_studio_json ?? [])->keyBy('nama');
            @endphp
            <div class="pt-4 border-t border-slate-100">
                <h4 class="text-xs font-black uppercase tracking-wider text-cyan-800 mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-cyan-600"></span> Status 4 Studio Penyiaran
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2">
                        <span class="text-xs font-bold text-slate-800">Studio 1</span>
                        <input type="text" name="studio_1_status" value="{{ $studioMap->get('Studio 1')['status'] ?? 'ON AIR' }}" placeholder="Status: ON AIR" class="w-full text-xs rounded-xl border-slate-300">
                        <input type="text" name="studio_1_ket" value="{{ $studioMap->get('Studio 1')['keterangan'] ?? 'Siaran Langsung Maluku Hari Ini' }}" placeholder="Keterangan" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2">
                        <span class="text-xs font-bold text-slate-800">Studio 2</span>
                        <input type="text" name="studio_2_status" value="{{ $studioMap->get('Studio 2')['status'] ?? 'Siap Produksi (Siap)' }}" placeholder="Status: Siap Produksi (Siap)" class="w-full text-xs rounded-xl border-slate-300">
                        <input type="text" name="studio_2_ket" value="{{ $studioMap->get('Studio 2')['keterangan'] ?? 'Standby untuk Dialog Maluku' }}" placeholder="Keterangan" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2">
                        <span class="text-xs font-bold text-slate-800">Studio 3</span>
                        <input type="text" name="studio_3_status" value="{{ $studioMap->get('Studio 3')['status'] ?? 'Siap Produksi (Siap)' }}" placeholder="Status: Siap Produksi (Siap)" class="w-full text-xs rounded-xl border-slate-300">
                        <input type="text" name="studio_3_ket" value="{{ $studioMap->get('Studio 3')['keterangan'] ?? 'Standby untuk Program Budaya' }}" placeholder="Keterangan" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2">
                        <span class="text-xs font-bold text-slate-800">Studio 4</span>
                        <input type="text" name="studio_4_status" value="{{ $studioMap->get('Studio 4')['status'] ?? 'Maintenance (Dalam Perbaikan)' }}" placeholder="Status: Maintenance (Dalam Perbaikan)" class="w-full text-xs rounded-xl border-slate-300">
                        <input type="text" name="studio_4_ket" value="{{ $studioMap->get('Studio 4')['keterangan'] ?? 'Inspeksi & penanganan audio tidak terdeteksi' }}" placeholder="Keterangan" class="w-full text-xs rounded-xl border-slate-300">
                    </div>
                </div>
            </div>

            <!-- Section 8: Inventaris Peralatan Utama -->
            @php
                $invMap = collect($ringkasan->inventaris_json ?? [])->keyBy('nama');
            @endphp
            <div class="pt-4 border-t border-slate-100">
                <h4 class="text-xs font-black uppercase tracking-wider text-cyan-800 mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-cyan-600"></span> Inventaris Peralatan Utama (Jumlah & Kondisi Baik)
                </h4>
                <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1">
                        <span class="text-[11px] font-bold text-slate-700">Kamera</span>
                        <div><label class="text-[10px] text-slate-500">Total</label><input type="number" name="inv_kamera_total" value="{{ $invMap->get('Kamera')['total'] ?? 24 }}" min="0" class="w-full text-xs rounded-lg border-slate-300"></div>
                        <div><label class="text-[10px] text-slate-500">Baik</label><input type="number" name="inv_kamera_baik" value="{{ $invMap->get('Kamera')['baik'] ?? 21 }}" min="0" class="w-full text-xs rounded-lg border-slate-300"></div>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1">
                        <span class="text-[11px] font-bold text-slate-700">Editing</span>
                        <div><label class="text-[10px] text-slate-500">Total</label><input type="number" name="inv_editing_total" value="{{ $invMap->get('Perangkat Editing')['total'] ?? 12 }}" min="0" class="w-full text-xs rounded-lg border-slate-300"></div>
                        <div><label class="text-[10px] text-slate-500">Baik</label><input type="number" name="inv_editing_baik" value="{{ $invMap->get('Perangkat Editing')['baik'] ?? 11 }}" min="0" class="w-full text-xs rounded-lg border-slate-300"></div>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1">
                        <span class="text-[11px] font-bold text-slate-700">Server & Storage</span>
                        <div><label class="text-[10px] text-slate-500">Total</label><input type="number" name="inv_server_total" value="{{ $invMap->get('Server & Storage')['total'] ?? 8 }}" min="0" class="w-full text-xs rounded-lg border-slate-300"></div>
                        <div><label class="text-[10px] text-slate-500">Baik</label><input type="number" name="inv_server_baik" value="{{ $invMap->get('Server & Storage')['baik'] ?? 7 }}" min="0" class="w-full text-xs rounded-lg border-slate-300"></div>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1">
                        <span class="text-[11px] font-bold text-slate-700">Transmisi</span>
                        <div><label class="text-[10px] text-slate-500">Total</label><input type="number" name="inv_tx_total" value="{{ $invMap->get('Perangkat Transmisi')['total'] ?? 16 }}" min="0" class="w-full text-xs rounded-lg border-slate-300"></div>
                        <div><label class="text-[10px] text-slate-500">Baik</label><input type="number" name="inv_tx_baik" value="{{ $invMap->get('Perangkat Transmisi')['baik'] ?? 15 }}" min="0" class="w-full text-xs rounded-lg border-slate-300"></div>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1">
                        <span class="text-[11px] font-bold text-slate-700">Genset</span>
                        <div><label class="text-[10px] text-slate-500">Total</label><input type="number" name="inv_genset_total" value="{{ $invMap->get('Genset')['total'] ?? 6 }}" min="0" class="w-full text-xs rounded-lg border-slate-300"></div>
                        <div><label class="text-[10px] text-slate-500">Baik</label><input type="number" name="inv_genset_baik" value="{{ $invMap->get('Genset')['baik'] ?? 5 }}" min="0" class="w-full text-xs rounded-lg border-slate-300"></div>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1">
                        <span class="text-[11px] font-bold text-slate-700">Software Lisensi</span>
                        <div><label class="text-[10px] text-slate-500">Total</label><input type="number" name="inv_software_total" value="{{ $invMap->get('Software Lisensi')['total'] ?? 12 }}" min="0" class="w-full text-xs rounded-lg border-slate-300"></div>
                        <div><label class="text-[10px] text-slate-500">Aktif</label><input type="number" name="inv_software_baik" value="{{ $invMap->get('Software Lisensi')['baik'] ?? 12 }}" min="0" class="w-full text-xs rounded-lg border-slate-300"></div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#005B9F] hover:bg-[#004780] text-white text-xs font-bold shadow-md transition-all">
                    Simpan Seluruh Parameter Dashboard & Fasilitas
                </button>
            </div>
        </form>
    </div>
    @endif

</div>

<!-- Modal Edit Jadwal (Simple vanilla JS) -->
<div id="modalEditJadwal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h4 class="text-sm font-bold text-slate-900">Edit Jadwal Produksi</h4>
            <button type="button" onclick="closeEditJadwal()" class="text-slate-400 hover:text-slate-600 font-bold">&times;</button>
        </div>
        <form id="formEditJadwal" method="POST" class="space-y-3">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Waktu</label>
                <input type="text" id="editWaktu" name="waktu" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Program</label>
                <input type="text" id="editProgram" name="program" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Lokasi</label>
                <input type="text" id="editLokasi" name="lokasi" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Kebutuhan Teknik</label>
                <input type="text" id="editKebutuhan" name="kebutuhan" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                <select id="editStatus" name="status" class="w-full text-xs rounded-xl border-slate-300">
                    <option value="Selesai">Selesai</option>
                    <option value="Sedang Berjalan">Sedang Berjalan</option>
                    <option value="Siap">Siap</option>
                    <option value="Terjadwal">Terjadwal</option>
                </select>
            </div>
            <div class="pt-3 flex justify-end gap-2">
                <button type="button" onclick="closeEditJadwal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Maintenance -->
<div id="modalEditMaint" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h4 class="text-sm font-bold text-slate-900">Edit Maintenance & Servis</h4>
            <button type="button" onclick="closeEditMaint()" class="text-slate-400 hover:text-slate-600 font-bold">&times;</button>
        </div>
        <form id="formEditMaint" method="POST" class="space-y-3">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal</label>
                <input type="text" id="editMaintTanggal" name="tanggal" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Peralatan</label>
                <input type="text" id="editMaintPeralatan" name="peralatan" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Maintenance</label>
                <input type="text" id="editMaintJenis" name="jenis" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">PIC / Tim</label>
                <input type="text" id="editMaintPic" name="pic" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                <select id="editMaintStatus" name="status" class="w-full text-xs rounded-xl border-slate-300">
                    <option value="Selesai">Selesai</option>
                    <option value="On Progress">On Progress</option>
                    <option value="Terjadwal">Terjadwal</option>
                </select>
            </div>
            <div class="pt-3 flex justify-end gap-2">
                <button type="button" onclick="closeEditMaint()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-cyan-600 hover:bg-cyan-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Insiden -->
<div id="modalEditInsiden" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h4 class="text-sm font-bold text-slate-900">Edit Log Insiden & Gangguan</h4>
            <button type="button" onclick="closeEditInsiden()" class="text-slate-400 hover:text-slate-600 font-bold">&times;</button>
        </div>
        <form id="formEditInsiden" method="POST" class="space-y-3">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Waktu Kejadian</label>
                <input type="text" id="editInsidenWaktu" name="waktu" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Lokasi / Sistem</label>
                <input type="text" id="editInsidenLokasi" name="lokasi" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Deskripsi Insiden</label>
                <textarea id="editInsidenDeskripsi" name="deskripsi" rows="3" required class="w-full text-xs rounded-xl border-slate-300"></textarea>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                <select id="editInsidenStatus" name="status" class="w-full text-xs rounded-xl border-slate-300">
                    <option value="Dalam Perbaikan">Dalam Perbaikan</option>
                    <option value="Investigasi">Investigasi</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>
            <div class="pt-3 flex justify-end gap-2">
                <button type="button" onclick="closeEditInsiden()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-amber-600 hover:bg-amber-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit SOP -->
<div id="modalEditSop" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h4 class="text-sm font-bold text-slate-900">Edit Dokumen SOP Teknik</h4>
            <button type="button" onclick="closeEditSop()" class="text-slate-400 hover:text-slate-600 font-bold">&times;</button>
        </div>
        <form id="formEditSop" method="POST" class="space-y-3">
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
                <label class="block text-xs font-semibold text-slate-600 mb-1">Judul Dokumen</label>
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
                        <option value="Revisi">Revisi</option>
                        <option value="Arsip">Arsip</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Keterangan / Ruang Lingkup</label>
                <textarea id="editSopKeterangan" name="keterangan" rows="2" class="w-full text-xs rounded-xl border-slate-300"></textarea>
            </div>
            <div class="pt-3 flex justify-end gap-2">
                <button type="button" onclick="closeEditSop()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Transmitter -->
<div id="modalEditTx" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h4 class="text-sm font-bold text-slate-900">Edit Stasiun Transmitter</h4>
            <button type="button" onclick="closeEditTx()" class="text-slate-400 hover:text-slate-600 font-bold">&times;</button>
        </div>
        <form id="formEditTx" method="POST" class="space-y-3">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Lokasi Stasiun</label>
                <input type="text" id="editTxLokasi" name="lokasi" required class="w-full text-xs rounded-xl border-slate-300">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Frekuensi</label>
                    <input type="text" id="editTxFrekuensi" name="frekuensi" required class="w-full text-xs rounded-xl border-slate-300">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Daya (Power)</label>
                    <input type="text" id="editTxDaya" name="daya" required class="w-full text-xs rounded-xl border-slate-300">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status Operasi</label>
                    <select id="editTxStatus" name="status" class="w-full text-xs rounded-xl border-slate-300">
                        <option value="Aktif">Aktif</option>
                        <option value="Siaga">Siaga</option>
                        <option value="Maintenance">Maintenance</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Indikator Sinyal</label>
                    <select id="editTxSinyal" name="sinyal_warna" class="w-full text-xs rounded-xl border-slate-300">
                        <option value="Hijau">Hijau (Stabil / Kuat)</option>
                        <option value="Kuning">Kuning (Siaga / Warning)</option>
                        <option value="Merah">Merah (Gangguan)</option>
                    </select>
                </div>
            </div>
            <div class="pt-3 flex justify-end gap-2">
                <button type="button" onclick="closeEditTx()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-cyan-600 hover:bg-cyan-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function setupBulk(checkAllId, itemClass, btnId) {
        const checkAll = document.getElementById(checkAllId);
        const btn = document.getElementById(btnId);
        if (!checkAll || !btn) return;
        const items = document.querySelectorAll('.' + itemClass);

        function updateBtn() {
            const checkedCount = document.querySelectorAll('.' + itemClass + ':checked').length;
            btn.disabled = checkedCount === 0;
            if (checkedCount > 0) {
                btn.innerText = 'Hapus Terpilih (' + checkedCount + ')';
            } else {
                btn.innerText = 'Hapus Terpilih';
            }
        }

        checkAll.addEventListener('change', function() {
            items.forEach(el => el.checked = checkAll.checked);
            updateBtn();
        });

        items.forEach(el => el.addEventListener('change', updateBtn));
    }

    document.addEventListener('DOMContentLoaded', function() {
        setupBulk('checkAllJadwal', 'check-item-jadwal', 'btnBulkJadwal');
        setupBulk('checkAllMaint', 'check-item-maint', 'btnBulkMaint');
        setupBulk('checkAllInsiden', 'check-item-insiden', 'btnBulkInsiden');
        setupBulk('checkAllSop', 'check-item-sop', 'btnBulkSop');
        setupBulk('checkAllTx', 'check-item-tx', 'btnBulkTx');
    });

    function openEditJadwal(data) {
        document.getElementById('formEditJadwal').action = '/tvri/teknik/jadwal/' + data.id;
        document.getElementById('editWaktu').value = data.waktu;
        document.getElementById('editProgram').value = data.program;
        document.getElementById('editLokasi').value = data.lokasi;
        document.getElementById('editKebutuhan').value = data.kebutuhan;
        document.getElementById('editStatus').value = data.status;
        const modal = document.getElementById('modalEditJadwal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditJadwal() {
        const modal = document.getElementById('modalEditJadwal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function openEditMaint(data) {
        document.getElementById('formEditMaint').action = '/tvri/teknik/maintenance/' + data.id;
        document.getElementById('editMaintTanggal').value = data.tanggal;
        document.getElementById('editMaintPeralatan').value = data.peralatan;
        document.getElementById('editMaintJenis').value = data.jenis;
        document.getElementById('editMaintPic').value = data.pic;
        document.getElementById('editMaintStatus').value = data.status;
        const modal = document.getElementById('modalEditMaint');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditMaint() {
        const modal = document.getElementById('modalEditMaint');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function openEditInsiden(data) {
        document.getElementById('formEditInsiden').action = '/tvri/teknik/insiden/' + data.id;
        document.getElementById('editInsidenWaktu').value = data.waktu;
        document.getElementById('editInsidenLokasi').value = data.lokasi;
        document.getElementById('editInsidenDeskripsi').value = data.deskripsi;
        document.getElementById('editInsidenStatus').value = data.status;
        const modal = document.getElementById('modalEditInsiden');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditInsiden() {
        const modal = document.getElementById('modalEditInsiden');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function openEditSop(data) {
        document.getElementById('formEditSop').action = '/tvri/teknik/sop/' + data.id;
        document.getElementById('editSopKode').value = data.kode;
        document.getElementById('editSopVersi').value = data.versi;
        document.getElementById('editSopJudul').value = data.judul;
        document.getElementById('editSopKategori').value = data.kategori;
        document.getElementById('editSopStatus').value = data.status;
        document.getElementById('editSopKeterangan').value = data.keterangan || '';
        const modal = document.getElementById('modalEditSop');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditSop() {
        const modal = document.getElementById('modalEditSop');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function openEditTx(data) {
        document.getElementById('formEditTx').action = '/tvri/teknik/transmitter/' + data.id;
        document.getElementById('editTxLokasi').value = data.lokasi;
        document.getElementById('editTxFrekuensi').value = data.frekuensi;
        document.getElementById('editTxDaya').value = data.daya;
        document.getElementById('editTxStatus').value = data.status;
        document.getElementById('editTxSinyal').value = data.sinyal_warna;
        const modal = document.getElementById('modalEditTx');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditTx() {
        const modal = document.getElementById('modalEditTx');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection
