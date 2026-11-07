<!-- FILE PREVIEW & DATABASE INSPECTOR MODAL -->
<div id="filePreviewModal" class="fixed inset-0 z-[70] flex items-center justify-center bg-black/80 backdrop-blur-sm hidden opacity-0 transition-opacity duration-200">
    <div class="bg-[#0b1325] border border-blue-500/40 rounded-2xl shadow-2xl w-full max-w-2xl transform scale-95 transition-transform duration-200 overflow-hidden flex flex-col max-h-[90vh]" id="filePreviewModalContent">
        
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-4 border-b border-slate-800 bg-[#070e1c] shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-600/20 border border-blue-500/40 flex items-center justify-center text-app-primary shadow-inner">
                    <i class="fa-solid fa-file-waveform text-lg"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-white text-base flex items-center gap-2">
                        Pratinjau Berkas & Database Inspector
                        <span class="text-[9px] bg-emerald-950/60 text-emerald-400 border border-emerald-500/30 px-2 py-0.5 rounded-full font-mono flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            SIMANDAT SIWALIMA
                        </span>
                    </h3>
                    <p class="text-xxs text-slate-400">Pusat Data Arsip Terintegrasi - TVRI Maluku</p>
                </div>
            </div>
            <button onclick="app.closeFilePreviewModal()" class="text-slate-400 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-800">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-5 space-y-4 overflow-y-auto custom-scrollbar flex-1">
            
            <!-- Metadata & Database Info -->
            <div class="bg-[#070e1c] border border-slate-800 rounded-xl p-4 space-y-2.5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <span class="text-[10px] font-mono text-app-primary bg-app-primary/10 border border-app-primary/30 px-2 py-0.5 rounded" id="previewDocCode">DOC-2.1-001</span>
                        <h4 class="text-sm font-bold text-white mt-1.5" id="previewDocTitle">Judul Dokumen</h4>
                        <div class="text-xxs text-slate-400 mt-0.5" id="previewDocCategory">Modul 2.1 Subbag. Umum dan Aset</div>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="inline-block text-[10px] bg-app-success/20 text-app-success border border-app-success/40 px-2.5 py-0.5 rounded-full font-bold">
                            <i class="fa-solid fa-circle-check mr-1"></i> VALIDATED
                        </span>
                        <div class="text-[10px] text-slate-400 mt-1" id="previewDocFormatSize">PDF (3.8 MB)</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 pt-2.5 border-t border-slate-800/80 text-xs">
                    <div>
                        <div class="text-[10px] text-slate-400 font-semibold">Tabel Database:</div>
                        <div class="font-mono text-blue-400 text-xxs font-bold mt-0.5">simandat_archives</div>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-semibold">Waktu Unggah:</div>
                        <div class="text-white text-xxs font-medium mt-0.5" id="previewDocDateTime">Sabtu, 12 Sep 2026</div>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-semibold">Pengunggah:</div>
                        <div class="text-white text-xxs font-medium mt-0.5" id="previewDocUploader">Super Admin</div>
                    </div>
                </div>
            </div>

            <!-- Content Preview Paper -->
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <div class="text-xxs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-code text-app-primary"></i>
                        Isi & Catatan Rekam Dokumen (Database Stream)
                    </div>
                    <span class="text-[9px] text-slate-500 font-mono">Status: Read Only</span>
                </div>
                
                <pre class="bg-[#070e1c] border border-slate-800 rounded-xl p-4 text-xs font-mono text-slate-300 overflow-x-auto leading-relaxed max-h-64 custom-scrollbar whitespace-pre-wrap selection:bg-app-primary selection:text-black" id="previewDocContent">Memuat data dari database SIMANDAT SIWALIMA...</pre>
            </div>

        </div>

        <!-- Modal Footer -->
        <div class="p-4 border-t border-slate-800 flex justify-between items-center bg-[#070e1c] shrink-0">
            <span class="text-xxs text-slate-400 flex items-center gap-1.5">
                <i class="fa-solid fa-shield-halved text-emerald-400"></i>
                Dilindungi Soft-Delete pada MySQL
            </span>
            <div class="flex items-center gap-2">
                <button onclick="app.closeFilePreviewModal()" class="px-4 py-2 text-xs text-slate-400 hover:text-white transition-colors">Tutup</button>
                <button onclick="app.downloadCurrentPreviewFile()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-2 shadow-[0_0_12px_rgba(37,99,235,0.4)]">
                    <i class="fa-solid fa-download"></i> Unduh File Ini
                </button>
            </div>
        </div>
    </div>
</div>
