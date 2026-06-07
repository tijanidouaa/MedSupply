<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Hospital extends Model
{
    use Auditable;

    protected $fillable = [
        'name',
        'address',
        'city',
        'phone',
        'contact_email',
        'is_active',
        'logo_path',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function stockItems()
    {
        return $this->hasMany(StockItem::class);
    }
}