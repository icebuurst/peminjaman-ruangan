# Update Old Approved Bookings - Manual SQL

## Untuk booking yang tanggal nya sudah lewat (auto confirm):
```sql
UPDATE booking 
SET 
    status = 'confirmed',
    confirmed_at = updated_at
WHERE 
    status = 'approved' 
    AND confirmation_deadline IS NULL
    AND tanggal_mulai < CURDATE();
```

## Untuk booking yang tanggalnya masih akan datang (set deadline):
```sql
UPDATE booking 
SET confirmation_deadline = DATE_ADD(NOW(), INTERVAL 12 HOUR)
WHERE 
    status = 'approved' 
    AND confirmation_deadline IS NULL
    AND tanggal_mulai >= CURDATE();
```

## Atau lebih aman, untuk booking ID #12 (yang di screenshot):
```sql
-- Cek dulu booking nya
SELECT id_booking, status, tanggal_mulai, confirmation_deadline 
FROM booking 
WHERE id_booking = 12;

-- Jika tanggal 27 Nov 2025 masih akan datang, set deadline:
UPDATE booking 
SET confirmation_deadline = DATE_ADD(NOW(), INTERVAL 12 HOUR)
WHERE id_booking = 12;

-- Refresh halaman, nanti muncul card konfirmasi
```

## Alternatif: Set Deadline via Artisan Tinker
```bash
php artisan tinker

# Di tinker:
$booking = App\Models\Booking::find(12);
$booking->confirmation_deadline = now()->addHours(12);
$booking->save();
exit
```
