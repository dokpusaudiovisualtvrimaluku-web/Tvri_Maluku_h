@extends('layouts.app')

@section('title', 'Edit Dokumen Program & Perencanaan - TVRI Maluku')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Breadcrumb & Back -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-xs text-slate-500">
            <a href="{{ route('perencanaan.program.index') }}" class="hover:text-purple-600 font-medium">Program & Perencanaan</a>
            <span>/</span>
            <a href="{{ route('perencanaan.program.show', $program) }}" class="hover:text-purple-600 font-medium">{{ $program->nomor_registrasi }}</a>
            <span>/</span>
            <span class="text-slate-800 font-bold">Edit Dokumen</span>
        </div>
        <a href="{{ route('perencanaan.program.show', $program) }}" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Kembali ke Detail</span>
        </a>
    </div>

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-[#00264A] to-[#004880] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 bg-purple-400/20 text-purple-200 border border-purple-400/40 rounded-full text-[11px] font-bold tracking-wider uppercase">
                    Perbarui Dokumen Perencanaan
                </span>
                <span class="text-xs font-mono text-purple-200 bg-white/10 px-2 py-0.5 rounded">
                    {{ $program->nomor_registrasi }}
                </span>
            </div>
            <h1 class="text-2xl font-black text-white mt-2">Edit: {{ $program->nama_program_kegiatan }}</h1>
            <p class="text-xs text-blue-100 max-w-2xl mt-1 leading-relaxed">
                Perbarui rincian anggaran, target kinerja, realisasi, atau lampiran berkas dokumen perencanaan terkait.
            </p>
        </div>
    </div>

    <!-- Error Validation Alert -->
    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
            <p class="font-bold mb-1">Terdapat kesalahan input formulir:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Container -->
    <form action="{{ route('perencanaan.program.update', $program) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- 1. Klasifikasi Dokumen Perencanaan -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
            <h2 class="text-sm font-bold text-[#00264A] uppercase tracking-wider flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-purple-100 text-purple-800 inline-flex items-center justify-center text-xs font-black">1</span>
                Klasifikasi Dokumen Perencanaan
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Jenis Dokumen -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Dokumen Perencanaan <span class="text-rose-500">*</span></label>
                    <select name="jenis_dokumen" required class="w-full py-2.5 px-3 text-xs rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none font-semibold text-slate-800 bg-slate-50/50">
                        @foreach($jenisDokumenOptions as $key => $meta)
                            <option value="{{ $key }}" {{ old('jenis_dokumen', $program->jenis_dokumen) == $key ? 'selected' : '' }}>
                                {{ $key }} ({{ $meta['label'] }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Bidang Penanggung Jawab -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Bidang / Unit Kerja Penanggung Jawab <span class="text-rose-500">*</span></label>
                    <select name="bidang_penanggung_jawab" required class="w-full py-2.5 px-3 text-xs rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none">
                        @foreach($bidangOptions as $key => $label)
                            <option value="{{ $key }}" {{ old('bidang_penanggung_jawab', $program->bidang_penanggung_jawab) == $key ? 'selected' : '' }}>{{ $key }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tahun Anggaran -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tahun Anggaran <span class="text-rose-500">*</span></label>
                    <input type="number" name="tahun_anggaran" value="{{ old('tahun_anggaran', $program->tahun_anggaran) }}" required min="2020" max="2035"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none">
                </div>
            </div>
        </div>

        <!-- 2. Rincian Program / Kegiatan & Kode Rekening -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
            <h2 class="text-sm font-bold text-[#00264A] uppercase tracking-wider flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-purple-100 text-purple-800 inline-flex items-center justify-center text-xs font-black">2</span>
                Rincian Nama Program / Kegiatan
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kode Rekening / Subkegiatan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kode Rekening / Kode Program (SIPD)</label>
                    <input type="text" name="kode_rekening" value="{{ old('kode_rekening', $program->kode_rekening) }}" placeholder="Contoh: 1.01.02.1.01.0002"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none font-mono">
                </div>

                <!-- Periode Pelaksanaan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Periode Pelaksanaan</label>
                    <input type="text" name="periode_pelaksanaan" value="{{ old('periode_pelaksanaan', $program->periode_pelaksanaan) }}" placeholder="Contoh: 2024-2026 / Triwulan I - IV"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none">
                </div>

                <!-- Nama Program / Kegiatan -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Program / Kegiatan / Dokumen <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_program_kegiatan" value="{{ old('nama_program_kegiatan', $program->nama_program_kegiatan) }}" required
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none font-semibold">
                </div>

                <!-- Uraian Kegiatan -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Uraian / Deskripsi Lengkap <span class="text-rose-500">*</span></label>
                    <textarea name="uraian_kegiatan" rows="3" required
                              class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none">{{ old('uraian_kegiatan', $program->uraian_kegiatan) }}</textarea>
                </div>
            </div>
        </div>

        <!-- 3. Alokasi Pagu Anggaran & Target Kinerja -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
            <h2 class="text-sm font-bold text-[#00264A] uppercase tracking-wider flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-purple-100 text-purple-800 inline-flex items-center justify-center text-xs font-black">3</span>
                Alokasi Pagu Anggaran & Target Kinerja
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Pagu Anggaran -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Pagu Anggaran (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" name="pagu_anggaran" value="{{ old('pagu_anggaran', $program->pagu_anggaran) }}" required min="0" step="1000"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none font-mono font-bold text-slate-800">
                </div>

                <!-- Realisasi Anggaran -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Realisasi Anggaran Terkini (Rp)</label>
                    <input type="number" name="realisasi_anggaran" value="{{ old('realisasi_anggaran', $program->realisasi_anggaran) }}" min="0" step="1000"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none font-mono font-bold text-emerald-700">
                </div>

                <!-- Status Pelaksanaan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status Pelaksanaan <span class="text-rose-500">*</span></label>
                    <select name="status_pelaksanaan" required class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none">
                        @foreach($statusOptions as $k => $st)
                            <option value="{{ $k }}" {{ old('status_pelaksanaan', $program->status_pelaksanaan) == $k ? 'selected' : '' }}>{{ $st['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Target Kinerja -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Target Indikator Kinerja</label>
                    <input type="text" name="target_kinerja" value="{{ old('target_kinerja', $program->target_kinerja) }}" placeholder="Contoh: 100 Unit Sekolah / 12 Laporan Monev"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none">
                </div>

                <!-- Realisasi Kinerja -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Realisasi Kinerja Terkini</label>
                    <input type="text" name="realisasi_kinerja" value="{{ old('realisasi_kinerja', $program->realisasi_kinerja) }}" placeholder="Contoh: 85 Unit Sekolah / 8 Laporan"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none">
                </div>

                <!-- Pejabat Penanggung Jawab / PPTK -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Pejabat / PPTK Penanggung Jawab</label>
                    <input type="text" name="pejabat_penanggung_jawab" value="{{ old('pejabat_penanggung_jawab', $program->pejabat_penanggung_jawab) }}" placeholder="Nama Kabid / PPTK / Kasubbag"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none">
                </div>
            </div>
        </div>

        <!-- 4. Lampiran Berkas Dokumen -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
            <h2 class="text-sm font-bold text-[#00264A] uppercase tracking-wider flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-purple-100 text-purple-800 inline-flex items-center justify-center text-xs font-black">4</span>
                Berkas Dokumen Perencanaan (PDF / Excel / Word)
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 rounded-xl bg-purple-50/50 border border-purple-100 md:col-span-2">
                    <label class="block text-xs font-bold text-purple-900 mb-1">Ganti Berkas Dokumen (Opsional - Maksimal 15 MB)</label>
                    @if($program->file_path)
                        <div class="flex items-center space-x-2 mb-2 p-2 bg-white rounded-lg border border-purple-200 text-xs">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="font-medium text-slate-700 truncate">Berkas saat ini: {{ basename($program->file_path) }}</span>
                            <a href="{{ route('perencanaan.program.download', $program) }}" class="text-purple-600 hover:underline font-bold text-[11px] ml-auto">Unduh</a>
                        </div>
                    @endif
                    <input type="file" name="file_dokumen" accept=".pdf,.doc,.docx,.xls,.xlsx,.zip"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-600 file:text-white hover:file:bg-purple-700">
                </div>

                <!-- Catatan Evaluasi -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Evaluasi / Keterangan Tambahan</label>
                    <input type="text" name="catatan_evaluasi" value="{{ old('catatan_evaluasi', $program->catatan_evaluasi) }}" placeholder="Catatan hasil verifikasi atau rekomendasi tim perencanaan..."
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none">
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end space-x-3 pt-2">
            <a href="{{ route('perencanaan.program.show', $program) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-[#00264A] hover:bg-[#003B70] text-white text-xs font-bold rounded-xl shadow-lg hover:shadow-purple-900/30 transition-all duration-200">
                Simpan Perubahan
            </button>
        </div>
    </form>

</div>
@endsection