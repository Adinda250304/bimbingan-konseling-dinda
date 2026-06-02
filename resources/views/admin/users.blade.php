@extends('layouts.admin')
@section('title', 'Kelola Data Pengguna')
@section('nav-title', 'Kelola Data Pengguna')

@section('styles')
<style>
    .shadow-subtle {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }
</style>
@endsection

@section('content')
<!-- Header Section -->
<div class="flex justify-between items-start mb-10 flex-wrap gap-4">
    <div>
        <h1 class="font-display text-3xl font-bold text-on-surface mb-2">Kelola Data Pengguna</h1>
        <p class="text-on-surface-variant text-sm">Kelola informasi guru, siswa, dan orang tua dalam satu tempat yang tenang.</p>
    </div>
    <button class="bg-primary text-white px-5 py-2.5 rounded-xl font-semibold flex items-center gap-2 hover:bg-primary-container transition-colors shadow-subtle" 
            onclick="openAddModal()">
        <span class="material-symbols-outlined">add</span>
        Tambah Pengguna Baru
    </button>
</div>

<!-- Filter Panel -->
<form action="{{ route('admin.users') }}" method="GET" class="grid grid-cols-12 gap-gutter mb-8">
    <div class="col-span-12 md:col-span-8 relative">
        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[1.25rem]">search</span>
        <input name="search" value="{{ $search }}" 
               class="w-full bg-white border border-outline-variant/50 rounded-xl py-3 pl-12 pr-4 focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none text-sm" 
               placeholder="Cari berdasarkan nama, email, atau ID..." type="text"
               oninput="clearTimeout(window._st);window._st=setTimeout(()=>this.form.submit(),450)"/>
    </div>
    <div class="col-span-12 md:col-span-4">
        <select name="role" class="w-full bg-white border border-outline-variant/50 rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none text-sm text-on-surface-variant"
                onchange="this.form.submit()">
            <option value="semua" {{ $role === 'semua' ? 'selected' : '' }}>Semua Peran</option>
            <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin / Guru BK</option>
            <option value="wali_kelas" {{ $role === 'wali_kelas' ? 'selected' : '' }}>Wali Kelas</option>
            <option value="siswa" {{ $role === 'siswa' ? 'selected' : '' }}>Siswa</option>
        </select>
    </div>
</form>

