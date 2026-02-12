<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'code',
        'name',
        'stock',
        'buy_price',
        'sell_price',
        'remark',
        'category_id',
        'sub_category_id'
    ];

    public function category() {
        return $this->belongsTo("App\Category")->withDefault([
            "name" => "Other"
        ]);
    }

    public function sub_category() {
        return $this->belongsTo("App\SubCategory")->withDefault([
            "name" => "Other"
        ]);
    }

    public function product_purchases() {
        return $this->hasMany('App\ProductPurchase');
    }

    public function supplier() {
        return $this->belongsTo("App\Supplier")->withDefault([
            "name" => "Unknown"
        ]);
    }

    public function scopeDeleted($query) {
        return $query->whereNotNull("deleted_at");
    }
}
