<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pangkalan Data Dinas PUPR Maluku | SIMANDAT</title>
    <meta name="description" content="Pangkalan Data Dinas PUPR Maluku – akses aplikasi SIWALIMA, sistem pengelolaan pangkalan data TVRI Maluku." />
    <link rel="icon" href="{{ asset('favicon.ico') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --blue-700: #1d4ed8;
            --blue-900: #1e3a8a;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            background: #fcfaf7;
            color: #0f172a;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        .pd-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 16px;
            background: linear-gradient(90deg, var(--blue-900), var(--blue-700));
            color: #fff;
            box-shadow: 0 2px 10px rgba(30, 58, 138, .25);
        }
        .pd-brand { display: flex; align-items: center; gap: 10px; min-width: 0; }
        .pd-badge {
            width: 38px; height: 38px; border-radius: 10px; flex: none;
            background: rgba(255, 255, 255, .14);
            display: flex; align-items: center; justify-content: center;
        }
        .pd-badge img { width: 26px; height: 26px; object-fit: contain; }
        .pd-title { font-size: 15px; font-weight: 800; letter-spacing: -.02em; line-height: 1.2; }
        .pd-sub { font-size: 11px; opacity: .8; margin-top: 2px; }
        .pd-actions { display: flex; align-items: center; gap: 8px; flex: none; }
        .pd-btn {
            font: inherit; font-size: 12px; font-weight: 700;
            padding: 8px 12px; border-radius: 8px; text-decoration: none;
            border: 1px solid rgba(255, 255, 255, .35);
            background: transparent; color: #fff; white-space: nowrap;
        }
        .pd-btn:hover { background: rgba(255, 255, 255, .12); }
        .pd-btn--accent { background: #fbbf24; border-color: #fbbf24; color: #78350f; }
        .pd-btn--accent:hover { background: #f59e0b; border-color: #f59e0b; }
        .pd-frame { flex: 1; border: 0; width: 100%; background: #fff; }
        @media (max-width: 640px) {
            .pd-sub { display: none; }
            .pd-btn { padding: 7px 9px; font-size: 11px; }
        }
    </style>
</head>
<body>
    <header class="pd-bar">
        <div class="pd-brand">
            <span class="pd-badge"><img src="{{ asset('images/logo_tvri.png') }}" alt="Logo TVRI" /></span>
            <span>
                <span class="pd-title">Pangkalan Data Dinas PUPR Maluku</span>
                <span class="pd-sub">SIMANDAT TVRI Maluku &middot; SIWALIMA</span>
            </span>
        </div>
        <nav class="pd-actions">
            <a class="pd-btn pd-btn--accent" href="{{ $siwalimaUrl }}" target="_blank" rel="noopener">Buka Layar Penuh</a>
        </nav>
    </header>
    <iframe
        class="pd-frame"
        src="{{ $siwalimaUrl }}"
        title="Pangkalan Data SIWALIMA"
        allow="clipboard-read; clipboard-write; fullscreen"
        loading="eager"
    ></iframe>
</body>
</html>
