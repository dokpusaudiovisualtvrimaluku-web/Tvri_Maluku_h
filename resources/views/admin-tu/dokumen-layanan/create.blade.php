@extends('layouts.app')
@section('title', 'Upload Dokumen Layanan')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin-tu.dokumen-layanan.index') }}" class="inline-flex items-center text-xs font-bold text-slate-400 hover:text-blue-600 mb-2 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar
            </a>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Upload Dokumen Baru</h1>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin-tu.dokumen-layanan.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        @csrf
        
        <div class="p-6 md:p-8 space-y-6">
            @if($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium space-y-1">
                    <p class="font-bold">Terdapat kesalahan:</p>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-6">
                <!-- Nama Dokumen -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama / Judul Dokumen <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_dokumen" value="{{ old('nama_dokumen') }}" required placeholder="Contoh: SK Kenaikan Pangkat Golongan III/b" 
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70] focus:border-transparent transition-all">
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kategori Dokumen <span class="text-rose-500">*</span></label>
                    <select name="kategori" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70] focus:border-transparent transition-all">
                        <option value="">Pilih Kategori...</option>
                        @foreach($categories as $kat)
                            <option value="{{ $kat }}" {{ old('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- File Upload -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">File Dokumen <span class="text-rose-500">*</span></label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 border-dashed rounded-2xl bg-slate-50 hover:bg-slate-100 transition-colors relative cursor-pointer" onclick="document.getElementById('file-upload').click()">
                        <div class="space-y-2 text-center">
                            <svg class="mx-auto h-10 w-10 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-slate-600 justify-center">
                                <span class="relative rounded-md font-bold text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Pilih file</span>
                                    <input id="file-upload" name="file" type="file" class="sr-only" required onchange="document.getElementById('file-name').textContent = this.files[0] ? this.files[0].name : 'Belum ada file dipilih'">
                                </span>
                                <p class="pl-1">atau tarik dan lepas</p>
                            </div>
                            <p class="text-xs text-slate-500">PDF, DOC/X, XLS/X, JPG, PNG (Maks 10MB)</p>
                            <p id="file-name" class="text-xs font-bold text-[#003B70] mt-2"></p>
                        </div>
                    </div>
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Keterangan / Catatan Tambahan</label>
                    <textarea name="keterangan" rows="3" placeholder="Opsional..." 
                              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70] focus:border-transparent transition-all">{{ old('keterangan') }}</textarea>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end space-x-3">
            <a href="{{ route('admin-tu.dokumen-layanan.index') }}" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 bg-[#005B9F] text-white text-xs font-bold rounded-xl hover:bg-[#003B70] transition-colors shadow-sm shadow-[#005B9F]/30 flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                <span>Upload Dokumen</span>
            </button>
        </div>
    </form>
</div>
@endsection
