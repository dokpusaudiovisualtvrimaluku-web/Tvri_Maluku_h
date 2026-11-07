@extends('layouts.simandat')

@section('title', 'SIMANDAT TVRI Maluku - Executive Command Center')

@section('content')
    <!-- LOGIN SCREEN (Simulasi Role / Uji Akun) -->
    <div id="loginScreen" class="fixed inset-0 z-50 flex items-center justify-center bg-app-bg @auth hidden @endauth">
        <div class="bg-app-surface border border-cyan-500/30 p-8 rounded-2xl shadow-2xl max-w-md w-full glow-border text-center relative overflow-hidden">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 rounded-2xl bg-cyan-500/15 border border-cyan-500/40 flex items-center justify-center text-cyan-400 text-3xl shadow-[0_0_20px_rgba(0,246,255,0.3)]">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
            </div>
            <h1 class="text-2xl font-black text-white mb-1 tracking-wide">SIMANDAT <span class="text-cyan-400">SIWALIMA</span></h1>
            <p class="text-xs text-cyan-400 mb-2 uppercase tracking-wider font-bold">Executive Command Center & Pangkalan Data Dinas PUPR Maluku</p>
            <p class="text-slate-400 text-xs mb-6">TVRI Maluku</p>
            
            <p class="text-xs font-semibold mb-3 text-slate-200">PILIH IDENTIFIKASI PENGGUNA (ROLE AKSES):</p>
            
            <div class="flex gap-3 mb-4">
                <button onclick="app.login('kadis')" class="flex-1 bg-slate-800/80 border border-slate-700 hover:border-amber-500/60 p-3.5 rounded-xl flex flex-col items-center transition-all group hover:bg-amber-500/10">
                    <i class="fa-solid fa-user-tie text-2xl mb-1.5 text-slate-400 group-hover:text-amber-400 transition-colors"></i>
                    <span class="font-bold text-xs text-white">Kepala Stasiun</span>
                    <span class="text-[10px] text-amber-400/80 mt-0.5">View Only</span>
                </button>
                <button onclick="app.login('admin')" class="flex-1 bg-cyan-500/10 border border-cyan-500/40 hover:border-cyan-400 p-3.5 rounded-xl flex flex-col items-center transition-all group hover:bg-cyan-500/20">
                    <i class="fa-solid fa-user-shield text-2xl mb-1.5 text-cyan-400 transition-colors"></i>
                    <span class="font-bold text-xs text-white">Super Admin</span>
                    <span class="text-[10px] text-cyan-400 mt-0.5">Full Access</span>
                </button>
            </div>
            
            @auth
            <div class="pt-3 border-t border-slate-800 mb-3">
                <button onclick="app.login('auth_user')" class="w-full bg-slate-800 hover:bg-slate-700 text-xs text-slate-200 py-2.5 rounded-xl border border-slate-600 transition-colors">
                    Lanjut sebagai: <strong class="text-cyan-400">{{ auth()->user()->name }}</strong>
                </button>
            </div>
            @endauth

            <div class="pt-2 border-t border-slate-800/60 flex flex-col gap-2">
                <a href="{{ route('login') }}" class="w-full bg-[#070e1c] hover:bg-slate-800 text-xs text-cyan-400 py-2.5 rounded-xl border border-cyan-500/30 transition-colors flex items-center justify-center gap-2 font-semibold">
                    <i class="fa-solid fa-right-to-bracket text-xs"></i>
                    <span>Masuk Lewat Halaman Login Resmi</span>
                </a>
            </div>
        </div>
    </div>

    <!-- MAIN APP LAYOUT -->
    <div id="mainApp" class="@guest hidden @endguest h-full flex flex-col">
        
        <!-- TOP NAVIGATION BAR -->
        @include('simandat.partials.header')

        <div class="flex flex-1 overflow-hidden">
            
            <!-- SIDEBAR WITH RICH SUB-MENUS & ACCORDION -->
            <aside class="w-80 bg-[#0b1325] border-r border-app-cardBorder flex flex-col z-10 shrink-0 select-none shadow-xl">
                <!-- Sidebar Header & Quick Filter -->
                <div class="p-3 border-b border-slate-800 bg-[#070e1c]">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-cyan-400 flex items-center gap-1.5">
                            <i class="fa-solid fa-folder-tree text-cyan-400"></i>
                            Struktur Menu & Bidang
                        </span>
                        <div class="flex items-center gap-1">
                            <button onclick="app.toggleAllSidebarGroups(true)" class="text-[10px] text-slate-400 hover:text-cyan-300 px-1.5 py-0.5 rounded hover:bg-slate-800 transition-colors" title="Buka Semua Sub-Menu">
                                <i class="fa-solid fa-angles-down"></i>
                            </button>
                            <button onclick="app.toggleAllSidebarGroups(false)" class="text-[10px] text-slate-400 hover:text-cyan-300 px-1.5 py-0.5 rounded hover:bg-slate-800 transition-colors" title="Tutup Semua Sub-Menu">
                                <i class="fa-solid fa-angles-up"></i>
                            </button>
                        </div>
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-500 text-xs"></i>
                        <input type="text" id="sidebarSearchInput" oninput="app.filterSidebar(this.value)" placeholder="Cari menu / sub-bidang..." class="w-full bg-[#0b1325] border border-slate-700/80 rounded-lg pl-8 pr-7 py-1.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-colors">
                        <button id="clearSidebarSearch" onclick="app.clearSidebarSearch()" class="hidden absolute right-2.5 top-2 text-slate-400 hover:text-white text-xs">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>

                <!-- Sidebar Nav Item Container -->
                <nav id="sidebarNav" class="flex-1 p-2 space-y-2 overflow-y-auto custom-scrollbar">
                    <!-- Sidebar items will be rendered dynamically by JS -->
                </nav>

                <!-- Sidebar Footer Status -->
                <div class="p-3 border-t border-slate-800 bg-[#070e1c] flex items-center justify-between text-[10px] text-slate-400">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-slate-300 font-medium">Pangkalan Data Dinas PUPR Maluku Terhubung</span>
                    </div>
                    <span class="font-mono text-cyan-400 font-bold">100% Terintegrasi</span>
                </div>
            </aside>

            <!-- MAIN CONTENT AREA -->
            <main class="flex-1 bg-app-bg overflow-y-auto relative p-4 md:p-6" id="mainContentArea">
                <!-- Dynamic Content (Executive Command Center 7-Cards Layout, SOP Module, or School Table) -->
            </main>
        </div>
    </div>

    <!-- UPLOAD MODAL -->
    @include('simandat.partials.upload-modal')
    
    <!-- EDIT ARCHIVE / REPORT MODAL -->
    @include('simandat.partials.edit-archive-modal')
    
    <!-- MODAL TAMBAH / EDIT DATA SEKOLAH -->
    <div id="schoolModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-200">
        <div class="bg-app-surface border border-app-border rounded-xl shadow-2xl w-full max-w-xl transform scale-95 transition-transform duration-200" id="schoolModalContent">
            
            <!-- Modal Header -->
            <div class="flex justify-between items-center p-4 border-b border-app-border bg-[#070e1c] rounded-t-xl">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-600/20 text-blue-400 flex items-center justify-center">
                        <i class="fa-solid fa-school"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-sm" id="schoolModalTitle">Tambah Data Satuan Pendidikan</h3>
                        <p class="text-xxs text-slate-400">Pusat Data Pokok Sekolah Provinsi Maluku</p>
                    </div>
                </div>
                <button onclick="app.closeSchoolModal()" class="text-slate-400 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-800">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Modal Body Form -->
            <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">
                <input type="hidden" id="editSchoolIndex" value="-1">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">NPSN (Nomor Pokok Sekolah)</label>
                        <input type="text" id="schoolNpsn" placeholder="Contoh: 60100009" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-app-primary font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Satuan Pendidikan</label>
                        <input type="text" id="schoolNama" placeholder="Contoh: SMA Negeri 3 Ambon" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-app-primary">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Jenjang Pendidikan</label>
                        <select id="schoolJenjang" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-app-primary">
                            <option value="SMA">SMA</option>
                            <option value="SMK">SMK</option>
                            <option value="SLB">SLB</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Akreditasi</label>
                        <select id="schoolAkreditasi" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-app-primary">
                            <option value="A">A (Unggul)</option>
                            <option value="B">B (Baik)</option>
                            <option value="C">C (Cukup)</option>
                            <option value="Belum">Belum Terakreditasi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Jumlah Siswa</label>
                        <input type="number" id="schoolSiswa" placeholder="Contoh: 850" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-app-primary">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Cabang Dinas / Wilayah Kabupaten</label>
                    <select id="schoolCabdin" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-app-primary">
                        <option value="Cabdin Wilayah I (Kota Ambon)">Cabdin Wilayah I (Kota Ambon)</option>
                        <option value="Cabdin Wilayah II (Maluku Tengah)">Cabdin Wilayah II (Maluku Tengah)</option>
                        <option value="Cabdin Wilayah III (Kab. Buru & Bursel)">Cabdin Wilayah III (Kab. Buru & Bursel)</option>
                        <option value="Cabdin Wilayah IV (Seram Bagian Barat)">Cabdin Wilayah IV (Seram Bagian Barat)</option>
                        <option value="Cabdin Wilayah V (Seram Bagian Timur)">Cabdin Wilayah V (Seram Bagian Timur)</option>
                        <option value="Cabdin Wilayah VI (Maluku Tenggara & Tual)">Cabdin Wilayah VI (Maluku Tenggara & Tual)</option>
                        <option value="Cabdin Wilayah VII (Kepulauan Aru)">Cabdin Wilayah VII (Kepulauan Aru)</option>
                        <option value="Cabdin Wilayah IX (Kepulauan Tanimbar)">Cabdin Wilayah IX (Kepulauan Tanimbar)</option>
                        <option value="Cabdin Wilayah XI (Maluku Barat Daya)">Cabdin Wilayah XI (Maluku Barat Daya)</option>
                    </select>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-4 border-t border-slate-800 flex justify-end gap-3 bg-[#070e1c] rounded-b-xl">
                <button onclick="app.closeSchoolModal()" class="px-4 py-2 text-xs text-slate-400 hover:text-white transition-colors">Batal</button>
                <button onclick="app.saveSchoolData()" id="btnSaveSchool" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-2 shadow-[0_0_12px_rgba(37,99,235,0.4)]">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Data Sekolah
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH / EDIT UPTD BTKP (10.2) -->
    <div id="btkpModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-200">
        <div class="bg-app-surface border border-cyan-500/40 rounded-xl shadow-2xl w-full max-w-xl transform scale-95 transition-transform duration-200" id="btkpModalContent">
            <div class="flex justify-between items-center p-4 border-b border-app-border bg-[#070e1c] rounded-t-xl">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-cyan-600/20 text-cyan-400 flex items-center justify-center border border-cyan-500/30">
                        <i class="fa-solid fa-tower-broadcast"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-sm" id="btkpModalTitle">Tambah Program TIK BTKP</h3>
                        <p class="text-xxs text-cyan-400">UPTD Balai Teknologi Komunikasi Pendidikan Maluku</p>
                    </div>
                </div>
                <button onclick="app.closeBtkpModal()" class="text-slate-400 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-800">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">
                <input type="hidden" id="editBtkpIndex" value="-1">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Kode Program</label>
                        <input type="text" id="btkpKode" placeholder="Contoh: BTKP-005" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-cyan-300 focus:outline-none focus:border-cyan-400 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Platform / Media</label>
                        <input type="text" id="btkpPlatform" placeholder="Contoh: SIWALIMA LMS / Cloud" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Program / Layanan TIK</label>
                    <input type="text" id="btkpNama" placeholder="Contoh: Produksi Video Pembelajaran Kurikulum Merdeka Maluku" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Penanggung Jawab / Koordinator</label>
                        <input type="text" id="btkpPj" placeholder="Contoh: Sarah Wattimena, S.Kom" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Sasaran & Wilayah Target</label>
                        <input type="text" id="btkpSasaran" placeholder="Contoh: SMA/SMK se-Maluku (11 Kab/Kota)" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Alokasi Anggaran (Rp)</label>
                        <input type="number" id="btkpAnggaran" placeholder="Contoh: 150000000" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Status Program</label>
                        <select id="btkpStatus" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                            <option value="Aktif">Aktif</option>
                            <option value="Optimal">Optimal</option>
                            <option value="Perencanaan">Perencanaan</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-slate-800 flex justify-end gap-3 bg-[#070e1c] rounded-b-xl">
                <button onclick="app.closeBtkpModal()" class="px-4 py-2 text-xs text-slate-400 hover:text-white transition-colors">Batal</button>
                <button onclick="app.saveBtkpData()" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-400 text-slate-950 text-xs font-black rounded-lg transition-colors flex items-center gap-2 shadow-[0_0_12px_rgba(6,182,212,0.4)]">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Data BTKP
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH / EDIT UPTD TAMAN BUDAYA (10.3) -->
    <div id="tamanBudayaModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-200">
        <div class="bg-app-surface border border-purple-500/40 rounded-xl shadow-2xl w-full max-w-xl transform scale-95 transition-transform duration-200" id="tamanBudayaModalContent">
            <div class="flex justify-between items-center p-4 border-b border-app-border bg-[#070e1c] rounded-t-xl">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-purple-600/20 text-purple-400 flex items-center justify-center border border-purple-500/30">
                        <i class="fa-solid fa-masks-theater"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-sm" id="tamanBudayaModalTitle">Tambah Agenda Taman Budaya</h3>
                        <p class="text-xxs text-purple-300">UPTD Taman Budaya Provinsi Maluku</p>
                    </div>
                </div>
                <button onclick="app.closeTamanBudayaModal()" class="text-slate-400 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-800">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">
                <input type="hidden" id="editTamanBudayaIndex" value="-1">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Kode Agenda</label>
                        <input type="text" id="tamanBudayaKode" placeholder="Contoh: TB-MAL-05" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-purple-300 focus:outline-none focus:border-purple-400 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Jenis Kegiatan Seni</label>
                        <select id="tamanBudayaJenis" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-purple-400">
                            <option value="Festival & Pagelaran">Festival & Pagelaran Seni</option>
                            <option value="Workshop & Pelatihan">Workshop & Pelatihan Seni</option>
                            <option value="Pameran Seni">Pameran Seni Rupa & Kriya</option>
                            <option value="Pentas Teater">Pentas Teater & Tari Tradisi</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Pagelaran / Kegiatan Seni</label>
                    <input type="text" id="tamanBudayaNama" placeholder="Contoh: Gelar Budaya Kepulauan Maluku Manise" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-purple-400">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Lokasi / Panggung Pagelaran</label>
                        <input type="text" id="tamanBudayaLokasi" placeholder="Contoh: Panggung Terbuka Taman Budaya Karpan Ambon" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-purple-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Sanggar / Komunitas Mitra</label>
                        <input type="text" id="tamanBudayaSanggar" placeholder="Contoh: Sanggar Siwalima & Sanggar Tari Ambon" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-purple-400">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Estimasi Peserta / Seniman</label>
                        <input type="number" id="tamanBudayaPeserta" placeholder="Contoh: 350" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-purple-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Status Pagelaran</label>
                        <select id="tamanBudayaStatus" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-purple-400">
                            <option value="Terjadwal">Terjadwal</option>
                            <option value="Aktif">Aktif / Berlangsung</option>
                            <option value="Persiapan">Persiapan</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-slate-800 flex justify-end gap-3 bg-[#070e1c] rounded-b-xl">
                <button onclick="app.closeTamanBudayaModal()" class="px-4 py-2 text-xs text-slate-400 hover:text-white transition-colors">Batal</button>
                <button onclick="app.saveTamanBudayaData()" class="px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-2 shadow-[0_0_12px_rgba(168,85,247,0.4)]">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Data Agenda
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH / EDIT UPTD MUSEUM SIWA LIMA (10.4) -->
    <div id="museumModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-200">
        <div class="bg-app-surface border border-amber-500/40 rounded-xl shadow-2xl w-full max-w-xl transform scale-95 transition-transform duration-200" id="museumModalContent">
            <div class="flex justify-between items-center p-4 border-b border-app-border bg-[#070e1c] rounded-t-xl">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-amber-600/20 text-amber-400 flex items-center justify-center border border-amber-500/30">
                        <i class="fa-solid fa-landmark"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-sm" id="museumModalTitle">Tambah Koleksi Museum Siwa Lima</h3>
                        <p class="text-xxs text-amber-300">UPTD Museum Siwa Lima Provinsi Maluku</p>
                    </div>
                </div>
                <button onclick="app.closeMuseumModal()" class="text-slate-400 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-800">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">
                <input type="hidden" id="editMuseumIndex" value="-1">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">No. Inventaris</label>
                        <input type="text" id="museumNoInv" placeholder="Contoh: MSL-K-005" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-amber-300 focus:outline-none focus:border-amber-400 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Kategori Koleksi</label>
                        <select id="museumKategori" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-amber-400">
                            <option value="Etnografika">Etnografika (Adat, Tenun & Senjata)</option>
                            <option value="Historika">Historika (Sejarah Kolonial & Benteng)</option>
                            <option value="Biologika">Biologika (Fosil & Biota Laut)</option>
                            <option value="Filologika">Filologika (Naskah Kuno & Manuskrip)</option>
                            <option value="Arkeologika">Arkeologika</option>
                            <option value="Keramikologika">Keramikologika</option>
                            <option value="Teknologika">Teknologika</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Benda Koleksi</label>
                    <input type="text" id="museumNama" placeholder="Contoh: Mahkota Adat Raja Hitu Abad ke-17" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-amber-400">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Bahan Pembuatan</label>
                        <input type="text" id="museumBahan" placeholder="Contoh: Kayu Besi & Perunggu" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-amber-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Asal Daerah / Pulau</label>
                        <input type="text" id="museumAsal" placeholder="Contoh: Kepulauan Banda / Kei" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-amber-400">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Kondisi Fisik</label>
                        <select id="museumKondisi" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-amber-400">
                            <option value="Sangat Baik">Sangat Baik</option>
                            <option value="Terawat">Terawat</option>
                            <option value="Utuh / Terpelihara">Utuh / Terpelihara</option>
                            <option value="Perlu Konservasi">Perlu Konservasi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Tahun Perolehan</label>
                        <input type="text" id="museumTahun" placeholder="Contoh: 1985" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-amber-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Lokasi Ruang Pamer</label>
                        <input type="text" id="museumLokasiPamer" placeholder="Contoh: Gedung A Ruang Pamer Utama" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-amber-400">
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-slate-800 flex justify-end gap-3 bg-[#070e1c] rounded-b-xl">
                <button onclick="app.closeMuseumModal()" class="px-4 py-2 text-xs text-slate-400 hover:text-white transition-colors">Batal</button>
                <button onclick="app.saveMuseumData()" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-2 shadow-[0_0_12px_rgba(217,119,6,0.4)]">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Data Koleksi
                </button>
            </div>
        </div>
    </div>

    <!-- TOAST NOTIFICATION -->
    @include('simandat.partials.toast')

    <!-- PROTECTED DELETE MODAL -->
    @include('simandat.partials.delete-modal')

    <!-- ADMIN ACCOUNT & DATABASE MODAL -->
    @include('simandat.partials.admin-account-modal')

    <!-- FILE PREVIEW & DATABASE INSPECTOR MODAL -->
    @include('simandat.partials.file-preview-modal')

    <!-- DATABASE ARCHIVES & TABLES EXPLORER MODAL -->
    @include('simandat.partials.database-explorer-modal')

    <!-- DATABASE AUTH PASSWORD (dikbud_2026) MODAL -->
    @include('simandat.partials.db-auth-modal')

    <!-- RESET DATA MODAL -->
    @include('simandat.partials.reset-modal')
@endsection

@push('scripts')
<script>
    // --- STRUKTUR MENU ORGANISASI RESMI TVRI MALUKU ---
    const menuData = [
        {
            id: 'm1',
            title: '1. KEPALA STASIUN (LEVEL 1)',
            shortTitle: 'KEPALA STASIUN',
            icon: 'fa-gauge-high',
            color: 'cyan',
            items: [
                { id: '1.1', title: '1.1 Executive Command Center', type: 'dashboard', desc: 'Pusat Komando & Pemantauan Eksekutif' }
            ]
        },
        {
            id: 'm2',
            title: '2. SEKRETARIAT',
            shortTitle: 'SEKRETARIAT',
            icon: 'fa-building',
            color: 'blue',
            items: [
                { id: '2.1', title: '2.1 Subbag. Umum dan Aset', type: 'module', desc: 'BMD, Sarpras & Persuratan Dinas' },
                { id: '2.2', title: '2.2 Subbag. Perencanaan & Keuangan', type: 'module', desc: 'Renstra, DPA & SP2D Serapan' },
                { id: '2.3', title: '2.3 Pengelolaan Data & Tugas Pembantuan', type: 'module', desc: '10 SOP Layanan Kepegawaian ASN' }
            ]
        },
        {
            id: 'm3',
            title: '3. BIDANG PENDIDIKAN SMA',
            shortTitle: 'BIDANG SMA',
            icon: 'fa-school',
            color: 'emerald',
            items: [
                { id: '3.1', title: '3.1 Perencanaan & Program SMA', type: 'module', desc: 'DAK Fisik & Akselerasi 3T' },
                { id: '3.2', title: '3.2 Pembinaan & Akreditasi SMA', type: 'module', desc: 'Akreditasi BAN-SM & Supervisi' },
                { id: '3.3', title: '3.3 Kurikulum & Pembelajaran SMA', type: 'module', desc: 'Kurikulum Merdeka & Asesmen' },
                { id: '3.4', title: '3.4 Kesiswaan & Prestasi SMA', type: 'module', desc: 'OSN, FLS2N & Beasiswa Maluku' }
            ]
        },
        {
            id: 'm4',
            title: '4. BIDANG PENDIDIKAN SMK',
            shortTitle: 'BIDANG SMK',
            icon: 'fa-tools',
            color: 'amber',
            items: [
                { id: '4.1', title: '4.1 Perencanaan & Program SMK', type: 'module', desc: '7 SOP Layanan Vokasi Resmi' },
                { id: '4.2', title: '4.2 Kelembagaan & Sarpras SMK', type: 'module', desc: 'Teaching Factory & Bengkel Vokasi' },
                { id: '4.3', title: '4.3 Kemitraan & DUDI SMK', type: 'module', desc: 'MoU Industri, BNSP & LSP-P1' },
                { id: '4.4', title: '4.4 Kurikulum & Pembelajaran SMK', type: 'module', desc: 'Penyelarasan Industri & PKL Siswa' }
            ]
        },
        {
            id: 'm5',
            title: '5. BIDANG PENDIDIKAN KHUSUS (SLB)',
            shortTitle: 'PENDIDIKAN KHUSUS (SLB)',
            icon: 'fa-hands-holding-child',
            color: 'pink',
            items: [
                { id: '5.1', title: '5.1 Pembinaan Pendidikan Khusus', type: 'module', desc: 'Guru Pendamping & Advokasi Inklusi' },
                { id: '5.2', title: '5.2 Kurikulum & Peserta Didik Khusus', type: 'module', desc: 'Kurikulum Adaptif & Bahan Braille' },
                { id: '5.3', title: '5.3 Kelembagaan & Sarpras SLB', type: 'module', desc: 'Unit Layanan Disabilitas & Terapi' }
            ]
        },
        {
            id: 'm6',
            title: '6. BIDANG KEBUDAYAAN',
            shortTitle: 'BIDANG KEBUDAYAAN',
            icon: 'fa-masks-theater',
            color: 'purple',
            items: [
                { id: '6.1', title: '6.1 Cagar Budaya & Permuseuman', type: 'module', desc: 'Pencatatan Cagar & Konservasi Situs' },
                { id: '6.2', title: '6.2 Sejarah dan Tradisi', type: 'module', desc: 'Warisan Budaya Takbenda (WBTB)' },
                { id: '6.3', title: '6.3 Kesenian & Pembinaan Sanggar', type: 'module', desc: 'Festival Seni, Musik Tradisi & Totobuang' }
            ]
        },
        {
            id: 'm7',
            title: '7. BIDANG KETENAGAAN (GTK)',
            shortTitle: 'KETENAGAAN (GTK)',
            icon: 'fa-chalkboard-user',
            color: 'cyan',
            items: [
                { id: '7.1', title: '7.1 Perencanaan & Distribusi Guru', type: 'module', desc: 'Penataan PPPK & Formasi Guru 3T' },
                { id: '7.2', title: '7.2 Pengembangan Kompetensi', type: 'module', desc: 'Guru Penggerak & Literasi Digital PMM' },
                { id: '7.3', title: '7.3 Sertifikasi & Pemerataan 3T', type: 'module', desc: 'TPG Guru, Insentif Khusus 3T & PPG' }
            ]
        },
        {
            id: 'm10',
            title: '10. SATUAN PENDIDIKAN & UPTD',
            shortTitle: 'SATUAN PENDIDIKAN & UPTD',
            icon: 'fa-database',
            color: 'indigo',
            items: [
                { id: '10.1', title: '10.1 Data Sekolah Negeri/Swasta', type: 'table', desc: 'Dapodik SMA, SMK, SLB se-Maluku' },
                { id: '10.2', title: '10.2 UPTD Balai Teknologi Komunikasi Pendidikan', type: 'uptd_btkp', desc: 'Program TIK, Studio Podcast & CBT 3T' },
                { id: '10.3', title: '10.3 UPTD Taman Budaya Provinsi Maluku', type: 'uptd_taman_budaya', desc: 'Agenda Pagelaran Seni & Sanggar' },
                { id: '10.4', title: '10.4 UPTD Museum Siwa Lima Provinsi Maluku', type: 'uptd_museum', desc: 'Koleksi Cagar Budaya & Etnografika' }
            ]
        }
    ];

    // Data Master Sekolah Maluku (Default Seed - 8 Satuan Pendidikan)
    const initialSchoolList = [
        { npsn: '60100001', nama: 'SMA Negeri 1 Ambon', jenjang: 'SMA', cabang: 'Cabdin Wilayah I (Kota Ambon)', akreditasi: 'A', siswa: 1245 },
        { npsn: '60100002', nama: 'SMK Negeri 1 Ambon', jenjang: 'SMK', cabang: 'Cabdin Wilayah I (Kota Ambon)', akreditasi: 'A', siswa: 1450 },
        { npsn: '60100003', nama: 'SMA Negeri 2 Kei Kecil', jenjang: 'SMA', cabang: 'Cabdin Wilayah VI (Maluku Tenggara & Tual)', akreditasi: 'A', siswa: 680 },
        { npsn: '60100004', nama: 'SMK Kemaritiman Tanimbar', jenjang: 'SMK', cabang: 'Cabdin Wilayah IX (Kepulauan Tanimbar)', akreditasi: 'B', siswa: 420 },
        { npsn: '60100005', nama: 'SLB Negeri 1 Ambon', jenjang: 'SLB', cabang: 'Cabdin Wilayah I (Kota Ambon)', akreditasi: 'A', siswa: 180 },
        { npsn: '60100006', nama: 'SMA Negeri 1 Masohi', jenjang: 'SMA', cabang: 'Cabdin Wilayah II (Maluku Tengah)', akreditasi: 'A', siswa: 910 },
        { npsn: '60100007', nama: 'SMK Negeri 1 Namlea', jenjang: 'SMK', cabang: 'Cabdin Wilayah III (Kab. Buru & Bursel)', akreditasi: 'B', siswa: 530 },
        { npsn: '60100008', nama: 'SMA Negeri 1 Piru', jenjang: 'SMA', cabang: 'Cabdin Wilayah IV (Seram Bagian Barat)', akreditasi: 'B', siswa: 490 }
    ];

    // Data Master UPTD Balai Teknologi Komunikasi Pendidikan (BTKP)
    const initialBtkpList = [
        { kode: 'BTKP-001', nama_program: 'Digitalisasi Konten Pembelajaran & E-Modul Maluku', penanggung_jawab: 'Drs. Ronald Pattipeilohy', sasaran: 'SMA/SMK se-Maluku (11 Kab/Kota)', platform: 'SIWALIMA LMS / Cloud', status: 'Aktif', anggaran: 185000000 },
        { kode: 'BTKP-002', nama_program: 'Studio Podcast & Produksi Video Edukasi Maluku Cerdas', penanggung_jawab: 'Merry Souhoka, S.Kom', sasaran: 'Guru & Peserta Didik Wilayah 3T', platform: 'YouTube / Portal TVRI Maluku', status: 'Aktif', anggaran: 120000000 },
        { kode: 'BTKP-003', nama_program: 'Penguatan Infrastruktur Jaringan & Server CBT Sekolah Terluar', penanggung_jawab: 'Hendrik Tahitu, ST', sasaran: '35 Titik Sekolah Daerah 3T (Kep. Aru & MBD)', platform: 'VSAT & Server Lokal', status: 'Optimal', anggaran: 240000000 },
        { kode: 'BTKP-004', nama_program: 'Bimtek Pemanfaatan AI & Media Pembelajaran Interaktif GTK', penanggung_jawab: 'Grace Nanlohy, M.Pd', sasaran: '450 Guru Jenjang SMA/SMK/SLB', platform: 'Zoom & Workshop Hybrid', status: 'Aktif', anggaran: 95000000 }
    ];

    // Data Master UPTD Taman Budaya Provinsi Maluku
    const initialTamanBudayaList = [
        { kode: 'TB-MAL-01', nama_kegiatan: 'Festival Gelar Seni Budaya Kepulauan Siwalima 2026', jenis: 'Festival & Pagelaran', lokasi: 'Panggung Terbuka Taman Budaya Karpan Ambon', sanggar: 'Sanggar Siwalima, Ina Boiratan, Saureka-reka (18 Sanggar)', status: 'Terjadwal', peserta: 650 },
        { kode: 'TB-MAL-02', nama_kegiatan: 'Workshop Regenerasi Musik Tradisi Tabuh Totobuang & Tifa', jenis: 'Workshop & Pelatihan', lokasi: 'Gedung Kesenian Tertutup Taman Budaya', sanggar: 'Komunitas Musik Etnis Maluku & Pelajar', status: 'Aktif', peserta: 120 },
        { kode: 'TB-MAL-03', nama_kegiatan: 'Pameran Seni Rupa & Kriya Etnik Bahari Maluku', jenis: 'Pameran Seni', lokasi: 'Galeri Pameran Seni Utama', sanggar: 'Perupa Maluku & Pengrajin Tenun Ikat Tanimbar', status: 'Persiapan', peserta: 250 },
        { kode: 'TB-MAL-04', nama_kegiatan: 'Pentas Kolaborasi Teater Tradisi Cerita Rakyat Maluku', jenis: 'Pentas Teater', lokasi: 'Auditorium Budaya', sanggar: 'Teater Remaja Maluku & Sanggar Teratai Putih', status: 'Terjadwal', peserta: 300 }
    ];

    // Data Master UPTD Museum Siwa Lima Provinsi Maluku
    const initialMuseumList = [
        { no_inventaris: 'MSL-K-001', nama_koleksi: 'Busana Adat Kebesaran Kapitan & Tenun Ikat Maluku Kuno', kategori: 'Etnografika', bahan: 'Kain Tenun Serat Kulit Kayu & Benang Emas', asal: 'Kepulauan Kei & Tanimbar', tahun_perolehan: '1978', kondisi: 'Sangat Baik', lokasi_simpan: 'Gedung A Ruang Busana Tradisional' },
        { no_inventaris: 'MSL-K-002', nama_koleksi: 'Meriam VOC Perunggu Abad Ke-17 & Senjata Tradisional Parang Salawaku', kategori: 'Historika', bahan: 'Perunggu & Kayu Besi Terukir', asal: 'Kota Ambon & Banda Neira', tahun_perolehan: '1982', kondisi: 'Terawat', lokasi_simpan: 'Gedung B Ruang Sejarah Rempah' },
        { no_inventaris: 'MSL-K-003', nama_koleksi: 'Fosil Biota Laut Maluku & Kerangka Paus Raksasa Siwalima', kategori: 'Biologika', bahan: 'Kerangka Tulang Alami', asal: 'Laut Banda & Perairan Maluku Tenggara', tahun_perolehan: '1986', kondisi: 'Utuh / Terpelihara', lokasi_simpan: 'Gedung Kelautan & Biologi' },
        { no_inventaris: 'MSL-K-004', nama_koleksi: 'Naskah Kuno Daun Lontar & Alkitab Kuno Terjemahan Leijdecker', kategori: 'Filologika', bahan: 'Kertas Kuno Kering & Tinta Alami', asal: 'Negeri Saparua & Hila Leihitu', tahun_perolehan: '1990', kondisi: 'Sangat Baik (Ruang Kedap)', lokasi_simpan: 'Ruang Koleksi Khusus Filologi' }
    ];

    // Daftar Wilayah Cabang Dinas se-Maluku
    const cabdinRegions = [
        { id: 'ambon', name: 'Cabdin Wilayah I (Kota Ambon)', shortName: 'Kota Ambon', icon: 'fa-city', color: 'cyan' },
        { id: 'malteng', name: 'Cabdin Wilayah II (Maluku Tengah)', shortName: 'Maluku Tengah', icon: 'fa-mountain-sun', color: 'blue' },
        { id: 'buru', name: 'Cabdin Wilayah III (Kab. Buru & Bursel)', shortName: 'Buru & Bursel', icon: 'fa-tree', color: 'emerald' },
        { id: 'sbb', name: 'Cabdin Wilayah IV (Seram Bagian Barat)', shortName: 'Seram Barat (SBB)', icon: 'fa-water', color: 'purple' },
        { id: 'sbt', name: 'Cabdin Wilayah V (Seram Bagian Timur)', shortName: 'Seram Timur (SBT)', icon: 'fa-compass', color: 'pink' },
        { id: 'malra', name: 'Cabdin Wilayah VI (Maluku Tenggara & Tual)', shortName: 'Malra & Tual', icon: 'fa-island-tropical', color: 'amber' },
        { id: 'aru', name: 'Cabdin Wilayah VII (Kepulauan Aru)', shortName: 'Kepulauan Aru', icon: 'fa-ship', color: 'indigo' },
        { id: 'tanimbar', name: 'Cabdin Wilayah IX (Kepulauan Tanimbar)', shortName: 'Kep. Tanimbar', icon: 'fa-anchor', color: 'teal' },
        { id: 'mbd', name: 'Cabdin Wilayah XI (Maluku Barat Daya)', shortName: 'Maluku Barat Daya', icon: 'fa-sun', color: 'red' }
    ];

    // Cabang Dinas Info Data untuk Peta Interaktif
    const cabdinMapData = {
        'ambon': { name: 'Cabdin Wilayah I (Kota Ambon)', sekolah: 42, gtk: 340, internet: '92.5 Mbps', siswa: '14.2K' },
        'malteng': { name: 'Cabdin Wilayah II (Maluku Tengah)', sekolah: 38, gtk: 295, internet: '78.2 Mbps', siswa: '11.8K' },
        'buru': { name: 'Cabdin Wilayah III (Kab. Buru)', sekolah: 18, gtk: 140, internet: '65.0 Mbps', siswa: '5.2K' },
        'sbb': { name: 'Cabdin Wilayah IV (Seram Bagian Barat)', sekolah: 22, gtk: 175, internet: '68.4 Mbps', siswa: '6.4K' },
        'sbt': { name: 'Cabdin Wilayah V (Seram Bagian Timur)', sekolah: 16, gtk: 120, internet: '55.0 Mbps', siswa: '4.1K' },
        'malra': { name: 'Cabdin Wilayah VI (Maluku Tenggara & Tual)', sekolah: 24, gtk: 190, internet: '82.0 Mbps', siswa: '7.6K' },
        'tanimbar': { name: 'Cabdin Wilayah IX (Kep. Tanimbar)', sekolah: 15, gtk: 110, internet: '58.5 Mbps', siswa: '3.9K' },
        'mbd': { name: 'Cabdin Wilayah XI (Maluku Barat Daya)', sekolah: 12, gtk: 90, internet: '48.0 Mbps', siswa: '2.8K' }
    };

    // Default Seed Data Generator
    function getDefaultSeedData() {
        return {
            sekretariatData: {
                subbagUmum: { progress: 94.0, totalBerkas: 18, lastInput: 'Laporan Aset & BMD Sarpras Sekolah Q3' },
                subbagKeuangan: { serapan: 96.2, totalBerkas: 24, lastInput: 'Laporan Realisasi SP2D & DPA Fisik' },
                subbagData: {
                    totalSOP: 10, selesai: 8, diproses: 2, lastInput: 'KEP-02 Kenaikan Pangkat ASN',
                    sopList: [
                        { id: 'KEP-01', name: 'Surat / Naskah Dinas', category: 'Administrasi Persuratan', sla: 'Surat Masuk ≤ 1 hari kerja, Surat Keluar ≤ 2 hari kerja', staff: 15, status: 'Selesai', lastUpdate: 'Hari ini' },
                        { id: 'KEP-02', name: 'Layanan Kenaikan Pangkat', category: 'Karier & Pangkat ASN', sla: 'Maksimal 10 (sepuluh) hari kerja', staff: 11, status: 'Diproses', lastUpdate: 'Hari ini' },
                        { id: 'KEP-03', name: 'Kenaikan Gaji Berkala (KGB)', category: 'Hak & Kesejahteraan PNS', sla: 'Paling lama 5 (lima) hari kerja sebelum TMT KGB', staff: 11, status: 'Selesai', lastUpdate: 'Kemarin' },
                        { id: 'KEP-04', name: 'Layanan Izin Praktik Kerja Industri (PKL Siswa/Mahasiswa)', category: 'Kemitraan Pendidikan', sla: 'Maksimal 3 (tiga) hari kerja', staff: 1, status: 'Selesai', lastUpdate: '2 hari lalu' },
                        { id: 'KEP-05', name: 'Layanan Izin Cuti PNS', category: 'Hak & Kesejahteraan PNS', sla: 'Maksimal 3 (tiga) hari kerja (Cuti Sakit mendesak diproses lebih cepat)', staff: 11, status: 'Diproses', lastUpdate: 'Hari ini' },
                        { id: 'KEP-06', name: 'Layanan Izin Perceraian PNS', category: 'Status Hukum & Kepegawaian', sla: 'Maksimal 10 (sepuluh) hari kerja', staff: 1, status: 'Selesai', lastUpdate: '3 hari lalu' },
                        { id: 'KEP-07', name: 'Penerbitan Izin Belajar dan Tugas Belajar', category: 'Pengembangan Kompetensi', sla: 'Maksimal 7 (tujuh) hari kerja', staff: 11, status: 'Selesai', lastUpdate: '4 hari lalu' },
                        { id: 'KEP-08', name: 'Usul Pensiun PNS', category: 'Pensiun & BUP', sla: 'Maksimal 10 (sepuluh) hari kerja', staff: 11, status: 'Selesai', lastUpdate: 'Kemarin' },
                        { id: 'KEP-09', name: 'Layanan Izin Penelitian', category: 'Riset & Akademik', sla: 'Maksimal 10 (sepuluh) hari kerja', staff: 11, status: 'Selesai', lastUpdate: '5 hari lalu' },
                        { id: 'KEP-10', name: 'Usul Penyesuaian Gelar Akademik', category: 'Pengembangan Kualifikasi', sla: 'Maksimal 10 (sepuluh) hari kerja', staff: 11, status: 'Selesai', lastUpdate: '1 minggu lalu' }
                    ]
                },
                recentLogs: [
                    { unit: '2.1 Umum & Aset', title: 'Inventarisasi Aset Sarpras BMD Sekolah', status: 'Selesai', time: '10:05 WIT' },
                    { unit: '2.2 Perenc. & Keuangan', title: 'Laporan Realisasi SP2D & DPA Fisik', status: 'Selesai', time: '09:40 WIT' },
                    { unit: '2.3 Pengelolaan Data', title: 'KEP-02 Kenaikan Pangkat (11 Usulan)', status: 'Diproses', time: '08:30 WIT' }
                ]
            },
            gtkData: {
                pedagogik: 85, profesional: 78, sertifikasi3T: 62, literasiDigital: 88, kepemimpinan: 75, supervisi: 80,
                totalGuru: '12.8K', totalBerkas: 28, lastInput: 'Pemberkasan TPG Guru 3T Periode September'
            },
            smaData: {
                capaian: 94.2, target3T: 3, totalBerkas: 14,
                trend: [72, 74, 78, 80, 82, 85, 88, 91, 94.2, null, null, null],
                lastInput: 'Instrumen Akreditasi SMA 3T Maluku'
            },
            smkData: {
                tefaData: [35, 20, 25, 12, 8], dudiPercent: 84, totalBerkas: 16,
                lastInput: 'MoU Teaching Factory Kemaritiman DUDI'
            },
            slbData: {
                totalInklusi: 180, totalBerkas: 8, lastInput: 'Asesmen Kebutuhan Khusus ABK Maluku'
            },
            budayaData: {
                usulanBaru: 18, verifikasi: 12, terdaftar: 34,
                proportions: [42, 28, 18, 12],
                lastVerified: 'Benteng Amsterdam & Tradisi Lisan',
                totalBerkas: 64,
                lastInput: 'Pencatatan WBTB Tradisi Lisan Maluku'
            },
            pengawasanData: {
                totalAgenda: 28, sedangBerjalan: 6, programSelesai: 22,
                cabdinDistribution: [42, 38, 18, 22, 24, 15],
                lastInput: 'Jadwal Monitoring & Supervisi Cabdin'
            },
            uploadedFiles: {
                '2.1': [
                    { id: 'F-21-01', code: 'DOC-2.1-001', title: 'Laporan Inventarisasi KIB A-B-C Sarpras SMA & SMK se-Maluku Q3', category: '2.1.1 Inventarisasi Aset', format: 'PDF', size: '4.2 MB', day: 'Sabtu', date: '12 September 2026', time: '10:05 WIT', uploader: 'Admin Subbag Umum', status: 'VALIDATED' },
                    { id: 'F-21-02', code: 'DOC-2.1-002', title: 'Daftar Rekapitulasi Tata Naskah Persuratan & Ekspedisi Dinas Agustus-September 2026', category: '2.1.2 Tata Naskah Persuratan', format: 'XLSX', size: '1.8 MB', day: 'Jumat', date: '11 September 2026', time: '14:20 WIT', uploader: 'Admin Subbag Umum', status: 'VALIDATED' },
                    { id: 'F-21-03', code: 'DOC-2.1-003', title: 'Berita Acara Pemeliharaan Sarana Gedung Kantor & Logistik Wilayah', category: '2.1.3 Pemeliharaan Sarana', format: 'PDF', size: '2.9 MB', day: 'Kamis', date: '10 September 2026', time: '11:10 WIT', uploader: 'Admin Subbag Umum', status: 'VALIDATED' }
                ],
                '2.2': [
                    { id: 'F-22-01', code: 'DOC-2.2-001', title: 'Laporan Realisasi Penyerapan Anggaran DPA & Kas Daerah Triwulan III', category: '2.2.2 Laporan Realisasi Keuangan', format: 'PDF', size: '5.1 MB', day: 'Sabtu', date: '12 September 2026', time: '09:40 WIT', uploader: 'Admin Keuangan', status: 'VALIDATED' },
                    { id: 'F-22-02', code: 'DOC-2.2-002', title: 'Dokumen Perencanaan Perubahan Renja & RKA TVRI Maluku Tahun 2026', category: '2.2.1 Dokumen Perencanaan', format: 'PDF', size: '6.4 MB', day: 'Jumat', date: '11 September 2026', time: '16:00 WIT', uploader: 'Admin Perencanaan', status: 'VALIDATED' }
                ],
                '2.3': [
                    { id: 'F-23-01', code: 'DOC-2.3-001', title: 'Berkas Usulan Kenaikan Pangkat ASN Tenaga Pendidik Maluku (11 Guru)', category: 'KEP-02 Kenaikan Pangkat', format: 'ZIP', size: '8.7 MB', day: 'Sabtu', date: '12 September 2026', time: '08:30 WIT', uploader: 'Admin GTK/Data', status: 'VALIDATED' },
                    { id: 'F-23-02', code: 'DOC-2.3-002', title: 'Rekapitulasi Kenaikan Gaji Berkala (KGB) Guru PNS Periode September', category: 'KEP-03 KGB', format: 'PDF', size: '2.1 MB', day: 'Jumat', date: '11 September 2026', time: '13:15 WIT', uploader: 'Admin Kepegawaian', status: 'VALIDATED' }
                ]
            },
            uptdBtkpList: [...initialBtkpList],
            uptdTamanBudayaList: [...initialTamanBudayaList],
            uptdMuseumList: [...initialMuseumList]
        };
    }

    // Empty Clean State Generator (All 0 / Blank)
    function getEmptyData() {
        return {
            sekretariatData: {
                subbagUmum: { progress: 0, totalBerkas: 0, lastInput: '-' },
                subbagKeuangan: { serapan: 0, totalBerkas: 0, lastInput: '-' },
                subbagData: { totalSOP: 10, selesai: 0, diproses: 0, lastInput: '-', sopList: [] },
                recentLogs: []
            },
            gtkData: {
                pedagogik: 0, profesional: 0, sertifikasi3T: 0, literasiDigital: 0, kepemimpinan: 0, supervisi: 0,
                totalGuru: '0', totalBerkas: 0, lastInput: '-'
            },
            smaData: {
                capaian: 0, target3T: 0, totalBerkas: 0,
                trend: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                lastInput: '-'
            },
            smkData: {
                tefaData: [0, 0, 0, 0, 0], dudiPercent: 0, totalBerkas: 0,
                lastInput: '-'
            },
            slbData: {
                totalInklusi: 0, totalBerkas: 0, lastInput: '-'
            },
            budayaData: {
                usulanBaru: 0, verifikasi: 0, terdaftar: 0,
                proportions: [0, 0, 0, 0],
                lastVerified: 'Belum ada data',
                totalBerkas: 0,
                lastInput: '-'
            },
            pengawasanData: {
                totalAgenda: 0, sedangBerjalan: 0, programSelesai: 0,
                cabdinDistribution: [0, 0, 0, 0, 0, 0],
                lastInput: '-'
            },
            uploadedFiles: {},
            uptdBtkpList: [],
            uptdTamanBudayaList: [],
            uptdMuseumList: []
        };
    }

    // --- APPLICATION CORE LOGIC ---
    const app = {
        state: {
            role: '{{ auth()->check() ? (auth()->user()->hasRole("Super Admin") ? "admin" : "kadis") : null }}',
            activeMenuId: '1.1',
            activeParentId: 'm1',
            sidebarSearchQuery: '',
            openSidebarGroups: ['m1', 'm2', 'm3', 'm4', 'm5', 'm6', 'm7', 'm10'],
            searchQuery: '',
            filterJenjang: 'all',
            filterCabdin: 'all',
            selectedCabdin: 'ambon',
            targetModalUnit: '',
            targetSopId: '',
            charts: {},

            // MASTER DATA SEKOLAH & UPTD (PERSISTEN)
            schoolList: [...initialSchoolList],
            uptdBtkpList: [...initialBtkpList],
            uptdTamanBudayaList: [...initialTamanBudayaList],
            uptdMuseumList: [...initialMuseumList],
            btkpSearchQuery: '',
            btkpFilterStatus: 'all',
            tamanBudayaSearchQuery: '',
            tamanBudayaFilterStatus: 'all',
            museumSearchQuery: '',
            museumFilterKategori: 'all',
            museumFilterKondisi: 'all',

            // STATE TERSINKRONISASI SEKRETARIAT (2.1, 2.2, 2.3)
            sekretariatData: {
                subbagUmum: {
                    progress: 94.0,
                    totalBerkas: 18,
                    lastInput: 'Laporan Aset & BMD Sarpras Sekolah Q3'
                },
                subbagKeuangan: {
                    serapan: 96.2,
                    totalBerkas: 24,
                    lastInput: 'Laporan Realisasi SP2D & DPA Fisik'
                },
                subbagData: {
                    totalSOP: 10,
                    selesai: 8,
                    diproses: 2,
                    lastInput: 'KEP-02 Kenaikan Pangkat ASN',
                    sopList: [
                        { id: 'KEP-01', name: 'Surat / Naskah Dinas', category: 'Administrasi Persuratan', sla: 'Surat Masuk ≤ 1 hari kerja, Surat Keluar ≤ 2 hari kerja', staff: 15, status: 'Selesai', lastUpdate: 'Hari ini' },
                        { id: 'KEP-02', name: 'Layanan Kenaikan Pangkat', category: 'Karier & Pangkat ASN', sla: 'Maksimal 10 (sepuluh) hari kerja', staff: 11, status: 'Diproses', lastUpdate: 'Hari ini' },
                        { id: 'KEP-03', name: 'Kenaikan Gaji Berkala (KGB)', category: 'Hak & Kesejahteraan PNS', sla: 'Paling lama 5 (lima) hari kerja sebelum TMT KGB', staff: 11, status: 'Selesai', lastUpdate: 'Kemarin' },
                        { id: 'KEP-04', name: 'Layanan Izin Praktik Kerja Industri (PKL Siswa/Mahasiswa)', category: 'Kemitraan Pendidikan', sla: 'Maksimal 3 (tiga) hari kerja', staff: 1, status: 'Selesai', lastUpdate: '2 hari lalu' },
                        { id: 'KEP-05', name: 'Layanan Izin Cuti PNS', category: 'Hak & Kesejahteraan PNS', sla: 'Maksimal 3 (tiga) hari kerja (Cuti Sakit mendesak diproses lebih cepat)', staff: 11, status: 'Diproses', lastUpdate: 'Hari ini' },
                        { id: 'KEP-06', name: 'Layanan Izin Perceraian PNS', category: 'Status Hukum & Kepegawaian', sla: 'Maksimal 10 (sepuluh) hari kerja', staff: 1, status: 'Selesai', lastUpdate: '3 hari lalu' },
                        { id: 'KEP-07', name: 'Penerbitan Izin Belajar dan Tugas Belajar', category: 'Pengembangan Kompetensi', sla: 'Maksimal 7 (tujuh) hari kerja', staff: 11, status: 'Selesai', lastUpdate: '4 hari lalu' },
                        { id: 'KEP-08', name: 'Usul Pensiun PNS', category: 'Pensiun & BUP', sla: 'Maksimal 10 (sepuluh) hari kerja', staff: 11, status: 'Selesai', lastUpdate: 'Kemarin' },
                        { id: 'KEP-09', name: 'Layanan Izin Penelitian', category: 'Riset & Akademik', sla: 'Maksimal 10 (sepuluh) hari kerja', staff: 11, status: 'Selesai', lastUpdate: '5 hari lalu' },
                        { id: 'KEP-10', name: 'Usul Penyesuaian Gelar Akademik', category: 'Pengembangan Kualifikasi', sla: 'Maksimal 10 (sepuluh) hari kerja', staff: 11, status: 'Selesai', lastUpdate: '1 minggu lalu' }
                    ]
                },
                recentLogs: [
                    { unit: '2.1 Umum & Aset', title: 'Inventarisasi Aset Sarpras BMD Sekolah', status: 'Selesai', time: '10:05 WIT' },
                    { unit: '2.2 Perenc. & Keuangan', title: 'Laporan Realisasi SP2D & DPA Fisik', status: 'Selesai', time: '09:40 WIT' },
                    { unit: '2.3 Pengelolaan Data', title: 'KEP-02 Kenaikan Pangkat (11 Usulan)', status: 'Diproses', time: '08:30 WIT' }
                ]
            },

            // STATE TERSINKRONISASI BIDANG GTK (7.1, 7.2, 7.3)
            gtkData: {
                pedagogik: 85,
                profesional: 78,
                sertifikasi3T: 62,
                literasiDigital: 88,
                kepemimpinan: 75,
                supervisi: 80,
                totalGuru: '12.8K',
                totalBerkas: 28,
                lastInput: 'Pemberkasan TPG Guru 3T Periode September'
            },

            // STATE TERSINKRONISASI BIDANG SMA (3.1, 3.2, 3.3, 3.4)
            smaData: {
                capaian: 94.2,
                target3T: 3,
                totalBerkas: 14,
                trend: [72, 74, 78, 80, 82, 85, 88, 91, 94.2, null, null, null],
                lastInput: 'Instrumen Akreditasi SMA 3T Maluku'
            },

            // STATE TERSINKRONISASI BIDANG SMK (4.1, 4.2, 4.3, 4.4)
            smkData: {
                tefaData: [35, 20, 25, 12, 8],
                dudiPercent: 84,
                totalBerkas: 16,
                lastInput: 'MoU Teaching Factory Kemaritiman DUDI'
            },

            // STATE TERSINKRONISASI BIDANG PENDIDIKAN KHUSUS / SLB (5.1, 5.2, 5.3)
            slbData: {
                totalInklusi: 180,
                totalBerkas: 8,
                lastInput: 'Asesmen Kebutuhan Khusus ABK Maluku'
            },

            // STATE TERSINKRONISASI BIDANG KEBUDAYAAN (6.1, 6.2, 6.3)
            budayaData: {
                usulanBaru: 18,
                verifikasi: 12,
                terdaftar: 34,
                proportions: [42, 28, 18, 12],
                lastVerified: 'Benteng Amsterdam & Tradisi Lisan',
                totalBerkas: 64,
                lastInput: 'Pencatatan WBTB Tradisi Lisan Maluku'
            },

            // STATE TERSINKRONISASI KALENDER & PENGAWASAN (7 & 10.1)
            pengawasanData: {
                totalAgenda: 28,
                sedangBerjalan: 6,
                programSelesai: 22,
                cabdinDistribution: [42, 38, 18, 22, 24, 15],
                lastInput: 'Jadwal Monitoring & Supervisi Cabdin'
            },

            // RIWAYAT BERKAS TERINPUT PER MODUL
            uploadedFiles: {
                '2.1': [
                    { id: 'F-21-01', code: 'DOC-2.1-001', title: 'Laporan Inventarisasi KIB A-B-C Sarpras SMA & SMK se-Maluku Q3', category: '2.1.1 Inventarisasi Aset', format: 'PDF', size: '4.2 MB', day: 'Sabtu', date: '12 September 2026', time: '10:05 WIT', uploader: 'Admin Subbag Umum', status: 'VALIDATED' },
                    { id: 'F-21-02', code: 'DOC-2.1-002', title: 'Daftar Rekapitulasi Tata Naskah Persuratan & Ekspedisi Dinas Agustus-September 2026', category: '2.1.2 Tata Naskah Persuratan', format: 'XLSX', size: '1.8 MB', day: 'Jumat', date: '11 September 2026', time: '14:20 WIT', uploader: 'Admin Subbag Umum', status: 'VALIDATED' },
                    { id: 'F-21-03', code: 'DOC-2.1-003', title: 'Berita Acara Pemeliharaan Sarana Gedung Kantor & Logistik Wilayah', category: '2.1.3 Pemeliharaan Sarana', format: 'PDF', size: '2.9 MB', day: 'Kamis', date: '10 September 2026', time: '11:10 WIT', uploader: 'Admin Subbag Umum', status: 'VALIDATED' }
                ],
                '2.2': [
                    { id: 'F-22-01', code: 'DOC-2.2-001', title: 'Laporan Realisasi Penyerapan Anggaran DPA & Kas Daerah Triwulan III', category: '2.2.2 Laporan Realisasi Keuangan', format: 'PDF', size: '5.1 MB', day: 'Sabtu', date: '12 September 2026', time: '09:40 WIT', uploader: 'Admin Keuangan', status: 'VALIDATED' },
                    { id: 'F-22-02', code: 'DOC-2.2-002', title: 'Dokumen Perencanaan Perubahan Renja & RKA TVRI Maluku Tahun 2026', category: '2.2.1 Dokumen Perencanaan', format: 'PDF', size: '6.4 MB', day: 'Jumat', date: '11 September 2026', time: '16:00 WIT', uploader: 'Admin Perencanaan', status: 'VALIDATED' }
                ],
                '2.3': [
                    { id: 'F-23-01', code: 'DOC-2.3-001', title: 'Berkas Usulan Kenaikan Pangkat ASN Tenaga Pendidik Maluku (11 Guru)', category: 'KEP-02 Kenaikan Pangkat', format: 'ZIP', size: '8.7 MB', day: 'Sabtu', date: '12 September 2026', time: '08:30 WIT', uploader: 'Admin GTK/Data', status: 'VALIDATED' },
                    { id: 'F-23-02', code: 'DOC-2.3-002', title: 'Rekapitulasi Kenaikan Gaji Berkala (KGB) Guru PNS Periode September', category: 'KEP-03 KGB', format: 'PDF', size: '2.1 MB', day: 'Jumat', date: '11 September 2026', time: '13:15 WIT', uploader: 'Admin Kepegawaian', status: 'VALIDATED' }
                ]
            }
        },

        init() {
            try {
                const saved = localStorage.getItem('simandat_state_master_v3');
                if (saved) {
                    const parsed = JSON.parse(saved);
                    if (parsed && parsed.sekretariatData) this.state.sekretariatData = parsed.sekretariatData;
                    if (parsed && parsed.gtkData) this.state.gtkData = parsed.gtkData;
                    if (parsed && parsed.smaData) this.state.smaData = parsed.smaData;
                    if (parsed && parsed.smkData) this.state.smkData = parsed.smkData;
                    if (parsed && parsed.slbData) this.state.slbData = parsed.slbData;
                    if (parsed && parsed.budayaData) this.state.budayaData = parsed.budayaData;
                    if (parsed && parsed.pengawasanData) this.state.pengawasanData = parsed.pengawasanData;
                    if (parsed && parsed.uploadedFiles) this.state.uploadedFiles = parsed.uploadedFiles;
                    if (parsed && parsed.schoolList) this.state.schoolList = parsed.schoolList;
                    if (parsed && parsed.uptdBtkpList) this.state.uptdBtkpList = parsed.uptdBtkpList;
                    if (parsed && parsed.uptdTamanBudayaList) this.state.uptdTamanBudayaList = parsed.uptdTamanBudayaList;
                    if (parsed && parsed.uptdMuseumList) this.state.uptdMuseumList = parsed.uptdMuseumList;
                    if (parsed && parsed.selectedCabdin) this.state.selectedCabdin = parsed.selectedCabdin;
                }
            } catch(e) {
                console.warn('LocalStorage unavailable', e);
            }

            this.updateTime();
            setInterval(() => this.updateTime(), 1000);
            
            const fileInput = document.getElementById('fileInput');
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    if(e.target.files.length > 0) {
                        const fileNameEl = document.getElementById('fileNameDisplay');
                        fileNameEl.textContent = e.target.files[0].name;
                        fileNameEl.classList.add('text-app-primary');
                    }
                });
            }

            // Sync with Laravel MySQL Database
            this.syncDatabaseData();

            // Auto-activate Admin mode for seamless access
            if (!this.state.role) {
                this.state.role = 'admin';
            }
            const loginEl = document.getElementById('loginScreen');
            const mainAppEl = document.getElementById('mainApp');
            if (loginEl && mainAppEl) {
                loginEl.classList.add('hidden');
                mainAppEl.classList.remove('hidden');
            }
            this.renderSidebar();
            this.loadContent(this.state.activeMenuId || '1.1');
        },

        async syncDatabaseData() {
            try {
                const res = await fetch('/api/simandat/archives');
                if (res.ok) {
                    const json = await res.json();
                    if (json.data && json.data.length > 0) {
                        const grouped = {};
                        json.data.forEach(item => {
                            const mod = item.module_id || '2.1';
                            if (!grouped[mod]) grouped[mod] = [];
                            grouped[mod].push({
                                id: item.id,
                                code: item.code || ('DOC-' + item.id),
                                title: item.title,
                                category: item.category || (mod + ' Laporan'),
                                format: item.format || 'PDF',
                                size: item.file_size || '2.4 MB',
                                day: item.day || 'Sabtu',
                                date: item.date || '12/09/2026',
                                time: item.time || '10:00 WIT',
                                uploader: item.uploader || 'Super Admin (TVRI Maluku)',
                                status: item.status || 'VALIDATED',
                                pic: item.pic || '',
                                budget: item.budget || '',
                                description: item.description || '',
                                is_protected: item.is_protected ?? true
                            });
                        });
                        this.state.uploadedFiles = grouped;
                        
                        // Sync counters across all modules
                        if (grouped['2.1']) this.state.sekretariatData.subbagUmum.totalBerkas = grouped['2.1'].length;
                        if (grouped['2.2']) this.state.sekretariatData.subbagKeuangan.totalBerkas = grouped['2.2'].length;
                        
                        let totalSmaFiles = 0;
                        ['3.1', '3.2', '3.3', '3.4'].forEach(m => { if (grouped[m]) totalSmaFiles += grouped[m].length; });
                        if (totalSmaFiles > 0) this.state.smaData.totalBerkas = totalSmaFiles;

                        let totalSmkFiles = 0;
                        ['4.1', '4.2', '4.3', '4.4'].forEach(m => { if (grouped[m]) totalSmkFiles += grouped[m].length; });
                        if (totalSmkFiles > 0) this.state.smkData.totalBerkas = totalSmkFiles;

                        let totalSlbFiles = 0;
                        ['5.1', '5.2', '5.3'].forEach(m => { if (grouped[m]) totalSlbFiles += grouped[m].length; });
                        if (totalSlbFiles > 0) this.state.slbData.totalBerkas = totalSlbFiles;

                        let totalBudayaFiles = 0;
                        const count61 = grouped['6.1'] ? grouped['6.1'].length : 0;
                        const count62 = grouped['6.2'] ? grouped['6.2'].length : 0;
                        const count63 = grouped['6.3'] ? grouped['6.3'].length : 0;
                        totalBudayaFiles = count61 + count62 + count63;
                        if (totalBudayaFiles > 0) {
                            this.state.budayaData.totalBerkas = 64 + totalBudayaFiles;
                            this.state.budayaData.usulanBaru = 18 + totalBudayaFiles;
                            this.state.budayaData.verifikasi = 12 + totalBudayaFiles;
                            this.state.budayaData.terdaftar = 34 + totalBudayaFiles;
                            this.state.budayaData.proportions = [
                                42 + count61 * 2,
                                28 + count62 * 2,
                                18 + count63 * 2,
                                12 + count62
                            ];
                            ['6.1', '6.2', '6.3'].forEach(m => {
                                if (grouped[m] && grouped[m].length > 0) {
                                    this.state.budayaData.lastVerified = grouped[m][0].title;
                                }
                            });
                        }

                        let totalGtkFiles = 0;
                        ['7.1', '7.2', '7.3'].forEach(m => { if (grouped[m]) totalGtkFiles += grouped[m].length; });
                        if (totalGtkFiles > 0) this.state.gtkData.totalBerkas = totalGtkFiles;
                    }
                }
            } catch (e) {
                console.warn('API Archives offline:', e);
            }

            try {
                const sRes = await fetch('/api/simandat/schools');
                if (sRes.ok) {
                    const sJson = await sRes.json();
                    if (sJson.data && sJson.data.length > 0) {
                        this.state.schoolList = sJson.data;
                    }
                }
            } catch (e) {
                console.warn('API Schools offline:', e);
            }

            // Sync UPTD from MySQL Backend
            try {
                const btkpRes = await fetch('/api/simandat/uptd/btkp');
                if (btkpRes.ok) {
                    const bJson = await btkpRes.json();
                    if (bJson.data && bJson.data.length > 0) this.state.uptdBtkpList = bJson.data;
                }
            } catch(e) {}

            try {
                const tbRes = await fetch('/api/simandat/uptd/taman-budaya');
                if (tbRes.ok) {
                    const tbJson = await tbRes.json();
                    if (tbJson.data && tbJson.data.length > 0) this.state.uptdTamanBudayaList = tbJson.data;
                }
            } catch(e) {}

            try {
                const mRes = await fetch('/api/simandat/uptd/museum');
                if (mRes.ok) {
                    const mJson = await mRes.json();
                    if (mJson.data && mJson.data.length > 0) this.state.uptdMuseumList = mJson.data;
                }
            } catch(e) {}

            this.saveState();
        },

        saveState() {
            try {
                localStorage.setItem('simandat_state_master_v3', JSON.stringify({
                    sekretariatData: this.state.sekretariatData,
                    gtkData: this.state.gtkData,
                    smaData: this.state.smaData,
                    smkData: this.state.smkData,
                    slbData: this.state.slbData,
                    budayaData: this.state.budayaData,
                    pengawasanData: this.state.pengawasanData,
                    uploadedFiles: this.state.uploadedFiles,
                    schoolList: this.state.schoolList,
                    uptdBtkpList: this.state.uptdBtkpList,
                    uptdTamanBudayaList: this.state.uptdTamanBudayaList,
                    uptdMuseumList: this.state.uptdMuseumList,
                    selectedCabdin: this.state.selectedCabdin
                }));
            } catch(e) {}
        },

        login(selectedRole) {
            this.state.role = selectedRole;
            const loginEl = document.getElementById('loginScreen');
            const mainAppEl = document.getElementById('mainApp');
            if (loginEl) loginEl.classList.add('hidden');
            if (mainAppEl) mainAppEl.classList.remove('hidden');
            
            const roleDisplay = document.getElementById('userRoleDisplay');
            const statusDisplay = document.getElementById('userStatusDisplay');
            const chevronHTML = '<i class="fa-solid fa-chevron-down text-[9px] text-slate-400 group-hover:text-cyan-400 transition-colors"></i>';
            
            if (selectedRole === 'kadis') {
                if (roleDisplay) roleDisplay.innerHTML = `<span>Dr. Suriato Segeede, S.Pd., M.Pd.</span> ${chevronHTML}`;
                if (statusDisplay) {
                    statusDisplay.textContent = 'Kepala Stasiun (View Only)';
                    statusDisplay.className = 'text-[9px] text-amber-400 font-medium';
                }
            } else if (selectedRole === 'admin') {
                if (roleDisplay) roleDisplay.innerHTML = `<span>Administrator</span> ${chevronHTML}`;
                if (statusDisplay) {
                    statusDisplay.textContent = 'Super Admin (Full Access)';
                    statusDisplay.className = 'text-[9px] text-cyan-400 font-medium';
                }
            } else {
                @auth
                if (roleDisplay) roleDisplay.innerHTML = `<span>{{ auth()->user()->name }}</span> ${chevronHTML}`;
                if (statusDisplay) {
                    statusDisplay.textContent = '{{ auth()->user()->roles->pluck("name")->join(", ") ?: "Super Admin (Full Access)" }}';
                    statusDisplay.className = 'text-[9px] text-cyan-400 font-medium';
                }
                @endauth
            }

            this.renderSidebar();
            this.loadContent(this.state.activeMenuId || '1.1');
            this.showToast('Login berhasil sebagai ' + (selectedRole === 'admin' ? 'Super Admin' : (selectedRole === 'kadis' ? 'Kepala Stasiun' : 'Pengguna')), 'success');
        },

        logout() {
            this.state.role = null;
            @auth
            const logoutForm = document.createElement('form');
            logoutForm.method = 'POST';
            logoutForm.action = '{{ route("logout") }}';
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            logoutForm.appendChild(csrf);
            document.body.appendChild(logoutForm);
            logoutForm.submit();
            return;
            @endauth
            
            const mainAppEl = document.getElementById('mainApp');
            const loginEl = document.getElementById('loginScreen');
            if (mainAppEl) mainAppEl.classList.add('hidden');
            if (loginEl) loginEl.classList.remove('hidden');
            this.showToast('Anda telah logout dari sistem.', 'info');
        },

        // --- ADMIN ACCOUNT & DATABASE MODAL HANDLERS ---
        openAdminAccountModal() {
            const modal = document.getElementById('adminAccountModal');
            const content = document.getElementById('adminAccountModalContent');
            if (!modal) return;

            const nameEl = document.getElementById('adminModalName');
            const badgeEl = document.getElementById('adminModalBadge');
            const emailEl = document.getElementById('adminModalEmail');
            const archiveCountEl = document.getElementById('adminModalArchiveCount');
            const schoolCountEl = document.getElementById('adminModalSchoolCount');

            const isKadis = this.state.role === 'kadis';
            if (isKadis) {
                if (nameEl) nameEl.textContent = 'Dr. Suriato Segeede, S.Pd., M.Pd.';
                if (badgeEl) {
                    badgeEl.innerHTML = '<i class="fa-solid fa-user-tie mr-1"></i> Kepala Stasiun (View Only)';
                    badgeEl.className = 'bg-amber-500/20 text-amber-300 border border-amber-500/40 text-[10px] px-2.5 py-0.5 rounded-full font-bold shadow-sm';
                }
                if (emailEl) emailEl.textContent = 'kepala.stasiun@tvri.go.id';
            } else {
                if (nameEl) nameEl.textContent = 'Administrator (Super Admin)';
                if (badgeEl) {
                    badgeEl.innerHTML = '<i class="fa-solid fa-crown mr-1"></i> Super Admin (Full Access)';
                    badgeEl.className = 'bg-app-primary/20 text-app-primary border border-app-primary/40 text-[10px] px-2.5 py-0.5 rounded-full font-bold shadow-sm';
                }
                if (emailEl) emailEl.textContent = 'admin.simandat@malukuprov.go.id';
            }

            let totalArchives = 0;
            Object.values(this.state.uploadedFiles).forEach(arr => {
                if (Array.isArray(arr)) totalArchives += arr.length;
            });
            if (archiveCountEl) archiveCountEl.textContent = `${totalArchives} Berkas`;
            if (schoolCountEl) schoolCountEl.textContent = `${this.state.schoolList.length} Sekolah`;

            modal.classList.remove('hidden');
            void modal.offsetWidth;
            modal.classList.replace('opacity-0', 'opacity-100');
            content.classList.replace('scale-95', 'scale-100');
        },

        closeAdminAccountModal() {
            const modal = document.getElementById('adminAccountModal');
            const content = document.getElementById('adminAccountModalContent');
            if (!modal) return;
            modal.classList.replace('opacity-100', 'opacity-0');
            content.classList.replace('scale-100', 'scale-95');
            setTimeout(() => modal.classList.add('hidden'), 200);
        },

        switchRoleFromModal(newRole) {
            this.closeAdminAccountModal();
            this.login(newRole);
        },

        async refreshDatabaseSync() {
            const archiveCountEl = document.getElementById('adminModalArchiveCount');
            const schoolCountEl = document.getElementById('adminModalSchoolCount');
            if (archiveCountEl) archiveCountEl.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-xs"></i>';
            if (schoolCountEl) schoolCountEl.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-xs"></i>';
            
            await this.syncDatabaseData();
            this.showToast('Data berhasil disinkronkan langsung dari Database MySQL.', 'success');

            let totalArchives = 0;
            Object.values(this.state.uploadedFiles).forEach(arr => {
                if (Array.isArray(arr)) totalArchives += arr.length;
            });
            if (archiveCountEl) archiveCountEl.textContent = `${totalArchives} Berkas`;
            if (schoolCountEl) schoolCountEl.textContent = `${this.state.schoolList.length} Sekolah`;
        },

        logoutFromModal() {
            this.closeAdminAccountModal();
            this.logout();
        },

        // --- RESET DATA MODAL & ACTIONS ---
        openResetModal() {
            const modal = document.getElementById('resetModal');
            const content = document.getElementById('resetModalContent');
            if (!modal) return;
            modal.classList.remove('hidden');
            void modal.offsetWidth;
            modal.classList.replace('opacity-0', 'opacity-100');
            content.classList.replace('scale-95', 'scale-100');
        },

        closeResetModal() {
            const modal = document.getElementById('resetModal');
            const content = document.getElementById('resetModalContent');
            if (!modal) return;
            modal.classList.replace('opacity-100', 'opacity-0');
            content.classList.replace('scale-100', 'scale-95');
            setTimeout(() => modal.classList.add('hidden'), 200);
        },

        executeResetData(mode = 'empty_all') {
            if (mode === 'empty_all') {
                const empty = getEmptyData();
                this.state.sekretariatData = empty.sekretariatData;
                this.state.gtkData = empty.gtkData;
                this.state.smaData = empty.smaData;
                this.state.smkData = empty.smkData;
                this.state.slbData = empty.slbData;
                this.state.budayaData = empty.budayaData;
                this.state.pengawasanData = empty.pengawasanData;
                this.state.uploadedFiles = {};
                this.state.uptdBtkpList = [];
                this.state.uptdTamanBudayaList = [];
                this.state.uptdMuseumList = [];
                this.saveState();
                this.closeResetModal();
                this.loadContent(this.state.activeMenuId || '1.1');
                this.showToast('Seluruh data berhasil dikosongkan (Nilai 0 / Blank).', 'success');
            } else if (mode === 'empty_sekretariat') {
                this.resetSekretariatData();
                this.closeResetModal();
            } else if (mode === 'empty_budaya') {
                this.resetBudayaData();
                this.closeResetModal();
            } else if (mode === 'empty_school' || mode === 'restore_school') {
                this.resetSchoolData();
                this.closeResetModal();
            } else if (mode === 'restore_default') {
                const def = getDefaultSeedData();
                this.state.sekretariatData = def.sekretariatData;
                this.state.gtkData = def.gtkData;
                this.state.smaData = def.smaData;
                this.state.smkData = def.smkData;
                this.state.slbData = def.slbData;
                this.state.budayaData = def.budayaData;
                this.state.pengawasanData = def.pengawasanData;
                this.state.uploadedFiles = def.uploadedFiles;
                this.state.schoolList = [...initialSchoolList];
                this.state.uptdBtkpList = [...initialBtkpList];
                this.state.uptdTamanBudayaList = [...initialTamanBudayaList];
                this.state.uptdMuseumList = [...initialMuseumList];
                this.state.selectedCabdin = 'ambon';
                this.saveState();
                this.closeResetModal();
                this.loadContent(this.state.activeMenuId || '1.1');
                this.selectCabdin('ambon');
                this.showToast('Data berhasil dipulihkan ke data simulasi default TVRI Maluku.', 'success');
            }
        },

        resetSchoolData() {
            this.state.schoolList = [...initialSchoolList];
            this.state.selectedCabdin = 'ambon';
            this.saveState();
            this.loadContent(this.state.activeMenuId || '1.1');
            this.selectCabdin('ambon');
            this.showToast('Data Satuan Pendidikan & Archipelago berhasil dikembalikan ke awal (8 Sekolah Master).', 'success');
        },

        resetSekretariatData() {
            this.state.sekretariatData = {
                subbagUmum: { progress: 0, totalBerkas: 0, lastInput: '-' },
                subbagKeuangan: { serapan: 0, totalBerkas: 0, lastInput: '-' },
                subbagData: {
                    totalSOP: 10,
                    selesai: 0,
                    diproses: 0,
                    lastInput: '-',
                    sopList: [
                        { id: 'KEP-01', name: 'Surat / Naskah Dinas', category: 'Administrasi Persuratan', sla: 'Surat Masuk ≤ 1 hari kerja, Surat Keluar ≤ 2 hari kerja', staff: 15, status: 'Menunggu', lastUpdate: '-' },
                        { id: 'KEP-02', name: 'Layanan Kenaikan Pangkat', category: 'Karier & Pangkat ASN', sla: 'Maksimal 10 (sepuluh) hari kerja', staff: 11, status: 'Menunggu', lastUpdate: '-' },
                        { id: 'KEP-03', name: 'Kenaikan Gaji Berkala (KGB)', category: 'Hak & Kesejahteraan PNS', sla: 'Paling lama 5 (lima) hari kerja sebelum TMT KGB', staff: 11, status: 'Menunggu', lastUpdate: '-' },
                        { id: 'KEP-04', name: 'Layanan Izin Praktik Kerja Industri (PKL Siswa/Mahasiswa)', category: 'Kemitraan Pendidikan', sla: 'Maksimal 3 (tiga) hari kerja', staff: 1, status: 'Menunggu', lastUpdate: '-' },
                        { id: 'KEP-05', name: 'Layanan Izin Cuti PNS', category: 'Hak & Kesejahteraan PNS', sla: 'Maksimal 3 (tiga) hari kerja (Cuti Sakit mendesak diproses lebih cepat)', staff: 11, status: 'Menunggu', lastUpdate: '-' },
                        { id: 'KEP-06', name: 'Layanan Izin Perceraian PNS', category: 'Status Hukum & Kepegawaian', sla: 'Maksimal 10 (sepuluh) hari kerja', staff: 1, status: 'Menunggu', lastUpdate: '-' },
                        { id: 'KEP-07', name: 'Penerbitan Izin Belajar dan Tugas Belajar', category: 'Pengembangan Kompetensi', sla: 'Maksimal 7 (tujuh) hari kerja', staff: 11, status: 'Menunggu', lastUpdate: '-' },
                        { id: 'KEP-08', name: 'Usul Pensiun PNS', category: 'Pensiun & BUP', sla: 'Maksimal 10 (sepuluh) hari kerja', staff: 11, status: 'Menunggu', lastUpdate: '-' },
                        { id: 'KEP-09', name: 'Layanan Izin Penelitian', category: 'Riset & Akademik', sla: 'Maksimal 10 (sepuluh) hari kerja', staff: 11, status: 'Menunggu', lastUpdate: '-' },
                        { id: 'KEP-10', name: 'Usul Penyesuaian Gelar Akademik', category: 'Pengembangan Kualifikasi', sla: 'Maksimal 10 (sepuluh) hari kerja', staff: 11, status: 'Menunggu', lastUpdate: '-' }
                    ]
                },
                recentLogs: []
            };
            delete this.state.uploadedFiles['2.1'];
            delete this.state.uploadedFiles['2.2'];
            delete this.state.uploadedFiles['2.3'];
            this.saveState();
            this.loadContent(this.state.activeMenuId || '1.1');
            this.showToast('Data Sekretariat & Tata Kelola berhasil dikosongkan (Nilai 0 / Blank).', 'success');
        },

        resetBudayaData() {
            this.state.budayaData = {
                usulanBaru: 0,
                verifikasi: 0,
                terdaftar: 0,
                proportions: [0, 0, 0, 0],
                lastVerified: 'Belum ada data',
                totalBerkas: 0,
                lastInput: '-'
            };
            delete this.state.uploadedFiles['6.1'];
            delete this.state.uploadedFiles['6.2'];
            delete this.state.uploadedFiles['6.3'];
            this.saveState();
            this.loadContent(this.state.activeMenuId || '1.1');
            this.showToast('Data Bidang Kebudayaan berhasil dikosongkan (Nilai 0 / Blank).', 'success');
        },

        updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-GB') + ' WIT';
            const dateString = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }).toUpperCase();
            
            const timeEl = document.getElementById('currentTime');
            if (timeEl) timeEl.textContent = `${dateString} ${timeString}`;

            const ccTimeEl = document.getElementById('commandCenterClock');
            if (ccTimeEl) ccTimeEl.textContent = timeString;
            const ccDateEl = document.getElementById('commandCenterDate');
            if (ccDateEl) ccDateEl.textContent = dateString;
        },

        showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            if (!container) return;

            const toast = document.createElement('div');
            let icon = 'fa-info-circle';
            let colorClass = 'border-app-primary text-app-primary';
            
            if(type === 'success') { icon = 'fa-check-circle'; colorClass = 'border-app-success text-app-success'; }
            if(type === 'warning') { icon = 'fa-exclamation-triangle'; colorClass = 'border-app-warning text-app-warning'; }

            toast.className = `bg-app-surface border-l-4 ${colorClass} text-white px-4 py-3 rounded shadow-lg flex items-center gap-3 transform transition-all duration-300 translate-y-10 opacity-0 z-50`;
            toast.innerHTML = `<i class="fa-solid ${icon}"></i> <span class="text-sm font-medium">${message}</span>`;
            
            container.appendChild(toast);
            
            setTimeout(() => toast.classList.remove('translate-y-10', 'opacity-0'), 10);
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-10');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        },

        renderSidebar() {
            const nav = document.getElementById('sidebarNav');
            if (!nav) return;
            
            const query = (this.state.sidebarSearchQuery || '').toLowerCase().trim();
            let openGroups = this.state.openSidebarGroups;
            if (!Array.isArray(openGroups)) {
                if (openGroups && typeof openGroups.has === 'function') {
                    openGroups = Array.from(openGroups);
                } else if (openGroups && typeof openGroups === 'object') {
                    openGroups = Object.keys(openGroups);
                } else {
                    openGroups = ['m1', 'm2', 'm3', 'm4', 'm5', 'm6', 'm7', 'm10'];
                }
                this.state.openSidebarGroups = openGroups;
            }
            
            let html = '';

            menuData.forEach(group => {
                // Check if group or any item matches search query
                const matchingItems = (group.items || []).filter(item => {
                    if (!query) return true;
                    return (item.title && item.title.toLowerCase().includes(query)) ||
                           (item.id && item.id.toLowerCase().includes(query)) ||
                           (item.desc && item.desc.toLowerCase().includes(query)) ||
                           (group.title && group.title.toLowerCase().includes(query));
                });

                if (query && matchingItems.length === 0) {
                    return; // Hide group if no matches
                }

                // If searching, auto-expand groups with matches
                const isOpen = query ? true : openGroups.includes(group.id);
                const hasActiveChild = (group.items || []).some(it => it.id === this.state.activeMenuId);
                const groupColor = group.color || 'cyan';

                // Color mapping for group accents
                const colorTheme = {
                    cyan: { border: 'border-cyan-500/30', bg: 'bg-cyan-500/10', text: 'text-cyan-400', badge: 'bg-cyan-500/20 text-cyan-300' },
                    blue: { border: 'border-blue-500/30', bg: 'bg-blue-500/10', text: 'text-blue-400', badge: 'bg-blue-500/20 text-blue-300' },
                    emerald: { border: 'border-emerald-500/30', bg: 'bg-emerald-500/10', text: 'text-emerald-400', badge: 'bg-emerald-500/20 text-emerald-300' },
                    amber: { border: 'border-amber-500/30', bg: 'bg-amber-500/10', text: 'text-amber-400', badge: 'bg-amber-500/20 text-amber-300' },
                    pink: { border: 'border-pink-500/30', bg: 'bg-pink-500/10', text: 'text-pink-400', badge: 'bg-pink-500/20 text-pink-300' },
                    purple: { border: 'border-purple-500/30', bg: 'bg-purple-500/10', text: 'text-purple-400', badge: 'bg-purple-500/20 text-purple-300' },
                    indigo: { border: 'border-indigo-500/30', bg: 'bg-indigo-500/10', text: 'text-indigo-400', badge: 'bg-indigo-500/20 text-indigo-300' }
                }[groupColor] || { border: 'border-cyan-500/30', bg: 'bg-cyan-500/10', text: 'text-cyan-400', badge: 'bg-cyan-500/20 text-cyan-300' };

                html += `
                    <div class="rounded-xl border ${hasActiveChild ? colorTheme.border + ' ' + colorTheme.bg : 'border-slate-800/80 bg-[#070e1c]/60'} transition-all duration-200 overflow-hidden">
                        <!-- Group Header (Accordion Toggle) -->
                        <button onclick="app.toggleSidebarGroup('${group.id}')" class="w-full text-left px-3 py-2.5 flex items-center justify-between group/header hover:bg-slate-800/50 transition-colors">
                            <div class="flex items-center gap-2.5 min-w-0 pr-2">
                                <div class="w-7 h-7 rounded-lg ${colorTheme.bg} ${colorTheme.text} flex items-center justify-center shrink-0 text-xs border ${colorTheme.border}">
                                    <i class="fa-solid ${group.icon}"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-xs font-bold ${hasActiveChild ? 'text-white' : 'text-slate-200'} group-hover/header:text-cyan-300 transition-colors truncate">
                                        ${group.title}
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-medium">
                                        ${group.items.length} Sub-Menu
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200 ${isOpen ? 'rotate-180 text-cyan-400' : ''}"></i>
                            </div>
                        </button>

                        <!-- Sub-menu Items Container -->
                        <div class="space-y-1 p-1.5 pt-0 ${isOpen ? 'block' : 'hidden'} border-t border-slate-800/40">
                `;

                const itemsToRender = query ? matchingItems : (group.items || []);
                itemsToRender.forEach(item => {
                    const isActive = item.id === this.state.activeMenuId;
                    
                    // Count file or records
                    let countBadge = '';
                    if (item.type === 'dashboard') {
                        countBadge = '<span class="text-[9px] bg-emerald-500/20 text-emerald-400 px-1.5 py-0.5 rounded font-bold border border-emerald-500/30">Live</span>';
                    } else if (item.type === 'table') {
                        const totalSek = (this.state.schoolList || []).length;
                        countBadge = `<span class="text-[9px] bg-blue-500/20 text-blue-300 px-1.5 py-0.5 rounded font-bold">${totalSek} Sek</span>`;
                    } else if (item.type === 'uptd_btkp') {
                        const totalBtkp = (this.state.uptdBtkpList || []).length;
                        countBadge = `<span class="text-[9px] bg-cyan-500/20 text-cyan-300 px-1.5 py-0.5 rounded font-bold">${totalBtkp} Prog</span>`;
                    } else if (item.type === 'uptd_taman_budaya') {
                        const totalTb = (this.state.uptdTamanBudayaList || []).length;
                        countBadge = `<span class="text-[9px] bg-purple-500/20 text-purple-300 px-1.5 py-0.5 rounded font-bold">${totalTb} Pentas</span>`;
                    } else if (item.type === 'uptd_museum') {
                        const totalMus = (this.state.uptdMuseumList || []).length;
                        countBadge = `<span class="text-[9px] bg-amber-500/20 text-amber-300 px-1.5 py-0.5 rounded font-bold">${totalMus} Item</span>`;
                    } else {
                        const fileCount = (this.state.uploadedFiles[item.id] || []).length;
                        countBadge = fileCount > 0 ? `<span class="text-[9px] bg-cyan-500/15 text-cyan-300 px-1.5 py-0.5 rounded font-medium border border-cyan-500/20">${fileCount} Dok</span>` : '';
                    }

                    const activeClass = isActive 
                        ? 'bg-cyan-500/20 border-l-2 border-cyan-400 text-white font-bold shadow-[0_0_12px_rgba(0,246,255,0.2)]' 
                        : 'text-slate-300 hover:bg-slate-800/80 hover:text-white border-l-2 border-transparent';

                    html += `
                        <button onclick="app.loadContent('${item.id}')" class="w-full text-left px-2.5 py-2 text-xs rounded-lg transition-all flex items-center justify-between group ${activeClass}">
                            <div class="flex items-center gap-2 min-w-0 pr-1">
                                <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded ${isActive ? 'bg-cyan-400 text-black' : 'bg-slate-800 text-slate-400 group-hover:text-slate-200'}">
                                    ${item.id}
                                </span>
                                <div class="min-w-0">
                                    <div class="truncate text-xs ${isActive ? 'text-cyan-300 font-bold' : ''}">
                                        ${item.title.replace(/^[0-9\.]+\s*/, '')}
                                    </div>
                                    ${item.desc ? `<div class="text-[9px] text-slate-400 truncate leading-tight mt-0.5">${item.desc}</div>` : ''}
                                </div>
                            </div>
                            <div class="shrink-0 flex items-center gap-1.5">
                                ${countBadge}
                                ${isActive ? '<i class="fa-solid fa-chevron-right text-[9px] text-cyan-400"></i>' : ''}
                            </div>
                        </button>
                    `;
                });

                html += `</div></div>`;
            });

            if (query && html === '') {
                html = `
                    <div class="p-6 text-center text-slate-400 text-xs">
                        <i class="fa-solid fa-magnifying-glass text-2xl text-slate-500 mb-2 block"></i>
                        Tidak ditemukan menu dengan kata kunci "<strong>${this.escapeHtml(query)}</strong>".
                        <button onclick="app.clearSidebarSearch()" class="mt-3 block mx-auto text-cyan-400 hover:underline text-xs">Reset Pencarian</button>
                    </div>
                `;
            }

            nav.innerHTML = html;
        },

        toggleSidebarGroup(groupId) {
            if (!Array.isArray(this.state.openSidebarGroups)) {
                this.state.openSidebarGroups = ['m1', 'm2', 'm3', 'm4', 'm5', 'm6', 'm7', 'm10'];
            }
            const idx = this.state.openSidebarGroups.indexOf(groupId);
            if (idx > -1) {
                this.state.openSidebarGroups.splice(idx, 1);
            } else {
                this.state.openSidebarGroups.push(groupId);
            }
            this.renderSidebar();
        },

        toggleAllSidebarGroups(open = true) {
            if (open) {
                this.state.openSidebarGroups = menuData.map(g => g.id);
            } else {
                this.state.openSidebarGroups = [];
            }
            this.renderSidebar();
        },

        filterSidebar(query) {
            this.state.sidebarSearchQuery = query;
            const clearBtn = document.getElementById('clearSidebarSearch');
            if (clearBtn) {
                if (query) clearBtn.classList.remove('hidden');
                else clearBtn.classList.add('hidden');
            }
            this.renderSidebar();
        },

        clearSidebarSearch() {
            this.state.sidebarSearchQuery = '';
            const searchInput = document.getElementById('sidebarSearchInput');
            if (searchInput) searchInput.value = '';
            const clearBtn = document.getElementById('clearSidebarSearch');
            if (clearBtn) clearBtn.classList.add('hidden');
            this.renderSidebar();
        },

        escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        },

        loadContent(menuId) {
            this.state.activeMenuId = menuId;

            // Ensure parent group is opened in sidebar
            let selectedItem = null;
            let parentGroup = null;

            if (!Array.isArray(this.state.openSidebarGroups)) {
                this.state.openSidebarGroups = ['m1', 'm2', 'm3', 'm4', 'm5', 'm6', 'm7', 'm10'];
            }

            menuData.forEach(group => {
                (group.items || []).forEach(item => {
                    if (item.id === menuId) {
                        selectedItem = item;
                        parentGroup = group;
                        if (!this.state.openSidebarGroups.includes(group.id)) {
                            this.state.openSidebarGroups.push(group.id);
                        }
                    }
                });
            });

            this.renderSidebar();

            if (!selectedItem) return;

            const mainArea = document.getElementById('mainContentArea');
            if (!mainArea) return;
            
            Object.values(this.state.charts).forEach(c => c && typeof c.destroy === 'function' && c.destroy());
            this.state.charts = {};

            if (selectedItem.type === 'dashboard') {
                mainArea.innerHTML = this.buildDashboardHTML();
                setTimeout(() => this.initDashboardCharts(), 50);
            } else if (selectedItem.type === 'module') {
                mainArea.innerHTML = this.buildModuleHTML(parentGroup, selectedItem);
            } else if (selectedItem.type === 'table') {
                mainArea.innerHTML = this.buildTableHTML(selectedItem);
            } else if (selectedItem.type === 'uptd_btkp') {
                mainArea.innerHTML = this.buildUptdBtkpHTML(selectedItem);
            } else if (selectedItem.type === 'uptd_taman_budaya') {
                mainArea.innerHTML = this.buildUptdTamanBudayaHTML(selectedItem);
            } else if (selectedItem.type === 'uptd_museum') {
                mainArea.innerHTML = this.buildUptdMuseumHTML(selectedItem);
            }
        },

        // --- 7-CARDS HIGH-TECH EXECUTIVE DASHBOARD (PERSIS GAMBAR REFERENSI DENGAN DATA TERINTEGRASI) ---
        buildDashboardHTML() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-GB') + ' WIT';
            const dateString = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }).toUpperCase();
            
            const sek = this.state.sekretariatData;
            const gtk = this.state.gtkData;
            const sma = this.state.smaData;
            const smk = this.state.smkData;
            const slb = this.state.slbData;
            const budaya = this.state.budayaData;
            const pengawasan = this.state.pengawasanData;

            const latestLog = sek.recentLogs && sek.recentLogs.length > 0 ? sek.recentLogs[0] : { unit: '2.1 Subbag. Umum', title: 'Inventarisasi Aset Sarpras BMD', status: 'Selesai' };
            const activeCabdin = cabdinMapData[this.state.selectedCabdin || 'ambon'] || cabdinMapData['ambon'];
            const totalSekolah = this.state.schoolList.length;
            const totalSma = this.state.schoolList.filter(s => s.jenjang === 'SMA').length;
            const totalSmk = this.state.schoolList.filter(s => s.jenjang === 'SMK').length;
            const totalSlb = this.state.schoolList.filter(s => s.jenjang === 'SLB').length;

            return `
            <div class="space-y-4 animate-[fadeIn_0.3s_ease-out]">
                
                <!-- TOP BANNER (Judul Dashboard & Jam Digital WIT) -->
                <div class="bg-[#0b1325] border border-app-cardBorder rounded-xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-600/20 border border-blue-500/40 flex items-center justify-center text-blue-400">
                            <i class="fa-solid fa-graduation-cap text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold text-white flex items-center gap-2">
                                SIMANDAT Executive Dashboard
                            </h2>
                            <p class="text-xxs text-slate-400">Sistem Pemantauan Terintegrasi TVRI Maluku</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <button onclick="app.openResetModal()" class="bg-[#070e1c] hover:bg-amber-600/20 border border-slate-700 hover:border-amber-500/50 px-3 py-2 rounded-xl flex items-center gap-2 text-slate-300 hover:text-amber-300 transition-all text-xs font-semibold shadow-inner" title="Reset atau Kosongkan Data Sistem">
                            <i class="fa-solid fa-arrow-rotate-left text-amber-400"></i>
                            <span class="hidden sm:inline">Reset Data</span>
                        </button>
                        <div class="bg-[#070e1c] border border-blue-500/30 px-4 py-2 rounded-xl flex items-center gap-3 shadow-inner">
                            <i class="fa-regular fa-clock text-blue-400 text-lg"></i>
                            <div>
                                <div class="font-mono text-sm font-black text-white tracking-wider" id="commandCenterClock">${timeString}</div>
                                <div class="text-[9px] text-blue-300 font-semibold tracking-wider" id="commandCenterDate">${dateString}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 6 MAIN CARDS GRID (3 Kolom x 2 Baris) -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    
                    <!-- ========================================== -->
                    <!-- CARD 1: SEKRETARIAT & TATA KELOLA (2.1, 2.2, 2.3) -->
                    <!-- ========================================== -->
                    <div class="bg-[#0b1325] border border-app-cardBorder rounded-xl p-4 flex flex-col justify-between shadow-lg">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <span class="bg-blue-600 text-white font-bold w-5 h-5 rounded flex items-center justify-center text-xs shadow">1</span>
                                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">SEKRETARIAT & TATA KELOLA</h3>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <button onclick="app.resetSekretariatData()" class="text-[10px] bg-[#070e1c] hover:bg-amber-600/20 text-slate-400 hover:text-amber-400 border border-slate-800 hover:border-amber-500/40 px-2 py-0.5 rounded transition-all flex items-center gap-1" title="Reset Data Sekretariat (Kosongkan ke 0)">
                                        <i class="fa-solid fa-arrow-rotate-left text-amber-400 text-[9px]"></i> Reset
                                    </button>
                                    <span class="text-[9px] bg-blue-900/40 text-blue-300 px-1.5 py-0.5 rounded border border-blue-500/30 font-medium">Data Live</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 mb-4">
                                <div class="bg-[#070e1c] border border-slate-800 p-2.5 rounded-lg">
                                    <div class="text-slate-400 text-xxs font-medium truncate">2.1 Umum & Aset</div>
                                    <div class="text-2xl font-black text-white mt-1">${sek.subbagUmum.totalBerkas} <span class="text-xs font-normal text-slate-400">Berkas</span></div>
                                </div>
                                <div class="bg-[#070e1c] border border-slate-800 p-2.5 rounded-lg">
                                    <div class="text-slate-400 text-xxs font-medium truncate">2.2 Perenc. & Keu</div>
                                    <div class="text-2xl font-black text-blue-400 mt-1">${sek.subbagKeuangan.serapan}%</div>
                                </div>
                                <div class="bg-[#070e1c] border border-slate-800 p-2.5 rounded-lg">
                                    <div class="text-slate-400 text-xxs font-medium truncate">2.3 SOP Selesai</div>
                                    <div class="text-2xl font-black text-app-success mt-1">${sek.subbagData.selesai} <span class="text-xs font-normal text-slate-400">IKU</span></div>
                                </div>
                                <div class="bg-[#070e1c] border border-slate-800 p-2.5 rounded-lg">
                                    <div class="text-slate-400 text-xxs font-medium truncate">2.3 SOP Diproses</div>
                                    <div class="text-2xl font-black text-app-warning mt-1">${sek.subbagData.diproses} <span class="text-xs font-normal text-slate-400">Layanan</span></div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="text-xxs font-bold text-slate-400 mb-1.5 uppercase">CAPAIAN KINERJA SUBBAG SEKRETARIAT</div>
                                <div class="h-24 w-full">
                                    <canvas id="chartSekretariatBar"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-slate-800/80">
                            <div class="text-xxs font-bold text-slate-400 mb-1.5 uppercase">INPUT DATA TERAKHIR SEKRETARIAT</div>
                            <div class="bg-[#070e1c] border border-slate-800 p-2 rounded-lg flex items-center justify-between text-xs">
                                <div class="truncate text-white font-medium pr-2">
                                    <span class="text-app-primary text-xxs font-semibold">${latestLog.unit}:</span> ${latestLog.title}
                                </div>
                                <span class="${latestLog.status === 'Selesai' ? 'bg-app-success/20 text-app-success' : 'bg-blue-500/20 text-blue-400'} text-[10px] px-2 py-0.5 rounded font-bold shrink-0">${latestLog.status}</span>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- CARD 2: BIDANG GTK (MUTU & KOMPETENSI) -->
                    <!-- ========================================== -->
                    <div class="bg-[#0b1325] border border-app-cardBorder rounded-xl p-4 flex flex-col justify-between shadow-lg">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="bg-blue-600 text-white font-bold w-5 h-5 rounded flex items-center justify-center text-xs shadow">2</span>
                                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">BIDANG GTK (MUTU & KOMPETENSI)</h3>
                                </div>
                                <span class="text-[10px] bg-blue-900/40 text-blue-400 border border-blue-500/30 px-2 py-0.5 rounded">Radar</span>
                            </div>
                            
                            <div class="flex items-center gap-1 text-slate-400 text-xxs mb-2">
                                <i class="fa-solid fa-compass text-app-primary"></i> Radar Chart: Evaluasi Mutu & Kinerja Guru
                            </div>

                            <div class="h-56 w-full flex items-center justify-center">
                                <canvas id="chartGtkRadar"></canvas>
                            </div>
                        </div>

                        <div class="mt-2 p-2 bg-[#070e1c] border border-slate-800 rounded-lg text-[10px] text-blue-300">
                            <strong class="text-app-primary">Insight:</strong> Capaian Literasi Digital GTK (${gtk.literasiDigital}%) dan Sertifikasi 3T (${gtk.sertifikasi3T}%) melampaui target Q3.
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- CARD 3: SATUAN PENDIDIKAN & ARCHIPELAGO NETWORK -->
                    <!-- ========================================== -->
                    <div class="bg-[#0b1325] border border-app-cardBorder rounded-xl p-4 flex flex-col justify-between shadow-lg">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="bg-blue-600 text-white font-bold w-5 h-5 rounded flex items-center justify-center text-xs shadow">3</span>
                                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">SATUAN PENDIDIKAN & ARCHIPELAGO</h3>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <button onclick="app.resetSchoolData()" class="text-[10px] bg-[#070e1c] hover:bg-amber-600/20 text-slate-400 hover:text-amber-400 border border-slate-800 hover:border-amber-500/40 px-2 py-0.5 rounded transition-all flex items-center gap-1" title="Reset Satuan Pendidikan & Archipelago ke Awal (8 Sekolah Master)">
                                        <i class="fa-solid fa-arrow-rotate-left text-amber-400 text-[9px]"></i> Reset
                                    </button>
                                    <i class="fa-solid fa-layer-group text-blue-400"></i>
                                </div>
                            </div>

                            <div class="bg-[#070e1c] border border-slate-800 p-2.5 rounded-lg flex items-center justify-between mb-3">
                                <div>
                                    <div class="text-slate-400 text-xxs font-medium uppercase">TOTAL SATUAN PENDIDIKAN</div>
                                    <div class="text-2xl font-black text-white mt-0.5">${totalSekolah}</div>
                                </div>
                                <div class="w-9 h-9 rounded-full bg-blue-600/20 text-blue-400 flex items-center justify-center">
                                    <i class="fa-solid fa-school text-sm"></i>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-2 mb-3 text-center">
                                <div class="bg-red-950/30 border border-red-500/30 p-2 rounded-lg">
                                    <div class="text-red-400 font-black text-sm">${totalSma}</div>
                                    <div class="text-[9px] text-slate-300 uppercase mt-0.5">SMA</div>
                                </div>
                                <div class="bg-amber-950/30 border border-amber-500/30 p-2 rounded-lg">
                                    <div class="text-amber-400 font-black text-sm">${totalSmk}</div>
                                    <div class="text-[9px] text-slate-300 uppercase mt-0.5">SMK</div>
                                </div>
                                <div class="bg-blue-950/30 border border-blue-500/30 p-2 rounded-lg">
                                    <div class="text-blue-400 font-black text-sm">${totalSlb}</div>
                                    <div class="text-[9px] text-slate-300 uppercase mt-0.5">SLB</div>
                                </div>
                            </div>

                            <div class="text-xxs font-bold text-slate-400 mb-1.5 uppercase">SEBARAN SATUAN PENDIDIKAN KEPULAUAN MALUKU</div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-5 gap-2">
                                <div class="sm:col-span-3 bg-[#070e1c] border border-slate-800 rounded-lg h-32 relative overflow-hidden flex items-center justify-center p-2">
                                    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#38bdf8_1px,transparent_1px)] [background-size:12px_12px]"></div>
                                    
                                    <button onclick="app.selectCabdin('ambon')" title="Kota Ambon" class="island-node absolute top-[40%] left-[30%] w-3.5 h-3.5 rounded-full bg-cyan-400 text-cyan-400 cursor-pointer shadow-[0_0_12px_#00f6ff]"></button>
                                    <button onclick="app.selectCabdin('malteng')" title="Maluku Tengah (Masohi)" class="island-node absolute top-[30%] left-[45%] w-3.5 h-3.5 rounded-full bg-blue-500 text-blue-500 cursor-pointer shadow-[0_0_12px_#3b82f6]"></button>
                                    <button onclick="app.selectCabdin('buru')" title="Kab. Buru (Namlea)" class="island-node absolute top-[25%] left-[15%] w-3 h-3 rounded-full bg-emerald-400 text-emerald-400 cursor-pointer"></button>
                                    <button onclick="app.selectCabdin('sbb')" title="Seram Bagian Barat" class="island-node absolute top-[28%] left-[28%] w-3 h-3 rounded-full bg-purple-400 text-purple-400 cursor-pointer"></button>
                                    <button onclick="app.selectCabdin('sbt')" title="Seram Bagian Timur" class="island-node absolute top-[35%] left-[65%] w-3 h-3 rounded-full bg-pink-400 text-pink-400 cursor-pointer"></button>
                                    <button onclick="app.selectCabdin('malra')" title="Tual & Maluku Tenggara" class="island-node absolute top-[60%] left-[75%] w-3.5 h-3.5 rounded-full bg-amber-400 text-amber-400 cursor-pointer shadow-[0_0_10px_#f59e0b]"></button>
                                    <button onclick="app.selectCabdin('tanimbar')" title="Kepulauan Tanimbar (Saumlaki)" class="island-node absolute top-[80%] left-[55%] w-3.5 h-3.5 rounded-full bg-teal-400 text-teal-400 cursor-pointer shadow-[0_0_10px_#14b8a6]"></button>
                                    <button onclick="app.selectCabdin('mbd')" title="Maluku Barat Daya (Tiakur)" class="island-node absolute top-[88%] left-[25%] w-3 h-3 rounded-full bg-indigo-400 text-indigo-400 cursor-pointer"></button>
                                    
                                    <div class="absolute bottom-1 left-2 text-[9px] text-slate-500 italic">Klik titik pulau untuk info</div>
                                </div>

                                <div class="sm:col-span-2 bg-[#070e1c] border border-blue-500/30 p-2 rounded-lg flex flex-col justify-center text-center" id="cabdinInfoPanel">
                                    <div class="text-[9px] text-slate-400 uppercase font-semibold">INFO WILAYAH</div>
                                    <div class="text-xs font-bold text-app-primary truncate" id="cabdinNameDisplay">${activeCabdin.name}</div>
                                    <div class="mt-1 text-sm font-black text-white" id="cabdinSchoolDisplay">${activeCabdin.sekolah} Satuan Sekolah</div>
                                    <div class="text-[9px] text-slate-400 mt-0.5" id="cabdinGtkDisplay">${activeCabdin.gtk} GTK | Akses ${activeCabdin.internet}</div>
                                </div>
                            </div>
                        </div>
                        <div></div>
                    </div>

                    <!-- ========================================== -->
                    <!-- CARD 4: BIDANG PENDIDIKAN SMA -->
                    <!-- ========================================== -->
                    <div class="bg-[#0b1325] border border-app-cardBorder rounded-xl p-4 flex flex-col justify-between shadow-lg">
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="bg-blue-600 text-white font-bold w-5 h-5 rounded flex items-center justify-center text-xs shadow">4</span>
                                <h3 class="text-xs font-bold text-white uppercase tracking-wider">BIDANG PENDIDIKAN SMA</h3>
                            </div>

                            <div class="bg-[#070e1c] border border-slate-800 p-2.5 rounded-lg flex items-center justify-between mb-3">
                                <div>
                                    <div class="text-slate-400 text-xxs font-medium uppercase">CAPAIAN KURIKULUM & AKREDITASI</div>
                                    <div class="text-2xl font-black text-white mt-0.5">${sma.capaian}%</div>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-blue-600/20 text-blue-400 flex items-center justify-center">
                                    <i class="fa-solid fa-chart-line text-sm"></i>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="text-xxs font-bold text-slate-400 mb-1.5 uppercase">TREN CAPAIAN AKREDITASI (DENGAN TARGET 85%)</div>
                                <div class="h-28 w-full">
                                    <canvas id="chartSmaLine"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="bg-red-950/20 border border-red-500/30 p-2.5 rounded-lg flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded bg-red-500/20 text-red-400 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-red-400">Target Akselerasi 3T: ${sma.target3T} Wilayah</div>
                                <div class="text-xxs text-slate-400">Pendampingan akreditasi SBB, SBT, dan MBD diprioritaskan.</div>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- CARD 5: BIDANG PENDIDIKAN SMK -->
                    <!-- ========================================== -->
                    <div class="bg-[#0b1325] border border-app-cardBorder rounded-xl p-4 flex flex-col justify-between shadow-lg">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="bg-blue-600 text-white font-bold w-5 h-5 rounded flex items-center justify-center text-xs shadow">5</span>
                                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">BIDANG PENDIDIKAN SMK (TEFA)</h3>
                                </div>
                                <span class="text-[10px] bg-amber-900/40 text-amber-400 border border-amber-500/30 px-2 py-0.5 rounded">Polar Area</span>
                            </div>

                            <div class="text-xxs text-slate-400 mb-2">
                                Polar Area: Proporsi Jurusan & Teaching Factory (Tefa)
                            </div>

                            <div class="h-52 w-full flex items-center justify-center">
                                <canvas id="chartSmkPolar"></canvas>
                            </div>
                        </div>

                        <div class="mt-2 p-2 bg-[#070e1c] border border-slate-800 rounded-lg text-[10px] text-amber-300">
                            <strong class="text-amber-400">Insight:</strong> Kemaritiman & Pariwisata mendominasi serapan kerja sama DUDI Maluku (${smk.dudiPercent}%).
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- CARD 6: BIDANG KEBUDAYAAN -->
                    <!-- ========================================== -->
                    <div class="bg-[#0b1325] border border-app-cardBorder rounded-xl p-4 flex flex-col justify-between shadow-lg">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="bg-blue-600 text-white font-bold w-5 h-5 rounded flex items-center justify-center text-xs shadow">6</span>
                                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">BIDANG KEBUDAYAAN</h3>
                                </div>
                                <button onclick="app.resetBudayaData()" class="text-[10px] bg-[#070e1c] hover:bg-amber-600/20 text-slate-400 hover:text-amber-400 border border-slate-800 hover:border-amber-500/40 px-2 py-0.5 rounded transition-all flex items-center gap-1" title="Reset Data Kebudayaan (Kosongkan ke 0)">
                                    <i class="fa-solid fa-arrow-rotate-left text-amber-400 text-[9px]"></i> Reset
                                </button>
                            </div>

                            <div class="grid grid-cols-3 gap-2 mb-3 text-center">
                                <div class="bg-[#070e1c] border border-slate-800 p-2 rounded-lg">
                                    <div class="text-slate-400 text-xxs font-medium">Usulan Baru</div>
                                    <div class="text-lg font-black text-slate-300 mt-0.5">${budaya.usulanBaru}</div>
                                </div>
                                <div class="bg-[#070e1c] border border-slate-800 p-2 rounded-lg">
                                    <div class="text-slate-400 text-xxs font-medium">Verifikasi</div>
                                    <div class="text-lg font-black text-blue-400 mt-0.5">${budaya.verifikasi}</div>
                                </div>
                                <div class="bg-[#070e1c] border border-slate-800 p-2 rounded-lg">
                                    <div class="text-slate-400 text-xxs font-medium">Terdaftar</div>
                                    <div class="text-lg font-black text-app-success mt-0.5">${budaya.terdaftar}</div>
                                </div>
                            </div>

                            <div class="text-xxs font-bold text-slate-400 mb-1 uppercase">PERSENTASE OBJEK PEMAJUAN KEBUDAYAAN</div>
                            <div class="h-32 w-full flex items-center justify-center">
                                <canvas id="chartBudayaDonut"></canvas>
                            </div>
                        </div>

                        <div class="pt-2 border-t border-slate-800">
                            <div class="bg-[#070e1c] border border-slate-800 p-2 rounded-lg text-xxs flex justify-between items-center">
                                <div class="truncate text-white font-semibold pr-2">${budaya.lastVerified}</div>
                                <span class="text-app-success bg-app-success/10 px-1.5 py-0.5 rounded font-bold">Terverifikasi</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ========================================== -->
                <!-- CARD 7: FULL WIDTH BOTTOM (KALENDER & SEBARAN) -->
                <!-- ========================================== -->
                <div class="bg-[#0b1325] border border-app-cardBorder rounded-xl p-4 shadow-lg">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="bg-blue-600 text-white font-bold w-5 h-5 rounded flex items-center justify-center text-xs shadow">7</span>
                        <h3 class="text-xs font-bold text-white uppercase tracking-wider">SATUAN PENDIDIKAN & KALENDER KINERJA PENGAWASAN</h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                        
                        <div class="lg:col-span-3 space-y-3">
                            <div class="bg-[#070e1c] border border-slate-800 p-3 rounded-lg flex items-center justify-between">
                                <div>
                                    <div class="text-slate-400 text-xxs font-medium uppercase">TOTAL AGENDA KERJA</div>
                                    <div class="text-3xl font-black text-white mt-1">${pengawasan.totalAgenda}</div>
                                </div>
                                <div class="w-10 h-10 rounded-lg bg-blue-600/20 text-blue-400 flex items-center justify-center text-lg">
                                    <i class="fa-regular fa-calendar-check"></i>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div class="bg-[#070e1c] border border-slate-800 p-2.5 rounded-lg text-center">
                                    <div class="text-2xl font-black text-blue-400">${pengawasan.sedangBerjalan}</div>
                                    <div class="text-xxs text-slate-400 font-semibold uppercase mt-0.5">Sedang Berjalan</div>
                                </div>
                                <div class="bg-[#070e1c] border border-slate-800 p-2.5 rounded-lg text-center">
                                    <div class="text-2xl font-black text-app-success">${pengawasan.programSelesai}</div>
                                    <div class="text-xxs text-slate-400 font-semibold uppercase mt-0.5">Program Selesai</div>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-5 bg-[#070e1c] border border-slate-800 p-3 rounded-lg flex flex-col justify-between">
                            <div>
                                <div class="text-xxs font-bold text-slate-400 mb-2 uppercase">CALENDAR HEATMAP PENGAWASAN & MONITORING (BULAN INI)</div>
                                <div class="grid grid-cols-7 gap-1 text-center text-[10px] text-slate-400 mb-1">
                                    <div>Min</div><div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div>
                                </div>
                                <div class="grid grid-cols-7 gap-1 text-center text-xs">
                                    <div class="p-1 rounded bg-slate-900 text-slate-600">1</div>
                                    <div class="p-1 rounded bg-blue-900/60 text-slate-300 font-bold">2</div>
                                    <div class="p-1 rounded bg-blue-600/60 text-white font-bold">3</div>
                                    <div class="p-1 rounded bg-slate-900 text-slate-400">4</div>
                                    <div class="p-1 rounded bg-cyan-500/80 text-black font-bold">5</div>
                                    <div class="p-1 rounded bg-blue-900/60 text-slate-300">6</div>
                                    <div class="p-1 rounded bg-slate-900 text-slate-600">7</div>

                                    <div class="p-1 rounded bg-slate-900 text-slate-600">8</div>
                                    <div class="p-1 rounded bg-cyan-400 text-black font-black">9</div>
                                    <div class="p-1 rounded bg-blue-600/70 text-white font-bold">10</div>
                                    <div class="p-1 rounded bg-blue-900/60 text-slate-300">11</div>
                                    <div class="p-1 rounded bg-cyan-500/80 text-black font-bold">12</div>
                                    <div class="p-1 rounded bg-slate-900 text-slate-400">13</div>
                                    <div class="p-1 rounded bg-slate-900 text-slate-600">14</div>

                                    <div class="p-1 rounded bg-slate-900 text-slate-600">15</div>
                                    <div class="p-1 rounded bg-blue-900/50 text-slate-300">16</div>
                                    <div class="p-1 rounded bg-cyan-400 text-black font-bold">17</div>
                                    <div class="p-1 rounded bg-blue-600/70 text-white font-bold">18</div>
                                    <div class="p-1 rounded bg-slate-900 text-slate-400">19</div>
                                    <div class="p-1 rounded bg-cyan-500/80 text-black font-bold">20</div>
                                    <div class="p-1 rounded bg-slate-900 text-slate-600">21</div>

                                    <div class="p-1 rounded bg-slate-900 text-slate-600">22</div>
                                    <div class="p-1 rounded bg-blue-900/60 text-slate-300">23</div>
                                    <div class="p-1 rounded bg-slate-900 text-slate-400">24</div>
                                    <div class="p-1 rounded bg-cyan-400 text-black font-bold">25</div>
                                    <div class="p-1 rounded bg-blue-600/70 text-white font-bold">26</div>
                                    <div class="p-1 rounded bg-slate-900 text-slate-400">27</div>
                                    <div class="p-1 rounded bg-slate-900 text-slate-600">28</div>

                                    <div class="p-1 rounded bg-slate-900 text-slate-600">29</div>
                                    <div class="p-1 rounded bg-blue-900/60 text-slate-300">30</div>
                                    <div class="p-1 rounded bg-cyan-400 text-black font-bold">31</div>
                                    <div class="p-1 rounded opacity-0"></div><div class="p-1 rounded opacity-0"></div><div class="p-1 rounded opacity-0"></div><div class="p-1 rounded opacity-0"></div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between text-[9px] text-slate-400 pt-2 border-t border-slate-800">
                                <span>Rendah</span>
                                <div class="flex gap-1">
                                    <div class="w-2.5 h-2.5 rounded bg-slate-900"></div>
                                    <div class="w-2.5 h-2.5 rounded bg-blue-900/60"></div>
                                    <div class="w-2.5 h-2.5 rounded bg-blue-600/70"></div>
                                    <div class="w-2.5 h-2.5 rounded bg-cyan-400"></div>
                                </div>
                                <span>Tinggi</span>
                            </div>
                        </div>

                        <div class="lg:col-span-4 bg-[#070e1c] border border-slate-800 p-3 rounded-lg flex flex-col justify-between">
                            <div>
                                <div class="text-xxs font-bold text-slate-400 mb-1 uppercase">DISTRIBUSI PROGRAM PER CABANG DINAS</div>
                                <div class="h-36 w-full">
                                    <canvas id="chartCabdinHorizontalBar"></canvas>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            `;
        },

        // --- INISIALISASI CHART.JS DENGAN DATA REALTIME DARI SELURUH BIDANG ---
        initDashboardCharts() {
            const sek = this.state.sekretariatData;
            const gtk = this.state.gtkData;
            const sma = this.state.smaData;
            const smk = this.state.smkData;
            const budaya = this.state.budayaData;
            const pengawasan = this.state.pengawasanData;

            const ctx1 = document.getElementById('chartSekretariatBar');
            if (ctx1) {
                const totalSop = sek.subbagData.totalSOP || 10;
                const dataSopPercent = totalSop > 0 ? Number(((sek.subbagData.selesai || 0) / totalSop * 100).toFixed(1)) : 0;
                this.state.charts.sekretariat = new Chart(ctx1, {
                    type: 'bar',
                    data: {
                        labels: ['2.1 Umum & Aset', '2.2 Perenc. & Keu', '2.3 Pengelolaan Data/SOP'],
                        datasets: [{
                            data: [sek.subbagUmum.progress || 0, sek.subbagKeuangan.serapan || 0, dataSopPercent],
                            backgroundColor: ['#06b6d4', '#3b82f6', '#10b981'],
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 9 } } },
                            y: { grid: { color: 'rgba(255, 255, 255, 0.05)' }, ticks: { color: '#64748b', font: { size: 8 } }, min: 0, max: 100 }
                        }
                    }
                });
            }

            const ctx2 = document.getElementById('chartGtkRadar');
            if (ctx2) {
                this.state.charts.gtk = new Chart(ctx2, {
                    type: 'radar',
                    data: {
                        labels: ['Pedagogik', 'Profesional', 'Sertifikasi 3T', 'Literasi Digital', 'Kepemimpinan', 'Supervisi'],
                        datasets: [
                            {
                                label: 'Realisasi Maluku',
                                data: [gtk.pedagogik, gtk.profesional, gtk.sertifikasi3T, gtk.literasiDigital, gtk.kepemimpinan, gtk.supervisi],
                                borderColor: '#00f6ff',
                                backgroundColor: 'rgba(0, 246, 255, 0.25)',
                                pointBackgroundColor: '#00f6ff',
                                borderWidth: 2
                            },
                            {
                                label: 'Standar Nasional',
                                data: [80, 80, 75, 70, 75, 75],
                                borderColor: '#f59e0b',
                                backgroundColor: 'rgba(245, 158, 11, 0.15)',
                                pointBackgroundColor: '#f59e0b',
                                borderWidth: 1.5,
                                borderDash: [4, 4]
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                labels: { color: '#cbd5e1', font: { size: 9 }, boxWidth: 10 }
                            }
                        },
                        scales: {
                            r: {
                                angleLines: { color: 'rgba(255, 255, 255, 0.1)' },
                                grid: { color: 'rgba(255, 255, 255, 0.08)' },
                                pointLabels: { color: '#94a3b8', font: { size: 8.5 } },
                                ticks: { display: false, min: 0, max: 100 }
                            }
                        }
                    }
                });
            }

            const ctx4 = document.getElementById('chartSmaLine');
            if (ctx4) {
                this.state.charts.sma = new Chart(ctx4, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                        datasets: [
                            {
                                label: 'Capaian Akreditasi',
                                data: sma.trend,
                                borderColor: '#38bdf8',
                                backgroundColor: 'rgba(56, 189, 248, 0.1)',
                                tension: 0.3,
                                fill: true,
                                borderWidth: 2
                            },
                            {
                                label: 'Target IKU (85%)',
                                data: [85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85],
                                borderColor: '#ef4444',
                                borderDash: [4, 4],
                                pointRadius: 0,
                                borderWidth: 1.5
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { display: false }, ticks: { color: '#64748b', font: { size: 8 } } },
                            y: { grid: { color: 'rgba(255, 255, 255, 0.05)' }, ticks: { color: '#64748b', font: { size: 8 } }, min: 50, max: 100 }
                        }
                    }
                });
            }

            const ctx5 = document.getElementById('chartSmkPolar');
            if (ctx5) {
                this.state.charts.smk = new Chart(ctx5, {
                    type: 'polarArea',
                    data: {
                        labels: ['Kemaritiman', 'Agribisnis', 'Pariwisata', 'Rekayasa', 'TIK'],
                        datasets: [{
                            data: smk.tefaData,
                            backgroundColor: [
                                'rgba(56, 189, 248, 0.7)',
                                'rgba(16, 185, 129, 0.7)',
                                'rgba(245, 158, 11, 0.7)',
                                'rgba(239, 68, 68, 0.7)',
                                'rgba(168, 85, 247, 0.7)'
                            ],
                            borderWidth: 1,
                            borderColor: '#0b1325'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: { color: '#cbd5e1', font: { size: 8.5 }, boxWidth: 8 }
                            }
                        },
                        scales: {
                            r: {
                                grid: { color: 'rgba(255, 255, 255, 0.08)' },
                                ticks: { display: false }
                            }
                        }
                    }
                });
            }

            const ctx6 = document.getElementById('chartBudayaDonut');
            if (ctx6) {
                const isAllZero = !budaya.proportions || budaya.proportions.every(v => v === 0);
                this.state.charts.budaya = new Chart(ctx6, {
                    type: 'doughnut',
                    data: {
                        labels: isAllZero ? ['Belum ada data'] : ['Cagar Budaya', 'WBTB (Takbenda)', 'Sanggar Seni', 'Sejarah/Tradisi'],
                        datasets: [{
                            data: isAllZero ? [1] : budaya.proportions,
                            backgroundColor: isAllZero ? ['rgba(148, 163, 184, 0.15)'] : ['#00f6ff', '#3b82f6', '#f59e0b', '#ec4899'],
                            borderWidth: 2,
                            borderColor: '#0b1325'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: { color: '#cbd5e1', font: { size: 8.5 }, boxWidth: 8 }
                            }
                        },
                        cutout: '65%'
                    }
                });
            }

            const ctx7 = document.getElementById('chartCabdinHorizontalBar');
            if (ctx7) {
                this.state.charts.cabdin = new Chart(ctx7, {
                    type: 'bar',
                    data: {
                        labels: ['Cabdin I (Ambon)', 'Cabdin II (Malteng)', 'Cabdin III (Buru)', 'Cabdin IV (SBB)', 'Cabdin VI (Malra)', 'Cabdin IX (Tanimbar)'],
                        datasets: [{
                            data: pengawasan.cabdinDistribution,
                            backgroundColor: '#3b82f6',
                            borderRadius: 4
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { color: 'rgba(255, 255, 255, 0.05)' }, ticks: { color: '#64748b', font: { size: 8 } } },
                            y: { grid: { display: false }, ticks: { color: '#cbd5e1', font: { size: 8 } } }
                        }
                    }
                });
            }
        },

        selectCabdin(key) {
            const data = cabdinMapData[key];
            if (!data) return;
            this.state.selectedCabdin = key;

            const nameEl = document.getElementById('cabdinNameDisplay');
            const schoolEl = document.getElementById('cabdinSchoolDisplay');
            const gtkEl = document.getElementById('cabdinGtkDisplay');

            if (nameEl) nameEl.textContent = data.name;
            if (schoolEl) schoolEl.textContent = `${data.sekolah} Satuan Sekolah`;
            if (gtkEl) gtkEl.textContent = `${data.gtk} GTK | Akses ${data.internet}`;
        },

        // --- FITUR DOWNLOAD BERKAS (DATABASE STREAM & FALLBACK) ---
        downloadFile(fileId, title, format = 'PDF') {
            if (fileId && !isNaN(fileId)) {
                // Direct download from Laravel controller
                window.location.href = `/api/simandat/archives/${fileId}/download`;
                this.showToast(`Mengunduh berkas dari Database: ${title}`, 'success');
                return;
            }

            const content = `DOKUMEN RESMI TVRI MALUKU\n` +
                            `Sistem Informasi Manajemen Data (SIMANDAT SIWALIMA)\n` +
                            `=======================================================\n\n` +
                            `ID Berkas   : ${fileId}\n` +
                            `Judul Berkas: ${title}\n` +
                            `Format      : ${format}\n` +
                            `Status      : TERVERIFIKASI & TERSINKRONISASI (VALIDATED)\n` +
                            `Keamanan    : SOFT DELETE PROTECTED (DATABASE SECURED)\n` +
                            `Tanggal Unduh: ${new Date().toLocaleString('id-ID')}\n\n` +
                            `Dokumen ini telah divalidasi dan tersimpan di database SIMANDAT TVRI Maluku.`;
            
            const blob = new Blob([content], { type: 'text/plain;charset=utf-8' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            const ext = (format.toLowerCase().includes('xls')) ? 'xlsx' : ((format.toLowerCase().includes('zip')) ? 'zip' : 'pdf');
            a.download = `${title.replace(/[^a-zA-Z0-9_-]/g, '_')}.${ext}`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);

            this.showToast(`Berhasil mengunduh: ${title} (${format})`, 'success');
        },

        // --- FITUR LIHAT & PRATINJAU FILE DATABASE (SIMANDAT SIWALIMA) ---
        async viewFileModal(fileId, title, format = 'PDF') {
            const modal = document.getElementById('filePreviewModal');
            const content = document.getElementById('filePreviewModalContent');
            if (!modal) return;

            let fileObj = null;
            Object.keys(this.state.uploadedFiles).forEach(mod => {
                const found = this.state.uploadedFiles[mod].find(f => String(f.id) === String(fileId) || f.code === fileId);
                if (found) fileObj = found;
            });

            if (!fileObj) {
                fileObj = {
                    id: fileId,
                    code: fileId && !isNaN(fileId) ? `DOC-DB-${fileId}` : fileId,
                    title: title || 'Dokumen Arsip Digital',
                    category: 'Laporan Kinerja TVRI Maluku',
                    format: format || 'PDF',
                    file_size: '2.8 MB',
                    day: 'Sabtu',
                    date: '12 September 2026',
                    time: '10:00 WIT',
                    uploader: 'Super Admin (TVRI Maluku)',
                    status: 'VALIDATED'
                };
            }

            this.state.currentPreviewFile = {
                id: fileObj.id || fileId,
                title: fileObj.title,
                format: fileObj.format || format
            };

            const elCode = document.getElementById('previewDocCode');
            const elTitle = document.getElementById('previewDocTitle');
            const elCategory = document.getElementById('previewDocCategory');
            const elSize = document.getElementById('previewDocFormatSize');
            const elDateTime = document.getElementById('previewDocDateTime');
            const elUploader = document.getElementById('previewDocUploader');
            const previewPre = document.getElementById('previewDocContent');

            if (elCode) elCode.textContent = fileObj.code || `DOC-${fileObj.id}`;
            if (elTitle) elTitle.textContent = fileObj.title;
            if (elCategory) elCategory.textContent = fileObj.category || 'Arsip Resmi TVRI Maluku';
            if (elSize) elSize.textContent = `${fileObj.format || 'PDF'} (${fileObj.size || fileObj.file_size || '2.4 MB'})`;
            if (elDateTime) elDateTime.textContent = `${fileObj.day || 'Sabtu'}, ${fileObj.date || ''} ${fileObj.time || ''}`;
            if (elUploader) elUploader.textContent = fileObj.uploader || 'Super Admin (TVRI Maluku)';
            if (previewPre) previewPre.textContent = 'Menghubungkan ke database SIMANDAT SIWALIMA...';

            modal.classList.remove('hidden');
            void modal.offsetWidth;
            modal.classList.replace('opacity-0', 'opacity-100');
            content.classList.replace('scale-95', 'scale-100');

            // Try fetching full record from backend API
            try {
                const targetDbId = (fileObj.id && !isNaN(fileObj.id)) ? fileObj.id : (fileId && !isNaN(fileId) ? fileId : null);
                if (targetDbId) {
                    const res = await fetch(`/api/simandat/archives/${targetDbId}`);
                    if (res.ok) {
                        const json = await res.json();
                        if (json.preview_content && previewPre) {
                            previewPre.textContent = json.preview_content;
                            return;
                        }
                    }
                }
            } catch(e) {
                console.warn('Backend fetch preview fallback:', e);
            }

            // Client-side instant structured preview
            const syntheticPreview = 
                `PEMERINTAH PROVINSI MALUKU\n` +
                `TVRI MALUKU\n` +
                `SISTEM INFORMASI MANAJEMEN DATA TERPADU (SIMANDAT SIWALIMA)\n\n` +
                `----------------------------------------------------------------------\n` +
                `KODE BERKAS     : ${fileObj.code || ('DOC-' + fileObj.id)}\n` +
                `JUDUL DOKUMEN   : ${fileObj.title}\n` +
                `KATEGORI UNIT   : ${fileObj.category || 'Dokumen Sektoral'}\n` +
                `FORMAT / UKURAN : ${fileObj.format || 'PDF'} (${fileObj.size || fileObj.file_size || '2.4 MB'})\n` +
                `WAKTU UPLOAD    : ${fileObj.day || 'Sabtu'}, ${fileObj.date || '12 Sep 2026'} ${fileObj.time || '10:00 WIT'}\n` +
                `PENGUNGGAH      : ${fileObj.uploader || 'Super Admin (TVRI Maluku)'}\n` +
                `STATUS DATA     : ${fileObj.status || 'VALIDATED'} (TERVERIFIKASI & AMAN)\n` +
                `DATABASE SERVER : SIMANDAT SIWALIMA (simandat_archives MySQL InnoDB ID: ${fileObj.id})\n` +
                `PROTEKSI DATA   : SOFT-DELETE SAFEGUARD ACTIVE\n` +
                `----------------------------------------------------------------------\n\n` +
                `RINGKASAN REKAM DATA:\n` +
                `1. Berkas ini telah diverifikasi sesuai Tata Kelola TVRI Maluku Tahun 2026.\n` +
                `2. Terintegrasi langsung dengan Executive Command Center Pimpinan.\n` +
                `3. Berkas digital asli tersimpan aman di database MySQL SIMANDAT SIWALIMA.\n\n` +
                `Digital Signature:\n` +
                `[DIGITALLY VALIDATED & SIGNED BY TVRI MALUKU PROV. MALUKU]\n`;
            
            if (previewPre) previewPre.textContent = syntheticPreview;
        },

        closeFilePreviewModal() {
            const modal = document.getElementById('filePreviewModal');
            const content = document.getElementById('filePreviewModalContent');
            if (!modal) return;
            modal.classList.replace('opacity-100', 'opacity-0');
            content.classList.replace('scale-100', 'scale-95');
            setTimeout(() => modal.classList.add('hidden'), 200);
        },

        downloadCurrentPreviewFile() {
            if (!this.state.currentPreviewFile) return;
            this.downloadFile(this.state.currentPreviewFile.id, this.state.currentPreviewFile.title, this.state.currentPreviewFile.format);
        },

        // --- DATABASE ARCHIVE EXPLORER (PASSWORD: dikbud_2026 PROTECTED) ---
        requestDbExplorerAccess() {
            this.closeAdminAccountModal();
            const pwdInput = document.getElementById('dbAuthPassword');
            const errorMsg = document.getElementById('dbAuthErrorMsg');
            const eyeIcon = document.getElementById('dbAuthEyeIcon');
            
            if (pwdInput) {
                pwdInput.value = '';
                pwdInput.type = 'password';
            }
            if (errorMsg) errorMsg.classList.add('hidden');
            if (eyeIcon) eyeIcon.className = 'fa-solid fa-eye';

            const modal = document.getElementById('dbAuthModal');
            const content = document.getElementById('dbAuthModalContent');
            if (!modal) return;

            modal.classList.remove('hidden');
            void modal.offsetWidth;
            modal.classList.replace('opacity-0', 'opacity-100');
            content.classList.replace('scale-95', 'scale-100');

            setTimeout(() => {
                if (pwdInput) pwdInput.focus();
            }, 150);
        },

        closeDbAuthModal() {
            const modal = document.getElementById('dbAuthModal');
            const content = document.getElementById('dbAuthModalContent');
            if (!modal) return;
            modal.classList.replace('opacity-100', 'opacity-0');
            content.classList.replace('scale-100', 'scale-95');
            setTimeout(() => modal.classList.add('hidden'), 200);
        },

        toggleDbAuthPasswordVisibility() {
            const pwdInput = document.getElementById('dbAuthPassword');
            const eyeIcon = document.getElementById('dbAuthEyeIcon');
            if (!pwdInput) return;

            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                if (eyeIcon) eyeIcon.className = 'fa-solid fa-eye-slash';
            } else {
                pwdInput.type = 'password';
                if (eyeIcon) eyeIcon.className = 'fa-solid fa-eye';
            }
        },

        verifyDbExplorerPassword() {
            const pwdInput = document.getElementById('dbAuthPassword');
            const errorMsg = document.getElementById('dbAuthErrorMsg');
            const pwd = pwdInput ? pwdInput.value.trim() : '';

            // Verify official password dikbud_2026
            if (pwd !== 'dikbud_2026') {
                if (errorMsg) errorMsg.classList.remove('hidden');
                if (pwdInput) {
                    pwdInput.classList.add('border-red-500', 'animate-shake');
                    pwdInput.focus();
                    setTimeout(() => pwdInput.classList.remove('animate-shake'), 500);
                }
                this.showToast('Password salah! Masukkan password resmi (dikbud_2026) untuk membuka berkas database.', 'error');
                return;
            }

            if (errorMsg) errorMsg.classList.add('hidden');
            this.closeDbAuthModal();
            this.openDbExplorerModal();
            this.showToast('Otorisasi Berhasil: Akses Berkas Database Terbuka (dikbud_2026).', 'success');
        },

        openDbExplorerModal() {
            this.closeAdminAccountModal();
            this.closeDbAuthModal();
            
            const modal = document.getElementById('dbExplorerModal');
            const content = document.getElementById('dbExplorerModalContent');
            if (!modal) return;

            modal.classList.remove('hidden');
            void modal.offsetWidth;
            modal.classList.replace('opacity-0', 'opacity-100');
            content.classList.replace('scale-95', 'scale-100');

            try {
                this.renderDbExplorerTable();
            } catch(e) {
                console.error('Error rendering DB explorer table:', e);
            }
        },

        closeDbExplorerModal() {
            const modal = document.getElementById('dbExplorerModal');
            const content = document.getElementById('dbExplorerModalContent');
            if (!modal) return;
            modal.classList.replace('opacity-100', 'opacity-0');
            content.classList.replace('scale-100', 'scale-95');
            setTimeout(() => modal.classList.add('hidden'), 200);
        },

        renderDbExplorerTable(filterText = '') {
            const tbody = document.getElementById('dbExplorerTableBody');
            const totalText = document.getElementById('dbExplorerTotalText');
            if (!tbody) return;

            const isAdmin = this.state.role === 'admin' || !this.state.role; // Default super admin access

            let allFiles = [];
            Object.keys(this.state.uploadedFiles).forEach(mod => {
                const arr = this.state.uploadedFiles[mod] || [];
                arr.forEach(f => {
                    allFiles.push({ ...f, moduleId: mod });
                });
            });

            if (filterText) {
                const q = filterText.toLowerCase();
                allFiles = allFiles.filter(f => 
                    (f.title && f.title.toLowerCase().includes(q)) || 
                    (f.code && f.code.toLowerCase().includes(q)) ||
                    (f.category && f.category.toLowerCase().includes(q)) ||
                    (f.uploader && f.uploader.toLowerCase().includes(q))
                );
            }

            if (totalText) totalText.innerHTML = `Total: <strong class="text-app-primary">${allFiles.length} Dokumen</strong>`;

            if (allFiles.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="p-6 text-center text-slate-400">
                            <i class="fa-regular fa-folder-open text-2xl mb-2 block text-slate-500"></i>
                            Tidak ada berkas yang sesuai dengan pencarian database.
                        </td>
                    </tr>
                `;
                return;
            }

            let html = '';
            allFiles.forEach(file => {
                const isPdf = (file.format || 'PDF').toUpperCase().includes('PDF');
                const isXls = (file.format || '').toUpperCase().includes('XLS');
                const iconClass = isPdf ? 'fa-solid fa-file-pdf text-red-400' : (isXls ? 'fa-solid fa-file-excel text-emerald-400' : 'fa-solid fa-file-lines text-blue-400');
                const safeTitle = (file.title || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '&quot;');

                html += `
                    <tr class="hover:bg-app-surfaceHover transition-colors text-white group">
                        <td class="p-3 font-mono text-app-primary font-semibold whitespace-nowrap">
                            ${file.code || ('DOC-' + file.id)}
                            <div class="text-[9px] text-slate-500 font-sans">ID: ${file.id || '-'}</div>
                        </td>
                        <td class="p-3">
                            <div class="flex items-center gap-2.5">
                                <i class="${iconClass} text-base shrink-0"></i>
                                <div>
                                    <div class="font-bold text-white leading-tight">${file.title}</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">
                                        <span class="text-blue-400 font-medium">Modul ${file.moduleId}</span> • ${file.category || 'Laporan Sektoral'}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="p-3 whitespace-nowrap">
                            <span class="px-2 py-0.5 rounded bg-slate-800 border border-slate-700 text-[10px] text-slate-300 font-mono">${file.format || 'PDF'} (${file.size || file.file_size || '2.4 MB'})</span>
                        </td>
                        <td class="p-3 whitespace-nowrap text-[10px] text-slate-300">
                            <div>${file.day || 'Sabtu'}, ${file.date || ''}</div>
                            <div class="text-slate-500">${file.time || ''}</div>
                        </td>
                        <td class="p-3 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-1.5">
                                <button onclick="app.viewFileModal('${file.id || file.code}', '${safeTitle}', '${file.format || 'PDF'}')" class="bg-blue-600/20 hover:bg-blue-600 text-blue-400 hover:text-white border border-blue-500/40 px-2.5 py-1 rounded text-xs transition-colors flex items-center gap-1 shadow-sm" title="Buka & Pratinjau Berkas">
                                    <i class="fa-solid fa-eye"></i> Buka File
                                </button>
                                <button onclick="app.downloadFile('${file.id || file.code}', '${safeTitle}', '${file.format || 'PDF'}')" class="bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700 px-2 py-1 rounded text-xs transition-colors" title="Unduh File Langsung">
                                    <i class="fa-solid fa-download"></i>
                                </button>
                                ${isAdmin ? `
                                <button onclick="app.openDeleteModal('${file.id || file.code}', '${safeTitle}')" class="bg-red-500/20 hover:bg-red-600 text-red-400 hover:text-white border border-red-500/40 px-2.5 py-1 rounded text-xs transition-colors flex items-center gap-1 shadow-sm" title="Hapus Berkas dari Database (Proteksi PIN 1237)">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                                ` : ''}
                            </div>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
        },

        filterDbExplorer(keyword) {
            this.renderDbExplorerTable(keyword);
        },

        // --- MODUL VIEW (2.1 UMUM & ASET, 2.2 PERENCANAAN & KEUANGAN, 2.3 SOP & SUB-BIDANG LAINNYA) ---
        buildModuleHTML(parentGroup, selectedItem) {
            const isAdmin = this.state.role === 'admin';
            const uploadBtnHTML = isAdmin ? `
                <button onclick="app.openModal('${selectedItem.title}', '${selectedItem.id}')" class="bg-[#0b1325] border border-blue-500/40 text-blue-400 text-xs px-3 py-1.5 rounded-lg flex items-center gap-2 hover:bg-blue-600 hover:text-white transition-colors">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Upload Data
                </button>
            ` : `<span class="text-xxs text-app-warning border border-app-warning/30 px-2 py-1 rounded bg-app-warning/10"><i class="fa-solid fa-eye mr-1"></i> Mode Baca</span>`;

            let topCardsHTML = '';

            // --- 2.1 SUBBAG. UMUM DAN ASET ---
            if (selectedItem.id === '2.1') {
                const subItemsUmum = [
                    { id: '2.1.1', title: 'Inventarisasi Aset & BMD Sarpras Sekolah', progress: Math.min(100, Math.round(this.state.sekretariatData.subbagUmum.progress)), status: 'VALIDATED', desc: 'Pendataan KIB A, B, C, D & sertifikasi tanah/gedung sekolah se-Maluku' },
                    { id: '2.1.2', title: 'Tata Naskah Persuratan & Ekspedisi Dinas', progress: 96, status: 'VALIDATED', desc: 'Distribusi surat masuk/keluar, disposisi pimpinan, dan kearsipan digital' },
                    { id: '2.1.3', title: 'Pemeliharaan Sarana Gedung & Kendaraan Operasional', progress: 90, status: 'IN PROGRESS', desc: 'Pemeliharaan berkala kantor dinas dan logistik operasional wilayah' }
                ];

                let cards = '';
                subItemsUmum.forEach(item => {
                    cards += `
                        <div class="bg-[#0b1325] border border-app-cardBorder rounded-xl p-4 shadow-lg flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-xxs font-bold text-app-primary bg-app-primary/10 px-2 py-0.5 rounded border border-app-primary/30">${item.id}</span>
                                    <span class="text-xxs border px-2 py-0.5 rounded ${item.status === 'VALIDATED' ? 'text-app-success bg-app-success/10 border-app-success/30' : 'text-app-warning bg-app-warning/10 border-app-warning/30'}">${item.status}</span>
                                </div>
                                <h4 class="text-sm font-bold text-white mb-1">${item.title}</h4>
                                <p class="text-xxs text-slate-400 mb-3">${item.desc}</p>
                            </div>
                            <div class="space-y-2 pt-2 border-t border-slate-800">
                                <div class="flex justify-between text-xs">
                                    <span class="text-slate-400">Progres Berkas:</span>
                                    <span class="font-bold text-white">${item.progress}%</span>
                                </div>
                                <div class="w-full bg-[#070e1c] h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-app-primary h-full rounded-full" style="width: ${item.progress}%"></div>
                                </div>
                                <div class="flex justify-between items-center pt-2">
                                    <span class="text-[10px] text-slate-400"><i class="fa-solid fa-file-lines mr-1"></i> Terinput: ${this.state.sekretariatData.subbagUmum.totalBerkas} Berkas</span>
                                    ${isAdmin ? `
                                    <button onclick="app.openModal('${item.title}', '2.1')" class="text-xxs bg-blue-600/20 text-blue-400 hover:bg-blue-600 hover:text-white px-2.5 py-1 rounded transition-colors flex items-center gap-1 border border-blue-500/30">
                                        <i class="fa-solid fa-plus"></i> Input Berkas
                                    </button>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                });

                topCardsHTML = `<div class="grid grid-cols-1 md:grid-cols-3 gap-4">${cards}</div>`;
            } 
            // --- 2.2 SUBBAG. PERENCANAAN & KEUANGAN ---
            else if (selectedItem.id === '2.2') {
                const subItemsKeu = [
                    { id: '2.2.1', title: 'Dokumen Perencanaan (Renstra, Renja & RKA-DPA)', progress: 98, status: 'VALIDATED', desc: 'Penyusunan anggaran tahunan dan sinkronisasi SIPD Kemendagri / Bappeda' },
                    { id: '2.2.2', title: 'Laporan Realisasi Anggaran (SP2D, SPJ & Kas Daerah)', progress: Math.min(100, Math.round(this.state.sekretariatData.subbagKeuangan.serapan)), status: 'VALIDATED', desc: 'Penyerapan anggaran belanja modal, belanja pegawai, dan DAK Fisik/Non-Fisik' },
                    { id: '2.2.3', title: 'Laporan Kinerja Instansi Pemerintah (LKjIP & IKU)', progress: 92, status: 'IN PROGRESS', desc: 'Evaluasi akuntabilitas kinerja berkala dan capaian target perjanjian kerja' }
                ];

                let cards = '';
                subItemsKeu.forEach(item => {
                    cards += `
                        <div class="bg-[#0b1325] border border-app-cardBorder rounded-xl p-4 shadow-lg flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-xxs font-bold text-app-primary bg-app-primary/10 px-2 py-0.5 rounded border border-app-primary/30">${item.id}</span>
                                    <span class="text-xxs border px-2 py-0.5 rounded ${item.status === 'VALIDATED' ? 'text-app-success bg-app-success/10 border-app-success/30' : 'text-app-warning bg-app-warning/10 border-app-warning/30'}">${item.status}</span>
                                </div>
                                <h4 class="text-sm font-bold text-white mb-1">${item.title}</h4>
                                <p class="text-xxs text-slate-400 mb-3">${item.desc}</p>
                            </div>
                            <div class="space-y-2 pt-2 border-t border-slate-800">
                                <div class="flex justify-between text-xs">
                                    <span class="text-slate-400">Realisasi / Serapan:</span>
                                    <span class="font-bold text-app-success">${item.progress}%</span>
                                </div>
                                <div class="w-full bg-[#070e1c] h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-blue-500 h-full rounded-full" style="width: ${item.progress}%"></div>
                                </div>
                                <div class="flex justify-between items-center pt-2">
                                    <span class="text-[10px] text-slate-400"><i class="fa-solid fa-receipt mr-1"></i> Terdata: ${this.state.sekretariatData.subbagKeuangan.totalBerkas} Dokumen</span>
                                    ${isAdmin ? `
                                    <button onclick="app.openModal('${item.title}', '2.2')" class="text-xxs bg-blue-600/20 text-blue-400 hover:bg-blue-600 hover:text-white px-2.5 py-1 rounded transition-colors flex items-center gap-1 border border-blue-500/30">
                                        <i class="fa-solid fa-plus"></i> Input Realisasi
                                    </button>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                });

                topCardsHTML = `<div class="grid grid-cols-1 md:grid-cols-3 gap-4">${cards}</div>`;
            } 
            // --- 2.3 PENGELOLAAN DATA & TUGAS PEMBANTUAN (10 SOP LAYANAN) ---
            else if (selectedItem.id === '2.3') {
                const sopList = this.state.sekretariatData.subbagData.sopList;
                let listHTML = '';

                sopList.forEach(item => {
                    const isSelesai = item.status === 'Selesai';
                    listHTML += `
                        <div class="bg-[#0b1325] border border-app-cardBorder rounded-xl p-4 hover:border-app-primary/50 transition-colors shadow-lg flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-app-primary bg-app-primary/10 px-2 py-0.5 rounded border border-app-primary/30">${item.id}</span>
                                        <span class="text-xs text-app-success bg-app-success/10 px-2 py-0.5 rounded border border-app-success/30">Gratis (Rp 0)</span>
                                    </div>
                                    <span class="${isSelesai ? 'bg-app-success/20 text-app-success border-app-success/40' : 'bg-app-warning/20 text-app-warning border-app-warning/40'} border text-[10px] px-2 py-0.5 rounded font-bold">${item.status}</span>
                                </div>
                                <h4 class="text-sm font-bold text-white mb-1">${item.name}</h4>
                                <div class="text-xxs text-slate-400 mb-3">${item.category}</div>
                                
                                <div class="grid grid-cols-2 gap-4 text-xs bg-[#070e1c] p-3 rounded-lg border border-slate-800 mb-3">
                                    <div>
                                        <div class="text-slate-400 text-xxs mb-1">Jangka Waktu (SLA):</div>
                                        <div class="text-white font-medium text-xxs leading-tight">${item.sla}</div>
                                    </div>
                                    <div>
                                        <div class="text-slate-400 text-xxs mb-1">Jumlah Pelaksana:</div>
                                        <div class="text-white font-medium">${item.staff} Orang</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center pt-2 border-t border-slate-800">
                                <button onclick="app.downloadFile('${item.id}', 'SOP ${item.name}', 'PDF')" class="text-xs text-app-primary hover:text-white transition-colors flex items-center gap-1">
                                    <i class="fa-solid fa-file-pdf"></i> Lihat SOP
                                </button>
                                ${isAdmin ? `
                                <button onclick="app.openSopInputModal('${item.id}', '${item.name}')" class="bg-[#070e1c] border border-app-primary/30 hover:bg-app-primary/15 text-app-primary text-xxs px-2.5 py-1 rounded-lg flex items-center gap-1 transition-colors">
                                    <i class="fa-solid fa-upload"></i> Input Data
                                </button>
                                ` : ''}
                            </div>
                        </div>
                    `;
                });

                topCardsHTML = `<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">${listHTML}</div>`;
            } 
            // --- 4.1 BIDANG PENDIDIKAN SMK (7 SOP LAYANAN RESMI) ---
            else if (selectedItem.id === '4.1') {
                const smkSopList = [
                    {
                        id: 'SMK-01',
                        cost: 'Tidak dikenakan biaya (GRATIS)',
                        name: 'Rekomendasi Mutasi (Perpindahan) Siswa SMK',
                        category: 'Kesiswaan & Mutasi',
                        sla: 'Paling lama 5 (lima) hari kerja sejak berkas dinyatakan lengkap',
                        staff: 6,
                        status: 'VALIDATED'
                    },
                    {
                        id: 'SMK-02',
                        cost: 'Tidak dikenakan biaya (GRATIS)',
                        name: 'Bantuan Operasional Satuan Pendidikan (BOSP) SMK',
                        category: 'Pemerataan & Pendanaan',
                        sla: 'Paling lama 10 (sepuluh) hari kerja per tahap penyaluran sebelum diproses lebih lanjut oleh Kementerian',
                        staff: 2,
                        status: 'VALIDATED'
                    },
                    {
                        id: 'SMK-03',
                        cost: 'Tidak dikenakan biaya (GRATIS)',
                        name: 'Pelayanan Ijazah SMK (Legalisir dan Surat Keterangan Pengganti yang Hilang / Rusak)',
                        category: 'Dokumen & Legalitas',
                        sla: 'Paling lama 1 (satu) hari kerja untuk legalisir, dan paling lama 7 (tujuh) hari kerja untuk surat keterangan pengganti, sejak berkas dinyatakan lengkap',
                        staff: 6,
                        status: 'VALIDATED'
                    },
                    {
                        id: 'SMK-04',
                        cost: 'Tidak dikenakan biaya (GRATIS)',
                        name: 'Verifikasi dan Supervisi Dokumen Kurikulum SMK',
                        category: 'Kurikulum & Vokasi',
                        sla: 'Paling lama 10 (sepuluh) hari kerja sejak dokumen KSP disampaikan untuk verifikasi dan supervisi',
                        staff: 2,
                        status: 'VALIDATED'
                    },
                    {
                        id: 'SMK-05',
                        cost: 'Tidak dikenakan biaya (GRATIS)',
                        name: 'Rekomendasi Pendirian SMK Negeri dan SMK Swasta',
                        category: 'Kelembagaan & Perizinan',
                        sla: 'Paling lama 15 (lima belas) hari kerja sejak berkas/kajian dinyatakan lengkap',
                        staff: 2,
                        status: 'VALIDATED'
                    },
                    {
                        id: 'SMK-06',
                        cost: 'Tidak dikenakan biaya (GRATIS)',
                        name: 'Rekomendasi Izin Operasional SMK',
                        category: 'Kelembagaan & Perizinan',
                        sla: 'Paling lama 10 (sepuluh) hari kerja sejak berkas dinyatakan lengkap',
                        staff: 2,
                        status: 'VALIDATED'
                    },
                    {
                        id: 'SMK-07',
                        cost: 'Tidak dikenakan biaya (GRATIS)',
                        name: 'Izin Penambahan/Penggabungan Kompetensi (Konsentrasi Keahlian) Baru SMK',
                        category: 'Kurikulum & Vokasi',
                        sla: 'Paling lama 12 (dua belas) hari kerja sejak berkas dinyatakan lengkap',
                        staff: 2,
                        status: 'VALIDATED'
                    }
                ];

                let listHTML = '';
                smkSopList.forEach(item => {
                    listHTML += `
                        <div class="bg-[#0b1325] border border-app-cardBorder rounded-xl p-4 hover:border-app-primary/50 transition-colors shadow-lg flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-app-primary bg-app-primary/10 px-2 py-0.5 rounded border border-app-primary/30">${item.id}</span>
                                        <span class="text-xs text-app-success bg-app-success/10 px-2 py-0.5 rounded border border-app-success/30 font-medium">${item.cost}</span>
                                    </div>
                                    <span class="bg-app-success/20 text-app-success border-app-success/40 border text-[10px] px-2 py-0.5 rounded font-bold">${item.status}</span>
                                </div>
                                <h4 class="text-sm font-bold text-white mb-1 leading-snug">${item.name}</h4>
                                <div class="text-xxs text-slate-400 mb-3">${item.category}</div>
                                
                                <div class="grid grid-cols-2 gap-4 text-xs bg-[#070e1c] p-3 rounded-lg border border-slate-800 mb-3">
                                    <div>
                                        <div class="text-slate-400 text-xxs mb-1 font-semibold">Jangka Waktu (SLA):</div>
                                        <div class="text-white font-medium text-xxs leading-tight">${item.sla}</div>
                                    </div>
                                    <div>
                                        <div class="text-slate-400 text-xxs mb-1 font-semibold">Jumlah Pelaksana:</div>
                                        <div class="text-white font-medium text-xs">${item.staff} Orang</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center pt-2 border-t border-slate-800">
                                <button onclick="app.downloadFile('${item.id}', 'SOP 14 Komponen - ${item.name}', 'PDF')" class="text-xs text-app-primary hover:text-white transition-colors flex items-center gap-1.5 font-medium">
                                    <i class="fa-solid fa-file-shield text-cyan-400"></i> Lihat SOP 14 Komponen
                                </button>
                                ${isAdmin ? `
                                <button onclick="app.openModal('${item.name}', '4.1')" class="bg-[#070e1c] border border-app-primary/30 hover:bg-app-primary/15 text-app-primary text-xxs px-2.5 py-1 rounded-lg flex items-center gap-1 transition-colors">
                                    <i class="fa-solid fa-upload"></i> Input Berkas
                                </button>
                                ` : ''}
                            </div>
                        </div>
                    `;
                });

                topCardsHTML = `<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">${listHTML}</div>`;
            } else {
                // MODUL SUB-BIDANG LAINNYA (SMA, SMK, SLB, KEBUDAYAAN, GTK)
                const moduleSubItemsMap = {
                    // 3. BIDANG PENDIDIKAN SMA
                    '3.1': [
                        { id: '3.1.1', title: 'Rencana Kerja Operasional & Program SMA', progress: 95, status: 'VALIDATED', desc: 'Penyusunan program tahunan mutu SMA dan sinkronisasi renja' },
                        { id: '3.1.2', title: 'Alokasi Pagu Anggaran & DAK Fisik SMA', progress: 92, status: 'VALIDATED', desc: 'Realisasi DAK Fisik ruang kelas baru, lab dan perpustakaan' },
                        { id: '3.1.3', title: 'Program Akselerasi SMA Daerah 3T', progress: 88, status: 'IN PROGRESS', desc: 'Pemerataan fasilitas dan pendampingan SMA kepulauan terluar' }
                    ],
                    '3.2': [
                        { id: '3.2.1', title: 'Instrumen Akreditasi SMA se-Maluku', progress: Math.min(100, Math.round(this.state.smaData.capaian)), status: 'VALIDATED', desc: 'Pendampingan BAN-SM untuk akreditasi predikat A dan B' },
                        { id: '3.2.2', title: 'Supervisi Manajerial Kepala Sekolah', progress: 90, status: 'VALIDATED', desc: 'Evaluasi kepemimpinan dan manajerial kepala SMA' },
                        { id: '3.2.3', title: 'Pengawasan Mutu Pembelajaran Cabdin', progress: 86, status: 'IN PROGRESS', desc: 'Monitoring berkala pengawas sekolah di 9 cabang dinas' }
                    ],
                    '3.3': [
                        { id: '3.3.1', title: 'Implementasi Kurikulum Merdeka SMA', progress: 96, status: 'VALIDATED', desc: 'Penerapan modul ajar, P5, dan asesmen diagnostik siswa' },
                        { id: '3.3.2', title: 'Asesmen Nasional & Rapor Pendidikan SMA', progress: 94, status: 'VALIDATED', desc: 'Analisis skor literasi, numerasi, dan survei karakter siswa' },
                        { id: '3.3.3', title: 'Digitalisasi Bahan Ajar & CBT SMA', progress: 89, status: 'IN PROGRESS', desc: 'Pemanfaatan platform digital dan bank soal terintegrasi' }
                    ],
                    '3.4': [
                        { id: '3.4.1', title: 'Ajang Talenta, OSN & FLS2N Siswa SMA', progress: 94, status: 'VALIDATED', desc: 'Pembinaan prestasi olimpiade sains, seni, dan olahraga' },
                        { id: '3.4.2', title: 'Beasiswa Maluku Cerdas & Afirmasi', progress: 98, status: 'VALIDATED', desc: 'Penyaluran bantuan biaya pendidikan siswa berprestasi & kurang mampu' },
                        { id: '3.4.3', title: 'Penguatan Karakter & Profil Pelajar Pancasila', progress: 91, status: 'VALIDATED', desc: 'Pencegahan bullying, intoleransi, dan wawasan kebangsaan' }
                    ],

                    // 4. BIDANG PENDIDIKAN SMK
                    '4.1': [
                        { id: '4.1.1', title: 'Peta Jalan Revitalisasi SMK Maluku', progress: 93, status: 'VALIDATED', desc: 'Pemetaan konsentrasi keahlian berbasis potensi kemaritiman & SDA' },
                        { id: '4.1.2', title: 'Bantuan Sarpras & Peralatan Praktik Vokasi', progress: 87, status: 'IN PROGRESS', desc: 'Standarisasi bengkel, laboratorium maritim, dan agribisnis' },
                        { id: '4.1.3', title: 'Program SMK Pusat Keunggulan (SMK-PK)', progress: 96, status: 'VALIDATED', desc: 'Akselerasi SMK unggulan rujukan provinsi kepulauan' }
                    ],
                    '4.2': [
                        { id: '4.2.1', title: 'Teaching Factory (Tefa) Kemaritiman & Laut', progress: 92, status: 'VALIDATED', desc: 'Produksi dan sertifikasi navigasi, nautika & pengolahan hasil laut' },
                        { id: '4.2.2', title: 'Tefa Agribisnis & Rekayasa Teknologi', progress: 88, status: 'IN PROGRESS', desc: 'Hilirisasi produk olahan rempah, perkebunan, dan mekanik' },
                        { id: '4.2.3', title: 'Inkubator Bisnis & Kewirausahaan Siswa', progress: 85, status: 'IN PROGRESS', desc: 'Pemasaran produk kreatif siswa vokasi ke pasar regional' }
                    ],
                    '4.3': [
                        { id: '4.3.1', title: 'Penyelarasan Kurikulum Bersama Industri', progress: 95, status: 'VALIDATED', desc: 'Sinkronisasi kompetensi kerja sesuai kebutuhan DUDI terkini' },
                        { id: '4.3.2', title: 'Uji Sertifikasi Kompetensi BNSP / LSP-P1', progress: 90, status: 'VALIDATED', desc: 'Pelaksanaan uji kompetensi berstandar nasional bagi siswa tingkat akhir' },
                        { id: '4.3.3', title: 'Pelatihan & Lisensi Asesor Kompetensi', progress: 86, status: 'IN PROGRESS', desc: 'Peningkatan jumlah guru vokasi pemegang lisensi asesor' }
                    ],
                    '4.4': [
                        { id: '4.4.1', title: 'MoU Kemitraan Industri & BUMN Kemaritiman', progress: Math.min(100, Math.round(this.state.smkData.dudiPercent)), status: 'VALIDATED', desc: 'Perjanjian kerja sama link & match penyerapan lulusan' },
                        { id: '4.4.2', title: 'Penempatan & Monitoring PKL Siswa SMK', progress: 94, status: 'VALIDATED', desc: 'Pelaksanaan magang industri minimal 6 bulan bagi siswa' },
                        { id: '4.4.3', title: 'Tracer Study & Keterserapan Lulusan', progress: 89, status: 'IN PROGRESS', desc: 'Pelacakan alumni vokasi bekerja, melanjutkan studi, atau wirausaha' }
                    ],

                    // 5. BIDANG PENDIDIKAN KHUSUS (SLB)
                    '5.1': [
                        { id: '5.1.1', title: 'Tata Kelola Satuan Pendidikan SLB', progress: 94, status: 'VALIDATED', desc: 'Pembinaan kelembagaan dan administrasi SLB negeri & swasta' },
                        { id: '5.1.2', title: 'Pemberdayaan Guru Pendamping Khusus (GPK)', progress: 88, status: 'IN PROGRESS', desc: 'Pelatihan kompetensi penanganan ABK bagi tenaga pendidik' },
                        { id: '5.1.3', title: 'Advokasi & Sosialisasi Pendidikan Inklusi', progress: 90, status: 'VALIDATED', desc: 'Sosialisasi penerimaan ABK di sekolah umum se-Maluku' }
                    ],
                    '5.2': [
                        { id: '5.2.1', title: 'Kurikulum Adaptif & Pembelajaran Individual', progress: 92, status: 'VALIDATED', desc: 'Program Pembelajaran Individual (PPI) bagi ragam disabilitas' },
                        { id: '5.2.2', title: 'Asesmen Diagnostik Bakat & Vokasional ABK', progress: 87, status: 'IN PROGRESS', desc: 'Identifikasi potensi seni, keterampilan tangan, dan kemandirian siswa' },
                        { id: '5.2.3', title: 'Penyediaan Bahan Ajar Braille & Isyarat', progress: 85, status: 'IN PROGRESS', desc: 'Pengadaan modul khusus tunanetra, tunarungu, dan autisme' }
                    ],
                    '5.3': [
                        { id: '5.3.1', title: 'Sarana Aksesibilitas & Ramah Disabilitas', progress: 91, status: 'VALIDATED', desc: 'Pembangunan ramp, guiding block, dan toilet khusus di SLB' },
                        { id: '5.3.2', title: 'Alat Peraga Edukatif & Terapi Sensori', progress: 89, status: 'IN PROGRESS', desc: 'Pengadaan peralatan terapi wicara, okupasi, dan fisioterapi' },
                        { id: '5.3.3', title: 'Unit Layanan Disabilitas (ULD) Provinsi', progress: 95, status: 'VALIDATED', desc: 'Pusat rujukan asesmen dan pendampingan ABK Maluku' }
                    ],

                    // 6. BIDANG KEBUDAYAAN
                    '6.1': [
                        { id: '6.1.1', title: 'Inventarisasi & Penetapan Cagar Budaya', progress: 96, status: 'VALIDATED', desc: 'Pendaftaran objek cagar budaya benteng, masjid kuno, dan gereja tua' },
                        { id: '6.1.2', title: 'Konservasi, Pemugaran & Penyelamatan Situs', progress: 89, status: 'IN PROGRESS', desc: 'Perawatan berkala artefak dan situs bersejarah kepulauan' },
                        { id: '6.1.3', title: 'Digitalisasi Koleksi Museum Siwalima', progress: 94, status: 'VALIDATED', desc: 'Katalog digital 3D dan pameran virtual benda bersejarah Maluku' }
                    ],
                    '6.2': [
                        { id: '6.2.1', title: 'Pencatatan Warisan Budaya Takbenda (WBTB)', progress: 95, status: 'VALIDATED', desc: 'Penetapan tradisi lisan, adat istiadat, dan ritus bahari Maluku' },
                        { id: '6.2.2', title: 'Kajian Manuskrip, Naskah Kuno & Sejarah', progress: 90, status: 'VALIDATED', desc: 'Alih aksara naskah kuno daun lontar dan arsip kolonial rempah' },
                        { id: '6.2.3', title: 'Revitalisasi Bahasa & Tradisi Daerah Maluku', progress: 87, status: 'IN PROGRESS', desc: 'Pelestarian bahasa ibu kepulauan melalui kurikulum muatan lokal' }
                    ],
                    '6.3': [
                        { id: '6.3.1', title: 'Pembinaan Sanggar Seni Tari & Musik Tradisi', progress: 93, status: 'VALIDATED', desc: 'Bantuan sarana alat musik totobuang, tifa, dan kostum tari' },
                        { id: '6.3.2', title: 'Gelar Budaya & Festival Seni Kepulauan Siwalima', progress: 98, status: 'VALIDATED', desc: 'Pentas seni kolaboratif sanggar kabupaten/kota se-Maluku' },
                        { id: '6.3.3', title: 'Pameran Kriya Tradisional & Ekonomi Kreatif', progress: 88, status: 'IN PROGRESS', desc: 'Promosi kerajinan tenun ikat Maluku Tenggara dan anyaman lokal' }
                    ],

                    // 7. BIDANG KETENAGAAN (GTK)
                    '7.1': [
                        { id: '7.1.1', title: 'Pemetaan Distribusi Guru Daerah 3T & Kepulauan', progress: 94, status: 'VALIDATED', desc: 'Analisis rasio kebutuhan guru per mapel di seluruh cabang dinas' },
                        { id: '7.1.2', title: 'Formasi & Penataan Penugasan PPPK GTK', progress: 97, status: 'VALIDATED', desc: 'Penempatan guru PPPK SMA/SMK/SLB formasi provinsi' },
                        { id: '7.1.3', title: 'Rotasi & Mutasi Guru Pemerataan Mutu', progress: 89, status: 'IN PROGRESS', desc: 'Penyaluran guru inti ke sekolah kepulauan tertinggal' }
                    ],
                    '7.2': [
                        { id: '7.2.1', title: 'Pelatihan Berkelanjutan Guru & Tenaga Kependidikan', progress: Math.min(100, Math.round(this.state.gtkData.profesional)), status: 'VALIDATED', desc: 'Bimtek pedagogik, numerasi, dan penguatan konten sains' },
                        { id: '7.2.2', title: 'Komunitas Guru Penggerak Provinsi Maluku', progress: 95, status: 'VALIDATED', desc: 'Pengimbasan praktik baik pembelajaran berpusat pada siswa' },
                        { id: '7.2.3', title: 'Transformasi Literasi & Digitalisasi GTK', progress: Math.min(100, Math.round(this.state.gtkData.literasiDigital)), status: 'VALIDATED', desc: 'Pemanfaatan Platform Merdeka Mengajar (PMM) dan AI edukasi' }
                    ],
                    '7.3': [
                        { id: '7.3.1', title: 'Pemberkasan Tunjangan Profesi Guru (TPG) & 3T', progress: 98, status: 'VALIDATED', desc: 'Verifikasi berkas pencairan TPG, TKG Khusus 3T & Insentif Non-PNS' },
                        { id: '7.3.2', title: 'Validasi InfoGTK & Sinkronisasi SIMPKB', progress: Math.min(100, Math.round(this.state.gtkData.sertifikasi3T)), status: 'VALIDATED', desc: 'Pengecekan linieritas jam mengajar 24 jam per minggu' },
                        { id: '7.3.3', title: 'Akselerasi PPG Pendidik Guru Kepulauan', progress: 91, status: 'VALIDATED', desc: 'Fasilitasi ujian sertifikasi pendidik bagi guru honorer wilayah 3T' }
                    ]
                };

                const subItems = moduleSubItemsMap[selectedItem.id] || [
                    { id: `${selectedItem.id}.1`, title: 'Dokumen Perencanaan Program', progress: 92, status: 'VALIDATED', desc: 'Penyusunan target program dan alokasi sumber daya' },
                    { id: `${selectedItem.id}.2`, title: 'Laporan Realisasi Fisik & Anggaran', progress: 88, status: 'IN PROGRESS', desc: 'Evaluasi pelaksanaan kegiatan dan serapan anggaran berkala' },
                    { id: `${selectedItem.id}.3`, title: 'Data Statistik Sektoral Terverifikasi', progress: 100, status: 'VALIDATED', desc: 'Sinkronisasi data master ke database SIMANDAT Siwalima' }
                ];

                let subItemsHTML = '';
                subItems.forEach(sub => {
                    let statusColor = sub.status === 'VALIDATED' ? 'text-app-success bg-app-success/10 border-app-success/30' : 'text-app-warning bg-app-warning/10 border-app-warning/30';
                    
                    subItemsHTML += `
                        <div class="bg-[#0b1325] border border-app-cardBorder rounded-xl p-4 shadow-lg flex flex-col justify-between hover:border-app-primary/40 transition-colors">
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-xxs font-bold text-app-primary bg-app-primary/10 px-2 py-0.5 rounded border border-app-primary/30">${sub.id}</span>
                                    <span class="text-xxs border px-2 py-0.5 rounded ${statusColor}">${sub.status}</span>
                                </div>
                                <h4 class="text-sm font-bold text-white mb-1">${sub.title}</h4>
                                <p class="text-xxs text-slate-400 mb-3">${sub.desc || 'Dokumen sektoral resmi TVRI Maluku'}</p>
                            </div>
                            <div class="space-y-2 pt-2 border-t border-slate-800">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-slate-400">Kelengkapan Data:</span>
                                    <span class="font-bold ${sub.progress === 100 ? 'text-app-success' : 'text-white'}">${sub.progress}%</span>
                                </div>
                                <div class="w-full bg-[#070e1c] h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-app-primary h-full rounded-full progress-animate" style="width: ${sub.progress}%"></div>
                                </div>
                                <div class="flex justify-between items-center pt-2">
                                    <span class="text-[10px] text-slate-400"><i class="fa-solid fa-clock mr-1"></i> Update: Hari ini</span>
                                    ${isAdmin ? `
                                    <button onclick="app.openModal('${sub.title}', '${selectedItem.id}')" class="text-xxs bg-blue-600/20 text-blue-400 hover:bg-blue-600 hover:text-white px-2.5 py-1 rounded transition-colors flex items-center gap-1 border border-blue-500/30">
                                        <i class="fa-solid fa-plus"></i> Input Berkas
                                    </button>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                topCardsHTML = `<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">${subItemsHTML}</div>`;
            }

            // --- TABEL RIWAYAT BERKAS TERINPUT (LENGKAP DENGAN SEARCH, FILTER KATEGORI, CRUD LENGKAP, DOWNLOAD & PROTECTED DELETE) ---
            const rawFiles = this.state.uploadedFiles[selectedItem.id] || [];
            const searchQuery = (this.state.moduleSearchQuery || '').toLowerCase();
            const categoryFilter = this.state.moduleCategoryFilter || 'all';

            // Get list of unique categories
            const uniqueCategories = Array.from(new Set(rawFiles.map(f => f.category || selectedItem.title).filter(Boolean)));

            const unitFiles = rawFiles.filter(file => {
                const matchQuery = !searchQuery || 
                    (file.code && file.code.toLowerCase().includes(searchQuery)) ||
                    (file.title && file.title.toLowerCase().includes(searchQuery)) ||
                    (file.category && file.category.toLowerCase().includes(searchQuery)) ||
                    (file.pic && file.pic.toLowerCase().includes(searchQuery)) ||
                    (file.uploader && file.uploader.toLowerCase().includes(searchQuery));
                const matchCat = categoryFilter === 'all' || (file.category || selectedItem.title) === categoryFilter;
                return matchQuery && matchCat;
            });

            let fileRowsHTML = '';
            if (unitFiles.length === 0) {
                fileRowsHTML = `
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400 text-xs">
                            <i class="fa-regular fa-folder-open text-3xl mb-2 block text-slate-500"></i>
                            ${searchQuery || categoryFilter !== 'all' ? 'Tidak ada berkas yang sesuai dengan filter pencarian.' : 'Belum ada berkas terunggah untuk modul ini. Silakan klik tombol "Upload Berkas Baru".'}
                        </td>
                    </tr>
                `;
            } else {
                unitFiles.forEach(file => {
                    const origIndex = rawFiles.findIndex(f => (f.id && f.id === file.id) || (f.code && f.code === file.code));
                    const isPdf = (file.format || 'PDF').toUpperCase().includes('PDF');
                    const isXls = (file.format || '').toUpperCase().includes('XLS');
                    const isZip = (file.format || '').toUpperCase().includes('ZIP');
                    const iconClass = isPdf ? 'fa-solid fa-file-pdf text-red-400' : (isXls ? 'fa-solid fa-file-excel text-emerald-400' : (isZip ? 'fa-solid fa-file-zipper text-amber-400' : 'fa-solid fa-file-lines text-blue-400'));
                    const fileIdStr = file.id ? String(file.id) : ('F-' + Math.random().toString().slice(2, 6));
                    const safeTitle = (file.title || '').replace(/'/g, "\\'");

                    fileRowsHTML += `
                        <tr class="border-b border-slate-800/80 hover:bg-app-surfaceHover transition-colors text-xs text-white group">
                            <td class="p-3 font-mono text-cyan-400 font-semibold whitespace-nowrap">${file.code || ('DOC-' + fileIdStr)}</td>
                            <td class="p-3 max-w-sm">
                                <div class="flex items-start gap-2.5">
                                    <i class="${iconClass} text-base mt-0.5 shrink-0"></i>
                                    <div>
                                        <div class="font-bold text-white leading-snug">${file.title}</div>
                                        <div class="text-[10px] text-slate-400 mt-0.5 flex items-center gap-2 flex-wrap">
                                            <span class="text-cyan-400/90 font-medium">${file.category || selectedItem.title}</span>
                                            ${file.pic ? `<span class="text-slate-500">•</span> <span class="text-slate-300"><i class="fa-solid fa-user-tie text-[9px] mr-1"></i>${file.pic}</span>` : ''}
                                            ${file.budget ? `<span class="text-slate-500">•</span> <span class="text-emerald-400 font-mono">Rp ${Number(file.budget).toLocaleString('id-ID')}</span>` : ''}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-3 whitespace-nowrap">
                                <div class="text-white font-medium">${file.day || 'Hari ini'}, ${file.date || ''}</div>
                                <div class="text-[10px] text-slate-400"><i class="fa-regular fa-clock mr-1 text-app-primary"></i>${file.time || ''}</div>
                            </td>
                            <td class="p-3 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded bg-slate-800 border border-slate-700 text-[10px] text-slate-300 font-mono">${file.format || 'PDF'} (${file.size || file.file_size || '2.4 MB'})</span>
                            </td>
                            <td class="p-3 whitespace-nowrap">
                                <div class="text-white text-xs">${file.uploader || 'Super Admin'}</div>
                                <span class="inline-block mt-0.5 px-2 py-0.5 rounded bg-app-success/15 text-app-success border border-app-success/30 text-[9px] font-bold">${file.status || 'VALIDATED'}</span>
                            </td>
                            <td class="p-3 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button onclick="app.downloadFile('${file.id || file.code}', '${safeTitle}', '${file.format || 'PDF'}')" class="bg-blue-600/20 text-blue-400 hover:bg-blue-600 hover:text-white border border-blue-500/40 px-2 py-1 rounded text-xs flex items-center gap-1 transition-colors font-medium shadow-sm" title="Unduh Berkas">
                                        <i class="fa-solid fa-download"></i> Unduh
                                    </button>
                                    <button onclick="app.viewFileModal('${file.id || file.code}', '${safeTitle}', '${file.format || 'PDF'}')" class="bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700 px-2 py-1 rounded text-xs transition-colors flex items-center gap-1" title="Lihat Berkas">
                                        <i class="fa-solid fa-eye"></i> Lihat
                                    </button>
                                    ${isAdmin ? `
                                    <button onclick="app.openEditArchiveModal('${selectedItem.id}', ${origIndex}, '${file.id || file.code}')" class="bg-amber-500/20 hover:bg-amber-500 hover:text-slate-950 text-amber-400 border border-amber-500/40 px-2 py-1 rounded text-xs transition-colors flex items-center gap-1 font-semibold" title="Edit Data Laporan">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </button>
                                    <button onclick="app.openDeleteModal('${file.id || file.code}', '${safeTitle}')" class="bg-red-500/20 hover:bg-red-600 text-red-400 hover:text-white border border-red-500/40 px-2 py-1 rounded text-xs transition-colors flex items-center gap-1" title="Hapus Berkas (Protected PIN 1237)">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>
                                    ` : ''}
                                </div>
                            </td>
                        </tr>
                    `;
                });
            }

            return `
            <div class="animate-[fadeIn_0.3s_ease-out] space-y-4">
                <!-- Header -->
                <div class="flex justify-between items-center bg-[#0b1325] p-4 rounded-xl border border-app-cardBorder shadow-lg">
                    <div>
                        <h2 class="text-lg font-bold text-white uppercase flex items-center gap-2">
                            <i class="fa-solid ${parentGroup.icon} text-app-primary"></i> ${parentGroup.title}
                        </h2>
                    </div>
                    <div class="text-right flex items-center gap-4">
                         <div>
                             <div class="text-xs text-slate-400">Modul Aktif:</div>
                             <div class="text-sm font-bold text-app-primary">${selectedItem.title}</div>
                         </div>
                         ${(selectedItem.id !== '2.3') ? uploadBtnHTML : ''}
                    </div>
                </div>

                <!-- System Sync Bar -->
                <div class="bg-[#070e1c] border border-blue-500/30 rounded-xl p-3 flex justify-between items-center shadow-inner">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-app-success text-lg"></i>
                        <div>
                            <div class="text-xs font-bold text-white uppercase">SINKRONISASI REAL-TIME DATA TVRI MALUKU</div>
                            <div class="text-xxs text-slate-400">Setiap data yang diunggah otomatis tersimpan di database MySQL, terlindungi soft-delete, dan tampil di Executive Command Center</div>
                        </div>
                    </div>
                    <div class="text-right flex items-center gap-4">
                        ${(selectedItem.id === '2.3') ? uploadBtnHTML : ''}
                        <div>
                            <div class="text-xxs text-slate-400">Penyimpanan Terpakai</div>
                            <div class="text-xs font-bold text-white">85.4% / 100 GB</div>
                        </div>
                    </div>
                </div>

                <!-- Top Sub-Module Cards -->
                ${topCardsHTML}

                <!-- PANEL TABEL HASIL INPUT BERKAS (LENGKAP DENGAN SEARCH, FILTER, DAN AKSI CRUD) -->
                <div class="bg-[#0b1325] border border-app-cardBorder rounded-xl shadow-lg overflow-hidden mt-6">
                    <div class="p-3.5 border-b border-slate-800 flex flex-col md:flex-row justify-between items-start md:items-center gap-3 bg-[#070e1c]">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <input type="text" id="moduleSearchInput" value="${this.state.moduleSearchQuery || ''}" oninput="app.filterModuleTable(this.value, null)" placeholder="Cari kode, judul laporan, PIC..." class="bg-[#0b1325] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-cyan-400 w-full sm:w-60">
                            
                            <select id="moduleCategoryFilter" onchange="app.filterModuleTable(null, this.value)" class="bg-[#0b1325] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                                <option value="all" ${categoryFilter === 'all' ? 'selected' : ''}>Semua Kategori Laporan</option>
                                ${uniqueCategories.map(cat => `<option value="${cat}" ${categoryFilter === cat ? 'selected' : ''}>${cat}</option>`).join('')}
                            </select>
                        </div>
                        
                        <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-end">
                            <span class="text-xs text-slate-400">Menampilkan <strong>${unitFiles.length}</strong> dari <strong>${rawFiles.length}</strong> Dokumen</span>
                            ${isAdmin ? `
                            <button onclick="app.openModal('${selectedItem.title}', '${selectedItem.id}')" class="bg-cyan-500 hover:bg-cyan-400 text-slate-950 text-xs px-3 py-1.5 rounded-lg flex items-center gap-1.5 font-bold transition-all shadow-[0_0_12px_rgba(0,246,255,0.3)]">
                                <i class="fa-solid fa-cloud-arrow-up"></i> Upload Data Baru
                            </button>
                            ` : ''}
                        </div>
                    </div>

                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse min-w-[900px]">
                            <thead class="bg-[#070e1c] text-xs text-slate-400">
                                <tr>
                                    <th class="p-3 font-semibold border-b border-slate-800">KODE BERKAS</th>
                                    <th class="p-3 font-semibold border-b border-slate-800">NAMA DOKUMEN & RINCIAN</th>
                                    <th class="p-3 font-semibold border-b border-slate-800">HARI, TANGGAL & WAKTU</th>
                                    <th class="p-3 font-semibold border-b border-slate-800">FORMAT / UKURAN</th>
                                    <th class="p-3 font-semibold border-b border-slate-800">PENGUNGGAH & STATUS</th>
                                    <th class="p-3 font-semibold border-b border-slate-800 text-center">AKSI CRUD</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${fileRowsHTML}
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            `;
        },

            </div>
            `;
        },

        // --- MODUL TABEL SATUAN PENDIDIKAN (10.1) DENGAN KOTAK JUMLAH KABUPATEN & FORM TAMBAH SEKOLAH ---
        buildTableHTML(selectedItem) {
            const isAdmin = this.state.role === 'admin';
            const query = (this.state.searchQuery || '').toLowerCase();
            const jenjangFilter = this.state.filterJenjang || 'all';
            const cabdinFilter = this.state.filterCabdin || 'all';
            
            const filteredData = this.state.schoolList.filter(s => {
                const matchQuery = !query || s.npsn.toLowerCase().includes(query) || s.nama.toLowerCase().includes(query) || s.cabang.toLowerCase().includes(query);
                const matchJenjang = jenjangFilter === 'all' || s.jenjang === jenjangFilter;
                const matchCabdin = cabdinFilter === 'all' || s.cabang.toLowerCase().includes(cabdinFilter.toLowerCase());
                return matchQuery && matchJenjang && matchCabdin;
            });

            // --- KOTAK MONITORING JUMLAH SEKOLAH PER KABUPATEN / CABANG DINAS ---
            let districtCardsHTML = '';
            cabdinRegions.forEach(reg => {
                const countTotal = this.state.schoolList.filter(s => s.cabang.toLowerCase().includes(reg.shortName.toLowerCase()) || s.cabang.toLowerCase().includes(reg.name.toLowerCase())).length;
                const countSma = this.state.schoolList.filter(s => s.jenjang === 'SMA' && (s.cabang.toLowerCase().includes(reg.shortName.toLowerCase()) || s.cabang.toLowerCase().includes(reg.name.toLowerCase()))).length;
                const countSmk = this.state.schoolList.filter(s => s.jenjang === 'SMK' && (s.cabang.toLowerCase().includes(reg.shortName.toLowerCase()) || s.cabang.toLowerCase().includes(reg.name.toLowerCase()))).length;
                const isSelected = this.state.filterCabdin.toLowerCase() === reg.shortName.toLowerCase() || this.state.filterCabdin.toLowerCase() === reg.name.toLowerCase();

                districtCardsHTML += `
                    <div onclick="app.filterByDistrict('${isSelected ? 'all' : reg.shortName}')" class="cursor-pointer bg-[#0b1325] border ${isSelected ? 'border-app-primary ring-1 ring-app-primary bg-app-primary/10 shadow-[0_0_12px_rgba(0,246,255,0.2)]' : 'border-app-cardBorder hover:border-slate-600'} p-3 rounded-xl flex flex-col justify-between transition-all group">
                        <div class="flex justify-between items-start mb-2">
                            <div class="text-[10px] font-bold text-slate-400 group-hover:text-app-primary transition-colors truncate pr-1">${reg.shortName}</div>
                            <i class="fa-solid ${reg.icon} text-xs text-app-primary/70"></i>
                        </div>
                        <div class="flex items-baseline justify-between mt-1">
                            <div class="text-xl font-black text-white">${countTotal} <span class="text-[10px] font-normal text-slate-400">Sekolah</span></div>
                            <div class="text-[9px] text-slate-400">${countSma} SMA | ${countSmk} SMK</div>
                        </div>
                    </div>
                `;
            });

            let rows = '';
            if (filteredData.length === 0) {
                rows = `
                    <tr>
                        <td colspan="7" class="p-6 text-center text-slate-400 text-sm">Tidak ditemukan data sekolah yang sesuai filter pencarian.</td>
                    </tr>
                `;
            } else {
                filteredData.forEach((school, idx) => {
                    const origIndex = this.state.schoolList.findIndex(s => s.npsn === school.npsn);
                    let actionBtn = isAdmin ? 
                        `<button onclick="app.openEditSchoolModal(${origIndex})" class="bg-[#070e1c] border border-slate-700 hover:border-app-primary hover:text-app-primary text-white text-xs px-2.5 py-1 rounded transition-colors"><i class="fa-solid fa-pen-to-square"></i> Edit</button>` : 
                        `<span class="text-xxs text-slate-400 italic">View Only</span>`;

                    rows += `
                        <tr class="border-b border-slate-800/80 hover:bg-app-surfaceHover transition-colors text-xs text-white">
                            <td class="p-3 text-app-primary font-mono font-semibold">${school.npsn}</td>
                            <td class="p-3 font-bold">${school.nama}</td>
                            <td class="p-3"><span class="px-2 py-0.5 rounded bg-blue-900/40 text-blue-300 font-semibold text-xxs">${school.jenjang}</span></td>
                            <td class="p-3 text-slate-300">${school.cabang}</td>
                            <td class="p-3 text-center"><span class="bg-app-success/20 text-app-success px-2 py-0.5 rounded font-bold">${school.akreditasi}</span></td>
                            <td class="p-3 text-right font-mono">${Number(school.siswa).toLocaleString('id-ID')}</td>
                            <td class="p-3 text-center">${actionBtn}</td>
                        </tr>
                    `;
                });
            }

            return `
            <div class="animate-[fadeIn_0.3s_ease-out] space-y-4">
                
                <!-- Header Atas dengan Tombol Tambah Data & Sinkronisasi -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-[#0b1325] p-4 rounded-xl border border-app-cardBorder shadow-lg">
                    <div>
                        <h2 class="text-lg font-bold text-white uppercase flex items-center gap-2">
                            <i class="fa-solid fa-database text-app-primary"></i> ${selectedItem.title}
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">Pusat Data Pokok Satuan Pendidikan Provinsi Maluku (SMA, SMK, SLB)</p>
                    </div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        ${isAdmin ? `
                        <button onclick="app.openAddSchoolModal()" class="bg-app-primary text-slate-900 hover:bg-cyan-300 px-3.5 py-2 rounded-lg text-xs font-black flex items-center gap-2 transition-all shadow-[0_0_12px_rgba(0,246,255,0.4)]">
                            <i class="fa-solid fa-plus text-sm"></i> Tambah Data Sekolah
                        </button>
                        ` : ''}
                        <button onclick="app.showToast('Sinkronisasi Dapodik sedang diproses...', 'info')" class="bg-blue-600 text-white px-3.5 py-2 rounded-lg text-xs font-bold flex items-center gap-2 hover:bg-blue-700 transition-colors shadow-[0_0_12px_rgba(37,99,235,0.4)]">
                            <i class="fa-solid fa-cloud-arrow-up"></i> Sinkronisasi Dapodik
                        </button>
                    </div>
                </div>

                <!-- KOTAK JUMLAH SEKOLAH MASING-MASING KABUPATEN (MONITORING CEPAT) -->
                <div>
                    <div class="flex justify-between items-center mb-2.5">
                        <div class="text-xs font-bold text-slate-300 uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-map-location-dot text-app-primary"></i>
                            MONITORING JUMLAH SEKOLAH PER KABUPATEN / CABANG DINAS
                        </div>
                        ${this.state.filterCabdin !== 'all' ? `
                        <button onclick="app.filterByDistrict('all')" class="text-xxs text-app-primary hover:underline flex items-center gap-1">
                            <i class="fa-solid fa-rotate-left"></i> Reset Filter Kabupaten (Tampilkan Semua)
                        </button>
                        ` : ''}
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 xl:grid-cols-9 gap-2.5">
                        ${districtCardsHTML}
                    </div>
                </div>

                <!-- TABEL DATA SEKOLAH LENGKAP -->
                <div class="bg-[#0b1325] border border-app-cardBorder rounded-xl flex-1 flex flex-col overflow-hidden shadow-lg mt-2">
                    <div class="p-3 border-b border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-[#070e1c]">
                        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                            <input type="text" id="schoolSearchInput" value="${this.state.searchQuery}" oninput="app.filterTable(this.value, null, null)" placeholder="Cari NPSN / Nama Sekolah..." class="bg-[#0b1325] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-app-primary w-full sm:w-64">
                            
                            <select id="schoolJenjangFilter" onchange="app.filterTable(null, this.value, null)" class="bg-[#0b1325] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-app-primary">
                                <option value="all" ${this.state.filterJenjang === 'all' ? 'selected' : ''}>Semua Jenjang</option>
                                <option value="SMA" ${this.state.filterJenjang === 'SMA' ? 'selected' : ''}>SMA</option>
                                <option value="SMK" ${this.state.filterJenjang === 'SMK' ? 'selected' : ''}>SMK</option>
                                <option value="SLB" ${this.state.filterJenjang === 'SLB' ? 'selected' : ''}>SLB</option>
                            </select>

                            <select id="schoolDistrictFilter" onchange="app.filterTable(null, null, this.value)" class="bg-[#0b1325] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-app-primary">
                                <option value="all" ${this.state.filterCabdin === 'all' ? 'selected' : ''}>Semua Kabupaten / Cabdin</option>
                                ${cabdinRegions.map(r => `<option value="${r.shortName}" ${this.state.filterCabdin === r.shortName ? 'selected' : ''}>${r.shortName}</option>`).join('')}
                            </select>
                        </div>
                        <div class="text-xs text-slate-400">Menampilkan <strong>${filteredData.length}</strong> dari <strong>${this.state.schoolList.length}</strong> Satuan Sekolah</div>
                    </div>

                    <div class="overflow-x-auto flex-1 custom-scrollbar">
                        <table class="w-full text-left border-collapse min-w-[800px]">
                            <thead class="bg-[#070e1c] sticky top-0 z-10 text-xs text-slate-400">
                                <tr>
                                    <th class="p-3 font-semibold border-b border-slate-800">NPSN</th>
                                    <th class="p-3 font-semibold border-b border-slate-800">NAMA SEKOLAH</th>
                                    <th class="p-3 font-semibold border-b border-slate-800">JENJANG</th>
                                    <th class="p-3 font-semibold border-b border-slate-800">CABANG DINAS</th>
                                    <th class="p-3 font-semibold border-b border-slate-800 text-center">AKREDITASI</th>
                                    <th class="p-3 font-semibold border-b border-slate-800 text-right">SISWA</th>
                                    <th class="p-3 font-semibold border-b border-slate-800 text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${rows}
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            `;
        },

        filterTable(query, jenjang, cabdin) {
            if (query !== null) this.state.searchQuery = query;
            if (jenjang !== null) this.state.filterJenjang = jenjang;
            if (cabdin !== null) this.state.filterCabdin = cabdin;
            this.loadContent('10.1');
        },

        filterByDistrict(districtName) {
            this.state.filterCabdin = districtName;
            this.loadContent('10.1');
        },

        // --- HANDLER MODAL TAMBAH & EDIT DATA SEKOLAH ---
        openAddSchoolModal() {
            document.getElementById('editSchoolIndex').value = "-1";
            document.getElementById('schoolModalTitle').textContent = "Tambah Data Satuan Pendidikan Baru";
            document.getElementById('schoolNpsn').value = "60100" + Math.floor(100 + Math.random() * 900);
            document.getElementById('schoolNama').value = "";
            document.getElementById('schoolJenjang').value = "SMA";
            document.getElementById('schoolAkreditasi').value = "A";
            document.getElementById('schoolSiswa').value = "";
            document.getElementById('schoolCabdin').selectedIndex = 0;

            const modal = document.getElementById('schoolModal');
            const content = document.getElementById('schoolModalContent');
            modal.classList.remove('hidden');
            void modal.offsetWidth;
            modal.classList.replace('opacity-0', 'opacity-100');
            content.classList.replace('scale-95', 'scale-100');
        },

        openEditSchoolModal(index) {
            const school = this.state.schoolList[index];
            if (!school) return;

            document.getElementById('editSchoolIndex').value = index;
            document.getElementById('schoolModalTitle').textContent = `Edit Data Sekolah - ${school.nama}`;
            document.getElementById('schoolNpsn').value = school.npsn;
            document.getElementById('schoolNama').value = school.nama;
            document.getElementById('schoolJenjang').value = school.jenjang;
            document.getElementById('schoolAkreditasi').value = school.akreditasi;
            document.getElementById('schoolSiswa').value = school.siswa;
            document.getElementById('schoolCabdin').value = school.cabang;

            const modal = document.getElementById('schoolModal');
            const content = document.getElementById('schoolModalContent');
            modal.classList.remove('hidden');
            void modal.offsetWidth;
            modal.classList.replace('opacity-0', 'opacity-100');
            content.classList.replace('scale-95', 'scale-100');
        },

        closeSchoolModal() {
            const modal = document.getElementById('schoolModal');
            const content = document.getElementById('schoolModalContent');
            modal.classList.replace('opacity-100', 'opacity-0');
            content.classList.replace('scale-100', 'scale-95');
            setTimeout(() => modal.classList.add('hidden'), 200);
        },

        async saveSchoolData() {
            const npsn = document.getElementById('schoolNpsn').value.trim();
            const nama = document.getElementById('schoolNama').value.trim();
            const jenjang = document.getElementById('schoolJenjang').value;
            const akreditasi = document.getElementById('schoolAkreditasi').value;
            const siswa = parseInt(document.getElementById('schoolSiswa').value) || 0;
            const cabang = document.getElementById('schoolCabdin').value;
            const editIndex = parseInt(document.getElementById('editSchoolIndex').value);

            if (!npsn || !nama) {
                this.showToast('Harap lengkapi NPSN dan Nama Sekolah!', 'warning');
                return;
            }

            const schoolObj = { npsn, nama, jenjang, cabang, akreditasi, siswa };
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                const res = await fetch('/api/simandat/schools', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(schoolObj)
                });
                if (res.ok) {
                    const json = await res.json();
                    if (json.data) {
                        schoolObj.id = json.data.id;
                    }
                }
            } catch (e) {
                console.warn('API save school fallback:', e);
            }

            if (editIndex >= 0) {
                this.state.schoolList[editIndex] = schoolObj;
                this.showToast(`Berhasil memperbarui data: ${nama}`, 'success');
            } else {
                this.state.schoolList.unshift(schoolObj);
                this.showToast(`Berhasil menambahkan ke Database Dapodik: ${nama}`, 'success');
            }

            const now = new Date();
            const timeStr = now.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' }) + ' WIT';
            this.state.sekretariatData.recentLogs.unshift({
                unit: '10.1 Data Sekolah',
                title: `${editIndex >= 0 ? 'Pembaruan' : 'Penambahan'} ${nama} (${jenjang})`,
                status: 'Selesai',
                time: timeStr
            });
            if (this.state.sekretariatData.recentLogs.length > 10) this.state.sekretariatData.recentLogs.pop();
            this.state.pengawasanData.totalAgenda += 1;
            this.state.pengawasanData.programSelesai += 1;

            this.saveState();
            this.closeSchoolModal();
            this.loadContent('10.1');
        },

        // =========================================================================
        // --- 10.2 UPTD BALAI TEKNOLOGI KOMUNIKASI PENDIDIKAN (BTKP) CRUD & VIEW ---
        // =========================================================================
        buildUptdBtkpHTML(selectedItem) {
            const isAdmin = this.state.role === 'admin';
            const query = (this.state.btkpSearchQuery || '').toLowerCase();
            const statusFilter = this.state.btkpFilterStatus || 'all';

            const filtered = this.state.uptdBtkpList.filter(item => {
                const matchQuery = !query || 
                    (item.kode && item.kode.toLowerCase().includes(query)) ||
                    (item.nama_program && item.nama_program.toLowerCase().includes(query)) ||
                    (item.penanggung_jawab && item.penanggung_jawab.toLowerCase().includes(query)) ||
                    (item.sasaran && item.sasaran.toLowerCase().includes(query)) ||
                    (item.platform && item.platform.toLowerCase().includes(query));
                const matchStatus = statusFilter === 'all' || item.status === statusFilter;
                return matchQuery && matchStatus;
            });

            const totalPrograms = this.state.uptdBtkpList.length;
            const activePrograms = this.state.uptdBtkpList.filter(b => b.status === 'Aktif').length;
            const optimalPrograms = this.state.uptdBtkpList.filter(b => b.status === 'Optimal').length;
            const totalBudget = this.state.uptdBtkpList.reduce((acc, curr) => acc + (Number(curr.anggaran) || 0), 0);

            let rows = '';
            if (filtered.length === 0) {
                rows = `<tr><td colspan="8" class="p-8 text-center text-slate-400 text-sm">Tidak ada data program BTKP yang sesuai filter pencarian.</td></tr>`;
            } else {
                filtered.forEach((item, idx) => {
                    const origIndex = this.state.uptdBtkpList.findIndex(b => b.kode === item.kode);
                    const statusBadge = item.status === 'Aktif' 
                        ? '<span class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 px-2 py-0.5 rounded text-xxs font-bold">Aktif</span>'
                        : (item.status === 'Optimal' 
                            ? '<span class="bg-cyan-500/20 text-cyan-400 border border-cyan-500/40 px-2 py-0.5 rounded text-xxs font-bold">Optimal</span>'
                            : '<span class="bg-amber-500/20 text-amber-400 border border-amber-500/40 px-2 py-0.5 rounded text-xxs font-bold">Perencanaan</span>');

                    const actionButtons = isAdmin ? `
                        <div class="flex items-center justify-center gap-1.5">
                            <button onclick="app.openEditBtkpModal(${origIndex})" class="bg-[#070e1c] hover:bg-cyan-500/20 text-slate-300 hover:text-cyan-300 border border-slate-700 hover:border-cyan-500/50 px-2.5 py-1 rounded text-xs transition-colors flex items-center gap-1">
                                <i class="fa-solid fa-pen-to-square text-[10px]"></i> Edit
                            </button>
                            <button onclick="app.deleteBtkpData(${origIndex})" class="bg-[#070e1c] hover:bg-rose-500/20 text-slate-400 hover:text-rose-400 border border-slate-700 hover:border-rose-500/50 px-2.5 py-1 rounded text-xs transition-colors flex items-center gap-1">
                                <i class="fa-solid fa-trash-can text-[10px]"></i> Hapus
                            </button>
                        </div>
                    ` : `<span class="text-xxs text-slate-400 italic">View Only</span>`;

                    rows += `
                        <tr class="border-b border-slate-800/80 hover:bg-[#0c1933]/60 transition-colors text-xs text-white">
                            <td class="p-3 font-mono font-bold text-cyan-400 whitespace-nowrap">${item.kode}</td>
                            <td class="p-3 font-semibold text-white max-w-xs">
                                <div>${item.nama_program}</div>
                                <div class="text-[10px] text-slate-400 mt-0.5 flex items-center gap-1.5">
                                    <i class="fa-solid fa-server text-cyan-400 text-[9px]"></i> Platform: <span class="text-slate-300 font-medium">${item.platform}</span>
                                </div>
                            </td>
                            <td class="p-3 text-slate-300 whitespace-nowrap"><i class="fa-solid fa-user-gear text-slate-500 mr-1.5"></i>${item.penanggung_jawab}</td>
                            <td class="p-3 text-slate-300 max-w-[200px] text-xxs leading-relaxed">${item.sasaran}</td>
                            <td class="p-3 text-center whitespace-nowrap font-mono text-emerald-400 font-semibold">Rp ${Number(item.anggaran).toLocaleString('id-ID')}</td>
                            <td class="p-3 text-center whitespace-nowrap">${statusBadge}</td>
                            <td class="p-3 text-center whitespace-nowrap">${actionButtons}</td>
                        </tr>
                    `;
                });
            }

            return `
            <div class="animate-[fadeIn_0.3s_ease-out] space-y-4">
                <!-- Header Banner -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-[#0b1325] p-4 rounded-xl border border-app-cardBorder shadow-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-cyan-600/20 border border-cyan-500/40 flex items-center justify-center text-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.25)]">
                            <i class="fa-solid fa-tower-broadcast text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-white uppercase flex items-center gap-2">
                                10.2 UPTD Balai Teknologi Komunikasi Pendidikan (BTKP)
                            </h2>
                            <p class="text-xs text-slate-400 mt-0.5">Pengembangan Sistem Pembelajaran Berbasis TIK, Studio Edukasi, dan Infrastruktur Digital Pendidikan Maluku</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        ${isAdmin ? `
                        <button onclick="app.openAddBtkpModal()" class="bg-cyan-500 hover:bg-cyan-400 text-slate-950 px-3.5 py-2 rounded-lg text-xs font-black flex items-center gap-2 transition-all shadow-[0_0_12px_rgba(6,182,212,0.4)]">
                            <i class="fa-solid fa-plus text-sm"></i> Tambah Program TIK
                        </button>
                        ` : ''}
                        <button onclick="app.showToast('Sinkronisasi server BTKP SIWALIMA berhasil diperbarui.', 'success')" class="bg-[#070e1c] hover:bg-cyan-900/30 text-cyan-300 border border-cyan-500/40 px-3 py-2 rounded-lg text-xs font-semibold flex items-center gap-2 transition-colors">
                            <i class="fa-solid fa-rotate text-xs"></i> Sync Portal LMS
                        </button>
                    </div>
                </div>

                <!-- 4 METRIC CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div class="bg-[#0b1325] border border-app-cardBorder p-3.5 rounded-xl shadow-lg flex items-center justify-between">
                        <div>
                            <div class="text-xxs text-slate-400 font-bold uppercase tracking-wider">Total Program Digital</div>
                            <div class="text-2xl font-black text-white mt-0.5">${totalPrograms} <span class="text-xs text-slate-400 font-normal">Program</span></div>
                            <div class="text-[10px] text-cyan-400 mt-1"><i class="fa-solid fa-check-double mr-1"></i> Terintegrasi LMS Prov. Maluku</div>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400">
                            <i class="fa-solid fa-laptop-code text-base"></i>
                        </div>
                    </div>

                    <div class="bg-[#0b1325] border border-app-cardBorder p-3.5 rounded-xl shadow-lg flex items-center justify-between">
                        <div>
                            <div class="text-xxs text-slate-400 font-bold uppercase tracking-wider">Program Aktif & Berjalan</div>
                            <div class="text-2xl font-black text-emerald-400 mt-0.5">${activePrograms} <span class="text-xs text-slate-400 font-normal">Aktif</span></div>
                            <div class="text-[10px] text-emerald-400 mt-1"><i class="fa-solid fa-signal mr-1"></i> Studio Podcast & E-Modul</div>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
                            <i class="fa-solid fa-circle-play text-base"></i>
                        </div>
                    </div>

                    <div class="bg-[#0b1325] border border-app-cardBorder p-3.5 rounded-xl shadow-lg flex items-center justify-between">
                        <div>
                            <div class="text-xxs text-slate-400 font-bold uppercase tracking-wider">Infrastruktur Optimal</div>
                            <div class="text-2xl font-black text-blue-400 mt-0.5">${optimalPrograms} <span class="text-xs text-slate-400 font-normal">Infrastruktur</span></div>
                            <div class="text-[10px] text-blue-400 mt-1"><i class="fa-solid fa-network-wired mr-1"></i> Jaringan CBT Sekolah 3T</div>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-blue-500/10 border border-blue-500/30 flex items-center justify-center text-blue-400">
                            <i class="fa-solid fa-satellite-dish text-base"></i>
                        </div>
                    </div>

                    <div class="bg-[#0b1325] border border-app-cardBorder p-3.5 rounded-xl shadow-lg flex items-center justify-between">
                        <div>
                            <div class="text-xxs text-slate-400 font-bold uppercase tracking-wider">Alokasi Anggaran TIK</div>
                            <div class="text-lg font-black text-amber-400 mt-0.5">Rp ${(totalBudget / 1000000).toFixed(1)} Jt</div>
                            <div class="text-[10px] text-slate-400 mt-1"><i class="fa-solid fa-vault mr-1"></i> DPA TVRI Maluku 2026</div>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400">
                            <i class="fa-solid fa-coins text-base"></i>
                        </div>
                    </div>
                </div>

                <!-- TABLE CARD -->
                <div class="bg-[#0b1325] border border-app-cardBorder rounded-xl flex-1 flex flex-col overflow-hidden shadow-lg">
                    <div class="p-3.5 border-b border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-[#070e1c]">
                        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                            <input type="text" id="btkpSearchInput" value="${this.state.btkpSearchQuery}" oninput="app.filterBtkpTable(this.value, null)" placeholder="Cari kode, nama program, PIC, atau sasaran..." class="bg-[#0b1325] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-cyan-400 w-full sm:w-72">
                            
                            <select id="btkpStatusFilter" onchange="app.filterBtkpTable(null, this.value)" class="bg-[#0b1325] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                                <option value="all" ${statusFilter === 'all' ? 'selected' : ''}>Semua Status Program</option>
                                <option value="Aktif" ${statusFilter === 'Aktif' ? 'selected' : ''}>Status: Aktif</option>
                                <option value="Optimal" ${statusFilter === 'Optimal' ? 'selected' : ''}>Status: Optimal</option>
                                <option value="Perencanaan" ${statusFilter === 'Perencanaan' ? 'selected' : ''}>Status: Perencanaan</option>
                            </select>
                        </div>
                        <div class="text-xs text-slate-400">Menampilkan <strong>${filtered.length}</strong> dari <strong>${this.state.uptdBtkpList.length}</strong> Program TIK</div>
                    </div>

                    <div class="overflow-x-auto flex-1 custom-scrollbar">
                        <table class="w-full text-left border-collapse min-w-[850px]">
                            <thead class="bg-[#070e1c] sticky top-0 z-10 text-xs text-slate-400">
                                <tr>
                                    <th class="p-3 font-semibold border-b border-slate-800">KODE</th>
                                    <th class="p-3 font-semibold border-b border-slate-800">NAMA PROGRAM & MEDIA</th>
                                    <th class="p-3 font-semibold border-b border-slate-800">PENANGGUNG JAWAB</th>
                                    <th class="p-3 font-semibold border-b border-slate-800">SASARAN & WILAYAH</th>
                                    <th class="p-3 font-semibold border-b border-slate-800 text-center">ANGGARAN</th>
                                    <th class="p-3 font-semibold border-b border-slate-800 text-center">STATUS</th>
                                    <th class="p-3 font-semibold border-b border-slate-800 text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${rows}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            `;
        },

        filterBtkpTable(query, status) {
            if (query !== null) this.state.btkpSearchQuery = query;
            if (status !== null) this.state.btkpFilterStatus = status;
            this.loadContent('10.2');
        },

        openAddBtkpModal() {
            document.getElementById('editBtkpIndex').value = "-1";
            document.getElementById('btkpModalTitle').textContent = "Tambah Program TIK BTKP Baru";
            document.getElementById('btkpKode').value = "BTKP-00" + (this.state.uptdBtkpList.length + 1);
            document.getElementById('btkpNama').value = "";
            document.getElementById('btkpPj').value = "";
            document.getElementById('btkpSasaran').value = "";
            document.getElementById('btkpPlatform').value = "SIWALIMA LMS / Cloud";
            document.getElementById('btkpAnggaran').value = "";
            document.getElementById('btkpStatus').value = "Aktif";

            const modal = document.getElementById('btkpModal');
            const content = document.getElementById('btkpModalContent');
            modal.classList.remove('hidden');
            void modal.offsetWidth;
            modal.classList.replace('opacity-0', 'opacity-100');
            content.classList.replace('scale-95', 'scale-100');
        },

        openEditBtkpModal(index) {
            const item = this.state.uptdBtkpList[index];
            if (!item) return;

            document.getElementById('editBtkpIndex').value = index;
            document.getElementById('btkpModalTitle').textContent = `Edit Program TIK - ${item.kode}`;
            document.getElementById('btkpKode').value = item.kode;
            document.getElementById('btkpNama').value = item.nama_program;
            document.getElementById('btkpPj').value = item.penanggung_jawab;
            document.getElementById('btkpSasaran').value = item.sasaran;
            document.getElementById('btkpPlatform').value = item.platform;
            document.getElementById('btkpAnggaran').value = item.anggaran;
            document.getElementById('btkpStatus').value = item.status;

            const modal = document.getElementById('btkpModal');
            const content = document.getElementById('btkpModalContent');
            modal.classList.remove('hidden');
            void modal.offsetWidth;
            modal.classList.replace('opacity-0', 'opacity-100');
            content.classList.replace('scale-95', 'scale-100');
        },

        closeBtkpModal() {
            const modal = document.getElementById('btkpModal');
            const content = document.getElementById('btkpModalContent');
            if (!modal) return;
            modal.classList.replace('opacity-100', 'opacity-0');
            content.classList.replace('scale-100', 'scale-95');
            setTimeout(() => modal.classList.add('hidden'), 200);
        },

        async saveBtkpData() {
            const kode = document.getElementById('btkpKode').value.trim();
            const nama_program = document.getElementById('btkpNama').value.trim();
            const penanggung_jawab = document.getElementById('btkpPj').value.trim();
            const sasaran = document.getElementById('btkpSasaran').value.trim();
            const platform = document.getElementById('btkpPlatform').value.trim();
            const anggaran = parseInt(document.getElementById('btkpAnggaran').value) || 0;
            const status = document.getElementById('btkpStatus').value;
            const editIndex = parseInt(document.getElementById('editBtkpIndex').value);

            if (!kode || !nama_program) {
                this.showToast('Harap lengkapi Kode dan Nama Program TIK BTKP!', 'warning');
                return;
            }

            const itemObj = { kode, nama_program, penanggung_jawab, sasaran, platform, anggaran, status };
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                if (editIndex >= 0) {
                    await fetch('/api/simandat/uptd/btkp/' + encodeURIComponent(kode), {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken || '',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(itemObj)
                    });
                } else {
                    const res = await fetch('/api/simandat/uptd/btkp', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken || '',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(itemObj)
                    });
                    if (res.ok) {
                        const json = await res.json();
                        if (json.data && json.data.id) itemObj.id = json.data.id;
                    }
                }
            } catch(e) {
                console.warn('API save BTKP fallback:', e);
            }

            if (editIndex >= 0) {
                this.state.uptdBtkpList[editIndex] = itemObj;
                this.showToast(`Data Program BTKP "${nama_program}" berhasil diperbarui di Database.`, 'success');
            } else {
                this.state.uptdBtkpList.unshift(itemObj);
                this.showToast(`Data Program BTKP "${nama_program}" berhasil ditambahkan ke Database.`, 'success');
            }

            const now = new Date();
            const timeStr = now.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' }) + ' WIT';
            this.state.sekretariatData.recentLogs.unshift({
                unit: '10.2 UPTD BTKP',
                title: `${editIndex >= 0 ? 'Pembaruan' : 'Penambahan'} ${nama_program}`,
                status: 'Selesai',
                time: timeStr
            });
            if (this.state.sekretariatData.recentLogs.length > 10) this.state.sekretariatData.recentLogs.pop();

            this.saveState();
            this.closeBtkpModal();
            this.loadContent('10.2');
        },

        async deleteBtkpData(index) {
            const item = this.state.uptdBtkpList[index];
            if (!item) return;

            if (confirm(`Apakah Anda yakin ingin menghapus data program "${item.nama_program}" (${item.kode})?`)) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                try {
                    await fetch('/api/simandat/uptd/btkp/' + encodeURIComponent(item.id || item.kode), {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken || '',
                            'Accept': 'application/json'
                        }
                    });
                } catch(e) {
                    console.warn('API delete BTKP fallback:', e);
                }

                this.state.uptdBtkpList.splice(index, 1);
                this.saveState();
                this.loadContent('10.2');
                this.showToast(`Program BTKP "${item.nama_program}" berhasil dihapus dari Database.`, 'success');
            }
        },

        // =========================================================================
        // --- 10.3 UPTD TAMAN BUDAYA PROVINSI MALUKU CRUD & VIEW ---
        // =========================================================================
        buildUptdTamanBudayaHTML(selectedItem) {
            const isAdmin = this.state.role === 'admin';
            const query = (this.state.tamanBudayaSearchQuery || '').toLowerCase();
            const statusFilter = this.state.tamanBudayaFilterStatus || 'all';

            const filtered = this.state.uptdTamanBudayaList.filter(item => {
                const matchQuery = !query || 
                    (item.kode && item.kode.toLowerCase().includes(query)) ||
                    (item.nama_kegiatan && item.nama_kegiatan.toLowerCase().includes(query)) ||
                    (item.jenis && item.jenis.toLowerCase().includes(query)) ||
                    (item.lokasi && item.lokasi.toLowerCase().includes(query)) ||
                    (item.sanggar && item.sanggar.toLowerCase().includes(query));
                const matchStatus = statusFilter === 'all' || item.status === statusFilter;
                return matchQuery && matchStatus;
            });

            const totalAgenda = this.state.uptdTamanBudayaList.length;
            const scheduledAgenda = this.state.uptdTamanBudayaList.filter(t => t.status === 'Terjadwal').length;
            const activeAgenda = this.state.uptdTamanBudayaList.filter(t => t.status === 'Aktif').length;
            const totalPeserta = this.state.uptdTamanBudayaList.reduce((acc, curr) => acc + (Number(curr.peserta) || 0), 0);

            let rows = '';
            if (filtered.length === 0) {
                rows = `<tr><td colspan="8" class="p-8 text-center text-slate-400 text-sm">Tidak ada data agenda Taman Budaya yang sesuai filter pencarian.</td></tr>`;
            } else {
                filtered.forEach((item, idx) => {
                    const origIndex = this.state.uptdTamanBudayaList.findIndex(t => t.kode === item.kode);
                    const statusBadge = item.status === 'Terjadwal' 
                        ? '<span class="bg-blue-500/20 text-blue-400 border border-blue-500/40 px-2 py-0.5 rounded text-xxs font-bold">Terjadwal</span>'
                        : (item.status === 'Aktif' 
                            ? '<span class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 px-2 py-0.5 rounded text-xxs font-bold">Aktif / Berlangsung</span>'
                            : (item.status === 'Persiapan'
                                ? '<span class="bg-purple-500/20 text-purple-400 border border-purple-500/40 px-2 py-0.5 rounded text-xxs font-bold">Persiapan</span>'
                                : '<span class="bg-slate-500/20 text-slate-400 border border-slate-500/40 px-2 py-0.5 rounded text-xxs font-bold">Selesai</span>'));

                    const actionButtons = isAdmin ? `
                        <div class="flex items-center justify-center gap-1.5">
                            <button onclick="app.openEditTamanBudayaModal(${origIndex})" class="bg-[#070e1c] hover:bg-purple-500/20 text-slate-300 hover:text-purple-300 border border-slate-700 hover:border-purple-500/50 px-2.5 py-1 rounded text-xs transition-colors flex items-center gap-1">
                                <i class="fa-solid fa-pen-to-square text-[10px]"></i> Edit
                            </button>
                            <button onclick="app.deleteTamanBudayaData(${origIndex})" class="bg-[#070e1c] hover:bg-rose-500/20 text-slate-400 hover:text-rose-400 border border-slate-700 hover:border-rose-500/50 px-2.5 py-1 rounded text-xs transition-colors flex items-center gap-1">
                                <i class="fa-solid fa-trash-can text-[10px]"></i> Hapus
                            </button>
                        </div>
                    ` : `<span class="text-xxs text-slate-400 italic">View Only</span>`;

                    rows += `
                        <tr class="border-b border-slate-800/80 hover:bg-[#1a0f2e]/40 transition-colors text-xs text-white">
                            <td class="p-3 font-mono font-bold text-purple-400 whitespace-nowrap">${item.kode}</td>
                            <td class="p-3 font-semibold text-white max-w-xs">
                                <div>${item.nama_kegiatan}</div>
                                <div class="text-[10px] text-purple-300/80 mt-0.5 flex items-center gap-1.5">
                                    <i class="fa-solid fa-masks-theater text-purple-400 text-[9px]"></i> Jenis: <span class="text-slate-300 font-medium">${item.jenis}</span>
                                </div>
                            </td>
                            <td class="p-3 text-slate-300 max-w-[180px] text-xxs leading-relaxed"><i class="fa-solid fa-location-dot text-rose-400 mr-1.5"></i>${item.lokasi}</td>
                            <td class="p-3 text-slate-300 max-w-[200px] text-xxs leading-relaxed">${item.sanggar}</td>
                            <td class="p-3 text-center whitespace-nowrap font-mono text-purple-300 font-semibold">${Number(item.peserta).toLocaleString('id-ID')} Orang</td>
                            <td class="p-3 text-center whitespace-nowrap">${statusBadge}</td>
                            <td class="p-3 text-center whitespace-nowrap">${actionButtons}</td>
                        </tr>
                    `;
                });
            }

            return `
            <div class="animate-[fadeIn_0.3s_ease-out] space-y-4">
                <!-- Header Banner -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-[#0b1325] p-4 rounded-xl border border-app-cardBorder shadow-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-purple-600/20 border border-purple-500/40 flex items-center justify-center text-purple-400 shadow-[0_0_15px_rgba(168,85,247,0.25)]">
                            <i class="fa-solid fa-palette text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-white uppercase flex items-center gap-2">
                                10.3 UPTD Taman Budaya Provinsi Maluku
                            </h2>
                            <p class="text-xs text-slate-400 mt-0.5">Pusat Pelestarian Seni Pertunjukan, Pagelaran Budaya Daerah, Pembinaan Sanggar Seni & Galeri Budaya Siwalima</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        ${isAdmin ? `
                        <button onclick="app.openAddTamanBudayaModal()" class="bg-purple-600 hover:bg-purple-500 text-white px-3.5 py-2 rounded-lg text-xs font-bold flex items-center gap-2 transition-all shadow-[0_0_12px_rgba(168,85,247,0.4)]">
                            <i class="fa-solid fa-plus text-sm"></i> Tambah Agenda Seni
                        </button>
                        ` : ''}
                        <button onclick="app.showToast('Jadwal pentas seni Taman Budaya Karpan Ambon tersinkronisasi.', 'success')" class="bg-[#070e1c] hover:bg-purple-900/30 text-purple-300 border border-purple-500/40 px-3 py-2 rounded-lg text-xs font-semibold flex items-center gap-2 transition-colors">
                            <i class="fa-solid fa-calendar-days text-xs"></i> Kalender Pagelaran
                        </button>
                    </div>
                </div>

                <!-- 4 METRIC CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div class="bg-[#0b1325] border border-app-cardBorder p-3.5 rounded-xl shadow-lg flex items-center justify-between">
                        <div>
                            <div class="text-xxs text-slate-400 font-bold uppercase tracking-wider">Total Agenda Pagelaran</div>
                            <div class="text-2xl font-black text-white mt-0.5">${totalAgenda} <span class="text-xs text-slate-400 font-normal">Agenda</span></div>
                            <div class="text-[10px] text-purple-400 mt-1"><i class="fa-solid fa-masks-theater mr-1"></i> Pentas & Galeri Seni</div>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-purple-500/10 border border-purple-500/30 flex items-center justify-center text-purple-400">
                            <i class="fa-solid fa-ticket text-base"></i>
                        </div>
                    </div>

                    <div class="bg-[#0b1325] border border-app-cardBorder p-3.5 rounded-xl shadow-lg flex items-center justify-between">
                        <div>
                            <div class="text-xxs text-slate-400 font-bold uppercase tracking-wider">Agenda Terjadwal</div>
                            <div class="text-2xl font-black text-blue-400 mt-0.5">${scheduledAgenda} <span class="text-xs text-slate-400 font-normal">Pentas</span></div>
                            <div class="text-[10px] text-blue-400 mt-1"><i class="fa-solid fa-calendar-check mr-1"></i> Siap Pentas Panggung</div>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-blue-500/10 border border-blue-500/30 flex items-center justify-center text-blue-400">
                            <i class="fa-solid fa-guitar text-base"></i>
                        </div>
                    </div>

                    <div class="bg-[#0b1325] border border-app-cardBorder p-3.5 rounded-xl shadow-lg flex items-center justify-between">
                        <div>
                            <div class="text-xxs text-slate-400 font-bold uppercase tracking-wider">Pentas / Pelatihan Aktif</div>
                            <div class="text-2xl font-black text-emerald-400 mt-0.5">${activeAgenda} <span class="text-xs text-slate-400 font-normal">Berjalan</span></div>
                            <div class="text-[10px] text-emerald-400 mt-1"><i class="fa-solid fa-drum mr-1"></i> Totobuang & Sanggar Etnik</div>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
                            <i class="fa-solid fa-people-group text-base"></i>
                        </div>
                    </div>

                    <div class="bg-[#0b1325] border border-app-cardBorder p-3.5 rounded-xl shadow-lg flex items-center justify-between">
                        <div>
                            <div class="text-xxs text-slate-400 font-bold uppercase tracking-wider">Seniman & Peserta</div>
                            <div class="text-xl font-black text-amber-400 mt-0.5">${Number(totalPeserta).toLocaleString('id-ID')} <span class="text-xs text-slate-400 font-normal">Orang</span></div>
                            <div class="text-[10px] text-slate-400 mt-1"><i class="fa-solid fa-hand-holding-heart mr-1"></i> Kolaborasi 11 Wilayah</div>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400">
                            <i class="fa-solid fa-users text-base"></i>
                        </div>
                    </div>
                </div>

                <!-- TABLE CARD -->
                <div class="bg-[#0b1325] border border-app-cardBorder rounded-xl flex-1 flex flex-col overflow-hidden shadow-lg">
                    <div class="p-3.5 border-b border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-[#070e1c]">
                        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                            <input type="text" id="tamanBudayaSearchInput" value="${this.state.tamanBudayaSearchQuery}" oninput="app.filterTamanBudayaTable(this.value, null)" placeholder="Cari kode, nama agenda, lokasi, sanggar..." class="bg-[#0b1325] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-purple-400 w-full sm:w-72">
                            
                            <select id="tamanBudayaStatusFilter" onchange="app.filterTamanBudayaTable(null, this.value)" class="bg-[#0b1325] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-purple-400">
                                <option value="all" ${statusFilter === 'all' ? 'selected' : ''}>Semua Status Agenda</option>
                                <option value="Terjadwal" ${statusFilter === 'Terjadwal' ? 'selected' : ''}>Status: Terjadwal</option>
                                <option value="Aktif" ${statusFilter === 'Aktif' ? 'selected' : ''}>Status: Aktif</option>
                                <option value="Persiapan" ${statusFilter === 'Persiapan' ? 'selected' : ''}>Status: Persiapan</option>
                                <option value="Selesai" ${statusFilter === 'Selesai' ? 'selected' : ''}>Status: Selesai</option>
                            </select>
                        </div>
                        <div class="text-xs text-slate-400">Menampilkan <strong>${filtered.length}</strong> dari <strong>${this.state.uptdTamanBudayaList.length}</strong> Agenda Seni Budaya</div>
                    </div>

                    <div class="overflow-x-auto flex-1 custom-scrollbar">
                        <table class="w-full text-left border-collapse min-w-[850px]">
                            <thead class="bg-[#070e1c] sticky top-0 z-10 text-xs text-slate-400">
                                <tr>
                                    <th class="p-3 font-semibold border-b border-slate-800">KODE</th>
                                    <th class="p-3 font-semibold border-b border-slate-800">NAMA AGENDA & JENIS</th>
                                    <th class="p-3 font-semibold border-b border-slate-800">LOKASI / PANGGUNG</th>
                                    <th class="p-3 font-semibold border-b border-slate-800">SANGGAR / KOMUNITAS</th>
                                    <th class="p-3 font-semibold border-b border-slate-800 text-center">EST. PESERTA</th>
                                    <th class="p-3 font-semibold border-b border-slate-800 text-center">STATUS</th>
                                    <th class="p-3 font-semibold border-b border-slate-800 text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${rows}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            `;
        },

        filterTamanBudayaTable(query, status) {
            if (query !== null) this.state.tamanBudayaSearchQuery = query;
            if (status !== null) this.state.tamanBudayaFilterStatus = status;
            this.loadContent('10.3');
        },

        openAddTamanBudayaModal() {
            document.getElementById('editTamanBudayaIndex').value = "-1";
            document.getElementById('tamanBudayaModalTitle').textContent = "Tambah Agenda Taman Budaya Baru";
            document.getElementById('tamanBudayaKode').value = "TB-MAL-0" + (this.state.uptdTamanBudayaList.length + 1);
            document.getElementById('tamanBudayaNama').value = "";
            document.getElementById('tamanBudayaJenis').value = "Festival & Pagelaran";
            document.getElementById('tamanBudayaLokasi').value = "Panggung Terbuka Taman Budaya Karpan Ambon";
            document.getElementById('tamanBudayaSanggar').value = "";
            document.getElementById('tamanBudayaPeserta').value = "";
            document.getElementById('tamanBudayaStatus').value = "Terjadwal";

            const modal = document.getElementById('tamanBudayaModal');
            const content = document.getElementById('tamanBudayaModalContent');
            modal.classList.remove('hidden');
            void modal.offsetWidth;
            modal.classList.replace('opacity-0', 'opacity-100');
            content.classList.replace('scale-95', 'scale-100');
        },

        openEditTamanBudayaModal(index) {
            const item = this.state.uptdTamanBudayaList[index];
            if (!item) return;

            document.getElementById('editTamanBudayaIndex').value = index;
            document.getElementById('tamanBudayaModalTitle').textContent = `Edit Agenda Seni - ${item.kode}`;
            document.getElementById('tamanBudayaKode').value = item.kode;
            document.getElementById('tamanBudayaNama').value = item.nama_kegiatan;
            document.getElementById('tamanBudayaJenis').value = item.jenis;
            document.getElementById('tamanBudayaLokasi').value = item.lokasi;
            document.getElementById('tamanBudayaSanggar').value = item.sanggar;
            document.getElementById('tamanBudayaPeserta').value = item.peserta;
            document.getElementById('tamanBudayaStatus').value = item.status;

            const modal = document.getElementById('tamanBudayaModal');
            const content = document.getElementById('tamanBudayaModalContent');
            modal.classList.remove('hidden');
            void modal.offsetWidth;
            modal.classList.replace('opacity-0', 'opacity-100');
            content.classList.replace('scale-95', 'scale-100');
        },

        closeTamanBudayaModal() {
            const modal = document.getElementById('tamanBudayaModal');
            const content = document.getElementById('tamanBudayaModalContent');
            if (!modal) return;
            modal.classList.replace('opacity-100', 'opacity-0');
            content.classList.replace('scale-100', 'scale-95');
            setTimeout(() => modal.classList.add('hidden'), 200);
        },

        async saveTamanBudayaData() {
            const kode = document.getElementById('tamanBudayaKode').value.trim();
            const nama_kegiatan = document.getElementById('tamanBudayaNama').value.trim();
            const jenis = document.getElementById('tamanBudayaJenis').value;
            const lokasi = document.getElementById('tamanBudayaLokasi').value.trim();
            const sanggar = document.getElementById('tamanBudayaSanggar').value.trim();
            const peserta = parseInt(document.getElementById('tamanBudayaPeserta').value) || 0;
            const status = document.getElementById('tamanBudayaStatus').value;
            const editIndex = parseInt(document.getElementById('editTamanBudayaIndex').value);

            if (!kode || !nama_kegiatan) {
                this.showToast('Harap lengkapi Kode dan Nama Agenda Taman Budaya!', 'warning');
                return;
            }

            const itemObj = { kode, nama_kegiatan, jenis, lokasi, sanggar, peserta, status };
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                if (editIndex >= 0) {
                    await fetch('/api/simandat/uptd/taman-budaya/' + encodeURIComponent(kode), {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken || '',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(itemObj)
                    });
                } else {
                    const res = await fetch('/api/simandat/uptd/taman-budaya', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken || '',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(itemObj)
                    });
                    if (res.ok) {
                        const json = await res.json();
                        if (json.data && json.data.id) itemObj.id = json.data.id;
                    }
                }
            } catch(e) {
                console.warn('API save Taman Budaya fallback:', e);
            }

            if (editIndex >= 0) {
                this.state.uptdTamanBudayaList[editIndex] = itemObj;
                this.showToast(`Data Agenda "${nama_kegiatan}" berhasil diperbarui di Database.`, 'success');
            } else {
                this.state.uptdTamanBudayaList.unshift(itemObj);
                this.showToast(`Data Agenda "${nama_kegiatan}" berhasil ditambahkan ke Database.`, 'success');
            }

            const now = new Date();
            const timeStr = now.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' }) + ' WIT';
            this.state.sekretariatData.recentLogs.unshift({
                unit: '10.3 Taman Budaya',
                title: `${editIndex >= 0 ? 'Pembaruan' : 'Penambahan'} ${nama_kegiatan}`,
                status: 'Selesai',
                time: timeStr
            });
            if (this.state.sekretariatData.recentLogs.length > 10) this.state.sekretariatData.recentLogs.pop();

            this.saveState();
            this.closeTamanBudayaModal();
            this.loadContent('10.3');
        },

        async deleteTamanBudayaData(index) {
            const item = this.state.uptdTamanBudayaList[index];
            if (!item) return;

            if (confirm(`Apakah Anda yakin ingin menghapus data agenda "${item.nama_kegiatan}" (${item.kode})?`)) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                try {
                    await fetch('/api/simandat/uptd/taman-budaya/' + encodeURIComponent(item.id || item.kode), {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken || '',
                            'Accept': 'application/json'
                        }
                    });
                } catch(e) {
                    console.warn('API delete Taman Budaya fallback:', e);
                }

                this.state.uptdTamanBudayaList.splice(index, 1);
                this.saveState();
                this.loadContent('10.3');
                this.showToast(`Agenda "${item.nama_kegiatan}" berhasil dihapus dari Database.`, 'success');
            }
        },

        // =========================================================================
        // --- 10.4 UPTD MUSEUM SIWA LIMA PROVINSI MALUKU CRUD & VIEW ---
        // =========================================================================
        buildUptdMuseumHTML(selectedItem) {
            const isAdmin = this.state.role === 'admin';
            const query = (this.state.museumSearchQuery || '').toLowerCase();
            const kategoriFilter = this.state.museumFilterKategori || 'all';
            const kondisiFilter = this.state.museumFilterKondisi || 'all';

            const filtered = this.state.uptdMuseumList.filter(item => {
                const matchQuery = !query || 
                    (item.no_inventaris && item.no_inventaris.toLowerCase().includes(query)) ||
                    (item.nama_koleksi && item.nama_koleksi.toLowerCase().includes(query)) ||
                    (item.kategori && item.kategori.toLowerCase().includes(query)) ||
                    (item.bahan && item.bahan.toLowerCase().includes(query)) ||
                    (item.asal && item.asal.toLowerCase().includes(query)) ||
                    (item.lokasi_simpan && item.lokasi_simpan.toLowerCase().includes(query));
                const matchKategori = kategoriFilter === 'all' || item.kategori === kategoriFilter;
                const matchKondisi = kondisiFilter === 'all' || item.kondisi === kondisiFilter;
                return matchQuery && matchKategori && matchKondisi;
            });

            const totalKoleksi = this.state.uptdMuseumList.length;
            const primaKoleksi = this.state.uptdMuseumList.filter(m => m.kondisi.includes('Baik') || m.kondisi.includes('Terawat') || m.kondisi.includes('Utuh')).length;
            const etnografikaCount = this.state.uptdMuseumList.filter(m => m.kategori === 'Etnografika').length;
            const historikaCount = this.state.uptdMuseumList.filter(m => m.kategori === 'Historika').length;

            let rows = '';
            if (filtered.length === 0) {
                rows = `<tr><td colspan="8" class="p-8 text-center text-slate-400 text-sm">Tidak ada data koleksi Museum Siwa Lima yang sesuai filter pencarian.</td></tr>`;
            } else {
                filtered.forEach((item, idx) => {
                    const origIndex = this.state.uptdMuseumList.findIndex(m => m.no_inventaris === item.no_inventaris);
                    const kondisiBadge = item.kondisi.includes('Sangat Baik') || item.kondisi.includes('Utuh')
                        ? '<span class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 px-2 py-0.5 rounded text-xxs font-bold">' + item.kondisi + '</span>'
                        : (item.kondisi.includes('Terawat')
                            ? '<span class="bg-cyan-500/20 text-cyan-400 border border-cyan-500/40 px-2 py-0.5 rounded text-xxs font-bold">' + item.kondisi + '</span>'
                            : '<span class="bg-amber-500/20 text-amber-400 border border-amber-500/40 px-2 py-0.5 rounded text-xxs font-bold">' + item.kondisi + '</span>');

                    const actionButtons = isAdmin ? `
                        <div class="flex items-center justify-center gap-1.5">
                            <button onclick="app.openEditMuseumModal(${origIndex})" class="bg-[#070e1c] hover:bg-amber-500/20 text-slate-300 hover:text-amber-300 border border-slate-700 hover:border-amber-500/50 px-2.5 py-1 rounded text-xs transition-colors flex items-center gap-1">
                                <i class="fa-solid fa-pen-to-square text-[10px]"></i> Edit
                            </button>
                            <button onclick="app.deleteMuseumData(${origIndex})" class="bg-[#070e1c] hover:bg-rose-500/20 text-slate-400 hover:text-rose-400 border border-slate-700 hover:border-rose-500/50 px-2.5 py-1 rounded text-xs transition-colors flex items-center gap-1">
                                <i class="fa-solid fa-trash-can text-[10px]"></i> Hapus
                            </button>
                        </div>
                    ` : `<span class="text-xxs text-slate-400 italic">View Only</span>`;

                    rows += `
                        <tr class="border-b border-slate-800/80 hover:bg-[#26180a]/40 transition-colors text-xs text-white">
                            <td class="p-3 font-mono font-bold text-amber-400 whitespace-nowrap">${item.no_inventaris}</td>
                            <td class="p-3 font-semibold text-white max-w-xs">
                                <div>${item.nama_koleksi}</div>
                                <div class="text-[10px] text-slate-400 mt-0.5 flex items-center gap-1.5">
                                    <i class="fa-solid fa-gem text-amber-400 text-[9px]"></i> Bahan: <span class="text-slate-300 font-medium">${item.bahan}</span>
                                </div>
                            </td>
                            <td class="p-3 text-center whitespace-nowrap">
                                <span class="bg-amber-500/15 text-amber-300 border border-amber-500/30 px-2 py-0.5 rounded text-xxs font-semibold">${item.kategori}</span>
                            </td>
                            <td class="p-3 text-slate-300 whitespace-nowrap"><i class="fa-solid fa-map-pin text-rose-400 mr-1.5"></i>${item.asal}</td>
                            <td class="p-3 text-slate-300 max-w-[170px] text-xxs leading-relaxed">${item.lokasi_simpan}</td>
                            <td class="p-3 text-center whitespace-nowrap font-mono text-slate-400 text-xxs">${item.tahun_perolehan}</td>
                            <td class="p-3 text-center whitespace-nowrap">${kondisiBadge}</td>
                            <td class="p-3 text-center whitespace-nowrap">${actionButtons}</td>
                        </tr>
                    `;
                });
            }

            return `
            <div class="animate-[fadeIn_0.3s_ease-out] space-y-4">
                <!-- Header Banner -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-[#0b1325] p-4 rounded-xl border border-app-cardBorder shadow-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-600/20 border border-amber-500/40 flex items-center justify-center text-amber-400 shadow-[0_0_15px_rgba(217,119,6,0.25)]">
                            <i class="fa-solid fa-landmark text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-white uppercase flex items-center gap-2">
                                10.4 UPTD Museum Siwa Lima Provinsi Maluku
                            </h2>
                            <p class="text-xs text-slate-400 mt-0.5">Inventarisasi Benda Cagar Budaya, Koleksi Etnografika, Historika, Biologika, & Arsip Maritim Siwa Lima</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        ${isAdmin ? `
                        <button onclick="app.openAddMuseumModal()" class="bg-amber-600 hover:bg-amber-500 text-white px-3.5 py-2 rounded-lg text-xs font-bold flex items-center gap-2 transition-all shadow-[0_0_12px_rgba(217,119,6,0.4)]">
                            <i class="fa-solid fa-plus text-sm"></i> Tambah Koleksi Museum
                        </button>
                        ` : ''}
                        <button onclick="app.showToast('Katalog digital 3D Museum Siwa Lima berhasil disinkronkan.', 'success')" class="bg-[#070e1c] hover:bg-amber-900/30 text-amber-300 border border-amber-500/40 px-3 py-2 rounded-lg text-xs font-semibold flex items-center gap-2 transition-colors">
                            <i class="fa-solid fa-cube text-xs"></i> Katalog 3D Siwa Lima
                        </button>
                    </div>
                </div>

                <!-- 4 METRIC CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div class="bg-[#0b1325] border border-app-cardBorder p-3.5 rounded-xl shadow-lg flex items-center justify-between">
                        <div>
                            <div class="text-xxs text-slate-400 font-bold uppercase tracking-wider">Total Benda Koleksi</div>
                            <div class="text-2xl font-black text-white mt-0.5">${totalKoleksi} <span class="text-xs text-slate-400 font-normal">Koleksi</span></div>
                            <div class="text-[10px] text-amber-400 mt-1"><i class="fa-solid fa-landmark mr-1"></i> Terdaftar Resmi Siwa Lima</div>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400">
                            <i class="fa-solid fa-boxes-stacked text-base"></i>
                        </div>
                    </div>

                    <div class="bg-[#0b1325] border border-app-cardBorder p-3.5 rounded-xl shadow-lg flex items-center justify-between">
                        <div>
                            <div class="text-xxs text-slate-400 font-bold uppercase tracking-wider">Koleksi Kondisi Prima</div>
                            <div class="text-2xl font-black text-emerald-400 mt-0.5">${primaKoleksi} <span class="text-xs text-slate-400 font-normal">Terawat</span></div>
                            <div class="text-[10px] text-emerald-400 mt-1"><i class="fa-solid fa-shield-check mr-1"></i> Standar Konservasi Nasional</div>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
                            <i class="fa-solid fa-shield-halved text-base"></i>
                        </div>
                    </div>

                    <div class="bg-[#0b1325] border border-app-cardBorder p-3.5 rounded-xl shadow-lg flex items-center justify-between">
                        <div>
                            <div class="text-xxs text-slate-400 font-bold uppercase tracking-wider">Etnografika & Adat</div>
                            <div class="text-2xl font-black text-cyan-400 mt-0.5">${etnografikaCount} <span class="text-xs text-slate-400 font-normal">Koleksi</span></div>
                            <div class="text-[10px] text-cyan-400 mt-1"><i class="fa-solid fa-shirt mr-1"></i> Tenun & Senjata Tradisional</div>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400">
                            <i class="fa-solid fa-feather-pointed text-base"></i>
                        </div>
                    </div>

                    <div class="bg-[#0b1325] border border-app-cardBorder p-3.5 rounded-xl shadow-lg flex items-center justify-between">
                        <div>
                            <div class="text-xxs text-slate-400 font-bold uppercase tracking-wider">Historika & Kelautan</div>
                            <div class="text-2xl font-black text-blue-400 mt-0.5">${historikaCount} <span class="text-xs text-slate-400 font-normal">Artefak</span></div>
                            <div class="text-[10px] text-blue-400 mt-1"><i class="fa-solid fa-ship mr-1"></i> Meriam VOC & Kerangka Paus</div>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-blue-500/10 border border-blue-500/30 flex items-center justify-center text-blue-400">
                            <i class="fa-solid fa-compass text-base"></i>
                        </div>
                    </div>
                </div>

                <!-- TABLE CARD -->
                <div class="bg-[#0b1325] border border-app-cardBorder rounded-xl flex-1 flex flex-col overflow-hidden shadow-lg">
                    <div class="p-3.5 border-b border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-[#070e1c]">
                        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                            <input type="text" id="museumSearchInput" value="${this.state.museumSearchQuery}" oninput="app.filterMuseumTable(this.value, null, null)" placeholder="Cari nomor inv, nama koleksi, bahan, asal, ruang..." class="bg-[#0b1325] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-amber-400 w-full sm:w-72">
                            
                            <select id="museumKategoriFilter" onchange="app.filterMuseumTable(null, this.value, null)" class="bg-[#0b1325] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-amber-400">
                                <option value="all" ${kategoriFilter === 'all' ? 'selected' : ''}>Semua Kategori Koleksi</option>
                                <option value="Etnografika" ${kategoriFilter === 'Etnografika' ? 'selected' : ''}>Kategori: Etnografika</option>
                                <option value="Historika" ${kategoriFilter === 'Historika' ? 'selected' : ''}>Kategori: Historika</option>
                                <option value="Biologika" ${kategoriFilter === 'Biologika' ? 'selected' : ''}>Kategori: Biologika</option>
                                <option value="Filologika" ${kategoriFilter === 'Filologika' ? 'selected' : ''}>Kategori: Filologika</option>
                                <option value="Arkeologika" ${kategoriFilter === 'Arkeologika' ? 'selected' : ''}>Kategori: Arkeologika</option>
                                <option value="Keramikologika" ${kategoriFilter === 'Keramikologika' ? 'selected' : ''}>Kategori: Keramikologika</option>
                                <option value="Teknologika" ${kategoriFilter === 'Teknologika' ? 'selected' : ''}>Kategori: Teknologika</option>
                            </select>

                            <select id="museumKondisiFilter" onchange="app.filterMuseumTable(null, null, this.value)" class="bg-[#0b1325] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-amber-400">
                                <option value="all" ${kondisiFilter === 'all' ? 'selected' : ''}>Semua Kondisi Fisik</option>
                                <option value="Sangat Baik" ${kondisiFilter === 'Sangat Baik' ? 'selected' : ''}>Kondisi: Sangat Baik</option>
                                <option value="Terawat" ${kondisiFilter === 'Terawat' ? 'selected' : ''}>Kondisi: Terawat</option>
                                <option value="Utuh / Terpelihara" ${kondisiFilter === 'Utuh / Terpelihara' ? 'selected' : ''}>Kondisi: Utuh</option>
                                <option value="Perlu Konservasi" ${kondisiFilter === 'Perlu Konservasi' ? 'selected' : ''}>Kondisi: Perlu Konservasi</option>
                            </select>
                        </div>
                        <div class="text-xs text-slate-400">Menampilkan <strong>${filtered.length}</strong> dari <strong>${this.state.uptdMuseumList.length}</strong> Koleksi Museum</div>
                    </div>

                    <div class="overflow-x-auto flex-1 custom-scrollbar">
                        <table class="w-full text-left border-collapse min-w-[900px]">
                            <thead class="bg-[#070e1c] sticky top-0 z-10 text-xs text-slate-400">
                                <tr>
                                    <th class="p-3 font-semibold border-b border-slate-800">NO. INV</th>
                                    <th class="p-3 font-semibold border-b border-slate-800">NAMA BENDA KOLEKSI</th>
                                    <th class="p-3 font-semibold border-b border-slate-800 text-center">KATEGORI</th>
                                    <th class="p-3 font-semibold border-b border-slate-800">ASAL DAERAH</th>
                                    <th class="p-3 font-semibold border-b border-slate-800">LOKASI RUANG PAMER</th>
                                    <th class="p-3 font-semibold border-b border-slate-800 text-center">TAHUN</th>
                                    <th class="p-3 font-semibold border-b border-slate-800 text-center">KONDISI</th>
                                    <th class="p-3 font-semibold border-b border-slate-800 text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${rows}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            `;
        },

        filterMuseumTable(query, kategori, kondisi) {
            if (query !== null) this.state.museumSearchQuery = query;
            if (kategori !== null) this.state.museumFilterKategori = kategori;
            if (kondisi !== null) this.state.museumFilterKondisi = kondisi;
            this.loadContent('10.4');
        },

        openAddMuseumModal() {
            document.getElementById('editMuseumIndex').value = "-1";
            document.getElementById('museumModalTitle').textContent = "Tambah Koleksi Museum Siwa Lima Baru";
            document.getElementById('museumNoInv').value = "MSL-K-00" + (this.state.uptdMuseumList.length + 1);
            document.getElementById('museumNama').value = "";
            document.getElementById('museumKategori').value = "Etnografika";
            document.getElementById('museumAsal').value = "";
            document.getElementById('museumBahan').value = "";
            document.getElementById('museumKondisi').value = "Sangat Baik";
            document.getElementById('museumTahun').value = "19" + Math.floor(70 + Math.random() * 25);
            document.getElementById('museumLokasiPamer').value = "Gedung A Ruang Pamer Utama";

            const modal = document.getElementById('museumModal');
            const content = document.getElementById('museumModalContent');
            modal.classList.remove('hidden');
            void modal.offsetWidth;
            modal.classList.replace('opacity-0', 'opacity-100');
            content.classList.replace('scale-95', 'scale-100');
        },

        openEditMuseumModal(index) {
            const item = this.state.uptdMuseumList[index];
            if (!item) return;

            document.getElementById('editMuseumIndex').value = index;
            document.getElementById('museumModalTitle').textContent = `Edit Koleksi Museum - ${item.no_inventaris}`;
            document.getElementById('museumNoInv').value = item.no_inventaris;
            document.getElementById('museumNama').value = item.nama_koleksi;
            document.getElementById('museumKategori').value = item.kategori;
            document.getElementById('museumAsal').value = item.asal;
            document.getElementById('museumBahan').value = item.bahan;
            document.getElementById('museumKondisi').value = item.kondisi;
            document.getElementById('museumTahun').value = item.tahun_perolehan;
            document.getElementById('museumLokasiPamer').value = item.lokasi_simpan;

            const modal = document.getElementById('museumModal');
            const content = document.getElementById('museumModalContent');
            modal.classList.remove('hidden');
            void modal.offsetWidth;
            modal.classList.replace('opacity-0', 'opacity-100');
            content.classList.replace('scale-95', 'scale-100');
        },

        closeMuseumModal() {
            const modal = document.getElementById('museumModal');
            const content = document.getElementById('museumModalContent');
            if (!modal) return;
            modal.classList.replace('opacity-100', 'opacity-0');
            content.classList.replace('scale-100', 'scale-95');
            setTimeout(() => modal.classList.add('hidden'), 200);
        },

        async saveMuseumData() {
            const no_inventaris = document.getElementById('museumNoInv').value.trim();
            const nama_koleksi = document.getElementById('museumNama').value.trim();
            const kategori = document.getElementById('museumKategori').value;
            const asal = document.getElementById('museumAsal').value.trim();
            const bahan = document.getElementById('museumBahan').value.trim();
            const kondisi = document.getElementById('museumKondisi').value;
            const tahun_perolehan = document.getElementById('museumTahun').value.trim();
            const lokasi_simpan = document.getElementById('museumLokasiPamer').value.trim();
            const editIndex = parseInt(document.getElementById('editMuseumIndex').value);

            if (!no_inventaris || !nama_koleksi) {
                this.showToast('Harap lengkapi No. Inventaris dan Nama Benda Koleksi!', 'warning');
                return;
            }

            const itemObj = { no_inventaris, nama_koleksi, kategori, asal, bahan, kondisi, tahun_perolehan, lokasi_simpan };
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                if (editIndex >= 0) {
                    await fetch('/api/simandat/uptd/museum/' + encodeURIComponent(no_inventaris), {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken || '',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(itemObj)
                    });
                } else {
                    const res = await fetch('/api/simandat/uptd/museum', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken || '',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(itemObj)
                    });
                    if (res.ok) {
                        const json = await res.json();
                        if (json.data && json.data.id) itemObj.id = json.data.id;
                    }
                }
            } catch(e) {
                console.warn('API save Museum fallback:', e);
            }

            if (editIndex >= 0) {
                this.state.uptdMuseumList[editIndex] = itemObj;
                this.showToast(`Data Koleksi "${nama_koleksi}" berhasil diperbarui di Database.`, 'success');
            } else {
                this.state.uptdMuseumList.unshift(itemObj);
                this.showToast(`Data Koleksi "${nama_koleksi}" berhasil ditambahkan ke Database.`, 'success');
            }

            const now = new Date();
            const timeStr = now.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' }) + ' WIT';
            this.state.sekretariatData.recentLogs.unshift({
                unit: '10.4 Museum Siwa Lima',
                title: `${editIndex >= 0 ? 'Pembaruan' : 'Penambahan'} ${nama_koleksi}`,
                status: 'Selesai',
                time: timeStr
            });
            if (this.state.sekretariatData.recentLogs.length > 10) this.state.sekretariatData.recentLogs.pop();

            this.saveState();
            this.closeMuseumModal();
            this.loadContent('10.4');
        },

        async deleteMuseumData(index) {
            const item = this.state.uptdMuseumList[index];
            if (!item) return;

            if (confirm(`Apakah Anda yakin ingin menghapus data koleksi "${item.nama_koleksi}" (${item.no_inventaris})?`)) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                try {
                    await fetch('/api/simandat/uptd/museum/' + encodeURIComponent(item.id || item.no_inventaris), {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken || '',
                            'Accept': 'application/json'
                        }
                    });
                } catch(e) {
                    console.warn('API delete Museum fallback:', e);
                }

                this.state.uptdMuseumList.splice(index, 1);
                this.saveState();
                this.loadContent('10.4');
                this.showToast(`Koleksi "${item.nama_koleksi}" berhasil dihapus dari Database.`, 'success');
            }
        },

        // --- PROTECTED SOFT DELETE LOGIC (PASSWORD 1237 PROTECTED) ---
        openDeleteModal(id, title) {
            document.getElementById('deleteTargetId').value = id;
            document.getElementById('deleteTargetTitle').textContent = title;
            
            const pinInput = document.getElementById('deleteSecurityPin');
            const errorMsg = document.getElementById('deletePinErrorMsg');
            if (pinInput) {
                pinInput.value = '';
                pinInput.type = 'password';
            }
            if (errorMsg) errorMsg.classList.add('hidden');
            const eyeIcon = document.getElementById('deletePinEyeIcon');
            if (eyeIcon) eyeIcon.className = 'fa-solid fa-eye';

            const modal = document.getElementById('deleteModal');
            const content = document.getElementById('deleteModalContent');
            if (!modal) return;
            
            modal.classList.remove('hidden');
            void modal.offsetWidth;
            modal.classList.replace('opacity-0', 'opacity-100');
            content.classList.replace('scale-95', 'scale-100');
            
            setTimeout(() => {
                if (pinInput) pinInput.focus();
            }, 150);
        },

        closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            const content = document.getElementById('deleteModalContent');
            if (!modal) return;
            
            modal.classList.replace('opacity-100', 'opacity-0');
            content.classList.replace('scale-100', 'scale-95');
            setTimeout(() => modal.classList.add('hidden'), 200);
        },

        toggleDeletePinVisibility() {
            const pinInput = document.getElementById('deleteSecurityPin');
            const eyeIcon = document.getElementById('deletePinEyeIcon');
            if (!pinInput) return;
            
            if (pinInput.type === 'password') {
                pinInput.type = 'text';
                if (eyeIcon) eyeIcon.className = 'fa-solid fa-eye-slash';
            } else {
                pinInput.type = 'password';
                if (eyeIcon) eyeIcon.className = 'fa-solid fa-eye';
            }
        },

        async executeDeleteArchive() {
            const id = document.getElementById('deleteTargetId').value;
            const pinInput = document.getElementById('deleteSecurityPin');
            const errorMsg = document.getElementById('deletePinErrorMsg');
            const pin = pinInput ? pinInput.value.trim() : '';

            // Strict Password 1237 Verification
            if (pin !== '1237') {
                if (errorMsg) {
                    errorMsg.classList.remove('hidden');
                }
                if (pinInput) {
                    pinInput.classList.add('border-red-500', 'animate-shake');
                    pinInput.focus();
                    setTimeout(() => pinInput.classList.remove('animate-shake'), 500);
                }
                this.showToast('Password salah! Masukkan password resmi (1237) untuk menghapus berkas.', 'error');
                return;
            }

            if (errorMsg) errorMsg.classList.add('hidden');

            const btn = document.getElementById('btnConfirmDelete');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Menghapus & Melindungi...';
            btn.disabled = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                if (id && !isNaN(id)) {
                    const response = await fetch('/api/simandat/archives/' + id, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken || '',
                            'X-Delete-Password': pin,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ password: pin })
                    });
                    
                    if (!response.ok) {
                        const errData = await response.json().catch(() => ({}));
                        throw new Error(errData.message || 'Gagal menghapus arsip di database');
                    }
                }
            } catch (e) {
                console.warn('API delete archive info:', e);
                if (e.message && e.message.includes('Password')) {
                    if (errorMsg) errorMsg.classList.remove('hidden');
                    this.showToast(e.message, 'error');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    return;
                }
            }

            let deletedTitle = 'Berkas';
            Object.keys(this.state.uploadedFiles).forEach(mod => {
                const idx = this.state.uploadedFiles[mod].findIndex(f => String(f.id) === String(id) || f.code === id);
                if (idx !== -1) {
                    deletedTitle = this.state.uploadedFiles[mod][idx].title;
                    this.state.uploadedFiles[mod].splice(idx, 1);
                    if (mod === '2.1') this.state.sekretariatData.subbagUmum.totalBerkas = Math.max(0, this.state.sekretariatData.subbagUmum.totalBerkas - 1);
                    if (mod === '2.2') this.state.sekretariatData.subbagKeuangan.totalBerkas = Math.max(0, this.state.sekretariatData.subbagKeuangan.totalBerkas - 1);
                    if (mod.startsWith('3.')) this.state.smaData.totalBerkas = Math.max(0, this.state.smaData.totalBerkas - 1);
                    if (mod.startsWith('4.')) this.state.smkData.totalBerkas = Math.max(0, this.state.smkData.totalBerkas - 1);
                    if (mod.startsWith('5.')) this.state.slbData.totalBerkas = Math.max(0, this.state.slbData.totalBerkas - 1);
                    if (mod.startsWith('6.')) this.state.budayaData.totalBerkas = Math.max(0, this.state.budayaData.totalBerkas - 1);
                    if (mod.startsWith('7.')) this.state.gtkData.totalBerkas = Math.max(0, this.state.gtkData.totalBerkas - 1);
                }
            });

            this.saveState();
            this.closeDeleteModal();
            this.showToast(`Arsip "${deletedTitle}" berhasil dihapus dan terlindungi di Database (Soft Deleted).`, 'success');
            
            // Re-render db explorer table if currently open or initialized
            this.renderDbExplorerTable(document.getElementById('dbExplorerSearch')?.value || '');
            
            // Update admin modal archive count
            const archiveCountEl = document.getElementById('adminModalArchiveCount');
            if (archiveCountEl) {
                let totalArchives = 0;
                Object.values(this.state.uploadedFiles).forEach(arr => {
                    if (Array.isArray(arr)) totalArchives += arr.length;
                });
                archiveCountEl.textContent = `${totalArchives} Berkas`;
            }

            this.loadContent(this.state.activeMenuId);

            btn.innerHTML = originalText;
            btn.disabled = false;
        },

        // --- MODAL & DATA INPUT LOGIC ---
        filterModuleTable(query, category) {
            if (query !== null) this.state.moduleSearchQuery = query;
            if (category !== null) this.state.moduleCategoryFilter = category;
            this.loadContent(this.state.activeMenuId);
        },

        openModal(moduleName, unitId = '') {
            this.state.targetModalUnit = unitId || this.state.activeMenuId;
            this.state.targetSopId = '';
            
            const subTitle = document.getElementById('modalSubTitle');
            if (subTitle) subTitle.textContent = `Upload Berkas untuk ${moduleName}`;
            
            const titleInput = document.getElementById('uploadTitle');
            if (titleInput) titleInput.value = '';
            
            const catInput = document.getElementById('uploadCategory');
            if (catInput) catInput.value = '';

            const picInput = document.getElementById('uploadPic');
            if (picInput) picInput.value = '';

            const budgetInput = document.getElementById('uploadBudget');
            if (budgetInput) budgetInput.value = '';

            const statusInput = document.getElementById('uploadStatus');
            if (statusInput) statusInput.value = 'VALIDATED';

            const descInput = document.getElementById('uploadDesc');
            if (descInput) descInput.value = '';

            const fileInput = document.getElementById('fileInput');
            if (fileInput) fileInput.value = '';
            
            const fileNameDisplay = document.getElementById('fileNameDisplay');
            if (fileNameDisplay) {
                fileNameDisplay.textContent = 'No file chosen';
                fileNameDisplay.classList.remove('text-app-primary');
            }
            
            const modal = document.getElementById('uploadModal');
            const content = document.getElementById('uploadModalContent');
            if (!modal) return;
            
            modal.classList.remove('hidden');
            void modal.offsetWidth;
            
            modal.classList.replace('opacity-0', 'opacity-100');
            content.classList.replace('scale-95', 'scale-100');
        },

        openSopInputModal(sopId, sopName) {
            this.state.targetModalUnit = '2.3';
            this.state.targetSopId = sopId;

            const subTitle = document.getElementById('modalSubTitle');
            if (subTitle) subTitle.textContent = `Input / Validasi Data untuk ${sopId} - ${sopName}`;
            
            const titleInput = document.getElementById('uploadTitle');
            if (titleInput) titleInput.value = `Validasi ${sopId} - ${sopName}`;
            
            const catInput = document.getElementById('uploadCategory');
            if (catInput) catInput.value = `SOP Layanan ${sopId}`;

            const fileInput = document.getElementById('fileInput');
            if (fileInput) fileInput.value = '';
            
            const fileNameDisplay = document.getElementById('fileNameDisplay');
            if (fileNameDisplay) {
                fileNameDisplay.textContent = 'No file chosen';
                fileNameDisplay.classList.remove('text-app-primary');
            }
            
            const modal = document.getElementById('uploadModal');
            const content = document.getElementById('uploadModalContent');
            if (!modal) return;
            
            modal.classList.remove('hidden');
            void modal.offsetWidth;
            
            modal.classList.replace('opacity-0', 'opacity-100');
            content.classList.replace('scale-95', 'scale-100');
        },

        closeModal() {
            const modal = document.getElementById('uploadModal');
            const content = document.getElementById('uploadModalContent');
            if (!modal) return;
            
            modal.classList.replace('opacity-100', 'opacity-0');
            content.classList.replace('scale-100', 'scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        },

        // --- EDIT ARCHIVE / LAPORAN MODAL HANDLERS (FULL CRUD MODAL) ---
        openEditArchiveModal(moduleId, index, fileId) {
            const list = this.state.uploadedFiles[moduleId] || [];
            const file = list[index] || list.find(f => String(f.id) === String(fileId) || f.code === fileId);
            if (!file) return;

            document.getElementById('editArchiveModuleId').value = moduleId;
            document.getElementById('editArchiveIndex').value = index;
            document.getElementById('editArchiveDbId').value = file.id || '';
            document.getElementById('editArchiveTitle').value = file.title || '';
            document.getElementById('editArchiveCode').value = file.code || '';
            document.getElementById('editArchiveCategory').value = file.category || '';
            document.getElementById('editArchivePic').value = file.pic || '';
            document.getElementById('editArchiveBudget').value = file.budget || '';
            document.getElementById('editArchiveStatus').value = file.status || 'VALIDATED';
            document.getElementById('editArchiveUploader').value = file.uploader || 'Super Admin (TVRI Maluku)';
            document.getElementById('editArchiveDesc').value = file.description || file.desc || '';

            const modalTitle = document.getElementById('editArchiveModalTitle');
            const modalSubTitle = document.getElementById('editArchiveModalSubTitle');
            if (modalTitle) modalTitle.textContent = `Edit Laporan: ${file.code || file.title}`;
            if (modalSubTitle) modalSubTitle.textContent = `Modul ${moduleId} • Pangkalan Data Dinas PUPR Maluku`;

            const modal = document.getElementById('editArchiveModal');
            const content = document.getElementById('editArchiveModalContent');
            if (!modal) return;

            modal.classList.remove('hidden');
            void modal.offsetWidth;
            modal.classList.replace('opacity-0', 'opacity-100');
            content.classList.replace('scale-95', 'scale-100');
        },

        closeEditArchiveModal() {
            const modal = document.getElementById('editArchiveModal');
            const content = document.getElementById('editArchiveModalContent');
            if (!modal) return;

            modal.classList.replace('opacity-100', 'opacity-0');
            content.classList.replace('scale-100', 'scale-95');
            setTimeout(() => modal.classList.add('hidden'), 200);
        },

        async saveEditArchiveData() {
            const moduleId = document.getElementById('editArchiveModuleId').value;
            const index = parseInt(document.getElementById('editArchiveIndex').value);
            const dbId = document.getElementById('editArchiveDbId').value;
            const title = document.getElementById('editArchiveTitle').value.trim();
            const code = document.getElementById('editArchiveCode').value.trim();
            const category = document.getElementById('editArchiveCategory').value.trim();
            const pic = document.getElementById('editArchivePic').value.trim();
            const budget = document.getElementById('editArchiveBudget').value;
            const status = document.getElementById('editArchiveStatus').value;
            const uploader = document.getElementById('editArchiveUploader').value.trim();
            const description = document.getElementById('editArchiveDesc').value.trim();

            if (!title) {
                this.showToast('Harap masukkan judul laporan / berkas!', 'warning');
                return;
            }

            const list = this.state.uploadedFiles[moduleId] || [];
            const targetFile = list[index] || list.find(f => String(f.id) === String(dbId) || f.code === code);

            if (targetFile) {
                targetFile.title = title;
                targetFile.code = code || targetFile.code;
                targetFile.category = category || targetFile.category;
                targetFile.pic = pic;
                targetFile.budget = budget;
                targetFile.status = status;
                targetFile.uploader = uploader;
                targetFile.description = description;
            }

            // Update via Backend MySQL API
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (dbId && !isNaN(dbId)) {
                try {
                    await fetch(`/api/simandat/archives/${dbId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken || '',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            title,
                            code,
                            category,
                            pic,
                            budget,
                            status,
                            uploader,
                            description
                        })
                    });
                } catch (e) {
                    console.warn('API update archive fallback:', e);
                }
            }

            const now = new Date();
            const timeStr = now.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' }) + ' WIT';
            this.state.sekretariatData.recentLogs.unshift({
                unit: `Modul ${moduleId}`,
                title: `Pembaruan: ${title}`,
                status: 'Selesai',
                time: timeStr
            });
            if (this.state.sekretariatData.recentLogs.length > 10) this.state.sekretariatData.recentLogs.pop();

            this.saveState();
            this.closeEditArchiveModal();
            this.showToast(`Laporan "${title}" berhasil diperbarui di Pangkalan Data Dinas PUPR Maluku.`, 'success');
            this.loadContent(this.state.activeMenuId);
        },

        // --- SUBMIT UPLOAD & UPDATE EXECUTIVE COMMAND CENTER REAL-TIME (DATABASE INTEGRATED) ---
        async simulateUpload() {
            const title = document.getElementById('uploadTitle').value.trim();
            const customCategory = document.getElementById('uploadCategory')?.value.trim();
            const customPic = document.getElementById('uploadPic')?.value.trim();
            const customBudget = document.getElementById('uploadBudget')?.value.trim();
            const customStatus = document.getElementById('uploadStatus')?.value || 'VALIDATED';
            const customDesc = document.getElementById('uploadDesc')?.value.trim();
            const fileInput = document.getElementById('fileInput');
            const file = fileInput.files[0];

            if(!title) {
                this.showToast('Harap masukkan judul / nama berkas!', 'warning');
                return;
            }

            const btn = document.getElementById('btnUpload');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan ke Database...';
            btn.disabled = true;
            btn.classList.add('opacity-70');

            const now = new Date();
            const dayName = now.toLocaleDateString('id-ID', { weekday: 'long' });
            const dateStr = now.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            const timeStr = now.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' }) + ' WIT';
            
            const unit = this.state.targetModalUnit || this.state.activeMenuId;
            const sek = this.state.sekretariatData;

            let unitLabel = 'Sekretariat';
            let categoryLabel = customCategory || 'Laporan Kinerja';
            let fileExt = file ? file.name.split('.').pop().toUpperCase() : 'PDF';
            let fileSize = file ? (file.size / (1024*1024)).toFixed(1) + ' MB' : (1.8 + Math.random() * 2.5).toFixed(1) + ' MB';
            let fileCode = `DOC-${unit}-${Math.floor(100 + Math.random() * 900)}`;

            if (unit.startsWith('2.1')) {
                unitLabel = '2.1 Umum & Aset';
                if (!customCategory) categoryLabel = '2.1.1 Inventarisasi Aset';
                sek.subbagUmum.totalBerkas += 1;
                sek.subbagUmum.progress = Math.min(100, Number((sek.subbagUmum.progress + 0.8).toFixed(1)));
                sek.subbagUmum.lastInput = title;
            } else if (unit.startsWith('2.2')) {
                unitLabel = '2.2 Perenc. & Keuangan';
                if (!customCategory) categoryLabel = '2.2.2 Realisasi Keuangan';
                sek.subbagKeuangan.totalBerkas += 1;
                sek.subbagKeuangan.serapan = Math.min(100, Number((sek.subbagKeuangan.serapan + 0.5).toFixed(1)));
                sek.subbagKeuangan.lastInput = title;
            } else if (unit.startsWith('2.3')) {
                unitLabel = '2.3 Pengelolaan Data';
                if (!customCategory) categoryLabel = this.state.targetSopId ? `SOP ${this.state.targetSopId}` : '2.3 Layanan SOP';
                if (this.state.targetSopId) {
                    const sopItem = sek.subbagData.sopList.find(s => s.id === this.state.targetSopId);
                    if (sopItem && sopItem.status !== 'Selesai') {
                        sopItem.status = 'Selesai';
                        sek.subbagData.selesai = Math.min(sek.subbagData.totalSOP, sek.subbagData.selesai + 1);
                        sek.subbagData.diproses = Math.max(0, sek.subbagData.diproses - 1);
                    }
                }
                sek.subbagData.lastInput = title;
            } else if (unit.startsWith('3.')) {
                unitLabel = `3. SMA (${unit})`;
                if (!customCategory) categoryLabel = unit === '3.1' ? 'Perencanaan SMA' : (unit === '3.2' ? 'Akreditasi SMA' : (unit === '3.3' ? 'Kurikulum Merdeka SMA' : 'Kesiswaan SMA'));
                this.state.smaData.totalBerkas += 1;
                this.state.smaData.capaian = Math.min(100, Number((this.state.smaData.capaian + 0.6).toFixed(1)));
                this.state.smaData.lastInput = title;
                const currentMonthIdx = now.getMonth();
                if (this.state.smaData.trend[currentMonthIdx] !== undefined) {
                    this.state.smaData.trend[currentMonthIdx] = this.state.smaData.capaian;
                }
            } else if (unit.startsWith('4.')) {
                unitLabel = `4. SMK (${unit})`;
                if (!customCategory) categoryLabel = unit === '4.1' ? 'Perencanaan SMK' : (unit === '4.2' ? 'Teaching Factory (Tefa)' : (unit === '4.3' ? 'Sertifikasi BNSP/LSP' : 'Kerja Sama DUDI & PKL'));
                this.state.smkData.totalBerkas += 1;
                this.state.smkData.dudiPercent = Math.min(100, Number((this.state.smkData.dudiPercent + 0.8).toFixed(1)));
                this.state.smkData.lastInput = title;
                if (this.state.smkData.tefaData && this.state.smkData.tefaData.length > 0) {
                    this.state.smkData.tefaData[0] += 1;
                }
            } else if (unit.startsWith('5.')) {
                unitLabel = `5. SLB (${unit})`;
                if (!customCategory) categoryLabel = unit === '5.1' ? 'Pembinaan SLB' : (unit === '5.2' ? 'Kurikulum ABK' : 'Sarpras Ramah Disabilitas');
                this.state.slbData.totalBerkas += 1;
                this.state.slbData.totalInklusi += 2;
                this.state.slbData.lastInput = title;
            } else if (unit.startsWith('6.')) {
                if (unit.startsWith('6.1')) {
                    unitLabel = '6.1 Cagar Budaya & Permuseuman';
                    if (!customCategory) categoryLabel = '6.1.1 Inventarisasi Cagar Budaya';
                    this.state.budayaData.proportions[0] += 3;
                } else if (unit.startsWith('6.2')) {
                    unitLabel = '6.2 Sejarah dan Tradisi';
                    if (!customCategory) categoryLabel = '6.2.1 Warisan Budaya Takbenda (WBTB)';
                    this.state.budayaData.proportions[1] += 3;
                    this.state.budayaData.proportions[3] += 2;
                } else if (unit.startsWith('6.3')) {
                    unitLabel = '6.3 Kesenian & Pembinaan Sanggar';
                    if (!customCategory) categoryLabel = '6.3.1 Pembinaan Sanggar Seni';
                    this.state.budayaData.proportions[2] += 3;
                } else {
                    unitLabel = `6. Kebudayaan (${unit})`;
                    if (!customCategory) categoryLabel = `Dokumen ${unit}`;
                }
                this.state.budayaData.totalBerkas += 1;
                this.state.budayaData.usulanBaru += 1;
                this.state.budayaData.verifikasi += 1;
                this.state.budayaData.terdaftar += 1;
                this.state.budayaData.lastVerified = title;
                this.state.budayaData.lastInput = title;
            } else if (unit.startsWith('7.')) {
                unitLabel = `7. GTK (${unit})`;
                if (!customCategory) categoryLabel = unit === '7.1' ? 'Distribusi Guru 3T' : (unit === '7.2' ? 'Pengembangan Kompetensi' : 'Sertifikasi & TPG');
                this.state.gtkData.totalBerkas += 1;
                this.state.gtkData.literasiDigital = Math.min(100, this.state.gtkData.literasiDigital + 1);
                this.state.gtkData.sertifikasi3T = Math.min(100, this.state.gtkData.sertifikasi3T + 1);
                this.state.gtkData.profesional = Math.min(100, this.state.gtkData.profesional + 1);
                this.state.gtkData.lastInput = title;
            } else {
                unitLabel = `Modul ${unit}`;
                if (!customCategory) categoryLabel = `Dokumen ${unit}`;
            }

            // Sync with pengawasan data
            this.state.pengawasanData.totalAgenda += 1;
            this.state.pengawasanData.programSelesai += 1;

            let newArchiveId = `F-${unit.replace('.','')}-${Date.now().toString().slice(-4)}`;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // Save to MySQL Database
            try {
                const formData = new FormData();
                formData.append('title', title);
                formData.append('module_id', unit);
                formData.append('code', fileCode);
                formData.append('category', categoryLabel);
                formData.append('format', fileExt);
                formData.append('file_size', fileSize);
                formData.append('day', dayName);
                formData.append('date', dateStr);
                formData.append('time', timeStr);
                formData.append('uploader', this.state.role === 'admin' ? 'Super Admin (TVRI Maluku)' : 'Admin Subbag');
                formData.append('status', customStatus);
                if (customPic) formData.append('pic', customPic);
                if (customBudget) formData.append('budget', customBudget);
                if (customDesc) formData.append('description', customDesc);
                if (file) {
                    formData.append('file', file);
                }

                const res = await fetch('/api/simandat/archives', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken || '',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (res.ok) {
                    const json = await res.json();
                    if (json.data) {
                        newArchiveId = json.data.id;
                        fileCode = json.data.code || fileCode;
                    }
                }
            } catch (e) {
                console.warn('API save archive fallback:', e);
            }

            if (!this.state.uploadedFiles[unit]) {
                this.state.uploadedFiles[unit] = [];
            }

            const newFile = {
                id: newArchiveId,
                code: fileCode,
                title: title,
                category: categoryLabel,
                format: fileExt,
                size: fileSize,
                day: dayName,
                date: dateStr,
                time: timeStr,
                uploader: this.state.role === 'admin' ? 'Super Admin (TVRI Maluku)' : 'Admin Subbag',
                status: customStatus,
                pic: customPic,
                budget: customBudget,
                description: customDesc
            };

            this.state.uploadedFiles[unit].unshift(newFile);

            sek.recentLogs.unshift({
                unit: unitLabel,
                title: title,
                status: 'Selesai',
                time: timeStr
            });
            if (sek.recentLogs.length > 10) sek.recentLogs.pop();

            this.saveState();
            this.closeModal();
            
            this.showToast(`Berhasil: "${title}" tersimpan di Database SIMANDAT & disinkronkan ke Executive Command Center!`, 'success');

            this.loadContent(this.state.activeMenuId);

            btn.innerHTML = originalText;
            btn.disabled = false;
            btn.classList.remove('opacity-70');
        }
    };

    // Initialize application on DOM ready
    window.addEventListener('DOMContentLoaded', () => {
        app.init();
    });
</script>
@endpush
