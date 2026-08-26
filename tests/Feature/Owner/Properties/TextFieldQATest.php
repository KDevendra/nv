<?php

namespace Tests\Feature\Owner\Properties;

use App\Models\PropertyEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TextFieldQATest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => 'owner',
        ]);
    }

    public function test_plain_text_fields_trim_whitespace_and_strip_xss_scripts(): void
    {
        $entry = PropertyEntry::create([
            'property_type' => 'house_villa_farmhouse',
            'field_officer_id' => $this->user->id,
            'status' => 'submitted',
            'custom_fields' => json_encode([
                'submitter_full_name' => '   John Doe   ',
                'owner_full_name' => '   Jane Smith   ',
                'locality_broad_area' => '   Civil Lines & Market — हिंदी   ',
                'full_address_house_plot_no_street' => '   House #12/A, Street 4   ',
                'field_officer_submitter_remarks' => '   <script>alert("XSS")</script> Verified OK   ',
            ]),
            'field_officer_name' => 'QA Officer',
            'field_verified' => false,
        ]);

        $this->assertEquals('John Doe', $entry->fieldValue('submitter_full_name'));
        $this->assertEquals('Jane Smith', $entry->fieldValue('owner_full_name'));
        $this->assertEquals('Civil Lines & Market — हिंदी', $entry->fieldValue('locality_broad_area'));
        $this->assertEquals('House #12/A, Street 4', $entry->fieldValue('full_address_house_plot_no_street'));
        $this->assertEquals('Verified OK', $entry->fieldValue('field_officer_submitter_remarks'));
    }

    public function test_text_fields_round_trip_cleanly_on_all_14_property_edit_pages(): void
    {
        $types = [
            'warehouse',
            'apartment_flat_studio',
            'house_villa_farmhouse',
            'builder_floor',
            'residential_plot_land',
            'service_apartment_pg',
            'office_space',
            'retail_shop_showroom',
            'sez_eou_stpi_unit',
            'factory_manufacturing_industrial',
            'commercial_institutional_land',
            'agricultural_farm_land',
            'multi_tenant_building',
            'hotel_resort_guesthouse_banquet',
        ];

        foreach ($types as $type) {
            $slug = str_replace('_', '-', $type);
            $entry = PropertyEntry::create([
                'property_type' => $type,
                'field_officer_id' => $this->user->id,
                'status' => 'submitted',
                'custom_fields' => json_encode([
                    'owner_full_name' => 'Test Owner Name',
                    'locality_broad_area' => 'Green Park Zone',
                ]),
                'field_officer_name' => 'QA Submitter',
                'field_verified' => false,
            ]);

            $url = ($type === 'warehouse') ? "/owner/properties/{$entry->id}/edit" : "/owner/properties/{$slug}/{$entry->id}/edit";
            $response = $this->actingAs($this->user)->get($url);
            if ($response->status() === 302) {
                $url = $response->headers->get('Location');
                $response = $this->actingAs($this->user)->get($url);
            }
            $response->assertStatus(200);
            $response->assertSee('Test Owner Name');
            $response->assertSee('Green Park Zone');
        }
    }
}
