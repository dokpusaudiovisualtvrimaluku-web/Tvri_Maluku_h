<!-- ADMIN ACCOUNT & DATABASE STATUS MODAL -->
<div id="adminAccountModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-sm hidden opacity-0 transition-opacity duration-200">
    <div class="bg-[#0b1325] border border-blue-500/40 rounded-2xl shadow-2xl w-full max-w-xl transform scale-95 transition-transform duration-200 overflow-hidden" id="adminAccountModalContent">
        
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-4 border-b border-slate-800 bg-[#070e1c]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-600/20 border border-blue-500/40 flex items-center justify-center text-app-primary shadow-inner">
                    <i class="fa-solid fa-user-shield text-lg"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-white text-base flex items-center gap-2">
                        Akun Administrator & Database
                        <span class="text-[10px] bg-blue-900/40 text-blue-300 border border-blue-500/30 px-2 py-0.5 rounded-full font-mono">SIMANDAT SIWALIMA</span>
                    </h3>
                    <p class="text-xxs text-slate-400">TVRI Maluku</p>
                </div>
            </div>
            <button onclick="app.closeAdminAccountModal()" class="text-slate-400 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-800">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-5 space-y-4 max-h-[80vh] overflow-y-auto custom-scrollbar">
            
            <!-- User Profile Box -->
            <div class="bg-[#070e1c] border border-slate-800 rounded-xl p-4 flex items-start gap-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-tr from-blue-600 to-cyan-400 p-0.5 shrink-0 shadow-lg">
                    <div class="w-full h-full bg-[#0b1325] rounded-[10px] flex items-center justify-center text-app-primary text-2xl">
                        <i class="fa-solid fa-user-gear"></i>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2 flex-wrap">
                        <h4 class="text-sm font-black text-white truncate" id="adminModalName">Administrator</h4>
                        <span class="bg-app-primary/20 text-app-primary border border-app-primary/40 text-[10px] px-2.5 py-0.5 rounded-full font-bold shadow-sm" id="adminModalBadge">
                            <i class="fa-solid fa-crown mr-1"></i> Super Admin (Full Access)
                        </span>
                    </div>
                    <div class="text-xs text-slate-300 font-mono mt-1" id="adminModalEmail">admin.simandat@malukuprov.go.id</div>
                    <div class="text-xxs text-slate-400 mt-1 flex items-center gap-2">
                        <span><i class="fa-solid fa-id-card text-app-primary mr-1"></i> NIP: 19820314 200801 1 002</span>
                        <span>•</span>
                        <span><i class="fa-solid fa-building-columns text-app-primary mr-1"></i> TVRI Maluku</span>
                    </div>
                </div>
            </div>

            <!-- Database Engine & Health Status -->
            <div class="bg-[#070e1c] border border-slate-800 rounded-xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-xs font-bold text-slate-300 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-database text-app-primary"></i>
                        Status Database Terintegrasi
                    </div>
                    <span class="bg-app-success/20 text-app-success border border-app-success/40 text-[10px] px-2.5 py-0.5 rounded-full font-bold flex items-center gap-1.5 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-app-success animate-ping"></span>
                        MySQL ONLINE
                    </span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 text-xs">
                    <div class="bg-[#0b1325] border border-slate-800 p-2.5 rounded-lg">
                        <div class="text-xxs text-slate-400 font-semibold">Tipe & Engine</div>
                        <div class="font-bold text-white mt-0.5">MySQL (InnoDB)</div>
                        <div class="text-[9px] text-slate-500 font-mono mt-0.5">Laravel 11 ORM</div>
                    </div>
                    <div class="bg-[#0b1325] border border-slate-800 p-2.5 rounded-lg">
                        <div class="text-xxs text-slate-400 font-semibold">Nama Database</div>
                        <div class="font-bold text-blue-400 font-mono mt-0.5">SIMANDAT SIWALIMA</div>
                        <div class="text-[9px] text-slate-500 font-mono mt-0.5">127.0.0.1:3306</div>
                    </div>
                    <div class="bg-[#0b1325] border border-slate-800 p-2.5 rounded-lg">
                        <div class="text-xxs text-slate-400 font-semibold">Proteksi Data</div>
                        <div class="font-bold text-app-success mt-0.5">Soft-Delete Active</div>
                        <div class="text-[9px] text-slate-500 font-mono mt-0.5">Auto Safeguard</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2.5 mt-2.5">
                    <div onclick="app.requestDbExplorerAccess()" class="bg-[#0b1325] border border-blue-500/40 hover:border-app-primary hover:bg-blue-600/10 p-2.5 rounded-lg flex items-center justify-between cursor-pointer transition-all group shadow-sm" title="Klik untuk verifikasi password dan membuka berkas database">
                        <div>
                            <div class="text-xxs text-slate-400 group-hover:text-app-primary transition-colors flex items-center gap-1">
                                <span>Total Arsip Digital Terdata</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-[8px] text-app-primary"></i>
                            </div>
                            <div class="text-base font-black text-app-primary mt-0.5" id="adminModalArchiveCount">9 Berkas</div>
                        </div>
                        <div class="w-8 h-8 rounded-lg bg-blue-600/20 text-app-primary flex items-center justify-center text-sm group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <i class="fa-solid fa-folder-open"></i>
                        </div>
                    </div>
                    <div onclick="app.closeAdminAccountModal(); app.loadContent('10.1');" class="bg-[#0b1325] border border-slate-800 hover:border-app-primary hover:bg-blue-600/10 p-2.5 rounded-lg flex items-center justify-between cursor-pointer transition-all group shadow-sm" title="Klik untuk membuka Master Satuan Pendidikan (10.1)">
                        <div>
                            <div class="text-xxs text-slate-400 group-hover:text-app-primary transition-colors flex items-center gap-1">
                                <span>Total Satuan Pendidikan Master</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-[8px] text-app-primary"></i>
                            </div>
                            <div class="text-base font-black text-white group-hover:text-app-primary mt-0.5 transition-colors" id="adminModalSchoolCount">8 Sekolah</div>
                        </div>
                        <div class="w-8 h-8 rounded-lg bg-slate-800 text-slate-300 flex items-center justify-center text-sm group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <i class="fa-solid fa-school"></i>
                        </div>
                    </div>
                </div>

                <!-- Dedicated Button to View and Open Files from DB -->
                <button onclick="app.requestDbExplorerAccess()" class="w-full mt-2.5 py-2 px-3 bg-gradient-to-r from-blue-600/30 to-cyan-600/30 hover:from-blue-600 hover:to-cyan-600 text-cyan-300 hover:text-white border border-cyan-500/40 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-2 shadow-sm">
                    <i class="fa-solid fa-database text-app-primary group-hover:text-white"></i>
                    <span>Buka & Lihat Berkas Database</span>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </button>
            </div>

            <!-- Quick Role Switch & Database Re-sync -->
            <div class="bg-[#070e1c] border border-slate-800 rounded-xl p-4">
                <div class="text-xs font-bold text-slate-300 uppercase tracking-wider mb-2.5 flex items-center gap-2">
                    <i class="fa-solid fa-arrows-rotate text-app-primary"></i>
                    Opsi Cepat & Ganti Peran Akses
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <button onclick="app.switchRoleFromModal('admin')" class="p-2.5 rounded-lg bg-[#0b1325] border border-blue-500/40 hover:bg-blue-600/20 text-left transition-all group flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-600/20 text-blue-400 flex items-center justify-center shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <i class="fa-solid fa-crown text-sm"></i>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-white group-hover:text-app-primary transition-colors">Mode Super Admin</div>
                            <div class="text-[10px] text-slate-400">Akses Penuh CRUD, Upload & Hapus</div>
                        </div>
                    </button>

                    <button onclick="app.switchRoleFromModal('kadis')" class="p-2.5 rounded-lg bg-[#0b1325] border border-amber-500/30 hover:bg-amber-600/20 text-left transition-all group flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-600/20 text-amber-400 flex items-center justify-center shrink-0 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                            <i class="fa-solid fa-user-tie text-sm"></i>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-white group-hover:text-amber-400 transition-colors">Mode Kepala Stasiun</div>
                            <div class="text-[10px] text-slate-400">Akses Eksekutif & View Only</div>
                        </div>
                    </button>
                </div>

                <div class="mt-2.5 pt-2.5 border-t border-slate-800 flex items-center justify-between flex-wrap gap-2">
                    <div class="flex items-center gap-3">
                        <button onclick="app.refreshDatabaseSync()" class="text-xs text-app-primary hover:underline flex items-center gap-1.5">
                            <i class="fa-solid fa-rotate"></i> Sinkronkan Ulang dari Database MySQL
                        </button>
                        <button onclick="app.closeAdminAccountModal(); app.openResetModal();" class="text-xs text-amber-400 hover:text-amber-300 hover:underline flex items-center gap-1.5">
                            <i class="fa-solid fa-arrow-rotate-left"></i> Reset / Kosongkan Data
                        </button>
                    </div>
                    <button onclick="app.logoutFromModal()" class="text-xs text-red-400 hover:text-red-300 hover:underline flex items-center gap-1">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar Sistem
                    </button>
                </div>
            </div>

        </div>

        <!-- Modal Footer -->
        <div class="p-4 border-t border-slate-800 flex justify-end gap-3 bg-[#070e1c]">
            <button onclick="app.closeAdminAccountModal()" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-colors shadow-[0_0_12px_rgba(37,99,235,0.4)]">
                Tutup Jendela Akun
            </button>
        </div>
    </div>
</div>
