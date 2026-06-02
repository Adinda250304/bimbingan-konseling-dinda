@extends('layouts.dashboard')
@section('title', 'Rujuk Siswa')
@section('nav-title', 'Rujuk Siswa ke BK')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('wali.siswa') }}" class="inline-flex items-center gap-2 text-primary hover:underline font-bold text-sm">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Kembali ke Data Siswa
        </a>
    </div>

    <div class="bg-surface rounded-3xl shadow-sm border border-outline-variant/30 overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-outline-variant/30 bg-error/5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-error text-white flex items-center justify-center flex-shrink-0 shadow-md">
                    <span class="material-symbols-outlined">report_problem</span>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-on-surface">Form Rujukan Konseling</h2>
                    <p class="text-sm text-on-surface-variant mt-1">Siswa: <strong class="text-on-surface">{{ $siswa->name }}</strong> ({{ $siswa->kelas }})</p>
                </div>
            </div>
        </div>

        <form action="{{ route('wali.rujuk.store', $siswa->id) }}" method="POST" class="p-6 sm:p-8 space-y-6">
            @csrf

            <!-- Jenis Masalah -->
            <div>
                <label for="jenis_masalah" class="block text-sm font-bold text-on-surface mb-2">Bidang Masalah <span class="text-error">*</span></label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">category</span>
                    <select id="jenis_masalah" name="jenis_masalah" required
                            class="w-full bg-surface-container-lowest border border-outline-variant/50 text-on-surface text-sm rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent block py-3.5 pl-12 pr-4 transition-all appearance-none cursor-pointer">
                        <option value="" disabled selected>Pilih Bidang Masalah...</option>
                        <option value="Akademik">Bimbingan Akademik (Penurunan nilai, sering bolos)</option>
                        <option value="Karir">Bimbingan Karir (Kebingungan jurusan/kuliah)</option>
                        <option value="Sosial">Bimbingan Sosial (Konflik dengan teman, perundungan)</option>
                        <option value="Pribadi">Bimbingan Pribadi (Masalah keluarga, psikologis)</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">expand_more</span>
                </div>
                @error('jenis_masalah')
                    <p class="mt-2 text-xs text-error font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Alasan Rujukan -->
            <div>
                <label for="alasan_rujukan" class="block text-sm font-bold text-on-surface mb-2">Alasan Merujuk Siswa <span class="text-error">*</span></label>
                <p class="text-xs text-on-surface-variant mb-3">Jelaskan mengapa Anda merasa siswa ini perlu ditangani oleh Guru BK. Detail apa yang Anda amati di kelas?</p>
                <textarea id="alasan_rujukan" name="alasan_rujukan" rows="5" required
                          class="w-full bg-surface-container-lowest border border-outline-variant/50 text-on-surface text-sm rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent block p-4 transition-all resize-y"
                          placeholder="Contoh: Siswa ini sudah absen 5 kali berturut-turut tanpa keterangan yang jelas dan terlihat murung saat di kelas..."></textarea>
                @error('alasan_rujukan')
                    <p class="mt-2 text-xs text-error font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-primary text-white font-bold py-3.5 px-8 rounded-xl hover:bg-primary/90 transition-all focus:ring-4 focus:ring-primary/20 active:scale-95 shadow-md">
                    <span class="material-symbols-outlined">send</span>
                    Kirim Rujukan ke BK
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
