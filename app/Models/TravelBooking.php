<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelBooking extends Model
{
    protected $fillable = [
        'user_id',
        'travel_package_id',
        'travel_date',
        'guests',
        'total_price',
        'status',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'total_price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(TravelPackage::class, 'travel_package_id');
    }
}
