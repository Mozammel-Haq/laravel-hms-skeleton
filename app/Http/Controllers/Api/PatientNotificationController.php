<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * PatientNotificationController
 *
 * Handles API requests for patient notifications.
 * Allows retrieving, marking as read, and deleting notifications.
 */
class PatientNotificationController extends Controller
{
    /**
     * Display a listing of notifications for the authenticated patient.
     * Returns the latest 20 notifications and the count of unread ones.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Get all notifications, unread first
        $notifications = $user->notifications()
            ->limit(20)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'data' => $notification->data,
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $user->unreadNotifications()->count()
        ]);
    }

    /**
     * Mark a notification as read.
     * Can mark a specific notification or all notifications.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id  Notification ID or 'all'
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request, $id)
    {
        $user = $request->user();

        if ($id === 'all') {
            $user->unreadNotifications->markAsRead();
            return response()->json(['message' => 'All notifications marked as read']);
        }

        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['message' => 'Notification marked as read']);
    }

    /**
     * Delete a notification.
     * Can delete a specific notification or all notifications.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id  Notification ID or 'all'
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        if ($id === 'all') {
            $user->notifications()->delete();
            return response()->json(['message' => 'All notifications deleted']);
        }

        $notification = $user->notifications()->findOrFail($id);
        $notification->delete();

        return response()->json(['message' => 'Notification deleted']);
    }
}
