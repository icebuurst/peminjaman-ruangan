@extends('layouts.app')

@section('title', 'Tambah Ruangan')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Tambah Ruangan Baru</h2>
        <p class="text-muted mb-0">Isi form di bawah untuk menambahkan ruangan baru</p>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-door-open me-2"></i>Form Tambah Ruangan
                </div>
                <div class="card-body">
                    <form action="{{ route('rooms.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="nama_room" class="form-label">Nama Ruangan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_room') is-invalid @enderror" 
                                   id="nama_room" name="nama_room" value="{{ old('nama_room') }}" 
                                   placeholder="Contoh: Lab Komputer 1" required>
                            @error('nama_room')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="lokasi" class="form-label">Lokasi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('lokasi') is-invalid @enderror" 
                                   id="lokasi" name="lokasi" value="{{ old('lokasi') }}" 
                                   placeholder="Contoh: Gedung A Lt. 2" required>
                            @error('lokasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="kapasitas" class="form-label">Kapasitas <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('kapasitas') is-invalid @enderror" 
                                   id="kapasitas" name="kapasitas" value="{{ old('kapasitas') }}" 
                                   placeholder="Jumlah orang" min="1" required>
                            @error('kapasitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" name="deskripsi" rows="4" 
                                      placeholder="Deskripsi fasilitas dan kelengkapan ruangan">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="foto" class="form-label">Foto Ruangan</label>
                            <input type="file" class="form-control @error('foto') is-invalid @enderror" 
                                   id="foto" name="foto" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG. Maksimal 2MB</small>
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary-custom btn-custom">
                                <i class="bi bi-save me-2"></i>Simpan
                            </button>
                            <a href="{{ route('rooms.index') }}" class="btn btn-secondary btn-custom">
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
                    <h6 class="fw-semibold mb-3">Tips Mengisi Form:</h6>
                    <ul class="small">
                        <li class="mb-2"><strong>Nama Ruangan:</strong> Gunakan nama yang jelas dan mudah dikenali</li>
                        <li class="mb-2"><strong>Lokasi:</strong> Sertakan gedung dan lantai</li>
                        <li class="mb-2"><strong>Kapasitas:</strong> Jumlah maksimal orang yang bisa menempati</li>
                        <li class="mb-2"><strong>Deskripsi:</strong> Jelaskan fasilitas yang tersedia (AC, proyektor, dll)</li>
                        <li><strong>Foto:</strong> Upload foto yang menampilkan kondisi ruangan dengan jelas</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
