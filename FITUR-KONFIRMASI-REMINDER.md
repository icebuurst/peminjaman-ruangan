# Fitur Konfirmasi Peminjaman & Reminder

## 📋 Overview

Fitur baru yang menambahkan sistem konfirmasi peminjaman setelah approval dan reminder otomatis untuk admin/petugas.

## 🎯 Fitur yang Ditambahkan

### 1. **Konfirmasi Peminjam (12 Jam)**
Setelah peminjaman di-**approve oleh ADMIN/PETUGAS**:
- **PEMINJAM** wajib **konfirmasi** apakah jadi memakai ruangan atau tidak
- Batas waktu konfirmasi: **12 jam** sejak di-approve
- Pilihan konfirmasi: **"Ya, Saya Jadi"** atau **"Tidak Jadi"**
- Jika lewat 12 jam tanpa konfirmasi dari peminjam → otomatis **EXPIRED**

### 2. **Reminder Admin/Petugas (Setiap 6 Jam)**
Untuk peminjaman yang masih **PENDING** (belum di-approve/reject):
- Sistem kirim **notifikasi reminder** ke **ADMIN/PETUGAS** setiap **6 jam**
- Mencegah peminjaman pending tertimbun dan terlupakan
- Notifikasi otomatis menghitung berapa lama sudah pending
- Reminder terus dikirim sampai admin/petugas approve atau reject

---

## 🔄 Flow Proses Baru

```
1. PEMINJAM Submit Booking
   ↓
   Status: PENDING
   ↓
   [Reminder setiap 6 jam ke Admin/Petugas: "Ada peminjaman pending!"]
   ↓
2. ADMIN/PETUGAS Approve Peminjaman
   ↓
   Status: APPROVED (Menunggu Konfirmasi Peminjam)
   + Set confirmation_deadline = now + 12 jam
   + Notifikasi ke PEMINJAM: "Peminjaman disetujui, konfirmasi dalam 12 jam!"
   ↓
3. PEMINJAM Harus Konfirmasi dalam 12 Jam:
   
   3a. PEMINJAM Klik "Ya, Saya Jadi"
       ↓
       Status: CONFIRMED ✅
       + confirmed_at = now
       + Notifikasi ke Admin/Petugas: "Peminjam sudah konfirmasi jadi"
       
   3b. PEMINJAM Klik "Tidak Jadi" 
       ↓
       Status: CANCELLED_BY_USER ❌
       + Notifikasi ke Admin/Petugas: "Peminjam batalkan booking"
       
   3c. Lewat 12 Jam Tanpa Konfirmasi (Auto oleh System)
       ↓
       Status: EXPIRED ⏱️
       + Notifikasi ke PEMINJAM: "Booking expired karena tidak konfirmasi"
       + Notifikasi ke Admin/Petugas: "Booking expired"
```

---

## 📊 Status Baru

| Status | Deskripsi | Warna Badge |
|--------|-----------|-------------|
| `pending` | Menunggu persetujuan | Kuning |
| `approved` | Disetujui, butuh konfirmasi user | Orange |
| `confirmed` | User sudah konfirmasi jadi | Hijau |
| `rejected` | Ditolak admin/petugas | Merah |
| `cancelled_by_user` | User batalkan setelah approve | Kuning |
| `expired` | Lewat batas 12 jam tanpa konfirmasi | Abu-abu |

---

## 🗄️ Database Changes

### Migration: `add_confirmation_fields_to_bookings_table`

```php
$table->timestamp('confirmed_at')->nullable();
$table->timestamp('confirmation_deadline')->nullable();
$table->timestamp('last_reminder_sent_at')->nullable();
```

**Kolom Baru:**
- `confirmed_at`: Timestamp saat user konfirmasi
- `confirmation_deadline`: Batas waktu konfirmasi (12 jam setelah approval)
- `last_reminder_sent_at`: Tracking kapan reminder terakhir dikirim

---

## 🎨 UI/UX Changes

