@extends('layouts.app')

@section('title', $def['nama'] . ' - Tata Usaha TVRI Maluku')

@php
    $badge = [
        'Selesai' => 'bg-emerald-100 text-emerald-700', 'Proses' => 'bg-blue-100 text-blue-700',
        'Disposisi' => 'bg-amber-100 text-amber-700', 'Baru' => 'bg-slate-100 text-slate-600',
        'Akan Datang' => 'bg-blue-100 text-blue-700', 'Berlangsung' => 'bg-emerald-100 text-emerald-700',
        'Dibatalkan' => 'bg-rose-100 text-rose-700',
    ];
    $tampil = array_filter($def['kolom'], fn ($k) => $k['tampil'] ?? false);
    $selects = array_filter($def['kolom'], fn ($k) => $k['type'] === 'select');
@endphp

@section('content')
<div class="space-y-5">

    <!-- ===== HEADER ===== -->
    <div class="bg-gradient-to-r from-[#001D38] via-[#00264A] to-[#003B70] rounded-3xl p-6 text-white shadow-xl border border-blue-400/20">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-white p-2 shadow-lg flex-shrink-0">
                    <img src="{{ asset('images/logo_tvri.png') }}" alt="Logo TVRI" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="text-xl font-black tracking-tight uppercase leading-tight">{{ $def['nama'] }}</h1>
                    <p class="text-sm font-extrabold text-cyan-300 tracking-widest uppercase">Dashboard Tata Usaha &bull; TVRI Maluku</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('tvri.tu.dashboard') }}" class="px-4 py-2 rounded-xl bg-white/10 border border-white/20 text-xs font-bold hover:bg-white/20">&larr; Dashboard TU</a>
                <a href="{{ route('tvri.tu.create', $entitas) }}" class="px-4 py-2 rounded-xl bg-[#E31837] text-xs font-extrabold hover:bg-[#c01230] shadow-lg">+ Tambah {{ $def['nama'] }}</a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl px-4 py-3 text-sm font-semibold">{{ session('success') }}</div>
    @endif

    <!-- ===== TAB ENTITAS ===== -->
    <div class="flex flex-wrap gap-2">
        @foreach (App\Http\Controllers\Tvri\TataUsahaController::ENTITAS as $key => $e)
            <a href="{{ route('tvri.tu.list', $key) }}" class="px-4 py-2 rounded-xl text-xs font-bold border transition-colors {{ $key === $entitas ? 'bg-[#003B70] text-white border-[#003B70]' : 'bg-white text-slate-600 border-slate-200 hover:border-[#005B9F]' }}">{{ $e['nama'] }}</a>
        @endforeach
    </div>

    <!-- ===== TABEL + FILTER ===== -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-3">
            <p class="text-[12px] font-extrabold uppercase tracking-wider text-[#00264A]">Data {{ $def['nama'] }} &bull; {{ number_format($rows->total()) }} records</p>
            <form method="GET" action="{{ route('tvri.tu.list', $entitas) }}" class="flex flex-wrap items-center gap-2">
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari..." class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#005B9F] w-44">
                @foreach ($selects as $nama => $kolom)
                    <select name="f_{{ $nama }}" class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#005B9F]">
                        <option value="">Semua {{ $kolom['label'] }}</option>
                        @foreach ($kolom['opsi'] as $opsi)
                            <option value="{{ $opsi }}" @selected(request('f_' . $nama) == $opsi)>{{ $opsi }}</option>
                        @endforeach
                    </select>
                @endforeach
                <button class="px-4 py-1.5 rounded-xl bg-[#003B70] text-white text-xs font-bold hover:bg-[#00264A]">Filter</button>
                <a href="{{ route('tvri.tu.list', $entitas) }}" class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-500 hover:bg-slate-50">Reset</a>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-[#001D38] text-white">
                    <tr>
                        @foreach ($tampil as $kolom)
                            <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider">{{ $kolom['label'] }}</th>
                        @endforeach
                        <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($rows as $row)
                        <tr class="hover:bg-blue-50/40 transition-colors">
                            @foreach ($tampil as $nama => $kolom)
                                <td class="px-4 py-3 text-[11px] font-semibold text-slate-600 {{ $nama === array_key_first($tampil) ? 'font-bold text-slate-800' : '' }}">
                                    @if ($kolom['type'] === 'select')
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold {{ $badge[$row->$nama] ?? 'bg-slate-100 text-slate-600' }}">{{ $row->$nama }}</span>
                                    @elseif ($kolom['type'] === 'date')
                                        {{ $row->$nama?->format('d/m/Y') }}
                                    @elseif ($kolom['type'] === 'number')
                                        {{ number_format($row->$nama) }}
                                    @else
                                        {{ Str::limit($row->$nama, 60) }}
                                    @endif
                                </td>
                            @endforeach
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('tvri.tu.edit', [$entitas, $row->id]) }}" class="px-2.5 py-1 rounded-lg bg-blue-50 text-[#003B70] text-[10px] font-bold hover:bg-blue-100">Ubah</a>
                                    <form method="POST" action="{{ route('tvri.tu.destroy', [$entitas, $row->id]) }}" onsubmit="return confirm('Hapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 text-[10px] font-bold hover:bg-rose-100">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ count($tampil) + 1 }}" class="px-4 py-8 text-center text-xs text-slate-400 font-semibold">Tidak ada data yang cocok dengan filter.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">{{ $rows->links() }}</div>
    </div>
</div>
@endsection
