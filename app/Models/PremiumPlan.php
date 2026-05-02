<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PremiumPlan extends Model
{
    protected $fillable = [
        'name', 
        'tier_key', 
        'min_bookings',
        'discount_percentage', 
        'price', 
        'benefits', 
        'is_active'
    ];

    protected $casts = [
        'benefits' => 'array',
        'is_active' => 'boolean',
    ];
}
