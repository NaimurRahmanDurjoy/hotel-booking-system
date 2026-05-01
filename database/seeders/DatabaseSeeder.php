<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Room;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@hotel.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '1234567890',
            'address' => 'Admin Address',
        ]);

        // Create Manager
        User::create([
            'name' => 'Manager User',
            'email' => 'manager@hotel.com',
            'password' => Hash::make('password123'),
            'role' => 'manager',
            'phone' => '1234567891',
            'address' => 'Manager Address',
        ]);

        // Create sample customers
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'phone' => '1234567892',
            'address' => 'Customer Address',
        ]);

        // Create Rooms
        $rooms = [
            [
                'room_number' => '101',
                'room_type' => 'standard',
                'description' => 'Comfortable standard room with all basic amenities. Perfect for solo travelers or couples.',
                'price_per_night' => 100.00,
                'capacity' => 2,
                'amenities' => json_encode(['WiFi', 'TV', 'Air Conditioning', 'Private Bathroom']),
                'status' => 'available',
            ],
            [
                'room_number' => '102',
                'room_type' => 'standard',
                'description' => 'Spacious standard room with city view. Features modern furnishings and comfortable bedding.',
                'price_per_night' => 120.00,
                'capacity' => 2,
                'amenities' => json_encode(['WiFi', 'TV', 'Air Conditioning', 'Private Bathroom', 'City View']),
                'status' => 'available',
            ],
            [
                'room_number' => '201',
                'room_type' => 'deluxe',
                'description' => 'Elegant deluxe room with premium amenities. Features a king-size bed and separate seating area.',
                'price_per_night' => 200.00,
                'capacity' => 2,
                'amenities' => json_encode(['WiFi', 'Smart TV', 'Air Conditioning', 'Mini Bar', 'Safe', 'City View']),
                'status' => 'available',
            ],
            [
                'room_number' => '202',
                'room_type' => 'deluxe',
                'description' => 'Luxurious deluxe room with ocean view. Includes balcony and premium bathroom amenities.',
                'price_per_night' => 250.00,
                'capacity' => 3,
                'amenities' => json_encode(['WiFi', 'Smart TV', 'Air Conditioning', 'Mini Bar', 'Safe', 'Ocean View', 'Balcony']),
                'status' => 'available',
            ],
            [
                'room_number' => '301',
                'room_type' => 'suite',
                'description' => 'Stunning suite with separate living room and bedroom. Panoramic views and premium services.',
                'price_per_night' => 400.00,
                'capacity' => 4,
                'amenities' => json_encode(['WiFi', 'Smart TV', 'Air Conditioning', 'Mini Bar', 'Safe', 'Ocean View', 'Balcony', 'Living Room', 'Jacuzzi']),
                'status' => 'available',
            ],
            [
                'room_number' => '401',
                'room_type' => 'presidential',
                'description' => 'Ultimate luxury presidential suite. Features multiple rooms, private terrace, and butler service.',
                'price_per_night' => 1000.00,
                'capacity' => 6,
                'amenities' => json_encode(['WiFi', 'Smart TV', 'Air Conditioning', 'Mini Bar', 'Safe', 'Ocean View', 'Private Terrace', 'Living Room', 'Dining Room', 'Jacuzzi', 'Butler Service']),
                'status' => 'available',
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }

        // Create Services
        $services = [
            [
                'name' => 'Spa & Wellness',
                'description' => 'Relax and rejuvenate with our professional spa treatments. Includes massage, facial, and body treatments.',
                'price' => 80.00,
                'is_available' => true,
            ],
            [
                'name' => 'Restaurant',
                'description' => 'Fine dining experience with international cuisine. Our chefs prepare delicious meals using fresh ingredients.',
                'price' => 50.00,
                'is_available' => true,
            ],
            [
                'name' => 'Gym & Fitness',
                'description' => 'State-of-the-art fitness center with modern equipment. Personal trainers available on request.',
                'price' => 30.00,
                'is_available' => true,
            ],
            [
                'name' => 'Room Service',
                'description' => '24/7 room service for all your dining needs. Enjoy delicious meals in the comfort of your room.',
                'price' => 20.00,
                'is_available' => true,
            ],
            [
                'name' => 'Airport Transfer',
                'description' => 'Comfortable airport transfer service. Our professional drivers will pick you up in a luxury vehicle.',
                'price' => 50.00,
                'is_available' => true,
            ],
            [
                'name' => 'Laundry Service',
                'description' => 'Professional laundry and dry cleaning services. Quick turnaround available.',
                'price' => 25.00,
                'is_available' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
