<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class OtpAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_otp_success_for_existing_user(): void
    {
        $user = User::factory()->create([
            'phone' => '9876543210',
        ]);

        $response = $this->postJson(route('otp.send'), [
            'phone' => '9876543210',
            'type'  => 'login',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertNotNull(Cache::get('otp_9876543210'));
    }

    public function test_send_otp_fails_if_login_user_not_found(): void
    {
        $response = $this->postJson(route('otp.send'), [
            'phone' => '9999999999',
            'type'  => 'login',
        ]);

        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    public function test_login_with_valid_otp(): void
    {
        $user = User::factory()->create([
            'phone' => '9876543210',
            'role'  => 'owner',
        ]);

        Cache::put('otp_9876543210', '123456', 600);

        $response = $this->postJson(route('otp.login'), [
            'phone' => '9876543210',
            'otp'   => '123456',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success'      => true,
                'redirect_url' => route('owner.dashboard'),
            ]);

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_invalid_otp(): void
    {
        User::factory()->create([
            'phone' => '9876543210',
        ]);

        Cache::put('otp_9876543210', '123456', 600);

        $response = $this->postJson(route('otp.login'), [
            'phone' => '9876543210',
            'otp'   => '999999',
        ]);

        $response->assertStatus(422)
            ->assertJson(['success' => false]);

        $this->assertGuest();
    }

    public function test_register_with_valid_otp(): void
    {
        Cache::put('otp_9876543210', '654321', 600);

        $response = $this->postJson(route('otp.register'), [
            'name'  => 'Test User',
            'phone' => '9876543210',
            'email' => 'testuser@example.com',
            'otp'   => '654321',
            'role'  => 'user',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('users', [
            'phone' => '9876543210',
            'email' => 'testuser@example.com',
            'role'  => 'user',
        ]);

        $this->assertAuthenticated();
    }
}
