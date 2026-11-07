@extends('layouts.app')

@section('title', 'Detail Dokumen: ' . $document->title)

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'preview' }">

    <!-- Top Navigation Breadcrumbs & Back -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-xs font-semibold text-slate-500">
            <a href="{{ route('dokumen.dashboard') }}" class="hover:text-blue-600">Dokumen & Arsip</a>
            <span>/</span>
            <a href="{{ route('dokumen.documents.index') }}" class="hover:text-blue-600">Pangkalan Dokumen</a>
            <span>/</span>
            <span class="text-slate-800 truncate max-w-xs">{{ $document->title }}</span>
        </div>

        <div class="flex items-center space-x-2">
            <a href="{{ route('dokumen.documents.index') }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Kembali</span>
            </a>
            <a href="{{ route('dokumen.documents.edit', $document->id) }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <span>Edit Metadata</span>
            </a>
            <a href="{{ route('dokumen.documents.download', $document->id) }}" class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                <span>Unduh Berkas ({{ $document->current_version }})</span>
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
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm relative overflow-hidden">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
            <div class="space-y-3 max-w-3xl">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-1 text-xs font-black rounded-lg border {{ $document->format_badge_color }}">
                        {{ $document->file_type }}
                    </span>
                    @php $conf = \App\Models\SimandatDocument::CONFIDENTIALITY_LIST[$document->confidentiality] ?? ['label' => $document->confidentiality, 'badge' => 'bg-gray-100 text-gray-800 border-gray-300']; @endphp
                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg border {{ $conf['badge'] }}">
                        Akses: {{ $conf['label'] }}
                    </span>
                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-blue-50 text-blue-800 border border-blue-200">
                        {{ $document->category }}
                    </span>
                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200">
                        Versi Aktif: {{ $document->current_version }}
                    </span>
                </div>

                <div>
                    <span class="font-mono text-xs font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded border border-slate-200 inline-block mb-1.5">
                        {{ $document->document_number ?: 'NON-NOMOR DOKUMEN' }}
                    </span>
                    <h1 class="text-xl lg:text-2xl font-black text-[#00264A] leading-snug">
                        {{ $document->title }}
                    </h1>
                </div>

                <p class="text-xs text-slate-600 leading-relaxed">
                    {{ $document->description ?: 'Tidak ada catatan deskripsi tambahan untuk naskah ini.' }}
                </p>

                <div class="flex flex-wrap items-center gap-y-2 gap-x-4 text-xs text-slate-500 pt-2 border-t border-slate-100">
                    <div>
                        <span class="text-slate-400">Unit / Bidang:</span>
                        <strong class="text-slate-700 ml-1">{{ $document->division }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400">Folder:</span>
                        <strong class="text-slate-700 ml-1">📁 {{ $document->folder ? $document->folder->name : 'Umum' }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400">Tanggal Dokumen:</span>
                        <strong class="text-slate-700 ml-1">{{ $document->document_date ? $document->document_date->format('d F Y') : '-' }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400">Ukuran Berkas:</span>
                        <strong class="text-slate-700 ml-1">{{ $document->file_size }}</strong>
                    </div>
                </div>
            </div>

            <!-- Mini QR Preview on Right -->
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 flex flex-col items-center text-center space-y-2 flex-shrink-0 w-48">
                <img src="{{ $document->qr_image_url }}" alt="QR Code Verifikasi" class="w-28 h-28 rounded-xl bg-white p-1 border border-slate-200 shadow-sm">
                <div class="text-[10px] text-slate-500">
                    <p class="font-bold text-slate-700">QR Verifikasi Dokumen</p>
                    <p class="truncate font-mono text-[9px] text-slate-400 mt-0.5">{{ Str::limit($document->qr_token, 16) }}</p>
                </div>
                <a href="{{ $document->verification_url }}" target="_blank" class="w-full py-1.5 px-2 bg-[#003B70] hover:bg-[#00264A] text-white text-[10px] font-bold rounded-lg transition-colors">
                    Validasi Keaslian ↗
                </a>
            </div>
        </div>
    </div>

    <!-- Interactive Tabs Navigation -->
    <div class="border-b border-slate-200">
        <nav class="flex space-x-6 text-xs font-bold">
            <button @click="activeTab = 'preview'" 
                    :class="activeTab === 'preview' ? 'border-[#003B70] text-[#003B70] border-b-2 pb-3' : 'text-slate-500 hover:text-slate-800 pb-3'">
                📄 Pratinjau Dokumen (In-Browser Preview)
            </button>
            <button @click="activeTab = 'versions'" 
                    :class="activeTab === 'versions' ? 'border-[#003B70] text-[#003B70] border-b-2 pb-3' : 'text-slate-500 hover:text-slate-800 pb-3'">
                🕒 Riwayat Versi & Unggah Revisi ({{ $document->versions->count() }})
            </button>
            <button @click="activeTab = 'metadata'" 
                    :class="activeTab === 'metadata' ? 'border-[#003B70] text-[#003B70] border-b-2 pb-3' : 'text-slate-500 hover:text-slate-800 pb-3'">
                📑 Metadata Dokumen
            </button>
            <button @click="activeTab = 'qr'" 
                    :class="activeTab === 'qr' ? 'border-[#003B70] text-[#003B70] border-b-2 pb-3' : 'text-slate-500 hover:text-slate-800 pb-3'">
                🛡️ QR Code & Sertifikat Validasi
            </button>
        </nav>
    </div>

    <!-- TAB CONTENT 1: IN-BROWSER PREVIEW -->
    <div x-show="activeTab === 'preview'" class="space-y-4">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <h3 class="text-sm font-bold text-slate-800">Pratinjau Berkas: {{ $document->file_name }}</h3>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('dokumen.documents.download', $document->id) }}" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition-colors flex items-center space-x-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Unduh File</span>
                    </a>
                </div>
            </div>

            <!-- Conditional Preview Engine -->
            <div class="bg-slate-900 rounded-xl overflow-hidden min-h-[500px] flex items-center justify-center p-4">
                @if(in_array(strtoupper($document->file_type), ['PDF']))
                    <iframe src="{{ route('dokumen.documents.preview', $document->id) }}" class="w-full h-[650px] rounded-lg border-0 bg-white"></iframe>
                @elseif(in_array(strtoupper($document->file_type), ['JPG', 'JPEG', 'PNG', 'WEBP']))
                    <img src="{{ $document->file_url ?: route('dokumen.documents.preview', $document->id) }}" alt="Preview Foto" class="max-h-[600px] max-w-full rounded-lg object-contain shadow-2xl">
                @elseif(in_array(strtoupper($document->file_type), ['MP3', 'WAV', 'OGG']))
                    <div class="text-center p-8 bg-slate-800 rounded-2xl w-full max-w-md space-y-4">
                        <div class="w-16 h-16 rounded-full bg-pink-500/20 text-pink-400 mx-auto flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12 0c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                        </div>
                        <h4 class="text-white font-bold text-sm">{{ $document->title }}</h4>
                        <audio controls class="w-full mt-4">
                            <source src="{{ route('dokumen.documents.preview', $document->id) }}" type="audio/mpeg">
                            Browser Anda tidak mendukung pemutar audio.
                        </audio>
                    </div>
                @elseif(in_array(strtoupper($document->file_type), ['MP4', 'WEBM', 'MOV']))
                    <div class="w-full max-w-3xl">
                        <video controls class="w-full max-h-[500px] rounded-xl shadow-2xl">
                            <source src="{{ route('dokumen.documents.preview', $document->id) }}" type="video/mp4">
                            Browser Anda tidak mendukung pemutar video.
                        </video>
                    </div>
                @else
                    <!-- Generic Document Visualizer -->
                    <div class="bg-white rounded-2xl p-8 max-w-xl text-center space-y-4 shadow-2xl">
                        <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-700 mx-auto flex items-center justify-center">
                            <span class="text-xl font-black">{{ $document->file_type }}</span>
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-slate-800">{{ $document->file_name }}</h4>
                            <p class="text-xs text-slate-500 mt-1">Berkas format {{ $document->file_type }} ({{ $document->file_size }}) siap diunduh untuk dibuka pada perangkat lokal.</p>
                        </div>
                        <div class="pt-2">
                            <a href="{{ route('dokumen.documents.download', $document->id) }}" class="inline-flex items-center space-x-2 px-5 py-2.5 bg-[#003B70] hover:bg-[#00264A] text-white text-xs font-bold rounded-xl shadow-md transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                <span>Unduh {{ $document->file_name }}</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- TAB CONTENT 2: VERSION HISTORY & UPLOAD NEW VERSION -->
    <div x-show="activeTab === 'versions'" class="space-y-6">
        
        <!-- Form Unggah Versi Baru -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <div class="flex items-center space-x-3 mb-4 pb-3 border-b border-slate-100">
                <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[#00264A]">Unggah Revisi Versi Baru</h3>
                    <p class="text-xs text-slate-500">Unggah berkas pembaharuan naskah tanpa menghapus arsip riwayat terdahulu.</p>
                </div>
            </div>

            <form action="{{ route('dokumen.documents.version.store', $document->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Versi Baru</label>
                        @php
                            $nextVerNum = 'v' . ($document->versions->count() + 1) . '.0';
                        @endphp
                        <input type="text" name="version_number" value="{{ $nextVerNum }}" placeholder="Contoh: v1.1 atau v2.0" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Berkas Revisi Baru <span class="text-rose-500">*</span></label>
                        <input type="file" name="file" required class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Revisi / Changelog <span class="text-rose-500">*</span></label>
                    <textarea name="changelog_note" rows="2" required placeholder="Jelaskan perubahan atau penyempurnaan pada naskah versi ini..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]"></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-5 py-2.5 bg-[#003B70] hover:bg-[#00264A] text-white text-xs font-bold rounded-xl shadow-md transition-colors flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        <span>Simpan Versi Baru</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Timeline Riwayat Versi -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="text-sm font-bold text-[#00264A] mb-4 pb-2 border-b border-slate-100">Histori Seluruh Versi Dokumen</h3>

            <div class="space-y-4">
                @foreach($document->versions as $idx => $ver)
                    <div class="flex items-start space-x-4 p-4 rounded-xl {{ $ver->version_number === $document->current_version ? 'bg-blue-50/60 border border-blue-200' : 'bg-slate-50 border border-slate-100' }}">
                        <div class="flex-shrink-0 mt-1">
                            <span class="px-2.5 py-1 text-xs font-black rounded-lg {{ $ver->version_number === $document->current_version ? 'bg-[#003B70] text-white' : 'bg-slate-200 text-slate-700' }}">
                                {{ $ver->version_number }}
                            </span>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                <h4 class="text-xs font-bold text-slate-800 flex items-center space-x-2">
                                    <span>{{ $ver->file_name }}</span>
                                    @if($ver->version_number === $document->current_version)
                                        <span class="px-2 py-0.5 text-[9px] font-bold bg-emerald-100 text-emerald-800 rounded">Versi Aktif Saat Ini</span>
                                    @endif
                                </h4>
                                <span class="text-[11px] text-slate-400">{{ $ver->created_at->format('d M Y, H:i') }} WIT</span>
                            </div>

                            <p class="text-xs text-slate-600 mt-1.5 bg-white p-2.5 rounded-lg border border-slate-100">
                                <strong class="text-slate-500 text-[11px]">Catatan Perubahan:</strong> {{ $ver->changelog_note ?: 'Tidak ada catatan.' }}
                            </p>

                            <div class="flex items-center justify-between text-[11px] text-slate-400 mt-2">
                                <div>
                                    <span>Format: <strong class="text-slate-600">{{ $ver->file_type }}</strong></span>
                                    <span class="mx-1">•</span>
                                    <span>Ukuran: <strong class="text-slate-600">{{ $ver->file_size }}</strong></span>
                                    <span class="mx-1">•</span>
                                    <span>Pengunggah: <strong class="text-slate-600">{{ $ver->uploader_name }}</strong></span>
                                </div>

                                <a href="{{ route('dokumen.documents.version.download', $ver->id) }}" class="inline-flex items-center space-x-1 text-xs font-bold text-blue-600 hover:text-blue-800">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    <span>Unduh Versi Ini</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- TAB CONTENT 3: METADATA & DATA RELATION -->
    <div x-show="activeTab === 'metadata'" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Metadata Kearsipan Detail -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-4">
                <h3 class="text-sm font-bold text-[#00264A] pb-3 border-b border-slate-100">Metadata Kearsipan Standar</h3>
                
                <dl class="divide-y divide-slate-100 text-xs">
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-500">Nomor Registrasi / SK</dt>
                        <dd class="font-mono font-bold text-slate-800">{{ $document->document_number ?: '-' }}</dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-500">Klasifikasi Berkas</dt>
                        <dd class="font-bold text-blue-700">{{ $document->category }}</dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-500">Unit Kerja / Bidang</dt>
                        <dd class="font-semibold text-slate-800">{{ $document->division }}</dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-500">Tahun Anggaran / Data</dt>
                        <dd class="font-bold text-slate-800">Tahun {{ $document->year ?: '-' }}</dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-500">Tingkat Kerahasiaan</dt>
                        <dd><span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $conf['badge'] }}">{{ $conf['label'] }}</span></dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-500">Status Retensi</dt>
                        <dd><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">{{ $document->status }}</span></dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-500">Pengunggah Awal</dt>
                        <dd class="font-semibold text-slate-800">{{ $document->uploader_name }}</dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-500">Waktu Ditambahkan</dt>
                        <dd class="text-slate-600">{{ $document->created_at->format('d/m/Y H:i') }} WIT</dd>
                    </div>
                </dl>
            </div>

        </div>
    </div>

    <!-- TAB CONTENT 4: QR CODE & OFFICIAL CERTIFICATE -->
    <div x-show="activeTab === 'qr'" class="space-y-6">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8 max-w-2xl mx-auto text-center space-y-6">
            
            <!-- Seal & Header -->
            <div class="space-y-2">
                <div class="w-16 h-16 rounded-2xl bg-[#003B70] text-white p-2 mx-auto flex items-center justify-center shadow-lg">
                    <img src="{{ asset('images/logo_tvri.png') }}" alt="Logo TVRI" class="w-full h-full object-contain rounded-xl" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Coat_of_arms_of_Maluku.svg/1200px-Coat_of_arms_of_Maluku.svg.png'">
                </div>
                <h2 class="text-base font-black text-[#00264A] uppercase tracking-wider">
                    Pemerintah Provinsi Maluku<br>
                    <span class="text-xs text-blue-700">TVRI Maluku</span>
                </h2>
                <p class="text-xs text-slate-500">Sertifikat Integritas & Verifikasi Dokumen Digital Resmi</p>
            </div>

            <!-- QR Code Canvas -->
            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200 inline-block">
                <img src="{{ $document->qr_image_url }}" alt="QR Code Keaslian" class="w-48 h-48 mx-auto rounded-xl bg-white p-2 border border-slate-200 shadow-inner">
                <p class="font-mono text-xs font-bold text-slate-600 mt-3">{{ $document->qr_token }}</p>
                <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded-full mt-2">
                    ✓ TERVERIFIKASI & TERSERTIFIKASI SIMANDAT
                </span>
            </div>

            <!-- Meta verification info -->
            <div class="text-left bg-slate-50 p-4 rounded-xl border border-slate-100 text-xs space-y-1.5 text-slate-600">
                <p><strong>Nomor Surat/SK:</strong> {{ $document->document_number ?: 'NON-NOMOR' }}</p>
                <p><strong>Judul Naskah:</strong> {{ $document->title }}</p>
                <p><strong>Tautan Validasi:</strong> <a href="{{ $document->verification_url }}" target="_blank" class="text-blue-600 hover:underline break-all">{{ $document->verification_url }}</a></p>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-center space-x-3">
                <a href="{{ $document->verification_url }}" target="_blank" class="px-5 py-2.5 bg-[#003B70] hover:bg-[#00264A] text-white text-xs font-bold rounded-xl shadow-md transition-colors">
                    Buka Portal Verifikasi Publik ↗
                </a>
                <button onclick="window.print()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                    Cetak Sertifikat QR
                </button>
            </div>

        </div>
    </div>

</div>
@endsection
