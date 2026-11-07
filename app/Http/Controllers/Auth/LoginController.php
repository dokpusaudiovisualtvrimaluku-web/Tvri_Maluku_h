<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('KEPALA_DINAS') || $user->hasRole('Kepala Stasiun') || $user->username === 'kepala.stasiun' || $user->email === 'kepala.stasiun@tvri.go.id' || str_contains(strtolower($user->name), 'kepala stasiun') || str_contains(strtolower($user->name), 'kepala dinas')) {
                return redirect()->route('tvri.ecc.dashboard');
            }
            if ($user->username === 'admin.tu' || $user->username === 'tu' || $user->email === 'tu@tvri.go.id' || $user->hasRole('Tata Usaha') || str_contains(strtolower($user->name ?? ''), 'tata usaha')) {
                return redirect()->route('tvri.tu.dashboard');
            }
            if ($user->username === 'berita' || $user->email === 'berita@tvri.go.id') {
                return redirect()->route('tvri.berita.dashboard');
            }
            if ($user->username === 'program' || $user->email === 'program@tvri.go.id') {
                return redirect()->route('tvri.program.dashboard');
            }
            if ($user->username === 'konten.media.baru' || $user->username === 'kmb' || $user->email === 'kmb@tvri.go.id') {
                return redirect()->route('tvri.mbn.dashboard');
            }
            if ($user->username === 'promo' || $user->email === 'promo@tvri.go.id' || $user->hasRole('Promo') || str_contains(strtolower($user->name ?? ''), 'promo')) {
                return redirect()->route('tvri.promo.dashboard');
            }
            if ($user->username === 'pengembangan.usaha' || $user->username === 'usaha' || $user->email === 'usaha@tvri.go.id') {
                return redirect()->route('tvri.usaha.dashboard');
            }
            if ($user->username === 'teknik' || $user->email === 'teknik@tvri.go.id') {
                return redirect()->route('tvri.teknik.dashboard');
            }
            if ($user->username === 'teknik.transmisi' || $user->username === 'transmisi' || $user->email === 'transmisi@tvri.go.id') {
                return redirect()->route('tvri.transmisi.dashboard');
            }
            if ($user->username === 'umum' || $user->email === 'umum@tvri.go.id') {
                return redirect()->route('tvri.umum.dashboard');
            }
            if ($user->username === 'keuangan' || $user->username === 'kasubag.keuangan' || $user->email === 'keuangan@tvri.go.id') {
                return redirect()->route('tvri.keuangan.dashboard');
            }
            if ($user->hasRole('ADMIN') || $user->username === 'admin' || $user->username === 'superadmin' || $user->email === 'admin@tvri.go.id') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Username atau Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $this->ensureIsNotRateLimited($request);

        $loginInput = $request->input('login');
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        // Normalise common alias logins
        $normalizedLogin = strtolower(trim((string)$loginInput));
        $tuAliases = ['tu', 'admin.tu', 'tata.usaha', 'tatausaha'];

        // Check user by username or email with alias support
        $user = User::where('username', $loginInput)
            ->orWhere('email', $loginInput)
            ->when(in_array($normalizedLogin, $tuAliases), function ($q) {
                $q->orWhere('username', 'admin.tu')
                  ->orWhere('username', 'tu')
                  ->orWhere('email', 'tu@tvri.go.id');
            })
            ->when(in_array($normalizedLogin, ['superadmin', 'admin.ti']), function ($q) {
                $q->orWhere('username', 'admin')
                  ->orWhere('email', 'admin@tvri.go.id');
            })
            ->first();

        // Check password validity (including hosting / seeders designated fallback)
        $isPasswordValid = false;
        if ($user) {
            if (Hash::check($password, $user->password)) {
                $isPasswordValid = true;
            } else {
                // Per-account designated credentials fallback map
                $accountPasswordMap = [
                    'umum' => 'TvriMaluku@2026',
                    'umum@tvri.go.id' => 'TvriMaluku@2026',
                    'kepala.stasiun' => 'KepstaTvriMaluku@2026',
                    'kepala.stasiun@tvri.go.id' => 'KepstaTvriMaluku@2026',
                    'berita' => 'TvriMalukuberita@2026',
                    'berita@tvri.go.id' => 'TvriMalukuberita@2026',
                    'keuangan' => 'TvriMalukukeuangan@2026',
                    'keuangan@tvri.go.id' => 'TvriMalukukeuangan@2026',
                    'kasubag.keuangan' => 'TvriMalukukeuangan@2026',
                    'kasubag.keuangan@tvri.go.id' => 'TvriMalukukeuangan@2026',
                    'kmb' => 'TvriMalukuKMB@2026',
                    'kmb@tvri.go.id' => 'TvriMalukuKMB@2026',
                    'konten.media.baru' => 'TvriMalukuKMB@2026',
                    'usaha' => 'TvriMalukuPU@2026',
                    'usaha@tvri.go.id' => 'TvriMalukuPU@2026',
                    'pengembangan.usaha' => 'TvriMalukuPU@2026',
                    'promo' => 'TvriMalukupromo@2026',
                    'promo@tvri.go.id' => 'TvriMalukupromo@2026',
                    'tu' => 'TvriMalukuTU@2026',
                    'admin.tu' => 'TvriMalukuTU@2026',
                    'tu@tvri.go.id' => 'TvriMalukuTU@2026',
                    'teknik' => 'TvriMalukuteknik@2026',
                    'teknik@tvri.go.id' => 'TvriMalukuteknik@2026',
                    'transmisi' => 'TvriMalukutransmisi@2026',
                    'teknik.transmisi' => 'TvriMalukutransmisi@2026',
                    'transmisi@tvri.go.id' => 'TvriMalukutransmisi@2026',
                    'program' => 'TvriMalukuprogram@2026',
                    'program@tvri.go.id' => 'TvriMalukuprogram@2026',
                    'admin' => 'AdminTI@Maluku2026',
                    'admin@tvri.go.id' => 'AdminTI@Maluku2026',
                    'superadmin' => 'AdminTI@Maluku2026',
                    'superadmin@tvri.go.id' => 'AdminTI@Maluku2026',
                ];

                $userKey = strtolower((string)$user->username);
                $emailKey = strtolower((string)$user->email);
                $expectedPassword = $accountPasswordMap[$userKey] ?? $accountPasswordMap[$emailKey] ?? null;

                if ($expectedPassword && $password === $expectedPassword) {
                    $isPasswordValid = true;
                    $user->password = Hash::make($password);
                    $user->save();
                }
            }
        }

        if ($user && $isPasswordValid) {
            // Check status active
            if (!$user->isActive()) {
                ActivityLogger::log('LOGIN_FAILED', "Percobaan login akun nonaktif: {$loginInput}", $user->id);
                throw ValidationException::withMessages([
                    'login' => 'Akun Anda saat ini dinonaktifkan oleh Administrator. Hubungi pihak dinas.',
                ]);
            }

            // Authenticate user
            Auth::login($user, $remember);
            RateLimiter::clear($this->throttleKey($request));
            $request->session()->regenerate();

            // Update last login
            $user->update([
                'last_login_at' => now(),
            ]);

            // Log Login
            ActivityLogger::log('LOGIN', "Pengguna {$user->name} ({$user->username}) berhasil masuk ke sistem.");

            // Redirect based on role and bidang
            if ($user->hasRole('KEPALA_DINAS') || $user->hasRole('Kepala Stasiun') || $user->username === 'kepala.stasiun' || $user->email === 'kepala.stasiun@tvri.go.id' || str_contains(strtolower($user->name), 'kepala stasiun') || str_contains(strtolower($user->name), 'kepala dinas')) {
                return redirect()->route('tvri.ecc.dashboard')->with('success', "Selamat Datang, Bapak/Ibu {$user->name}");
            }
            
            if ($user->username === 'admin.tu' || $user->username === 'tu' || $user->email === 'tu@tvri.go.id' || $user->hasRole('Tata Usaha') || str_contains(strtolower($user->name ?? ''), 'tata usaha')) {
                return redirect()->route('tvri.tu.dashboard')->with('success', "Selamat Datang di Bidang Tata Usaha (KTU), {$user->name}");
            }

            if ($user->username === 'berita' || $user->email === 'berita@tvri.go.id') {
                return redirect()->route('tvri.berita.dashboard')->with('success', "Selamat Datang di Newsroom, {$user->name}");
            }

            if ($user->username === 'program' || $user->email === 'program@tvri.go.id') {
                return redirect()->route('tvri.program.dashboard')->with('success', "Selamat Datang di Bidang Program, {$user->name}");
            }

            if ($user->username === 'konten.media.baru' || $user->username === 'kmb' || $user->email === 'kmb@tvri.go.id') {
                return redirect()->route('tvri.mbn.dashboard')->with('success', "Selamat Datang di Konten Media Baru, {$user->name}");
            }

            if ($user->username === 'promo' || $user->email === 'promo@tvri.go.id' || $user->hasRole('Promo') || str_contains(strtolower($user->name ?? ''), 'promo')) {
                return redirect()->route('tvri.promo.dashboard')->with('success', "Selamat Datang di Bidang Promo, {$user->name}");
            }

            if ($user->username === 'pengembangan.usaha' || $user->username === 'usaha' || $user->email === 'usaha@tvri.go.id') {
                return redirect()->route('tvri.usaha.dashboard')->with('success', "Selamat Datang di Bidang Pengembangan Usaha, {$user->name}");
            }

            if ($user->username === 'teknik' || $user->email === 'teknik@tvri.go.id') {
                return redirect()->route('tvri.teknik.dashboard')->with('success', "Selamat Datang di Bidang Teknik, {$user->name}");
            }

            if ($user->username === 'teknik.transmisi' || $user->username === 'transmisi' || $user->email === 'transmisi@tvri.go.id') {
                return redirect()->route('tvri.transmisi.dashboard')->with('success', "Selamat Datang di Bidang Teknik Transmisi, {$user->name}");
            }

            if ($user->username === 'umum' || $user->email === 'umum@tvri.go.id') {
                return redirect()->route('tvri.umum.dashboard')->with('success', "Selamat Datang di Bidang Umum, {$user->name}");
            }

            if ($user->username === 'keuangan' || $user->username === 'kasubag.keuangan' || $user->email === 'keuangan@tvri.go.id') {
                return redirect()->route('tvri.keuangan.dashboard')->with('success', "Selamat Datang di Bidang Keuangan, {$user->name}");
            }

            if ($user->username === 'kabid.kebudayaan') {
                return redirect()->route('kebudayaan.dashboard')->with('success', "Selamat Datang, {$user->name}");
            }

            return redirect()->route('admin.dashboard')->with('success', "Selamat Datang, {$user->name}");
        }

        RateLimiter::hit($this->throttleKey($request));
        ActivityLogger::log('LOGIN_FAILED', "Percobaan login gagal untuk identitas: {$loginInput}");

        throw ValidationException::withMessages([
            'login' => 'Username/Email atau Password yang Anda masukkan tidak sesuai.',
        ]);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            ActivityLogger::log('LOGOUT', "Pengguna " . Auth::user()->name . " telah keluar dari sistem.");
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }

    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'login' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
        ]);
    }

    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('login')) . '|' . $request->ip());
    }
}
