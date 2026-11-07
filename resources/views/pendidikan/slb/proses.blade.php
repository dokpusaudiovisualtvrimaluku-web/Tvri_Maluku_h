@extends('layouts.app')

@section('title', 'Proses Verifikasi: ' . $layanan->nomor_registrasi)

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 text-xs font-semibold text-amber-600 uppercase tracking-wider mb-1">
                <a href="{{ route('pendidikan.slb.verifikasi') }}" class="hover:underline">Dashboard Verifikasi SLB</a>
                <span>/</span>
                <a href="{{ route('pendidikan.slb.layanan.show', $layanan->id) }}" class="hover:underline">{{ $layanan->nomor_registrasi }}</a>
                <span>/</span>
                <span>Proses Layanan</span>
            </div>
            <h1 class="text-2xl font-black text-[#00264A] tracking-tight">Proses & Verifikasi Permohonan Layanan</h1>
            <p class="text-xs text-slate-500 mt-1">Perbarui status tindak lanjut, input nomor SK/surat rekomendasi, dan unggah berkas hasil penerbitan.</p>
        </div>

        <a href="{{ route('pendidikan.slb.verifikasi') }}" class="inline-flex items-center space-x-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
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

    <form action="{{ route('pendidikan.slb.layanan.update-verifikasi', $layanan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Card 1: Tindak Lanjut Status & Penerbitan SK -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm space-y-4">
            <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">1</div>
                <h2 class="text-sm font-bold text-[#00264A]">Status Verifikasi & Penerbitan SK</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Status Layanan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Pembaruan Status Layanan <span class="text-rose-500">*</span></label>
                    <select name="status_layanan" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-bold focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                        @foreach($statusOptions as $sKey => $sVal)
                            <option value="{{ $sKey }}" {{ old('status_layanan', $layanan->status_layanan) == $sKey ? 'selected' : '' }}>
                                {{ $sVal['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nomor SK / Surat Terbit -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor SK / Surat Izin Resmi yang Diterbitkan</label>
                    <input type="text" name="nomor_surat_rekomendasi" value="{{ old('nomor_surat_rekomendasi', $layanan->nomor_surat_rekomendasi) }}" placeholder="Contoh: 823.3/089-SLB/TVRI MALUKU/2026" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-mono focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                </div>

                <!-- Tanggal Selesai / Terbit -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Penyelesaian / SK Diterbitkan</label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $layanan->tanggal_selesai ? $layanan->tanggal_selesai->format('Y-m-d') : '') }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">
                </div>

                <!-- Unggah Berkas SK Terbit -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Unggah Berkas SK / Surat Izin Terbit (PDF)</label>
                    <input type="file" name="file_hasil_rekomendasi" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                    @if($layanan->file_hasil_rekomendasi)
                        <p class="text-[10px] text-emerald-600 mt-1">✓ Berkas SK tersimpan: {{ basename($layanan->file_hasil_rekomendasi) }}</p>
                    @endif
                </div>

                <!-- Catatan Petugas -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Hasil Verifikasi & Instruksi Tindak Lanjut</label>
                    <textarea name="catatan_verifikator" rows="3" placeholder="Tuliskan catatan hasil pemeriksaan berkas, alasan penolakan jika ada, atau informasi pengambilan berkas fisik..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#003B70]">{{ old('catatan_verifikator', $layanan->catatan_verifikator) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Card 2: Data Pemohon (Read/Edit) -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm space-y-4">
            <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center font-bold">2</div>
                <h2 class="text-sm font-bold text-[#00264A]">Informasi Pemohon (Read Only)</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pemohon</label>
                    <input type="text" value="{{ $layanan->nama_pemohon }}" readonly class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs text-slate-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Identitas</label>
                    <input type="text" value="{{ $layanan->nomor_identitas ?? '-' }}" readonly class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs text-slate-600 font-mono">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Satuan Pendidikan (SLB)</label>
                    <input type="text" value="{{ $layanan->nama_satuan_pendidikan }}" readonly class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs text-slate-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kabupaten / Kota</label>
                    <input type="text" value="{{ $layanan->kabupaten_kota }}" readonly class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs text-slate-600">
                </div>

                <!-- Dokumen Laporan / Persyaratan -->
                <div class="md:col-span-2 mt-2 pt-4 border-t border-slate-100">
                    <label class="block text-xs font-bold text-slate-700 mb-2">Dokumen Persyaratan / Laporan Pemohon</label>
                    @if($layanan->file_persyaratan)
                        <div class="flex items-center space-x-3">
                            <a href="{{ Storage::url($layanan->file_persyaratan) }}" target="_blank" class="inline-flex items-center space-x-1.5 px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-xl transition-colors border border-indigo-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <span>Lihat Laporan</span>
                            </a>
                            <a href="{{ route('pendidikan.slb.layanan.download', ['id' => $layanan->id, 'type' => 'persyaratan']) }}" class="inline-flex items-center space-x-1.5 px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold rounded-xl transition-colors border border-emerald-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                <span>Unduh File Laporan</span>
                            </a>
                            <span class="text-[10px] text-slate-500 truncate max-w-[200px]">{{ $layanan->file_nama_persyaratan ?? 'Berkas Laporan' }}</span>
                        </div>
                    @else
                        <div class="px-4 py-3 bg-slate-50 rounded-xl text-xs text-slate-500 italic border border-slate-200">
                            Tidak ada dokumen / laporan yang diunggah oleh pemohon.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end space-x-3 pt-2">
            <a href="{{ route('pendidikan.slb.verifikasi') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-[#003B70] hover:bg-[#00264A] text-white text-xs font-bold rounded-xl shadow-lg hover:shadow-blue-900/30 transition-all flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Simpan Perubahan & Terbitkan Status</span>
            </button>
        </div>

    </form>

</div>
@endsection
