@extends('layouts.admin')
@section('title', 'Isi Hasil Konseling')
@section('nav-title', 'Isi Hasil & Saran Konseling')

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Tombol Kembali --}}
    <a href="{{ route('admin.jadwal') }}"
       class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-500 hover:text-blue-600 transition mb-4">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
        </svg>
        Kembali ke Jadwal
    </a>

    {{-- Info Siswa --}}
    <div class="bg-white rounded-2xl shadow-sm p-5 mb-4 border-l-4 border-blue-500">
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            <div>
                <p class="text-xs text-gray-400 font-medium mb-0.5">Nama Siswa</p>
                <p class="text-sm font-semibold text-gray-800">{{ $konseling->siswa->name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium mb-0.5">Kelas</p>
                <p class="text-sm font-semibold text-gray-800">{{ $konseling->siswa->kelas ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium mb-0.5">Jenis Masalah</p>
                <p class="text-sm font-semibold text-gray-800">{{ $konseling->jenis_masalah }}</p>
            </div>
        </div>
        @if($konseling->deskripsi_masalah)
        <div class="mt-3 pt-3 border-t border-gray-100">
            <p class="text-xs text-gray-400 font-medium mb-0.5">Deskripsi Masalah</p>
            <p class="text-sm text-gray-700">{{ $konseling->deskripsi_masalah }}</p>
        </div>
        @endif
    </div>

    {{-- Form Hasil --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="font-bold text-gray-800 text-base mb-5">Catatan & Hasil Konseling</h3>

        @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('admin.konseling.hasil.store', $konseling) }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Catatan Konselor
                    <span class="text-red-500 ml-0.5">*</span>
                </label>
                <p class="text-xs text-gray-400 mb-2">Tuliskan jalannya sesi, kondisi siswa, dan permasalahan yang dibahas.</p>
                <textarea name="catatan_konselor" rows="5" required
                    placeholder="Contoh: Siswa terlihat cemas saat membahas nilai akademik. Kami mendiskusikan strategi belajar yang lebih efektif..."
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm font-poppins text-gray-800 outline-none focus:border-blue-400 resize-y transition placeholder:text-gray-300">{{ old('catatan_konselor', $konseling->hasil?->catatan_konselor) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Saran untuk Siswa
                    <span class="text-red-500 ml-0.5">*</span>
                </label>
                <p class="text-xs text-gray-400 mb-2">Tuliskan rekomendasi dan saran konkret yang diberikan kepada siswa.</p>
                <textarea name="saran" rows="4" required
                    placeholder="Contoh: Disarankan untuk membuat jadwal belajar harian, mengurangi penggunaan gadget di malam hari..."
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm font-poppins text-gray-800 outline-none focus:border-blue-400 resize-y transition placeholder:text-gray-300">{{ old('saran', $konseling->hasil?->saran) }}</textarea>
            </div>

            <div class="border-t border-gray-100 pt-5 mt-5">
                <div class="flex items-center gap-2 mb-3">
                    <input type="checkbox" id="buat_tl" name="buat_tl" value="1" 
                        class="w-4 h-4 text-blue-600 rounded border-gray-300 cursor-pointer" 
                        onchange="document.getElementById('tl-form').classList.toggle('hidden', !this.checked)">
                    <label for="buat_tl" class="text-sm font-semibold text-gray-700 cursor-pointer">
                        Buat Surat Tindak Lanjut Resmi (Opsional)
                    </label>
                </div>
                
                <div id="tl-form" class="hidden space-y-4 bg-blue-50/50 p-4 rounded-xl border border-blue-100 mt-2">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Jenis Tindak Lanjut</label>
                        <select name="tl_jenis" class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm bg-white focus:border-blue-500 cursor-pointer transition">
                            <option value="pemanggilan_ortu">Pemanggilan Orang Tua</option>
                            <option value="mediasi">Mediasi</option>
                            <option value="rujukan">Rujukan Profesional</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Catatan Detail (Untuk Surat)</label>
                        <textarea name="tl_catatan" rows="3" placeholder="Tuliskan detail catatan / alasan tindak lanjut diputuskan..."
                            class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm bg-white focus:border-blue-500 resize-y transition"></textarea>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-2 border-t border-gray-100">
                <a href="{{ route('admin.jadwal') }}"
                   class="flex-1 text-center py-3 border-2 border-gray-300 hover:border-gray-400 rounded-xl text-sm font-semibold text-gray-700 transition">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm font-bold tracking-wide transition shadow-sm">
                    Simpan & Tandai Selesai
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
