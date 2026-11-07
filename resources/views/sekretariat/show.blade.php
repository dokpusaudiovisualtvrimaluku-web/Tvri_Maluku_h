@extends('layouts.app')

@section('title', 'Detail Agenda: ' . $agenda->judul_kegiatan . ' - Sekretariat TVRI Maluku')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Breadcrumb & Top Bar -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-xs text-slate-500">
            <a href="{{ route('sekretariat.data.index') }}" class="hover:text-[#003B70] font-medium">Sekretariat Dinas</a>
            <span>/</span>
            <span class="text-slate-800 font-bold">Detail Agenda: {{ $agenda->nomor_agenda }}</span>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('sekretariat.data.index') }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Kembali</span>
            </a>
            <a href="{{ route('sekretariat.data.edit', $agenda->id) }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-bold rounded-xl transition-all shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <span>Edit Agenda</span>
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
        $meta = $agenda->fungsi_meta;
        $statusOptions = \App\Models\SimandatSekretariat::STATUS_LIST;
        $prioritasOptions = \App\Models\SimandatSekretariat::PRIORITAS_LIST;
        $statusBadge = $statusOptions[$agenda->status_pelaksanaan] ?? 'bg-gray-100 text-gray-800 border-gray-300';
        $prioritasBadge = $prioritasOptions[$agenda->tingkat_prioritas] ?? 'bg-gray-50 text-gray-700';
    @endphp

    <div class="bg-gradient-to-r from-[#00264A] via-[#003B70] to-[#005B9F] rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 w-96 h-96 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                <div class="flex items-center space-x-2.5">
                    <span class="px-3 py-1 bg-white/10 text-white border border-white/20 rounded-full text-xs font-mono font-bold">
                        {{ $agenda->nomor_agenda }}
                    </span>
                    <span class="px-3 py-1 bg-amber-500/20 text-amber-300 border border-amber-500/40 rounded-full text-xs font-bold">
                        {{ $meta['label'] }}
                    </span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 text-xs font-bold rounded-lg border {{ $statusBadge }}">
                        {{ $agenda->status_pelaksanaan }}
                    </span>
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg border {{ $prioritasBadge }}">
                        Prioritas {{ $agenda->tingkat_prioritas }}
                    </span>
                </div>
            </div>

            <h1 class="text-2xl lg:text-3xl font-black text-white leading-tight">
                {{ $agenda->judul_kegiatan }}
            </h1>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-5 border-t border-white/10 text-xs">
                <div>
                    <p class="text-blue-300 font-medium">Penanggung Jawab (PIC)</p>
                    <p class="text-white font-bold text-sm mt-0.5">{{ $agenda->penanggung_jawab }}</p>
                </div>
                <div>
                    <p class="text-blue-300 font-medium">Bidang / Unit Terkait</p>
                    <p class="text-white font-bold text-sm mt-0.5">{{ $agenda->bidang_unit_terkait ?? 'Sekretariat Dinas' }}</p>
                </div>
                <div>
                    <p class="text-blue-300 font-medium">Tahun Anggaran</p>
                    <p class="text-white font-bold text-sm mt-0.5">TA {{ $agenda->tahun_anggaran }}</p>
                </div>
                <div>
                    <p class="text-blue-300 font-medium">Tanggal Pelaksanaan</p>
                    <p class="text-white font-bold text-sm mt-0.5">{{ $agenda->tanggal_kegiatan ? $agenda->tanggal_kegiatan->format('d F Y') : '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 2 Column Layout for Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Details & Content (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- 1. Klasifikasi Tugas Pokok -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-[#003B70]"></span>
                    <span>Rincian Tugas Pokok Sekretaris Stasiun</span>
                </h3>
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                    <div class="flex items-start space-x-2 text-xs">
                        <span class="font-bold text-slate-700 min-w-[120px]">Fungsi Pokok:</span>
                        <span class="font-semibold text-[#003B70]">{{ $meta['label'] }}</span>
                    </div>
                    <div class="flex items-start space-x-2 text-xs">
                        <span class="font-bold text-slate-700 min-w-[120px]">Sub-Tugas:</span>
                        <span class="text-slate-800 font-medium">{{ $agenda->sub_kegiatan }}</span>
                    </div>
                    <div class="flex items-start space-x-2 text-xs pt-1 border-t border-slate-200/60">
                        <span class="font-bold text-slate-500 min-w-[120px]">Ruang Lingkup:</span>
                        <span class="text-slate-600 italic text-[11px]">{{ $meta['deskripsi'] }}</span>
                    </div>
                </div>
            </div>

            <!-- 2. Deskripsi & Uraian Teknis -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-[#003B70]"></span>
                    <span>Deskripsi & Uraian Teknis Kegiatan</span>
                </h3>
                <div class="prose prose-sm text-xs text-slate-700 leading-relaxed bg-slate-50/50 p-4 rounded-xl border border-slate-100 whitespace-pre-line">
                    {{ $agenda->deskripsi_uraian ?: 'Belum ada deskripsi uraian teknis yang dimasukkan.' }}
                </div>
            </div>

            <!-- 3. Hasil Keluaran (Output / Deliverables) -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-[#003B70]"></span>
                    <span>Hasil Keluaran (Output / Deliverables)</span>
                </h3>
                <div class="p-4 bg-emerald-50/40 rounded-xl border border-emerald-200/60 text-xs text-slate-800 whitespace-pre-line">
                    {{ $agenda->hasil_keluaran_output ?: 'Belum ada catatan hasil keluaran/output yang dicatat.' }}
                </div>
            </div>

            <!-- 4. Catatan Arahan & Pembinaan Sekretaris Stasiun -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-amber-200">
                <div class="flex items-center space-x-2 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-amber-900">Arahan & Pembinaan Sekretaris Stasiun</h3>
                        <p class="text-[10px] text-amber-700">Fungsi Pengendalian & Koordinasi Internal</p>
                    </div>
                </div>
                <div class="p-4 bg-amber-50/60 rounded-xl border border-amber-200 text-xs font-medium text-amber-950 leading-relaxed whitespace-pre-line">
                    {{ $agenda->catatan_arahan_sekretaris ?: 'Belum ada catatan arahan atau disposisi dari Sekretaris Stasiun.' }}
                </div>
            </div>

        </div>

        <!-- Right Column: Budget & Files (1 Col) -->
        <div class="space-y-6">

            <!-- Capaian & Kinerja Box -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-4">Capaian & Kinerja</h3>
                
                <div class="text-center p-4 bg-slate-50 rounded-xl border border-slate-100">
                    <p class="text-xs font-semibold text-slate-500">Persentase Capaian</p>
                    <p class="text-4xl font-black text-[#003B70] mt-1">{{ $agenda->persentase_capaian }}%</p>
                    
                    <div class="w-full bg-slate-200 rounded-full h-2.5 mt-3 overflow-hidden">
                        <div class="h-2.5 rounded-full {{ $agenda->persentase_capaian >= 100 ? 'bg-emerald-500' : ($agenda->persentase_capaian >= 50 ? 'bg-blue-500' : 'bg-amber-500') }}" 
                             style="width: {{ min(100, $agenda->persentase_capaian) }}%"></div>
                    </div>
                </div>

                <div class="mt-4 space-y-3 text-xs">
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-slate-500">Status</span>
                        <span class="font-bold text-slate-800">{{ $agenda->status_pelaksanaan }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-slate-500">Prioritas</span>
                        <span class="font-bold text-slate-800">{{ $agenda->tingkat_prioritas }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-slate-500">Target Selesai</span>
                        <span class="font-bold text-slate-800">{{ $agenda->tanggal_target_selesai ? $agenda->tanggal_target_selesai->format('d/m/Y') : '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Anggaran Box -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-4">Akuntabilitas Anggaran</h3>

                @if($agenda->alokasi_anggaran > 0)
                    <div class="space-y-3 text-xs">
                        <div class="p-3 bg-blue-50/50 rounded-xl border border-blue-100">
                            <span class="text-slate-500 block text-[11px]">Alokasi Pagu Anggaran</span>
                            <span class="text-base font-black text-[#00264A]">Rp {{ number_format($agenda->alokasi_anggaran, 0, ',', '.') }}</span>
                        </div>

                        <div class="p-3 bg-emerald-50/50 rounded-xl border border-emerald-100">
                            <span class="text-slate-500 block text-[11px]">Realisasi Anggaran</span>
                            <span class="text-base font-black text-emerald-700">Rp {{ number_format($agenda->realisasi_anggaran, 0, ',', '.') }}</span>
                        </div>

                        @php
                            $sisa = $agenda->alokasi_anggaran - $agenda->realisasi_anggaran;
                            $serapan = $agenda->alokasi_anggaran > 0 ? round(($agenda->realisasi_anggaran / $agenda->alokasi_anggaran) * 100, 1) : 0;
                        @endphp
                        <div class="flex justify-between items-center py-2 text-[11px] border-t border-slate-100">
                            <span class="text-slate-500">Sisa Pagu Anggaran:</span>
                            <span class="font-bold text-slate-800">Rp {{ number_format($sisa, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[11px]">
                            <span class="text-slate-500">Tingkat Serapan:</span>
                            <span class="font-bold text-emerald-600">{{ $serapan }}%</span>
                        </div>
                    </div>
                @else
                    <div class="p-4 bg-slate-50 rounded-xl text-center text-xs text-slate-400">
                        Kegiatan Non-Budgeter (Administratif / Fasilitasi Rutin)
                    </div>
                @endif
            </div>

            <!-- Berkas Lampiran Dokumen -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Berkas Dokumen Pendukung</h3>

                @if($agenda->file_dokumen)
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                        <div class="flex items-center space-x-2 text-xs">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 text-[#003B70] flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path></svg>
                            </div>
                            <div class="overflow-hidden">
                                <p class="font-bold text-slate-800 truncate" title="{{ $agenda->file_nama_asli }}">{{ $agenda->file_nama_asli }}</p>
                                <p class="text-[10px] text-slate-400">{{ number_format($agenda->file_size / 1024, 0) }} KB</p>
                            </div>
                        </div>

                        <a href="{{ route('sekretariat.data.download', $agenda->id) }}" class="w-full py-2 bg-[#003B70] hover:bg-[#00264A] text-white text-xs font-bold rounded-xl shadow transition-all flex items-center justify-center space-x-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            <span>Unduh Berkas Lampiran</span>
                        </a>
                    </div>
                @else
                    <div class="p-4 bg-slate-50 rounded-xl text-center text-xs text-slate-400">
                        Tidak ada berkas fisik lampiran
                    </div>
                @endif
            </div>

            <!-- Audit Trail & Meta -->
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-[11px] text-slate-500 space-y-1">
                <p><span class="font-semibold">Dibuat:</span> {{ $agenda->created_at ? $agenda->created_at->format('d/m/Y H:i') : '-' }}</p>
                <p><span class="font-semibold">Terakhir Diperbarui:</span> {{ $agenda->updated_at ? $agenda->updated_at->format('d/m/Y H:i') : '-' }}</p>
                @if($agenda->user)
                    <p><span class="font-semibold">Operator Entri:</span> {{ $agenda->user->name }}</p>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
