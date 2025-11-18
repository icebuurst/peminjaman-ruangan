<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\JadwalRegulerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;

// Redirect root to login
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

// Public routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
// Registration - only for peminjam (self-registration), only for guests
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rooms - ONLY ADMIN can CRUD, others can only view
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    
    // CRUD routes - admin only (MUST BE BEFORE {room} route!)
    Route::middleware('can:isAdmin')->group(function () {
        Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
        Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
        Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
        Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
        Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');
    });
    
    // View detail - all can access (MUST BE AFTER /rooms/create!)
    Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');
    
    // Bookings - all can view, ONLY PEMINJAM can create (pengajuan), admin & petugas can approve/reject
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    
    // Pengajuan Peminjaman - ONLY PEMINJAM (MUST BE BEFORE {booking} route!)
    Route::middleware('can:isPeminjam')->group(function () {
        Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
        Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
        Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    });
    
    // View detail - all can access (MUST BE AFTER /bookings/create!)
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    
    // Approval - admin & petugas only
    Route::post('/bookings/{id}/status', [BookingController::class, 'updateStatus'])
        ->name('bookings.updateStatus')
        ->middleware('can:isAdminOrPetugas');
    
    // Laporan Peminjaman - admin & petugas only
    Route::middleware('can:isAdminOrPetugas')->group(function () {
        Route::get('/bookings-laporan', [BookingController::class, 'laporan'])
            ->name('bookings.laporan');
        Route::get('/bookings-export', [BookingController::class, 'export'])
            ->name('bookings.export');
    });
    
    // Jadwal Reguler - admin & petugas can CRUD, peminjam can only view
    Route::get('/jadwal-reguler', [JadwalRegulerController::class, 'index'])->name('jadwal-reguler.index');
    
    // CRUD routes - admin & petugas only (MUST BE BEFORE {jadwal_reguler} route!)
    Route::middleware('can:isAdminOrPetugas')->group(function () {
        Route::get('/jadwal-reguler/create', [JadwalRegulerController::class, 'create'])->name('jadwal-reguler.create');
        Route::post('/jadwal-reguler', [JadwalRegulerController::class, 'store'])->name('jadwal-reguler.store');
        Route::get('/jadwal-reguler/{jadwal_reguler}/edit', [JadwalRegulerController::class, 'edit'])->name('jadwal-reguler.edit');
        Route::put('/jadwal-reguler/{jadwal_reguler}', [JadwalRegulerController::class, 'update'])->name('jadwal-reguler.update');
        Route::delete('/jadwal-reguler/{jadwal_reguler}', [JadwalRegulerController::class, 'destroy'])->name('jadwal-reguler.destroy');
    });
    
    // View detail - all can access (MUST BE AFTER /create and /edit routes!)
    Route::get('/jadwal-reguler/{jadwal_reguler}', [JadwalRegulerController::class, 'show'])->name('jadwal-reguler.show');
    
    // Notifications - accessible by all authenticated users
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])
        ->name('notifications.unreadCount');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.markAllAsRead');
    
    // User Management - admin only
    Route::resource('users', UserController::class)->middleware('can:isAdmin');
});

Route::get('/test-vue', function () {
    return view('test-vue');
});
