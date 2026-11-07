@extends('layouts.app')

@section('title', 'Edit Layanan SLB - ' . $layanan->nomor_registrasi)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Breadcrumb & Back -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-xs text-slate-500">
            <a href="{{ route('pendidikan.slb.layanan.index') }}" class="hover:text-indigo-600 font-medium">Layanan SLB</a>
            <span>/</span>
            <a href="{{ route('pendidikan.slb.layanan.show', $layanan->id) }}" class="hover:text-indigo-600 font-medium">{{ $layanan->nomor_registrasi }}</a>
            <span>/</span>
            <span class="text-slate-800 font-bold">Edit Dokumen</span>
        </div>
        <a href="{{ route('pendidikan.slb.layanan.show', $layanan->id) }}" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Kembali ke Detail</span>
        </a>
    </div>

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-[#00264A] to-[#004880] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 bg-indigo-400/20 text-indigo-200 border border-indigo-400/40 rounded-full text-[11px] font-bold uppercase">
                    Edit & Perbarui Dokumen SLB
                </span>
                <span class="font-mono text-xs text-blue-200">{{ $layanan->nomor_registrasi }}</span>
            </div>
            <h1 class="text-2xl font-black text-white mt-2">{{ $layanan->nama_satuan_pendidikan }}</h1>
            <p class="text-xs text-blue-100 max-w-2xl mt-1 leading-relaxed">
                Perbarui data permohonan layanan pendidikan khusus, status verifikasi berkas, atau unggah surat rekomendasi resmi yang telah diterbitkan.
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
    <form action="{{ route('pendidikan.slb.layanan.update', $layanan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- 1. Pilihan Jenis Layanan & Info Box -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
            <h2 class="text-sm font-bold text-[#00264A] uppercase tracking-wider flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-800 inline-flex items-center justify-center text-xs font-black">1</span>
                Katalog Produk Layanan Pendidikan Khusus
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Layanan SLB <span class="text-rose-500">*</span></label>
                    <select id="jenis_layanan" name="jenis_layanan" onchange="updateServiceGuide()" required
                            class="w-full py-2.5 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-slate-50/50 font-semibold text-slate-800">
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

        <!-- 2. Identitas Pemohon & Satuan Pendidikan (SLB) -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
            <h2 class="text-sm font-bold text-[#00264A] uppercase tracking-wider flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-800 inline-flex items-center justify-center text-xs font-black">2</span>
                Identitas Pemohon & Satuan Pendidikan SLB
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nama Pemohon -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pemohon (Kepala Sekolah / Wali Siswa / Pengurus) <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_pemohon" value="{{ old('nama_pemohon', $layanan->nama_pemohon) }}" required
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>

                <!-- Nomor Identitas (NIK/NIP/NISN) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Identitas (NIK / NIP / NISN)</label>
                    <input type="text" name="nomor_identitas" value="{{ old('nomor_identitas', $layanan->nomor_identitas) }}"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none font-mono">
                </div>

                <!-- Nama Satuan Pendidikan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Satuan Pendidikan (SLB) <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_satuan_pendidikan" value="{{ old('nama_satuan_pendidikan', $layanan->nama_satuan_pendidikan) }}" required
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>

                <!-- NPSN Sekolah -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">NPSN Satuan Pendidikan (Jika Ada)</label>
                    <input type="text" name="npsn_sekolah" value="{{ old('npsn_sekolah', $layanan->npsn_sekolah) }}"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none font-mono">
                </div>

                <!-- Kabupaten / Kota -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kabupaten / Kota <span class="text-rose-500">*</span></label>
                    <select name="kabupaten_kota" required class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                        @foreach($kabupatenKotaList as $kab)
                            <option value="{{ $kab }}" {{ old('kabupaten_kota', $layanan->kabupaten_kota) == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- No HP / WA -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">No. HP / WhatsApp Aktif</label>
                    <input type="text" name="no_hp_whatsapp" value="{{ old('no_hp_whatsapp', $layanan->no_hp_whatsapp) }}"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>

                <!-- Email -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Email Aktif</label>
                    <input type="email" name="email" value="{{ old('email', $layanan->email) }}"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
            </div>
        </div>

        <!-- 3. Detail Perihal & Tanggal Permohonan -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
            <h2 class="text-sm font-bold text-[#00264A] uppercase tracking-wider flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-800 inline-flex items-center justify-center text-xs font-black">3</span>
                Detail Perihal & Uraian Permohonan
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Tanggal Pengajuan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Pengajuan <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_pengajuan" value="{{ old('tanggal_pengajuan', $layanan->tanggal_pengajuan ? $layanan->tanggal_pengajuan->format('Y-m-d') : date('Y-m-d')) }}" required
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>

                <!-- Tanggal Selesai -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Selesai / Terbit</label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $layanan->tanggal_selesai ? $layanan->tanggal_selesai->format('Y-m-d') : '') }}"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>

                <!-- Nomor Surat / Rekomendasi Resmi -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Surat / Rekomendasi Resmi</label>
                    <input type="text" name="nomor_surat_rekomendasi" value="{{ old('nomor_surat_rekomendasi', $layanan->nomor_surat_rekomendasi) }}" placeholder="Nomor surat bila sudah diterbitkan dinas"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>

                <!-- Perihal Uraian -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Perihal / Uraian Detail Permohonan <span class="text-rose-500">*</span></label>
                    <textarea name="perihal_uraian" rows="3" required
                              class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">{{ old('perihal_uraian', $layanan->perihal_uraian) }}</textarea>
                </div>
            </div>
        </div>

        <!-- 4. Berkas Persyaratan & Dokumen Hasil -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
            <h2 class="text-sm font-bold text-[#00264A] uppercase tracking-wider flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-800 inline-flex items-center justify-center text-xs font-black">4</span>
                Unggah Dokumen & Status Verifikasi
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- File Persyaratan -->
                <div class="p-4 rounded-xl bg-blue-50/50 border border-blue-100">
                    <label class="block text-xs font-bold text-blue-900 mb-1">Ganti Berkas Persyaratan (PDF / ZIP)</label>
                    @if($layanan->file_persyaratan)
                        <div class="mb-2 p-2 rounded-lg bg-white border border-blue-200 flex items-center justify-between text-xs">
                            <span class="text-slate-600 truncate">{{ $layanan->file_nama_persyaratan ?? 'Berkas saat ini tersedia' }}</span>
                            <a href="{{ route('pendidikan.slb.layanan.download', ['id' => $layanan->id, 'type' => 'persyaratan']) }}" class="text-blue-600 hover:text-blue-800 font-bold ml-2">Unduh</a>
                        </div>
                    @endif
                    <input type="file" name="file_persyaratan" accept=".pdf,.zip,.doc,.docx,.jpg,.jpeg,.png"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                </div>

                <!-- File Hasil Rekomendasi -->
                <div class="p-4 rounded-xl bg-emerald-50/50 border border-emerald-100">
                    <label class="block text-xs font-bold text-emerald-900 mb-1">Unggah/Ganti Surat Rekomendasi / SK Hasil</label>
                    @if($layanan->file_hasil_rekomendasi)
                        <div class="mb-2 p-2 rounded-lg bg-white border border-emerald-200 flex items-center justify-between text-xs">
                            <span class="text-slate-600 truncate">{{ $layanan->file_nama_hasil ?? 'Surat Rekomendasi tersedia' }}</span>
                            <a href="{{ route('pendidikan.slb.layanan.download', ['id' => $layanan->id, 'type' => 'hasil']) }}" class="text-emerald-600 hover:text-emerald-800 font-bold ml-2">Unduh</a>
                        </div>
                    @endif
                    <input type="file" name="file_hasil_rekomendasi" accept=".pdf,.zip,.doc,.docx,.jpg,.jpeg,.png"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700">
                </div>

                <!-- Status Pengajuan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status Verifikasi <span class="text-rose-500">*</span></label>
                    <select name="status_layanan" required class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                        @foreach($statusOptions as $k => $st)
                            <option value="{{ $k }}" {{ old('status_layanan', $layanan->status_layanan) == $k ? 'selected' : '' }}>{{ $st['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Petugas Verifikator -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Petugas Verifikator</label>
                    <input type="text" name="petugas_verifikator" value="{{ old('petugas_verifikator', $layanan->petugas_verifikator) }}" placeholder="Nama petugas/tim teknis verifikator SLB"
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>

                <!-- Catatan Verifikasi -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Catatan / Keterangan Verifikasi Dinas</label>
                    <input type="text" name="catatan_verifikator" value="{{ old('catatan_verifikator', $layanan->catatan_verifikator) }}" placeholder="Catatan tim verifikator bidang Pendidikan Khusus..."
                           class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end space-x-3 pt-2">
            <a href="{{ route('pendidikan.slb.layanan.show', $layanan->id) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-[#00264A] hover:bg-[#003B70] text-white text-xs font-bold rounded-xl shadow-lg hover:shadow-indigo-900/30 transition-all duration-200">
                Simpan Perubahan
            </button>
        </div>
    </form>

</div>

@push('scripts')
<script>
    const guideData = {
        'Rekomendasi Pendirian SLB Negeri dan SLB Swasta': {
            title: 'Standar Layanan Rekomendasi Pendirian SLB',
            items: [
                'Proposal Pendirian SLB beserta Analisis Kebutuhan Khusus di Wilayah',
                'Surat Rekomendasi Dinas Pendidikan Kabupaten/Kota setempat',
                'Bukti Kepemilikan/Penguasaan Lahan dan Kesiapan Sarpras Aksesibel',
                'Daftar Calon Tenaga Pendidik PLB (Pendidikan Luar Biasa) dan Calon Siswa'
            ],
            color: 'text-indigo-800 border-indigo-200 bg-indigo-50/70'
        },
        'Rekomendasi Mutasi (Perpindahan) Siswa SLB': {
            title: 'Standar Layanan Mutasi Siswa SLB',
            items: [
                'Surat Permohonan Mutasi dari Orang Tua / Wali Siswa',
                'Surat Keterangan Pindah & Asesmen Kekhususan dari SLB Asal',
                'Surat Kesiapan Menerima dari SLB Tujuan',
                'Fotokopi Buku Rapor Lengkap dan NISN yang Terdaftar di Dapodik'
            ],
            color: 'text-sky-800 border-sky-200 bg-sky-50/70'
        },
        'Bantuan Operasional Satuan Pendidikan (BOSP) SLB': {
            title: 'Standar Layanan Penyaluran & Rekonsiliasi BOSP SLB',
            items: [
                'Dokumen RKAS (Rencana Kegiatan & Anggaran Sekolah) BOSP SLB',
                'Berita Acara Rekonsiliasi Belanja BOSP Semester Sebelumnya',
                'Validasi & Sinkronisasi Cut-Off Data Jumlah Siswa Khusus di Dapodik',
                'Surat Pernyataan Tanggung Jawab Mutlak (SPTJM) Kepala SLB'
            ],
            color: 'text-emerald-800 border-emerald-200 bg-emerald-50/70'
        },
        'Legalisir Ijazah/STTB dan Surat Keterangan Lulus (SKL) SLB': {
            title: 'Standar Layanan Legalisir Ijazah / SKL SLB',
            items: [
                'Fotokopi Ijazah / STTB / SKL SLB yang Akan Dilegalisasi (Maks. 5 Lembar)',
                'Menunjukkan Dokumen Asli Ijazah / STTB / SKL saat Verifikasi',
                'Surat Keterangan Kehilangan dari Kepolisian (Jika Ijazah Hilang)',
                'Surat Keterangan dari Kepala SLB Penerbit Ijazah'
            ],
            color: 'text-amber-800 border-amber-200 bg-amber-50/70'
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
