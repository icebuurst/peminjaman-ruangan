@extends('layouts.app')

@section('title', 'Detail Jadwal Reguler')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="fw-bold mb-1">{{ $jadwal->nama_kegiatan }}</h2>
        <p class="text-muted mb-0">Detail jadwal kegiatan reguler</p>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-info-circle me-2"></i>Informasi Jadwal</span>
                    @if(Auth::user()->role !== 'peminjam')
                    <a href="{{ route('jadwal-reguler.edit', $jadwal->id_reguler) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 fw-semibold">ID Jadwal:</div>
                        <div class="col-md-9">#{{ str_pad($jadwal->id_reguler, 5, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3 fw-semibold">Nama Kegiatan:</div>
                        <div class="col-md-9">{{ $jadwal->nama_kegiatan }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3 fw-semibold">Ruangan:</div>
                        <div class="col-md-9">
                            <div class="fw-semibold">{{ $jadwal->room->nama_room }}</div>
                            <small class="text-muted">{{ $jadwal->room->lokasi }}</small><br>
                            <small class="text-muted">Kapasitas: {{ $jadwal->room->kapasitas }} orang</small>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3 fw-semibold">Hari:</div>
                        <div class="col-md-9">
                            <span class="badge bg-primary">{{ $jadwal->hari }}</span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3 fw-semibold">Waktu:</div>
                        <div class="col-md-9">
                            <i class="bi bi-clock me-1"></i>{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }} WIB
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3 fw-semibold">Penanggung Jawab:</div>
                        <div class="col-md-9">{{ $jadwal->penanggung_jawab ?? '-' }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3 fw-semibold">Dibuat:</div>
                        <div class="col-md-9">{{ $jadwal->created_at ? $jadwal->created_at->format('d F Y H:i') : '-' }}</div>
                    </div>
                    
                    @if($jadwal->updated_at && $jadwal->created_at && $jadwal->updated_at != $jadwal->created_at)
                    <div class="row">
                        <div class="col-md-3 fw-semibold">Terakhir Diupdate:</div>
                        <div class="col-md-9">{{ $jadwal->updated_at->format('d F Y H:i') }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-gear me-2"></i>Aksi
                </div>
                <div class="card-body">
                    <a href="{{ route('jadwal-reguler.index') }}" class="btn btn-secondary btn-custom w-100 mb-2">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                    </a>
                    
                    @if(Auth::user()->role !== 'peminjam')
                    <a href="{{ route('jadwal-reguler.edit', $jadwal->id_reguler) }}" class="btn btn-warning btn-custom w-100 mb-2">
                        <i class="bi bi-pencil me-2"></i>Edit Jadwal
                    </a>
                    
                    <button type="button" class="btn btn-danger btn-custom w-100" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bi bi-trash me-2"></i>Hapus Jadwal
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
                                    Apakah Anda yakin ingin menghapus jadwal reguler ini?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('jadwal-reguler.destroy', $jadwal->id_reguler) }}" method="POST">
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
                    <h6 class="fw-semibold">{{ $jadwal->room->nama_room }}</h6>
                    <p class="mb-2 small text-muted">
                        <i class="bi bi-geo-alt me-1"></i>{{ $jadwal->room->lokasi }}
                    </p>
                    <p class="mb-2 small text-muted">
                        <i class="bi bi-people me-1"></i>Kapasitas: {{ $jadwal->room->kapasitas }} orang
                    </p>
                    @if($jadwal->room->deskripsi)
                    <p class="mb-0 small">{{ $jadwal->room->deskripsi }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
