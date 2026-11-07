@extends('layouts.app')

@section('title', $program->nama_program_kegiatan . ' - TVRI Maluku')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Breadcrumb & Navigation -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-xs text-slate-500">
            <a href="{{ route('perencanaan.program.index') }}" class="hover:text-purple-600 font-medium">Program & Perencanaan</a>
            <span>/</span>
            <span class="text-slate-800 font-bold">{{ $program->nomor_registrasi }}</span>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('perencanaan.program.index') }}" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Kembali</span>
            </a>
            <a href="{{ route('perencanaan.program.edit', $program) }}" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded-lg shadow transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <span>Edit Dokumen</span>
            </a>
        </div>
    </div>

    <!-- Banner Header -->
    <div class="bg-gradient-to-r from-[#00264A] to-[#004880] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-2">
                    <span class="px-2.5 py-0.5 bg-purple-400/20 text-purple-200 border border-purple-400/40 rounded-full text-[11px] font-bold tracking-wider uppercase">
                        {{ $program->jenis_dokumen }}
                    </span>
                    <span class="text-xs font-mono text-purple-200 bg-white/10 px-2 py-0.5 rounded">
                        {{ $program->nomor_registrasi }}
                    </span>
                    <span class="text-xs text-blue-200">
                        TA {{ $program->tahun_anggaran }}
                    </span>
                </div>
                <h1 class="text-2xl font-black text-white mt-2 leading-snug">{{ $program->nama_program_kegiatan }}</h1>
                <p class="text-xs text-blue-100 max-w-2xl mt-1">
                    Unit Penanggung Jawab: <span class="font-bold text-white">{{ $program->bidang_penanggung_jawab }}</span>
                </p>
            </div>

            <!-- Status Badge -->
            <div class="flex flex-col items-start md:items-end">
                <span class="text-[10px] text-blue-200 uppercase font-bold tracking-wider mb-1">Status Pelaksanaan</span>
                <span class="px-3 py-1.5 rounded-xl text-xs font-black {{ $program->status_badge_class }} border shadow-sm">
                    {{ $program->status_label }}
                </span>
            </div>
        </div>
    </div>

    <!-- Ringkasan Anggaran & Progres Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Pagu -->
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Pagu Anggaran</span>
            <div class="text-lg font-black text-slate-900 mt-1">
                {{ $program->formatted_pagu }}
            </div>
            <div class="text-[10px] text-slate-400 mt-1">Tahun Anggaran {{ $program->tahun_anggaran }}</div>
        </div>

        <!-- Realisasi -->
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Realisasi Keuangan</span>
            <div class="text-lg font-black text-emerald-600 mt-1">
                {{ $program->formatted_realisasi }}
            </div>
            <div class="text-[10px] text-emerald-600 font-bold mt-1">
                Capaian: {{ $program->persentase_realisasi }}%
            </div>
        </div>

        <!-- Sisa Anggaran -->
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Sisa Anggaran</span>
            <div class="text-lg font-black text-purple-600 mt-1">
                {{ $program->formatted_sisa }}
            </div>
            <div class="text-[10px] text-purple-600 font-bold mt-1">
                {{ 100 - $program->persentase_realisasi }}% Tersisa
            </div>
        </div>

        <!-- Progress Bar Card -->
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex flex-col justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Progres Penyerapan</span>
                <div class="text-sm font-bold text-slate-800 mt-1">
                    {{ $program->persentase_realisasi }}%
                </div>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden mt-2">
                <div class="h-2.5 rounded-full transition-all duration-500 {{ $program->persentase_realisasi >= 80 ? 'bg-emerald-500' : ($program->persentase_realisasi >= 50 ? 'bg-blue-500' : 'bg-amber-500') }}"
                     style="width: {{ min(100, $program->persentase_realisasi) }}%"></div>
            </div>
        </div>
    </div>

    <!-- Main Content Details -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Left Details Column (2 Cols) -->
        <div class="md:col-span-2 space-y-6">

            <!-- Detail Program & Uraian -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
                <h2 class="text-sm font-bold text-[#00264A] uppercase tracking-wider border-b border-slate-100 pb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Uraian & Deskripsi Lengkap
                </h2>

                <div class="space-y-4 text-xs text-slate-700 leading-relaxed whitespace-pre-line bg-slate-50/50 p-4 rounded-xl border border-slate-100">
                    {{ $program->uraian_kegiatan }}
                </div>

                @if($program->catatan_evaluasi)
                    <div class="mt-4 p-4 rounded-xl bg-amber-50 border border-amber-200">
                        <div class="text-xs font-bold text-amber-900 mb-1 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Catatan Evaluasi / Rekomendasi Tim Perencanaan
                        </div>
                        <div class="text-xs text-amber-800 leading-relaxed">
                            {{ $program->catatan_evaluasi }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Target & Realisasi Kinerja -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
                <h2 class="text-sm font-bold text-[#00264A] uppercase tracking-wider border-b border-slate-100 pb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Target & Capaian Indikator Kinerja Output
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-3 bg-blue-50/50 rounded-xl border border-blue-100">
                        <div class="text-[11px] font-bold text-blue-900 uppercase">Target Kinerja</div>
                        <div class="text-xs font-semibold text-slate-800 mt-1">
                            {{ $program->target_kinerja ?: 'Belum ditentukan' }}
                        </div>
                    </div>
                    <div class="p-3 bg-emerald-50/50 rounded-xl border border-emerald-100">
                        <div class="text-[11px] font-bold text-emerald-900 uppercase">Realisasi Kinerja</div>
                        <div class="text-xs font-semibold text-slate-800 mt-1">
                            {{ $program->realisasi_kinerja ?: 'Dalam proses pelaksanaan' }}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Meta Column (1 Col) -->
        <div class="space-y-6">

            <!-- Meta Data Dokumen -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
                <h2 class="text-xs font-bold text-[#00264A] uppercase tracking-wider border-b border-slate-100 pb-2">
                    Metadata Dokumen
                </h2>

                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Kode Rekening SIPD</span>
                        <span class="font-mono font-bold text-slate-800">{{ $program->kode_rekening ?: '-' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Periode Pelaksanaan</span>
                        <span class="font-medium text-slate-800">{{ $program->periode_pelaksanaan ?: '-' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Pejabat / PPTK</span>
                        <span class="font-medium text-slate-800">{{ $program->pejabat_penanggung_jawab ?: '-' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Dibuat Oleh</span>
                        <span class="font-medium text-slate-800">{{ $program->user->name ?? 'Administrator' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Waktu Registrasi</span>
                        <span class="font-medium text-slate-600">{{ $program->created_at->format('d M Y, H:i') }} WIT</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Pembaruan Terakhir</span>
                        <span class="font-medium text-slate-600">{{ $program->updated_at->format('d M Y, H:i') }} WIT</span>
                    </div>
                </div>
            </div>

            <!-- Berkas File Dokumen -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
                <h2 class="text-xs font-bold text-[#00264A] uppercase tracking-wider border-b border-slate-100 pb-2">
                    Berkas Lampiran
                </h2>

                @if($program->file_path)
                    <div class="p-3 bg-purple-50 rounded-xl border border-purple-100 space-y-2">
                        <div class="flex items-center space-x-2">
                            <svg class="w-6 h-6 text-purple-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-purple-900 truncate">{{ basename($program->file_path) }}</p>
                                <p class="text-[10px] text-purple-700">Tersimpan di Cloud Storage TVRI Maluku</p>
                            </div>
                        </div>
                        <a href="{{ route('perencanaan.program.download', $program) }}" class="w-full inline-flex items-center justify-center space-x-1.5 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-lg shadow transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            <span>Unduh Berkas</span>
                        </a>
                    </div>
                @else
                    <div class="p-4 rounded-xl bg-slate-50 border border-dashed border-slate-200 text-center">
                        <p class="text-xs text-slate-400 font-medium">Tidak ada berkas yang diunggah.</p>
                    </div>
                @endif
            </div>

            <!-- Delete Action -->
            <div class="pt-2">
                <form action="{{ route('perencanaan.program.destroy', $program) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data dokumen perencanaan ini? Tindakan ini tidak dapat dibatalkan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition-colors inline-flex items-center justify-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        <span>Hapus Dokumen</span>
                    </button>
                </form>
            </div>

        </div>

    </div>

</div>
@endsection