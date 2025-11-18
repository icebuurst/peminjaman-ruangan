@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="mb-4" data-aos="fade-down">
                <h2 class="fw-bold mb-1" style="color: #191919;">
                    <i class="bi bi-pencil-square me-2" style="color: #1ceff4;"></i>Edit User
                </h2>
                <p class="text-muted mb-0">Update informasi user: {{ $user->name }}</p>
            </div>

            <!-- Form Card -->
            <div class="chart-card-modern" data-aos="fade-up">
                <div class="chart-header-modern">
                    <h3 class="chart-title-modern mb-0">
                        <div class="chart-icon">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <span>Informasi User</span>
                    </h3>
                </div>
                <div class="chart-body-modern">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label for="name" class="form-label fw-semibold">
                                    <i class="bi bi-person me-1" style="color: #1ceff4;"></i>Nama Lengkap
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="bi bi-envelope me-1" style="color: #1ceff4;"></i>Email
                                </label>
                                <input type="email" 
                                       class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="role" class="form-label fw-semibold">
                                    <i class="bi bi-shield-fill-check me-1" style="color: #1ceff4;"></i>Role
                                </label>
                                <select class="form-select form-select-lg @error('role') is-invalid @enderror" 
                                        id="role" 
                                        name="role" 
                                        required>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                        Admin - Full Access
                                    </option>
                                    <option value="petugas" {{ old('role', $user->role) == 'petugas' ? 'selected' : '' }}>
                                        Petugas - Manage Rooms & Approve Bookings
                                    </option>
                                    <option value="peminjam" {{ old('role', $user->role) == 'peminjam' ? 'selected' : '' }}>
                                        Peminjam - Create Bookings
                                    </option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="alert" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border: none; border-radius: 12px;">
                                    <i class="bi bi-info-circle-fill me-2" style="color: #92400e;"></i>
                                    <strong style="color: #92400e;">Password:</strong>
                                    <span style="color: #78350f;">Kosongkan jika tidak ingin mengubah password</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="bi bi-lock-fill me-1" style="color: #1ceff4;"></i>Password Baru
                                </label>
                                <input type="password" 
                                       class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password"
                                       placeholder="Minimal 6 karakter">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold">
                                    <i class="bi bi-lock-fill me-1" style="color: #1ceff4;"></i>Konfirmasi Password
                                </label>
                                <input type="password" 
                                       class="form-control form-control-lg" 
                                       id="password_confirmation" 
                                       name="password_confirmation"
                                       placeholder="Ulangi password baru">
                            </div>
                        </div>

                        <div class="d-flex gap-3 mt-4 pt-3 border-top">
                            <a href="{{ route('users.index') }}" class="btn btn-lg btn-modern btn-outline-modern flex-grow-1">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-lg btn-modern btn-primary-modern flex-grow-1">
                                <i class="bi bi-check-circle me-2"></i>Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
