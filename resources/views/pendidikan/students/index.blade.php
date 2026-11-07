@extends('layouts.app')

@php
$kabupatenList = [
    'Kota Ambon', 'Maluku Tengah', 'Maluku Tenggara', 'Kepulauan Tanimbar',
    'Buru', 'Seram Bagian Barat', 'Seram Bagian Timur', 'Kepulauan Aru',
    'Maluku Barat Daya', 'Buru Selatan', 'Kota Tual'
];
@endphp

@section('title', 'Pangkalan Data Peserta Didik')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div>
            <div class="flex items-center space-x-3">
                <span class="p-2.5 bg-blue-50 text-blue-600 rounded-xl">
                    <i class="fas fa-user-graduate text-xl"></i>
                </span>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Pangkalan Data Peserta Didik</h1>
                    <p class="text-sm text-slate-500 mt-0.5">Kelola data siswa, NISN, status, dan validasi per satuan pendidikan di Maluku</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('pendidikan.students.index', array_merge(request()->all(), ['export' => 'csv'])) }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                <i class="fas fa-file-excel mr-2"></i> Export CSV
            </a>
            <a href="{{ route('pendidikan.students.create') }}" class="inline-flex items-center px-4 py-2.5 bg-[#003B70] hover:bg-[#002b52] text-white text-sm font-semibold rounded-xl transition shadow-sm">
                <i class="fas fa-plus mr-2"></i> Tambah Siswa
            </a>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('pendidikan.students.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Pencarian</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="NISN, Nama Siswa..." class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Kabupaten/Kota</label>
                <select name="kabupaten_kota" class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Wilayah</option>
                    @foreach($kabupatenList as $kab)
                        <option value="{{ $kab }}" {{ request('kabupaten_kota') == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Jenjang</label>
                <select name="jenjang" class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Jenjang</option>
                    @foreach(['PAUD', 'TK', 'SD', 'SMP', 'SMA', 'SMK', 'SLB'] as $j)
                        <option value="{{ $j }}" {{ request('jenjang') == $j ? 'selected' : '' }}>{{ $j }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Status Validasi</label>
                <select name="status_validasi" class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    @foreach(['DRAFT', 'SUBMITTED', 'VERIFIED', 'VALIDATED', 'REJECTED'] as $st)
                        <option value="{{ $st }}" {{ request('status_validasi') == $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('pendidikan.students.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">NISN & Nama</th>
                        <th class="px-6 py-4">Satuan Pendidikan</th>
                        <th class="px-6 py-4">Jenjang & Kelas</th>
                        <th class="px-6 py-4">Kab/Kota</th>
                        <th class="px-6 py-4">L/P</th>
                        <th class="px-6 py-4">Status Siswa</th>
                        <th class="px-6 py-4">Status Validasi</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $student)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $student->nama }}</div>
                            <div class="text-xs text-blue-600 font-mono">NISN: {{ $student->nisn }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-medium text-slate-800">{{ $student->school->nama_sekolah ?? '-' }}</span>
                            <div class="text-xs text-slate-400">NPSN: {{ $student->school->npsn ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-slate-100 text-slate-800 rounded-lg text-xs font-bold">{{ $student->jenjang }}</span>
                            <span class="text-xs text-slate-500 block mt-1">{{ $student->tingkat_kelas }} ({{ $student->tahun_ajaran }})</span>
                        </td>
                        <td class="px-6 py-4 text-slate-700">{{ $student->kabupaten_kota }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded text-xs font-bold {{ $student->jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                                {{ $student->jenis_kelamin }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $student->status == 'AKTIF' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $student->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $valColors = [
                                    'VALIDATED' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'VERIFIED' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'SUBMITTED' => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'DRAFT' => 'bg-slate-100 text-slate-700 border-slate-200',
                                    'REJECTED' => 'bg-rose-100 text-rose-800 border-rose-200',
                                ];
                            @endphp
                            <span class="px-2.5 py-1 rounded-md text-xs font-bold border {{ $valColors[$student->status_validasi] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $student->status_validasi }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('pendidikan.students.edit', $student->id) }}" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('pendidikan.students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Hapus data peserta didik ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-12 text-slate-400">
                            <i class="fas fa-user-slash text-4xl mb-3 block"></i>
                            Belum ada data peserta didik yang cocok.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($students->hasPages())
        <div class="p-6 border-t border-slate-100">
            {{ $students->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
