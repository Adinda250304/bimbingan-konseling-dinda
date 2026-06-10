@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('nav-title', 'Dashboard Admin')

@section('content')
<!-- Welcome Banner -->
<section class="mb-section-margin bg-surface-container-lowest rounded-2xl p-8 border border-surface-variant shadow-subtle relative overflow-hidden flex flex-col md:flex-row md:items-center gap-6">
    <div class="absolute right-0 top-0 w-full md:w-1/2 h-full bg-gradient-to-l from-secondary-container/30 to-transparent pointer-events-none"></div>
    <div class="relative z-10 max-w-2xl">
        @php
            $hour = now()->format('H');
            if ($hour < 11) {
                $greeting = 'Selamat Pagi';
            } elseif ($hour < 15) {
                $greeting = 'Selamat Siang';
            } elseif ($hour < 18) {
                $greeting = 'Selamat Sore';
            } else {
                $greeting = 'Selamat Malam';
            }
        @endphp
        <h2 class="font-headline-lg text-headline-lg text-primary mb-2">{{ $greeting }}, {{ auth()->user()->name }}!</h2>
        <p class="text-body-md text-on-surface-variant">Anda berada di Dashboard Utama. Pantau statistik dan kelola pengguna dari sini.</p>
    </div>
</section>

<!-- Key Metrics -->
<section class="grid grid-cols-1 md:grid-cols-3 gap-card-gap mb-section-margin">
    <div class="bg-surface-container-lowest rounded-2xl p-6 border border-surface-variant shadow-subtle flex items-center gap-4">
        <div class="p-4 bg-blue-100 text-blue-700 rounded-xl">
            <span class="material-symbols-outlined text-[2rem]">school</span>
        </div>
        <div>
            <p class="font-body-sm text-body-sm text-on-surface-variant mb-1">Total Siswa</p>
            <p class="font-headline-md text-headline-md text-on-surface">{{ $total_siswa }}</p>
        </div>
    </div>
    <div class="bg-surface-container-lowest rounded-2xl p-6 border border-surface-variant shadow-subtle flex items-center gap-4">
        <div class="p-4 bg-purple-100 text-purple-700 rounded-xl">
            <span class="material-symbols-outlined text-[2rem]">psychology</span>
        </div>
        <div>
            <p class="font-body-sm text-body-sm text-on-surface-variant mb-1">Total Guru BK</p>
            <p class="font-headline-md text-headline-md text-on-surface">{{ $total_guru_bk }}</p>
        </div>
    </div>
    <div class="bg-surface-container-lowest rounded-2xl p-6 border border-surface-variant shadow-subtle flex items-center gap-4">
        <div class="p-4 bg-amber-100 text-amber-700 rounded-xl">
            <span class="material-symbols-outlined text-[2rem]">group</span>
        </div>
        <div>
            <p class="font-body-sm text-body-sm text-on-surface-variant mb-1">Total Wali Kelas</p>
            <p class="font-headline-md text-headline-md text-on-surface">{{ $total_wali_kelas }}</p>
        </div>
    </div>
</section>

<!-- Recent Users List -->
<section class="mb-section-margin">
    <h3 class="font-headline-sm text-headline-sm text-on-surface mb-4">Pengguna Baru Terdaftar</h3>
    <div class="bg-surface rounded-2xl border border-outline-variant/30 shadow-subtle overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-surface-variant border-b border-outline-variant/30">
                        <th class="px-6 py-4 text-[0.6875rem] font-semibold text-on-surface-variant uppercase tracking-widest">Pengguna</th>
                        <th class="px-6 py-4 text-[0.6875rem] font-semibold text-on-surface-variant uppercase tracking-widest">Peran</th>
                        <th class="px-6 py-4 text-[0.6875rem] font-semibold text-on-surface-variant uppercase tracking-widest">Waktu Daftar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse($recent_users as $u)
                        <tr class="hover:bg-background/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-on-surface text-sm">{{ $u->name }}</div>
                                <div class="text-[0.75rem] text-on-surface-variant">{{ $u->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($u->hasRole('admin'))
                                    <span class="px-3 py-1 rounded-full text-[0.6875rem] font-bold bg-purple-100 text-purple-700 uppercase">Admin</span>
                                @elseif($u->hasRole('guru_bk'))
                                    <span class="px-3 py-1 rounded-full text-[0.6875rem] font-bold bg-primary/10 text-primary uppercase">Guru BK</span>
                                @elseif($u->hasRole('wali_kelas'))
                                    <span class="px-3 py-1 rounded-full text-[0.6875rem] font-bold bg-amber-100 text-amber-700 uppercase">Wali Kelas</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-[0.6875rem] font-bold bg-blue-100 text-blue-700 uppercase">Siswa</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-on-surface-variant">
                                {{ $u->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-on-surface-variant">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
