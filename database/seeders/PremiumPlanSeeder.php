<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PremiumPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\PremiumPlan::updateOrCreate(['tier_key' => 'silver'], [
            'name' => 'Silver Member',
            'min_bookings' => 3,
            'discount_percentage' => 5,
            'price' => 0,
            'benefits' => ['5% off all bookings', 'Priority support'],
            'is_active' => true,
        ]);

        \App\Models\PremiumPlan::updateOrCreate(['tier_key' => 'gold'], [
            'name' => 'Gold Member',
            'min_bookings' => 10,
            'discount_percentage' => 10,
            'price' => 0,
            'benefits' => ['10% off all bookings', 'Free upgrades', 'Late check-out'],
            'is_active' => true,
        ]);
    }
}
