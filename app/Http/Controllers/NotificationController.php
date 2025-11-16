<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = now()->toDateString();
        $notifications = $user->notifications
            ->where('type', 'App\\Notifications\\FollowUpDueNotification')
            ->filter(function ($notification) use ($today) {
                return isset($notification->data['type'])
                    && $notification->data['type'] === 'follow_up_due'
                    && $notification->created_at->toDateString() === $today
                    && $notification->read_at === null;
            });
        return view('notifications.index', compact('notifications'));
    }
}
