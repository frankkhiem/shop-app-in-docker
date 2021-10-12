<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'id', 'category_id', 'name', 'image', 'thumbnail', 'short_desc', 'full_desc', 'price', 'status_product_id', 'star',
    ];
    public function status_product()
    {
        return $this->belongsTo('App\Models\StatusProduct', 'status_product_id');
    }
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
    public function info_products() {
        return $this->hasMany('App\Models\InfoProduct');
    }
    public function orders() {
        return $this->hasMany('App\Models\Order');
    }
    public function comments() {
        return $this->hasMany('App\Models\Comment');
    }
}
