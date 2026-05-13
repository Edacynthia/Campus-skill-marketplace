<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$notification->is_read) {
            $notification->markAsRead();
        }

        return back()->with('success', 'Notification marked as read');
    }

    public function markAllAsRead()
    {
        Auth::user()->markNotificationsAsRead();

        return back()->with('success', 'All notifications marked as read');
    }

    public function getUnreadCount()
    {
        return response()->json([
            'count' => Auth::user()->unreadNotificationCount()
        ]);
    }

    public function getRecentNotifications()
    {
        $notifications = Auth::user()
            ->notifications()
            ->unread()
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'notifications' => $notifications
        ]);
    }

    public function open($id)
{
    $notification = Notification::where('id', $id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    if (!$notification->is_read) {
        $notification->markAsRead();
    }

    return redirect($notification->url ?: route('notifications.index'));
}
}