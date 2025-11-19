@extends('layouts.app')

@section('title', 'Kelola User')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Kelola User</li>
@endsection

@section('content')
<style>
    /* ---------- Stats cards ---------- */
    .stat-card-modern {
        background: #fff;
        border-radius: 14px;
        padding: 1rem 1rem 0.9rem 1rem;
        display: flex;
        gap: .75rem;
        align-items: center;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
        border: 1px solid rgba(15,23,42,0.04);
        min-height: 90px;
        transition: transform .18s ease, box-shadow .18s ease;
    }
    .stat-card-modern:hover{ transform: translateY(-4px); box-shadow: 0 10px 30px rgba(15,23,42,0.08); }
    .stat-icon-modern{ width:56px; height:56px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.25rem; box-shadow: inset 0 -6px 18px rgba(255,255,255,0.6);}
    .stat-label{ color:#6b7280; font-weight:700; font-size:0.85rem; }
    .stat-value{ font-size:1.35rem; font-weight:800; color:#111827; }

    /* ---------- User cards ---------- */
    .user-card { background: #ffffff; border-radius: 14px; border: 1px solid rgba(15,23,42,0.04); box-shadow: 0 8px 24px rgba(15,23,42,0.04); transition: transform .18s ease; }
    .user-card:hover { transform: translateY(-6px); box-shadow: 0 18px 45px rgba(15,23,42,0.06); }
    .user-avatar-large { width:72px; height:72px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:1.5rem; color:#fff; }

    .role-badge-modern { padding:0.35rem 0.9rem; border-radius:999px; font-weight:700; font-size:0.72rem; text-transform:none; display:inline-flex; gap:0.5rem; align-items:center; }
    .role-admin{ background: linear-gradient(135deg,#ffedd5,#fed7aa); color:#92400e; }
    .role-petugas{ background: linear-gradient(135deg,#dbeafe,#bfdbfe); color:#1e3a8a; }
    .role-peminjam{ background: linear-gradient(135deg,#dcfce7,#bbf7d0); color:#065f46; }

    /* actions */
    .btn-outline-modern{ border:1px solid rgba(15,23,42,0.06); color:#0f172a; background:transparent; }
    .btn-modern{ border-radius:10px; padding:0.5rem 0.9rem; }

    /* responsive tweaks */
    @media (max-width: 767px){ .stat-value{ font-size:1.05rem; } .user-avatar-large{ width:64px; height:64px; } }
</style>

<div class="container-fluid px-4 py-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
        <div>
            <h2 class="fw-bold mb-1" style="color: #191919;">
                <i class="bi bi-people-fill me-2" style="color: #1ceff4;"></i>Kelola User
            </h2>
            <p class="text-muted mb-0">Manage semua user dalam sistem</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-modern btn-primary-modern">
            <i class="bi bi-plus-circle me-2"></i>Tambah User Baru
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-md-3" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card-modern">
                <div class="stat-icon-modern" style="background:linear-gradient(135deg,#fff7ed,#ffedd5);">
                    <i class="bi bi-shield-fill-check text-warning"></i>
                </div>
                <div>
                    <div class="stat-label">Admin</div>
                    <div class="stat-value">{{ $users->where('role', 'admin')->count() }}</div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3" data-aos="fade-up" data-aos-delay="150">
            <div class="stat-card-modern">
                <div class="stat-icon-modern" style="background:linear-gradient(135deg,#eff6ff,#dbeafe);">
                    <i class="bi bi-person-badge text-primary"></i>
                </div>
                <div>
                    <div class="stat-label">Petugas</div>
                    <div class="stat-value">{{ $users->where('role', 'petugas')->count() }}</div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card-modern">
                <div class="stat-icon-modern" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);">
                    <i class="bi bi-person text-success"></i>
                </div>
                <div>
                    <div class="stat-label">Peminjam</div>
                    <div class="stat-value">{{ $users->where('role', 'peminjam')->count() }}</div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3" data-aos="fade-up" data-aos-delay="250">
            <div class="stat-card-modern">
                <div class="stat-icon-modern" style="background:linear-gradient(135deg,#f0fdfa,#e6f7f7);">
                    <i class="bi bi-people-fill text-info"></i>
                </div>
                <div>
                    <div class="stat-label">Total User</div>
                    <div class="stat-value">{{ $users->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Grid -->
    <div class="row g-4">
        @forelse($users as $user)
        <div class="col-12 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 30 }}">
            <div class="user-card p-3">
                <div class="d-flex align-items-start gap-3 mb-2">
                    <div class="user-avatar-large" style="background: 
                        @if($user->role == 'admin') linear-gradient(135deg,#ffedd5,#fed7aa)
                        @elseif($user->role == 'petugas') linear-gradient(135deg,#eff6ff,#dbeafe)
                        @else linear-gradient(135deg,#ecfdf5,#d1fae5)
                        @endif;">
                        <span aria-hidden="true">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="fw-semibold mb-0" style="color:#0f172a;">{{ $user->name }}</h5>
                        <div class="text-muted small mt-1"><i class="bi bi-envelope me-1"></i>{{ $user->email }}</div>
                        <div class="mt-2">
                            <span class="role-badge-modern role-{{ $user->role }}">
                                @if($user->role == 'admin')<i class="bi bi-shield-fill-check"></i>
                                @elseif($user->role == 'petugas')<i class="bi bi-person-badge"></i>
                                @else<i class="bi bi-person"></i>@endif
                                <span style="margin-left:6px">{{ ucfirst($user->role) }}</span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="border-top pt-3 mt-3 d-flex justify-content-between align-items-center">
                    <div class="small text-muted">
                        <i class="bi bi-calendar-plus"></i> Bergabung
                    </div>
                    <div class="text-end">
                        <div class="fw-semibold" style="color:#0f172a;">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</div>
                        <div class="mt-2 d-flex gap-2">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-modern btn-outline-modern">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if(Auth::id() != $user->id)
                            <button type="button" class="btn btn-modern" style="background:#ffefef;color:#b91c1c;border:1px solid rgba(185,28,28,0.08);" onclick="confirmDelete('{{ $user->name }} ({{ $user->email }})', '{{ route('users.destroy', $user->id) }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                            @else
                            <button class="btn btn-modern" style="background:#f1f3f5;color:#6b7280;" disabled>
                                <i class="bi bi-lock"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="empty-state-modern">
                <div class="empty-icon">
                    <i class="bi bi-people"></i>
                </div>
                <h4 style="color: #191919; font-weight: 700;">Belum Ada User</h4>
                <p style="color: #808080;">Tambahkan user pertama untuk memulai</p>
                <a href="{{ route('users.create') }}" class="btn btn-modern btn-primary-modern mt-3">
                    <i class="bi bi-plus-circle me-2"></i>Tambah User Sekarang
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
