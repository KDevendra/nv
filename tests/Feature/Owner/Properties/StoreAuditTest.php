<?php

namespace Tests\Feature\Owner\Properties;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreAuditTest extends TestCase
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

    public function test_store_route_for_all_14_property_types(): void
    {
        $types = [
            'warehouse',
            'apartment-flat-studio',
            'house-villa-farmhouse',
            'builder-floor',
            'residential-plot-land',
            'service-apartment-pg',
            'office-space',
            'retail-shop-showroom',
            'sez-eou-stpi-unit',
            'factory-manufacturing-industrial',
            'commercial-institutional-land',
            'agricultural-farm-land',
            'multi-tenant-building',
            'hotel-resort-guesthouse-banquet',
        ];

        foreach ($types as $type) {
            $url = ($type === 'warehouse') ? '/owner/properties' : "/owner/properties/$type";

            $payload = [
                'property_type' => str_replace('-', '_', $type),
                'submitter_role' => 'Owner',
                'submitter_full_name' => 'QA Owner',
                'submitter_phone' => '9876543210',
                'submitter_email' => 'owner@example.com',
                'owner_full_name' => 'QA Owner',
                'owner_contact_number' => '9876543210',
                'full_address_house_plot_no_street' => '123 Main Road',
                'city' => 'Raipur',
                'state' => 'Chattisgarh',
                'pin_code' => '493111',
                'locality_broad_area' => 'Civil Lines',
                'action' => 'draft',
            ];

            $response = $this->actingAs($this->user)->post($url, $payload);
            
            // Output status and error if 500
            if ($response->status() === 500) {
                dump("TYPE $type RETURNED 500: " . $response->exception?->getMessage());
                dump($response->exception?->getTraceAsString());
            }

            $this->assertNotEquals(500, $response->status(), "Property type {$type} returned 500 error on store!");
        }
    }
}
