<!DOCTYPE html>
<html lang="id" class="h-full bg-[#001D38]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <title>Masuk | Executive Command Center TVRI Maluku</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
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
                            dark: '#001D38',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        @media (max-width: 768px) {
            input[type="text"],
            input[type="password"] {
                font-size: 16px !important;
            }
        }

        @keyframes bgInfografisMotion {
            0% {
                transform: scale(1.06) translate(0%, 0%);
            }
            25% {
                transform: scale(1.14) translate(-2.5%, 1.8%);
            }
            50% {
                transform: scale(1.18) translate(1.8%, -1.8%);
            }
            75% {
                transform: scale(1.12) translate(-1.8%, -2.2%);
            }
            100% {
                transform: scale(1.06) translate(0%, 0%);
            }
        }

        .animate-bg-infografis {
            animation: bgInfografisMotion 26s ease-in-out infinite alternate;
            will-change: transform;
        }

        @keyframes scanGlow {
            0% {
                transform: translateY(-100%);
                opacity: 0;
            }
            30% {
                opacity: 0.3;
            }
            70% {
                opacity: 0.3;
            }
            100% {
                transform: translateY(100vh);
                opacity: 0;
            }
        }

        .animate-scan-glow {
            animation: scanGlow 10s ease-in-out infinite;
        }
    </style>
