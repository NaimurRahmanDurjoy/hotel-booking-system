<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


/**
 * User Model with Sanctum support
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'is_premium',
        'premium_tier',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_premium' => 'boolean',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function messagesSent()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function messagesReceived()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function premiumSubscription()
    {
        return $this->hasOne(PremiumSubscription::class)->where('is_active', true);
    }

    public function hotels()
    {
        return $this->hasMany(Hotel::class, 'manager_id');
    }

    public function travelPackages()
    {
        return $this->hasMany(TravelPackage::class, 'vendor_id');
    }

    public function travelBookings()
    {
        return $this->hasMany(TravelBooking::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function getDiscountPercentage()
    {
        if (!$this->is_premium || !$this->premium_tier) {
            return 0;
        }

        return $this->premium_tier === 'gold' ? 10 : 5;
    }
}
