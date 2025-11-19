@extends('layouts.app')

@section('title', 'Jadwal Reguler')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Jadwal Reguler</li>
@endsection

@section('content')
<style>
    .day-card-modern {
        background: #ffffff;
        border-radius: 16px;
        padding: 0;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        overflow: hidden;
        transition: all 0.3s ease;
        border: none;
    }
    
    .day-card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }
    
    .day-header {
        padding: 1.25rem 1.5rem;
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        position: relative;
        overflow: hidden;
    }
    
    .day-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100%;
        background: rgba(255,255,255,0.1);
        transform: skewX(-20deg);
    }
    
    .day-header i {
        font-size: 1.3rem;
    }
    
    .day-senin { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
    .day-selasa { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); }
    .day-rabu { background: linear-gradient(135deg, #eab308 0%, #ca8a04 100%); }
    .day-kamis { background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); }
    .day-jumat { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
    .day-sabtu { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
    .day-minggu { background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); }
    
    .schedule-item-modern {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .schedule-item-modern:last-child {
        border-bottom: none;
    }
    
    .schedule-item-modern:hover {
        background: #f8f9fa;
        padding-left: 1.75rem;
    }
    
    .schedule-item-modern::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background: linear-gradient(135deg, #1ceff4 0%, #0dd1d6 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .schedule-item-modern:hover::before {
        opacity: 1;
    }
    
    .activity-title {
        font-weight: 700;
        font-size: 1.05rem;
        color: #191919;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .activity-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 0.75rem;
    }
    
    .meta-item-inline {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #808080;
        font-size: 0.9rem;
    }
    
    .meta-icon-inline {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, #f0f0f0 0%, #e8e8e8 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1ceff4;
        flex-shrink: 0;
    }
    
    .empty-day {
        padding: 2rem;
        text-align: center;
        color: #b2b2b2;
    }
    
    .table-view-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .table-view-header {
        background: linear-gradient(135deg, #191919 0%, #2d2d2d 100%);
        color: white;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .table-modern {
        width: 100%;
        margin-bottom: 0;
    }
    
    .table-modern thead th {
        background: #f8f9fa;
        border: none;
        padding: 1rem 1.5rem;
        font-weight: 700;
        color: #191919;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .table-modern tbody td {
        padding: 1.25rem 1.5rem;
        border: none;
        border-bottom: 1px solid #f0f0f0;
        color: #191919;
    }
    
    .table-modern tbody tr:hover {
        background: #f8f9fa;
    }
    
    .day-badge-inline {
        padding: 0.4rem 1rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.8rem;
        display: inline-block;
        color: white;
    }
    
    .view-toggle {
        background: #ffffff;
        border-radius: 12px;
        padding: 0.5rem;
        display: inline-flex;
        gap: 0.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .view-btn {
        padding: 0.5rem 1.25rem;
        border: none;
        background: transparent;
        border-radius: 8px;
        font-weight: 600;
        color: #808080;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .view-btn.active {
        background: linear-gradient(135deg, #1ceff4 0%, #0dd1d6 100%);
        color: #191919;
        box-shadow: 0 2px 8px rgba(28,239,244,0.3);
    }
</style>

<div class="container-fluid px-4 py-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3" data-aos="fade-down">
        <div>
            <h2 class="fw-bold mb-1" style="color: #191919;">
                <i class="bi bi-calendar3-fill me-2" style="color: #1ceff4;"></i>
                Jadwal Reguler Ruangan
            </h2>
            <p class="text-muted mb-0">Daftar kegiatan reguler yang terjadwal di setiap ruangan</p>
        </div>
        <div class="d-flex gap-3 align-items-center">
            <div class="view-toggle">
                <button class="view-btn active" onclick="switchView('cards', event)">
                    <i class="bi bi-grid-3x3-gap me-2"></i>Kartu
                </button>
                <button class="view-btn" onclick="switchView('table', event)">
                    <i class="bi bi-table me-2"></i>Tabel
                </button>
            </div>
            @if(Auth::user()->role !== 'peminjam')
            <a href="{{ route('jadwal-reguler.create') }}" class="btn btn-modern btn-primary-modern">
                <i class="bi bi-plus-circle me-2"></i>Tambah Jadwal
            </a>
            @endif
        </div>
    </div>
    
    @if($jadwals->isEmpty())
    <!-- Empty State -->
    <div class="empty-state-modern" data-aos="fade-up">
        <div class="empty-icon">
            <i class="bi bi-calendar-x"></i>
        </div>
        <h4 style="color: #191919; font-weight: 700;">Belum Ada Jadwal Reguler</h4>
        <p style="color: #808080; font-size: 1.05rem;">
            Mulai tambahkan jadwal kegiatan rutin di ruangan
        </p>
        @if(Auth::user()->role !== 'peminjam')
        <a href="{{ route('jadwal-reguler.create') }}" class="btn btn-modern btn-primary-modern mt-3">
            <i class="bi bi-plus-circle me-2"></i>Tambah Jadwal Pertama
        </a>
        @endif
    </div>
    @else
    
    <!-- Card View (Default) -->
    <div id="cardsView" class="row g-4">
        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $dayIndex => $hari)
            @php
                $jadwalHari = $jadwals->where('hari', $hari);
                $dayClass = 'day-' . strtolower($hari);
            @endphp
            <div class="col-lg-6 col-xl-4" data-aos="fade-up" data-aos-delay="{{ $dayIndex * 50 }}">
                <div class="day-card-modern">
                    <div class="day-header {{ $dayClass }}">
                        <i class="bi bi-calendar-day-fill"></i>
                        <span>{{ $hari }}</span>
                        <span class="ms-auto" style="font-size: 0.9rem; opacity: 0.9;">
                            {{ $jadwalHari->count() }} Kegiatan
                        </span>
                    </div>
                    @if($jadwalHari->isEmpty())
                    <div class="empty-day">
                        <i class="bi bi-calendar-x" style="font-size: 2rem;"></i>
                        <p class="mb-0 mt-2">Tidak ada jadwal</p>
                    </div>
                    @else
                    <div>
                        @foreach($jadwalHari->sortBy('jam_mulai') as $jadwal)
                        <div class="schedule-item-modern">
                            <div class="activity-title">
                                <i class="bi bi-bookmark-fill" style="color: #1ceff4;"></i>
                                {{ $jadwal->nama_kegiatan }}
                            </div>
                            <div class="activity-meta">
                                <div class="meta-item-inline">
                                    <div class="meta-icon-inline">
                                        <i class="bi bi-door-open-fill"></i>
                                    </div>
                                    <div>
                                        <small class="d-block text-muted" style="font-size: 0.75rem;">Ruangan</small>
                                        <strong>{{ $jadwal->room->nama_room ?? '-' }}</strong>
                                    </div>
                                </div>
                                <div class="meta-item-inline">
                                    <div class="meta-icon-inline">
                                        <i class="bi bi-clock-fill"></i>
                                    </div>
                                    <div>
                                        <small class="d-block text-muted" style="font-size: 0.75rem;">Waktu</small>
                                        <strong>{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</strong>
                                    </div>
                                </div>
                                @if($jadwal->penanggung_jawab)
                                <div class="meta-item-inline">
                                    <div class="meta-icon-inline">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div>
                                        <small class="d-block text-muted" style="font-size: 0.75rem;">PJ</small>
                                        <strong>{{ $jadwal->penanggung_jawab }}</strong>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @if(Auth::user()->role !== 'peminjam')
                            <div class="mt-3 d-flex gap-2">
                                <a href="{{ route('jadwal-reguler.show', $jadwal->id_reguler) }}" 
                                   class="btn btn-sm btn-modern btn-outline-modern">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                                <a href="{{ route('jadwal-reguler.edit', $jadwal->id_reguler) }}" 
                                   class="btn btn-sm btn-modern btn-primary-modern">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <button type="button" class="btn btn-sm btn-modern btn-danger-modern" 
                                        onclick="confirmDelete('{{ $jadwal->nama_kegiatan }}', '{{ route('jadwal-reguler.destroy', $jadwal->id_reguler) }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Table View (Hidden by default) -->
    <div id="tableView" style="display: none;" data-aos="fade-up">
        <div class="table-view-card">
            <div class="table-view-header">
                <i class="bi bi-table" style="font-size: 1.5rem;"></i>
                <div>
                    <h5 class="mb-0 fw-bold">Tampilan Tabel</h5>
                    <small style="opacity: 0.8;">Semua jadwal dalam satu tabel</small>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Kegiatan</th>
                            <th>Ruangan</th>
                            <th>Hari</th>
                            <th>Waktu</th>
                            <th>Penanggung Jawab</th>
                            @if(Auth::user()->role !== 'peminjam')
                            <th style="width: 150px;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jadwals->sortBy(['hari', 'jam_mulai']) as $index => $jadwal)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $jadwal->nama_kegiatan }}</strong>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $jadwal->room->nama_room ?? '-' }}</div>
                                <small class="text-muted">{{ $jadwal->room->lokasi ?? '-' }}</small>
                            </td>
                            <td>
                                <span class="day-badge-inline day-{{ strtolower($jadwal->hari) }}">
                                    {{ $jadwal->hari }}
                                </span>
                            </td>
                            <td>
                                <i class="bi bi-clock me-1" style="color: #1ceff4;"></i>
                                {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}
                            </td>
                            <td>{{ $jadwal->penanggung_jawab ?? '-' }}</td>
                            @if(Auth::user()->role !== 'peminjam')
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('jadwal-reguler.show', $jadwal->id_reguler) }}" 
                                       class="btn btn-sm btn-modern btn-outline-modern" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('jadwal-reguler.edit', $jadwal->id_reguler) }}" 
                                       class="btn btn-sm btn-modern btn-primary-modern" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-modern btn-danger-modern" 
                                            onclick="confirmDelete('{{ $jadwal->nama_kegiatan }}', '{{ route('jadwal-reguler.destroy', $jadwal->id_reguler) }}')" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection

@section('scripts')
<script>
    function switchView(view, event) {
        // Update active button robustly
        document.querySelectorAll('.view-btn').forEach(btn => btn.classList.remove('active'));
        var btn = null;
        if (event) {
            btn = event.currentTarget || event.target.closest('.view-btn');
        }
        if (btn) btn.classList.add('active');
        
        // Switch views
        if (view === 'cards') {
            document.getElementById('cardsView').style.display = '';
            document.getElementById('tableView').style.display = 'none';
        } else {
            document.getElementById('cardsView').style.display = 'none';
            document.getElementById('tableView').style.display = '';
        }
    }
</script>
@endsection
