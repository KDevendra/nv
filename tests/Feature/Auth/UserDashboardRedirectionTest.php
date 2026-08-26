<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDashboardRedirectionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unauthenticated_user_accessing_dashboard_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function owner_accessing_dashboard_is_redirected_to_owner_dashboard(): void
    {
        $owner = User::factory()->create(['role' => 'owner', 'is_active' => true]);
        $response = $this->actingAs($owner)->get('/dashboard');
        $response->assertRedirect(route('owner.dashboard'));
    }

    /** @test */
    public function user_role_accessing_dashboard_is_redirected_to_user_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertRedirect(route('user.dashboard'));
    }

    /** @test */
    public function field_officer_accessing_dashboard_is_redirected_to_field_dashboard(): void
    {
        $fieldOfficer = User::factory()->create(['role' => 'field_officer', 'division' => 'warehousing', 'is_active' => true]);
        $response = $this->actingAs($fieldOfficer)->get('/dashboard');
        $response->assertRedirect(route('field.dashboard'));
    }

    /** @test */
    public function channel_partner_accessing_dashboard_is_redirected_to_cp_dashboard(): void
    {
        $cp = User::factory()->create(['role' => 'channel_partner', 'is_active' => true]);
        $response = $this->actingAs($cp)->get('/dashboard');
        $response->assertRedirect(route('channel_partner.dashboard'));
    }

    /** @test */
    public function login_redirects_user_to_their_role_specific_dashboard(): void
    {
        $owner = User::factory()->create([
            'email' => 'owner@example.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'login_field' => 'owner@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('owner.dashboard'));
    }
}
