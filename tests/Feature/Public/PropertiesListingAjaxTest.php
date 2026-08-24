<?php

namespace Tests\Feature\Public;

use App\Models\PropertyEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertiesListingAjaxTest extends TestCase
{
    use RefreshDatabase;

    private User $officer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->officer = User::factory()->create(['role' => 'field_officer', 'is_active' => true]);
    }

    private function createLiveEntry(array $attributes = []): PropertyEntry
    {
        return PropertyEntry::create(array_merge([
            'field_officer_id'    => $this->officer->id,
            'status'              => 'verified',
            'admin_status'        => 'approved',
            'show_on_website'     => true,
            'submitted_at'        => now(),
            'property_type'       => 'apartment_flat_studio',
            'city'                => 'Raipur',
            'locality_broad_area' => 'VVIP Colony',
            'property_name'       => 'Default Live Property',
        ], $attributes));
    }

    /** @test */
    public function ajax_request_returns_only_results_grid_partial_html(): void
    {
        $this->createLiveEntry(['property_name' => 'Ajax Test Property']);

        $response = $this->get(route('properties.index'), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertStatus(200);
        $response->assertSee('Property Listings');
        $response->assertSee('Ajax Test Property');

        // Assert partial output does NOT contain full layout / header / navbar
        $response->assertDontSee('<html');
        $response->assertDontSee('<head');
        $response->assertDontSee('Properties - ZendoIndia');
    }

    /** @test */
    public function full_page_and_partial_routes_produce_identical_result_rows(): void
    {
        $entry1 = $this->createLiveEntry([
            'property_name' => 'Raipur Warehouse 1',
            'city' => 'Raipur',
            'property_type' => 'warehouse',
        ]);

        $entry2 = $this->createLiveEntry([
            'property_name' => 'Mumbai Apartment 1',
            'city' => 'Mumbai',
            'property_type' => 'apartment_flat_studio',
        ]);

        $queryParams = ['city' => 'Raipur', 'property_type_slug' => 'warehouse'];

        // 1. Full page GET
        $fullPageResponse = $this->get(route('properties.index', $queryParams));
        $fullPageResponse->assertStatus(200);
        $fullPageResponse->assertSee('Raipur Warehouse 1');
        $fullPageResponse->assertDontSee('Mumbai Apartment 1');

        // 2. Partial AJAX GET
        $ajaxResponse = $this->get(route('properties.index', $queryParams), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);
        $ajaxResponse->assertStatus(200);
        $ajaxResponse->assertSee('Raipur Warehouse 1');
        $ajaxResponse->assertDontSee('Mumbai Apartment 1');
    }

    /** @test */
    public function direct_get_without_ajax_header_returns_full_page_with_preselected_filters(): void
    {
        $this->createLiveEntry([
            'property_name' => 'Shared Link Property',
            'city' => 'Raipur',
            'property_type' => 'warehouse',
        ]);

        $response = $this->get(route('properties.index', [
            'city' => 'Raipur',
            'property_type_slug' => 'warehouse',
        ]));

        $response->assertStatus(200);
        $response->assertSee('<html', false);
        $response->assertSee('Properties - ZendoIndia');
        $response->assertSee('Shared Link Property');

        // Assert preselected option markup using assertSeeInOrder for multi-line Blade formatting
        $response->assertSeeInOrder(['value="Raipur"', 'selected']);
        $response->assertSeeInOrder(['value="warehouse"', 'selected']);
    }
}
