@extends('layouts.app')

@section('title', 'Buat Permohonan Layanan Bidang Kebudayaan - TVRI Maluku')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Breadcrumb & Back -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-xs text-slate-500">
            <a href="{{ route('pendidikan.gtk.layanan.index') }}" class="hover:text-blue-600 font-medium">Layanan Bidang Kebudayaan</a>
            <span>/</span>
            <span class="text-slate-800 font-bold">Buat Permohonan Baru</span>
        </div>
        <a href="{{ route('pendidikan.gtk.layanan.index') }}" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Kembali ke Daftar</span>
        </a>
    </div>

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-[#00264A] to-[#004880] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10">
            <span class="px-2.5 py-0.5 bg-amber-400/20 text-amber-200 border border-amber-400/40 rounded-full text-[11px] font-bold tracking-wider uppercase">
                Formulir Registrasi Dokumen Kebudayaan
            </span>
            <h1 class="text-2xl font-black text-white mt-2">Permohonan Layanan Bidang Kebudayaan</h1>
            <p class="text-xs text-blue-100 max-w-2xl mt-1 leading-relaxed">
                Silakan lengkapi formulir permohonan layanan Bidang Kebudayaan dengan data yang valid dan unggah berkas pendukung (PDF/ZIP).
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
    <form action="{{ route('pendidikan.gtk.layanan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- 1. Pilihan Jenis Layanan & Info Box -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
            <h2 class="text-sm font-bold text-[#00264A] uppercase tracking-wider flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-800 inline-flex items-center justify-center text-xs font-black">1</span>
                Katalog & Standar Layanan
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Layanan Kebudayaan <span class="text-rose-500">*</span></label>
                    <select id="jenis_layanan" name="jenis_layanan" onchange="updateServiceGuide()" required
                            class="w-full py-2.5 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none bg-slate-50/50 font-semibold text-slate-800">
                            <option value="Cagar Budaya & Permuseuman" {{ old('jenis_layanan') == 'Cagar Budaya & Permuseuman' ? 'selected' : '' }}>Cagar Budaya & Permuseuman</option>
                            <option value="Warisan Budaya Takbenda" {{ old('jenis_layanan') == 'Warisan Budaya Takbenda' ? 'selected' : '' }}>Warisan Budaya Takbenda</option>
                            <option value="Kesenian & Event" {{ old('jenis_layanan') == 'Kesenian & Event' ? 'selected' : '' }}>Kesenian & Event</option>
                            <option value="Komunitas & Lembaga Adat" {{ old('jenis_layanan') == 'Komunitas & Lembaga Adat' ? 'selected' : '' }}>Komunitas & Lembaga Adat</option>
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
                Identitas Pemohon & Lembaga / Sanggar Kebudayaan
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pemohon (Ketua Komunitas / Pengurus / Tokoh Adat / Seniman) <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_lengkap_guru" value="{{ old('nama_lengkap_guru') }}" required placeholder="Contoh: Dra. Maria Latumahina, M.Pd"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                </div>

                <!-- NIP / NIK -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">NIP / NIK</label>
                    <input type="text" name="nip_nik" value="{{ old('nip_nik') }}" placeholder="18 digit NIP atau 16 digit NIK"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                </div>

                <!-- NUPTK -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">NUPTK (Jika Ada / Dalam Pengusulan)</label>
                    <input type="text" name="nuptk" value="{{ old('nuptk') }}" placeholder="16 digit NUPTK (Opsional untuk pengusulan awal)"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none font-mono">
                </div>

                <!-- Status Kepegawaian -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status Kepegawaian <span class="text-rose-500">*</span></label>
                    <select name="status_kepegawaian" required class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                        @foreach($statusKepegawaianOptions as $key => $label)
                            <option value="{{ $key }}" {{ old('status_kepegawaian') == $key ? 'selected' : '' }}>{{ $label }}</option>
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
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lembaga / Sanggar / Komunitas Budaya <span class="text-rose-500">*</span></label>
                    <input type="text" name="tempat_tugas_sekolah" value="{{ old('tempat_tugas_sekolah') }}" required placeholder="Contoh: SMAN 1 Ambon / SMKN 2 Buru"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                </div>

                <!-- NPSN Sekolah -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">NPSN Sekolah (Opsional)</label>
                    <input type="text" name="npsn_sekolah" value="{{ old('npsn_sekolah') }}" placeholder="8 digit NPSN"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none font-mono">
                </div>

                <!-- Jenjang -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jenjang Sekolah <span class="text-rose-500">*</span></label>
                    <select name="jenjang_sekolah" required class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                        @foreach($jenjangList as $jenjang)
                            <option value="{{ $jenjang }}" {{ old('jenjang_sekolah', 'SMA') == $jenjang ? 'selected' : '' }}>{{ $jenjang }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Kabupaten/Kota -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kabupaten / Kota <span class="text-rose-500">*</span></label>
                    <select name="kabupaten_kota" required class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                        @foreach($kabupatenKotaList as $kab)
                            <option value="{{ $kab }}" {{ old('kabupaten_kota') == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Pengajuan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Pengajuan <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_pengajuan" value="{{ old('tanggal_pengajuan', date('Y-m-d')) }}" required
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                </div>

                <!-- No SK / Rekomendasi -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor SK / Rekomendasi (Opsional)</label>
                    <input type="text" name="nomor_sk_rekomendasi" value="{{ old('nomor_sk_rekomendasi') }}" placeholder="Nomor SK bila sudah terbit"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <!-- Detail Permohonan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Perihal / Uraian Detail Permohonan <span class="text-rose-500">*</span></label>
                <textarea name="perihal_uraian" rows="3" required placeholder="Jelaskan kebutuhan pengajuan secara rinci..."
                          class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">{{ old('perihal_uraian') }}</textarea>
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
                    <label class="block text-xs font-bold text-blue-900 mb-1">Unggah Berkas Persyaratan (PDF / ZIP)</label>
                    <p class="text-[11px] text-blue-700/80 mb-2">Gabungan berkas persyaratan sesuai ketentuan layanan (Maksimal 10 MB).</p>
                    <input type="file" name="file_permohonan" accept=".pdf,.zip,.doc,.docx,.jpg,.jpeg,.png"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                </div>

                <!-- File SK Hasil -->
                <div class="p-4 rounded-xl bg-emerald-50/50 border border-emerald-100">
                    <label class="block text-xs font-bold text-emerald-900 mb-1">Unggah SK / Sertifikat Hasil (Opsional)</label>
                    <p class="text-[11px] text-emerald-700/80 mb-2">Diunggah oleh verifikator dinas jika dokumen telah disetujui/terbit (Maks 10 MB).</p>
                    <input type="file" name="file_hasil_sk" accept=".pdf,.zip,.doc,.docx,.jpg,.jpeg,.png"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700">
                </div>

                <!-- Status Pengajuan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status Verifikasi <span class="text-rose-500">*</span></label>
                    <select name="status_layanan" required class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                        @foreach($statusOptions as $k => $st)
                            <option value="{{ $k }}" {{ old('status_layanan', 'PROSES_VERIFIKASI') == $k ? 'selected' : '' }}>{{ $st['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Petugas Verifikator -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Petugas Verifikator</label>
                    <input type="text" name="petugas_verifikator" value="{{ old('petugas_verifikator') }}" placeholder="Nama petugas/operator dinas yang menangani"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                </div>

                <!-- Catatan Verifikasi -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Catatan / Keterangan Verifikasi Dinas</label>
                    <input type="text" name="catatan_verifikator" value="{{ old('catatan_verifikator') }}" placeholder="Catatan tim verifikator bidang GTK..."
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end space-x-3 pt-2">
            <a href="{{ route('pendidikan.gtk.layanan.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-[#00264A] hover:bg-[#003B70] text-white text-xs font-bold rounded-xl shadow-lg hover:shadow-blue-900/30 transition-all duration-200">
                Simpan & Daftarkan Permohonan
            </button>
        </div>
    </form>

</div>

@push('scripts')
<script>
        const guideData = {
        'Cagar Budaya & Permuseuman': {
            title: 'Standar Layanan Rekomendasi Cagar Budaya & Museum',
            items: [
                'Dokumen Kajian Teknis / Naskah Akademik Cagar Budaya',
                'Surat Rekomendasi Tim Ahli Cagar Budaya (TACB) Kabupaten/Kota',
                'Dokumentasi Foto Resolusi Tinggi Objek Cagar Budaya / Bangunan Museum',
                'Surat Pernyataan Kepemilikan atau Penguasaan Objek yang Sah'
            ],
            color: 'text-indigo-800 border-indigo-200 bg-indigo-50/70'
        },
        'Warisan Budaya Takbenda': {
            title: 'Standar Layanan Usulan Penetapan WBTb',
            items: [
                'Formulir Pencatatan & Penetapan WBTb Lengkap',
                'Dokumentasi Video dan Audio Karya Budaya (Minimal 3 Menit)',
                'Kajian Historis dan Kajian Etnografis Warisan Budaya',
                'Surat Dukungan Komunitas / Tokoh Adat Pemilik Tradisi'
            ],
            color: 'text-emerald-800 border-emerald-200 bg-emerald-50/70'
        },
        'Kesenian & Event': {
            title: 'Standar Layanan Rekomendasi Pagelaran / Festival Seni',
            items: [
                'Proposal Rangkaian Acara / Festival Seni Budaya',
                'Jadwal Pelaksanaan, Lokasi, dan Daftar Kontingen / Pengisi Acara',
                'Rekomendasi TVRI Maluku Kabupaten/Kota Setempat',
                'Surat Pernyataan Pelestarian Nilai Budaya Lokal dan Kearifan Daerah'
            ],
            color: 'text-amber-800 border-amber-200 bg-amber-50/70'
        },
        'Komunitas & Lembaga Adat': {
            title: 'Standar Layanan Legalitas & Pendaftaran Komunitas Budaya',
            items: [
                'Profil Lembaga Adat / Komunitas / Sanggar Seni',
                'Akta Notaris / SK Kemenkumham / Surat Keterangan Domisili Desa/Negeri',
                'Struktur Pengurus Lembaga dan Daftar Anggota Aktif',
                'Daftar Rekam Jejak Portofolio Kegiatan Kebudayaan Terakhir'
            ],
            color: 'text-rose-800 border-rose-200 bg-rose-50/70'
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
