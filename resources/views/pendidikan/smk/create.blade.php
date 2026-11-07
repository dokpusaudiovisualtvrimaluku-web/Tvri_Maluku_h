@extends('layouts.app')

@section('title', 'Buat Permohonan Dokumen SMK - TVRI Maluku')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Breadcrumb & Back -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-xs text-slate-500">
            <a href="{{ route('pendidikan.smk.layanan.index') }}" class="hover:text-blue-600 font-medium">Layanan SMK</a>
            <span>/</span>
            <span class="text-slate-800 font-bold">Buat Permohonan Baru</span>
        </div>
        <a href="{{ route('pendidikan.smk.layanan.index') }}" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Kembali ke Daftar</span>
        </a>
    </div>

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-[#00264A] to-[#004880] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10">
            <span class="px-2.5 py-0.5 bg-blue-400/20 text-blue-200 border border-blue-400/40 rounded-full text-[11px] font-bold tracking-wider uppercase">
                Formulir Registrasi Dokumen SMK
            </span>
            <h1 class="text-2xl font-black text-white mt-2">Permohonan Layanan Bidang Pembinaan SMK</h1>
            <p class="text-xs text-blue-100 max-w-2xl mt-1 leading-relaxed">
                Silakan lengkapi formulir permohonan layanan SMK dengan data yang valid dan unggah berkas pendukung (PDF/ZIP).
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
    <form action="{{ route('pendidikan.smk.layanan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- 1. Pilihan Jenis Layanan & Info Box -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
            <h2 class="text-sm font-bold text-[#00264A] uppercase tracking-wider flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-800 inline-flex items-center justify-center text-xs font-black">1</span>
                Katalog & Standar Layanan
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Layanan SMK <span class="text-rose-500">*</span></label>
                    <select id="jenis_layanan" name="jenis_layanan" required
                            class="w-full text-xs py-2 px-3 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                        <option value="">-- Pilih Jenis Layanan --</option>
                        <option value="Kesiswaan & Mutasi">Kesiswaan & Mutasi</option>
                        <option value="Pemerataan & Pendanaan">Pemerataan & Pendanaan</option>
                        <option value="Dokumen & Legalitas">Dokumen & Legalitas</option>
                        <option value="Kurikulum & Vokasi">Kurikulum & Vokasi</option>
                        <option value="Kelembagaan & Perizinan">Kelembagaan & Perizinan</option>
                    </select>
                </div>
            </div>
            
            <div id="syarat_box" class="p-4 bg-blue-50 border border-blue-100 rounded-xl mt-3 hidden">
                <p class="text-xs font-bold text-blue-800 mb-2" id="syarat_title">Standar Layanan</p>
                <ul class="text-[11px] text-slate-600 list-disc list-inside space-y-1" id="syarat_list">
                    <li>Proposal Permohonan</li>
                    <li>Surat Rekomendasi Cabang Dinas (Cabdin)</li>
                    <li>Dokumen pendukung relevan lainnya</li>
                </ul>
            </div>
        </div>

        <!-- 2. Data Pemohon -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
            <h2 class="text-sm font-bold text-[#00264A] uppercase tracking-wider flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-800 inline-flex items-center justify-center text-xs font-black">2</span>
                Identitas Pemohon & Satuan Pendidikan SMK
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Pemohon (Kepala Sekolah / Wali Siswa / Pengurus) <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_pemohon" value="{{ old('nama_pemohon') }}" required
                           class="w-full text-xs py-2 px-3 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Identitas (NISN / NIK / NIP / NPSN)</label>
                    <input type="text" name="nomor_identitas" value="{{ old('nomor_identitas') }}"
                           class="w-full text-xs py-2 px-3 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Satuan Pendidikan Asal (SMK) <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_sekolah_asal" value="{{ old('nama_sekolah_asal') }}" required
                           class="w-full text-xs py-2 px-3 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Konsentrasi Keahlian / Jurusan</label>
                    <input type="text" name="konsentrasi_keahlian" value="{{ old('konsentrasi_keahlian') }}"
                           class="w-full text-xs py-2 px-3 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                </div>
            </div>
        </div>

        <!-- 3. Detail Permohonan -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
            <h2 class="text-sm font-bold text-[#00264A] uppercase tracking-wider flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-800 inline-flex items-center justify-center text-xs font-black">3</span>
                Detail Administrasi & Perihal
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kabupaten / Kota Asal <span class="text-rose-500">*</span></label>
                    <select name="kabupaten_kota" required class="w-full text-xs py-2 px-3 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                        <option value="">-- Pilih Wilayah --</option>
                        <option value="Kota Ambon">Kota Ambon</option>
                        <option value="Maluku Tengah">Maluku Tengah</option>
                        <option value="Seram Bagian Barat">Seram Bagian Barat</option>
                        <option value="Seram Bagian Timur">Seram Bagian Timur</option>
                        <option value="Buru">Buru</option>
                        <option value="Buru Selatan">Buru Selatan</option>
                        <option value="Kepulauan Aru">Kepulauan Aru</option>
                        <option value="Maluku Tenggara">Maluku Tenggara</option>
                        <option value="Kepulauan Tanimbar">Kepulauan Tanimbar</option>
                        <option value="Maluku Barat Daya">Maluku Barat Daya</option>
                        <option value="Kota Tual">Kota Tual</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Pengajuan <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_pengajuan" value="{{ old('tanggal_pengajuan', date('Y-m-d')) }}" required
                           class="w-full text-xs py-2 px-3 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Perihal / Uraian Detail Permohonan <span class="text-rose-500">*</span></label>
                <textarea name="perihal_uraian" rows="3" required class="w-full text-xs py-2 px-3 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">{{ old('perihal_uraian') }}</textarea>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Validasi Dokumen SMK <span class="text-rose-500">*</span></label>
                <select name="status_layanan" class="w-full text-xs py-2 px-3 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                    <option value="DRAFT" selected>Draft / Pengajuan Awal</option>
                    <option value="PROSES_VERIFIKASI">Proses Verifikasi Bidang</option>
                    <option value="DISETUJUI">Disetujui / Rekomendasi Terbit</option>
                    <option value="DITOLAK">Ditolak / Perlu Perbaikan</option>
                </select>
            </div>
        </div>

        <!-- 4. Upload Berkas -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
            <h2 class="text-sm font-bold text-[#00264A] uppercase tracking-wider flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-800 inline-flex items-center justify-center text-xs font-black">4</span>
                Unggah Dokumen & Hasil
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Unggah Berkas Persyaratan (PDF / ZIP)</label>
                    <div class="border border-slate-200 rounded-lg p-3 bg-slate-50 relative">
                        <input type="file" name="file_permohonan" class="w-full text-xs text-slate-600 file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200">
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1">Gabungan berkas persyaratan (Maksimal 10 MB).</p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Unggah SK / Dokumen Terbit (Opsional)</label>
                    <div class="border border-slate-200 rounded-lg p-3 bg-slate-50 relative">
                        <input type="file" name="file_hasil_sk" class="w-full text-xs text-slate-600 file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200">
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1">Diunggah oleh verifikator jika dokumen disetujui (Maks 10 MB).</p>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end space-x-3 pt-2">
            <a href="{{ route('pendidikan.smk.layanan.index') }}" class="px-5 py-2 text-xs font-bold text-slate-500 hover:text-slate-700 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-[#00264A] hover:bg-[#003B70] text-white text-xs font-bold rounded-lg shadow-md transition-all">
                Simpan & Daftarkan Permohonan
            </button>
        </div>

    </form>
</div>

<script>
    document.getElementById('jenis_layanan').addEventListener('change', function() {
        const box = document.getElementById('syarat_box');
        if(this.value) {
            box.classList.remove('hidden');
            document.getElementById('syarat_title').innerText = "Standar Layanan " + this.value;
        } else {
            box.classList.add('hidden');
        }
    });
</script>
@endsection


