@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumbs')
    <li class="breadcrumb-item"><i class="bi bi-house-door me-1"></i> Dashboard</li>
@endsection

@section('content')
<style>
    /* Modern Dashboard Styles */
    .dashboard-hero {
        background: linear-gradient(135deg, #191919 0%, #2a2a2a 50%, #191919 100%);
        border-radius: 24px;
        padding: 3rem 2.5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
    
    .dashboard-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(28,239,244,0.15) 0%, transparent 70%);
        border-radius: 50%;
        animation: pulse 4s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }
    
    .hero-content {
        position: relative;
        z-index: 1;
    }
    
    .hero-title {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #ffffff 0%, #1ceff4 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
    }
    
    .hero-subtitle {
        color: #b2b2b2;
        font-size: 1.1rem;
        font-weight: 400;
    }
    
    .hero-badge {
        display: inline-block;
        background: linear-gradient(135deg, #1ceff4 0%, #0dd1d6 100%);
        color: #191919;
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 8px 20px rgba(28,239,244,0.3);
    }
    
    .time-label {
        font-size: 0.9rem;
    }
    
    .time-display {
        font-size: 1.5rem;
    }
    
    .stat-card-modern {
        background: #ffffff;
        border-radius: 20px;
        padding: 2rem;
        border: none;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--card-color) 0%, transparent 100%);
    }
    
    .stat-card-modern:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }
    
    .stat-icon-modern {
        width: 70px;
        height: 70px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 1.5rem;
        position: relative;
    }
    
    .stat-icon-modern::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background: inherit;
        border-radius: inherit;
        opacity: 0.2;
        filter: blur(15px);
    }
    
    .stat-label {
        color: #808080;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }
    
    .stat-value {
        font-size: 2.75rem;
        font-weight: 800;
        color: #191919;
        line-height: 1;
        margin-bottom: 0.25rem;
    }
    
    .stat-change {
        font-size: 0.85rem;
        color: #1ceff4;
        font-weight: 600;
    }
    
    .chart-card-modern {
        background: #ffffff;
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        overflow: hidden;
        height: 100%;
    }
    
    .chart-header-modern {
        padding: 1.75rem 2rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-bottom: 1px solid #e9ecef;
    }
    
    .chart-title-modern {
        font-size: 1.15rem;
        font-weight: 700;
        color: #191919;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .chart-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #1ceff4 0%, #0dd1d6 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }
    
    .chart-body-modern {
        padding: 2rem;
    }
    
    .table-modern {
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .table-modern thead th {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        color: #191919;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        padding: 1.25rem 1.5rem;
        border: none;
    }
    
    .table-modern thead th:first-child {
        border-radius: 12px 0 0 0;
    }
    
    .table-modern thead th:last-child {
        border-radius: 0 12px 0 0;
    }
    
    .table-modern tbody tr {
        transition: all 0.3s ease;
        background: #ffffff;
    }
    
    .table-modern tbody tr:hover {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        transform: scale(1.01);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    
    .table-modern tbody td {
        padding: 1.25rem 1.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f5;
    }
    
    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        background: linear-gradient(135deg, #1ceff4 0%, #0dd1d6 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
    }
    
    .badge-modern {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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
    
    .btn-modern {
        padding: 0.65rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    
    .btn-primary-modern {
        background: linear-gradient(135deg, #1ceff4 0%, #0dd1d6 100%);
        color: #191919;
    }
    
    .btn-outline-modern {
        background: transparent;
        border: 2px solid #1ceff4;
        color: #1ceff4;
    }
    
    .empty-state-modern {
        text-align: center;
        padding: 4rem 2rem;
    }
    
    .empty-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: #adb5bd;
    }
    
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }
    
    .quick-action-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        border: 2px solid #f1f3f5;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    
    .quick-action-card:hover {
        border-color: #1ceff4;
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(28,239,244,0.2);
    }
    
    .quick-action-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, #1ceff4 0%, #0dd1d6 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: white;
    }
    
    /* ========================================
       COMPREHENSIVE MOBILE RESPONSIVE
       ======================================== */
    
    /* Tablet (≤1024px) */
    @media (max-width: 1024px) {
        .dashboard-hero {
            padding: 2rem 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .hero-title {
            font-size: 2rem;
        }
        
        .hero-subtitle {
            font-size: 1rem;
        }
        
        .stat-card-modern {
            padding: 1.5rem;
        }
        
        .stat-icon-modern {
            width: 60px;
            height: 60px;
            font-size: 1.75rem;
        }
        
        .stat-value-modern {
            font-size: 2rem;
        }
    }
    
    /* Mobile (≤768px) */
    @media (max-width: 768px) {
        .dashboard-hero {
            padding: 1.5rem 1.25rem !important;
            margin-bottom: 1rem !important;
            border-radius: 16px !important;
        }
        
        .dashboard-hero::before {
            width: 300px;
            height: 300px;
            right: -20%;
        }
        
        .hero-title {
            font-size: 1.5rem !important;
            margin-bottom: 0.5rem !important;
        }
        
        .hero-subtitle {
            font-size: 0.9rem !important;
        }
        
        .hero-badge {
            padding: 0.375rem 1rem !important;
            font-size: 0.75rem !important;
        }
        
        /* Override inline styles untuk waktu */
        .time-label {
            font-size: 0.75rem !important;
        }
        
        .time-display {
            font-size: 1.1rem !important;
        }
        
        /* Stats grid: 2 columns */
        .row > [class*="col-"] {
            margin-bottom: 1rem;
        }
        
        /* Override semua stat card elements */
        .stat-card-modern {
            padding: 1.25rem !important;
            border-radius: 16px !important;
        }
        
        .stat-icon-modern {
            width: 50px !important;
            height: 50px !important;
            font-size: 1.5rem !important;
            margin-bottom: 0.75rem !important;
        }
        
        .stat-label,
        .stat-label-modern {
            font-size: 0.75rem !important;
            margin-bottom: 0.5rem !important;
        }
        
        .stat-value,
        .stat-value-modern {
            font-size: 1.75rem !important;
            margin-bottom: 0.25rem !important;
        }
        
        .stat-change,
        .stat-change-modern {
            font-size: 0.7rem !important;
        }
        
        /* Chart cards */
        .chart-card-modern,
        .card {
            margin-bottom: 1rem !important;
            border-radius: 16px !important;
        }
        
        .chart-header-modern,
        .card-header {
            padding: 1rem !important;
        }
        
        .chart-header-modern h3,
        .card-title {
            font-size: 1rem !important;
        }
        
        .chart-body-modern,
        .card-body {
            padding: 1rem !important;
        }
        
        /* Quick actions: stack on mobile */
        .quick-actions {
            grid-template-columns: 1fr !important;
            gap: 0.75rem !important;
        }
        
        .quick-action-card {
            padding: 1.25rem !important;
            border-radius: 12px !important;
        }
        
        .quick-action-icon {
            width: 50px !important;
            height: 50px !important;
            font-size: 1.5rem !important;
            margin-bottom: 0.75rem !important;
        }
        
        /* Recent bookings */
        .booking-item-modern {
            padding: 1rem !important;
        }
        
        .booking-user-avatar {
            width: 40px !important;
            height: 40px !important;
            font-size: 1rem !important;
        }
        
        .booking-user-name {
            font-size: 0.9rem !important;
        }
        
        .booking-user-email {
            font-size: 0.75rem !important;
        }
        
        .booking-room-name {
            font-size: 0.85rem !important;
        }
        
        .booking-meta-grid {
            grid-template-columns: 1fr !important;
            gap: 0.5rem !important;
        }
        
        .booking-meta-item {
            font-size: 0.75rem !important;
        }
        
        /* Badges */
        .status-badge {
            font-size: 0.7rem !important;
            padding: 0.25rem 0.65rem !important;
        }
        
        /* Empty states */
        .empty-icon {
            width: 80px !important;
            height: 80px !important;
            font-size: 2rem !important;
            margin-bottom: 1rem !important;
        }
        
        .empty-state h5 {
            font-size: 1rem !important;
        }
        
        .empty-state p {
            font-size: 0.85rem !important;
        }
        
        /* Container fluid padding */
        .container-fluid {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
    }
    
    /* Small Mobile (≤480px) */
    @media (max-width: 480px) {
        .dashboard-hero {
            padding: 1.25rem 1rem !important;
            border-radius: 12px !important;
        }
        
        .hero-title {
            font-size: 1.25rem !important;
        }
        
        .hero-subtitle {
            font-size: 0.8rem !important;
        }
        
        .hero-badge {
            padding: 0.3rem 0.85rem !important;
            font-size: 0.7rem !important;
        }
        
        /* Override inline style waktu */
        .time-label {
            font-size: 0.7rem !important;
        }
        
        .time-display {
            font-size: 1rem !important;
        }
        
        /* Stats: reduce gutter */
        .row.g-4 {
            --bs-gutter-x: 0.75rem !important;
            --bs-gutter-y: 0.75rem !important;
        }
        
        .stat-card-modern {
            padding: 1rem !important;
            border-radius: 12px !important;
        }
        
        .stat-icon-modern {
            width: 45px !important;
            height: 45px !important;
            font-size: 1.25rem !important;
            margin-bottom: 0.5rem !important;
        }
        
        .stat-label,
        .stat-label-modern {
            font-size: 0.7rem !important;
        }
        
        .stat-value,
        .stat-value-modern {
            font-size: 1.5rem !important;
        }
        
        .stat-change,
        .stat-change-modern {
            font-size: 0.65rem !important;
        }
        
        /* Charts: reduce canvas height */
        canvas {
            max-height: 200px !important;
        }
        
        /* Quick actions: full width */
        .quick-action-card {
            padding: 1rem !important;
        }
        
        .quick-action-card h6 {
            font-size: 0.9rem !important;
        }
        
        .quick-action-card p {
            font-size: 0.75rem !important;
        }
        
        /* Booking items compact */
        .booking-item-modern {
            padding: 0.85rem !important;
        }
        
        .booking-header-modern {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 0.5rem !important;
        }
        
        .booking-user {
            margin-bottom: 0.5rem !important;
        }
        
        /* Card headers */
        .chart-header-modern h3,
        .card-title {
            font-size: 0.9rem !important;
        }
        
        .chart-header-modern p,
        .card-text {
            font-size: 0.75rem !important;
        }
        
        /* Buttons smaller */
        .btn {
            font-size: 0.8rem !important;
            padding: 0.5rem 1rem !important;
        }
        
        .btn-sm {
            font-size: 0.7rem !important;
            padding: 0.375rem 0.75rem !important;
        }
        
        /* Container fluid */
        .container-fluid {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }
    }
    
    /* Landscape Mobile (height ≤ 600px) */
    @media (max-height: 600px) and (orientation: landscape) {
        .dashboard-hero {
            padding: 1rem;
            margin-bottom: 0.75rem;
        }
        
        .hero-title {
            font-size: 1.25rem;
            margin-bottom: 0.25rem;
        }
        
        .hero-subtitle {
            font-size: 0.75rem;
        }
        
        .stat-card-modern {
            padding: 0.75rem;
        }
        
        .stat-icon-modern {
            width: 40px;
            height: 40px;
            font-size: 1.1rem;
        }
        
        .stat-value-modern {
            font-size: 1.25rem;
        }
        
        /* Hide charts in landscape to save space */
        .chart-card-modern {
            display: none;
        }
        
        /* Compact spacing */
        .mb-4 {
            margin-bottom: 0.75rem !important;
        }
    }
    
    /* Ultra small screens (≤375px - iPhone SE) */
    @media (max-width: 375px) {
        .dashboard-hero {
            padding: 1rem 0.85rem !important;
        }
        
        .hero-title {
            font-size: 1.1rem !important;
        }
        
        .hero-subtitle {
            font-size: 0.75rem !important;
        }
        
        .time-label {
            font-size: 0.65rem !important;
        }
        
        .time-display {
            font-size: 0.9rem !important;
        }
        
        .stat-card-modern {
            padding: 0.85rem !important;
        }
        
        .stat-icon-modern {
            width: 40px !important;
            height: 40px !important;
            font-size: 1.1rem !important;
        }
        
        .stat-value,
        .stat-value-modern {
            font-size: 1.35rem !important;
        }
        
        .stat-label,
        .stat-label-modern {
            font-size: 0.65rem !important;
        }
        
        .quick-action-icon {
            width: 45px !important;
            height: 45px !important;
            font-size: 1.25rem !important;
        }
        
        .container-fluid {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
    }
    
    /* Touch optimization for all mobile */
    @media (max-width: 768px) {
        /* Better touch targets */
        a, button, .clickable {
            min-height: 44px;
            touch-action: manipulation;
        }
        
        /* Improve tap highlight */
        * {
            -webkit-tap-highlight-color: rgba(28, 239, 244, 0.1);
        }
        
        /* Smooth scrolling */
        body, .card-body, .chart-body-modern {
            -webkit-overflow-scrolling: touch;
        }
    }
    
    /* Prevent horizontal scroll */
    @media (max-width: 768px) {
        body {
            overflow-x: hidden;
        }
        
        .row {
            margin-left: 0;
            margin-right: 0;
        }
        
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }
</style>

<div class="container-fluid px-4 py-3">
    <!-- Modern Hero Section -->
    <div class="dashboard-hero" data-aos="fade-down">
        <div class="hero-content">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h1 class="hero-title">Halo, {{ Auth::user()->name }}! 👋</h1>
                    <p class="hero-subtitle mb-3">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</p>
                    <span class="hero-badge">{{ strtoupper(Auth::user()->role) }} PANEL</span>
                </div>
                <div class="text-end">
                    <div class="time-label" style="color: #b2b2b2; margin-bottom: 0.5rem;">Waktu Sekarang</div>
                    <div class="time-display" style="color: #1ceff4; font-weight: 700;" id="currentTime"></div>
                </div>
            </div>
        </div>
    </div>
    
    @if(Auth::user()->role !== 'peminjam')
    <!-- Modern Stats Cards for Admin/Petugas -->
    <div class="row g-4 mb-4">
        <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card-modern" style="--card-color: #1ceff4;">
                <div class="stat-icon-modern" style="background: linear-gradient(135deg, #e0fbfc 0%, #d0f9fa 100%);">
                    <i class="bi bi-door-open" style="color: #1ceff4;"></i>
                </div>
                <div class="stat-label">Total Ruangan</div>
                <div class="stat-value">{{ $totalRooms }}</div>
                <div class="stat-change">
                    <i class="bi bi-arrow-up-right"></i> Aktif
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card-modern" style="--card-color: #191919;">
                <div class="stat-icon-modern" style="background: linear-gradient(135deg, #f1f3f5 0%, #e9ecef 100%);">
                    <i class="bi bi-calendar-check" style="color: #191919;"></i>
                </div>
                <div class="stat-label">Total Peminjaman</div>
                <div class="stat-value">{{ $totalBookings }}</div>
                <div class="stat-change">
                    <i class="bi bi-graph-up"></i> Semua Data
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card-modern" style="--card-color: #fbbf24;">
                <div class="stat-icon-modern" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);">
                    <i class="bi bi-hourglass-split" style="color: #f59e0b;"></i>
                </div>
                <div class="stat-label">Menunggu Approval</div>
                <div class="stat-value">{{ $statusPending }}</div>
                <div class="stat-change">
                    <i class="bi bi-clock-history"></i> Pending
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-card-modern" style="--card-color: #808080;">
                <div class="stat-icon-modern" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                    <i class="bi bi-people" style="color: #808080;"></i>
                </div>
                <div class="stat-label">Total Peminjam</div>
                <div class="stat-value">{{ $totalUsers }}</div>
                <div class="stat-change">
                    <i class="bi bi-person-check"></i> Terdaftar
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions for Admin/Petugas -->
    <div class="chart-card-modern mb-4" data-aos="fade-up">
        <div class="chart-header-modern">
            <h3 class="chart-title-modern">
                <div class="chart-icon" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);">
                    <i class="bi bi-lightning-charge-fill"></i>
                </div>
                <span>⚡ Aksi Cepat</span>
            </h3>
            <p style="color: #808080; font-size: 0.9rem; margin: 0;">Shortcut untuk tugas yang sering dilakukan</p>
        </div>
        <div class="chart-body-modern">
            <div class="quick-actions">
                @if($pendingCount > 0)
                <a href="{{ route('bookings.index') }}" class="quick-action-card" style="border-color: #fbbf24;">
                    <div class="quick-action-icon" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); position: relative;">
                        <i class="bi bi-hourglass-split"></i>
                        <span style="position: absolute; top: -8px; right: -8px; background: #ef4444; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; border: 2px solid white;">{{ $pendingCount }}</span>
                    </div>
                    <h6 style="color: #191919; font-weight: 700; margin-bottom: 0.25rem;">Review Pending</h6>
                    <p style="color: #f59e0b; font-size: 0.85rem; margin: 0; font-weight: 600;">{{ $pendingCount }} menunggu approval</p>
                </a>
                @endif
                
                <a href="{{ route('bookings.laporan') }}" class="quick-action-card">
                    <div class="quick-action-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                    </div>
                    <h6 style="color: #191919; font-weight: 700; margin-bottom: 0.25rem;">Laporan</h6>
                    <p style="color: #808080; font-size: 0.85rem; margin: 0;">Export Excel/PDF</p>
                </a>
                
                <a href="{{ route('rooms.index') }}" class="quick-action-card">
                    <div class="quick-action-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                        <i class="bi bi-door-open-fill"></i>
                    </div>
                    <h6 style="color: #191919; font-weight: 700; margin-bottom: 0.25rem;">Daftar Ruangan</h6>
                    <p style="color: #808080; font-size: 0.85rem; margin: 0;">Lihat semua ruang</p>
                </a>
                
                @if(Auth::user()->role === 'admin')
                <a href="{{ route('users.index') }}" class="quick-action-card">
                    <div class="quick-action-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h6 style="color: #191919; font-weight: 700; margin-bottom: 0.25rem;">Kelola User</h6>
                    <p style="color: #808080; font-size: 0.85rem; margin: 0;">Manage akun</p>
                </a>
                @endif
                
                <a href="{{ route('jadwal-reguler.index') }}" class="quick-action-card">
                    <div class="quick-action-icon" style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <h6 style="color: #191919; font-weight: 700; margin-bottom: 0.25rem;">Jadwal Reguler</h6>
                    <p style="color: #808080; font-size: 0.85rem; margin: 0;">View jadwal tetap</p>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Modern Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8" data-aos="fade-right">
            <div class="chart-card-modern">
                <div class="chart-header-modern">
                    <h3 class="chart-title-modern">
                        <div class="chart-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <span>Trend Peminjaman 7 Hari Terakhir</span>
                    </h3>
                </div>
                <div class="chart-body-modern">
                    <canvas id="bookingTrendChart" height="80"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4" data-aos="fade-left">
            <div class="chart-card-modern">
                <div class="chart-header-modern">
                    <h3 class="chart-title-modern">
                        <div class="chart-icon">
                            <i class="bi bi-pie-chart-fill"></i>
                        </div>
                        <span>Status Peminjaman</span>
                    </h3>
                </div>
                <div class="chart-body-modern">
                    <canvas id="statusChart"></canvas>
                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex justify-content-between mb-2">
                            <span style="color: #808080; font-size: 0.9rem;">
                                <i class="bi bi-circle-fill" style="color: #fbbf24; font-size: 0.6rem;"></i> Pending
                            </span>
                            <strong style="color: #191919;">{{ $statusPending }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span style="color: #808080; font-size: 0.9rem;">
                                <i class="bi bi-circle-fill" style="color: #1ceff4; font-size: 0.6rem;"></i> Disetujui
                            </span>
                            <strong style="color: #191919;">{{ $statusApproved }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span style="color: #808080; font-size: 0.9rem;">
                                <i class="bi bi-circle-fill" style="color: #ef4444; font-size: 0.6rem;"></i> Ditolak
                            </span>
                            <strong style="color: #191919;">{{ $statusRejected }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Room Usage Chart -->
    <div class="row g-4 mb-4">
        <div class="col-12" data-aos="fade-up">
            <div class="chart-card-modern">
                <div class="chart-header-modern">
                    <h3 class="chart-title-modern">
                        <div class="chart-icon">
                            <i class="bi bi-bar-chart-fill"></i>
                        </div>
                        <span>Top 5 Ruangan Paling Populer</span>
                    </h3>
                </div>
                <div class="chart-body-modern">
                    <canvas id="roomUsageChart" height="60"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modern Pending Bookings Table -->
    @if($pendingBookings->count() > 0)
    <div class="chart-card-modern" data-aos="fade-up">
        <div class="chart-header-modern">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="chart-title-modern mb-0">
                    <div class="chart-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <span>Peminjaman Menunggu Approval ({{ $pendingBookings->count() }})</span>
                </h3>
                <a href="{{ route('bookings.index') }}" class="btn btn-modern btn-outline-modern">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="p-0">
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>Peminjam</th>
                            <th>Ruangan</th>
                            <th>Keperluan</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingBookings->take(5) as $booking)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold" style="color: #191919;">{{ $booking->user->name }}</div>
                                        <small class="text-muted">{{ $booking->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold" style="color: #191919;">{{ $booking->room->nama_room }}</div>
                                <small class="text-muted">
                                    <i class="bi bi-geo-alt"></i> {{ $booking->room->lokasi ?? 'N/A' }}
                                </small>
                            </td>
                            <td>
                                <div style="max-width: 200px;">{{ Str::limit($booking->keperluan, 40) }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold" style="color: #191919;">{{ $booking->tanggal_mulai->format('d M Y') }}</div>
                                @if($booking->tanggal_mulai != $booking->tanggal_selesai)
                                <small class="text-muted">s/d {{ $booking->tanggal_selesai->format('d M') }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge" style="background: #f1f3f5; color: #191919; font-weight: 600;">
                                    <i class="bi bi-clock"></i> {{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('bookings.show', $booking->id_booking) }}" class="btn btn-modern btn-primary-modern btn-sm">
                                    <i class="bi bi-eye"></i> Detail
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
    <div class="chart-card-modern" data-aos="fade-up">
        <div class="empty-state-modern">
            <div class="empty-icon">
                <i class="bi bi-check-circle-fill" style="color: #10b981;"></i>
            </div>
            <h4 style="color: #191919; font-weight: 700;">Semua Bersih! 🎉</h4>
            <p style="color: #808080; font-size: 1.05rem;">Tidak ada peminjaman yang menunggu approval</p>
        </div>
    </div>
    @endif
    
    @else
    <!-- Modern Dashboard for Peminjam -->
    <div class="row g-4 mb-4">
        <div class="col-6 col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card-modern" style="--card-color: #fbbf24;">
                <div class="stat-icon-modern" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);">
                    <i class="bi bi-hourglass-split" style="color: #f59e0b;"></i>
                </div>
                <div class="stat-label">Menunggu Approval</div>
                <div class="stat-value">{{ $pendingCount }}</div>
                <div class="stat-change">
                    <i class="bi bi-clock-history"></i> Sedang Diproses
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card-modern" style="--card-color: #10b981;">
                <div class="stat-icon-modern" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);">
                    <i class="bi bi-check-circle" style="color: #059669;"></i>
                </div>
                <div class="stat-label">Disetujui</div>
                <div class="stat-value">{{ $approvedCount }}</div>
                <div class="stat-change">
                    <i class="bi bi-check-all"></i> Berhasil
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-4" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card-modern" style="--card-color: #ef4444;">
                <div class="stat-icon-modern" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);">
                    <i class="bi bi-x-circle" style="color: #dc2626;"></i>
                </div>
                <div class="stat-label">Ditolak</div>
                <div class="stat-value">{{ $rejectedCount }}</div>
                <div class="stat-change">
                    <i class="bi bi-info-circle"></i> Review Lagi
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions for Peminjam -->
    <div class="chart-card-modern mb-4" data-aos="fade-up">
        <div class="chart-header-modern">
            <h3 class="chart-title-modern">
                <div class="chart-icon" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);">
                    <i class="bi bi-lightning-charge-fill"></i>
                </div>
                <span>⚡ Aksi Cepat</span>
            </h3>
            <p style="color: #808080; font-size: 0.9rem; margin: 0;">Shortcut untuk aksi yang sering kamu lakukan</p>
        </div>
        <div class="chart-body-modern">
            <div class="quick-actions">
                <a href="{{ route('bookings.create') }}" class="quick-action-card" style="border-color: #1ceff4;">
                    <div class="quick-action-icon">
                        <i class="bi bi-plus-circle"></i>
                    </div>
                    <h6 style="color: #191919; font-weight: 700; margin-bottom: 0.25rem;">Ajukan Peminjaman</h6>
                    <p style="color: #1ceff4; font-size: 0.85rem; margin: 0; font-weight: 600;">Booking ruangan baru</p>
                </a>
                
                <a href="{{ route('bookings.index') }}" class="quick-action-card">
                    <div class="quick-action-icon" style="background: linear-gradient(135deg, #191919 0%, #2a2a2a 100%);">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <h6 style="color: #191919; font-weight: 700; margin-bottom: 0.25rem;">Riwayat Peminjaman</h6>
                    <p style="color: #808080; font-size: 0.85rem; margin: 0;">Lihat semua booking</p>
                </a>
                
                <a href="{{ route('rooms.index') }}" class="quick-action-card">
                    <div class="quick-action-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <i class="bi bi-door-open-fill"></i>
                    </div>
                    <h6 style="color: #191919; font-weight: 700; margin-bottom: 0.25rem;">Daftar Ruangan</h6>
                    <p style="color: #808080; font-size: 0.85rem; margin: 0;">Lihat semua ruang</p>
                </a>
                
                <a href="{{ route('jadwal-reguler.index') }}" class="quick-action-card">
                    <div class="quick-action-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <h6 style="color: #191919; font-weight: 700; margin-bottom: 0.25rem;">Jadwal Reguler</h6>
                    <p style="color: #808080; font-size: 0.85rem; margin: 0;">View jadwal tetap</p>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Recent Bookings for Peminjam -->
    <div class="chart-card-modern" data-aos="fade-up">
        <div class="chart-header-modern">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="chart-title-modern mb-0">
                    <div class="chart-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <span>Riwayat Peminjaman Terbaru</span>
                </h3>
                @if(!$myBookings->isEmpty())
                <a href="{{ route('bookings.index') }}" class="btn btn-modern btn-outline-modern">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="p-0">
            @if($myBookings->isEmpty())
            <div class="empty-state-modern">
                <div class="empty-icon">
                    <i class="bi bi-calendar-x"></i>
                </div>
                <h4 style="color: #191919; font-weight: 700;">Belum Ada Peminjaman</h4>
                <p style="color: #808080; font-size: 1.05rem; margin-bottom: 2rem;">
                    Mulai ajukan peminjaman ruangan pertama Anda!
                </p>
                <a href="{{ route('bookings.create') }}" class="btn btn-modern btn-primary-modern">
                    <i class="bi bi-plus-circle me-2"></i>Ajukan Peminjaman Sekarang
                </a>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>Ruangan</th>
                            <th>Keperluan</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($myBookings as $booking)
                        <tr>
                            <td>
                                <div class="fw-bold" style="color: #191919;">{{ $booking->room->nama_room }}</div>
                                <small class="text-muted">
                                    <i class="bi bi-geo-alt"></i> {{ $booking->room->lokasi ?? 'N/A' }}
                                </small>
                            </td>
                            <td>
                                <div style="max-width: 200px;">{{ Str::limit($booking->keperluan, 40) }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold" style="color: #191919;">{{ $booking->tanggal_mulai->format('d M Y') }}</div>
                                @if($booking->tanggal_mulai != $booking->tanggal_selesai)
                                <small class="text-muted">s/d {{ $booking->tanggal_selesai->format('d M') }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge" style="background: #f1f3f5; color: #191919; font-weight: 600;">
                                    <i class="bi bi-clock"></i> {{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-modern badge-{{ $booking->status }}-modern">
                                    @if($booking->status == 'pending')
                                        <i class="bi bi-hourglass-split"></i> Pending
                                    @elseif($booking->status == 'approved')
                                        <i class="bi bi-check-circle"></i> Disetujui
                                    @else
                                        <i class="bi bi-x-circle"></i> Ditolak
                                    @endif
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('bookings.show', $booking->id_booking) }}" class="btn btn-modern btn-primary-modern btn-sm">
                                    <i class="bi bi-eye"></i> Detail
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

<script>
    // Update current time
    function updateTime() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('currentTime').textContent = `${hours}:${minutes}:${seconds}`;
    }
    updateTime();
    setInterval(updateTime, 1000);
</script>

@if(Auth::user()->role !== 'peminjam')
<script>
    // Modern Chart Configuration
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#808080';
    
    // Booking Trend Chart (Modern Line)
    const trendCtx = document.getElementById('bookingTrendChart').getContext('2d');
    const trendGradient = trendCtx.createLinearGradient(0, 0, 0, 300);
    trendGradient.addColorStop(0, 'rgba(28, 239, 244, 0.3)');
    trendGradient.addColorStop(1, 'rgba(28, 239, 244, 0.01)');
    
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartDates) !!},
            datasets: [{
                label: 'Peminjaman',
                data: {!! json_encode($chartCounts) !!},
                borderColor: '#1ceff4',
                backgroundColor: trendGradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#1ceff4',
                pointBorderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointHoverBackgroundColor: '#1ceff4',
                pointHoverBorderColor: '#ffffff',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#191919',
                    titleColor: '#ffffff',
                    bodyColor: '#1ceff4',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: {
                        size: 13,
                        weight: '600'
                    },
                    bodyFont: {
                        size: 16,
                        weight: '700'
                    },
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' Peminjaman';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        color: '#b2b2b2',
                        font: {
                            size: 12,
                            weight: '600'
                        }
                    },
                    grid: {
                        color: '#f1f3f5',
                        drawBorder: false
                    },
                    border: {
                        display: false
                    }
                },
                x: {
                    ticks: {
                        color: '#808080',
                        font: {
                            size: 12,
                            weight: '600'
                        }
                    },
                    grid: {
                        display: false
                    },
                    border: {
                        display: false
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
    
    // Status Chart (Modern Doughnut)
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Disetujui', 'Ditolak'],
            datasets: [{
                data: [{{ $statusPending }}, {{ $statusApproved }}, {{ $statusRejected }}],
                backgroundColor: ['#fbbf24', '#1ceff4', '#ef4444'],
                borderWidth: 0,
                hoverOffset: 15,
                spacing: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#191919',
                    titleColor: '#ffffff',
                    bodyColor: '#1ceff4',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: {
                        size: 13,
                        weight: '600'
                    },
                    bodyFont: {
                        size: 16,
                        weight: '700'
                    },
                    displayColors: true,
                    boxWidth: 12,
                    boxHeight: 12,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return ' ' + context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            },
            cutout: '70%'
        }
    });
    
    // Room Usage Chart (Modern Horizontal Bar)
    const roomCtx = document.getElementById('roomUsageChart').getContext('2d');
    new Chart(roomCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($roomStats->pluck('nama_room')) !!},
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: {!! json_encode($roomStats->pluck('bookings_count')) !!},
                backgroundColor: ['#1ceff4', '#0dd1d6', '#10b981', '#808080', '#b2b2b2'],
                borderRadius: 10,
                borderSkipped: false,
                barThickness: 40
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#191919',
                    titleColor: '#ffffff',
                    bodyColor: '#1ceff4',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: {
                        size: 13,
                        weight: '600'
                    },
                    bodyFont: {
                        size: 16,
                        weight: '700'
                    },
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.x + ' Peminjaman';
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        color: '#b2b2b2',
                        font: {
                            size: 12,
                            weight: '600'
                        }
                    },
                    grid: {
                        color: '#f1f3f5',
                        drawBorder: false
                    },
                    border: {
                        display: false
                    }
                },
                y: {
                    ticks: {
                        color: '#191919',
                        font: {
                            size: 13,
                            weight: '600'
                        }
                    },
                    grid: {
                        display: false
                    },
                    border: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endif
@endsection
