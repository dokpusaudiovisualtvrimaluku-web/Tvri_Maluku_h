<!-- UPLOAD MODAL -->
<div id="uploadModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-200">
    <div class="bg-app-surface border border-app-border rounded-xl shadow-2xl w-full max-w-lg transform scale-95 transition-transform duration-200" id="uploadModalContent">
        
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-4 border-b border-app-border">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-app-primary/20 flex items-center justify-center text-app-primary">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                </div>
                <div>
                    <h3 class="font-bold text-white">Kotak Upload Data</h3>
                    <p class="text-xxs text-app-text" id="modalSubTitle">Modul Terpilih</p>
                </div>
            </div>
            <button onclick="app.closeModal()" class="text-app-text hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-app-surfaceHover">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Judul / Nama Laporan & Berkas</label>
                <input type="text" id="uploadTitle" placeholder="Contoh: Laporan Kinerja Bulanan..." class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400 transition-colors">
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Kategori / Jenis Laporan</label>
                    <input type="text" id="uploadCategory" placeholder="Contoh: Laporan Realisasi / Dokumen Sektoral" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Penanggung Jawab / PIC</label>
                    <input type="text" id="uploadPic" placeholder="Contoh: Tim Kerja Bidang / Subbag" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Pagu / Nilai Anggaran (Rp)</label>
                    <input type="number" id="uploadBudget" placeholder="0" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Status Laporan</label>
                    <select id="uploadStatus" class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                        <option value="VALIDATED" selected>VALIDATED (Terverifikasi)</option>
                        <option value="APPROVED">APPROVED (Disetujui)</option>
                        <option value="IN REVIEW">IN REVIEW (Dalam Proses)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Ringkasan / Uraian Dokumen (Opsional)</label>
                <textarea id="uploadDesc" rows="2" placeholder="Uraian singkat isi berkas..." class="w-full bg-[#070e1c] border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400"></textarea>
            </div>

            <div class="border-2 border-dashed border-slate-700 rounded-lg p-5 text-center hover:border-cyan-400/50 transition-colors bg-[#070e1c]/80 cursor-pointer" onclick="document.getElementById('fileInput').click()">
                <i class="fa-solid fa-file-arrow-up text-2xl text-cyan-400 mb-1.5"></i>
                <p class="text-xs font-medium text-white mb-0.5">Pilih Berkas Lampiran (PDF / XLSX / DOCX / ZIP)</p>
                <p class="text-[10px] text-slate-400">Maksimal ukuran file 20 MB</p>
                <input type="file" id="fileInput" class="hidden">
                
                <div class="mt-3 flex items-center justify-center gap-2">
                    <button type="button" class="bg-slate-800 border border-slate-700 text-xxs px-2.5 py-1 rounded hover:bg-slate-700 text-white">Choose File</button>
                    <span class="text-xxs text-slate-400" id="fileNameDisplay">No file chosen</span>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-4 border-t border-slate-800 flex justify-end gap-3 bg-[#070e1c] rounded-b-xl">
            <button onclick="app.closeModal()" class="px-4 py-2 text-xs text-slate-400 hover:text-white transition-colors">Batal</button>
            <button onclick="app.simulateUpload()" id="btnUpload" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-400 text-slate-950 text-xs font-black rounded-lg transition-colors flex items-center gap-2 shadow-[0_0_12px_rgba(0,246,255,0.4)]">
                <i class="fa-solid fa-upload"></i> Unggah & Simpan Data
            </button>
        </div>
    </div>
</div>
