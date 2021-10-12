<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function getListNotifications() {
        $user_id = Auth::id();
        $listNotification = DB::table('notifications')
                            ->join('user_notifications', 'notifications.id', '=', 'user_notifications.notification_id')
                            ->select('notifications.*', 'user_notifications.watched_in_menu', 'user_notifications.watched_detail')
                            ->where('user_notifications.user_id', $user_id)
                            ->orderByDesc('id')
                            ->paginate(10);
        return response()->json($listNotification, 200);
    }

    public function getDetailNotificationById($id) {
        $notification = Notification::find($id);
        if (Auth::check()) {
            UserNotification::where('user_id', Auth::id())
                            ->where('notification_id', $id)
                            ->update([
                                'watched_detail' => true
                            ]);
        }

        return response()->json($notification, 200);
    }

    public function getNumberNewNotifications() {
        $user_id = Auth::id();
        $numberNewNotis = UserNotification::where('user_id', $user_id)
                                            ->where('watched_in_menu', false)
                                            ->count();
        return response()->json($numberNewNotis, 200);
    }

    public function watchedNewNotifications() {
        $user_id = Auth::id();
        UserNotification::where('user_id', $user_id)
                    ->where('watched_in_menu', false)
                    ->update([
                        'watched_in_menu' => true,
                    ]);
       
        return response()->json([
            'message' => 'success'
        ], 200);
    }
}
