@extends('layouts.app')

@section('title', 'Pangkalan Dokumen & Arsip Digital - TVRI Maluku')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <div>
            <div class="flex items-center space-x-2 text-xs font-semibold text-rose-600 uppercase tracking-wider mb-1">
                <span>DMS Repositori</span>
                <span>•</span>
                <span>Pusat Arsip Resmi</span>
            </div>
            <h1 class="text-2xl font-black text-[#00264A] tracking-tight">Pangkalan Dokumen & Arsip Digital</h1>
            <p class="text-xs text-slate-500 mt-1">Daftar seluruh naskah dinas, SK penetapan, regulasi daerah, dan kajian budaya terverifikasi.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl border border-slate-200 transition-colors">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Export CSV</span>
            </a>
            <a href="{{ route('dokumen.folders.index') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold rounded-xl border border-amber-200 transition-colors">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                <span>Folder Explorer</span>
            </a>
            <a href="{{ route('dokumen.documents.create') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-[#003B70] hover:bg-[#00264A] text-white text-xs font-bold rounded-xl shadow-md transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Unggah Dokumen Baru</span>
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

    <!-- Filter & Search Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
        <form method="GET" action="{{ route('dokumen.documents.index') }}" class="space-y-4">
            <!-- Search row -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="md:col-span-2 relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor SK, judul dokumen, deskripsi, atau tags..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <div>
                    <select name="folder_id" class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                        <option value="">Semua Folder</option>
                        @foreach($folders as $f)
                            <option value="{{ $f->id }}" {{ request('folder_id') == $f->id ? 'selected' : '' }}>📁 {{ $f->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="division" class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                        <option value="">Semua Bidang / Unit</option>
                        @foreach($divisions as $div)
                            <option value="{{ $div }}" {{ request('division') == $div ? 'selected' : '' }}>{{ $div }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Detailed filters row -->
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-5 gap-3 pt-3 border-t border-slate-100">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Klasifikasi</label>
                    <select name="category" class="w-full py-2 px-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Tingkat Akses</label>
                    <select name="confidentiality" class="w-full py-2 px-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                        <option value="">Semua Akses</option>
                        @foreach($confidentialityOptions as $key => $opt)
                            <option value="{{ $key }}" {{ request('confidentiality') == $key ? 'selected' : '' }}>{{ $opt['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Format Berkas</label>
                    <select name="format" class="w-full py-2 px-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                        <option value="">Semua Format</option>
                        <option value="PDF" {{ request('format') == 'PDF' ? 'selected' : '' }}>PDF Document</option>
                        <option value="DOCX" {{ request('format') == 'DOCX' ? 'selected' : '' }}>Word Document (DOCX)</option>
                        <option value="XLSX" {{ request('format') == 'XLSX' ? 'selected' : '' }}>Excel Spreadsheet (XLSX)</option>
                        <option value="PPTX" {{ request('format') == 'PPTX' ? 'selected' : '' }}>Presentation (PPTX)</option>
                        <option value="JPG" {{ request('format') == 'JPG' ? 'selected' : '' }}>Gambar / Foto (JPG/PNG)</option>
                        <option value="ZIP" {{ request('format') == 'ZIP' ? 'selected' : '' }}>Arsip Terkompresi (ZIP)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Status Dokumen</label>
                    <select name="status" class="w-full py-2 px-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                        <option value="">Semua Status</option>
                        <option value="AKTIF" {{ request('status') == 'AKTIF' ? 'selected' : '' }}>AKTIF</option>
                        <option value="INAKTIF" {{ request('status') == 'INAKTIF' ? 'selected' : '' }}>INAKTIF</option>
                        <option value="RETENSI" {{ request('status') == 'RETENSI' ? 'selected' : '' }}>RETENSI</option>
                        <option value="DIARSIPKAN" {{ request('status') == 'DIARSIPKAN' ? 'selected' : '' }}>DIARSIPKAN</option>
                    </select>
                </div>

                <div class="col-span-2 sm:col-span-4 lg:col-span-1 flex items-end gap-2">
                    <button type="submit" class="flex-1 py-2 px-3 bg-[#003B70] hover:bg-[#00264A] text-white text-xs font-bold rounded-xl transition-colors">
                        Terapkan
                    </button>
                    <a href="{{ route('dokumen.documents.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl text-center transition-colors">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Documents Data Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between flex-wrap gap-2">
            <span class="text-xs font-bold text-slate-700">Daftar Dokumen & Berkas Digital</span>
            <button type="button" id="btnBulkDoc" onclick="submitBulkDoc()" class="hidden px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-sm transition-all flex items-center space-x-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                <span id="btnBulkDocText">Hapus Dokumen Terpilih</span>
            </button>
        </div>

        <form id="formBulkDoc" action="{{ route('dokumen.documents.bulk-destroy') }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
            <div id="bulkDocContainer"></div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="px-3 py-4 w-8 text-center">
                            <input type="checkbox" id="checkAllDoc" class="rounded border-slate-300 text-blue-600 w-4 h-4 cursor-pointer" title="Kotak Central: Centang Semua Dokumen">
                        </th>
                        <th class="px-5 py-4">Format & Judul Dokumen</th>
                        <th class="px-4 py-4">Nomor SK / Surat</th>
                        <th class="px-4 py-4">Bidang & Folder</th>
                        <th class="px-4 py-4">Klasifikasi</th>
                        <th class="px-4 py-4">Akses & Versi</th>
                        <th class="px-4 py-4">Statistik</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($documents as $doc)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="px-3 py-4 text-center">
                                <input type="checkbox" value="{{ $doc->id }}" class="doc-cb rounded border-slate-300 text-blue-600 w-4 h-4 cursor-pointer">
                            </td>
                            <!-- Format & Title -->
                            <td class="px-5 py-4">
                                <div class="flex items-start space-x-3">
                                    <div class="mt-0.5 flex-shrink-0">
                                        <span class="px-2 py-1 text-[10px] font-black rounded-lg border {{ $doc->format_badge_color }}">
                                            {{ $doc->file_type }}
                                        </span>
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('dokumen.documents.show', $doc->id) }}" class="text-xs font-bold text-slate-800 hover:text-[#003B70] line-clamp-2">
                                            {{ $doc->title }}
                                        </a>
                                        <div class="flex items-center space-x-2 text-[10px] text-slate-400 mt-1">
                                            <span>{{ $doc->file_name }}</span>
                                            <span>•</span>
                                            <span class="font-medium text-slate-500">{{ $doc->file_size }}</span>
                                            <span>•</span>
                                            <span>{{ $doc->document_date ? $doc->document_date->format('d M Y') : 'Tahun ' . $doc->year }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Document Number -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="font-mono text-[11px] font-semibold text-slate-700 bg-slate-100 px-2 py-1 rounded-md border border-slate-200">
                                    {{ $doc->document_number ?: 'NON-NOMOR' }}
                                </span>
                            </td>

                            <!-- Division & Folder -->
                            <td class="px-4 py-4">
                                <p class="font-semibold text-slate-800">{{ $doc->division }}</p>
                                <p class="text-[11px] text-slate-500 flex items-center space-x-1 mt-0.5">
                                    <span>📁</span>
                                    <span class="truncate">{{ $doc->folder ? $doc->folder->name : 'Umum / Tanpa Folder' }}</span>
                                </p>
                            </td>

                            <!-- Category -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg bg-blue-50 text-blue-800 border border-blue-200">
                                    {{ $doc->category }}
                                </span>
                            </td>

                            <!-- Confidentiality & Version -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    @php $conf = $confidentialityOptions[$doc->confidentiality] ?? ['label' => $doc->confidentiality, 'badge' => 'bg-gray-100 text-gray-800']; @endphp
                                    <span class="inline-block px-2 py-0.5 text-[10px] font-bold rounded-md border {{ $conf['badge'] }}">
                                        {{ $conf['label'] }}
                                    </span>
                                    <div>
                                        <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-200">
                                            {{ $doc->current_version }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 ml-1">({{ $doc->versions->count() }} versi)</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Downloads & Views -->
                            <td class="px-4 py-4 whitespace-nowrap text-[11px]">
                                <p class="font-semibold text-slate-700">{{ $doc->download_count }} <span class="text-slate-400 text-[10px]">unduhan</span></p>
                                <p class="text-[10px] text-slate-400">{{ $doc->view_count }} dilihat</p>
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-1.5">
                                    <a href="{{ route('dokumen.documents.show', $doc->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" title="Lihat Detail & Versi">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>

                                    <a href="{{ route('dokumen.documents.download', $doc->id) }}" class="p-1.5 text-emerald-600 hover:bg-emerald-100 rounded-lg transition-colors" title="Unduh Berkas Aktif">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    </a>

                                    <a href="{{ route('dokumen.documents.edit', $doc->id) }}" class="p-1.5 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors" title="Edit Metadata">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>

                                    @if(auth()->user()->hasRole('ADMIN'))
                                        <form action="{{ route('dokumen.documents.destroy', $doc->id) }}" method="POST" class="inline" onsubmit="return confirm('Pindahkan dokumen ini ke folder retensi arsip terhapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-100 rounded-lg transition-colors" title="Hapus Dokumen">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="max-w-xs mx-auto text-center space-y-3">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <p class="text-sm font-bold text-slate-700">Tidak ada dokumen ditemukan</p>
                                    <p class="text-xs text-slate-500">Coba ubah filter pencarian atau unggah naskah baru ke sistem.</p>
                                    <a href="{{ route('dokumen.documents.create') }}" class="inline-block px-4 py-2 bg-[#003B70] text-white text-xs font-bold rounded-xl">
                                        Unggah Dokumen
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($documents->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $documents->links() }}
            </div>
        @endif
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkAll = document.getElementById('checkAllDoc');
    const items = document.querySelectorAll('.doc-cb');
    const btn = document.getElementById('btnBulkDoc');
    const btnText = document.getElementById('btnBulkDocText');

    function update() {
        const count = document.querySelectorAll('.doc-cb:checked').length;
        if (btn) {
            if (count > 0) {
                btn.classList.remove('hidden');
                if (btnText) btnText.innerText = `Hapus (${count}) Dokumen Terpilih`;
            } else {
                btn.classList.add('hidden');
            }
        }
    }

    if (checkAll) {
        checkAll.addEventListener('change', function() {
            items.forEach(cb => cb.checked = checkAll.checked);
            update();
        });
    }

    items.forEach(cb => cb.addEventListener('change', update));
});

window.submitBulkDoc = function() {
    const checked = document.querySelectorAll('.doc-cb:checked');
    if (checked.length === 0) return;
    if (confirm(`Apakah Anda yakin ingin memindahkan ${checked.length} berkas dokumen terpilih ke folder retensi arsip terhapus?`)) {
        const form = document.getElementById('formBulkDoc');
        const container = document.getElementById('bulkDocContainer');
        container.innerHTML = '';
        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = cb.value;
            container.appendChild(input);
        });
        form.submit();
    }
};
</script>

        <!-- Pagination -->
        @if($documents->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $documents->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
