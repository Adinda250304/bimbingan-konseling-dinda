@extends('layouts.admin')
@section('title', 'Isi Hasil Konseling')
@section('nav-title', 'Isi Hasil & Saran')

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Tombol Kembali --}}
    <a href="{{ route('admin.konseling.show', $konseling) }}"
       class="inline-flex items-center gap-1.5 text-sm font-semibold text-on-surface-variant hover:text-primary transition mb-6">
        <span class="material-symbols-outlined text-[1.125rem]">arrow_back</span>
        Kembali ke Detail Konseling
    </a>

    {{-- Info Siswa Card --}}
    <div class="bg-surface-container-lowest rounded-2xl p-6 border border-surface-variant shadow-subtle mb-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-base shrink-0">
                {{ strtoupper(substr($konseling->siswa->name, 0, 2)) }}
            </div>
            <div>
                <h4 class="font-headline-sm text-headline-sm font-semibold text-on-surface">{{ $konseling->siswa->name }}</h4>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Kelas {{ $konseling->siswa->kelas ?? '—' }} • Sesi Offline</p>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 pt-4 border-t border-outline-variant/30">
            <div class="bg-surface-container-low rounded-xl p-3.5 border border-outline-variant/20">
                <p class="text-[0.6875rem] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Topik / Kategori Masalah</p>
                <p class="text-sm font-semibold text-on-surface">{{ $konseling->jenis_masalah }}</p>
            </div>
            @if($konseling->tanggal_konseling)
            <div class="bg-surface-container-low rounded-xl p-3.5 border border-outline-variant/20">
                <p class="text-[0.6875rem] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Waktu Sesi</p>
                <p class="text-sm font-semibold text-on-surface">
                    {{ $konseling->tanggal_konseling->translatedFormat('d F Y') }} • pukul {{ \Carbon\Carbon::parse($konseling->jam_konseling)->format('H:i') }} WIB
                </p>
            </div>
            @endif
        </div>
        @if($konseling->deskripsi_masalah)
        <div class="bg-surface-container-low rounded-xl p-4 border border-outline-variant/20 mt-4">
            <p class="text-[0.6875rem] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Deskripsi Masalah</p>
            <p class="text-sm text-on-surface leading-relaxed">{{ $konseling->deskripsi_masalah }}</p>
        </div>
        @endif
    </div>

    {{-- Form Hasil Card --}}
    <div class="bg-surface-container-lowest rounded-2xl p-8 border border-surface-variant shadow-subtle">
        <h3 class="font-headline-md text-headline-md text-primary font-bold mb-6">Catatan & Hasil Sesi</h3>

        @if($errors->any())
        <div class="mb-5 bg-error-container/20 border border-error-container/30 text-error rounded-xl px-4 py-3 text-sm font-medium">
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('admin.konseling.hasil.store', $konseling) }}" method="POST" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <label class="block font-body-sm text-body-sm font-bold text-on-surface-variant uppercase tracking-wider">
                    Catatan Konselor
                    <span class="text-error ml-0.5">*</span>
                </label>
                <p class="text-xs text-on-surface-variant">Tuliskan jalannya sesi, kondisi siswa, dan permasalahan yang dibahas secara lengkap.</p>
                <textarea name="catatan_konselor" rows="5" required
                    placeholder="Contoh: Siswa terlihat cemas saat membahas nilai akademik. Kami mendiskusikan strategi belajar yang lebih efektif..."
                    class="w-full px-4 py-3 border border-outline-variant/60 rounded-xl text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary transition bg-surface resize-none">{{ old('catatan_konselor', $konseling->hasil?->catatan_konselor) }}</textarea>
            </div>

            <div class="space-y-2">
                <label class="block font-body-sm text-body-sm font-bold text-on-surface-variant uppercase tracking-wider">
                    Saran untuk Siswa
                    <span class="text-error ml-0.5">*</span>
                </label>
                <p class="text-xs text-on-surface-variant">Tuliskan rekomendasi dan saran konkret yang diberikan kepada siswa.</p>
                <textarea name="saran" rows="4" required
                    placeholder="Contoh: Disarankan untuk membuat jadwal belajar harian, mengurangi penggunaan gadget di malam hari..."
                    class="w-full px-4 py-3 border border-outline-variant/60 rounded-xl text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary transition bg-surface resize-none">{{ old('saran', $konseling->hasil?->saran) }}</textarea>
            </div>

            <div class="pt-4 border-t border-outline-variant/30">
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="buat_tl" name="buat_tl" value="1" 
                        class="w-4 h-4 text-primary focus:ring-primary border-outline-variant rounded cursor-pointer" 
                        onchange="document.getElementById('tl-form').classList.toggle('hidden', !this.checked)">
                    <label for="buat_tl" class="text-sm font-semibold text-on-surface cursor-pointer select-none">
                        Buat Surat Tindak Lanjut Resmi (Opsional)
                    </label>
                </div>
                
                <div id="tl-form" class="hidden space-y-4 bg-primary-container/5 p-5 rounded-2xl border border-primary-container/20 mt-4">
                    <div class="space-y-2">
                        <label class="block text-[0.6875rem] font-bold text-on-surface-variant uppercase tracking-wider">Jenis Tindak Lanjut</label>
                        <select name="tl_jenis" class="w-full bg-surface border border-outline-variant/60 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all text-sm outline-none cursor-pointer">
                            <option value="pemanggilan_ortu">Pemanggilan Orang Tua</option>
                            <option value="mediasi">Mediasi</option>
                            <option value="rujukan">Rujukan Profesional</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[0.6875rem] font-bold text-on-surface-variant uppercase tracking-wider">Catatan Detail (Untuk Surat)</label>
                        <textarea name="tl_catatan" rows="3" placeholder="Tuliskan detail catatan / alasan tindak lanjut diputuskan..."
                            class="w-full bg-surface border border-outline-variant/60 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all text-sm outline-none resize-none"></textarea>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 pt-4 border-t border-outline-variant/30">
                <a href="{{ route('admin.jadwal') }}"
                   class="flex-1 text-center py-3 border border-outline text-on-surface-variant hover:bg-surface-container-low rounded-xl transition font-semibold text-sm">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 py-3 bg-primary hover:bg-primary-container text-white rounded-xl transition font-bold text-sm shadow-md">
                    Simpan & Tandai Selesai
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
