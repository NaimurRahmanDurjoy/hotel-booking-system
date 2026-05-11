<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'manager_id', 'name', 'brand', 'model_year', 'type', 'base_city',
        'transmission', 'fuel_type', 'price_per_day', 
        'capacity', 'description', 'image', 'status'
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function bookings()
    {
        return $this->hasMany(CarBooking::class);
    }
}
