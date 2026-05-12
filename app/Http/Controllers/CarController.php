<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\CarBooking;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        if ($request->wantsJson() || $request->ajax()) {
            $query = Car::query();
            if ($request->has('type')) {
                $query->where('type', $request->type);
            }
            if ($request->has('capacity')) {
                $query->where('capacity', '>=', $request->capacity);
            }
            if ($request->has('status')) {
                $query->where('status', $request->status);
            } else {
                $query->where('status', 'available');
            }
            return response()->json($query->get());
        }

        if ($user && $user->isAdmin()) {
            $cars = Car::paginate(10);
        } else {
            $cars = Car::where('manager_id', $user->id)->paginate(10);
        }

        return view('manager.cars.index', compact('cars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string',
            'model_year' => 'required|string',
            'type' => 'required|string',
            'transmission' => 'required|string',
            'fuel_type' => 'required|string',
            'price_per_day' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        $data['manager_id'] = auth()->id();
        $data['status'] = 'available';

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('cars', 'public');
            $data['image'] = '/storage/' . $path;
        }

        $car = Car::create($data);

        return redirect()->back()->with('success', 'Car added successfully');
    }

    public function update(Request $request, Car $car)
    {
        if (auth()->id() !== $car->manager_id && !auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'brand' => 'sometimes|string',
            'price_per_day' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:available,booked,maintenance',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('cars', 'public');
            $data['image'] = '/storage/' . $path;
        }

        $car->update($data);

        return redirect()->back()->with('success', 'Car updated successfully');
    }

    public function destroy(Car $car)
    {
        if (auth()->id() !== $car->manager_id && !auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $car->delete();
        return response()->json(['message' => 'Car deleted successfully']);
    }

    public function show(Car $car)
    {
        return response()->json($car);
    }

    public function bookingsIndex()
    {
        $user = auth()->user();
        if ($user->isAdmin()) {
            $bookings = CarBooking::with(['user', 'car'])->latest()->paginate(15);
        } else {
            $bookings = CarBooking::whereHas('car', fn($q) => $q->where('manager_id', $user->id))
                ->with(['user', 'car'])
                ->latest()
                ->paginate(15);
        }
        return view('manager.cars.bookings', compact('bookings'));
    }

    public function updateBooking(Request $request, CarBooking $carBooking)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,completed,cancelled']);
        $carBooking->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Car booking status updated');
    }

    public function storeBooking(Request $request)
    {
        // (existing storeBooking logic...)
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
        
        $pickup = new \DateTime($request->pickup_date);
        $return = new \DateTime($request->return_date);
        $days = $pickup->diff($return)->days;
        if ($days == 0) $days = 1;

        $base_total = $days * $car->price_per_day;
        
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
