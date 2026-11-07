@extends('layouts.app')

@php
$kabupatenList = [
    'Kota Ambon', 'Maluku Tengah', 'Maluku Tenggara', 'Kepulauan Tanimbar',
    'Buru', 'Seram Bagian Barat', 'Seram Bagian Timur', 'Kepulauan Aru',
    'Maluku Barat Daya', 'Buru Selatan', 'Kota Tual'
];
@endphp

@section('title', 'Tambah Guru / GTK')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center space-x-3">
            <span class="p-2.5 bg-blue-50 text-blue-600 rounded-xl">
                <i class="fas fa-user-plus text-xl"></i>
            </span>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Tambah Guru / GTK Baru</h1>
                <p class="text-sm text-slate-500">Input data tenaga pendidik dan kependidikan Maluku</p>
            </div>
        </div>
        <a href="{{ route('pendidikan.teachers.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
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
        <form action="{{ route('pendidikan.teachers.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Nama Lengkap & Gelar *</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">NIP</label>
                    <input type="text" name="nip" value="{{ old('nip') }}" placeholder="18 digit jika PNS/PPPK" class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">NUPTK</label>
                    <input type="text" name="nuptk" value="{{ old('nuptk') }}" class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" required class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
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
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Jabatan *</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan', 'Guru Mapel') }}" required class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Mata Pelajaran yang Diampu</label>
                    <input type="text" name="mata_pelajaran" value="{{ old('mata_pelajaran') }}" placeholder="Contoh: Matematika, Bahasa Indonesia" class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Status Kepegawaian *</label>
                    <select name="status_kepegawaian" required class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                        @foreach(['PNS', 'PPPK', 'GTT', 'GTY', 'Honorer'] as $st)
                            <option value="{{ $st }}" {{ old('status_kepegawaian') == $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Pangkat / Golongan</label>
                    <input type="text" name="pangkat_golongan" value="{{ old('pangkat_golongan', 'Penata Muda / III/a') }}" class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Pendidikan Terakhir</label>
                    <input type="text" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir', 'S1') }}" placeholder="D3 / S1 / S2 / S3" class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Status Sertifikasi Guru *</label>
                    <select name="sertifikasi" required class="w-full text-sm rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                        <option value="0" {{ old('sertifikasi') == '0' ? 'selected' : '' }}>Belum Tersertifikasi</option>
                        <option value="1" {{ old('sertifikasi') == '1' ? 'selected' : '' }}>Sudah Tersertifikasi (Pendidik)</option>
                    </select>
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

            <div class="pt-6 border-t border-slate-100 flex justify-end space-x-3">
                <a href="{{ route('pendidikan.teachers.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-[#003B70] hover:bg-[#002b52] text-white text-sm font-semibold rounded-xl transition shadow-sm">
                    <i class="fas fa-save mr-2"></i> Simpan Data Guru/GTK
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
