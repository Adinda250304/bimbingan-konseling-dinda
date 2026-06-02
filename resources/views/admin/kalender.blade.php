@extends('layouts.admin')
@section('title', 'Kalender Ketersediaan')
@section('nav-title', 'Kalender Ketersediaan')

@section('styles')
<style>
    /* FullCalendar Premium Custom Styling */
    .fc {
        font-family: 'Inter', sans-serif;
    }
    
    /* Toolbar styling */
    .fc-header-toolbar {
        margin-bottom: 1.5rem !important;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .fc-toolbar-title {
        font-family: 'Manrope', sans-serif;
        font-size: 1.25rem !important;
        font-weight: 700 !important;
        color: #005050 !important;
    }
    
    /* Button Customization */
    .fc .fc-button-primary {
        background-color: #eeeeec !important;
        border-color: transparent !important;
        color: #1a1c1b !important;
        border-radius: 0.75rem !important;
        font-weight: 600 !important;
        font-size: 0.85rem !important;
        padding: 0.5rem 1rem !important;
        box-shadow: none !important;
        transition: all 0.2s ease;
        text-transform: capitalize;
    }
    .fc .fc-button-primary:hover {
        background-color: #e2e3e1 !important;
        color: #005050 !important;
    }
    .fc .fc-button-primary:not(:disabled):active,
    .fc .fc-button-primary:not(:disabled).fc-button-active {
        background-color: #005050 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(0, 80, 80, 0.15) !important;
    }
    .fc-button-group {
        display: flex;
        gap: 0.35rem;
    }
    .fc-button-group > .fc-button {
        border-radius: 0.75rem !important;
        margin: 0 !important;
    }
    .fc-today-button {
        border-radius: 0.75rem !important;
    }
    
    /* Scrollgrid and Borders */
    .fc-theme-standard td, .fc-theme-standard th {
        border-color: rgba(226, 227, 225, 0.7) !important;
    }
    .fc-theme-standard .fc-scrollgrid {
        border: 1px solid #e2e3e1 !important;
        border-radius: 1rem !important;
        overflow: hidden;
    }
    
    /* Column headers */
    .fc-col-header-cell {
        background-color: #f4f4f2 !important;
    }
    .fc-col-header-cell-cushion {
        padding: 10px 4px !important;
        font-family: 'Manrope', sans-serif;
        font-weight: 700 !important;
        color: #1a1c1b !important;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        text-decoration: none !important;
    }
    
    /* Time slot grid */
    .fc-timegrid-slot {
        height: 3.5rem !important;
    }
    .fc-timegrid-slot-label-cushion {
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        font-size: 0.75rem;
        color: #3f4948;
    }
    .fc-timegrid-axis-cushion {
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        font-size: 0.75rem;
        color: #3f4948;
    }
    
    /* Daygrid view date number */
    .fc-daygrid-day-number {
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        font-size: 0.85rem;
        color: #1a1c1b;
        padding: 8px 10px !important;
        text-decoration: none !important;
    }
    
    /* Today highlight */
    .fc-day-today {
        background-color: rgba(164, 240, 239, 0.12) !important;
    }
    .fc-day-today .fc-daygrid-day-number {
        color: #005050 !important;
        font-weight: 700 !important;
    }
    
    /* Event custom design */
    .fc-v-event, .fc-h-event {
        border-radius: 0.75rem !important;
        border: 1px solid !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease !important;
    }
    .fc-v-event:hover, .fc-h-event:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06) !important;
    }
    
    /* Align texts in event content */
    .fc-event-main {
        padding: 4px !important;
    }
</style>
@endsection

@section('content')
<!-- Page Header Description -->


