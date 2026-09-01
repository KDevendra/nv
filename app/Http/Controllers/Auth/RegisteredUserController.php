<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'phone'    => ['required', 'string', 'regex:/^[0-9]{10}$/', 'unique:'.User::class.',phone'],
            'email'    => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', 'string', 'in:channel_partner,owner,user'],
        ], [
            'phone.required' => 'Mobile number is required.',
            'phone.regex'    => 'Please enter a valid 10-digit mobile number.',
            'phone.unique'   => 'This mobile number is already registered.',
        ]);

        $phone = preg_replace('/[^0-9]/', '', $request->input('phone'));
        $email = $request->input('email');
        if (empty($email) && !empty($phone)) {
            $email = $phone . '@zendoindia.local';
        }

        $user = User::create([
            'name'     => $request->name,
            'phone'    => $phone,
            'email'    => $email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect($user->getDashboardUrl());
    }
}
