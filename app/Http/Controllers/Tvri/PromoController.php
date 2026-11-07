<?php

namespace App\Http\Controllers\Tvri;

use App\Http\Controllers\Controller;
use App\Models\TvriPromoRingkasan;
use App\Models\TvriPromoSop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class PromoController extends Controller
{
    /**
     * Ambil data ringkasan promo atau fallback ke seeder/default
     */
    protected function getRingkasan(): TvriPromoRingkasan
    {
        $ringkasan = TvriPromoRingkasan::first();
        if (!$ringkasan) {
            Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\TvriPromoDataSeeder']);
            $ringkasan = TvriPromoRingkasan::first();
        }
        return $ringkasan;
    }

    /**
     * Tampilan Utama DASHBOARD PROMO TVRI MALUKU
     */
    public function index()
    {
        $ringkasan = $this->getRingkasan();

        // 1. Ringkasan Metrik Utama
        $metrik = [
            'total_kampanye' => $ringkasan->total_kampanye,
            'diff_kampanye' => $ringkasan->diff_kampanye,
            'materi_diproduksi' => $ringkasan->materi_diproduksi,
            'diff_materi' => $ringkasan->diff_materi,
            'penayangan_onair' => $ringkasan->penayangan_onair,
            'diff_onair' => $ringkasan->diff_onair,
            'reach_total' => $ringkasan->reach_total,
            'diff_reach' => $ringkasan->diff_reach,
            'engagement_digital' => $ringkasan->engagement_digital,
            'diff_engagement' => $ringkasan->diff_engagement,
            'tingkat_capaian' => $ringkasan->tingkat_capaian,
            'target_capaian' => $ringkasan->target_capaian,
        ];

        // 2. Distribusi Media Promo (Total Penayangan: 1.230)
        $distribusiMedia = $ringkasan->distribusi_media_json ?: [
            ['media' => 'TV On-Air', 'persen' => 45, 'jumlah' => 554, 'color' => 'bg-indigo-600', 'textColor' => 'text-indigo-600'],
            ['media' => 'YouTube', 'persen' => 20, 'jumlah' => 246, 'color' => 'bg-rose-600', 'textColor' => 'text-rose-600'],
            ['media' => 'Instagram', 'persen' => 15, 'jumlah' => 185, 'color' => 'bg-pink-600', 'textColor' => 'text-pink-600'],
            ['media' => 'TikTok', 'persen' => 10, 'jumlah' => 123, 'color' => 'bg-slate-800', 'textColor' => 'text-slate-800'],
            ['media' => 'Website', 'persen' => 5, 'jumlah' => 62, 'color' => 'bg-blue-600', 'textColor' => 'text-blue-600'],
            ['media' => 'Media Luar Ruang', 'persen' => 5, 'jumlah' => 60, 'color' => 'bg-emerald-600', 'textColor' => 'text-emerald-600'],
        ];

        // 3. Capaian Kampanye
        $capaianKampanye = $ringkasan->capaian_kampanye_json ?: [
            ['kategori' => 'Identitas TVRI Maluku', 'persen' => 120, 'status' => 'Melampaui Target', 'color' => 'bg-emerald-500'],
            ['kategori' => 'Program Unggulan', 'persen' => 95, 'status' => 'On Track', 'color' => 'bg-indigo-500'],
            ['kategori' => 'Berita & Current Affairs', 'persen' => 88, 'status' => 'Baik', 'color' => 'bg-blue-500'],
            ['kategori' => 'Kegiatan Daerah', 'persen' => 92, 'status' => 'On Track', 'color' => 'bg-cyan-500'],
            ['kategori' => 'Layanan Publik', 'persen' => 76, 'status' => 'Perhatian', 'color' => 'bg-amber-500'],
            ['kategori' => 'Event Khusus', 'persen' => 110, 'status' => 'Melampaui Target', 'color' => 'bg-purple-500'],
        ];

        // 4. Top Kampanye (Berdasarkan Engagement)
        $topKampanye = $ringkasan->top_kampanye_json ?: [
            ['nama' => 'Maluku Bisa!', 'reach' => '420K Reach', 'engagement' => '68K Engagement', 'pertumbuhan' => '+56%', 'peringkat' => 1],
            ['nama' => 'Dialog Maluku', 'reach' => '320K Reach', 'engagement' => '52K Engagement', 'pertumbuhan' => '+38%', 'peringkat' => 2],
            ['nama' => 'Pesona Maluku', 'reach' => '286K Reach', 'engagement' => '40K Engagement', 'pertumbuhan' => '+32%', 'peringkat' => 3],
            ['nama' => 'UMKM Maluku', 'reach' => '210K Reach', 'engagement' => '28K Engagement', 'pertumbuhan' => '+25%', 'peringkat' => 4],
            ['nama' => 'Ayo Nonton TVRI', 'reach' => '198K Reach', 'engagement' => '24K Engagement', 'pertumbuhan' => '+18%', 'peringkat' => 5],
        ];

        // 5. Performa Platform Digital
        $platformDigital = $ringkasan->platform_digital_json ?: [
            [
                'platform' => 'YouTube',
                'audience' => '286K Subscribers',
                'aud_growth' => '+12%',
                'metric' => '1,2M Views',
                'metric_growth' => '+35%',
                'color' => 'text-rose-600',
                'bg' => 'bg-rose-50 border-rose-100',
            ],
            [
                'platform' => 'Instagram',
                'audience' => '186K Followers',
                'aud_growth' => '+18%',
                'metric' => '520K Reach',
                'metric_growth' => '+42%',
                'color' => 'text-pink-600',
                'bg' => 'bg-pink-50 border-pink-100',
            ],
            [
                'platform' => 'TikTok',
                'audience' => '142K Followers',
                'aud_growth' => '+28%',
                'metric' => '480K Views',
                'metric_growth' => '+55%',
                'color' => 'text-slate-900',
                'bg' => 'bg-slate-100 border-slate-200',
            ],
            [
                'platform' => 'Website',
                'audience' => '88K Visitors',
                'aud_growth' => '+20%',
                'metric' => '3,5 Min Avg. Session',
                'metric_growth' => '+15%',
                'color' => 'text-blue-600',
                'bg' => 'bg-blue-50 border-blue-100',
            ],
        ];

        // 6. Status Produksi Materi (Total: 56)
        $statusProduksi = $ringkasan->status_produksi_json ?: [
            'total' => 56,
            'items' => [
                ['status' => 'Selesai', 'jumlah' => 28, 'persen' => 50, 'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'bar' => 'bg-emerald-500'],
                ['status' => 'Dalam Produksi', 'jumlah' => 15, 'persen' => 27, 'badge' => 'bg-blue-50 text-blue-700 border-blue-200', 'bar' => 'bg-blue-500'],
                ['status' => 'Review', 'jumlah' => 8, 'persen' => 14, 'badge' => 'bg-indigo-50 text-indigo-700 border-indigo-200', 'bar' => 'bg-indigo-500'],
                ['status' => 'Revisi', 'jumlah' => 3, 'persen' => 5, 'badge' => 'bg-amber-50 text-amber-700 border-amber-200', 'bar' => 'bg-amber-500'],
                ['status' => 'Ditunda', 'jumlah' => 2, 'persen' => 4, 'badge' => 'bg-slate-100 text-slate-700 border-slate-200', 'bar' => 'bg-slate-400'],
            ]
        ];

        // 7. Materi Promo Terbaru
        $materiTerbaru = [
            ['nama' => 'Maluku Bisa!', 'jenis' => 'Video Promo', 'durasi' => '00:30', 'status' => 'Selesai'],
            ['nama' => 'Pesona Maluku', 'jenis' => 'Video Promo', 'durasi' => '00:30', 'status' => 'Selesai'],
            ['nama' => 'Dialog Maluku', 'jenis' => 'Bumper Program', 'durasi' => '00:15', 'status' => 'Selesai'],
            ['nama' => 'UMKM Maluku', 'jenis' => 'Video Promo', 'durasi' => '00:30', 'status' => 'Selesai'],
            ['nama' => 'Ayo Nonton TVRI', 'jenis' => 'Bumper Ajakan', 'durasi' => '00:15', 'status' => 'Selesai'],
        ];

        // Jika ada entri baru dari database tabel tvri_materi_promos, gabungkan
        $dbMateri = DB::table('tvri_materi_promos')->orderByDesc('id')->limit(5)->get();
        if ($dbMateri->isNotEmpty()) {
            foreach ($dbMateri as $dm) {
                if (!collect($materiTerbaru)->pluck('nama')->contains($dm->nama)) {
                    array_unshift($materiTerbaru, [
                        'nama' => $dm->nama,
                        'jenis' => $dm->jenis ?? 'Promo Spot',
                        'durasi' => '00:' . str_pad($dm->durasi_detik ?? 30, 2, '0', STR_PAD_LEFT),
                        'status' => $dm->status,
                    ]);
                }
            }
        }

        // 8. Jadwal Penayangan Hari Ini
        $jadwalTayang = $ringkasan->jadwal_tayang_json ?: [
            ['waktu' => '06:15', 'program' => 'Maluku Pagi Ini', 'media' => 'TV', 'durasi' => 30, 'status' => 'Tayang', 'badge' => 'bg-emerald-50 text-emerald-700 border border-emerald-200'],
            ['waktu' => '08:30', 'program' => 'Pesona Maluku', 'media' => 'TV', 'durasi' => 30, 'status' => 'Tayang', 'badge' => 'bg-emerald-50 text-emerald-700 border border-emerald-200'],
            ['waktu' => '12:00', 'program' => 'UMKM Maluku', 'media' => 'TV', 'durasi' => 30, 'status' => 'Tayang', 'badge' => 'bg-emerald-50 text-emerald-700 border border-emerald-200'],
            ['waktu' => '15:30', 'program' => 'Dialog Maluku', 'media' => 'TV', 'durasi' => 60, 'status' => 'Menunggu', 'badge' => 'bg-amber-50 text-amber-700 border border-amber-200 animate-pulse'],
            ['waktu' => '17:45', 'program' => 'Maluku Bisa!', 'media' => 'TV', 'durasi' => 30, 'status' => 'Tayang', 'badge' => 'bg-emerald-50 text-emerald-700 border border-emerald-200'],
            ['waktu' => '19:30', 'program' => 'Ayo Nonton TVRI', 'media' => 'TV', 'durasi' => 30, 'status' => 'Tayang', 'badge' => 'bg-emerald-50 text-emerald-700 border border-emerald-200'],
            ['waktu' => '21:00', 'program' => 'Promo Program Malam', 'media' => 'TV', 'durasi' => 30, 'status' => 'Terjadwal', 'badge' => 'bg-blue-50 text-blue-700 border border-blue-200'],
            ['waktu' => '22:15', 'program' => 'Layanan Publik', 'media' => 'TV', 'durasi' => 30, 'status' => 'Terjadwal', 'badge' => 'bg-blue-50 text-blue-700 border border-blue-200'],
        ];

        // 9. Agenda Promo & Event
        $agendaPromo = $ringkasan->agenda_promo_json ?: [
            ['tanggal' => '4 Sep 2025', 'kegiatan' => 'Promo HUT Maluku', 'pic' => 'Tim Promo', 'status' => 'Persiapan', 'badge' => 'bg-amber-50 text-amber-700 border-amber-200'],
            ['tanggal' => '8 Sep 2025', 'kegiatan' => 'Kampanye UMKM', 'pic' => 'Tim Promo', 'status' => 'On Progress', 'badge' => 'bg-blue-50 text-blue-700 border-blue-200'],
            ['tanggal' => '12 Sep 2025', 'kegiatan' => 'Peluncuran Program Baru', 'pic' => 'Tim Promo', 'status' => 'Persiapan', 'badge' => 'bg-amber-50 text-amber-700 border-amber-200'],
            ['tanggal' => '20 Sep 2025', 'kegiatan' => 'Event Maluku Kreatif', 'pic' => 'Tim Promo', 'status' => 'Planning', 'badge' => 'bg-slate-100 text-slate-700 border-slate-200'],
            ['tanggal' => '25 Sep 2025', 'kegiatan' => 'Promo Hari Pariwisata', 'pic' => 'Tim Promo', 'status' => 'On Progress', 'badge' => 'bg-blue-50 text-blue-700 border-blue-200'],
        ];

        // Metadata Header
        $meta = [
            'waktu' => 'Selasa, 2 September 2025, 10:24 WIT',
            'status' => 'Live Data',
            'periode' => 'September 2025',
        ];

        // SOP & Regulasi Promo
        $sops = TvriPromoSop::orderBy('urutan')->get();

        return view('tvri.promo.dashboard', compact(
            'metrik', 'distribusiMedia', 'capaianKampanye', 'topKampanye',
            'platformDigital', 'statusProduksi', 'materiTerbaru',
            'jadwalTayang', 'agendaPromo', 'meta', 'sops'
        ));
    }

    /**
     * Halaman Input & Kelola Data Promo (khusus akun promo & admin)
     */
    public function manage(Request $request)
    {
        // Proteksi Kepala Stasiun: Kepala Stasiun hanya monitoring
        if (auth()->check()) {
            $user = auth()->user();
            $isKepalaStasiun = $user->hasRole('KEPALA_DINAS') || $user->hasRole('Kepala Stasiun') || $user->username === 'kepala.stasiun' || str_contains(strtolower($user->name ?? ''), 'kepala stasiun');
            if ($isKepalaStasiun) {
                return redirect()->route('tvri.promo.dashboard')->with('warning', 'Akun Kepala Stasiun memiliki akses monitoring eksekutif.');
            }
        }

        $ringkasan = $this->getRingkasan();
        $materiList = DB::table('tvri_materi_promos')->orderByDesc('id')->get();
        $kampanyeList = DB::table('tvri_kampanyes')->orderByDesc('id')->get();
        $sops = TvriPromoSop::orderBy('urutan')->get();
        $tab = $request->query('tab', 'materi-kampanye');

        return view('tvri.promo.manage', compact('ringkasan', 'materiList', 'kampanyeList', 'sops', 'tab'));
    }

    /**
     * Update Ringkasan Metrik Promo
     */
    public function updateRingkasan(Request $request)
    {
        $ringkasan = $this->getRingkasan();
        $data = $request->only([
            'total_kampanye', 'diff_kampanye', 'materi_diproduksi', 'diff_materi',
            'penayangan_onair', 'diff_onair', 'reach_total', 'diff_reach',
            'engagement_digital', 'diff_engagement', 'tingkat_capaian', 'target_capaian'
        ]);

        $ringkasan->update($data);

        return redirect()->route('tvri.promo.manage', ['tab' => 'ringkasan'])->with('success', 'Parameter & Ringkasan Metrik Promo berhasil disimpan dan terhubung ke Dashboard Promo & ECC Kepala Stasiun.');
    }

    /**
     * Pulihkan data default Promo & Citra
     */
    public function reseedDefaults()
    {
        Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\TvriPromoDataSeeder']);
        return redirect()->route('tvri.promo.manage')->with('success', 'Data Promo & Citra berhasil dipulihkan sesuai spesifikasi resmi.');
    }

    /**
     * Simpan jadwal tayang baru
     */
    public function storeJadwal(Request $request)
    {
        $request->validate([
            'waktu' => 'required|string',
            'program' => 'required|string',
            'media' => 'required|string',
            'durasi' => 'required|integer|min:5|max:300',
            'status' => 'required|string',
        ]);

        $ringkasan = $this->getRingkasan();
        $jadwal = $ringkasan->jadwal_tayang_json ?: [];

        $badge = 'bg-blue-50 text-blue-700 border border-blue-200';
        if ($request->status === 'Tayang') {
            $badge = 'bg-emerald-50 text-emerald-700 border border-emerald-200';
        } elseif ($request->status === 'Menunggu') {
            $badge = 'bg-amber-50 text-amber-700 border border-amber-200 animate-pulse';
        }

        $jadwal[] = [
            'waktu' => $request->waktu,
            'program' => $request->program,
            'media' => $request->media,
            'durasi' => (int) $request->durasi,
            'status' => $request->status,
            'badge' => $badge,
        ];

        $ringkasan->update(['jadwal_tayang_json' => $jadwal]);

        return redirect()->route('tvri.promo.manage', ['tab' => 'jadwal-agenda'])->with('success', 'Jadwal Penayangan Promo berhasil ditambahkan!');
    }

    /**
     * Hapus jadwal tayang berdasarkan index
     */
    public function destroyJadwal($index)
    {
        $ringkasan = $this->getRingkasan();
        $jadwal = $ringkasan->jadwal_tayang_json ?: [];

        if (isset($jadwal[$index])) {
            array_splice($jadwal, $index, 1);
            $ringkasan->update(['jadwal_tayang_json' => $jadwal]);
            return redirect()->route('tvri.promo.manage', ['tab' => 'jadwal-agenda'])->with('success', 'Jadwal Penayangan Promo berhasil dihapus!');
        }

        return redirect()->route('tvri.promo.manage', ['tab' => 'jadwal-agenda'])->with('error', 'Jadwal Penayangan tidak ditemukan.');
    }

    /**
     * Simpan agenda promo baru
     */
    public function storeAgenda(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|string',
            'kegiatan' => 'required|string',
            'pic' => 'required|string',
            'status' => 'required|string',
        ]);

        $ringkasan = $this->getRingkasan();
        $agenda = $ringkasan->agenda_promo_json ?: [];

        $badge = 'bg-slate-100 text-slate-700 border-slate-200';
        if ($request->status === 'Persiapan') {
            $badge = 'bg-amber-50 text-amber-700 border-amber-200';
        } elseif ($request->status === 'On Progress') {
            $badge = 'bg-blue-50 text-blue-700 border-blue-200';
        }

        $agenda[] = [
            'tanggal' => $request->tanggal,
            'kegiatan' => $request->kegiatan,
            'pic' => $request->pic,
            'status' => $request->status,
            'badge' => $badge,
        ];

        $ringkasan->update(['agenda_promo_json' => $agenda]);

        return redirect()->route('tvri.promo.manage', ['tab' => 'jadwal-agenda'])->with('success', 'Agenda Promo berhasil ditambahkan!');
    }

    /**
     * Hapus agenda promo berdasarkan index
     */
    public function destroyAgenda($index)
    {
        $ringkasan = $this->getRingkasan();
        $agenda = $ringkasan->agenda_promo_json ?: [];

        if (isset($agenda[$index])) {
            array_splice($agenda, $index, 1);
            $ringkasan->update(['agenda_promo_json' => $agenda]);
            return redirect()->route('tvri.promo.manage', ['tab' => 'jadwal-agenda'])->with('success', 'Agenda Promo berhasil dihapus!');
        }

        return redirect()->route('tvri.promo.manage', ['tab' => 'jadwal-agenda'])->with('error', 'Agenda Promo tidak ditemukan.');
    }

    /**
     * Simpan materi promo baru
     */
    public function storeMateri(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string',
            'durasi_detik' => 'required|integer|min:5|max:300',
            'status' => 'required|string',
        ]);

        DB::table('tvri_materi_promos')->insert([
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'durasi_detik' => $request->durasi_detik,
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Update materi_diproduksi count
        $count = DB::table('tvri_materi_promos')->count();
        $ringkasan = $this->getRingkasan();
        $ringkasan->update(['materi_diproduksi' => $count]);

        return redirect()->route('tvri.promo.manage', ['tab' => 'materi-kampanye'])->with('success', 'Materi Promo berhasil ditambahkan!');
    }

    /**
     * Update materi promo
     */
    public function updateMateri(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string',
            'durasi_detik' => 'required|integer|min:5|max:300',
            'status' => 'required|string',
        ]);

        DB::table('tvri_materi_promos')->where('id', $id)->update([
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'durasi_detik' => $request->durasi_detik,
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        return redirect()->route('tvri.promo.manage', ['tab' => 'materi-kampanye'])->with('success', 'Materi Promo berhasil diperbarui!');
    }

    /**
     * Hapus materi promo
     */
    public function destroyMateri($id)
    {
        DB::table('tvri_materi_promos')->where('id', $id)->delete();

        $count = DB::table('tvri_materi_promos')->count();
        $ringkasan = $this->getRingkasan();
        $ringkasan->update(['materi_diproduksi' => $count]);

        return redirect()->route('tvri.promo.manage', ['tab' => 'materi-kampanye'])->with('success', 'Materi Promo berhasil dihapus!');
    }

    public function bulkDestroyMateri(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            $count = DB::table('tvri_materi_promos')->whereIn('id', $ids)->delete();
            $totalCount = DB::table('tvri_materi_promos')->count();
            $ringkasan = $this->getRingkasan();
            $ringkasan->update(['materi_diproduksi' => $totalCount]);

            return redirect()->route('tvri.promo.manage', ['tab' => 'materi-kampanye'])->with('success', "{$count} Materi Promo terpilih berhasil dihapus!");
        }
        return redirect()->route('tvri.promo.manage', ['tab' => 'materi-kampanye'])->with('error', 'Tidak ada materi promo yang dipilih.');
    }

    /**
     * Simpan kampanye promo baru
     */
    public function storeKampanye(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'media' => 'required|string',
            'target' => 'required|integer',
            'realisasi' => 'required|integer',
            'reach' => 'required|integer',
            'engagement' => 'required|integer',
            'status' => 'required|string',
        ]);

        DB::table('tvri_kampanyes')->insert([
            'nama' => $request->nama,
            'media' => $request->media,
            'target' => $request->target,
            'realisasi' => $request->realisasi,
            'reach' => $request->reach,
            'engagement' => $request->engagement,
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $count = DB::table('tvri_kampanyes')->count();
        $ringkasan = $this->getRingkasan();
        $ringkasan->update(['total_kampanye' => $count]);

        return redirect()->route('tvri.promo.manage', ['tab' => 'materi-kampanye'])->with('success', 'Kampanye Promo berhasil ditambahkan!');
    }

    /**
     * Update kampanye promo
     */
    public function updateKampanye(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'media' => 'required|string',
            'target' => 'required|integer',
            'realisasi' => 'required|integer',
            'reach' => 'required|integer',
            'engagement' => 'required|integer',
            'status' => 'required|string',
        ]);

        DB::table('tvri_kampanyes')->where('id', $id)->update([
            'nama' => $request->nama,
            'media' => $request->media,
            'target' => $request->target,
            'realisasi' => $request->realisasi,
            'reach' => $request->reach,
            'engagement' => $request->engagement,
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        return redirect()->route('tvri.promo.manage', ['tab' => 'materi-kampanye'])->with('success', 'Kampanye Promo berhasil diperbarui!');
    }

    /**
     * Hapus kampanye promo
     */
    public function destroyKampanye($id)
    {
        DB::table('tvri_kampanyes')->where('id', $id)->delete();

        $count = DB::table('tvri_kampanyes')->count();
        $ringkasan = $this->getRingkasan();
        $ringkasan->update(['total_kampanye' => $count]);

        return redirect()->route('tvri.promo.manage', ['tab' => 'materi-kampanye'])->with('success', 'Kampanye Promo berhasil dihapus!');
    }

    public function bulkDestroyKampanye(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            $count = DB::table('tvri_kampanyes')->whereIn('id', $ids)->delete();
            $totalCount = DB::table('tvri_kampanyes')->count();
            $ringkasan = $this->getRingkasan();
            $ringkasan->update(['total_kampanye' => $totalCount]);

            return redirect()->route('tvri.promo.manage', ['tab' => 'materi-kampanye'])->with('success', "{$count} Kampanye Promo terpilih berhasil dihapus!");
        }
        return redirect()->route('tvri.promo.manage', ['tab' => 'materi-kampanye'])->with('error', 'Tidak ada kampanye promo yang dipilih.');
    }

    // ================= CRUD SOP & REGULASI PROMO =================
    public function storeSop(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:50',
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'versi' => 'nullable|string|max:20',
            'status' => 'nullable|string|max:50',
            'tanggal_efektif' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'urutan' => 'nullable|integer',
        ]);

        $data = $request->except(["_token", "file"]); if ($request->hasFile("file")) { $clean = time()."_".preg_replace("/[^a-zA-Z0-9_\.-]/","_", $request->file("file")->getClientOriginalName()); $path = $request->file("file")->storeAs("sops", $clean, "public"); $data["file_url"] = "storage/".$path; $data["file_path"] = "storage/".$path; } TvriPromoSop::create($data);

        return redirect()->route('tvri.promo.manage', ['tab' => 'sop'])->with('success', 'Dokumen SOP Promo baru berhasil ditambahkan.');
    }

    public function updateSop(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:50',
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'versi' => 'nullable|string|max:20',
            'status' => 'nullable|string|max:50',
            'tanggal_efektif' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'urutan' => 'nullable|integer',
        ]);

        $sop = TvriPromoSop::findOrFail($id);
        $data = $request->except(["_token", "_method", "file"]); if ($request->hasFile("file")) { $clean = time()."_".preg_replace("/[^a-zA-Z0-9_\.-]/","_", $request->file("file")->getClientOriginalName()); $path = $request->file("file")->storeAs("sops", $clean, "public"); $data["file_url"] = "storage/".$path; $data["file_path"] = "storage/".$path; } $sop->update($data);

        return redirect()->route('tvri.promo.manage', ['tab' => 'sop'])->with('success', 'Dokumen SOP Promo berhasil diperbarui.');
    }

    public function destroySop($id)
    {
        TvriPromoSop::findOrFail($id)->delete();
        return redirect()->route('tvri.promo.manage', ['tab' => 'sop'])->with('success', 'Dokumen SOP Promo berhasil dihapus.');
    }

    public function bulkDestroySop(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            TvriPromoSop::whereIn('id', $ids)->delete();
            return redirect()->route('tvri.promo.manage', ['tab' => 'sop'])->with('success', count($ids) . ' dokumen SOP Promo berhasil dihapus.');
        }
        return redirect()->route('tvri.promo.manage', ['tab' => 'sop'])->with('error', 'Tidak ada dokumen SOP Promo yang dipilih.');
    }
}
