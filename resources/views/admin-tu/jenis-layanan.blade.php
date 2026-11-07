@extends('layouts.app')

@section('title', 'Direktori Standar Pelayanan Publik - TVRI Maluku')

@section('content')
@php
$services = [];
$pageDesc = '{{ $pageDesc }}';
$dirTitle = '{{ $dirTitle }}';
$dirSub = '{{ $dirSub }}';

if (auth()->check() && auth()->user()->username === 'kabid.sma') {
    $idx = 1;
    $pageDesc = 'Layanan Utama Bidang Pembinaan SMA TVRI Maluku.';
    $dirTitle = 'Direktori Produk Layanan Bidang Pembinaan SMA';
    foreach (\App\Models\SimandatSmaLayanan::getJenisLayananOptions() as $title => $data) {
        $services[] = [
            'code' => $data['prefix'] ?? ('SMA-' . str_pad($idx, 2, '0', STR_PAD_LEFT)),
            'title' => $title,
            'category' => $data['seksi'] ?? 'Pembinaan SMA',
            'sla' => '1 - 3 hari kerja',
            'staff' => 'Petugas Bidang'
        ];
        $idx++;
    }
} elseif (auth()->check() && auth()->user()->username === 'kabid.diksus') {
    $pageDesc = '4 Layanan Utama Bidang Pembinaan Pendidikan Khusus TVRI Maluku.';
    $dirTitle = 'Direktori Produk Layanan Bidang Pembinaan Pendidikan Khusus (4 Layanan)';
    $dirSub = 'Klik pada kartu layanan untuk menampilkan rincian SOP 14 komponen lengkap';
    $services = [
        [
            'code' => 'SLB-01',
            'title' => 'Rekomendasi Pendirian SLB Negeri dan SLB Swasta',
            'category' => 'Kelembagaan & Perizinan',
            'sla' => 'Paling lama 15 (lima belas) hari kerja sejak berkas/kajian dinyatakan lengkap',
            'staff' => '1 Orang',
            'biaya' => 'Tidak dikenakan biaya (GRATIS)'
        ],
        [
            'code' => 'SLB-02',
            'title' => 'Rekomendasi Mutasi (Perpindahan) Siswa SLB',
            'category' => 'Kesiswaan & Mutasi',
            'sla' => 'Paling lama 5 (lima) hari kerja sejak berkas dinyatakan lengkap',
            'staff' => '1 Orang',
            'biaya' => 'Tidak dikenakan biaya (GRATIS)'
        ],
        [
            'code' => 'SLB-03',
            'title' => 'Bantuan Operasional Satuan Pendidikan (BOSP) SLB',
            'category' => 'Pemerataan & Pendanaan',
            'sla' => 'Paling lama 10 (sepuluh) hari kerja per tahap penyaluran sebelum diproses lebih lanjut oleh Kementerian',
            'staff' => '1 Orang',
            'biaya' => 'Tidak dikenakan biaya (GRATIS)'
        ],
        [
            'code' => 'SLB-04',
            'title' => 'Legalisir Ijazah/STTB dan Surat Keterangan Lulus (SKL) SLB',
            'category' => 'Dokumen & Legalitas',
            'sla' => 'Paling lama 1 (satu) hari kerja untuk legalisir, dan paling lama 7 (tujuh) hari kerja untuk surat keterangan pengganti, sejak berkas dinyatakan lengkap',
            'staff' => '1 Orang',
            'biaya' => 'Tidak dikenakan biaya (GRATIS)'
        ]
    ];
} else {
    $services = [
        [
            'code' => 'KEP-01',
            'title' => 'Surat / Naskah Dinas',
            'category' => 'Administrasi Persuratan',
            'sla' => 'Surat Masuk = 1 hari kerja, Surat Keluar = 2 hari kerja',
            'staff' => '15 Orang'
        ],
        [
            'code' => 'KEP-02',
            'title' => 'Layanan Kenaikan Pangkat',
            'category' => 'Karier & Pangkat ASN',
            'sla' => 'Maksimal 10 (sepuluh) hari kerja',
            'staff' => '11 Orang'
        ],
        [
            'code' => 'KEP-03',
            'title' => 'Kenaikan Gaji Berkala (KGB)',
            'category' => 'Hak & Kesejahteraan PNS',
            'sla' => 'Paling lama 5 (lima) hari kerja sebelum TMT KGB',
            'staff' => '11 Orang'
        ],
        [
            'code' => 'KEP-04',
            'title' => 'Layanan Izin Praktik Kerja Industri (PKL Siswa/Mahasiswa)',
            'category' => 'Kemitraan Pendidikan',
            'sla' => 'Maksimal 3 (tiga) hari kerja',
            'staff' => '1 Orang'
        ],
        [
            'code' => 'KEP-05',
            'title' => 'Layanan Izin Cuti PNS',
            'category' => 'Hak & Kesejahteraan PNS',
            'sla' => 'Maksimal 3 (tiga) hari kerja (Cuti Sakit mendesak diproses lebih cepat)',
            'staff' => '11 Orang'
        ],
        [
            'code' => 'KEP-06',
            'title' => 'Layanan Izin Perceraian PNS',
            'category' => 'Status Hukum & Kepegawaian',
            'sla' => 'Maksimal 10 (sepuluh) hari kerja',
            'staff' => '1 Orang'
        ],
        [
            'code' => 'KEP-07',
            'title' => 'Penerbitan Izin Belajar dan Tugas Belajar',
            'category' => 'Pengembangan Kompetensi',
            'sla' => 'Maksimal 7 (tujuh) hari kerja',
            'staff' => '11 Orang'
        ],
        [
            'code' => 'KEP-08',
            'title' => 'Usul Pensiun PNS',
            'category' => 'Pensiun & BUP',
            'sla' => 'Maksimal 10 (sepuluh) hari kerja',
            'staff' => '11 Orang'
        ],
        [
            'code' => 'KEP-09',
            'title' => 'Layanan Izin Penelitian',
            'category' => 'Riset & Akademik',
            'sla' => 'Maksimal 10 (sepuluh) hari kerja',
            'staff' => '11 Orang'
        ],
        [
            'code' => 'KEP-10',
            'title' => 'Usul Penyesuaian Gelar Akademik',
            'category' => 'Pengembangan Kualifikasi',
            'sla' => 'Maksimal 10 (sepuluh) hari kerja',
            'staff' => '11 Orang'
        ]
    ];
}

