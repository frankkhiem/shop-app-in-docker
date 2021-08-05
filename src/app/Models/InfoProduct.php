<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfoProduct extends Model
{
    //
    protected $fillable = [
        'product_id', 'attribute', 'information',
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
}
