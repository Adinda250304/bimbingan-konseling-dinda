@extends('layouts.dashboard')
@section('title', 'Ajukan Konseling')
@section('nav-title', 'Ajukan Konseling')

@section('content')
<div class="max-w-5xl mx-auto space-y-10">
    <!-- Page Header -->
    <section class="space-y-4">
        <div class="bg-primary-container/5 border border-primary/10 rounded-2xl p-6 flex items-start gap-4">
            <span class="material-symbols-outlined text-primary text-3xl" style="font-variation-settings: 'FILL' 1;">shield_with_heart</span>
            <div class="space-y-1">
                <h3 class="font-headline-sm text-headline-sm text-primary">Ruang Aman Anda (Your Safe Space)</h3>
                <p class="text-body-md text-on-surface-variant max-w-3xl">Kami mengutamakan privasi dan kesejahteraan emosional Anda. Permintaan ini bersifat sangat rahasia. Konselor kami siap mendengarkan tanpa menghakimi dalam lingkungan yang aman dan mendukung.</p>
            </div>
        </div>
    </section>

    @if($errors->any())
    <div class="mb-5 bg-error-container/20 border border-error-container/30 text-error rounded-xl px-4 py-3 text-sm font-medium">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form id="pengajuan-form" action="{{ route('siswa.pengajuan.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        @csrf
        <!-- Hidden Inputs for Form State -->
        <input type="hidden" name="jenis_masalah" id="input-jenis-masalah" value="{{ old('jenis_masalah') }}">
        <input type="hidden" name="guru_id" id="input-guru-id" value="{{ old('guru_id') }}">
        <input type="hidden" name="tanggal_konseling" id="input-tanggal" value="{{ old('tanggal_konseling') }}">
        <input type="hidden" name="jam_konseling" id="input-jam" value="{{ old('jam_konseling') }}">

        <!-- Left Column: Form Steps -->
        <div class="lg:col-span-8 space-y-8">
            <!-- Step 1: Kategori Masalah -->
            <div class="bg-white rounded-[1.5rem] p-8 card-shadow border border-surface-variant/30 space-y-6">
                <div class="flex items-center gap-3">
                    <span class="bg-primary/10 text-primary w-8 h-8 rounded-full flex items-center justify-center font-bold">1</span>
                    <h4 class="font-headline-sm text-headline-sm">Apa yang sedang kamu pikirkan?</h4>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <button type="button" data-value="Akademik" class="reason-chip flex flex-col items-center gap-3 p-4 rounded-2xl border border-surface-variant hover:border-primary/50 hover:bg-primary/5 transition-all text-center group cursor-pointer">
                        <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary">school</span>
                        <span class="text-body-sm font-medium">Masalah Akademik</span>
                    </button>
                    <button type="button" data-value="Pribadi" class="reason-chip flex flex-col items-center gap-3 p-4 rounded-2xl border border-surface-variant hover:border-primary/50 hover:bg-primary/5 transition-all text-center group cursor-pointer">
                        <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary">psychology</span>
                        <span class="text-body-sm font-medium">Masalah Pribadi</span>
                    </button>
                    <button type="button" data-value="Sosial" class="reason-chip flex flex-col items-center gap-3 p-4 rounded-2xl border border-surface-variant hover:border-primary/50 hover:bg-primary/5 transition-all text-center group cursor-pointer">
                        <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary">diversity_3</span>
                        <span class="text-body-sm font-medium">Kehidupan Sosial</span>
                    </button>
                    <button type="button" data-value="Karir" class="reason-chip flex flex-col items-center gap-3 p-4 rounded-2xl border border-surface-variant hover:border-primary/50 hover:bg-primary/5 transition-all text-center group cursor-pointer">
                        <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary">lightbulb</span>
                        <span class="text-body-sm font-medium">Bimbingan Karir</span>
                    </button>
                    <button type="button" data-value="Keluarga" data-display="Masalah Keluarga" class="reason-chip flex flex-col items-center gap-3 p-4 rounded-2xl border border-surface-variant hover:border-primary/50 hover:bg-primary/5 transition-all text-center group cursor-pointer">
                        <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary">family_restroom</span>
                        <span class="text-body-sm font-medium">Masalah Keluarga</span>
                    </button>
                    <button type="button" data-value="Pribadi" data-display="Lainnya" class="reason-chip flex flex-col items-center gap-3 p-4 rounded-2xl border border-surface-variant hover:border-primary/50 hover:bg-primary/5 transition-all text-center group border-dashed cursor-pointer">
                        <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary">more_horiz</span>
                        <span class="text-body-sm font-medium">Lainnya</span>
                    </button>
                </div>
            </div>

            <!-- Step 2: Counselor Selection -->
            <div class="bg-white rounded-[1.5rem] p-8 card-shadow border border-surface-variant/30 space-y-6">
                <div class="flex items-center gap-3">
                    <span class="bg-primary/10 text-primary w-8 h-8 rounded-full flex items-center justify-center font-bold">2</span>
                    <h4 class="font-headline-sm text-headline-sm">Pilih Guru BK</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($gurubk as $index => $g)
                        @php
                            // Keep the actual database name
                            $displayName = $g->name;
                            
                            // Load real database rating and sessions count
                            $sessions = $g->completed_sessions_count ?: 0;
                            $rating = $g->average_rating ? number_format($g->average_rating, 1) : '5.0';
                            
                            // Assign professional details based on the name or index dynamically
                            if (Str::contains(strtolower($g->name), ['sarah', 'wijaya'])) {
                                $specialty = 'Spesialis: Stres & Kesulitan Belajar';
                                $avatar = 'https://lh3.googleusercontent.com/aida-public/AB6AXuByysjoI7CZ2_eMZmUWp4Ud8OmwN_SCt5s2xI8aZgVPgO4TmqPgyU2EZxgPSWo6HK7o1PHtAC1bc_VQoRlBO8MJLiyLWHuNWZ3rA4ERGEcaoB3YMi9kqLJPz0i5auaMPrNd_ohF5PDg6we62v6LjiA1e_lRsMYyRGsLHByXZEkbrsqY_8MOsnTTpZ-wpCHh2KettUuyQ2wP9ameEzkSncibLdbWN703I9AxsxqLYtC3HGqVG5FIe9wx-8YSxeggpveNHaUHIsuPii0';
                            } elseif (Str::contains(strtolower($g->name), ['adi', 'pratama'])) {
                                $specialty = 'Spesialis: Karir & Motivasi Diri';
                                $avatar = 'https://lh3.googleusercontent.com/aida-public/AB6AXuAfcQmBdAQNyTSj1QmxpU0DjfqkjQz6QpKAsYsaXAYAccLuusNz1qki4UOQ01FUl7Q0I57A9lpvwFRAOB3JbNRM3GwKPGJlNgx5RVqYE9LCMoGwXNy9YK1hQ3OsAAPHjS2ehI-Y1HvIuqFvv0DSMgl7yCG1B-HXR97XAScChoCU8RDFi3z6QFFQ46u-I05BsUKQXRXlpOvAFh593O9py8VpduUzTWNmLqZISC8Ws3D3mcXFw_B8YgWmfJjNuAhKV43rE2HA3Y6PUpM';
                            } else {
                                // Default fallback depending on index
                                if ($index % 2 === 0) {
                                    $specialty = 'Spesialis: Stres, Belajar & Pengembangan Diri';
                                    $avatar = 'https://lh3.googleusercontent.com/aida-public/AB6AXuByysjoI7CZ2_eMZmUWp4Ud8OmwN_SCt5s2xI8aZgVPgO4TmqPgyU2EZxgPSWo6HK7o1PHtAC1bc_VQoRlBO8MJLiyLWHuNWZ3rA4ERGEcaoB3YMi9kqLJPz0i5auaMPrNd_ohF5PDg6we62v6LjiA1e_lRsMYyRGsLHByXZEkbrsqY_8MOsnTTpZ-wpCHh2KettUuyQ2wP9ameEzkSncibLdbWN703I9AxsxqLYtC3HGqVG5FIe9wx-8YSxeggpveNHaUHIsuPii0';
                                } else {
                                    $specialty = 'Spesialis: Masalah Pribadi & Relasi';
                                    $avatar = 'https://lh3.googleusercontent.com/aida-public/AB6AXuAfcQmBdAQNyTSj1QmxpU0DjfqkjQz6QpKAsYsaXAYAccLuusNz1qki4UOQ01FUl7Q0I57A9lpvwFRAOB3JbNRM3GwKPGJlNgx5RVqYE9LCMoGwXNy9YK1hQ3OsAAPHjS2ehI-Y1HvIuqFvv0DSMgl7yCG1B-HXR97XAScChoCU8RDFi3z6QFFQ46u-I05BsUKQXRXlpOvAFh593O9py8VpduUzTWNmLqZISC8Ws3D3mcXFw_B8YgWmfJjNuAhKV43rE2HA3Y6PUpM';
                                }
                            }
                        @endphp
                        <div data-id="{{ $g->id }}" data-name="{{ $displayName }}" class="counselor-card relative p-4 rounded-2xl border border-surface-variant flex flex-col sm:flex-row gap-4 hover:border-primary transition-all cursor-pointer group bg-surface-container-lowest">
                            <img class="w-full h-40 sm:w-20 sm:h-20 rounded-xl object-cover shrink-0" src="{{ $avatar }}" alt="{{ $displayName }}" />
                            <div class="flex-1 flex flex-col justify-between">
                                <div>
                                    <h5 class="font-bold text-on-surface text-sm sm:text-base">{{ $displayName }}</h5>
                                    <p class="text-body-sm text-on-surface-variant mb-2">{{ $specialty }}</p>
                                </div>
                                <div class="flex items-center gap-1 mt-auto">
                                    <span class="material-symbols-outlined text-[1rem] text-amber-500" style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="text-label-md text-xs sm:text-sm">{{ $rating }} ({{ $sessions }} sesi)</span>
                                </div>
                            </div>
                            <div class="absolute top-4 right-4 check-icon opacity-0 transition-opacity">
                                <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Step 3: Date & Details -->
            <div class="bg-white rounded-[1.5rem] p-8 card-shadow border border-surface-variant/30 space-y-8">
                <div class="flex items-center gap-3">
                    <span class="bg-primary/10 text-primary w-8 h-8 rounded-full flex items-center justify-center font-bold">3</span>
                    <h4 class="font-headline-sm text-headline-sm">Jadwal & Catatan</h4>
                </div>

                <!-- Dynamic Slots Recommendation Section -->
                <div id="recommendation-container" class="hidden space-y-3">
                    <label class="block text-body-sm font-semibold text-on-surface-variant">Rekomendasi Jadwal Guru BK yang Tersedia</label>
                    <div id="slot-loading" class="hidden text-xs text-on-surface-variant py-2 flex items-center gap-2">
                        <span class="animate-spin rounded-full h-4 w-4 border-2 border-primary border-t-transparent"></span>
                        Memuat jadwal ketersediaan guru...
                    </div>
                    <div id="slot-list" class="flex flex-wrap gap-2"></div>
                    <div id="slot-empty" class="hidden text-xs text-on-surface-variant py-2">
                        Belum ada jadwal ketersediaan khusus di kalender guru ini. Silakan tentukan tanggal & waktu secara manual di bawah.
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                    <div class="space-y-4">
                        <label class="block text-body-sm font-semibold text-on-surface-variant">Pilih Tanggal Konseling</label>
                        <div class="p-4 bg-surface-container-low rounded-2xl border border-transparent focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/20 transition-all">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary">calendar_month</span>
                                <input type="date" id="date-picker" min="{{ today()->toDateString() }}" class="bg-transparent border-none p-0 text-body-md w-full focus:ring-0 cursor-pointer" />
                            </div>
                        </div>

                        <label class="block text-body-sm font-semibold text-on-surface-variant pt-2">Pilih Jam Pertemuan</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" data-time="08:00" class="time-slot-btn py-2 px-2.5 text-label-md border border-surface-variant rounded-full hover:bg-primary-container/10 transition-all cursor-pointer whitespace-nowrap">08:00 WIB</button>
                            <button type="button" data-time="09:30" class="time-slot-btn py-2 px-2.5 text-label-md border border-surface-variant rounded-full hover:bg-primary-container/10 transition-all cursor-pointer whitespace-nowrap">09:30 WIB</button>
                            <button type="button" data-time="11:00" class="time-slot-btn py-2 px-2.5 text-label-md border border-surface-variant rounded-full hover:bg-primary-container/10 transition-all cursor-pointer whitespace-nowrap">11:00 WIB</button>
                            <button type="button" data-time="13:00" class="time-slot-btn py-2 px-2.5 text-label-md border border-surface-variant rounded-full hover:bg-primary-container/10 transition-all cursor-pointer whitespace-nowrap">13:00 WIB</button>
                            <button type="button" data-time="14:30" class="time-slot-btn py-2 px-2.5 text-label-md border border-surface-variant rounded-full hover:bg-primary-container/10 transition-all cursor-pointer whitespace-nowrap">14:30 WIB</button>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <label class="block text-body-sm font-semibold text-on-surface-variant">Deskripsi Masalah</label>
                        <textarea name="deskripsi_masalah" id="deskripsi-masalah" rows="6" required class="w-full bg-surface-container-low border-transparent rounded-2xl p-4 text-body-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all resize-none" placeholder="Ceritakan singkat mengenai hal yang ingin Anda diskusikan... (minimal 20 karakter)">{{ old('deskripsi_masalah') }}</textarea>
                        <p id="char-warning" class="text-xs text-error hidden">Minimal deskripsi masalah adalah 20 karakter.</p>
                    </div>
                </div>

                <div class="pt-6 border-t border-surface-variant flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <p class="text-body-sm text-on-surface-variant flex items-center gap-2">
                        <span class="material-symbols-outlined text-teal-600">verified_user</span>
                        Pengajuan aman dan rahasia
                    </p>
                    <button type="submit" class="w-full sm:w-auto bg-primary text-white py-4 px-10 rounded-2xl font-bold hover:shadow-lg hover:-translate-y-1 active:scale-95 transition-all cursor-pointer text-center">
                        Kirim Pengajuan
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Column: Summary / Support -->
        <aside class="lg:col-span-4 space-y-6">
            <!-- Session Summary Card -->
            <div class="bg-surface-container-low/50 backdrop-blur-md rounded-[1.5rem] p-6 border border-white/50 space-y-6">
                <h4 class="font-headline-sm text-headline-sm">Ringkasan Pilihan</h4>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="bg-white p-2 rounded-lg shadow-sm">
                            <span class="material-symbols-outlined text-primary">help</span>
                        </div>
                        <div>
                            <p class="text-label-md text-on-surface-variant uppercase tracking-wider">Kategori</p>
                            <p class="text-body-md font-semibold" id="summary-reason">Belum dipilih</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="bg-white p-2 rounded-lg shadow-sm">
                            <span class="material-symbols-outlined text-primary">person</span>
                        </div>
                        <div>
                            <p class="text-label-md text-on-surface-variant uppercase tracking-wider">Konselor</p>
                            <p class="text-body-md font-semibold" id="summary-counselor">Belum dipilih</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="bg-white p-2 rounded-lg shadow-sm">
                            <span class="material-symbols-outlined text-primary">event</span>
                        </div>
                        <div>
                            <p class="text-label-md text-on-surface-variant uppercase tracking-wider">Jadwal</p>
                            <p class="text-body-md font-semibold" id="summary-date">Belum ditentukan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Support Card -->
            <div class="bg-white rounded-[1.5rem] p-6 card-shadow border border-surface-variant/30 space-y-4 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <span class="material-symbols-outlined text-[5rem]">clinical_notes</span>
                </div>
                <h4 class="font-bold text-on-surface">Butuh bantuan segera?</h4>
                <p class="text-body-sm text-on-surface-variant">Jika Anda berada dalam situasi darurat atau krisis emosional, silakan hubungi kontak darurat kami segera.</p>
                <a class="text-primary font-bold flex items-center gap-2 hover:gap-3 transition-all" target="_blank" href="https://wa.me/6285775658758?text=Halo%20Bapak/Ibu,%20saya%20butuh%20bantuan%20darurat%20sekarang.">
                    Hubungi Hotline Layanan <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>

            <!-- Decorative Graphic -->
            <div class="rounded-[1.5rem] aspect-[4/3] overflow-hidden card-shadow">
                <img class="w-full h-full object-cover scale-[1.35] origin-center" src="{{ asset('img/hotline_support.png') }}" alt="Hotline Support" />
            </div>
        </aside>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Elements
        const reasonChips = document.querySelectorAll('.reason-chip');
        const counselorCards = document.querySelectorAll('.counselor-card');
        const timeSlotBtns = document.querySelectorAll('.time-slot-btn');
        const datePicker = document.getElementById('date-picker');
        const deskripsiMasalah = document.getElementById('deskripsi-masalah');
        const charWarning = document.getElementById('char-warning');

        const inputJenisMasalah = document.getElementById('input-jenis-masalah');
        const inputGuruId = document.getElementById('input-guru-id');
        const inputTanggal = document.getElementById('input-tanggal');
        const inputJam = document.getElementById('input-jam');

        const summaryReason = document.getElementById('summary-reason');
        const summaryCounselor = document.getElementById('summary-counselor');
        const summaryDate = document.getElementById('summary-date');

        const recommendationContainer = document.getElementById('recommendation-container');
        const slotLoading = document.getElementById('slot-loading');
        const slotList = document.getElementById('slot-list');
        const slotEmpty = document.getElementById('slot-empty');

        // Functions
        function selectCategory(chip) {
            reasonChips.forEach(c => {
                c.classList.remove('bg-primary/10', 'border-primary', 'text-primary');
            });
            chip.classList.add('bg-primary/10', 'border-primary', 'text-primary');
            
            const value = chip.dataset.value;
            const display = chip.dataset.display || chip.querySelector('span:last-child').textContent.trim();
            
            inputJenisMasalah.value = value;
            summaryReason.textContent = display;
        }

        function selectCounselor(card) {
            counselorCards.forEach(c => {
                c.classList.remove('border-primary', 'ring-2', 'ring-primary/10');
                c.querySelector('.check-icon').classList.add('opacity-0');
            });
            card.classList.add('border-primary', 'ring-2', 'ring-primary/10');
            card.querySelector('.check-icon').classList.remove('opacity-0');

            const id = card.dataset.id;
            const name = card.dataset.name;

            inputGuruId.value = id;
            summaryCounselor.textContent = name;

            // Fetch counselor slots
            fetchSlots(id);
        }

        function selectTime(btn) {
            // Deselect manual slot buttons
            timeSlotBtns.forEach(b => {
                b.classList.remove('bg-primary/10', 'text-primary', 'border-primary');
            });
            
            // Deselect recommendation buttons if any
            document.querySelectorAll('.rec-slot-btn').forEach(b => {
                b.classList.remove('bg-primary/10', 'text-primary', 'border-primary');
            });

            if (btn) {
                btn.classList.add('bg-primary/10', 'text-primary', 'border-primary');
                const time = btn.dataset.time;
                inputJam.value = time;
            }
            updateScheduleSummary();
        }

        function updateScheduleSummary() {
            const dateVal = inputTanggal.value;
            const timeVal = inputJam.value;

            if (dateVal && timeVal) {
                summaryDate.textContent = `${dateVal} | ${timeVal} WIB`;
            } else if (dateVal) {
                summaryDate.textContent = `${dateVal} | Jam Belum Dipilih`;
            } else if (timeVal) {
                summaryDate.textContent = `Tanggal Belum Dipilih | ${timeVal} WIB`;
            } else {
                summaryDate.textContent = 'Belum ditentukan';
            }
        }

        function fetchSlots(guruId) {
            recommendationContainer.classList.remove('hidden');
            slotLoading.classList.remove('hidden');
            slotList.innerHTML = '';
            slotEmpty.classList.add('hidden');

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
                        btn.className = 'rec-slot-btn inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-outline-variant bg-surface text-xs text-on-surface hover:border-primary hover:bg-primary/5 hover:text-primary transition font-semibold cursor-pointer';
                        
                        // Check if this slot matches currently selected date & time
                        if (inputTanggal.value === slot.tanggal && inputJam.value === slot.jam) {
                            btn.classList.add('bg-primary/10', 'text-primary', 'border-primary');
                        }

                        btn.innerHTML = `<span class="material-symbols-outlined text-xs">schedule</span> ${slot.label}`;
                        
                        btn.addEventListener('click', function () {
                            // Deselect others
                            document.querySelectorAll('.rec-slot-btn').forEach(b => {
                                b.classList.remove('bg-primary/10', 'text-primary', 'border-primary');
                            });
                            timeSlotBtns.forEach(b => {
                                b.classList.remove('bg-primary/10', 'text-primary', 'border-primary');
                            });

                            // Select this
                            this.classList.add('bg-primary/10', 'text-primary', 'border-primary');
                            
                            // Update input and date picker
                            inputTanggal.value = this.dataset.tanggal;
                            datePicker.value = this.dataset.tanggal;
                            inputJam.value = this.dataset.jam;
                            
                            // Highlight custom manual time slot button if matches
                            timeSlotBtns.forEach(b => {
                                if (b.dataset.time === this.dataset.jam) {
                                    b.classList.add('bg-primary/10', 'text-primary', 'border-primary');
                                }
                            });

                            updateScheduleSummary();
                        });

                        slotList.appendChild(btn);
                    });
                })
                .catch(() => {
                    slotLoading.classList.add('hidden');
                    slotEmpty.classList.remove('hidden');
                });
        }

        // Event Listeners
        reasonChips.forEach(chip => {
            chip.addEventListener('click', () => selectCategory(chip));
        });

        counselorCards.forEach(card => {
            card.addEventListener('click', () => selectCounselor(card));
        });

        timeSlotBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                selectTime(btn);
            });
        });

        datePicker.addEventListener('change', function () {
            inputTanggal.value = this.value;
            // Deselect recommended slot buttons highlight because user changed date manually
            document.querySelectorAll('.rec-slot-btn').forEach(b => {
                if (b.dataset.tanggal !== this.value) {
                    b.classList.remove('bg-primary', 'text-white', 'border-primary');
                }
            });
            updateScheduleSummary();
        });

        deskripsiMasalah.addEventListener('input', function () {
            if (this.value.trim().length >= 20) {
                charWarning.classList.add('hidden');
            } else {
                charWarning.classList.remove('hidden');
            }
        });

        // Form Submit Validation
        const form = document.getElementById('pengajuan-form');
        form.addEventListener('submit', function (e) {
            let errors = [];

            if (!inputJenisMasalah.value) {
                errors.push('Silakan pilih kategori masalah di Langkah 1.');
            }
            if (!inputGuruId.value) {
                errors.push('Silakan pilih Guru BK di Langkah 2.');
            }
            if (!inputTanggal.value) {
                errors.push('Silakan pilih tanggal konseling di Langkah 3.');
            }
            if (!inputJam.value) {
                errors.push('Silakan pilih jam pertemuan di Langkah 3.');
            }
            if (deskripsiMasalah.value.trim().length < 20) {
                errors.push('Deskripsi masalah harus minimal 20 karakter.');
            }

            if (errors.length > 0) {
                e.preventDefault();
                if (window.Swal) {
                    Swal.fire({
                        title: 'Form Belum Lengkap',
                        html: `<ul class="text-left list-disc list-inside text-sm text-on-surface-variant space-y-1">${errors.map(err => `<li>${err}</li>`).join('')}</ul>`,
                        icon: 'warning',
                        customClass: {
                            popup: 'impeccable-swal font-poppins',
                            title: 'impeccable-title',
                            htmlContainer: 'impeccable-content',
                            confirmButton: 'impeccable-confirm bg-error text-white hover:bg-error/90 transition-colors',
                        },
                        buttonsStyling: false,
                    });
                } else {
                    alert(errors.join('\n'));
                }
            }
        });

        // Initial setup from old inputs or defaults
        const oldJenisMasalah = "{{ old('jenis_masalah') }}";
        const oldGuruId = "{{ old('guru_id', request('guru_id')) }}";
        const oldTanggal = "{{ old('tanggal_konseling', request('tanggal')) }}";
        const oldJam = "{{ old('jam_konseling', request('jam')) }}";

        if (oldJenisMasalah) {
            const activeChip = Array.from(reasonChips).find(c => c.dataset.value === oldJenisMasalah);
            if (activeChip) selectCategory(activeChip);
        }

        if (oldGuruId) {
            const activeCard = Array.from(counselorCards).find(c => c.dataset.id === oldGuruId);
            if (activeCard) selectCounselor(activeCard);
        }

        if (oldTanggal) {
            datePicker.value = oldTanggal;
            inputTanggal.value = oldTanggal;
            updateScheduleSummary();
        }

        if (oldJam) {
            inputJam.value = oldJam;
            const activeTimeBtn = Array.from(timeSlotBtns).find(b => b.dataset.time === oldJam);
            if (activeTimeBtn) selectTime(activeTimeBtn);
            else updateScheduleSummary();
        }
    });
</script>
@endsection
