@extends('layouts.app')

@section('title', 'Detail Permohonan: ' . $layanan->nomor_agenda)

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    <!-- Top Navigation Breadcrumbs & Actions -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-xs font-semibold text-slate-500">
            <a href="{{ route('kepegawaian.layanan.index') }}" class="hover:text-blue-600">Subbag Kepegawaian & Umum</a>
            <span>/</span>
            <span class="text-slate-800 truncate">{{ $layanan->nomor_agenda }}</span>
        </div>

        <div class="flex items-center space-x-2">
            <a href="{{ route('kepegawaian.layanan.index') }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Kembali</span>
            </a>
            <a href="{{ route('kepegawaian.layanan.edit', $layanan->id) }}" class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <span>Proses / Ubah Status</span>
            </a>
            <a href="{{ route('kepegawaian.layanan.download', ['id' => $layanan->id, 'type' => $layanan->file_hasil_sk ? 'sk' : 'syarat']) }}" class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                <span>Unduh Berkas / Tanda Bukti</span>
            </a>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold">&times;</button>
        </div>
    @endif

    <!-- Main Header Card -->
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
            <div class="space-y-3 max-w-2xl">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-1 text-xs font-black rounded-lg border {{ $layanan->jenis_layanan_config['badge'] }}">
                        {{ $layanan->jenis_layanan }}
                    </span>
                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg border {{ $layanan->status_config['badge'] }}">
                        Status: {{ $layanan->status_config['label'] }}
                    </span>
                </div>

                <div>
                    <span class="font-mono text-xs font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded border border-slate-200 inline-block mb-1">
                        AGENDA: {{ $layanan->nomor_agenda }}
                    </span>
                    <h1 class="text-xl lg:text-2xl font-black text-[#00264A]">
                        {{ $layanan->nama_pemohon }}
                    </h1>
                    <p class="text-xs text-slate-500 font-mono mt-0.5">NIP/NIK: {{ $layanan->nip_nik_nisn ?: 'Non-NIP' }} • {{ $layanan->pangkat_golongan ?: 'Non-ASN' }}</p>
                </div>

                <p class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <strong class="text-slate-700 block mb-1">Perihal / Deskripsi Pengajuan:</strong>
                    {{ $layanan->perihal_deskripsi ?: 'Tidak ada catatan deskripsi khusus.' }}
                </p>
            </div>

            <!-- Mini Summary Box -->
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 flex flex-col justify-between space-y-3 flex-shrink-0 w-64 text-xs">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Unit Kerja / Sekolah</p>
                    <p class="font-bold text-slate-800 mt-0.5">{{ $layanan->jabatan_unit_kerja }}</p>
                    <p class="text-[11px] text-slate-500">{{ $layanan->kabupaten_kota }}</p>
                </div>

                <div class="pt-2 border-t border-slate-200">
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Tanggal Permohonan</p>
                    <p class="font-semibold text-slate-800 mt-0.5">{{ $layanan->tanggal_permohonan ? $layanan->tanggal_permohonan->format('d F Y') : '-' }}</p>
                </div>

                @if($layanan->nomor_sk_surat)
                    <div class="pt-2 border-t border-slate-200 bg-emerald-50 -mx-4 -mb-4 p-4 rounded-b-2xl border-emerald-200">
                        <p class="text-[10px] font-black text-emerald-800 uppercase">Nomor SK / Surat Terbit</p>
                        <p class="font-mono font-bold text-emerald-900 mt-0.5">{{ $layanan->nomor_sk_surat }}</p>
                        <p class="text-[10px] text-emerald-700">Tgl Terbit: {{ $layanan->tanggal_selesai ? $layanan->tanggal_selesai->format('d/m/Y') : '-' }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Status Timeline & Verifikasi Progress -->
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm space-y-4">
        <h3 class="text-sm font-bold text-[#00264A] pb-2 border-b border-slate-100">Tahapan Alur Pelayanan Kepegawaian</h3>

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            <!-- Step 1: Pendaftaran -->
            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 space-y-1">
                <div class="flex items-center space-x-1.5 text-emerald-800 font-bold">
                    <span>✓</span>
                    <span>1. Pendaftaran Berkas</span>
                </div>
                <p class="text-[10px] text-emerald-600">Didaftarkan pada {{ $layanan->tanggal_permohonan ? $layanan->tanggal_permohonan->format('d/m/Y') : '-' }}</p>
            </div>

            <!-- Step 2: Verifikasi & Telaah -->
            @php
                $isStep2Done = in_array($layanan->status_layanan, ['PROSES_VERIFIKASI', 'DISETUJUI', 'SELESAI']);
            @endphp
            <div class="p-3.5 rounded-xl {{ $isStep2Done ? 'bg-emerald-50 border border-emerald-200 text-emerald-800' : 'bg-slate-50 border border-slate-200 text-slate-400' }} space-y-1">
                <div class="flex items-center space-x-1.5 font-bold">
                    <span>{{ $isStep2Done ? '✓' : '○' }}</span>
                    <span>2. Telaah & Verifikasi</span>
                </div>
                <p class="text-[10px] {{ $isStep2Done ? 'text-emerald-600' : 'text-slate-400' }}">Verifikasi berkas persyaratan</p>
            </div>

            <!-- Step 3: Persetujuan / Draf SK -->
            @php
                $isStep3Done = in_array($layanan->status_layanan, ['DISETUJUI', 'SELESAI']);
            @endphp
            <div class="p-3.5 rounded-xl {{ $isStep3Done ? 'bg-emerald-50 border border-emerald-200 text-emerald-800' : 'bg-slate-50 border border-slate-200 text-slate-400' }} space-y-1">
                <div class="flex items-center space-x-1.5 font-bold">
                    <span>{{ $isStep3Done ? '✓' : '○' }}</span>
                    <span>3. Persetujuan Pimpinan</span>
                </div>
                <p class="text-[10px] {{ $isStep3Done ? 'text-emerald-600' : 'text-slate-400' }}">Draf SK disetujui / BKD</p>
            </div>

            <!-- Step 4: Selesai & Terbit -->
            @php
                $isStep4Done = ($layanan->status_layanan === 'SELESAI');
            @endphp
            <div class="p-3.5 rounded-xl {{ $isStep4Done ? 'bg-emerald-50 border border-emerald-200 text-emerald-800' : 'bg-slate-50 border border-slate-200 text-slate-400' }} space-y-1">
                <div class="flex items-center space-x-1.5 font-bold">
                    <span>{{ $isStep4Done ? '✓' : '○' }}</span>
                    <span>4. SK / Surat Terbit</span>
                </div>
                <p class="text-[10px] {{ $isStep4Done ? 'text-emerald-600' : 'text-slate-400' }}">Dokumen selesai diproses</p>
            </div>
        </div>
    </div>

    <!-- 2 Columns: Berkas & Catatan Petugas -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Berkas Pengajuan & SK Terbit -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-[#00264A] pb-2 border-b border-slate-100">Berkas & Dokumen Terlampir</h3>

            <div class="space-y-3">
                <!-- Berkas Syarat -->
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                            📄
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Berkas Syarat Permohonan</p>
                            <p class="text-[10px] text-slate-400">{{ $layanan->file_permohonan ? basename($layanan->file_permohonan) : 'Berkas fisik terlampir' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('kepegawaian.layanan.download', ['id' => $layanan->id, 'type' => 'syarat']) }}" class="px-3 py-1.5 bg-[#003B70] hover:bg-[#00264A] text-white text-[11px] font-bold rounded-lg transition-colors">
                        Unduh
                    </a>
                </div>

                <!-- Berkas SK Hasil (Jika Terbit) -->
                <div class="p-3.5 rounded-xl {{ $layanan->file_hasil_sk ? 'bg-emerald-50 border-emerald-200' : 'bg-slate-50 border-slate-200' }} border flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-lg {{ $layanan->file_hasil_sk ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center font-bold">
                            🏛️
                        </div>
                        <div>
                            <p class="text-xs font-bold {{ $layanan->file_hasil_sk ? 'text-emerald-900' : 'text-slate-600' }}">Surat Keputusan (SK) / Surat Izin Resmi</p>
                            <p class="text-[10px] {{ $layanan->file_hasil_sk ? 'text-emerald-700' : 'text-slate-400' }}">
                                {{ $layanan->nomor_sk_surat ? ('No: ' . $layanan->nomor_sk_surat) : 'Belum diterbitkan' }}
                            </p>
                        </div>
                    </div>
                    @if($layanan->file_hasil_sk)
                        <a href="{{ route('kepegawaian.layanan.download', ['id' => $layanan->id, 'type' => 'sk']) }}" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold rounded-lg transition-colors">
                            Unduh SK
                        </a>
                    @else
                        <a href="{{ route('kepegawaian.layanan.edit', $layanan->id) }}" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-[11px] font-bold rounded-lg transition-colors">
                            Unggah SK
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Catatan Petugas & Tanda Tangan -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-[#00264A] pb-2 border-b border-slate-100">Catatan Petugas & Penanganan</h3>

            <div class="space-y-3 text-xs">
                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Catatan Verifikator Kepegawaian</p>
                    <p class="text-slate-700 mt-1 italic leading-relaxed">
                        "{{ $layanan->catatan_petugas ?: 'Berkas permohonan dalam penanganan tertib administrasi Subbag Kepegawaian & Umum.' }}"
                    </p>
                </div>

                <div class="flex items-center justify-between text-slate-500 pt-2 border-t border-slate-100">
                    <span>Petugas Verifikasi:</span>
                    <strong class="text-slate-800">{{ $layanan->petugas_nama }}</strong>
                </div>

                <div class="flex items-center justify-between text-slate-500">
                    <span>Terakhir Diperbarui:</span>
                    <strong class="text-slate-800">{{ $layanan->updated_at->format('d/m/Y H:i') }} WIT</strong>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
