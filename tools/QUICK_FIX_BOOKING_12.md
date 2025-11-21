# CARA CEPAT - Update Booking #12 Agar Bisa Dikonfirmasi

Buka terminal baru, jalankan:

```bash
php artisan tinker
```

Lalu paste command ini:

```php
$booking = App\Models\Booking::find(12);
$booking->confirmation_deadline = now()->addHours(12);
$booking->save();
echo "✅ Booking #12 deadline set! Refresh browser.\n";
exit
```

Atau langsung one-liner:

```bash
php artisan tinker --execute="App\Models\Booking::find(12)->update(['confirmation_deadline' => now()->addHours(12)]); echo '✅ Done!';"
```

Setelah itu refresh halaman browser, nanti card konfirmasi langsung muncul! 🎉

---

## Untuk Booking Baru

Mulai sekarang, setiap booking yang baru di-approve akan **otomatis** dapat `confirmation_deadline` (12 jam), jadi peminjam langsung bisa konfirmasi tanpa harus manual update lagi.

Flow-nya:
1. Peminjam submit booking → pending
2. Admin approve → status jadi approved + deadline set otomatis
3. Peminjam refresh/buka halaman → langsung lihat card konfirmasi
4. Peminjam klik "Ya, Saya Jadi" atau "Tidak Jadi"
