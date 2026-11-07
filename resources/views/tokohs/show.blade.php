@extends('layouts.app')
@section('title', 'Detail Tokoh Masyarakat')
@section('page-title', 'Detail Tokoh')
@section('breadcrumbs')
    <li>Manajemen Data</li>
    <li><a href="{{ route('tokohs.index') }}">Tokoh Masyarakat</a></li>
    <li>Detail</li>
@endsection

@section('page-actions')
    <a href="{{ route('tokohs.edit', $tokoh->id) }}" class="btn btn-warning">
        <i class="fa-solid fa-pen-to-square mr-2"></i> Edit Data
    </a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="col-span-1">
        <div class="card bg-base-100 shadow-xl border border-base-200">
            <figure class="px-10 pt-10">
                @if($tokoh->foto)
                    <img src="{{ Storage::url($tokoh->foto) }}" alt="Foto" class="rounded-xl w-48 shadow-lg" />
                @else
                    <div class="w-48 h-48 bg-base-200 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fa-solid fa-user text-6xl text-base-content/30"></i>
                    </div>
                @endif
            </figure>
            <div class="card-body items-center text-center">
                <h2 class="card-title text-2xl">{{ $tokoh->nama_lengkap }}</h2>
                <p class="text-base-content/70">{{ $tokoh->kategori_tokoh->name ?? 'Belum ada kategori' }}</p>
                <div class="mt-2">
                    @if($tokoh->status_verifikasi == 'Disetujui')
                        <div class="badge badge-success gap-2"><i class="fa-solid fa-check"></i> Disetujui</div>
                    @else
                        <div class="badge badge-warning gap-2"><i class="fa-solid fa-clock"></i> {{ $tokoh->status_verifikasi }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-span-1 lg:col-span-2">
        <div class="card bg-base-100 shadow-xl border border-base-200">
            <div class="card-body">
                <h2 class="card-title border-b pb-2">Informasi Lengkap</h2>
                <table class="table w-full">
                    <tbody>
                        <tr><td class="w-1/3 font-semibold">NIK</td><td>{{ $tokoh->nik }}</td></tr>
                        <tr><td class="font-semibold">Tempat, Tgl Lahir</td><td>{{ $tokoh->tempat_lahir }}, {{ $tokoh->tanggal_lahir->format('d M Y') }}</td></tr>
                        <tr><td class="font-semibold">Jenis Kelamin</td><td>{{ $tokoh->jenis_kelamin }}</td></tr>
                        <tr><td class="font-semibold">Agama</td><td>{{ $tokoh->agama->name ?? '-' }}</td></tr>
                        <tr><td class="font-semibold">Alamat Lengkap</td><td>{{ $tokoh->alamat }}, Desa {{ $tokoh->desa->name ?? '-' }}, Kec. {{ $tokoh->kecamatan->name ?? '-' }}, {{ $tokoh->kabupaten->name ?? '-' }}, {{ $tokoh->provinsi->name ?? '-' }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection