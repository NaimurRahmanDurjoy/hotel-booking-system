<?php

namespace App\Http\Controllers;

use App\Models\PremiumSubscription;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\PremiumPlan;

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

    public function checkStatus()
    {
        $user = Auth::user();
        if (!$user) return response()->json(['premium' => false]);

        $subscription = PremiumSubscription::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        $discount = 0;
        if ($subscription) {
            $plan = PremiumPlan::where('tier_key', $subscription->tier)->first();
            $discount = $plan ? $plan->discount_percentage : 0;
        }

        return response()->json([
            'premium' => (bool) $subscription,
            'discount' => $discount,
            'subscription' => $subscription,
            'completed_bookings' => $user->completed_bookings_count
        ]);
    }

    public function plans()
    {
        $plans = PremiumPlan::where('is_active', true)->get();
        return response()->json($plans);
    }

    public function subscribe(Request $request)
    {
        $validTiers = PremiumPlan::where('is_active', true)->pluck('tier_key')->toArray();
        $request->validate([
            'tier' => 'required|in:' . implode(',', $validTiers),
            'duration_months' => 'required|integer|min:1|max:12',
        ]);

        $user = Auth::user();
        
        // Find the plan
        $plan = PremiumPlan::where('tier_key', $request->tier)->first();
        if (!$plan) {
            return response()->json(['message' => 'Invalid plan selected'], 422);
        }

        // Check requirements (completed bookings)
        $completedBookings = $user->completed_bookings_count;
        if ($completedBookings < $plan->min_bookings) {
            return response()->json([
                'message' => "You need at least {$plan->min_bookings} completed bookings to join this tier. You currently have {$completedBookings}."
            ], 422);
        }

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

    public function storePlan(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tier_key' => 'required|string|max:255|unique:premium_plans',
            'min_bookings' => 'required|integer|min:0',
            'discount_percentage' => 'required|integer|min:0|max:100',
            'price' => 'required|numeric|min:0',
            'benefits' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $plan = PremiumPlan::create($validated);

        return response()->json(['message' => 'Plan created successfully', 'plan' => $plan], 201);
    }

    public function updatePlan(Request $request, PremiumPlan $plan)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'tier_key' => 'sometimes|string|max:255|unique:premium_plans,tier_key,' . $plan->id,
            'min_bookings' => 'sometimes|integer|min:0',
            'discount_percentage' => 'sometimes|integer|min:0|max:100',
            'price' => 'sometimes|numeric|min:0',
            'benefits' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $plan->update($validated);

        return response()->json(['message' => 'Plan updated successfully', 'plan' => $plan]);
    }

    public function deletePlan(PremiumPlan $plan)
    {
        $plan->delete();
        return response()->json(['message' => 'Plan deleted successfully']);
    }
}