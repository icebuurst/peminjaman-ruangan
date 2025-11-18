@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Detail Peminjaman</h2>
        <p class="text-muted mb-0">Informasi lengkap peminjaman ruangan</p>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-info-circle me-2"></i>Informasi Peminjaman</span>
                    <span class="badge-status badge-{{ $booking->status }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 fw-semibold">ID Booking:</div>
                        <div class="col-md-9">#{{ str_pad($booking->id_booking, 5, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    
                    @if(Auth::user()->role !== 'peminjam')
                    <div class="row mb-3">
                        <div class="col-md-3 fw-semibold">Peminjam:</div>
                        <div class="col-md-9">
                            <div>{{ $booking->user->name }}</div>
                            <small class="text-muted">{{ $booking->user->email }}</small><br>
                            <small class="text-muted">ID: {{ $booking->user->identity }}</small>
                        </div>
                    </div>
                    @endif
                    
                    <div class="row mb-3">
                        <div class="col-md-3 fw-semibold">Ruangan:</div>
                        <div class="col-md-9">
                            <div class="fw-semibold">{{ $booking->room->nama_room }}</div>
                            <small class="text-muted">{{ $booking->room->lokasi }}</small><br>
                            <small class="text-muted">Kapasitas: {{ $booking->room->kapasitas }} orang</small>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3 fw-semibold">Keperluan:</div>
                        <div class="col-md-9">{{ $booking->keperluan }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3 fw-semibold">Tanggal:</div>
                        <div class="col-md-9">
                            {{ $booking->tanggal_mulai->format('d F Y') }}
                            @if($booking->tanggal_mulai != $booking->tanggal_selesai)
                                s/d {{ $booking->tanggal_selesai->format('d F Y') }}
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3 fw-semibold">Waktu:</div>
                        <div class="col-md-9">
                            {{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }} WIB
                            @php
                                $jamMulai = \Carbon\Carbon::parse($booking->jam_mulai);
                                $jamSelesai = \Carbon\Carbon::parse($booking->jam_selesai);
                                $diffHours = $jamMulai->diffInHours($jamSelesai);
                                $diffMinutes = $jamMulai->diffInMinutes($jamSelesai) % 60;
                            @endphp
                            <small class="text-muted">
                                (Durasi: {{ $diffHours }} jam {{ $diffMinutes }} menit)
                            </small>
                        </div>
                    </div>
                    
                    @if($booking->catatan)
                    <div class="row mb-3">
                        <div class="col-md-3 fw-semibold">Catatan:</div>
                        <div class="col-md-9">
                            <div class="alert alert-light mb-0">
                                {{ $booking->catatan }}
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="row mb-3">
                        <div class="col-md-3 fw-semibold">Diajukan:</div>
                        <div class="col-md-9">{{ $booking->created_at ? $booking->created_at->format('d F Y H:i') : '-' }}</div>
                    </div>
                    
                    @if($booking->updated_at && $booking->created_at && $booking->updated_at != $booking->created_at)
                    <div class="row">
                        <div class="col-md-3 fw-semibold">Terakhir Diupdate:</div>
                        <div class="col-md-9">{{ $booking->updated_at->format('d F Y H:i') }}</div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Actions -->
            @if(Auth::user()->role !== 'peminjam' && $booking->status === 'pending')
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-check-square me-2"></i>Tindakan
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                            <i class="bi bi-check-circle me-2"></i>Setujui Peminjaman
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="bi bi-x-circle me-2"></i>Tolak Peminjaman
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Approve Modal -->
            <div class="modal fade" id="approveModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">Setujui Peminjaman</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('bookings.updateStatus', $booking->id_booking) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="approved">
                            <div class="modal-body">
                                <p>Apakah Anda yakin ingin menyetujui peminjaman ini?</p>
                                <div class="mb-3">
                                    <label for="catatan" class="form-label">Catatan Persetujuan (opsional)</label>
                                    <textarea class="form-control" name="catatan" rows="2" placeholder="Tambahkan catatan jika diperlukan"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">Setujui</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Reject Modal -->
            <div class="modal fade" id="rejectModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Tolak Peminjaman</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('bookings.updateStatus', $booking->id_booking) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="rejected">
                            <div class="modal-body">
                                <p>Apakah Anda yakin ingin menolak peminjaman ini?</p>
                                <div class="mb-3">
                                    <label for="catatan" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="catatan" rows="3" placeholder="Jelaskan alasan penolakan" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Tolak</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-gear me-2"></i>Aksi
                </div>
                <div class="card-body">
                    <a href="{{ route('bookings.index') }}" class="btn btn-secondary btn-custom w-100 mb-2">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                    </a>
                    
                    @if(Auth::user()->role === 'peminjam' && $booking->status === 'pending')
                    <a href="{{ route('bookings.edit', $booking->id_booking) }}" class="btn btn-warning btn-custom w-100 mb-2">
                        <i class="bi bi-pencil me-2"></i>Edit Peminjaman
                    </a>
                    
                    <button type="button" class="btn btn-danger btn-custom w-100" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bi bi-trash me-2"></i>Hapus Peminjaman
                    </button>
                    
                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Apakah Anda yakin ingin menghapus peminjaman ini?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('bookings.destroy', $booking->id_booking) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <i class="bi bi-door-open me-2"></i>Info Ruangan
                </div>
                <div class="card-body">
                    <h6 class="fw-semibold">{{ $booking->room->nama_room }}</h6>
                    <p class="mb-2 small text-muted">
                        <i class="bi bi-geo-alt me-1"></i>{{ $booking->room->lokasi }}
                    </p>
                    <p class="mb-2 small text-muted">
                        <i class="bi bi-people me-1"></i>Kapasitas: {{ $booking->room->kapasitas }} orang
                    </p>
                    @if($booking->room->deskripsi)
                    <p class="mb-0 small">{{ $booking->room->deskripsi }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
