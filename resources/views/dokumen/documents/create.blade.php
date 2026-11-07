@extends('layouts.app')

@section('title', 'Unggah Dokumen & Arsip Digital - TVRI Maluku')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 text-xs font-semibold text-rose-600 uppercase tracking-wider mb-1">
                <a href="{{ route('dokumen.documents.index') }}" class="hover:underline">Pangkalan Dokumen</a>
                <span>/</span>
                <span>Unggah Naskah Baru</span>
            </div>
            <h1 class="text-2xl font-black text-[#00264A] tracking-tight">Unggah Dokumen & Arsip Digital</h1>
            <p class="text-xs text-slate-500 mt-1">Lengkapi metadata naskah dinas, SK penetapan, atau berkas digital resmi untuk repositori terpusat.</p>
        </div>

        <a href="{{ route('dokumen.documents.index') }}" class="inline-flex items-center space-x-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
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

    <!-- Upload Form -->
    <form action="{{ route('dokumen.documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Card 1: File Uploader Zone -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm space-y-4">
            <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                <div class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold">1</div>
                <h2 class="text-sm font-bold text-[#00264A]">Pilih Berkas Naskah Digital</h2>
            </div>

            <div class="border-2 border-dashed border-slate-300 hover:border-[#003B70] rounded-2xl p-8 text-center transition-colors bg-slate-50/50">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-[#003B70] mx-auto flex items-center justify-center mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                </div>
                <label class="block text-xs font-bold text-slate-700 mb-1 cursor-pointer">
                    <span class="text-[#003B70] hover:underline">Klik untuk memilih berkas</span> atau seret file ke area ini
                </label>
                <p class="text-[11px] text-slate-400 mb-4">Mendukung berkas: PDF, DOCX, XLSX, PPTX, JPG, PNG, MP4, MP3, ZIP (Maksimal 50 MB)</p>
                
                <input type="file" name="file" required class="mx-auto block text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#003B70] file:text-white hover:file:bg-[#00264A] cursor-pointer">
            </div>
        </div>

        <!-- Card 2: Metadata Dokumen -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm space-y-4">
            <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center font-bold">2</div>
                <h2 class="text-sm font-bold text-[#00264A]">Metadata & Klasifikasi Naskah</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Judul Dokumen -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul / Nama Dokumen <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: SK Penetapan Satuan Pendidikan Penerima BOS Kinerja 2026" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                </div>

                <!-- Nomor Dokumen / SK -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor SK / Surat / Registrasi</label>
                    <input type="text" name="document_number" value="{{ old('document_number') }}" placeholder="Contoh: SK.420/128/TVRI MALUKU/2026" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-mono focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                    @php
                        $defaultFolderId = request('folder_id');
                        if (!$defaultFolderId && auth()->check()) {
                            $userMap = [
                                'admin.tu' => 'Tata Usaha',
                                'tu' => 'Tata Usaha',
                                'berita' => 'Berita',
                                'umum' => 'Umum',
                                'program' => 'Program',
                                'konten.media.baru' => 'KBM',
                                'promo' => 'promo',
                                'pengembangan.usaha' => 'Pengembangan Usaha',
                                'teknik' => 'Teknik',
                                'teknik.transmisi' => 'Teknik Transmisi',
                                'transmisi' => 'Teknik Transmisi',
                                'keuangan' => 'Keuangan',
                                'kasubag.keuangan' => 'Keuangan',
                            ];
                            $targetName = $userMap[auth()->user()->username] ?? null;
                            if ($targetName) {
                                $matchedFolder = $folders->firstWhere('name', $targetName);
                                if ($matchedFolder) {
                                    $defaultFolderId = $matchedFolder->id;
                                }
                            }
                        }
                    @endphp
                    <input type="hidden" name="folder_id" value="{{ old('folder_id', $defaultFolderId) }}">
                </div>

                <!-- Klasifikasi Kategori -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Klasifikasi Dokumen <span class="text-rose-500">*</span></label>
                    <select name="category" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category', request('category', (auth()->check() && auth()->user()->username === 'keuangan' ? 'RKA & DPA Perencanaan' : ''))) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Bidang / Unit Kerja -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Bidang / Unit Kerja <span class="text-rose-500">*</span></label>
                    @php
                        $defaultDivision = request('division');
                        if (!$defaultDivision && auth()->check()) {
                            $divMap = [
                                'admin.tu' => 'Tata Usaha',
                                'berita' => 'Berita',
                                'program' => 'Program',
                                'umum' => 'Umum',
                                'teknik' => 'Teknik Studio',
                                'teknik.transmisi' => 'Teknik Transmisi',
                                'transmisi' => 'Teknik Transmisi',
                                'promo' => 'Promo',
                                'konten.media.baru' => 'KBM',
                                'pengembangan.usaha' => 'Pengembangan Usaha',
                                'keuangan' => 'Keuangan',
                                'kasubag.keuangan' => 'Keuangan',
                            ];
                            $defaultDivision = $divMap[auth()->user()->username] ?? null;
                        }
                    @endphp
                    <select name="division" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                        @foreach($divisions as $div)
                            <option value="{{ $div }}" {{ old('division', $defaultDivision) == $div ? 'selected' : '' }}>{{ $div }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Dokumen -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Dokumen / Ditetapkan</label>
                    <input type="date" name="document_date" value="{{ old('document_date', date('Y-m-d')) }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                </div>

                <!-- Tahun Anggaran / Data -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tahun Anggaran / Data</label>
                    <input type="number" name="year" value="{{ old('year', date('Y')) }}" min="1990" max="2050" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                </div>

                <!-- Tingkat Kerahasiaan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tingkat Kerahasiaan (Access Level) <span class="text-rose-500">*</span></label>
                    <select name="confidentiality" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                        <option value="INTERNAL" {{ old('confidentiality') == 'INTERNAL' ? 'selected' : '' }}>Internal Dinas (Akses Pegawai TVRI Maluku)</option>
                        <option value="PUBLIK" {{ old('confidentiality') == 'PUBLIK' ? 'selected' : '' }}>Publik (Dapat diakses masyarakat umum)</option>
                        <option value="TERBATAS" {{ old('confidentiality') == 'TERBATAS' ? 'selected' : '' }}>Terbatas (Pejabat Eselon & Pimpinan)</option>
                        <option value="RAHASIA" {{ old('confidentiality') == 'RAHASIA' ? 'selected' : '' }}>Rahasia & Khusus (Kepala Stasiun & Super Admin)</option>
                    </select>
                </div>

                <!-- Status Berkas -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status Dokumen <span class="text-rose-500">*</span></label>
                    <select name="status" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                        <option value="AKTIF" {{ old('status') == 'AKTIF' ? 'selected' : '' }}>AKTIF (Berlaku Aktif)</option>
                        <option value="INAKTIF" {{ old('status') == 'INAKTIF' ? 'selected' : '' }}>INAKTIF (Tidak Aktif)</option>
                        <option value="RETENSI" {{ old('status') == 'RETENSI' ? 'selected' : '' }}>RETENSI (Masa Simpan Arsip)</option>
                        <option value="DIARSIPKAN" {{ old('status') == 'DIARSIPKAN' ? 'selected' : '' }}>DIARSIPKAN (Arsip Permanen)</option>
                    </select>
                </div>

                <!-- Deskripsi / Ringkasan -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi / Ringkasan Naskah</label>
                    <textarea name="description" rows="3" placeholder="Tuliskan ringkasan isi naskah, maksud penetapan, atau poin-poin penting keputusan..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end space-x-3 pt-2">
            <a href="{{ route('dokumen.documents.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-[#003B70] hover:bg-[#00264A] text-white text-xs font-bold rounded-xl shadow-lg hover:shadow-blue-900/30 transition-all flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                <span>Simpan & Terbitkan Dokumen Digital</span>
            </button>
        </div>

    </form>

</div>
@endsection
