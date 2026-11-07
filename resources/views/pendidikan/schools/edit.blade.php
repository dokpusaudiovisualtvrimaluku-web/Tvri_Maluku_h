@extends('layouts.app')

@section('title', 'Edit Satuan Pendidikan - ' . $school->nama)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="bg-gradient-to-r from-[#003B70] to-[#005B9F] rounded-3xl p-6 text-white shadow-xl flex items-center justify-between">
        <div>
            <h1 class="text-xl font-black">EDIT SATUAN PENDIDIKAN</h1>
            <p class="text-xs text-blue-100 mt-0.5">{{ $school->nama }} &bull; NPSN: {{ $school->npsn }}</p>
        </div>
        <a href="{{ route('pendidikan.schools.show', $school->id) }}" class="px-3.5 py-2 bg-white/15 hover:bg-white/25 rounded-xl text-xs font-bold transition">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
        <form action="{{ route('pendidikan.schools.update', $school->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">NPSN <span class="text-rose-500">*</span></label>
                    <input type="text" name="npsn" value="{{ old('npsn', $school->npsn) }}" required class="w-full text-xs rounded-xl border-slate-200 focus:border-[#005B9F] focus:ring-[#005B9F]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Satuan Pendidikan <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $school->nama) }}" required class="w-full text-xs rounded-xl border-slate-200 focus:border-[#005B9F] focus:ring-[#005B9F]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jenjang Pendidikan <span class="text-rose-500">*</span></label>
                    <select name="jenjang" required class="w-full text-xs rounded-xl border-slate-200 focus:border-[#005B9F] focus:ring-[#005B9F]">
                        @foreach(['PAUD', 'TK', 'SD', 'SMP', 'SMA', 'SMK', 'SLB'] as $j)
                            <option value="{{ $j }}" {{ old('jenjang', $school->jenjang) == $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status Sekolah <span class="text-rose-500">*</span></label>
                    <select name="status_sekolah" required class="w-full text-xs rounded-xl border-slate-200 focus:border-[#005B9F] focus:ring-[#005B9F]">
                        <option value="Negeri" {{ old('status_sekolah', $school->status_sekolah) == 'Negeri' ? 'selected' : '' }}>Negeri</option>
                        <option value="Swasta" {{ old('status_sekolah', $school->status_sekolah) == 'Swasta' ? 'selected' : '' }}>Swasta</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kabupaten / Kota <span class="text-rose-500">*</span></label>
                    <select name="kabupaten_kota" required class="w-full text-xs rounded-xl border-slate-200 focus:border-[#005B9F] focus:ring-[#005B9F]">
                        @foreach($kabupatenOptions as $kab)
                            <option value="{{ $kab }}" {{ old('kabupaten_kota', $school->kabupaten_kota ?? $school->cabang) == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kecamatan</label>
                    <input type="text" name="kecamatan" value="{{ old('kecamatan', $school->kecamatan) }}" class="w-full text-xs rounded-xl border-slate-200 focus:border-[#005B9F] focus:ring-[#005B9F]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Desa / Kelurahan</label>
                    <input type="text" name="desa_kelurahan" value="{{ old('desa_kelurahan', $school->desa_kelurahan) }}" class="w-full text-xs rounded-xl border-slate-200 focus:border-[#005B9F] focus:ring-[#005B9F]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kepala Sekolah</label>
                    <input type="text" name="kepala_sekolah" value="{{ old('kepala_sekolah', $school->kepala_sekolah) }}" class="w-full text-xs rounded-xl border-slate-200 focus:border-[#005B9F] focus:ring-[#005B9F]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Akreditasi</label>
                    <select name="akreditasi" class="w-full text-xs rounded-xl border-slate-200 focus:border-[#005B9F] focus:ring-[#005B9F]">
                        @foreach(['A', 'B', 'C', 'Belum Terakreditasi'] as $ak)
                            <option value="{{ $ak }}" {{ old('akreditasi', $school->akreditasi) == $ak ? 'selected' : '' }}>Akreditasi {{ $ak }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tahun Data</label>
                    <input type="text" name="tahun_data" value="{{ old('tahun_data', $school->tahun_data) }}" required class="w-full text-xs rounded-xl border-slate-200 focus:border-[#005B9F] focus:ring-[#005B9F]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Latitude</label>
                    <input type="text" name="latitude" value="{{ old('latitude', $school->latitude) }}" class="w-full text-xs rounded-xl border-slate-200 focus:border-[#005B9F] focus:ring-[#005B9F]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Longitude</label>
                    <input type="text" name="longitude" value="{{ old('longitude', $school->longitude) }}" class="w-full text-xs rounded-xl border-slate-200 focus:border-[#005B9F] focus:ring-[#005B9F]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status Validasi</label>
                    <select name="status_validasi" class="w-full text-xs rounded-xl border-slate-200 focus:border-[#005B9F] focus:ring-[#005B9F]">
                        @foreach(['DRAFT', 'SUBMITTED', 'VERIFIED', 'VALIDATED', 'REJECTED'] as $sv)
                            <option value="{{ $sv }}" {{ old('status_validasi', $school->status_validasi) == $sv ? 'selected' : '' }}>{{ $sv }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Lengkap</label>
                <textarea name="alamat" rows="2" class="w-full text-xs rounded-xl border-slate-200 focus:border-[#005B9F] focus:ring-[#005B9F]">{{ old('alamat', $school->alamat) }}</textarea>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('pendidikan.schools.show', $school->id) }}" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-[#003B70] hover:bg-[#00264A] rounded-xl shadow transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
