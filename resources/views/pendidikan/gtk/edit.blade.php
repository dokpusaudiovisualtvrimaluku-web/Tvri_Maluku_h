@extends('layouts.app')

@section('title', 'Edit Layanan GTK - ' . $layanan->nomor_registrasi)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Breadcrumb & Back -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-xs text-slate-500">
            <a href="{{ route('pendidikan.gtk.layanan.index') }}" class="hover:text-blue-600 font-medium">Layanan GTK</a>
            <span>/</span>
            <a href="{{ route('pendidikan.gtk.layanan.show', $layanan->id) }}" class="hover:text-blue-600 font-medium">{{ $layanan->nomor_registrasi }}</a>
            <span>/</span>
            <span class="text-slate-800 font-bold">Edit Dokumen</span>
        </div>
        <a href="{{ route('pendidikan.gtk.layanan.show', $layanan->id) }}" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Kembali ke Detail</span>
        </a>
    </div>

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-[#00264A] to-[#004880] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 bg-amber-400/20 text-amber-200 border border-amber-400/40 rounded-full text-[11px] font-bold uppercase">
                    Edit & Perbarui Dokumen
                </span>
                <span class="font-mono text-xs text-blue-200">{{ $layanan->nomor_registrasi }}</span>
            </div>
            <h1 class="text-2xl font-black text-white mt-2">{{ $layanan->nama_lengkap_guru }}</h1>
            <p class="text-xs text-blue-100 max-w-2xl mt-1 leading-relaxed">
                Perbarui data identitas guru, status verifikasi permohonan, atau unggah dokumen surat keputusan / SK hasil layanan GTK.
            </p>
        </div>
    </div>

    <!-- Error Validation Alert -->
    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
            <p class="font-bold mb-1">Terdapat kesalahan input formulir:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Container -->
    <form action="{{ route('pendidikan.gtk.layanan.update', $layanan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- 1. Pilihan Jenis Layanan & Info Box -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
            <h2 class="text-sm font-bold text-[#00264A] uppercase tracking-wider flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-800 inline-flex items-center justify-center text-xs font-black">1</span>
                Katalog & Standar Layanan
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Layanan GTK <span class="text-rose-500">*</span></label>
                    <select id="jenis_layanan" name="jenis_layanan" onchange="updateServiceGuide()" required
                            class="w-full py-2.5 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none bg-slate-50/50 font-semibold text-slate-800">
                        @foreach($jenisLayananOptions as $key => $meta)
                            <option value="{{ $key }}" {{ old('jenis_layanan', $layanan->jenis_layanan) == $key ? 'selected' : '' }}>
                                {{ $key }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Info Guide Box (Dynamic) -->
                <div id="serviceGuide" class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                    <!-- Populated by JS -->
                </div>
            </div>
        </div>

        <!-- 2. Data Guru & Kepegawaian -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
            <h2 class="text-sm font-bold text-[#00264A] uppercase tracking-wider flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-800 inline-flex items-center justify-center text-xs font-black">2</span>
                Identitas Guru / Tenaga Kependidikan
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Guru / PTK (dengan Gelar) <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_lengkap_guru" value="{{ old('nama_lengkap_guru', $layanan->nama_lengkap_guru) }}" required
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                </div>

                <!-- NIP / NIK -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">NIP / NIK</label>
                    <input type="text" name="nip_nik" value="{{ old('nip_nik', $layanan->nip_nik) }}"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                </div>

                <!-- NUPTK -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">NUPTK</label>
                    <input type="text" name="nuptk" value="{{ old('nuptk', $layanan->nuptk) }}" placeholder="16 digit NUPTK"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none font-mono">
                </div>

                <!-- Status Kepegawaian -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status Kepegawaian <span class="text-rose-500">*</span></label>
                    <select name="status_kepegawaian" required class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                        @foreach($statusKepegawaianOptions as $key => $label)
                            <option value="{{ $key }}" {{ old('status_kepegawaian', $layanan->status_kepegawaian) == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- 3. Satuan Pendidikan & Wilayah Tugas -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
            <h2 class="text-sm font-bold text-[#00264A] uppercase tracking-wider flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-800 inline-flex items-center justify-center text-xs font-black">3</span>
                Satuan Pendidikan & Wilayah Tugas
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Sekolah -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Satuan Pendidikan <span class="text-rose-500">*</span></label>
                    <input type="text" name="tempat_tugas_sekolah" value="{{ old('tempat_tugas_sekolah', $layanan->tempat_tugas_sekolah) }}" required
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                </div>

                <!-- NPSN Sekolah -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">NPSN Sekolah (Opsional)</label>
                    <input type="text" name="npsn_sekolah" value="{{ old('npsn_sekolah', $layanan->npsn_sekolah) }}" placeholder="8 digit NPSN"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none font-mono">
                </div>

                <!-- Jenjang -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jenjang Sekolah <span class="text-rose-500">*</span></label>
                    <select name="jenjang_sekolah" required class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                        @foreach($jenjangList as $jenjang)
                            <option value="{{ $jenjang }}" {{ old('jenjang_sekolah', $layanan->jenjang_sekolah) == $jenjang ? 'selected' : '' }}>{{ $jenjang }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Kabupaten/Kota -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kabupaten / Kota <span class="text-rose-500">*</span></label>
                    <select name="kabupaten_kota" required class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                        @foreach($kabupatenKotaList as $kab)
                            <option value="{{ $kab }}" {{ old('kabupaten_kota', $layanan->kabupaten_kota) == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Pengajuan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Pengajuan <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_pengajuan" value="{{ old('tanggal_pengajuan', $layanan->tanggal_pengajuan ? $layanan->tanggal_pengajuan->format('Y-m-d') : date('Y-m-d')) }}" required
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                </div>

                <!-- Tanggal Selesai -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Selesai / SK</label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $layanan->tanggal_selesai ? $layanan->tanggal_selesai->format('Y-m-d') : '') }}"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                </div>

                <!-- No SK / Rekomendasi -->
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor SK / Rekomendasi</label>
                    <input type="text" name="nomor_sk_rekomendasi" value="{{ old('nomor_sk_rekomendasi', $layanan->nomor_sk_rekomendasi) }}" placeholder="Nomor SK bila sudah terbit"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <!-- Detail Permohonan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Perihal / Uraian Detail Permohonan <span class="text-rose-500">*</span></label>
                <textarea name="perihal_uraian" rows="3" required
                          class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">{{ old('perihal_uraian', $layanan->perihal_uraian) }}</textarea>
            </div>
        </div>

        <!-- 4. Berkas Persyaratan & Dokumen Hasil -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
            <h2 class="text-sm font-bold text-[#00264A] uppercase tracking-wider flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-800 inline-flex items-center justify-center text-xs font-black">4</span>
                Unggah Dokumen & Status Verifikasi
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- File Permohonan -->
                <div class="p-4 rounded-xl bg-blue-50/50 border border-blue-100">
                    <label class="block text-xs font-bold text-blue-900 mb-1">Ganti Berkas Persyaratan (PDF / ZIP)</label>
                    @if($layanan->file_permohonan)
                        <div class="mb-2 p-2 rounded-lg bg-white border border-blue-200 flex items-center justify-between text-xs">
                            <span class="text-slate-600 truncate">{{ $layanan->file_nama_permohonan ?? 'Berkas saat ini tersedia' }}</span>
                            <a href="{{ route('pendidikan.gtk.layanan.download', ['id' => $layanan->id, 'type' => 'permohonan']) }}" class="text-blue-600 hover:text-blue-800 font-bold ml-2">Unduh</a>
                        </div>
                    @endif
                    <input type="file" name="file_permohonan" accept=".pdf,.zip,.doc,.docx,.jpg,.jpeg,.png"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                </div>

                <!-- File SK Hasil -->
                <div class="p-4 rounded-xl bg-emerald-50/50 border border-emerald-100">
                    <label class="block text-xs font-bold text-emerald-900 mb-1">Unggah/Ganti SK / Sertifikat Hasil</label>
                    @if($layanan->file_hasil_sk)
                        <div class="mb-2 p-2 rounded-lg bg-white border border-emerald-200 flex items-center justify-between text-xs">
                            <span class="text-slate-600 truncate">{{ $layanan->file_nama_hasil_sk ?? 'SK Hasil saat ini tersedia' }}</span>
                            <a href="{{ route('pendidikan.gtk.layanan.download', ['id' => $layanan->id, 'type' => 'sk']) }}" class="text-emerald-600 hover:text-emerald-800 font-bold ml-2">Unduh</a>
                        </div>
                    @endif
                    <input type="file" name="file_hasil_sk" accept=".pdf,.zip,.doc,.docx,.jpg,.jpeg,.png"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700">
                </div>

                <!-- Status Pengajuan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status Verifikasi <span class="text-rose-500">*</span></label>
                    <select name="status_layanan" required class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                        @foreach($statusOptions as $k => $st)
                            <option value="{{ $k }}" {{ old('status_layanan', $layanan->status_layanan) == $k ? 'selected' : '' }}>{{ $st['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Petugas Verifikator -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Petugas Verifikator</label>
                    <input type="text" name="petugas_verifikator" value="{{ old('petugas_verifikator', $layanan->petugas_verifikator) }}" placeholder="Nama petugas/operator dinas yang menangani"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                </div>

                <!-- Catatan Verifikasi -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Catatan / Keterangan Verifikasi Dinas</label>
                    <input type="text" name="catatan_verifikator" value="{{ old('catatan_verifikator', $layanan->catatan_verifikator) }}" placeholder="Catatan tim verifikator bidang GTK..."
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end space-x-3 pt-2">
            <a href="{{ route('pendidikan.gtk.layanan.show', $layanan->id) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-[#00264A] hover:bg-[#003B70] text-white text-xs font-bold rounded-xl shadow-lg hover:shadow-blue-900/30 transition-all duration-200">
                Simpan Perubahan
            </button>
        </div>
    </form>

</div>

@push('scripts')
<script>
    const guideData = {
        'Sertifikasi Pendidik': {
            title: 'Standar Layanan Sertifikasi Pendidik',
            items: [
                'Pemberkasan PPG Dalam Jabatan / Prajabatan',
                'Penerbitan Nomor Registrasi Guru (NRG)',
                'Verifikasi Keaslian Sertifikat Pendidik & Ijazah S1 Linier',
                'Persyaratan: Scan Ijazah S1, Transkrip, SK Pengangkatan, Surat Rekomendasi'
            ],
            color: 'text-amber-800 border-amber-200 bg-amber-50/70'
        },
        'NUPTK': {
            title: 'Standar Pengajuan & Verval NUPTK',
            items: [
                'Penerbitan NUPTK Baru melalui Verval PTK Pusdatin',
                'Koreksi / Perbaikan Data Identitas, Nama Ibu Kandung, & Riwayat',
                'Validasi KTP, Ijazah SD-S1 Asli, dan SK Pengangkatan Guru',
                'Pastikan data di Dapodik sekolah sudah sinkron dan valid'
            ],
            color: 'text-blue-800 border-blue-200 bg-blue-50/70'
        },
        'Tunjangan Pendidik': {
            title: 'Standar Pengelolaan Tunjangan Profesi / Khusus / Tamsil',
            items: [
                'Validasi Info GTK & Penerbitan Surat Keputusan Tunjangan Profesi (SKTP)',
                'Tunjangan Khusus Guru (TKG) untuk daerah 3T / Kepulauan Maluku',
                'Tambahan Penghasilan (Tamsil) bagi Guru Non-Sertifikasi ASN',
                'Syarat: Beban mengajar minimal 24 JP linier, absensi DHGTK terisi'
            ],
            color: 'text-emerald-800 border-emerald-200 bg-emerald-50/70'
        },
        'SIMPKB / PKB': {
            title: 'Standar Layanan SIMPKB & Pengembangan Keprofesian Berkelanjutan',
            items: [
                'Aktivasi / Reset Password Akun SIMPKB & Komunitas KKG/MGMP',
                'Registrasi Seleksi Guru Penggerak, Pengawas Sekolah, & Kepala Sekolah',
                'Diklat Peningkatan Kompetensi & Verval Pelatihan Berkelanjutan (PKB)',
                'Syarat: NUPTK/PegID terdaftar, surat tugas kepala sekolah/organisasi mitra'
            ],
            color: 'text-purple-800 border-purple-200 bg-purple-50/70'
        }
    };

    function updateServiceGuide() {
        const sel = document.getElementById('jenis_layanan').value;
        const info = guideData[sel];
        const container = document.getElementById('serviceGuide');

        if (info) {
            let html = `<div class="p-3 rounded-lg border ${info.color}">`;
            html += `<h4 class="font-bold mb-1">${info.title}</h4>`;
            html += `<ul class="list-disc list-inside space-y-0.5 text-[11px] opacity-90">`;
            info.items.forEach(it => {
                html += `<li>${it}</li>`;
            });
            html += `</ul></div>`;
            container.innerHTML = html;
        }
    }

    document.addEventListener('DOMContentLoaded', updateServiceGuide);
</script>
@endpush
@endsection