<!-- Premium Table Card -->
<div class="bg-surface rounded-2xl border border-outline-variant/30 shadow-subtle overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-surface-variant border-b border-outline-variant/30">
                    <th class="px-6 py-4 text-[0.6875rem] font-semibold text-on-surface-variant uppercase tracking-widest">Pengguna</th>
                    <th class="px-6 py-4 text-[0.6875rem] font-semibold text-on-surface-variant uppercase tracking-widest">Peran</th>
                    <th class="px-6 py-4 text-[0.6875rem] font-semibold text-on-surface-variant uppercase tracking-widest">Departemen / Relasi</th>
                    <th class="px-6 py-4 text-[0.6875rem] font-semibold text-on-surface-variant uppercase tracking-widest text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/10">
                @forelse($users as $u)
                    <tr class="hover:bg-background/50 transition-colors">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-primary-container/10 flex items-center justify-center text-primary-container font-bold text-sm">
                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-on-surface text-sm">{{ $u->name }}</div>
                                    <div class="text-[0.75rem] text-on-surface-variant">{{ $u->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            @if($u->hasRole('admin'))
                                <span class="px-3 py-1 rounded-full text-[0.6875rem] font-bold bg-primary/10 text-primary uppercase">Guru BK</span>
                            @elseif($u->hasRole('wali_kelas'))
                                <span class="px-3 py-1 rounded-full text-[0.6875rem] font-bold bg-amber-100 text-amber-700 uppercase">Wali Kelas</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-[0.6875rem] font-bold bg-blue-100 text-blue-700 uppercase">Siswa</span>
                            @endif
                        </td>
                        <td class="px-6 py-5">
                            @if($u->hasRole('admin'))
                                <span class="text-sm text-on-surface-variant">Konseling & Kesiswaan</span>
                            @elseif($u->hasRole('wali_kelas'))
                                <span class="text-sm text-on-surface-variant">Wali {{ $u->kelas ?? 'Kelas' }}</span>
                            @else
                                <span class="text-sm text-on-surface-variant">{{ $u->kelas ?? '—' }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <button class="w-8 h-8 flex items-center justify-center rounded-full text-primary hover:bg-primary/10 transition-all"
                                        onclick="openEditModal({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ $u->username }}', '{{ $u->email }}', '{{ $u->roles->first()?->name }}', '{{ $u->kelas }}', '{{ $u->no_telp }}')">
                                    <span class="material-symbols-outlined text-[1.25rem]">edit</span>
                                </button>
                                @if(auth()->id() !== $u->id)
                                    <button class="w-8 h-8 flex items-center justify-center rounded-full text-error hover:bg-error/10 transition-all"
                                            onclick="openDeleteModal({{ $u->id }}, '{{ addslashes($u->name) }}')">
                                        <span class="material-symbols-outlined text-[1.25rem]">delete</span>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-on-surface-variant font-medium">
                            Tidak ada pengguna ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-5 border-t border-outline-variant/20 flex justify-between items-center bg-white flex-wrap gap-4">
        <p class="text-[0.8125rem] text-on-surface-variant">
            Menampilkan <span class="font-bold text-on-surface">{{ $users->firstItem() ?? 0 }}</span> - <span class="font-bold text-on-surface">{{ $users->lastItem() ?? 0 }}</span> dari <span class="font-bold text-on-surface">{{ $users->total() }}</span> pengguna
        </p>
        @if($users->hasPages())
            <div class="flex gap-1">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('modals')
<!-- Modal: Tambah/Edit User -->
<div class="hidden fixed inset-0 z-[100] flex items-center justify-center px-4" id="userModal">
    <div class="absolute inset-0 bg-on-background/40 backdrop-blur-md" onclick="closeUserModal()"></div>
    <div class="relative bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden border border-outline-variant/20">
        <div class="p-6 border-b border-outline-variant/10">
            <h3 class="font-display text-xl font-bold text-on-surface" id="modalTitle">Tambah Pengguna Baru</h3>
        </div>
        <form id="userForm" class="p-6 space-y-4" method="POST">
            @csrf
            <div id="methodContainer"></div>
            
            <div>
                <label class="block text-sm font-semibold text-on-surface-variant mb-1.5">Nama Lengkap</label>
                <input id="userName" name="name" required class="w-full bg-background border-none rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary outline-none transition-all text-sm" placeholder="Masukkan nama lengkap..." type="text"/>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-on-surface-variant mb-1.5">Peran</label>
                    <select id="userRole" name="role" required class="w-full bg-background border-none rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary outline-none transition-all text-sm cursor-pointer" onchange="toggleRoleFields()">
                        <option value="siswa">Siswa</option>
                        <option value="wali_kelas">Wali Kelas</option>
                        <option value="admin">Guru BK</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-on-surface-variant mb-1.5">ID Identitas</label>
                    <input id="userUsername" name="username" required class="w-full bg-background border-none rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary outline-none transition-all text-sm" placeholder="NIP / NISN" type="text"/>
                </div>
            </div>

            <!-- Fields only for student (Siswa) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="siswaFields">
                <div>
                    <label class="block text-sm font-semibold text-on-surface-variant mb-1.5">Tingkat Kelas</label>
                    <select id="userTingkat" name="tingkat_kelas" class="w-full bg-background border-none rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary outline-none transition-all text-sm cursor-pointer">
                        <option value="">—</option>
                        <option value="X">X</option>
                        <option value="XI">XI</option>
                        <option value="XII">XII</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-on-surface-variant mb-1.5">Jurusan</label>
                    <input id="userJurusan" name="jurusan" class="w-full bg-background border-none rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary outline-none transition-all text-sm" placeholder="Contoh: DKV 1, TKJ 1" type="text"/>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-on-surface-variant mb-1.5">Email</label>
                <input id="userEmail" name="email" required class="w-full bg-background border-none rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary outline-none transition-all text-sm" placeholder="email@contoh.com" type="email"/>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-on-surface-variant mb-1.5">Password</label>
                    <input id="userPassword" name="password" class="w-full bg-background border-none rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary outline-none transition-all text-sm" placeholder="••••••••" type="password" minlength="8" maxlength="20"/>
                    <p class="text-[0.625rem] text-on-surface-variant mt-1" id="pwHelp">8-20 karakter, huruf besar, kecil, angka & spesial.</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-on-surface-variant mb-1.5">No. Telepon</label>
                    <input id="userNotelp" name="no_telp" class="w-full bg-background border-none rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary outline-none transition-all text-sm" placeholder="Contoh: 0812345678" type="text"/>
                </div>
            </div>
            
            <div class="pt-6 flex gap-3">
                <button class="flex-1 py-3 border border-outline-variant rounded-xl font-bold text-on-surface-variant hover:bg-background transition-all" onclick="closeUserModal()" type="button">
                    Batal
                </button>
                <button class="flex-1 py-3 bg-primary text-white rounded-xl font-bold hover:bg-primary-container transition-all shadow-subtle" type="submit">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Hapus User -->
<div id="deleteModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-on-background/40 backdrop-blur-md" onclick="closeDeleteModal()"></div>
    <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-xl overflow-hidden border border-outline-variant/20">
        <div class="px-6 pt-6 pb-5 text-center">
            <div class="w-12 h-12 bg-error-container/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-error text-[1.75rem]">delete</span>
            </div>
            <h3 class="font-bold text-on-surface text-base mb-1">Hapus Pengguna?</h3>
            <p id="deleteLabel" class="text-sm text-on-surface-variant"></p>
        </div>
        <form id="deleteForm" method="POST">
            @csrf @method('DELETE')
            <div class="flex gap-3 px-6 pb-6">
                <button type="button" onclick="closeDeleteModal()"
                    class="flex-1 py-2.5 text-sm font-semibold text-on-surface-variant rounded-xl border border-outline-variant hover:bg-background transition-all">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 text-sm font-semibold text-white bg-error hover:bg-error/80 rounded-xl transition-all">
                    Hapus
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleRoleFields() {
    var role = document.getElementById('userRole').value;
    var fields = document.getElementById('siswaFields');
    if (role === 'siswa' || role === 'wali_kelas') {
        fields.classList.remove('hidden');
    } else {
        fields.classList.add('hidden');
    }
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Pengguna Baru';
    document.getElementById('userForm').action = "{{ route('admin.users.store') }}";
    document.getElementById('methodContainer').innerHTML = '';
    
    // Clear inputs
    document.getElementById('userName').value = '';
    document.getElementById('userRole').value = 'siswa';
    document.getElementById('userUsername').value = '';
    document.getElementById('userEmail').value = '';
    document.getElementById('userPassword').value = '';
    document.getElementById('userPassword').required = true;
    document.getElementById('pwHelp').textContent = '8-20 karakter, huruf besar, kecil, angka & spesial.';
    document.getElementById('userNotelp').value = '';
    document.getElementById('userTingkat').value = '';
    document.getElementById('userJurusan').value = '';
    
    toggleRoleFields();
    openModal('userModal');
}

function openEditModal(id, name, username, email, role, kelas, notelp) {
    document.getElementById('modalTitle').textContent = 'Edit Pengguna';
    document.getElementById('userForm').action = "/admin/users/" + id;
    document.getElementById('methodContainer').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    
    // Populate inputs
    document.getElementById('userName').value = name;
    document.getElementById('userRole').value = role || 'siswa';
    document.getElementById('userUsername').value = username;
    document.getElementById('userEmail').value = email;
    document.getElementById('userPassword').value = '';
    document.getElementById('userPassword').required = false;
    document.getElementById('pwHelp').textContent = 'Biarkan kosong jika tidak ingin mengubah password. Jika diisi: 8-20 karakter, huruf besar, kecil, angka & spesial.';
    document.getElementById('userNotelp').value = notelp || '';
    
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
    document.getElementById('userTingkat').value = tingkat;
    document.getElementById('userJurusan').value = jurusan;
    
    toggleRoleFields();
    openModal('userModal');
}

function closeUserModal() {
    closeModal('userModal');
}

function openDeleteModal(id, name) {
    document.getElementById('deleteForm').action = "/admin/users/" + id;
    document.getElementById('deleteLabel').textContent = 'Data pengguna "' + name + '" akan dihapus secara permanen.';
    openModal('deleteModal');
}

function closeDeleteModal() {
    closeModal('deleteModal');
}
</script>
@endsection
