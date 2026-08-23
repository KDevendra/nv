<?php

namespace Tests\Feature\Owner\Properties;

use App\Models\User;
use App\Models\PropertyEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AllPropertyTypesFormsTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->create([
            'role'      => 'owner',
            'is_active' => true,
        ]);
    }

    /** @test */
    public function owner_can_access_select_property_type_hub(): void
    {
        $response = $this->actingAs($this->owner)->get(route('owner.properties.select-type'));
        $response->assertStatus(200);
        $response->assertSee('Choose Property Type');
    }

    /** @test */
    public function owner_can_access_all_13_property_type_create_forms(): void
    {
        $types = [
            'apartment-flat-studio'             => 'Apartment / Flat / Studio',
            'house-villa-farmhouse'             => 'House / Villa / Farmhouse',
            'builder-floor'                     => 'Builder Floor',
            'residential-plot-land'             => 'Plot / Land',
            'service-apartment-pg'              => 'Service Apartment / Co-living / PG',
            'office-space'                      => 'Commercial Office Space',
            'retail-shop-showroom'              => 'Retail Shop / Showroom',
            'sez-eou-stpi-unit'                 => 'SEZ / EOU / STPI Unit',
            'factory-manufacturing-industrial'  => 'Factory / Manufacturing / Industrial',
            'commercial-institutional-land'     => 'Commercial / Institutional Land',
            'agricultural-farm-land'            => 'Agricultural / Farm Land',
            'multi-tenant-building'             => 'Multi-Tenant Commercial Building',
            'hotel-resort-guesthouse-banquet'   => 'Hospitality — Hotel / Resort / Guest House',
        ];

        foreach ($types as $slug => $label) {
            $response = $this->actingAs($this->owner)->get(route("owner.properties.{$slug}.create"));
            $response->assertStatus(200);
        }
    }

    /** @test */
    public function owner_can_submit_listing_for_house_villa_farmhouse(): void
    {
        $payload = [
            'submitter_full_name'  => 'Rahul Sharma',
            'submitter_phone'      => '9876543210',
            'submitter_email'      => 'rahul@example.com',
            'submitter_role'       => 'Owner',
            'owner_contact_name'   => 'Rahul Sharma',
            'owner_contact_phone'  => '9876543210',
            'city'                 => 'Jaipur',
            'locality_broad_area'  => 'Vaishali Nagar',
            'name_full_address'    => 'Plot 42, Vaishali Nagar, Jaipur',
            'postal_address_pin'   => '302021',
            'state'                => 'Rajasthan',
            'plot_area'            => 2400,
            'built_up_area'        => 3200,
            'no_of_bedrooms'       => 4,
            'no_of_bathrooms'      => 4,
            'furnishing_status'    => 'Fully furnished',
            'property_status'      => 'New',
            'construction_listing_status' => 'Ready to Move',
            'availability'         => 'Immediate',
            'ownership_type'       => 'Freehold',
            'title_status'         => 'Clear',
            'rera_registered'      => 'No',
            'occupancy_certificate'=> 'Received',
            'encumbrance_loan_on_property' => 'None',
            'lift_elevator'        => 'No',
            'deal_type'            => 'Sale',
            'expected_sale_price'  => 17500000,
            'currently_rented_tenanted' => 'No',
            'remarks'              => 'Luxury independent villa in prime location',
            'inspection_submission_date' => date('Y-m-d'),
        ];

        $response = $this->actingAs($this->owner)->post(route('owner.properties.house-villa-farmhouse.store'), $payload);
        $response->assertRedirect(route('owner.properties.index'));

        $this->assertDatabaseHas('property_entries', [
            'property_type'        => 'house_villa_farmhouse',
            'submitter_full_name'  => 'Rahul Sharma',
            'city'                 => 'Jaipur',
            'code'                 => 'ZI-RH-001',
        ]);
    }

    /** @test */
    public function sequence_code_prefixes_are_isolated_for_different_property_types(): void
    {
        $villa = PropertyEntry::create([
            'property_type'       => 'house_villa_farmhouse',
            'field_officer_id'    => $this->owner->id,
            'submitter_full_name' => 'Villa Owner',
            'city'                => 'Goa',
            'status'              => 'submitted',
        ]);

        $office = PropertyEntry::create([
            'property_type'       => 'office_space',
            'field_officer_id'    => $this->owner->id,
            'submitter_full_name' => 'Office Owner',
            'city'                => 'Bengaluru',
            'status'              => 'submitted',
        ]);

        $this->assertEquals('ZI-RH-001', $villa->code);
        $this->assertEquals('ZI-CO-001', $office->code);
    }

    /** @test */
    public function owner_can_filter_properties_by_pill_tab_groups_and_types(): void
    {
        PropertyEntry::create([
            'property_type'       => 'house_villa_farmhouse',
            'field_officer_id'    => $this->owner->id,
            'submitter_full_name' => 'Villa Owner',
            'city'                => 'Jaipur',
            'nearest_city'        => 'Jaipur',
            'status'              => 'submitted',
        ]);

        PropertyEntry::create([
            'property_type'       => 'office_space',
            'field_officer_id'    => $this->owner->id,
            'submitter_full_name' => 'Office Owner',
            'city'                => 'Mumbai',
            'nearest_city'        => 'Mumbai',
            'status'              => 'submitted',
        ]);

        // Filter by Residential Group Pill
        $responseGroup = $this->actingAs($this->owner)->get(route('owner.properties.index', ['group' => 'residential']));
        $responseGroup->assertStatus(200);
        $responseGroup->assertSee('Jaipur');
        $responseGroup->assertDontSee('Mumbai');

        // Filter by Specific Type Pill
        $responseType = $this->actingAs($this->owner)->get(route('owner.properties.index', ['type' => 'office_space']));
        $responseType->assertStatus(200);
        $responseType->assertSee('Mumbai');
        $responseType->assertDontSee('Jaipur');
    }
}
