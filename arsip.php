<?php
$controllerFile = 'app/Http/Controllers/AdminTUController.php';
$content = file_get_contents($controllerFile);

$arsipMethod = <<<PHP
    public function arsip(Request \)
    {
        \ = \App\Models\SimandatDokumenLayanan::query();

        if (auth()->check()) {
            \ = auth()->user();
            \ = \->username === 'admin.tu';
            \ = \->hasRole('KEPALA_DINAS') || str_contains(strtolower(\->name), 'kepala dinas');
            \ = \->username === 'kabid.sma';
            
            // Allow TU and Kadis to see all.
            if (!\ && !\) {
                if (\) {
                    \->where(function(\) use (\) {
                        \->where('user_id', \->id)
                          ->orWhere('nama_dokumen', 'like', '%SMA%')
                          ->orWhere('keterangan', 'like', '%SMA%');
                    });
                } else {
                    \->where('user_id', \->id);
                }
            }
        }

        if (\->filled('search')) {
            \ = \->search;
            \->where(function (\) use (\) {
                \->where('nama_dokumen', 'like', "%{\}%")
                  ->orWhere('keterangan', 'like', "%{\}%")
                  ->orWhere('petugas_nama', 'like', "%{\}%");
            });
        }

        if (\->filled('kategori') && \->kategori !== 'all') {
            \->where('kategori', \->kategori);
        }

        \ = \->latest()->paginate(15);
        \ = \App\Models\SimandatDokumenLayanan::KATEGORI_LIST;
        
        // Stats
        \ = \->count();
        \ = (clone \)->where('kategori', 'Kepegawaian')->count();
        \ = (clone \)->whereIn('kategori', ['Surat Masuk', 'Surat Keluar'])->count();

        return view('admin-tu.arsip', compact(
            'documents', 'categories', 'totalDokumen', 'totalKepegawaian', 'totalSurat'
        ));
    }
PHP;

$content = preg_replace("/public function arsip\(\)\s*\{\s*return view\('admin-tu\.arsip'\);\s*\}/s", $arsipMethod, $content);
file_put_contents($controllerFile, $content);
echo "AdminTUController modified!";
