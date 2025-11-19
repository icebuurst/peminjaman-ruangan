@php
    // allow passing $alternatives directly when rendering server-side for AJAX
    $alternatives = isset($alternatives) ? $alternatives : (session()->has('alternatives') ? session('alternatives') : []);
    $partialBookingId = isset($booking) && isset($booking->id_booking) ? $booking->id_booking : null;
@endphp
@if(is_array($alternatives) && count($alternatives) > 0)
<div class="card mb-4 border-warning" @if($partialBookingId) data-booking-id="{{ $partialBookingId }}" @endif>
    <div class="card-header bg-warning text-dark fw-semibold">
        <i class="bi bi-lightbulb-fill me-2"></i>Alternatif Waktu Tersedia
    </div>
    <div class="card-body">
        <p class="small text-muted">Form yang Anda isi bertabrakan dengan jadwal reguler; pilih salah satu alternatif berikut atau batalkan peminjaman.</p>
        <div class="list-group">
            @foreach($alternatives as $alt)
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $alt['date'] }}</strong>
                    <div class="small text-muted">{{ $alt['jam_mulai'] }} - {{ $alt['jam_selesai'] }}</div>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-primary btn-choose-alt" data-date="{{ $alt['date'] }}" data-start="{{ $alt['jam_mulai'] }}" data-end="{{ $alt['jam_selesai'] }}">Pilih</button>
                    @if($partialBookingId)
                        <button type="button" class="btn btn-sm btn-outline-secondary btn-cancel" data-booking-id="{{ $partialBookingId }}">Batalkan</button>
                    @else
                        <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-outline-secondary">Batalkan</a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
    // When JS enabled, allow choose alternative to either fill parent form or POST directly via AJAX
    document.querySelectorAll('.btn-choose-alt').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const date = this.dataset.date;
            const start = this.dataset.start;
            const end = this.dataset.end;

            // Try to find parent booking form; if found, fill values and scroll
            const dateStartEl = document.getElementById('tanggal_mulai');
            const dateEndEl = document.getElementById('tanggal_selesai');
            const jsStartEl = document.getElementById('jam_mulai');
            const jsEndEl = document.getElementById('jam_selesai');

            if (dateStartEl && jsStartEl) {
                dateStartEl.value = date;
                dateEndEl.value = date;
                jsStartEl.value = start;
                jsEndEl.value = end;

                const submit = document.querySelector('form button[type="submit"]');
                if (submit) submit.scrollIntoView({behavior: 'smooth'});
                return;
            }

            // Otherwise, try to submit to reschedule endpoint using hidden form data attributes (requires data-booking-id)
            const bookingId = this.closest('.list-group-item').datasetBookingId || this.dataset.bookingId;
            if (bookingId) {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                // disable buttons in this card while request is running
                const card = this.closest('.card');
                const btns = card.querySelectorAll('button');
                btns.forEach(b => b.disabled = true);
                const spinner = document.createElement('span');
                spinner.className = 'spinner-border spinner-border-sm ms-2';
                this.appendChild(spinner);

                fetch(`/bookings/${bookingId}/reschedule`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ tanggal_mulai: date, tanggal_selesai: date, jam_mulai: start, jam_selesai: end })
                }).then(r => r.json()).then(json => {
                    btns.forEach(b => b.disabled = false);
                    spinner.remove();
                    if (json.success) {
                        alert(json.message || 'Peminjaman dijadwal ulang');
                        window.location = '/bookings';
                    } else if (json.alternatives) {
                        // replace card body with new alternatives returned by server
                        const newHtml = json.alternatives_html || null;
                        if (newHtml) {
                            card.outerHTML = newHtml;
                        } else {
                            alert(json.error || 'Gagal menjadwal ulang');
                        }
                    } else {
                        alert(json.error || 'Gagal menjadwal ulang');
                    }
                }).catch(err => {
                    console.error(err);
                    btns.forEach(b => b.disabled = false);
                    spinner.remove();
                    alert('Gagal menghubungi server');
                });
            }
        });
    });

    // Cancel button via AJAX when booking id present
    document.querySelectorAll('.btn-cancel').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            const bookingId = this.dataset.bookingId;
            if (!bookingId) return;
            if (!confirm('Yakin ingin membatalkan peminjaman ini?')) return;

            const tokenEl = document.querySelector('meta[name="csrf-token"]');
            const token = tokenEl ? tokenEl.getAttribute('content') : '';

            fetch(`/bookings/${bookingId}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            }).then(r => r.json()).then(json => {
                if (json.success) {
                    alert('Peminjaman dibatalkan');
                    window.location = '/bookings';
                } else {
                    alert(json.error || 'Gagal membatalkan peminjaman');
                }
            }).catch(err => {
                console.error(err);
                alert('Gagal menghubungi server');
            });
        });
    });
</script>
@endpush
@endif
