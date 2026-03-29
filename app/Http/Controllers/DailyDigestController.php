<?php

namespace App\Http\Controllers;

use App\Models\DailyDigestSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyDigestController extends Controller
{
    public function subscribe(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('intended', url()->current());
        }

        $user = Auth::user();

        // Check if already subscribed
        $existing = DailyDigestSubscriber::where('user_id', $user->id)->first();

        if ($existing) {
            return back()->with('message', 'You are already subscribed to the daily digest!');
        }

        DailyDigestSubscriber::create([
            'user_id' => $user->id,
        ]);

        return back()->with('message', 'Successfully subscribed to the daily digest! You will receive an email at 8 AM with all new coffees added in the last 24 hours.');
    }

    public function unsubscribe(Request $request, string $token)
    {
        $subscriber = DailyDigestSubscriber::where('unsubscribe_token', $token)->first();

        if (!$subscriber) {
            abort(404, 'Subscription not found.');
        }

        $subscriber->delete();

        return view('daily-digest.unsubscribed');
    }

    public function checkSubscription(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['subscribed' => false]);
        }

        $subscribed = DailyDigestSubscriber::where('user_id', Auth::id())->exists();

        return response()->json(['subscribed' => $subscribed]);
    }
}