</head>
<body class="min-h-full text-slate-800 antialiased flex flex-col justify-between relative overflow-x-hidden selection:bg-[#005B9F] selection:text-white"
      x-data="{ showPass: false, loading: false, showForgotModal: false, fillDev(user) { const u = this.$refs.loginInput || document.getElementById('login'); const p = this.$refs.passInput || document.getElementById('password'); if (u) u.value = user; if (p) { p.value = ''; p.focus(); } } }">

    <!-- Background Decoration with Animated High-Tech Infographic Photo -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute -inset-10 animate-bg-infografis opacity-45">
            <img src="{{ asset('images/bg-infografis.png') }}" alt="Background Infografis TVRI" class="w-full h-full object-cover">
        </div>
        <!-- High-tech cyan-blue scanline effect -->
        <div class="absolute inset-x-0 h-40 bg-gradient-to-b from-transparent via-cyan-400/10 to-transparent animate-scan-glow pointer-events-none"></div>
        <!-- TVRI Theme Navy/Blue Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-[#001D38] via-[#00264A]/75 to-[#001D38]/90"></div>
    </div>

    <!-- Header Portal Bar -->
    <header class="relative z-10 w-full px-6 py-4 flex items-center justify-between border-b border-blue-900/40 bg-[#001D38]/80 backdrop-blur-md">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-lg bg-white p-1 border border-blue-400/30 flex items-center justify-center shadow-xs">
                <img src="{{ asset('images/tvri-maluku.webp') }}" alt="Logo TVRI Maluku" class="w-full h-full object-contain rounded">
            </div>
            <div>
                <h1 class="text-xs font-bold text-white tracking-wider uppercase leading-none">LPP Televisi Republik Indonesia</h1>
                <p class="text-[10px] text-blue-300 font-medium">TVRI Stasiun Maluku</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="/" target="_top" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-white bg-white/10 hover:bg-white/20 border border-blue-400/30 transition-all shadow-sm">
                <svg class="w-3.5 h-3.5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Kembali ke Halaman Utama</span>
            </a>
            <div class="hidden sm:flex items-center space-x-2 pl-2 border-l border-blue-900/60">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-xs font-semibold text-blue-200">Sistem Resmi TVRI Maluku</span>
            </div>
        </div>
    </header>

    <!-- Main Content Container (Landscape Orientation) -->
    <main class="relative z-10 flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-5xl xl:max-w-6xl">
            
            <!-- Landscape Login Card -->
            <div class="bg-[#002244]/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-blue-500/30 p-6 sm:p-8 lg:p-9 relative overflow-hidden text-white">
                
                <!-- Red-Navy Top Accent -->
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#003B70] via-[#005B9F] to-[#E31837]"></div>

                <!-- 2-Column Landscape Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                    
                    <!-- Left Column: Form Login Executive -->
                    <div class="lg:col-span-5 flex flex-col justify-between">
                        <div>
                            <!-- Header Title -->
                            <div class="text-center mb-6">
                                <div class="w-20 h-16 mx-auto mb-3 rounded-2xl bg-white p-2 border border-blue-300/40 flex items-center justify-center shadow-md">
                                    <img src="{{ asset('images/tvri-maluku.webp') }}" alt="Logo TVRI Maluku" class="w-full h-full object-contain">
                                </div>
                                <span class="inline-block px-3 py-1 bg-blue-950/80 text-blue-200 text-[10px] font-extrabold uppercase tracking-widest rounded-full mb-1.5 border border-blue-400/30">
                                    Media Pemersatu Bangsa
                                </span>
                                <h2 class="text-base sm:text-lg font-extrabold text-white tracking-tight leading-snug">
                                    EXECUTIVE COMMAND CENTER
                                </h2>
                                <p class="text-xs font-semibold text-blue-300 leading-tight mt-0.5">
                                    TVRI MALUKU
                                </p>
                            </div>

                            <!-- Session Flash Messages -->
                            @if(session('success'))
                                <div class="mb-4 p-3.5 bg-emerald-950/60 border border-emerald-500/40 text-emerald-200 rounded-xl text-xs flex items-center space-x-2.5">
                                    <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span>{{ session('success') }}</span>
                                </div>
                            @endif

                            @if($errors->has('login'))
                                <div class="mb-4 p-3.5 bg-rose-950/60 border border-rose-500/40 text-rose-200 rounded-xl text-xs flex items-start space-x-2.5">
                                    <svg class="w-4 h-4 text-rose-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="font-medium">{{ $errors->first('login') }}</span>
                                </div>
                            @endif

                            <!-- Login Form -->
                            <form id="loginForm" x-ref="loginForm" action="{{ route('login.post') }}" method="POST" target="_top" @submit="loading = true" class="space-y-4">
                                @csrf

                                <!-- Identifier Input -->
                                <div>
                                    <label for="login" class="block text-xs font-bold text-blue-200 uppercase tracking-wider mb-1.5">
                                        Username / Email Resmi
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-blue-300/70">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        <input type="text" 
                                               id="login" 
                                               name="login" 
                                               x-ref="loginInput"
                                               value="{{ old('login', '') }}" 
                                               required 
                                               autofocus 
                                               autocomplete="username" 
                                               placeholder="Masukkan username atau email"
                                               class="w-full pl-10 pr-4 py-2.5 bg-[#001830]/90 border border-blue-400/30 rounded-xl text-xs sm:text-sm text-white placeholder-blue-300/50 focus:bg-[#001428] focus:outline-none focus:ring-2 focus:ring-[#005B9F] focus:border-cyan-400 transition-all">
                                    </div>
                                </div>

                                <!-- Password Input -->
                                <div>
                                    <div class="flex items-center justify-between mb-1.5">
                                        <label for="password" class="block text-xs font-bold text-blue-200 uppercase tracking-wider">
                                            Kata Sandi
                                        </label>
                                    </div>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-blue-300/70">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        </div>
                                        <input type="password" 
                                               id="password" 
                                               name="password" 
                                               x-ref="passInput"
                                               value="" 
                                               required 
                                               autocomplete="current-password" 
                                               placeholder="Masukkan password Anda"
                                               class="w-full pl-10 pr-11 py-2.5 bg-[#001830]/90 border border-blue-400/30 rounded-xl text-xs sm:text-sm text-white placeholder-blue-300/50 focus:bg-[#001428] focus:outline-none focus:ring-2 focus:ring-[#005B9F] focus:border-cyan-400 transition-all">
                                        
                                        <!-- Toggle Password Eye Button -->
                                        <button type="button" 
                                                onclick="togglePasswordVisibility()" 
                                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-blue-300/70 hover:text-white focus:outline-none">
                                            <svg id="eye-icon-closed" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            <svg id="eye-icon-open" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Submit Button with Loading State -->
                                <button type="submit" 
                                        :disabled="loading"
                                        class="w-full py-3 px-4 bg-[#005B9F] hover:bg-[#004a8c] text-white font-bold text-xs sm:text-sm rounded-xl shadow-lg shadow-blue-950/40 border border-blue-400/30 flex items-center justify-center space-x-2 transition-all duration-200 transform active:scale-[0.99] disabled:opacity-75">
                                    <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" x-cloak>
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span x-text="loading ? 'Memverifikasi Kredensial...' : 'MASUK KE SISTEM'">MASUK KE SISTEM</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Right Column: Akun Development Unit TVRI Maluku -->
                    <div class="lg:col-span-7 flex flex-col justify-between lg:border-l lg:border-blue-900/60 lg:pl-8">
                        <div class="p-4 sm:p-5 bg-[#001830]/75 rounded-2xl border border-blue-400/25 text-left h-full flex flex-col justify-between backdrop-blur-sm">
                            <div>
                                <div class="flex items-center justify-between mb-3 pb-2.5 border-b border-blue-900/60">
                                    <span class="text-xs font-extrabold text-blue-200 uppercase tracking-wider flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Akun Development (Tahap 1)
                                    </span>
                                    <span class="text-[9px] font-semibold bg-rose-500/20 text-rose-300 px-2 py-0.5 rounded-full border border-rose-500/30">Wajib Ganti di Prod</span>
                                </div>
                                
                                <p class="text-[11px] text-blue-200/80 mb-3 font-medium">
                                    Pilih salah satu unit kerja di bawah untuk pengisian username / email resmi:
                                </p>

                                <!-- Grid of TVRI Units Requested by User -->
                                <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-3 gap-2.5 text-[11px]">
                                    
                                    <!-- Kepala Stasiun -->
                                    <button type="button" @click="fillDev('kepala.stasiun@tvri.go.id')" class="p-2.5 bg-[#00264A]/80 hover:bg-[#003B70] rounded-xl border border-blue-400/30 hover:border-amber-400 text-left hover:shadow-md transition-all group">
                                        <p class="font-bold text-amber-300 group-hover:text-amber-200 truncate">Kepala Stasiun</p>
                                        <p class="text-[9px] text-blue-300/80 group-hover:text-blue-100 font-mono truncate">kepala.stasiun@tvri.go.id</p>
                                    </button>

                                    <!-- Super Admin -->
                                    <button type="button" @click="fillDev('admin@tvri.go.id')" class="p-2.5 bg-[#00264A]/80 hover:bg-[#003B70] rounded-xl border border-blue-400/30 hover:border-cyan-400 text-left hover:shadow-md transition-all group">
                                        <p class="font-bold text-cyan-300 group-hover:text-cyan-200 truncate">Super Admin</p>
                                        <p class="text-[9px] text-blue-300/80 group-hover:text-blue-100 font-mono truncate">admin@tvri.go.id</p>
                                    </button>

                                    <!-- Tata Usaha -->
                                    <button type="button" @click="fillDev('tu@tvri.go.id')" class="p-2.5 bg-[#00264A]/80 hover:bg-[#003B70] rounded-xl border border-blue-400/30 hover:border-cyan-400 text-left hover:shadow-md transition-all group">
                                        <p class="font-bold text-blue-100 group-hover:text-white truncate">Tata Usaha</p>
                                        <p class="text-[9px] text-blue-300/80 group-hover:text-blue-100 font-mono truncate">tu@tvri.go.id</p>
                                    </button>

                                    <!-- Berita -->
                                    <button type="button" @click="fillDev('berita@tvri.go.id')" class="p-2.5 bg-[#00264A]/80 hover:bg-[#003B70] rounded-xl border border-blue-400/30 hover:border-cyan-400 text-left hover:shadow-md transition-all group">
                                        <p class="font-bold text-blue-100 group-hover:text-white truncate">Berita</p>
                                        <p class="text-[9px] text-blue-300/80 group-hover:text-blue-100 font-mono truncate">berita@tvri.go.id</p>
                                    </button>

                                    <!-- Program -->
                                    <button type="button" @click="fillDev('program@tvri.go.id')" class="p-2.5 bg-[#00264A]/80 hover:bg-[#003B70] rounded-xl border border-blue-400/30 hover:border-cyan-400 text-left hover:shadow-md transition-all group">
                                        <p class="font-bold text-blue-100 group-hover:text-white truncate">Program</p>
                                        <p class="text-[9px] text-blue-300/80 group-hover:text-blue-100 font-mono truncate">program@tvri.go.id</p>
                                    </button>

                                    <!-- Konten Media Baru (KMB) -->
                                    <button type="button" @click="fillDev('kmb@tvri.go.id')" class="p-2.5 bg-[#00264A]/80 hover:bg-[#003B70] rounded-xl border border-blue-400/30 hover:border-cyan-400 text-left hover:shadow-md transition-all group">
                                        <p class="font-bold text-blue-100 group-hover:text-white truncate">KMB</p>
                                        <p class="text-[9px] text-blue-300/80 group-hover:text-blue-100 font-mono truncate">kmb@tvri.go.id</p>
                                    </button>

                                    <!-- Promo -->
                                    <button type="button" @click="fillDev('promo@tvri.go.id')" class="p-2.5 bg-[#00264A]/80 hover:bg-[#003B70] rounded-xl border border-blue-400/30 hover:border-cyan-400 text-left hover:shadow-md transition-all group">
                                        <p class="font-bold text-blue-100 group-hover:text-white truncate">Promo</p>
                                        <p class="text-[9px] text-blue-300/80 group-hover:text-blue-100 font-mono truncate">promo@tvri.go.id</p>
                                    </button>

                                    <!-- Pengembangan Usaha -->
                                    <button type="button" @click="fillDev('usaha@tvri.go.id')" class="p-2.5 bg-[#00264A]/80 hover:bg-[#003B70] rounded-xl border border-blue-400/30 hover:border-cyan-400 text-left hover:shadow-md transition-all group">
                                        <p class="font-bold text-blue-100 group-hover:text-white truncate">Pengembangan Usaha</p>
                                        <p class="text-[9px] text-blue-300/80 group-hover:text-blue-100 font-mono truncate">usaha@tvri.go.id</p>
                                    </button>

                                    <!-- Teknik -->
                                    <button type="button" @click="fillDev('teknik@tvri.go.id')" class="p-2.5 bg-[#00264A]/80 hover:bg-[#003B70] rounded-xl border border-blue-400/30 hover:border-cyan-400 text-left hover:shadow-md transition-all group">
                                        <p class="font-bold text-blue-100 group-hover:text-white truncate">Teknik</p>
                                        <p class="text-[9px] text-blue-300/80 group-hover:text-blue-100 font-mono truncate">teknik@tvri.go.id</p>
                                    </button>

                                    <!-- Teknik Transmisi -->
                                    <button type="button" @click="fillDev('transmisi@tvri.go.id')" class="p-2.5 bg-[#00264A]/80 hover:bg-[#003B70] rounded-xl border border-blue-400/30 hover:border-cyan-400 text-left hover:shadow-md transition-all group">
                                        <p class="font-bold text-blue-100 group-hover:text-white truncate">Teknik Transmisi</p>
                                        <p class="text-[9px] text-blue-300/80 group-hover:text-blue-100 font-mono truncate">transmisi@tvri.go.id</p>
                                    </button>

                                    <!-- Umum -->
                                    <button type="button" @click="fillDev('umum@tvri.go.id')" class="p-2.5 bg-[#00264A]/80 hover:bg-[#003B70] rounded-xl border border-blue-400/30 hover:border-cyan-400 text-left hover:shadow-md transition-all group">
                                        <p class="font-bold text-blue-100 group-hover:text-white truncate">Umum</p>
                                        <p class="text-[9px] text-blue-300/80 group-hover:text-blue-100 font-mono truncate">umum@tvri.go.id</p>
                                    </button>

                                    <!-- Keuangan -->
                                    <button type="button" @click="fillDev('keuangan@tvri.go.id')" class="p-2.5 bg-[#00264A]/80 hover:bg-[#003B70] rounded-xl border border-blue-400/30 hover:border-cyan-400 text-left hover:shadow-md transition-all group">
                                        <p class="font-bold text-blue-100 group-hover:text-white truncate">Keuangan</p>
                                        <p class="text-[9px] text-blue-300/80 group-hover:text-blue-100 font-mono truncate">keuangan@tvri.go.id</p>
                                    </button>

                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-blue-900/60 flex items-center gap-2 text-[11px] text-blue-200">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Pilih unit di atas untuk mengisi akun resmi, lalu ketik kata sandi Anda untuk MASUK KE SISTEM.</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Navigasi Kembali ke Halaman Utama -->
            <div class="text-center mt-4">
                <a href="/" target="_top" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-200 hover:text-white transition-colors py-1.5 px-4 rounded-xl hover:bg-white/10 border border-transparent hover:border-blue-400/30 shadow-sm">
                    <svg class="w-3.5 h-3.5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Kembali ke Halaman Utama</span>
                </a>
            </div>

            <!-- Footer Note -->
            <p class="text-center text-xs text-blue-200/80 mt-5 font-medium">
                &copy; {{ date('Y') }} TVRI Maluku &bull; Dari Maluku untuk Indonesia
            </p>

        </div>
    </main>

    <!-- Forgot Password Modal -->
    <div x-show="showForgotModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm"
         x-cloak>
        <div @click.away="showForgotModal = false" class="bg-[#002244] rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl border border-blue-500/40 text-white">
            <div class="w-12 h-12 rounded-2xl bg-[#001830] text-cyan-300 mx-auto flex items-center justify-center mb-3 border border-blue-400/30">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h3 class="text-sm font-bold text-white">Pemulihan Kata Sandi</h3>
            <p class="text-xs text-blue-200/80 mt-2 leading-relaxed">
                Untuk alasan keamanan sistem, reset kata sandi wajib dilakukan secara langsung melalui <strong class="text-white">Administrator Sistem TVRI Maluku</strong>.
            </p>
            <button @click="showForgotModal = false" class="mt-5 w-full py-2.5 bg-[#005B9F] text-white font-bold text-xs rounded-xl hover:bg-[#004a8c] transition-colors border border-blue-400/30">
                Mengerti
            </button>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIconClosed = document.getElementById('eye-icon-closed');
            const eyeIconOpen = document.getElementById('eye-icon-open');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIconClosed.classList.add('hidden');
                eyeIconOpen.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIconClosed.classList.remove('hidden');
                eyeIconOpen.classList.add('hidden');
            }
        }
    </script>
</body>
</html>