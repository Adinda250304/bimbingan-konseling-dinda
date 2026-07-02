@extends('layouts.dashboard')
@section('title', 'Data Siswa')
@section('nav-title', 'Data Siswa')

@section('content')
<div class="bg-surface rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden mb-6">
    <div class="p-6 border-b border-outline-variant/30 bg-surface-container-lowest">
        <h2 class="text-xl font-bold text-on-surface">Data Siswa Kelas {{ $kelas }}</h2>
        <p class="text-sm text-on-surface-variant mt-1">Daftar siswa di bawah perwalian Anda. Klik "Rujuk ke BK" jika siswa membutuhkan penanganan khusus.</p>
    </div>

    @if(session('success'))
        <div class="p-4 m-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low text-on-surface-variant text-xs uppercase font-bold tracking-wider">
                    <th class="p-4 px-6">Nama Siswa</th>
                    <th class="p-4 px-6">Email / Username</th>
                    <th class="p-4 px-6 text-center">Status</th>
                    <th class="p-4 px-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
                @forelse($siswas as $siswa)
                <tr class="hover:bg-surface-container-lowest transition-colors">
                    <td class="p-4 px-6">
                        <div class="font-bold text-on-surface">{{ $siswa->name }}</div>
                    </td>
                    <td class="p-4 px-6">
                        <div class="text-sm text-on-surface-variant">{{ $siswa->email }}</div>
                        <div class="text-xs text-outline">{{ $siswa->username }}</div>
                    </td>
                    <td class="p-4 px-6 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Aktif
                        </span>
                    </td>
                    <td class="p-4 px-6 text-right">
                        <a href="{{ route('wali.rujuk', $siswa->id) }}" class="inline-flex items-center justify-center gap-2 bg-gray-500 text-white text-sm font-bold py-2 px-4 rounded-xl hover:bg-gray-600 transition-all focus:ring-4 focus:ring-error/20 active:scale-95 shadow-sm">
                            <span class="material-symbols-outlined text-[1.125rem]">warning</span>
                            Rujuk ke BK
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-8 text-center text-on-surface-variant">
                        Belum ada data siswa di kelas ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
