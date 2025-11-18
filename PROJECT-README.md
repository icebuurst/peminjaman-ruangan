# 🏢 Sistem Peminjaman Ruangan

Aplikasi web modern untuk mengelola peminjaman ruangan berbasis Laravel dengan UI/UX yang menarik dan interaktif.

![Version](https://img.shields.io/badge/version-2.0-cyan)
![Laravel](https://img.shields.io/badge/Laravel-11.37.0-red)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3.3-purple)
![Status](https://img.shields.io/badge/status-production-green)

## ✨ Features

### 🎨 Modern UI/UX
- **Custom Color Palette**: Black, Grey & Cyan Accent
- **Toast Notifications**: Smooth notifications dengan Toastify.js
- **Animations**: Scroll animations dengan AOS library
- **Charts**: Interactive dashboard dengan Chart.js
- **Calendar View**: FullCalendar integration untuk booking
- **Image Lightbox**: GLightbox untuk zoom foto ruangan
- **Loading States**: Spinner dan skeleton loaders
- **Hover Effects**: Rich interactions pada cards dan buttons

### 📊 Dashboard
- **Statistics Cards**: Total rooms, bookings, pending, users
- **Booking Trend**: Line chart 7 hari terakhir
- **Status Distribution**: Doughnut chart status booking
- **Room Usage**: Bar chart ruangan paling populer

### 🏢 Room Management
- CRUD Ruangan (Create, Read, Update, Delete)
- Upload foto ruangan dengan preview
- Filter dan search ruangan
- Lightbox untuk zoom foto

### 📅 Booking System
- Create booking dengan form validation
- Approval workflow (Pending → Approved/Rejected)
- View bookings dengan tab filter (All, Pending, Approved, Rejected)
- Calendar view dengan color-coded events
- Booking details dengan status badge

### 📑 Laporan & Export
- Filter laporan by date range
- Preview data sebelum export
- Export to Excel dengan styling
- Summary statistics

### 👥 User Roles
- **Admin**: Full access semua fitur
- **Petugas**: Manage rooms dan approve bookings
- **Peminjam**: Create dan view own bookings

## 🚀 Installation

### Prerequisites
- PHP >= 8.2
- Composer
- MySQL
- Node.js & NPM (optional, untuk compile assets)

### Setup Laravel

1. Navigate to the project directory:
```bash
cd peminjaman-ruangan
```

2. Install PHP dependencies:
```bash
composer install
```

3. Set up environment variables:
```bash
cp .env.example .env
```
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=peminjaman_ruangan
DB_USERNAME=root
DB_PASSWORD=
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Create database and run migrations:
```bash
php artisan migrate --seed
```

6. Create storage link:
```bash
php artisan storage:link
```

7. Clear cache:
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

8. Start the development server:
```bash
php artisan serve
```

The application will be available at **http://127.0.0.1:8000**

## 🔑 Default Users

After seeding, you can login with:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | password |
| Petugas | petugas@example.com | password |
| Peminjam | peminjam@example.com | password |

## 📦 Tech Stack

### Backend
- **Laravel**: 11.37.0
- **PHP**: 8.2+
- **MySQL**: Database
- **Laravel Excel**: Export functionality

### Frontend
- **Bootstrap**: 5.3.3
- **Chart.js**: Data visualization
- **FullCalendar**: Calendar view
- **GLightbox**: Image lightbox
- **Toastify.js**: Toast notifications
- **AOS**: Scroll animations

### Libraries (CDN)
All loaded via CDN - no build step required!

## 📁 Project Structure

```
peminjaman-ruangan/
├── app/
│   ├── Exports/
│   │   └── BookingsExport.php          # Excel export class
│   ├── Http/Controllers/
│   │   ├── AppController.php           # Auth & dashboard
│   │   ├── BookingController.php       # Booking CRUD & features
│   │   ├── DashboardController.php     # Dashboard with charts
│   │   ├── JadwalRegulerController.php # Regular schedule
│   │   └── RoomController.php          # Room CRUD
│   └── Models/
│       ├── Booking.php                 # Booking model
│       ├── JadwalReguler.php           # Schedule model
│       ├── Room.php                    # Room model
│       └── User.php                    # User model
├── database/
│   ├── migrations/                     # Database schema
│   └── seeders/                        # Sample data
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php           # Master layout (UI framework)
│       ├── auth/                       # Login, register
│       ├── bookings/                   # Booking views
│       │   ├── index.blade.php         # List with tabs
│       │   ├── create.blade.php        # Create form
│       │   ├── edit.blade.php          # Edit form
│       │   ├── show.blade.php          # Detail view
│       │   ├── laporan.blade.php       # Reports & export
│       │   └── calendar.blade.php      # Calendar view
│       ├── rooms/                      # Room views
│       │   ├── index.blade.php         # List with animations
│       │   ├── create.blade.php        # Create form
│       │   ├── edit.blade.php          # Edit form
│       │   └── show.blade.php          # Detail with lightbox
│       ├── jadwal-reguler/             # Regular schedule views
│       └── dashboard.blade.php         # Dashboard with charts
└── routes/
    └── web.php                         # Route definitions
```

## 🎨 UI/UX Documentation

Lihat file [DOKUMENTASI-UI-ENHANCEMENT.md](../DOKUMENTASI-UI-ENHANCEMENT.md) untuk:
- Color palette details
- Animation guide
- Chart implementation
- Library usage
- Developer notes

## 📱 Features Walkthrough

### 1. Login & Dashboard
- Login dengan role berbeda (admin/petugas/peminjam)
- Dashboard menampilkan stats dan 3 charts interaktif
- Toast notification saat login success

### 2. Room Management (Admin/Petugas)
- Lihat daftar ruangan dengan scroll animation
- Click "Tambah Ruangan" untuk create
- Upload foto ruangan
- Click foto → lightbox zoom
- Edit/delete room

### 3. Booking Process (All Roles)
- Click "Tambah Peminjaman"
- Select ruangan, tanggal, jam, keperluan
- Form validation
- Loading spinner saat submit
- Toast notification success

### 4. Calendar View
- Menu "Calendar View" di sidebar
- Monthly/weekly/daily views
- Color-coded events by status
- Click event → modal detail

### 5. Approval Workflow (Admin/Petugas)
- View pending bookings di dashboard
- Go to "Semua Peminjaman"
- Filter by status (Pending tab)
- Click detail → Setujui/Tolak
- Toast notification

### 6. Export Laporan
- Menu "Laporan"
- Set date range
- Preview data
- Click "Export Excel"
- Download styled Excel file

## 🧪 Testing

### Manual Testing
1. Login as each role
2. Test CRUD operations
3. Test approval workflow
4. Export Excel
5. Test calendar
6. Test lightbox
7. Verify animations
8. Check toast notifications

### Browser Testing
- ✅ Chrome/Edge (recommended)
- ✅ Firefox
- ✅ Safari
- ✅ Mobile browsers

## 🐛 Troubleshooting

### Charts tidak muncul
- Check browser console untuk errors
- Verify Chart.js CDN loaded
- Ensure controller sends chart data

### Calendar kosong
- Check ada data booking di database
- Verify FullCalendar CDN loaded
- Check route `/bookings-calendar` accessible

### Lightbox tidak work
- Verify GLightbox CDN loaded
- Check image src valid
- Ensure class `glightbox` on anchor tag

### Animations tidak smooth
- Clear browser cache
- Check AOS CDN loaded
- Verify `data-aos` attributes on elements

## 📝 Development Notes

### Adding New Feature
1. Create controller method
2. Add route in `web.php`
3. Create view in `resources/views`
4. Use color palette CSS variables
5. Add AOS animations
6. Test and verify

### Modifying Colors
Edit CSS variables in `layouts/app.blade.php`:
```css
:root {
    --color-black: #191919;
    --color-cyan: #1ceff4;
    /* etc */
}
```

## 🔮 Future Enhancements

- [ ] Dark mode toggle
- [ ] Real-time notifications (WebSocket)
- [ ] Drag & drop upload
- [ ] Advanced search filters
- [ ] Room rating system
- [ ] Export to PDF
- [ ] Email notifications
- [ ] Mobile app (Flutter)

## 📞 Support

For issues or questions:
1. Check browser console
2. Clear cache (Ctrl+F5)
3. Review documentation
4. Check Laravel logs in `storage/logs`

## 📄 License

This project is open-source and available for educational purposes.

---

**Version**: 2.0 - UI/UX Enhanced  
**Last Updated**: November 15, 2025  
**Status**: ✅ Production Ready

Made with ❤️ using Laravel & modern web technologies
