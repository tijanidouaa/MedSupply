<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    protected $fillable = [
        'name',
        'category',
        'quantity',
        'min_quantity',
        'price',
        'hospital_id',
        'product_id',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Stock critique ?
    public function isCritical(): bool
    {
        return $this->quantity > 0 && $this->quantity <= $this->min_quantity;
    }

    // Rupture totale ?
    public function isOutOfStock(): bool
    {
        return $this->quantity === 0;
    }
}