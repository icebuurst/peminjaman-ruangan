@extends('layouts.app')

@section('title', 'Laporan Peminjaman Ruangan')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Laporan Peminjaman Ruangan</h2>
        <p class="text-muted mb-0">Filter dan export data peminjaman ruangan</p>
    </div>
    
    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-funnel me-2"></i>Filter Laporan
        </div>
        <div class="card-body">
            <form action="{{ route('bookings.laporan') }}" method="GET" id="filterForm">
                <div class="row align-items-end">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="{{ $startDate }}" required>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label for="end_date" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="{{ $endDate }}" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary-custom btn-custom me-2">
                            <i class="bi bi-search me-2"></i>Tampilkan
                        </button>
                        <button type="button" class="btn btn-secondary btn-custom" onclick="resetFilter()">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-white-50">Total Peminjaman</h6>
                    <h2 class="mb-0">{{ $bookings->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-white-50">Disetujui</h6>
                    <h2 class="mb-0">{{ $bookings->where('status', 'approved')->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-white-50">Pending</h6>
                    <h2 class="mb-0">{{ $bookings->where('status', 'pending')->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-white-50">Ditolak</h6>
                    <h2 class="mb-0">{{ $bookings->where('status', 'rejected')->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Button -->
    @if($bookings->count() > 0)
    <div class="mb-3">
        <form action="{{ route('bookings.export') }}" method="GET" class="d-inline">
            <input type="hidden" name="start_date" value="{{ $startDate }}">
            <input type="hidden" name="end_date" value="{{ $endDate }}">
            <input type="hidden" name="format" value="pdf">
            <button type="submit" class="btn btn-danger btn-custom">
                <i class="bi bi-file-earmark-pdf me-2"></i>Export ke PDF
            </button>
        </form>
        <span class="text-muted ms-2">
            <i class="bi bi-info-circle me-1"></i>
            Total {{ $bookings->count() }} data akan di-export
        </span>
    </div>
    @endif

    <!-- Data Table -->
    <div class="card">
        <div class="card-header">
            <i class="bi bi-table me-2"></i>Preview Data Peminjaman
            <span class="badge bg-secondary ms-2">{{ $bookings->count() }} data</span>
        </div>
        <div class="card-body">
            @if($bookings->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Tanggal Booking</th>
                            <th>Peminjam</th>
                            <th>Ruangan</th>
                            <th>Keperluan</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Jam</th>
                            <th>Peserta</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $index => $booking)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $booking->created_at ? $booking->created_at->format('d/m/Y H:i') : '-' }}</td>
                            <td>
                                <div class="fw-bold">{{ $booking->user->nama }}</div>
                                <small class="text-muted">{{ $booking->user->email }}</small>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $booking->room->nama_room }}</div>
                                <small class="text-muted">{{ $booking->room->lokasi }}</small>
                            </td>
                            <td>{{ Str::limit($booking->keperluan, 40) }}</td>
                            <td>{{ $booking->tanggal_mulai->format('d/m/Y') }}</td>
                            <td>{{ $booking->tanggal_selesai->format('d/m/Y') }}</td>
                            <td>
                                <small>{{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }}</small>
                            </td>
                            <td class="text-center">{{ $booking->jumlah_peserta ?? '-' }}</td>
                            <td>
                                @if($booking->status == 'pending')
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @elseif($booking->status == 'approved')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif($booking->status == 'rejected')
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                <p class="text-muted mt-3">Tidak ada data peminjaman pada periode ini</p>
                <p class="text-muted small">Silakan ubah filter tanggal untuk melihat data lain</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function resetFilter() {
    // Set to current month
    const now = new Date();
    const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
    const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
    
    document.getElementById('start_date').value = firstDay.toISOString().split('T')[0];
    document.getElementById('end_date').value = lastDay.toISOString().split('T')[0];
    
    document.getElementById('filterForm').submit();
}

// Validate end_date >= start_date
document.getElementById('start_date').addEventListener('change', function() {
    const startDate = this.value;
    document.getElementById('end_date').min = startDate;
});

document.getElementById('end_date').addEventListener('change', function() {
    const endDate = this.value;
    const startDate = document.getElementById('start_date').value;
    
    if (endDate < startDate) {
        alert('Tanggal selesai tidak boleh lebih kecil dari tanggal mulai');
        this.value = startDate;
    }
});
</script>

<style>
/* Responsive untuk Halaman Laporan */
@media (max-width: 768px) {
    /* Summary Cards - Stack ke bawah */
    .row.mb-4 .col-md-3 {
        margin-bottom: 1rem !important;
    }
    
    /* Filter Form - Stack buttons */
    .col-md-4 {
        margin-bottom: 1rem !important;
    }
    
    .col-md-4 button {
        width: 100% !important;
        margin-bottom: 0.5rem !important;
    }
    
    .col-md-4 .btn.me-2 {
        margin-right: 0 !important;
        margin-bottom: 0.5rem !important;
    }
    
    /* Export Section */
    .d-inline {
        display: block !important;
        width: 100%;
    }
    
    .d-inline button {
        width: 100% !important;
        margin-bottom: 1rem !important;
    }
    
    .ms-2 {
        margin-left: 0 !important;
        display: block !important;
        margin-top: 0.5rem !important;
    }
    
    /* Table responsive enhancement */
    .table-responsive {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin: -1px;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch !important;
    }
    
    .table-responsive table {
        min-width: 1000px !important;
        margin-bottom: 0 !important;
    }
    
    /* Table cells - Compact pada mobile */
    .table th,
    .table td {
        font-size: 0.8rem !important;
        padding: 0.5rem !important;
        white-space: nowrap;
    }
    
    .table td small {
        font-size: 0.7rem !important;
    }
    
    /* Summary cards - Compact */
    .card.bg-primary,
    .card.bg-success,
    .card.bg-warning,
    .card.bg-danger {
        margin-bottom: 0.75rem !important;
    }
    
    .card.bg-primary .card-body,
    .card.bg-success .card-body,
    .card.bg-warning .card-body,
    .card.bg-danger .card-body {
        padding: 1rem !important;
    }
    
    .card.bg-primary h2,
    .card.bg-success h2,
    .card.bg-warning h2,
    .card.bg-danger h2 {
        font-size: 1.75rem !important;
    }
    
    .card.bg-primary h6,
    .card.bg-success h6,
    .card.bg-warning h6,
    .card.bg-danger h6 {
        font-size: 0.85rem !important;
    }
    
    /* Filter card header */
    .card-header {
        font-size: 0.95rem !important;
        padding: 0.75rem 1rem !important;
    }
    
    /* Badge pada header */
    .badge.ms-2 {
        margin-left: 0.5rem !important;
        font-size: 0.7rem !important;
    }
}

@media (max-width: 480px) {
    /* Ultra compact untuk layar kecil */
    h2.fw-bold {
        font-size: 1.25rem !important;
    }
    
    .card-body {
        padding: 0.75rem !important;
    }
    
    .table th,
    .table td {
        font-size: 0.75rem !important;
        padding: 0.4rem !important;
    }
    
    /* Summary cards - More compact */
    .card.bg-primary h2,
    .card.bg-success h2,
    .card.bg-warning h2,
    .card.bg-danger h2 {
        font-size: 1.5rem !important;
    }
    
    /* Date inputs lebih kecil */
    .form-control,
    .form-label {
        font-size: 0.85rem !important;
    }
    
    /* Buttons */
    .btn {
        font-size: 0.8rem !important;
        padding: 0.5rem 1rem !important;
    }
}

/* Horizontal scroll indicator */
@media (max-width: 768px) {
    .table-responsive::after {
        content: "⟵ Geser ke kiri/kanan untuk melihat semua kolom ⟶";
        display: block;
        text-align: center;
        padding: 0.5rem;
        font-size: 0.75rem;
        color: #6c757d;
        background: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }
}
</style>
@endsection
