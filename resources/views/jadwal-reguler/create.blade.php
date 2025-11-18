@extends('layouts.app')

@section('title', 'Tambah Jadwal Reguler')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Tambah Jadwal Reguler</h2>
        <p class="text-muted mb-0">Buat jadwal kegiatan rutin yang berulang setiap minggu</p>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-calendar-plus me-2"></i>Form Jadwal Reguler
                </div>
                <div class="card-body">
                    <form action="{{ route('jadwal-reguler.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="nama_kegiatan" class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_kegiatan') is-invalid @enderror" 
                                   id="nama_kegiatan" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" 
                                   placeholder="Contoh: Praktikum RPL Kelas XII" required>
                            @error('nama_kegiatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="id_room" class="form-label">Ruangan <span class="text-danger">*</span></label>
                            <select class="form-select @error('id_room') is-invalid @enderror" id="id_room" name="id_room" required>
                                <option value="">-- Pilih Ruangan --</option>
                                @foreach($rooms as $room)
                                <option value="{{ $room->id_room }}" {{ old('id_room') == $room->id_room ? 'selected' : '' }}>
                                    {{ $room->nama_room }} - {{ $room->lokasi }}
                                </option>
                                @endforeach
                            </select>
                            @error('id_room')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="hari" class="form-label">Hari <span class="text-danger">*</span></label>
                            <select class="form-select @error('hari') is-invalid @enderror" id="hari" name="hari" required>
                                <option value="">-- Pilih Hari --</option>
                                <option value="Senin" {{ old('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                                <option value="Selasa" {{ old('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                <option value="Rabu" {{ old('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                <option value="Kamis" {{ old('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                <option value="Jumat" {{ old('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                <option value="Sabtu" {{ old('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                                <option value="Minggu" {{ old('hari') == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                            </select>
                            @error('hari')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                        
                        <div class="mb-4">
                            <label for="penanggung_jawab" class="form-label">Penanggung Jawab</label>
                            <input type="text" class="form-control @error('penanggung_jawab') is-invalid @enderror" 
                                   id="penanggung_jawab" name="penanggung_jawab" value="{{ old('penanggung_jawab') }}" 
                                   placeholder="Nama guru/petugas yang bertanggung jawab">
                            @error('penanggung_jawab')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary-custom btn-custom">
                                <i class="bi bi-save me-2"></i>Simpan Jadwal
                            </button>
                            <a href="{{ route('jadwal-reguler.index') }}" class="btn btn-secondary btn-custom">
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
                    <h6 class="fw-semibold mb-3">Tentang Jadwal Reguler:</h6>
                    <p class="small">Jadwal reguler adalah kegiatan yang berulang setiap minggu pada hari dan waktu yang sama.</p>
                    
                    <h6 class="fw-semibold mb-2 mt-3">Contoh Kegiatan:</h6>
                    <ul class="small">
                        <li>Praktikum RPL</li>
                        <li>Upacara Bendera</li>
                        <li>Rapat Guru</li>
                        <li>Literasi Pagi</li>
                        <li>Mata Pelajaran Reguler</li>
                    </ul>
                    
                    <div class="alert alert-warning small mt-3 mb-0">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Jadwal reguler akan muncul sebagai pengingat saat peminjam mengajukan booking.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
