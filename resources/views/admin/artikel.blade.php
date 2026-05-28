@extends('layouts.admin')
@section('title', 'Kelola Artikel')
@section('nav-title', 'Kelola Artikel')

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
        <h1 class="font-display text-3xl font-bold text-on-surface mb-2">Kelola Artikel</h1>
        <p class="text-on-surface-variant text-sm">Tulis, edit, dan terbitkan artikel edukatif serta kesehatan mental untuk siswa.</p>
    </div>
    <button class="bg-primary text-white px-5 py-2.5 rounded-xl font-semibold flex items-center gap-2 hover:bg-primary-container transition-colors shadow-subtle cursor-pointer" 
            onclick="openAddModal()">
        <span class="material-symbols-outlined">add</span>
        Tambah Artikel Baru
    </button>
</div>

<!-- Premium Table Card -->
<div class="bg-surface rounded-2xl border border-outline-variant/30 shadow-subtle overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-surface-variant border-b border-outline-variant/30">
                    <th class="px-6 py-4 text-[11px] font-semibold text-on-surface-variant uppercase tracking-widest">Artikel</th>
                    <th class="px-6 py-4 text-[11px] font-semibold text-on-surface-variant uppercase tracking-widest">Penulis</th>
                    <th class="px-6 py-4 text-[11px] font-semibold text-on-surface-variant uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4 text-[11px] font-semibold text-on-surface-variant uppercase tracking-widest">Tanggal Dibuat</th>
                    <th class="px-6 py-4 text-[11px] font-semibold text-on-surface-variant uppercase tracking-widest text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/10">
                @forelse($artikels as $art)
                    <tr class="hover:bg-background/50 transition-colors">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-12 rounded-lg overflow-hidden flex-shrink-0 bg-surface-variant">
                                    <img src="{{ $art->thumbnail }}" alt="Thumbnail" class="w-full h-full object-cover">
                                </div>
                                <div class="max-w-xs md:max-w-md">
                                    <div class="font-bold text-on-surface text-sm truncate" title="{{ $art->judul }}">{{ $art->judul }}</div>
                                    <div class="text-[12px] text-on-surface-variant truncate">{{ Str::limit(strip_tags($art->konten), 60) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-sm font-semibold text-on-surface">{{ $art->author->name }}</span>
                        </td>
                        <td class="px-6 py-5">
                            @if($art->is_published)
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-primary/10 text-primary uppercase">Published</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-neutral-100 text-neutral-600 uppercase">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-sm text-on-surface-variant">{{ $art->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <button class="w-8 h-8 flex items-center justify-center rounded-full text-primary hover:bg-primary/10 transition-all cursor-pointer"
                                        onclick="openEditModal({{ $art->id }}, '{{ addslashes($art->judul) }}', '{{ addslashes($art->konten) }}', '{{ $art->thumbnail }}', {{ $art->is_published ? 1 : 0 }})">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>
                                <button class="w-8 h-8 flex items-center justify-center rounded-full text-error hover:bg-error/10 transition-all cursor-pointer"
                                        onclick="openDeleteModal({{ $art->id }}, '{{ addslashes($art->judul) }}')">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-on-surface-variant font-medium">
                            Belum ada artikel yang ditambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-5 border-t border-outline-variant/20 flex justify-between items-center bg-white flex-wrap gap-4">
        <p class="text-[13px] text-on-surface-variant">
            Menampilkan <span class="font-bold text-on-surface">{{ $artikels->firstItem() ?? 0 }}</span> - <span class="font-bold text-on-surface">{{ $artikels->lastItem() ?? 0 }}</span> dari <span class="font-bold text-on-surface">{{ $artikels->total() }}</span> artikel
        </p>
        @if($artikels->hasPages())
            <div class="flex gap-1">
                {{ $artikels->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('modals')
<!-- Modal: Tambah/Edit Artikel -->
<div class="hidden fixed inset-0 z-[100] flex items-center justify-center px-4" id="artikelModal">
    <div class="absolute inset-0 bg-on-background/40 backdrop-blur-md" onclick="closeArtikelModal()"></div>
    <div class="relative bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden border border-outline-variant/20">
        <div class="p-6 border-b border-outline-variant/10">
            <h3 class="font-display text-xl font-bold text-on-surface" id="modalTitle">Tambah Artikel Baru</h3>
        </div>
        <form id="artikelForm" class="p-6 space-y-4" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="methodContainer"></div>
            
            <div>
                <label class="block text-sm font-semibold text-on-surface-variant mb-1.5">Judul Artikel</label>
                <input id="artikelJudul" name="judul" required class="w-full bg-background border border-outline-variant/30 rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary outline-none transition-all text-sm" placeholder="Masukkan judul menarik..." type="text"/>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-on-surface-variant mb-1.5">Thumbnail Gambar</label>
                <input id="artikelThumbnail" name="thumbnail" class="w-full bg-background border border-outline-variant/30 rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary outline-none transition-all text-sm file:mr-4 file:py-1.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20" type="file" accept="image/*"/>
                <p class="text-[10px] text-on-surface-variant mt-1" id="thumbnailHelp">Pilih file gambar (JPG, PNG, JPEG, GIF) maksimal 2MB. Biarkan kosong jika tidak ingin mengubah thumbnail.</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-on-surface-variant mb-1.5">Konten Artikel</label>
                <textarea id="artikelKonten" name="konten" required rows="8" class="w-full bg-background border border-outline-variant/30 rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary outline-none transition-all text-sm leading-relaxed" placeholder="Tulis konten artikel di sini..."></textarea>
            </div>

            <div class="flex items-center gap-3 py-2">
                <input class="w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary transition-all cursor-pointer" 
                       id="artikelIsPublished" 
                       name="is_published" 
                       value="1"
                       type="checkbox"/>
                <label class="font-body-sm text-body-sm text-on-surface-variant cursor-pointer select-none" for="artikelIsPublished">Terbitkan artikel ini agar langsung dapat dibaca oleh siswa</label>
            </div>
            
            <div class="pt-4 border-t border-outline-variant/10 flex gap-3">
                <button class="flex-1 py-3 border border-outline-variant rounded-xl font-bold text-on-surface-variant hover:bg-background transition-all cursor-pointer" onclick="closeArtikelModal()" type="button">
                    Batal
                </button>
                <button class="flex-1 py-3 bg-primary text-white rounded-xl font-bold hover:bg-primary-container transition-all shadow-subtle cursor-pointer" type="submit">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Hapus Artikel -->
<div id="deleteModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-on-background/40 backdrop-blur-md" onclick="closeDeleteModal()"></div>
    <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-xl overflow-hidden border border-outline-variant/20">
        <div class="px-6 pt-6 pb-5 text-center">
            <div class="w-12 h-12 bg-error-container/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-error text-[28px]">delete</span>
            </div>
            <h3 class="font-bold text-on-surface text-base mb-1">Hapus Artikel?</h3>
            <p id="deleteLabel" class="text-sm text-on-surface-variant"></p>
        </div>
        <form id="deleteForm" method="POST">
            @csrf @method('DELETE')
            <div class="flex gap-3 px-6 pb-6">
                <button type="button" onclick="closeDeleteModal()"
                    class="flex-1 py-2.5 text-sm font-semibold text-on-surface-variant rounded-xl border border-outline-variant hover:bg-background transition-all cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 text-sm font-semibold text-white bg-error hover:bg-error/80 rounded-xl transition-all cursor-pointer">
                    Hapus
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Artikel Baru';
    document.getElementById('artikelForm').action = "{{ route('admin.artikel.store') }}";
    document.getElementById('methodContainer').innerHTML = '';
    
    // Clear inputs
    document.getElementById('artikelJudul').value = '';
    document.getElementById('artikelThumbnail').value = '';
    document.getElementById('artikelKonten').value = '';
    document.getElementById('artikelIsPublished').checked = true; // publish by default for convenience
    
    openModal('artikelModal');
}

function openEditModal(id, judul, konten, thumbnail, isPublished) {
    document.getElementById('modalTitle').textContent = 'Edit Artikel';
    document.getElementById('artikelForm').action = "/admin/artikel/" + id;
    document.getElementById('methodContainer').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    
    // Populate inputs
    document.getElementById('artikelJudul').value = judul;
    document.getElementById('artikelThumbnail').value = ''; // file input cannot be programmatically set
    document.getElementById('artikelKonten').value = konten;
    document.getElementById('artikelIsPublished').checked = (isPublished == 1);
    
    openModal('artikelModal');
}

function closeArtikelModal() {
    closeModal('artikelModal');
}

function openDeleteModal(id, judul) {
    document.getElementById('deleteForm').action = "/admin/artikel/" + id;
    document.getElementById('deleteLabel').textContent = 'Artikel "' + judul + '" akan dihapus secara permanen.';
    openModal('deleteModal');
}

function closeDeleteModal() {
    closeModal('deleteModal');
}
</script>
@endsection
