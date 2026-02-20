<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FilterSubscriptionController extends Controller
{
    /**
     * Display a listing of the user's subscriptions.
     */
    public function index()
    {
        $subscriptions = Auth::user()->subscriptions()
            ->latest()
            ->get();

        return response()->json($subscriptions);
    }

    /**
     * Store a newly created subscription.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'filters' => 'required|array',
            'filters.companies' => 'array',
            'filters.processes' => 'array',
            'filters.flavor_notes' => 'array',
            'filters.varieties' => 'array',
            'filters.countries' => 'array',
            'search' => 'nullable|string|max:255',
        ]);

        // Check if at least one filter is selected
        $hasFilters = false;
        foreach (['companies', 'processes', 'flavor_notes', 'varieties', 'countries'] as $filterType) {
            if (!empty($validated['filters'][$filterType])) {
                $hasFilters = true;
                break;
            }
        }

        if (!$hasFilters && empty($validated['search'])) {
            return back()->withErrors([
                'filters' => 'Please select at least one filter or enter a search term.'
            ]);
        }

        $subscription = Auth::user()->subscriptions()->create([
            'filters' => $validated['filters'],
            'search' => $validated['search'] ?? null,
        ]);

        return back()->with('success', 'You will receive daily email notifications when new coffees match your filters.');
    }

    /**
     * Remove the specified subscription.
     */
    public function destroy(Subscription $subscription)
    {
        // Ensure the subscription belongs to the authenticated user
        if ($subscription->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $subscription->delete();

        return back()->with('success', 'Subscription deleted successfully.');
    }
}
