<!-- EDIT ARCHIVE / REPORT MODAL -->
<div id="editArchiveModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-200">
    <div class="bg-app-surface border border-cyan-500/40 rounded-xl shadow-2xl w-full max-w-xl transform scale-95 transition-transform duration-200" id="editArchiveModalContent">
        
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-4 border-b border-app-border bg-[#070e1c] rounded-t-xl">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-cyan-500/20 text-cyan-400 flex items-center justify-center border border-cyan-500/40">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <div>
                    <h3 class="font-bold text-white text-sm" id="editArchiveModalTitle">Edit Data Laporan & Berkas</h3>
                    <p class="text-xxs text-cyan-400/80" id="editArchiveModalSubTitle">Pangkalan Data Dinas PUPR Maluku</p>
                </div>
            </div>
            <button onclick="app.closeEditArchiveModal()" class="text-slate-400 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-800">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Modal Body Form -->
        <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">
            <input type="hidden" id="editArchiveModuleId" value="">
            <input type="hidden" id="editArchiveIndex" value="-1">
            <input type="hidden" id="editArchiveDbId" value="">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Judul / Nama Laporan & Dokumen</label>
                    <input type="text" id="editArchiveTitle" placeholder="Contoh: Laporan Kinerja Bulanan..." class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Kode / Nomor Berkas</label>
                    <input type="text" id="editArchiveCode" placeholder="Contoh: DOC-2.1-001" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-cyan-300 font-mono focus:outline-none focus:border-cyan-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Kategori / Jenis Laporan</label>
                    <input type="text" id="editArchiveCategory" placeholder="Contoh: Laporan Realisasi / Sektoral" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Penanggung Jawab / PIC</label>
                    <input type="text" id="editArchivePic" placeholder="Contoh: Subbag Perencanaan & Keuangan" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Pagu / Nilai Anggaran (Rp)</label>
                    <input type="number" id="editArchiveBudget" placeholder="0" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Status Verifikasi Sistem</label>
                    <select id="editArchiveStatus" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                        <option value="VALIDATED">VALIDATED (Terverifikasi)</option>
                        <option value="APPROVED">APPROVED (Disetujui Pimpinan)</option>
                        <option value="IN REVIEW">IN REVIEW (Dalam Proses)</option>
                        <option value="ARCHIVED">ARCHIVED (Tersimpan di Pangkalan Data Dinas PUPR Maluku)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Pengunggah Dokumen</label>
                    <input type="text" id="editArchiveUploader" placeholder="Super Admin (TVRI Maluku)" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Ringkasan / Uraian Laporan</label>
                    <textarea id="editArchiveDesc" rows="3" placeholder="Uraian singkat isi laporan atau tindak lanjut..." class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400"></textarea>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-4 border-t border-slate-800 flex justify-end gap-3 bg-[#070e1c] rounded-b-xl">
            <button onclick="app.closeEditArchiveModal()" class="px-4 py-2 text-xs text-slate-400 hover:text-white transition-colors">Batal</button>
            <button onclick="app.saveEditArchiveData()" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-400 text-slate-950 text-xs font-black rounded-lg transition-colors flex items-center gap-2 shadow-[0_0_12px_rgba(0,246,255,0.4)]">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Pembaruan Laporan
            </button>
        </div>
    </div>
</div>