<div class="grid grid-cols-12 gap-8">
    <!-- Left column: Legend and metrics (col-span-4) -->
    <div class="col-span-12 lg:col-span-4 space-y-6">
        <!-- Legend Slot Card -->
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-6 shadow-subtle">
            <h3 class="font-headline-sm text-lg text-on-surface mb-6">Keterangan Slot</h3>
            <ul class="space-y-4 mb-8">
                <li class="flex items-center gap-4">
                    <div class="h-10 w-10 rounded-xl bg-[#e6f4f1] border border-[#a4f0ef] flex items-center justify-center">
                        <div class="h-4 w-4 rounded-full bg-primary-fixed-dim"></div>
                    </div>
                    <div>
                        <p class="font-semibold text-on-surface">Tersedia</p>
                        <p class="text-xs text-on-surface-variant">Siap untuk sesi bimbingan</p>
                    </div>
                </li>
                <li class="flex items-center gap-4">
                    <div class="h-10 w-10 rounded-xl bg-[#fff2ef] border border-[#ffdad2] flex items-center justify-center">
                        <div class="h-4 w-4 rounded-full bg-secondary"></div>
                    </div>
                    <div>
                        <p class="font-semibold text-on-surface">Terjadwal</p>
                        <p class="text-xs text-on-surface-variant">Sesi bimbingan aktif</p>
                    </div>
                </li>
                <li class="flex items-center gap-4">
                    <div class="h-10 w-10 rounded-xl bg-surface-container-low border border-outline-variant/50 flex items-center justify-center">
                        <div class="h-4 w-4 rounded-full bg-outline"></div>
                    </div>
                    <div>
                        <p class="font-semibold text-on-surface">Libur / Off</p>
                        <p class="text-xs text-on-surface-variant">Waktu pribadi/non-aktif</p>
                    </div>
                </li>
            </ul>
            <button onclick="quickUnavailable()" class="w-full py-4 bg-primary text-white rounded-xl font-semibold flex items-center justify-center gap-2 hover:opacity-90 active:scale-[0.98] transition-all">
                <span class="material-symbols-outlined">event_busy</span>
                Atur Libur Cepat
            </button>
        </div>

        <!-- Monthly Summary Bento Card -->
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-6 shadow-subtle">
            <h3 class="font-label-md text-label-md text-on-surface-variant uppercase mb-4">Statistik Bulan Ini</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-surface-container-low rounded-xl">
                    <p class="text-2xl font-bold text-primary">{{ $total_slot_terisi }}</p>
                    <p class="text-xs text-on-surface-variant">Slot Terisi</p>
                </div>
                <div class="p-4 bg-surface-container-low rounded-xl">
                    <p class="text-2xl font-bold text-tertiary">{{ $siswa_baru_count }}</p>
                    <p class="text-xs text-on-surface-variant">Siswa Baru</p>
                </div>
            </div>
        </div>

        <!-- Upcoming Highlight Card -->
        @if($sesi_terdekat)
        <div class="relative overflow-hidden bg-primary-container text-on-primary-container rounded-2xl p-6 shadow-subtle">
            <div class="relative z-10">
                <p class="text-xs font-semibold opacity-80 mb-2">SESI TERDEKAT</p>
                <h4 class="font-headline-sm text-white mb-1 leading-snug">{{ $sesi_terdekat->siswa->name ?? 'Siswa' }}</h4>
                <p class="text-sm mb-4 leading-normal">
                    {{ $sesi_terdekat->tanggal_konseling->translatedFormat('l, d M Y') }} • {{ substr($sesi_terdekat->jam_konseling, 0, 5) }} WIB
                </p>
                <a href="{{ route('admin.jadwal', ['status' => 'hari_ini']) }}" class="inline-block text-xs font-bold px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-colors">
                    Lihat Detail
                </a>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-10">
                <span class="material-symbols-outlined" style="font-size: 120px;">calendar_today</span>
            </div>
        </div>
        @else
        <div class="relative overflow-hidden bg-surface-container-lowest border border-outline-variant/30 text-on-surface-variant rounded-2xl p-6 shadow-subtle">
            <div class="relative z-10">
                <p class="text-xs font-semibold opacity-85 mb-2 text-primary">SESI TERDEKAT</p>
                <h4 class="font-headline-sm text-on-surface mb-2">Tidak Ada Sesi Terdekat</h4>
                <p class="text-sm">Semua sesi telah selesai atau belum ada pengajuan baru.</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Right column: Interactive calendar (col-span-8) -->
    <div class="col-span-12 lg:col-span-8">
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-6 shadow-subtle overflow-hidden">
            <div id="calendar"></div>
        </div>
    </div>
