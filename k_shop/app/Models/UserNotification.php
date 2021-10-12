<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    //
    protected $fillable = [
        'user_id', 'notification_id', 'watched_in_menu', 'watched_detail'
    ];
}
