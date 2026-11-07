@extends('layouts.app')

@section('title', 'Folder Explorer - Repositori Digital TVRI Maluku')

@section('content')
<div class="space-y-6" x-data="{ showNewFolderModal: false, showEditFolderModal: false, editFolder: { id: null, name: '', code: '', color: 'blue', description: '' } }">

    <!-- Header & Breadcrumbs Explorer Bar -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <!-- Breadcrumbs Navigation -->
            <nav class="flex items-center space-x-2 text-xs font-bold text-slate-500 mb-1.5 overflow-x-auto py-1">
                <a href="{{ route('dokumen.folders.index') }}" class="flex items-center space-x-1 hover:text-blue-700 {{ !$currentFolder ? 'text-[#003B70]' : '' }}">
                    <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path></svg>
                    <span>Repositori Utama (Root)</span>
                </a>
                @foreach($breadcrumbs as $b)
                    <span class="text-slate-300">/</span>
                    <a href="{{ route('dokumen.folders.index', ['folder_id' => $b->id]) }}" class="hover:text-blue-700 {{ $currentFolder && $currentFolder->id == $b->id ? 'text-[#003B70] font-black' : '' }}">
                        {{ $b->name }}
                    </a>
                @endforeach
            </nav>

            <h1 class="text-2xl font-black text-[#00264A] tracking-tight flex items-center space-x-2">
                <span>📁</span>
                <span>{{ $currentFolder ? $currentFolder->name : 'Folder Explorer & Direktori Berkas' }}</span>
            </h1>
            @if($currentFolder && $currentFolder->description)
                <p class="text-xs text-slate-500 mt-0.5">{{ $currentFolder->description }}</p>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center space-x-2.5 flex-shrink-0">
            <button @click="showNewFolderModal = true" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold rounded-xl border border-amber-200 transition-colors">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                <span>+ Folder Baru</span>
            </button>
            <a href="{{ route('dokumen.documents.create', $currentFolder ? ['folder_id' => $currentFolder->id] : []) }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-[#003B70] hover:bg-[#00264A] text-white text-xs font-bold rounded-xl shadow-md transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Unggah ke Folder Ini</span>
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

    <!-- 1. Grid of Folders / Subfolders -->
    @if($subfolders->count() > 0)
        <div class="space-y-3">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">
                {{ $currentFolder ? 'Sub-Folder di Direktori Ini' : 'Kategori Folder Utama' }} ({{ $subfolders->count() }})
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($subfolders as $folder)
                    <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all group flex flex-col justify-between">
                        <div class="flex items-start justify-between">
                            <a href="{{ route('dokumen.folders.index', ['folder_id' => $folder->id]) }}" class="flex items-center space-x-3 min-w-0">
                                <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0 group-hover:bg-amber-100 transition-colors">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path></svg>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-xs font-bold text-slate-800 group-hover:text-blue-700 truncate leading-tight">
                                        {{ $folder->name }}
                                    </h3>
                                    <p class="text-[10px] font-medium text-slate-400 mt-0.5">
                                        {{ $folder->documents->count() }} berkas • {{ $folder->children->count() }} subfolder
                                    </p>
                                </div>
                            </a>

                            @if(auth()->user()->hasRole('ADMIN'))
                                <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button @click="editFolder = { id: {{ $folder->id }}, name: '{{ addslashes($folder->name) }}', code: '{{ addslashes($folder->code) }}', color: '{{ $folder->color }}', description: '{{ addslashes($folder->description) }}' }; showEditFolderModal = true" class="p-1 text-slate-400 hover:text-blue-600 rounded">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <form action="{{ route('dokumen.folders.destroy', $folder->id) }}" method="POST" onsubmit="return confirm('Hapus folder ini? Dokumen di dalamnya akan dipindahkan ke Root.')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 rounded">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- 2. Documents in Current Folder -->
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">
                Dokumen & Naskah Digital ({{ $documents->total() }})
            </h2>
            <a href="{{ route('dokumen.documents.index', $currentFolder ? ['folder_id' => $currentFolder->id] : []) }}" class="text-xs font-bold text-blue-600 hover:text-blue-800">
                Mode Tabel Lengkap →
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-4">Format & Nama Dokumen</th>
                            <th class="px-4 py-4">Nomor SK / Surat</th>
                            <th class="px-4 py-4">Klasifikasi</th>
                            <th class="px-4 py-4">Akses & Versi</th>
                            <th class="px-4 py-4">Tanggal / Ukuran</th>
                            <th class="px-5 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($documents as $doc)
                            <tr class="hover:bg-blue-50/30 transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-start space-x-3">
                                        <span class="px-2 py-1 text-[10px] font-black rounded-lg border {{ $doc->format_badge_color }} mt-0.5">
                                            {{ $doc->file_type }}
                                        </span>
                                        <div class="min-w-0">
                                            <a href="{{ route('dokumen.documents.show', $doc->id) }}" class="text-xs font-bold text-slate-800 hover:text-[#003B70] block truncate">
                                                {{ $doc->title }}
                                            </a>
                                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $doc->file_name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap font-mono text-[11px] font-semibold text-slate-700">
                                    {{ $doc->document_number ?: 'NON-NOMOR' }}
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-lg bg-blue-50 text-blue-800 border border-blue-200">
                                        {{ $doc->category }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    @php $conf = \App\Models\SimandatDocument::CONFIDENTIALITY_LIST[$doc->confidentiality] ?? ['label' => $doc->confidentiality, 'badge' => 'bg-gray-100 text-gray-800']; @endphp
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-md border {{ $conf['badge'] }}">
                                        {{ $conf['label'] }}
                                    </span>
                                    <span class="text-[10px] font-bold text-blue-700 ml-1">{{ $doc->current_version }}</span>
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap text-[11px] text-slate-500">
                                    {{ $doc->document_date ? $doc->document_date->format('d/m/Y') : '-' }} • {{ $doc->file_size }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-1.5">
                                        <a href="{{ route('dokumen.documents.show', $doc->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" title="Lihat">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                        <a href="{{ route('dokumen.documents.download', $doc->id) }}" class="p-1.5 text-emerald-600 hover:bg-emerald-100 rounded-lg transition-colors" title="Unduh">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-xs text-slate-500">
                                    <div class="max-w-xs mx-auto text-center space-y-2">
                                        <p class="font-bold text-slate-700">Folder ini kosong</p>
                                        <p class="text-[11px] text-slate-400">Belum ada dokumen yang dimasukkan ke dalam folder ini.</p>
                                        <a href="{{ route('dokumen.documents.create', $currentFolder ? ['folder_id' => $currentFolder->id] : []) }}" class="inline-block px-3 py-1.5 bg-[#003B70] text-white text-xs font-bold rounded-xl">
                                            + Unggah Dokumen
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($documents->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $documents->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- MODAL 1: BUAT FOLDER BARU -->
    <div x-show="showNewFolderModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" style="display: none;">
        <div @click.away="showNewFolderModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-[#00264A] flex items-center space-x-2">
                    <span>📁</span>
                    <span>Buat Folder Kategori Baru</span>
                </h3>
                <button @click="showNewFolderModal = false" class="text-slate-400 hover:text-slate-600 font-bold">&times;</button>
            </div>

            <form action="{{ route('dokumen.folders.store') }}" method="POST" class="space-y-4">
                @csrf
                @if($currentFolder)
                    <input type="hidden" name="parent_id" value="{{ $currentFolder->id }}">
                    <p class="text-[11px] text-slate-500 bg-blue-50 p-2.5 rounded-xl border border-blue-100">
                        Folder akan dibuat di dalam: <strong>{{ $currentFolder->name }}</strong>
                    </p>
                @endif

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Folder <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Dokumen Akreditasi SMA 2026" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kode Folder</label>
                        <input type="text" name="code" placeholder="Contoh: FLD-AKRED" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 uppercase focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Warna Aksen</label>
                        <select name="color" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                            <option value="blue">Biru (Dinas)</option>
                            <option value="emerald">Hijau (Pendidikan)</option>
                            <option value="purple">Ungu (Kebudayaan)</option>
                            <option value="amber">Kuning (GTK)</option>
                            <option value="rose">Merah (Regulasi/SK)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" placeholder="Tujuan atau isi dokumen dalam folder ini..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]"></textarea>
                </div>

                <div class="flex items-center justify-end space-x-2 pt-2">
                    <button type="button" @click="showNewFolderModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#003B70] text-white text-xs font-bold rounded-xl shadow-md">Simpan Folder</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: EDIT FOLDER -->
    <div x-show="showEditFolderModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" style="display: none;">
        <div @click.away="showEditFolderModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-[#00264A] flex items-center space-x-2">
                    <span>✏️</span>
                    <span>Edit Folder Kategori</span>
                </h3>
                <button @click="showEditFolderModal = false" class="text-slate-400 hover:text-slate-600 font-bold">&times;</button>
            </div>

            <form :action="'{{ url('dokumen/folders') }}/' + editFolder.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Folder <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" x-model="editFolder.name" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kode Folder</label>
                        <input type="text" name="code" x-model="editFolder.code" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 uppercase focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Warna Aksen</label>
                        <select name="color" x-model="editFolder.color" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                            <option value="blue">Biru (Dinas)</option>
                            <option value="emerald">Hijau (Pendidikan)</option>
                            <option value="purple">Ungu (Kebudayaan)</option>
                            <option value="amber">Kuning (GTK)</option>
                            <option value="rose">Merah (Regulasi/SK)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Singkat</label>
                    <textarea name="description" x-model="editFolder.description" rows="2" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]"></textarea>
                </div>

                <div class="flex items-center justify-end space-x-2 pt-2">
                    <button type="button" @click="showEditFolderModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#003B70] text-white text-xs font-bold rounded-xl shadow-md">Perbarui Folder</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
