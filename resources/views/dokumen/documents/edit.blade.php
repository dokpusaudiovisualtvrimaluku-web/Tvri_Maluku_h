@extends('layouts.app')

@section('title', 'Edit Metadata Dokumen: ' . $document->title)

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 text-xs font-semibold text-rose-600 uppercase tracking-wider mb-1">
                <a href="{{ route('dokumen.documents.index') }}" class="hover:underline">Pangkalan Dokumen</a>
                <span>/</span>
                <a href="{{ route('dokumen.documents.show', $document->id) }}" class="hover:underline">Detail Dokumen</a>
                <span>/</span>
                <span>Edit Metadata</span>
            </div>
            <h1 class="text-2xl font-black text-[#00264A] tracking-tight">Edit Metadata Dokumen</h1>
            <p class="text-xs text-slate-500 mt-1">Perbarui nomor dokumen, klasifikasi, bidang, atau hak akses dokumen.</p>
        </div>

        <a href="{{ route('dokumen.documents.show', $document->id) }}" class="inline-flex items-center space-x-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Batal</span>
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

    <!-- Form -->
    <form action="{{ route('dokumen.documents.update', $document->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Metadata Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm space-y-4">
            <h2 class="text-sm font-bold text-[#00264A] pb-3 border-b border-slate-100">Metadata Dokumen</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Judul Dokumen -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul / Nama Dokumen <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $document->title) }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                </div>

                <!-- Nomor Dokumen / SK -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor SK / Surat / Registrasi</label>
                    <input type="text" name="document_number" value="{{ old('document_number', $document->document_number) }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-mono focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                    <input type="hidden" name="folder_id" value="{{ old('folder_id', $document->folder_id) }}">
                </div>

                <!-- Klasifikasi Kategori -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Klasifikasi Dokumen <span class="text-rose-500">*</span></label>
                    <select name="category" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category', $document->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Bidang / Unit Kerja -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Bidang / Unit Kerja <span class="text-rose-500">*</span></label>
                    <select name="division" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                        @foreach($divisions as $div)
                            <option value="{{ $div }}" {{ old('division', $document->division) == $div ? 'selected' : '' }}>{{ $div }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Dokumen -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Dokumen / Ditetapkan</label>
                    <input type="date" name="document_date" value="{{ old('document_date', $document->document_date ? $document->document_date->format('Y-m-d') : '') }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                </div>

                <!-- Tahun Anggaran / Data -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tahun Anggaran / Data</label>
                    <input type="number" name="year" value="{{ old('year', $document->year) }}" min="1990" max="2050" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                </div>

                <!-- Tingkat Kerahasiaan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tingkat Kerahasiaan (Access Level) <span class="text-rose-500">*</span></label>
                    <select name="confidentiality" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                        <option value="INTERNAL" {{ old('confidentiality', $document->confidentiality) == 'INTERNAL' ? 'selected' : '' }}>Internal Dinas (Akses Pegawai TVRI Maluku)</option>
                        <option value="PUBLIK" {{ old('confidentiality', $document->confidentiality) == 'PUBLIK' ? 'selected' : '' }}>Publik (Dapat diakses masyarakat umum)</option>
                        <option value="TERBATAS" {{ old('confidentiality', $document->confidentiality) == 'TERBATAS' ? 'selected' : '' }}>Terbatas (Pejabat Eselon & Pimpinan)</option>
                        <option value="RAHASIA" {{ old('confidentiality', $document->confidentiality) == 'RAHASIA' ? 'selected' : '' }}>Rahasia & Khusus (Kepala Stasiun & Super Admin)</option>
                    </select>
                </div>

                <!-- Status Berkas -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status Dokumen <span class="text-rose-500">*</span></label>
                    <select name="status" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                        <option value="AKTIF" {{ old('status', $document->status) == 'AKTIF' ? 'selected' : '' }}>AKTIF (Berlaku Aktif)</option>
                        <option value="INAKTIF" {{ old('status', $document->status) == 'INAKTIF' ? 'selected' : '' }}>INAKTIF (Tidak Aktif)</option>
                        <option value="RETENSI" {{ old('status', $document->status) == 'RETENSI' ? 'selected' : '' }}>RETENSI (Masa Simpan Arsip)</option>
                        <option value="DIARSIPKAN" {{ old('status', $document->status) == 'DIARSIPKAN' ? 'selected' : '' }}>DIARSIPKAN (Arsip Permanen)</option>
                    </select>
                </div>

                <!-- Deskripsi / Ringkasan -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi / Ringkasan Naskah</label>
                    <textarea name="description" rows="3" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">{{ old('description', $document->description) }}</textarea>
                </div>
            </div>
        </div>

        <input type="hidden" name="school_id" value="{{ $document->school_id }}">
        <input type="hidden" name="culture_id" value="{{ $document->culture_id }}">
        <input type="hidden" name="tags" value="{{ $document->tags }}">

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end space-x-3 pt-2">
            <a href="{{ route('dokumen.documents.show', $document->id) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-[#003B70] hover:bg-[#00264A] text-white text-xs font-bold rounded-xl shadow-lg hover:shadow-blue-900/30 transition-all flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Perbarui Metadata</span>
            </button>
        </div>

    </form>

</div>
@endsection
