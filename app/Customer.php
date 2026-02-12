<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'remark'
    ];

    public function cars() {
        return $this->hasMany("App\Car");
    }
    
}
