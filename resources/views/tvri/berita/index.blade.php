@extends('layouts.app')

@section('title', 'Dashboard Pemberitaan - TVRI Maluku')

@php
    use App\Http\Controllers\Tvri\BeritaController;
    $badge = [
        'Selesai' => 'bg-emerald-100 text-emerald-700',
        'Dalam Proses' => 'bg-blue-100 text-blue-700',
        'Rencana' => 'bg-slate-100 text-slate-600',
        'Terkendala' => 'bg-rose-100 text-rose-700',
        'Batal' => 'bg-amber-100 text-amber-700',
    ];
    $maxKat = max(1, $perKategori->max() ?? 1);
@endphp

@section('content')
<div class="space-y-5">

    <!-- ===== HEADER ===== -->
    <div class="bg-gradient-to-r from-[#001D38] via-[#00264A] to-[#003B70] rounded-3xl p-6 text-white shadow-xl relative overflow-hidden border border-blue-400/20">
        <div class="absolute -right-10 -top-16 w-64 h-64 bg-rose-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-white p-2 shadow-lg flex-shrink-0">
                    <img src="{{ asset('images/logo_tvri.png') }}" alt="Logo TVRI" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="text-xl font-black tracking-tight uppercase leading-tight">Dashboard Pemberitaan</h1>
                    <p class="text-sm font-extrabold text-cyan-300 tracking-widest uppercase">TVRI Newsroom &bull; Maluku</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('tvri.ecc.dashboard') }}" class="px-4 py-2 rounded-xl bg-white/10 border border-white/20 text-xs font-bold hover:bg-white/20 transition-colors">&larr; ECC Kepala Stasiun</a>
                <a href="{{ route('tvri.berita.manage', ['tab' => 'berita']) }}" class="px-4 py-2 rounded-xl bg-[#E31837] text-xs font-extrabold hover:bg-[#c01230] transition-colors shadow-lg">+ Tambah Berita</a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl px-4 py-3 text-sm font-semibold">{{ session('success') }}</div>
    @endif

    <!-- ===== KPI ===== -->
    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-5 gap-3">
        <div class="bg-[#00264A] rounded-2xl p-4 text-white shadow-lg border border-blue-400/20">
            <p class="text-[10px] font-bold uppercase tracking-wider text-cyan-300">Total Liputan</p>
            <p class="text-3xl font-black mt-1">{{ number_format($total) }}</p>
            <p class="text-[10px] font-semibold text-blue-300 mt-0.5">seluruh kategori</p>
        </div>
        @foreach (['Selesai' => 'text-emerald-400', 'Dalam Proses' => 'text-blue-300', 'Terkendala' => 'text-rose-400', 'Batal' => 'text-amber-400'] as $st => $warna)
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ $st }}</p>
                <p class="text-3xl font-black text-[#00264A] mt-1">{{ number_format($perStatus[$st] ?? 0) }}</p>
                <span class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-[9px] font-extrabold {{ $badge[$st] }}">{{ $st }}</span>
            </div>
        @endforeach
    </div>

    <!-- ===== DISTRIBUSI ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <p class="text-[11px] font-extrabold uppercase tracking-wider text-[#00264A] mb-3">Sebaran Kategori Liputan</p>
            <div class="space-y-2.5">
                @foreach ($perKategori as $kat => $jumlah)
                    <div>
                        <div class="flex justify-between text-[11px] font-semibold text-slate-600 mb-0.5"><span>{{ $kat }}</span><span>{{ $jumlah }}</span></div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden"><div class="h-full bg-[#005B9F] rounded-full" style="width: {{ round($jumlah / $maxKat * 100) }}%"></div></div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <p class="text-[11px] font-extrabold uppercase tracking-wider text-[#00264A] mb-3">Sebaran Lokasi</p>
            <div class="space-y-2.5">
                @foreach ($perLokasi as $lok => $jumlah)
                    <div>
                        <div class="flex justify-between text-[11px] font-semibold text-slate-600 mb-0.5"><span>{{ $lok }}</span><span>{{ $jumlah }}</span></div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden"><div class="h-full bg-[#E31837] rounded-full" style="width: {{ round($jumlah / max(1, $perLokasi->max()) * 100) }}%"></div></div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <p class="text-[11px] font-extrabold uppercase tracking-wider text-[#00264A] mb-3">Reporter Aktif (Top 5)</p>
            <div class="space-y-2">
                @foreach ($perReporter as $r)
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2 last:border-0">
                        <div>
                            <p class="text-xs font-bold text-slate-700">{{ $r->reporter }}</p>
                            <p class="text-[10px] text-slate-400 font-semibold">{{ $r->jumlah }} liputan &bull; {{ number_format($r->views) }} views</p>
                        </div>
                        <span class="w-8 h-8 rounded-full bg-blue-50 text-[#003B70] flex items-center justify-center text-[11px] font-black">{{ $loop->iteration }}</span>
                    </div>
                @endforeach
            </div>
            <div class="mt-3 bg-[#001D38] rounded-xl p-3 text-center">
                <p class="text-[9px] font-bold uppercase tracking-wider text-blue-300">Total Views Liputan</p>
                <p class="text-xl font-black text-white">{{ number_format($totalViews) }}</p>
            </div>
        </div>
    </div>

    <!-- ===== TABEL + FILTER ===== -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-3">
            <p class="text-[12px] font-extrabold uppercase tracking-wider text-[#00264A]">Data Liputan Berita</p>
            <form method="GET" action="{{ route('tvri.berita.dashboard') }}" class="flex flex-wrap items-center gap-2">
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari judul / reporter..." class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#005B9F] w-44">
                <select name="status" class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#005B9F]">
                    <option value="">Semua Status</option>
                    @foreach (BeritaController::STATUS as $st)
                        <option value="{{ $st }}" @selected(request('status') == $st)>{{ $st }}</option>
                    @endforeach
                </select>
                <select name="kategori" class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#005B9F]">
                    <option value="">Semua Kategori</option>
                    @foreach (BeritaController::KATEGORI as $kat)
                        <option value="{{ $kat }}" @selected(request('kategori') == $kat)>{{ $kat }}</option>
                    @endforeach
                </select>
                <select name="lokasi" class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#005B9F]">
                    <option value="">Semua Lokasi</option>
                    @foreach (BeritaController::LOKASI as $lok)
                        <option value="{{ $lok }}" @selected(request('lokasi') == $lok)>{{ $lok }}</option>
                    @endforeach
                </select>
                <button class="px-4 py-1.5 rounded-xl bg-[#003B70] text-white text-xs font-bold hover:bg-[#00264A]">Filter</button>
                <a href="{{ route('tvri.berita.dashboard') }}" class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-500 hover:bg-slate-50">Reset</a>
                <button type="button" id="btnBulkDeleteIndex" onclick="submitBulkDeleteIndex()" class="hidden px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-sm transition-all flex items-center space-x-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span id="btnBulkDeleteIndexText">Hapus Terpilih</span>
                </button>
            </form>
        </div>

        <!-- Hidden Form Bulk Delete -->
        <form id="formBulkDeleteIndex" action="{{ route('tvri.berita.bulk-destroy') }}" method="POST" class="hidden">
            @csrf
            <div id="bulkDeleteIndexContainer"></div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-[#001D38] text-white">
                    <tr>
                        <th class="w-8 px-4 py-3 text-center">
                            <input type="checkbox" id="checkAllIndex" class="rounded border-slate-300 text-blue-600 w-4 h-4 cursor-pointer" title="Kotak Central: Centang Semua Berita untuk Dihapus">
                        </th>
                        <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider">Judul</th>
                        <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider">Kategori</th>
                        <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider">Jenis</th>
                        <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider">Lokasi</th>
                        <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider">Reporter</th>
                        <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider">Rencana</th>
                        <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider">Tayang</th>
                        <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider">Views</th>
                        <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-[10px] font-bold uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($beritas as $b)
                        <tr class="hover:bg-blue-50/40 transition-colors">
                            <td class="px-4 py-3 text-center">
                                <input type="checkbox" value="{{ $b->id }}" class="index-checkbox rounded border-slate-300 text-blue-600 w-4 h-4 cursor-pointer" title="Pilih berita ini">
                            </td>
                            <td class="px-4 py-3 text-xs font-bold text-slate-800 max-w-xs">{{ $b->judul }}</td>
                            <td class="px-4 py-3 text-[11px] text-slate-600 font-semibold">{{ $b->kategori }}</td>
                            <td class="px-4 py-3 text-[11px] text-slate-600">{{ $b->jenis }}</td>
                            <td class="px-4 py-3 text-[11px] text-slate-600">{{ $b->lokasi }}</td>
                            <td class="px-4 py-3 text-[11px] text-slate-600 font-semibold">{{ $b->reporter }}</td>
                            <td class="px-4 py-3 text-[11px] text-slate-500">{{ $b->tanggal_rencana?->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-[11px] text-slate-500">{{ $b->tanggal_tayang?->format('d/m/Y') ?? '-' }}</td>
                            <td class="px-4 py-3 text-[11px] text-slate-600 font-semibold">{{ number_format($b->views) }}</td>
                            <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold {{ $badge[$b->status] ?? 'bg-slate-100 text-slate-600' }}">{{ $b->status }}</span></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('tvri.berita.manage', ['tab' => 'berita']) }}" class="px-2.5 py-1 rounded-lg bg-blue-50 text-[#003B70] text-[10px] font-bold hover:bg-blue-100">Ubah</a>
                                    <form method="POST" action="{{ route('tvri.berita.destroy', $b) }}" onsubmit="return confirm('Hapus berita ini?')">
                                        @csrf @method('DELETE')
                                        <button class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 text-[10px] font-bold hover:bg-rose-100">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="11" class="px-4 py-8 text-center text-xs text-slate-400 font-semibold">Tidak ada data yang cocok dengan filter.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">{{ $beritas->links() }}</div>
    </div>

    <!-- ===== TERBARU ===== -->
    @if ($terbaru->isNotEmpty())
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <p class="text-[11px] font-extrabold uppercase tracking-wider text-[#00264A] mb-3">Entri Terbaru</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-3">
                @foreach ($terbaru as $t)
                    <div class="border border-slate-100 rounded-xl p-3 bg-slate-50/50">
                        <p class="text-[11px] font-bold text-slate-700 leading-snug">{{ Str::limit($t->judul, 48) }}</p>
                        <p class="text-[10px] text-slate-400 font-semibold mt-1">{{ $t->reporter }} &bull; {{ $t->tanggal_rencana?->format('d/m/Y') }}</p>
                        <span class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-[9px] font-extrabold {{ $badge[$t->status] ?? 'bg-slate-100 text-slate-600' }}">{{ $t->status }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkAll = document.getElementById('checkAllIndex');
    const checkboxes = document.querySelectorAll('.index-checkbox');
    const btnBulk = document.getElementById('btnBulkDeleteIndex');
    const btnBulkText = document.getElementById('btnBulkDeleteIndexText');

    function updateState() {
        const checkedCount = document.querySelectorAll('.index-checkbox:checked').length;
        if (btnBulk) {
            if (checkedCount > 0) {
                btnBulk.classList.remove('hidden');
                if (btnBulkText) btnBulkText.innerText = `Hapus (${checkedCount}) Terpilih`;
            } else {
                btnBulk.classList.add('hidden');
            }
        }
    }

    if (checkAll) {
        checkAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = checkAll.checked);
            updateState();
        });
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updateState));
});

window.submitBulkDeleteIndex = function() {
    const checked = document.querySelectorAll('.index-checkbox:checked');
    if (checked.length === 0) return;
    if (confirm(`Apakah Anda yakin ingin menghapus ${checked.length} data berita terpilih beserta file video terkait? Tindakan ini tidak dapat dibatalkan.`)) {
        const form = document.getElementById('formBulkDeleteIndex');
        const container = document.getElementById('bulkDeleteIndexContainer');
        if (container) {
            container.innerHTML = '';
            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                container.appendChild(input);
            });
        }
        form.submit();
    }
};
</script>
@endsection
