@extends('layouts.app')

@section('title', 'Edit Jadwal Reguler')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Edit Jadwal Reguler</h2>
        <p class="text-muted mb-0">Ubah data jadwal kegiatan reguler</p>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-pencil me-2"></i>Form Edit Jadwal
                </div>
                <div class="card-body">
                    <form action="{{ route('jadwal-reguler.update', $jadwal->id_reguler) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="nama_kegiatan" class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_kegiatan') is-invalid @enderror" 
                                   id="nama_kegiatan" name="nama_kegiatan" value="{{ old('nama_kegiatan', $jadwal->nama_kegiatan) }}" required>
                            @error('nama_kegiatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="id_room" class="form-label">Ruangan <span class="text-danger">*</span></label>
                            <select class="form-select @error('id_room') is-invalid @enderror" id="id_room" name="id_room" required>
                                <option value="">-- Pilih Ruangan --</option>
                                @foreach($rooms as $room)
                                <option value="{{ $room->id_room }}" {{ old('id_room', $jadwal->id_room) == $room->id_room ? 'selected' : '' }}>
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
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                                <option value="{{ $hari }}" {{ old('hari', $jadwal->hari) == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                                @endforeach
                            </select>
                            @error('hari')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jam_mulai" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('jam_mulai') is-invalid @enderror" 
                                       id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai', substr($jadwal->jam_mulai, 0, 5)) }}" required>
                                @error('jam_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="jam_selesai" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('jam_selesai') is-invalid @enderror" 
                                       id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai', substr($jadwal->jam_selesai, 0, 5)) }}" required>
                                @error('jam_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="penanggung_jawab" class="form-label">Penanggung Jawab</label>
                            <input type="text" class="form-control @error('penanggung_jawab') is-invalid @enderror" 
                                   id="penanggung_jawab" name="penanggung_jawab" value="{{ old('penanggung_jawab', $jadwal->penanggung_jawab) }}">
                            @error('penanggung_jawab')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary-custom btn-custom">
                                <i class="bi bi-save me-2"></i>Update Jadwal
                            </button>
                            <a href="{{ route('jadwal-reguler.show', $jadwal->id_reguler) }}" class="btn btn-secondary btn-custom">
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
                    @if($jadwal->created_at)
                    <p class="mb-2"><strong>Dibuat:</strong> {{ $jadwal->created_at->format('d M Y H:i') }}</p>
                    @endif
                    @if($jadwal->updated_at && $jadwal->created_at && $jadwal->updated_at != $jadwal->created_at)
                    <p class="mb-0"><strong>Terakhir Diubah:</strong> {{ $jadwal->updated_at->format('d M Y H:i') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
