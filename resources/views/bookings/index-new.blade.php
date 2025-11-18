@extends('layouts.app')

@section('title', 'Kelola Peminjaman')

@section('content')
<style>
    .modern-tabs {
        background: #ffffff;
        border-radius: 16px;
        padding: 0.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
        display: inline-flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .tab-modern {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        border: none;
        background: transparent;
        color: #808080;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    
    .tab-modern:hover {
        background: #f8f9fa;
        color: #191919;
    }
    
    .tab-modern.active {
        background: linear-gradient(135deg, #1ceff4 0%, #0dd1d6 100%);
        color: #191919;
        box-shadow: 0 4px 12px rgba(28,239,244,0.3);
    }
    
    .tab-badge {
        display: inline-block;
        background: rgba(255,255,255,0.3);
        padding: 0.15rem 0.5rem;
        border-radius: 50px;
        font-size: 0.75rem;
        margin-left: 0.5rem;
    }
    
    .tab-modern.active .tab-badge {
        background: rgba(25,25,25,0.2);
    }
    
    .booking-card-modern {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .booking-card-modern:hover {
        transform: translateX(5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .booking-card-modern.status-pending {
        border-left-color: #fbbf24;
    }
    
    .booking-card-modern.status-approved {
        border-left-color: #10b981;
    }
    
    .booking-card-modern.status-rejected {
        border-left-color: #ef4444;
    }
    
    .user-info-modern {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .user-avatar-medium {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: linear-gradient(135deg, #1ceff4 0%, #0dd1d6 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    
    .booking-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 12px;
        margin: 1rem 0;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .meta-icon {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1ceff4;
        flex-shrink: 0;
    }
    
    .status-badge-large {
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .status-badge-large.pending {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
    }
    
    .status-badge-large.approved {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }
    
    .status-badge-large.rejected {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
    }
</style>

<div class="container-fluid px-4 py-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
        <div>
            <h2 class="fw-bold mb-1" style="color: #191919;">
                <i class="bi bi-calendar-check-fill me-2" style="color: #1ceff4;"></i>
                @if(Auth::user()->role === 'peminjam')
                    Peminjaman Saya
                @else
                    Kelola Peminjaman
                @endif
            </h2>
            <p class="text-muted mb-0">
                @if(Auth::user()->role === 'peminjam')
                    Daftar riwayat peminjaman ruangan Anda
                @else
                    Manajemen semua peminjaman ruangan
                @endif
            </p>
        </div>
        @if(Auth::user()->role === 'peminjam')
        <a href="{{ route('bookings.create') }}" class="btn btn-modern btn-primary-modern">
            <i class="bi bi-plus-circle me-2"></i>Ajukan Peminjaman
        </a>
        @endif
    </div>
    
    @if($bookings->isEmpty())
    <!-- Empty State -->
    <div class="empty-state-modern" data-aos="fade-up">
        <div class="empty-icon">
            <i class="bi bi-calendar-x"></i>
        </div>
        <h4 style="color: #191919; font-weight: 700;">Belum Ada Peminjaman</h4>
        <p style="color: #808080; font-size: 1.05rem;">
            @if(Auth::user()->role === 'peminjam')
                Mulai ajukan peminjaman ruangan pertama Anda
            @else
                Belum ada data peminjaman di sistem
            @endif
        </p>
        @if(Auth::user()->role === 'peminjam')
        <a href="{{ route('bookings.create') }}" class="btn btn-modern btn-primary-modern mt-3">
            <i class="bi bi-plus-circle me-2"></i>Ajukan Peminjaman Sekarang
        </a>
        @endif
    </div>
    @else
    <!-- Tabs -->
    @if(Auth::user()->role !== 'peminjam')
    <div class="mb-4" data-aos="fade-up">
        <div class="modern-tabs">
            <button class="tab-modern active" onclick="filterBookings('all')">
                <i class="bi bi-list-ul me-2"></i>Semua
                <span class="tab-badge">{{ $bookings->count() }}</span>
            </button>
            <button class="tab-modern" onclick="filterBookings('pending')">
                <i class="bi bi-hourglass-split me-2"></i>Pending
                <span class="tab-badge">{{ $bookings->where('status', 'pending')->count() }}</span>
            </button>
            <button class="tab-modern" onclick="filterBookings('approved')">
                <i class="bi bi-check-circle me-2"></i>Disetujui
                <span class="tab-badge">{{ $bookings->where('status', 'approved')->count() }}</span>
            </button>
            <button class="tab-modern" onclick="filterBookings('rejected')">
                <i class="bi bi-x-circle me-2"></i>Ditolak
                <span class="tab-badge">{{ $bookings->where('status', 'rejected')->count() }}</span>
            </button>
        </div>
    </div>
    @endif
    
    <!-- Bookings List -->
    <div class="row g-4">
        @foreach($bookings->sortByDesc('created_at') as $index => $booking)
        <div class="col-12 booking-item" 
             data-status="{{ $booking->status }}"
             data-aos="fade-up" 
             data-aos-delay="{{ ($index % 5) * 50 }}">
            <div class="booking-card-modern status-{{ $booking->status }}">
                <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-3">
                    <div class="user-info-modern">
                        <div class="user-avatar-medium">
                            {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1" style="color: #191919;">{{ $booking->user->name }}</h5>
                            <p class="text-muted mb-0" style="font-size: 0.9rem;">
                                <i class="bi bi-envelope me-1"></i>{{ $booking->user->email }}
                            </p>
                        </div>
                    </div>
                    <span class="status-badge-large {{ $booking->status }}">
                        @if($booking->status == 'pending')
                            <i class="bi bi-hourglass-split"></i> Pending
                        @elseif($booking->status == 'approved')
                            <i class="bi bi-check-circle-fill"></i> Disetujui
                        @else
                            <i class="bi bi-x-circle-fill"></i> Ditolak
                        @endif
                    </span>
                </div>
                
                <div class="booking-meta">
                    <div class="meta-item">
                        <div class="meta-icon">
                            <i class="bi bi-door-open-fill"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Ruangan</small>
                            <strong style="color: #191919;">{{ $booking->room->nama_room }}</strong>
                        </div>
                    </div>
                    
                    <div class="meta-item">
                        <div class="meta-icon">
                            <i class="bi bi-calendar-date"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Tanggal</small>
                            <strong style="color: #191919;">{{ $booking->tanggal_mulai->format('d M Y') }}</strong>
                        </div>
                    </div>
                    
                    <div class="meta-item">
                        <div class="meta-icon">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Waktu</small>
                            <strong style="color: #191919;">{{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }}</strong>
                        </div>
                    </div>
                    
                    <div class="meta-item">
                        <div class="meta-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Peserta</small>
                            <strong style="color: #191919;">{{ $booking->jumlah_peserta ?? 'N/A' }} Orang</strong>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">
                        <i class="bi bi-card-text me-1"></i>Keperluan
                    </small>
                    <p class="mb-0" style="color: #191919;">{{ $booking->keperluan }}</p>
                </div>
                
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('bookings.show', $booking->id_booking) }}" 
                       class="btn btn-modern btn-outline-modern flex-grow-1">
                        <i class="bi bi-eye"></i> Detail
                    </a>
                    @if(Auth::user()->role !== 'peminjam' && $booking->status == 'pending')
                    <a href="{{ route('bookings.edit', $booking->id_booking) }}" 
                       class="btn btn-modern btn-primary-modern">
                        <i class="bi bi-pencil"></i> Review
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@section('scripts')
<script>
    function filterBookings(status) {
        // Update active tab
        document.querySelectorAll('.tab-modern').forEach(tab => {
            tab.classList.remove('active');
        });
        event.target.closest('.tab-modern').classList.add('active');
        
        // Filter bookings with animation
        document.querySelectorAll('.booking-item').forEach((item, index) => {
            if (status === 'all' || item.dataset.status === status) {
                item.style.display = '';
                item.style.animation = `fadeInUp 0.4s ease ${index * 0.05}s both`;
            } else {
                item.style.display = 'none';
            }
        });
    }
    
    // Add CSS animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    `;
    document.head.appendChild(style);
</script>
@endsection
@endsection
