<!-- DATABASE ACCESS AUTHORIZATION MODAL (PASSWORD: dikbud_2026) -->
<div id="dbAuthModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/80 backdrop-blur-sm hidden opacity-0 transition-opacity duration-200">
    <div class="bg-[#0b1325] border border-cyan-500/40 rounded-2xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-200 overflow-hidden" id="dbAuthModalContent">
        
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-4 border-b border-slate-800 bg-[#070e1c]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-cyan-500/20 border border-cyan-500/40 flex items-center justify-center text-app-primary shadow-inner">
                    <i class="fa-solid fa-lock text-lg"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-white text-sm flex items-center gap-1.5">
                        Otorisasi Akun Database
                        <span class="text-[9px] bg-cyan-950/80 text-cyan-300 border border-cyan-500/40 px-2 py-0.5 rounded font-mono">
                            dikbud_2026
                        </span>
                    </h3>
                    <p class="text-xxs text-slate-400">Verifikasi Keamanan Akses Berkas Database TVRI Maluku</p>
                </div>
            </div>
            <button onclick="app.closeDbAuthModal()" class="text-slate-400 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-800">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-5 space-y-3.5">
            <div class="p-3 bg-blue-950/30 border border-blue-500/30 rounded-xl text-xxs text-blue-200 space-y-1">
                <div class="font-bold flex items-center gap-1.5 text-app-primary text-xs">
                    <i class="fa-solid fa-shield-halved"></i>
                    Akses Berkas Terlindungi
                </div>
                <p class="text-slate-300 leading-relaxed">
                    Masukkan password akun administrator resmi untuk membuka, meninjau, dan mengelola seluruh arsip digital yang tersimpan pada sistem database terintegrasi.
                </p>
            </div>

            <!-- Password / PIN Input -->
            <div class="space-y-1.5 bg-[#070e1c] p-3.5 rounded-xl border border-slate-800">
                <label class="block text-[11px] font-bold text-slate-300 flex items-center justify-between">
                    <span class="flex items-center gap-1.5 text-white">
                        <i class="fa-solid fa-key text-app-primary text-xs"></i>
                        Password Akun Database:
                    </span>
                    <span class="text-[10px] text-cyan-400 font-mono font-semibold">*Wajib Diisi</span>
                </label>
                
                <div class="relative">
                    <input type="password" id="dbAuthPassword" placeholder="Ketik Password (dikbud_2026)..." onkeyup="if(event.key==='Enter') app.verifyDbExplorerPassword()" class="w-full bg-[#0b1325] border border-slate-700 focus:border-app-primary rounded-lg px-3.5 py-2.5 text-xs text-white placeholder:text-slate-500 font-mono tracking-wider focus:outline-none transition-colors">
                    <button type="button" onclick="app.toggleDbAuthPasswordVisibility()" class="absolute right-3 top-2.5 text-slate-400 hover:text-white text-xs" title="Lihat/Sembunyikan Password">
                        <i class="fa-solid fa-eye" id="dbAuthEyeIcon"></i>
                    </button>
                </div>

                <div class="flex items-center justify-between text-[10px] text-slate-400 pt-1">
                    <span>Password Resmi: <strong class="text-app-primary font-mono font-bold">dikbud_2026</strong></span>
                    <span class="text-slate-500 italic">TVRI Maluku Security</span>
                </div>

                <div id="dbAuthErrorMsg" class="hidden text-[11px] font-bold text-red-400 flex items-center gap-1.5 pt-1.5 border-t border-red-500/20">
                    <i class="fa-solid fa-circle-exclamation text-xs"></i>
                    <span>Password salah! Masukkan password resmi (<code class="font-mono text-red-300">dikbud_2026</code>) untuk membuka berkas database.</span>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-4 border-t border-slate-800 flex justify-end gap-3 bg-[#070e1c]">
            <button onclick="app.closeDbAuthModal()" class="px-4 py-2 text-xs text-slate-400 hover:text-white transition-colors font-medium">
                Batal
            </button>
            <button onclick="app.verifyDbExplorerPassword()" id="btnConfirmDbAuth" class="px-5 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-500 hover:to-cyan-500 text-white text-xs font-bold rounded-lg transition-all flex items-center gap-2 shadow-[0_0_15px_rgba(0,246,255,0.3)]">
                <i class="fa-solid fa-lock-open"></i> Buka Berkas Database
            </button>
        </div>
    </div>
</div>
