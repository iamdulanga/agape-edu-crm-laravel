<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $allowedTypes = [
            'lead_created',
            'lead_updated',
            'lead_deleted',
            'follow_up_due',
            'follow_up_due_soon',
        ];

        $type = request('type');
        $group = (bool) request('group');

        $query = $user->unreadNotifications()->latest();

        if ($type && in_array($type, $allowedTypes, true)) {
            $query->where('data->type', $type);
        }

        $notifications = $query->get();

        // Optionally group in controller for convenience
        $grouped = $group ? $notifications->groupBy(function ($n) {
            return $n->data['type'] ?? 'other';
        }) : collect();

        return view('notifications.index', [
            'notifications' => $notifications,
            'grouped' => $grouped,
            'group' => $group,
            'type' => $type,
            'types' => $allowedTypes,
        ]);
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
