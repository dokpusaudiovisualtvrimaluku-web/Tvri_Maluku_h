<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $activityFilter = $request->input('activity');

        $query = ActivityLog::with('user')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('ip', 'like', "%{$search}%")
                  ->orWhere('activity', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('username', 'like', "%{$search}%");
                  });
            });
        }

        if ($activityFilter) {
            $query->where('activity', $activityFilter);
        }

        $logs = $query->paginate(20)->withQueryString();

        $activityTypes = [
            'LOGIN' => 'Login Berhasil',
            'LOGOUT' => 'Logout',
            'LOGIN_FAILED' => 'Login Gagal',
            'PROFILE_UPDATED' => 'Profil Diperbarui',
            'PASSWORD_CHANGED' => 'Password Diubah',
            'USER_CREATED' => 'Pengguna Ditambahkan',
            'USER_UPDATED' => 'Pengguna Diperbarui',
            'USER_DISABLED' => 'Pengguna Dinonaktifkan',
            'DOCUMENT_UPLOAD' => 'Dokumen Diunggah',
            'DOCUMENT_DELETE' => 'Dokumen Dihapus',
            'DOCUMENT_DOWNLOAD' => 'Dokumen Diunduh',
            'FOLDER_CREATE' => 'Folder Dibuat',
        ];

        return view('admin.activity_logs.index', compact('logs', 'search', 'activityFilter', 'activityTypes'));
    }

    public function destroy($id)
    {
        $log = ActivityLog::findOrFail($id);
        $log->delete();

        return redirect()->back()->with('success', 'Aktivitas log berhasil dihapus.');
    }

    public function destroyAll()
    {
        ActivityLog::truncate();

        return redirect()->back()->with('success', 'Semua riwayat aktivitas log berhasil dibersihkan.');
    }
}
