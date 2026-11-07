<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover" />
    <title>Pangkalan Data TVRI Maluku | Integrated Digital Operations Platform</title>
    <meta name="description" content="Pangkalan Data TVRI Maluku – Integrated Digital Operations Platform yang mencakup Tata Usaha, berita, program, konten media baru, Promo, Umum, teknik, Teknik transmisi, Pengembagan Usaha, dan Keuangan." />
    <meta property="og:title" content="Pangkalan Data TVRI Maluku | Integrated Digital Operations Platform" />
    <meta property="og:description" content="Pangkalan Data TVRI Maluku – Integrated Digital Operations Platform yang mencakup Tata Usaha, berita, program, konten media baru, Promo, Umum, teknik, Teknik transmisi, Pengembagan Usaha, dan Keuangan." />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Pangkalan Data TVRI Maluku" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Pangkalan Data TVRI Maluku | Integrated Digital Operations Platform" />
    <meta name="twitter:description" content="Pangkalan Data TVRI Maluku – Integrated Digital Operations Platform yang mencakup Tata Usaha, berita, program, konten media baru, Promo, Umum, teknik, Teknik transmisi, Pengembagan Usaha, dan Keuangan." />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @if ($bundleCss ?? null)
        <link rel="stylesheet" href="{{ asset('react/assets/'.$bundleCss) }}?v={{ @filemtime(public_path('react/assets/'.$bundleCss)) }}" />
    @endif
    <link rel="stylesheet" href="{{ asset('css/biru-keemasan.css') }}?v={{ @filemtime(public_path('css/biru-keemasan.css')) ?: time() }}" />
    <style>
        html, body {
            max-width: 100%;
            overflow-x: hidden;
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: transparent;
            scroll-behavior: smooth;
        }
        .overflow-x-auto, .overflow-y-auto {
            -webkit-overflow-scrolling: touch;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-[#051329] text-slate-100 font-['Plus_Jakarta_Sans',sans-serif] antialiased min-h-screen">
    @yield('content')
    @if ($bundleJs ?? null)
        <script type="module" src="{{ asset('react/assets/'.$bundleJs) }}?v={{ @filemtime(public_path('react/assets/'.$bundleJs)) }}"></script>
    @endif
    @stack('scripts')
</body>
</html>
