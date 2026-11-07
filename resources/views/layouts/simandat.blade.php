<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIMANDAT TVRI Maluku - Executive Command Center')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        app: {
                            bg: '#070b14',       // Very dark navy background as in reference image
                            surface: '#0d1527',  // Card background
                            surfaceHover: '#141f38',
                            cardBorder: '#1e2e4f',
                            primary: '#00f6ff',  // Bright cyan/teal
                            primaryDark: '#00b8c4',
                            accentBlue: '#2563eb', // Royal blue for numbered badges
                            text: '#8da2c0',     // Muted text
                            textLight: '#f8fafc',// White text
                            border: '#1b2a47',
                            success: '#10b981',
                            warning: '#f59e0b',
                            danger: '#ef4444'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    fontSize: {
                        'xxs': '0.65rem',
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts & Font Awesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #070b14;
            color: #f8fafc;
            overflow: hidden;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #070b14;
        }
        ::-webkit-scrollbar-thumb {
            background: #1e2e4f;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #2a3f6c;
        }

        .glow-text {
            text-shadow: 0 0 10px rgba(0, 246, 255, 0.5);
        }
        .glow-border {
            box-shadow: 0 0 15px rgba(0, 246, 255, 0.2);
        }

        /* Pulse glow animation for island map nodes */
        @keyframes pulseGlow {
            0%, 100% {
                transform: scale(1);
                opacity: 0.9;
                box-shadow: 0 0 10px currentColor;
            }
            50% {
                transform: scale(1.35);
                opacity: 1;
                box-shadow: 0 0 18px currentColor;
            }
        }
        .island-node {
            animation: pulseGlow 2.5s infinite ease-in-out;
        }

        /* Loading animation for progress bars */
        @keyframes fillProgress {
            from { width: 0; }
        }
        .progress-animate {
            animation: fillProgress 1s ease-out forwards;
        }

        /* Modal transition */
        .modal-enter { opacity: 0; transform: scale(0.95); }
        .modal-enter-active { opacity: 1; transform: scale(1); transition: opacity 200ms, transform 200ms; }
        .modal-exit { opacity: 1; transform: scale(1); }
        .modal-exit-active { opacity: 0; transform: scale(0.95); transition: opacity 200ms, transform 200ms; }

        /* Responsive tablet & mobile scroll support */
        @media (max-width: 1023px) {
            body {
                overflow-y: auto !important;
                height: auto !important;
                min-height: 100vh;
                min-height: 100dvh;
            }
            .overflow-x-auto, .overflow-y-auto {
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="h-screen flex flex-col bg-app-bg text-app-textLight font-sans antialiased">
    
    @yield('content')

    @stack('scripts')
</body>
</html>
