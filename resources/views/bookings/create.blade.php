@extends('layouts.app')

@section('title', 'Ajukan Peminjaman')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Ajukan Peminjaman Ruangan</h2>
        <p class="text-muted mb-0">Isi form di bawah untuk mengajukan peminjaman ruangan</p>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-calendar-plus me-2"></i>Form Peminjaman
                </div>
                <div class="card-body">
                    @include('bookings._alternatives')
                    <form action="{{ route('bookings.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="id_room" class="form-label">Ruangan <span class="text-danger">*</span></label>
                            <select class="form-select @error('id_room') is-invalid @enderror" id="id_room" name="id_room" required>
                                <option value="">-- Pilih Ruangan --</option>
                                @foreach($rooms as $room)
                                <option value="{{ $room->id_room }}" {{ old('id_room') == $room->id_room ? 'selected' : '' }}>
                                    {{ $room->nama_room }} - {{ $room->lokasi ?? 'Lokasi tidak tersedia' }} (Kapasitas: {{ $room->kapasitas }} orang)
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
                                   id="keperluan" name="keperluan" value="{{ old('keperluan') }}" 
                                   placeholder="Contoh: Praktikum Web Programming" required>
                            @error('keperluan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                       id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" 
                                       min="{{ date('Y-m-d') }}" required>
                                @error('tanggal_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_selesai" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                       id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" 
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
                                       id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai') }}" required>
                                @error('jam_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="jam_selesai" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('jam_selesai') is-invalid @enderror" 
                                       id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai') }}" required>
                                @error('jam_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="jumlah_peserta" class="form-label">Jumlah Peserta</label>
                            <input type="number" class="form-control @error('jumlah_peserta') is-invalid @enderror" 
                                   id="jumlah_peserta" name="jumlah_peserta" value="{{ old('jumlah_peserta') }}" 
                                   min="1" placeholder="Jumlah peserta yang akan menggunakan ruangan">
                            @error('jumlah_peserta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="catatan" class="form-label">Catatan/Permintaan Khusus</label>
                            <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                      id="catatan" name="catatan" rows="3" 
                                      placeholder="Contoh: Butuh proyektor dan sound system">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Informasi:</strong> Peminjaman akan berstatus <strong>Pending</strong> dan menunggu persetujuan dari petugas/admin.
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary-custom btn-custom">
                                <i class="bi bi-send me-2"></i>Ajukan Peminjaman
                            </button>
                            <a href="{{ route('bookings.index') }}" class="btn btn-secondary btn-custom">
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
                    <i class="bi bi-info-circle me-2"></i>Panduan
                </div>
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Langkah Pengajuan:</h6>
                    <ol class="small">
                        <li class="mb-2">Pilih ruangan yang ingin dipinjam</li>
                        <li class="mb-2">Isi keperluan dengan jelas</li>
                        <li class="mb-2">Tentukan tanggal dan waktu</li>
                        <li class="mb-2">Tambahkan catatan jika ada permintaan khusus</li>
                        <li class="mb-2">Submit form dan tunggu persetujuan</li>
                    </ol>
                    
                    <hr class="my-3">
                    
                    <h6 class="fw-semibold mb-2">Status Peminjaman:</h6>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge-status badge-pending me-2">Pending</span>
                        <small>Menunggu persetujuan</small>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge-status badge-disetujui me-2">Disetujui</span>
                        <small>Peminjaman disetujui</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge-status badge-ditolak me-2">Ditolak</span>
                        <small>Peminjaman ditolak</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-set tanggal_selesai sama dengan tanggal_mulai
    document.getElementById('tanggal_mulai').addEventListener('change', function() {
        const tanggalSelesai = document.getElementById('tanggal_selesai');
        if (!tanggalSelesai.value || tanggalSelesai.value < this.value) {
            tanggalSelesai.value = this.value;
        }
        tanggalSelesai.min = this.value;
    });
</script>
@endsection
