<!-- Sidebar Container -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
       class="fixed lg:static inset-y-0 left-0 z-50 w-72 max-w-[85vw] bg-[#00264A] text-white flex flex-col transition-transform duration-300 ease-in-out shadow-2xl lg:shadow-none flex-shrink-0 select-none">

    <!-- Logo & Brand Header -->
    <div class="h-20 bg-[#001D38] border-b border-blue-900/50 flex items-center justify-between px-5">
        <div class="flex items-center space-x-3.5">
            <div class="w-11 h-11 rounded-xl bg-white p-1.5 border border-blue-400/30 flex items-center justify-center shadow-inner">
                <img src="{{ asset('images/logo_tvri.png') }}" alt="Logo TVRI" class="w-full h-full object-contain rounded-lg">
            </div>
            <div>
                <h1 class="text-sm font-black tracking-wider uppercase text-white leading-tight">TVRI MALUKU</h1>
                <p class="text-[10px] text-blue-300 font-medium leading-tight">Media Pemersatu Bangsa</p>
            </div>
        </div>

        <!-- Mobile Close Button -->
        <button @click="sidebarOpen = false" class="lg:hidden text-blue-300 hover:text-white p-1.5 rounded-lg hover:bg-blue-900/50">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <!-- Tagline Banner -->
    <div class="px-5 py-2.5 bg-[#003B70]/70 border-b border-blue-900/40">
        <p class="text-[10px] font-semibold text-blue-200 uppercase tracking-widest text-center">
            Satu Data, Satu Arah, Penyiaran Lebih Kuat
        </p>
    </div>

    <!-- Navigation Menu Items -->
    <div class="flex-1 overflow-y-auto px-4 py-4 space-y-6" @click="if (window.innerWidth < 1024 && $event.target.closest('a')) sidebarOpen = false">

        @php
            $currentUser = auth()->user();
            $isBerita = $currentUser && (
                $currentUser->username === 'berita' ||
                $currentUser->email === 'berita@tvri.go.id' ||
                (method_exists($currentUser, 'hasRole') && $currentUser->hasRole('Berita')) ||
                str_contains(strtolower($currentUser->name ?? ''), 'berita')
            );
            $isKeuangan = $currentUser && (
                in_array($currentUser->username, ['keuangan', 'kasubag.keuangan']) ||
                $currentUser->email === 'keuangan@tvri.go.id' ||
                (method_exists($currentUser, 'hasRole') && $currentUser->hasRole('Keuangan')) ||
                str_contains(strtolower($currentUser->name ?? ''), 'keuangan')
            );
            $isAdmin = $currentUser && in_array($currentUser->username, ['admin', 'superadmin']);
            $isKepalaStasiun = $currentUser && ($currentUser->hasRole('KEPALA_DINAS') || $currentUser->hasRole('Kepala Stasiun') || $currentUser->username === 'kepala.stasiun' || str_contains(strtolower($currentUser->name), 'kepala stasiun'));

            $isTU = $currentUser && (in_array($currentUser->username, ['admin.tu', 'tu']) || $currentUser->hasRole('Tata Usaha') || str_contains(strtolower($currentUser->name ?? ''), 'tata usaha'));
            $isProgram = $currentUser && ($currentUser->username === 'program');
            $isKMB = $currentUser && ($currentUser->username === 'konten.media.baru' || str_contains(strtolower($currentUser->name ?? ''), 'konten media baru'));
            $isPromo = $currentUser && ($currentUser->username === 'promo' || $currentUser->hasRole('Promo') || str_contains(strtolower($currentUser->name ?? ''), 'promo'));
            $isUsaha = $currentUser && ($currentUser->username === 'pengembangan.usaha' || str_contains(strtolower($currentUser->name ?? ''), 'pengembangan usaha'));
            $isTeknik = $currentUser && ($currentUser->username === 'teknik');
            $isTransmisi = $currentUser && (
                in_array($currentUser->username, ['teknik.transmisi', 'transmisi']) ||
                $currentUser->email === 'transmisi@tvri.go.id' ||
                (method_exists($currentUser, 'hasRole') && $currentUser->hasRole('Teknik Transmisi')) ||
                str_contains(strtolower($currentUser->name ?? ''), 'transmisi')
            );
            $isUmum = $currentUser && ($currentUser->username === 'umum');

            if ($isBerita) {
                $dashboardRoute = route('tvri.berita.dashboard');
                $dashboardLabel = 'Berita (Newsroom)';
                $isDashboardActive = request()->routeIs('tvri.berita.dashboard');
            } elseif ($isKeuangan) {
                $dashboardRoute = route('tvri.keuangan.dashboard');
                $dashboardLabel = 'Keuangan & PNBP';
                $isDashboardActive = request()->routeIs('tvri.keuangan.dashboard');
            } elseif ($isTU) {
                $dashboardRoute = route('tvri.tu.dashboard');
                $dashboardLabel = 'Tata Usaha (KTU)';
                $isDashboardActive = request()->routeIs('tvri.tu.*') || request()->routeIs('tvri.ktu.*');
            } elseif ($isProgram) {
                $dashboardRoute = route('tvri.program.dashboard');
                $dashboardLabel = 'Program & Siaran';
                $isDashboardActive = request()->routeIs('tvri.program.*');
            } elseif ($isKMB) {
                $dashboardRoute = route('tvri.mbn.dashboard');
                $dashboardLabel = 'Konten Media Baru';
                $isDashboardActive = request()->routeIs('tvri.mbn.*') || request()->routeIs('tvri.kmb.*');
            } elseif ($isPromo) {
                $dashboardRoute = route('tvri.promo.dashboard');
                $dashboardLabel = 'Dasboar Unit Kerja Promo';
                $isDashboardActive = request()->routeIs('tvri.promo.*');
            } elseif ($isUsaha) {
                $dashboardRoute = route('tvri.usaha.dashboard');
                $dashboardLabel = 'Pengembangan Usaha';
                $isDashboardActive = request()->routeIs('tvri.usaha.*');
            } elseif ($isTeknik) {
                $dashboardRoute = route('tvri.teknik.dashboard');
                $dashboardLabel = 'Teknik Studio';
                $isDashboardActive = request()->routeIs('tvri.teknik.*');
            } elseif ($isTransmisi) {
                $dashboardRoute = route('tvri.transmisi.dashboard');
                $dashboardLabel = 'Teknik Transmisi';
                $isDashboardActive = request()->routeIs('tvri.transmisi.*');
            } elseif ($isUmum) {
                $dashboardRoute = route('tvri.umum.dashboard');
                $dashboardLabel = 'Umum & Logistik';
                $isDashboardActive = request()->routeIs('tvri.umum.*');
            } else {
                $dashboardRoute = route('tvri.ecc.dashboard');
                $dashboardLabel = 'Executive Command Center';
                $isDashboardActive = request()->routeIs('tvri.ecc.dashboard') || request()->routeIs('kepala-dinas.dashboard');
            }
        @endphp

        <!-- 1. UTAMA -->
        <div>
            <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-blue-400">Utama</div>
            <div class="space-y-1">
                <a href="{{ $dashboardRoute }}"
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ $isDashboardActive ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>{{ $dashboardLabel }}</span>
                </a>
                @if(!$isKepalaStasiun)
                <a href="{{ route('tvri.ecc.dashboard') }}"
                   class="flex items-center space-x-3 px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('tvri.ecc.dashboard') ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-200 hover:bg-blue-900/50 hover:text-white' }}">
                    <svg class="w-4 h-4 flex-shrink-0 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span>Monitoring ECC</span>
                </a>
                @else
                <a href="{{ route('tvri.berita.dashboard') }}"
                   class="flex items-center space-x-3 px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('tvri.berita.*') ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-200 hover:bg-blue-900/50 hover:text-white' }}">
                    <svg class="w-4 h-4 flex-shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    <span>Newsroom Berita</span>
                </a>
                <a href="{{ route('dokumen.documents.index') }}"
                   class="flex items-center space-x-3 px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('dokumen.documents.*') ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-200 hover:bg-blue-900/50 hover:text-white' }}">
                    <svg class="w-4 h-4 flex-shrink-0 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    <span>Arsip Data</span>
                </a>
                @endif
            </div>
        </div>

        <!-- 2. UNIT KERJA -->
        @php
            $bidangNav = [];
        @endphp
        @if(!empty($bidangNav))
        <div>
            <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-amber-300">Unit Kerja</div>
            <div class="space-y-1">
                @foreach ($bidangNav as $bn)
                    @php $bnActive = request()->routeIs('tvri.transmisi.dashboard') && !request()->routeIs('tvri.transmisi.manage'); @endphp
                    <a href="{{ route($bn['route']) }}"
                       class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 {{ $bnActive ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                        <div class="flex items-center space-x-2.5">
                            <span class="w-1.5 h-1.5 rounded-full {{ $bnActive ? 'bg-amber-300' : 'bg-blue-300/60' }}"></span>
                            <span>{{ $bn['label'] }}</span>
                        </div>
                        <span class="px-1.5 py-0.5 text-[9px] font-bold rounded border {{ $bnActive ? 'bg-white/20 text-white' : 'bg-white/10 text-blue-200 border-white/10' }}">{{ $bn['badge'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        @if($isBerita || ($isKepalaStasiun && request()->routeIs('tvri.berita.*')))
        <!-- MENU UTAMA / NAVIGASI KIRI BIDANG BERITA (NEWSROOM) -->
        <div>
            <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-rose-300">Navigasi Newsroom</div>
            <div class="space-y-1">
                <a href="{{ route('tvri.berita.dashboard') }}"
                   class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('tvri.berita.dashboard') && !request()->routeIs('tvri.berita.manage') ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('tvri.berita.dashboard') ? 'bg-amber-300' : 'bg-rose-400' }}"></span>
                        <span>Dashboard Berita</span>
                    </div>
                </a>
                <a href="{{ route('tvri.berita.dashboard') }}#agenda"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Agenda Liputan</span>
                    </div>
                </a>
                <a href="{{ route('tvri.berita.dashboard') }}#realtime"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Real Time Feeds</span>
                    </div>
                </a>
                <a href="{{ route('tvri.berita.dashboard') }}#sebaran"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Sebaran Liputan</span>
                    </div>
                </a>
                <a href="{{ route('tvri.berita.dashboard') }}#top-berita"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Top Berita Digital</span>
                    </div>
                </a>
                <a href="{{ route('tvri.berita.dashboard') }}#produksi-paket"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Produksi Paket</span>
                    </div>
                </a>
                <a href="{{ route('tvri.berita.dashboard') }}#monitoring-berita"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Monitoring Berita Harian</span>
                    </div>
                </a>
                <a href="{{ route('tvri.berita.dashboard') }}#dokumentasi-berita"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Dokumentasi Siaran</span>
                    </div>
                </a>
                <a href="{{ route('tvri.berita.dashboard') }}#sop"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Dokumen & SOP</span>
                    </div>
                </a>
                <a href="{{ route('dokumen.documents.index', ['folder_id' => 3, 'division' => 'Berita']) }}"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('dokumen.documents.*') && request('division') === 'Berita' ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                        <span>Arsip Data</span>
                    </div>
                    <span class="px-1.5 py-0.5 text-[9px] font-bold rounded border bg-rose-500/20 text-rose-200 border-rose-400/30">DMS</span>
                </a>
                @if(!$isKepalaStasiun)
                <a href="{{ route('tvri.berita.manage') }}"
                   class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('tvri.berita.manage') ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        <span>Input & Kelola Berita</span>
                    </div>
                    <span class="px-1.5 py-0.5 text-[9px] font-bold rounded border bg-amber-500/20 text-amber-200 border-amber-400/30">CRUD</span>
                </a>
                @else
                <a href="{{ route('tvri.ecc.dashboard') }}"
                   class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold bg-cyan-900/40 border border-cyan-400/30 text-cyan-200 hover:bg-cyan-800/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span>Kembali ke ECC</span>
                    </div>
                </a>
                @endif
            </div>
        </div>
        @endif

        @if($isProgram)
        <!-- MENU UTAMA / NAVIGASI KIRI BIDANG PROGRAM -->
        <div>
            <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-purple-300">Navigasi Program</div>
            <div class="space-y-1">
                <a href="{{ route('tvri.program.dashboard') }}"
                   class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('tvri.program.dashboard') ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('tvri.program.dashboard') ? 'bg-amber-300' : 'bg-purple-400' }}"></span>
                        <span>Dashboard</span>
                    </div>
                </a>
                <a href="{{ route('tvri.program.dashboard') }}#perencanaan"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Perencanaan</span>
                    </div>
                </a>
                <a href="{{ route('tvri.program.dashboard') }}#tahap-produksi"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Pra Produksi & Produksi</span>
                    </div>
                </a>
                <a href="{{ route('tvri.program.dashboard') }}#tahap-produksi"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Pasca Produksi & QC</span>
                    </div>
                </a>
                <a href="{{ route('tvri.program.dashboard') }}#daftar-program"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Penayangan & Jadwal</span>
                    </div>
                </a>
                <a href="{{ route('tvri.program.dashboard') }}#evaluasi"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Evaluasi Program</span>
                    </div>
                </a>
                <a href="{{ route('tvri.program.dashboard') }}#kalender"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Kalender Program</span>
                    </div>
                </a>
                <a href="{{ route('tvri.program.dashboard') }}#laporan"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Arsip & Laporan</span>
                    </div>
                </a>
                <a href="{{ route('dokumen.documents.index', ['folder_id' => 5, 'division' => 'Program']) }}"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('dokumen.documents.*') && request('division') === 'Program' ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-purple-400"></span>
                        <span>Arsip Data</span>
                    </div>
                    <span class="px-1.5 py-0.5 text-[9px] font-bold rounded border bg-purple-500/20 text-purple-200 border-purple-400/30">DMS</span>
                </a>
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Pengaturan</span>
                    </div>
                </a>
            </div>
        </div>
        @endif

        @if($isUmum)
        <!-- MENU UTAMA / NAVIGASI KIRI BIDANG UMUM & LOGISTIK -->
        <div>
            <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-emerald-400">Navigasi Umum & Logistik</div>
            <div class="space-y-1">
                <a href="{{ route('tvri.umum.dashboard') }}"
                   class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('tvri.umum.dashboard') ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('tvri.umum.dashboard') ? 'bg-amber-300' : 'bg-emerald-400' }}"></span>
                        <span>Dashboard</span>
                    </div>
                </a>
                <a href="{{ route('tvri.umum.dashboard') }}#manajemen-aset"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Manajemen Aset</span>
                    </div>
                </a>
                <a href="{{ route('tvri.umum.dashboard') }}#sarana-prasarana"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Sarana Prasarana</span>
                    </div>
                </a>
                <a href="{{ route('tvri.umum.dashboard') }}#kendaraan-dinas"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Kendaraan Dinas</span>
                    </div>
                </a>
                <a href="{{ route('tvri.umum.dashboard') }}#pemeliharaan"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Pemeliharaan</span>
                    </div>
                </a>
                <a href="{{ route('tvri.umum.dashboard') }}#layanan-umum"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Layanan Umum</span>
                    </div>
                </a>
                <a href="{{ route('tvri.umum.dashboard') }}#keamanan-k3"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Keamanan & K3</span>
                    </div>
                </a>
                <a href="{{ route('tvri.umum.dashboard') }}#kebersihan-lingkungan"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Kebersihan & Lingkungan</span>
                    </div>
                </a>
                <a href="{{ route('tvri.umum.dashboard') }}#pengadaan-logistik"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Pengadaan & Logistik</span>
                    </div>
                </a>
                <a href="{{ route('tvri.umum.dashboard') }}#monitoring-layanan"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Monitoring Layanan</span>
                    </div>
                </a>
                <a href="{{ route('tvri.umum.dashboard') }}#laporan"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Laporan</span>
                    </div>
                </a>
                <a href="{{ route('dokumen.documents.index', ['folder_id' => 4, 'division' => 'Umum']) }}"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('dokumen.documents.*') && request('division') === 'Umum' ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        <span>Arsip Data</span>
                    </div>
                    <span class="px-1.5 py-0.5 text-[9px] font-bold rounded border bg-emerald-500/20 text-emerald-200 border-emerald-400/30">DMS</span>
                </a>
                <a href="{{ route('tvri.umum.dashboard') }}#kalender-kegiatan"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Kalender Kegiatan</span>
                    </div>
                </a>
                <a href="{{ route('tvri.umum.dashboard') }}#kontak-vendor"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Kontak & Vendor</span>
                    </div>
                </a>
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Pengaturan</span>
                    </div>
                </a>
            </div>
        </div>
        @endif

        @if($isTU)
        <!-- MENU UTAMA / NAVIGASI KIRI BIDANG TATA USAHA -->
        <div>
            <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-blue-300">Navigasi Tata Usaha</div>
            <div class="space-y-1">
                <a href="{{ route('tvri.tu.dashboard') }}"
                   class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('tvri.tu.dashboard') && !request()->routeIs('tvri.tu.manage') ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('tvri.tu.dashboard') ? 'bg-amber-300' : 'bg-blue-400' }}"></span>
                        <span>Dashboard</span>
                    </div>
                </a>
                <a href="{{ route('tvri.tu.dashboard') }}#surat-masuk"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Surat Masuk</span>
                    </div>
                </a>
                <a href="{{ route('tvri.tu.dashboard') }}#surat-keluar"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Surat Keluar</span>
                    </div>
                </a>
                <a href="{{ route('tvri.tu.dashboard') }}#status-disposisi"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Disposisi</span>
                    </div>
                </a>
                <a href="{{ route('tvri.tu.dashboard') }}#agenda"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Agenda & Kegiatan</span>
                    </div>
                </a>
                <a href="{{ route('tvri.tu.dashboard') }}#layanan-adm"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Layanan Administrasi</span>
                    </div>
                </a>
                <a href="{{ route('tvri.tu.dashboard') }}#arsip-dokumen"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Arsip & Dokumentasi</span>
                    </div>
                </a>
                <a href="{{ route('dokumen.documents.index', ['folder_id' => 2, 'division' => 'Tata Usaha']) }}"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('dokumen.documents.*') && request('division') === 'Tata Usaha' ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                        <span>Arsip Data</span>
                    </div>
                    <span class="px-1.5 py-0.5 text-[9px] font-bold rounded border bg-blue-500/20 text-blue-200 border-blue-400/30">DMS</span>
                </a>
                <a href="{{ route('tvri.tu.dashboard') }}#sop-regulasi"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>SOP & Regulasi</span>
                    </div>
                </a>
                <a href="{{ route('tvri.tu.dashboard') }}#monitoring-kinerja"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Monitoring Kinerja</span>
                    </div>
                </a>
                <a href="{{ route('tvri.tu.dashboard') }}#laporan"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Laporan</span>
                    </div>
                </a>
                <a href="{{ route('tvri.tu.dashboard') }}#kalender"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Kalender</span>
                    </div>
                </a>
                <a href="{{ route('tvri.tu.dashboard') }}#kontak-link"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Kontak & Direktorat</span>
                    </div>
                </a>
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Pengaturan</span>
                    </div>
                </a>
            </div>
        </div>
        @endif

        @if($isUsaha)
        <!-- MENU UTAMA / NAVIGASI KIRI BIDANG PENGEMBANGAN USAHA -->
        <div>
            <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-amber-300">Navigasi Pengembangan Usaha</div>
            <div class="space-y-1">
                <a href="{{ route('tvri.usaha.dashboard') }}"
                   class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('tvri.usaha.dashboard') && !request()->routeIs('tvri.usaha.manage') ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('tvri.usaha.dashboard') ? 'bg-amber-300' : 'bg-orange-400' }}"></span>
                        <span>Beranda</span>
                    </div>
                </a>
                <a href="{{ route('tvri.usaha.dashboard') }}#pendapatan"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Pendapatan</span>
                    </div>
                </a>
                <a href="{{ route('tvri.usaha.dashboard') }}#kemitraan"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Kemitraan</span>
                    </div>
                </a>
                <a href="{{ route('tvri.usaha.dashboard') }}#program-usaha"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Program Usaha</span>
                    </div>
                </a>
                <a href="{{ route('tvri.usaha.dashboard') }}#iklan-sponsorship"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Iklan & Sponsorship</span>
                    </div>
                </a>
                <a href="{{ route('tvri.usaha.dashboard') }}#event-aktivasi"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Event & Aktivasi</span>
                    </div>
                </a>
                <a href="{{ route('tvri.usaha.dashboard') }}#aset-fasilitas"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Aset & Fasilitas</span>
                    </div>
                </a>
                <a href="{{ route('tvri.usaha.dashboard') }}#analitik-audiens"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Analitik Audiens</span>
                    </div>
                </a>
                <a href="{{ route('tvri.usaha.dashboard') }}#keuangan"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Keuangan</span>
                    </div>
                </a>
                <a href="{{ route('tvri.usaha.dashboard') }}#manajemen-risiko"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Manajemen Risiko</span>
                    </div>
                </a>
                <a href="{{ route('tvri.usaha.dashboard') }}#rencana-kerja"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Rencana Kerja</span>
                    </div>
                </a>
                <a href="{{ route('tvri.usaha.dashboard') }}#laporan"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Laporan</span>
                    </div>
                </a>
                <a href="{{ route('dokumen.documents.index', ['folder_id' => 8, 'division' => 'Pengembangan Usaha']) }}"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('dokumen.documents.*') && request('division') === 'Pengembangan Usaha' ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        <span>Arsip Data</span>
                    </div>
                    <span class="px-1.5 py-0.5 text-[9px] font-bold rounded border bg-amber-500/20 text-amber-200 border-amber-400/30">DMS</span>
                </a>
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Pengaturan</span>
                    </div>
                </a>
            </div>
        </div>
        @endif

        @if($isKMB)
        <!-- MENU UTAMA / NAVIGASI KIRI BIDANG KONTEN MEDIA BARU -->
        <div>
            <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-pink-300">Navigasi Media Baru</div>
            <div class="space-y-1">
                <a href="{{ route('tvri.mbn.dashboard') }}"
                   class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 {{ (request()->routeIs('tvri.mbn.dashboard') || request()->routeIs('tvri.kmb.dashboard')) && !request()->routeIs('tvri.mbn.manage') ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('tvri.mbn.dashboard') || request()->routeIs('tvri.kmb.dashboard') ? 'bg-amber-300' : 'bg-pink-400' }}"></span>
                        <span>Dashboard</span>
                    </div>
                </a>
                <a href="{{ route('tvri.mbn.dashboard') }}#konten"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Konten</span>
                    </div>
                </a>
                <a href="{{ route('tvri.mbn.dashboard') }}#publishing"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Publishing</span>
                    </div>
                </a>
                <a href="{{ route('tvri.mbn.dashboard') }}#analitik"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Analitik</span>
                    </div>
                </a>
                <a href="{{ route('tvri.mbn.dashboard') }}#audiens"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Audiens</span>
                    </div>
                </a>
                <a href="{{ route('tvri.mbn.dashboard') }}#media-sosial"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Media Sosial</span>
                    </div>
                </a>
                <a href="{{ route('tvri.mbn.dashboard') }}#live-streaming"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Live Streaming</span>
                    </div>
                </a>
                <a href="{{ route('tvri.mbn.dashboard') }}#website"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Website</span>
                    </div>
                </a>
                <a href="{{ route('tvri.mbn.dashboard') }}#kolaborasi"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Kolaborasi</span>
                    </div>
                </a>
                <a href="{{ route('tvri.mbn.dashboard') }}#monetisasi"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Monetisasi</span>
                    </div>
                </a>
                <a href="{{ route('tvri.mbn.dashboard') }}#laporan"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Laporan</span>
                    </div>
                </a>
                <a href="{{ route('dokumen.documents.index', ['folder_id' => 6, 'division' => 'KBM']) }}"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('dokumen.documents.*') && request('division') === 'KBM' ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-pink-400"></span>
                        <span>Arsip Data</span>
                    </div>
                    <span class="px-1.5 py-0.5 text-[9px] font-bold rounded border bg-pink-500/20 text-pink-200 border-pink-400/30">DMS</span>
                </a>
                <a href="{{ route('tvri.mbn.dashboard') }}#kalender-konten"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Kalender Konten</span>
                    </div>
                </a>
                <a href="{{ route('tvri.mbn.dashboard') }}#manajemen-tim"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Manajemen Tim</span>
                    </div>
                </a>
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Pengaturan</span>
                    </div>
                </a>
            </div>
        </div>
        @endif

        @if($isTeknik)
        <!-- MENU UTAMA / NAVIGASI KIRI BIDANG TEKNIK STUDIO -->
        <div>
            <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-cyan-300">Navigasi Teknik Studio</div>
            <div class="space-y-1">
                <a href="{{ route('tvri.teknik.dashboard') }}"
                   class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('tvri.teknik.dashboard') && !request()->routeIs('tvri.teknik.manage') ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('tvri.teknik.dashboard') ? 'bg-amber-300' : 'bg-cyan-400' }}"></span>
                        <span>Dashboard</span>
                    </div>
                </a>
                <a href="{{ route('tvri.teknik.dashboard') }}#status-siaran"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Status Siaran (Live)</span>
                    </div>
                </a>
                <a href="{{ route('tvri.teknik.dashboard') }}#readiness"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Technical Readiness</span>
                    </div>
                </a>
                <a href="{{ route('tvri.teknik.dashboard') }}#peta-transmisi"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Peta Jangkauan & Tx</span>
                    </div>
                </a>
                <a href="{{ route('tvri.teknik.dashboard') }}#status-studio"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Status Studio</span>
                    </div>
                </a>
                <a href="{{ route('tvri.teknik.dashboard') }}#status-transmitter"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Status Transmitter</span>
                    </div>
                </a>
                <a href="{{ route('tvri.teknik.dashboard') }}#monitoring-sistem"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Monitoring Sistem</span>
                    </div>
                </a>
                <a href="{{ route('tvri.teknik.dashboard') }}#jadwal-produksi"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Jadwal Produksi</span>
                    </div>
                </a>
                <a href="{{ route('tvri.teknik.dashboard') }}#maintenance-servis"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Maintenance & Servis</span>
                    </div>
                </a>
                <a href="{{ route('tvri.teknik.dashboard') }}#insiden-gangguan"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Insiden & Gangguan</span>
                    </div>
                </a>
                <a href="{{ route('tvri.teknik.dashboard') }}#inventaris"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Inventaris Utama</span>
                    </div>
                </a>
                <a href="{{ route('tvri.teknik.dashboard') }}#sop-dokumen"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Dokumen & SOP</span>
                    </div>
                </a>
                <a href="{{ route('dokumen.documents.index', ['folder_id' => 9, 'division' => 'Teknik Studio']) }}"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('dokumen.documents.*') && request('division') === 'Teknik Studio' ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                        <span>Arsip Data</span>
                    </div>
                    <span class="px-1.5 py-0.5 text-[9px] font-bold rounded border bg-cyan-500/20 text-cyan-200 border-cyan-400/30">DMS</span>
                </a>
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Pengaturan</span>
                    </div>
                </a>
            </div>
        </div>
        @endif

        @if($isTransmisi)
        <!-- MENU UTAMA / NAVIGASI KIRI BIDANG TEKNIK TRANSMISI -->
        <div>
            <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-cyan-300">Navigasi Transmisi</div>
            <div class="space-y-1">
                <a href="{{ route('tvri.transmisi.dashboard') }}"
                   class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('tvri.transmisi.dashboard') && !request()->routeIs('tvri.transmisi.manage') ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('tvri.transmisi.dashboard') ? 'bg-amber-300' : 'bg-cyan-400' }}"></span>
                        <span>Dashboard</span>
                    </div>
                </a>
                <a href="{{ route('tvri.transmisi.dashboard') }}#peta-transmisi"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Peta Transmisi</span>
                    </div>
                </a>
                <a href="{{ route('tvri.transmisi.dashboard') }}#stasiun-pemancar"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Stasiun Pemancar</span>
                    </div>
                </a>
                <a href="{{ route('tvri.transmisi.dashboard') }}#monitoring-siaran"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Monitoring Siaran</span>
                    </div>
                </a>
                <a href="{{ route('tvri.transmisi.dashboard') }}#kualitas-sinyal"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Kualitas Sinyal</span>
                    </div>
                </a>
                <a href="{{ route('tvri.transmisi.dashboard') }}#infrastruktur"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Infrastruktur</span>
                    </div>
                </a>
                <a href="{{ route('tvri.transmisi.dashboard') }}#daya-energi"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Daya & Energi</span>
                    </div>
                </a>
                <a href="{{ route('tvri.transmisi.dashboard') }}#alarm-gangguan"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Alarm & Gangguan</span>
                    </div>
                </a>
                <a href="{{ route('tvri.transmisi.dashboard') }}#pemeliharaan"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Pemeliharaan</span>
                    </div>
                </a>
                <a href="{{ route('tvri.transmisi.dashboard') }}#log-aktivitas"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Log Aktivitas</span>
                    </div>
                </a>
                <a href="{{ route('tvri.transmisi.dashboard') }}#dokumen-laporan"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Dokumen & Laporan</span>
                    </div>
                </a>
                <a href="{{ route('dokumen.documents.index', ['folder_id' => 10, 'division' => 'Teknik Transmisi']) }}"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('dokumen.documents.*') && request('division') === 'Teknik Transmisi' ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                        <span>Arsip Data</span>
                    </div>
                    <span class="px-1.5 py-0.5 text-[9px] font-bold rounded border bg-sky-500/20 text-sky-200 border-sky-400/30">DMS</span>
                </a>
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Pengaturan</span>
                    </div>
                </a>
            </div>
        </div>
        @endif

        @if($isKeuangan)
        <!-- MENU UTAMA / NAVIGASI KIRI BIDANG KEUANGAN -->
        <div>
            <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-teal-300">Navigasi Keuangan</div>
            <div class="space-y-1">
                <a href="{{ route('tvri.keuangan.dashboard') }}"
                   class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('tvri.keuangan.dashboard') && !request()->routeIs('tvri.keuangan.manage') ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('tvri.keuangan.dashboard') ? 'bg-amber-300' : 'bg-teal-400' }}"></span>
                        <span>Beranda</span>
                    </div>
                </a>
                <a href="{{ route('tvri.keuangan.dashboard') }}#realisasi-anggaran"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Realisasi Anggaran</span>
                    </div>
                </a>
                <a href="{{ route('tvri.keuangan.dashboard') }}#realisasi-pnbp"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Realisasi PNBP</span>
                    </div>
                </a>
                <a href="{{ route('tvri.keuangan.dashboard') }}#perencanaan-anggaran"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Perencanaan Anggaran</span>
                    </div>
                </a>
                <a href="{{ route('tvri.keuangan.dashboard') }}#pembayaran"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Pembayaran</span>
                    </div>
                </a>
                <a href="{{ route('tvri.keuangan.dashboard') }}#piutang-tagihan"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Piutang & Tagihan</span>
                    </div>
                </a>
                <a href="{{ route('tvri.keuangan.dashboard') }}#posisi-aset"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Aset & BMN</span>
                    </div>
                </a>
                <a href="{{ route('tvri.keuangan.dashboard') }}#laporan-keuangan"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Laporan Keuangan</span>
                    </div>
                </a>
                <a href="{{ route('tvri.keuangan.dashboard') }}#kinerja-ikpa"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Monitoring IKPA</span>
                    </div>
                </a>
                <a href="{{ route('tvri.keuangan.dashboard') }}#analisis-keuangan"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Analisis Keuangan</span>
                    </div>
                </a>
                <a href="{{ route('tvri.keuangan.dashboard') }}#manajemen-risiko"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Manajemen Risiko</span>
                    </div>
                </a>
                <a href="{{ route('tvri.keuangan.dashboard') }}#dokumen-sop"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Dokumen & SOP</span>
                    </div>
                </a>
                <a href="{{ route('dokumen.documents.index', ['folder_id' => 11, 'division' => 'Keuangan']) }}"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('dokumen.documents.*') && request('division') === 'Keuangan' ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-teal-400"></span>
                        <span>Arsip Data</span>
                    </div>
                    <span class="px-1.5 py-0.5 text-[9px] font-bold rounded border bg-teal-500/20 text-teal-200 border-teal-400/30">DMS</span>
                </a>
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Pengaturan</span>
                    </div>
                </a>
            </div>

            <!-- KELOLA & INPUT DATA OPERASIONAL KEUANGAN -->
            <div class="mt-4 pt-3 border-t border-blue-900/40">
                <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-teal-300">Input & Kelola Data</div>
                <div class="space-y-1">
                    @php $isKeuanganManage = request()->routeIs('tvri.keuangan.manage'); @endphp
                    <a href="{{ route('tvri.keuangan.manage') }}"
                       class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 {{ $isKeuanganManage ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                        <div class="flex items-center space-x-2.5">
                            <svg class="w-4 h-4 {{ $isKeuanganManage ? 'text-white' : 'text-teal-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <span>Input Data Keuangan</span>
                        </div>
                        <span class="px-1.5 py-0.5 text-[9px] font-bold rounded border {{ $isKeuanganManage ? 'bg-white/20 text-white' : 'bg-teal-500/20 text-teal-200 border-teal-400/30' }}">CRUD</span>
                    </a>
                </div>
            </div>

            <!-- Slogan Bawah Kiri -->
            <div class="mt-4 px-3 py-2 rounded-xl bg-teal-950/60 border border-teal-500/30 text-center shadow-inner">
                <span class="text-[11px] text-teal-300 font-extrabold tracking-wide">✨ Maluku Lebih Baik Bersama</span>
            </div>
        </div>
        @endif

        @if($isTransmisi)
        <!-- KELOLA & INPUT DATA OPERASIONAL -->
        <div>
            <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-cyan-400">Input & Kelola Data</div>
            <div class="space-y-1">
                @php $isTransmisiManage = request()->routeIs('tvri.transmisi.manage'); @endphp
                <a href="{{ route('tvri.transmisi.manage') }}"
                   class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 {{ $isTransmisiManage ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 {{ $isTransmisiManage ? 'text-white' : 'text-cyan-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                        <span>Input Data Transmisi</span>
                    </div>
                    <span class="px-1.5 py-0.5 text-[9px] font-bold rounded border {{ $isTransmisiManage ? 'bg-white/20 text-white' : 'bg-cyan-500/20 text-cyan-200 border-cyan-400/30' }}">TX</span>
                </a>
            </div>
        </div>
        @endif

        @if($isPromo)
        <!-- MENU UTAMA / NAVIGASI KIRI BIDANG PROMO -->
        <div>
            <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-indigo-300">Navigasi Promo</div>
            <div class="space-y-1">
                <a href="{{ route('tvri.promo.dashboard') }}"
                   class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('tvri.promo.dashboard') ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('tvri.promo.dashboard') ? 'bg-amber-300' : 'bg-indigo-400' }}"></span>
                        <span>Dashboard Promo</span>
                    </div>
                </a>
                <a href="{{ route('dokumen.documents.index', ['folder_id' => 7, 'division' => 'Promo']) }}"
                   class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('dokumen.documents.*') && request('division') === 'Promo' ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        <span>Arsip Data</span>
                    </div>
                    <span class="px-1.5 py-0.5 text-[9px] font-bold rounded border bg-indigo-500/20 text-indigo-200 border-indigo-400/30">DMS</span>
                </a>
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center justify-between px-3.5 py-1.5 rounded-xl text-xs font-semibold text-blue-100 hover:bg-blue-900/50 hover:text-white transition-all">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-300/60"></span>
                        <span>Pengaturan</span>
                    </div>
                </a>
            </div>
        </div>
        @endif

        <!-- 3. ARSIP & DOKUMENTASI -->
        <div>
            <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-rose-400">Arsip & Dokumentasi</div>
            <div class="space-y-1">
                <a href="{{ route('dokumen.dashboard') }}"
                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('dokumen.dashboard') ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        <span>Dashboard Kearsipan</span>
                    </div>
                </a>
                <a href="{{ route('dokumen.documents.index') }}"
                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('dokumen.documents.index') || request()->routeIs('dokumen.documents.show') || request()->routeIs('dokumen.documents.edit') ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        <span>Arsip Data</span>
                    </div>
                </a>
                <a href="{{ route('dokumen.folders.index') }}"
                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('dokumen.folders.*') ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                        <span>Folder Explorer</span>
                    </div>
                    <span class="px-1.5 py-0.5 text-[9px] font-bold bg-amber-500/20 text-amber-300 rounded border border-amber-500/30">DMS</span>
                </a>
                <a href="{{ route('dokumen.documents.create') }}"
                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('dokumen.documents.create') ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        <span>Unggah Dokumen Baru</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- 4. ADMINISTRASI SISTEM (ADMIN ONLY) -->
        @if($isAdmin)
            <div>
                <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-emerald-400">Administrasi & Keamanan</div>
                <div class="space-y-1">
                    <a href="{{ route('dokumen.documents.index') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('dokumen.documents.*') ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                        <div class="flex items-center space-x-2.5">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                            <span>Arsip Data (Super Admin)</span>
                        </div>
                        <span class="px-1.5 py-0.5 text-[9px] font-bold bg-emerald-500/20 text-emerald-300 rounded border border-emerald-500/30">DMS</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                        <div class="flex items-center space-x-2.5">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span>Pengguna Sistem</span>
                        </div>
                        <span class="px-1.5 py-0.5 text-[9px] font-bold bg-emerald-500/20 text-emerald-300 rounded border border-emerald-500/30">Aktif</span>
                    </a>

                    <a href="{{ route('admin.activity-logs.index') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('admin.activity-logs.*') ? 'bg-[#005B9F] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white' }}">
                        <div class="flex items-center space-x-2.5">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Aktivitas Sistem</span>
                        </div>
                        <span class="px-1.5 py-0.5 text-[9px] font-bold bg-emerald-500/20 text-emerald-300 rounded border border-emerald-500/30">Aktif</span>
                    </a>
                </div>
            </div>
        @endif

    </div>

    <!-- User Mini Profile Footer in Sidebar -->
    <div class="p-3.5 bg-[#001D38] border-t border-blue-900/50 flex items-center justify-between">
        <div class="flex items-center space-x-2.5 min-w-0">
            <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-8 h-8 rounded-lg object-cover ring-2 ring-blue-400/40">
            <div class="min-w-0 truncate">
                <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-blue-300 uppercase tracking-wider font-bold">{{ auth()->user()->role_name }}</p>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" title="Keluar dari Sistem" class="p-2 text-rose-300 hover:text-rose-100 hover:bg-rose-900/40 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </button>
        </form>
    </div>
</aside>
