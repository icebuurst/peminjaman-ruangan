<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\User;

class CheckExpiredBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and expire bookings that haven\'t been confirmed within 12 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired booking confirmations...');
        
        // Get all approved bookings that need confirmation and are past deadline
        $expiredBookings = Booking::where('status', 'approved')
            ->whereNull('confirmed_at')
            ->whereNotNull('confirmation_deadline')
            ->where('confirmation_deadline', '<', now())
            ->get();
        
        if ($expiredBookings->isEmpty()) {
            $this->info('No expired bookings found.');
            return 0;
        }
        
        $count = 0;
        foreach ($expiredBookings as $booking) {
            try {
                $booking->status = Booking::STATUS_EXPIRED;
                $booking->save();
                
                // Notify the user
                Notification::create([
                    'user_id' => $booking->id_user,
                    'type' => 'booking_expired',
                    'title' => 'Peminjaman Kadaluarsa',
                    'message' => 'Peminjaman ruangan ' . $booking->room->nama_room . ' telah kadaluarsa karena tidak dikonfirmasi dalam 12 jam.',
                    'icon' => 'bi-clock-history',
                    'link' => route('bookings.show', $booking->id_booking),
                    'is_read' => false
                ]);
                
                // Notify admin/petugas
                $adminUsers = User::whereIn('role', ['admin', 'petugas'])->get();
                foreach ($adminUsers as $admin) {
                    Notification::create([
                        'user_id' => $admin->id,
                        'type' => 'booking_expired_admin',
                        'title' => 'Peminjaman Kadaluarsa',
                        'message' => 'Peminjaman ruangan ' . $booking->room->nama_room . ' oleh ' . $booking->user->name . ' telah kadaluarsa (tidak dikonfirmasi).',
                        'icon' => 'bi-clock-history',
                        'link' => route('bookings.show', $booking->id_booking),
                        'is_read' => false
                    ]);
                }
                
                $count++;
                $this->line("Expired: Booking #{$booking->id_booking} - {$booking->room->nama_room}");
            } catch (\Exception $e) {
                $this->error("Failed to expire booking #{$booking->id_booking}: " . $e->getMessage());
            }
        }
        
        $this->info("Successfully expired {$count} booking(s).");
        return 0;
    }
}
