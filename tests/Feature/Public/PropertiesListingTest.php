<?php

namespace Tests\Feature\Public;

use App\Models\PropertyEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PropertiesListingTest extends TestCase
{
    use RefreshDatabase;

    private User $officer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->officer = User::factory()->create(['role' => 'field_officer', 'is_active' => true]);
    }

    /**
     * A row that passes the public gate: admin-approved AND toggled on for
     * the website. Internal review `status` is deliberately independent of
     * that gate, so it defaults to 'verified' here but isn't what makes the
     * row visible.
     */
    private function liveEntry(array $attributes = []): PropertyEntry
    {
        return PropertyEntry::create(array_merge([
            'field_officer_id' => $this->officer->id,
            'status'           => 'verified',
            'admin_status'     => 'approved',
            'show_on_website'  => true,
            'submitted_at'     => now(),
            'property_type'    => 'apartment_flat_studio',
            'city'             => 'Mumbai',
            'locality_broad_area' => 'Bandra West',
            'property_name'    => 'Test Residence',
        ], $attributes));
    }

    private function hiddenEntry(array $attributes = []): PropertyEntry
    {
        return PropertyEntry::create(array_merge([
            'field_officer_id' => $this->officer->id,
            'status'           => 'draft',
            'admin_status'     => null,
            'show_on_website'  => false,
            'property_type'    => 'apartment_flat_studio',
            'city'             => 'Hiddenville',
            'property_name'    => 'Should Not Appear',
        ], $attributes));
    }

    /** @test */
    public function listing_query_only_ever_touches_property_entries(): void
    {
        $this->liveEntry();

        DB::enableQueryLog();
        $this->get(route('properties.index'))->assertStatus(200);
        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        $listingQueries = collect($queries)->pluck('query')
            ->filter(fn ($q) => str_contains($q, 'property_entries'));

        $this->assertTrue($listingQueries->isNotEmpty(), 'Expected at least one property_entries query.');

        // The legacy `properties` table must never be read for this page.
        // Guard against the bare table name only, so `property_entries`
        // and `property_entry_photos` don't trip a naive substring check.
        foreach ($queries as $q) {
            $this->assertDoesNotMatchRegularExpression(
                '/\bfrom\s+`?properties`?\b/i',
                $q['query'],
                'Public listing must not read the legacy `properties` table.'
            );
        }
    }

    /** @test */
    public function draft_and_rejected_rows_never_appear_publicly(): void
    {
        $this->liveEntry(['property_name' => 'Live Listing']);
        $this->hiddenEntry(['property_name' => 'Draft Listing', 'status' => 'draft']);
        $this->hiddenEntry(['property_name' => 'Rejected Listing', 'status' => 'rejected']);

        $response = $this->get(route('properties.index'));

        $response->assertSee('Live Listing');
        $response->assertDontSee('Draft Listing');
        $response->assertDontSee('Rejected Listing');
    }

    /** @test */
    public function an_approved_row_that_is_toggled_off_is_not_public(): void
    {
        $this->liveEntry(['property_name' => 'Visible One']);
        $this->liveEntry(['property_name' => 'Toggled Off', 'show_on_website' => false]);

        $response = $this->get(route('properties.index'));

        $response->assertSee('Visible One');
        $response->assertDontSee('Toggled Off');
    }

    /** @test */
    public function internal_and_verified_tier_fields_are_never_rendered_for_anonymous_visitors(): void
    {
        $this->liveEntry([
            // INTERNAL tier — must never reach the public page
            'owner_contact_name'  => 'SecretOwnerName',
            'owner_contact_phone' => '9876500001',
            'submitter_phone'     => '9876500002',
            'submitter_email'     => 'secret.submitter@example.com',
            'expected_rent'       => 123456,
            'expected_sale_price' => 98765432,
            'negotiable_floor_price' => 111111,
            'owner_flexibility_notes' => 'SecretFlexibilityNote',
            'remarks'             => 'SecretInternalRemark',
            'name_full_address'   => 'Flat 9, SecretStreet Exact Address',
            // WEBSITE tier — allowed, used to prove the card still renders
            'rent_range_band'     => 'From Rs 1.2 Lac',
        ]);

        $response = $this->get(route('properties.index'));
        $response->assertStatus(200);

        foreach ([
            'SecretOwnerName', '9876500001', '9876500002', 'secret.submitter@example.com',
            '123456', '98765432', '111111', 'SecretFlexibilityNote', 'SecretInternalRemark',
            'SecretStreet',
        ] as $leak) {
            $response->assertDontSee($leak);
        }

        // The WEBSITE-tier band is what should be shown instead of exact price.
        $response->assertSee('From Rs 1.2 Lac');
    }

    /** @test */
    public function city_filter_narrows_results(): void
    {
        $this->liveEntry(['property_name' => 'Mumbai Flat', 'city' => 'Mumbai']);
        $this->liveEntry(['property_name' => 'Pune Flat', 'city' => 'Pune']);

        $response = $this->get(route('properties.index', ['city' => 'Mumbai']));

        $response->assertSee('Mumbai Flat');
        $response->assertDontSee('Pune Flat');
    }

    /** @test */
    public function locality_filter_narrows_results(): void
    {
        $this->liveEntry(['property_name' => 'Bandra Unit', 'locality_broad_area' => 'Bandra West']);
        $this->liveEntry(['property_name' => 'Andheri Unit', 'locality_broad_area' => 'Andheri East']);

        $response = $this->get(route('properties.index', ['locality' => 'Bandra West']));

        $response->assertSee('Bandra Unit');
        $response->assertDontSee('Andheri Unit');
    }

    /** @test */
    public function property_type_filter_narrows_across_three_types(): void
    {
        $this->liveEntry(['property_name' => 'Apartment Row', 'property_type' => 'apartment_flat_studio']);
        $this->liveEntry(['property_name' => 'Warehouse Row', 'property_type' => 'warehouse', 'city' => null, 'nearest_city' => 'Gurgaon']);
        $this->liveEntry(['property_name' => 'Office Row', 'property_type' => 'office_space']);

        $apartments = $this->get(route('properties.index', ['property_type_slug' => 'apartment_flat_studio']));
        $apartments->assertSee('Apartment Row');
        $apartments->assertDontSee('Warehouse Row');
        $apartments->assertDontSee('Office Row');

        $warehouses = $this->get(route('properties.index', ['property_type_slug' => 'warehouse']));
        $warehouses->assertSee('Warehouse Row');
        $warehouses->assertDontSee('Apartment Row');

        $offices = $this->get(route('properties.index', ['property_type_slug' => 'office_space']));
        $offices->assertSee('Office Row');
        $offices->assertDontSee('Apartment Row');
    }

    /** @test */
    public function construction_status_filter_narrows_results(): void
    {
        $this->liveEntry(['property_name' => 'Ready Unit', 'construction_listing_status' => 'Ready to Move']);
        $this->liveEntry(['property_name' => 'UC Unit', 'construction_listing_status' => 'Under Construction']);

        $response = $this->get(route('properties.index', ['construction_status' => 'Ready to Move']));

        $response->assertSee('Ready Unit');
        $response->assertDontSee('UC Unit');
    }

    /** @test */
    public function builder_filter_narrows_results(): void
    {
        $this->liveEntry(['property_name' => 'Lodha Unit', 'builder_developer_name' => 'Lodha Group']);
        $this->liveEntry(['property_name' => 'Godrej Unit', 'builder_developer_name' => 'Godrej Properties']);

        $response = $this->get(route('properties.index', ['builder' => 'Lodha Group']));

        $response->assertSee('Lodha Unit');
        $response->assertDontSee('Godrej Unit');
    }

    /** @test */
    public function a_warehouse_card_never_renders_a_bhk_configuration_field(): void
    {
        // `configuration` is apartment-only. Even if a warehouse row somehow
        // carries a stale value in that column, the card must not print it —
        // this is exactly the "Delhi • 4+ BHK • Warehouse" bug.
        $this->liveEntry([
            'property_name'  => 'Big Shed',
            'property_type'  => 'warehouse',
            'city'           => null,
            'nearest_city'   => 'Delhi',
            'facility_type'  => 'Industrial Shed',
            'configuration'  => '4+ BHK',
        ]);

        $response = $this->get(route('properties.index'));

        $response->assertSee('Big Shed');
        $response->assertSee('Industrial Shed');
        $response->assertDontSee('4+ BHK');
    }

    /** @test */
    public function an_apartment_card_never_renders_warehouse_only_amenities(): void
    {
        $this->liveEntry([
            'property_name'         => 'City Apartment',
            'property_type'         => 'apartment_flat_studio',
            'configuration'         => '2 BHK',
            'amenities_checklist'   => ['Gym', 'Pool'],
            // Warehouse-only columns — must not surface on an apartment card
            'dock_door_count'       => 12,
            'clear_height_highest'  => 40,
            'power_sanctioned_kva'  => 500,
        ]);

        $response = $this->get(route('properties.index'));

        $response->assertSee('City Apartment');
        $response->assertSee('2 BHK');
        $response->assertSee('Gym');
        $response->assertDontSee('Dock Doors');
        $response->assertDontSee('KVA Power');
        $response->assertDontSee('ft Height');
    }

    /** @test */
    public function filter_dropdowns_never_offer_a_value_with_zero_live_rows(): void
    {
        $this->liveEntry([
            'property_name'               => 'Live Row',
            'city'                        => 'Mumbai',
            'locality_broad_area'         => 'Bandra West',
            'builder_developer_name'      => 'Lodha Group',
            'construction_listing_status' => 'Ready to Move',
        ]);

        // Values that exist only on non-public rows must not reach any dropdown.
        $this->hiddenEntry([
            'property_name'               => 'Hidden Row',
            'city'                        => 'GhostCity',
            'locality_broad_area'         => 'GhostLocality',
            'builder_developer_name'      => 'GhostBuilder',
            'construction_listing_status' => 'GhostStatus',
        ]);

        $response = $this->get(route('properties.index'));

        $response->assertSee('Mumbai');
        $response->assertDontSee('GhostCity');
    }
}
