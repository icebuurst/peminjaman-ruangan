@extends('layouts.app')

@section('title', 'Edit Ruangan')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Edit Ruangan</h2>
        <p class="text-muted mb-0">Ubah data ruangan {{ $room->nama_room }}</p>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-pencil me-2"></i>Form Edit Ruangan
                </div>
                <div class="card-body">
                    <form action="{{ route('rooms.update', $room->id_room) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="nama_room" class="form-label">Nama Ruangan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_room') is-invalid @enderror" 
                                   id="nama_room" name="nama_room" value="{{ old('nama_room', $room->nama_room) }}" required>
                            @error('nama_room')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="lokasi" class="form-label">Lokasi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('lokasi') is-invalid @enderror" 
                                   id="lokasi" name="lokasi" value="{{ old('lokasi', $room->lokasi) }}" required>
                            @error('lokasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="kapasitas" class="form-label">Kapasitas <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('kapasitas') is-invalid @enderror" 
                                   id="kapasitas" name="kapasitas" value="{{ old('kapasitas', $room->kapasitas) }}" min="1" required>
                            @error('kapasitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" name="deskripsi" rows="4">{{ old('deskripsi', $room->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="foto" class="form-label">Foto Ruangan</label>
                            @if($room->foto)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $room->foto) }}" 
                                     alt="{{ $room->nama_room }}" 
                                     class="img-thumbnail" 
                                     style="max-height: 150px;"
                                     onerror="this.onerror=null; this.src='{{ asset('images/placeholder-room.svg') }}';">
                                <small class="d-block text-muted">Foto saat ini</small>
                            </div>
                            @endif
                            <input type="file" class="form-control @error('foto') is-invalid @enderror" 
                                   id="foto" name="foto" accept="image/*">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah foto. Format: JPG, PNG. Maksimal 2MB</small>
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary-custom btn-custom">
                                <i class="bi bi-save me-2"></i>Update
                            </button>
                            <a href="{{ route('rooms.show', $room->id_room) }}" class="btn btn-secondary btn-custom">
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
                    <p class="mb-2"><strong>Dibuat:</strong> {{ $room->created_at ? $room->created_at->format('d M Y H:i') : '-' }}</p>
                    <p class="mb-0"><strong>Terakhir Diubah:</strong> {{ $room->updated_at ? $room->updated_at->format('d M Y H:i') : '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
