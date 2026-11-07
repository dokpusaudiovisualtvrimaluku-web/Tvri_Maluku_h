const fs = require('fs');
let file = 'app/Http/Controllers/AdminTUController.php';
let content = fs.readFileSync(file, 'utf8');

const newMethod = `    public function arsip(Request $request)
    {
        $query = \\App\\Models\\SimandatDokumenLayanan::query();

        if (auth()->check()) {
            $user = auth()->user();
            $isTU = $user->username === 'admin.tu';
            $isKadis = $user->hasRole('KEPALA_DINAS') || str_contains(strtolower($user->name), 'kepala dinas');
            $isKabidSMA = $user->username === 'kabid.sma';
            
            // Allow TU and Kadis to see all.
            if (!$isTU && !$isKadis) {
                if ($isKabidSMA) {
                    $query->where(function($q) use ($user) {
                        $q->where('user_id', $user->id)
                          ->orWhere('nama_dokumen', 'like', '%SMA%')
                          ->orWhere('keterangan', 'like', '%SMA%');
                    });
                } else {
                    $query->where('user_id', $user->id);
                }
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_dokumen', 'like', "%" . $search . "%")
                  ->orWhere('keterangan', 'like', "%" . $search . "%")
                  ->orWhere('petugas_nama', 'like', "%" . $search . "%");
            });
        }

        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori', $request->kategori);
        }

        $documents = $query->latest()->paginate(15);
        $categories = \\App\\Models\\SimandatDokumenLayanan::KATEGORI_LIST;
        
        // Stats
        $totalDokumen = $query->count();
        $totalKepegawaian = (clone $query)->where('kategori', 'Kepegawaian')->count();
        $totalSurat = (clone $query)->whereIn('kategori', ['Surat Masuk', 'Surat Keluar'])->count();

        return view('admin-tu.arsip', compact(
            'documents', 'categories', 'totalDokumen', 'totalKepegawaian', 'totalSurat'
        ));
    }
}`;

content = content.replace(/public function arsip\(Request \\\).*$/sm, newMethod);

fs.writeFileSync(file, content);
console.log("Fixed AdminTUController!");
