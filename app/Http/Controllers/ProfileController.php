<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Company\Models\Company;
use Modules\Offering\Models\Country;
use Modules\Offering\Models\FlavorNote;
use Modules\Offering\Models\Process;
use Modules\Offering\Models\Variety;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $subscriptions = $request->user()->subscriptions()->latest()->get();

        // Get all filter options to resolve IDs to names
        $countries = Country::all(['id', 'name'])->keyBy('id');
        $processes = Process::all(['id', 'name'])->keyBy('id');
        $flavorNotes = FlavorNote::all(['id', 'name'])->keyBy('id');
        $varieties = Variety::all(['id', 'name'])->keyBy('id');
        $companies = Company::all(['id', 'name'])->keyBy('id');

        // Resolve filter IDs to names for each subscription
        $subscriptions->each(function ($subscription) use ($countries, $processes, $flavorNotes, $varieties, $companies) {
            $filters = $subscription->filters ?? [];
            $resolvedFilters = [];

            if (!empty($filters['countries'])) {
                $resolvedFilters['countries'] = collect($filters['countries'])
                    ->map(fn($id) => $countries->get($id)?->name)
                    ->filter()
                    ->values()
                    ->toArray();
            }

            if (!empty($filters['processes'])) {
                $resolvedFilters['processes'] = collect($filters['processes'])
                    ->map(fn($id) => $processes->get($id)?->name)
                    ->filter()
                    ->values()
                    ->toArray();
            }

            if (!empty($filters['flavor_notes'])) {
                $resolvedFilters['flavor_notes'] = collect($filters['flavor_notes'])
                    ->map(fn($id) => $flavorNotes->get($id)?->name)
                    ->filter()
                    ->values()
                    ->toArray();
            }

            if (!empty($filters['varieties'])) {
                $resolvedFilters['varieties'] = collect($filters['varieties'])
                    ->map(fn($id) => $varieties->get($id)?->name)
                    ->filter()
                    ->values()
                    ->toArray();
            }

            if (!empty($filters['companies'])) {
                $resolvedFilters['companies'] = collect($filters['companies'])
                    ->map(fn($id) => $companies->get($id)?->name)
                    ->filter()
                    ->values()
                    ->toArray();
            }

            $subscription->resolved_filters = $resolvedFilters;
        });

        $tokens = $request->user()->tokens()
            ->select(['id', 'name', 'last_used_at', 'created_at'])
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'subscriptions' => $subscriptions,
            'tokens' => $tokens,
            'newToken' => session('new_token'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update the user's profile picture.
     */
    public function updateProfilePicture(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_picture' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user = $request->user();

        // Delete old profile picture if exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Store new image
        $path = $request->file('profile_picture')->store('profile-pictures', 'public');

        $user->profile_picture = $path;
        $user->save();

        return back()->with('status', 'profile-picture-updated');
    }

    /**
     * Remove the user's profile picture.
     */
    public function removeProfilePicture(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
            $user->profile_picture = null;
            $user->save();
        }

        return back()->with('status', 'profile-picture-removed');
    }
}
