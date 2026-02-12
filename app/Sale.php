<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Utils\Helpers;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'discount',
        'remark',
        'car_id',
        'is_taxi',
        'customer_id',
        'job_done_by',
        'model',
        'mileage',
        'advance',
        'date'
    ];

    /**
     * Appends the custom accessors 
     * Ref: https://laravel.com/docs/5.7/eloquent-serialization
     */
    protected $appends = [
        'sub_total',
        'total',
        'tax'
    ];

    /**
     * The soft delete column
     */
    protected $dates = ['deleted_at'];

    /**
     * The relationship to App\SaleProduct
     */
    public function sale_products() {
        return $this->hasMany('App\SaleProduct');
    } 


    /**
     * The relationship to App\SaleService
     */
    public function sale_services() {
        return $this->hasMany('App\SaleService');
    }

    /**
     * The relationship to App\SaleExternalProduct
     */
    public function sale_external_products() {
        return $this->hasMany('App\SaleExternalProduct');
    }


    /**
     * The relationship to App\User
     */
    public function user() {
        return $this->belongsTo('App\User')->withDefault([
            'name' => 'Anonymous'
        ]);
    }

    /**
     * The relationship to App\Customer
     */
    public function customer() {
        return $this->belongsTo('App\Customer')->withDefault([
            'name' => 'Guest'
        ]);
    }
        
    /**
     * The many to many relation ship to the expenses
     */
    public function expenses() {
        return $this->belongsToMany('App\Expense', 'sale_expenses', 'sale_id', 'expense_id')->without('sale');
    }

    public function car() {
        return $this->belongsTo('App\Car')->withDefault([
            "car_no" => "Unknown",
            "model" => "Unknown"
        ]);
    }

    /**
     * The one to many relation ship to the payments
     */
    public function payments() {
        return $this->hasMany('App\SalePayment')->without('sale');
    }


    /**
     * Set Date Attribute Mutator
     */
    public function setDateAttribute($value) {
        $this->attributes["date"] = date('Y-m-d', strtotime($value));
    }

    /**
     * The custom computed accessor for the sub total 
     */
    public function getSubTotalAttribute() {
        return ($this->sale_products->sum('total') + 
        $this->sale_services->sum('total') + 
        $this->sale_external_products->sum('total'));
    }

    /**
     * The custom computed accessor for the sub total 
     */
    public function getTotalAttribute() {
        return ($this->sub_total - $this->discount + $this->tax);
    }

    /**
     * The computed attribute for the tax of the sale
     */
    public function getTaxAttribute() {
        return ($this->sub_total * (Helpers::getValue('tax') * 0.01));
    }

    public function getTotalPaymentAttribute() {
        return ($this->payments->sum('amount'));
    }

}
