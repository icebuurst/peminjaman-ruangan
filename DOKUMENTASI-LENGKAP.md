# 📚 Dokumentasi Lengkap - Sistem Peminjaman Ruangan

## 🎯 Overview Sistem

Sistem Peminjaman Ruangan adalah aplikasi berbasis web untuk mengelola peminjaman ruangan di sekolah dengan 3 role berbeda: **Admin**, **Petugas**, dan **Peminjam**.

---

## 🚀 Cara Menjalankan Aplikasi

### Prerequisites
- PHP 8.2+
- Composer
- MySQL/SQLite
- Laravel 11.x

### Langkah Instalasi

1. **Clone atau Extract Project**
   ```bash
   cd peminjaman-ruangan
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Setup Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Setup Database**
   - Buka file `.env`
   - Pastikan `DB_CONNECTION=sqlite` (atau sesuaikan dengan MySQL)

5. **Migrate & Seed Database**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Jalankan Server**
   ```bash
   php artisan serve
   ```

7. **Akses Aplikasi**
   - Buka browser: `http://127.0.0.1:8000`

---

## 👥 Akun Demo

### Admin
```
Email: admin@sekolah.sch.id
Password: password123
```

### Petugas
```
Email: petugas@sekolah.sch.id
Password: password123
```

### Peminjam (Guru)
```
Email: siti.guru@sekolah.sch.id
Password: password123
```

### Peminjam (Siswa)
```
Email: ahmad.siswa@sekolah.sch.id
Password: password123
```

---

## 📋 Fitur Berdasarkan Role

### 👨‍💼 Admin
✅ **Manajemen User**
- Lihat semua user
- Tambah, edit, hapus user
- Atur role user

✅ **Manajemen Ruangan**
- Lihat daftar ruangan
- Tambah ruangan baru (nama, lokasi, kapasitas, deskripsi, foto)
- Edit detail ruangan
- Hapus ruangan

✅ **Manajemen Peminjaman**
- Lihat semua peminjaman
- Approve/Reject peminjaman
- Edit status peminjaman
- Hapus peminjaman

✅ **Jadwal Ruangan**
- Lihat jadwal reguler semua ruangan
- Tambah jadwal reguler baru
- Edit jadwal reguler
- Hapus jadwal reguler

✅ **Laporan**
- Generate laporan peminjaman
- Export data

✅ **Dashboard**
- Statistik total ruangan
- Statistik total peminjaman
- Daftar peminjaman pending
- Peminjaman hari ini

---

### 🛠️ Petugas
✅ **Manajemen Peminjaman**
- Lihat semua peminjaman
- Approve/Reject peminjaman
- Edit status peminjaman

✅ **Jadwal Ruangan**
- Lihat jadwal reguler semua ruangan
- Lihat ketersediaan ruangan

✅ **Laporan**
- Generate laporan peminjaman
- Export data

✅ **Dashboard**
- Statistik peminjaman
- Daftar peminjaman pending
- Peminjaman hari ini

---

### 📝 Peminjam (Guru/Siswa)
✅ **Ajukan Peminjaman**
- Pilih ruangan
- Pilih tanggal & waktu
- Isi keperluan peminjaman
- Submit pengajuan

✅ **Lihat Jadwal**
- Lihat jadwal reguler ruangan
- Cek ketersediaan ruangan
- Lihat booking yang ada

✅ **Riwayat Peminjaman**
- Lihat peminjaman saya
- Status peminjaman (pending/disetujui/ditolak/selesai)
- Detail peminjaman

✅ **Dashboard**
- Statistik peminjaman saya
- Jumlah pending
- Jumlah disetujui

---

## 🗄️ Database Schema

### Tabel: `users`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT (PK) | ID user |
| name | VARCHAR | Nama lengkap |
| email | VARCHAR (UNIQUE) | Email login |
| password | VARCHAR | Password (hashed) |
| role | ENUM | admin, petugas, peminjam |
| identity | VARCHAR | NIP/NIS/ID |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |

### Tabel: `room`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id_room | BIGINT (PK) | ID ruangan |
| nama_room | VARCHAR | Nama ruangan |
| lokasi | VARCHAR | Lokasi ruangan |
| deskripsi | TEXT | Deskripsi ruangan |
| kapasitas | INT | Kapasitas orang |
| foto | VARCHAR | Path foto |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |

### Tabel: `booking`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id_booking | BIGINT (PK) | ID booking |
| id_room | BIGINT (FK) | ID ruangan |
| id_user | BIGINT (FK) | ID peminjam |
| keperluan | VARCHAR | Keperluan peminjaman |
| tanggal_mulai | DATE | Tanggal mulai |
| tanggal_selesai | DATE | Tanggal selesai |
| mulai | DATETIME | Waktu mulai |
| selesai | DATETIME | Waktu selesai |
| status | ENUM | pending, disetujui, ditolak, selesai |
| catatan | TEXT | Catatan petugas |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |

### Tabel: `jadwal_reguler`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id_reguler | BIGINT (PK) | ID jadwal |
| id_room | BIGINT (FK) | ID ruangan |
| nama_kegiatan | VARCHAR | Nama kegiatan |
| hari | ENUM | Senin-Minggu |
| jam_mulai | TIME | Jam mulai |
| jam_selesai | TIME | Jam selesai |
| penanggung_jawab | VARCHAR | Nama PJ |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |

---

## 🔐 Auto Role Detection

Sistem mendeteksi role otomatis dari email saat registrasi:

- Email dengan prefix `admin@` → Role: **Admin**
- Email dengan prefix `petugas@` → Role: **Petugas**
- Email lainnya → Role: **Peminjam**

Contoh:
- `admin@sekolah.sch.id` → Admin
- `petugas@sekolah.sch.id` → Petugas
- `john.doe@sekolah.sch.id` → Peminjam