</div>
@endsection

@section('modals')
{{-- Modal Tambah/Edit Jadwal --}}
<div id="modal-kalender" class="hidden fixed inset-0 bg-black/40 backdrop-blur-md z-[100] items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl p-6 w-full max-w-sm shadow-2xl border border-outline-variant/20">
        <div class="flex justify-between items-center mb-5">
            <h3 class="font-headline-sm text-lg text-primary" id="modal-title">Tambah Ketersediaan</h3>
            <button onclick="closeModal('modal-kalender')" class="text-on-surface-variant hover:text-on-surface text-2xl leading-none">&times;</button>
        </div>
        
        <form id="kalender-form" onsubmit="saveEvent(event)">
            <input type="hidden" id="event-id">
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-on-surface mb-2">Status Ketersediaan</label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="flex items-center gap-2 p-2.5 border border-outline-variant/50 rounded-xl cursor-pointer hover:bg-surface-container-low transition">
                        <input type="radio" name="is_available" id="status-available" value="1" checked class="w-4 h-4 text-primary focus:ring-primary border-outline">
                        <span class="text-sm font-semibold text-primary">Tersedia</span>
                    </label>
                    <label class="flex items-center gap-2 p-2.5 border border-outline-variant/50 rounded-xl cursor-pointer hover:bg-surface-container-low transition">
                        <input type="radio" name="is_available" id="status-unavailable" value="0" class="w-4 h-4 text-error focus:ring-error border-outline">
                        <span class="text-sm font-semibold text-error">Tidak Bisa</span>
                    </label>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-on-surface mb-1">Keterangan / Topik</label>
                <input type="text" id="event-title" required placeholder="Misal: Konseling Tersedia"
                    class="w-full px-4 py-2.5 border border-outline-variant/50 rounded-xl text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary transition bg-background">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-on-surface mb-1">Pilih Slot Waktu Standard</label>
                <select id="event-slot-select" onchange="applyStandardSlot(this.value)" class="w-full bg-background border border-outline-variant/50 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-1 focus:ring-primary focus:border-primary transition cursor-pointer">
                    <option value="custom">Kustom / Manual</option>
                    <option value="08:00-09:30">08:00 - 09:30 (Sesi 1)</option>
                    <option value="09:30-11:00">09:30 - 11:00 (Sesi 2)</option>
                    <option value="11:00-12:30">11:00 - 12:30 (Sesi 3)</option>
                    <option value="13:00-14:30">13:00 - 14:30 (Sesi 4)</option>
                    <option value="14:30-16:00">14:30 - 16:00 (Sesi 5)</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1">Mulai</label>
                    <input type="datetime-local" id="event-start" required
                        class="w-full px-3 py-2.5 border border-outline-variant/50 rounded-xl text-xs outline-none focus:border-primary focus:ring-1 focus:ring-primary transition bg-background">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1">Selesai</label>
                    <input type="datetime-local" id="event-end"
                        class="w-full px-3 py-2.5 border border-outline-variant/50 rounded-xl text-xs outline-none focus:border-primary focus:ring-1 focus:ring-primary transition bg-background">
                </div>
            </div>

            <div class="flex gap-2 justify-end mt-6">
                <button type="button" id="btn-delete" onclick="deleteEvent()" class="hidden px-4 py-2 bg-error-container/20 text-error hover:bg-error-container/40 rounded-xl text-sm font-semibold transition">Hapus</button>
                <button type="button" onclick="closeModal('modal-kalender')" class="px-4 py-2 border border-outline-variant/50 text-on-surface-variant hover:bg-surface-container-low rounded-xl text-sm font-semibold transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-primary text-white hover:bg-primary-container rounded-xl text-sm font-semibold transition shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Atur Libur Cepat --}}
