<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $fillable = [
        "name",
        "category_id"
    ];

    public function category() {
        return $this->belongsTo("App\Category")->withDefault([
            "name" => "Unspecified"
        ]);
    }

    public function products() {
        return $this->hasMany("App\Product");
    }
}
