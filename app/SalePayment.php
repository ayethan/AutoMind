<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalePayment extends Model
{
    protected $fillable = [
        'sale_id',
        'date',
        'amount',
        'remark'
    ];

    public function sale() {
        return $this->belongsTo('App\Sale');
    }
}
