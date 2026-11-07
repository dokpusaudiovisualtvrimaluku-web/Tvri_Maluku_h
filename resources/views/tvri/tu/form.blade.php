@extends('layouts.app')

@section('title', ($mode === 'create' ? 'Tambah' : 'Ubah') . ' ' . $def['nama'] . ' - Tata Usaha TVRI Maluku')

@section('content')
<div class="space-y-5 max-w-3xl mx-auto">

    <div class="bg-gradient-to-r from-[#001D38] to-[#003B70] rounded-3xl p-5 text-white shadow-lg flex items-center justify-between border border-blue-400/20">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-white p-1.5"><img src="{{ asset('images/logo_tvri.png') }}" alt="Logo TVRI" class="w-full h-full object-contain"></div>
            <div>
                <h1 class="text-base font-black uppercase tracking-tight">{{ $mode === 'create' ? 'Tambah' : 'Ubah' }} {{ $def['nama'] }}</h1>
                <p class="text-[11px] font-bold text-cyan-300 uppercase tracking-wider">Dashboard Tata Usaha &bull; TVRI Maluku</p>
            </div>
        </div>
        <a href="{{ route('tvri.tu.list', $entitas) }}" class="px-3 py-1.5 rounded-xl bg-white/10 border border-white/20 text-[11px] font-bold hover:bg-white/20">&larr; Kembali</a>
    </div>

    <form method="POST" action="{{ $mode === 'create' ? route('tvri.tu.store', $entitas) : route('tvri.tu.update', [$entitas, $row->id]) }}"
          class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
        @csrf
        @if ($mode === 'edit') @method('PUT') @endif

        @foreach ($def['kolom'] as $nama => $kolom)
            @php $value = old($nama, $kolom['type'] === 'date' ? $row->$nama?->format('Y-m-d') : $row->$nama); @endphp
            <div class="{{ in_array($kolom['type'], ['textarea']) ? '' : 'grid grid-cols-1 sm:grid-cols-2 gap-4 ' }}{{ $loop->first || $loop->last ? '' : '' }}">
                <div>
                    <label class="text-[11px] font-extrabold uppercase tracking-wider text-[#00264A]">{{ $kolom['label'] }} @if($kolom['required'])<span class="text-rose-600">*</span>@endif</label>
                    @if ($kolom['type'] === 'select')
                        <select name="{{ $nama }}" @required($kolom['required']) class="mt-1 w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#005B9F] @error($nama) border-rose-400 @enderror">
                            <option value="">— Pilih —</option>
                            @foreach ($kolom['opsi'] as $opsi)
                                <option value="{{ $opsi }}" @selected($value == $opsi)>{{ $opsi }}</option>
                            @endforeach
                        </select>
                    @elseif ($kolom['type'] === 'textarea')
                        <textarea name="{{ $nama }}" @required($kolom['required']) rows="3" maxlength="1000"
                                  class="mt-1 w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#005B9F] @error($nama) border-rose-400 @enderror">{{ $value }}</textarea>
                    @else
                        <input type="{{ $kolom['type'] }}" name="{{ $nama }}" value="{{ $value }}" @required($kolom['required'])
                               @if($kolom['type'] === 'number') min="0" step="1" @endif maxlength="255"
                               class="mt-1 w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#005B9F] @error($nama) border-rose-400 @enderror">
                    @endif
                    @error($nama)<p class="text-[10px] text-rose-600 font-semibold mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        @endforeach

        <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
            <a href="{{ route('tvri.tu.list', $entitas) }}" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-500 hover:bg-slate-50">Batal</a>
            <button class="px-5 py-2 rounded-xl bg-[#E31837] text-white text-xs font-extrabold hover:bg-[#c01230] shadow-lg">
                {{ $mode === 'create' ? 'Simpan Data' : 'Perbarui Data' }}
            </button>
        </div>
    </form>
</div>
@endsection
