<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'hospital_id',
        'product_id',
        'supplier_id',
        'quantity',
        'unit_price',
        'discount',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'discount'   => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    // Prix après remise
    public function getPriceAfterDiscountAttribute(): float
    {
        return $this->unit_price * (1 - $this->discount / 100);
    }

    // Sous-total HT
    public function getSubtotalAttribute(): float
    {
        return $this->price_after_discount * $this->quantity;
    }

    // Sous-total TTC (TVA 20%)
    public function getSubtotalTtcAttribute(): float
    {
        return $this->subtotal * 1.20;
    }
}