<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // List unread notifications (most recent first). You can narrow by type if needed.
        $notifications = $user->unreadNotifications()->latest()->get();

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
        // Mark all unread notifications as read for this user
        $user->unreadNotifications->each->markAsRead();

        return redirect()->route('notifications.index')->with('success', 'All notifications marked as read.');
    }
}
