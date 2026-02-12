<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Service;

class SaleService extends Model
{
    protected $fillable = [
        'price',
        'qty',
        'service_id',
        'sale_id',
        'remark'
    ];

    public function sale() {
        return $this->belongsTo('App\Sale');
    }

    public function service() {
        return $this->belongsTo('App\Service')->withDefault();
    }

    public function getTotalAttribute() {
        return ($this->price) * ($this->qty);
    }
}
