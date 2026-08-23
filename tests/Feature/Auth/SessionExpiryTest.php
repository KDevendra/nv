<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class SessionExpiryTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'owner', 'is_active' => true]);
    }

    /** @test */
    public function a_normal_login_then_logout_round_trip_does_not_419(): void
    {
        $this->actingAs($this->user);

        $response = $this->post(route('logout'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    /**
     * Renders an exception through the application's real exception handler.
     *
     * Laravel's VerifyCsrfToken middleware short-circuits whenever
     * runningUnitTests() is true, so a stale `_token` can never actually
     * trigger a TokenMismatchException through the HTTP test harness — a
     * test that just POSTs a bad token passes trivially and proves nothing.
     * Driving the handler directly is what genuinely exercises the fix.
     */
    private function renderThroughHandler(\Throwable $e, \Illuminate\Http\Request $request)
    {
        return app(\Illuminate\Contracts\Debug\ExceptionHandler::class)->render($request, $e);
    }

    /**
     * @test
     *
     * The real-world case: a tab left open past SESSION_LIFETIME still holds
     * the old CSRF token, so the logout POST fails token verification. That
     * must log the user out and land them on /login rather than dead-ending
     * on a 419 page.
     */
    public function a_token_mismatch_on_logout_redirects_to_login_instead_of_419(): void
    {
        $request = \Illuminate\Http\Request::create('/logout', 'POST');
        $request->setLaravelSession($this->app['session.store']);

        $response = $this->renderThroughHandler(
            new \Illuminate\Session\TokenMismatchException('CSRF token mismatch.'),
            $request
        );

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame(route('login'), $response->headers->get('Location'));
    }

    /** @test */
    public function a_token_mismatch_on_any_other_route_still_produces_a_419(): void
    {
        // CSRF protection must remain fully intact everywhere else — the
        // logout handling above is scoped, not a blanket exemption.
        $request = \Illuminate\Http\Request::create('/owner/properties', 'POST');
        $request->setLaravelSession($this->app['session.store']);

        $response = $this->renderThroughHandler(
            new \Illuminate\Session\TokenMismatchException('CSRF token mismatch.'),
            $request
        );

        $this->assertSame(419, $response->getStatusCode());
        $this->assertStringNotContainsString('Location', (string) $response->headers->get('Location'));
    }

    /** @test */
    public function the_custom_419_view_renders_with_a_link_back_to_login(): void
    {
        $this->assertFileExists(resource_path('views/errors/419.blade.php'));

        $html = view('errors.419')->render();

        $this->assertStringContainsString('Your Session Expired', $html);
        $this->assertStringContainsString(route('login'), $html);
        $this->assertStringContainsString('Log In Again', $html);
    }

    /** @test */
    public function session_configuration_is_sane_for_this_environment(): void
    {
        // Guards against the misconfigurations that produce a silent 419:
        // a too-short lifetime, or a cookie the browser will never send back.
        $this->assertGreaterThanOrEqual(120, config('session.lifetime'));
        $this->assertFalse((bool) config('session.expire_on_close'));

        // On a non-HTTPS local origin a secure cookie is never returned,
        // which breaks the session silently.
        if (! str_starts_with((string) config('app.url'), 'https://')) {
            $this->assertNotTrue(config('session.secure'));
        }
    }
}
