@extends('layouts.app')

@section('title', 'Kelola & Input Data Keuangan - TVRI Stasiun Maluku')

@php
    $formatRp = fn($val) => 'Rp ' . number_format($val, 0, ',', '.');
    $currentTab = request('tab', $tab ?? 'ringkasan');
@endphp

@section('content')
<div class="space-y-6 pb-12" x-data="{ activeTab: '{{ $currentTab }}', editModal: false, editType: '', editData: {}, showAddIkpaModal: false }">

    <!-- ===== HEADER MANAJEMEN DATA ===== -->
    <div class="relative bg-gradient-to-r from-[#001830] via-[#002B54] to-[#003B70] rounded-3xl p-6 sm:p-7 text-white shadow-xl overflow-hidden border border-blue-400/20">
        <div class="absolute -right-10 -top-16 w-72 h-72 bg-teal-400/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-white p-2 shadow-lg flex-shrink-0 flex items-center justify-center border border-blue-300/40">
                    <img src="{{ asset('images/logo_tvri.png') }}" alt="Logo TVRI" class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-teal-400/20 text-teal-300 border border-teal-400/30">
                            CRUD DATA KEUANGAN
                        </span>
                        <div class="flex items-center space-x-1.5 bg-white/10 rounded-lg px-2 py-0.5 border border-white/20">
                            <span class="text-xs text-blue-200 font-bold">T.A.</span>
                            <select onchange="window.location.href='?tahun=' + this.value + '&tab=' + (window.Alpine ? (Alpine.$data(this.closest('[x-data]'))?.activeTab || '{{ $tab }}') : '{{ $tab }}')" 
                                    class="bg-transparent text-white font-black text-xs border-0 focus:ring-0 cursor-pointer p-0">
                                @foreach(range(date('Y') + 1, 2022) as $yr)
                                    <option value="{{ $yr }}" class="bg-[#002B54] text-white" {{ $tahun == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white mt-1 leading-tight uppercase">
                        Kelola & Input Data Keuangan
                    </h1>
                    <p class="text-xs text-blue-300 font-medium">
                        Input data anggaran, realisasi belanja, PNBP, piutang, arus kas, IKPA, dan kegiatan yang otomatis terhubung ke Dashboard Keuangan.
                    </p>
                </div>
            </div>

            <!-- Header Action Buttons -->
            <div class="flex items-center space-x-2.5 flex-wrap gap-2">
                <div class="bg-black/35 backdrop-blur-md px-3.5 py-1.5 rounded-xl border border-teal-400/30 flex items-center space-x-2 text-xs font-bold text-white shadow-inner">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="global-live-datetime-bullet">{{ now()->isoFormat('dddd, D MMMM Y') }} &bull; {{ now()->format('H:i:s') }} WIT</span>
                </div>
                <form action="{{ route('tvri.keuangan.sync-dokumen') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                    <button type="submit" 
                            class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-2xl text-xs font-black bg-emerald-600 hover:bg-emerald-500 text-white shadow-md border border-emerald-400/30 transition-all hover:scale-[1.02]">
                        <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        <span>Sinkronkan ke Dokumen</span>
                    </button>
                </form>
                <a href="{{ route('tvri.keuangan.dashboard') }}" 
                   class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-2xl text-xs font-black bg-blue-600 hover:bg-blue-700 text-white shadow-md border border-blue-400/30 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Dashboard Keuangan</span>
                </a>
                <a href="{{ route('dokumen.documents.index') }}" 
                   class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-2xl text-xs font-black bg-white/10 hover:bg-white/20 text-white border border-white/20 transition-all">
                    <svg class="w-4 h-4 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Pangkalan Dokumen</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ===== TAB NAVIGASI CRUD ===== -->
    <div class="bg-white rounded-2xl p-2 border border-slate-200 shadow-sm flex items-center space-x-1.5 overflow-x-auto">
        <button @click="activeTab = 'ringkasan'" 
                :class="activeTab === 'ringkasan' ? 'bg-[#005B9F] text-white shadow-sm font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'"
                class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap flex items-center space-x-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            <span>Ringkasan & Aset</span>
        </button>

        <button @click="activeTab = 'anggaran'" 
                :class="activeTab === 'anggaran' ? 'bg-[#005B9F] text-white shadow-sm font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'"
                class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap flex items-center space-x-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>Rincian Belanja (Akun 51-54)</span>
        </button>

        <button @click="activeTab = 'pnbp'" 
                :class="activeTab === 'pnbp' ? 'bg-[#005B9F] text-white shadow-sm font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'"
                class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap flex items-center space-x-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            <span>Rincian PNBP</span>
        </button>

        <button @click="activeTab = 'piutang'" 
                :class="activeTab === 'piutang' ? 'bg-[#005B9F] text-white shadow-sm font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'"
                class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap flex items-center space-x-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            <span>Piutang & Tagihan</span>
        </button>

        <button @click="activeTab = 'aruskas'" 
                :class="activeTab === 'aruskas' ? 'bg-[#005B9F] text-white shadow-sm font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'"
                class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap flex items-center space-x-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span>Arus Kas (Jan - Des)</span>
        </button>

        <button @click="activeTab = 'kegiatan'" 
                :class="activeTab === 'kegiatan' ? 'bg-[#005B9F] text-white shadow-sm font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'"
                class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap flex items-center space-x-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            <span>Rencana Kegiatan</span>
        </button>

        <button @click="activeTab = 'dokumen'" 
                :class="activeTab === 'dokumen' ? 'bg-[#005B9F] text-white shadow-sm font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'"
                class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap flex items-center space-x-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span>Dokumen Keuangan (DMS)</span>
        </button>

        <button @click="activeTab = 'sop'" 
                :class="activeTab === 'sop' ? 'bg-[#005B9F] text-white shadow-sm font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'"
                class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap flex items-center space-x-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <span>Dokumen & SOP Keuangan</span>
        </button>
    </div>

    <!-- ========================================== -->
    <!-- TAB 1: RINGKASAN, POSISI ASET & IKPA      -->
    <!-- ========================================== -->
    <div x-show="activeTab === 'ringkasan'" class="space-y-6">
        <form action="{{ route('tvri.keuangan.ringkasan.update') }}" method="POST">
            @csrf
            <input type="hidden" name="tahun" value="{{ $tahun }}">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Card 1: Ringkasan Utama & IKPA -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
                    <div class="flex items-center space-x-2 pb-3 border-b border-slate-100">
                        <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">
                            Pagu, PNBP & Indikator IKPA
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Pagu Anggaran (Rp)</label>
                            <input type="number" step="1000" name="pagu_anggaran" value="{{ old('pagu_anggaran', $ringkasan->pagu_anggaran) }}"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-blue-500">
                            <p class="text-[10px] text-slate-400 mt-0.5">Contoh: 45750000000</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Realisasi Anggaran (Rp)</label>
                            <input type="number" step="1000" name="realisasi_anggaran" value="{{ old('realisasi_anggaran', $ringkasan->realisasi_anggaran) }}"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-blue-500">
                            <p class="text-[10px] text-slate-400 mt-0.5">Contoh: 34356500000</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Target PNBP (Rp)</label>
                            <input type="number" step="1000" name="target_pnbp" value="{{ old('target_pnbp', $ringkasan->target_pnbp) }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-blue-500">
                            <p class="text-[10px] text-slate-400 mt-0.5">Contoh: 5000000</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Realisasi PNBP (Rp)</label>
                            <input type="number" step="1000" name="realisasi_pnbp" value="{{ old('realisasi_pnbp', $ringkasan->realisasi_pnbp) }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-blue-500">
                            <p class="text-[10px] text-slate-400 mt-0.5">Contoh: 400000000</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Piutang / Tagihan (Rp)</label>
                            <input type="number" step="1000" name="piutang_tagihan" value="{{ old('piutang_tagihan', $ringkasan->piutang_tagihan) }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-blue-500">
                            <p class="text-[10px] text-slate-400 mt-0.5">Contoh: 425000000</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Skor Kinerja IKPA (0 - 100)</label>
                            <input type="number" step="0.01" min="0" max="100" name="ikpa_skor" value="{{ old('ikpa_skor', $ringkasan->ikpa_skor) }}"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Predikat IKPA</label>
                            <select name="ikpa_predikat" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-blue-500">
                                @foreach(['Sangat Baik', 'Baik', 'Cukup', 'Perlu Pembinaan'] as $pred)
                                    <option value="{{ $pred }}" {{ $ringkasan->ikpa_predikat == $pred ? 'selected' : '' }}>{{ $pred }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="p-3 bg-blue-50 rounded-xl border border-blue-100 text-xs text-blue-800">
                        <strong>💡 Catatan Sinkronisasi Otomatis:</strong> Nilai Pagu, Realisasi, PNBP, dan Piutang di atas tersinkronisasi otomatis dengan rincian belanja akun 51-54, data PNBP, dan piutang mitra pada tab masing-masing, serta terhubung langsung ke Dashboard Keuangan dan ECC Kepala Stasiun.
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-black text-xs shadow-md transition-all flex items-center justify-center space-x-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Simpan Perubahan Pagu, Realisasi, PNBP & IKPA</span>
                        </button>
                    </div>
                </div>

                <!-- Card 2: Posisi Aset & Pasiva -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
                    <div class="flex items-center space-x-2 pb-3 border-b border-slate-100">
                        <span class="w-3 h-3 rounded-full bg-teal-500"></span>
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">
                            Posisi Aset BMN & Pasiva
                        </h3>
                    </div>

                    <div class="space-y-3">
                        <p class="text-xs font-black text-slate-500 uppercase tracking-wider">Aset (Aktiva)</p>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Aset Lancar (Rp)</label>
                                <input type="number" step="1000" name="aset_lancar" value="{{ old('aset_lancar', $ringkasan->aset_lancar) }}"
                                       class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Aset Tetap (BMN)</label>
                                <input type="number" step="1000" name="aset_tetap" value="{{ old('aset_tetap', $ringkasan->aset_tetap) }}"
                                       class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Aset Lainnya</label>
                                <input type="number" step="1000" name="aset_lainnya" value="{{ old('aset_lainnya', $ringkasan->aset_lainnya) }}"
                                       class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3 pt-3 border-t border-slate-100">
                        <p class="text-xs font-black text-slate-500 uppercase tracking-wider">Kewajiban & Ekuitas (Pasiva)</p>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Kewajiban Lancar</label>
                                <input type="number" step="1000" name="kewajiban_lancar" value="{{ old('kewajiban_lancar', $ringkasan->kewajiban_lancar) }}"
                                       class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Kewajiban Panjang</label>
                                <input type="number" step="1000" name="kewajiban_panjang" value="{{ old('kewajiban_panjang', $ringkasan->kewajiban_panjang) }}"
                                       class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Ekuitas Pemerintah</label>
                                <input type="number" step="1000" name="ekuitas" value="{{ old('ekuitas', $ringkasan->ekuitas) }}"
                                       class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold">
                            </div>
                        </div>
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-black text-xs shadow-md transition-all">
                            Simpan Perubahan Ringkasan & Aset
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Form Indikator IKPA -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 mb-4 border-b border-slate-100 gap-2">
                <div>
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">
                        Rincian Indikator Evaluasi IKPA (Satker 700225)
                    </h3>
                    <p class="text-xs text-slate-500">Indikator Kinerja Pelaksanaan Anggaran TVRI Stasiun Maluku (Nilai, Bobot, Skor Capaian)</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" @click="showAddIkpaModal = !showAddIkpaModal" class="px-3 py-1.5 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 font-bold text-xs flex items-center space-x-1 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span>Tambah Indikator</span>
                    </button>
                </div>
            </div>

            <!-- Form Tambah Indikator Baru (Collapsible) -->
            <div x-show="showAddIkpaModal" x-cloak class="mb-4 p-4 rounded-xl bg-blue-50/50 border border-blue-200">
                <form action="{{ route('tvri.keuangan.ikpa.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-5 gap-3 items-end">
                    @csrf
                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                    <div class="sm:col-span-2">
                        <label class="block text-[10px] font-bold text-slate-600 mb-1">Nama Indikator</label>
                        <input type="text" name="indikator" required placeholder="Contoh: Dispensasi SPM" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 mb-1">Nilai (0-100)</label>
                        <input type="number" step="0.01" min="0" max="100" name="nilai" required placeholder="100.00" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-800">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 mb-1">Bobot (%)</label>
                        <input type="text" name="bobot" required placeholder="10%" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-600 mb-1">Skor Akhir</label>
                        <input type="text" name="skor" required placeholder="10.00" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-blue-700">
                    </div>
                    <div class="sm:col-span-5 flex justify-end space-x-2 pt-1">
                        <button type="button" @click="showAddIkpaModal = false" class="px-3 py-1.5 rounded-lg bg-slate-200 text-slate-700 text-xs font-bold">Batal</button>
                        <button type="submit" class="px-4 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm">+ Simpan Indikator Baru</button>
                    </div>
                </form>
            </div>

            <!-- Form Edit Batch Seluruh Indikator -->
            <form action="{{ route('tvri.keuangan.ikpa.update') }}" method="POST">
                @csrf
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($ikpas as $ik)
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs space-y-2 relative group hover:border-blue-300 transition-colors">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-800 block truncate text-xs">{{ $ik->indikator }}</span>
                                @if(count($ikpas) > 7)
                                <button type="button" onclick="if(confirm('Hapus indikator {{ $ik->indikator }}?')) document.getElementById('delete-ikpa-{{ $ik->id }}').submit();" class="text-rose-400 hover:text-rose-600 text-[10px] p-0.5" title="Hapus">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                                @endif
                            </div>
                            <div class="grid grid-cols-3 gap-1.5">
                                <div>
                                    <label class="text-[9px] text-slate-500 font-bold block mb-0.5">Nilai</label>
                                    <input type="number" step="0.01" min="0" max="100" name="ikpa[{{ $ik->id }}][nilai]" value="{{ (float)$ik->nilai }}"
                                           class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold ikpa-input-nilai"
                                           data-id="{{ $ik->id }}">
                                </div>
                                <div>
                                    <label class="text-[9px] text-slate-500 font-bold block mb-0.5">Bobot</label>
                                    <input type="text" name="ikpa[{{ $ik->id }}][bobot]" value="{{ $ik->bobot }}"
                                           class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold ikpa-input-bobot"
                                           data-id="{{ $ik->id }}">
                                </div>
                                <div>
                                    <label class="text-[9px] text-slate-500 font-bold block mb-0.5">Skor Capaian</label>
                                    <input type="text" name="ikpa[{{ $ik->id }}][skor]" value="{{ $ik->skor }}"
                                           class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-blue-700 ikpa-input-skor"
                                           data-id="{{ $ik->id }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="text-xs text-slate-600 flex items-center space-x-2">
                        <span>Total Nilai Akhir IKPA:</span>
                        <span id="ikpa-calc-total" class="font-black text-[#00264A] text-sm">{{ number_format((float)$ringkasan->ikpa_skor, 2, ',', '.') }}</span>
                        <span id="ikpa-calc-predikat" class="px-2 py-0.5 rounded text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                            {{ $ringkasan->ikpa_predikat ?? 'Sangat Baik' }}
                        </span>
                    </div>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#005B9F] hover:bg-[#004780] text-white font-bold text-xs shadow-sm flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Simpan Perubahan Indikator IKPA</span>
                    </button>
                </div>
            </form>

            @foreach($ikpas as $ik)
                @if(count($ikpas) > 7)
                <form id="delete-ikpa-{{ $ik->id }}" action="{{ route('tvri.keuangan.ikpa.destroy', $ik->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                </form>
                @endif
            @endforeach
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 2: RINCIAN BELANJA ANGGARAN (CRUD)     -->
    <!-- ========================================== -->
    <div x-show="activeTab === 'anggaran'" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Form Tambah Anggaran -->
            <div class="lg:col-span-4 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm h-fit">
                <div class="flex items-center space-x-2 pb-3 mb-4 border-b border-slate-100">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Tambah Akun Belanja</h3>
                </div>

                <form action="{{ route('tvri.keuangan.anggaran.store') }}" method="POST" class="space-y-3.5">
                    @csrf
                    <input type="hidden" name="tahun" value="{{ $tahun }}">

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kode Akun (Contoh: 51, 52, 53)</label>
                        <input type="text" name="kode" required placeholder="51"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Uraian Jenis Belanja</label>
                        <input type="text" name="uraian" required placeholder="Belanja Pegawai"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Pagu Alokasi (Rp)</label>
                        <input type="number" step="1000" name="pagu" required placeholder="19500000000"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Realisasi Serapan (Rp)</label>
                        <input type="number" step="1000" name="realisasi" required placeholder="16006500000"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition-all">
                        + Tambah Rincian Belanja
                    </button>
                </form>
            </div>

            <!-- Tabel Daftar Belanja Anggaran -->
            <div class="lg:col-span-8 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">
                            Daftar Realisasi Anggaran Belanja (T.A. {{ $tahun }})
                        </h3>
                        <p class="text-xs text-slate-500">Data ini langsung menyusun grafik & tabel di Dashboard Keuangan</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-400 text-[10px] uppercase font-bold">
                                <th class="py-2.5">Kode</th>
                                <th class="py-2.5">Uraian Belanja</th>
                                <th class="py-2.5 text-right">Pagu</th>
                                <th class="py-2.5 text-right">Realisasi</th>
                                <th class="py-2.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($anggarans as $ang)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <form action="{{ route('tvri.keuangan.anggaran.update', $ang->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <td class="py-2 w-16">
                                            <input type="text" name="kode" value="{{ $ang->kode }}" class="w-full px-2 py-1 rounded border border-slate-200 text-xs font-bold">
                                        </td>
                                        <td class="py-2">
                                            <input type="text" name="uraian" value="{{ $ang->uraian }}" class="w-full px-2 py-1 rounded border border-slate-200 text-xs font-semibold">
                                        </td>
                                        <td class="py-2 text-right">
                                            <input type="number" step="1000" name="pagu" value="{{ (float)$ang->pagu }}" class="w-28 px-2 py-1 rounded border border-slate-200 text-xs font-bold text-right">
                                        </td>
                                        <td class="py-2 text-right">
                                            <input type="number" step="1000" name="realisasi" value="{{ (float)$ang->realisasi }}" class="w-28 px-2 py-1 rounded border border-slate-200 text-xs font-bold text-right text-emerald-700">
                                        </td>
                                        <td class="py-2 text-center">
                                            <div class="flex items-center justify-center space-x-1">
                                                <button type="submit" class="p-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100" title="Simpan Perubahan">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                    </form>
                                                <form action="{{ route('tvri.keuangan.anggaran.destroy', $ang->id) }}" method="POST" onsubmit="return confirm('Hapus rincian anggaran ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100" title="Hapus">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-400">Belum ada rincian belanja.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 3: RINCIAN PNBP (CRUD)                 -->
    <!-- ========================================== -->
    <div x-show="activeTab === 'pnbp'" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Form Tambah PNBP -->
            <div class="lg:col-span-4 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm h-fit">
                <div class="flex items-center space-x-2 pb-3 mb-4 border-b border-slate-100">
                    <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Tambah Kategori PNBP</h3>
                </div>

                <form action="{{ route('tvri.keuangan.pnbp.store') }}" method="POST" class="space-y-3.5">
                    @csrf
                    <input type="hidden" name="tahun" value="{{ $tahun }}">

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Kategori / Sumber Pendapatan</label>
                        <input type="text" name="jenis" required placeholder="Iklan TV & Radio"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Target Pendapatan (Rp)</label>
                        <input type="number" step="1000" name="target" required placeholder="1500000000"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Realisasi Penerimaan (Rp)</label>
                        <input type="number" step="1000" name="realisasi" required placeholder="1300000000"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs shadow-md transition-all">
                        + Tambah Kategori PNBP
                    </button>
                </form>
            </div>

            <!-- Tabel Daftar PNBP -->
            <div class="lg:col-span-8 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">
                            Daftar Target & Realisasi PNBP
                        </h3>
                        <p class="text-xs text-slate-500">Mempengaruhi grafik komposisi & target vs realisasi di Dashboard</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-400 text-[10px] uppercase font-bold">
                                <th class="py-2.5">Kategori PNBP</th>
                                <th class="py-2.5 text-right">Target (Rp)</th>
                                <th class="py-2.5 text-right">Realisasi (Rp)</th>
                                <th class="py-2.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($pnbps as $p)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <form action="{{ route('tvri.keuangan.pnbp.update', $p->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <td class="py-2">
                                            <input type="text" name="jenis" value="{{ $p->jenis }}" class="w-full px-2 py-1 rounded border border-slate-200 text-xs font-semibold">
                                        </td>
                                        <td class="py-2 text-right">
                                            <input type="number" step="1000" name="target" value="{{ (float)$p->target }}" class="w-32 px-2 py-1 rounded border border-slate-200 text-xs font-bold text-right">
                                        </td>
                                        <td class="py-2 text-right">
                                            <input type="number" step="1000" name="realisasi" value="{{ (float)$p->realisasi }}" class="w-32 px-2 py-1 rounded border border-slate-200 text-xs font-bold text-right text-teal-700">
                                        </td>
                                        <td class="py-2 text-center">
                                            <div class="flex items-center justify-center space-x-1">
                                                <button type="submit" class="p-1.5 rounded-lg bg-teal-50 text-teal-600 hover:bg-teal-100" title="Simpan Perubahan">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                    </form>
                                                <form action="{{ route('tvri.keuangan.pnbp.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus kategori PNBP ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100" title="Hapus">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-slate-400">Belum ada kategori PNBP.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 4: PIUTANG / TAGIHAN MITRA (CRUD)      -->
    <!-- ========================================== -->
    <div x-show="activeTab === 'piutang'" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Form Tambah Piutang -->
            <div class="lg:col-span-4 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm h-fit">
                <div class="flex items-center space-x-2 pb-3 mb-4 border-b border-slate-100">
                    <div class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Tambah Piutang / Tagihan</h3>
                </div>

                <form action="{{ route('tvri.keuangan.piutang.store') }}" method="POST" class="space-y-3.5">
                    @csrf
                    <input type="hidden" name="tahun" value="{{ $tahun }}">

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Mitra / Klien</label>
                        <input type="text" name="klien" required placeholder="Pemda Maluku"
                                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nilai Tagihan (Rp)</label>
                        <input type="number" step="1000" name="nilai" required placeholder="150000000"
                                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Jatuh Tempo</label>
                        <input type="date" name="jatuh_tempo"
                                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Status Pembayaran</label>
                        <select name="status" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                            <option value="Belum Bayar">Belum Bayar</option>
                            <option value="Proses">Proses</option>
                            <option value="Lunas">Lunas</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md transition-all">
                        + Tambah Piutang
                    </button>
                </form>
            </div>

            <!-- Tabel Daftar Piutang -->
            <div class="lg:col-span-8 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">
                            Daftar Piutang & Tagihan Aktif
                        </h3>
                        <p class="text-xs text-slate-500">Mempengaruhi kotak stat Card 6 & tabel piutang di Dashboard</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-400 text-[10px] uppercase font-bold">
                                <th class="py-2.5">Mitra / Klien</th>
                                <th class="py-2.5 text-right">Nilai Tagihan</th>
                                <th class="py-2.5">Jatuh Tempo</th>
                                <th class="py-2.5 text-center">Status</th>
                                <th class="py-2.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($piutangs as $piu)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <form action="{{ route('tvri.keuangan.piutang.update', $piu->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="tahun" value="{{ $tahun }}">
                                        <td class="py-2">
                                            <input type="text" name="klien" value="{{ $piu->klien }}" class="w-full px-2 py-1 rounded border border-slate-200 text-xs font-semibold">
                                        </td>
                                        <td class="py-2 text-right">
                                            <input type="number" step="1000" name="nilai" value="{{ (float)$piu->nilai }}" class="w-28 px-2 py-1 rounded border border-slate-200 text-xs font-bold text-right text-rose-600">
                                        </td>
                                        <td class="py-2">
                                            <input type="date" name="jatuh_tempo" value="{{ $piu->jatuh_tempo ? (is_string($piu->jatuh_tempo) ? substr($piu->jatuh_tempo, 0, 10) : $piu->jatuh_tempo->format('Y-m-d')) : '' }}" class="w-32 px-2 py-1 rounded border border-slate-200 text-xs">
                                        </td>
                                        <td class="py-2 text-center">
                                            <select name="status" class="px-2 py-1 rounded border border-slate-200 text-[10px] font-bold">
                                                <option value="Belum Bayar" {{ $piu->status == 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                                <option value="Proses" {{ $piu->status == 'Proses' ? 'selected' : '' }}>Proses</option>
                                                <option value="Lunas" {{ $piu->status == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                                            </select>
                                        </td>
                                        <td class="py-2 text-center">
                                            <div class="flex items-center justify-center space-x-1">
                                                <button type="submit" class="p-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100" title="Simpan">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                    </form>
                                                <form action="{{ route('tvri.keuangan.piutang.destroy', $piu->id) }}" method="POST" onsubmit="return confirm('Hapus piutang ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                                                    <button type="submit" class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100" title="Hapus">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-400">Belum ada daftar piutang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 5: ARUS KAS BULANAN (JAN - DES)        -->
    <!-- ========================================== -->
    <div x-show="activeTab === 'aruskas'" class="space-y-6">
        <form action="{{ route('tvri.keuangan.arus-kas.update') }}" method="POST">
            @csrf
            <input type="hidden" name="tahun" value="{{ $tahun }}">

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">
                            Input Arus Kas Bulanan (T.A. {{ $tahun }})
                        </h3>
                        <p class="text-xs text-slate-500">Saldo bulanan akan otomatis dikalkulasi (Penerimaan dikurangi Pengeluaran)</p>
                    </div>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md">
                        Simpan Seluruh Arus Kas
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    @foreach($arusKas as $ak)
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs space-y-2">
                            <div class="flex items-center justify-between font-black text-slate-800 border-b border-slate-200/60 pb-1">
                                <span>Bulan {{ $ak->bulan }}</span>
                                <span class="text-[10px] text-blue-600">Urutan ke-{{ $ak->urutan }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-[10px] text-emerald-700 font-bold">Penerimaan (Rp)</label>
                                    <input type="number" step="1000" name="arus_kas[{{ $ak->id }}][penerimaan]" value="{{ (float)$ak->penerimaan }}"
                                           class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-emerald-800">
                                </div>
                                <div>
                                    <label class="text-[10px] text-rose-700 font-bold">Pengeluaran (Rp)</label>
                                    <input type="number" step="1000" name="arus_kas[{{ $ak->id }}][pengeluaran]" value="{{ (float)$ak->pengeluaran }}"
                                           class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-rose-800">
                                </div>
                            </div>
                            <div class="flex items-center justify-between pt-1 text-[10px] text-slate-500">
                                <span>Saldo saat ini:</span>
                                <span class="font-bold text-blue-700">{{ $formatRp((float)$ak->saldo) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 pt-3 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md">
                        Simpan Seluruh Arus Kas
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- ========================================== -->
    <!-- TAB 6: RENCANA KEGIATAN KEUANGAN (CRUD)    -->
    <!-- ========================================== -->
    <div x-show="activeTab === 'kegiatan'" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Form Tambah Kegiatan -->
            <div class="lg:col-span-4 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm h-fit">
                <div class="flex items-center space-x-2 pb-3 mb-4 border-b border-slate-100">
                    <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Tambah Rencana Kegiatan</h3>
                </div>

                <form action="{{ route('tvri.keuangan.kegiatan.store') }}" method="POST" class="space-y-3.5">
                    @csrf
                    <input type="hidden" name="tahun" value="{{ $tahun }}">

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Urut</label>
                        <input type="number" name="no_urut" required value="{{ count($kegiatans) + 1 }}"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Kegiatan Strategis</label>
                        <input type="text" name="kegiatan" required placeholder="Rekonsiliasi BMN"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Target Waktu (Contoh: Nov 2024)</label>
                        <input type="text" name="target" placeholder="Des 2024"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Realisasi / Progres</label>
                        <input type="text" name="realisasi" placeholder="Dalam Proses / 85%"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Status Kegiatan</label>
                        <select name="status" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold">
                            <option value="On Track">On Track</option>
                            <option value="Belum Mulai">Belum Mulai</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Tertunda">Tertunda</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition-all">
                        + Tambah Kegiatan
                    </button>
                </form>
            </div>

            <!-- Tabel Daftar Kegiatan -->
            <div class="lg:col-span-8 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">
                            Daftar Rencana & Realisasi Kegiatan
                        </h3>
                        <p class="text-xs text-slate-500">Mempengaruhi tabel kegiatan strategis di Dashboard</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-400 text-[10px] uppercase font-bold">
                                <th class="py-2.5 w-12 text-center">No</th>
                                <th class="py-2.5">Kegiatan Strategis</th>
                                <th class="py-2.5">Target</th>
                                <th class="py-2.5">Realisasi</th>
                                <th class="py-2.5 text-center">Status</th>
                                <th class="py-2.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($kegiatans as $k)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <form action="{{ route('tvri.keuangan.kegiatan.update', $k->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <td class="py-2 text-center">
                                            <input type="number" name="no_urut" value="{{ $k->no_urut }}" class="w-12 px-1 py-1 rounded border border-slate-200 text-xs font-bold text-center">
                                        </td>
                                        <td class="py-2">
                                            <input type="text" name="kegiatan" value="{{ $k->kegiatan }}" class="w-full px-2 py-1 rounded border border-slate-200 text-xs font-semibold">
                                        </td>
                                        <td class="py-2">
                                            <input type="text" name="target" value="{{ $k->target }}" class="w-24 px-2 py-1 rounded border border-slate-200 text-xs">
                                        </td>
                                        <td class="py-2">
                                            <input type="text" name="realisasi" value="{{ $k->realisasi }}" class="w-24 px-2 py-1 rounded border border-slate-200 text-xs">
                                        </td>
                                        <td class="py-2 text-center">
                                            <select name="status" class="px-2 py-1 rounded border border-slate-200 text-[10px] font-bold">
                                                <option value="On Track" {{ $k->status == 'On Track' ? 'selected' : '' }}>On Track</option>
                                                <option value="Belum Mulai" {{ $k->status == 'Belum Mulai' ? 'selected' : '' }}>Belum Mulai</option>
                                                <option value="Selesai" {{ $k->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                                <option value="Tertunda" {{ $k->status == 'Tertunda' ? 'selected' : '' }}>Tertunda</option>
                                            </select>
                                        </td>
                                        <td class="py-2 text-center">
                                            <div class="flex items-center justify-center space-x-1">
                                                <button type="submit" class="p-1.5 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100" title="Simpan">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                    </form>
                                                <form action="{{ route('tvri.keuangan.kegiatan.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus kegiatan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100" title="Hapus">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-slate-400">Belum ada rencana kegiatan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 7: DOKUMEN KEUANGAN (DMS)             -->
    <!-- ========================================== -->
    <div x-show="activeTab === 'dokumen'" class="space-y-6">
        <!-- Banner Sinkronisasi -->
        <div class="bg-gradient-to-r from-[#00264A] to-[#005B9F] rounded-2xl p-6 text-white shadow-md flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-2 text-teal-300 text-xs font-bold uppercase tracking-wider mb-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Sinkronisasi Otomatis Terhubung</span>
                </div>
                <h3 class="text-lg font-black text-white leading-tight">Pangkalan Dokumen & Kearsipan Keuangan TVRI Stasiun Maluku</h3>
                <p class="text-xs text-blue-200 mt-1 max-w-2xl leading-relaxed">
                    Setiap perubahan pagu, realisasi belanja, PNBP, piutang, dan arus kas secara otomatis tersinkronisasi ke <strong>Pangkalan Dokumen</strong> dan <strong>Dashboard Kearsipan</strong> dalam folder <em>Subbag Perencanaan & Keuangan (DPA/RKA)</em>.
                </p>
            </div>
            <div class="flex items-center space-x-2 flex-shrink-0">
                <form action="{{ route('tvri.keuangan.sync-dokumen') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                    <button type="submit" class="inline-flex items-center space-x-1.5 px-4 py-2.5 rounded-xl text-xs font-bold bg-emerald-500 hover:bg-emerald-600 text-white shadow transition-all hover:scale-[1.02]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        <span>Sinkronkan Sekarang</span>
                    </button>
                </form>
                <a href="{{ route('dokumen.documents.create') }}" class="inline-flex items-center space-x-1.5 px-4 py-2.5 rounded-xl text-xs font-bold bg-white/10 hover:bg-white/20 text-white border border-white/20 transition-all">
                    <svg class="w-4 h-4 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Upload SPJ/DIPA Baru</span>
                </a>
            </div>
        </div>

        <!-- Tabel Dokumen Tersinkronisasi -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-bold text-[#00264A] uppercase tracking-wide">Daftar Dokumen Keuangan Tersinkronisasi (DMS)</h3>
                    <p class="text-xs text-slate-500">Arsip digital bukti kas, DIPA, LRA, PNBP, dan piutang yang aktif di Pangkalan Dokumen</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-teal-50 text-teal-700 border border-teal-200">
                    {{ count($dokumenKeuangans) }} Berkas Terdata
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 text-[10px] uppercase font-bold">
                            <th class="py-2.5">Judul Dokumen & Nomor Registrasi</th>
                            <th class="py-2.5">Kategori / Klasifikasi</th>
                            <th class="py-2.5">T.A. / Tanggal</th>
                            <th class="py-2.5">Format & Ukuran</th>
                            <th class="py-2.5 text-center">Status</th>
                            <th class="py-2.5 text-center">Aksi Pangkalan Dokumen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($dokumenKeuangans as $doc)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3">
                                    <div class="flex items-start space-x-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">
                                            📄
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ route('dokumen.documents.show', $doc->id) }}" class="font-bold text-slate-900 hover:text-blue-600 transition-colors line-clamp-1">
                                                {{ $doc->title }}
                                            </a>
                                            <p class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $doc->document_number ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ $doc->category }}
                                    </span>
                                </td>
                                <td class="py-3 font-semibold text-slate-600">
                                    <div>Tahun {{ $doc->year ?? $tahun }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $doc->created_at ? $doc->created_at->format('d M Y') : '-' }}</div>
                                </td>
                                <td class="py-3">
                                    <span class="font-black text-slate-700">{{ strtoupper($doc->file_type ?? 'PDF') }}</span>
                                    <span class="text-[10px] text-slate-400 block">{{ $doc->file_size ?? '1.2 MB' }}</span>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        {{ $doc->status ?? 'AKTIF' }}
                                    </span>
                                </td>
                                <td class="py-3 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('dokumen.documents.show', $doc->id) }}" 
                                           class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 transition-colors"
                                           title="Buka Detail di Pangkalan Dokumen">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            <span>Detail</span>
                                        </a>
                                        @if($doc->file_path)
                                            <a href="{{ route('dokumen.documents.download', $doc->id) }}" 
                                               class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors"
                                               title="Unduh Berkas Resmi">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">
                                    <p class="font-bold text-slate-600">Belum ada dokumen keuangan yang tersinkronisasi.</p>
                                    <p class="text-xs mt-1">Klik tombol "Sinkronkan Sekarang" di atas untuk membuat arsip LRA, DIPA, dan PNBP secara otomatis.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 8: DOKUMEN & SOP KEUANGAN (CRUD)       -->
    <!-- ========================================== -->
    <div x-show="activeTab === 'sop'" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Form Tambah SOP Keuangan (4 Kolom) -->
            <div class="lg:col-span-4 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm h-fit">
                <div class="flex items-center space-x-2 pb-3 mb-4 border-b border-slate-100">
                    <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <h3 class="text-sm font-black text-[#00264A] uppercase tracking-wide">Tambah SOP Keuangan</h3>
                </div>

                <form action="{{ route('tvri.keuangan.sop.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5">
                    @csrf
                    <input type="hidden" name="tahun" value="{{ $tahun }}">

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kode SOP</label>
                        <input type="text" name="kode" required placeholder="SOP-KEU-07"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-teal-500">
                        <p class="text-[10px] text-slate-400 mt-0.5">Contoh: SOP-KEU-01, SOP-KEU-02</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Judul SOP / Prosedur</label>
                        <input type="text" name="judul" required placeholder="Judul Prosedur Operasional Standar"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-teal-500">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kategori</label>
                            <select name="kategori" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-teal-500">
                                <option value="Pengelolaan Anggaran">Pengelolaan Anggaran</option>
                                <option value="Pengelolaan PNBP">Pengelolaan PNBP</option>
                                <option value="Perbendaharaan">Perbendaharaan</option>
                                <option value="Pengelolaan Piutang">Pengelolaan Piutang</option>
                                <option value="Akuntansi & Pelaporan">Akuntansi & Pelaporan</option>
                                <option value="Kinerja Anggaran">Kinerja Anggaran</option>
                                <option value="Manajemen Risiko">Manajemen Risiko</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Versi</label>
                            <input type="text" name="versi" value="v1.0"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-teal-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
                            <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-teal-500">
                                <option value="Aktif">Aktif</option>
                                <option value="Revisi">Revisi</option>
                                <option value="Nonaktif">Nonaktif</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Urutan</label>
                            <input type="number" name="urutan" value="{{ count($sops) + 1 }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-teal-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan / Uraian Prosedur</label>
                        <textarea name="keterangan" rows="3" placeholder="Uraian ringkas langkah kerja dan dasar hukum SOP..."
                                  class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-normal focus:ring-2 focus:ring-teal-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Unggah Fisik SOP (PDF / Dokumen / Gambar)</label>
                        <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png"
                               class="w-full px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-medium text-slate-700 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 cursor-pointer">
                        <p class="text-[10px] text-slate-400 mt-0.5">Format: PDF, Word, Excel, JPG, PNG (Maks 20MB)</p>
                    </div>

                    <button type="submit" 
                            class="w-full py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs shadow-md transition-all">
                        Simpan SOP Keuangan
                    </button>
                </form>
            </div>

            <!-- Tabel Daftar SOP Keuangan (8 Kolom) -->
            <div class="lg:col-span-8 bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 flex-wrap gap-2">
                    <div>
                        <h3 class="text-sm font-bold text-[#00264A] uppercase tracking-wide">Daftar Dokumen & SOP Bidang Keuangan</h3>
                        <p class="text-xs text-slate-500">Prosedur Operasional Standar resmi tata kelola keuangan, PNBP, dan perbendaharaan TVRI Stasiun Maluku</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button type="button" id="btnBulkSopKeuangan" onclick="submitBulkSopKeuangan()" class="hidden px-3 py-1.5 rounded-xl bg-rose-600 text-white font-black text-xs hover:bg-rose-700 shadow-md transition-all flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <span id="btnBulkSopKeuanganText">Hapus SOP Terpilih</span>
                        </button>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-teal-50 text-teal-700 border border-teal-200">
                            {{ count($sops) }} Dokumen SOP
                        </span>
                    </div>
                </div>

                <form id="formBulkSopKeuangan" action="{{ route('tvri.keuangan.sop.bulk-destroy') }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                    <div id="bulkSopKeuanganContainer"></div>
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-400 text-[10px] uppercase font-bold">
                                <th class="py-2.5 w-8 text-center">
                                    <input type="checkbox" id="checkAllSopKeuangan" class="rounded border-slate-300 text-blue-600 w-4 h-4 cursor-pointer" title="Kotak Central: Centang Semua SOP Keuangan">
                                </th>
                                <th class="py-2.5 w-10 text-center">No</th>
                                <th class="py-2.5 w-24">Kode</th>
                                <th class="py-2.5">Judul SOP & Keterangan</th>
                                <th class="py-2.5">Kategori</th>
                                <th class="py-2.5 text-center">Status</th>
                                <th class="py-2.5 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($sops as $sop)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-2.5 text-center">
                                        <input type="checkbox" value="{{ $sop->id }}" class="sop-keuangan-cb rounded border-slate-300 text-blue-600 w-4 h-4 cursor-pointer">
                                    </td>
                                    <form action="{{ route('tvri.keuangan.sop.update', $sop->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="tahun" value="{{ $tahun }}">
                                        <td class="py-2.5 text-center">
                                            <input type="number" name="urutan" value="{{ $sop->urutan }}" class="w-10 px-1 py-1 rounded border border-slate-200 text-xs font-bold text-center">
                                        </td>
                                        <td class="py-2.5">
                                            <input type="text" name="kode" value="{{ $sop->kode }}" class="w-24 px-2 py-1 rounded border border-slate-200 text-xs font-mono font-bold text-teal-700">
                                        </td>
                                        <td class="py-2.5">
                                            <input type="text" name="judul" value="{{ $sop->judul }}" class="w-full px-2 py-1 rounded border border-slate-200 text-xs font-semibold text-slate-800">
                                            <input type="text" name="keterangan" value="{{ $sop->keterangan }}" placeholder="Keterangan SOP..." class="w-full px-2 py-0.5 mt-1 rounded border border-slate-100 text-[10px] text-slate-500">
                                        </td>
                                        <td class="py-2.5">
                                            <select name="kategori" class="w-28 px-1.5 py-1 rounded border border-slate-200 text-[10px] font-semibold">
                                                @foreach(['Pengelolaan Anggaran', 'Pengelolaan PNBP', 'Perbendaharaan', 'Pengelolaan Piutang', 'Akuntansi & Pelaporan', 'Kinerja Anggaran', 'Manajemen Risiko'] as $kat)
                                                    <option value="{{ $kat }}" {{ $sop->kategori == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                                @endforeach
                                            </select>
                                            <div class="text-[9px] text-slate-400 font-mono mt-0.5">Versi: 
                                                <input type="text" name="versi" value="{{ $sop->versi }}" class="w-12 px-1 py-0.5 rounded border border-slate-200 text-[9px] inline">
                                            </div>
                                        </td>
                                        <td class="py-2.5 text-center">
                                            <select name="status" class="px-2 py-1 rounded border border-slate-200 text-[10px] font-bold {{ $sop->status == 'Aktif' ? 'text-emerald-700 bg-emerald-50' : 'text-amber-700 bg-amber-50' }}">
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
                                                <button type="submit" class="p-1.5 rounded-lg bg-teal-50 text-teal-600 hover:bg-teal-100 transition-colors" title="Simpan Perubahan">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                    </form>
                                                <form action="{{ route('tvri.keuangan.sop.destroy', $sop->id) }}" method="POST" onsubmit="return confirm('Hapus SOP {{ $sop->kode }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                                                    <button type="submit" class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors" title="Hapus SOP">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-slate-400">
                                        <p class="font-bold text-slate-600">Belum ada SOP Keuangan yang tersimpan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkAll = document.getElementById('checkAllSopKeuangan');
    const items = document.querySelectorAll('.sop-keuangan-cb');
    const btn = document.getElementById('btnBulkSopKeuangan');
    const btnText = document.getElementById('btnBulkSopKeuanganText');

    function update() {
        const count = document.querySelectorAll('.sop-keuangan-cb:checked').length;
        if (btn) {
            if (count > 0) {
                btn.classList.remove('hidden');
                if (btnText) btnText.innerText = `Hapus (${count}) SOP Terpilih`;
            } else {
                btn.classList.add('hidden');
            }
        }
    }

    if (checkAll) {
        checkAll.addEventListener('change', function() {
            items.forEach(cb => cb.checked = checkAll.checked);
            update();
        });
    }

    items.forEach(cb => cb.addEventListener('change', update));

    // Auto-kalkulasi IKPA (Nilai * Bobot -> Skor Capaian & Total Preview)
    function recalculateIkpaForm() {
        let total = 0;
        const rows = document.querySelectorAll('.ikpa-input-skor');
        rows.forEach(skorInput => {
            const val = parseFloat(skorInput.value.replace('%', '').replace(',', '.')) || 0;
            total += val;
        });

        const totalEl = document.getElementById('ikpa-calc-total');
        const predikatEl = document.getElementById('ikpa-calc-predikat');
        if (totalEl) {
            totalEl.innerText = total.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
        if (predikatEl) {
            const pred = total >= 90 ? 'Sangat Baik' : (total >= 80 ? 'Baik' : (total >= 70 ? 'Cukup' : 'Perlu Evaluasi'));
            predikatEl.innerText = pred;
        }
    }

    document.querySelectorAll('.ikpa-input-nilai, .ikpa-input-bobot').forEach(input => {
        input.addEventListener('input', function() {
            const id = this.getAttribute('data-id');
            const nilaiInput = document.querySelector(`.ikpa-input-nilai[data-id="${id}"]`);
            const bobotInput = document.querySelector(`.ikpa-input-bobot[data-id="${id}"]`);
            const skorInput = document.querySelector(`.ikpa-input-skor[data-id="${id}"]`);

            if (nilaiInput && bobotInput && skorInput) {
                const n = parseFloat(nilaiInput.value.replace(',', '.')) || 0;
                const b = parseFloat(bobotInput.value.replace('%', '').replace(',', '.')) || 0;
                if (b > 0) {
                    const calc = (n * b) / 100;
                    skorInput.value = calc.toFixed(2).replace('.', ',');
                }
            }
            recalculateIkpaForm();
        });
    });

    document.querySelectorAll('.ikpa-input-skor').forEach(input => {
        input.addEventListener('input', recalculateIkpaForm);
    });
});

window.submitBulkSopKeuangan = function() {
    const checked = document.querySelectorAll('.sop-keuangan-cb:checked');
    if (checked.length === 0) return;
    if (confirm(`Apakah Anda yakin ingin menghapus ${checked.length} SOP Keuangan terpilih?`)) {
        const form = document.getElementById('formBulkSopKeuangan');
        const container = document.getElementById('bulkSopKeuanganContainer');
        container.innerHTML = '';
        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = cb.value;
            container.appendChild(input);
        });
        form.submit();
    }
};
</script>
@endsection
