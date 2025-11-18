# 🔧 Fix: Blade vs Vue.js Syntax Conflict

**Error:** `Undefined constant "activeTab"`  
**File:** `resources/views/app.blade.php:837`  
**Status:** ✅ Fixed

---

## ❌ Problem

Blade template engine mencoba mengevaluasi `{{ activeTab }}` sebagai **PHP code**, bukan sebagai **Vue.js variable**.

```blade
<!-- ❌ SALAH - Blade akan coba evaluasi ini sebagai PHP -->
{{ activeTab === "login" ? "Masuk ke akun" : "Buat akun baru" }}
{{ errors.email }}
{{ toast.title }}
```

---

## ✅ Solution

Gunakan `@{{ }}` untuk Vue.js variables di dalam Blade template:

```blade
<!-- ✅ BENAR - @ memberitahu Blade untuk skip evaluasi -->
@{{ activeTab === "login" ? "Masuk ke akun" : "Buat akun baru" }}
@{{ errors.email }}
@{{ toast.title }}
```

---

## 📝 Changes Made

Updated all Vue.js interpolations in `app.blade.php`:

1. **Line 837:** Title header
   ```blade
   @{{ activeTab === "login" ? "Masuk ke akun" : "Buat akun baru" }}
   ```

2. **Line 929:** Email error
   ```blade
   @{{ errors.email }}
   ```

3. **Line 958:** Password error
   ```blade
   @{{ errors.password }}
   ```

4. **Line 976:** Name error
   ```blade
   @{{ errors.name }}
   ```

5. **Line 1027:** Submit button text
   ```blade
   @{{ activeTab === "login" ? "Masuk sekarang" : "Daftar sebagai peminjam" }}
   ```

6. **Lines 1067-1068:** Toast header
   ```blade
   @{{ toast.title }}
   @{{ toast.subtitle }}
   ```

7. **Line 1077:** Toast message
   ```blade
   @{{ toast.message }}
   ```

---

## 🔑 Key Points

| Syntax | Usage | Description |
|--------|-------|-------------|
| `{{ }}` | Blade/PHP | Evaluasi PHP expression |
| `@{{ }}` | Vue.js | Skip Blade, render as `{{ }}` untuk Vue |
| `v-if`, `v-model`, `:disabled` | Vue.js | Directive tetap tanpa `@` |

---

## 🚀 Testing

```bash
# Clear view cache
php artisan view:clear

# Restart server
php artisan serve
```

Access: **http://127.0.0.1:8000**

---

## ✅ Result

Website sekarang berjalan tanpa error! Vue.js dapat mengakses semua reactive variables dengan benar.

---

**Fixed by:** GitHub Copilot  
**Date:** November 14, 2025, 23:10 WIB
