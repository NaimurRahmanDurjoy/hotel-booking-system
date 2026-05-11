<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelPackage extends Model
{
    protected $fillable = [
        'title',
        'description',
        'destination',
        'price',
        'duration_days',
        'images',
        'vendor_id',
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function bookings()
    {
        return $this->hasMany(TravelBooking::class);
    }
}
