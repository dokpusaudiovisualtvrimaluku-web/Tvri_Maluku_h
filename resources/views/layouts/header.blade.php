<!-- Top Navigation Header -->
<header class="h-16 sm:h-20 bg-white border-b border-slate-200/90 flex items-center justify-between px-3 sm:px-6 lg:px-8 shadow-sm flex-shrink-0 z-20">
    
    <!-- Left Section: Mobile Toggle & Official Title -->
    <div class="flex items-center space-x-2.5 sm:space-x-3.5 min-w-0 flex-1">
        <!-- Mobile Sidebar Hamburger -->
        <button @click="sidebarOpen = !sidebarOpen" 
                type="button" 
                class="lg:hidden p-2 rounded-xl text-slate-600 hover:text-[#003B70] hover:bg-slate-100 transition-colors focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>

        <div class="min-w-0">
            <div class="flex items-center space-x-2">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-[#E31837] animate-pulse"></span>
                <span class="text-[11px] font-bold uppercase tracking-wider text-[#003B70]">LPP Televisi Republik Indonesia</span>
            </div>
            <h2 class="text-xs sm:text-sm md:text-base font-extrabold text-[#00264A] tracking-tight truncate leading-snug">
                TVRI MALUKU &bull; EXECUTIVE COMMAND CENTER
            </h2>
        </div>
    </div>

    <!-- Right Section: Notification & User Profile Dropdown -->
    <div class="flex items-center space-x-2 sm:space-x-4">

        <!-- Real-Time Clock Widget (Semua Akun) -->
        <div class="hidden lg:flex items-center space-x-2.5 bg-slate-50 border border-slate-200/90 px-3 py-1.5 rounded-xl shadow-xs">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <div class="text-[11px] font-bold text-slate-700 flex items-center space-x-1.5 leading-none">
                <span class="global-live-daydate">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
                <span class="text-slate-300">&bull;</span>
                <span class="font-mono font-black text-[#003B70] global-live-clock">{{ now()->format('H:i:s') }} WIT</span>
            </div>
        </div>

        <!-- Notification Bell (Informative / Stage 1 Ready) -->
        <div class="relative" x-data="{ openNotif: false }">
            <button @click="openNotif = !openNotif" 
                    class="p-2.5 rounded-xl text-slate-500 hover:text-[#003B70] hover:bg-[#EAF4FB] transition-colors relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-emerald-500 ring-2 ring-white"></span>
            </button>

            <!-- Notification Dropdown Modal -->
            <div x-show="openNotif" 
                 @click.away="openNotif = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-200 py-3 z-50"
                 x-cloak>
                <div class="px-4 py-2 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-[#00264A] uppercase tracking-wider">Pemberitahuan Sistem</h3>
                    <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Sistem Normal</span>
                </div>
                <div class="p-4 text-center">
                    <div class="w-10 h-10 rounded-full bg-[#EAF4FB] text-[#003B70] mx-auto flex items-center justify-center mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-xs font-medium text-slate-700">Sistem Monitoring TVRI Maluku Aktif</p>
                    <p class="text-[11px] text-slate-500 mt-1">Dashboard terintegrasi seluruh bidang TVRI Maluku siap digunakan.</p>
                </div>
            </div>
        </div>

        <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>

        <!-- User Profile Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" 
                    type="button" 
                    class="flex items-center space-x-3 p-1.5 rounded-xl hover:bg-slate-100 transition-colors focus:outline-none group">
                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-9 h-9 rounded-xl object-cover ring-2 ring-[#003B70]/20 shadow-sm group-hover:ring-[#003B70]/40">
                <div class="text-left hidden md:block">
                    <p class="text-xs font-bold text-slate-800 leading-tight group-hover:text-[#003B70] truncate max-w-[130px] lg:max-w-[200px]">{{ auth()->user()->name }}</p>
                    @php
                        $userRoleUpper = strtoupper(str_replace(' ', '_', auth()->user()->role_name ?? ''));
                        $isTuUser = auth()->user()->username === 'tu' || str_contains(strtolower(auth()->user()->name ?? ''), 'tata usaha');
                        $showRoleBadge = auth()->user()->role_name && $userRoleUpper !== 'KEPALA_DINAS' && !$isTuUser && $userRoleUpper !== 'OPERATOR';
                    @endphp
                    @if($showRoleBadge)
                    <span class="inline-block mt-0.5 px-2 py-0.5 text-[9px] font-extrabold uppercase rounded-full {{ auth()->user()->hasRole('ADMIN') ? 'bg-[#003B70] text-white' : 'bg-amber-600 text-white' }}">
                        {{ auth()->user()->role_name }}
                    </span>
                    @endif
                </div>
                <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" 
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-200 py-2 z-50 divide-y divide-slate-100"
                 x-cloak>
                
                <div class="px-4 py-2.5 md:hidden">
                    <p class="text-xs font-bold text-slate-800">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-slate-500">{{ auth()->user()->email }}</p>
                </div>

                <div class="py-1">
                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-[#EAF4FB] hover:text-[#003B70] transition-colors">
                        <svg class="w-4 h-4 mr-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Profil Pengguna
                    </a>
                    <a href="{{ route('profile.password') }}" class="flex items-center px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-[#EAF4FB] hover:text-[#003B70] transition-colors">
                        <svg class="w-4 h-4 mr-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        Ubah Password
                    </a>
                </div>

                <div class="py-1">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 hover:text-rose-700 transition-colors">
                            <svg class="w-4 h-4 mr-2.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Keluar (Logout)
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</header>
