<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'expense_type_id',
        'amount',
        'remark',
        'date'
    ];

    function user() {
        return $this->belongsTo('App\User')->withDefault([
            'name' => 'Anonymous'
        ]);
    }

    function expense_type() {
        return $this->belongsTo('App\ExpenseType');
    }

    /**
     * Set Date Attribute Mutator
     */
    public function setDateAttribute($value) {
        $this->attributes["date"] = date('Y-m-d', strtotime($value));
    }

    public function sale() {
        $this->belongsTo("App\Expense")->without('expenses')->withDefault();
    }
}
