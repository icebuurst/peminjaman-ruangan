Backup otomatis (spatie/laravel-backup)

Ringkasan
- Paket: spatie/laravel-backup
- Konfigurasi: `config/backup.php` dan `config/database.php` (gunakan `DB_DUMP_PATH` untuk menunjuk lokasi `mysqldump` di Windows)

Cara kerja
1. Scheduler Laravel menjalankan perintah `backup:run` sesuai jadwal di `app/Console/Kernel.php`.
2. Untuk lingkungan Windows (Laragon/XAMPP), pastikan `DB_DUMP_PATH` diarahkan ke folder `bin` MySQL yang berisi `mysqldump.exe`.

Perintah manual
- Jalankan backup DB-only:
```powershell
php artisan backup:run --only-db
```
- Jalankan backup penuh:
```powershell
php artisan backup:run
```

Memeriksa file backup
- Lokasi default: disk `local` (lihat `config/filesystems.php`). Biasanya ada di `storage/app/` atau `storage/app/backups`.
- Contoh: `storage/app/laravel-backup-<timestamp>.zip`

Menjalankan scheduler di Windows
- Laravel scheduler perlu dijalankan tiap menit; di Windows gunakan Task Scheduler untuk menjalankan:
```powershell
php "E:\RPL\peminjaman-ruangan\artisan" schedule:run
```
- Set task untuk berjalan setiap menit.

Men-debug masalah umum
- Error: `"mysqldump" is not recognized...` => set `DB_DUMP_PATH` di `.env` ke path bin MySQL, lalu jalankan `php artisan config:clear`.
- Pastikan user yang menjalankan Task Scheduler punya akses ke folder proyek dan `php.exe`.

Optional
- Tambahkan notifikasi email di `config/backup.php` untuk menerima notifikasi apabila backup gagal atau sukses.
