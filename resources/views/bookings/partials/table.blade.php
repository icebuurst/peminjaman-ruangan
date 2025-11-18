<div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead>
            <tr>
                <th>No</th>
                @if(Auth::user()->role !== 'peminjam')
                <th>Peminjam</th>
                @endif
                <th>Ruangan</th>
                <th>Keperluan</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $index => $booking)
            <tr>
                <td>{{ $index + 1 }}</td>
                @if(Auth::user()->role !== 'peminjam')
                <td>
                    <div class="fw-semibold">{{ $booking->user->name }}</div>
                    <small class="text-muted">{{ $booking->user->email }}</small>
                </td>
                @endif
                <td>
                    <div class="fw-semibold">{{ $booking->room->nama_room }}</div>
                    <small class="text-muted">{{ $booking->room->lokasi }}</small>
                </td>
                <td>{{ Str::limit($booking->keperluan, 30) }}</td>
                <td>{{ $booking->tanggal_mulai->format('d M Y') }}</td>
                <td>
                    <small>{{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }}</small>
                </td>
                <td>
                    <span class="badge-status badge-{{ $booking->status }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ route('bookings.show', $booking->id_booking) }}" class="btn btn-sm btn-info" title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        
                        @if(Auth::user()->role === 'peminjam' && $booking->status === 'pending')
                        <a href="{{ route('bookings.edit', $booking->id_booking) }}" class="btn btn-sm btn-warning" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $booking->id_booking }}" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                        @endif
                        
                        @if(Auth::user()->role !== 'peminjam' && $booking->status === 'pending')
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $booking->id_booking }}" title="Setujui">
                            <i class="bi bi-check-circle"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $booking->id_booking }}" title="Tolak">
                            <i class="bi bi-x-circle"></i>
                        </button>
                        @endif
                    </div>
                    
                    <!-- Delete Modal -->
                    @if(Auth::user()->role === 'peminjam' && $booking->status === 'pending')
                    <div class="modal fade" id="deleteModal{{ $booking->id_booking }}" tabindex="-1">
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
                    
                    <!-- Approve Modal -->
                    @if(Auth::user()->role !== 'peminjam' && $booking->status === 'pending')
                    <div class="modal fade" id="approveModal{{ $booking->id_booking }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title">Setujui Peminjaman</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('bookings.updateStatus', $booking->id_booking) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="disetujui">
                                    <div class="modal-body">
                                        <p>Setujui peminjaman <strong>{{ $booking->room->nama_room }}</strong> oleh <strong>{{ $booking->user->name }}</strong>?</p>
                                        <div class="mb-3">
                                            <label for="catatan_approve_{{ $booking->id_booking }}" class="form-label">Catatan (opsional)</label>
                                            <textarea class="form-control" name="catatan" id="catatan_approve_{{ $booking->id_booking }}" rows="2" placeholder="Tambahkan catatan jika diperlukan"></textarea>
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
                    <div class="modal fade" id="rejectModal{{ $booking->id_booking }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">Tolak Peminjaman</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('bookings.updateStatus', $booking->id_booking) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="ditolak">
                                    <div class="modal-body">
                                        <p>Tolak peminjaman <strong>{{ $booking->room->nama_room }}</strong> oleh <strong>{{ $booking->user->name }}</strong>?</p>
                                        <div class="mb-3">
                                            <label for="catatan_reject_{{ $booking->id_booking }}" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                            <textarea class="form-control" name="catatan" id="catatan_reject_{{ $booking->id_booking }}" rows="3" placeholder="Jelaskan alasan penolakan" required></textarea>
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
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-4 text-muted">
                    Tidak ada data peminjaman
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
