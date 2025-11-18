@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="mb-4" data-aos="fade-down">
        <h2 class="fw-bold mb-1">Selamat Datang, {{ Auth::user()->name }}! 👋</h2>
        <p class="text-muted mb-0">{{ ucfirst(Auth::user()->role) }} Panel - {{ now()->format('d F Y') }}</p>
    </div>
    
    @if(Auth::user()->role !== 'peminjam')
    <!-- Stats Cards for Admin/Petugas -->
    <div class="row mb-4">
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
            <div class="card border-0" style="background: linear-gradient(135deg, #1ceff4 0%, #0dd1d6 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Total Ruangan</h6>
                            <h2 class="fw-bold mb-0">{{ $totalRooms }}</h2>
                        </div>
                        <i class="bi bi-door-open fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
            <div class="card border-0" style="background: linear-gradient(135deg, #191919 0%, #808080 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Total Peminjaman</h6>
                            <h2 class="fw-bold mb-0">{{ $totalBookings }}</h2>
                        </div>
                        <i class="bi bi-calendar-check fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
            <div class="card border-0" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Menunggu Approval</h6>
                            <h2 class="fw-bold mb-0">{{ $statusPending }}</h2>
                        </div>
                        <i class="bi bi-hourglass-split fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
            <div class="card border-0" style="background: linear-gradient(135deg, #b2b2b2 0%, #808080 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Total Peminjam</h6>
                            <h2 class="fw-bold mb-0">{{ $totalUsers }}</h2>
                        </div>
                        <i class="bi bi-people fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-lg-8" data-aos="fade-right">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-graph-up me-2"></i>Trend Peminjaman (7 Hari Terakhir)
                </div>
                <div class="card-body">
                    <canvas id="bookingTrendChart" height="80"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4" data-aos="fade-left">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-pie-chart me-2"></i>Status Peminjaman
                </div>
                <div class="card-body">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Room Usage Chart -->
    <div class="row mb-4">
        <div class="col-12" data-aos="fade-up">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-bar-chart me-2"></i>Ruangan Paling Sering Digunakan
                </div>
                <div class="card-body">
                    <canvas id="roomUsageChart" height="60"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pending Bookings Table -->
    @if($pendingBookings->count() > 0)
    <div class="card" data-aos="fade-up">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-clock-history me-2"></i>Peminjaman Menunggu Approval ({{ $pendingBookings->count() }})</span>
            <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-primary-custom">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Peminjam</th>
                            <th>Ruangan</th>
                            <th>Keperluan</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingBookings->take(5) as $booking)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $booking->user->name }}</div>
                                <small class="text-muted">{{ $booking->user->email }}</small>
                            </td>
                            <td>{{ $booking->room->nama_room }}</td>
                            <td>{{ Str::limit($booking->keperluan, 30) }}</td>
                            <td>{{ $booking->tanggal_mulai->format('d M Y') }}</td>
                            <td>
                                {{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }}
                            </td>
                            <td>
                                <a href="{{ route('bookings.show', $booking->id_booking) }}" class="btn btn-sm btn-primary-custom">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="card" data-aos="fade-up">
        <div class="card-body text-center py-5">
            <i class="bi bi-check-circle text-success fs-1 mb-3 d-block"></i>
            <h5>Tidak Ada Peminjaman Pending</h5>
            <p class="text-muted">Semua peminjaman sudah diproses</p>
        </div>
    </div>
    @endif
    
    @else
    <!-- Dashboard for Peminjam -->
    <div class="row mb-4">
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card border-0" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Menunggu Approval</h6>
                            <h2 class="fw-bold mb-0">{{ $pendingCount }}</h2>
                        </div>
                        <i class="bi bi-hourglass-split fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card border-0" style="background: linear-gradient(135deg, #1ceff4 0%, #0dd1d6 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Disetujui</h6>
                            <h2 class="fw-bold mb-0">{{ $approvedCount }}</h2>
                        </div>
                        <i class="bi bi-check-circle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
            <div class="card border-0" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Ditolak</h6>
                            <h2 class="fw-bold mb-0">{{ $rejectedCount }}</h2>
                        </div>
                        <i class="bi bi-x-circle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Bookings for Peminjam -->
    <div class="card" data-aos="fade-up">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-calendar-check me-2"></i>Riwayat Peminjaman</span>
            <a href="{{ route('bookings.create') }}" class="btn btn-sm btn-primary-custom btn-custom">
                <i class="bi bi-plus-circle me-1"></i>Ajukan Peminjaman
            </a>
        </div>
        <div class="card-body p-0">
            @if($myBookings->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-calendar-x fs-1 mb-3 d-block"></i>
                <p>Belum ada riwayat peminjaman</p>
                <a href="{{ route('bookings.create') }}" class="btn btn-primary-custom btn-custom mt-2">
                    Ajukan Peminjaman Pertama
                </a>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Ruangan</th>
                            <th>Keperluan</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($myBookings as $booking)
                        <tr>
                            <td>{{ $booking->room->nama_room }}</td>
                            <td>{{ Str::limit($booking->keperluan, 30) }}</td>
                            <td>{{ $booking->tanggal_mulai->format('d M Y') }}</td>
                            <td>
                                {{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }}
                            </td>
                            <td>
                                <span class="badge-status badge-{{ $booking->status }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('bookings.show', $booking->id_booking) }}" class="btn btn-sm btn-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@if(Auth::user()->role !== 'peminjam')
<script>
    // Booking Trend Chart
    const trendCtx = document.getElementById('bookingTrendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartDates) !!},
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: {!! json_encode($chartCounts) !!},
                borderColor: '#1ceff4',
                backgroundColor: 'rgba(28, 239, 244, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#1ceff4',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    
    // Status Chart (Donut)
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Disetujui', 'Ditolak'],
            datasets: [{
                data: [{{ $statusPending }}, {{ $statusApproved }}, {{ $statusRejected }}],
                backgroundColor: ['#fbbf24', '#1ceff4', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    
    // Room Usage Chart
    const roomCtx = document.getElementById('roomUsageChart').getContext('2d');
    new Chart(roomCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($roomStats->pluck('nama_room')) !!},
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: {!! json_encode($roomStats->pluck('bookings_count')) !!},
                backgroundColor: [
                    '#1ceff4',
                    '#0dd1d6',
                    '#808080',
                    '#b2b2b2',
                    '#191919'
                ],
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endif
@endsection
