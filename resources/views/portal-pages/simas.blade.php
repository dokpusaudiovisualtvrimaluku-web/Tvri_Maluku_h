<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SIMAS PUPR Maluku | Sistem Administrasi Manajemen Sekolah TVRI Maluku</title>
    <meta name="description" content="SIMAS PUPR Maluku – Sistem Administrasi Manajemen Sekolah TVRI Maluku: surat masuk, disposisi berantai, tindak lanjut, surat keluar, arsip digital, dan laporan persuratan." />
    <link rel="icon" href="{{ asset('favicon.ico') }}" />
    @if ($bundleCss ?? null)
        <link rel="stylesheet" href="{{ asset('simas-assets/assets/'.$bundleCss) }}?v={{ file_exists(public_path('simas-assets/assets/'.$bundleCss)) ? filemtime(public_path('simas-assets/assets/'.$bundleCss)) : time() }}" />
    @endif
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-900 antialiased selection:bg-blue-200 selection:text-blue-900 font-sans">
    <div id="root"></div>
    @if ($bundleJs ?? null)
        <script type="module" src="{{ asset('simas-assets/assets/'.$bundleJs) }}?v={{ file_exists(public_path('simas-assets/assets/'.$bundleJs)) ? filemtime(public_path('simas-assets/assets/'.$bundleJs)) : time() }}"></script>
    @endif
    @stack('scripts')
</body>
</html>
