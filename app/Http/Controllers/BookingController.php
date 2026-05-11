<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $bookings = Booking::with(['user', 'room', 'services', 'hotel'])->get();
        } elseif ($user->isManager()) {
            $bookings = Booking::whereHas('hotel', function($q) use ($user) {
                $q->where('manager_id', $user->id);
            })->with(['user', 'room', 'services', 'hotel'])->get();
        } else {
            $bookings = Booking::with(['room', 'services', 'hotel'])
                ->where('user_id', $user->id)
                ->get();
        }
        
        return response()->json($bookings);
    }

    public function myBookings()
    {
        $user = Auth::user();
        $bookings = Booking::with(['room', 'services', 'hotel'])
            ->where('user_id', $user->id)
            ->get();

        return response()->json($bookings);
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'service_ids' => 'nullable|array',
            'service_ids.*' => 'exists:services,id',
            'notes' => 'nullable|string',
        ]);

        $room = Room::with('hotel')->findOrFail($request->room_id);
        
        // Check room availability
        $checkIn = \Carbon\Carbon::parse($request->check_in_date);
        $checkOut = \Carbon\Carbon::parse($request->check_out_date);
        $nights = $checkIn->diffInDays($checkOut);
        
        // Check for overlapping bookings
        $overlapping = Booking::where('room_id', $request->room_id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where('check_in_date', '<', $checkOut)
                      ->where('check_out_date', '>', $checkIn);
            })->exists();

        if ($overlapping) {
            return response()->json(['message' => 'Room is not available for selected dates'], 422);
        }

        $user = Auth::user();
        $subtotal = $room->price_per_night * $nights;
        
        // Apply premium discount (if model has getDiscountPercentage)
        $discount = 0;
        if ($user->is_premium) {
            $discountPercentage = 0;
            $subscription = \App\Models\PremiumSubscription::where('user_id', $user->id)->where('is_active', true)->first();
            if ($subscription) {
                $plan = \App\Models\PremiumPlan::where('tier_key', $subscription->tier)->first();
                $discountPercentage = $plan ? $plan->discount_percentage : 0;
            }
            $discount = $subtotal * ($discountPercentage / 100);
        }

        $totalPrice = $subtotal - $discount;

        $booking = Booking::create([
            'user_id' => $user->id,
            'room_id' => $request->room_id,
            'hotel_id' => $room->hotel_id,
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'total_price' => $totalPrice,
            'discount_applied' => $discount,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        // Attach services if any
        if ($request->has('service_ids')) {
            foreach ($request->service_ids as $serviceId) {
                $service = \App\Models\Service::find($serviceId);
                if ($service) {
                    $booking->services()->attach($serviceId, [
                        'quantity' => 1,
                        'price' => $service->price,
                    ]);
                }
            }
        }

        return response()->json([
            'message' => 'Booking created successfully',
            'booking' => $booking->load(['room', 'services']),
        ], 201);
    }

    public function show(Booking $booking)
    {
        $user = Auth::user();
        
        // Check authorization
        if (!$user->isAdmin() && !$user->isManager() && $booking->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($booking->load(['user', 'room', 'services']));
    }

    public function update(Request $request, Booking $booking)
    {
        $user = Auth::user();
        
        // Only manager/admin can update booking status
        if (!$user->isManager() && !$user->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'sometimes|in:pending,confirmed,rejected,completed,cancelled',
        ]);

        $oldStatus = $booking->status;
        $booking->update($request->only(['status']));
        $newStatus = $booking->status;

        // Sync user's completed booking count
        if ($oldStatus !== 'completed' && $newStatus === 'completed') {
            $booking->user->increment('completed_bookings_count');
        } elseif ($oldStatus === 'completed' && $newStatus !== 'completed') {
            $booking->user->decrement('completed_bookings_count');
        }

        // Update room status based on booking
        if (in_array($newStatus, ['confirmed'])) {
            $booking->room->update(['status' => 'occupied']);
        } elseif (in_array($newStatus, ['rejected', 'cancelled', 'completed'])) {
            $booking->room->update(['status' => 'available']);
        }

        return response()->json(['message' => 'Booking updated successfully', 'booking' => $booking]);
    }

    public function destroy(Booking $booking)
    {
        $user = Auth::user();
        
        // Only owner or admin can cancel
        if (!$user->isAdmin() && $booking->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (in_array($booking->status, ['confirmed', 'completed'])) {
            return response()->json(['message' => 'Cannot delete confirmed or completed bookings'], 422);
        }

        $booking->delete();

        return response()->json(['message' => 'Booking deleted successfully']);
    }
}