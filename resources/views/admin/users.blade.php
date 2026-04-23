@extends('layouts.admin')
@section('title', 'Kelola Data User')
@section('nav-title', 'Selamat Datang, ' . auth()->user()->name . '!')

@section('styles')
<style>
    .field-select {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239CA3AF'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1rem 1rem;
        padding-right: 2.5rem;
    }
</style>
@endsection

@section('content')
<div class="mb-6">
    {{-- Page Header --}}
    <div class="flex items-start justify-between mb-6 flex-wrap gap-4">
        <div>
            <h2 class="text-gray-800 font-bold text-xl">Manajemen Pengguna</h2>
            <p class="text-sm text-gray-400 mt-0.5">Kelola akses dan data pengguna sistem.</p>
        </div>

        <div class="flex items-center gap-3 flex-wrap sm:flex-nowrap">
            {{-- Search --}}
            <form method="GET" id="search-form">
                <input type="hidden" name="role" value="{{ $role }}">
                <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-4 py-2.5 shadow-sm focus-within:border-blue-400 focus-within:ring-2 focus-within:ring-blue-100 transition-all min-w-[220px]">
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari pengguna..."
                        class="flex-1 text-sm outline-none text-gray-700 bg-transparent placeholder-gray-400"
                        oninput="clearTimeout(window._st);window._st=setTimeout(()=>document.getElementById('search-form').submit(),450)">
                </div>
            </form>

            {{-- Tambah Button --}}
            <button onclick="openModal('modal-add')"
                class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm shadow-blue-200 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Tambah User
            </button>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="mb-5">
        <div class="flex gap-1 bg-gray-100 p-1 rounded-xl w-fit">
            @foreach(['semua' => 'Semua', 'siswa' => 'Siswa', 'admin' => 'Guru BK'] as $val => $lbl)
            <a href="{{ route('admin.users', ['role' => $val, 'search' => $search]) }}"
               class="px-4 py-1.5 rounded-lg text-sm font-medium transition-all whitespace-nowrap
                      {{ $role === $val ? 'bg-white text-blue-600 font-semibold shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                {{ $lbl }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-6 py-3.5 text-[11px] font-semibold text-gray-400 uppercase tracking-widest">Nama</th>
                        <th class="px-6 py-3.5 text-[11px] font-semibold text-gray-400 uppercase tracking-widest">Email</th>
                        <th class="px-6 py-3.5 text-[11px] font-semibold text-gray-400 uppercase tracking-widest">Kelas</th>
                        <th class="px-6 py-3.5 text-[11px] font-semibold text-gray-400 uppercase tracking-widest">Akses</th>
                        <th class="px-6 py-3.5 text-[11px] font-semibold text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/60 transition-colors">
                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold text-gray-800">{{ $u->name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-500">{{ $u->email }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($u->hasRole('siswa'))
                                <span class="text-sm text-gray-600">{{ $u->kelas ?? '—' }}</span>
                            @else
                                <span class="text-sm text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($u->hasRole('admin'))
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                    Guru BK
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                    Siswa
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="openEditUser({{ $u->id }},'{{ addslashes($u->name) }}','{{ $u->username }}','{{ $u->email }}','{{ $u->roles->first()?->name }}','{{ $u->kelas }}','{{ $u->no_telp }}')"
                                    class="p-1.5 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>
                                @if(auth()->id() !== $u->id)
                                <button onclick="openDeleteUser({{ $u->id }}, '{{ addslashes($u->name) }}')"
                                    class="p-1.5 rounded-lg text-gray-300 hover:text-red-500 hover:bg-red-50 transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-600">Tidak ada data</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Belum ada pengguna terdaftar.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $users->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@section('modals')

{{-- ── MODAL TAMBAH ── --}}
<div id="modal-add" class="hidden fixed inset-0 bg-black/30 backdrop-blur-[2px] z-[100] items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-800 text-base">Tambah Pengguna</h3>
            <button onclick="closeModal('modal-add')" class="p-1 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Body --}}
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="px-6 py-5 space-y-4">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" required
                            class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Username</label>
                        <input type="text" name="username" required
                            class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Email</label>
                    <input type="email" name="email" required
                        class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Role</label>
                    <select name="role" required class="field-select w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition cursor-pointer">
                        <option value="siswa">Siswa</option>
                        <option value="admin">Guru BK</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Tingkat Kelas</label>
                        <select name="tingkat_kelas" class="field-select w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-400 outline-none transition cursor-pointer">
                            <option value="">—</option>
                            <option value="X">X</option>
                            <option value="XI">XI</option>
                            <option value="XII">XII</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Jurusan</label>
                        <input type="text" name="jurusan" placeholder="Contoh: TKJ 1"
                            class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Password</label>
                        <input type="password" name="password" required minlength="6"
                            class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">No. Telepon</label>
                        <input type="text" name="no_telp"
                            class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition">
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
                <button type="button" onclick="closeModal('modal-add')"
                    class="px-5 py-2.5 text-sm font-medium text-gray-600 rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm shadow-blue-200 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── MODAL EDIT ── --}}
<div id="modal-edit" class="hidden fixed inset-0 bg-black/30 backdrop-blur-[2px] z-[100] items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-800 text-base">Edit Pengguna</h3>
            <button onclick="closeModal('modal-edit')" class="p-1 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="form-edit" method="POST">
            @csrf @method('PUT')
            <div class="px-6 py-5 space-y-4">

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Nama Pengguna</label>
                    <input type="text" name="name" id="eu-name" required
                        class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Email</label>
                    <input type="email" name="email" id="eu-email" required
                        class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Role</label>
                    <select name="role" id="eu-role" required class="field-select w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition cursor-pointer">
                        <option value="siswa">Siswa</option>
                        <option value="admin">Guru BK</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Tingkat Kelas</label>
                        <select name="tingkat_kelas" id="eu-tingkat" class="field-select w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-400 outline-none transition cursor-pointer">
                            <option value="">—</option>
                            <option value="X">X</option>
                            <option value="XI">XI</option>
                            <option value="XII">XII</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Jurusan</label>
                        <input type="text" name="jurusan" id="eu-jurusan" placeholder="Contoh: TKJ 1"
                            class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition">
                    </div>
                </div>

            </div>
            <input type="hidden" name="username" id="eu-username">
            <input type="hidden" name="no_telp" id="eu-notelp">

            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
                <button type="button" onclick="closeModal('modal-edit')"
                    class="px-5 py-2.5 text-sm font-medium text-gray-600 rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm shadow-blue-200 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── MODAL HAPUS ── --}}
<div id="modal-hapus" class="hidden fixed inset-0 bg-black/30 backdrop-blur-[2px] z-[100] items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm">
        <div class="px-6 pt-6 pb-5 text-center">
            <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="font-bold text-gray-800 text-base mb-1">Hapus Pengguna?</h3>
            <p id="hapus-label" class="text-sm text-gray-500"></p>
        </div>
        <form id="form-hapus" method="POST">
            @csrf @method('DELETE')
            <div class="flex gap-3 px-6 pb-6">
                <button type="button" onclick="closeModal('modal-hapus')"
                    class="flex-1 py-2.5 text-sm font-medium text-gray-600 rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 text-sm font-semibold text-white bg-red-500 hover:bg-red-600 rounded-xl transition">
                    Hapus
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
function openEditUser(id, name, username, email, role, kelas, notelp) {
    document.getElementById('form-edit').action = '/admin/users/' + id;
    document.getElementById('eu-name').value     = name;
    document.getElementById('eu-username').value = username;
    document.getElementById('eu-email').value    = email;
    document.getElementById('eu-role').value     = role;
    document.getElementById('eu-notelp').value   = notelp || '';

    var tingkat = '', jurusan = '';
    if (kelas) {
        var parts = kelas.trim().split(' ');
        if (['X', 'XI', 'XII'].includes(parts[0])) {
            tingkat = parts[0];
            jurusan = parts.slice(1).join(' ');
        } else {
            jurusan = kelas;
        }
    }
    document.getElementById('eu-tingkat').value = tingkat;
    document.getElementById('eu-jurusan').value = jurusan;
    openModal('modal-edit');
}
function openDeleteUser(id, name) {
    document.getElementById('form-hapus').action = '/admin/users/' + id;
    document.getElementById('hapus-label').textContent = 'Data pengguna "' + name + '" akan dihapus permanen.';
    openModal('modal-hapus');
}
</script>
@endsection
