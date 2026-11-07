@extends('layouts.app')

@section('title', 'Aktivitas Sistem & Audit Trail')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-[#00264A]">Aktivitas Sistem & Audit Trail</h1>
            <p class="text-xs text-slate-500 mt-0.5">Rekam jejak seluruh riwayat autentikasi dan mutasi data dalam sistem</p>
        </div>
    </div>

    <!-- Filters & Search Card -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="flex-grow grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="sm:col-span-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari keterangan aktivitas, IP, nama, atau username..." 
                       class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-[#003B70] outline-none">
            </div>
            <div class="flex items-center space-x-2">
                <select name="activity" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#003B70] outline-none">
                    <option value="">Semua Tipe Aktivitas</option>
                    @foreach($activityTypes as $key => $label)
                        <option value="{{ $key }}" {{ $activityFilter == $key ? 'selected' : '' }}>{{ $key }} ({{ $label }})</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-[#003B70] text-white rounded-xl text-xs font-bold hover:bg-[#00264A] transition-colors">
                    Filter
                </button>
            </div>
        </form>
        <div class="shrink-0">
            <form action="{{ route('admin.activity-logs.destroyAll') }}" method="POST" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin menghapus SEMUA riwayat aktivitas? Tindakan ini tidak dapat dibatalkan.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="flex items-center space-x-2 px-4 py-2 bg-rose-50 text-rose-600 border border-rose-200 rounded-xl text-xs font-bold hover:bg-rose-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span>Hapus Semua</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden mt-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[10px] font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4">Waktu</th>
                        <th class="py-3.5 px-4">Pengguna / Aktor</th>
                        <th class="py-3.5 px-4">Aktivitas</th>
                        <th class="py-3.5 px-4">Keterangan</th>
                        <th class="py-3.5 px-4">Alamat IP</th>
                        <th class="py-3.5 px-4">User Agent</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3.5 px-4 text-slate-500 whitespace-nowrap">
                                <p class="font-bold text-slate-700">{{ $log->created_at->format('d/m/Y H:i:s') }}</p>
                                <span class="text-[10px] text-slate-400">{{ $log->created_at->diffForHumans() }}</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($log->user)
                                    <div class="flex items-center space-x-2">
                                        <span class="font-bold text-[#00264A]">{{ $log->user->name }}</span>
                                        <span class="text-[10px] text-slate-400">({{ $log->user->username }})</span>
                                    </div>
                                @else
                                    <span class="text-slate-400 italic">Tamu / Sistem</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 text-[10px] font-extrabold uppercase rounded-full 
                                    @if(str_contains($log->activity, 'LOGIN') && !str_contains($log->activity, 'FAILED')) bg-emerald-100 text-emerald-800
                                    @elseif(str_contains($log->activity, 'FAILED') || str_contains($log->activity, 'DISABLED')) bg-rose-100 text-rose-800
                                    @elseif(str_contains($log->activity, 'LOGOUT')) bg-slate-100 text-slate-700
                                    @else bg-blue-100 text-[#003B70] @endif">
                                    {{ $log->activity }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 max-w-xs truncate text-slate-800" title="{{ $log->description }}">
                                {{ $log->description ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-600 whitespace-nowrap">{{ $log->ip ?? '127.0.0.1' }}</td>
                            <td class="py-3.5 px-4 max-w-xs truncate text-[11px] text-slate-400" title="{{ $log->user_agent }}">
                                {{ $log->user_agent ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <form action="{{ route('admin.activity-logs.destroy', $log->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus riwayat aktivitas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-100 rounded-lg transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-slate-400">Belum ada riwayat aktivitas sistem.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection