@extends('layouts.app')

@section('title', 'Kelola User')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Kelola User</li>
@endsection

@section('content')
<style>
    .user-card {
        background: #ffffff;
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    
    .user-card:hover {
        box-shadow: 0 15px 50px rgba(0,0,0,0.12);
    }
    
    .user-avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        background: linear-gradient(135deg, #1ceff4 0%, #0dd1d6 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 2rem;
        box-shadow: 0 8px 20px rgba(28,239,244,0.3);
    }
    
    .role-badge-modern {
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .role-admin {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
    }
    
    .role-petugas {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
    }
    
    .role-peminjam {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }
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
    <div class="row g-4 mb-4">
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card-modern" style="--card-color: #fbbf24;">
                <div class="stat-icon-modern" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);">
                    <i class="bi bi-shield-fill-check" style="color: #f59e0b;"></i>
                </div>
                <div class="stat-label">Admin</div>
                <div class="stat-value">{{ $users->where('role', 'admin')->count() }}</div>
            </div>
        </div>
        
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card-modern" style="--card-color: #3b82f6;">
                <div class="stat-icon-modern" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);">
                    <i class="bi bi-person-badge" style="color: #2563eb;"></i>
                </div>
                <div class="stat-label">Petugas</div>
                <div class="stat-value">{{ $users->where('role', 'petugas')->count() }}</div>
            </div>
        </div>
        
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card-modern" style="--card-color: #10b981;">
                <div class="stat-icon-modern" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);">
                    <i class="bi bi-person" style="color: #059669;"></i>
                </div>
                <div class="stat-label">Peminjam</div>
                <div class="stat-value">{{ $users->where('role', 'peminjam')->count() }}</div>
            </div>
        </div>
        
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-card-modern" style="--card-color: #1ceff4;">
                <div class="stat-icon-modern" style="background: linear-gradient(135deg, #e0fbfc 0%, #d0f9fa 100%);">
                    <i class="bi bi-people-fill" style="color: #1ceff4;"></i>
                </div>
                <div class="stat-label">Total User</div>
                <div class="stat-value">{{ $users->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Users Grid -->
    <div class="row g-4">
        @forelse($users as $user)
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
            <div class="user-card p-4">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="user-avatar-large" style="background: linear-gradient(135deg, 
                        @if($user->role == 'admin') #fbbf24, #f59e0b
                        @elseif($user->role == 'petugas') #3b82f6, #2563eb
                        @else #10b981, #059669
                        @endif
                    );">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="fw-bold mb-1" style="color: #191919;">{{ $user->name }}</h5>
                        <p class="text-muted mb-2" style="font-size: 0.9rem;">
                            <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                        </p>
                        <span class="role-badge-modern role-{{ $user->role }}">
                            @if($user->role == 'admin')
                                <i class="bi bi-shield-fill-check me-1"></i>
                            @elseif($user->role == 'petugas')
                                <i class="bi bi-person-badge me-1"></i>
                            @else
                                <i class="bi bi-person me-1"></i>
                            @endif
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>
                
                <div class="border-top pt-3 mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted">
                            <i class="bi bi-calendar-plus"></i> Bergabung
                        </small>
                        <small class="fw-semibold" style="color: #191919;">
                            {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                        </small>
                    </div>
                    
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-modern btn-outline-modern flex-grow-1">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        @if(Auth::id() != $user->id)
                        <button type="button" 
                                class="btn btn-modern flex-grow-1" 
                                style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: #991b1b;"
                                onclick="confirmDelete('{{ $user->name }} ({{ $user->email }})', '{{ route('users.destroy', $user->id) }}')">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                        @else
                        <button class="btn btn-modern flex-grow-1" style="background: #f1f3f5; color: #adb5bd;" disabled>
                            <i class="bi bi-lock"></i> Akun Anda
                        </button>
                        @endif
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
