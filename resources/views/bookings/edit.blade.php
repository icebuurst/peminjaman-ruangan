@extends('layouts.app')

@section('title', 'Edit Peminjaman')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Edit Peminjaman</h2>
        <p class="text-muted mb-0">Ubah data peminjaman ruangan</p>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-pencil me-2"></i>Form Edit Peminjaman
                </div>
                <div class="card-body">
                    <form action="{{ route('bookings.update', $booking->id_booking) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="id_room" class="form-label">Ruangan <span class="text-danger">*</span></label>
                            <select class="form-select @error('id_room') is-invalid @enderror" id="id_room" name="id_room" required>
                                <option value="">-- Pilih Ruangan --</option>
                                @foreach($rooms as $room)
                                <option value="{{ $room->id_room }}" {{ old('id_room', $booking->id_room) == $room->id_room ? 'selected' : '' }}>
                                    {{ $room->nama_room }} - {{ $room->lokasi }} (Kapasitas: {{ $room->kapasitas }})
                                </option>
                                @endforeach
                            </select>
                            @error('id_room')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="keperluan" class="form-label">Keperluan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('keperluan') is-invalid @enderror" 
                                   id="keperluan" name="keperluan" value="{{ old('keperluan', $booking->keperluan) }}" required>
                            @error('keperluan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                       id="tanggal_mulai" name="tanggal_mulai" 
                                       value="{{ old('tanggal_mulai', $booking->tanggal_mulai->format('Y-m-d')) }}" 
                                       min="{{ date('Y-m-d') }}" required>
                                @error('tanggal_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_selesai" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                       id="tanggal_selesai" name="tanggal_selesai" 
                                       value="{{ old('tanggal_selesai', $booking->tanggal_selesai->format('Y-m-d')) }}" 
                                       min="{{ date('Y-m-d') }}" required>
                                @error('tanggal_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jam_mulai" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('jam_mulai') is-invalid @enderror" 
                                       id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai', substr($booking->jam_mulai, 0, 5)) }}" required>
                                @error('jam_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="jam_selesai" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('jam_selesai') is-invalid @enderror" 
                                       id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai', substr($booking->jam_selesai, 0, 5)) }}" required>
                                @error('jam_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="jumlah_peserta" class="form-label">Jumlah Peserta</label>
                            <input type="number" class="form-control @error('jumlah_peserta') is-invalid @enderror" 
                                   id="jumlah_peserta" name="jumlah_peserta" value="{{ old('jumlah_peserta', $booking->jumlah_peserta) }}" 
                                   min="1" placeholder="Jumlah peserta yang akan menggunakan ruangan">
                            @error('jumlah_peserta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="catatan" class="form-label">Catatan/Permintaan Khusus</label>
                            <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                      id="catatan" name="catatan" rows="3">{{ old('catatan', $booking->catatan) }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Perhatian:</strong> Perubahan hanya bisa dilakukan jika status masih <strong>Pending</strong>.
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary-custom btn-custom">
                                <i class="bi bi-save me-2"></i>Update Peminjaman
                            </button>
                            <a href="{{ route('bookings.show', $booking->id_booking) }}" class="btn btn-secondary btn-custom">
                                <i class="bi bi-x-circle me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-info-circle me-2"></i>Informasi
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Status:</strong> 
                        <span class="badge-status badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                    </p>
                    <p class="mb-2"><strong>Diajukan:</strong> {{ $booking->created_at ? $booking->created_at->format('d M Y H:i') : '-' }}</p>
                    @if($booking->updated_at && $booking->created_at && $booking->updated_at != $booking->created_at)
                    <p class="mb-0"><strong>Terakhir Diubah:</strong> {{ $booking->updated_at->format('d M Y H:i') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.getElementById('tanggal_mulai').addEventListener('change', function() {
        const tanggalSelesai = document.getElementById('tanggal_selesai');
        if (!tanggalSelesai.value || tanggalSelesai.value < this.value) {
            tanggalSelesai.value = this.value;
        }
        tanggalSelesai.min = this.value;
    });
</script>
@endsection
@endsection
