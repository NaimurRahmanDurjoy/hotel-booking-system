<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $stats = [
                'total_users' => User::count(),
                'total_customers' => User::where('role', 'customer')->count(),
                'total_managers' => User::where('role', 'manager')->count(),
                'total_admins' => User::where('role', 'admin')->count(),
                'total_rooms' => Room::count(),
                'available_rooms' => Room::where('status', 'available')->count(),
                'total_bookings' => Booking::count(),
                'total_travel_packages' => \App\Models\TravelPackage::count(),
                'total_travel_bookings' => \App\Models\TravelBooking::count(),
                'total_cars' => \App\Models\Car::count(),
                'pending_bookings' => Booking::where('status', 'pending')->count(),
                'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
                'completed_bookings' => Booking::where('status', 'completed')->count(),
                'total_revenue' => Booking::whereIn('status', ['confirmed', 'completed'])->sum('total_price'),
                'premium_users' => User::where('is_premium', true)->count(),
                'total_hotels' => \App\Models\Hotel::count(),
            ];

            $recentBookings = Booking::with(['user', 'room', 'hotel'])->latest()->take(5)->get();
            return view('admin.dashboard', compact('stats', 'recentBookings'));
        } else {
            // Manager Stats
            $stats = [
                'total_hotels' => $user->hotels()->count(),
                'total_rooms' => Room::whereHas('hotel', fn($q) => $q->where('manager_id', $user->id))->count(),
                'available_rooms' => Room::whereHas('hotel', fn($q) => $q->where('manager_id', $user->id))->where('status', 'available')->count(),
                'total_bookings' => Booking::whereHas('hotel', fn($q) => $q->where('manager_id', $user->id))->count(),
                'pending_bookings' => Booking::whereHas('hotel', fn($q) => $q->where('manager_id', $user->id))->where('status', 'pending')->count(),
                'total_revenue' => Booking::whereHas('hotel', fn($q) => $q->where('manager_id', $user->id))->whereIn('status', ['confirmed', 'completed'])->sum('total_price'),
                'total_travel_packages' => $user->travelPackages()->count(),
                'total_travel_bookings' => \App\Models\TravelBooking::whereHas('package', fn($q) => $q->where('vendor_id', $user->id))->count(),
            ];

            $recentBookings = Booking::whereHas('hotel', fn($q) => $q->where('manager_id', $user->id))
                ->with(['user', 'room', 'hotel'])
                ->latest()
                ->take(5)
                ->get();

            return view('manager.dashboard', compact('stats', 'recentBookings'));
        }
    }

    public function users()
    {
        $users = User::paginate(15);
        return view('admin.users', compact('users'));
    }

    public function user(User $user)
    {
        return response()->json($user);
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'role' => 'sometimes|in:customer,manager,admin',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'password' => 'nullable|string|min:8',
            'is_premium' => 'sometimes|boolean',
            'premium_tier' => 'nullable|in:silver,gold',
        ]);

        $data = $request->except(['password']);
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    public function deleteUser(User $user)
    {
        if ($user->isAdmin()) {
            return response()->json(['message' => 'Cannot delete admin users'], 422);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:customer,manager,admin',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
        ], 201);
    }

    public function bookings(Request $request)
    {
        $query = Booking::with(['user', 'room', 'services']);
        
        if ($request->has('status') && $request->status !== 'All Statuses') {
            $query->where('status', strtolower($request->status));
        }

        $bookings = $query->latest()->paginate(15);
        return view('admin.bookings', compact('bookings'));
    }

    public function services()
    {
        $services = Service::all();
        return response()->json($services);
    }
}