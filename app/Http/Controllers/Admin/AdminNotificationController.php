<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminNotificationController extends Controller
{
    public function notifications(Request $request){
        $user = Auth::guard('admin')->user();

        $notifications = $user->notifications()
            ->orderByRaw('read_at IS NULL DESC, created_at DESC')
            ->take(10)
            ->get();
//        return $notifications;
        return view('dashboard.notifications', ['notifications' => $notifications]);
    }
}
