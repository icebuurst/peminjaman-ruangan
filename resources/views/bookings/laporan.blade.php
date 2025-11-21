@extends('layouts.app')

@section('title', 'Laporan Peminjaman Ruangan')

@section('content')
<style>
    /* Modern Filter Card */
    .filter-card-modern {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        border: none;
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .filter-header-modern {
        background: linear-gradient(135deg, #1ceff4 0%, #0dd1d6 100%);
        color: #191919;
        padding: 1.25rem 1.5rem;
        font-weight: 700;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .date-input-modern {
        position: relative;
    }
    
    .date-input-modern label {
        font-weight: 600;
        color: #191919;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .date-input-modern input {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    
    .date-input-modern input:focus {
        border-color: #1ceff4;
        box-shadow: 0 0 0 0.2rem rgba(28,239,244,0.15);
    }
    
    /* Modern Stats Card */
    .stat-card-modern-report {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        border: none;
        height: 100%;
    }
    
    .stat-card-modern-report::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--card-gradient);
    }
    
    .stat-card-modern-report:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 35px rgba(0,0,0,0.12);
    }
    
    .stat-icon-modern {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        background: var(--icon-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }
    
    .stat-icon-modern i {
        font-size: 1.75rem;
        color: var(--icon-color);
    }
    
    .stat-label-modern {
        color: #808080;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }
    
    .stat-value-modern {
        color: #191919;
        font-size: 2.5rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 0;
    }
    
    /* Card Colors */
    .stat-card-total {
        --card-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --icon-bg: rgba(102, 126, 234, 0.1);
        --icon-color: #667eea;
    }
    
    .stat-card-approved {
        --card-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
        --icon-bg: rgba(16, 185, 129, 0.1);
        --icon-color: #10b981;
    }
    
    .stat-card-pending {
        --card-gradient: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        --icon-bg: rgba(251, 191, 36, 0.1);
        --icon-color: #f59e0b;
    }
    
    .stat-card-rejected {
        --card-gradient: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        --icon-bg: rgba(239, 68, 68, 0.1);
        --icon-color: #ef4444;
    }
    
    /* Modern Buttons */
    .btn-filter-modern {
        background: linear-gradient(135deg, #1ceff4 0%, #0dd1d6 100%);
        border: none;
        color: #191919;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-filter-modern:hover {
        background: linear-gradient(135deg, #0dd1d6 0%, #0ab8bc 100%);
        color: #191919;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(28,239,244,0.3);
    }
    
    .btn-reset-modern {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        color: #191919;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-reset-modern:hover {
        background: #e9ecef;
        border-color: #dee2e6;
        color: #191919;
        transform: translateY(-2px);
    }

    /* Quick Filter Buttons */
    .btn-quick-filter {
        background: linear-gradient(135deg, #f0fdff 0%, #e0f9ff 100%);
        border: 2px solid #bae6fd;
        color: #191919;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .btn-quick-filter:hover {
        background: linear-gradient(135deg, #1ceff4 0%, #0dd1d6 100%);
        border-color: #1ceff4;
        color: #191919;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(28, 239, 244, 0.3);
    }

    .btn-quick-filter i {
        font-size: 1rem;
    }

    /* Insight Cards */
    .insight-card {
        background: white;
        border-radius: 14px;
        padding: 1.25rem;
        border: 1px solid #e8edf2;
        transition: all 0.3s ease;
        height: 100%;
    }

    .insight-card:hover {
        border-color: #1ceff4;
        box-shadow: 0 4px 12px rgba(28, 239, 244, 0.1);
        transform: translateY(-2px);
    }

    .insight-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .insight-label {
        font-size: 0.8rem;
        color: #808080;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

    .insight-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #191919;
        line-height: 1.2;
        margin-bottom: 0.25rem;
    }

    .insight-meta {
        font-size: 0.85rem;
        color: #808080;
        font-weight: 500;
    }

    /* Export Card */
    .export-card {
        background: white;
        border: 2px dashed #e8edf2;
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    .export-card:hover {
        border-color: #1ceff4;
        background: linear-gradient(135deg, rgba(28, 239, 244, 0.02) 0%, rgba(28, 239, 244, 0.05) 100%);
    }

    .btn-export-pdf {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        border: none;
        padding: 0.65rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-export-pdf:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(153, 27, 27, 0.2);
        color: #7f1d1d;
    }
</style>

<div class="container-fluid px-4 py-3">
    <!-- Header -->
    <div class="mb-4" data-aos="fade-down">
        <h2 class="fw-bold mb-1" style="color: #191919;">
            <i class="bi bi-file-earmark-bar-graph-fill me-2" style="color: #1ceff4;"></i>Laporan Peminjaman Ruangan
        </h2>
        <p class="text-muted mb-0">Filter dan export data peminjaman ruangan</p>
    </div>
    
    <!-- Filter Card -->
    <div class="filter-card-modern" data-aos="fade-up">
        <div class="filter-header-modern">
            <i class="bi bi-funnel-fill"></i>
            <span>Filter Laporan</span>
        </div>
        <div class="p-4">
            <!-- Quick Filter Presets -->
            <div class="mb-3">
                <label class="form-label" style="font-weight: 600; color: #191919; font-size: 0.85rem; margin-bottom: 0.75rem;">
                    <i class="bi bi-lightning-fill me-1" style="color: #fbbf24;"></i>Quick Filter
                </label>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn-quick-filter" onclick="setQuickFilter('today')">
                        <i class="bi bi-calendar-day"></i> Hari Ini
                    </button>
                    <button type="button" class="btn-quick-filter" onclick="setQuickFilter('week')">
                        <i class="bi bi-calendar-week"></i> 7 Hari Terakhir
                    </button>
                    <button type="button" class="btn-quick-filter" onclick="setQuickFilter('month')">
                        <i class="bi bi-calendar-month"></i> Bulan Ini
                    </button>
                    <button type="button" class="btn-quick-filter" onclick="setQuickFilter('lastmonth')">
                        <i class="bi bi-calendar3"></i> Bulan Lalu
                    </button>
                    <button type="button" class="btn-quick-filter" onclick="setQuickFilter('year')">
                        <i class="bi bi-calendar-range"></i> Tahun Ini
                    </button>
                </div>
            </div>

            <hr style="border-color: #e9ecef; margin: 1.5rem 0;">

            <!-- Date Range Selector -->
            <form action="{{ route('bookings.laporan') }}" method="GET" id="filterForm">
                <div class="row align-items-end">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="date-input-modern">
                            <label for="start_date">
                                <i class="bi bi-calendar-check me-1" style="color: #1ceff4;"></i>Tanggal Mulai
                            </label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   value="{{ $startDate }}" required>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="date-input-modern">
                            <label for="end_date">
                                <i class="bi bi-calendar-x me-1" style="color: #1ceff4;"></i>Tanggal Selesai
                            </label>
                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                   value="{{ $endDate }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-filter-modern flex-grow-1">
                                <i class="bi bi-search"></i>
                                Tampilkan
                            </button>
                            <button type="button" class="btn btn-reset-modern" onclick="resetFilter()">
                                <i class="bi bi-arrow-clockwise"></i>
                                Reset
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Date Range Indicator -->
                <div class="mt-3 p-3" style="background: linear-gradient(135deg, #f0fdff 0%, #e0f9ff 100%); border-radius: 12px; border-left: 4px solid #1ceff4;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-calendar-range-fill" style="color: #1ceff4; font-size: 1.25rem;"></i>
                        <div>
                            <div style="font-size: 0.75rem; color: #808080; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Periode Dipilih</div>
                            <div style="font-size: 0.95rem; color: #191919; font-weight: 700;" id="selectedPeriod">
                                {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                            </div>
                            <div style="font-size: 0.8rem; color: #808080;" id="daysDuration">
                                <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1 }} hari
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="50">
            <div class="stat-card-modern-report stat-card-total">
                <div class="stat-icon-modern">
                    <i class="bi bi-list-ul"></i>
                </div>
                <div class="stat-label-modern">Total Peminjaman</div>
                <div class="stat-value-modern">{{ $bookings->count() }}</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card-modern-report stat-card-approved">
                <div class="stat-icon-modern">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="stat-label-modern">Disetujui</div>
                <div class="stat-value-modern">{{ $bookings->where('status', 'approved')->count() }}</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="150">
            <div class="stat-card-modern-report stat-card-pending">
                <div class="stat-icon-modern">
                    <i class="bi bi-clock-fill"></i>
                </div>
                <div class="stat-label-modern">Pending</div>
                <div class="stat-value-modern">{{ $bookings->where('status', 'pending')->count() }}</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card-modern-report stat-card-rejected">
                <div class="stat-icon-modern">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
                <div class="stat-label-modern">Ditolak</div>
                <div class="stat-value-modern">{{ $bookings->where('status', 'rejected')->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Data Insights -->
    @if($bookings->count() > 0)
    <div class="row g-3 mb-4" data-aos="fade-up">
        <!-- Most Booked Room -->
        @php
            $mostBookedRoom = $bookings->groupBy('id_room')->sortByDesc(function($group) {
                return $group->count();
            })->first();
            $approvalRate = $bookings->count() > 0 ? round(($bookings->where('status', 'approved')->count() / $bookings->count()) * 100, 1) : 0;
            $avgParticipants = $bookings->where('jumlah_peserta', '>', 0)->avg('jumlah_peserta');
        @endphp
        
        <div class="col-md-4">
            <div class="insight-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="insight-icon" style="background: rgba(139, 92, 246, 0.1);">
                        <i class="bi bi-trophy-fill" style="color: #8b5cf6;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="insight-label">Ruangan Terpopuler</div>
                        <div class="insight-value">{{ $mostBookedRoom ? $mostBookedRoom->first()->room->nama_room : '-' }}</div>
                        <div class="insight-meta">{{ $mostBookedRoom ? $mostBookedRoom->count() : 0 }} peminjaman</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approval Rate -->
        <div class="col-md-4">
            <div class="insight-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="insight-icon" style="background: rgba(16, 185, 129, 0.1);">
                        <i class="bi bi-graph-up-arrow" style="color: #10b981;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="insight-label">Tingkat Persetujuan</div>
                        <div class="insight-value">{{ $approvalRate }}%</div>
                        <div class="insight-meta">dari total peminjaman</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Avg Participants -->
        <div class="col-md-4">
            <div class="insight-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="insight-icon" style="background: rgba(251, 191, 36, 0.1);">
                        <i class="bi bi-people-fill" style="color: #fbbf24;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="insight-label">Rata-rata Peserta</div>
                        <div class="insight-value">{{ $avgParticipants ? round($avgParticipants) : 0 }}</div>
                        <div class="insight-meta">orang per peminjaman</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Section -->
    <div class="mb-4" data-aos="fade-up">
        <div class="export-card">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-download" style="color: #991b1b; font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <div style="font-weight: 700; color: #191919; font-size: 1.05rem;">Export Laporan</div>
                        <div style="font-size: 0.85rem; color: #808080;">
                            <i class="bi bi-file-earmark-text me-1"></i>
                            {{ $bookings->count() }} data siap untuk di-export
                        </div>
                    </div>
                </div>
                <form action="{{ route('bookings.export') }}" method="GET" class="m-0">
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <input type="hidden" name="format" value="pdf">
                    <button type="submit" class="btn btn-export-pdf">
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                        Export PDF
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Data Table -->
    <div class="card" style="background: #ffffff; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); border: none; overflow: hidden;" data-aos="fade-up">
        <div class="card-header" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-bottom: 2px solid #dee2e6; padding: 1.25rem 1.5rem;">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-table" style="color: #1ceff4; font-size: 1.25rem;"></i>
                    <span style="font-weight: 700; color: #191919; font-size: 1.05rem;">Preview Data Peminjaman</span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <!-- Search Box -->
                    <div class="position-relative" style="min-width: 250px;">
                        <i class="bi bi-search position-absolute" style="left: 12px; top: 50%; transform: translateY(-50%); color: #808080;"></i>
                        <input type="text" id="tableSearch" class="form-control" placeholder="Cari data..." onkeyup="searchTable()" 
                               style="border: 2px solid #e9ecef; border-radius: 12px; padding: 0.5rem 0.75rem 0.5rem 2.5rem; font-size: 0.9rem;">
                    </div>
                    <span class="badge" style="background: linear-gradient(135deg, #1ceff4 0%, #0dd1d6 100%); color: #191919; padding: 0.5rem 1rem; border-radius: 50px; font-weight: 600;">
                        {{ $bookings->count() }} data
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if($bookings->count() > 0)
            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Tanggal Booking</th>
                            <th>Peminjam</th>
                            <th>Ruangan</th>
                            <th>Keperluan</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Jam</th>
                            <th class="text-center">Peserta</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $index => $booking)
                        <tr>
                            <td><strong>{{ $index + 1 }}</strong></td>
                            <td>
                                <div style="color: #191919; font-weight: 600;">{{ $booking->created_at ? $booking->created_at->format('d/m/Y') : '-' }}</div>
                                <small style="color: #808080;">{{ $booking->created_at ? $booking->created_at->format('H:i') : '' }}</small>
                            </td>
                            <td>
                                <div style="color: #191919; font-weight: 600;">{{ $booking->user->name }}</div>
                                <small style="color: #808080;">{{ $booking->user->email }}</small>
                            </td>
                            <td>
                                <div style="color: #191919; font-weight: 600;">{{ $booking->room->nama_room }}</div>
                                <small style="color: #808080;">
                                    <i class="bi bi-geo-alt-fill me-1"></i>{{ $booking->room->lokasi }}
                                </small>
                            </td>
                            <td>{{ Str::limit($booking->keperluan, 40) }}</td>
                            <td>
                                <div style="color: #191919; font-weight: 600;">{{ $booking->tanggal_mulai->format('d M Y') }}</div>
                            </td>
                            <td>
                                <div style="color: #191919; font-weight: 600;">{{ $booking->tanggal_selesai->format('d M Y') }}</div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span style="color: #191919; font-weight: 600;">{{ substr($booking->jam_mulai, 0, 5) }}</span>
                                    <small style="color: #808080;">s/d {{ substr($booking->jam_selesai, 0, 5) }}</small>
                                </div>
                            </td>
                            <td class="text-center">
                                <span style="background: #f8f9fa; padding: 0.4rem 0.8rem; border-radius: 8px; font-weight: 600; color: #191919;">
                                    <i class="bi bi-people-fill me-1" style="color: #1ceff4;"></i>{{ $booking->jumlah_peserta ?? '-' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($booking->status == 'pending')
                                    <span class="badge-modern badge-pending-modern">
                                        <i class="bi bi-clock-fill"></i>Menunggu
                                    </span>
                                @elseif($booking->status == 'approved')
                                    <span class="badge-modern badge-approved-modern">
                                        <i class="bi bi-check-circle-fill"></i>Disetujui
                                    </span>
                                @elseif($booking->status == 'rejected')
                                    <span class="badge-modern badge-rejected-modern">
                                        <i class="bi bi-x-circle-fill"></i>Ditolak
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state-report">
                <i class="bi bi-inbox"></i>
                <h5>Tidak ada data peminjaman</h5>
                <p>Tidak ada data peminjaman pada periode ini</p>
                <p style="color: #b2b2b2; font-size: 0.9rem;">Silakan ubah filter tanggal untuk melihat data lain</p>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Modern Table Styling */
.table-modern {
    margin-bottom: 0;
}

.table-modern thead {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.table-modern thead th {
    border: none;
    color: #191919;
    font-weight: 700;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem;
}

.table-modern tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f1f5f9;
}

.table-modern tbody tr:hover {
    background: #f0fdff;
    transform: translateX(4px);
}

.table-modern tbody td {
    padding: 1rem;
    vertical-align: middle;
    color: #191919;
    font-size: 0.9rem;
}

/* Modern Badge Status */
.badge-modern {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.8rem;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}

.badge-pending-modern {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
}

.badge-approved-modern {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
}

.badge-rejected-modern {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #991b1b;
}

/* Empty State */
.empty-state-report {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-state-report i {
    font-size: 5rem;
    color: #cbd5e1;
    margin-bottom: 1.5rem;
}

.empty-state-report h5 {
    color: #191919;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.empty-state-report p {
    color: #808080;
    margin-bottom: 0;
}
</style>

<script>
// Reset Filter
function resetFilter() {
    const now = new Date();
    const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
    const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
    
    document.getElementById('start_date').value = firstDay.toISOString().split('T')[0];
    document.getElementById('end_date').value = lastDay.toISOString().split('T')[0];
    
    document.getElementById('filterForm').submit();
}

// Quick Filter Presets
function setQuickFilter(period) {
    const now = new Date();
    let startDate, endDate;
    
    switch(period) {
        case 'today':
            startDate = endDate = now;
            break;
        case 'week':
            startDate = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
            endDate = now;
            break;
        case 'month':
            startDate = new Date(now.getFullYear(), now.getMonth(), 1);
            endDate = new Date(now.getFullYear(), now.getMonth() + 1, 0);
            break;
        case 'lastmonth':
            startDate = new Date(now.getFullYear(), now.getMonth() - 1, 1);
            endDate = new Date(now.getFullYear(), now.getMonth(), 0);
            break;
        case 'year':
            startDate = new Date(now.getFullYear(), 0, 1);
            endDate = new Date(now.getFullYear(), 11, 31);
            break;
    }
    
    document.getElementById('start_date').value = startDate.toISOString().split('T')[0];
    document.getElementById('end_date').value = endDate.toISOString().split('T')[0];
    
    document.getElementById('filterForm').submit();
}

// Date Validation
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

// Table Search Functionality
function searchTable() {
    const input = document.getElementById('tableSearch');
    const filter = input.value.toUpperCase();
    const table = document.querySelector('.table-responsive table tbody');
    const rows = table.getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let found = false;
        
        for (let j = 0; j < cells.length; j++) {
            const cell = cells[j];
            if (cell) {
                const text = cell.textContent || cell.innerText;
                if (text.toUpperCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }
        
        row.style.display = found ? '' : 'none';
    }
}
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
