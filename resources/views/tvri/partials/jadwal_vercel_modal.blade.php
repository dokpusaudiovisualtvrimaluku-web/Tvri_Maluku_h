<!-- ===== MODAL JADWAL PELIPUTAN BERITA TVRI MALUKU (LIVE PORTAL VERCEL) ===== -->
<div id="jadwalVercelModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-3 sm:p-5 bg-slate-900/70 backdrop-blur-sm transition-opacity duration-200" aria-modal="true" role="dialog">
    <div class="relative w-full max-w-6xl h-[92vh] bg-white rounded-3xl shadow-2xl flex flex-col overflow-hidden border border-slate-200/80 animate-in fade-in zoom-in-95 duration-200">
        <!-- Modal Header -->
        <div class="px-5 py-3.5 bg-gradient-to-r from-[#00142A] via-[#00264A] to-[#005B9F] text-white flex items-center justify-between flex-shrink-0 border-b border-cyan-400/20">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-blue-600/60 border border-cyan-400/30 flex items-center justify-center flex-shrink-0 text-cyan-300 shadow-inner">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <h3 class="text-sm font-black tracking-wide text-white uppercase">
                            Jadwal Peliputan Berita & Rencana Penugasan Crew
                        </h3>
                        <span class="px-2 py-0.5 rounded-full bg-cyan-400/20 text-cyan-200 text-[10px] font-extrabold border border-cyan-300/30">Live Portal</span>
                    </div>
                    <p class="text-[11px] text-blue-200 font-semibold mt-0.5">
                        TVRI Stasiun Maluku &bull; Koordinator Redaksi &amp; Penugasan Armada Lapangan
                    </p>
                </div>
            </div>

            <!-- Header Action Buttons -->
            <div class="flex items-center space-x-2">
                <a href="{{ route('tvri.berita.jadwal-portal') }}" target="_blank" rel="noopener noreferrer" 
                   class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-white/15 hover:bg-white/25 text-white border border-white/20 transition-all shadow-sm">
                    <span>Buka di Tab Baru</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                </a>
                <button type="button" onclick="closeJadwalVercelModal()" 
                        class="p-1.5 rounded-xl text-white/80 hover:text-white hover:bg-white/20 transition-colors" title="Tutup Modal (Esc)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        <!-- Modal Iframe Body -->
        <div class="relative flex-1 w-full bg-slate-100 overflow-hidden flex flex-col">
            <iframe id="jadwalVercelIframe" 
                    data-src="{{ route('tvri.berita.jadwal-portal') }}" 
                    class="w-full h-full flex-1 border-0" 
                    title="Jadwal Peliputan TVRI Maluku" 
                    loading="lazy"></iframe>
        </div>

        <!-- Modal Footer -->
        <div class="px-5 py-2.5 bg-slate-50 border-t border-slate-200 text-slate-500 text-[11px] flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <span class="font-bold text-slate-700">Digital Hub Newsroom &bull; TVRI Stasiun Maluku</span>
                <span>&bull;</span>
                <span class="text-slate-500">Terhubung langsung ke Command Center Kepala Stasiun &amp; Dasbor Berita</span>
            </div>
            <button type="button" onclick="closeJadwalVercelModal()" class="px-3 py-1 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-200 transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    window.openJadwalVercelModal = function() {
        const modal = document.getElementById('jadwalVercelModal');
        const iframe = document.getElementById('jadwalVercelIframe');
        if (modal) {
            if (iframe) {
                const baseSrc = iframe.getAttribute('data-src') || '{{ route('tvri.berita.jadwal-portal') }}';
                iframe.src = baseSrc + (baseSrc.includes('?') ? '&' : '?') + '_t=' + Date.now();
            }
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeJadwalVercelModal = function() {
        const modal = document.getElementById('jadwalVercelModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    };

    // Close on backdrop click
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('jadwalVercelModal');
        if (modal && !modal.classList.contains('hidden') && e.target === modal) {
            closeJadwalVercelModal();
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeJadwalVercelModal();
        }
    });
</script>
