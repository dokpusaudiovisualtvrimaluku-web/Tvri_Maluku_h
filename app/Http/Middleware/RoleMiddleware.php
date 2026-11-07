<?php

namespace App\Http\Middleware;

use App\Services\ActivityLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user status is ACTIVE
        if (!$user->isActive()) {
            ActivityLogger::log('USER_DISABLED_ACCESS_ATTEMPT', 'Mencoba mengakses sistem dalam status nonaktif', $user->id);
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'login' => 'Akun Anda saat ini telah dinonaktifkan oleh Administrator. Hubungi pihak dinas untuk informasi lebih lanjut.',
            ]);
        }

        if (empty($roles)) {
            return $next($request);
        }

        // Check if user has any of the allowed roles
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
            
            // Explicit exception for TU and Kabid users accessing ADMIN routes
            if ($role === 'ADMIN' && in_array($user->username, ['admin.tu', 'kabid.ketenagaan', 'kabid.sma', 'kabid.diksus'])) {
                return $next($request);
            }
        }

        // Cross-role access denied
        if ($user->hasRole('KEPALA_DINAS') && in_array('ADMIN', $roles)) {
            return redirect()->route('kepala-dinas.dashboard')->with('error', 'Akses ditolak: Kepala Dinas tidak memiliki wewenang untuk mengakses modul Administrator.');
        }

        if ($user->hasRole('ADMIN') && in_array('KEPALA_DINAS', $roles)) {
            return redirect()->route('admin.dashboard')->with('info', 'Anda diarahkan kembali ke Dashboard Administrator.');
        }

        abort(403, 'Akses Ditolak: Anda tidak memiliki izin role yang sesuai untuk mengakses halaman ini.');
    }
}
