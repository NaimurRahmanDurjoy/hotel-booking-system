<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            $hotels = Hotel::with('manager')->paginate(15);
        } else {
            $hotels = Hotel::where('manager_id', $user->id)->paginate(15);
        }

        return view('manager.hotels', compact('hotels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'address', 'city']);
        $data['manager_id'] = Auth::id();

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('hotels', 'public');
                $images[] = '/storage/' . $path;
            }
            $data['images'] = $images;
        }

        $hotel = Hotel::create($data);

        return response()->json(['message' => 'Hotel created successfully', 'hotel' => $hotel], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Hotel $hotel)
    {
        return response()->json($hotel->load('rooms', 'services'));
    }

    /**
     * Get distinct cities.
     */
    public function getCities()
    {
        $cities = Hotel::select('city')->distinct()->whereNotNull('city')->pluck('city');
        return response()->json($cities);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hotel $hotel)
    {
        // Ensure user owns the hotel or is admin
        if (Auth::id() !== $hotel->manager_id && !Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'address' => 'sometimes|string',
            'city' => 'sometimes|string',
            'status' => 'sometimes|in:active,inactive',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('hotels', 'public');
            $data['image'] = '/storage/' . $path;
        }

        $hotel->update($data);

        return response()->json(['message' => 'Hotel updated successfully', 'hotel' => $hotel]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hotel $hotel)
    {
        if (Auth::id() !== $hotel->manager_id && !Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $hotel->delete();

        return response()->json(['message' => 'Hotel deleted successfully']);
    }
}