---

## 🎨 Teknologi yang Digunakan

### Backend
- **Framework**: Laravel 11.x
- **PHP**: 8.2+
- **Database**: SQLite/MySQL
- **ORM**: Eloquent
- **Authentication**: Laravel Auth

### Frontend
- **CSS Framework**: Bootstrap 5.3.3
- **JavaScript**: Vue.js 3 (CDN)
- **Icons**: Bootstrap Icons
- **Font**: Inter (Google Fonts)

### Konsep OOP
- **Models**: User, Room, Booking, JadwalReguler
- **Controllers**: RoomController, BookingController, JadwalRegulerController, AuthController, DashboardController
- **Relationships**: One-to-Many, Belongs-To
- **Validation**: Request Validation
- **Middleware**: Auth Middleware

---

## 📁 Struktur Folder

```
peminjaman-ruangan/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AuthController.php
│   │       ├── DashboardController.php
│   │       ├── RoomController.php
│   │       ├── BookingController.php
│   │       └── JadwalRegulerController.php
│   └── Models/
│       ├── User.php
│       ├── Room.php
│       ├── Booking.php
│       └── JadwalReguler.php
├── database/
│   ├── migrations/
│   │   ├── create_users_table.php
│   │   ├── create_room_table.php
│   │   ├── create_booking_table.php
│   │   └── create_jadwal_reguler_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   └── views/
│       ├── app.blade.php (Login/Register)
│       ├── dashboard.blade.php
│       ├── rooms/
│       ├── bookings/
│       └── jadwal-reguler/
└── routes/
    └── web.php
```

---

## 🔄 Flow Peminjaman

1. **Peminjam** mengajukan peminjaman:
   - Pilih ruangan
   - Pilih tanggal & waktu
   - Isi keperluan
   - Submit (status: `pending`)

2. **Petugas/Admin** melihat peminjaman pending:
   - Dashboard menampilkan pending list
   - Klik "Detail" untuk review

3. **Petugas/Admin** approve/reject:
   - Approve → status: `disetujui`
   - Reject → status: `ditolak` (tambahkan catatan alasan)

4. **Peminjam** melihat hasil:
   - Dashboard menampilkan status terbaru
   - Notifikasi status peminjaman

5. **Setelah selesai**:
   - Petugas ubah status → `selesai`

---

## 📊 Data Seed (Demo)

### Users (8)
- 1 Admin
- 2 Petugas
- 2 Guru (Peminjam)
- 3 Siswa (Peminjam)

### Ruangan (8)
- Lab Komputer 1 (40 orang)
- Lab Komputer 2 (30 orang)
- Lab Jaringan (35 orang)
- Aula Serba Guna (200 orang)
- Ruang Baca Perpustakaan (20 orang)
- Ruang Kelas 10A (36 orang)
- Ruang Kelas 11B (36 orang)
- Ruang Rapat Guru (25 orang)

### Booking (7)
- 2 Disetujui
- 2 Pending
- 1 Ditolak
- 2 Selesai

### Jadwal Reguler (8)
- Praktikum RPL, Multimedia, Jaringan
- Upacara Bendera
- Rapat Guru
- Literasi Pagi
- Mata pelajaran reguler

---

## 🐛 Troubleshooting

### Error: "Column not found: created_at"
**Solusi**: Jalankan `php artisan migrate:fresh --seed`

### Error: "Class not found"
**Solusi**: Jalankan `composer dump-autoload`

### Error: "SQLSTATE[HY000]"
**Solusi**: Periksa konfigurasi `.env`, pastikan database sudah dibuat

### Login tidak bisa
**Solusi**: 
1. Clear cache: `php artisan cache:clear`
2. Clear view: `php artisan view:clear`
3. Regenerate key: `php artisan key:generate`

---

## 📝 Catatan Pengembangan

### Fitur Sesuai Requirement UKK
✅ Client-Server (Web-based)
✅ Role-based Access Control (Admin, Petugas, Peminjam)
✅ Object-Oriented Programming (Models, Controllers)
✅ Database CRUD Operations
✅ UI/UX Modern & Responsif
✅ Login/Logout/Register
✅ Manajemen Ruangan
✅ Manajemen Peminjaman
✅ Jadwal Ruangan
✅ Laporan (Dashboard Statistik)

### Fitur Tambahan (Bonus)
🎁 Auto Role Detection dari Email
🎁 Full-screen Modern Login Page
🎁 Dashboard dengan Statistik
🎁 Bootstrap 5 Responsive Design
🎁 Vue.js 3 Interactive Components
🎁 Timestamps untuk Audit Trail

---

## 👨‍💻 Developer Notes

**Tanggal Dibuat**: 15 November 2025
**Framework**: Laravel 11.37.0
**PHP Version**: 8.2.28
**Database**: SQLite (production-ready untuk MySQL)

**Contact**: Untuk pertanyaan atau issue, silakan hubungi developer.

---

## ✅ Checklist UKK

- [x] Sistem berbasis Client-Server ✅
- [x] Role-based access (Admin, Petugas, Peminjam) ✅
- [x] OOP implementation ✅
- [x] Database dengan CRUD ✅
- [x] UI/UX yang baik ✅
- [x] Login/Logout/Register ✅
- [x] Manajemen User (Admin) ✅
- [x] Manajemen Ruangan (Admin) ✅
- [x] Manajemen Peminjaman (Admin/Petugas) ✅
- [x] Ajukan Peminjaman (Peminjam) ✅
- [x] Lihat Jadwal ✅
- [x] Laporan/Dashboard ✅
- [x] Aplikasi berjalan dengan baik ✅

**Status**: ✅ READY FOR SUBMISSION

---

**Good Luck dengan UKK! 🚀**
