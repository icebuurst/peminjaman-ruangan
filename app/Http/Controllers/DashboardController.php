<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
use App\Models\JadwalReguler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $data = [
            'user' => $user,
            'totalRooms' => Room::count(),
            'totalBookings' => Booking::count(),
            'totalUsers' => User::where('role', 'peminjam')->count(),
        ];

        if ($user->role === 'peminjam') {
            // Dashboard for peminjam
            $data['myBookings'] = Booking::with('room')
                ->where('id_user', $user->id)
                ->orderBy('tanggal_mulai', 'desc')
                ->limit(5)
                ->get();
            
            $data['pendingCount'] = Booking::where('id_user', $user->id)
                ->where('status', 'pending')
                ->count();
                
            $data['approvedCount'] = Booking::where('id_user', $user->id)
                ->where('status', 'approved')
                ->count();
                
            $data['rejectedCount'] = Booking::where('id_user', $user->id)
                ->where('status', 'rejected')
                ->count();
                
        } else {
            // Dashboard for admin/petugas
            $data['recentBookings'] = Booking::with(['room', 'user'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
                
            $data['pendingBookings'] = Booking::with(['room', 'user'])
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();
                
            $data['todayBookings'] = Booking::with(['room', 'user'])
                ->whereDate('tanggal_mulai', today())
                ->get();
            
            // Chart data - Booking trends last 7 days
            $data['chartDates'] = [];
            $data['chartCounts'] = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $data['chartDates'][] = $date->format('d M');
                $data['chartCounts'][] = Booking::whereDate('created_at', $date)->count();
            }
            
            // Status distribution
            $data['pendingCount'] = Booking::where('status', 'pending')->count();
            $data['statusPending'] = $data['pendingCount']; // Keep for compatibility
            $data['statusApproved'] = Booking::where('status', 'approved')->count();
            $data['statusRejected'] = Booking::where('status', 'rejected')->count();
            
            // Room usage
            $data['roomStats'] = Room::withCount('bookings')
                ->orderBy('bookings_count', 'desc')
                ->limit(5)
                ->get();
        }

        return view('dashboard', $data);
    }
}
