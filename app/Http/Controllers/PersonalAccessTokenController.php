<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PersonalAccessTokenController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $token = $request->user()->createToken($request->name);

        return redirect('/profile')
            ->with('new_token', $token->plainTextToken);
    }

    public function destroy(Request $request, int $tokenId): RedirectResponse
    {
        $token = $request->user()->tokens()->findOrFail($tokenId);
        $token->delete();

        return redirect('/profile')
            ->with('status', 'token-revoked');
    }
}
