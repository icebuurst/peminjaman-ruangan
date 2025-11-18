@extends('layouts.app')

@section('title', Auth::user()->role === 'admin' ? 'Kelola Ruangan' : 'Daftar Ruangan')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">{{ Auth::user()->role === 'admin' ? 'Kelola Ruangan' : 'Daftar Ruangan' }}</li>
@endsection

@section('content')
<style>
    .room-card-modern {
        background: #ffffff;
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
        height: 100%;
    }
    
    .room-card-modern:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }
    
    .room-image-container {
        position: relative;
        width: 100%;
        height: 200px;
        overflow: hidden;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    
    .room-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    
    .room-card-modern:hover .room-image {
        transform: scale(1.1);
    }
    
    .room-badge-overlay {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(25, 25, 25, 0.85);
        backdrop-filter: blur(10px);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.85rem;
    }
    
    .availability-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.8rem;
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    
    .availability-badge.available {
        background: rgba(16, 185, 129, 0.95);
        color: white;
    }
    
    .availability-badge.booked {
        background: rgba(251, 191, 36, 0.95);
        color: #191919;
    }
    
    .availability-badge.busy {
        background: rgba(239, 68, 68, 0.95);
        color: white;
    }
    
    .availability-badge .pulse {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: currentColor;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
        }
        50% {
            opacity: 0.5;
            transform: scale(1.2);
        }
    }
    
    .room-info-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #191919;
    }
    
    .filter-section {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }
    /* Improved stat cards */
    .stat-card-modern {
        background: linear-gradient(180deg, #ffffff 0%, #fbfbfb 100%);
        border-radius: 12px;
        padding: 1rem;
        min-height: 72px;
        transition: transform .25s ease, box-shadow .25s ease;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 6px 18px rgba(16,24,40,0.04);
    }
    .stat-card-modern:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 40px rgba(16,24,40,0.08);
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .stat-card-modern:hover .stat-icon {
        transform: scale(1.05);
        box-shadow: 0 6px 18px rgba(16,24,40,0.06);
    }
    .stat-card-modern .stat-label {
        font-size: 0.85rem;
        color: #6b7280;
    }
    .stat-card-modern .stat-value {
        font-size: 1.35rem;
        font-weight: 700;
        color: #111827;
    }
    @media (max-width: 576px) {
        .stat-card-modern { padding: 0.75rem; }
        .stat-card-modern .stat-value { font-size: 1.1rem; }
    }
</style>

<div class="container-fluid px-4 py-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
        <div>
            <h2 class="fw-bold mb-1" style="color: #191919;">
                <i class="bi bi-door-open-fill me-2" style="color: #1ceff4;"></i>{{ Auth::user()->role === 'admin' ? 'Kelola Ruangan' : 'Daftar Ruangan' }}
            </h2>
            <p class="text-muted mb-0">
                @if(Auth::user()->role === 'admin')
                    Manajemen data ruangan yang tersedia
                @else
                    Lihat semua ruangan yang tersedia untuk dipinjam
                @endif
            </p>
        </div>
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('rooms.create') }}" class="btn btn-modern btn-primary-modern">
            <i class="bi bi-plus-circle me-2"></i>Tambah Ruangan
        </a>
        @endif
    </div>
    
    <!-- Stats Quick View -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-3" data-aos="fade-up" data-aos-delay="50">
            <div class="stat-card-modern p-3 d-flex align-items-center" style="--card-color: #1ceff4;">
                <div class="stat-icon me-3 rounded-circle" style="background: rgba(28,238,244,0.12);">
                    <i class="bi bi-building" style="font-size: 1.5rem; color: var(--card-color);"></i>
                </div>
                <div>
                    <div class="stat-label">Total Ruangan</div>
                    <div class="stat-value">{{ $rooms->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card-modern p-3 d-flex align-items-center" style="--card-color: #10b981;">
                <div class="stat-icon me-3 rounded-circle" style="background: rgba(16,185,129,0.08);">
                    <i class="bi bi-people-fill" style="font-size: 1.5rem; color: var(--card-color);"></i>
                </div>
                <div>
                    <div class="stat-label">Total Kapasitas</div>
                    <div class="stat-value">{{ $rooms->sum('kapasitas') }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3" data-aos="fade-up" data-aos-delay="150">
            <div class="stat-card-modern p-3 d-flex align-items-center" style="--card-color: #fbbf24;">
                <div class="stat-icon me-3 rounded-circle" style="background: rgba(251,191,36,0.08);">
                    <i class="bi bi-calendar-check" style="font-size: 1.5rem; color: var(--card-color);"></i>
                </div>
                <div>
                    <div class="stat-label">Total Booking</div>
                    <div class="stat-value">{{ $rooms->sum('bookings_count') }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card-modern p-3 d-flex align-items-center" style="--card-color: #808080;">
                <div class="stat-icon me-3 rounded-circle" style="background: rgba(128,128,128,0.08);">
                    <i class="bi bi-bar-chart" style="font-size: 1.5rem; color: var(--card-color);"></i>
                </div>
                <div>
                    <div class="stat-label">Avg Kapasitas</div>
                    <div class="stat-value">{{ $rooms->avg('kapasitas') ? round($rooms->avg('kapasitas')) : 0 }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Search & Filter -->
    <div class="filter-section" data-aos="fade-up">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search" style="color: #1ceff4;"></i>
                    </span>
                    <input type="text" 
                           class="form-control border-start-0" 
                           id="searchRoom" 
                           placeholder="Cari ruangan berdasarkan nama atau lokasi...">
                </div>
            </div>
            <div class="col-md-4">
                <select class="form-select form-select-lg" id="sortRoom">
                    <option value="name">Urutkan: Nama A-Z</option>
                    <option value="capacity">Urutkan: Kapasitas Terbesar</option>
                    <option value="bookings">Urutkan: Paling Populer</option>
                </select>
            </div>
        </div>
    </div>
    
    @if($rooms->isEmpty())
    <!-- Empty State -->
    <div class="empty-state-modern" data-aos="fade-up">
        <div class="empty-icon">
            <i class="bi bi-door-open"></i>
        </div>
        <h4 style="color: #191919; font-weight: 700;">Belum Ada Ruangan</h4>
        <p style="color: #808080; font-size: 1.05rem;">Tambahkan ruangan pertama untuk memulai</p>
        @if(Auth::user()->role !== 'peminjam')
        <a href="{{ route('rooms.create') }}" class="btn btn-modern btn-primary-modern mt-3">
            <i class="bi bi-plus-circle me-2"></i>Tambah Ruangan Sekarang
        </a>
        @endif
    </div>
    @else
    <!-- Rooms Grid -->
    <div class="row g-4" id="roomsGrid">
        @foreach($rooms as $index => $room)
        <div class="col-md-6 col-lg-4 room-item" 
             data-aos="fade-up" 
             data-aos-delay="{{ ($index % 3) * 100 }}"
             data-name="{{ strtolower($room->nama_room) }}"
             data-location="{{ strtolower($room->lokasi ?? '') }}"
             data-capacity="{{ $room->kapasitas }}"
             data-bookings="{{ $room->bookings_count }}">
            <div class="room-card-modern">
                <!-- Room Image -->
                <div class="room-image-container">
                    @if($room->foto)
                    <img src="{{ asset('storage/' . $room->foto) }}" 
                         alt="{{ $room->nama_room }}" 
                         class="room-image">
                    @else
                    <div class="room-image d-flex align-items-center justify-content-center">
                        <i class="bi bi-door-open" style="font-size: 4rem; color: #b2b2b2;"></i>
                    </div>
                    @endif
                    
                    <!-- Availability Badge -->
                    <div class="availability-badge {{ $room->availability_status }}">
                        <span class="pulse"></span>
                        {{ $room->availability_text }}
                    </div>
                    
                    <!-- Booking Count Badge -->
                    <div class="room-badge-overlay">
                        <i class="bi bi-star-fill me-1" style="color: #fbbf24;"></i>
                        {{ $room->bookings_count }} Bookings
                    </div>
                </div>
                
                <!-- Room Info -->
                <div class="p-4">
                    <h5 class="fw-bold mb-2" style="color: #191919;">{{ $room->nama_room }}</h5>
                    
                    <p class="text-muted mb-3" style="font-size: 0.9rem; min-height: 40px;">
                        {{ Str::limit($room->deskripsi ?? 'Tidak ada deskripsi', 80) }}
                    </p>
                    
                    <div class="d-flex gap-2 mb-3 flex-wrap">
                        <div class="room-info-tag">
                            <i class="bi bi-geo-alt-fill" style="color: #3b82f6;"></i>
                            {{ $room->lokasi ?? 'Tidak ada lokasi' }}
                        </div>
                        <div class="room-info-tag">
                            <i class="bi bi-people-fill" style="color: #10b981;"></i>
                            {{ $room->kapasitas }} Orang
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('rooms.show', $room->id_room) }}" 
                           class="btn btn-modern btn-outline-modern {{ Auth::user()->role === 'admin' ? 'flex-grow-1' : 'w-100' }}">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                        @if(Auth::user()->role === 'admin')
                        <a href="{{ route('rooms.edit', $room->id_room) }}" 
                           class="btn btn-modern btn-primary-modern flex-grow-1">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <button type="button" 
                                class="btn btn-modern" 
                                style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: #991b1b;"
                                onclick="confirmDelete('{{ $room->nama_room }}', '{{ route('rooms.destroy', $room->id_room) }}')">
                            <i class="bi bi-trash"></i>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@section('scripts')
<script>
    // Search functionality
    document.getElementById('searchRoom')?.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        document.querySelectorAll('.room-item').forEach(item => {
            const name = item.dataset.name;
            const location = item.dataset.location || '';
            const isVisible = name.includes(searchTerm) || location.includes(searchTerm);
            item.style.display = isVisible ? '' : 'none';
        });
    });
    
    // Sort functionality
    document.getElementById('sortRoom')?.addEventListener('change', function(e) {
        const sortBy = e.target.value;
        const grid = document.getElementById('roomsGrid');
        const items = Array.from(document.querySelectorAll('.room-item'));
        
        items.sort((a, b) => {
            if (sortBy === 'name') {
                return a.dataset.name.localeCompare(b.dataset.name);
            } else if (sortBy === 'capacity') {
                return parseInt(b.dataset.capacity) - parseInt(a.dataset.capacity);
            } else if (sortBy === 'bookings') {
                return parseInt(b.dataset.bookings) - parseInt(a.dataset.bookings);
            }
        });
        
        items.forEach(item => grid.appendChild(item));
    });
</script>
@endsection
@endsection
