@extends('layouts.app')

@section('title', 'Edit Data Agenda Sekretariat - TVRI Maluku')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Breadcrumb & Top Bar -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-xs text-slate-500">
            <a href="{{ route('sekretariat.data.index') }}" class="hover:text-[#003B70] font-medium">Sekretariat Dinas</a>
            <span>/</span>
            <a href="{{ route('sekretariat.data.show', $agenda->id) }}" class="hover:text-[#003B70] font-medium">{{ $agenda->nomor_agenda }}</a>
            <span>/</span>
            <span class="text-slate-800 font-bold">Edit Data</span>
        </div>
        <a href="{{ route('sekretariat.data.show', $agenda->id) }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Kembali ke Detail</span>
        </a>
    </div>

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-[#00264A] via-[#003B70] to-[#005B9F] rounded-2xl p-6 text-white shadow-xl">
        <div class="flex items-center space-x-3 mb-2">
            <span class="px-3 py-1 bg-amber-500/20 text-amber-300 border border-amber-500/40 rounded-full text-xs font-bold uppercase tracking-wider">
                Perbarui Agenda
            </span>
            <span class="text-xs text-blue-200 font-mono font-bold">{{ $agenda->nomor_agenda }}</span>
        </div>
        <h1 class="text-2xl font-black text-white">{{ $agenda->judul_kegiatan }}</h1>
        <p class="text-xs text-blue-100 mt-1 max-w-2xl leading-relaxed">
            Perbarui informasi teknis, progres capaian, realisasi keuangan, arahan pembinaan Sekretaris Stasiun, atau ganti berkas lampiran.
        </p>
    </div>

    <!-- Form Container -->
    <form action="{{ route('sekretariat.data.update', $agenda->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- 1. Kategori 8 Fungsi & Sub-Tugas -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-5">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="text-sm font-bold text-slate-800 flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-[#003B70]"></span>
                    <span>1. Klasifikasi 8 Fungsi Sekretaris Stasiun</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Pilih rumpun fungsi tugas dan sub-tugas pokok yang bersangkutan</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Kategori Fungsi -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Kategori Fungsi Utama <span class="text-rose-500">*</span>
                    </label>
                    <select name="kategori_fungsi" id="kategori_fungsi" required 
                            class="w-full text-xs py-2.5 px-3 bg-slate-50 border @error('kategori_fungsi') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all"
                            onchange="updateSubKegiatanOptions()">
                        @foreach($allFungsi as $fKey => $fMeta)
                            <option value="{{ $fKey }}" {{ old('kategori_fungsi', $agenda->kategori_fungsi) === $fKey ? 'selected' : '' }}>
                                {{ $loop->iteration }}. {{ $fMeta['label'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_fungsi')
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sub Kegiatan Sesuai Tugas Pokok -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Rincian Sub-Tugas Pokok <span class="text-rose-500">*</span>
                    </label>
                    <select name="sub_kegiatan" id="sub_kegiatan" required
                            class="w-full text-xs py-2.5 px-3 bg-slate-50 border @error('sub_kegiatan') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">
                        <!-- Populated by JavaScript -->
                    </select>
                    @error('sub_kegiatan')
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Function Deskripsi Box -->
            <div id="fungsi_deskripsi_box" class="p-3.5 bg-blue-50/70 border border-blue-200/70 rounded-xl text-xs text-[#003B70] leading-relaxed">
                <!-- Populated by JS -->
            </div>
        </div>

        <!-- 2. Rincian Agenda & Pelaksanaan -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-5">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="text-sm font-bold text-slate-800 flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-[#003B70]"></span>
                    <span>2. Detail Kegiatan & Penanggung Jawab</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Informasi judul agenda, PIC unit pelaksana, dan batas waktu pelaksanaan</p>
            </div>

            <!-- Judul Kegiatan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Judul Agenda / Program / Kegiatan <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="judul_kegiatan" value="{{ old('judul_kegiatan', $agenda->judul_kegiatan) }}" required 
                       class="w-full text-xs py-2.5 px-3 bg-slate-50 border @error('judul_kegiatan') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">
                @error('judul_kegiatan')
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Penanggung Jawab (PIC) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Penanggung Jawab / Tim Kerja <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="penanggung_jawab" value="{{ old('penanggung_jawab', $agenda->penanggung_jawab) }}" required 
                           class="w-full text-xs py-2.5 px-3 bg-slate-50 border @error('penanggung_jawab') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">
                    @error('penanggung_jawab')
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bidang / Mitra Terkait -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Bidang / Mitra Kerja Terkait
                    </label>
                    <select name="bidang_unit_terkait" 
                            class="w-full text-xs py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">
                        <option value="">-- Pilih Bidang / Unit Terkait (Opsional) --</option>
                        @foreach($bidangOptions as $bOpt)
                            <option value="{{ $bOpt }}" {{ old('bidang_unit_terkait', $agenda->bidang_unit_terkait) === $bOpt ? 'selected' : '' }}>{{ $bOpt }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Tanggal Kegiatan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Tanggal Kegiatan / Mulai <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="tanggal_kegiatan" value="{{ old('tanggal_kegiatan', $agenda->tanggal_kegiatan ? $agenda->tanggal_kegiatan->format('Y-m-d') : '') }}" required 
                           class="w-full text-xs py-2.5 px-3 bg-slate-50 border @error('tanggal_kegiatan') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">
                    @error('tanggal_kegiatan')
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Target Selesai -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Target Selesai / Batas Waktu
                    </label>
                    <input type="date" name="tanggal_target_selesai" value="{{ old('tanggal_target_selesai', $agenda->tanggal_target_selesai ? $agenda->tanggal_target_selesai->format('Y-m-d') : '') }}" 
                           class="w-full text-xs py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">
                </div>

                <!-- Tahun Anggaran -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Tahun Anggaran <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="tahun_anggaran" value="{{ old('tahun_anggaran', $agenda->tahun_anggaran) }}" required min="2020" max="2035" 
                           class="w-full text-xs py-2.5 px-3 bg-slate-50 border @error('tahun_anggaran') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">
                </div>
            </div>
        </div>

        <!-- 3. Anggaran, Progres & Status -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-5">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="text-sm font-bold text-slate-800 flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-[#003B70]"></span>
                    <span>3. Alokasi Anggaran, Kinerja & Status</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Penetapan pagu anggaran, capaian progres, prioritas dan status pemantauan</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Alokasi Anggaran -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Alokasi Pagu Anggaran (Rp)
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-xs text-slate-400 font-bold">Rp</span>
                        <input type="number" name="alokasi_anggaran" value="{{ old('alokasi_anggaran', $agenda->alokasi_anggaran) }}" min="0" step="1000" 
                               class="w-full text-xs py-2.5 pl-9 pr-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">
                    </div>
                </div>

                <!-- Realisasi Anggaran -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Realisasi Anggaran (Rp)
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-xs text-slate-400 font-bold">Rp</span>
                        <input type="number" name="realisasi_anggaran" value="{{ old('realisasi_anggaran', $agenda->realisasi_anggaran) }}" min="0" step="1000" 
                               class="w-full text-xs py-2.5 pl-9 pr-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">
                    </div>
                </div>

                <!-- Persentase Capaian (%) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Capaian Kinerja (0 - 100%)
                    </label>
                    <div class="relative">
                        <input type="number" name="persentase_capaian" value="{{ old('persentase_capaian', $agenda->persentase_capaian) }}" min="0" max="100" 
                               class="w-full text-xs py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">
                        <span class="absolute right-3 top-2.5 text-xs text-slate-400 font-bold">%</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Status Pelaksanaan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Status Pelaksanaan <span class="text-rose-500">*</span>
                    </label>
                    <select name="status_pelaksanaan" required 
                            class="w-full text-xs py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">
                        @foreach($statusOptions as $st => $stBadge)
                            <option value="{{ $st }}" {{ old('status_pelaksanaan', $agenda->status_pelaksanaan) === $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Prioritas -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Tingkat Prioritas <span class="text-rose-500">*</span>
                    </label>
                    <select name="tingkat_prioritas" required 
                            class="w-full text-xs py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">
                        @foreach($prioritasOptions as $pr => $prBadge)
                            <option value="{{ $pr }}" {{ old('tingkat_prioritas', $agenda->tingkat_prioritas) === $pr ? 'selected' : '' }}>{{ $pr }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- 4. Uraian, Output & Arahan Sekretaris -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-5">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="text-sm font-bold text-slate-800 flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-[#003B70]"></span>
                    <span>4. Deskripsi, Hasil Keluaran & Catatan Pembinaan</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Penjabaran teknis kegiatan, output/deliverables, dan arahan pengendalian Sekretaris Stasiun</p>
            </div>

            <!-- Deskripsi Uraian -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Deskripsi & Uraian Teknis Pelaksanaan
                </label>
                <textarea name="deskripsi_uraian" rows="3" 
                          class="w-full text-xs py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">{{ old('deskripsi_uraian', $agenda->deskripsi_uraian) }}</textarea>
            </div>

            <!-- Hasil Keluaran (Output) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Hasil Keluaran (Output / Deliverables)
                </label>
                <textarea name="hasil_keluaran_output" rows="2" 
                          class="w-full text-xs py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">{{ old('hasil_keluaran_output', $agenda->hasil_keluaran_output) }}</textarea>
            </div>

            <!-- Catatan / Arahan & Pembinaan Sekretaris Stasiun -->
            <div class="p-4 bg-amber-50/50 border border-amber-200 rounded-2xl">
                <label class="block text-xs font-bold text-amber-900 mb-1.5 flex items-center space-x-1.5">
                    <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <span>Catatan Arahan, Pembinaan & Pengendalian Sekretaris Stasiun</span>
                </label>
                <textarea name="catatan_arahan_sekretaris" rows="2" 
                          class="w-full text-xs py-2 px-3 bg-white border border-amber-300 rounded-xl focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">{{ old('catatan_arahan_sekretaris', $agenda->catatan_arahan_sekretaris) }}</textarea>
                <p class="text-[11px] text-amber-700 mt-1">Digunakan untuk mencatat fungsi pembinaan dan pengendalian langsung oleh Sekretaris Stasiun.</p>
            </div>

            <!-- Upload Berkas Lampiran -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Berkas Lampiran Pendukung
                </label>

                @if($agenda->file_dokumen)
                    <div class="mb-3 p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-between">
                        <div class="flex items-center space-x-2 text-xs">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path></svg>
                            <span class="font-bold text-slate-800">{{ $agenda->file_nama_asli }}</span>
                            <span class="text-slate-400">({{ number_format($agenda->file_size / 1024, 0) }} KB)</span>
                        </div>
                        <a href="{{ route('sekretariat.data.download', $agenda->id) }}" class="px-2.5 py-1 bg-blue-50 text-[#003B70] rounded-lg text-xs font-bold hover:bg-blue-100 transition-colors">
                            Unduh Berkas
                        </a>
                    </div>
                @endif

                <div class="border-2 border-dashed border-slate-200 rounded-2xl p-5 text-center hover:bg-slate-50 transition-colors">
                    <input type="file" name="file_dokumen" id="file_dokumen" class="hidden" onchange="previewUpload(this)">
                    <label for="file_dokumen" class="cursor-pointer">
                        <div class="w-12 h-12 mx-auto bg-blue-50 text-[#003B70] rounded-2xl flex items-center justify-center mb-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        </div>
                        <p class="text-xs font-bold text-[#003B70]">Pilih file baru untuk mengganti berkas saat ini (Opsional)</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Format didukung: PDF, Word (DOC/DOCX), Excel (XLS/XLSX), PPT, Foto (JPG/PNG), ZIP. Maksimal 25MB.</p>
                    </label>
                    <div id="file_preview_name" class="mt-3 text-xs font-bold text-emerald-600 hidden"></div>
                </div>
                @error('file_dokumen')
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Submit & Actions -->
        <div class="flex items-center justify-end space-x-3 pt-2">
            <a href="{{ route('sekretariat.data.show', $agenda->id) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-[#003B70] hover:bg-[#00264A] text-white text-xs font-bold rounded-xl shadow-lg hover:shadow-blue-900/30 transition-all flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </form>

</div>

<script>
    const allFungsiData = @json($allFungsi);
    const initialSubKegiatan = @json(old('sub_kegiatan', $agenda->sub_kegiatan));

    function updateSubKegiatanOptions() {
        const selectFungsi = document.getElementById('kategori_fungsi');
        const selectSub = document.getElementById('sub_kegiatan');
        const deskripsiBox = document.getElementById('fungsi_deskripsi_box');
        
        const selectedKey = selectFungsi.value;
        const currentData = allFungsiData[selectedKey];

        selectSub.innerHTML = '';

        if (currentData && currentData.sub_kegiatan) {
            currentData.sub_kegiatan.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item;
                opt.textContent = item;
                if (initialSubKegiatan && initialSubKegiatan === item) {
                    opt.selected = true;
                }
                selectSub.appendChild(opt);
            });

            deskripsiBox.innerHTML = `
                <div class="flex items-center space-x-2 font-bold mb-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#003B70]"></span>
                    <span>Ruang Lingkup Tugas: ${currentData.label}</span>
                </div>
                <p class="text-slate-600">${currentData.deskripsi}</p>
            `;
        }
    }

    function previewUpload(input) {
        const previewEl = document.getElementById('file_preview_name');
        if (input.files && input.files[0]) {
            previewEl.textContent = `✓ Berkas baru dipilih: ${input.files[0].name} (${(input.files[0].size / 1024 / 1024).toFixed(2)} MB)`;
            previewEl.classList.remove('hidden');
        } else {
            previewEl.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateSubKegiatanOptions();
    });
</script>
@endsection
