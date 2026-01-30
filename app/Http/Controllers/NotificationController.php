<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

/**
 * NotificationController
 *
 * Manages user notifications.
 * Allows viewing, filtering, and marking notifications as read.
 */
class NotificationController extends Controller
{
    /**
     * Display a listing of notifications.
     *
     * Supports filtering by:
     * - Status: 'read', 'unread'
     * - Search: Notification content
     * - Date Range: Creation date
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $query = auth()->user()->notifications();

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('data', 'like', "%{$search}%");
            });
        }

        if (request()->filled('status')) {
            if (request('status') === 'unread') {
                $query->whereNull('read_at');
            } elseif (request('status') === 'read') {
                $query->whereNotNull('read_at');
            }
        }

        if (request()->filled('from')) {
            $query->whereDate('created_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('created_at', '<=', request('to'));
        }

        $notifications = $query->latest()->paginate(20)->withQueryString();
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a specific notification as read.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read for the current user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Remove the specified notification.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();
        return back()->with('success', 'Notification removed.');
    }
}
