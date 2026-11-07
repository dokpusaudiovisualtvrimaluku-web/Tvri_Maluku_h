<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Executive\DashboardController as ExecutiveDashboardController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SimasController;

// Portal & Public Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/simas', [SimasController::class, 'index'])->name('simas');
Route::get('/pangkalan-data', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->hasRole('KEPALA_DINAS') || str_contains(strtolower($user->name), 'kepala stasiun')) {
            return redirect()->route('tvri.ecc.dashboard');
        }
        if ($user->username === 'kabid.kebudayaan') {
            return redirect()->route('kebudayaan.dashboard');
        }
        if ($user->username === 'promo' || $user->email === 'promo@tvri.go.id' || $user->hasRole('Promo') || str_contains(strtolower($user->name ?? ''), 'promo')) {
            return redirect()->route('tvri.promo.dashboard');
        }
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
})->name('pangkalan-data');

Route::get('/simandat', function () {
    return view('dashboard');
})->name('simandat');

// Public Document Verification Portal (Scanned QR Codes)
Route::get('dokumen/verify/{token}', [App\Http\Controllers\Dokumen\DocumentVerificationController::class, 'verify'])->name('dokumen.verify');

// Public Real-Time Visitor Stats API
Route::get('api/statistik-pengunjung', [App\Http\Controllers\Dokumen\DashboardDokumenController::class, 'getPublicVisitorStats'])->name('api.visitor-stats');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::any('logout', [LoginController::class, 'logout'])->name('logout');

    // Profile & Password Management (All authenticated users)
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('profile/password', [ProfileController::class, 'editPassword'])->name('profile.password');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Shared Admin/Kabid Dashboard
    Route::get('admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // ADMIN Restricted Routes
    Route::middleware('role:ADMIN')->prefix('admin')->name('admin.')->group(function () {
        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('users', [AdminUserController::class, 'store'])->name('users.store');
        Route::put('users/{id}', [AdminUserController::class, 'update'])->name('users.update');
        Route::patch('users/{id}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
        
        // Activity Logs & Audit Trail
        Route::get('activity-logs', [AdminActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::delete('activity-logs/clear', [AdminActivityLogController::class, 'destroyAll'])->name('activity-logs.destroyAll');
        Route::delete('activity-logs/{id}', [AdminActivityLogController::class, 'destroy'])->name('activity-logs.destroy');
    });

    // KEPALA DINAS Restricted Routes
    Route::middleware('role:KEPALA_DINAS')->prefix('kepala-dinas')->name('kepala-dinas.')->group(function () {
        Route::get('dashboard', [ExecutiveDashboardController::class, 'index'])->name('dashboard');
    });

    // SEKRETARIAT DINAS (8 FUNGSI KOORDINASI, PEMBINAAN & PENGENDALIAN)
    Route::prefix('sekretariat')->name('sekretariat.')->group(function () {
        Route::get('data/export-csv', [App\Http\Controllers\Sekretariat\SekretariatController::class, 'exportCsv'])->name('data.export-csv');
        Route::get('data/{id}/download', [App\Http\Controllers\Sekretariat\SekretariatController::class, 'downloadFile'])->name('data.download');
        Route::resource('data', App\Http\Controllers\Sekretariat\SekretariatController::class);
        Route::get('/', [App\Http\Controllers\Sekretariat\SekretariatController::class, 'index'])->name('index');
    });

    // PANGKALAN DATA DINAS PUPR MALUKU PENDIDIKAN (TAHAP 3)
    Route::prefix('pendidikan')->name('pendidikan.')->group(function () {
        Route::get('dashboard', [App\Http\Controllers\Pendidikan\DashboardPendidikanController::class, 'index'])->name('dashboard');
        
        // BIDANG PEMBINAAN SMA (11 LAYANAN & DOKUMEN RESMI)
        Route::prefix('sma')->name('sma.')->group(function () {
            Route::get('layanan/export-csv', [App\Http\Controllers\Pendidikan\SmaLayananController::class, 'exportCsv'])->name('layanan.export-csv');
            Route::get('layanan/{id}/download/{type}', [App\Http\Controllers\Pendidikan\SmaLayananController::class, 'downloadFile'])->name('layanan.download');
            Route::resource('layanan', App\Http\Controllers\Pendidikan\SmaLayananController::class);
            Route::get('/', [App\Http\Controllers\Pendidikan\SmaLayananController::class, 'index'])->name('index');
        });

        // BIDANG PEMBINAAN SMK (LAYANAN & DOKUMEN VOKASI)
        Route::prefix('smk')->name('smk.')->group(function () {
            Route::get('layanan/export-csv', [App\Http\Controllers\Pendidikan\SmkLayananController::class, 'exportCsv'])->name('layanan.export-csv');
            Route::get('layanan/{id}/download/{type}', [App\Http\Controllers\Pendidikan\SmkLayananController::class, 'downloadFile'])->name('layanan.download');
            Route::resource('layanan', App\Http\Controllers\Pendidikan\SmkLayananController::class);
            Route::get('/', [App\Http\Controllers\Pendidikan\SmkLayananController::class, 'index'])->name('index');
        });

        // KATALOG & STANDAR LAYANAN GTK (SERTIFIKASI, NUPTK, TUNJANGAN, SIMPKB)
        Route::prefix('gtk')->name('gtk.')->group(function () {
            Route::get('layanan/export-csv', [App\Http\Controllers\Pendidikan\GtkLayananController::class, 'exportCsv'])->name('layanan.export-csv');
            Route::get('layanan/{id}/download/{type}', [App\Http\Controllers\Pendidikan\GtkLayananController::class, 'downloadFile'])->name('layanan.download');
            Route::resource('layanan', App\Http\Controllers\Pendidikan\GtkLayananController::class);
            Route::get('/', [App\Http\Controllers\Pendidikan\GtkLayananController::class, 'index'])->name('index');
        });

        // DIREKTORI PRODUK LAYANAN BIDANG PEMBINAAN PENDIDIKAN KHUSUS (SLB)
        Route::prefix('slb')->name('slb.')->group(function () {
            Route::get('layanan/export-csv', [App\Http\Controllers\Pendidikan\SlbLayananController::class, 'exportCsv'])->name('layanan.export-csv');
            Route::get('layanan/{id}/download/{type}', [App\Http\Controllers\Pendidikan\SlbLayananController::class, 'downloadFile'])->name('layanan.download');
            Route::get('verifikasi', [App\Http\Controllers\Pendidikan\SlbLayananController::class, 'verifikasi'])->name('verifikasi');
            Route::get('layanan/{id}/proses', [App\Http\Controllers\Pendidikan\SlbLayananController::class, 'proses'])->name('layanan.proses');
            Route::put('layanan/{id}/proses', [App\Http\Controllers\Pendidikan\SlbLayananController::class, 'updateVerifikasi'])->name('layanan.update-verifikasi');
            Route::resource('layanan', App\Http\Controllers\Pendidikan\SlbLayananController::class);
            Route::get('/', [App\Http\Controllers\Pendidikan\SlbLayananController::class, 'index'])->name('index');
        });

        Route::patch('schools/{id}/validate', [App\Http\Controllers\Pendidikan\SchoolController::class, 'updateValidationStatus'])->name('schools.validate');
        Route::resource('schools', App\Http\Controllers\Pendidikan\SchoolController::class);
        Route::resource('students', App\Http\Controllers\Pendidikan\StudentController::class);
        Route::resource('teachers', App\Http\Controllers\Pendidikan\TeacherController::class);
        Route::resource('sarpras', App\Http\Controllers\Pendidikan\SarprasController::class);
        Route::get('import', [App\Http\Controllers\Pendidikan\ImportController::class, 'index'])->name('import.index');
        Route::post('import/preview', [App\Http\Controllers\Pendidikan\ImportController::class, 'preview'])->name('import.preview');
        Route::post('import/execute', [App\Http\Controllers\Pendidikan\ImportController::class, 'execute'])->name('import.execute');
    });

    // PANGKALAN DATA DINAS PUPR MALUKU KEBUDAYAAN (TAHAP 4)
    Route::prefix('kebudayaan')->name('kebudayaan.')->group(function () {
        Route::get('dashboard', [App\Http\Controllers\Kebudayaan\DashboardKebudayaanController::class, 'index'])->name('dashboard');
        Route::get('map', [App\Http\Controllers\Kebudayaan\CultureMapController::class, 'index'])->name('map.index');
        Route::get('gallery', [App\Http\Controllers\Kebudayaan\CultureGalleryController::class, 'index'])->name('gallery.index');
        Route::patch('cultures/{id}/validate', [App\Http\Controllers\Kebudayaan\CultureController::class, 'updateValidationStatus'])->name('cultures.validate');
        Route::resource('cultures', App\Http\Controllers\Kebudayaan\CultureController::class);
    });

    // DOKUMEN & ARSIP DIGITAL (TAHAP 5)
    Route::prefix('dokumen')->name('dokumen.')->group(function () {
        Route::get('dashboard', [App\Http\Controllers\Dokumen\DashboardDokumenController::class, 'index'])->name('dashboard');
        Route::post('visitor-stats/update', [App\Http\Controllers\Dokumen\DashboardDokumenController::class, 'updateVisitorStats'])->name('visitor-stats.update');
        Route::post('visitor-stats/reset', [App\Http\Controllers\Dokumen\DashboardDokumenController::class, 'resetVisitorStats'])->name('visitor-stats.reset');
        Route::post('visitor-stats/patokan', [App\Http\Controllers\Dokumen\DashboardDokumenController::class, 'patokanVisitorStats'])->name('visitor-stats.patokan');
        
        // Master Katalog & Dataset Operasional TVRI Maluku
        Route::post('datasets/bulk-update', [App\Http\Controllers\Dokumen\DashboardDokumenController::class, 'bulkUpdateDatasets'])->name('datasets.bulk-update');
        Route::post('datasets/reseed', [App\Http\Controllers\Dokumen\DashboardDokumenController::class, 'reseedDatasets'])->name('datasets.reseed');
        Route::post('datasets', [App\Http\Controllers\Dokumen\DashboardDokumenController::class, 'storeDataset'])->name('datasets.store');
        Route::match(['put', 'patch', 'post'], 'datasets/{id}', [App\Http\Controllers\Dokumen\DashboardDokumenController::class, 'updateDataset'])->name('datasets.update');
        Route::delete('datasets/{id}', [App\Http\Controllers\Dokumen\DashboardDokumenController::class, 'destroyDataset'])->name('datasets.destroy');
        Route::get('datasets/{id}/export-csv', [App\Http\Controllers\Dokumen\DashboardDokumenController::class, 'exportDatasetCsv'])->name('datasets.export-csv');

        Route::get('katalog/{id}/export-csv', [App\Http\Controllers\Dokumen\DashboardDokumenController::class, 'exportDatasetCsv'])->name('katalog.export-csv');
        Route::post('katalog/store', [App\Http\Controllers\Dokumen\DashboardDokumenController::class, 'storeDataset'])->name('katalog.store');
        Route::post('katalog/{id}/update', [App\Http\Controllers\Dokumen\DashboardDokumenController::class, 'updateDataset'])->name('katalog.update');
        Route::delete('katalog/{id}', [App\Http\Controllers\Dokumen\DashboardDokumenController::class, 'destroyDataset'])->name('katalog.destroy');

        // Folder Explorer & Management
        Route::resource('folders', App\Http\Controllers\Dokumen\FolderController::class)->except(['create', 'show', 'edit']);
        
        // Document Operations
        Route::match(['post', 'delete'], 'documents/bulk-destroy', [App\Http\Controllers\Dokumen\DocumentController::class, 'bulkDestroy'])->name('documents.bulk-destroy');
        Route::get('documents/{id}/preview', [App\Http\Controllers\Dokumen\DocumentController::class, 'previewFile'])->name('documents.preview');
        Route::get('documents/{id}/download', [App\Http\Controllers\Dokumen\DocumentController::class, 'download'])->name('documents.download');
        Route::post('documents/{id}/version', [App\Http\Controllers\Dokumen\DocumentController::class, 'storeVersion'])->name('documents.version.store');
        Route::get('versions/{id}/download', [App\Http\Controllers\Dokumen\DocumentController::class, 'downloadVersion'])->name('documents.version.download');
        Route::resource('documents', App\Http\Controllers\Dokumen\DocumentController::class);
    });

    // SUB BAGIAN KEPEGAWAIAN DAN UMUM (10 JENIS LAYANAN & NASKAH DINAS)
    Route::prefix('kepegawaian')->name('kepegawaian.')->group(function () {
        Route::get('layanan/{id}/download/{type}', [App\Http\Controllers\Kepegawaian\KepegawaianLayananController::class, 'downloadFile'])->name('layanan.download');
        Route::resource('layanan', App\Http\Controllers\Kepegawaian\KepegawaianLayananController::class);
    });

    // PROGRAM & PERENCANAAN (PROGRAM, KEGIATAN, RENSTRA, RENJA, RKA, DPA)
    Route::prefix('perencanaan')->name('perencanaan.')->group(function () {
        Route::get('program/export-csv', [App\Http\Controllers\Perencanaan\PerencanaanProgramController::class, 'exportCsv'])->name('program.export-csv');
        Route::get('program/{id}/download', [App\Http\Controllers\Perencanaan\PerencanaanProgramController::class, 'downloadFile'])->name('program.download');
        Route::resource('program', App\Http\Controllers\Perencanaan\PerencanaanProgramController::class);
    });

    // ADMIN TU
    Route::prefix('admin-tu')->name('admin-tu.')->middleware('auth')->group(function () {
        Route::get('permohonan', [App\Http\Controllers\AdminTUController::class, 'permohonan'])->name('permohonan');
        Route::get('layanan', [App\Http\Controllers\AdminTUController::class, 'layanan'])->name('layanan');
        Route::get('jenis-layanan', [App\Http\Controllers\AdminTUController::class, 'jenisLayanan'])->name('jenis-layanan');
        Route::post('jenis-layanan/sma', [App\Http\Controllers\AdminTUController::class, 'storeJenisLayananSma'])->name('jenis-layanan.sma.store');
        
        // DOKUMEN LAYANAN
        Route::get('dokumen-layanan', [App\Http\Controllers\SimandatDokumenLayananController::class, 'index'])->name('dokumen-layanan.index');
        Route::get('dokumen-layanan/create', [App\Http\Controllers\SimandatDokumenLayananController::class, 'create'])->name('dokumen-layanan.create');
        Route::post('dokumen-layanan', [App\Http\Controllers\SimandatDokumenLayananController::class, 'store'])->name('dokumen-layanan.store');
        Route::get('dokumen-layanan/{id}/download', [App\Http\Controllers\SimandatDokumenLayananController::class, 'download'])->name('dokumen-layanan.download');
        Route::delete('dokumen-layanan/{id}', [App\Http\Controllers\SimandatDokumenLayananController::class, 'destroy'])->name('dokumen-layanan.destroy');

        Route::get('laporan', [App\Http\Controllers\SimandatLaporanController::class, 'index'])->name('laporan');
        Route::get('laporan/create', [App\Http\Controllers\SimandatLaporanController::class, 'create'])->name('laporan.create');
        Route::post('laporan', [App\Http\Controllers\SimandatLaporanController::class, 'store'])->name('laporan.store');
        Route::get('laporan/show/{id}', [App\Http\Controllers\SimandatLaporanController::class, 'show'])->name('laporan.show');
        Route::get('laporan/{id}/edit', [App\Http\Controllers\SimandatLaporanController::class, 'edit'])->name('laporan.edit');
        Route::put('laporan/{id}', [App\Http\Controllers\SimandatLaporanController::class, 'update'])->name('laporan.update');
        Route::delete('laporan/{id}', [App\Http\Controllers\SimandatLaporanController::class, 'destroy'])->name('laporan.destroy');
        Route::get('arsip', [App\Http\Controllers\AdminTUController::class, 'arsip'])->name('arsip');
    });

    // EKSEKUTIF & LAPORAN
    Route::get('eksekutif/dashboard', [ExecutiveDashboardController::class, 'index'])->name('eksekutif.dashboard');

    // TVRI MALUKU — EXECUTIVE COMMAND CENTER (Dashboard Kepala Stasiun)
    Route::get('tvri/ecc', [App\Http\Controllers\Tvri\EccDashboardController::class, 'index'])->name('tvri.ecc.dashboard');

    // TVRI MALUKU — DASHBOARD BIDANG TATA USAHA (CRUD penuh & Input Data Terpadu)
    Route::prefix('tvri/tu')->name('tvri.tu.')->group(function () {
        Route::get('/', [App\Http\Controllers\Tvri\TataUsahaController::class, 'index'])->name('dashboard');
        Route::get('manage', [App\Http\Controllers\Tvri\TataUsahaController::class, 'manage'])->name('manage');
        Route::post('ringkasan', [App\Http\Controllers\Tvri\TataUsahaController::class, 'updateRingkasan'])->name('ringkasan.update');
        Route::post('reseed', [App\Http\Controllers\Tvri\TataUsahaController::class, 'reseedDefaults'])->name('reseed');

        // CRUD Surat Masuk
        Route::post('surat-masuk', [App\Http\Controllers\Tvri\TataUsahaController::class, 'storeSuratMasuk'])->name('surat-masuk.store');
        Route::put('surat-masuk/{id}', [App\Http\Controllers\Tvri\TataUsahaController::class, 'updateSuratMasuk'])->name('surat-masuk.update');
        Route::delete('surat-masuk/bulk-destroy', [App\Http\Controllers\Tvri\TataUsahaController::class, 'bulkDestroySuratMasuk'])->name('surat-masuk.bulk-destroy');
        Route::delete('surat-masuk/{id}', [App\Http\Controllers\Tvri\TataUsahaController::class, 'destroySuratMasuk'])->name('surat-masuk.destroy');

        // CRUD Surat Keluar
        Route::post('surat-keluar', [App\Http\Controllers\Tvri\TataUsahaController::class, 'storeSuratKeluar'])->name('surat-keluar.store');
        Route::put('surat-keluar/{id}', [App\Http\Controllers\Tvri\TataUsahaController::class, 'updateSuratKeluar'])->name('surat-keluar.update');
        Route::delete('surat-keluar/bulk-destroy', [App\Http\Controllers\Tvri\TataUsahaController::class, 'bulkDestroySuratKeluar'])->name('surat-keluar.bulk-destroy');
        Route::delete('surat-keluar/{id}', [App\Http\Controllers\Tvri\TataUsahaController::class, 'destroySuratKeluar'])->name('surat-keluar.destroy');

        // CRUD Agenda Kegiatan
        Route::post('agenda', [App\Http\Controllers\Tvri\TataUsahaController::class, 'storeAgenda'])->name('agenda.store');
        Route::put('agenda/{id}', [App\Http\Controllers\Tvri\TataUsahaController::class, 'updateAgenda'])->name('agenda.update');
        Route::delete('agenda/bulk-destroy', [App\Http\Controllers\Tvri\TataUsahaController::class, 'bulkDestroyAgenda'])->name('agenda.bulk-destroy');
        Route::delete('agenda/{id}', [App\Http\Controllers\Tvri\TataUsahaController::class, 'destroyAgenda'])->name('agenda.destroy');

        // CRUD Layanan Administrasi
        Route::post('layanan', [App\Http\Controllers\Tvri\TataUsahaController::class, 'storeLayanan'])->name('layanan.store');
        Route::put('layanan/{id}', [App\Http\Controllers\Tvri\TataUsahaController::class, 'updateLayanan'])->name('layanan.update');
        Route::delete('layanan/bulk-destroy', [App\Http\Controllers\Tvri\TataUsahaController::class, 'bulkDestroyLayanan'])->name('layanan.bulk-destroy');
        Route::delete('layanan/{id}', [App\Http\Controllers\Tvri\TataUsahaController::class, 'destroyLayanan'])->name('layanan.destroy');

        // CRUD Fitur SOP & Regulasi
        Route::post('sop', [App\Http\Controllers\Tvri\TataUsahaController::class, 'storeSop'])->name('sop.store');
        Route::put('sop/{id}', [App\Http\Controllers\Tvri\TataUsahaController::class, 'updateSop'])->name('sop.update');
        Route::delete('sop/bulk-destroy', [App\Http\Controllers\Tvri\TataUsahaController::class, 'bulkDestroySop'])->name('sop.bulk-destroy');
        Route::delete('sop/{id}', [App\Http\Controllers\Tvri\TataUsahaController::class, 'destroySop'])->name('sop.destroy');

        // Legacy Generic List & Form (Tetap dipertahankan untuk backward compatibility)
        Route::get('data/{entitas}', [App\Http\Controllers\Tvri\TataUsahaController::class, 'list'])->name('list');
        Route::get('data/{entitas}/tambah', [App\Http\Controllers\Tvri\TataUsahaController::class, 'create'])->name('create');
        Route::post('data/{entitas}', [App\Http\Controllers\Tvri\TataUsahaController::class, 'store'])->name('store');
        Route::get('data/{entitas}/{id}/ubah', [App\Http\Controllers\Tvri\TataUsahaController::class, 'edit'])->name('edit');
        Route::put('data/{entitas}/{id}', [App\Http\Controllers\Tvri\TataUsahaController::class, 'update'])->name('update');
        Route::delete('data/{entitas}/{id}', [App\Http\Controllers\Tvri\TataUsahaController::class, 'destroy'])->name('destroy');
    });

    // TVRI MALUKU — DASHBOARD BIDANG PEMBERITAAN (NEWSROOM & CRUD PENUH)
    Route::prefix('tvri/berita')->name('tvri.berita.')->group(function () {
        Route::get('/', [App\Http\Controllers\Tvri\BeritaController::class, 'index'])->name('dashboard');
        Route::get('manage', [App\Http\Controllers\Tvri\BeritaController::class, 'manage'])->name('manage');
        Route::get('jadwal-portal', [App\Http\Controllers\Tvri\BeritaController::class, 'jadwalPortal'])->name('jadwal-portal');
        Route::post('ringkasan', [App\Http\Controllers\Tvri\BeritaController::class, 'updateRingkasan'])->name('ringkasan.update');

        // CRUD Berita & Upload Video
        Route::post('/', [App\Http\Controllers\Tvri\BeritaController::class, 'store'])->name('store');
        Route::post('bulk-destroy', [App\Http\Controllers\Tvri\BeritaController::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::delete('{id}/delete-video', [App\Http\Controllers\Tvri\BeritaController::class, 'deleteVideo'])->name('delete-video');
        Route::put('{berita}', [App\Http\Controllers\Tvri\BeritaController::class, 'update'])->name('update');
        Route::delete('{berita}', [App\Http\Controllers\Tvri\BeritaController::class, 'destroy'])->name('destroy');

        // CRUD Real Time Feeds
        Route::match(['post', 'delete'], 'realtime/bulk-destroy', [App\Http\Controllers\Tvri\BeritaController::class, 'bulkDestroyRealtime'])->name('realtime.bulk-destroy');
        Route::post('realtime', [App\Http\Controllers\Tvri\BeritaController::class, 'storeRealtime'])->name('realtime.store');
        Route::put('realtime/{id}', [App\Http\Controllers\Tvri\BeritaController::class, 'updateRealtime'])->name('realtime.update');
        Route::delete('realtime/{id}', [App\Http\Controllers\Tvri\BeritaController::class, 'destroyRealtime'])->name('realtime.destroy');

        // CRUD Agenda Liputan
        Route::match(['post', 'delete'], 'agenda/bulk-destroy', [App\Http\Controllers\Tvri\BeritaController::class, 'bulkDestroyAgenda'])->name('agenda.bulk-destroy');
        Route::post('agenda', [App\Http\Controllers\Tvri\BeritaController::class, 'storeAgenda'])->name('agenda.store');
        Route::put('agenda/{id}', [App\Http\Controllers\Tvri\BeritaController::class, 'updateAgenda'])->name('agenda.update');
        Route::delete('agenda/{id}', [App\Http\Controllers\Tvri\BeritaController::class, 'destroyAgenda'])->name('agenda.destroy');

        // CRUD Catatan & Peringatan
        Route::match(['post', 'delete'], 'catatan/bulk-destroy', [App\Http\Controllers\Tvri\BeritaController::class, 'bulkDestroyCatatan'])->name('catatan.bulk-destroy');
        Route::post('catatan', [App\Http\Controllers\Tvri\BeritaController::class, 'storeCatatan'])->name('catatan.store');
        Route::put('catatan/{id}', [App\Http\Controllers\Tvri\BeritaController::class, 'updateCatatan'])->name('catatan.update');
        Route::delete('catatan/{id}', [App\Http\Controllers\Tvri\BeritaController::class, 'destroyCatatan'])->name('catatan.destroy');

        // CRUD SOP Berita
        Route::post('sop', [App\Http\Controllers\Tvri\BeritaController::class, 'storeSop'])->name('sop.store');
        Route::post('sop/{id}', [App\Http\Controllers\Tvri\BeritaController::class, 'updateSop'])->name('sop.update');
        Route::delete('sop/{id}', [App\Http\Controllers\Tvri\BeritaController::class, 'destroySop'])->name('sop.destroy');
        Route::post('sop/bulk-delete', [App\Http\Controllers\Tvri\BeritaController::class, 'bulkDestroySop'])->name('sop.bulk-destroy');

        // CRUD Produksi Paket
        Route::match(['post', 'delete'], 'produksi/bulk-destroy', [App\Http\Controllers\Tvri\BeritaController::class, 'bulkDestroyProduksi'])->name('produksi.bulk-destroy');
        Route::post('produksi', [App\Http\Controllers\Tvri\BeritaController::class, 'storeProduksi'])->name('produksi.store');
        Route::put('produksi/{id}', [App\Http\Controllers\Tvri\BeritaController::class, 'updateProduksi'])->name('produksi.update');
        Route::delete('produksi/{id}', [App\Http\Controllers\Tvri\BeritaController::class, 'destroyProduksi'])->name('produksi.destroy');

        // CRUD Monitoring Berita Harian
        Route::match(['post', 'delete'], 'monitoring/bulk-destroy', [App\Http\Controllers\Tvri\BeritaController::class, 'bulkDestroyMonitoring'])->name('monitoring.bulk-destroy');
        Route::post('monitoring', [App\Http\Controllers\Tvri\BeritaController::class, 'storeMonitoring'])->name('monitoring.store');
        Route::put('monitoring/{id}', [App\Http\Controllers\Tvri\BeritaController::class, 'updateMonitoring'])->name('monitoring.update');
        Route::delete('monitoring/{id}', [App\Http\Controllers\Tvri\BeritaController::class, 'destroyMonitoring'])->name('monitoring.destroy');

        // CRUD Dokumentasi Berita Harian
        Route::match(['post', 'delete'], 'dokumentasi/bulk-destroy', [App\Http\Controllers\Tvri\BeritaController::class, 'bulkDestroyDokumentasi'])->name('dokumentasi.bulk-destroy');
        Route::post('dokumentasi', [App\Http\Controllers\Tvri\BeritaController::class, 'storeDokumentasi'])->name('dokumentasi.store');
        Route::put('dokumentasi/{id}', [App\Http\Controllers\Tvri\BeritaController::class, 'updateDokumentasi'])->name('dokumentasi.update');
        Route::delete('dokumentasi/{id}', [App\Http\Controllers\Tvri\BeritaController::class, 'destroyDokumentasi'])->name('dokumentasi.destroy');
    });

    // TVRI MALUKU — DASHBOARD & CRUD BIDANG KEUANGAN (TERPADU DENGAN PANGKALAN DOKUMEN)
    Route::prefix('tvri/keuangan')->name('tvri.keuangan.')->group(function () {
        Route::get('/', [App\Http\Controllers\Tvri\KeuanganController::class, 'index'])->name('dashboard');
        Route::get('manage', [App\Http\Controllers\Tvri\KeuanganController::class, 'manage'])->name('manage');
        Route::post('ringkasan', [App\Http\Controllers\Tvri\KeuanganController::class, 'updateRingkasan'])->name('ringkasan.update');

        // CRUD Anggaran Belanja
        Route::match(['post', 'delete'], 'anggaran/bulk-destroy', [App\Http\Controllers\Tvri\KeuanganController::class, 'bulkDestroyAnggaran'])->name('anggaran.bulk-destroy');
        Route::post('anggaran', [App\Http\Controllers\Tvri\KeuanganController::class, 'storeAnggaran'])->name('anggaran.store');
        Route::put('anggaran/{id}', [App\Http\Controllers\Tvri\KeuanganController::class, 'updateAnggaran'])->name('anggaran.update');
        Route::delete('anggaran/{id}', [App\Http\Controllers\Tvri\KeuanganController::class, 'destroyAnggaran'])->name('anggaran.destroy');

        // CRUD PNBP
        Route::match(['post', 'delete'], 'pnbp/bulk-destroy', [App\Http\Controllers\Tvri\KeuanganController::class, 'bulkDestroyPnbp'])->name('pnbp.bulk-destroy');
        Route::post('pnbp', [App\Http\Controllers\Tvri\KeuanganController::class, 'storePnbp'])->name('pnbp.store');
        Route::put('pnbp/{id}', [App\Http\Controllers\Tvri\KeuanganController::class, 'updatePnbp'])->name('pnbp.update');
        Route::delete('pnbp/{id}', [App\Http\Controllers\Tvri\KeuanganController::class, 'destroyPnbp'])->name('pnbp.destroy');

        // CRUD Piutang / Tagihan
        Route::match(['post', 'delete'], 'piutang/bulk-destroy', [App\Http\Controllers\Tvri\KeuanganController::class, 'bulkDestroyPiutang'])->name('piutang.bulk-destroy');
        Route::post('piutang', [App\Http\Controllers\Tvri\KeuanganController::class, 'storePiutang'])->name('piutang.store');
        Route::put('piutang/{id}', [App\Http\Controllers\Tvri\KeuanganController::class, 'updatePiutang'])->name('piutang.update');
        Route::delete('piutang/{id}', [App\Http\Controllers\Tvri\KeuanganController::class, 'destroyPiutang'])->name('piutang.destroy');

        // CRUD Arus Kas
        Route::post('arus-kas', [App\Http\Controllers\Tvri\KeuanganController::class, 'updateArusKas'])->name('arus-kas.update');

        // CRUD Rencana Kegiatan
        Route::match(['post', 'delete'], 'kegiatan/bulk-destroy', [App\Http\Controllers\Tvri\KeuanganController::class, 'bulkDestroyKegiatan'])->name('kegiatan.bulk-destroy');
        Route::post('kegiatan', [App\Http\Controllers\Tvri\KeuanganController::class, 'storeKegiatan'])->name('kegiatan.store');
        Route::put('kegiatan/{id}', [App\Http\Controllers\Tvri\KeuanganController::class, 'updateKegiatan'])->name('kegiatan.update');
        Route::delete('kegiatan/{id}', [App\Http\Controllers\Tvri\KeuanganController::class, 'destroyKegiatan'])->name('kegiatan.destroy');

        // CRUD IKPA
        Route::post('ikpa', [App\Http\Controllers\Tvri\KeuanganController::class, 'updateIkpa'])->name('ikpa.update');
        Route::post('ikpa/store', [App\Http\Controllers\Tvri\KeuanganController::class, 'storeIkpa'])->name('ikpa.store');
        Route::delete('ikpa/{id}', [App\Http\Controllers\Tvri\KeuanganController::class, 'destroyIkpa'])->name('ikpa.destroy');

        // CRUD Dokumen & SOP Keuangan
        Route::post('sop', [App\Http\Controllers\Tvri\KeuanganController::class, 'storeSop'])->name('sop.store');
        Route::put('sop/{id}', [App\Http\Controllers\Tvri\KeuanganController::class, 'updateSop'])->name('sop.update');
        Route::match(['post', 'delete'], 'sop/bulk-destroy', [App\Http\Controllers\Tvri\KeuanganController::class, 'bulkDestroySop'])->name('sop.bulk-destroy');
        Route::delete('sop/{id}', [App\Http\Controllers\Tvri\KeuanganController::class, 'destroySop'])->name('sop.destroy');

        // Sinkronisasi Manual ke Pangkalan Dokumen
        Route::match(['get', 'post'], 'sync-dokumen', [App\Http\Controllers\Tvri\KeuanganController::class, 'syncDokumenManual'])->name('sync-dokumen');
    });

    // TVRI MALUKU — DASHBOARD PER BIDANG RESMI
    Route::get('tvri/ktu', [App\Http\Controllers\Tvri\TataUsahaController::class, 'index'])->name('tvri.ktu.dashboard');

    // TVRI MALUKU — BIDANG PROGRAM & SIARAN (DASHBOARD & MANAGE INPUT)
    Route::prefix('tvri/program')->name('tvri.program.')->group(function () {
        Route::get('/', [App\Http\Controllers\Tvri\ProgramController::class, 'index'])->name('dashboard');
        Route::get('manage', [App\Http\Controllers\Tvri\ProgramController::class, 'manage'])->name('manage');
        Route::post('store', [App\Http\Controllers\Tvri\ProgramController::class, 'store'])->name('store');
        Route::put('{id}', [App\Http\Controllers\Tvri\ProgramController::class, 'update'])->name('update');
        Route::delete('bulk-destroy', [App\Http\Controllers\Tvri\ProgramController::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::delete('agenda-bulk-destroy', [App\Http\Controllers\Tvri\ProgramController::class, 'bulkDestroyAgenda'])->name('agenda.bulk-destroy');
        Route::delete('highlight-bulk-destroy', [App\Http\Controllers\Tvri\ProgramController::class, 'bulkDestroyHighlight'])->name('highlight.bulk-destroy');
        Route::delete('{id}', [App\Http\Controllers\Tvri\ProgramController::class, 'destroy'])->name('destroy');
        Route::post('ringkasan', [App\Http\Controllers\Tvri\ProgramController::class, 'updateRingkasan'])->name('ringkasan.update');
        Route::post('agenda', [App\Http\Controllers\Tvri\ProgramController::class, 'storeAgenda'])->name('agenda.store');
        Route::delete('agenda/{id}', [App\Http\Controllers\Tvri\ProgramController::class, 'destroyAgenda'])->name('agenda.destroy');
        Route::post('highlight', [App\Http\Controllers\Tvri\ProgramController::class, 'storeHighlight'])->name('highlight.store');
        Route::delete('highlight/{id}', [App\Http\Controllers\Tvri\ProgramController::class, 'destroyHighlight'])->name('highlight.destroy');
        Route::post('sop', [App\Http\Controllers\Tvri\ProgramController::class, 'storeSop'])->name('sop.store');
        Route::put('sop/{id}', [App\Http\Controllers\Tvri\ProgramController::class, 'updateSop'])->name('sop.update');
        Route::delete('sop-bulk-destroy', [App\Http\Controllers\Tvri\ProgramController::class, 'bulkDestroySop'])->name('sop.bulk-destroy');
        Route::delete('sop/{id}', [App\Http\Controllers\Tvri\ProgramController::class, 'destroySop'])->name('sop.destroy');
        Route::post('reseed-defaults', [App\Http\Controllers\Tvri\ProgramController::class, 'reseedDefaults'])->name('reseed');
    });
    // TVRI MALUKU — BIDANG UMUM & LOGISTIK
    Route::prefix('tvri/umum')->name('tvri.umum.')->group(function () {
        Route::get('/', [App\Http\Controllers\Tvri\UmumController::class, 'index'])->name('dashboard');
        Route::get('manage', [App\Http\Controllers\Tvri\UmumController::class, 'manage'])->name('manage');
        Route::post('ringkasan', [App\Http\Controllers\Tvri\UmumController::class, 'updateRingkasan'])->name('ringkasan.update');
        Route::post('aset', [App\Http\Controllers\Tvri\UmumController::class, 'storeAset'])->name('aset.store');
        Route::put('aset/{id}', [App\Http\Controllers\Tvri\UmumController::class, 'updateAset'])->name('aset.update');
        Route::delete('aset/bulk-destroy', [App\Http\Controllers\Tvri\UmumController::class, 'bulkDestroyAset'])->name('aset.bulk-destroy');
        Route::delete('aset/{id}', [App\Http\Controllers\Tvri\UmumController::class, 'destroyAset'])->name('aset.destroy');
        Route::post('kendaraan', [App\Http\Controllers\Tvri\UmumController::class, 'storeKendaraan'])->name('kendaraan.store');
        Route::put('kendaraan/{id}', [App\Http\Controllers\Tvri\UmumController::class, 'updateKendaraan'])->name('kendaraan.update');
        Route::delete('kendaraan/bulk-destroy', [App\Http\Controllers\Tvri\UmumController::class, 'bulkDestroyKendaraan'])->name('kendaraan.bulk-destroy');
        Route::delete('kendaraan/{id}', [App\Http\Controllers\Tvri\UmumController::class, 'destroyKendaraan'])->name('kendaraan.destroy');
        Route::post('layanan', [App\Http\Controllers\Tvri\UmumController::class, 'storeLayanan'])->name('layanan.store');
        Route::put('layanan/{id}', [App\Http\Controllers\Tvri\UmumController::class, 'updateLayanan'])->name('layanan.update');
        Route::delete('layanan/bulk-destroy', [App\Http\Controllers\Tvri\UmumController::class, 'bulkDestroyLayanan'])->name('layanan.bulk-destroy');
        Route::delete('layanan/{id}', [App\Http\Controllers\Tvri\UmumController::class, 'destroyLayanan'])->name('layanan.destroy');
        Route::post('sarpras', [App\Http\Controllers\Tvri\UmumController::class, 'storeSarpras'])->name('sarpras.store');
        Route::put('sarpras/{id}', [App\Http\Controllers\Tvri\UmumController::class, 'updateSarpras'])->name('sarpras.update');
        Route::delete('sarpras/bulk-destroy', [App\Http\Controllers\Tvri\UmumController::class, 'bulkDestroySarpras'])->name('sarpras.bulk-destroy');
        Route::delete('sarpras/{id}', [App\Http\Controllers\Tvri\UmumController::class, 'destroySarpras'])->name('sarpras.destroy');
        Route::post('pemeliharaan', [App\Http\Controllers\Tvri\UmumController::class, 'storePemeliharaan'])->name('pemeliharaan.store');
        Route::put('pemeliharaan/{id}', [App\Http\Controllers\Tvri\UmumController::class, 'updatePemeliharaan'])->name('pemeliharaan.update');
        Route::delete('pemeliharaan/bulk-destroy', [App\Http\Controllers\Tvri\UmumController::class, 'bulkDestroyPemeliharaan'])->name('pemeliharaan.bulk-destroy');
        Route::delete('pemeliharaan/{id}', [App\Http\Controllers\Tvri\UmumController::class, 'destroyPemeliharaan'])->name('pemeliharaan.destroy');
        Route::post('stok', [App\Http\Controllers\Tvri\UmumController::class, 'storeStok'])->name('stok.store');
        Route::put('stok/{id}', [App\Http\Controllers\Tvri\UmumController::class, 'updateStok'])->name('stok.update');
        Route::delete('stok/bulk-destroy', [App\Http\Controllers\Tvri\UmumController::class, 'bulkDestroyStok'])->name('stok.bulk-destroy');
        Route::delete('stok/{id}', [App\Http\Controllers\Tvri\UmumController::class, 'destroyStok'])->name('stok.destroy');
        Route::post('reseed', [App\Http\Controllers\Tvri\UmumController::class, 'reseedDefaults'])->name('reseed');
    });
    // TVRI MALUKU - BIDANG KONTEN MEDIA BARU (DASHBOARD, MANAGE CRUD & SOP TERPADU)
    Route::prefix('tvri/mbn')->name('tvri.mbn.')->group(function () {
        Route::get('/', [App\Http\Controllers\Tvri\KontenMediaBaruController::class, 'index'])->name('dashboard');
        Route::get('manage', [App\Http\Controllers\Tvri\KontenMediaBaruController::class, 'manage'])->name('manage');
        Route::post('ringkasan', [App\Http\Controllers\Tvri\KontenMediaBaruController::class, 'updateRingkasan'])->name('ringkasan.update');
        Route::post('reseed', [App\Http\Controllers\Tvri\KontenMediaBaruController::class, 'reseedDefaults'])->name('reseed');

        // CRUD Konten
        Route::post('konten', [App\Http\Controllers\Tvri\KontenMediaBaruController::class, 'storeKonten'])->name('konten.store');
        Route::put('konten/{id}', [App\Http\Controllers\Tvri\KontenMediaBaruController::class, 'updateKonten'])->name('konten.update');
        Route::delete('konten/bulk-destroy', [App\Http\Controllers\Tvri\KontenMediaBaruController::class, 'bulkDestroyKonten'])->name('konten.bulk-destroy');
        Route::delete('konten/{id}', [App\Http\Controllers\Tvri\KontenMediaBaruController::class, 'destroyKonten'])->name('konten.destroy');

        // CRUD SOP & Regulasi
        Route::post('sop', [App\Http\Controllers\Tvri\KontenMediaBaruController::class, 'storeSop'])->name('sop.store');
        Route::put('sop/{id}', [App\Http\Controllers\Tvri\KontenMediaBaruController::class, 'updateSop'])->name('sop.update');
        Route::delete('sop/bulk-destroy', [App\Http\Controllers\Tvri\KontenMediaBaruController::class, 'bulkDestroySop'])->name('sop.bulk-destroy');
        Route::delete('sop/{id}', [App\Http\Controllers\Tvri\KontenMediaBaruController::class, 'destroySop'])->name('sop.destroy');
    });
    Route::get('tvri/kmb', [App\Http\Controllers\Tvri\KontenMediaBaruController::class, 'index'])->name('tvri.kmb.dashboard');
    Route::get('tvri/kmb/manage', [App\Http\Controllers\Tvri\KontenMediaBaruController::class, 'manage'])->name('tvri.kmb.manage');
    // TVRI MALUKU — BIDANG PROMO & CITRA (DASHBOARD & MANAGE INPUT)
    Route::prefix('tvri/promo')->name('tvri.promo.')->group(function () {
        Route::get('/', [App\Http\Controllers\Tvri\PromoController::class, 'index'])->name('dashboard');
        Route::get('manage', [App\Http\Controllers\Tvri\PromoController::class, 'manage'])->name('manage');
        Route::get('create', [App\Http\Controllers\Tvri\PromoController::class, 'manage'])->name('create');
        Route::post('ringkasan', [App\Http\Controllers\Tvri\PromoController::class, 'updateRingkasan'])->name('ringkasan.update');
        Route::post('reseed', [App\Http\Controllers\Tvri\PromoController::class, 'reseedDefaults'])->name('reseed');
        Route::post('jadwal', [App\Http\Controllers\Tvri\PromoController::class, 'storeJadwal'])->name('jadwal.store');
        Route::delete('jadwal/{index}', [App\Http\Controllers\Tvri\PromoController::class, 'destroyJadwal'])->name('jadwal.destroy');
        Route::post('agenda', [App\Http\Controllers\Tvri\PromoController::class, 'storeAgenda'])->name('agenda.store');
        Route::delete('agenda/{index}', [App\Http\Controllers\Tvri\PromoController::class, 'destroyAgenda'])->name('agenda.destroy');

        Route::match(['post', 'delete'], 'materi/bulk-destroy', [App\Http\Controllers\Tvri\PromoController::class, 'bulkDestroyMateri'])->name('materi.bulk-destroy');
        Route::post('materi', [App\Http\Controllers\Tvri\PromoController::class, 'storeMateri'])->name('materi.store');
        Route::put('materi/{id}', [App\Http\Controllers\Tvri\PromoController::class, 'updateMateri'])->name('materi.update');
        Route::delete('materi/{id}', [App\Http\Controllers\Tvri\PromoController::class, 'destroyMateri'])->name('materi.destroy');
        Route::match(['post', 'delete'], 'kampanye/bulk-destroy', [App\Http\Controllers\Tvri\PromoController::class, 'bulkDestroyKampanye'])->name('kampanye.bulk-destroy');
        Route::post('kampanye', [App\Http\Controllers\Tvri\PromoController::class, 'storeKampanye'])->name('kampanye.store');
        Route::put('kampanye/{id}', [App\Http\Controllers\Tvri\PromoController::class, 'updateKampanye'])->name('kampanye.update');
        Route::delete('kampanye/{id}', [App\Http\Controllers\Tvri\PromoController::class, 'destroyKampanye'])->name('kampanye.destroy');

        // CRUD Fitur SOP & Regulasi Promo
        Route::post('sop', [App\Http\Controllers\Tvri\PromoController::class, 'storeSop'])->name('sop.store');
        Route::put('sop/{id}', [App\Http\Controllers\Tvri\PromoController::class, 'updateSop'])->name('sop.update');
        Route::delete('sop/bulk-destroy', [App\Http\Controllers\Tvri\PromoController::class, 'bulkDestroySop'])->name('sop.bulk-destroy');
        Route::delete('sop/{id}', [App\Http\Controllers\Tvri\PromoController::class, 'destroySop'])->name('sop.destroy');
    });
    // TVRI MALUKU — BIDANG PENGEMBANGAN USAHA (DASHBOARD, MANAGE CRUD & SOP TERPADU)
    Route::prefix('tvri/usaha')->name('tvri.usaha.')->group(function () {
        Route::get('/', [App\Http\Controllers\Tvri\PengembanganUsahaController::class, 'index'])->name('dashboard');
        Route::get('manage', [App\Http\Controllers\Tvri\PengembanganUsahaController::class, 'manage'])->name('manage');
        Route::post('ringkasan', [App\Http\Controllers\Tvri\PengembanganUsahaController::class, 'updateRingkasan'])->name('ringkasan.update');
        Route::post('reseed', [App\Http\Controllers\Tvri\PengembanganUsahaController::class, 'reseedDefaults'])->name('reseed');

        // CRUD Mitra / Klien
        Route::post('mitra', [App\Http\Controllers\Tvri\PengembanganUsahaController::class, 'storeMitra'])->name('mitra.store');
        Route::put('mitra/{id}', [App\Http\Controllers\Tvri\PengembanganUsahaController::class, 'updateMitra'])->name('mitra.update');
        Route::delete('mitra/bulk-destroy', [App\Http\Controllers\Tvri\PengembanganUsahaController::class, 'bulkDestroyMitra'])->name('mitra.bulk-destroy');
        Route::delete('mitra/{id}', [App\Http\Controllers\Tvri\PengembanganUsahaController::class, 'destroyMitra'])->name('mitra.destroy');

        // CRUD Kegiatan Usaha
        Route::post('kegiatan', [App\Http\Controllers\Tvri\PengembanganUsahaController::class, 'storeKegiatan'])->name('kegiatan.store');
        Route::put('kegiatan/{id}', [App\Http\Controllers\Tvri\PengembanganUsahaController::class, 'updateKegiatan'])->name('kegiatan.update');
        Route::delete('kegiatan/bulk-destroy', [App\Http\Controllers\Tvri\PengembanganUsahaController::class, 'bulkDestroyKegiatan'])->name('kegiatan.bulk-destroy');
        Route::delete('kegiatan/{id}', [App\Http\Controllers\Tvri\PengembanganUsahaController::class, 'destroyKegiatan'])->name('kegiatan.destroy');

        // CRUD Sumber Pendapatan / PNBP
        Route::post('pnbp', [App\Http\Controllers\Tvri\PengembanganUsahaController::class, 'storePnbp'])->name('pnbp.store');
        Route::put('pnbp/{id}', [App\Http\Controllers\Tvri\PengembanganUsahaController::class, 'updatePnbp'])->name('pnbp.update');
        Route::delete('pnbp/bulk-destroy', [App\Http\Controllers\Tvri\PengembanganUsahaController::class, 'bulkDestroyPnbp'])->name('pnbp.bulk-destroy');
        Route::delete('pnbp/{id}', [App\Http\Controllers\Tvri\PengembanganUsahaController::class, 'destroyPnbp'])->name('pnbp.destroy');

        // CRUD SOP & Regulasi
        Route::post('sop', [App\Http\Controllers\Tvri\PengembanganUsahaController::class, 'storeSop'])->name('sop.store');
        Route::put('sop/{id}', [App\Http\Controllers\Tvri\PengembanganUsahaController::class, 'updateSop'])->name('sop.update');
        Route::delete('sop/bulk-destroy', [App\Http\Controllers\Tvri\PengembanganUsahaController::class, 'bulkDestroySop'])->name('sop.bulk-destroy');
        Route::delete('sop/{id}', [App\Http\Controllers\Tvri\PengembanganUsahaController::class, 'destroySop'])->name('sop.destroy');
    });
    // Bidang Teknik Studio & Fasilitas
    Route::prefix('tvri/teknik')->name('tvri.teknik.')->group(function () {
        Route::get('/', [App\Http\Controllers\Tvri\TeknikController::class, 'index'])->name('dashboard');
        Route::get('/manage', [App\Http\Controllers\Tvri\TeknikController::class, 'manage'])->name('manage');
        Route::post('/ringkasan', [App\Http\Controllers\Tvri\TeknikController::class, 'updateRingkasan'])->name('ringkasan.update');
        Route::post('/reseed', [App\Http\Controllers\Tvri\TeknikController::class, 'reseedDefaults'])->name('reseed');

        // CRUD Jadwal Produksi
        Route::post('/jadwal', [App\Http\Controllers\Tvri\TeknikController::class, 'storeJadwal'])->name('jadwal.store');
        Route::put('/jadwal/{id}', [App\Http\Controllers\Tvri\TeknikController::class, 'updateJadwal'])->name('jadwal.update');
        Route::delete('/jadwal/bulk-destroy', [App\Http\Controllers\Tvri\TeknikController::class, 'bulkDestroyJadwal'])->name('jadwal.bulk-destroy');
        Route::delete('/jadwal/{id}', [App\Http\Controllers\Tvri\TeknikController::class, 'destroyJadwal'])->name('jadwal.destroy');

        // CRUD Maintenance Peralatan
        Route::post('/maintenance', [App\Http\Controllers\Tvri\TeknikController::class, 'storeMaintenance'])->name('maintenance.store');
        Route::put('/maintenance/{id}', [App\Http\Controllers\Tvri\TeknikController::class, 'updateMaintenance'])->name('maintenance.update');
        Route::delete('/maintenance/bulk-destroy', [App\Http\Controllers\Tvri\TeknikController::class, 'bulkDestroyMaintenance'])->name('maintenance.bulk-destroy');
        Route::delete('/maintenance/{id}', [App\Http\Controllers\Tvri\TeknikController::class, 'destroyMaintenance'])->name('maintenance.destroy');

        // CRUD Insiden & Gangguan
        Route::post('/insiden', [App\Http\Controllers\Tvri\TeknikController::class, 'storeInsiden'])->name('insiden.store');
        Route::put('/insiden/{id}', [App\Http\Controllers\Tvri\TeknikController::class, 'updateInsiden'])->name('insiden.update');
        Route::delete('/insiden/bulk-destroy', [App\Http\Controllers\Tvri\TeknikController::class, 'bulkDestroyInsiden'])->name('insiden.bulk-destroy');
        Route::delete('/insiden/{id}', [App\Http\Controllers\Tvri\TeknikController::class, 'destroyInsiden'])->name('insiden.destroy');

        // CRUD SOP Teknik
        Route::post('/sop', [App\Http\Controllers\Tvri\TeknikController::class, 'storeSop'])->name('sop.store');
        Route::put('/sop/{id}', [App\Http\Controllers\Tvri\TeknikController::class, 'updateSop'])->name('sop.update');
        Route::delete('/sop/bulk-destroy', [App\Http\Controllers\Tvri\TeknikController::class, 'bulkDestroySop'])->name('sop.bulk-destroy');
        Route::delete('/sop/{id}', [App\Http\Controllers\Tvri\TeknikController::class, 'destroySop'])->name('sop.destroy');

        // CRUD Transmitter Digital
        Route::post('/transmitter', [App\Http\Controllers\Tvri\TeknikController::class, 'storeTransmitter'])->name('transmitter.store');
        Route::put('/transmitter/{id}', [App\Http\Controllers\Tvri\TeknikController::class, 'updateTransmitter'])->name('transmitter.update');
        Route::delete('/transmitter/bulk-destroy', [App\Http\Controllers\Tvri\TeknikController::class, 'bulkDestroyTransmitter'])->name('transmitter.bulk-destroy');
        Route::delete('/transmitter/{id}', [App\Http\Controllers\Tvri\TeknikController::class, 'destroyTransmitter'])->name('transmitter.destroy');
    });
    // Bidang Teknik Transmisi & Pemancar
    Route::prefix('tvri/transmisi')->name('tvri.transmisi.')->group(function () {
        Route::get('/', [App\Http\Controllers\Tvri\TransmisiController::class, 'index'])->name('dashboard');
        Route::get('/manage', [App\Http\Controllers\Tvri\TransmisiController::class, 'manage'])->name('manage');
        Route::post('/ringkasan', [App\Http\Controllers\Tvri\TransmisiController::class, 'updateRingkasan'])->name('ringkasan.update');
        Route::post('/reseed', [App\Http\Controllers\Tvri\TransmisiController::class, 'reseedDefaults'])->name('reseed');

        // CRUD Pemancar
        Route::post('/pemancar', [App\Http\Controllers\Tvri\TransmisiController::class, 'storePemancar'])->name('pemancar.store');
        Route::put('/pemancar/{id}', [App\Http\Controllers\Tvri\TransmisiController::class, 'updatePemancar'])->name('pemancar.update');
        Route::delete('/pemancar/bulk-destroy', [App\Http\Controllers\Tvri\TransmisiController::class, 'bulkDestroyPemancar'])->name('pemancar.bulk-destroy');
        Route::delete('/pemancar/{id}', [App\Http\Controllers\Tvri\TransmisiController::class, 'destroyPemancar'])->name('pemancar.destroy');

        // CRUD Gangguan
        Route::post('/gangguan', [App\Http\Controllers\Tvri\TransmisiController::class, 'storeGangguan'])->name('gangguan.store');
        Route::put('/gangguan/{id}', [App\Http\Controllers\Tvri\TransmisiController::class, 'updateGangguan'])->name('gangguan.update');
        Route::delete('/gangguan/bulk-destroy', [App\Http\Controllers\Tvri\TransmisiController::class, 'bulkDestroyGangguan'])->name('gangguan.bulk-destroy');
        Route::delete('/gangguan/{id}', [App\Http\Controllers\Tvri\TransmisiController::class, 'destroyGangguan'])->name('gangguan.destroy');

        // CRUD Pemeliharaan
        Route::post('/pemeliharaan', [App\Http\Controllers\Tvri\TransmisiController::class, 'storePemeliharaan'])->name('pemeliharaan.store');
        Route::put('/pemeliharaan/{id}', [App\Http\Controllers\Tvri\TransmisiController::class, 'updatePemeliharaan'])->name('pemeliharaan.update');
        Route::delete('/pemeliharaan/bulk-destroy', [App\Http\Controllers\Tvri\TransmisiController::class, 'bulkDestroyPemeliharaan'])->name('pemeliharaan.bulk-destroy');
        Route::delete('/pemeliharaan/{id}', [App\Http\Controllers\Tvri\TransmisiController::class, 'destroyPemeliharaan'])->name('pemeliharaan.destroy');

        // CRUD Log Aktivitas
        Route::post('/log', [App\Http\Controllers\Tvri\TransmisiController::class, 'storeLog'])->name('log.store');
        Route::put('/log/{id}', [App\Http\Controllers\Tvri\TransmisiController::class, 'updateLog'])->name('log.update');
        Route::delete('/log/bulk-destroy', [App\Http\Controllers\Tvri\TransmisiController::class, 'bulkDestroyLog'])->name('log.bulk-destroy');
        Route::delete('/log/{id}', [App\Http\Controllers\Tvri\TransmisiController::class, 'destroyLog'])->name('log.destroy');

        // CRUD SOP Transmisi
        Route::post('/sop', [App\Http\Controllers\Tvri\TransmisiController::class, 'storeSop'])->name('sop.store');
        Route::put('/sop/{id}', [App\Http\Controllers\Tvri\TransmisiController::class, 'updateSop'])->name('sop.update');
        Route::delete('/sop/bulk-destroy', [App\Http\Controllers\Tvri\TransmisiController::class, 'bulkDestroySop'])->name('sop.bulk-destroy');
        Route::delete('/sop/{id}', [App\Http\Controllers\Tvri\TransmisiController::class, 'destroySop'])->name('sop.destroy');
    });

    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('terpadu', [App\Http\Controllers\Laporan\LaporanController::class, 'terpadu'])->name('terpadu');
        Route::get('terpadu/export-csv', [App\Http\Controllers\Laporan\LaporanController::class, 'exportTerpaduCsv'])->name('terpadu.export-csv');
        Route::get('statistik', [App\Http\Controllers\Laporan\LaporanController::class, 'statistik'])->name('statistik');
    });
    
    // Tokoh CRUD
    Route::resource('tokohs', App\Http\Controllers\TokohController::class);
    
    // Ajax Regions
    Route::get('ajax/kabupatens', [App\Http\Controllers\RegionAjaxController::class, 'getKabupaten']);
    Route::get('ajax/kecamatans', [App\Http\Controllers\RegionAjaxController::class, 'getKecamatan']);
    Route::get('ajax/desas', [App\Http\Controllers\RegionAjaxController::class, 'getDesa']);

    // Internal API for React Dashboard
    Route::prefix('api')->group(function () {
        Route::apiResource('digital-archives', App\Http\Controllers\DigitalArchiveController::class);
        Route::apiResource('sops', App\Http\Controllers\SopController::class); // Older one?
        Route::apiResource('newsrooms', App\Http\Controllers\NewsroomController::class);
        Route::apiResource('hybrid-broadcasts', App\Http\Controllers\HybridBroadcastController::class);
        
        // Phase 1 New Menus
        Route::apiResource('penugasan', App\Http\Controllers\PenugasanController::class);
        Route::apiResource('rapat-koordinasi', App\Http\Controllers\RapatKoordinasiController::class);
        Route::apiResource('sop-perencanaan', App\Http\Controllers\SopPerencanaanController::class);
        Route::apiResource('kalender-produksi', App\Http\Controllers\KalenderProduksiController::class);
        Route::get('dashboard/metrics', [App\Http\Controllers\DashboardMetricsController::class, 'getMetrics']);

        // Phase 2 New Menus
        Route::apiResource('mitra', App\Http\Controllers\MitraController::class);
        Route::apiResource('arsip-digital', App\Http\Controllers\ArsipDigitalController::class);

        // Phase 3 New Menus
        Route::apiResource('berita-newsroom', App\Http\Controllers\BeritaNewsroomController::class);
        Route::apiResource('distribusi-konten', App\Http\Controllers\DistribusiKontenController::class);
        Route::apiResource('socmed-publish', App\Http\Controllers\SocialMediaPublishController::class);
        
        // Island Content Network
        Route::apiResource('island-content-networks', App\Http\Controllers\IslandContentNetworkController::class);
    });
});

Route::middleware('auth')->prefix('master')->group(function () {
    Route::resource('agamas', App\Http\Controllers\AgamaController::class)->except(['create', 'show', 'edit'])->names([
        'index' => 'master.agamas.index',
        'store' => 'master.agamas.store',
        'update' => 'master.agamas.update',
        'destroy' => 'master.agamas.destroy',
    ]);
    Route::resource('sukus', App\Http\Controllers\SukuController::class)->except(['create', 'show', 'edit'])->names([
        'index' => 'master.sukus.index',
        'store' => 'master.sukus.store',
        'update' => 'master.sukus.update',
        'destroy' => 'master.sukus.destroy',
    ]);
    Route::resource('pendidikans', App\Http\Controllers\PendidikanController::class)->except(['create', 'show', 'edit'])->names([
        'index' => 'master.pendidikans.index',
        'store' => 'master.pendidikans.store',
        'update' => 'master.pendidikans.update',
        'destroy' => 'master.pendidikans.destroy',
    ]);
    Route::resource('profesis', App\Http\Controllers\ProfesiController::class)->except(['create', 'show', 'edit'])->names([
        'index' => 'master.profesis.index',
        'store' => 'master.profesis.store',
        'update' => 'master.profesis.update',
        'destroy' => 'master.profesis.destroy',
    ]);
    Route::resource('organisasis', App\Http\Controllers\OrganisasiController::class)->except(['create', 'show', 'edit'])->names([
        'index' => 'master.organisasis.index',
        'store' => 'master.organisasis.store',
        'update' => 'master.organisasis.update',
        'destroy' => 'master.organisasis.destroy',
    ]);
    Route::resource('kategori-tokohs', App\Http\Controllers\KategoriTokohController::class)->except(['create', 'show', 'edit'])->names([
        'index' => 'master.kategori-tokohs.index',
        'store' => 'master.kategori-tokohs.store',
        'update' => 'master.kategori-tokohs.update',
        'destroy' => 'master.kategori-tokohs.destroy',
    ]);
    Route::resource('provinsis', App\Http\Controllers\ProvinsiController::class)->except(['create', 'show', 'edit'])->names([
        'index' => 'master.provinsis.index',
        'store' => 'master.provinsis.store',
        'update' => 'master.provinsis.update',
        'destroy' => 'master.provinsis.destroy',
    ]);
    Route::resource('kabupatens', App\Http\Controllers\KabupatenController::class)->except(['create', 'show', 'edit'])->names([
        'index' => 'master.kabupatens.index',
        'store' => 'master.kabupatens.store',
        'update' => 'master.kabupatens.update',
        'destroy' => 'master.kabupatens.destroy',
    ]);
    Route::resource('kecamatans', App\Http\Controllers\KecamatanController::class)->except(['create', 'show', 'edit'])->names([
        'index' => 'master.kecamatans.index',
        'store' => 'master.kecamatans.store',
        'update' => 'master.kecamatans.update',
        'destroy' => 'master.kecamatans.destroy',
    ]);
    Route::resource('desas', App\Http\Controllers\DesaController::class)->except(['create', 'show', 'edit'])->names([
        'index' => 'master.desas.index',
        'store' => 'master.desas.store',
        'update' => 'master.desas.update',
        'destroy' => 'master.desas.destroy',
    ]);
    Route::resource('provinsis', App\Http\Controllers\ProvinsiController::class)->except(['create', 'show', 'edit'])->names([
        'index' => 'master.provinsis.index',
        'store' => 'master.provinsis.store',
        'update' => 'master.provinsis.update',
        'destroy' => 'master.provinsis.destroy',
    ]);
    Route::resource('kabupatens', App\Http\Controllers\KabupatenController::class)->except(['create', 'show', 'edit'])->names([
        'index' => 'master.kabupatens.index',
        'store' => 'master.kabupatens.store',
        'update' => 'master.kabupatens.update',
        'destroy' => 'master.kabupatens.destroy',
    ]);
    Route::resource('kecamatans', App\Http\Controllers\KecamatanController::class)->except(['create', 'show', 'edit'])->names([
        'index' => 'master.kecamatans.index',
        'store' => 'master.kecamatans.store',
        'update' => 'master.kecamatans.update',
        'destroy' => 'master.kecamatans.destroy',
    ]);
    Route::resource('desas', App\Http\Controllers\DesaController::class)->except(['create', 'show', 'edit'])->names([
        'index' => 'master.desas.index',
        'store' => 'master.desas.store',
        'update' => 'master.desas.update',
        'destroy' => 'master.desas.destroy',
    ]);
});




