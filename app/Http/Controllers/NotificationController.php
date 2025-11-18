<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get user notifications
     */
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->recent(20)
            ->get();
            
        return response()->json($notifications);
    }
    
    /**
     * Get unread count
     */
    public function unreadCount()
    {
        $count = Auth::user()->unreadNotificationsCount();
        return response()->json(['count' => $count]);
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
            
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Mark all as read
     */
    public function markAllAsRead()
    {
        Auth::user()
            ->notifications()
            ->unread()
            ->update(['is_read' => true]);
            
        return response()->json(['success' => true]);
    }
}
