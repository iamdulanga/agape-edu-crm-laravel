<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get all unread follow-up due notifications from today
        $notifications = $user->unreadNotifications()
            ->where('type', 'App\\Notifications\\FollowUpDueNotification')
            ->whereDate('created_at', today())
            ->where('data->type', 'follow_up_due')
            ->get();
        
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->markAsRead();
        }
        
        // Redirect to lead profile if lead_id exists
        if ($notification && isset($notification->data['lead_id'])) {
            return redirect()->route('leads.show', $notification->data['lead_id']);
        }
        
        return redirect()->route('notifications.index');
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications()
            ->where('type', 'App\\Notifications\\FollowUpDueNotification')
            ->whereDate('created_at', today())
            ->update(['read_at' => now()]);
        
        return redirect()->route('notifications.index')->with('success', 'All notifications marked as read.');
    }
}
