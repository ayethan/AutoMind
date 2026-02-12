<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleExternalProduct extends Model
{
    protected $fillable = [
        "name",
        "buy_price",
        "sell_price",
        "remark"
    ];


    public function getTotalAttribute() {
        return $this->qty * $this->sell_price;
    }
}
