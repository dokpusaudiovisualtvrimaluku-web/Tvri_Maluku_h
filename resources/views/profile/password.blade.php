@extends('layouts.app')

@section('title', 'Ubah Password')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Page Header & Tabs -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-xl font-extrabold text-[#00264A]">Keamanan Akun</h1>
            <p class="text-xs text-slate-500 mt-0.5">Perbarui kata sandi untuk melindungi akun Anda</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl border border-slate-200 transition-colors">
                Informasi Profil
            </a>
            <a href="{{ route('profile.password') }}" class="px-4 py-2 bg-[#003B70] text-white text-xs font-bold rounded-xl shadow-sm">
                Ubah Password
            </a>
        </div>
    </div>

    <!-- Password Form Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
        <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" required 
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#003B70] outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi Baru</label>
                <input type="password" name="password" required minlength="6" 
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#003B70] outline-none">
                <p class="text-[11px] text-slate-400 mt-1">Minimal 6 karakter dengan kombinasi huruf dan angka.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" required minlength="6" 
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#003B70] outline-none">
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-[#003B70] hover:bg-[#00264A] text-white text-xs font-bold rounded-xl shadow-sm transition-all">
                    Perbarui Kata Sandi
                </button>
            </div>
        </form>
    </div>

</div>
@endsection