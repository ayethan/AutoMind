<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class SaleProduct extends Model
{
    protected $fillable = [
        'buy_price',
        'sell_price',
        'qty',
        'product_id',
        'sale_id',
        'name',
        "remark"
    ];

    /**
     * Appends the custom accessors 
     * Ref: https://laravel.com/docs/5.7/eloquent-serialization
     */
    protected $appends = [
        'total'
    ];

    /**
     * The relationship to App\Product
     */
    public function product() {
        return $this->belongsTo('App\Product')->withDefault([
            "name" => "Deleted Product"
        ]);
    }

    /**
     * The relationship to App\Sale
     */
    public function sale() {
        return $this->belongsTo('App\Sale');
    }

    /**
     * The custom accessor to calcualte the total amount
     */
    public function getTotalAttribute() {
        return ($this->sell_price) * ($this->qty);
    }
}
