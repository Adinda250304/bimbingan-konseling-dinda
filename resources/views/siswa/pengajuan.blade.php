@extends('layouts.dashboard')
@section('title', 'Ajukan Konseling')
@section('nav-title', 'Ajukan Konseling')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6 max-w-2xl mx-auto">
    <div class="mb-6">
        <h2 class="text-blue-600 font-bold text-xl">Formulir Pengajuan Konseling</h2>
        <p class="text-xs text-gray-400 mt-0.5">Isi detail kebutuhanmu, dan pilih Guru BK yang tersedia.</p>
    </div>

    @if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('siswa.pengajuan.store') }}" method="POST" class="space-y-5">
        @csrf

        {{-- Jenis Masalah --}}
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Jenis Masalah / Topik</label>
            <input type="text" name="jenis_masalah" value="{{ old('jenis_masalah') }}" required
                placeholder="Contoh: Konsultasi karir, Masalah belajar..."
                class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50 focus:bg-white rounded-xl text-sm outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
        </div>

        {{-- Deskripsi --}}
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Deskripsi Masalah</label>
            <textarea name="deskripsi_masalah" rows="4" required
                placeholder="Ceritakan masalah yang ingin dikonseling (minimal 20 karakter)..."
                class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50 focus:bg-white rounded-xl text-sm outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 resize-y transition">{{ old('deskripsi_masalah') }}</textarea>
        </div>

        {{-- Divider --}}
        <div class="border-t border-gray-100 pt-2">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Pilih Guru BK & Jadwal</p>

            {{-- Pilih Guru BK --}}
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Guru BK <span class="text-red-500">*</span></label>
                <select id="guru-select" name="guru_id" required
                    class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50 focus:bg-white rounded-xl text-sm outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 cursor-pointer transition">
                    <option value="">— Pilih siapa saja / tidak ada preferensi —</option>
                    @foreach($gurubk as $g)
                    <option value="{{ $g->id }}" {{ old('guru_id') == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Slot Jadwal Tersedia --}}
            <div id="slot-container" class="hidden mb-4">
                <label class="block text-xs font-semibold text-gray-600 mb-2">Pilih Jadwal Tersedia</label>
                <div id="slot-loading" class="hidden text-xs text-gray-400 py-2">Memuat jadwal...</div>
                <div id="slot-list" class="flex flex-wrap gap-2"></div>
                <div id="slot-empty" class="hidden text-xs text-amber-600 bg-amber-50 border border-amber-200 rounded-xl px-3 py-2">
                    Belum ada jadwal tersedia dari guru ini. Admin akan menjadwalkan setelah pengajuan masuk.
                </div>
                {{-- Hidden inputs yang akan diisi saat slot dipilih --}}
                <input type="hidden" name="tanggal_konseling" id="input-tanggal">
                <input type="hidden" name="jam_konseling" id="input-jam">
            </div>

            {{-- Fallback input manual jika tidak pilih guru --}}
            <div id="manual-container">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Pilih Tanggal</label>
                        <input type="date" id="manual-tanggal" name="tanggal_konseling" min="{{ today()->toDateString() }}"
                            class="w-full px-3 py-2.5 border border-gray-200 bg-gray-50 rounded-xl text-sm outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Pilih Jam</label>
                        <input type="time" id="manual-jam" name="jam_konseling"
                            class="w-full px-3 py-2.5 border border-gray-200 bg-gray-50 rounded-xl text-sm outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                    </div>
                </div>
                <p class="text-[10px] text-gray-400 mt-1.5">Sesuaikan dengan jadwal yang tersedia di Kalender Guru BK</p>
            </div>
        </div>

        <button type="submit"
            class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition shadow-sm shadow-blue-200">
            Kirim Pengajuan
        </button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    const guruSelect = document.getElementById('guru-select');
    const slotContainer = document.getElementById('slot-container');
    const manualContainer = document.getElementById('manual-container');
    const slotList = document.getElementById('slot-list');
    const slotLoading = document.getElementById('slot-loading');
    const slotEmpty = document.getElementById('slot-empty');
    const inputTanggal = document.getElementById('input-tanggal');
    const inputJam = document.getElementById('input-jam');

    let selectedSlot = null;

    guruSelect.addEventListener('change', function () {
        const guruId = this.value;

        if (!guruId) {
            // Kembali ke mode manual
            slotContainer.classList.add('hidden');
            manualContainer.classList.remove('hidden');
            // Re-enable manual inputs
            manualContainer.querySelectorAll('input').forEach(i => i.removeAttribute('disabled'));
            return;
        }

        // Sembunyikan manual, tampilkan slot
        manualContainer.classList.add('hidden');
        manualContainer.querySelectorAll('input').forEach(i => i.setAttribute('disabled', 'disabled'));
        slotContainer.classList.remove('hidden');
        slotList.innerHTML = '';
        slotEmpty.classList.add('hidden');
        slotLoading.classList.remove('hidden');
        selectedSlot = null;
        inputTanggal.value = '';
        inputJam.value = '';

        fetch(`/siswa/api/slot-guru?guru_id=${guruId}`)
            .then(res => res.json())
            .then(data => {
                slotLoading.classList.add('hidden');
                if (data.length === 0) {
                    slotEmpty.classList.remove('hidden');
                    return;
                }
                data.forEach(slot => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.dataset.tanggal = slot.tanggal;
                    btn.dataset.jam = slot.jam;
                    btn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> ${slot.label}`;
                    btn.className = 'slot-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 bg-gray-50 text-xs text-gray-700 hover:border-blue-400 hover:bg-blue-50 hover:text-blue-600 transition font-medium cursor-pointer';

                    btn.addEventListener('click', function () {
                        // Deselect all
                        document.querySelectorAll('.slot-btn').forEach(b => {
                            b.classList.remove('border-blue-500', 'bg-blue-50', 'text-blue-700', 'ring-2', 'ring-blue-200');
                            b.classList.add('border-gray-200', 'bg-gray-50', 'text-gray-700');
                        });
                        // Select this
                        this.classList.add('border-blue-500', 'bg-blue-50', 'text-blue-700', 'ring-2', 'ring-blue-200');
                        this.classList.remove('border-gray-200', 'bg-gray-50', 'text-gray-700');
                        inputTanggal.value = this.dataset.tanggal;
                        inputJam.value = this.dataset.jam;
                        selectedSlot = slot;
                    });

                    slotList.appendChild(btn);
                });
            })
            .catch(() => {
                slotLoading.classList.add('hidden');
                slotEmpty.classList.remove('hidden');
            });
    });
</script>
@endsection
