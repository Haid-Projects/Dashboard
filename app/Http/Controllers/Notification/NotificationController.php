<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    use GeneralTrait;

    /**
     * Get new notifications for the authenticated user.
     */
    public function getNotifications(Request $request)
    {
        $user = $this->getAuthenticatedUser();

        $notifications = $user->notifications()
            ->orderByRaw('read_at IS NULL DESC, created_at DESC')
            ->take(10)
            ->get();

        return $this->returnSuccessData($notifications, 'كل الاشعارات', 200);
    }

    /**
     * Mark notifications as read.
     */
    public function markAsRead(Request $request)
    {
        $request->validate([
            'notification_ids' => 'required|array',
            'notification_ids.*' => 'string|exists:notifications,id'
        ]);

        $user = $this->getAuthenticatedUser();
        DatabaseNotification::whereIn('id', $request->notification_ids)
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->update(['read_at' => now()]);

        return $this->returnSuccessMessage('تمت العملية بنجاح', 200);
    }

    /**
     * Get the authenticated user based on the guard.
     */
    private function getAuthenticatedUser()
    {
        if (Auth::guard('user')->check()) {
            return Auth::guard('user')->user();
        }

        if (Auth::guard('state_manager')->check()) {
            return Auth::guard('state_manager')->user();
        }

        if (Auth::guard('specialist')->check()) {
            return Auth::guard('specialist')->user();
        }
        if (Auth::guard('admin')->check()) {
            return Auth::guard('admin')->user();
        }

        return null;
    }
}
