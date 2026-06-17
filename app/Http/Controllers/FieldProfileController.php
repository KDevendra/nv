<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class FieldProfileController extends Controller
{
    /**
     * Show the field profile page.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $user->load(['supplyHead', 'fieldOfficers']);

        return view('field.profile', compact('user'));
    }

    /**
     * Update name only — email is the username and cannot be changed.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $request->user()->update($validated);

        return Redirect::route('field.profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password'      => ['required', 'current_password'],
            'password'              => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return Redirect::route('field.profile.edit')->with('status', 'password-updated');
    }
}
