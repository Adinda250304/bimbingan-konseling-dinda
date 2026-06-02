@extends('layouts.dashboard')
@section('title', 'Kalender Guru BK')
@section('nav-title', 'Kalender Guru BK')

@section('styles')
<style>
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
    }
</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-primary mb-2">Kalender Guru BK</h2>
            <p class="text-body-md text-on-surface-variant max-w-xl">Telusuri ketersediaan slot waktu untuk melakukan sesi konseling. Temukan waktu yang paling tenang bagi Anda.</p>
        </div>
        <!-- Legend -->
        <div class="bg-white border border-outline-variant/30 rounded-2xl p-4 flex gap-6 shadow-sm">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-primary/20 border border-primary/40"></span>
                <span class="text-[0.6875rem] font-bold text-on-surface-variant uppercase tracking-wider">Tersedia (Available)</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-surface-container-highest"></span>
                <span class="text-[0.6875rem] font-bold text-on-surface-variant uppercase tracking-wider">Terisi (Booked)</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-secondary-container/50 border border-secondary/20"></span>
                <span class="text-[0.6875rem] font-bold text-on-surface-variant uppercase tracking-wider">Libur (Holiday)</span>
            </div>
        </div>
    </div>

    <!-- Calendar & Filters Bento Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter">
        <!-- Sidebar Filters -->
        <div class="lg:col-span-3 space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-outline-variant/30 shadow-[0px_4px_20px_rgba(16,106,106,0.04)]">
                <h3 class="font-headline-sm text-headline-sm mb-4">Pilih Konselor</h3>
                <div class="space-y-3" id="counselor-list">
                    @foreach($gurubk as $index => $g)
                        @php
                            $displayName = $g->name;
                            if (Str::contains(strtolower($g->name), ['sarah', 'wijaya'])) {
                                $specialty = 'Spesialis: Kecemasan & Belajar';
                            } elseif (Str::contains(strtolower($g->name), ['adi', 'pratama'])) {
                                $specialty = 'Spesialis: Karir & Motivasi';
                            } else {
                                if ($index % 2 === 0) {
                                    $specialty = 'Spesialis: Akademik & Belajar';
                                } else {
                                    $specialty = 'Spesialis: Masalah Pribadi & Relasi';
                                }
                            }
                        @endphp
                        <label class="counselor-option flex items-start p-3 rounded-xl border border-outline-variant/30 hover:bg-surface-container-low transition-all cursor-pointer relative" data-id="{{ $g->id }}" data-name="{{ $displayName }}">
                            <input type="radio" name="counselor" value="{{ $g->id }}" class="text-primary focus:ring-primary h-4 w-4 mt-1" {{ $index === 0 ? 'checked' : '' }} />
                            <div class="ml-3">
                                <p class="text-body-md font-semibold text-on-surface leading-tight">{{ $displayName }}</p>
                                <p class="text-body-sm text-on-surface-variant mt-0.5">{{ $specialty }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Urgent help banner -->
            <div class="bg-primary text-white rounded-2xl p-6 relative overflow-hidden shadow-md">
                <div class="relative z-10 space-y-2">
                    <h3 class="font-headline-sm text-headline-sm text-white">Butuh Bantuan Cepat?</h3>
                    <p class="text-body-sm opacity-90 text-white">Anda dapat langsung mengajukan konsultasi mendesak hari ini.</p>
                    <a href="{{ route('siswa.pengajuan') }}" class="inline-block bg-white text-primary px-4 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-primary-fixed transition-colors mt-2">Buat Pengajuan Sekarang</a>
                </div>
                <div class="absolute -right-4 -bottom-4 opacity-15">
                    <span class="material-symbols-outlined text-[6.25rem]">forum</span>
                </div>
            </div>
        </div>

        <!-- Monthly Calendar View -->
        <div class="lg:col-span-9">
            <div class="bg-white rounded-3xl p-8 border border-outline-variant/30 shadow-[0px_4px_20px_rgba(16,106,106,0.04)]">
                <!-- Calendar Controls -->
                <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
                    <div class="flex items-center gap-4">
                        <h3 class="font-headline-md text-headline-md text-primary" id="calendar-title">Oktober 2024</h3>
                        <div class="flex border border-outline-variant/30 rounded-full bg-white">
                            <button id="prev-month" class="p-2 hover:bg-surface-container-low transition-colors rounded-l-full border-r border-outline-variant/30 cursor-pointer">
                                <span class="material-symbols-outlined">chevron_left</span>
                            </button>
                            <button id="next-month" class="p-2 hover:bg-surface-container-low transition-colors rounded-r-full cursor-pointer">
                                <span class="material-symbols-outlined">chevron_right</span>
                            </button>
                        </div>
                    </div>
                    <div class="flex bg-surface-container-low p-1 rounded-xl">
                        <button class="px-6 py-2 rounded-lg text-body-sm font-semibold bg-white shadow-sm text-primary">Bulanan</button>
                    </div>
                </div>

                <!-- Calendar Days Header -->
                <div class="calendar-grid mb-4 border-b border-outline-variant/10 pb-2">
                    <div class="text-center font-label-md text-on-surface-variant uppercase text-xs tracking-wider">Sen</div>
                    <div class="text-center font-label-md text-on-surface-variant uppercase text-xs tracking-wider">Sel</div>
                    <div class="text-center font-label-md text-on-surface-variant uppercase text-xs tracking-wider">Rab</div>
                    <div class="text-center font-label-md text-on-surface-variant uppercase text-xs tracking-wider">Kam</div>
                    <div class="text-center font-label-md text-on-surface-variant uppercase text-xs tracking-wider">Jum</div>
                    <div class="text-center font-label-md text-secondary uppercase text-xs tracking-wider">Sab</div>
                    <div class="text-center font-label-md text-secondary uppercase text-xs tracking-wider">Min</div>
                </div>

                <!-- Calendar Body -->
                <div class="calendar-grid gap-2" id="calendar-body">
                    <!-- Loaded dynamically via Javascript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Selected Day Slots Section (Initially hidden) -->
    <section id="slots-section" class="hidden space-y-6 pt-6 border-t border-outline-variant/20">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-primary/10 text-primary rounded-2xl flex items-center justify-center">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">event_available</span>
            </div>
            <div>
                <h3 class="font-headline-md text-headline-md text-primary" id="slots-header">Slot Tersedia - 8 Oktober 2024</h3>
                <p class="text-body-sm text-on-surface-variant">Ketuk slot waktu ketersediaan di bawah untuk mengajukan jadwal pertemuan.</p>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4" id="slots-list">
            <!-- Loaded dynamically via Day Cell Click -->
        </div>

        <!-- Action / Confirmation Card (Initially hidden) -->
        <div id="confirmation-card" class="hidden bg-white border border-outline-variant/30 rounded-3xl p-8 flex flex-col md:flex-row items-center gap-8 shadow-sm transition-all duration-300">
            <div class="w-full md:w-1/3">
                <img alt="Sesi Konseling" class="w-full h-44 object-cover rounded-2xl" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBspo0SEJFOsjYp_4WeivjyKhkMoxzubEQSQCnZBTHOPZ7wKwZcXTeuOaw1rGIQh-MkG21t4_yg50xZQOFRo5CYKk8_4IlMwoDltDQfzGZU9DWiwovCgVsnTmAz_a5AEqBRuEJZha8ICmBKp3jfQwPRJScJkn_wCKNsIOKZ736uct-KBJ0-RaeD9SsnvZeLIKdpvqfIWKvW389OKisCt3oAtw75QgeWBQ7syxaDevAF4vAf6n0nRFqCHjbRzWgdPMHZB8TG2zpL5Wg" />
            </div>
            <div class="flex-grow space-y-4">
                <h4 class="font-headline-md text-headline-md text-primary">Konfirmasi Pengajuan Jadwal</h4>
                <p class="text-body-md text-on-surface-variant leading-relaxed">
                    Anda telah memilih slot waktu <span id="confirm-time" class="font-bold text-primary">08:00 - 09:00</span> bersama <span id="confirm-counselor" class="font-bold text-on-surface">Ibu Siti Rahma</span> pada hari <span id="confirm-date" class="font-bold text-on-surface">Selasa, 8 Oktober 2024</span>.
                </p>
                <div class="flex gap-3">
                    <button id="btn-submit-booking" class="bg-primary text-white px-8 py-3.5 rounded-xl font-bold hover:shadow-lg transition-all active:scale-95 cursor-pointer text-sm">
                        Ajukan Sesi Sekarang
                    </button>
                    <button id="btn-cancel-selection" class="border border-outline-variant text-on-surface-variant px-6 py-3.5 rounded-xl font-bold hover:bg-surface-container-low transition-all cursor-pointer text-sm">
                        Pilih Waktu Lain
                    </button>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        let currentDate = new Date();
        let currentYear = currentDate.getFullYear();
        let currentMonth = currentDate.getMonth();

        let allEvents = [];
        let selectedGuruId = null;
        let selectedGuruName = '';
        let selectedSlot = null; // { date, time }

        // Elements
        const calendarBody = document.getElementById('calendar-body');
        const calendarTitle = document.getElementById('calendar-title');
        const counselorList = document.getElementById('counselor-list');
        
        const slotsSection = document.getElementById('slots-section');
        const slotsHeader = document.getElementById('slots-header');
        const slotsList = document.getElementById('slots-list');
        const confirmationCard = document.getElementById('confirmation-card');
        const confirmTime = document.getElementById('confirm-time');
        const confirmCounselor = document.getElementById('confirm-counselor');
        const confirmDate = document.getElementById('confirm-date');

        const btnSubmitBooking = document.getElementById('btn-submit-booking');
        const btnCancelSelection = document.getElementById('btn-cancel-selection');

        const prevMonthBtn = document.getElementById('prev-month');
        const nextMonthBtn = document.getElementById('next-month');

        const monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        const dayNamesIndo = [
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        ];

        // Fetch events
        function fetchEventsAndRender() {
            fetch('/siswa/api/kalender')
                .then(res => res.json())
                .then(data => {
                    allEvents = data;
                    initDefaultCounselor();
                    renderCalendar();
                })
                .catch(err => console.error('Error fetching calendar events:', err));
        }

        function initDefaultCounselor() {
            const activeRadio = counselorList.querySelector('input[type="radio"]:checked');
            if (activeRadio) {
                selectedGuruId = activeRadio.value;
                const parent = activeRadio.closest('.counselor-option');
                selectedGuruName = parent.dataset.name;
                updateCounselorStyles(parent);
            }
        }

        function updateCounselorStyles(activeParent) {
            counselorList.querySelectorAll('.counselor-option').forEach(el => {
                el.classList.remove('border-primary', 'bg-primary-container/5');
                el.classList.add('border-outline-variant/30');
            });
            activeParent.classList.add('border-primary', 'bg-primary-container/5');
            activeParent.classList.remove('border-outline-variant/30');
        }

        // Render monthly calendar grid
        function renderCalendar() {
            calendarBody.innerHTML = '';
            calendarTitle.textContent = `${monthNames[currentMonth]} ${currentYear}`;

            const firstDayOfMonth = new Date(currentYear, currentMonth, 1);
            let startDayOfWeek = firstDayOfMonth.getDay(); // 0 = Sun, 1 = Mon ...
            
            // Adjust to make Monday first day of week
            if (startDayOfWeek === 0) startDayOfWeek = 7;

            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

            // Render empty cells for previous month padding
            for (let i = 1; i < startDayOfWeek; i++) {
                const emptyCell = document.createElement('div');
                emptyCell.className = 'aspect-square bg-surface-container-lowest/30 rounded-2xl border border-outline-variant/10';
                calendarBody.appendChild(emptyCell);
            }

            // Render dates of current month
            for (let day = 1; day <= daysInMonth; day++) {
                const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                
                // Filter events matching selected counselor and this date
                const dayEvents = allEvents.filter(e => {
                    const eDate = e.start.split('T')[0];
                    return eDate === dateStr && e.extendedProps.guru_id == selectedGuruId;
                });

                const availableSlots = dayEvents.filter(e => e.extendedProps.is_available);
                const hasHoliday = dayEvents.some(e => !e.extendedProps.is_available && e.title.toLowerCase().includes('libur'));
                const hasBooked = dayEvents.some(e => !e.extendedProps.is_available && !e.title.toLowerCase().includes('libur'));

                const cell = document.createElement('div');
                cell.className = 'aspect-square bg-white rounded-2xl border border-outline-variant/30 p-2 flex flex-col items-center justify-between cursor-pointer hover:border-primary transition-all group relative';
                
                // Highlight today
                const todayStr = new Date().toISOString().split('T')[0];
                if (dateStr === todayStr) {
                    cell.classList.add('ring-2', 'ring-primary', 'ring-offset-2', 'shadow-md');
                }

                // Date Number
                const numSpan = document.createElement('span');
                numSpan.className = 'text-body-md font-semibold text-on-surface-variant';
                numSpan.textContent = day;
                cell.appendChild(numSpan);

                // Slot indicators / styling
                if (hasHoliday) {
                    cell.className = 'aspect-square bg-secondary-container/20 border border-secondary/20 rounded-2xl p-2 flex flex-col items-center justify-between';
                    const lbl = document.createElement('span');
                    lbl.className = 'text-[0.625rem] text-secondary font-bold uppercase';
                    lbl.textContent = 'Libur';
                    cell.appendChild(lbl);
                } else if (availableSlots.length > 0) {
                    cell.className = 'aspect-square bg-primary-container/10 border border-primary/40 rounded-2xl p-2 flex flex-col items-center justify-between cursor-pointer hover:bg-primary-container/20 transition-all group';
                    const lbl = document.createElement('span');
                    lbl.className = 'text-[0.625rem] text-primary font-bold uppercase';
                    lbl.textContent = `${availableSlots.length} Slots`;
                    cell.appendChild(lbl);
                } else if (hasBooked) {
                    const dot = document.createElement('div');
                    dot.className = 'w-2 h-2 rounded-full bg-surface-container-highest';
                    cell.appendChild(dot);
                } else {
                    const dot = document.createElement('div');
                    dot.className = 'w-1.5 h-1.5 rounded-full bg-gray-200 opacity-0 group-hover:opacity-100 transition-opacity';
                    cell.appendChild(dot);
                }

                cell.addEventListener('click', () => {
                    // Highlight selected day cell
                    document.querySelectorAll('#calendar-body > div').forEach(c => {
                        c.classList.remove('ring-4', 'ring-primary/20', 'border-primary');
                    });
                    cell.classList.add('ring-4', 'ring-primary/20', 'border-primary');
                    
                    showDaySlots(dateStr, dayEvents);
                });

                calendarBody.appendChild(cell);
            }
        }

        // Show slot buttons for a clicked day
        function showDaySlots(dateStr, dayEvents) {
            slotsSection.classList.remove('hidden');
            confirmationCard.classList.add('hidden');
            selectedSlot = null;

            // Formulate Header Title
            const dateObj = new Date(dateStr);
            const dayName = dayNamesIndo[dateObj.getDay()];
            const dayNum = dateObj.getDate();
            const monthName = monthNames[dateObj.getMonth()];
            const yearNum = dateObj.getFullYear();
            
            slotsHeader.textContent = `Slot Tersedia - ${dayName}, ${dayNum} ${monthName} ${yearNum}`;
            slotsList.innerHTML = '';

            const availableEvents = dayEvents.filter(e => e.extendedProps.is_available);

            if (availableEvents.length === 0) {
                // If there are no slots, let's output a friendly alert
                slotsList.innerHTML = `
                    <div class="col-span-full py-6 text-center text-on-surface-variant font-medium text-sm">
                        Tidak ada slot waktu tersedia khusus yang dijadwalkan oleh guru pada tanggal ini.
                    </div>
                `;
                return;
            }

            // Sort slots by start time
            availableEvents.sort((a, b) => a.start.localeCompare(b.start));

            availableEvents.forEach(ev => {
                const startTime = ev.start.split('T')[1].substring(0, 5);
                const endTime = ev.end ? ev.end.split('T')[1].substring(0, 5) : '';
                const timeLabel = endTime ? `${startTime} - ${endTime}` : `${startTime}`;

                const btn = document.createElement('div');
                btn.className = 'p-4 bg-white border border-outline-variant/30 rounded-2xl text-center cursor-pointer hover:border-primary transition-all group';
                btn.innerHTML = `
                    <p class="text-body-md font-bold text-on-surface-variant group-hover:text-primary transition-colors">${timeLabel}</p>
                    <span class="text-[0.625rem] font-semibold text-on-surface-variant uppercase tracking-widest mt-1 block">Tersedia</span>
                `;

                btn.addEventListener('click', () => {
                    // Highlight selected slot button: use bg-primary/10 (light green), text-primary, border-primary
                    // just like category chips active state and time slot buttons in request form
                    slotsList.querySelectorAll('div').forEach(b => {
                        b.className = 'p-4 bg-white border border-outline-variant/30 rounded-2xl text-center cursor-pointer hover:border-primary transition-all group';
                        b.querySelector('p').className = 'text-body-md font-bold text-on-surface-variant group-hover:text-primary transition-colors';
                        b.querySelector('span').className = 'text-[0.625rem] font-semibold text-on-surface-variant uppercase tracking-widest mt-1 block';
                        b.classList.remove('ring-2', 'ring-primary', 'ring-offset-2');
                    });

                    btn.className = 'p-4 bg-primary/10 border border-primary rounded-2xl text-center cursor-pointer transition-all ring-2 ring-primary ring-offset-2';
                    btn.querySelector('p').className = 'text-body-md font-bold text-primary';
                    btn.querySelector('span').className = 'text-[0.625rem] font-bold text-primary uppercase tracking-widest mt-1 block';

                    selectedSlot = {
                        date: dateStr,
                        time: startTime,
                        displayDate: `${dayName}, ${dayNum} ${monthName} ${yearNum}`,
                        displayTime: timeLabel
                    };

                    showConfirmation();
                });

                slotsList.appendChild(btn);
            });
        }

        function showConfirmation() {
            if (!selectedSlot) return;

            confirmTime.textContent = selectedSlot.displayTime;
            confirmCounselor.textContent = selectedGuruName;
            confirmDate.textContent = selectedSlot.displayDate;

            confirmationCard.classList.remove('hidden');
            
            // Scroll to confirmation card smoothly
            confirmationCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        // Event listener for counselor options change
        counselorList.addEventListener('change', (e) => {
            if (e.target.name === 'counselor') {
                selectedGuruId = e.target.value;
                const parent = e.target.closest('.counselor-option');
                selectedGuruName = parent.dataset.name;
                
                updateCounselorStyles(parent);
                slotsSection.classList.add('hidden');
                confirmationCard.classList.add('hidden');
                renderCalendar();
            }
        });

        // Date Controls Navigation
        prevMonthBtn.addEventListener('click', () => {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            slotsSection.classList.add('hidden');
            confirmationCard.classList.add('hidden');
            renderCalendar();
        });

        nextMonthBtn.addEventListener('click', () => {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            slotsSection.classList.add('hidden');
            confirmationCard.classList.add('hidden');
            renderCalendar();
        });

        btnCancelSelection.addEventListener('click', () => {
            confirmationCard.classList.add('hidden');
            slotsList.querySelectorAll('div').forEach(b => {
                b.className = 'p-4 bg-white border border-outline-variant/30 rounded-2xl text-center cursor-pointer hover:border-primary transition-all group';
                b.querySelector('p').className = 'text-body-md font-bold text-on-surface-variant group-hover:text-primary transition-colors';
                b.querySelector('span').className = 'text-[0.625rem] font-semibold text-on-surface-variant uppercase tracking-widest mt-1 block';
                b.classList.remove('ring-2', 'ring-primary', 'ring-offset-2');
            });
            selectedSlot = null;
        });

        btnSubmitBooking.addEventListener('click', () => {
            if (selectedSlot) {
                // Redirect to pengajuan page with selected details
                const url = `{{ route('siswa.pengajuan') }}?guru_id=${selectedGuruId}&tanggal=${selectedSlot.date}&jam=${selectedSlot.time}`;
                window.location.href = url;
            }
        });

        // Initialize Page
        fetchEventsAndRender();
    });
</script>
@endsection
