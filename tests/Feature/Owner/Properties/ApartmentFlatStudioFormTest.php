<?php

namespace Tests\Feature\Owner\Properties;

use App\Models\PropertyEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApartmentFlatStudioFormTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->create([
            'role' => 'owner',
            'is_active' => true,
        ]);
    }

    /**
     * Every field required by the Excel spec's Section A-K "Mandatory = Yes"
     * column, with conditional branches (part_of_a_project_society,
     * construction_listing_status, availability, rera_registered, deal_type,
     * currently_rented_tenanted) all set to the branch that needs no further
     * fields, so this is the minimal fully-valid submit payload.
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'action' => 'submit',
            'submitter_full_name' => 'Rahul Sharma',
            'submitter_phone' => '9876543210',
            'submitter_email' => 'rahul@example.com',
            'submitter_role' => 'Owner',
            'owner_contact_name' => 'Rahul Sharma',
            'owner_contact_phone' => '9876543210',
            'city' => 'Mumbai',
            'locality_broad_area' => 'Bandra West',
            'name_full_address' => 'Flat 12B, Hill Road, Bandra West',
            'postal_address_pin' => '400050',
            'state' => 'Maharashtra',
            'part_of_a_project_society' => 'No',
            'unit_property_type' => 'Apartment',
            'configuration' => '2',
            'carpet_area' => 850,
            'floor_number' => 4,
            'number_of_floors' => 12,
            'no_of_bedrooms' => 2,
            'no_of_bathrooms' => 2,
            'furnishing_status' => 'Semi',
            'property_status' => 'New',
            'construction_listing_status' => 'Ready to Move',
            'availability' => 'Immediate',
            'ownership_type' => 'Freehold',
            'title_status' => 'Clear',
            'rera_registered' => 'No',
            'occupancy_certificate' => 'Received',
            'encumbrance_loan_on_property' => 'None',
            'lift_elevator' => 'Yes',
            'deal_type' => 'Sale',
            'expected_sale_price' => 9500000,
            'currently_rented_tenanted' => 'No',
            'remarks' => 'Well-maintained flat, ready to move in.',
        ], $overrides);
    }

    /** @test */
    public function final_submit_only_succeeds_when_every_required_field_is_present(): void
    {
        $response = $this->actingAs($this->owner)
            ->post(route('owner.properties.apartment-flat-studio.store'), $this->validPayload());

        $response->assertRedirect(route('owner.dashboard'));

        $this->assertDatabaseHas('property_entries', [
            'property_type' => 'apartment_flat_studio',
            'status' => 'submitted',
            'city' => 'Mumbai',
            'code' => 'ZI-RA-001',
        ]);
    }

    /** @test */
    public function submit_is_rejected_when_a_required_field_is_missing(): void
    {
        $payload = $this->validPayload();
        unset($payload['carpet_area']);

        $response = $this->actingAs($this->owner)
            ->post(route('owner.properties.apartment-flat-studio.store'), $payload);

        $response->assertSessionHasErrors(['carpet_area']);
        $this->assertDatabaseMissing('property_entries', ['property_type' => 'apartment_flat_studio']);
    }

    /** @test */
    public function save_draft_succeeds_with_only_partial_data(): void
    {
        $response = $this->actingAs($this->owner)
            ->post(route('owner.properties.apartment-flat-studio.store'), [
                'action' => 'draft',
                'submitter_full_name' => 'Rahul Sharma',
                'city' => 'Mumbai',
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('property_entries', [
            'property_type' => 'apartment_flat_studio',
            'status' => 'draft',
            'city' => 'Mumbai',
        ]);
    }

    /** @test */
    public function dropdown_rejects_an_out_of_list_value(): void
    {
        $response = $this->actingAs($this->owner)
            ->post(route('owner.properties.apartment-flat-studio.store'), $this->validPayload([
                'submitter_role' => 'Not A Real Role',
            ]));

        $response->assertSessionHasErrors(['submitter_role']);
        $this->assertDatabaseMissing('property_entries', ['property_type' => 'apartment_flat_studio']);
    }

    /** @test */
    public function multiselect_rejects_an_out_of_list_value(): void
    {
        $response = $this->actingAs($this->owner)
            ->post(route('owner.properties.apartment-flat-studio.store'), $this->validPayload([
                'additional_rooms' => ['Pooja', 'Not A Real Room'],
            ]));

        $response->assertSessionHasErrors(['additional_rooms.1']);
    }

    /** @test */
    public function deal_type_sale_requires_expected_sale_price_but_not_expected_rent(): void
    {
        $payload = $this->validPayload();
        unset($payload['expected_sale_price']);

        $response = $this->actingAs($this->owner)
            ->post(route('owner.properties.apartment-flat-studio.store'), $payload);

        $response->assertSessionHasErrors(['expected_sale_price']);
    }

    /** @test */
    public function deal_type_rent_requires_expected_rent_and_security_deposit_months(): void
    {
        $payload = $this->validPayload([
            'deal_type' => 'Rent',
        ]);
        unset($payload['expected_sale_price']);

        $response = $this->actingAs($this->owner)
            ->post(route('owner.properties.apartment-flat-studio.store'), $payload);

        $response->assertSessionHasErrors(['expected_rent', 'security_deposit_months']);
    }

    /** @test */
    public function rera_registered_yes_requires_rera_registration_id(): void
    {
        $payload = $this->validPayload([
            'rera_registered' => 'Yes',
        ]);

        $response = $this->actingAs($this->owner)
            ->post(route('owner.properties.apartment-flat-studio.store'), $payload);

        $response->assertSessionHasErrors(['rera_registration_id']);
    }

    /** @test */
    public function part_of_project_yes_requires_project_details(): void
    {
        $payload = $this->validPayload([
            'part_of_a_project_society' => 'Yes',
        ]);

        $response = $this->actingAs($this->owner)
            ->post(route('owner.properties.apartment-flat-studio.store'), $payload);

        $response->assertSessionHasErrors(['project_society_name', 'project_rera_id', 'developer_builder_name']);
    }

    /** @test */
    public function update_persists_edited_fields_and_keeps_submitted_status(): void
    {
        $entry = PropertyEntry::create(array_merge($this->validPayload(), [
            'property_type' => 'apartment_flat_studio',
            'field_officer_id' => $this->owner->id,
            'status' => 'draft',
        ]));

        $payload = $this->validPayload([
            'city' => 'Pune',
        ]);

        $response = $this->actingAs($this->owner)
            ->put(route('owner.properties.apartment-flat-studio.update', $entry), $payload);

        $response->assertRedirect(route('owner.dashboard'));
        $this->assertDatabaseHas('property_entries', [
            'id' => $entry->id,
            'city' => 'Pune',
            'status' => 'submitted',
        ]);
    }
}
