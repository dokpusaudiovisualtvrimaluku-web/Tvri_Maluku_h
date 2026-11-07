@extends('layouts.app')

@php
$kabupatenList = [
    'Kota Ambon', 'Maluku Tengah', 'Maluku Tenggara', 'Kepulauan Tanimbar',
    'Buru', 'Seram Bagian Barat', 'Seram Bagian Timur', 'Kepulauan Aru',
    'Maluku Barat Daya', 'Buru Selatan', 'Kota Tual'
];
@endphp

@section('title', 'Tambah Sarana Prasarana')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center space-x-3">
            <span class="p-2.5 bg-blue-50 text-blue-600 rounded-xl">
                <i class="fas fa-plus-circle text-xl"></i>
            </span>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Tambah Sarana & Prasarana</h1>
                <p class="text-sm text-slate-500">Input data inventaris fisik dan kondisi fasilitas sekolah</p>
            </div>
        </div>
        <a href="{{ route('pendidikan.sarpras.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
        <form action="{{ route('pendidikan.sarpras.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Nama Sarana / Prasarana *</label>
                    <input type="text" name="nama_sarpras" value="{{ old('nama_sarpras') }}" required placeholder="Contoh: Gedung Laboratorium Komputer A" class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Jenis Sarana *</label>
                    <select name="jenis_sarpras" required class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                        @foreach(['Ruang Kelas', 'Laboratorium', 'Perpustakaan', 'Sanitasi', 'Lapangan', 'Ruang Guru', 'Ruang Kepala Sekolah', 'Bangunan'] as $js)
                            <option value="{{ $js }}" {{ old('jenis_sarpras') == $js ? 'selected' : '' }}>{{ $js }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Satuan Pendidikan (Sekolah) *</label>
                    <select name="school_id" required class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Satuan Pendidikan --</option>
                        @foreach($schools as $sch)
                            <option value="{{ $sch->id }}" {{ old('school_id') == $sch->id ? 'selected' : '' }}>{{ $sch->nama_sekolah }} ({{ $sch->npsn }}) - {{ $sch->kabupaten_kota }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Kabupaten / Kota *</label>
                    <select name="kabupaten_kota" required class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Kab/Kota --</option>
                        @foreach($kabupatenList as $kab)
                            <option value="{{ $kab }}" {{ old('kabupaten_kota') == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Jumlah Total Unit *</label>
                    <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}" min="0" required class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Jumlah Kondisi Baik *</label>
                    <input type="number" name="kondisi_baik" value="{{ old('kondisi_baik', 1) }}" min="0" required class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Jumlah Rusak Ringan *</label>
                    <input type="number" name="rusak_ringan" value="{{ old('rusak_ringan', 0) }}" min="0" required class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Jumlah Rusak Berat *</label>
                    <input type="number" name="rusak_berat" value="{{ old('rusak_berat', 0) }}" min="0" required class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Tahun Data *</label>
                    <input type="number" name="tahun_data" value="{{ old('tahun_data', 2024) }}" required class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Status Validasi</label>
                    <select name="status_validasi" class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                        @foreach(['DRAFT', 'SUBMITTED', 'VERIFIED', 'VALIDATED', 'REJECTED'] as $v)
                            <option value="{{ $v }}" {{ old('status_validasi') == $v ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Keterangan / Catatan Spesifikasi</label>
                <textarea name="keterangan" rows="3" class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500">{{ old('keterangan') }}</textarea>
            </div>

            <div class="pt-6 border-t border-slate-100 flex justify-end space-x-3">
                <a href="{{ route('pendidikan.sarpras.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-[#003B70] hover:bg-[#002b52] text-white text-sm font-semibold rounded-xl transition shadow-sm">
                    <i class="fas fa-save mr-2"></i> Simpan Data Sarpras
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