### 1. **Bookings Show Page**
**Untuk Peminjam (Status = Approved):**
```
┌─────────────────────────────────────────┐
│ ⚠️ Konfirmasi Diperlukan                │
├─────────────────────────────────────────┤
│ Perhatian! Peminjaman telah disetujui.  │
│ Silakan konfirmasi apakah jadi atau     │
│ tidak.                                   │
│                                          │
│ 🕐 Batas waktu: 21 Nov 2025 20:00       │
│ (10 jam lagi)                            │
│                                          │
│ [✅ Ya, Saya Jadi] [❌ Tidak Jadi]      │
└─────────────────────────────────────────┘
```

### 2. **Bookings Index Page**
Status Badge menampilkan:
- **Pending** → "⏳ Pending"
- **Approved** → "⚠️ Butuh Konfirmasi"
- **Confirmed** → "✅ Dikonfirmasi"
- **Rejected** → "❌ Ditolak"
- **Cancelled by User** → "❌ Dibatalkan"
- **Expired** → "🕐 Kadaluarsa"

---

## ⚙️ Backend Implementation

### 1. **Booking Model**
```php
// Status constants
const STATUS_PENDING = 'pending';
const STATUS_APPROVED = 'approved';
const STATUS_CONFIRMED = 'confirmed';
const STATUS_REJECTED = 'rejected';
const STATUS_CANCELLED_BY_USER = 'cancelled_by_user';
const STATUS_EXPIRED = 'expired';

// Helper methods
isAwaitingConfirmation()
isConfirmationExpired()
needsConfirmation()
getConfirmationRemainingHours()
```

### 2. **BookingController - New Methods**
```php
confirm($id)  // Peminjam konfirmasi jadi
decline($id)  // Peminjam batalkan
```

**Updated:**
- `updateStatus()` - Set confirmation_deadline saat approve

### 3. **Routes**
```php
Route::post('/bookings/{booking}/confirm', [BookingController::class, 'confirm']);
Route::post('/bookings/{booking}/decline', [BookingController::class, 'decline']);
```

---

## 🤖 Scheduled Commands

### 1. **Check Expired Bookings**
```bash
php artisan bookings:check-expired
```

**Fungsi:**
- Cek bookings dengan status `approved`
- Yang sudah lewat `confirmation_deadline`
- Ubah status jadi `expired`
- Kirim notifikasi ke user & admin/petugas

**Schedule:** Setiap 1 jam

### 2. **Send Pending Reminders**
```bash
php artisan bookings:send-pending-reminders
```

**Fungsi:**
- Cek bookings dengan status `pending`
- Yang belum di-remind dalam 6 jam terakhir
- Kirim notifikasi reminder ke admin/petugas
- Update `last_reminder_sent_at`

**Schedule:** Setiap 6 jam

### Registered in `app/Console/Kernel.php`:
```php
$schedule->command('bookings:check-expired')->hourly();
$schedule->command('bookings:send-pending-reminders')->everySixHours();
```

---

## 📬 Notifikasi Baru

### 1. **Untuk Peminjam:**
- `booking_confirmation_needed` → Peminjaman disetujui, butuh konfirmasi
- `booking_expired` → Peminjaman kadaluarsa (lewat 12 jam)

### 2. **Untuk Admin/Petugas:**
- `booking_confirmed` → User konfirmasi jadi
- `booking_cancelled` → User batalkan peminjaman
- `booking_expired_admin` → Peminjaman kadaluarsa
- `booking_pending_reminder` → Reminder ada peminjaman pending

---

## 🚀 Cara Menggunakan

### Setup Schedule (Hosting/Production)

**1. Setup Cron Job** (Tambahkan ke crontab):
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

**2. Test Manual:**
```bash
# Test check expired
php artisan bookings:check-expired

# Test send reminders
php artisan bookings:send-pending-reminders

# Test schedule work
php artisan schedule:list
php artisan schedule:test
```

### Local Development

Run schedule worker:
```bash
php artisan schedule:work
```

---

## ✅ Testing Checklist

### Skenario 1: Konfirmasi Normal (Happy Path)
1. ✅ **PEMINJAM** submit booking → Status: `pending`
2. ✅ **ADMIN/PETUGAS** approve → Status: `approved`, deadline set (+12 jam)
3. ✅ **PEMINJAM** terima notifikasi: "Peminjaman disetujui, konfirmasi dalam 12 jam"
4. ✅ **PEMINJAM** buka halaman booking, lihat card konfirmasi
5. ✅ **PEMINJAM** klik "Ya, Saya Jadi" → Status: `confirmed`
6. ✅ **ADMIN/PETUGAS** terima notifikasi: "Peminjam sudah konfirmasi jadi"

