<?php

namespace App\Http\Controllers;

use App\Models\TravelBooking;
use App\Models\TravelPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TravelBookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            $bookings = TravelBooking::with(['user', 'package'])->latest()->paginate(15);
        } elseif ($user->role === 'manager') {
            $bookings = TravelBooking::whereHas('package', function($q) use ($user) {
                $q->where('vendor_id', $user->id);
            })->with(['user', 'package'])->latest()->paginate(15);
        } else {
            $bookings = TravelBooking::where('user_id', $user->id)->with('package')->latest()->paginate(15);
        }

        return response()->json($bookings);
    }

    public function store(Request $request)
    {
        $request->validate([
            'travel_package_id' => 'required|exists:travel_packages,id',
            'travel_date' => 'required|date|after:today',
            'guests' => 'required|integer|min:1',
        ]);

        $package = TravelPackage::findOrFail($request->travel_package_id);
        $total_price = $package->price * $request->guests;

        $booking = TravelBooking::create([
            'user_id' => Auth::id(),
            'travel_package_id' => $request->travel_package_id,
            'travel_date' => $request->travel_date,
            'guests' => $request->guests,
            'total_price' => $total_price,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Travel package booked successfully', 'booking' => $booking], 201);
    }

    public function update(Request $request, TravelBooking $travelBooking)
    {
        $user = Auth::user();
        
        // Only vendor or admin can update status
        if ($user->isAdmin() || ($user->role === 'manager' && $travelBooking->package->vendor_id === $user->id)) {
            $request->validate([
                'status' => 'required|in:pending,confirmed,cancelled,completed',
            ]);
            $travelBooking->update(['status' => $request->status]);
            return response()->json(['message' => 'Booking status updated', 'booking' => $travelBooking]);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
