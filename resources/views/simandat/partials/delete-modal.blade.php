<!-- PROTECTED SOFT DELETE CONFIRMATION MODAL -->
<div id="deleteModal" class="fixed inset-0 z-[75] flex items-center justify-center bg-black/75 backdrop-blur-sm hidden opacity-0 transition-opacity duration-200">
    <div class="bg-[#0b1325] border border-red-500/40 rounded-xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-200 overflow-hidden" id="deleteModalContent">
        
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-4 border-b border-slate-800 bg-red-950/30">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-red-500/20 border border-red-500/40 flex items-center justify-center text-red-400 shadow-inner">
                    <i class="fa-solid fa-shield-halved text-base"></i>
                </div>
                <div>
                    <h3 class="font-bold text-white text-sm">Proteksi Hapus Arsip Digital</h3>
                    <p class="text-xxs text-red-300">Database Soft-Delete Safeguard Active</p>
                </div>
            </div>
            <button onclick="app.closeDeleteModal()" class="text-slate-400 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-800">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-5 space-y-3">
            <input type="hidden" id="deleteTargetId" value="">
            
            <div class="p-3 bg-[#070e1c] border border-slate-800 rounded-lg">
                <div class="text-[10px] text-slate-400 uppercase font-semibold mb-1">Dokumen yang akan dihapus:</div>
                <div class="text-xs font-bold text-white leading-snug" id="deleteTargetTitle">-</div>
            </div>

            <div class="p-3 bg-amber-950/20 border border-amber-500/30 rounded-lg text-xxs text-amber-200 space-y-1">
                <div class="font-bold flex items-center gap-1.5 text-amber-400">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Sistem Perlindungan Keamanan Database:
                </div>
                <p>Arsip ini akan dipindahkan ke status <span class="font-bold text-white">Soft-Deleted</span> pada database SIMANDAT. Data tidak akan hilang permanen dan dapat dipulihkan sewaktu-waktu oleh Super Admin.</p>
            </div>

            <!-- Password / PIN Authorization Input -->
            <div class="space-y-1.5 pt-1 bg-[#070e1c] p-3 rounded-lg border border-slate-800">
                <label class="block text-[11px] font-bold text-slate-300 flex items-center justify-between">
                    <span class="flex items-center gap-1.5 text-white">
                        <i class="fa-solid fa-lock text-app-primary text-xs"></i>
                        Password Otorisasi Hapus (PIN):
                    </span>
                    <span class="text-[10px] text-red-400 font-mono font-semibold">*Wajib Diisi</span>
                </label>
                <div class="relative">
                    <input type="password" id="deleteSecurityPin" maxlength="10" placeholder="Ketik Password (1237)..." onkeyup="if(event.key==='Enter') app.executeDeleteArchive()" class="w-full bg-[#0b1325] border border-slate-700 focus:border-red-500 rounded-lg px-3.5 py-2 text-xs text-white placeholder:text-slate-500 font-mono tracking-widest focus:outline-none transition-colors">
                    <button type="button" onclick="app.toggleDeletePinVisibility()" class="absolute right-3 top-2 text-slate-400 hover:text-white text-xs" title="Lihat/Sembunyikan Password">
                        <i class="fa-solid fa-eye" id="deletePinEyeIcon"></i>
                    </button>
                </div>
                <div class="flex items-center justify-between text-[10px] text-slate-400 pt-0.5">
                    <span>Password Resmi: <strong class="text-app-primary font-mono font-bold">1237</strong></span>
                    <span class="text-slate-500 italic">Proteksi Data Kehilangan</span>
                </div>
                <div id="deletePinErrorMsg" class="hidden text-[10px] font-bold text-red-400 flex items-center gap-1 pt-1 border-t border-red-500/20">
                    <i class="fa-solid fa-circle-exclamation"></i> Password salah! Gunakan password resmi (1237) untuk menghapus.
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-4 border-t border-slate-800 flex justify-end gap-3 bg-[#070e1c]">
            <button onclick="app.closeDeleteModal()" class="px-4 py-2 text-xs text-slate-400 hover:text-white transition-colors font-medium">Batal</button>
            <button onclick="app.executeDeleteArchive()" id="btnConfirmDelete" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-2 shadow-[0_0_12px_rgba(239,68,68,0.4)]">
                <i class="fa-solid fa-trash"></i> Hapus & Lindungi di Database
            </button>
        </div>
    </div>
</div>
