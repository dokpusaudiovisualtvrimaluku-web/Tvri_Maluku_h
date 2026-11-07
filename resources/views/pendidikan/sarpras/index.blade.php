@extends('layouts.app')

@php
$kabupatenList = [
    'Kota Ambon', 'Maluku Tengah', 'Maluku Tenggara', 'Kepulauan Tanimbar',
    'Buru', 'Seram Bagian Barat', 'Seram Bagian Timur', 'Kepulauan Aru',
    'Maluku Barat Daya', 'Buru Selatan', 'Kota Tual'
];
@endphp

@section('title', 'Pangkalan Data Sarana & Prasarana')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div>
            <div class="flex items-center space-x-3">
                <span class="p-2.5 bg-blue-50 text-blue-600 rounded-xl">
                    <i class="fas fa-building text-xl"></i>
                </span>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Pangkalan Data Sarana & Prasarana</h1>
                    <p class="text-sm text-slate-500 mt-0.5">Pantau inventaris fisik, kondisi ruang kelas, laboratorium, sanitasi, dan bangunan sekolah di Maluku</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('pendidikan.sarpras.index', array_merge(request()->all(), ['export' => 'csv'])) }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                <i class="fas fa-file-excel mr-2"></i> Export CSV
            </a>
            <a href="{{ route('pendidikan.sarpras.create') }}" class="inline-flex items-center px-4 py-2.5 bg-[#003B70] hover:bg-[#002b52] text-white text-sm font-semibold rounded-xl transition shadow-sm">
                <i class="fas fa-plus mr-2"></i> Tambah Sarpras
            </a>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('pendidikan.sarpras.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Pencarian</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama sarana/sekolah..." class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500">
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
                <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Sarana</label>
                <select name="jenis_sarpras" class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Jenis</option>
                    @foreach(['Ruang Kelas', 'Laboratorium', 'Perpustakaan', 'Sanitasi', 'Lapangan', 'Ruang Guru', 'Ruang Kepala Sekolah', 'Bangunan'] as $js)
                        <option value="{{ $js }}" {{ request('jenis_sarpras') == $js ? 'selected' : '' }}>{{ $js }}</option>
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
                <a href="{{ route('pendidikan.sarpras.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
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
                        <th class="px-6 py-4">Nama Sarana & Jenis</th>
                        <th class="px-6 py-4">Satuan Pendidikan</th>
                        <th class="px-6 py-4">Kab/Kota</th>
                        <th class="px-6 py-4 text-center">Total Unit</th>
                        <th class="px-6 py-4 text-center">Kondisi (Baik / R.Ringan / R.Berat)</th>
                        <th class="px-6 py-4">Status Validasi</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sarpras as $item)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $item->nama_sarpras }}</div>
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded-md text-xs font-semibold">{{ $item->jenis_sarpras }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-medium text-slate-800">{{ $item->school->nama_sekolah ?? '-' }}</span>
                            <div class="text-xs text-slate-400">NPSN: {{ $item->school->npsn ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-700">{{ $item->kabupaten_kota }}</td>
                        <td class="px-6 py-4 text-center font-bold text-slate-900">{{ $item->jumlah }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center space-x-2 text-xs font-semibold">
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 rounded" title="Kondisi Baik">
                                    <i class="fas fa-check-circle mr-1 text-emerald-600"></i>{{ $item->kondisi_baik }}
                                </span>
                                <span class="px-2 py-0.5 bg-amber-100 text-amber-800 rounded" title="Rusak Ringan">
                                    <i class="fas fa-exclamation-triangle mr-1 text-amber-600"></i>{{ $item->rusak_ringan }}
                                </span>
                                <span class="px-2 py-0.5 bg-rose-100 text-rose-800 rounded" title="Rusak Berat">
                                    <i class="fas fa-times-circle mr-1 text-rose-600"></i>{{ $item->rusak_berat }}
                                </span>
                            </div>
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
                            <span class="px-2.5 py-1 rounded-md text-xs font-bold border {{ $valColors[$item->status_validasi] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $item->status_validasi }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('pendidikan.sarpras.edit', $item->id) }}" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('pendidikan.sarpras.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data sarpras ini?');" class="inline">
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
                        <td colspan="7" class="text-center py-12 text-slate-400">
                            <i class="fas fa-building text-4xl mb-3 block"></i>
                            Belum ada data sarana prasarana yang cocok.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($sarpras->hasPages())
        <div class="p-6 border-t border-slate-100">
            {{ $sarpras->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
