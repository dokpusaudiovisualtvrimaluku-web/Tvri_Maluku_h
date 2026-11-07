<div class="navbar bg-base-100 shadow-sm z-30 sticky top-0">
    <div class="flex-none lg:hidden">
        <label for="main-drawer" aria-label="open sidebar" class="btn btn-square btn-ghost">
            <i class="fa-solid fa-bars text-xl"></i>
        </label>
    </div>
    
    <div class="flex-1">
        <a class="btn btn-ghost text-xl lg:hidden">TOMA Hub</a>
    </div>
    
    <div class="flex-none gap-4">
        <!-- Theme Controller -->
        <label class="swap swap-rotate btn btn-ghost btn-circle">
            <input type="checkbox" id="theme-toggle" class="theme-controller" />
            
            <!-- sun icon -->
            <i class="fa-solid fa-sun swap-off text-xl"></i>
            
            <!-- moon icon -->
            <i class="fa-solid fa-moon swap-on text-xl"></i>
        </label>

        <!-- Notifications -->
        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                <div class="indicator">
                    <i class="fa-regular fa-bell text-xl"></i>
                    <span class="badge badge-sm indicator-item badge-primary">3</span>
                </div>
            </div>
            <div tabindex="0" class="mt-3 z-[1] card card-compact dropdown-content w-72 bg-base-100 shadow-xl border border-base-200">
                <div class="card-body">
                    <span class="font-bold text-lg">3 Notifikasi Baru</span>
                    <ul class="menu p-0">
                        <li><a><i class="fa-solid fa-user-check text-success"></i> Verifikasi Data Tokoh</a></li>
                        <li><a><i class="fa-solid fa-file-import text-info"></i> Laporan Masuk</a></li>
                    </ul>
                    <div class="card-actions">
                        <button class="btn btn-primary btn-block btn-sm">Lihat Semua</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Dropdown -->
        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                <div class="w-10 rounded-full border border-base-300">
                    <img alt="User Avatar" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=random" />
                </div>
            </div>
            <ul tabindex="0" class="mt-3 z-[1] p-2 shadow-xl menu menu-sm dropdown-content bg-base-100 rounded-box w-52 border border-base-200">
                <li class="menu-title px-4 py-2">
                    <span class="font-semibold text-base-content">{{ auth()->user()->name ?? 'Administrator' }}</span>
                    <span class="text-xs text-base-content/60">{{ auth()->user()->roles->first()->name ?? 'Role' }}</span>
                </li>
                <div class="divider my-0"></div>
                <li>
                    <a href="#" class="py-3">
                        <i class="fa-regular fa-user w-5"></i> Profil Saya
                    </a>
                </li>
                <li>
                    <a href="#" class="py-3">
                        <i class="fa-solid fa-gear w-5"></i> Pengaturan
                    </a>
                </li>
                <div class="divider my-0"></div>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="py-3 text-error w-full text-left flex items-center">
                            <i class="fa-solid fa-right-from-bracket w-5"></i> Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
