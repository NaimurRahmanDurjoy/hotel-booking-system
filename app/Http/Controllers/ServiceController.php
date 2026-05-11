<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($request->wantsJson()) {
            $query = Service::where('is_available', true);

            if ($request->has('hotel_id')) {
                $query->where('hotel_id', $request->hotel_id);
            }

            $services = $query->get();
            return response()->json($services);
        }

        if ($user->isAdmin()) {
            $services = Service::with('hotel')->paginate(15);
        } else {
            $services = Service::whereHas('hotel', function ($q) use ($user) {
                $q->where('manager_id', $user->id);
            })->with('hotel')->paginate(15);
        }

        return view('manager.services', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
        ]);

        // Ensure user owns the hotel
        $hotel = \App\Models\Hotel::findOrFail($request->hotel_id);
        if ($hotel->manager_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->all();
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('services', 'public');
            $data['image'] = '/storage/' . $path;
        }

        $service = Service::create($data);

        return response()->json(['message' => 'Service created successfully', 'service' => $service], 201);
    }

    public function show(Service $service)
    {
        return response()->json($service);
    }

    public function update(Request $request, Service $service)
    {
        // Ensure user owns the hotel or is admin
        if ($service->hotel->manager_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($service->image && str_contains($service->image, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $service->image);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
            }
            
            $path = $request->file('image')->store('services', 'public');
            $data['image'] = '/storage/' . $path;
        }

        $service->update($data);

        return response()->json(['message' => 'Service updated successfully', 'service' => $service]);
    }

    public function destroy(Service $service)
    {
        // Ensure user owns the hotel or is admin
        if ($service->hotel->manager_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $service->delete();

        return response()->json(['message' => 'Service deleted successfully']);
    }
}