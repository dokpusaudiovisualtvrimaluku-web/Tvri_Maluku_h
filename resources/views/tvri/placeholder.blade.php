@extends('layouts.app')

@php
    $bidangInfo = [
        'tu'        => ['nama' => 'Tata Usaha',            'sub' => 'Surat Masuk & Keluar, Disposisi, Agenda Kegiatan, Layanan Administrasi, Arsip & Dokumentasi, SOP'],
        'umum'      => ['nama' => 'Umum & Kepegawaian',    'sub' => 'Manajemen Aset, Kendaraan Dinas, Sarana Prasarana, Pemeliharaan, Keamanan & K3, Pengadaan & Logistik'],
        'transmisi' => ['nama' => 'Transmisi',             'sub' => 'Peta Transmisi, Stasiun Pemancar, Monitoring Siaran, Kualitas Sinyal, Daya & Energi, Alarm & Gangguan'],
        'teknik'    => ['nama' => 'Teknik',                'sub' => 'Siaran & Transmitter, Studio & Produksi, Peralatan & Aset, Maintenance, Monitoring Sistem, Insiden & Gangguan'],
        'berita'    => ['nama' => 'Berita / Newsroom',     'sub' => 'Rencana Liputan, Liputan, Produksi Berita, Tayang & Distribusi, Current Affairs, Breaking News, Arsip Berita'],
        'program'   => ['nama' => 'Program & Siaran',      'sub' => 'Perencanaan, Pra Produksi, Produksi, Pasca Produksi, Penayangan, Evaluasi, Kalender Program'],
        'usaha'     => ['nama' => 'Pengembangan Usaha',    'sub' => 'Pendapatan, Kemitraan, Program Usaha, Iklan & Sponsorship, Event & Aktivasi, Aset & Fasilitas'],
        'keuangan'  => ['nama' => 'Keuangan',              'sub' => 'Realisasi Anggaran, Realisasi PNBP, Perencanaan Anggaran, Pembayaran, Piutang & Tagihan, Aset & BMN'],
        'mbn'       => ['nama' => 'Konten Media Baru',     'sub' => 'Konten, Publishing, Analitik, Audiens, Media Sosial, Live Streaming, Website, Monetisasi'],
        'promo'     => ['nama' => 'Promo & Identitas',     'sub' => 'Perencanaan, Produksi Materi, Penayangan, Kampanye, Media Digital, Monitoring & Analitik'],
    ];
    $info = $bidangInfo[$bidang] ?? ['nama' => ucfirst($bidang), 'sub' => ''];
@endphp

@section('title', 'Dashboard ' . $info['nama'] . ' - TVRI Maluku')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-gradient-to-r from-[#00264A] via-[#003B70] to-[#005B9F] rounded-3xl p-8 text-white shadow-xl relative overflow-hidden border border-blue-400/20">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10">
            <div class="inline-flex items-center space-x-2 px-3 py-1 bg-cyan-500/20 rounded-full text-[11px] font-bold text-cyan-300 uppercase tracking-widest border border-cyan-500/30 mb-4">
                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                <span>TVRI Maluku &bull; Bidang {{ $info['nama'] }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight uppercase">DASHBOARD {{ strtoupper($info['nama']) }}</h1>
            <p class="text-sm text-blue-200 font-semibold mt-2">{{ $info['sub'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-8 mt-6 text-center">
        <div class="w-14 h-14 rounded-2xl bg-[#EAF4FB] text-[#003B70] mx-auto flex items-center justify-center mb-4">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
        </div>
        <h2 class="text-lg font-extrabold text-[#00264A]">Sedang Dibangun</h2>
        <p class="text-sm text-slate-500 mt-2 max-w-md mx-auto">
            Dashboard beserta fitur CRUD data untuk bidang <strong>{{ $info['nama'] }}</strong> akan dibangun pada tahap pengembangan berikutnya,
            terhubung langsung dengan Executive Command Center Kepala Stasiun.
        </p>
        <a href="{{ url()->previous() }}" class="inline-flex items-center space-x-2 mt-6 px-5 py-2.5 bg-[#005B9F] hover:bg-[#003B70] text-white text-sm font-bold rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Kembali</span>
        </a>
    </div>
</div>
@endsection
