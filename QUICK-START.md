# 🚀 Quick Start Guide

## Menjalankan Website

1. **Buka Terminal/PowerShell**

2. **Masuk ke folder project:**
   ```powershell
   cd e:/RPL/UKK/peminjaman-ruangan
   ```

3. **Jalankan server:**
   ```powershell
   php artisan serve
   ```

4. **Buka browser dan akses:**
   ```
   http://127.0.0.1:8000
   ```

---

## Login Demo

Gunakan akun berikut untuk testing:

### Admin
- **Email:** admin@sekolah.sch.id
- **Password:** password123

### Petugas
- **Email:** petugas@sekolah.sch.id
- **Password:** password123

### Peminjam
- **Email:** peminjam@sekolah.sch.id
- **Password:** password123

---

## Reset Database

Jika ingin mengisi ulang data dummy:

```powershell
php artisan migrate:fresh --seed
```

---

## Stop Server

Tekan `Ctrl + C` di terminal untuk menghentikan server.
