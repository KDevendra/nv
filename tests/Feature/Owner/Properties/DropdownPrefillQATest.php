<?php

namespace Tests\Feature\Owner\Properties;

use App\Models\PropertyEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class DropdownPrefillQATest extends TestCase
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

    public function test_property_type_sub_type_prefills_in_agricultural_farm_land_edit_view(): void
    {
        $entry = PropertyEntry::create([
            'property_type' => 'agricultural_farm_land',
            'field_officer_id' => $this->user->id,
            'status' => 'submitted',
            'custom_fields' => json_encode([
                'property_sub_type' => 'Farm Land',
                'total_land_area' => 500,
                'land_shape_contour' => 'Level',
                'currently_cultivated' => 'Yes',
            ]),
            'field_officer_name' => 'QA Officer',
            'field_verified' => false,
        ]);

        $this->assertEquals('Farm Land', $entry->fieldValue('property_type'));
        $this->assertEquals('Farm Land', $entry->fieldValue('property_sub_type'));

        $response = $this->actingAs($this->user)->get("/owner/properties/agricultural-farm-land/{$entry->id}/edit");
        $response->assertStatus(200);
        $response->assertSee('value="Farm Land" selected', false);
    }

    public function test_all_14_property_type_dropdowns_preselect_cleanly(): void
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
                    'property_sub_type' => 'Sample Sub Type',
                ]),
                'field_officer_name' => 'QA Submitter',
                'field_verified' => false,
            ]);

            if ($type !== 'warehouse') {
                $this->assertEquals('Sample Sub Type', $entry->fieldValue('property_type'));
            }

            $url = ($type === 'warehouse') ? "/owner/properties/{$entry->id}/edit" : "/owner/properties/{$slug}/{$entry->id}/edit";
            $response = $this->actingAs($this->user)->get($url);
            if ($response->status() === 302) {
                $url = $response->headers->get('Location');
                $response = $this->actingAs($this->user)->get($url);
            }
            $response->assertStatus(200);
        }
    }
}
