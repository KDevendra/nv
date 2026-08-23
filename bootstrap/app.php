<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\TrackPageVisit::class,
        ]);

        $middleware->alias([
            'permission' => \App\Http\Middleware\CheckPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Logging out with an expired session is a success state, not an
        // error: the user wants to end up logged out, and they already are.
        // Without this, a tab left open past SESSION_LIFETIME throws a
        // TokenMismatchException on the logout POST and dead-ends the user
        // on a 419 page they can do nothing useful with.
        //
        // Scoped to the logout path only — CSRF protection is left fully
        // intact for every other route, including normal logout requests
        // that do carry a valid token.
        // Type-hinted on HttpException, not TokenMismatchException: Laravel's
        // Handler::render() calls prepareException() BEFORE renderViaCallbacks(),
        // which has already converted the TokenMismatchException into an
        // HttpException(419) by the time this closure is matched.
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, \Illuminate\Http\Request $request) {
            if ($e->getStatusCode() !== 419 || ! $request->is('logout')) {
                return null; // fall through to the standard 419 response
            }

            try {
                \Illuminate\Support\Facades\Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            } catch (\Throwable $ignored) {
                // Session was already unusable — nothing left to tear down.
            }

            return redirect()->route('login')
                ->with('status', 'Your session had already expired, so you have been logged out.');
        });
    })->create();
