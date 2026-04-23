@extends('layouts.dashboard')
@section('title', 'Jadwal Guru BK')
@section('nav-title', 'Jadwal Guru BK')

@section('content')
<div class="mb-5">
    <h2 class="text-blue-600 font-bold text-xl">Kalender Ketersediaan Guru BK</h2>
    <p class="text-xs text-gray-400 mt-0.5">Lihat jadwal kosong Guru BK untuk menentukan waktu konseling Anda.</p>
</div>

<div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6">
    <div class="flex gap-4 mb-4">
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-green-500"></span>
            <span class="text-xs font-semibold text-gray-600">Tersedia</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-red-500"></span>
            <span class="text-xs font-semibold text-gray-600">Tidak Tersedia (Sibuk)</span>
        </div>
    </div>
    <div id="calendar"></div>
</div>
@endsection

@section('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            locale: 'id',
            allDaySlot: false,
            slotMinTime: '07:00:00',
            slotMaxTime: '16:00:00',
            selectable: false, 
            editable: false,   
            displayEventEnd: true,
            eventDisplay: 'block',
            events: '/siswa/api/kalender',
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
                container.className = 'flex flex-col p-0.5';

                if (timeText) {
                    let timeEl = document.createElement('div');
                    timeEl.className = 'font-bold text-[10px] opacity-100 flex items-center gap-1 border-b border-white/20 pb-0.5 mb-0.5';
                    timeEl.innerHTML = `<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>` + timeText.replace(' - ', ' - ');
                    container.appendChild(timeEl);
                }

                let titleEl = document.createElement('div');
                titleEl.className = 'text-[10px] leading-tight font-medium whitespace-normal break-words';
                titleEl.innerHTML = title;
                container.appendChild(titleEl);

                return { domNodes: [container] };
            },
            
            eventClick: function(info) {
                const event = info.event;
                let statusMsg = event.extendedProps.is_available 
                    ? 'Guru BK TERSEDIA pada waktu ini. Silakan ajukan konseling.' 
                    : 'Guru BK TIDAK TERSEDIA (Sibuk) pada waktu ini.';
                    
                Swal.fire({
                    title: event.title,
                    text: statusMsg,
                    icon: event.extendedProps.is_available ? 'info' : 'warning',
                    confirmButtonText: 'Tutup'
                });
            }
        });
        calendar.render();
    });
</script>
<style>
/* Kostumisasi Fullcalendar Modern */
.fc { font-family: 'Poppins', sans-serif; }
.fc-toolbar-title { font-size: 1.1rem !important; font-weight: 700 !important; color: #1e3a8a; }
.fc-button-primary { 
    background-color: #2563eb !important; 
    border-color: transparent !important; 
    border-radius: 0.5rem !important; 
    text-transform: capitalize; 
    font-family: 'Poppins', sans-serif;
    font-size: 0.875rem !important;
    padding: 0.4rem 1rem !important;
    box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    transition: all 0.2s;
}
.fc-button-primary:hover {
    background-color: #1d4ed8 !important;
}
.fc-button-primary:not(:disabled):active, .fc-button-primary:not(:disabled).fc-button-active {
    background-color: #1e40af !important;
}

.fc-button-group {
    display: flex;
    gap: 0.35rem;
}
.fc-button-group > .fc-button {
    border-radius: 0.5rem !important;
    margin: 0 !important;
}
.fc-header-toolbar { margin-bottom: 1.5rem !important; gap: 1rem; flex-wrap: wrap; }
.fc-event { 
    border-radius: 0.375rem !important; 
    padding: 3px 6px; 
    font-size: 0.75rem; 
    margin-bottom: 3px !important; 
    border: none !important; 
    font-weight: 500; 
    cursor: pointer;
    white-space: normal !important;
}

/* Custom Grid & Borders */
.fc-theme-standard td, .fc-theme-standard th { border-color: #f1f5f9 !important; }
.fc-theme-standard .fc-scrollgrid { border: 1px solid #e2e8f0 !important; border-radius: 0.75rem !important; overflow: hidden; }
.fc-col-header-cell-cushion { padding: 12px 4px !important; font-weight: 600 !important; color: #64748b; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; }
.fc-daygrid-day-number { font-weight: 600; font-size: 0.875rem; color: #475569; padding: 8px 10px !important; }
.fc-day-today { background-color: #f8fafc !important; }
.fc-timegrid-slot { height: 2.5em !important; }

.fc-event-main, .fc-event-title {
    white-space: normal !important;
    word-wrap: break-word;
}
.fc-event-title, .fc-event-time {
    font-size: 0.75rem;
}
.fc-event-time {
    font-weight: 700;
}
</style>
@endsection
