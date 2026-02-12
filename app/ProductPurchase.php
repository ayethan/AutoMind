<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPurchase extends Model
{

    protected $fillable = [
        'product_id',
        'qty',
        'buy_price',
        'sell_price'
    ];

    public function product() {
        return $this->belongsTo('App\Product');
    }

    public function created_by() {
        return $this->belongsTo('App\User')->withDefault();
    }
}
