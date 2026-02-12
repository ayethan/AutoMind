<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarMade extends Model
{
    protected $fillable = [
        "name"
    ];

    public function cars() {
        return $this->hasMany("App\Car");
    }
}
