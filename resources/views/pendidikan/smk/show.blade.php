@extends('layouts.app')

@section('title', 'Detail Permohonan SMK: ' . $layanan->nomor_registrasi . ' - TVRI Maluku')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Breadcrumb & Top Bar -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-xs text-slate-500">
            <a href="{{ route('pendidikan.smk.layanan.index') }}" class="hover:text-[#003B70] font-medium">Bidang Pembinaan SMK</a>
            <span>/</span>
            <span class="text-slate-800 font-bold">Detail: {{ $layanan->nomor_registrasi }}</span>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('pendidikan.smk.layanan.index') }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Kembali</span>
            </a>
            <a href="{{ route('pendidikan.smk.layanan.edit', $layanan->id) }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-bold rounded-xl transition-all shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <span>Edit Permohonan</span>
            </a>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold">&times;</button>
        </div>
    @endif

    <!-- Main Header Card -->
    @php
        $meta = $layanan->layanan_meta;
        $statusOptions = \App\Models\SimandatSmkLayanan::STATUS_LIST;
        $statusBadge = $statusOptions[$layanan->status_layanan] ?? 'bg-gray-100 text-gray-800 border-gray-300';
    @endphp

    <div class="bg-gradient-to-r from-[#00264A] via-[#003B70] to-[#005B9F] rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 w-96 h-96 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                <div class="flex items-center space-x-2.5">
                    <span class="px-3 py-1 bg-white/10 text-white border border-white/20 rounded-full text-xs font-mono font-bold">
                        {{ $layanan->nomor_registrasi }}
                    </span>
                    <span class="px-3 py-1 bg-cyan-400/20 text-cyan-200 border border-cyan-400/40 rounded-full text-xs font-bold">
                        {{ $meta['seksi'] ?? 'Bidang SMK' }}
                    </span>
                </div>
                <div>
                    <span class="px-3 py-1 text-xs font-bold rounded-lg border {{ $statusBadge }}">
                        {{ str_replace('_', ' ', $layanan->status_layanan) }}
                    </span>
                </div>
            </div>

            <h1 class="text-2xl lg:text-3xl font-black text-white leading-tight">
                {{ $layanan->jenis_layanan }}
            </h1>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-5 border-t border-white/10 text-xs">
                <div>
                    <p class="text-blue-300 font-medium">Nama Pemohon</p>
                    <p class="text-white font-bold text-sm mt-0.5">{{ $layanan->nama_pemohon }}</p>
                </div>
                <div>
                    <p class="text-blue-300 font-medium">Satuan Pendidikan SMK</p>
                    <p class="text-white font-bold text-sm mt-0.5">{{ $layanan->nama_sekolah_asal }}</p>
                </div>
                <div>
                    <p class="text-blue-300 font-medium">Konsentrasi Keahlian</p>
                    <p class="text-white font-bold text-sm mt-0.5">{{ $layanan->konsentrasi_keahlian ?: 'Umum Kejuruan' }}</p>
                </div>
                <div>
                    <p class="text-blue-300 font-medium">Tanggal Pengajuan</p>
                    <p class="text-white font-bold text-sm mt-0.5">{{ $layanan->tanggal_pengajuan ? $layanan->tanggal_pengajuan->format('d F Y') : '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 2 Column Details Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- 1. Informasi Layanan & SOP -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-[#003B70]"></span>
                    <span>Deskripsi & Ruang Lingkup Layanan Kejuruan</span>
                </h3>
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                    <div class="flex items-start space-x-2 text-xs">
                        <span class="font-bold text-slate-700 min-w-[120px]">Layanan:</span>
                        <span class="font-semibold text-[#003B70]">{{ $layanan->jenis_layanan }}</span>
                    </div>
                    <div class="flex items-start space-x-2 text-xs">
                        <span class="font-bold text-slate-700 min-w-[120px]">Seksi Pembina:</span>
                        <span class="text-slate-800 font-medium">{{ $meta['seksi'] ?? 'Bidang Pembinaan SMK' }}</span>
                    </div>
                    <div class="flex items-start space-x-2 text-xs pt-1 border-t border-slate-200/60">
                        <span class="font-bold text-slate-500 min-w-[120px]">Uraian SOP:</span>
                        <span class="text-slate-600 italic text-[11px]">{{ $meta['deskripsi'] ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- 2. Perihal & Uraian Rincian Permohonan -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-[#003B70]"></span>
                    <span>Uraian & Perihal Permohonan</span>
                </h3>
                <div class="prose prose-sm text-xs text-slate-700 leading-relaxed bg-slate-50/50 p-4 rounded-xl border border-slate-100 whitespace-pre-line">
                    {{ $layanan->perihal_uraian }}
                </div>
            </div>

            <!-- 3. Catatan & Disposisi Verifikasi -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-[#003B70]"></span>
                    <span>Catatan Verifikator & Status Disposisi</span>
                </h3>
                <div class="p-4 bg-cyan-50/50 rounded-xl border border-cyan-100 space-y-2 text-xs">
                    <div class="flex items-center space-x-2">
                        <span class="font-bold text-slate-700 min-w-[140px]">Petugas Verifikator:</span>
                        <span class="text-slate-800 font-semibold">{{ $layanan->petugas_verifikator ?: 'Tim Verifikasi Bidang SMK' }}</span>
                    </div>
                    <div class="flex items-start space-x-2 pt-1 border-t border-cyan-100">
                        <span class="font-bold text-slate-700 min-w-[140px]">Catatan Disposisi:</span>
                        <span class="text-slate-700">{{ $layanan->catatan_verifikator ?: 'Belum ada catatan khusus.' }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Status, SK & Files (1 Col) -->
        <div class="space-y-6">

            <!-- Identitas Card -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-4">Informasi Pemohon & Vokasi</h3>

                <div class="space-y-3 text-xs">
                    <div class="py-2 border-b border-slate-100">
                        <span class="text-slate-400 block text-[10px]">Nama Pemohon</span>
                        <span class="font-bold text-slate-800 text-sm">{{ $layanan->nama_pemohon }}</span>
                    </div>
                    <div class="py-2 border-b border-slate-100">
                        <span class="text-slate-400 block text-[10px]">Nomor Identitas (NISN/NIK/NPSN)</span>
                        <span class="font-bold text-slate-800 font-mono">{{ $layanan->nomor_identitas ?: '-' }}</span>
                    </div>
                    <div class="py-2 border-b border-slate-100">
                        <span class="text-slate-400 block text-[10px]">Sekolah Asal SMK</span>
                        <span class="font-bold text-slate-800">{{ $layanan->nama_sekolah_asal }}</span>
                        @if($layanan->npsn_sekolah)
                            <span class="text-[10px] text-slate-400 block font-mono">NPSN: {{ $layanan->npsn_sekolah }}</span>
                        @endif
                    </div>
                    <div class="py-2 border-b border-slate-100">
                        <span class="text-slate-400 block text-[10px]">Konsentrasi Keahlian / Jurusan</span>
                        <span class="font-bold text-cyan-800">{{ $layanan->konsentrasi_keahlian ?: '-' }}</span>
                    </div>
                    <div class="py-2">
                        <span class="text-slate-400 block text-[10px]">Wilayah Kabupaten / Kota</span>
                        <span class="font-bold text-slate-800">{{ $layanan->kabupaten_kota }}</span>
                    </div>
                </div>
            </div>

            <!-- Hasil Terbit Card -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Surat / SK Hasil</h3>

                @if($layanan->nomor_surat_keluar || $layanan->file_hasil_sk)
                    <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-200 space-y-3">
                        <div>
                            <span class="text-[10px] text-emerald-700 font-bold uppercase">Nomor Surat / SK Terbit:</span>
                            <p class="font-mono font-black text-[#00264A] text-xs mt-0.5">{{ $layanan->nomor_surat_keluar ?: 'Sudah Diterbitkan' }}</p>
                        </div>
                        @if($layanan->file_hasil_sk)
                            <a href="{{ route('pendidikan.smk.layanan.download', ['id' => $layanan->id, 'type' => 'hasil_sk']) }}" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow transition flex items-center justify-center space-x-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                <span>Unduh Surat Rekomendasi / SK</span>
                            </a>
                        @endif
                    </div>
                @else
                    <div class="p-4 bg-slate-50 rounded-xl text-center text-xs text-slate-400">
                        Surat / SK Terbit belum diunggah
                    </div>
                @endif
            </div>

            <!-- Berkas Syarat Permohonan -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Berkas Syarat Permohonan</h3>

                @if($layanan->file_permohonan)
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                        <div class="flex items-center space-x-2 text-xs">
                            <svg class="w-4 h-4 text-cyan-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path></svg>
                            <span class="font-bold text-slate-800 truncate" title="{{ $layanan->file_nama_permohonan }}">{{ $layanan->file_nama_permohonan }}</span>
                        </div>
                        <a href="{{ route('pendidikan.smk.layanan.download', ['id' => $layanan->id, 'type' => 'permohonan']) }}" class="w-full py-1.5 bg-cyan-50 hover:bg-cyan-100 text-cyan-800 text-xs font-bold rounded-lg border border-cyan-200 transition flex items-center justify-center space-x-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            <span>Unduh Berkas Syarat</span>
                        </a>
                    </div>
                @else
                    <div class="p-4 bg-slate-50 rounded-xl text-center text-xs text-slate-400">
                        Tidak ada berkas syarat fisik diunggah
                    </div>
                @endif
            </div>

            <!-- Meta & Audit Log -->
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-[11px] text-slate-500 space-y-1">
                <p><span class="font-semibold">Diajukan:</span> {{ $layanan->created_at ? $layanan->created_at->format('d/m/Y H:i') : '-' }}</p>
                <p><span class="font-semibold">Pembaruan:</span> {{ $layanan->updated_at ? $layanan->updated_at->format('d/m/Y H:i') : '-' }}</p>
            </div>

        </div>

    </div>

</div>
@endsection
