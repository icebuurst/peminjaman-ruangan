<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class SendPendingReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:send-pending-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder notifications to admin/petugas for pending bookings every 6 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for pending bookings that need reminders...');
        
        // Get all pending bookings that haven't been reminded in the last 6 hours
        $sixHoursAgo = now()->subHours(6);
        
        $pendingBookings = Booking::where('status', 'pending')
            ->where(function($query) use ($sixHoursAgo) {
                // Either never reminded OR last reminder was more than 6 hours ago
                $query->whereNull('last_reminder_sent_at')
                      ->orWhere('last_reminder_sent_at', '<', $sixHoursAgo);
            })
            ->get();
        
        if ($pendingBookings->isEmpty()) {
            $this->info('No pending bookings need reminders at this time.');
            return 0;
        }
        
        $this->info("Found {$pendingBookings->count()} pending booking(s) that need reminders.");
        
        // Get all admin and petugas users
        $adminUsers = User::whereIn('role', ['admin', 'petugas'])->get();
        
        if ($adminUsers->isEmpty()) {
            $this->warn('No admin/petugas users found to send reminders to.');
            return 1;
        }
        
        $reminderCount = 0;
        foreach ($pendingBookings as $booking) {
            try {
                // Calculate how long the booking has been pending
                $pendingSince = $booking->created_at->diffForHumans();
                
                // Send reminder to each admin/petugas
                foreach ($adminUsers as $admin) {
                    Notification::create([
                        'user_id' => $admin->id,
                        'type' => 'booking_pending_reminder',
                        'title' => 'Reminder: Peminjaman Pending',
                        'message' => 'Peminjaman ruangan ' . $booking->room->nama_room . ' oleh ' . $booking->user->name . ' masih menunggu persetujuan (pending sejak ' . $pendingSince . ').',
                        'icon' => 'bi-bell-fill',
                        'link' => route('bookings.show', $booking->id_booking),
                        'is_read' => false
                    ]);
                }
                
                // Update last reminder timestamp
                $booking->last_reminder_sent_at = now();
                $booking->save();
                
                $reminderCount++;
                $this->line("Reminder sent: Booking #{$booking->id_booking} - {$booking->room->nama_room} (Pending {$pendingSince})");
            } catch (\Exception $e) {
                $this->error("Failed to send reminder for booking #{$booking->id_booking}: " . $e->getMessage());
            }
        }
        
        $this->info("Successfully sent reminders for {$reminderCount} pending booking(s) to {$adminUsers->count()} admin/petugas user(s).");
        return 0;
    }
}
