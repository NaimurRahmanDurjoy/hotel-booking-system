<?php

namespace App\Http\Controllers;

use App\Models\PremiumSubscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PremiumController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $subscription = PremiumSubscription::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        return response()->json([
            'user' => $user,
            'subscription' => $subscription,
        ]);
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'tier' => 'required|in:silver,gold',
            'duration_months' => 'required|integer|min:1|max:12',
        ]);

        $user = Auth::user();

        // Check if already subscribed
        $existingSubscription = PremiumSubscription::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if ($existingSubscription) {
            return response()->json(['message' => 'Already subscribed to premium'], 422);
        }

        $startDate = Carbon::now();
        $endDate = $startDate->copy()->addMonths($request->duration_months);

        $subscription = PremiumSubscription::create([
            'user_id' => $user->id,
            'tier' => $request->tier,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_active' => true,
        ]);

        // Update user premium status
        $user->update([
            'is_premium' => true,
            'premium_tier' => $request->tier,
        ]);

        return response()->json([
            'message' => 'Premium subscription activated successfully',
            'subscription' => $subscription,
        ], 201);
    }

    public function cancel()
    {
        $user = Auth::user();

        $subscription = PremiumSubscription::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$subscription) {
            return response()->json(['message' => 'No active subscription'], 422);
        }

        $subscription->update(['is_active' => false]);

        // Update user premium status
        $user->update([
            'is_premium' => false,
            'premium_tier' => null,
        ]);

        return response()->json(['message' => 'Premium subscription cancelled']);
    }

    public function extend(Request $request)
    {
        $request->validate([
            'duration_months' => 'required|integer|min:1|max:12',
        ]);

        $user = Auth::user();

        $subscription = PremiumSubscription::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$subscription) {
            return response()->json(['message' => 'No active subscription to extend'], 422);
        }

        // Extend the subscription
        $newEndDate = Carbon::parse($subscription->end_date)->addMonths($request->duration_months);
        $subscription->update(['end_date' => $newEndDate]);

        return response()->json([
            'message' => 'Subscription extended successfully',
            'subscription' => $subscription,
        ]);
    }
}