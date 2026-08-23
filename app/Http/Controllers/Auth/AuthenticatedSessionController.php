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

        // Clear generic /dashboard or /login intended URLs so non-admins are never sent to 404/403 pages
        $intended = session('url.intended');
        if ($intended && (str_contains($intended, '/dashboard') || str_contains($intended, '/login'))) {
            session()->forget('url.intended');
        }

        return redirect()->intended($user->getDashboardUrl());
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
