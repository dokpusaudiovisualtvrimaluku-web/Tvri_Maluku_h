<!-- RESET DATA MODAL -->
<div id="resetModal" class="fixed inset-0 z-[65] flex items-center justify-center bg-black/80 backdrop-blur-sm hidden opacity-0 transition-opacity duration-200">
    <div class="bg-[#0b1325] border border-amber-500/40 rounded-2xl shadow-2xl w-full max-w-lg transform scale-95 transition-transform duration-200 overflow-hidden" id="resetModalContent">
        
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-4 border-b border-slate-800 bg-[#070e1c]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 border border-amber-500/40 flex items-center justify-center text-amber-400 shadow-inner">
                    <i class="fa-solid fa-arrow-rotate-left text-lg"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-white text-base flex items-center gap-2">
                        Reset & Kosongkan Data
                        <span class="text-[10px] bg-amber-900/40 text-amber-300 border border-amber-500/30 px-2 py-0.5 rounded-full font-mono">SIMANDAT SIWALIMA</span>
                    </h3>
                    <p class="text-xxs text-slate-400">Pusat Manajemen Pengosongan & Pemulihan Data Sistem</p>
                </div>
            </div>
            <button onclick="app.closeResetModal()" class="text-slate-400 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-800">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-5 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">
            
            <div class="bg-amber-950/30 border border-amber-500/30 p-3 rounded-xl flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                </div>
                <div class="text-xs text-slate-300">
                    <div class="font-bold text-amber-300 mb-0.5">Pilih Tindakan Reset Data:</div>
                    <p class="text-xxs text-slate-400 leading-relaxed">
                        Anda dapat mengosongkan seluruh angka capaian, usulan, dan berkas menjadi <strong>0 (kosong/blank)</strong> untuk input baru, atau mengembalikan ke data simulasi default.
                    </p>
                </div>
            </div>

            <!-- Action Cards -->
            <div class="space-y-2.5">
                
                <!-- Option 1: Kosongkan Seluruh Data (0/Blank) -->
                <div class="bg-[#070e1c] border border-red-500/30 hover:border-red-500/60 p-3.5 rounded-xl transition-all group">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-red-600/20 text-red-400 flex items-center justify-center shrink-0 group-hover:bg-red-600 group-hover:text-white transition-colors">
                                <i class="fa-solid fa-eraser text-base"></i>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-white group-hover:text-red-300 transition-colors">Kosongkan Seluruh Data (Nilai 0 / Blank)</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">
                                    Mereset Bidang Kebudayaan, Sekretariat, GTK, SMA, SMK, SLB, Kalender & semua berkas menjadi <strong>0 / Bersih</strong>.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 pt-2.5 border-t border-slate-800/80 flex justify-end">
                        <button onclick="app.executeResetData('empty_all')" class="px-3 py-1.5 bg-red-600/20 hover:bg-red-600 text-red-300 hover:text-white border border-red-500/40 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm">
                            <i class="fa-solid fa-trash-can text-xxs"></i>
                            <span>Kosongkan Semua Data</span>
                        </button>
                    </div>
                </div>

                <!-- Option 2: Kosongkan Khusus Sekretariat & Tata Kelola -->
                <div class="bg-[#070e1c] border border-cyan-500/30 hover:border-cyan-500/60 p-3.5 rounded-xl transition-all group">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-cyan-600/20 text-cyan-400 flex items-center justify-center shrink-0 group-hover:bg-cyan-600 group-hover:text-white transition-colors">
                                <i class="fa-solid fa-building-user text-base"></i>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-white group-hover:text-cyan-300 transition-colors">Kosongkan Khusus Sekretariat & Tata Kelola</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">
                                    Mereset 2.1 Umum & Aset, 2.2 Perenc. & Keu, 2.3 Layanan SOP, grafik & log aktivitas menjadi <strong>0 / Blank</strong>.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 pt-2.5 border-t border-slate-800/80 flex justify-end">
                        <button onclick="app.executeResetData('empty_sekretariat')" class="px-3 py-1.5 bg-cyan-600/20 hover:bg-cyan-600 text-cyan-300 hover:text-white border border-cyan-500/40 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm">
                            <i class="fa-solid fa-rotate-left text-xxs"></i>
                            <span>Kosongkan Sekretariat</span>
                        </button>
                    </div>
                </div>

                <!-- Option 3: Kosongkan Khusus Bidang Kebudayaan -->
                <div class="bg-[#070e1c] border border-blue-500/30 hover:border-blue-500/60 p-3.5 rounded-xl transition-all group">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-blue-600/20 text-blue-400 flex items-center justify-center shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <i class="fa-solid fa-masks-theater text-base"></i>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-white group-hover:text-blue-300 transition-colors">Kosongkan Khusus Bidang Kebudayaan</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">
                                    Mereset Usulan Baru, Verifikasi, Terdaftar, Donut Chart, & status objek menjadi <strong>0 / Kosong</strong>.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 pt-2.5 border-t border-slate-800/80 flex justify-end">
                        <button onclick="app.executeResetData('empty_budaya')" class="px-3 py-1.5 bg-blue-600/20 hover:bg-blue-600 text-blue-300 hover:text-white border border-blue-500/40 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm">
                            <i class="fa-solid fa-rotate-left text-xxs"></i>
                            <span>Kosongkan Kebudayaan</span>
                        </button>
                    </div>
                </div>

                <!-- Option 4: Pulihkan Satuan Pendidikan & Archipelago -->
                <div class="bg-[#070e1c] border border-indigo-500/30 hover:border-indigo-500/60 p-3.5 rounded-xl transition-all group">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-indigo-600/20 text-indigo-400 flex items-center justify-center shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <i class="fa-solid fa-school text-base"></i>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-white group-hover:text-indigo-300 transition-colors">Pulihkan Satuan Pendidikan & Archipelago</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">
                                    Mengembalikan daftar sekolah ke <strong>8 Sekolah Master</strong> (4 SMA, 3 SMK, 1 SLB) & info pulau default.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 pt-2.5 border-t border-slate-800/80 flex justify-end">
                        <button onclick="app.executeResetData('restore_school')" class="px-3 py-1.5 bg-indigo-600/20 hover:bg-indigo-600 text-indigo-300 hover:text-white border border-indigo-500/40 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm">
                            <i class="fa-solid fa-rotate-left text-xxs"></i>
                            <span>Pulihkan Satuan Pendidikan</span>
                        </button>
                    </div>
                </div>

                <!-- Option 5: Pulihkan Seluruh Data ke Default Seed -->
                <div class="bg-[#070e1c] border border-emerald-500/30 hover:border-emerald-500/60 p-3.5 rounded-xl transition-all group">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-emerald-600/20 text-emerald-400 flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                <i class="fa-solid fa-clock-rotate-left text-base"></i>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-white group-hover:text-emerald-300 transition-colors">Pulihkan ke Data Awal (Default Seed)</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">
                                    Mengembalikan seluruh angka, grafik, dan riwayat dokumen simulasi resmi TVRI Maluku ke kondisi awal.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 pt-2.5 border-t border-slate-800/80 flex justify-end">
                        <button onclick="app.executeResetData('restore_default')" class="px-3 py-1.5 bg-emerald-600/20 hover:bg-emerald-600 text-emerald-300 hover:text-white border border-emerald-500/40 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm">
                            <i class="fa-solid fa-cloud-arrow-down text-xxs"></i>
                            <span>Pulihkan Data Default</span>
                        </button>
                    </div>
                </div>

            </div>

        </div>

        <!-- Modal Footer -->
        <div class="p-4 border-t border-slate-800 flex justify-end gap-3 bg-[#070e1c]">
            <button onclick="app.closeResetModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold rounded-lg transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>
