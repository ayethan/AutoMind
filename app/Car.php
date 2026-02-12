<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        "car_no",
        "model",
        "car_made_id",
        "customer_id"
    ];

    public function customer() {
        return $this->belongsTo("App\Customer")->withDefault([
            "name" => "Guest"
        ]);
    }

    public function car_made() {
        return $this->belongsTo("App\CarMade")->withDefault([
            "name" => "Unknown Made"
        ]);
    }

    public function setCarNoAttribute($value) {
        $this->attributes["car_no"] = strtoupper($value);
    }
}
