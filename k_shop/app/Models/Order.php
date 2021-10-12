<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = [
        'user_id', 'product_id', 'quantity', 'sale_price', 'phone_order', 'address_order', 'status_order_id', 'shipped_date', 'completed_date',
    ];

    public function status_order()
    {
        return $this->belongsTo('App\Models\StatusOrder', 'status_order_id');
    }
    public function user() {
        return $this->belongsTo('App\User', 'user_id')->withTrashed();
    }
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id')->withTrashed();
    }
}
