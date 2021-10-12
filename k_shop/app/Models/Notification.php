<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'content', 'type',
    ];

    public function user_notifications() {
        return $this->hasMany('App\Models\UserNotification');
    }
}
