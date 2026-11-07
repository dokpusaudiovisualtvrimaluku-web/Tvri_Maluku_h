<!DOCTYPE html>
<html lang="id" class="h-full bg-[#EAF4FB]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Executive Command Center') | TVRI Maluku</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN for instant styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gov: {
                            navy: '#003B70',
                            blue: '#005B9F',
                            light: '#EAF4FB',
                            accent: '#E31837',
                            dark: '#00264A',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        :root {
            --sat: env(safe-area-inset-top, 0px);
            --sar: env(safe-area-inset-right, 0px);
            --sab: env(safe-area-inset-bottom, 0px);
            --sal: env(safe-area-inset-left, 0px);
        }
        html, body {
            max-width: 100%;
            overflow-x: hidden;
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: transparent;
            scroll-behavior: smooth;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #EAF4FB;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        /* Touch scroll momentum & overscroll containment for mobile & tablet */
        main, .overflow-x-auto, .overflow-y-auto {
            -webkit-overflow-scrolling: touch;
        }
        main {
            overscroll-behavior-y: contain;
        }
        /* Responsive table auto-scrolling */
        table {
            max-width: 100%;
        }
        .overflow-x-auto {
            scrollbar-width: thin;
        }
        /* Responsive image and chart constraints */
        img, svg, canvas {
            max-width: 100%;
        }
        canvas {
            height: auto;
        }
        /* Prevent auto-zoom on mobile inputs */
        @media (max-width: 768px) {
            input[type="text"],
            input[type="number"],
            input[type="email"],
            input[type="password"],
            input[type="search"],
            input[type="date"],
            input[type="datetime-local"],
            select,
            textarea {
                font-size: 16px !important;
            }
        }
        /* Safe area spacing on mobile devices */
        @supports (padding: env(safe-area-inset-bottom)) {
            header {
                padding-left: max(0.75rem, env(safe-area-inset-left));
                padding-right: max(0.75rem, env(safe-area-inset-right));
            }
            footer {
                padding-bottom: max(0.75rem, env(safe-area-inset-bottom));
                padding-left: max(1rem, env(safe-area-inset-left));
                padding-right: max(1rem, env(safe-area-inset-right));
            }
        }
        @supports (-webkit-touch-callout: none) {
            .h-screen {
                height: -webkit-fill-available;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="h-full antialiased text-slate-800 flex flex-col" x-data="{ sidebarOpen: false, profileDropdown: false }" @resize.window="if (window.innerWidth >= 1024) sidebarOpen = false">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-slate-900/60 z-40 lg:hidden backdrop-blur-sm"
         x-cloak></div>

    <div class="flex h-screen h-[100dvh] overflow-hidden">

        <!-- Sidebar Component -->
        @include('layouts.sidebar')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#F4F9FD]">
            
            <!-- Header Component -->
            @include('layouts.header')

            <!-- Main Scrollable Body -->
            <main class="flex-1 overflow-y-auto p-3 sm:p-4 md:p-6 lg:p-8">
                
                <!-- Toast Notifications -->
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                         class="mb-6 flex items-center justify-between p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl shadow-sm transition-all duration-300">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-sm font-medium">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" 
                         class="mb-6 flex items-center justify-between p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl shadow-sm transition-all duration-300">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-rose-500 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <span class="text-sm font-medium">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-rose-500 hover:text-rose-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl shadow-sm">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-rose-500 text-white flex items-center justify-center flex-shrink-0 shadow-sm mt-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-rose-900">Periksa kembali formulir input Anda:</h4>
                                <ul class="mt-1 list-disc list-inside text-xs text-rose-700 space-y-0.5">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Yielded Content -->
                @yield('content')
            </main>

            <!-- Official Gov Footer -->
            <footer class="bg-white border-t border-slate-200/80 px-6 py-3.5 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2">
                <div class="flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span><strong>TVRI Maluku</strong> &bull; Satu Data, Satu Arah, untuk Penyiaran yang Lebih Kuat</span>
                </div>
                <div>
                    &copy; {{ date('Y') }} TVRI Maluku. Hak Cipta Dilindungi.
                </div>
            </footer>
        </div>
    </div>

    <script>
    (function() {
        function getWitDate() {
            const now = new Date();
            const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
            return new Date(utc + (3600000 * 9)); // WIT (UTC+9)
        }

        function formatRealtime() {
            const wit = getWitDate();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            const day = days[wit.getDay()];
            const date = wit.getDate();
            const month = months[wit.getMonth()];
            const year = wit.getFullYear();
            const hours = String(wit.getHours()).padStart(2, '0');
            const mins = String(wit.getMinutes()).padStart(2, '0');
            const secs = String(wit.getSeconds()).padStart(2, '0');

            const fullWithComma = `${day}, ${date} ${month} ${year}, ${hours}:${mins}:${secs} WIT`;
            const fullWithPipe = `${day}, ${date} ${month} ${year} | ${hours}:${mins}:${secs} WIT`;
            const fullWithBullet = `${day}, ${date} ${month} ${year} • ${hours}:${mins}:${secs} WIT`;
            const dateOnly = `${day}, ${date} ${month} ${year}`;
            const timeOnly = `${hours}:${mins}:${secs} WIT`;

            document.querySelectorAll('.global-live-datetime').forEach(el => {
                el.textContent = fullWithComma;
            });
            document.querySelectorAll('.global-live-datetime-pipe').forEach(el => {
                el.textContent = fullWithPipe;
            });
            document.querySelectorAll('.global-live-datetime-bullet').forEach(el => {
                el.textContent = fullWithBullet;
            });
            document.querySelectorAll('.global-live-daydate').forEach(el => {
                el.textContent = dateOnly;
            });
            document.querySelectorAll('.global-live-clock').forEach(el => {
                el.textContent = timeOnly;
            });

            const promoClock = document.getElementById('liveDateTimePromo');
            if (promoClock) promoClock.textContent = fullWithComma;

            const liveClockWidgets = document.querySelectorAll('#liveClockWidget, .liveClockWidget');
            liveClockWidgets.forEach(el => {
                el.textContent = fullWithBullet;
            });

            const rtDayDate = document.getElementById('realtimeDayDate');
            if (rtDayDate) rtDayDate.textContent = dateOnly;

            const rtClock = document.getElementById('realtimeClock');
            if (rtClock) rtClock.textContent = timeOnly;
        }

        document.addEventListener('DOMContentLoaded', function() {
            formatRealtime();
            setInterval(formatRealtime, 1000);
        });
    })();
    </script>
    @stack('scripts')
</body>
</html>
