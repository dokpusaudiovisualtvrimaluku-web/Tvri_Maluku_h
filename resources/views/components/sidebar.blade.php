<ul class="menu p-4 w-80 min-h-full bg-base-100 text-base-content border-r border-base-200 shadow-sm flex flex-col">
    <!-- Brand / Logo -->
    <li class="mb-6 pointer-events-none">
        <div class="flex items-center gap-3 text-2xl font-bold px-2 py-4">
            <div class="w-10 h-10 rounded-xl bg-primary text-primary-content flex items-center justify-center shadow-lg">
                <i class="fa-solid fa-users"></i>
            </div>
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-primary to-secondary">TOMA Hub</span>
        </div>
    </li>

    <!-- Menu Section: Utama -->
    <li class="menu-title text-xs uppercase tracking-wider text-base-content/50 font-semibold mb-2 mt-4">Utama</li>
    <li>
        <a href="#" class="active:!bg-primary active:!text-primary-content {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary font-medium' : '' }}">
            <i class="fa-solid fa-chart-pie w-6 text-center"></i>
            Executive Dashboard
        </a>
    </li>
    <li>
        <a href="#">
            <i class="fa-solid fa-map-location-dot w-6 text-center"></i>
            Peta Digital (GIS)
        </a>
    </li>
    
    <!-- Menu Section: Manajemen Data -->
    <li class="menu-title text-xs uppercase tracking-wider text-base-content/50 font-semibold mb-2 mt-6">Manajemen Data</li>
    <li>
        <a href="{{ route('tokohs.index') }}">
            <i class="fa-solid fa-users-viewfinder w-6 text-center"></i>
            Data Tokoh Masyarakat
            <span class="badge badge-sm badge-primary">Baru</span>
        </a>
    </li>
    <li>
        <a href="#">
            <i class="fa-solid fa-clipboard-check w-6 text-center"></i>
            Verifikasi Data
            <span class="badge badge-sm badge-error">12</span>
        </a>
    </li>

    <!-- Menu Section: Master Data -->
    <li class="menu-title text-xs uppercase tracking-wider text-base-content/50 font-semibold mb-2 mt-6">Master Data</li>
    <li>
        <details>
            <summary>
                <i class="fa-solid fa-database w-6 text-center"></i> Data Demografi
            </summary>
            <ul>
                <li><a href="{{ route('master.kategori-tokohs.index') }}"><i class="fa-solid fa-minus text-xs opacity-50"></i>Kategori Tokoh</a></li>
                <li><a href="{{ route('master.profesis.index') }}"><i class="fa-solid fa-minus text-xs opacity-50"></i>Profesi</a></li>
                <li><a href="{{ route('master.pendidikans.index') }}"><i class="fa-solid fa-minus text-xs opacity-50"></i>Pendidikan</a></li>
                <li><a href="{{ route('master.agamas.index') }}"><i class="fa-solid fa-minus text-xs opacity-50"></i>Agama</a></li>
                <li><a href="{{ route('master.sukus.index') }}"><i class="fa-solid fa-minus text-xs opacity-50"></i>Suku</a></li>
                <li><a href="{{ route('master.organisasis.index') }}"><i class="fa-solid fa-minus text-xs opacity-50"></i>Organisasi</a></li>
            </ul>
        </details>
    </li>
    <li>
        <details>
            <summary>
                <i class="fa-solid fa-map w-6 text-center"></i> Wilayah Administrasi
            </summary>
            <ul>
                <li><a href="{{ route('master.provinsis.index') }}"><i class="fa-solid fa-minus text-xs opacity-50"></i>Provinsi</a></li>
                <li><a href="{{ route('master.kabupatens.index') }}"><i class="fa-solid fa-minus text-xs opacity-50"></i>Kabupaten/Kota</a></li>
                <li><a href="{{ route('master.kecamatans.index') }}"><i class="fa-solid fa-minus text-xs opacity-50"></i>Kecamatan</a></li>
                <li><a href="{{ route('master.desas.index') }}"><i class="fa-solid fa-minus text-xs opacity-50"></i>Desa/Kelurahan</a></li>
            </ul>
        </details>
    </li>

    <!-- Menu Section: Laporan -->
    <li class="menu-title text-xs uppercase tracking-wider text-base-content/50 font-semibold mb-2 mt-6">Laporan & Audit</li>
    <li>
        <a href="#">
            <i class="fa-solid fa-file-pdf w-6 text-center"></i>
            Report Builder
        </a>
    </li>
    <li>
        <a href="#">
            <i class="fa-solid fa-clock-rotate-left w-6 text-center"></i>
            Audit Log Trail
        </a>
    </li>

    <!-- Menu Section: Pengaturan (Bottom) -->
    <div class="mt-auto pt-6 pb-2">
        <li class="menu-title text-xs uppercase tracking-wider text-base-content/50 font-semibold mb-2">Sistem</li>
        <li>
            <a href="#">
                <i class="fa-solid fa-users-gear w-6 text-center"></i>
                Manajemen Pengguna
            </a>
        </li>
        <li>
            <a href="#">
                <i class="fa-solid fa-server w-6 text-center"></i>
                Backup Database
            </a>
        </li>
        <li>
            <a href="#">
                <i class="fa-solid fa-sliders w-6 text-center"></i>
                Pengaturan Umum
            </a>
        </li>
    </div>
</ul>
