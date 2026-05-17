<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($request->ajax() || $request->wantsJson() || $request->is('api/*') || $request->has('hotel_id') || $request->has('city')) {
            $query = Room::query()->where('status', 'available');

            // Filter by hotel if provided
            if ($request->has('hotel_id')) {
                $query->where('hotel_id', $request->hotel_id);
            }

            // Filter by city, hotel name, or address
            if ($request->has('city')) {
                $search = $request->city;
                $query->whereHas('hotel', function ($q) use ($search) {
                    $q->where('city', 'like', '%' . $search . '%')
                      ->orWhere('name', 'like', '%' . $search . '%')
                      ->orWhere('address', 'like', '%' . $search . '%');
                });
            }

            // Filter by capacity if provided
            if ($request->has('guests')) {
                $query->where('capacity', '>=', $request->guests);
            }

            // Filter by date availability if both dates provided
            if ($request->has('check_in') && $request->has('check_out')) {
                $checkIn = $request->check_in;
                $checkOut = $request->check_out;

                $query->whereDoesntHave('bookings', function ($q) use ($checkIn, $checkOut) {
                    $q->whereIn('status', ['pending', 'confirmed'])
                      ->where(function ($inner) use ($checkIn, $checkOut) {
                          $inner->where('check_in_date', '<', $checkOut)
                                ->where('check_out_date', '>', $checkIn);
                      });
                });
            }

            $rooms = $query->with('hotel')->paginate(9);
            return response()->json($rooms);
        }
        
        if ($user && $user->isAdmin()) {
            $rooms = Room::with('hotel')->paginate(15);
            $hotels = \App\Models\Hotel::all();
        } elseif ($user && $user->isManager()) {
            // Manager: only rooms in their hotels
            $rooms = Room::whereHas('hotel', function ($q) use ($user) {
                $q->where('manager_id', $user->id);
            })->with('hotel')->paginate(15);
            $hotels = \App\Models\Hotel::where('manager_id', $user->id)->get();
        } else {
            // Guest or Customer
            $rooms = Room::with('hotel')->paginate(15);
            $hotels = collect();
        }

        return view('manager.rooms', compact('rooms', 'hotels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_number' => [
                'required',
                'string',
                Rule::unique('rooms')->where(function ($query) use ($request) {
                    return $query->where('hotel_id', $request->hotel_id);
                })
            ],
            'room_type' => 'required|in:standard,deluxe,suite,presidential',
            'description' => 'required|string',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'amenities' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'in:available,occupied,maintenance',
        ]);

        // Ensure user owns the hotel
        $hotel = \App\Models\Hotel::findOrFail($request->hotel_id);
        if ($hotel->manager_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->all();
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('rooms', 'public');
            $data['image'] = '/storage/' . $path;
        }

        $room = Room::create($data);

        return response()->json(['message' => 'Room created successfully', 'room' => $room], 201);
    }

    public function show(Room $room)
    {
        return response()->json($room);
    }

    public function update(Request $request, Room $room)
    {
        // Ensure user owns the hotel or is admin
        if ($room->hotel->manager_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'room_number' => [
                'sometimes',
                'string',
                Rule::unique('rooms')->ignore($room->id)->where(function ($query) use ($room) {
                    return $query->where('hotel_id', $room->hotel_id);
                })
            ],
            'room_type' => 'sometimes|in:standard,deluxe,suite,presidential',
            'description' => 'sometimes|string',
            'price_per_night' => 'sometimes|numeric|min:0',
            'capacity' => 'sometimes|integer|min:1',
            'amenities' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'in:available,occupied,maintenance',
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($room->image && str_contains($room->image, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $room->image);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
            }
            
            $path = $request->file('image')->store('rooms', 'public');
            $data['image'] = '/storage/' . $path;
        }

        $room->update($data);

        return response()->json(['message' => 'Room updated successfully', 'room' => $room]);
    }

    public function destroy(Room $room)
    {
        // Ensure user owns the hotel or is admin
        if ($room->hotel->manager_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $room->delete();

        return response()->json(['message' => 'Room deleted successfully']);
    }

    public function browse()
    {
        $rooms = Room::available()->get();
        return view('customer.rooms', compact('rooms'));
    }
}