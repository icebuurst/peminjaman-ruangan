<?php
/**
 * Update Old Approved Bookings
 * 
 * Script untuk update booking lama yang sudah approved tapi belum punya confirmation_deadline
 * Jalankan sekali saja setelah implementasi fitur konfirmasi
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Booking;
use Carbon\Carbon;

echo "=== Update Old Approved Bookings ===\n\n";

// Get all approved bookings without confirmation_deadline
$oldApprovedBookings = Booking::where('status', 'approved')
    ->whereNull('confirmation_deadline')
    ->get();

if ($oldApprovedBookings->isEmpty()) {
    echo "✅ Tidak ada booking lama yang perlu di-update.\n";
    exit(0);
}

echo "Found {$oldApprovedBookings->count()} old approved booking(s).\n\n";

$updated = 0;
$skipped = 0;

foreach ($oldApprovedBookings as $booking) {
    try {
        // Check if booking date is in the past
        $bookingDate = Carbon::parse($booking->tanggal_mulai);
        
        if ($bookingDate->isPast()) {
            // If booking date already passed, mark as confirmed automatically
            $booking->status = Booking::STATUS_CONFIRMED;
            $booking->confirmed_at = $booking->updated_at; // Use last update time
            $booking->save();
            
            echo "✅ Booking #{$booking->id_booking} - CONFIRMED (tanggal sudah lewat)\n";
            $updated++;
        } else {
            // If booking date is in future, set confirmation deadline
            // Give 12 hours from now (or less if booking is soon)
            $deadline = now()->addHours(12);
            
            // If booking is less than 12 hours away, set deadline to 1 hour before booking
            if ($bookingDate->diffInHours(now()) < 12) {
                $deadline = $bookingDate->subHour();
            }
            
            $booking->confirmation_deadline = $deadline;
            $booking->save();
            
            echo "✅ Booking #{$booking->id_booking} - DEADLINE SET: {$deadline->format('Y-m-d H:i')}\n";
            $updated++;
        }
    } catch (\Exception $e) {
        echo "❌ Failed to update booking #{$booking->id_booking}: " . $e->getMessage() . "\n";
        $skipped++;
    }
}

echo "\n=== Summary ===\n";
echo "Updated: {$updated}\n";
echo "Skipped: {$skipped}\n";
echo "\nDone!\n";
