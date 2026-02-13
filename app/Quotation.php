<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'user_id',
        'quotation_date',
        'expiration_date',
        'status',
        'title',
        'notes',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'converted_sale_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'quotation_date' => 'datetime',
        'expiration_date' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the customer that owns the quotation.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the user that created the quotation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The products that belong to the quotation.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'quotation_products')
                    ->withPivot('quantity', 'price', 'discount', 'total')
                    ->withTimestamps();
    }

    /**
     * The services that belong to the quotation.
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'quotation_services')
                    ->withPivot('quantity', 'price', 'discount', 'total')
                    ->withTimestamps();
    }

    /**
     * Get the sale that the quotation was converted into.
     */
    public function convertedSale()
    {
        return $this->belongsTo(Sale::class, 'converted_sale_id');
    }
}
