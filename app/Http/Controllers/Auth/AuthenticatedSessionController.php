<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        // Redirect based on user role
        if (in_array($user->role, ['supply_head', 'field_officer'])) {
            return redirect()->intended(route('field.dashboard', absolute: false));
        } elseif ($user->role === 'owner') {
            return redirect()->intended(route('owner.dashboard', absolute: false));
        } elseif ($user->role === 'user') {
            return redirect()->intended(route('user.dashboard', absolute: false));
        }

        // Admin, super_admin and other roles go to main dashboard
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();
        } catch (\Exception $e) {
            // Log the error but don't prevent logout
            \Log::error('Logout error: ' . $e->getMessage());
        }

        // Always redirect to login regardless of any errors
        return redirect()->route('login');
    }
}
