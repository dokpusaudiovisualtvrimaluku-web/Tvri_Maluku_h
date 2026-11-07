@extends('layouts.app')

@section('title', 'Detail Satuan Pendidikan - ' . $school->nama)

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'profil' }">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-[#003B70] via-[#005B9F] to-[#00264A] rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-2 mb-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-[#003B70]">
                        {{ $school->jenjang }} &bull; {{ $school->status_sekolah ?? 'Negeri' }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">
                        {{ $school->akreditasi }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold 
                        @if($school->status_validasi == 'VALIDATED') bg-emerald-500 text-white
                        @elseif($school->status_validasi == 'VERIFIED') bg-blue-500 text-white
                        @else bg-amber-500 text-white @endif">
                        {{ $school->status_validasi }}
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">{{ $school->nama }}</h1>
                <p class="text-xs sm:text-sm text-blue-100 font-medium mt-1">
                    NPSN: <span class="font-mono font-bold">{{ $school->npsn }}</span> &bull; {{ $school->kabupaten_kota ?? $school->cabang }}
                </p>
            </div>
            
            <div class="flex items-center space-x-2">
                <!-- Change Validation Status Modal / Form -->
                <form action="{{ route('pendidikan.schools.validate', $school->id) }}" method="POST" class="flex items-center space-x-1.5 bg-white/10 backdrop-blur-md p-1.5 rounded-2xl border border-white/20">
                    @csrf
                    @method('PATCH')
                    <select name="status_validasi" class="text-xs bg-slate-900/60 text-white rounded-xl border-none focus:ring-0 py-1.5 px-3">
                        <option value="DRAFT" {{ $school->status_validasi == 'DRAFT' ? 'selected' : '' }}>DRAFT</option>
                        <option value="SUBMITTED" {{ $school->status_validasi == 'SUBMITTED' ? 'selected' : '' }}>SUBMITTED</option>
                        <option value="VERIFIED" {{ $school->status_validasi == 'VERIFIED' ? 'selected' : '' }}>VERIFIED</option>
                        <option value="VALIDATED" {{ $school->status_validasi == 'VALIDATED' ? 'selected' : '' }}>VALIDATED</option>
                        <option value="REJECTED" {{ $school->status_validasi == 'REJECTED' ? 'selected' : '' }}>REJECTED</option>
                    </select>
                    <button type="submit" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs rounded-xl transition">
                        Update
                    </button>
                </form>
                
                <a href="{{ route('pendidikan.schools.edit', $school->id) }}" class="px-3.5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-2xl transition shadow flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span>Edit</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="flex items-center space-x-2 border-b border-slate-200 pb-1 overflow-x-auto">
        <button @click="activeTab = 'profil'" :class="activeTab === 'profil' ? 'border-[#003B70] text-[#003B70] font-extrabold' : 'border-transparent text-slate-500 font-medium hover:text-slate-700'" class="py-2.5 px-4 border-b-2 text-xs transition flex items-center space-x-1.5 whitespace-nowrap">
            <span>Profil & Geospasial</span>
        </button>
        <button @click="activeTab = 'guru'" :class="activeTab === 'guru' ? 'border-[#003B70] text-[#003B70] font-extrabold' : 'border-transparent text-slate-500 font-medium hover:text-slate-700'" class="py-2.5 px-4 border-b-2 text-xs transition flex items-center space-x-1.5 whitespace-nowrap">
            <span>Guru & Tendik ({{ $school->teachers->count() }})</span>
        </button>
        <button @click="activeTab = 'siswa'" :class="activeTab === 'siswa' ? 'border-[#003B70] text-[#003B70] font-extrabold' : 'border-transparent text-slate-500 font-medium hover:text-slate-700'" class="py-2.5 px-4 border-b-2 text-xs transition flex items-center space-x-1.5 whitespace-nowrap">
            <span>Peserta Didik ({{ $school->students->count() }})</span>
        </button>
        <button @click="activeTab = 'sarpras'" :class="activeTab === 'sarpras' ? 'border-[#003B70] text-[#003B70] font-extrabold' : 'border-transparent text-slate-500 font-medium hover:text-slate-700'" class="py-2.5 px-4 border-b-2 text-xs transition flex items-center space-x-1.5 whitespace-nowrap">
            <span>Sarana Prasarana ({{ $school->sarpras->count() }})</span>
        </button>
        <button @click="activeTab = 'dokumen'" :class="activeTab === 'dokumen' ? 'border-[#003B70] text-[#003B70] font-extrabold' : 'border-transparent text-slate-500 font-medium hover:text-slate-700'" class="py-2.5 px-4 border-b-2 text-xs transition flex items-center space-x-1.5 whitespace-nowrap">
            <span>Dokumen / Arsip ({{ $school->archives->count() }})</span>
        </button>
    </div>

    <!-- Tab 1: Profil & Geospasial -->
    <div x-show="activeTab === 'profil'" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <h3 class="text-sm font-extrabold text-[#00264A] uppercase tracking-wider mb-2">Informasi Profil Sekolah</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="p-3 bg-slate-50 rounded-xl">
                    <span class="text-slate-400 block font-bold">Kepala Sekolah</span>
                    <span class="font-bold text-slate-800">{{ $school->kepala_sekolah ?? '-' }}</span>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl">
                    <span class="text-slate-400 block font-bold">Alamat</span>
                    <span class="font-bold text-slate-800">{{ $school->alamat ?? '-' }}</span>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl">
                    <span class="text-slate-400 block font-bold">Kecamatan & Desa</span>
                    <span class="font-bold text-slate-800">{{ $school->kecamatan ?? '-' }}, {{ $school->desa_kelurahan ?? '-' }}</span>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl">
                    <span class="text-slate-400 block font-bold">Kabupaten / Kota</span>
                    <span class="font-bold text-slate-800">{{ $school->kabupaten_kota ?? $school->cabang }}</span>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl">
                    <span class="text-slate-400 block font-bold">Tahun Data</span>
                    <span class="font-bold text-slate-800">{{ $school->tahun_data }}</span>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl">
                    <span class="text-slate-400 block font-bold">Status Validasi</span>
                    <span class="font-bold text-emerald-600">{{ $school->status_validasi }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-extrabold text-[#00264A] uppercase tracking-wider mb-2">Koordinat Geospasial</h3>
                <div class="space-y-3 text-xs mt-4">
                    <div class="p-3 bg-blue-50 rounded-xl">
                        <span class="text-slate-400 block font-bold">Latitude</span>
                        <span class="font-mono font-bold text-[#003B70]">{{ $school->latitude ?? '-3.654703' }}</span>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-xl">
                        <span class="text-slate-400 block font-bold">Longitude</span>
                        <span class="font-mono font-bold text-[#003B70]">{{ $school->longitude ?? '128.190643' }}</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 text-center">
                <a href="https://www.google.com/maps?q={{ $school->latitude ?? '-3.654703' }},{{ $school->longitude ?? '128.190643' }}" target="_blank" class="inline-flex items-center space-x-1.5 text-xs font-bold text-[#005B9F] hover:underline">
                    <span>Buka di Google Maps</span> &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Tab 2: Guru & Tendik -->
    <div x-show="activeTab === 'guru'" class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-extrabold text-[#00264A] uppercase tracking-wider">Daftar Guru & Tendik</h3>
            <a href="{{ route('pendidikan.teachers.create') }}" class="px-3 py-1.5 bg-teal-600 text-white text-xs font-bold rounded-xl hover:bg-teal-700 transition">
                + Tambah Guru
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[11px] font-extrabold uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-2.5 px-3 rounded-l-xl">NIP</th>
                        <th class="py-2.5 px-4">Nama Lengkap</th>
                        <th class="py-2.5 px-3">L/P</th>
                        <th class="py-2.5 px-3">Jabatan</th>
                        <th class="py-2.5 px-3">Status Pegawai</th>
                        <th class="py-2.5 px-3">Pendidikan</th>
                        <th class="py-2.5 px-3 rounded-r-xl">Sertifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($school->teachers as $t)
                        <tr class="hover:bg-slate-50">
                            <td class="py-2.5 px-3 font-mono text-slate-500">{{ $t->nip ?? '-' }}</td>
                            <td class="py-2.5 px-4 font-bold text-slate-800">{{ $t->nama }}</td>
                            <td class="py-2.5 px-3 font-semibold">{{ $t->jenis_kelamin }}</td>
                            <td class="py-2.5 px-3">{{ $t->jabatan }}</td>
                            <td class="py-2.5 px-3 font-semibold">{{ $t->status_kepegawaian }}</td>
                            <td class="py-2.5 px-3">{{ $t->pendidikan_terakhir }}</td>
                            <td class="py-2.5 px-3 font-bold text-emerald-600">{{ $t->sertifikasi }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-6 text-center text-slate-400">Belum ada data guru terhubung ke sekolah ini</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tab 3: Peserta Didik -->
    <div x-show="activeTab === 'siswa'" class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-extrabold text-[#00264A] uppercase tracking-wider">Daftar Peserta Didik</h3>
            <a href="{{ route('pendidikan.students.create') }}" class="px-3 py-1.5 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-700 transition">
                + Tambah Siswa
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[11px] font-extrabold uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-2.5 px-3 rounded-l-xl">NISN</th>
                        <th class="py-2.5 px-4">Nama Siswa</th>
                        <th class="py-2.5 px-3">L/P</th>
                        <th class="py-2.5 px-3">Jenjang</th>
                        <th class="py-2.5 px-3">Tahun Ajaran</th>
                        <th class="py-2.5 px-3 rounded-r-xl">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($school->students as $st)
                        <tr class="hover:bg-slate-50">
                            <td class="py-2.5 px-3 font-mono font-bold text-blue-700">{{ $st->nisn }}</td>
                            <td class="py-2.5 px-4 font-bold text-slate-800">{{ $st->nama }}</td>
                            <td class="py-2.5 px-3 font-semibold">{{ $st->jenis_kelamin }}</td>
                            <td class="py-2.5 px-3">{{ $st->jenjang }}</td>
                            <td class="py-2.5 px-3">{{ $st->tahun_ajaran }}</td>
                            <td class="py-2.5 px-3">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    {{ $st->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-6 text-center text-slate-400">Belum ada peserta didik terdaftar pada sekolah ini</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tab 4: Sarana Prasarana -->
    <div x-show="activeTab === 'sarpras'" class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-extrabold text-[#00264A] uppercase tracking-wider">Rekap Sarana & Prasarana</h3>
            <a href="{{ route('pendidikan.sarpras.create') }}" class="px-3 py-1.5 bg-amber-600 text-white text-xs font-bold rounded-xl hover:bg-amber-700 transition">
                + Tambah Sarpras
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[11px] font-extrabold uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-2.5 px-3 rounded-l-xl">Fasilitas / Sarpras</th>
                        <th class="py-2.5 px-3 text-center">Jumlah Total</th>
                        <th class="py-2.5 px-3 text-center">Kondisi Baik</th>
                        <th class="py-2.5 px-3 text-center">Rusak Ringan</th>
                        <th class="py-2.5 px-3 text-center">Rusak Berat</th>
                        <th class="py-2.5 px-3 rounded-r-xl text-center">Tahun</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($school->sarpras as $sp)
                        <tr class="hover:bg-slate-50">
                            <td class="py-2.5 px-3 font-bold text-slate-800">{{ $sp->jenis_sarpras }}</td>
                            <td class="py-2.5 px-3 text-center font-bold text-[#003B70]">{{ $sp->jumlah }}</td>
                            <td class="py-2.5 px-3 text-center font-bold text-emerald-600">{{ $sp->kondisi_baik }}</td>
                            <td class="py-2.5 px-3 text-center font-bold text-amber-600">{{ $sp->rusak_ringan }}</td>
                            <td class="py-2.5 px-3 text-center font-bold text-rose-600">{{ $sp->rusak_berat }}</td>
                            <td class="py-2.5 px-3 text-center text-slate-400">{{ $sp->tahun_data }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-6 text-center text-slate-400">Belum ada rincian sarpras untuk sekolah ini</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tab 5: Dokumen & Arsip -->
    <div x-show="activeTab === 'dokumen'" class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
        <h3 class="text-sm font-extrabold text-[#00264A] uppercase tracking-wider mb-4">Arsip & Dokumen Sekolah</h3>
        <div class="space-y-3">
            @forelse($school->archives as $arc)
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-800">{{ $arc->title }}</p>
                        <p class="text-[11px] text-slate-500">{{ $arc->category }} &bull; Diunggah oleh: {{ $arc->uploader }}</p>
                    </div>
                    <span class="text-[10px] font-bold text-indigo-700 bg-indigo-100 px-2.5 py-0.5 rounded-full">
                        {{ $arc->date }}
                    </span>
                </div>
            @empty
                <p class="text-xs text-slate-400 py-6 text-center">Belum ada berkas digital terhubung</p>
            @endforelse
        </div>
    </div>

</div>
@endsection