<div id="modal-libur-cepat" class="hidden fixed inset-0 bg-black/40 backdrop-blur-md z-[100] items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-3xl p-8 w-full max-w-md shadow-2xl border border-outline-variant/20 relative">
        <button onclick="closeModal('modal-libur-cepat')" class="absolute top-6 right-6 text-outline hover:text-on-surface flex items-center justify-center">
            <span class="material-symbols-outlined">close</span>
        </button>

        <h3 class="font-headline-sm text-headline-sm text-on-surface mb-2 font-bold flex items-center gap-2">
            <span class="material-symbols-outlined text-error">event_busy</span>
            Atur Libur / Off Cepat
        </h3>
        <p class="text-body-sm text-outline mb-6">Nonaktifkan slot ketersediaan bimbingan Anda dengan cepat.</p>

        <!-- Preset Buttons -->
        <div class="mb-5">
            <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-2">Preset Libur</label>
            <div class="grid grid-cols-2 gap-2">
                <button type="button" id="btn-preset-hari-ini" onclick="setPresetLibur('hari_ini')" class="py-2.5 px-4 bg-surface-container-low hover:bg-primary/10 hover:text-primary rounded-xl text-xs font-bold transition-all text-on-surface border border-outline-variant/30 flex items-center justify-center gap-1.5 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">today</span>
                    Hari Ini (Seharian)
                </button>
                <button type="button" id="btn-preset-besok" onclick="setPresetLibur('besok')" class="py-2.5 px-4 bg-surface-container-low hover:bg-primary/10 hover:text-primary rounded-xl text-xs font-bold transition-all text-on-surface border border-outline-variant/30 flex items-center justify-center gap-1.5 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">event_upcoming</span>
                    Besok (Seharian)
                </button>
            </div>
        </div>

        <form id="libur-cepat-form" onsubmit="saveLiburCepat(event)" class="space-y-4">
            <input type="hidden" name="is_available" value="0">
            
            <div>
                <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1.5">Alasan / Keterangan</label>
                <input type="text" id="libur-title" required value="Libur / Off" placeholder="Misal: Cuti, Rapat Guru, Keperluan Pribadi"
                    class="w-full px-4 py-2.5 border border-outline-variant/50 rounded-xl text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary transition bg-surface">
            </div>

            <!-- Seharian Checkbox -->
            <div class="flex items-center gap-2 py-1">
                <input type="checkbox" id="libur-seharian" checked onchange="toggleLiburTimes(); updatePresetButtonStyles(null);" class="w-4 h-4 text-primary focus:ring-primary border-outline-variant rounded">
                <label for="libur-seharian" class="text-sm font-semibold text-on-surface cursor-pointer select-none">Libur Seharian Penuh</label>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1.5">Mulai Tanggal</label>
                    <input type="date" id="libur-start-date" required onchange="syncLiburEndDate(); updatePresetButtonStyles(null);"
                        class="w-full px-3 py-2.5 border border-outline-variant/50 rounded-xl text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary transition bg-surface">
                </div>
                <div>
                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                    <input type="date" id="libur-end-date" required onchange="updatePresetButtonStyles(null);"
                        class="w-full px-3 py-2.5 border border-outline-variant/50 rounded-xl text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary transition bg-surface">
                </div>
            </div>

            <div id="libur-time-fields" class="grid grid-cols-2 gap-3 hidden">
                <div>
                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1.5">Jam Mulai</label>
                    <input type="time" id="libur-start-time" value="07:00" onchange="updatePresetButtonStyles(null);"
                        class="w-full px-3 py-2.5 border border-outline-variant/50 rounded-xl text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary transition bg-surface">
                </div>
                <div>
                    <label class="block text-xs font-bold text-outline uppercase tracking-wider mb-1.5">Jam Selesai</label>
                    <input type="time" id="libur-end-time" value="16:00" onchange="updatePresetButtonStyles(null);"
                        class="w-full px-3 py-2.5 border border-outline-variant/50 rounded-xl text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary transition bg-surface">
                </div>
            </div>

            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeModal('modal-libur-cepat')" 
                        class="flex-1 py-3 text-center border border-outline-variant/50 rounded-xl text-sm font-bold text-outline hover:bg-surface-container-low transition-all">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 py-3 bg-error hover:opacity-90 text-white rounded-xl text-sm font-bold transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">event_busy</span>
                    Simpan Libur
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
    let calendar;

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 650,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            locale: 'id',
            allDaySlot: false,
            slotMinTime: '07:00:00',
            slotMaxTime: '16:00:00',
            selectable: true,
            editable: true,
            displayEventEnd: true,
            eventDisplay: 'block',
            events: '/admin/api/kalender',
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false,
                omitZeroMinute: false
            },

            eventContent: function(arg) {
                let timeText = arg.timeText;
                let title = arg.event.title;
                
                let container = document.createElement('div');
                container.className = 'flex flex-col p-1 h-full justify-between';

                if (timeText) {
                    let timeEl = document.createElement('div');
                    timeEl.className = 'font-bold text-[9px] opacity-90 flex items-center gap-1 border-b border-current/10 pb-0.5 mb-1';
                    timeEl.innerHTML = `<svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>` + timeText;
                    container.appendChild(timeEl);
                }

                let titleEl = document.createElement('div');
                titleEl.className = 'text-[10px] leading-tight font-semibold whitespace-normal break-words flex-1';
                titleEl.innerHTML = title;
                container.appendChild(titleEl);

                return { domNodes: [container] };
            },
            
            // Ketika box kosong di klik
            select: function(info) {
                document.getElementById('kalender-form').reset();
                document.getElementById('event-id').value = '';
                document.getElementById('modal-title').innerText = 'Tambah Ketersediaan';
                document.getElementById('btn-delete').classList.add('hidden');
                
                // Set default to "Tersedia"
                document.getElementById('status-available').checked = true;
                
                // Format for datetime-local is YYYY-MM-DDTHH:mm
                const pad = n => n < 10 ? '0'+n : n;
                const d1 = info.start;
                const setStart = `${d1.getFullYear()}-${pad(d1.getMonth()+1)}-${pad(d1.getDate())}T${pad(d1.getHours())}:${pad(d1.getMinutes())}`;
                
                const slotSelect = document.getElementById('event-slot-select');
                if (info.allDay) {
                    slotSelect.value = '08:00-09:30';
                    const datePart = `${d1.getFullYear()}-${pad(d1.getMonth()+1)}-${pad(d1.getDate())}`;
                    document.getElementById('event-start').value = `${datePart}T08:00`;
                    document.getElementById('event-end').value = `${datePart}T09:30`;
                } else {
                    document.getElementById('event-start').value = setStart;
                    if(info.end) {
                        const d2 = info.end;
                        const setEnd = `${d2.getFullYear()}-${pad(d2.getMonth()+1)}-${pad(d2.getDate())}T${pad(d2.getHours())}:${pad(d2.getMinutes())}`;
                        document.getElementById('event-end').value = setEnd;
                        
                        const startHours = pad(d1.getHours()) + ':' + pad(d1.getMinutes());
                        const endHours = pad(d2.getHours()) + ':' + pad(d2.getMinutes());
                        const slotValue = `${startHours}-${endHours}`;
                        const optionExists = Array.from(slotSelect.options).some(opt => opt.value === slotValue);
                        if (optionExists) {
                            slotSelect.value = slotValue;
                        } else {
                            slotSelect.value = 'custom';
                        }
                    } else {
                        document.getElementById('event-end').value = '';
                        slotSelect.value = 'custom';
                    }
                }
                
                openModal('modal-kalender');
            },

            // Ketika event yang sudah ada di klik
            eventClick: function(info) {
                const event = info.event;
                
                if (event.extendedProps.is_readonly) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Sesi Terjadwal',
                        text: 'Slot ini berisi sesi bimbingan aktif dengan siswa dan tidak dapat diubah dari kalender.',
                        confirmButtonColor: '#005050'
                    });
                    return;
                }

                document.getElementById('event-id').value = event.extendedProps.db_id;
                document.getElementById('modal-title').innerText = 'Edit Ketersediaan';
                document.getElementById('event-title').value = event.title;
                
                if (event.extendedProps.is_available) {
                    document.getElementById('status-available').checked = true;
                } else {
                    document.getElementById('status-unavailable').checked = true;
                }

                const pad = n => n < 10 ? '0'+n : n;
                const d1 = event.start;
                const d2 = event.end;
                
                if (d1) {
                    document.getElementById('event-start').value = `${d1.getFullYear()}-${pad(d1.getMonth()+1)}-${pad(d1.getDate())}T${pad(d1.getHours())}:${pad(d1.getMinutes())}`;
                } else {
                    document.getElementById('event-start').value = '';
                }
                
                if (d2) {
                    document.getElementById('event-end').value = `${d2.getFullYear()}-${pad(d2.getMonth()+1)}-${pad(d2.getDate())}T${pad(d2.getHours())}:${pad(d2.getMinutes())}`;
                } else {
                    document.getElementById('event-end').value = '';
                }

                // Match to standard slot
                const slotSelect = document.getElementById('event-slot-select');
                if (d1 && d2) {
                    const startHours = pad(d1.getHours()) + ':' + pad(d1.getMinutes());
                    const endHours = pad(d2.getHours()) + ':' + pad(d2.getMinutes());
                    const matchVal = `${startHours}-${endHours}`;
                    const optionExists = Array.from(slotSelect.options).some(opt => opt.value === matchVal);
                    if (optionExists) {
                        slotSelect.value = matchVal;
                    } else {
                        slotSelect.value = 'custom';
                    }
                } else {
                    slotSelect.value = 'custom';
                }

                document.getElementById('btn-delete').classList.remove('hidden');
                openModal('modal-kalender');
            },

            eventDrop: function(info) {
                if (info.event.extendedProps.is_readonly) {
                    info.revert();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tidak Diizinkan',
                        text: 'Jadwal sesi bimbingan aktif tidak dapat dipindahkan dari kalender.',
                        confirmButtonColor: '#005050'
                    });
                    return;
                }
                updateAjax(info.event);
            },
            eventResize: function(info) {
                if (info.event.extendedProps.is_readonly) {
                    info.revert();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tidak Diizinkan',
                        text: 'Durasi sesi bimbingan aktif tidak dapat diubah dari kalender.',
                        confirmButtonColor: '#005050'
                    });
                    return;
                }
                updateAjax(info.event);
            }
        });

       
        if (window.innerWidth >= 1024) {
            const observer = new MutationObserver((mutations) => {
                let toUpdate = new Set();
                mutations.forEach(m => {
                    if (m.attributeName === 'style') {
                        const el = m.target;
                        if (el.classList.contains('fc-daygrid-event-harness') || el.classList.contains('fc-timegrid-event-harness')) {
                            if (el.style.getPropertyValue('--zoomed') !== '1') {
                                toUpdate.add(el);
                            }
                        }
                    }
                });

                if (toUpdate.size > 0) {
                    observer.disconnect(); // prevent infinite loop
                    toUpdate.forEach(el => {
                        ['left', 'right', 'top', 'bottom', 'margin-top'].forEach(prop => {
                            let val = el.style[prop];
                            if (val && val.endsWith('px') && val !== '0px') {
                                el.style[prop] = (parseFloat(val) * 1.25) + 'px';
                            }
                        });
                        el.style.setProperty('--zoomed', '1');
                    });
                    observer.observe(calendarEl, { attributes: true, subtree: true, attributeFilter: ['style'] });
                }
            });
            observer.observe(calendarEl, { attributes: true, subtree: true, attributeFilter: ['style'] });
        }

        calendar.render();
    });

    function saveEvent(e) {
        e.preventDefault();
        
        const id = document.getElementById('event-id').value;
        const data = {
            title: document.getElementById('event-title').value,
            start: document.getElementById('event-start').value,
            end: document.getElementById('event-end').value,
            is_available: document.getElementById('status-available').checked ? 1 : 0,
            _token: document.querySelector('meta[name="csrf-token"]').content
        };

        const url = id ? `/admin/api/kalender/${id}` : `/admin/api/kalender`;
        const method = id ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        }).then(res => res.json()).then(res => {
            if(res.success) {
                calendar.refetchEvents();
                closeModal('modal-kalender');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Jadwal ketersediaan berhasil disimpan',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire('Error', 'Gagal menyimpan ketersediaan', 'error');
            }
        });
    }

    function deleteEvent() {
        const id = document.getElementById('event-id').value;
        if(!id) return;
        
        Swal.fire({
            title: 'Hapus Jadwal?',
            text: "Anda yakin ingin menghapus slot ketersediaan ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ba1a1a',
            cancelButtonColor: '#6f7979',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/api/kalender/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        _token: document.querySelector('meta[name="csrf-token"]').content
                    })
                }).then(res => res.json()).then(res => {
                    if(res.success) {
                        calendar.refetchEvents();
                        closeModal('modal-kalender');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Slot ketersediaan telah dihapus.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire('Error', 'Gagal menghapus ketersediaan', 'error');
                    }
                });
            }
        });
    }

    function updateAjax(event) {
        const dbId = event.extendedProps.db_id;
        if (!dbId || event.extendedProps.is_readonly) return;
        
        const pad = n => n < 10 ? '0'+n : n;
        const d1 = event.start;
        const startStr = `${d1.getFullYear()}-${pad(d1.getMonth()+1)}-${pad(d1.getDate())}T${pad(d1.getHours())}:${pad(d1.getMinutes())}`;
        
        let endStr = null;
        if(event.end) {
            const d2 = event.end;
            endStr = `${d2.getFullYear()}-${pad(d2.getMonth()+1)}-${pad(d2.getDate())}T${pad(d2.getHours())}:${pad(d2.getMinutes())}`;
        }

        const data = {
            title: event.title,
            start: startStr,
            end: endStr,
            is_available: event.extendedProps.is_available ? 1 : 0,
            _token: document.querySelector('meta[name="csrf-token"]').content
        };

        fetch(`/admin/api/kalender/${dbId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        }).then(res => res.json()).then(res => {
            if(res.success) {
                // optional success microinteraction
            } else {
                calendar.refetchEvents();
                Swal.fire('Error', 'Gagal memperbarui jadwal', 'error');
            }
        });
    }

    function updatePresetButtonStyles(activeType) {
        const btnHariIni = document.getElementById('btn-preset-hari-ini');
        const btnBesok = document.getElementById('btn-preset-besok');
        
        if (!btnHariIni || !btnBesok) return;

        const activeClasses = ['bg-primary', 'text-white', 'border-primary'];
        const inactiveClasses = ['bg-surface-container-low', 'text-on-surface', 'border-outline-variant/30', 'hover:bg-primary/10', 'hover:text-primary'];

        if (activeType === 'hari_ini') {
            activeClasses.forEach(c => {
                btnHariIni.classList.add(c);
                btnBesok.classList.remove(c);
            });
            inactiveClasses.forEach(c => {
                btnHariIni.classList.remove(c);
                btnBesok.classList.add(c);
            });
        } else if (activeType === 'besok') {
            activeClasses.forEach(c => {
                btnBesok.classList.add(c);
                btnHariIni.classList.remove(c);
            });
            inactiveClasses.forEach(c => {
                btnBesok.classList.remove(c);
                btnHariIni.classList.add(c);
            });
        } else {
            activeClasses.forEach(c => {
                btnHariIni.classList.remove(c);
                btnBesok.classList.remove(c);
            });
            inactiveClasses.forEach(c => {
                btnHariIni.classList.add(c);
                btnBesok.classList.add(c);
            });
        }
    }

    function quickUnavailable() {
        const pad = n => n < 10 ? '0'+n : n;
        const d = new Date();
        const todayStr = `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;
        
        document.getElementById('libur-cepat-form').reset();
        document.getElementById('libur-start-date').value = todayStr;
        document.getElementById('libur-end-date').value = todayStr;
        document.getElementById('libur-title').value = 'Libur / Off';
        document.getElementById('libur-seharian').checked = true;
        
        toggleLiburTimes();
        openModal('modal-libur-cepat');
        updatePresetButtonStyles('hari_ini');
    }

    function toggleLiburTimes() {
        const seharian = document.getElementById('libur-seharian').checked;
        const timeFields = document.getElementById('libur-time-fields');
        if (seharian) {
            timeFields.classList.add('hidden');
            document.getElementById('libur-start-time').removeAttribute('required');
            document.getElementById('libur-end-time').removeAttribute('required');
        } else {
            timeFields.classList.remove('hidden');
            document.getElementById('libur-start-time').setAttribute('required', 'required');
            document.getElementById('libur-end-time').setAttribute('required', 'required');
        }
    }

    function syncLiburEndDate() {
        const startVal = document.getElementById('libur-start-date').value;
        const endInput = document.getElementById('libur-end-date');
        if (startVal && (!endInput.value || endInput.value < startVal)) {
            endInput.value = startVal;
        }
    }

    function setPresetLibur(type) {
        const pad = n => n < 10 ? '0'+n : n;
        const d = new Date();
        
        if (type === 'hari_ini') {
            const todayStr = `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;
            document.getElementById('libur-start-date').value = todayStr;
            document.getElementById('libur-end-date').value = todayStr;
        } else if (type === 'besok') {
            d.setDate(d.getDate() + 1);
            const tomorrowStr = `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;
            document.getElementById('libur-start-date').value = tomorrowStr;
            document.getElementById('libur-end-date').value = tomorrowStr;
        }
        
        document.getElementById('libur-seharian').checked = true;
        toggleLiburTimes();
        updatePresetButtonStyles(type);
    }

    function saveLiburCepat(e) {
        e.preventDefault();
        
        const seharian = document.getElementById('libur-seharian').checked;
        const startDate = document.getElementById('libur-start-date').value;
        const endDate = document.getElementById('libur-end-date').value;
        
        let startVal = '';
        let endVal = '';
        
        if (seharian) {
            startVal = startDate + 'T00:00';
            endVal = endDate + 'T23:59';
        } else {
            const startTime = document.getElementById('libur-start-time').value;
            const endTime = document.getElementById('libur-end-time').value;
            startVal = startDate + 'T' + startTime;
            endVal = endDate + 'T' + endTime;
        }
        
        const data = {
            title: document.getElementById('libur-title').value,
            start: startVal,
            end: endVal,
            is_available: 0,
            _token: document.querySelector('meta[name="csrf-token"]').content
        };

        fetch('/admin/api/kalender', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        }).then(res => res.json()).then(res => {
            if(res.success) {
                calendar.refetchEvents();
                closeModal('modal-libur-cepat');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Waktu Libur/Off berhasil disimpan',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire('Error', 'Gagal menyimpan waktu libur', 'error');
            }
        });
    }

    function applyStandardSlot(value) {
        if (value === 'custom') {
            return;
        }
        const [startTime, endTime] = value.split('-');
        
        const startInput = document.getElementById('event-start');
        const endInput = document.getElementById('event-end');
        
        if (startInput.value) {
            const datePart = startInput.value.split('T')[0];
            startInput.value = `${datePart}T${startTime}`;
            endInput.value = `${datePart}T${endTime}`;
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const startInput = document.getElementById('event-start');
        const endInput = document.getElementById('event-end');
        const slotSelect = document.getElementById('event-slot-select');

        if (startInput && endInput && slotSelect) {
            startInput.addEventListener('change', () => {
                slotSelect.value = 'custom';
            });
            endInput.addEventListener('change', () => {
                slotSelect.value = 'custom';
            });
        }
    });
</script>
@endsection
