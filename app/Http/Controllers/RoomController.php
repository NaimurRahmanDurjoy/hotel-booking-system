<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = Room::query()->where('status', 'available');

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

            $rooms = $query->get();
            return response()->json($rooms);
        }
        
        $rooms = Room::paginate(15);
        return view('manager.rooms', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_number' => 'required|string|unique:rooms',
            'room_type' => 'required|in:standard,deluxe,suite,presidential',
            'description' => 'required|string',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'amenities' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'in:available,occupied,maintenance',
        ]);

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
        $request->validate([
            'room_number' => 'sometimes|string|unique:rooms,room_number,' . $room->id,
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
        $room->delete();

        return response()->json(['message' => 'Room deleted successfully']);
    }

    public function browse()
    {
        $rooms = Room::available()->get();
        return view('customer.rooms', compact('rooms'));
    }
}