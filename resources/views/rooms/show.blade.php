@extends('layouts.app')

@section('title', 'Detail Ruangan')

@section('content')
<style>
    /* Enhanced Table Styling */
    .booking-table tbody tr {
        transition: all 0.3s ease;
    }
    
    .booking-table tbody tr:hover {
        background-color: #f0fdff !important;
        transform: translateX(4px);
        box-shadow: 0 2px 8px rgba(28, 239, 244, 0.1);
    }
    
    .user-avatar {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #1ceff4 0%, #17a2b8 100%);
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }
    
    .booking-table tbody tr:hover .user-avatar {
        transform: scale(1.1);
        box-shadow: 0 2px 8px rgba(28, 239, 244, 0.4);
    }
    
    .status-badge {
        transition: all 0.2s ease;
        cursor: default;
    }
    
    .status-badge:hover {
        transform: scale(1.05);
    }
    
    .view-all-btn {
        background: linear-gradient(135deg, #1ceff4 0%, #17a2b8 100%);
        color: white;
        border: none;
        padding: 0.5rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .view-all-btn:hover {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(28, 239, 244, 0.3);
        color: white;
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .booking-table {
            font-size: 0.8rem !important;
        }
        
        .booking-table th,
        .booking-table td {
            padding: 0.5rem !important;
        }
        
        .user-avatar {
            width: 28px;
            height: 28px;
            font-size: 0.75rem;
        }
    }
</style>

<div class="container-fluid">
    <div class="mb-4">
        <h2 class="fw-bold mb-1">{{ $room->nama_room }}</h2>
        <p class="text-muted mb-0"><i class="bi bi-geo-alt me-1"></i>{{ $room->lokasi }}</p>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-info-circle me-2"></i>Detail Ruangan</span>
                    @can('isAdmin')
                    <a href="{{ route('rooms.edit', $room->id_room) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 fw-semibold">Nama Ruangan:</div>
                        <div class="col-md-9">{{ $room->nama_room }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-semibold">Lokasi:</div>
                        <div class="col-md-9">{{ $room->lokasi }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-semibold">Kapasitas:</div>
                        <div class="col-md-9">
                            <span class="badge bg-info"><i class="bi bi-people me-1"></i>{{ $room->kapasitas }} orang</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-semibold">Deskripsi:</div>
                        <div class="col-md-9">{{ $room->deskripsi ?? '-' }}</div>
                    </div>
                    @if($room->foto)
                    <div class="row">
                        <div class="col-md-3 fw-semibold">Foto:</div>
                        <div class="col-md-9">
                            <a href="{{ (\Illuminate\Support\Facades\Storage::disk('public')->exists($room->foto) ? \Illuminate\Support\Facades\Storage::disk('public')->url($room->foto) : asset('images/placeholder-room.svg')) }}" class="glightbox" data-gallery="room-gallery">
                                        <img src="{{ (\Illuminate\Support\Facades\Storage::disk('public')->exists($room->foto) ? \Illuminate\Support\Facades\Storage::disk('public')->url($room->foto) : asset('images/placeholder-room.svg')) }}" alt="{{ $room->nama_room }}" 
                                     class="img-fluid rounded shadow-sm" 
                                     style="max-height: 300px; cursor: pointer; transition: all 0.3s;"
                                     onmouseover="this.style.transform='scale(1.05)'"
                                     onmouseout="this.style.transform='scale(1)'">
                                    </a>
                            <small class="text-muted d-block mt-2"><i class="bi bi-zoom-in me-1"></i>Klik untuk memperbesar</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Bookings -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-calendar-check me-2"></i>Riwayat Peminjaman</span>
                    <span class="badge" style="background: linear-gradient(135deg, #1ceff4 0%, #17a2b8 100%); color: white; padding: 0.4rem 0.8rem; font-size: 0.875rem;">
                        {{ $room->bookings->count() }} Total
                    </span>
                </div>
                <div class="card-body p-0">
                    @if($room->bookings->isEmpty())
                    <div class="text-center py-5 px-3">
                        <div class="mb-3">
                            <i class="bi bi-calendar-x" style="font-size: 4rem; color: #cbd5e1;"></i>
                        </div>
                        <h5 class="text-muted mb-2">Belum Ada Riwayat Peminjaman</h5>
                        <p class="text-muted small mb-0">Ruangan ini belum pernah dipinjam</p>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 booking-table" style="font-size: 0.9rem;">
                            <thead style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-bottom: 2px solid #dee2e6;">
                                <tr>
                                    <th class="py-3 px-3" style="font-weight: 600; color: #191919;">
                                        <i class="bi bi-person-circle me-1"></i>Peminjam
                                    </th>
                                    <th class="py-3 px-3" style="font-weight: 600; color: #191919;">
                                        <i class="bi bi-clipboard-check me-1"></i>Keperluan
                                    </th>
                                    <th class="py-3 px-3" style="font-weight: 600; color: #191919;">
                                        <i class="bi bi-calendar-date me-1"></i>Tanggal
                                    </th>
                                    <th class="py-3 px-3" style="font-weight: 600; color: #191919;">
                                        <i class="bi bi-clock me-1"></i>Waktu
                                    </th>
                                    <th class="py-3 px-3 text-center" style="font-weight: 600; color: #191919;">
                                        <i class="bi bi-check-circle me-1"></i>Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($room->bookings->sortByDesc('created_at')->take(10) as $index => $booking)
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td class="py-3 px-3">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center me-2 user-avatar">
                                                {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                                            </div>
                                            <span class="fw-semibold">{{ $booking->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="text-muted">{{ Str::limit($booking->keperluan, 30) }}</span>
                                    </td>
                                    <td class="py-3 px-3">
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold" style="color: #191919;">{{ $booking->tanggal_mulai->format('d M Y') }}</span>
                                            @if($booking->tanggal_mulai->format('Y-m-d') !== $booking->tanggal_selesai->format('Y-m-d'))
                                            <small class="text-muted">s/d {{ $booking->tanggal_selesai->format('d M Y') }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="badge" style="background-color: #f1f5f9; color: #475569; font-weight: 500; padding: 0.4rem 0.6rem;">
                                            <i class="bi bi-clock-fill me-1"></i>{{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        @if($booking->status === 'approved')
                                        <span class="badge status-badge" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); color: #065f46; font-weight: 600; padding: 0.4rem 0.8rem;">
                                            <i class="bi bi-check-circle-fill me-1"></i>Approved
                                        </span>
                                        @elseif($booking->status === 'pending')
                                        <span class="badge status-badge" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #92400e; font-weight: 600; padding: 0.4rem 0.8rem;">
                                            <i class="bi bi-clock-fill me-1"></i>Pending
                                        </span>
                                        @elseif($booking->status === 'rejected')
                                        <span class="badge status-badge" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: #991b1b; font-weight: 600; padding: 0.4rem 0.8rem;">
                                            <i class="bi bi-x-circle-fill me-1"></i>Rejected
                                        </span>
                                        @else
                                        <span class="badge bg-secondary status-badge">{{ ucfirst($booking->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($room->bookings->count() > 10)
                    <div class="p-3 text-center" style="background-color: #f8f9fa; border-top: 1px solid #dee2e6;">
                        <a href="{{ route('bookings.index', ['room' => $room->id_room]) }}" class="btn btn-sm view-all-btn">
                            <i class="bi bi-list-ul me-2"></i>Lihat Semua Peminjaman ({{ $room->bookings->count() }})
                        </a>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Jadwal Reguler -->
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-calendar3 me-2"></i>Jadwal Reguler
                </div>
                <div class="card-body">
                    @if($room->jadwalReguler->isEmpty())
                    <p class="text-muted mb-0">Tidak ada jadwal reguler</p>
                    @else
                    @foreach($room->jadwalReguler as $jadwal)
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="fw-semibold">{{ $jadwal->nama_kegiatan }}</div>
                        <small class="text-muted">
                            <i class="bi bi-calendar me-1"></i>{{ $jadwal->hari }}
                        </small><br>
                        <small class="text-muted">
                            <i class="bi bi-clock me-1"></i>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                        </small><br>
                        <small class="text-muted">
                            <i class="bi bi-person me-1"></i>{{ $jadwal->penanggung_jawab }}
                        </small>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-body">
                    <a href="{{ route('rooms.index') }}" class="btn btn-secondary btn-custom w-100 mb-2">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                    </a>
                    @can('isAdmin')
                    <a href="{{ route('rooms.edit', $room->id_room) }}" class="btn btn-warning btn-custom w-100">
                        <i class="bi bi-pencil me-2"></i>Edit Ruangan
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
