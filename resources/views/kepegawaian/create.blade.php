@extends('layouts.app')

@section('title', 'Buat Permohonan Layanan Kepegawaian & Umum - TVRI Maluku')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 text-xs font-semibold text-amber-600 uppercase tracking-wider mb-1">
                <a href="{{ route('kepegawaian.layanan.index') }}" class="hover:underline">Subbag Kepegawaian & Umum</a>
                <span>/</span>
                <span>Pendaftaran Layanan</span>
            </div>
            <h1 class="text-2xl font-black text-[#00264A] tracking-tight">Formulir Layanan Kepegawaian & Naskah Dinas</h1>
            <p class="text-xs text-slate-500 mt-1">Layanan pendaftaran usulan kenaikan pangkat, KGB, cuti, izin belajar, pensiun, PKL, izin penelitian, dan persuratan dinas.</p>
        </div>

        <a href="{{ route('kepegawaian.layanan.index') }}" class="inline-flex items-center space-x-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Error Validation Alert -->
    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
            <div class="font-bold flex items-center space-x-1.5">
                <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                <span>Terdapat kesalahan pengisian formulir:</span>
            </div>
            <ul class="list-disc list-inside pl-2 space-y-0.5 text-[11px]">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('kepegawaian.layanan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Card 1: Jenis Layanan -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm space-y-4">
            <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center font-bold">1</div>
                <h2 class="text-sm font-bold text-[#00264A]">Pilih Jenis Layanan / Dokumen</h2>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Pilih 1 dari 10 Layanan Sub Bagian Kepegawaian & Umum <span class="text-rose-500">*</span></label>
                <select name="jenis_layanan" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-bold focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                    @foreach($allJenisLayanan as $namaLayanan => $cfg)
                        <option value="{{ $namaLayanan }}" {{ old('jenis_layanan', $selectedService) == $namaLayanan ? 'selected' : '' }}>
                            {{ $namaLayanan }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Card 2: Identitas Pemohon -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm space-y-4">
            <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center font-bold">2</div>
                <h2 class="text-sm font-bold text-[#00264A]">Identitas Pemohon / Pengusul</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nama Pemohon -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap & Gelar Pemohon <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_pemohon" value="{{ old('nama_pemohon') }}" required placeholder="Contoh: Drs. Jacobus Pattipeilohy, M.Pd" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                </div>

                <!-- NIP / NIK / NISN -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">NIP / NIK / NIM / NISN</label>
                    <input type="text" name="nip_nik_nisn" value="{{ old('nip_nik_nisn') }}" placeholder="Contoh: 198509202010011018 atau NIM Mahasiswa" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-mono focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                </div>

                <!-- Pangkat & Golongan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Pangkat & Golongan Ruang (Jika ASN)</label>
                    <input type="text" name="pangkat_golongan" value="{{ old('pangkat_golongan') }}" placeholder="Contoh: Penata Tk. I (III/d) / Pembina (IV/a)" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                </div>

                <!-- Unit Kerja / Asal Sekolah -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jabatan & Unit Kerja / Sekolah / Kampus <span class="text-rose-500">*</span></label>
                    <input type="text" name="jabatan_unit_kerja" value="{{ old('jabatan_unit_kerja') }}" required placeholder="Contoh: Guru Madya SMAN 1 Siwalima Ambon / Mahasiswa Unpatti" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                </div>

                <!-- Kabupaten / Kota -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kabupaten / Kota <span class="text-rose-500">*</span></label>
                    <select name="kabupaten_kota" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                        @foreach($kabupatenOptions as $kab)
                            <option value="{{ $kab }}" {{ old('kabupaten_kota') == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Permohonan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Permohonan / Didaftarkan <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_permohonan" value="{{ old('tanggal_permohonan', date('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                </div>

                <!-- Deskripsi Perihal -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Perihal & Rincian Keterangan Permohonan</label>
                    <textarea name="perihal_deskripsi" rows="3" placeholder="Jelaskan maksud permohonan, alasan cuti, judul penelitian, atau catatan tambahan usulan..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">{{ old('perihal_deskripsi') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Card 3: Berkas Persyaratan -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm space-y-4">
            <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">3</div>
                <h2 class="text-sm font-bold text-[#00264A]">Unggah Berkas Persyaratan (PDF / ZIP)</h2>
            </div>

            <div class="border-2 border-dashed border-slate-300 hover:border-[#003B70] rounded-2xl p-6 text-center transition-colors bg-slate-50/50">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 mx-auto flex items-center justify-center mb-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                </div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    Unggah Berkas Kelengkapan Syarat Layanan
                </label>
                <p class="text-[11px] text-slate-400 mb-3">Satukan berkas SK terakhir, formulir pengajuan, proposal, atau surat pengantar dalam 1 file PDF atau ZIP (Maks 25 MB).</p>
                <input type="file" name="file_permohonan" class="mx-auto block text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#003B70] file:text-white hover:file:bg-[#00264A] cursor-pointer">
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end space-x-3 pt-2">
            <a href="{{ route('kepegawaian.layanan.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-[#003B70] hover:bg-[#00264A] text-white text-xs font-bold rounded-xl shadow-lg hover:shadow-blue-900/30 transition-all flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Daftarkan Permohonan Layanan</span>
            </button>
        </div>

    </form>

</div>
@endsection
