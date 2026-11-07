@extends('layouts.app')

@section('title', 'Produk Layanan GTK - TVRI Maluku')

@section('content')
<div class="space-y-6">
    <!-- Header/Portal Top -->
    <div class="bg-gradient-to-r from-[#003B70] to-[#005B9F] rounded-2xl p-6 shadow-lg text-white">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 border-b border-blue-400/30 pb-4 gap-4">
            <div>
                <h1 class="text-xs uppercase tracking-widest text-blue-200 font-bold mb-1">Pemerintah Daerah Provinsi Maluku</h1>
                <h2 class="text-xl font-black">TVRI Maluku</h2>
            </div>
            
            <!-- Horizontal Nav -->
            <div class="flex flex-wrap gap-2 text-xs font-bold">
                <a href="#" class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg transition-colors text-white">Beranda</a>
                <a href="#" class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg transition-colors text-white">Kegiatan Monev</a>
                <a href="#" class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg transition-colors text-white">Infografis Data</a>
                <a href="#" class="px-4 py-2 bg-white text-[#003B70] rounded-lg transition-colors shadow-sm">Produk Layanan GTK</a>
                <a href="#" class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg transition-colors text-white">Satu Data</a>
            </div>
        </div>
        
        <div class="max-w-3xl">
            <h3 class="text-sm font-semibold text-amber-400 mb-2">Produk Layanan Bidang Ketenagaan</h3>
            <h1 class="text-3xl md:text-4xl font-black tracking-tight leading-tight mb-4">
                Unit Kerja Guru dan Tenaga Kependidikan (GTK)
            </h1>
            <p class="text-blue-100 text-sm md:text-base mb-6 leading-relaxed">
                Mewujudkan Tata Kelola Layanan Pendidik yang Transparan, Akuntabel, dan Data-Driven di lingkungan Provinsi Maluku. Akses seluruh produk layanan, standar pelayanan, dan hasil monev di portal tunggal ini.
            </p>
            
            <div class="flex flex-wrap gap-4">
                <div class="bg-blue-900/50 backdrop-blur px-4 py-3 rounded-xl border border-blue-400/20">
                    <div class="text-[10px] text-blue-300 uppercase tracking-wider font-bold">Layanan GTK</div>
                    <div class="text-lg font-black text-white">4 Layanan Standar Terpadu</div>
                </div>
                <div class="bg-blue-900/50 backdrop-blur px-4 py-3 rounded-xl border border-blue-400/20">
                    <div class="text-[10px] text-blue-300 uppercase tracking-wider font-bold">Biaya Layanan</div>
                    <div class="text-lg font-black text-emerald-400">100% GRATIS (Bebas Pungli)</div>
                </div>
                <div class="bg-blue-900/50 backdrop-blur px-4 py-3 rounded-xl border border-blue-400/20">
                    <div class="text-[10px] text-blue-300 uppercase tracking-wider font-bold">Rata-Rata SLA</div>
                    <div class="text-lg font-black text-white">5.7 Hari Kerja Terukur</div>
                </div>
                <div class="bg-blue-900/50 backdrop-blur px-4 py-3 rounded-xl border border-blue-400/20">
                    <div class="text-[10px] text-blue-300 uppercase tracking-wider font-bold">Indeks SKM</div>
                    <div class="text-lg font-black text-amber-400">4.88 / 5.0 (Sangat Baik)</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Katalog Layanan -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <h3 class="text-lg font-black text-[#00264A] mb-1">Standar Pelayanan Publik</h3>
        <p class="text-xs text-slate-500 mb-6">Katalog & Standar Layanan GTK - 4 Layanan Utama Bidang Guru dan Tenaga Kependidikan TVRI Maluku.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-5 border-2 border-slate-100 rounded-xl hover:border-[#005B9F] transition-colors group cursor-pointer">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mb-4 group-hover:bg-[#005B9F] group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                </div>
                <h4 class="font-bold text-slate-800 mb-2 group-hover:text-[#005B9F]">1. Sertifikasi Pendidik</h4>
                <p class="text-xs text-slate-500 leading-relaxed">Pengurusan penetapan peserta sertifikasi, pemberkasan tunjangan profesi guru, dan verifikasi linearitas mata pelajaran.</p>
            </div>
            
            <div class="p-5 border-2 border-slate-100 rounded-xl hover:border-emerald-500 transition-colors group cursor-pointer">
                <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center mb-4 group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                </div>
                <h4 class="font-bold text-slate-800 mb-2 group-hover:text-emerald-600">2. NUPTK</h4>
                <p class="text-xs text-slate-500 leading-relaxed">Verifikasi dan validasi penerbitan Nomor Unik Pendidik dan Tenaga Kependidikan baru bagi guru yang memenuhi syarat Dapodik.</p>
            </div>
            
            <div class="p-5 border-2 border-slate-100 rounded-xl hover:border-amber-500 transition-colors group cursor-pointer">
                <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center mb-4 group-hover:bg-amber-500 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h4 class="font-bold text-slate-800 mb-2 group-hover:text-amber-600">3. Tunjangan Pendidik</h4>
                <p class="text-xs text-slate-500 leading-relaxed">Pengelolaan penyaluran Tambahan Penghasilan (Tamsil), Tunjangan Khusus (Terpencil), dan penanganan masalah rekening.</p>
            </div>
            
            <div class="p-5 border-2 border-slate-100 rounded-xl hover:border-rose-500 transition-colors group cursor-pointer">
                <div class="w-10 h-10 bg-rose-50 text-rose-600 rounded-lg flex items-center justify-center mb-4 group-hover:bg-rose-500 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <h4 class="font-bold text-slate-800 mb-2 group-hover:text-rose-600">4. SIMPKB & PKB</h4>
                <p class="text-xs text-slate-500 leading-relaxed">Pemutakhiran data Sistem Informasi Manajemen Pengembangan Keprofesian Berkelanjutan dan aktivasi akun guru.</p>
            </div>
        </div>
    </div>

    <!-- Monev Cards -->
    <div>
        <h3 class="text-lg font-black text-[#00264A] mb-1">Monitoring & Evaluasi Digital</h3>
        <p class="text-xs text-slate-500 mb-4">Kegiatan Utama Monev GTK - Pemantauan secara berkala pada seluruh satuan pendidikan menengah dan khusus di 11 Kabupaten/Kota se-Provinsi Maluku.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- SMA -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-[#005B9F]"></div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold text-blue-500 bg-blue-50 px-2 py-1 rounded uppercase tracking-wider">Monev Card 1</span>
                </div>
                <h4 class="font-black text-slate-800 text-lg mb-1">Satuan Pendidikan SMA</h4>
                <p class="text-xs text-slate-500 mb-4">Sekolah Menengah Atas Negeri & Swasta</p>
                
                <div class="space-y-3 mb-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Total Sekolah Target</span>
                        <span class="font-bold text-slate-800">284 SMA</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Guru Terverifikasi</span>
                        <span class="font-bold text-slate-800">6,420 Guru</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Capaian Sertifikasi</span>
                        <span class="font-bold text-emerald-600">88.4%</span>
                    </div>
                </div>
                
                <p class="text-xs text-slate-600 bg-slate-50 p-3 rounded-lg leading-relaxed mb-4">
                    <span class="font-bold">Ringkasan:</span> Pengawasan beban kerja mengajar 24 jam linier, pemutakhiran sertifikasi pendidik, dan penyaluran TPG SMA berjalan optimal.
                </p>
                <a href="#" class="text-xs font-bold text-[#005B9F] hover:underline flex items-center space-x-1">
                    <span>Detail Laporan</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <!-- SMK -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-amber-500"></div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded uppercase tracking-wider">Monev Card 2</span>
                </div>
                <h4 class="font-black text-slate-800 text-lg mb-1">Satuan Pendidikan SMK</h4>
                <p class="text-xs text-slate-500 mb-4">Sekolah Menengah Kejuruan Vokasi</p>
                
                <div class="space-y-3 mb-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Total Sekolah Target</span>
                        <span class="font-bold text-slate-800">118 SMK</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Guru Kejuruan/Produktif</span>
                        <span class="font-bold text-slate-800">3,150 Guru</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Keaktifan SIMPKB</span>
                        <span class="font-bold text-emerald-600">92.1%</span>
                    </div>
                </div>
                
                <p class="text-xs text-slate-600 bg-slate-50 p-3 rounded-lg leading-relaxed mb-4">
                    <span class="font-bold">Ringkasan:</span> Pemetaan kompetensi guru kejuruan berbasis industri, penerbitan NUPTK guru produktif baru, serta fasilitasi magang guru SMK.
                </p>
                <a href="#" class="text-xs font-bold text-[#005B9F] hover:underline flex items-center space-x-1">
                    <span>Detail Laporan</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <!-- SLB -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-rose-500"></div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold text-rose-600 bg-rose-50 px-2 py-1 rounded uppercase tracking-wider">Monev Card 3</span>
                </div>
                <h4 class="font-black text-slate-800 text-lg mb-1">Satuan Pendidikan Khusus</h4>
                <p class="text-xs text-slate-500 mb-4">Sinergi Integrasi Data GTK SLB, SMA, SMK</p>
                
                <div class="space-y-3 mb-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Total Lembaga Terdata</span>
                        <span class="font-bold text-slate-800">425 Satuan</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Cakupan Validasi NUPTK</span>
                        <span class="font-bold text-emerald-600">96.8% Valid</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Kelancaran Tunjangan</span>
                        <span class="font-bold text-emerald-600">95.4%</span>
                    </div>
                </div>
                
                <p class="text-xs text-slate-600 bg-slate-50 p-3 rounded-lg leading-relaxed mb-4">
                    <span class="font-bold">Ringkasan:</span> Evaluasi kesetaraan layanan publik GTK sekolah negeri dan swasta, sinkronisasi Dapodik, dan mitigasi kendala info GTK.
                </p>
                <a href="#" class="text-xs font-bold text-[#005B9F] hover:underline flex items-center space-x-1">
                    <span>Detail Laporan</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Visualisasi Analitis & Catatan -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Visualisasi -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <h3 class="text-lg font-black text-[#00264A] mb-1">Visualisasi Analitis</h3>
            <p class="text-xs text-slate-500 mb-6">Infografis Laporan & Tren Layanan - Data real-time evaluasi indeks kepuasan dan volume pemanfaatan produk layanan GTK.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Trend Rating -->
                <div>
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-4 flex items-center space-x-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Tren Rating Produk Layanan</span>
                    </h4>
                    <div class="flex items-end justify-between mb-2">
                        <div>
                            <span class="text-3xl font-black text-[#00264A]">4.88</span>
                            <span class="text-sm text-slate-500">/ 5.0</span>
                        </div>
                        <span class="text-emerald-500 font-bold text-sm flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            +4.2%
                        </span>
                    </div>
                    <p class="text-[10px] text-slate-400 mb-4">Evaluasi Skala Kepuasan Publik 2025 - 2026</p>
                    <div class="space-y-2">
                        <div class="flex items-center text-xs">
                            <span class="w-12 text-slate-500">5 Bintang</span>
                            <div class="flex-1 mx-2 h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-amber-400 rounded-full" style="width: 85%"></div>
                            </div>
                            <span class="w-8 text-right font-bold">85%</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <span class="w-12 text-slate-500">4 Bintang</span>
                            <div class="flex-1 mx-2 h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-amber-400 rounded-full" style="width: 12%"></div>
                            </div>
                            <span class="w-8 text-right font-bold">12%</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <span class="w-12 text-slate-500">3 Bintang</span>
                            <div class="flex-1 mx-2 h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-amber-400 rounded-full" style="width: 3%"></div>
                            </div>
                            <span class="w-8 text-right font-bold">3%</span>
                        </div>
                    </div>
                </div>

                <!-- Top Layanan -->
                <div>
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-4 flex items-center space-x-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span>Top Produk Layanan Diproses</span>
                    </h4>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="font-bold text-slate-700">Sertifikasi Pendidik</span>
                                <span class="text-slate-500">4,850 Berkas</span>
                            </div>
                            <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-[#005B9F] rounded-full" style="width: 80%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="font-bold text-slate-700">Penerbitan NUPTK</span>
                                <span class="text-slate-500">1,240 Berkas</span>
                            </div>
                            <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-[#005B9F] rounded-full" style="width: 45%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="font-bold text-slate-700">Pencairan Tunjangan</span>
                                <span class="text-slate-500">8,200 Berkas</span>
                            </div>
                            <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-[#005B9F] rounded-full" style="width: 95%"></div>
                            </div>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-4">Volume Berkas Usulan Layanan GTK (2025/2026)</p>
                </div>
            </div>
        </div>

        <!-- Catatan Hasil -->
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 shadow-sm text-white">
            <h3 class="text-sm font-bold uppercase tracking-wider text-amber-400 mb-6 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Catatan Hasil Pelaporan</span>
            </h3>
            
            <ul class="space-y-4">
                <li class="flex items-start space-x-3">
                    <div class="mt-0.5 text-emerald-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white">Informatif, Terpercaya, Berwibawa:</h4>
                        <p class="text-xs text-slate-400 mt-1">Seluruh indikator kinerja berbasis validasi Dapodik & SIMPKB.</p>
                    </div>
                </li>
                <li class="flex items-start space-x-3">
                    <div class="mt-0.5 text-emerald-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white">Capaian Data-Driven:</h4>
                        <p class="text-xs text-slate-400 mt-1">Penyelesaian SLA rata-rata di bawah 7 hari kerja tanpa biaya (100% Gratis).</p>
                    </div>
                </li>
                <li class="flex items-start space-x-3">
                    <div class="mt-0.5 text-emerald-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white">Penyajian Laporan Pimpinan:</h4>
                        <p class="text-xs text-slate-400 mt-1">Disiapkan secara terstruktur sebagai bahan masukan strategis Kepala Stasiun & Gubernur Maluku.</p>
                    </div>
                </li>
            </ul>
            
            <div class="mt-6 pt-6 border-t border-slate-700">
                <div class="flex items-center justify-between text-xs text-slate-400">
                    <span>Dokumen Terverifikasi</span>
                    <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
