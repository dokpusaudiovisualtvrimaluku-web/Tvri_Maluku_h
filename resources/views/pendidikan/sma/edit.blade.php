@extends('layouts.app')

@section('title', 'Edit Permohonan Dokumen SMA - TVRI Maluku')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Breadcrumb & Top Bar -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-xs text-slate-500">
            <a href="{{ route('pendidikan.sma.layanan.index') }}" class="hover:text-[#003B70] font-medium">Bidang Pembinaan SMA</a>
            <span>/</span>
            <a href="{{ route('pendidikan.sma.layanan.show', $layanan->id) }}" class="hover:text-[#003B70] font-medium">{{ $layanan->nomor_registrasi }}</a>
            <span>/</span>
            <span class="text-slate-800 font-bold">Edit Permohonan</span>
        </div>
        <a href="{{ route('pendidikan.sma.layanan.show', $layanan->id) }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Kembali ke Detail</span>
        </a>
    </div>

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-[#00264A] via-[#003B70] to-[#005B9F] rounded-2xl p-6 text-white shadow-xl">
        <div class="flex items-center space-x-3 mb-2">
            <span class="px-3 py-1 bg-amber-500/20 text-amber-300 border border-amber-500/40 rounded-full text-xs font-bold uppercase tracking-wider">
                Perbarui Layanan SMA
            </span>
            <span class="text-xs text-blue-200 font-mono font-bold">{{ $layanan->nomor_registrasi }}</span>
        </div>
        <h1 class="text-2xl font-black text-white">{{ $layanan->jenis_layanan }}</h1>
        <p class="text-xs text-blue-100 mt-1 max-w-2xl leading-relaxed">
            Pemohon: {{ $layanan->nama_pemohon }} ({{ $layanan->nama_sekolah_asal }})
        </p>
    </div>

    <!-- Form Container -->
    <form action="{{ route('pendidikan.sma.layanan.update', $layanan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- 1. Klasifikasi 11 Layanan SMA -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-5">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="text-sm font-bold text-slate-800 flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-[#003B70]"></span>
                    <span>1. Jenis Layanan Dokumen SMA</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Pilih salah satu dari 11 layanan administrasi resmi Bidang Pembinaan SMA</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Jenis Layanan SMA <span class="text-rose-500">*</span>
                </label>
                <select name="jenis_layanan" id="jenis_layanan" required 
                        class="w-full text-xs py-2.5 px-3 bg-slate-50 border @error('jenis_layanan') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all"
                        onchange="updateLayananInfo()">
                    @foreach($allJenisLayanan as $svcName => $svcMeta)
                        <option value="{{ $svcName }}" {{ old('jenis_layanan', $layanan->jenis_layanan) === $svcName ? 'selected' : '' }}>
                            {{ $loop->iteration }}. {{ $svcName }}
                        </option>
                    @endforeach
                </select>
                @error('jenis_layanan')
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="layanan_info_box" class="p-4 bg-blue-50/70 border border-blue-200/70 rounded-xl text-xs text-[#003B70] leading-relaxed">
                <!-- Populated by JS -->
            </div>
        </div>

        <!-- 2. Data Pemohon & Sekolah Asal -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-5">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="text-sm font-bold text-slate-800 flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-[#003B70]"></span>
                    <span>2. Identitas Pemohon & Satuan Pendidikan SMA</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Informasi nama pemohon (siswa/alumni/kepsek) dan sekolah asal di Maluku</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Nama Pemohon -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Nama Pemohon <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nama_pemohon" value="{{ old('nama_pemohon', $layanan->nama_pemohon) }}" required 
                           class="w-full text-xs py-2.5 px-3 bg-slate-50 border @error('nama_pemohon') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">
                    @error('nama_pemohon')
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor Identitas -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Nomor Identitas (NISN / NIK / NIP / NPSN)
                    </label>
                    <input type="text" name="nomor_identitas" value="{{ old('nomor_identitas', $layanan->nomor_identitas) }}" 
                           class="w-full text-xs py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Nama Sekolah Asal -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Nama Satuan Pendidikan SMA Asal / Terkait <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nama_sekolah_asal" value="{{ old('nama_sekolah_asal', $layanan->nama_sekolah_asal) }}" required 
                           class="w-full text-xs py-2.5 px-3 bg-slate-50 border @error('nama_sekolah_asal') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">
                    @error('nama_sekolah_asal')
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NPSN Sekolah -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        NPSN Sekolah (Opsional)
                    </label>
                    <input type="text" name="npsn_sekolah" value="{{ old('npsn_sekolah', $layanan->npsn_sekolah) }}" 
                           class="w-full text-xs py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Kabupaten / Kota -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Kabupaten / Kota <span class="text-rose-500">*</span>
                    </label>
                    <select name="kabupaten_kota" required 
                            class="w-full text-xs py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">
                        @foreach($kabupatenOptions as $kab)
                            <option value="{{ $kab }}" {{ old('kabupaten_kota', $layanan->kabupaten_kota) === $kab ? 'selected' : '' }}>{{ $kab }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Pengajuan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Tanggal Pengajuan <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="tanggal_pengajuan" value="{{ old('tanggal_pengajuan', $layanan->tanggal_pengajuan ? $layanan->tanggal_pengajuan->format('Y-m-d') : '') }}" required 
                           class="w-full text-xs py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">
                </div>

                <!-- Tanggal Selesai -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Tanggal Selesai (Jika Selesai)
                    </label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $layanan->tanggal_selesai ? $layanan->tanggal_selesai->format('Y-m-d') : '') }}" 
                           class="w-full text-xs py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">
                </div>
            </div>
        </div>

        <!-- 3. Perihal, Status & Verifikasi -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-5">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="text-sm font-bold text-slate-800 flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-[#003B70]"></span>
                    <span>3. Deskripsi Permohonan & Status Verifikasi</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Penjabaran isi permohonan, nomor surat keluar, serta status pemrosesan</p>
            </div>

            <!-- Perihal & Uraian -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Perihal & Uraian Rincian Permohonan <span class="text-rose-500">*</span>
                </label>
                <textarea name="perihal_uraian" rows="3" required 
                          class="w-full text-xs py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">{{ old('perihal_uraian', $layanan->perihal_uraian) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Status Layanan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Status Permohonan <span class="text-rose-500">*</span>
                    </label>
                    <select name="status_layanan" required 
                            class="w-full text-xs py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">
                        @foreach($statusOptions as $st => $stBadge)
                            <option value="{{ $st }}" {{ old('status_layanan', $layanan->status_layanan) === $st ? 'selected' : '' }}>
                                {{ str_replace('_', ' ', $st) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nomor Surat / SK Terbit -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Nomor Surat Rekomendasi / SK Terbit
                    </label>
                    <input type="text" name="nomor_surat_keluar" value="{{ old('nomor_surat_keluar', $layanan->nomor_surat_keluar) }}" 
                           class="w-full text-xs py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Petugas Verifikator -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Petugas / Seksi Verifikator
                    </label>
                    <input type="text" name="petugas_verifikator" value="{{ old('petugas_verifikator', $layanan->petugas_verifikator) }}" 
                           class="w-full text-xs py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">
                </div>

                <!-- Catatan Verifikator -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Catatan Verifikator / Disposisi
                    </label>
                    <input type="text" name="catatan_verifikator" value="{{ old('catatan_verifikator', $layanan->catatan_verifikator) }}" 
                           class="w-full text-xs py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#003B70] focus:ring-1 focus:ring-[#003B70] transition-all">
                </div>
            </div>
        </div>

        <!-- 4. Upload Berkas Dokumen -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-5">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="text-sm font-bold text-slate-800 flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-[#003B70]"></span>
                    <span>4. Berkas Lampiran & Dokumen Hasil</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Kelola berkas persyaratan dan surat keputusan/rekomendasi</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Berkas Permohonan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Berkas Syarat Permohonan
                    </label>
                    @if($layanan->file_permohonan)
                        <div class="mb-2 p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-800 truncate" title="{{ $layanan->file_nama_permohonan }}">{{ $layanan->file_nama_permohonan }}</span>
                            <a href="{{ route('pendidikan.sma.layanan.download', ['id' => $layanan->id, 'type' => 'permohonan']) }}" class="px-2 py-1 bg-blue-50 text-blue-700 rounded font-bold hover:bg-blue-100">Unduh</a>
                        </div>
                    @endif
                    <div class="border-2 border-dashed border-slate-200 rounded-2xl p-3 text-center hover:bg-slate-50">
                        <input type="file" name="file_permohonan" id="file_permohonan" class="hidden" onchange="previewFile(this, 'preview_req_name')">
                        <label for="file_permohonan" class="cursor-pointer">
                            <p class="text-xs font-bold text-[#003B70]">Ganti Berkas Syarat</p>
                            <p class="text-[10px] text-slate-400">PDF/DOC/JPG/ZIP (Maks 25MB)</p>
                        </label>
                        <div id="preview_req_name" class="mt-1 text-xs font-bold text-emerald-600 hidden"></div>
                    </div>
                </div>

                <!-- Berkas SK Terbit -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Berkas Hasil Rekomendasi / SK Terbit
                    </label>
                    @if($layanan->file_hasil_sk)
                        <div class="mb-2 p-3 bg-emerald-50 rounded-xl border border-emerald-200 flex items-center justify-between text-xs">
                            <span class="font-bold text-emerald-900 truncate" title="{{ $layanan->file_nama_hasil_sk }}">{{ $layanan->file_nama_hasil_sk }}</span>
                            <a href="{{ route('pendidikan.sma.layanan.download', ['id' => $layanan->id, 'type' => 'hasil_sk']) }}" class="px-2 py-1 bg-emerald-100 text-emerald-800 rounded font-bold hover:bg-emerald-200">Unduh</a>
                        </div>
                    @endif
                    <div class="border-2 border-dashed border-slate-200 rounded-2xl p-3 text-center hover:bg-slate-50">
                        <input type="file" name="file_hasil_sk" id="file_hasil_sk" class="hidden" onchange="previewFile(this, 'preview_sk_name')">
                        <label for="file_hasil_sk" class="cursor-pointer">
                            <p class="text-xs font-bold text-emerald-700">Unggah/Ganti SK Terbit</p>
                            <p class="text-[10px] text-slate-400">Dokumen final bertanda tangan</p>
                        </label>
                        <div id="preview_sk_name" class="mt-1 text-xs font-bold text-emerald-600 hidden"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit & Actions -->
        <div class="flex items-center justify-end space-x-3 pt-2">
            <a href="{{ route('pendidikan.sma.layanan.show', $layanan->id) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all">
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
    const allLayananData = @json($allJenisLayanan);

    function updateLayananInfo() {
        const select = document.getElementById('jenis_layanan');
        const box = document.getElementById('layanan_info_box');
        const currentData = allLayananData[select.value];

        if (currentData) {
            box.innerHTML = `
                <div class="flex items-center space-x-2 font-bold mb-1 text-slate-800">
                    <span class="w-2 h-2 rounded-full bg-[#003B70]"></span>
                    <span>Seksi Penanggung Jawab: ${currentData.seksi}</span>
                </div>
                <p class="text-slate-600">${currentData.deskripsi}</p>
            `;
        }
    }

    function previewFile(input, elementId) {
        const previewEl = document.getElementById(elementId);
        if (input.files && input.files[0]) {
            previewEl.textContent = `✓ Berkas baru: ${input.files[0].name} (${(input.files[0].size / 1024 / 1024).toFixed(2)} MB)`;
            previewEl.classList.remove('hidden');
        } else {
            previewEl.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateLayananInfo();
    });
</script>
@endsection
