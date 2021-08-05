<?php

namespace App\Http\Controllers\Admin;

use App\Events\GeneralNotification;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use App\User;

class AdminNotificationController extends Controller
{
    //
    public function createNewNotification(Request $request) {
        if ($request->notiType == "all") {
            $newNoti = Notification::create([
                'content' => $request->notificationContent,
                'type' => 'all user',
            ]);

            $allUserId = User::pluck('id');
            foreach($allUserId as $user_id) {
                UserNotification::create([
                    'user_id' => $user_id,
                    'notification_id' => $newNoti->id,
                ]);
            }
            broadcast(new GeneralNotification($newNoti));
        }
        return redirect()->route('adminPage');
    }
}