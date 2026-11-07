@extends('layouts.app')

@section('title', ($mode === 'create' ? 'Tambah' : 'Ubah') . ' Berita - TVRI Maluku')

@php use App\Http\Controllers\Tvri\BeritaController; @endphp

@section('content')
<div class="space-y-5 max-w-3xl mx-auto">

    <div class="bg-gradient-to-r from-[#001D38] to-[#003B70] rounded-3xl p-5 text-white shadow-lg flex items-center justify-between border border-blue-400/20">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-white p-1.5"><img src="{{ asset('images/logo_tvri.png') }}" alt="Logo TVRI" class="w-full h-full object-contain"></div>
            <div>
                <h1 class="text-base font-black uppercase tracking-tight">{{ $mode === 'create' ? 'Tambah Data Liputan' : 'Ubah Data Liputan' }}</h1>
                <p class="text-[11px] font-bold text-cyan-300 uppercase tracking-wider">Dashboard Pemberitaan &bull; TVRI Newsroom</p>
            </div>
        </div>
        <a href="{{ route('tvri.berita.dashboard') }}" class="px-3 py-1.5 rounded-xl bg-white/10 border border-white/20 text-[11px] font-bold hover:bg-white/20">&larr; Kembali</a>
    </div>

    <form method="POST" action="{{ $mode === 'create' ? route('tvri.berita.store') : route('tvri.berita.update', $berita) }}"
          class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
        @csrf
        @if ($mode === 'edit') @method('PUT') @endif

        <div>
            <label class="text-[11px] font-extrabold uppercase tracking-wider text-[#00264A]">Judul Liputan <span class="text-rose-600">*</span></label>
            <input type="text" name="judul" value="{{ old('judul', $berita->judul) }}" required maxlength="255"
                   class="mt-1 w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#005B9F] @error('judul') border-rose-400 @enderror">
            @error('judul')<p class="text-[10px] text-rose-600 font-semibold mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="text-[11px] font-extrabold uppercase tracking-wider text-[#00264A]">Kategori <span class="text-rose-600">*</span></label>
                <select name="kategori" required class="mt-1 w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#005B9F]">
                    @foreach (BeritaController::KATEGORI as $kat)
                        <option value="{{ $kat }}" @selected(old('kategori', $berita->kategori) == $kat)>{{ $kat }}</option>
                    @endforeach
                </select>
                @error('kategori')<p class="text-[10px] text-rose-600 font-semibold mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-[11px] font-extrabold uppercase tracking-wider text-[#00264A]">Jenis Berita <span class="text-rose-600">*</span></label>
                <select name="jenis" required class="mt-1 w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#005B9F]">
                    @foreach (BeritaController::JENIS as $jen)
                        <option value="{{ $jen }}" @selected(old('jenis', $berita->jenis) == $jen)>{{ $jen }}</option>
                    @endforeach
                </select>
                @error('jenis')<p class="text-[10px] text-rose-600 font-semibold mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-[11px] font-extrabold uppercase tracking-wider text-[#00264A]">Lokasi Liputan</label>
                <select name="lokasi" class="mt-1 w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#005B9F]">
                    <option value="">— Pilih lokasi —</option>
                    @foreach (BeritaController::LOKASI as $lok)
                        <option value="{{ $lok }}" @selected(old('lokasi', $berita->lokasi) == $lok)>{{ $lok }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[11px] font-extrabold uppercase tracking-wider text-[#00264A]">Status <span class="text-rose-600">*</span></label>
                <select name="status" required class="mt-1 w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#005B9F]">
                    @foreach (BeritaController::STATUS as $st)
                        <option value="{{ $st }}" @selected(old('status', $berita->status ?? 'Rencana') == $st)>{{ $st }}</option>
                    @endforeach
                </select>
                @error('status')<p class="text-[10px] text-rose-600 font-semibold mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-[11px] font-extrabold uppercase tracking-wider text-[#00264A]">Reporter / Tim</label>
                <input type="text" name="reporter" value="{{ old('reporter', $berita->reporter) }}" maxlength="100"
                       class="mt-1 w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#005B9F]" placeholder="mis. Tim 1">
            </div>
            <div>
                <label class="text-[11px] font-extrabold uppercase tracking-wider text-[#00264A]">Views (jika sudah tayang)</label>
                <input type="number" name="views" value="{{ old('views', $berita->views ?? 0) }}" min="0"
                       class="mt-1 w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#005B9F]">
            </div>
            <div>
                <label class="text-[11px] font-extrabold uppercase tracking-wider text-[#00264A]">Tanggal Rencana Liputan <span class="text-rose-600">*</span></label>
                <input type="date" name="tanggal_rencana" value="{{ old('tanggal_rencana', $berita->tanggal_rencana?->format('Y-m-d')) }}" required
                       class="mt-1 w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#005B9F] @error('tanggal_rencana') border-rose-400 @enderror">
                @error('tanggal_rencana')<p class="text-[10px] text-rose-600 font-semibold mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-[11px] font-extrabold uppercase tracking-wider text-[#00264A]">Tanggal Tayang</label>
                <input type="date" name="tanggal_tayang" value="{{ old('tanggal_tayang', $berita->tanggal_tayang?->format('Y-m-d')) }}"
                       class="mt-1 w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#005B9F] @error('tanggal_tayang') border-rose-400 @enderror">
                @error('tanggal_tayang')<p class="text-[10px] text-rose-600 font-semibold mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
            <a href="{{ route('tvri.berita.dashboard') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-500 hover:bg-slate-50">Batal</a>
            <button class="px-5 py-2 rounded-xl bg-[#E31837] text-white text-xs font-extrabold hover:bg-[#c01230] shadow-lg">
                {{ $mode === 'create' ? 'Simpan Berita' : 'Perbarui Berita' }}
            </button>
        </div>
    </form>
</div>
@endsection
