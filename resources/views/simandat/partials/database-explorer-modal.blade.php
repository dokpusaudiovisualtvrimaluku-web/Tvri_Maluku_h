<!-- DATABASE ARCHIVES & TABLES EXPLORER MODAL -->
<div id="dbExplorerModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/80 backdrop-blur-sm hidden opacity-0 transition-opacity duration-200">
    <div class="bg-[#0b1325] border border-blue-500/40 rounded-2xl shadow-2xl w-full max-w-4xl transform scale-95 transition-transform duration-200 overflow-hidden flex flex-col max-h-[90vh]" id="dbExplorerModalContent">
        
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-4 border-b border-slate-800 bg-[#070e1c] shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-600/20 border border-blue-500/40 flex items-center justify-center text-app-primary shadow-inner">
                    <i class="fa-solid fa-server text-lg"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-white text-base flex items-center gap-2">
                        Pusat Berkas Database <span class="text-blue-400 font-mono text-sm">SIMANDAT SIWALIMA</span>
                        <span class="text-[9px] bg-emerald-950/60 text-emerald-400 border border-emerald-500/30 px-2 py-0.5 rounded-full font-mono">
                            <i class="fa-solid fa-database mr-1"></i> MySQL Live
                        </span>
                    </h3>
                    <p class="text-xxs text-slate-400">Daftar Seluruh Berkas & Dokumen Terdata di Server Database SIMANDAT</p>
                </div>
            </div>
            <button onclick="app.closeDbExplorerModal()" class="text-slate-400 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-800">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-5 space-y-4 overflow-y-auto custom-scrollbar flex-1">
            
            <!-- Quick Search & Stats -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-[#070e1c] p-3 rounded-xl border border-slate-800">
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <input type="text" id="dbExplorerSearch" oninput="app.filterDbExplorer(this.value)" placeholder="Cari berkas di database..." class="bg-[#0b1325] border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:border-app-primary w-full sm:w-64">
                </div>
                <div class="text-xs text-slate-400 flex items-center gap-3">
                    <span>Tabel: <strong class="text-white font-mono">simandat_archives</strong></span>
                    <span>•</span>
                    <span id="dbExplorerTotalText">Total: <strong class="text-app-primary">9 Dokumen</strong></span>
                </div>
            </div>

            <!-- Table of all database archives -->
            <div class="overflow-x-auto custom-scrollbar border border-slate-800 rounded-xl">
                <table class="w-full text-left border-collapse min-w-[750px] text-xs">
                    <thead class="bg-[#070e1c] text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="p-3 font-semibold">ID / KODE</th>
                            <th class="p-3 font-semibold">JUDUL DOKUMEN & MODUL</th>
                            <th class="p-3 font-semibold">FORMAT / UKURAN</th>
                            <th class="p-3 font-semibold">WAKTU UNGGAH</th>
                            <th class="p-3 font-semibold text-center">AKSI DATABASE</th>
                        </tr>
                    </thead>
                    <tbody id="dbExplorerTableBody" class="divide-y divide-slate-800/60 bg-[#0b1325]">
                        <!-- Rows injected by JavaScript -->
                    </tbody>
                </table>
            </div>

        </div>

        <!-- Modal Footer -->
        <div class="p-4 border-t border-slate-800 flex justify-between items-center bg-[#070e1c] shrink-0">
            <span class="text-xxs text-slate-400">
                <i class="fa-solid fa-lock text-app-primary mr-1"></i>
                Setiap berkas diamankan dengan Soft-Delete & Database Backup
            </span>
            <button onclick="app.closeDbExplorerModal()" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-colors shadow-[0_0_12px_rgba(37,99,235,0.4)]">
                Tutup Explorer
            </button>
        </div>
    </div>
</div>
