<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Peminjaman Ruangan</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 0.25rem;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-radius: 12px;
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #e9ecef;
            padding: 1.25rem;
            font-weight: 600;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1rem;
        }
        
        .stat-card h3 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }
        
        .stat-card p {
            margin: 0;
            opacity: 0.9;
        }
        
        .badge-status {
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .badge-disetujui {
            background: #d1fae5;
            color: #065f46;
        }
        
        .badge-ditolak {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .badge-selesai {
            background: #e0e7ff;
            color: #3730a3;
        }
        
        .btn-custom {
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        .table {
            font-size: 0.9rem;
        }
        
        .table th {
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-4" style="width: 260px;">
            <div class="mb-4">
                <h4 class="fw-bold mb-0">
                    <i class="bi bi-grid-3x3-gap-fill me-2"></i>
                    Peminjaman Ruang
                </h4>
                <small class="opacity-75">{{ ucfirst($user->role) }} Panel</small>
            </div>
            
            <div class="mb-4">
                <div class="d-flex align-items-center mb-2">
                    <div class="bg-white bg-opacity-25 rounded-circle p-2 me-2">
                        <i class="bi bi-person-circle fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-semibold">{{ $user->name }}</div>
                        <small class="opacity-75">{{ $user->email }}</small>
                    </div>
                </div>
            </div>
            
            <hr class="opacity-25 my-3">
            
            <nav class="nav flex-column">
                <a href="{{ route('dashboard') }}" class="nav-link active">
                    <i class="bi bi-house-door me-2"></i> Dashboard
                </a>
                
                @if($user->role !== 'peminjam')
                <a href="{{ route('rooms.index') }}" class="nav-link">
                    <i class="bi bi-door-open me-2"></i> Kelola Ruangan
                </a>
                @endif
                
                <a href="{{ route('bookings.index') }}" class="nav-link">
                    <i class="bi bi-calendar-check me-2"></i> 
                    @if($user->role === 'peminjam')
                        Peminjaman Saya
                    @else
                        Kelola Peminjaman
                    @endif
                </a>
                
                <a href="{{ route('jadwal-reguler.index') }}" class="nav-link">
                    <i class="bi bi-calendar3 me-2"></i> Jadwal Reguler
                </a>
                
                @if($user->role === 'peminjam')
                <a href="{{ route('bookings.create') }}" class="nav-link">
                    <i class="bi bi-plus-circle me-2"></i> Ajukan Peminjaman
                </a>
                @endif
            </nav>
            
            <hr class="opacity-25 my-3">
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </form>
        </div>
        
        <!-- Main Content -->
        <div class="flex-grow-1 p-4">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold mb-1">Dashboard</h2>
                        <p class="text-muted mb-0">Selamat datang, {{ $user->name }}!</p>
                    </div>
                    <div class="text-muted">
                        <i class="bi bi-calendar3 me-2"></i>
                        {{ now()->isoFormat('dddd, D MMMM Y') }}
                    </div>
                </div>
                
                <!-- Stats Cards -->
                <div class="row g-3 mb-4">
                    @if($user->role !== 'peminjam')
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-door-open fs-3 mb-2 opacity-75"></i>
                            <h3>{{ $totalRooms }}</h3>
                            <p>Total Ruangan</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <i class="bi bi-calendar-check fs-3 mb-2 opacity-75"></i>
                            <h3>{{ $totalBookings }}</h3>
                            <p>Total Peminjaman</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <i class="bi bi-clock-history fs-3 mb-2 opacity-75"></i>
                            <h3>{{ $pendingBookings->count() }}</h3>
                            <p>Menunggu Persetujuan</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                            <i class="bi bi-people fs-3 mb-2 opacity-75"></i>
                            <h3>{{ $totalUsers }}</h3>
                            <p>Peminjam Terdaftar</p>
                        </div>
                    </div>
                    @else
                    <div class="col-md-4">
                        <div class="stat-card">
                            <i class="bi bi-clock-history fs-3 mb-2 opacity-75"></i>
                            <h3>{{ $pendingCount }}</h3>
                            <p>Menunggu Persetujuan</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                            <i class="bi bi-check-circle fs-3 mb-2 opacity-75"></i>
                            <h3>{{ $approvedCount }}</h3>
                            <p>Disetujui</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <i class="bi bi-calendar-check fs-3 mb-2 opacity-75"></i>
                            <h3>{{ $myBookings->count() }}</h3>
                            <p>Total Peminjaman</p>
                        </div>
                    </div>
                    @endif
                </div>
                
                @if($user->role !== 'peminjam')
                <!-- Pending Bookings for Admin/Petugas -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-clock-history me-2"></i>Menunggu Persetujuan</span>
                        <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-primary-custom btn-custom">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @if($pendingBookings->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-check-circle fs-1 mb-3 d-block"></i>
                            <p>Tidak ada peminjaman yang menunggu persetujuan</p>
                        </div>
                        @else
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
                                    @foreach($pendingBookings as $booking)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $booking->user->name }}</div>
                                            <small class="text-muted">{{ $booking->user->email }}</small>
                                        </td>
                                        <td>{{ $booking->room->nama_room }}</td>
                                        <td>{{ $booking->keperluan }}</td>
                                        <td>{{ $booking->tanggal_mulai->format('d M Y') }}</td>
                                        <td>
                                            {{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }}
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
                @else
                <!-- Recent Bookings for Peminjam -->
                <div class="card">
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
                                        <td>{{ $booking->keperluan }}</td>
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
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
