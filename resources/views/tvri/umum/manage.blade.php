@extends('layouts.app')

@section('title', 'Input & Kelola Data Umum & Logistik - TVRI Maluku')

@php
    $activeTab = request('tab', $tab ?? 'aset');
@endphp

@section('content')
<div class="space-y-6 pb-12" x-data="{
    activeTab: '{{ $activeTab }}',
    editAsetModal: false,
    editAsetData: {},
    editKendaraanModal: false,
    editKendaraanData: {},
    editSarprasModal: false,
    editSarprasData: {},
    editLayananModal: false,
    editLayananData: {},
    editPemeliharaanModal: false,
    editPemeliharaanData: {},
    editStokModal: false,
    editStokData: {},

    // Real-time Clock WIT
    currentWitTime: '{{ $ringkasan->header_waktu ?? 'Selasa, 2 September 2025 | 10:24:00 WIT' }}',
    initClock() {
        const update = () => {
            const now = new Date();
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

    // Checkbox Aset
    selectedAsets: [],
    allAsetIds: {{ json_encode($asets->pluck('id')) }},
    toggleAllAsets() {
        this.selectedAsets = this.selectedAsets.length === this.allAsetIds.length ? [] : [...this.allAsetIds];
    },

    // Checkbox Kendaraan
    selectedKendaraans: [],
    allKendaraanIds: {{ json_encode($kendaraans->pluck('id')) }},
    toggleAllKendaraans() {
        this.selectedKendaraans = this.selectedKendaraans.length === this.allKendaraanIds.length ? [] : [...this.allKendaraanIds];
    },

    // Checkbox Sarpras
    selectedSarpras: [],
    allSarprasIds: {{ json_encode($sarpras->pluck('id')) }},
    toggleAllSarpras() {
        this.selectedSarpras = this.selectedSarpras.length === this.allSarprasIds.length ? [] : [...this.allSarprasIds];
    },

    // Checkbox Layanan
    selectedLayanans: [],
    allLayananIds: {{ json_encode($permintaans->pluck('id')) }},
    toggleAllLayanans() {
        this.selectedLayanans = this.selectedLayanans.length === this.allLayananIds.length ? [] : [...this.allLayananIds];
    },

    // Checkbox Pemeliharaan
    selectedPemeliharaans: [],
    allPemeliharaanIds: {{ json_encode($pemeliharaans->pluck('id')) }},
    toggleAllPemeliharaans() {
        this.selectedPemeliharaans = this.selectedPemeliharaans.length === this.allPemeliharaanIds.length ? [] : [...this.allPemeliharaanIds];
    },

    // Checkbox Stok
    selectedStoks: [],
    allStokIds: {{ json_encode($stokBarangs->pluck('id')) }},
    toggleAllStoks() {
        this.selectedStoks = this.selectedStoks.length === this.allStokIds.length ? [] : [...this.allStokIds];
    }
}" x-init="initClock()">

    <!-- ===== HEADER MANAJEMEN UMUM ===== -->
    <div class="relative bg-gradient-to-r from-[#001D38] via-[#00264A] to-[#003B70] rounded-3xl p-6 sm:p-7 text-white shadow-xl overflow-hidden border border-emerald-400/20">
        <div class="absolute -right-10 -top-16 w-72 h-72 bg-emerald-400/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-white p-2 shadow-lg flex-shrink-0 flex items-center justify-center border border-emerald-300/40">
                    <img src="{{ asset('images/logo_tvri.png') }}" alt="Logo TVRI" class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-400/20 text-emerald-300 border border-emerald-400/30">
                            CRUD DATA UMUM & LOGISTIK
                        </span>
                        <span class="text-xs text-emerald-200 font-bold">Periode: {{ $ringkasan->periode ?? 'September 2025' }}</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white mt-1 leading-tight uppercase">
                        Input & Kelola Data Umum TVRI Maluku
                    </h1>
                    <p class="text-xs text-blue-200 font-medium">
                        Pengelolaan Aset BMN, Kendaraan, Sarpras, Layanan, Pemeliharaan & Stok terhubung langsung ke Dashboard & ECC.
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
                    <div class="text-xs font-black text-white mt-0.5 font-mono" x-text="currentWitTime">
                        {{ $ringkasan->header_waktu ?? 'Selasa, 2 September 2025 | 10:24 WIT' }}
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('tvri.umum.dashboard') }}"
                       class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <span>Lihat Dashboard</span>
                    </a>
                    <a href="{{ route('tvri.ecc.dashboard') }}"
                       class="inline-flex items-center space-x-1.5 px-3 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition-all">
                        <span>&larr; ECC</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-sm">
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" @click="$el.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center justify-between shadow-sm">
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <span>{{ session('error') }}</span>
        </div>
        <button type="button" @click="$el.parentElement.remove()" class="text-rose-600 hover:text-rose-900">&times;</button>
    </div>
    @endif

    <!-- ===== TAB NAVIGASI ===== -->
    <div class="bg-white rounded-2xl p-2 border border-slate-200 shadow-sm flex flex-wrap gap-1.5">
        <button type="button" @click="activeTab = 'aset'"
                :class="activeTab === 'aset' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'"
                class="px-3.5 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all flex items-center space-x-1.5">
            <span>Aset BMN ({{ count($asets) }})</span>
        </button>

        <button type="button" @click="activeTab = 'kendaraan'"
                :class="activeTab === 'kendaraan' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'"
                class="px-3.5 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all flex items-center space-x-1.5">
            <span>Kendaraan ({{ count($kendaraans) }})</span>
        </button>

        <button type="button" @click="activeTab = 'sarpras'"
                :class="activeTab === 'sarpras' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'"
                class="px-3.5 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all flex items-center space-x-1.5">
            <span>Sarpras ({{ count($sarpras) }})</span>
        </button>

        <button type="button" @click="activeTab = 'layanan'"
                :class="activeTab === 'layanan' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'"
                class="px-3.5 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all flex items-center space-x-1.5">
            <span>Layanan ({{ count($permintaans) }})</span>
        </button>

        <button type="button" @click="activeTab = 'pemeliharaan'"
                :class="activeTab === 'pemeliharaan' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'"
                class="px-3.5 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all flex items-center space-x-1.5">
            <span>Pemeliharaan ({{ count($pemeliharaans) }})</span>
        </button>

        <button type="button" @click="activeTab = 'stok'"
                :class="activeTab === 'stok' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'"
                class="px-3.5 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all flex items-center space-x-1.5">
            <span>Stok Logistik ({{ count($stokBarangs) }})</span>
        </button>

        <button type="button" @click="activeTab = 'ringkasan'"
                :class="activeTab === 'ringkasan' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'"
                class="px-3.5 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all flex items-center space-x-1.5">
            <span>Parameter & KPI</span>
        </button>
    </div>

    <!-- ==================== TAB 1: INVENTARIS ASET BMN ==================== -->
    <div x-show="activeTab === 'aset'" class="space-y-6">
        <!-- Form Tambah Aset -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A] mb-4 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span>Tambah Inventaris Aset BMN</span>
            </h3>

            <form action="{{ route('tvri.umum.aset.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-3">
                @csrf
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Nama Aset / Peralatan</label>
                    <input type="text" name="nama" required placeholder="Contoh: Kamera Studio 4K"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Kategori</label>
                    <select name="kategori" required class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="Tanah & Gedung">Tanah & Gedung</option>
                        <option value="Peralatan Pemancar">Peralatan Pemancar</option>
                        <option value="Peralatan Studio">Peralatan Studio</option>
                        <option value="Peralatan Audio">Peralatan Audio</option>
                        <option value="Lighting">Lighting</option>
                        <option value="Komputer & IT">Komputer & IT</option>
                        <option value="Tata Udara">Tata Udara</option>
                        <option value="Kelistrikan">Kelistrikan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Kondisi</label>
                    <select name="kondisi" required class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="Baik">Baik</option>
                        <option value="Rusak Ringan">Rusak Ringan</option>
                        <option value="Rusak Berat">Rusak Berat</option>
                        <option value="Dalam Perbaikan">Dalam Perbaikan</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Jumlah Unit</label>
                    <input type="number" name="jumlah" value="1" min="1" required
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Nilai Perolehan (Rp)</label>
                    <input type="number" name="nilai" value="0" min="0"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div class="md:col-span-3">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Lokasi Penempatan</label>
                    <input type="text" name="lokasi" placeholder="Contoh: Studio 1 & 2 TVRI Maluku"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div class="md:col-span-1">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Tahun</label>
                    <input type="number" name="tahun" value="{{ date('Y') }}"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div class="md:col-span-2 flex items-end">
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md transition-all">
                        + Tambah Aset BMN
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Aset -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">Daftar Inventaris Aset BMN</h3>
                    <p class="text-xs text-slate-500 font-semibold">Gunakan kotak centang untuk memilih aset yang ingin dihapus.</p>
                </div>

                <!-- Form Bulk Delete Aset -->
                <form action="{{ route('tvri.umum.aset.bulk-destroy') }}" method="POST"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus aset terpilih?');"
                      class="flex items-center gap-2">
                    @csrf
                    @method('DELETE')
                    <template x-for="id in selectedAsets" :key="id">
                        <input type="hidden" name="selected_ids[]" :value="id">
                    </template>
                    <button type="submit"
                            :disabled="selectedAsets.length === 0"
                            :class="selectedAsets.length === 0 ? 'opacity-50 cursor-not-allowed bg-rose-200 text-rose-500' : 'bg-rose-600 hover:bg-rose-500 text-white shadow-md'"
                            class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Hapus Terpilih (<span x-text="selectedAsets.length">0</span>)</span>
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 bg-slate-50/70">
                            <th class="py-3 px-3 w-10 text-center">
                                <input type="checkbox" @click="toggleAllAsets()"
                                       :checked="selectedAsets.length === allAsetIds.length && allAsetIds.length > 0"
                                       class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            </th>
                            <th class="py-3 px-3">Kode BMN</th>
                            <th class="py-3 px-3">Nama Aset</th>
                            <th class="py-3 px-3">Kategori</th>
                            <th class="py-3 px-3">Jumlah</th>
                            <th class="py-3 px-3">Kondisi</th>
                            <th class="py-3 px-3">Lokasi</th>
                            <th class="py-3 px-3 text-right">Nilai Perolehan</th>
                            <th class="py-3 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($asets as $a)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" :value="{{ $a->id }}" x-model="selectedAsets"
                                       class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            </td>
                            <td class="py-3 px-3 font-mono text-[11px] font-bold text-slate-500">{{ $a->kode }}</td>
                            <td class="py-3 px-3 font-black text-[#00264A]">{{ $a->nama }}</td>
                            <td class="py-3 px-3 text-slate-600">{{ $a->kategori }}</td>
                            <td class="py-3 px-3 font-bold text-slate-700">{{ $a->jumlah }}</td>
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase
                                    {{ $a->kondisi === 'Baik' ? 'bg-emerald-100 text-emerald-700' : ($a->kondisi === 'Rusak Ringan' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                    {{ $a->kondisi }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-slate-500 text-[11px]">{{ $a->lokasi }}</td>
                            <td class="py-3 px-3 text-right font-mono font-bold text-[#00264A] text-[11px]">Rp {{ number_format($a->nilai) }}</td>
                            <td class="py-3 px-3 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <button type="button"
                                            @click="editAsetData = {{ json_encode($a) }}; editAsetModal = true;"
                                            class="text-blue-600 hover:text-blue-900 font-bold text-xs">
                                        Edit
                                    </button>
                                    <form action="{{ route('tvri.umum.aset.destroy', $a->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus aset {{ $a->nama }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-900 font-bold text-xs">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-slate-400 font-semibold text-xs">
                                Belum ada data aset BMN.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ==================== TAB 2: KENDARAAN DINAS ==================== -->
    <div x-show="activeTab === 'kendaraan'" class="space-y-6">
        <!-- Form Tambah Kendaraan -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A] mb-4 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span>Tambah Kendaraan Dinas Operasional</span>
            </h3>

            <form action="{{ route('tvri.umum.kendaraan.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                @csrf
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Nama Kendaraan / Unit</label>
                    <input type="text" name="nama" required placeholder="Contoh: Toyota Innova Reborn (Kastasiun)"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Jenis Kendaraan</label>
                    <select name="jenis" required class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="Mobil">Mobil</option>
                        <option value="Motor">Motor</option>
                        <option value="Bus">Bus / OB Van</option>
                        <option value="Truk">Truk / Kendaraan Berat</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Plat Nomor</label>
                    <input type="text" name="plat_nomor" required placeholder="Contoh: DE 1001 AM"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 font-mono">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Status Operasional</label>
                    <select name="status" required class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="Siap Operasi">Siap Operasi</option>
                        <option value="Dalam Perbaikan">Dalam Perbaikan</option>
                        <option value="Tidak Operasi">Tidak Operasi</option>
                    </select>
                </div>

                <div class="md:col-span-3 flex items-end">
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md transition-all">
                        + Tambah Kendaraan Dinas
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Kendaraan -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">Armada Kendaraan Dinas & OB Van</h3>
                    <p class="text-xs text-slate-500 font-semibold">Gunakan kotak centang untuk menghapus kendaraan.</p>
                </div>

                <form action="{{ route('tvri.umum.kendaraan.bulk-destroy') }}" method="POST"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus kendaraan terpilih?');"
                      class="flex items-center gap-2">
                    @csrf
                    @method('DELETE')
                    <template x-for="id in selectedKendaraans" :key="id">
                        <input type="hidden" name="selected_ids[]" :value="id">
                    </template>
                    <button type="submit"
                            :disabled="selectedKendaraans.length === 0"
                            :class="selectedKendaraans.length === 0 ? 'opacity-50 cursor-not-allowed bg-rose-200 text-rose-500' : 'bg-rose-600 hover:bg-rose-500 text-white shadow-md'"
                            class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Hapus Terpilih (<span x-text="selectedKendaraans.length">0</span>)</span>
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 bg-slate-50/70">
                            <th class="py-3 px-3 w-10 text-center">
                                <input type="checkbox" @click="toggleAllKendaraans()"
                                       :checked="selectedKendaraans.length === allKendaraanIds.length && allKendaraanIds.length > 0"
                                       class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            </th>
                            <th class="py-3 px-3">Nama Kendaraan</th>
                            <th class="py-3 px-3">Jenis</th>
                            <th class="py-3 px-3">Plat Nomor</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kendaraans as $k)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" :value="{{ $k->id }}" x-model="selectedKendaraans"
                                       class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            </td>
                            <td class="py-3 px-3 font-black text-[#00264A]">{{ $k->nama }}</td>
                            <td class="py-3 px-3 text-slate-600">{{ $k->jenis }}</td>
                            <td class="py-3 px-3 font-mono font-bold text-slate-700">{{ $k->plat_nomor }}</td>
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase
                                    {{ $k->status === 'Siap Operasi' ? 'bg-emerald-100 text-emerald-700' : ($k->status === 'Dalam Perbaikan' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                    {{ $k->status }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <button type="button"
                                            @click="editKendaraanData = {{ json_encode($k) }}; editKendaraanModal = true;"
                                            class="text-blue-600 hover:text-blue-900 font-bold text-xs">
                                        Edit
                                    </button>
                                    <form action="{{ route('tvri.umum.kendaraan.destroy', $k->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus kendaraan {{ $k->nama }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-900 font-bold text-xs">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 font-semibold text-xs">
                                Belum ada data kendaraan dinas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ==================== TAB 3: SARANA PRASARANA ==================== -->
    <div x-show="activeTab === 'sarpras'" class="space-y-6">
        <!-- Form Tambah Sarpras -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A] mb-4 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span>Tambah Sarana Prasarana</span>
            </h3>

            <form action="{{ route('tvri.umum.sarpras.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-3">
                @csrf
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Nama Fasilitas / Sarpras</label>
                    <input type="text" name="nama" required placeholder="Contoh: Studio, Gedung Kantor, dll"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Kategori</label>
                    <input type="text" name="kategori" required placeholder="Contoh: Fasilitas Penyiaran"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Jumlah Unit</label>
                    <input type="number" name="jumlah_unit" value="1" min="1" required
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Kondisi Baik (%)</label>
                    <input type="text" name="kondisi_baik" value="85%" required placeholder="Contoh: 85%"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 font-mono">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Lokasi</label>
                    <input type="text" name="lokasi" placeholder="Contoh: Kompleks TVRI Stasiun Maluku"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div class="md:col-span-2 flex items-end">
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md transition-all">
                        + Tambah Sarpras
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Sarpras -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">Daftar Sarana Prasarana (56 Unit)</h3>
                    <p class="text-xs text-slate-500 font-semibold">Tampil pada kartu Kondisi Sarana Prasarana di dashboard.</p>
                </div>

                <form action="{{ route('tvri.umum.sarpras.bulk-destroy') }}" method="POST"
                      onsubmit="return confirm('Hapus sarpras terpilih?');" class="flex items-center gap-2">
                    @csrf
                    @method('DELETE')
                    <template x-for="id in selectedSarpras" :key="id">
                        <input type="hidden" name="selected_ids[]" :value="id">
                    </template>
                    <button type="submit"
                            :disabled="selectedSarpras.length === 0"
                            :class="selectedSarpras.length === 0 ? 'opacity-50 cursor-not-allowed bg-rose-200 text-rose-500' : 'bg-rose-600 hover:bg-rose-500 text-white shadow-md'"
                            class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Hapus Terpilih (<span x-text="selectedSarpras.length">0</span>)</span>
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 bg-slate-50/70">
                            <th class="py-3 px-3 w-10 text-center">
                                <input type="checkbox" @click="toggleAllSarpras()"
                                       :checked="selectedSarpras.length === allSarprasIds.length && allSarprasIds.length > 0"
                                       class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            </th>
                            <th class="py-3 px-3">Nama Fasilitas</th>
                            <th class="py-3 px-3">Kategori</th>
                            <th class="py-3 px-3">Jumlah Unit</th>
                            <th class="py-3 px-3">Kondisi Baik</th>
                            <th class="py-3 px-3">Lokasi</th>
                            <th class="py-3 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($sarpras as $s)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" :value="{{ $s->id }}" x-model="selectedSarpras"
                                       class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            </td>
                            <td class="py-3 px-3 font-black text-[#00264A]">{{ $s->nama }}</td>
                            <td class="py-3 px-3 text-slate-600">{{ $s->kategori }}</td>
                            <td class="py-3 px-3 font-bold text-slate-700">{{ $s->jumlah_unit }} Unit</td>
                            <td class="py-3 px-3 font-mono font-black text-emerald-600">{{ $s->kondisi_baik }}</td>
                            <td class="py-3 px-3 text-slate-500 text-[11px]">{{ $s->lokasi }}</td>
                            <td class="py-3 px-3 text-center">
                                <form action="{{ route('tvri.umum.sarpras.destroy', $s->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus {{ $s->nama }}?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-900 font-bold text-xs">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 font-semibold text-xs">
                                Belum ada sarana prasarana tercatat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ==================== TAB 4: PERMINTAAN LAYANAN & PERBAIKAN ==================== -->
    <div x-show="activeTab === 'layanan'" class="space-y-6">
        <!-- Form Tambah Permintaan -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A] mb-4 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span>Buat Tiket Permintaan Layanan Sarpras</span>
            </h3>

            <form action="{{ route('tvri.umum.layanan.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                @csrf
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Jenis Layanan / Permintaan</label>
                    <input type="text" name="jenis" required placeholder="Contoh: Perbaikan Kompresor AC Studio"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Pemohon</label>
                    <input type="text" name="pemohon" required placeholder="Contoh: Staf Bidang Berita"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Unit Kerja / Bidang</label>
                    <select name="unit" required class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="Berita">Berita</option>
                        <option value="Program">Program</option>
                        <option value="Teknik">Teknik Studio</option>
                        <option value="Transmisi">Transmisi</option>
                        <option value="Keuangan">Keuangan</option>
                        <option value="Tata Usaha">Tata Usaha</option>
                        <option value="Promo">Promo & Citra</option>
                        <option value="Umum">Umum</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Status Permintaan</label>
                    <select name="status" required class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="Selesai">Selesai</option>
                        <option value="Dalam Proses">Dalam Proses</option>
                        <option value="Tertunda">Tertunda</option>
                        <option value="Dibatalkan">Dibatalkan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div class="md:col-span-2 flex items-end">
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md transition-all">
                        + Simpan Tiket Permintaan
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Layanan -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">Tiket Permintaan Layanan & Sarpras</h3>
                    <p class="text-xs text-slate-500 font-semibold">Gunakan kotak centang untuk menghapus tiket layanan.</p>
                </div>

                <form action="{{ route('tvri.umum.layanan.bulk-destroy') }}" method="POST"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket terpilih?');"
                      class="flex items-center gap-2">
                    @csrf
                    @method('DELETE')
                    <template x-for="id in selectedLayanans" :key="id">
                        <input type="hidden" name="selected_ids[]" :value="id">
                    </template>
                    <button type="submit"
                            :disabled="selectedLayanans.length === 0"
                            :class="selectedLayanans.length === 0 ? 'opacity-50 cursor-not-allowed bg-rose-200 text-rose-500' : 'bg-rose-600 hover:bg-rose-500 text-white shadow-md'"
                            class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Hapus Terpilih (<span x-text="selectedLayanans.length">0</span>)</span>
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 bg-slate-50/70">
                            <th class="py-3 px-3 w-10 text-center">
                                <input type="checkbox" @click="toggleAllLayanans()"
                                       :checked="selectedLayanans.length === allLayananIds.length && allLayananIds.length > 0"
                                       class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            </th>
                            <th class="py-3 px-3">Nomor Tiket</th>
                            <th class="py-3 px-3">Jenis Layanan</th>
                            <th class="py-3 px-3">Pemohon</th>
                            <th class="py-3 px-3">Unit</th>
                            <th class="py-3 px-3">Tanggal</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($permintaans as $p)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" :value="{{ $p->id }}" x-model="selectedLayanans"
                                       class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            </td>
                            <td class="py-3 px-3 font-mono font-bold text-slate-700">{{ $p->nomor }}</td>
                            <td class="py-3 px-3 font-black text-[#00264A]">{{ $p->jenis }}</td>
                            <td class="py-3 px-3 text-slate-600">{{ $p->pemohon }}</td>
                            <td class="py-3 px-3 font-semibold text-slate-700">{{ $p->unit }}</td>
                            <td class="py-3 px-3 font-mono text-[11px] text-slate-500">{{ $p->tanggal }}</td>
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase
                                    {{ $p->status === 'Selesai' ? 'bg-emerald-100 text-emerald-700' : ($p->status === 'Tertunda' ? 'bg-amber-100 text-amber-700' : ($p->status === 'Dibatalkan' ? 'bg-rose-100 text-rose-700' : 'bg-blue-100 text-blue-700')) }}">
                                    {{ $p->status }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-center">
                                <form action="{{ route('tvri.umum.layanan.destroy', $p->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus tiket {{ $p->nomor }}?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-900 font-bold text-xs">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400 font-semibold text-xs">
                                Belum ada tiket permintaan layanan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ==================== TAB 5: JADWAL PEMELIHARAAN ==================== -->
    <div x-show="activeTab === 'pemeliharaan'" class="space-y-6">
        <!-- Form Tambah Pemeliharaan -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A] mb-4 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span>Tambah Agenda Pemeliharaan</span>
            </h3>

            <form action="{{ route('tvri.umum.pemeliharaan.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-3">
                @csrf
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Objek Fasilitas / Sarana</label>
                    <input type="text" name="objek" required placeholder="Contoh: AC Studio 1, Genset Utama"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Jenis Pekerjaan</label>
                    <input type="text" name="jenis" required placeholder="Contoh: Pemeliharaan Rutin, Servis"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Tanggal Rencana</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">PIC / Pelaksana</label>
                    <input type="text" name="pic" required placeholder="Contoh: Teknisi Eksternal, Tim Internal"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Status</label>
                    <select name="status" required class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="Terjadwal">Terjadwal</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Dalam Proses">Dalam Proses</option>
                    </select>
                </div>
                <div class="md:col-span-4 flex items-end">
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md transition-all">
                        + Tambah Agenda Pemeliharaan
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Pemeliharaan -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">Jadwal Pemeliharaan Mingguan</h3>
                    <p class="text-xs text-slate-500 font-semibold">Tampil pada kartu Jadwal Pemeliharaan di dashboard.</p>
                </div>

                <form action="{{ route('tvri.umum.pemeliharaan.bulk-destroy') }}" method="POST"
                      onsubmit="return confirm('Hapus pemeliharaan terpilih?');" class="flex items-center gap-2">
                    @csrf
                    @method('DELETE')
                    <template x-for="id in selectedPemeliharaans" :key="id">
                        <input type="hidden" name="selected_ids[]" :value="id">
                    </template>
                    <button type="submit"
                            :disabled="selectedPemeliharaans.length === 0"
                            :class="selectedPemeliharaans.length === 0 ? 'opacity-50 cursor-not-allowed bg-rose-200 text-rose-500' : 'bg-rose-600 hover:bg-rose-500 text-white shadow-md'"
                            class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Hapus Terpilih (<span x-text="selectedPemeliharaans.length">0</span>)</span>
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 bg-slate-50/70">
                            <th class="py-3 px-3 w-10 text-center">
                                <input type="checkbox" @click="toggleAllPemeliharaans()"
                                       :checked="selectedPemeliharaans.length === allPemeliharaanIds.length && allPemeliharaanIds.length > 0"
                                       class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            </th>
                            <th class="py-3 px-3">Tanggal</th>
                            <th class="py-3 px-3">Objek Pemeliharaan</th>
                            <th class="py-3 px-3">Jenis Pekerjaan</th>
                            <th class="py-3 px-3">PIC / Pelaksana</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pemeliharaans as $pm)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" :value="{{ $pm->id }}" x-model="selectedPemeliharaans"
                                       class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            </td>
                            <td class="py-3 px-3 font-mono font-bold text-slate-700">{{ $pm->tanggal }}</td>
                            <td class="py-3 px-3 font-black text-[#00264A]">{{ $pm->objek }}</td>
                            <td class="py-3 px-3 text-slate-600">{{ $pm->jenis }}</td>
                            <td class="py-3 px-3 text-slate-600">{{ $pm->pic }}</td>
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase
                                    {{ $pm->status === 'Selesai' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $pm->status }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-center">
                                <form action="{{ route('tvri.umum.pemeliharaan.destroy', $pm->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus {{ $pm->objek }}?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-900 font-bold text-xs">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 font-semibold text-xs">
                                Belum ada jadwal pemeliharaan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ==================== TAB 6: STOK LOGISTIK & ATK ==================== -->
    <div x-show="activeTab === 'stok'" class="space-y-6">
        <!-- Form Tambah Stok -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A] mb-4 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span>Tambah Stok Logistik & ATK</span>
            </h3>

            <form action="{{ route('tvri.umum.stok.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-3">
                @csrf
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Nama Barang</label>
                    <input type="text" name="nama" required placeholder="Contoh: Kertas A4, Tinta Printer"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Kategori</label>
                    <select name="kategori" required class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="ATK">ATK</option>
                        <option value="Operasional">Operasional (BBM/Solar)</option>
                        <option value="Teknik">Teknik (Lampu/Kabel)</option>
                        <option value="Kebersihan">Kebersihan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Jumlah</label>
                    <input type="number" name="jumlah" value="10" min="0" required
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Satuan & Batas Minimal</label>
                    <input type="text" name="satuan" required placeholder="Contoh: Rim (Minimal: 20 Rim)"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Status Stok</label>
                    <select name="status_stok" required class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="Perlu Pengadaan">Perlu Pengadaan</option>
                        <option value="Menipis">Menipis</option>
                        <option value="Aman">Aman</option>
                        <option value="Habis">Habis</option>
                    </select>
                </div>
                <div class="md:col-span-4 flex items-end">
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md transition-all">
                        + Tambah Barang Logistik
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Stok -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">Persediaan Stok Logistik & ATK</h3>
                    <p class="text-xs text-slate-500 font-semibold">Tampil pada kartu Stok Logistik & ATK Kritis di dashboard.</p>
                </div>

                <form action="{{ route('tvri.umum.stok.bulk-destroy') }}" method="POST"
                      onsubmit="return confirm('Hapus barang terpilih?');" class="flex items-center gap-2">
                    @csrf
                    @method('DELETE')
                    <template x-for="id in selectedStoks" :key="id">
                        <input type="hidden" name="selected_ids[]" :value="id">
                    </template>
                    <button type="submit"
                            :disabled="selectedStoks.length === 0"
                            :class="selectedStoks.length === 0 ? 'opacity-50 cursor-not-allowed bg-rose-200 text-rose-500' : 'bg-rose-600 hover:bg-rose-500 text-white shadow-md'"
                            class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Hapus Terpilih (<span x-text="selectedStoks.length">0</span>)</span>
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 bg-slate-50/70">
                            <th class="py-3 px-3 w-10 text-center">
                                <input type="checkbox" @click="toggleAllStoks()"
                                       :checked="selectedStoks.length === allStokIds.length && allStokIds.length > 0"
                                       class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            </th>
                            <th class="py-3 px-3">Nama Barang</th>
                            <th class="py-3 px-3">Kategori</th>
                            <th class="py-3 px-3">Jumlah</th>
                            <th class="py-3 px-3">Keterangan / Satuan</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($stokBarangs as $sb)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" :value="{{ $sb->id }}" x-model="selectedStoks"
                                       class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            </td>
                            <td class="py-3 px-3 font-black text-[#00264A]">{{ $sb->nama }}</td>
                            <td class="py-3 px-3 text-slate-600">{{ $sb->kategori }}</td>
                            <td class="py-3 px-3 font-mono font-bold text-slate-800">{{ $sb->jumlah }}</td>
                            <td class="py-3 px-3 text-slate-500 text-[11px]">{{ $sb->satuan }}</td>
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase
                                    {{ in_array(strtolower($sb->status_stok), ['aman', 'cukup']) ? 'bg-emerald-100 text-emerald-700' : (strtolower($sb->status_stok) === 'menipis' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                    {{ $sb->status_stok }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-center">
                                <form action="{{ route('tvri.umum.stok.destroy', $sb->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus {{ $sb->nama }}?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-900 font-bold text-xs">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 font-semibold text-xs">
                                Belum ada persediaan barang logistik.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ==================== TAB 7: PARAMETER RINGKASAN, K3 & ANGGARAN ==================== -->
    <div x-show="activeTab === 'ringkasan'" class="space-y-6">
        <form action="{{ route('tvri.umum.ringkasan.update') }}" method="POST">
            @csrf
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">Parameter Ringkasan, Keamanan & Anggaran</h3>
                        <p class="text-xs text-slate-500 font-semibold">Data yang disimpan akan langsung terbaca pada dashboard Umum dan ECC Kepala Stasiun.</p>
                    </div>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md transition-all">
                        Simpan Semua Parameter
                    </button>
                </div>

                <!-- 1. Indikator Kinerja Utama -->
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-emerald-700 mb-3">1. Indikator Kinerja Utama & Target</h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Diff Aset (% MoM)</label>
                            <input type="text" name="diff_aset" value="{{ $ringkasan->diff_aset }}" required
                                   class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Diff Kendaraan (% MoM)</label>
                            <input type="text" name="diff_kendaraan" value="{{ $ringkasan->diff_kendaraan }}" required
                                   class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Diff Sarpras (% MoM)</label>
                            <input type="text" name="diff_sarpras" value="{{ $ringkasan->diff_sarpras }}" required
                                   class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Diff Permintaan (% MoM)</label>
                            <input type="text" name="diff_permintaan" value="{{ $ringkasan->diff_permintaan }}" required
                                   class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Insiden Keamanan</label>
                            <input type="number" name="insiden_keamanan" value="{{ $ringkasan->insiden_keamanan }}" required
                                   class="w-full text-xs rounded-xl border-slate-200 font-bold text-amber-700">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Diff Insiden (% MoM)</label>
                            <input type="text" name="diff_insiden" value="{{ $ringkasan->diff_insiden }}" required
                                   class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Indeks Kenyamanan (%)</label>
                            <input type="number" name="indeks_kenyamanan" value="{{ $ringkasan->indeks_kenyamanan }}" required
                                   class="w-full text-xs rounded-xl border-slate-200 font-bold text-teal-700">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Operational Readiness (%)</label>
                            <input type="number" name="operational_readiness" value="{{ $ringkasan->operational_readiness }}" required
                                   class="w-full text-xs rounded-xl border-slate-200 font-bold text-emerald-700">
                        </div>
                    </div>
                </div>

                <!-- 2. Status Keamanan & K3 -->
                <div class="border-t border-slate-100 pt-4">
                    <h4 class="text-xs font-black uppercase tracking-wider text-emerald-700 mb-3">2. Status Keamanan & K3</h4>
                    <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Pos Keamanan</label>
                            <input type="number" name="pos_keamanan" value="{{ $ringkasan->pos_keamanan ?? 3 }}" required
                                   class="w-full text-xs rounded-xl border-slate-200 font-bold text-[#00264A]">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">CCTV Total</label>
                            <input type="number" name="cctv_total" value="{{ $ringkasan->cctv_total ?? 28 }}" required
                                   class="w-full text-xs rounded-xl border-slate-200 font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">CCTV Aktif</label>
                            <input type="number" name="cctv_aktif" value="{{ $ringkasan->cctv_aktif ?? 26 }}" required
                                   class="w-full text-xs rounded-xl border-slate-200 font-bold text-emerald-600">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">CCTV Perbaikan</label>
                            <input type="number" name="cctv_perbaikan" value="{{ $ringkasan->cctv_perbaikan ?? 2 }}" required
                                   class="w-full text-xs rounded-xl border-slate-200 font-bold text-amber-600">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">APAR Total</label>
                            <input type="number" name="apar_total" value="{{ $ringkasan->apar_total ?? 32 }}" required
                                   class="w-full text-xs rounded-xl border-slate-200 font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Petugas Security</label>
                            <input type="number" name="personel_security" value="{{ $ringkasan->personel_security ?? 12 }}" required
                                   class="w-full text-xs rounded-xl border-slate-200 font-bold text-emerald-600">
                        </div>
                    </div>
                </div>

                <!-- 3. Nilai Kebersihan & Lingkungan -->
                <div class="border-t border-slate-100 pt-4">
                    <h4 class="text-xs font-black uppercase tracking-wider text-emerald-700 mb-3">3. Nilai Kebersihan & Lingkungan</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Nilai Area Kantor</label>
                            <input type="number" name="nilai_kebersihan_kantor" value="{{ $ringkasan->nilai_kebersihan_kantor ?? 94 }}" required
                                   class="w-full text-xs rounded-xl border-slate-200 font-bold text-teal-600">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Nilai Area Studio</label>
                            <input type="number" name="nilai_kebersihan_studio" value="{{ $ringkasan->nilai_kebersihan_studio ?? 96 }}" required
                                   class="w-full text-xs rounded-xl border-slate-200 font-bold text-emerald-600">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Nilai Halaman & Parkir</label>
                            <input type="number" name="nilai_kebersihan_halaman" value="{{ $ringkasan->nilai_kebersihan_halaman ?? 90 }}" required
                                   class="w-full text-xs rounded-xl border-slate-200 font-bold text-cyan-600">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Nilai Toilet & Sanitasi</label>
                            <input type="number" name="nilai_kebersihan_toilet" value="{{ $ringkasan->nilai_kebersihan_toilet ?? 88 }}" required
                                   class="w-full text-xs rounded-xl border-slate-200 font-bold text-blue-600">
                        </div>
                    </div>
                </div>

                <!-- 4. Anggaran Operasional -->
                <div class="border-t border-slate-100 pt-4">
                    <h4 class="text-xs font-black uppercase tracking-wider text-emerald-700 mb-3">4. Anggaran Operasional Umum</h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Total Pagu (Rp)</label>
                            <input type="number" name="anggaran_pagu" value="{{ (int)$ringkasan->anggaran_pagu }}" required
                                   class="w-full text-xs rounded-xl border-slate-200 font-bold font-mono">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Realisasi (Rp)</label>
                            <input type="number" name="anggaran_terpakai" value="{{ (int)$ringkasan->anggaran_terpakai }}" required
                                   class="w-full text-xs rounded-xl border-slate-200 font-bold font-mono text-emerald-600">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Sisa Anggaran (Rp)</label>
                            <input type="number" name="anggaran_sisa" value="{{ (int)$ringkasan->anggaran_sisa }}" required
                                   class="w-full text-xs rounded-xl border-slate-200 font-bold font-mono text-blue-600">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Persentase Serapan (%)</label>
                            <input type="number" name="anggaran_persen" value="{{ $ringkasan->anggaran_persen }}" required
                                   class="w-full text-xs rounded-xl border-slate-200 font-bold">
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md transition-all">
                        Simpan Semua Parameter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- ===== MODAL EDIT ASET ===== -->
    <div x-show="editAsetModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;">
        <div class="bg-white rounded-3xl p-6 max-w-lg w-full border border-slate-200 shadow-2xl" @click.away="editAsetModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">Edit Data Aset BMN</h3>
                <button type="button" @click="editAsetModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
            </div>

            <form :action="'{{ url('tvri/umum/aset') }}/' + editAsetData.id" method="POST" class="space-y-3">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Nama Aset</label>
                    <input type="text" name="nama" x-model="editAsetData.nama" required class="w-full text-xs rounded-xl border-slate-200">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Kategori</label>
                        <input type="text" name="kategori" x-model="editAsetData.kategori" required class="w-full text-xs rounded-xl border-slate-200">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Kondisi</label>
                        <select name="kondisi" x-model="editAsetData.kondisi" required class="w-full text-xs rounded-xl border-slate-200">
                            <option value="Baik">Baik</option>
                            <option value="Rusak Ringan">Rusak Ringan</option>
                            <option value="Rusak Berat">Rusak Berat</option>
                            <option value="Dalam Perbaikan">Dalam Perbaikan</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Jumlah</label>
                        <input type="number" name="jumlah" x-model="editAsetData.jumlah" required class="w-full text-xs rounded-xl border-slate-200">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Nilai Perolehan</label>
                        <input type="number" name="nilai" x-model="editAsetData.nilai" class="w-full text-xs rounded-xl border-slate-200 font-mono">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Lokasi</label>
                    <input type="text" name="lokasi" x-model="editAsetData.lokasi" class="w-full text-xs rounded-xl border-slate-200">
                </div>
                <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="editAsetModal = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===== MODAL EDIT KENDARAAN ===== -->
    <div x-show="editKendaraanModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;">
        <div class="bg-white rounded-3xl p-6 max-w-md w-full border border-slate-200 shadow-2xl" @click.away="editKendaraanModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-sm font-black uppercase tracking-wider text-[#00264A]">Edit Data Kendaraan Dinas</h3>
                <button type="button" @click="editKendaraanModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
            </div>

            <form :action="'{{ url('tvri/umum/kendaraan') }}/' + editKendaraanData.id" method="POST" class="space-y-3">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Nama Kendaraan</label>
                    <input type="text" name="nama" x-model="editKendaraanData.nama" required class="w-full text-xs rounded-xl border-slate-200">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Jenis</label>
                        <select name="jenis" x-model="editKendaraanData.jenis" required class="w-full text-xs rounded-xl border-slate-200">
                            <option value="Mobil">Mobil</option>
                            <option value="Motor">Motor</option>
                            <option value="Bus">Bus</option>
                            <option value="Truk">Truk</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Plat Nomor</label>
                        <input type="text" name="plat_nomor" x-model="editKendaraanData.plat_nomor" required class="w-full text-xs rounded-xl border-slate-200 font-mono">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Status</label>
                    <select name="status" x-model="editKendaraanData.status" required class="w-full text-xs rounded-xl border-slate-200">
                        <option value="Siap Operasi">Siap Operasi</option>
                        <option value="Dalam Perbaikan">Dalam Perbaikan</option>
                        <option value="Tidak Operasi">Tidak Operasi</option>
                    </select>
                </div>
                <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="editKendaraanModal = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
