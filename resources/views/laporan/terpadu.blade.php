@extends('layouts.app')

@section('title', 'Laporan Terpadu - TVRI Maluku')

@section('content')
<div class="space-y-6">

    <!-- Screen-Only Actions & Filters -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 print:hidden">
        <div>
            <div class="flex items-center space-x-2 text-xs text-slate-500">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 font-medium">Dashboard</a>
                <span>/</span>
                <span class="text-slate-800 font-bold">Laporan Terpadu</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-[#00264A] tracking-tight mt-1">
                Laporan Terpadu TVRI Maluku
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Konsolidasi eksekutif lintas sektor Pendidikan, Kebudayaan, Pelayanan Administrasi, dan Anggaran Perencanaan.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Filter Tahun -->
            <form action="{{ route('laporan.terpadu') }}" method="GET" class="flex items-center space-x-2">
                <select name="tahun" onchange="this.form.submit()" class="py-2 px-3 text-xs rounded-xl border border-slate-200 bg-white font-semibold text-slate-700 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="semua" {{ $tahun == 'semua' ? 'selected' : '' }}>Semua Tahun</option>
                    @for($y = date('Y') + 1; $y >= 2024; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endfor
                </select>
            </form>

            <!-- Export CSV -->
            <a href="{{ route('laporan.terpadu.export-csv', ['tahun' => $tahun]) }}" 
               class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition-all duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Export CSV</span>
            </a>

            <!-- Print / Cetak -->
            <button onclick="window.print()" 
                    class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl bg-[#00264A] hover:bg-[#003B70] text-white text-xs font-bold shadow-md transition-all duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak Laporan</span>
            </button>
        </div>
    </div>

    <!-- Official Printable Report Container -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-8 print:shadow-none print:border-none print:p-0">

        <!-- Official Header (KOP SURAT) -->
        <div class="border-b-2 border-black pb-4 text-center">
            <div class="flex items-center justify-center space-x-4 mb-2">
                <img src="{{ asset('images/logo_tvri.png') }}" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Coat_of_arms_of_Maluku.svg/1200px-Coat_of_arms_of_Maluku.svg.png'" class="h-16 w-auto object-contain" alt="Logo TVRI">
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 leading-tight">Pemerintah Provinsi Maluku</h3>
                    <h2 class="text-base sm:text-lg font-black uppercase text-[#00264A] leading-tight">TVRI Maluku</h2>
                    <p class="text-[11px] text-slate-600 leading-tight mt-0.5">
                        Jalan Jenderal Achmad Yani No. 1, Batu Meja, Kec. Sirimau, Kota Ambon, Maluku 97124
                    </p>
                </div>
            </div>
            <div class="border-t border-black pt-2 mt-2">
                <h4 class="text-sm font-bold uppercase tracking-wide text-slate-900">
                    Laporan Kinerja, Pelayanan & Rekapitulasi Terpadu
                </h4>
                <p class="text-[11px] text-slate-600 font-medium">
                    Tahun Anggaran: <span class="font-bold text-slate-900">{{ $tahun == 'semua' ? 'Semua Periode' : $tahun }}</span> | Periode Data: {{ date('d F Y') }}
                </p>
            </div>
        </div>

        <!-- 1. Executive Summary Metric Grid -->
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#00264A] mb-3 flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-600 inline-block"></span>
                I. Ringkasan Eksekutif Indikator Utama
            </h3>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Sekolah & Siswa -->
                <div class="p-4 rounded-xl bg-blue-50/60 border border-blue-100">
                    <span class="text-[10px] uppercase font-bold text-blue-800 tracking-wider">Satuan Pendidikan</span>
                    <div class="text-xl font-black text-[#00264A] mt-1">{{ number_format($statPendidikan['total_sekolah']) }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">
                        SMA: {{ $statPendidikan['total_sma'] }} | SMK: {{ $statPendidikan['total_smk'] }} | SLB: {{ $statPendidikan['total_slb'] }}
                    </div>
                </div>

                <!-- Peserta Didik & Guru -->
                <div class="p-4 rounded-xl bg-purple-50/60 border border-purple-100">
                    <span class="text-[10px] uppercase font-bold text-purple-800 tracking-wider">Peserta Didik & Guru</span>
                    <div class="text-xl font-black text-purple-950 mt-1">{{ number_format($statPendidikan['total_siswa']) }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">
                        Total Guru Terdata: {{ number_format($statPendidikan['total_guru']) }}
                    </div>
                </div>

                <!-- Total Layanan Terlayani -->
                <div class="p-4 rounded-xl bg-emerald-50/60 border border-emerald-100">
                    <span class="text-[10px] uppercase font-bold text-emerald-800 tracking-wider">Layanan Administrasi</span>
                    <div class="text-xl font-black text-emerald-950 mt-1">{{ number_format($totalLayanan) }}</div>
                    <div class="text-[10px] text-slate-500 mt-1">
                        SMA, SMK, GTK, SLB, Umum & Sekretariat
                    </div>
                </div>

                <!-- Pagu & Realisasi Anggaran -->
                <div class="p-4 rounded-xl bg-amber-50/60 border border-amber-100">
                    <span class="text-[10px] uppercase font-bold text-amber-800 tracking-wider">Realisasi Anggaran</span>
                    <div class="text-xl font-black text-amber-950 mt-1">{{ $persenRealisasi }}%</div>
                    <div class="text-[10px] text-slate-500 mt-1 truncate">
                        Rp {{ number_format($totalRealisasi, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Rincian Pelayanan Administrasi & Kearsipan Digital -->
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#00264A] mb-3 flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 inline-block"></span>
                II. Rekapitulasi Pelayanan Lintas Bidang & Kearsipan
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Layanan Administrasi Sub-Sektor -->
                <div class="rounded-xl border border-slate-200 overflow-hidden">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-100 text-slate-700 uppercase text-[10px] font-bold">
                            <tr>
                                <th class="py-2.5 px-4">Bidang / Unit Layanan</th>
                                <th class="py-2.5 px-4 text-center">Fungsi / Standar</th>
                                <th class="py-2.5 px-4 text-right">Volume Berkas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="py-2 px-4 font-semibold text-slate-800">Bidang Pembinaan SMA</td>
                                <td class="py-2 px-4 text-center text-slate-500">11 Layanan & Dokumen</td>
                                <td class="py-2 px-4 text-right font-bold text-blue-700">{{ number_format($statLayanan['sma']) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 font-semibold text-slate-800">Bidang Pembinaan SMK</td>
                                <td class="py-2 px-4 text-center text-slate-500">7 Layanan Vokasi</td>
                                <td class="py-2 px-4 text-right font-bold text-cyan-700">{{ number_format($statLayanan['smk']) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 font-semibold text-slate-800">Katalog Layanan GTK</td>
                                <td class="py-2 px-4 text-center text-slate-500">Sertifikasi & Tunjangan</td>
                                <td class="py-2 px-4 text-right font-bold text-amber-700">{{ number_format($statLayanan['gtk']) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 font-semibold text-slate-800">Pendidikan Khusus (SLB)</td>
                                <td class="py-2 px-4 text-center text-slate-500">4 Layanan PKLK</td>
                                <td class="py-2 px-4 text-right font-bold text-indigo-700">{{ number_format($statLayanan['slb']) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 font-semibold text-slate-800">Subbag Kepegawaian & Umum</td>
                                <td class="py-2 px-4 text-center text-slate-500">10 Layanan Naskah</td>
                                <td class="py-2 px-4 text-right font-bold text-emerald-700">{{ number_format($statLayanan['kepegawaian']) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 font-semibold text-slate-800">Sekretariat Dinas</td>
                                <td class="py-2 px-4 text-center text-slate-500">8 Fungsi Koordinasi</td>
                                <td class="py-2 px-4 text-right font-bold text-purple-700">{{ number_format($statLayanan['sekretariat']) }}</td>
                            </tr>
                            <tr class="bg-slate-50 font-black">
                                <td class="py-2.5 px-4 text-slate-900" colspan="2">TOTAL VOLUME PELAYANAN</td>
                                <td class="py-2.5 px-4 text-right text-[#00264A] text-sm">{{ number_format($totalLayanan) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Kebudayaan & Kearsipan Digital -->
                <div class="rounded-xl border border-slate-200 overflow-hidden">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-100 text-slate-700 uppercase text-[10px] font-bold">
                            <tr>
                                <th class="py-2.5 px-4">Sektor Kebudayaan & DMS</th>
                                <th class="py-2.5 px-4 text-center">Tipe Entitas</th>
                                <th class="py-2.5 px-4 text-right">Jumlah Data</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="py-2 px-4 font-semibold text-slate-800">Cagar Budaya & Warisan</td>
                                <td class="py-2 px-4 text-center text-slate-500">Objek Budaya Maluku</td>
                                <td class="py-2 px-4 text-right font-bold text-amber-700">{{ number_format($statKebudayaan['total_cagar_budaya']) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 font-semibold text-slate-800">UPTD Balai Tekkomdik (BTKP)</td>
                                <td class="py-2 px-4 text-center text-slate-500">Program & Aset</td>
                                <td class="py-2 px-4 text-right font-bold text-blue-700">{{ number_format($statKebudayaan['btkp']) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 font-semibold text-slate-800">UPTD Museum Siwalima</td>
                                <td class="py-2 px-4 text-center text-slate-500">Koleksi & Kegiatan</td>
                                <td class="py-2 px-4 text-right font-bold text-purple-700">{{ number_format($statKebudayaan['museum']) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 font-semibold text-slate-800">UPTD Taman Budaya</td>
                                <td class="py-2 px-4 text-center text-slate-500">Pentas & Sanggar</td>
                                <td class="py-2 px-4 text-right font-bold text-rose-700">{{ number_format($statKebudayaan['taman_budaya']) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 font-semibold text-slate-800">Document Management System (DMS)</td>
                                <td class="py-2 px-4 text-center text-slate-500">Dokumen Digital Terverifikasi</td>
                                <td class="py-2 px-4 text-right font-bold text-emerald-700">{{ number_format($statDokumen['dms_documents']) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 font-semibold text-slate-800">Folder & Kategori Arsip</td>
                                <td class="py-2 px-4 text-center text-slate-500">Struktur Direktori DMS</td>
                                <td class="py-2 px-4 text-right font-bold text-slate-700">{{ number_format($statDokumen['dms_folders']) }}</td>
                            </tr>
                            <tr class="bg-slate-50 font-black">
                                <td class="py-2.5 px-4 text-slate-900" colspan="2">TOTAL ARSIP DIGITAL TERSIMPAN</td>
                                <td class="py-2.5 px-4 text-right text-[#00264A] text-sm">{{ number_format($statDokumen['arsip_digital'] + $statDokumen['dms_documents']) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 3. Realisasi Anggaran & Dokumen Perencanaan (SIPD) -->
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#00264A] mb-3 flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-purple-600 inline-block"></span>
                III. Evaluasi Dokumen Perencanaan & Alokasi Anggaran
            </h3>

            <!-- Ringkasan Dokumen Perencanaan Box Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3 mb-4">
                @foreach($dokumenPerencanaanByType as $k => $c)
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-center">
                        <span class="text-[10px] uppercase font-bold text-slate-500">{{ $k }}</span>
                        <div class="text-base font-black text-[#00264A] mt-0.5">{{ $c }}</div>
                        <span class="text-[9px] text-slate-400">Dokumen</span>
                    </div>
                @endforeach
            </div>

            <!-- Tabel Program Terkini -->
            <div class="rounded-xl border border-slate-200 overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-slate-100 text-slate-700 uppercase text-[10px] font-bold">
                        <tr>
                            <th class="py-2.5 px-3">No</th>
                            <th class="py-2.5 px-3">Nomor Registrasi</th>
                            <th class="py-2.5 px-3">Jenis</th>
                            <th class="py-2.5 px-3">Unit / Bidang</th>
                            <th class="py-2.5 px-3">Nama Program / Kegiatan</th>
                            <th class="py-2.5 px-3 text-right">Pagu (Rp)</th>
                            <th class="py-2.5 px-3 text-right">Realisasi (Rp)</th>
                            <th class="py-2.5 px-3 text-center">Capaian</th>
                            <th class="py-2.5 px-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($programList as $idx => $p)
                            <tr class="hover:bg-slate-50">
                                <td class="py-2 px-3 text-slate-500">{{ $idx + 1 }}</td>
                                <td class="py-2 px-3 font-mono font-bold text-slate-800">{{ $p->nomor_registrasi }}</td>
                                <td class="py-2 px-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                        {{ $p->jenis_dokumen }}
                                    </span>
                                </td>
                                <td class="py-2 px-3 font-medium text-slate-700">{{ $p->bidang_penanggung_jawab }}</td>
                                <td class="py-2 px-3 font-semibold text-slate-900 max-w-xs truncate">{{ $p->nama_program_kegiatan }}</td>
                                <td class="py-2 px-3 text-right font-mono font-bold text-slate-800">{{ $p->formatted_pagu }}</td>
                                <td class="py-2 px-3 text-right font-mono font-bold text-emerald-700">{{ $p->formatted_realisasi }}</td>
                                <td class="py-2 px-3 text-center font-bold {{ $p->persentase_realisasi >= 80 ? 'text-emerald-700' : 'text-amber-700' }}">
                                    {{ $p->persentase_realisasi }}%
                                </td>
                                <td class="py-2 px-3 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $p->status_badge_class }}">
                                        {{ $p->status_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-4 text-center text-slate-400 font-medium">Belum ada data dokumen program perencanaan.</td>
                            </tr>
                        @endforelse
                        <tr class="bg-slate-50 font-black">
                            <td colspan="5" class="py-2.5 px-3 text-slate-900 uppercase">TOTAL KESELURUHAN ANGGARAN</td>
                            <td class="py-2.5 px-3 text-right font-mono text-slate-900">Rp {{ number_format($totalPagu, 0, ',', '.') }}</td>
                            <td class="py-2.5 px-3 text-right font-mono text-emerald-700">Rp {{ number_format($totalRealisasi, 0, ',', '.') }}</td>
                            <td class="py-2.5 px-3 text-center font-mono text-[#00264A]">{{ $persenRealisasi }}%</td>
                            <td class="py-2.5 px-3 text-center text-[10px] text-slate-500">Sisa: Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 4. Official Signature Area -->
        <div class="pt-6 border-t border-slate-200 grid grid-cols-2 gap-8 text-xs text-center">
            <div>
                <p class="text-slate-600">Mengetahui,</p>
                <p class="font-bold text-slate-900 uppercase">Sekretaris TVRI Maluku</p>
                <p class="font-bold text-slate-900 uppercase">Provinsi Maluku</p>
                <div class="h-16"></div>
                <p class="font-bold text-slate-900 underline uppercase">( .................................................... )</p>
                <p class="text-[10px] text-slate-500">NIP. ....................................................</p>
            </div>
            <div>
                <p class="text-slate-600">Ambon, {{ date('d F Y') }}</p>
                <p class="font-bold text-slate-900 uppercase">Kepala TVRI Maluku</p>
                <p class="font-bold text-slate-900 uppercase">Provinsi Maluku</p>
                <div class="h-16"></div>
                <p class="font-bold text-slate-900 underline uppercase">( .................................................... )</p>
                <p class="text-[10px] text-slate-500">NIP. ....................................................</p>
            </div>
        </div>

    </div>

</div>
@endsection