$categories = ['Semua Layanan'];
if (auth()->check() && auth()->user()->username === 'kabid.sma') {
    foreach (\App\Models\SimandatSmaLayanan::getJenisLayananOptions() as $data) {
        if (isset($data['seksi']) && !in_array($data['seksi'], $categories)) {
            $categories[] = $data['seksi'];
        }
    }
} elseif (auth()->check() && auth()->user()->username === 'kabid.diksus') {
    $categories = [
        'Semua Layanan',
        'Kelembagaan & Perizinan',
        'Kesiswaan & Mutasi',
        'Pemerataan & Pendanaan',
        'Dokumen & Legalitas'
    ];
} else {
    $categories = [
        'Semua (10)',
        'Administrasi Persuratan',
        'Karier & Pangkat ASN',
        'Hak & Kesejahteraan PNS',
        'Kemitraan Pendidikan',
        'Status Hukum & Kepegawaian',
        'Pengembangan Kompetensi',
        'Pensiun & BUP',
        'Riset & Akademik',
        'Pengembangan Kualifikasi'
    ];
}
@endphp

<div class="space-y-6" x-data="{ search: '', activeCategory: '{{ $categories[0] ?? 'Semua Layanan' }}', openModal: false }">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-[#00264A] via-[#003B70] to-[#005B9F] rounded-2xl p-6 text-white shadow-xl relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between">
        <div class="relative z-10">
            <h2 class="text-xs font-black uppercase tracking-widest text-blue-200 mb-2">Standar Pelayanan Publik</h2>
            <h1 class="text-2xl md:text-3xl font-black mb-2">Katalog & Standar Layanan</h1>
            <p class="text-blue-100 text-sm max-w-2xl font-medium">{{ $pageDesc }}</p>
        </div>
        <div class="relative z-10 mt-4 md:mt-0 flex gap-3">
            @if(auth()->check() && auth()->user()->username === 'kabid.sma')
            <button @click="openModal = true" class="bg-white/20 hover:bg-white/30 text-white border border-white/30 transition-all font-bold text-xs py-2.5 px-4 rounded-xl flex items-center gap-2 backdrop-blur-sm shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Tambah Jenis Layanan Baru</span>
            </button>
            @endif
        </div>
        <div class="absolute right-0 top-0 w-96 h-96 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -right-8 -top-8 opacity-10 pointer-events-none transform rotate-12">
            <div class="w-48 h-48 bg-white rounded-full flex items-center justify-center">
                <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Title & Context -->
    <div class="px-2">
        <h2 class="text-xs font-bold text-[#00264A] uppercase tracking-wider">Profil Satu Data TVRI Maluku</h2>
        <h3 class="text-lg font-black text-slate-800 mt-1">{{ $dirTitle }}</h3>
        <p class="text-[13px] text-slate-500 mt-1">{{ $dirSub }}</p>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col lg:flex-row gap-4 justify-between">
        <!-- Scrollable Tabs -->
        <div class="flex-grow overflow-x-auto pb-2 lg:pb-0 hide-scrollbar">
            <div class="flex items-center gap-2 w-max">
                @foreach($categories as $cat)
                    <button @click="activeCategory = '{{ $cat }}'"
                            :class="activeCategory === '{{ $cat }}' ? 'bg-[#003B70] text-white shadow-md border-[#003B70]' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-blue-50 hover:text-blue-700'"
                            class="px-4 py-2 rounded-xl text-xs font-bold border transition-all duration-200 whitespace-nowrap">
                        {{ $cat }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Search Input -->
        <div class="shrink-0 w-full lg:w-72 relative">
            <input type="text" x-model="search" placeholder="Cari jenis layanan, kode..." 
                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#003B70] focus:border-transparent transition-all">
            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($services as $srv)
            <div x-show="(activeCategory === 'Semua (10)' || activeCategory === '{{ $srv['category'] }}') && ('{{ strtolower($srv['title'] . ' ' . $srv['code']) }}'.includes(search.toLowerCase()))" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-lg hover:border-blue-300 transition-all duration-300 flex flex-col overflow-hidden group">
                
                <!-- Card Header -->
                <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex items-start justify-between">
                    <div>
                        <span class="inline-block px-2.5 py-1 bg-[#003B70] text-white text-[10px] font-black tracking-widest rounded-lg shadow-sm">
                            {{ $srv['code'] }}
                        </span>
                    </div>
                    <span class="inline-flex items-center space-x-1 text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200 text-[10px] font-bold">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ $srv["biaya"] ?? "Gratis (Rp 0)" }}</span>
                    </span>
                </div>

                <!-- Card Body -->
                <div class="p-5 flex-grow flex flex-col">
                    <h4 class="text-sm font-bold text-slate-800 leading-tight group-hover:text-[#005B9F] transition-colors mb-2">
                        {{ $srv['title'] }}
                    </h4>
                    
                    <div class="mb-4">
                        <span class="inline-block px-2.5 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-semibold rounded-md border border-blue-100">
                            {{ $srv['category'] }}
                        </span>
                    </div>

                    <div class="space-y-3 mt-auto pt-4 border-t border-slate-100">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Jangka Waktu (SLA):</p>
                            <p class="text-xs font-medium text-slate-700 leading-snug">{{ $srv['sla'] }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Jumlah Pelaksana:</p>
                            <p class="text-xs font-medium text-slate-700 leading-snug flex items-center space-x-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <span>{{ $srv['staff'] }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card Footer Button -->
                <div class="p-4 bg-slate-50 border-t border-slate-100">
                    <button class="w-full flex items-center justify-center space-x-2 py-2.5 bg-white hover:bg-slate-100 border border-slate-200 rounded-xl text-xs font-bold text-[#003B70] transition-colors shadow-sm group-hover:border-blue-300">
                        <span>Lihat SOP 14 Komponen</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>

            </div>
        @endforeach
    </div>
    
    <!-- Empty State (Placeholder) -->
    <div x-show="false" id="emptyStateContainer"
         class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-sm">
        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <p class="text-sm font-bold text-slate-700">Layanan tidak ditemukan</p>
        <p class="text-xs text-slate-500 mt-1">Coba gunakan kata kunci pencarian yang lain.</p>
    </div>

    <!-- Modal Tambah Jenis Layanan -->
    <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="openModal" x-transition.opacity class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-slate-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="openModal" 
                 x-transition.scale.origin.bottom
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('admin-tu.jenis-layanan.sma.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-[#005B9F]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-black text-slate-900" id="modal-title">Tambah Jenis Layanan SMA Baru</h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Layanan *</label>
                                        <input type="text" name="nama_layanan" required placeholder="Contoh: Rekomendasi Siswa Pindah" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Seksi Penanggung Jawab *</label>
                                        <input type="text" name="seksi" required placeholder="Contoh: Seksi Kelembagaan & Sarpras" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Kode / Prefix</label>
                                            <input type="text" name="prefix" placeholder="Contoh: SMA-PINDAH" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-2xl">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-[#005B9F] text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Simpan Layanan Baru
                        </button>
                        <button type="button" @click="openModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Simple script to handle empty state -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.effect(() => {
            // Using Alpine's reactivity is fine, but we can just use CSS for empty state if needed.
            // Leaving it gracefully empty is better than a complex query in x-show for now.
        });
    });
</script>

<style>
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endsection
