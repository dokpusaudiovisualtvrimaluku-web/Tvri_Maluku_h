<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Keaslian Dokumen - TVRI Maluku</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4 antialiased selection:bg-blue-600 selection:text-white">

    <div class="max-w-xl w-full bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden">
        
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-[#00264A] via-[#003B70] to-[#005B9F] p-6 text-white text-center relative overflow-hidden">
            <div class="w-16 h-16 rounded-2xl bg-white/10 p-1.5 mx-auto border border-white/20 mb-3 shadow-inner">
                <img src="{{ asset('images/logo_tvri.png') }}" alt="Logo TVRI" class="w-full h-full object-contain rounded-xl" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Coat_of_arms_of_Maluku.svg/1200px-Coat_of_arms_of_Maluku.svg.png'">
            </div>
            <h1 class="text-sm font-black uppercase tracking-wider text-blue-100">
                Pemerintah Provinsi Maluku
            </h1>
            <p class="text-xs font-bold text-white uppercase tracking-widest mt-0.5">
                TVRI Maluku
            </p>
            <p class="text-[11px] text-blue-200 mt-1">
                Portal Resmi Verifikasi Keaslian Dokumen & Naskah Digital
            </p>
        </div>

        <div class="p-6 space-y-6">

            @if($isValid && $document)
                <!-- Valid Status Badge -->
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-center space-y-1">
                    <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 mx-auto flex items-center justify-center mb-2">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    </div>
                    <span class="inline-block px-3 py-1 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-wider rounded-full shadow-sm">
                        ✓ DOKUMEN RESMI TERVERIFIKASI
                    </span>
                    <h2 class="text-xs font-bold text-emerald-900 mt-1">Naskah Terdaftar pada Pangkalan Data SIMANDAT SIWALIMA</h2>
                    <p class="text-[11px] text-emerald-700">Dokumen ini sah, otentik, dan dikeluarkan resmi oleh TVRI Maluku.</p>
                </div>

                <!-- Document Details -->
                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200 space-y-3">
                    <h3 class="text-xs font-bold text-[#00264A] uppercase tracking-wider border-b border-slate-200 pb-2">
                        Informasi Naskah Dinas
                    </h3>

                    <dl class="divide-y divide-slate-200/70 text-xs space-y-1">
                        <div class="pt-2 pb-1 flex justify-between">
                            <dt class="text-slate-500">Nomor Dokumen / SK</dt>
                            <dd class="font-mono font-bold text-slate-800">{{ $document->document_number ?: 'NON-NOMOR' }}</dd>
                        </div>
                        <div class="py-2 flex justify-between">
                            <dt class="text-slate-500">Klasifikasi</dt>
                            <dd class="font-bold text-blue-700">{{ $document->category }}</dd>
                        </div>
                        <div class="py-2 flex justify-between">
                            <dt class="text-slate-500">Unit Pengusul / Bidang</dt>
                            <dd class="font-semibold text-slate-800">{{ $document->division }}</dd>
                        </div>
                        <div class="py-2 flex justify-between">
                            <dt class="text-slate-500">Judul Naskah</dt>
                            <dd class="font-bold text-slate-800 text-right max-w-xs">{{ $document->title }}</dd>
                        </div>
                        <div class="py-2 flex justify-between">
                            <dt class="text-slate-500">Tanggal Pengesahan</dt>
                            <dd class="font-semibold text-slate-800">{{ $document->document_date ? $document->document_date->format('d F Y') : '-' }}</dd>
                        </div>
                        <div class="py-2 flex justify-between">
                            <dt class="text-slate-500">Versi Terakhir</dt>
                            <dd class="font-bold text-emerald-700">{{ $document->current_version }} (Aktif)</dd>
                        </div>
                        <div class="py-2 flex justify-between">
                            <dt class="text-slate-500">Pengunggah Resmi</dt>
                            <dd class="font-semibold text-slate-800">{{ $document->uploader_name }}</dd>
                        </div>
                        <div class="py-2 flex justify-between">
                            <dt class="text-slate-500">Token Keaslian QR</dt>
                            <dd class="font-mono text-[10px] text-slate-600 truncate max-w-[200px]">{{ $document->qr_token }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Action Button -->
                <div class="text-center pt-2">
                    @auth
                        <a href="{{ route('dokumen.documents.show', $document->id) }}" class="inline-flex items-center space-x-2 px-6 py-2.5 bg-[#003B70] hover:bg-[#00264A] text-white text-xs font-bold rounded-xl shadow-md transition-colors">
                            <span>Buka Dokumen di SIMANDAT</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center space-x-2 px-6 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl shadow-md transition-colors">
                            <span>Masuk ke SIMANDAT SIWALIMA</span>
                        </a>
                    @endauth
                </div>

            @else
                <!-- Invalid QR Status -->
                <div class="p-6 rounded-2xl bg-rose-50 border border-rose-200 text-center space-y-2">
                    <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 mx-auto flex items-center justify-center mb-2">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <span class="inline-block px-3 py-1 bg-rose-600 text-white text-[10px] font-black uppercase tracking-wider rounded-full">
                        ✗ DOKUMEN TIDAK DITEMUKAN / TIDAK VALID
                    </span>
                    <h2 class="text-sm font-bold text-rose-900">Token Verifikasi Tidak Terdaftar</h2>
                    <p class="text-xs text-rose-700">Kode QR yang Anda pindai tidak cocok dengan arsip resmi manapun pada pangkalan data TVRI Maluku.</p>
                </div>
            @endif

            <!-- Security Footer Note -->
            <div class="border-t border-slate-100 pt-4 text-center text-[10px] text-slate-400 space-y-1">
                <p class="font-semibold text-slate-500">SISTEM INFORMASI MANAJEMEN DATA TERPADU (SIMANDAT SIWALIMA)</p>
                <p>Dokumen Dilindungi Enkripsi Digital & Verifikasi Berjenjang Pemerintah Provinsi Maluku</p>
            </div>

        </div>

    </div>

</body>
</html>
