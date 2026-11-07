@extends('layouts.app')

@section('title', 'Preview & Validasi Import')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div>
            <div class="flex items-center space-x-3">
                <span class="p-2.5 bg-blue-50 text-blue-600 rounded-xl">
                    <i class="fas fa-table text-xl"></i>
                </span>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Preview Data Import: {{ strtoupper($entity_type) }}</h1>
                    <p class="text-sm text-slate-500">Ditemukan <b>{{ $total_rows }}</b> baris data dari file <b>{{ $original_name }}</b></p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('pendidikan.import.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                <i class="fas fa-arrow-left mr-2"></i> Ganti File
            </a>
            <form action="{{ route('pendidikan.import.execute') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="file_path" value="{{ $file_path }}">
                <input type="hidden" name="entity_type" value="{{ $entity_type }}">
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition shadow-sm">
                    <i class="fas fa-check-circle mr-2"></i> Eksekusi Import Sekarang
                </button>
            </form>
        </div>
    </div>

    <!-- Table Preview -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-4 bg-slate-50 border-b border-slate-100 text-xs font-semibold text-slate-600 flex justify-between items-center">
            <span>Menampilkan {{ count($preview_rows) }} baris teratas (Preview)</span>
            <span class="text-emerald-600"><i class="fas fa-shield-alt mr-1"></i> Validasi Kolom Berhasil</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-100 text-slate-700 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        @foreach($header as $col)
                            <th class="px-4 py-3">{{ $col }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($preview_rows as $idx => $row)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-mono text-slate-400">{{ $idx + 1 }}</td>
                        @foreach($row as $val)
                            <td class="px-4 py-3 text-slate-800">{{ $val }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
