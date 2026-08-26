<?php

namespace Tests\Feature;

use App\Models\PropertyEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyTypeHubTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_property_type_select_hub(): void
    {
        $response = $this->get(route('owner.properties.select-type'));

        $this->assertTrue(
            $response->isRedirect() || $response->isForbidden(),
            'Guest should be redirected to login or forbidden'
        );
    }

    public function test_authenticated_owner_can_access_property_type_select_hub(): void
    {
        $owner = User::factory()->create([
            'role' => 'owner',
        ]);

        $response = $this->actingAs($owner)->get(route('owner.properties.select-type'));

        $response->assertStatus(200);
        $response->assertSee('Choose Property Type');

        // Verify warehouse card link
        $response->assertSee(route('owner.properties.create'), false);

        // Verify all 13 new type card links
        $types = config('property_types.types');
        foreach ($types as $key => $type) {
            if ($key === 'warehouse') {
                continue;
            }
            $expectedUrl = route('owner.properties.create-type', ['type' => $type['slug']]);
            $response->assertSee($expectedUrl, false);
        }
    }

    public function test_property_entry_default_code_prefix_for_warehouse_is_unchanged(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);

        // Create without property_type (null)
        $entry1 = PropertyEntry::create([
            'field_officer_id' => $owner->id,
        ]);

        $this->assertStringStartsWith('ZI-WH-', $entry1->code);
        $this->assertEquals('ZI-WH-001', $entry1->code);

        // Create with explicit property_type = 'warehouse'
        $entry2 = PropertyEntry::create([
            'field_officer_id' => $owner->id,
            'property_type'    => 'warehouse',
        ]);

        $this->assertStringStartsWith('ZI-WH-', $entry2->code);
        $this->assertEquals('ZI-WH-002', $entry2->code);
    }

    public function test_property_entry_generates_independent_sequences_for_different_types(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);

        // Warehouse entry
        $wh = PropertyEntry::create([
            'field_officer_id' => $owner->id,
            'property_type'    => 'warehouse',
        ]);
        $this->assertEquals('ZI-WH-001', $wh->code);

        // Office space entries (ZI-CO-)
        $off1 = PropertyEntry::create([
            'field_officer_id' => $owner->id,
            'property_type'    => 'office_space',
        ]);
        $this->assertEquals('ZI-CO-001', $off1->code);

        $off2 = PropertyEntry::create([
            'field_officer_id' => $owner->id,
            'property_type'    => 'office_space',
        ]);
        $this->assertEquals('ZI-CO-002', $off2->code);

        // Apartment entry (ZI-RA-)
        $apt1 = PropertyEntry::create([
            'field_officer_id' => $owner->id,
            'property_type'    => 'apartment_flat_studio',
        ]);
        $this->assertEquals('ZI-RA-001', $apt1->code);

        // Verify scopeOfType
        $this->assertEquals(2, PropertyEntry::ofType('office_space')->count());
        $this->assertEquals(1, PropertyEntry::ofType('apartment_flat_studio')->count());
        $this->assertEquals(1, PropertyEntry::ofType('warehouse')->count());
    }
}
