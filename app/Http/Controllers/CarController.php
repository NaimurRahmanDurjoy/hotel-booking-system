<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\CarBooking;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::query()->where('status', 'available');

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('capacity')) {
            $query->where('capacity', '>=', $request->capacity);
        }

        $cars = $query->paginate(9);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($cars);
        }

        return view('manager.cars.index', compact('cars'));
    }

    public function show(Car $car)
    {
        return response()->json($car);
    }

    public function storeBooking(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'pickup_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after:pickup_date',
            'pickup_city' => 'required|string',
            'dropoff_city' => 'required|string',
            'pickup_location' => 'required|string',
            'return_location' => 'required|string',
        ]);

        $car = Car::findOrFail($request->car_id);
        
        // Calculate days
        $pickup = new \DateTime($request->pickup_date);
        $return = new \DateTime($request->return_date);
        $days = $pickup->diff($return)->days;
        if ($days == 0) $days = 1;

        $base_total = $days * $car->price_per_day;
        
        // Inter-city surcharge (example: 2000 TK if cities are different)
        $surcharge = 0;
        if ($request->pickup_city !== $request->dropoff_city) {
            $surcharge = 2000;
        }

        $total_price = $base_total + $surcharge;

        $booking = CarBooking::create([
            'user_id' => auth()->id(),
            'car_id' => $request->car_id,
            'pickup_city' => $request->pickup_city,
            'dropoff_city' => $request->dropoff_city,
            'pickup_date' => $request->pickup_date,
            'return_date' => $request->return_date,
            'pickup_location' => $request->pickup_location,
            'return_location' => $request->return_location,
            'total_price' => $total_price,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Car booking request sent successfully', 
            'booking' => $booking,
            'base_price' => $base_total,
            'surcharge' => $surcharge,
            'total' => $total_price
        ], 201);
    }
}
