@extends('layouts.admin')
@section('title', 'Kalender Ketersediaan')
@section('nav-title', 'Kalender Ketersediaan')

@section('content')
<div class="mb-5">
    <h2 class="text-blue-600 font-bold text-xl">Kalender Ketersediaan Guru BK</h2>
    <p class="text-xs text-gray-400 mt-0.5">Kelola jadwal kosong, rapat, atau jadwal layanan Anda.</p>
</div>

<div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6">
    <div id="calendar"></div>
</div>
@endsection

@section('modals')
{{-- Modal Tambah/Edit Jadwal --}}
<div id="modal-kalender" class="hidden fixed inset-0 bg-black/40 z-[100] items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-2xl">
        <div class="flex justify-between items-center mb-5">
            <h3 class="font-bold text-lg text-gray-800" id="modal-title">Tambah Ketersediaan</h3>
            <button onclick="closeModal('modal-kalender')" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
        </div>
        
        <form id="kalender-form" onsubmit="saveEvent(event)">
            <input type="hidden" id="event-id">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Ketersediaan</label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="flex items-center gap-2 p-2 border rounded-xl cursor-pointer hover:bg-gray-50 transition">
                        <input type="radio" name="is_available" id="status-available" value="1" checked class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-green-600">Tersedia</span>
                    </label>
                    <label class="flex items-center gap-2 p-2 border rounded-xl cursor-pointer hover:bg-gray-50 transition">
                        <input type="radio" name="is_available" id="status-unavailable" value="0" class="w-4 h-4 text-red-600 focus:ring-red-500">
                        <span class="text-sm font-medium text-red-600">Tidak Bisa</span>
                    </label>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan / Topik</label>
                <input type="text" id="event-title" required placeholder="Misal: Konseling Tersedia / Rapat Bersama"
                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl text-sm outline-none focus:border-blue-400 transition">
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mulai</label>
                    <input type="datetime-local" id="event-start" required
                        class="w-full px-3 py-2 border-2 border-gray-200 rounded-xl text-xs outline-none focus:border-blue-400 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Selesai</label>
                    <input type="datetime-local" id="event-end"
                        class="w-full px-3 py-2 border-2 border-gray-200 rounded-xl text-xs outline-none focus:border-blue-400 transition">
                </div>
            </div>

            <div class="flex gap-2 justify-end">
                <button type="button" id="btn-delete" onclick="deleteEvent()" class="hidden px-4 py-2 bg-red-100 text-red-600 hover:bg-red-200 rounded-xl text-sm font-semibold transition">Hapus</button>
                <button type="button" onclick="closeModal('modal-kalender')" class="px-4 py-2 border-2 text-gray-600 hover:bg-gray-50 rounded-xl text-sm font-semibold transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 rounded-xl text-sm font-semibold transition">Simpan</button>
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
            
            // Ketika box kosong di klik
            select: function(info) {
                document.getElementById('kalender-form').reset();
                document.getElementById('event-id').value = '';
                document.getElementById('modal-title').innerText = 'Tambah Ketersediaan';
                document.getElementById('btn-delete').classList.add('hidden');
                
                // Set default dates based on selection
                // Format for datetime-local is YYYY-MM-DDTHH:mm
                const pad = n => n < 10 ? '0'+n : n;
                const d1 = info.start;
                const setStart = `${d1.getFullYear()}-${pad(d1.getMonth()+1)}-${pad(d1.getDate())}T${pad(d1.getHours())}:${pad(d1.getMinutes())}`;
                document.getElementById('event-start').value = setStart;
                
                if(info.end) {
                    const d2 = info.end;
                    const setEnd = `${d2.getFullYear()}-${pad(d2.getMonth()+1)}-${pad(d2.getDate())}T${pad(d2.getHours())}:${pad(d2.getMinutes())}`;
                    document.getElementById('event-end').value = setEnd;
                }
                
                openModal('modal-kalender');
            },

            // Ketika event yang sudah ada di klik
            eventClick: function(info) {
                const event = info.event;
                document.getElementById('event-id').value = event.id;
                document.getElementById('modal-title').innerText = 'Edit Jadwal';
                document.getElementById('event-title').value = event.title;
                
                if (event.extendedProps.is_available) {
                    document.getElementById('status-available').checked = true;
                } else {
                    document.getElementById('status-unavailable').checked = true;
                }

                const pad = n => n < 10 ? '0'+n : n;
                const d1 = event.start;
                if (d1) {
                    document.getElementById('event-start').value = `${d1.getFullYear()}-${pad(d1.getMonth()+1)}-${pad(d1.getDate())}T${pad(d1.getHours())}:${pad(d1.getMinutes())}`;
                }
                
                const d2 = event.end;
                if (d2) {
                    document.getElementById('event-end').value = `${d2.getFullYear()}-${pad(d2.getMonth()+1)}-${pad(d2.getDate())}T${pad(d2.getHours())}:${pad(d2.getMinutes())}`;
                } else {
                    document.getElementById('event-end').value = '';
                }

                document.getElementById('btn-delete').classList.remove('hidden');
                openModal('modal-kalender');
            },

            eventDrop: function(info) {
                updateAjax(info.event);
            },
            eventResize: function(info) {
                updateAjax(info.event);
            }
        });
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
                    text: 'Jadwal berhasil disimpan',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire('Error', 'Gagal menyimpan', 'error');
            }
        });
    }

    function deleteEvent() {
        const id = document.getElementById('event-id').value;
        if(!id) return;
        
        if(!confirm('Hapus jadwal ini?')) return;

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
            }
        });
    }

    function updateAjax(event) {
        // dipanggil saat drag-drop resize
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

        fetch(`/admin/api/kalender/${event.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
    }
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
/* Pisahkan button group agar tidak rapet */
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
