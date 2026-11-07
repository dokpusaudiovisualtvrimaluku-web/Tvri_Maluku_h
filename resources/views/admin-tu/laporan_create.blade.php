@extends('layouts.app')

@section('title', 'Buat Laporan Baru - Admin TU')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center space-x-3 mb-6">
        <a href="{{ route('admin-tu.laporan') }}" class="p-2 bg-white rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors">
            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-[#00264A] tracking-tight">Buat Laporan Baru</h1>
            <p class="text-sm text-slate-500 mt-0.5">Lengkapi formulir di bawah ini untuk menambahkan data laporan baru.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 md:p-8">
            <form action="{{ route('admin-tu.laporan.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Judul Laporan <span class="text-rose-500">*</span></label>
                        <input type="text" name="judul_laporan" required class="block w-full py-3 px-4 border border-slate-200 bg-slate-50 rounded-xl text-sm focus:bg-white focus:ring-[#003B70] focus:border-[#003B70]" placeholder="Contoh: Laporan Kinerja Bulanan Ketenagaan">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kategori Laporan <span class="text-rose-500">*</span></label>
                        <select name="kategori_laporan" required class="block w-full py-3 px-4 border border-slate-200 bg-slate-50 rounded-xl text-sm focus:bg-white focus:ring-[#003B70] focus:border-[#003B70]">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoriOptions as $opt)
                                <option value="{{ $opt }}">{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tanggal Laporan <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_laporan" required class="block w-full py-3 px-4 border border-slate-200 bg-slate-50 rounded-xl text-sm focus:bg-white focus:ring-[#003B70] focus:border-[#003B70]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pembuat Laporan</label>
                        <input type="text" name="pembuat_laporan" class="block w-full py-3 px-4 border border-slate-200 bg-slate-50 rounded-xl text-sm focus:bg-white focus:ring-[#003B70] focus:border-[#003B70]" placeholder="Nama Penyusun / Bidang">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Wilayah / Satuan Kerja</label>
                        <input type="text" name="wilayah" class="block w-full py-3 px-4 border border-slate-200 bg-slate-50 rounded-xl text-sm focus:bg-white focus:ring-[#003B70] focus:border-[#003B70]" placeholder="Contoh: Kota Ambon">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status Laporan <span class="text-rose-500">*</span></label>
                        <select name="status" required class="block w-full py-3 px-4 border border-slate-200 bg-slate-50 rounded-xl text-sm focus:bg-white focus:ring-[#003B70] focus:border-[#003B70]">
                            @foreach($statusOptions as $opt)
                                <option value="{{ $opt }}">{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Keterangan Tambahan</label>
                        <textarea name="keterangan" rows="3" class="block w-full py-3 px-4 border border-slate-200 bg-slate-50 rounded-xl text-sm focus:bg-white focus:ring-[#003B70] focus:border-[#003B70]" placeholder="Catatan singkat mengenai laporan ini..."></textarea>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('admin-tu.laporan') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#005B9F] hover:bg-[#003B70] text-white font-bold text-sm transition-colors shadow-md hover:shadow-lg flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Simpan Data</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