### Skenario 2: Peminjam Batalkan
1. ✅ **PEMINJAM** submit booking → Status: `pending`
2. ✅ **ADMIN/PETUGAS** approve → Status: `approved`
3. ✅ **PEMINJAM** terima notifikasi konfirmasi
4. ✅ **PEMINJAM** klik "Tidak Jadi" → Status: `cancelled_by_user`
5. ✅ **ADMIN/PETUGAS** terima notifikasi: "Peminjam batalkan booking"

### Skenario 3: Expired (Lewat 12 Jam Tanpa Konfirmasi)
1. ✅ **PEMINJAM** submit booking → Status: `pending`
2. ✅ **ADMIN/PETUGAS** approve → Status: `approved`, deadline set
3. ⏰ **PEMINJAM** tidak konfirmasi dalam 12 jam
4. ✅ System command `check-expired` run → Status: `expired`
5. ✅ **PEMINJAM** terima notifikasi: "Booking kadaluarsa karena tidak dikonfirmasi"
6. ✅ **ADMIN/PETUGAS** terima notifikasi: "Booking kadaluarsa (tidak dikonfirmasi peminjam)"

### Skenario 4: Reminder Pending (Belum Di-approve)
1. ✅ **PEMINJAM** submit booking → Status: `pending`
2. ⏰ Lewat 6 jam belum di-approve oleh admin/petugas
3. ✅ System command `send-pending-reminders` run
4. ✅ **ADMIN/PETUGAS** terima notifikasi reminder: "Ada peminjaman pending sejak X jam"
5. ⏰ Setiap 6 jam reminder terkirim lagi sampai di-approve/reject

---

## 🎯 Benefits

1. **Untuk Peminjam:**
   - Jelas harus konfirmasi atau tidak setelah disetujui
   - Transparansi batas waktu konfirmasi

2. **Untuk Admin/Petugas:**
   - Tidak lupa ada peminjaman pending (reminder otomatis)
   - Tahu mana peminjaman yang sudah pasti jadi (confirmed)
   - Expired otomatis dibersihkan sistemnya

3. **Untuk Sistem:**
   - Data lebih akurat (confirmed vs just approved)
   - Menghindari ruangan "dibooking tapi ga dipake"
   - Automasi pengelolaan expired bookings

---

## 📝 Notes

- Confirmation deadline: **12 jam** (bisa diubah di controller)
- Reminder interval: **6 jam** (bisa diubah di Kernel)
- Expired check: **setiap 1 jam** (bisa diubah di Kernel)
- Semua waktu menggunakan timezone server

---

## 🔧 Maintenance

### Jika ingin ubah durasi:

**1. Ubah Confirmation Deadline (default 12 jam):**
```php
// BookingController.php line ~429
$booking->confirmation_deadline = now()->addHours(24); // Ganti jadi 24 jam
```

**2. Ubah Reminder Interval (default 6 jam):**
```php
// app/Console/Kernel.php
$schedule->command('bookings:send-pending-reminders')->everyFourHours(); // Ganti jadi 4 jam
```

**3. Ubah Check Expired Frequency:**
```php
// app/Console/Kernel.php
$schedule->command('bookings:check-expired')->everyThirtyMinutes(); // Ganti jadi 30 menit
```

---

## 🐛 Troubleshooting

### Command tidak jalan otomatis
1. Cek cron job sudah terpasang:
   ```bash
   crontab -l
   ```
2. Test schedule work:
   ```bash
   php artisan schedule:work
   ```

### Notifikasi tidak muncul
1. Cek table `notifications` di database
2. Cek `user_id` sudah benar
3. Refresh halaman untuk update unread count

### Status tidak berubah ke expired
1. Jalankan command manual:
   ```bash
   php artisan bookings:check-expired
   ```
2. Cek log error di `storage/logs/laravel.log`

---

**Created:** 21 November 2025  
**Version:** 1.0  
**Developer:** GitHub Copilot + User
