<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'car_id', 'pickup_city', 'dropoff_city', 
        'pickup_date', 'return_date', 
        'pickup_location', 'return_location', 'total_price', 
        'status', 'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
