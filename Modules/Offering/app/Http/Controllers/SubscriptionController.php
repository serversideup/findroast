<?php

namespace Modules\Offering\Http\Controllers;

use Modules\Offering\Http\Requests\StoreSubscriptionRequest;
use Modules\Offering\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubscriptionController extends Controller
{
    /**
     * Store a new filter subscription.
     */
    public function store(StoreSubscriptionRequest $request): RedirectResponse
    {
        $subscription = Subscription::create([
            'email' => $request->validated('email'),
            'frequency' => $request->validated('frequency'),
            'filters' => $request->validated('filters'),
            'verification_token' => Str::random(64),
            'user_id' => $request->user()?->id,
        ]);

        // TODO: Send verification email to the user
        // Mail::to($subscription->email)->send(new VerifyFilterSubscription($subscription));

        return redirect()->back()->with('success', 'Subscription created! Please check your email to verify.');
    }

    /**
     * Verify a filter subscription.
     */
    public function verify(Request $request, string $token): RedirectResponse
    {
        $subscription = Subscription::where('verification_token', $token)
            ->whereNull('verified_at')
            ->firstOrFail();

        $subscription->update([
            'verified_at' => now(),
            'verification_token' => null,
        ]);

        return redirect()->route('offerings.index')->with('success', 'Your subscription has been verified!');
    }

    /**
     * Unsubscribe from filter notifications.
     */
    public function unsubscribe(Request $request, Subscription $subscription): RedirectResponse
    {
        $subscription->delete();

        return redirect()->route('offerings.index')->with('success', 'You have been unsubscribed.');
    }
}

