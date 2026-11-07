@extends('layouts.app')

@section('title', 'Detail Permohonan Layanan SLB - ' . $layanan->nomor_registrasi)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Breadcrumb & Back -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-xs text-slate-500">
            <a href="{{ route('pendidikan.slb.layanan.index') }}" class="hover:text-indigo-600 font-medium">Layanan SLB</a>
            <span>/</span>
            <span class="text-slate-800 font-bold font-mono">{{ $layanan->nomor_registrasi }}</span>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('pendidikan.slb.layanan.index') }}" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Kembali</span>
            </a>
            @if(auth()->check() && (auth()->user()->hasRole('KEPALA_DINAS') || str_contains(strtolower(auth()->user()->name), 'kepala dinas')))
                <a href="{{ route('pendidikan.slb.layanan.proses', $layanan->id) }}" class="inline-flex items-center space-x-1 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-lg shadow transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Proses Verifikasi</span>
                </a>
            @else
                <a href="{{ route('pendidikan.slb.layanan.edit', $layanan->id) }}" class="inline-flex items-center space-x-1 px-3.5 py-1.5 bg-amber-500 hover:bg-amber-400 text-slate-900 text-xs font-bold rounded-lg shadow transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span>Edit Dokumen</span>
                </a>
            @endif
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

    <!-- Header Card -->
    <div class="bg-gradient-to-r from-[#00264A] via-[#003B70] to-[#005B9F] rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <span class="px-3 py-1 bg-indigo-400/20 text-indigo-200 border border-indigo-400/40 rounded-full text-xs font-bold uppercase">
                        {{ $layanan->jenis_layanan }}
                    </span>
                    <span class="font-mono text-xs text-blue-200">No. Reg: {{ $layanan->nomor_registrasi }}</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-black text-white">
                    {{ $layanan->nama_satuan_pendidikan }}
                </h1>
                <p class="text-sm text-blue-100 max-w-2xl mt-1">
                    Pemohon: <strong>{{ $layanan->nama_pemohon }}</strong> &bull; {{ $layanan->kabupaten_kota }}
                </p>
            </div>

            <!-- Status Badge -->
            <div>
                @php
                    $st = $layanan->status_meta;
                @endphp
                <div class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold border {{ $st['bg'] }} {{ $st['text'] }} {{ $st['border'] }} shadow-sm">
                    <span class="w-2 h-2 rounded-full mr-2 {{ $layanan->status_layanan === 'SELESAI' ? 'bg-emerald-500' : ($layanan->status_layanan === 'DITOLAK' ? 'bg-rose-500' : 'bg-amber-500') }}"></span>
                    {{ $st['label'] }}
                </div>
            </div>
        </div>
    </div>

    <!-- Main Grid Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Details (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Identitas Pemohon & SLB -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
                <h3 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Informasi Satuan Pendidikan SLB & Pemohon
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Nama Pemohon</span>
                        <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $layanan->nama_pemohon }}</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Nomor Identitas (NIK/NIP/NISN)</span>
                        <span class="font-bold font-mono text-slate-800 text-sm mt-0.5 block">{{ $layanan->nomor_identitas ?? '-' }}</span>
                    </div>

                    <div class="sm:col-span-2 p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Satuan Pendidikan (SLB)</span>
                        <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $layanan->nama_satuan_pendidikan }}</span>
                        <span class="text-slate-500 text-xs mt-0.5 block">{{ $layanan->npsn_sekolah ? 'NPSN: ' . $layanan->npsn_sekolah . ' • ' : '' }}{{ $layanan->kabupaten_kota }}</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">No. HP / WhatsApp</span>
                        <span class="font-bold text-slate-800 text-xs mt-0.5 block">{{ $layanan->no_hp_whatsapp ?? '-' }}</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Email</span>
                        <span class="font-bold text-slate-800 text-xs mt-0.5 block truncate">{{ $layanan->email ?? '-' }}</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Tanggal Pengajuan</span>
                        <span class="font-bold text-slate-800 text-xs mt-0.5 block">{{ $layanan->tanggal_pengajuan ? $layanan->tanggal_pengajuan->format('d F Y') : '-' }}</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Nomor Surat / Rekomendasi</span>
                        <span class="font-bold text-slate-800 text-xs mt-0.5 block">{{ $layanan->nomor_surat_rekomendasi ?? 'Belum Diterbitkan' }}</span>
                    </div>
                </div>

                @if($layanan->perihal_uraian)
                    <div class="p-4 rounded-xl bg-indigo-50/50 border border-indigo-100 text-xs">
                        <span class="text-indigo-900 font-bold block mb-1">Perihal / Uraian Permohonan:</span>
                        <p class="text-slate-700 leading-relaxed">{{ $layanan->perihal_uraian }}</p>
                    </div>
                @endif
            </div>

            <!-- Catatan Verifikasi Dinas -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-3">
                <h3 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Catatan Tim Verifikator Pendidikan Khusus
                </h3>

                <div class="p-4 rounded-xl {{ $layanan->catatan_verifikator ? 'bg-slate-50 border border-slate-200' : 'bg-slate-50/50 border border-dashed border-slate-200 text-slate-400' }} text-xs">
                    @if($layanan->petugas_verifikator)
                        <div class="text-[11px] font-bold text-slate-600 mb-1">Petugas: {{ $layanan->petugas_verifikator }}</div>
                    @endif
                    @if($layanan->catatan_verifikator)
                        <p class="text-slate-700 leading-relaxed">{{ $layanan->catatan_verifikator }}</p>
                    @else
                        <p class="italic">Belum ada catatan tambahan dari verifikator bidang Pendidikan Khusus.</p>
                    @endif
                </div>
            </div>

        </div>

        <!-- Right Column: Documents & Metadata (1 Col) -->
        <div class="space-y-6">

            <!-- Berkas & Download Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
                <h3 class="text-xs font-black uppercase tracking-wider text-[#00264A] flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Dokumen & Lampiran
                </h3>

                <!-- File Persyaratan Pemohon -->
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 space-y-2">
                    <span class="text-[10px] font-bold uppercase text-slate-500">1. Berkas Persyaratan Pemohon</span>
                    @if($layanan->file_persyaratan)
                        <div class="flex items-center justify-between pt-1">
                            <span class="text-xs font-semibold text-slate-700 truncate max-w-[150px]">{{ $layanan->file_nama_persyaratan ?? 'Berkas Persyaratan' }}</span>
                            <a href="{{ route('pendidikan.slb.layanan.download', ['id' => $layanan->id, 'type' => 'persyaratan']) }}" 
                               class="inline-flex items-center space-x-1 px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                <span>Unduh</span>
                            </a>
                        </div>
                    @else
                        <p class="text-xs text-slate-400 italic">Belum diunggah</p>
                    @endif
                </div>

                <!-- File Surat Rekomendasi Hasil -->
                <div class="p-4 rounded-xl border border-emerald-200 bg-emerald-50/40 space-y-2">
                    <span class="text-[10px] font-bold uppercase text-emerald-700">2. Surat Rekomendasi / SK Resmi</span>
                    @if($layanan->file_hasil_rekomendasi)
                        <div class="flex items-center justify-between pt-1">
                            <span class="text-xs font-bold text-emerald-900 truncate max-w-[150px]">{{ $layanan->file_nama_hasil ?? 'Surat Rekomendasi Resmi' }}</span>
                            <a href="{{ route('pendidikan.slb.layanan.download', ['id' => $layanan->id, 'type' => 'hasil']) }}" 
                               class="inline-flex items-center space-x-1 px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                <span>Unduh SK</span>
                            </a>
                        </div>
                    @else
                        <p class="text-xs text-slate-400 italic">Belum diterbitkan</p>
                    @endif
                </div>
            </div>

            <!-- Meta Info Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-3 text-xs">
                <h3 class="text-xs font-black uppercase tracking-wider text-[#00264A]">Informasi Sistem</h3>
                <div class="space-y-2 text-slate-600">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Didaftarkan:</span>
                        <span class="font-semibold">{{ $layanan->created_at->format('d M Y, H:i') }} WIT</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Terakhir Update:</span>
                        <span class="font-semibold">{{ $layanan->updated_at->format('d M Y, H:i') }} WIT</span>
                    </div>
                    @if($layanan->user)
                        <div class="flex justify-between">
                            <span class="text-slate-400">Operator Penginput:</span>
                            <span class="font-semibold">{{ $layanan->user->name }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Card -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center justify-between">
                <form action="{{ route('pendidikan.slb.layanan.destroy', $layanan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data permohonan ini secara permanen?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold rounded-xl transition-colors">
                        Hapus Dokumen
                    </button>
                </form>

                @if(auth()->check() && (auth()->user()->hasRole('KEPALA_DINAS') || str_contains(strtolower(auth()->user()->name), 'kepala dinas')))
                    <a href="{{ route('pendidikan.slb.layanan.proses', $layanan->id) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow transition-colors">
                        Proses Verifikasi
                    </a>
                @else
                    <a href="{{ route('pendidikan.slb.layanan.edit', $layanan->id) }}" class="px-4 py-2 bg-[#00264A] hover:bg-[#003B70] text-white text-xs font-bold rounded-xl shadow transition-colors">
                        Edit Data
                    </a>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
