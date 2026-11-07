@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Page Header & Tabs -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-xl font-extrabold text-[#00264A]">Profil Pengguna</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola identitas akun dan informasi kontak Anda</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-[#003B70] text-white text-xs font-bold rounded-xl shadow-sm">
                Informasi Profil
            </a>
            <a href="{{ route('profile.password') }}" class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl border border-slate-200 transition-colors">
                Ubah Password
            </a>
        </div>
    </div>

    <!-- Profile Form Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Avatar Section -->
            <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-6 pb-6 border-b border-slate-100">
                <img src="{{ $user->avatar_url }}" alt="Avatar" class="w-20 h-20 rounded-2xl object-cover ring-4 ring-[#003B70]/20 shadow-md">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Foto Profil</label>
                    <input type="file" name="avatar" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#003B70] hover:file:bg-blue-100 cursor-pointer">
                    <p class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG, WEBP. Maksimal 2 MB.</p>
                </div>
            </div>

            <!-- Readonly Identity Details -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 bg-[#EAF4FB] rounded-2xl border border-blue-200/60 text-xs">
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase">Username</p>
                    <p class="font-mono font-bold text-[#00264A] mt-0.5">{{ $user->username ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase">Role Akun</p>
                    <span class="inline-block mt-0.5 px-2 py-0.5 text-[10px] font-extrabold uppercase rounded-full bg-[#003B70] text-white">
                        {{ $user->role_name }}
                    </span>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase">Login Terakhir</p>
                    <p class="font-bold text-slate-700 mt-0.5">{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : '-' }}</p>
                </div>
            </div>

            <!-- Editable Fields -->
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#003B70] outline-none font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#003B70] outline-none font-medium">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-[#003B70] hover:bg-[#00264A] text-white text-xs font-bold rounded-xl shadow-sm transition-all">
                    Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </div>

</div>
@endsection