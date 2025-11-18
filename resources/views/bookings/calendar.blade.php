@extends('layouts.app')

@section('title', 'Calendar Peminjaman')

@section('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css' rel='stylesheet' />
<style>
    .fc {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
    }
    
    .fc-toolbar-title {
        font-weight: 700 !important;
        color: var(--color-black);
    }
    
    .fc-button {
        background: linear-gradient(135deg, var(--color-cyan) 0%, var(--color-cyan-dark) 100%) !important;
        border: none !important;
        color: var(--color-black) !important;
        font-weight: 600 !important;
        text-transform: uppercase;
        font-size: 0.875rem;
    }
    
    .fc-button:hover {
        box-shadow: 0 4px 15px rgba(28, 239, 244, 0.4) !important;
    }
    
    .fc-button-active {
        background: linear-gradient(135deg, var(--color-black) 0%, var(--color-grey) 100%) !important;
        color: white !important;
    }
    
    .fc-event {
        border-radius: 4px;
        padding: 2px 4px;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .fc-event:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    
    .fc-daygrid-day-number {
        color: var(--color-black);
        font-weight: 600;
    }
    
    .fc-col-header-cell {
        background: rgba(28, 239, 244, 0.1);
        font-weight: 700;
        color: var(--color-black);
    }
    
    .legend-item {
        display: inline-flex;
        align-items: center;
        margin-right: 1rem;
        margin-bottom: 0.5rem;
    }
    
    .legend-color {
        width: 20px;
        height: 20px;
        border-radius: 4px;
        margin-right: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="mb-4" data-aos="fade-down">
        <h2 class="fw-bold mb-1">Calendar Peminjaman Ruangan 📅</h2>
        <p class="text-muted mb-0">Lihat jadwal peminjaman dalam tampilan kalender</p>
    </div>
    
    <!-- Legend -->
    <div class="card mb-3" data-aos="fade-up">
        <div class="card-body">
            <strong class="me-3">Keterangan Status:</strong>
            <div class="d-inline-block">
                <span class="legend-item">
                    <span class="legend-color" style="background: #fbbf24;"></span>
                    <span>Pending</span>
                </span>
                <span class="legend-item">
                    <span class="legend-color" style="background: #1ceff4;"></span>
                    <span>Disetujui</span>
                </span>
                <span class="legend-item">
                    <span class="legend-color" style="background: #ef4444;"></span>
                    <span>Ditolak</span>
                </span>
            </div>
        </div>
    </div>
    
    <!-- Calendar -->
    <div class="card" data-aos="fade-up" data-aos-delay="100">
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<!-- Event Detail Modal -->
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--color-black) 0%, var(--color-grey) 100%); color: white;">
                <h5 class="modal-title">Detail Peminjaman</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>Ruangan:</strong>
                    <p id="modal-room" class="mb-0"></p>
                </div>
                <div class="mb-3">
                    <strong>Peminjam:</strong>
                    <p id="modal-user" class="mb-0"></p>
                </div>
                <div class="mb-3">
                    <strong>Keperluan:</strong>
                    <p id="modal-keperluan" class="mb-0"></p>
                </div>
                <div class="mb-3">
                    <strong>Status:</strong>
                    <span id="modal-status"></span>
                </div>
            </div>
            <div class="modal-footer">
                <a id="modal-detail-btn" href="#" class="btn btn-primary-custom">Lihat Detail Lengkap</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
        
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu',
                day: 'Hari',
                list: 'List'
            },
            locale: 'id',
            firstDay: 1,
            height: 'auto',
            events: {!! json_encode($events) !!},
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                
                const event = info.event;
                const props = event.extendedProps;
                
                // Set modal content
                document.getElementById('modal-room').textContent = props.room;
                document.getElementById('modal-user').textContent = props.user;
                document.getElementById('modal-keperluan').textContent = props.keperluan;
                
                const statusBadge = document.getElementById('modal-status');
                statusBadge.className = 'badge-status badge-' + props.status;
                statusBadge.textContent = props.status.charAt(0).toUpperCase() + props.status.slice(1);
                
                document.getElementById('modal-detail-btn').href = props.url;
                
                // Show modal
                eventModal.show();
            },
            eventDidMount: function(info) {
                // Add tooltip
                info.el.title = info.event.title;
            }
        });
        
        calendar.render();
        
        // Add animation when calendar loads
        setTimeout(() => {
            calendar.updateSize();
        }, 300);
    });
</script>
@endsection
