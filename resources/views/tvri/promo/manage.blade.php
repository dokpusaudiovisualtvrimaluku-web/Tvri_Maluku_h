@extends('layouts.app')

@section('title', 'Input & Kelola Data Promo - TVRI Maluku')

@section('content')
<div class="space-y-6 pb-12">

    <!-- Header -->
    <div class="bg-gradient-to-r from-[#001D38] via-[#00264A] to-[#003B70] rounded-3xl p-6 text-white shadow-xl relative overflow-hidden border border-indigo-400/20">
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-white p-2 shadow-lg flex-shrink-0 flex items-center justify-center">
                    <img src="{{ asset('images/logo_tvri.png') }}" alt="Logo TVRI" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="text-xl font-black uppercase tracking-tight">Input & Kelola Data Promo</h1>
                    <p class="text-xs text-indigo-200 font-extrabold tracking-wider uppercase">Manajemen Materi Promosi & Kampanye Media TVRI Maluku</p>
                </div>
            </div>
            <div class="flex items-center space-x-2.5 flex-wrap gap-2">
                <!-- Live Clock Badge -->
                <div class="bg-white/10 backdrop-blur-md px-3.5 py-1.5 rounded-xl border border-white/15 flex items-center space-x-2 text-xs font-bold text-white">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="global-live-datetime-bullet">{{ now()->isoFormat('dddd, D MMMM Y') }} &bull; {{ now()->format('H:i:s') }} WIT</span>
                </div>

                <!-- Tombol Reseed / Sinkronisasi Data -->
                <form action="{{ route('tvri.promo.reseed') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memulihkan / menyinkronkan seluruh data Promo & Citra sesuai data spesifikasi?')">
                    @csrf
                    <button type="submit" 
                            class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Pulihkan / Sinkronkan Data</span>
                    </button>
                </form>

                <a href="{{ route('tvri.promo.dashboard') }}" 
                   class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-white/10 hover:bg-white/20 border border-white/20 text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Kembali ke Dasboar Unit Kerja Promo</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Success & Error -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between">
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center justify-between">
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Navigation Tabs -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-2">
        <div class="flex flex-wrap gap-2">
            @php
                $activeTab = request('tab', $tab ?? 'materi-kampanye');
            @endphp
            <a href="{{ route('tvri.promo.manage', ['tab' => 'materi-kampanye']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $activeTab === 'materi-kampanye' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                <span>Materi & Kampanye Media ({{ count($materiList) + count($kampanyeList) }})</span>
            </a>
            <a href="{{ route('tvri.promo.manage', ['tab' => 'ringkasan']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $activeTab === 'ringkasan' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Parameter & Ringkasan Metrik</span>
            </a>
            <a href="{{ route('tvri.promo.manage', ['tab' => 'jadwal-agenda']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $activeTab === 'jadwal-agenda' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Jadwal Tayang & Agenda Event</span>
            </a>
            <a href="{{ route('tvri.promo.manage', ['tab' => 'sop']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $activeTab === 'sop' ? 'bg-[#005B9F] text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <span>SOP & Regulasi ({{ $sops->count() }})</span>
            </a>
        </div>
    </div>

    <!-- TAB 1: MATERI & KAMPANYE -->
    @if($activeTab === 'materi-kampanye')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Form 1: Input Materi Promo -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-sm font-black uppercase text-[#00264A] flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                    <span>Tambah Materi Promo Baru</span>
                </h2>
                <span class="text-[10px] font-bold text-slate-400">Video / Bumper / Filler</span>
            </div>

            <form action="{{ route('tvri.promo.materi.store') }}" method="POST" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Materi Promo</label>
                    <input type="text" name="nama" required placeholder="Contoh: Maluku Bisa! (Video Promo)" 
                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Materi</label>
                        <select name="jenis" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                            <option value="Video Promo">Video Promo</option>
                            <option value="Bumper Program">Bumper Program</option>
                            <option value="Bumper Ajakan">Bumper Ajakan</option>
                            <option value="Identitas Stasiun">Identitas Stasiun</option>
                            <option value="Filler">Filler</option>
                            <option value="Iklan Layanan Masyarakat">ILM</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Durasi (Detik)</label>
                        <input type="number" name="durasi_detik" required value="30" min="5" max="300"
                               class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status Produksi</label>
                    <select name="status" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                        <option value="Selesai">Selesai (Siap Tayang)</option>
                        <option value="Dalam Produksi">Dalam Produksi</option>
                        <option value="Review">Review</option>
                        <option value="Revisi">Revisi</option>
                        <option value="Ditunda">Ditunda</option>
                    </select>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full py-2.5 rounded-xl text-xs font-black bg-gradient-to-r from-indigo-600 to-[#005B9F] hover:from-indigo-700 hover:to-[#004780] text-white shadow-md transition-all">
                        Simpan Materi Promo
                    </button>
                </div>
            </form>

            <!-- Tabel Daftar Materi Promo Terkini -->
            <div class="pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-[11px] font-extrabold uppercase text-[#00264A]">Daftar Materi di Database ({{ count($materiList) }})</p>
                    <button type="button" id="btnBulkMateri" onclick="submitBulkMateri()" class="hidden px-2.5 py-1 rounded-lg bg-rose-600 text-white text-[10px] font-bold hover:bg-rose-700 shadow-sm transition-all flex items-center space-x-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        <span id="btnBulkMateriText">Hapus Terpilih</span>
                    </button>
                </div>

                <form id="formBulkMateri" action="{{ route('tvri.promo.materi.bulk-destroy') }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                    <div id="bulkMateriContainer"></div>
                </form>

                <div class="overflow-x-auto max-h-60 overflow-y-auto border border-slate-100 rounded-xl">
                    <table class="w-full text-left text-xs">
                        <thead class="border-b border-slate-200 text-[10px] font-black uppercase text-slate-400 sticky top-0 bg-white">
                            <tr>
                                <th class="py-2 px-2 w-8 text-center">
                                    <input type="checkbox" id="checkAllMateri" class="rounded border-slate-300 text-blue-600 w-3.5 h-3.5 cursor-pointer" title="Kotak Central: Centang Semua Materi">
                                </th>
                                <th class="py-2">Nama</th>
                                <th class="py-2">Jenis</th>
                                <th class="py-2">Durasi</th>
                                <th class="py-2">Status</th>
                                <th class="py-2 px-2 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($materiList as $m)
                            <tr>
                                <td class="py-2 px-2 text-center">
                                    <input type="checkbox" value="{{ $m->id }}" class="materi-cb rounded border-slate-300 text-blue-600 w-3.5 h-3.5 cursor-pointer">
                                </td>
                                <td class="py-2 font-bold text-[#00264A]">{{ $m->nama }}</td>
                                <td class="py-2 text-slate-500 text-[11px]">{{ $m->jenis }}</td>
                                <td class="py-2 font-mono text-cyan-700">{{ $m->durasi_detik }}s</td>
                                <td class="py-2">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold {{ $m->status === 'Selesai' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                        {{ $m->status }}
                                    </span>
                                </td>
                                <td class="py-2 px-2 text-right">
                                    <form action="{{ route('tvri.promo.materi.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus materi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 text-[10px] font-bold">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="py-3 text-center text-slate-400 text-xs">Belum ada data materi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Form 2: Input Kampanye Promo -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-sm font-black uppercase text-[#00264A] flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-cyan-600"></span>
                    <span>Tambah Kampanye Media Baru</span>
                </h2>
                <span class="text-[10px] font-bold text-slate-400">TV & Multi-Platform</span>
            </div>

            <form action="{{ route('tvri.promo.kampanye.store') }}" method="POST" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Kampanye</label>
                    <input type="text" name="nama" required placeholder="Contoh: Ayo Nonton TVRI" 
                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kanal Media</label>
                        <select name="media" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                            <option value="TV On-Air">TV On-Air</option>
                            <option value="YouTube">YouTube</option>
                            <option value="Instagram">Instagram</option>
                            <option value="TikTok">TikTok</option>
                            <option value="Website">Website</option>
                            <option value="Media Luar Ruang">Media Luar Ruang</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Status Kampanye</label>
                        <select name="status" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                            <option value="Aktif">Aktif</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Persiapan">Persiapan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Target Spot / Tayang</label>
                        <input type="number" name="target" required value="100" min="1"
                               class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Realisasi Tayang</label>
                        <input type="number" name="realisasi" required value="92" min="0"
                               class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Estimasi Reach</label>
                        <input type="number" name="reach" required value="150000" min="0"
                               class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Estimasi Engagement</label>
                        <input type="number" name="engagement" required value="25000" min="0"
                               class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full py-2.5 rounded-xl text-xs font-black bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white shadow-md transition-all">
                        Simpan Kampanye
                    </button>
                </div>
            </form>

            <!-- Tabel Daftar Kampanye Terkini -->
            <div class="pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-[11px] font-extrabold uppercase text-[#00264A]">Daftar Kampanye di Database ({{ count($kampanyeList) }})</p>
                    <button type="button" id="btnBulkKampanye" onclick="submitBulkKampanye()" class="hidden px-2.5 py-1 rounded-lg bg-rose-600 text-white text-[10px] font-bold hover:bg-rose-700 shadow-sm transition-all flex items-center space-x-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        <span id="btnBulkKampanyeText">Hapus Terpilih</span>
                    </button>
                </div>

                <form id="formBulkKampanye" action="{{ route('tvri.promo.kampanye.bulk-destroy') }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                    <div id="bulkKampanyeContainer"></div>
                </form>

                <div class="overflow-x-auto max-h-60 overflow-y-auto border border-slate-100 rounded-xl">
                    <table class="w-full text-left text-xs">
                        <thead class="border-b border-slate-200 text-[10px] font-black uppercase text-slate-400 sticky top-0 bg-white">
                            <tr>
                                <th class="py-2 px-2 w-8 text-center">
                                    <input type="checkbox" id="checkAllKampanye" class="rounded border-slate-300 text-blue-600 w-3.5 h-3.5 cursor-pointer" title="Kotak Central: Centang Semua Kampanye">
                                </th>
                                <th class="py-2">Nama</th>
                                <th class="py-2">Media</th>
                                <th class="py-2">Reach</th>
                                <th class="py-2">Status</th>
                                <th class="py-2 px-2 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($kampanyeList as $k)
                            <tr>
                                <td class="py-2 px-2 text-center">
                                    <input type="checkbox" value="{{ $k->id }}" class="kampanye-cb rounded border-slate-300 text-blue-600 w-3.5 h-3.5 cursor-pointer">
                                </td>
                                <td class="py-2 font-bold text-[#00264A]">{{ $k->nama }}</td>
                                <td class="py-2 text-slate-500 text-[11px]">{{ $k->media }}</td>
                                <td class="py-2 font-mono text-cyan-700">{{ number_format($k->reach) }}</td>
                                <td class="py-2">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold {{ $k->status === 'Aktif' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $k->status }}
                                    </span>
                                </td>
                                <td class="py-2 px-2 text-right">
                                    <form action="{{ route('tvri.promo.kampanye.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus kampanye ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 text-[10px] font-bold">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="py-3 text-center text-slate-400 text-xs">Belum ada data kampanye.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    @endif

    <!-- TAB 2: PARAMETER & RINGKASAN METRIK -->
    @if($activeTab === 'ringkasan')
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
            <div>
                <h2 class="text-base font-black uppercase text-[#00264A] flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                    <span>Parameter & Ringkasan Metrik Utama Promo</span>
                </h2>
                <p class="text-xs text-slate-500 font-semibold mt-0.5">Nilai parameter ini terhubung langsung secara real-time ke Dashboard Promo dan ke Executive Command Center (Akun Kepala Stasiun).</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">Terintegrasi ECC</span>
        </div>

        <form action="{{ route('tvri.promo.ringkasan.update') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <!-- 1. Total Kampanye -->
                <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/80 space-y-2">
                    <label class="block text-xs font-black uppercase text-[#00264A]">1. Total Kampanye</label>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <span class="text-[10px] text-slate-500 font-bold block mb-1">Jumlah</span>
                            <input type="number" name="total_kampanye" value="{{ $ringkasan->total_kampanye }}" required
                                   class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F] font-bold">
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-500 font-bold block mb-1">Diff / Pertumbuhan</span>
                            <input type="text" name="diff_kampanye" value="{{ $ringkasan->diff_kampanye }}" required
                                   class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F] font-bold text-emerald-600">
                        </div>
                    </div>
                </div>

                <!-- 2. Materi Diproduksi -->
                <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/80 space-y-2">
                    <label class="block text-xs font-black uppercase text-[#00264A]">2. Materi Diproduksi</label>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <span class="text-[10px] text-slate-500 font-bold block mb-1">Jumlah</span>
                            <input type="number" name="materi_diproduksi" value="{{ $ringkasan->materi_diproduksi }}" required
                                   class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F] font-bold">
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-500 font-bold block mb-1">Diff / Pertumbuhan</span>
                            <input type="text" name="diff_materi" value="{{ $ringkasan->diff_materi }}" required
                                   class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F] font-bold text-emerald-600">
                        </div>
                    </div>
                </div>

                <!-- 3. Penayangan On-Air -->
                <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/80 space-y-2">
                    <label class="block text-xs font-black uppercase text-[#00264A]">3. Penayangan On-Air</label>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <span class="text-[10px] text-slate-500 font-bold block mb-1">Jumlah & Satuan</span>
                            <input type="text" name="penayangan_onair" value="{{ $ringkasan->penayangan_onair }}" required
                                   class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F] font-bold">
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-500 font-bold block mb-1">Diff / Pertumbuhan</span>
                            <input type="text" name="diff_onair" value="{{ $ringkasan->diff_onair }}" required
                                   class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F] font-bold text-emerald-600">
                        </div>
                    </div>
                </div>

                <!-- 4. Reach Total -->
                <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/80 space-y-2">
                    <label class="block text-xs font-black uppercase text-[#00264A]">4. Reach Total (Jangkauan)</label>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <span class="text-[10px] text-slate-500 font-bold block mb-1">Jangkauan</span>
                            <input type="text" name="reach_total" value="{{ $ringkasan->reach_total }}" required
                                   class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F] font-bold text-cyan-700">
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-500 font-bold block mb-1">Diff / Pertumbuhan</span>
                            <input type="text" name="diff_reach" value="{{ $ringkasan->diff_reach }}" required
                                   class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F] font-bold text-emerald-600">
                        </div>
                    </div>
                </div>

                <!-- 5. Engagement Digital -->
                <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/80 space-y-2">
                    <label class="block text-xs font-black uppercase text-[#00264A]">5. Engagement Digital</label>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <span class="text-[10px] text-slate-500 font-bold block mb-1">Interaksi</span>
                            <input type="text" name="engagement_digital" value="{{ $ringkasan->engagement_digital }}" required
                                   class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F] font-bold text-purple-700">
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-500 font-bold block mb-1">Diff / Pertumbuhan</span>
                            <input type="text" name="diff_engagement" value="{{ $ringkasan->diff_engagement }}" required
                                   class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F] font-bold text-emerald-600">
                        </div>
                    </div>
                </div>

                <!-- 6. Tingkat Capaian & Target -->
                <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/80 space-y-2">
                    <label class="block text-xs font-black uppercase text-[#00264A]">6. Tingkat Capaian vs Target</label>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <span class="text-[10px] text-slate-500 font-bold block mb-1">Capaian (%)</span>
                            <input type="number" name="tingkat_capaian" value="{{ $ringkasan->tingkat_capaian }}" required min="0" max="100"
                                   class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F] font-bold text-emerald-600">
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-500 font-bold block mb-1">Target (%)</span>
                            <input type="number" name="target_capaian" value="{{ $ringkasan->target_capaian }}" required min="0" max="100"
                                   class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F] font-bold text-indigo-600">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-black bg-[#005B9F] hover:bg-[#004780] text-white shadow-md transition-all">
                    Simpan Perubahan Metrik
                </button>
            </div>
        </form>

        <!-- Preview Distribusi Media & Platform Digital -->
        <div class="pt-6 border-t border-slate-100 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-3">
                <h3 class="text-xs font-black uppercase text-[#00264A]">Distribusi Media Promo Saat Ini</h3>
                <div class="space-y-2">
                    @foreach($ringkasan->distribusi_media_json ?: [] as $dm)
                    <div class="p-2.5 rounded-xl border border-slate-100 bg-slate-50 flex items-center justify-between text-xs">
                        <span class="font-bold text-[#00264A]">{{ $dm['media'] }}</span>
                        <div class="flex items-center space-x-2">
                            <span class="text-slate-500">{{ $dm['jumlah'] }} spot</span>
                            <span class="font-mono font-bold {{ $dm['textColor'] }}">{{ $dm['persen'] }}%</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-3">
                <h3 class="text-xs font-black uppercase text-[#00264A]">Performa Platform Digital Saat Ini</h3>
                <div class="grid grid-cols-2 gap-2.5">
                    @foreach($ringkasan->platform_digital_json ?: [] as $pd)
                    <div class="p-3 rounded-xl border {{ $pd['bg'] }} text-xs">
                        <p class="font-bold {{ $pd['color'] }}">{{ $pd['platform'] }}</p>
                        <p class="text-sm font-black text-[#00264A] mt-1">{{ $pd['audience'] }}</p>
                        <p class="text-[11px] text-slate-500">{{ $pd['metric'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- TAB 3: JADWAL TAYANG & AGENDA EVENT -->
    @if($activeTab === 'jadwal-agenda')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Form 1: Tambah Jadwal Penayangan Hari Ini -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <h2 class="text-sm font-black uppercase text-[#00264A] flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                    <span>Tambah Jadwal Penayangan Hari Ini</span>
                </h2>
                <span class="text-[10px] font-bold text-slate-400">Spot Siaran TV</span>
            </div>

            <form action="{{ route('tvri.promo.jadwal.store') }}" method="POST" class="space-y-3">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Waktu (Jam:Menit)</label>
                        <input type="text" name="waktu" required placeholder="Contoh: 17:45"
                               class="w-full text-xs px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Durasi (Detik)</label>
                        <input type="number" name="durasi" required value="30" min="5" max="300"
                               class="w-full text-xs px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Program / Materi Kampanye</label>
                    <input type="text" name="program" required placeholder="Contoh: Maluku Bisa!"
                           class="w-full text-xs px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kanal Media</label>
                        <select name="media" class="w-full text-xs px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                            <option value="TV">TV (On-Air)</option>
                            <option value="YouTube">YouTube</option>
                            <option value="Instagram">Instagram</option>
                            <option value="TikTok">TikTok</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Status Penayangan</label>
                        <select name="status" class="w-full text-xs px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                            <option value="Tayang">Tayang</option>
                            <option value="Menunggu">Menunggu</option>
                            <option value="Terjadwal">Terjadwal</option>
                        </select>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 rounded-xl text-xs font-black bg-[#005B9F] hover:bg-[#004780] text-white shadow-md transition-all">
                        Simpan Jadwal Penayangan
                    </button>
                </div>
            </form>

            <!-- Tabel Daftar Jadwal -->
            <div class="pt-4 border-t border-slate-100">
                <p class="text-[11px] font-extrabold uppercase text-[#00264A] mb-2">Daftar Jadwal Penayangan Hari Ini</p>
                <div class="overflow-x-auto max-h-60 overflow-y-auto border border-slate-100 rounded-xl">
                    <table class="w-full text-left text-xs">
                        <thead class="border-b border-slate-200 text-[10px] font-black uppercase text-slate-400 sticky top-0 bg-white">
                            <tr>
                                <th class="py-2 px-2">Waktu</th>
                                <th class="py-2">Program</th>
                                <th class="py-2">Media</th>
                                <th class="py-2">Durasi</th>
                                <th class="py-2">Status</th>
                                <th class="py-2 px-2 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($ringkasan->jadwal_tayang_json ?: [] as $idx => $jt)
                            <tr>
                                <td class="py-2 px-2 font-mono font-bold text-cyan-700">{{ $jt['waktu'] }}</td>
                                <td class="py-2 font-bold text-[#00264A]">{{ $jt['program'] }}</td>
                                <td class="py-2 text-slate-500">{{ $jt['media'] }}</td>
                                <td class="py-2 font-mono text-slate-600">{{ $jt['durasi'] }}s</td>
                                <td class="py-2">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold {{ $jt['badge'] ?? 'bg-blue-50 text-blue-700' }}">
                                        {{ $jt['status'] }}
                                    </span>
                                </td>
                                <td class="py-2 px-2 text-right">
                                    <form action="{{ route('tvri.promo.jadwal.destroy', $idx) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 text-[10px] font-bold">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="py-3 text-center text-slate-400 text-xs">Belum ada jadwal penayangan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Form 2: Tambah Agenda Promo & Event -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <h2 class="text-sm font-black uppercase text-[#00264A] flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    <span>Tambah Agenda Promo & Event</span>
                </h2>
                <span class="text-[10px] font-bold text-slate-400">Kalender Strategis</span>
            </div>

            <form action="{{ route('tvri.promo.agenda.store') }}" method="POST" class="space-y-3">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Kegiatan</label>
                        <input type="text" name="tanggal" required placeholder="Contoh: 12 Sep 2025"
                               class="w-full text-xs px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">PIC / Penanggung Jawab</label>
                        <input type="text" name="pic" required value="Tim Promo"
                               class="w-full text-xs px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Kegiatan / Agenda</label>
                    <input type="text" name="kegiatan" required placeholder="Contoh: Peluncuran Program Baru"
                           class="w-full text-xs px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status Agenda</label>
                    <select name="status" class="w-full text-xs px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#005B9F]">
                        <option value="Persiapan">Persiapan</option>
                        <option value="On Progress">On Progress</option>
                        <option value="Planning">Planning</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 rounded-xl text-xs font-black bg-amber-500 hover:bg-amber-600 text-white shadow-md transition-all">
                        Simpan Agenda Promo
                    </button>
                </div>
            </form>

            <!-- Tabel Daftar Agenda -->
            <div class="pt-4 border-t border-slate-100">
                <p class="text-[11px] font-extrabold uppercase text-[#00264A] mb-2">Daftar Agenda Promo & Event</p>
                <div class="overflow-x-auto max-h-60 overflow-y-auto border border-slate-100 rounded-xl">
                    <table class="w-full text-left text-xs">
                        <thead class="border-b border-slate-200 text-[10px] font-black uppercase text-slate-400 sticky top-0 bg-white">
                            <tr>
                                <th class="py-2 px-2">Tanggal</th>
                                <th class="py-2">Kegiatan</th>
                                <th class="py-2">PIC</th>
                                <th class="py-2">Status</th>
                                <th class="py-2 px-2 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($ringkasan->agenda_promo_json ?: [] as $idx => $ap)
                            <tr>
                                <td class="py-2 px-2 font-mono font-bold text-cyan-700 whitespace-nowrap">{{ $ap['tanggal'] }}</td>
                                <td class="py-2 font-bold text-[#00264A]">{{ $ap['kegiatan'] }}</td>
                                <td class="py-2 text-slate-500">{{ $ap['pic'] }}</td>
                                <td class="py-2">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold {{ $ap['badge'] ?? 'bg-blue-50 text-blue-700' }}">
                                        {{ $ap['status'] }}
                                    </span>
                                </td>
                                <td class="py-2 px-2 text-right">
                                    <form action="{{ route('tvri.promo.agenda.destroy', $idx) }}" method="POST" onsubmit="return confirm('Hapus agenda ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 text-[10px] font-bold">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="py-3 text-center text-slate-400 text-xs">Belum ada agenda kegiatan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    @endif

    <!-- TAB 4: SOP & REGULASI UNIT KERJA PROMO -->
    @if($activeTab === 'sop')
    <div class="space-y-6">
        <!-- Card Form Tambah SOP -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-sm font-black uppercase text-[#00264A] flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                    <span>Tambah Dokumen SOP & Regulasi Unit Kerja Promo</span>
                </h2>
                <span class="text-[10px] font-bold text-slate-400">Standar Operasional Baku</span>
            </div>

            <form action="{{ route('tvri.promo.sop.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kode SOP</label>
                    <input type="text" name="kode" required placeholder="Contoh: SOP-PRM-05" class="w-full text-xs rounded-xl border-slate-300 focus:ring-[#005B9F] focus:border-[#005B9F]">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul SOP / Regulasi</label>
                    <input type="text" name="judul" required placeholder="Nama lengkap dokumen SOP Promo" class="w-full text-xs rounded-xl border-slate-300 focus:ring-[#005B9F] focus:border-[#005B9F]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori</label>
                    <input type="text" name="kategori" required list="listKategoriSop" placeholder="Contoh: Produksi Materi" class="w-full text-xs rounded-xl border-slate-300 focus:ring-[#005B9F] focus:border-[#005B9F]">
                    <datalist id="listKategoriSop">
                        <option value="Produksi Materi">
                        <option value="Penyiaran & Traffic">
                        <option value="Distribusi Digital">
                        <option value="Evaluasi Kinerja">
                        <option value="Event & Aktivasi">
                    </datalist>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Versi</label>
                    <input type="text" name="versi" value="v1.0" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-[#005B9F] focus:border-[#005B9F]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Efektif</label>
                    <input type="date" name="tanggal_efektif" class="w-full text-xs rounded-xl border-slate-300 focus:ring-[#005B9F] focus:border-[#005B9F]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status Dokumen</label>
                    <select name="status" class="w-full text-xs rounded-xl border-slate-300 focus:ring-[#005B9F] focus:border-[#005B9F]">
                        <option value="Aktif">Aktif</option>
                        <option value="Revisi">Dalam Revisi</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Urutan Tampil</label>
                    <input type="number" name="urutan" value="{{ $sops->count() + 1 }}" min="1" class="w-full text-xs rounded-xl border-slate-300 focus:ring-[#005B9F] focus:border-[#005B9F]">
                </div>
                <div class="md:col-span-4">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan / Deskripsi Alur Prosedur</label>
                    <textarea name="keterangan" rows="2" placeholder="Uraian ringkas langkah kerja, prosedur atau dasar hukum pelaksanaan..." class="w-full text-xs rounded-xl border-slate-300 focus:ring-[#005B9F] focus:border-[#005B9F]"></textarea>
                </div>
                <div class="md:col-span-4 flex items-center justify-end">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md transition-all flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Simpan Dokumen SOP Baru</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Daftar Dokumen SOP -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-sm font-black uppercase text-[#00264A] flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <span>Daftar Dokumen SOP di Database ({{ $sops->count() }})</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">SOP otomatis terhubung ke Dasboar Unit Kerja Promo & Monitoring ECC</p>
                </div>
                <div class="flex items-center space-x-2">
                    <form id="formBulkSop" action="{{ route('tvri.promo.sop.bulk-destroy') }}" method="POST" onsubmit="return confirm('Hapus SOP terpilih?')">
                        @csrf
                        @method('DELETE')
                        <div id="bulkSopContainer"></div>
                        <button type="button" onclick="submitBulkSop()" id="btnBulkSop" class="hidden px-3.5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-sm transition-all flex items-center space-x-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span id="btnBulkSopText">Hapus Terpilih</span>
                        </button>
                    </form>
                </div>
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
                                <input type="checkbox" value="{{ $sop->id }}" class="sop-cb rounded border-slate-300">
                            </td>
                            <td class="p-3.5 font-mono font-bold text-[#005B9F]">{{ $sop->kode ?? '-' }}</td>
                            <td class="p-3.5">
                                <p class="font-bold text-slate-900">{{ $sop->judul }}</p>
                                @if($sop->keterangan)
                                <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-1">{{ $sop->keterangan }}</p>
                                @endif
                            </td>
                            <td class="p-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700">
                                    {{ $sop->kategori }}
                                </span>
                            </td>
                            <td class="p-3.5 text-center font-mono">{{ $sop->versi ?? 'v1.0' }}</td>
                            <td class="p-3.5 font-mono">{{ $sop->tanggal_efektif ? \Carbon\Carbon::parse($sop->tanggal_efektif)->format('d M Y') : '-' }}</td>
                            <td class="p-3.5 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $sop->status === 'Aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
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
<button type="button" onclick="editSop({{ json_encode($sop) }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit SOP">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form action="{{ route('tvri.promo.sop.destroy', $sop->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SOP ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus SOP">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400">Belum ada dokumen SOP terdata. Silakan gunakan form di atas untuk menambah SOP baru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>

<!-- Modal Edit SOP Promo -->
<div id="modalEditSop" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
            <h3 class="text-sm font-black uppercase text-[#00264A] flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                <span>Edit Dokumen SOP Promo</span>
            </h3>
            <button type="button" onclick="closeModal('modalEditSop')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="formEditSop" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kode SOP</label>
                    <input type="text" id="editSopKode" name="kode" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-[#005B9F] focus:border-[#005B9F]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Versi</label>
                    <input type="text" id="editSopVersi" name="versi" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-[#005B9F] focus:border-[#005B9F]">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Judul SOP</label>
                <input type="text" id="editSopJudul" name="judul" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-[#005B9F] focus:border-[#005B9F]">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori</label>
                    <input type="text" id="editSopKategori" name="kategori" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-[#005B9F] focus:border-[#005B9F]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
                    <select id="editSopStatus" name="status" class="w-full text-xs rounded-xl border-slate-300 focus:ring-[#005B9F] focus:border-[#005B9F]">
                        <option value="Aktif">Aktif</option>
                        <option value="Revisi">Dalam Revisi</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Efektif</label>
                    <input type="date" id="editSopTanggal" name="tanggal_efektif" class="w-full text-xs rounded-xl border-slate-300 focus:ring-[#005B9F] focus:border-[#005B9F]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Urutan</label>
                    <input type="number" id="editSopUrutan" name="urutan" min="1" class="w-full text-xs rounded-xl border-slate-300 focus:ring-[#005B9F] focus:border-[#005B9F]">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan / Alur Prosedur</label>
                <textarea id="editSopKeterangan" name="keterangan" rows="2" class="w-full text-xs rounded-xl border-slate-300 focus:ring-[#005B9F] focus:border-[#005B9F]"></textarea>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeModal('modalEditSop')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md transition-all">Perbarui SOP</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function setupBulk(checkAllId, itemClass, btnId, btnTextId) {
        const checkAll = document.getElementById(checkAllId);
        const btn = document.getElementById(btnId);
        const btnText = btnTextId ? document.getElementById(btnTextId) : null;

        function update() {
            const count = document.querySelectorAll('.' + itemClass + ':checked').length;
            if (btn) {
                if (count > 0) {
                    btn.classList.remove('hidden');
                    if (btnText) btnText.innerText = `Hapus (${count}) Terpilih`;
                } else {
                    btn.classList.add('hidden');
                }
            }
        }

        if (checkAll) {
            checkAll.addEventListener('change', function() {
                document.querySelectorAll('.' + itemClass).forEach(cb => cb.checked = checkAll.checked);
                update();
            });
        }

        document.querySelectorAll('.' + itemClass).forEach(cb => {
            cb.addEventListener('change', update);
        });
    }

    setupBulk('checkAllMateri', 'materi-cb', 'btnBulkMateri', 'btnBulkMateriText');
    setupBulk('checkAllKampanye', 'kampanye-cb', 'btnBulkKampanye', 'btnBulkKampanyeText');
    setupBulk('checkAllSop', 'sop-cb', 'btnBulkSop', 'btnBulkSopText');
});

window.submitBulkMateri = function() {
    const checked = document.querySelectorAll('.materi-cb:checked');
    if (checked.length === 0) return;
    if (confirm(`Apakah Anda yakin ingin menghapus ${checked.length} materi promo terpilih?`)) {
        const form = document.getElementById('formBulkMateri');
        const container = document.getElementById('bulkMateriContainer');
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

window.submitBulkKampanye = function() {
    const checked = document.querySelectorAll('.kampanye-cb:checked');
    if (checked.length === 0) return;
    if (confirm(`Apakah Anda yakin ingin menghapus ${checked.length} kampanye promo terpilih?`)) {
        const form = document.getElementById('formBulkKampanye');
        const container = document.getElementById('bulkKampanyeContainer');
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

window.submitBulkSop = function() {
    const checked = document.querySelectorAll('.sop-cb:checked');
    if (checked.length === 0) return;
    if (confirm(`Apakah Anda yakin ingin menghapus ${checked.length} dokumen SOP terpilih?`)) {
        const form = document.getElementById('formBulkSop');
        const container = document.getElementById('bulkSopContainer');
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

window.editSop = function(s) {
    document.getElementById('formEditSop').action = '/tvri/promo/sop/' + s.id;
    document.getElementById('editSopKode').value = s.kode || '';
    document.getElementById('editSopJudul').value = s.judul || '';
    document.getElementById('editSopKategori').value = s.kategori || '';
    document.getElementById('editSopVersi').value = s.versi || 'v1.0';
    document.getElementById('editSopTanggal').value = s.tanggal_efektif ? s.tanggal_efektif.substring(0, 10) : '';
    document.getElementById('editSopStatus').value = s.status || 'Aktif';
    document.getElementById('editSopUrutan').value = s.urutan || 1;
    document.getElementById('editSopKeterangan').value = s.keterangan || '';
    document.getElementById('modalEditSop').classList.remove('hidden');
};

window.closeModal = function(id) {
    const el = document.getElementById(id);
    if (el) el.classList.add('hidden');
};
</script>
@endsection
