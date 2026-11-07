<!-- TOP NAVIGATION BAR (SIMANDAT SIWALIMA) -->
<header class="bg-[#0b1325]/95 backdrop-blur-md border-b border-app-border sticky top-0 z-40 transition-all shadow-md">
    <!-- Top Bar: Brand, Quick Status & Admin Profile -->
    <div class="h-16 px-4 md:px-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <button onclick="app.loadContent('1.1')" class="flex items-center gap-3 text-left group">
                <div class="w-10 h-10 rounded-xl bg-cyan-500/15 border border-cyan-500/40 flex items-center justify-center text-cyan-400 group-hover:shadow-[0_0_18px_rgba(0,246,255,0.45)] transition-all">
                    <i class="fa-solid fa-graduation-cap text-lg"></i>
                </div>
                <div>
                    <h1 class="font-extrabold text-base text-white tracking-wide flex items-center gap-2">
                        SIMANDAT <span class="text-cyan-400 text-xs font-bold border border-cyan-500/30 rounded px-1.5 py-0.5 bg-cyan-500/10">SIWALIMA</span>
                    </h1>
                    <p class="text-xs text-slate-400 leading-tight">Pangkalan Data Dinas PUPR Maluku</p>
                </div>
            </button>
        </div>

        <!-- Center Quick Action: Command Center Live Badge -->
        <div class="hidden lg:flex items-center gap-2.5 bg-[#070e1c] border border-cyan-500/30 px-3.5 py-1.5 rounded-full text-xs shadow-inner">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
            <span class="text-slate-300 text-xs font-medium">Sistem Pangkalan Data Dinas PUPR Maluku & Pelaporan Terintegrasi</span>
            <button onclick="app.loadContent('1.1')" class="text-cyan-400 hover:text-cyan-300 font-bold text-xs ml-1 underline decoration-cyan-500/50 flex items-center gap-1">
                <i class="fa-solid fa-gauge-high text-[11px]"></i> 1.1 Executive View
            </button>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="hidden sm:flex items-center gap-2 text-slate-400 text-xs bg-[#070e1c] border border-slate-800 px-3 py-1.5 rounded-lg">
                <i class="fa-regular fa-clock text-cyan-400 text-xs"></i>
                <span id="currentTime" class="font-mono text-xs text-slate-200">Sabtu, 12 Sep 2026 08:30:41 WIT</span>
            </div>
            
            <div class="flex items-center gap-2.5 pl-3 border-l border-slate-800 cursor-pointer hover:bg-slate-800/60 p-1.5 rounded-xl transition-all border border-transparent hover:border-cyan-500/30 group" onclick="app.openAdminAccountModal()" title="Klik untuk membuka Informasi Akun & Database Explorer">
                <div class="text-right">
                    <div id="userRoleDisplay" class="font-bold text-xs text-white group-hover:text-cyan-400 transition-colors flex items-center justify-end gap-1">
                        <span>
                        @auth
                            {{ auth()->user()->name }}
                        @else
                            Administrator
                        @endauth
                        </span>
                        <i class="fa-solid fa-chevron-down text-[9px] text-slate-400 group-hover:text-cyan-400 transition-colors"></i>
                    </div>
                    <div id="userStatusDisplay" class="text-[9px] text-cyan-400 font-medium">
                        @auth
                            {{ auth()->user()->roles->pluck('name')->join(', ') ?: 'Super Admin (Full Access)' }}
                        @else
                            Super Admin (Full Access)
                        @endauth
                    </div>
                </div>
                <div class="w-9 h-9 rounded-lg bg-slate-800 border border-slate-700 flex items-center justify-center relative group-hover:border-cyan-400/60 group-hover:ring-1 group-hover:ring-cyan-400/40 transition-all">
                    <i class="fa-solid fa-user-shield text-sm text-cyan-400"></i>
                    <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-500 rounded-full border-2 border-[#0b1325]"></div>
                </div>
            </div>

            <!-- Dedicated Quick Logout Button -->
            <button onclick="app.logout()" class="px-3 py-1.5 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 border border-red-500/30 transition-colors text-xs font-semibold flex items-center gap-1.5 shadow-sm" title="Keluar / Logout dari Sistem">
                <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                <span class="hidden sm:inline">Logout</span>
            </button>
        </div>
    </div>
</header>
