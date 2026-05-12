<?php

namespace App\Http\Controllers;

use App\Models\TravelPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TravelPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($request->wantsJson()) {
            $query = TravelPackage::query();
            if ($request->has('destination')) {
                $query->where('destination', 'like', '%' . $request->destination . '%');
            }
            return response()->json($query->get());
        }

        if ($user->isAdmin()) {
            $packages = TravelPackage::with('vendor')->paginate(15);
        } else {
            $packages = TravelPackage::where('vendor_id', $user->id)->paginate(15);
        }

        return view('manager.travel.index', compact('packages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'destination' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'images' => 'nullable|array',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['title', 'description', 'destination', 'price', 'duration_days']);
        $data['vendor_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('packages', 'public');
            $data['images'] = ['/storage/' . $path];
        }

        $package = TravelPackage::create($data);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Travel package created successfully', 'package' => $package], 201);
        }

        return redirect()->back()->with('success', 'Travel package created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(TravelPackage $travelPackage)
    {
        return response()->json($travelPackage);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TravelPackage $travelPackage)
    {
        if (Auth::id() !== $travelPackage->vendor_id && !Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'destination' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'duration_days' => 'sometimes|integer|min:1',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['title', 'description', 'destination', 'price', 'duration_days']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('packages', 'public');
            $data['images'] = ['/storage/' . $path];
        }

        $travelPackage->update($data);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Travel package updated successfully', 'package' => $travelPackage]);
        }

        return redirect()->back()->with('success', 'Travel package updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TravelPackage $travelPackage)
    {
        if (Auth::id() !== $travelPackage->vendor_id && !Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $travelPackage->delete();

        return response()->json(['message' => 'Travel package deleted successfully']);
    }
}
