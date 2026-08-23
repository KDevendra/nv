<?php

namespace Tests\Feature\Owner\Properties;

use App\Models\PropertyEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Factory / Manufacturing / Industrial currently has no dedicated Form Request — its owner controller
 * mass-assigns any submitted key that matches PropertyEntry::$fillable and
 * routes everything else into the custom_fields JSON blob (see the
 * property-forms audit). These tests lock in that actual current behaviour,
 * including empty_submit_currently_succeeds_because_no_validation_exists_yet,
 * which documents the still-open validation gap rather than pretending it's
 * closed — it should start failing (and be rewritten to assert a rejection)
 * once a dedicated Form Request lands for this type.
 */
class FactoryManufacturingIndustrialFormTest extends TestCase
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
    public function owner_can_access_create_form(): void
    {
        $response = $this->actingAs($this->owner)->get(route('owner.properties.factory-manufacturing-industrial.create'));
        $response->assertStatus(200);
    }

    /** @test */
    public function full_submit_creates_entry_with_correct_type_and_code_prefix(): void
    {
        $response = $this->actingAs($this->owner)->post(route('owner.properties.factory-manufacturing-industrial.store'), [
            'action'              => 'submit',
            'submitter_full_name' => 'Test Submitter',
            'submitter_phone'     => '9876543210',
            'submitter_email'     => 'submitter@example.com',
            'submitter_role'      => 'Owner',
            'city'                => 'Mumbai',
            'locality_broad_area' => 'Andheri East',
            'state'               => 'Maharashtra',
            'crane_gantry_provision'    => 'Yes',
        ]);

        $response->assertRedirect(route('owner.properties.index'));

        $entry = PropertyEntry::where('property_type', 'factory_manufacturing_industrial')->first();
        $this->assertNotNull($entry);
        $this->assertSame('ZI-CF-001', $entry->code);
        $this->assertSame('submitted', $entry->status);
        $this->assertSame('Mumbai', $entry->city);
    }

    /** @test */
    public function type_specific_field_not_in_fillable_is_stored_in_custom_fields(): void
    {
        $this->actingAs($this->owner)->post(route('owner.properties.factory-manufacturing-industrial.store'), [
            'action'               => 'submit',
            'submitter_full_name'  => 'Test Submitter',
            'city'                 => 'Pune',
            'crane_gantry_provision'     => 'Yes',
        ]);

        $entry = PropertyEntry::where('property_type', 'factory_manufacturing_industrial')->first();
        $this->assertNotNull($entry);
        $custom = json_decode($entry->custom_fields ?? '{}', true);
        $this->assertSame('Yes', $custom['crane_gantry_provision'] ?? null);
    }

    /** @test */
    public function save_draft_succeeds_with_only_partial_data(): void
    {
        $response = $this->actingAs($this->owner)->post(route('owner.properties.factory-manufacturing-industrial.store'), [
            'action'              => 'draft',
            'submitter_full_name' => 'Draft Submitter',
            'city'                => 'Delhi',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('property_entries', [
            'property_type' => 'factory_manufacturing_industrial',
            'status'        => 'draft',
            'city'          => 'Delhi',
        ]);
    }

    /** @test */
    public function empty_submit_currently_succeeds_because_no_validation_exists_yet(): void
    {
        $response = $this->actingAs($this->owner)->post(route('owner.properties.factory-manufacturing-industrial.store'), [
            'action' => 'submit',
        ]);

        $response->assertRedirect(route('owner.properties.index'));
        $this->assertDatabaseHas('property_entries', [
            'property_type' => 'factory_manufacturing_industrial',
            'status'        => 'submitted',
        ]);
    }

    /** @test */
    public function update_persists_edited_fields_while_entry_is_a_draft(): void
    {
        $entry = PropertyEntry::create([
            'property_type'       => 'factory_manufacturing_industrial',
            'field_officer_id'    => $this->owner->id,
            'status'              => 'draft',
            'submitter_full_name' => 'Original Submitter',
            'city'                => 'Chennai',
        ]);

        $response = $this->actingAs($this->owner)->put(route('owner.properties.factory-manufacturing-industrial.update', $entry), [
            'action' => 'draft',
            'city'   => 'Bengaluru',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('property_entries', [
            'id'   => $entry->id,
            'city' => 'Bengaluru',
        ]);
    }
}
