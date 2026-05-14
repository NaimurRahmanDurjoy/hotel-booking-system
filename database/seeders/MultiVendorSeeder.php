<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\TravelPackage;
use App\Models\Car;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class MultiVendorSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks to truncate tables
        Schema::disableForeignKeyConstraints();
        Room::truncate();
        Hotel::truncate();
        TravelPackage::truncate();
        Schema::enableForeignKeyConstraints();

        // Create 5 Managers
        $managers = [];
        for ($i = 1; $i <= 5; $i++) {
            $managers[] = User::updateOrCreate(
                ['email' => "manager$i@example.com"],
                [
                    'name' => "Manager $i",
                    'password' => Hash::make('password'),
                    'role' => 'manager',
                ]
            );
        }

        // Create 5 Hotels in different cities
        $cities = ['Dhaka', 'Cox\'s Bazar', 'Sylhet', 'Chittagong', 'Rangamati'];
        $hotelNames = ['Grand Azure Dhaka', 'Ocean Breeze Resort', 'Sylhet Valley Inn', 'Port City Hotel', 'Lakeview Rangamati'];
        $hotelImages = [
            'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1445019980597-93fa8acb246c?auto=format&fit=crop&w=800&q=80'
        ];
        $hotels = [];

        foreach ($cities as $index => $city) {
            $hotels[] = Hotel::updateOrCreate(
                ['name' => $hotelNames[$index]],
                [
                    'manager_id' => $managers[$index]->id,
                    'city' => $city,
                    'address' => "$city Main Road, Sector $index",
                    'description' => "Experience premium hospitality at $hotelNames[$index] in the heart of $city. Our hotel offers world-class amenities and unparalleled comfort for all guests.",
                    'images' => [$hotelImages[$index]],
                ]
            );
        }

        // Create 20 Rooms (4 per hotel)
        $roomTypes = ['Deluxe', 'Suite', 'Standard', 'Presidential'];
        $roomImages = [
            'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=600&q=80'
        ];
        $prices = [4000, 12000, 2500, 25000];

        foreach ($hotels as $hotel) {
            foreach ($roomTypes as $i => $type) {
                Room::updateOrCreate(
                    [
                        'hotel_id' => $hotel->id,
                        'room_number' => $hotel->id . '0' . ($i + 1)
                    ],
                    [
                        'room_type' => strtolower($type),
                        'description' => "Beautiful $type room with modern amenities and a great view of $hotel->city.",
                        'price_per_night' => $prices[$i] + rand(100, 500),
                        'capacity' => ($i % 2 == 0) ? 2 : 4,
                        'status' => 'available',
                        'image' => $roomImages[$i],
                        'amenities' => ['WiFi', 'AC', 'TV', 'Coffee Maker']
                    ]
                );
            }
        }

        // Create 7 Travel Packages with Details
        $packages = [
            [
                'title' => 'Sundarbans Forest Expedition',
                'destination' => 'Sundarbans',
                'description' => 'Deep jungle safari, boat stay, and tiger tracking experience.',
                'price' => 12500,
                'duration_days' => 4,
                'transport' => 'AC Launch & Boat',
                'accommodation' => 'Forest Lodge & Boat Cabin',
                'meals' => 'Breakfast, Lunch, Dinner (Traditional)',
                'vendor_id' => $managers[0]->id,
                'images' => ['/storage/packages/sundarbans.png']
            ],
            [
                'title' => 'Sajek Valley Cloud Tour',
                'destination' => 'Sajek',
                'description' => 'Stay above the clouds, visit Kanglak Hill and enjoy the sunrise.',
                'price' => 6500,
                'duration_days' => 2,
                'transport' => 'Chander Gari (Jeep)',
                'accommodation' => 'Hillview Resort (Eco-Cottage)',
                'meals' => 'Breakfast & Dinner',
                'vendor_id' => $managers[1]->id,
                'images' => ['/storage/packages/sajek.png']
            ],
            [
                'title' => 'Cox\'s Bazar Beach Relaxation',
                'destination' => 'Cox\'s Bazar',
                'description' => 'Luxury stay at Inani beach, sunset dinner, and water sports.',
                'price' => 9000,
                'duration_days' => 3,
                'transport' => 'AC Bus (Green Line)',
                'accommodation' => '5-Star Beach Resort',
                'meals' => 'Buffet Breakfast & Seafood Dinner',
                'vendor_id' => $managers[2]->id,
                'images' => ['/storage/packages/coxs_bazar.png']
            ],
            [
                'title' => 'Sylhet Tea Garden Retreat',
                'destination' => 'Sylhet',
                'description' => 'Visit Ratargul Swamp Forest, Jaflong, and lush tea gardens.',
                'price' => 7500,
                'duration_days' => 3,
                'transport' => 'Private Car',
                'accommodation' => 'Boutique Tea Resort',
                'meals' => 'Breakfast & Traditional Sylheti Lunch',
                'vendor_id' => $managers[3]->id,
                'images' => ['/storage/packages/sylhet.png']
            ],
            [
                'title' => 'Saint Martin Island Escape',
                'destination' => 'Saint Martin',
                'description' => 'Crystal clear water, coral beach, and fresh seafood experience.',
                'price' => 11000,
                'duration_days' => 3,
                'transport' => 'Ship (Keari Sindbad)',
                'accommodation' => 'Ocean View Cottage',
                'meals' => 'Full Board Meals (All inclusive)',
                'vendor_id' => $managers[4]->id,
                'images' => ['https://images.unsplash.com/photo-1544945582-3b466d874eac?auto=format&fit=crop&w=800&q=80']
            ],
            [
                'title' => 'Rangamati Kaptai Lake Tour',
                'destination' => 'Rangamati',
                'description' => 'Boat cruise in Kaptai Lake, visit hanging bridge and waterfalls.',
                'price' => 5500,
                'duration_days' => 2,
                'transport' => 'AC Bus & Boat',
                'accommodation' => 'Lakeside Resort',
                'meals' => 'Breakfast & Bamboo Chicken Lunch',
                'vendor_id' => $managers[0]->id,
                'images' => ['https://images.unsplash.com/photo-1540979388789-6cee28a1cdc9?auto=format&fit=crop&w=800&q=80']
            ],
            [
                'title' => 'Bandarban Nilgiri Expedition',
                'destination' => 'Bandarban',
                'description' => 'Visit Nilgiri, Nilachal, and explore the tribal culture and hills.',
                'price' => 8500,
                'duration_days' => 3,
                'transport' => 'Jeep (Land Cruiser)',
                'accommodation' => 'Nilgiri Hill Resort',
                'meals' => 'Breakfast & Tribal Special Dinner',
                'vendor_id' => $managers[1]->id,
                'images' => ['https://images.unsplash.com/photo-1623517228321-72990d164506?auto=format&fit=crop&w=800&q=80']
            ]
        ];

        foreach ($packages as $pkg) {
            TravelPackage::updateOrCreate(['title' => $pkg['title']], $pkg);
        }

        // Create 5 Cars
        $cars = [
            [
                'name' => 'Toyota Noah',
                'brand' => 'Toyota',
                'model_year' => '2022',
                'type' => 'microbus',
                'transmission' => 'auto',
                'fuel_type' => 'octane',
                'price_per_day' => 4500,
                'capacity' => 7,
                'image' => 'https://images.unsplash.com/photo-1517994112540-009c47ea476b?auto=format&fit=crop&w=800&q=80',
                'description' => 'Spacious 7-seater microbus, perfect for family trips.'
            ],
            [
                'name' => 'Toyota Corolla Cross',
                'brand' => 'Toyota',
                'model_year' => '2023',
                'type' => 'suv',
                'transmission' => 'auto',
                'fuel_type' => 'octane',
                'price_per_day' => 6000,
                'capacity' => 5,
                'image' => 'https://images.unsplash.com/photo-1621135802920-133df287f89c?auto=format&fit=crop&w=800&q=80',
                'description' => 'Modern SUV with premium features and safety.'
            ],
            [
                'name' => 'Toyota Allion',
                'brand' => 'Toyota',
                'model_year' => '2021',
                'type' => 'sedan',
                'transmission' => 'auto',
                'fuel_type' => 'octane',
                'price_per_day' => 3500,
                'capacity' => 4,
                'image' => 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=800&q=80',
                'description' => 'Smooth sedan for city rides and business trips.'
            ],
            [
                'name' => 'Mercedes-Benz E-Class',
                'brand' => 'Mercedes',
                'model_year' => '2023',
                'type' => 'luxury',
                'transmission' => 'auto',
                'fuel_type' => 'octane',
                'price_per_day' => 15000,
                'capacity' => 4,
                'image' => 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?auto=format&fit=crop&w=800&q=80',
                'description' => 'Ultimate luxury and comfort for special occasions.'
            ],
            [
                'name' => 'Toyota Hiace',
                'brand' => 'Toyota',
                'model_year' => '2022',
                'type' => 'microbus',
                'transmission' => 'auto',
                'fuel_type' => 'diesel',
                'price_per_day' => 5500,
                'capacity' => 12,
                'image' => 'https://images.unsplash.com/photo-1506015391300-4802dc74de2e?auto=format&fit=crop&w=800&q=80',
                'description' => 'High-capacity van for large groups and tours.'
            ],
        ];

        foreach ($cars as $index => $car) {
            Car::updateOrCreate(
                ['name' => $car['name']],
                array_merge($car, ['manager_id' => $managers[$index % 5]->id])
            );
        }
    }
}
