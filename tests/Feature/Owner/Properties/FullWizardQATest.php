<?php

namespace Tests\Feature\Owner\Properties;

use App\Models\Permission;
use App\Models\PropertyEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FullWizardQATest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->owner = User::factory()->create(['role' => 'owner', 'name' => 'QA Owner']);
        $this->admin = User::factory()->create(['role' => 'admin', 'name' => 'QA Admin']);

        $perm = Permission::firstOrCreate(['name' => 'properties.view'], ['module' => 'properties', 'action' => 'view']);
        $perm2 = Permission::firstOrCreate(['name' => 'dashboard.view'], ['module' => 'dashboard', 'action' => 'view']);
        DB::table('role_permissions')->insertOrIgnore([
            ['role' => 'admin', 'permission_id' => $perm->id],
            ['role' => 'admin', 'permission_id' => $perm2->id],
        ]);
    }

    /** @test */
    public function qa_type_1_warehouse(): void
    {
        $response = $this->actingAs($this->owner)->get(route('owner.properties.create'));
        $response->assertStatus(200);

        $draftResponse = $this->actingAs($this->owner)->post(route('owner.properties.store'), [
            'action' => 'draft',
            'property_type' => 'warehouse',
            'city' => 'Raipur',
            'pin_code' => '493111',
            'expected_rent' => 50000,
        ]);
        $draftResponse->assertSessionHasNoErrors();
        $entry = PropertyEntry::latest('id')->first();
        $this->assertEquals('draft', $entry->status);
        $this->assertStringStartsWith('ZI-WH-', $entry->code);

        $submitResponse = $this->actingAs($this->owner)->post(route('owner.properties.store'), [
            'action' => 'submit',
            'property_type' => 'warehouse',
            'owner_full_name' => 'Devendra Verma',
            'owner_contact_number' => '9876543210',
            'city' => 'Raipur',
            'pin_code' => '493111',
            'full_address_house_plot_no_street' => 'Plot 42, Industrial Area',
            'carpet_area_sq_ft' => 5000,
            'expected_rent' => 50000,
            'security_deposit_months' => '3',
            'availability' => 'Immediate',
        ]);
        $submitResponse->assertSessionHasNoErrors();
        $submitted = PropertyEntry::latest('id')->first();
        $this->assertEquals('submitted', $submitted->status);
        $this->assertStringStartsWith('ZI-WH-', $submitted->code);

        $this->actingAs($this->owner)->get(route('owner.properties.show', $submitted))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.property-entry-report.show', $submitted))->assertStatus(200);
    }

    /** @test */
    public function qa_type_2_apartment_flat_studio(): void
    {
        $slug = 'apartment-flat-studio';
        $createRoute = "owner.properties.{$slug}.create";
        $storeRoute = "owner.properties.{$slug}.store";

        $response = $this->actingAs($this->owner)->get(route($createRoute));
        $response->assertStatus(200);
        $response->assertSee('window.__wizTabGateInstalled', false);

        $draftResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'draft',
            'property_type' => 'apartment_flat_studio',
            'city' => 'Raipur',
            'pin_code' => '493111',
        ]);
        $draftResponse->assertSessionHasNoErrors();
        $entry = PropertyEntry::latest('id')->first();
        $this->assertEquals('draft', $entry->status);
        $this->assertStringStartsWith('ZI-RA-', $entry->code);

        $submitResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'submit',
            'property_type' => 'apartment_flat_studio',
            'deal_type' => 'Rent',
            'submitter_full_name' => 'Devendra Verma',
            'submitter_phone' => '9876543210',
            'submitter_email' => 'devendra@example.com',
            'submitter_role' => 'Owner',
            'owner_contact_name' => 'Devendra Verma',
            'owner_contact_phone' => '9876543210',
            'city' => 'Raipur',
            'locality_broad_area' => 'Telibandha',
            'name_full_address' => 'Flat 402, Green Towers',
            'postal_address_pin' => '493111',
            'state' => 'Chattisgarh',
            'part_of_a_project_society' => 'No',
            'unit_property_type' => 'Apartment',
            'configuration' => '2',
            'carpet_area' => 1200,
            'floor_number' => 4,
            'number_of_floors' => 10,
            'no_of_bedrooms' => 2,
            'no_of_bathrooms' => 2,
            'furnishing_status' => 'Semi',
            'property_status' => 'Resale',
            'construction_listing_status' => 'Ready to Move',
            'ownership_type' => 'Freehold',
            'title_status' => 'Clear',
            'rera_registered' => 'No',
            'occupancy_certificate' => 'Received',
            'encumbrance_loan_on_property' => 'None',
            'lift_elevator' => 'Yes',
            'currently_rented_tenanted' => 'No',
            'remarks' => 'Good condition flat',
            'expected_rent' => 25000,
            'security_deposit_months' => '2',
            'availability' => 'Immediate',
        ]);
        $submitResponse->assertSessionHasNoErrors();
        $submitted = PropertyEntry::latest('id')->first();
        $this->assertEquals('submitted', $submitted->status);
        $this->assertStringStartsWith('ZI-RA-', $submitted->code);

        $this->actingAs($this->owner)->get(route('owner.properties.show', $submitted))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.property-entry-report.show', $submitted))->assertStatus(200);
    }

    /** @test */
    public function qa_type_3_house_villa_farmhouse(): void
    {
        $slug = 'house-villa-farmhouse';
        $createRoute = "owner.properties.{$slug}.create";
        $storeRoute = "owner.properties.{$slug}.store";

        $response = $this->actingAs($this->owner)->get(route($createRoute));
        $response->assertStatus(200);
        $response->assertSee('window.__wizTabGateInstalled', false);

        $draftResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'draft',
            'property_type' => 'house_villa_farmhouse',
            'city' => 'Raipur',
        ]);
        $draftResponse->assertSessionHasNoErrors();
        $entry = PropertyEntry::latest('id')->first();
        $this->assertEquals('draft', $entry->status);
        $this->assertStringStartsWith('ZI-RH-', $entry->code);

        $submitResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'submit',
            'property_type' => 'house_villa_farmhouse',
            'deal_type' => 'Rent',
            'owner_full_name' => 'Devendra Verma',
            'owner_contact_number' => '9876543210',
            'city' => 'Raipur',
            'pin_code' => '493111',
            'full_address_house_plot_no_street' => 'Villa 12, Royal Palms',
            'carpet_area_sq_ft' => 2500,
            'expected_rent' => 60000,
            'security_deposit_months' => '3',
            'availability' => 'Immediate',
        ]);
        $submitResponse->assertSessionHasNoErrors();
        $submitted = PropertyEntry::latest('id')->first();
        $this->assertEquals('submitted', $submitted->status);
        $this->assertStringStartsWith('ZI-RH-', $submitted->code);

        $this->actingAs($this->owner)->get(route('owner.properties.show', $submitted))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.property-entry-report.show', $submitted))->assertStatus(200);
    }

    /** @test */
    public function qa_type_4_builder_floor(): void
    {
        $slug = 'builder-floor';
        $createRoute = "owner.properties.{$slug}.create";
        $storeRoute = "owner.properties.{$slug}.store";

        $response = $this->actingAs($this->owner)->get(route($createRoute));
        $response->assertStatus(200);
        $response->assertSee('window.__wizTabGateInstalled', false);

        $draftResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'draft',
            'property_type' => 'builder_floor',
            'city' => 'Raipur',
        ]);
        $draftResponse->assertSessionHasNoErrors();
        $entry = PropertyEntry::latest('id')->first();
        $this->assertEquals('draft', $entry->status);
        $this->assertStringStartsWith('ZI-RB-', $entry->code);

        $submitResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'submit',
            'property_type' => 'builder_floor',
            'deal_type' => 'Rent',
            'owner_full_name' => 'Devendra Verma',
            'owner_contact_number' => '9876543210',
            'city' => 'Raipur',
            'pin_code' => '493111',
            'full_address_house_plot_no_street' => '2nd Floor, Block B',
            'carpet_area_sq_ft' => 1500,
            'expected_rent' => 30000,
            'security_deposit_months' => '2',
            'availability' => 'Immediate',
        ]);
        $submitResponse->assertSessionHasNoErrors();
        $submitted = PropertyEntry::latest('id')->first();
        $this->assertEquals('submitted', $submitted->status);
        $this->assertStringStartsWith('ZI-RB-', $submitted->code);

        $this->actingAs($this->owner)->get(route('owner.properties.show', $submitted))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.property-entry-report.show', $submitted))->assertStatus(200);
    }

    /** @test */
    public function qa_type_5_residential_plot_land(): void
    {
        $slug = 'residential-plot-land';
        $createRoute = "owner.properties.{$slug}.create";
        $storeRoute = "owner.properties.{$slug}.store";

        $response = $this->actingAs($this->owner)->get(route($createRoute));
        $response->assertStatus(200);

        $draftResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'draft',
            'property_type' => 'residential_plot_land',
            'city' => 'Raipur',
        ]);
        $draftResponse->assertSessionHasNoErrors();
        $entry = PropertyEntry::latest('id')->first();
        $this->assertEquals('draft', $entry->status);
        $this->assertStringStartsWith('ZI-RP-', $entry->code);

        $submitResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'submit',
            'property_type' => 'residential_plot_land',
            'deal_type' => 'Sale',
            'owner_full_name' => 'Devendra Verma',
            'owner_contact_number' => '9876543210',
            'city' => 'Raipur',
            'pin_code' => '493111',
            'full_address_house_plot_no_street' => 'Plot 88, Sunrise Enclave',
            'plot_area' => 2400,
            'expected_sale_price' => 4500000,
        ]);
        $submitResponse->assertSessionHasNoErrors();
        $submitted = PropertyEntry::latest('id')->first();
        $this->assertEquals('submitted', $submitted->status);
        $this->assertStringStartsWith('ZI-RP-', $submitted->code);

        $this->actingAs($this->owner)->get(route('owner.properties.show', $submitted))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.property-entry-report.show', $submitted))->assertStatus(200);
    }

    /** @test */
    public function qa_type_6_service_apartment_pg(): void
    {
        $slug = 'service-apartment-pg';
        $createRoute = "owner.properties.{$slug}.create";
        $storeRoute = "owner.properties.{$slug}.store";

        $response = $this->actingAs($this->owner)->get(route($createRoute));
        $response->assertStatus(200);

        $draftResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'draft',
            'property_type' => 'service_apartment_pg',
            'city' => 'Raipur',
        ]);
        $draftResponse->assertSessionHasNoErrors();
        $entry = PropertyEntry::latest('id')->first();
        $this->assertEquals('draft', $entry->status);
        $this->assertStringStartsWith('ZI-RS-', $entry->code);

        $submitResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'submit',
            'property_type' => 'service_apartment_pg',
            'deal_type' => 'Rent',
            'owner_full_name' => 'Devendra Verma',
            'owner_contact_number' => '9876543210',
            'city' => 'Raipur',
            'pin_code' => '493111',
            'full_address_house_plot_no_street' => 'PG House 5, Civil Lines',
            'expected_rent' => 8000,
            'security_deposit_months' => '1',
            'availability' => 'Immediate',
        ]);
        $submitResponse->assertSessionHasNoErrors();
        $submitted = PropertyEntry::latest('id')->first();
        $this->assertEquals('submitted', $submitted->status);
        $this->assertStringStartsWith('ZI-RS-', $submitted->code);

        $this->actingAs($this->owner)->get(route('owner.properties.show', $submitted))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.property-entry-report.show', $submitted))->assertStatus(200);
    }

    /** @test */
    public function qa_type_7_office_space(): void
    {
        $slug = 'office-space';
        $createRoute = "owner.properties.{$slug}.create";
        $storeRoute = "owner.properties.{$slug}.store";

        $response = $this->actingAs($this->owner)->get(route($createRoute));
        $response->assertStatus(200);

        $draftResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'draft',
            'property_type' => 'office_space',
            'city' => 'Raipur',
        ]);
        $draftResponse->assertSessionHasNoErrors();
        $entry = PropertyEntry::latest('id')->first();
        $this->assertEquals('draft', $entry->status);
        $this->assertStringStartsWith('ZI-CO-', $entry->code);

        $submitResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'submit',
            'property_type' => 'office_space',
            'deal_type' => 'Rent',
            'owner_full_name' => 'Devendra Verma',
            'owner_contact_number' => '9876543210',
            'city' => 'Raipur',
            'pin_code' => '493111',
            'full_address_house_plot_no_street' => 'Office 301, Tech Park',
            'carpet_area_sq_ft' => 1800,
            'expected_rent' => 75000,
            'security_deposit_months' => '3',
            'availability' => 'Immediate',
        ]);
        $submitResponse->assertSessionHasNoErrors();
        $submitted = PropertyEntry::latest('id')->first();
        $this->assertEquals('submitted', $submitted->status);
        $this->assertStringStartsWith('ZI-CO-', $submitted->code);

        $this->actingAs($this->owner)->get(route('owner.properties.show', $submitted))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.property-entry-report.show', $submitted))->assertStatus(200);
    }

    /** @test */
    public function qa_type_8_retail_shop_showroom(): void
    {
        $slug = 'retail-shop-showroom';
        $createRoute = "owner.properties.{$slug}.create";
        $storeRoute = "owner.properties.{$slug}.store";

        $response = $this->actingAs($this->owner)->get(route($createRoute));
        $response->assertStatus(200);

        $draftResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'draft',
            'property_type' => 'retail_shop_showroom',
            'city' => 'Raipur',
        ]);
        $draftResponse->assertSessionHasNoErrors();
        $entry = PropertyEntry::latest('id')->first();
        $this->assertEquals('draft', $entry->status);
        $this->assertStringStartsWith('ZI-CR-', $entry->code);

        $submitResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'submit',
            'property_type' => 'retail_shop_showroom',
            'deal_type' => 'Rent',
            'owner_full_name' => 'Devendra Verma',
            'owner_contact_number' => '9876543210',
            'city' => 'Raipur',
            'pin_code' => '493111',
            'full_address_house_plot_no_street' => 'Shop 12, Main Market',
            'carpet_area_sq_ft' => 850,
            'expected_rent' => 45000,
            'security_deposit_months' => '3',
            'availability' => 'Immediate',
        ]);
        $submitResponse->assertSessionHasNoErrors();
        $submitted = PropertyEntry::latest('id')->first();
        $this->assertEquals('submitted', $submitted->status);
        $this->assertStringStartsWith('ZI-CR-', $submitted->code);

        $this->actingAs($this->owner)->get(route('owner.properties.show', $submitted))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.property-entry-report.show', $submitted))->assertStatus(200);
    }

    /** @test */
    public function qa_type_9_sez_eou_stpi_unit(): void
    {
        $slug = 'sez-eou-stpi-unit';
        $createRoute = "owner.properties.{$slug}.create";
        $storeRoute = "owner.properties.{$slug}.store";

        $response = $this->actingAs($this->owner)->get(route($createRoute));
        $response->assertStatus(200);

        $draftResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'draft',
            'property_type' => 'sez_eou_stpi_unit',
            'city' => 'Raipur',
        ]);
        $draftResponse->assertSessionHasNoErrors();
        $entry = PropertyEntry::latest('id')->first();
        $this->assertEquals('draft', $entry->status);
        $this->assertStringStartsWith('ZI-CS-', $entry->code);

        $submitResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'submit',
            'property_type' => 'sez_eou_stpi_unit',
            'deal_type' => 'Rent',
            'owner_full_name' => 'Devendra Verma',
            'owner_contact_number' => '9876543210',
            'city' => 'Raipur',
            'pin_code' => '493111',
            'full_address_house_plot_no_street' => 'Unit 4A, IT SEZ Tower',
            'carpet_area_sq_ft' => 3500,
            'expected_rent' => 120000,
            'security_deposit_months' => '6 months',
            'availability' => 'Immediate',
        ]);
        $submitResponse->assertSessionHasNoErrors();
        $submitted = PropertyEntry::latest('id')->first();
        $this->assertEquals('submitted', $submitted->status);
        $this->assertStringStartsWith('ZI-CS-', $submitted->code);

        $this->actingAs($this->owner)->get(route('owner.properties.show', $submitted))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.property-entry-report.show', $submitted))->assertStatus(200);
    }

    /** @test */
    public function qa_type_10_factory_manufacturing_industrial(): void
    {
        $slug = 'factory-manufacturing-industrial';
        $createRoute = "owner.properties.{$slug}.create";
        $storeRoute = "owner.properties.{$slug}.store";

        $response = $this->actingAs($this->owner)->get(route($createRoute));
        $response->assertStatus(200);

        $draftResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'draft',
            'property_type' => 'factory_manufacturing_industrial',
            'city' => 'Raipur',
        ]);
        $draftResponse->assertSessionHasNoErrors();
        $entry = PropertyEntry::latest('id')->first();
        $this->assertEquals('draft', $entry->status);
        $this->assertStringStartsWith('ZI-CF-', $entry->code);

        $submitResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'submit',
            'property_type' => 'factory_manufacturing_industrial',
            'deal_type' => 'Rent',
            'owner_full_name' => 'Devendra Verma',
            'owner_contact_number' => '9876543210',
            'city' => 'Raipur',
            'pin_code' => '493111',
            'full_address_house_plot_no_street' => 'Shed 15, Industrial Estate',
            'carpet_area_sq_ft' => 8000,
            'expected_rent' => 200000,
            'security_deposit_months' => '6 months',
            'availability' => 'Immediate',
        ]);
        $submitResponse->assertSessionHasNoErrors();
        $submitted = PropertyEntry::latest('id')->first();
        $this->assertEquals('submitted', $submitted->status);
        $this->assertStringStartsWith('ZI-CF-', $submitted->code);

        $this->actingAs($this->owner)->get(route('owner.properties.show', $submitted))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.property-entry-report.show', $submitted))->assertStatus(200);
    }

    /** @test */
    public function qa_type_11_commercial_institutional_land(): void
    {
        $slug = 'commercial-institutional-land';
        $createRoute = "owner.properties.{$slug}.create";
        $storeRoute = "owner.properties.{$slug}.store";

        $response = $this->actingAs($this->owner)->get(route($createRoute));
        $response->assertStatus(200);

        $draftResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'draft',
            'property_type' => 'commercial_institutional_land',
            'city' => 'Raipur',
        ]);
        $draftResponse->assertSessionHasNoErrors();
        $entry = PropertyEntry::latest('id')->first();
        $this->assertEquals('draft', $entry->status);
        $this->assertStringStartsWith('ZI-CL-', $entry->code);

        $submitResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'submit',
            'property_type' => 'commercial_institutional_land',
            'deal_type' => 'Sale',
            'owner_full_name' => 'Devendra Verma',
            'owner_contact_number' => '9876543210',
            'city' => 'Raipur',
            'pin_code' => '493111',
            'full_address_house_plot_no_street' => 'Plot 100, Institutional Zone',
            'plot_area' => 10000,
            'expected_sale_price' => 15000000,
        ]);
        $submitResponse->assertSessionHasNoErrors();
        $submitted = PropertyEntry::latest('id')->first();
        $this->assertEquals('submitted', $submitted->status);
        $this->assertStringStartsWith('ZI-CL-', $submitted->code);

        $this->actingAs($this->owner)->get(route('owner.properties.show', $submitted))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.property-entry-report.show', $submitted))->assertStatus(200);
    }

    /** @test */
    public function qa_type_12_agricultural_farm_land(): void
    {
        $slug = 'agricultural-farm-land';
        $createRoute = "owner.properties.{$slug}.create";
        $storeRoute = "owner.properties.{$slug}.store";

        $response = $this->actingAs($this->owner)->get(route($createRoute));
        $response->assertStatus(200);

        $draftResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'draft',
            'property_type' => 'agricultural_farm_land',
            'city' => 'Raipur',
        ]);
        $draftResponse->assertSessionHasNoErrors();
        $entry = PropertyEntry::latest('id')->first();
        $this->assertEquals('draft', $entry->status);
        $this->assertStringStartsWith('ZI-CA-', $entry->code);

        $submitResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'submit',
            'property_type' => 'agricultural_farm_land',
            'deal_type' => 'Sale',
            'owner_full_name' => 'Devendra Verma',
            'owner_contact_number' => '9876543210',
            'city' => 'Raipur',
            'pin_code' => '493111',
            'full_address_house_plot_no_street' => 'Khasra 405, Akoli Village',
            'plot_area' => 43560,
            'expected_sale_price' => 8000000,
        ]);
        $submitResponse->assertSessionHasNoErrors();
        $submitted = PropertyEntry::latest('id')->first();
        $this->assertEquals('submitted', $submitted->status);
        $this->assertStringStartsWith('ZI-CA-', $submitted->code);

        $this->actingAs($this->owner)->get(route('owner.properties.show', $submitted))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.property-entry-report.show', $submitted))->assertStatus(200);
    }

    /** @test */
    public function qa_type_13_multi_tenant_building(): void
    {
        $slug = 'multi-tenant-building';
        $createRoute = "owner.properties.{$slug}.create";
        $storeRoute = "owner.properties.{$slug}.store";

        $response = $this->actingAs($this->owner)->get(route($createRoute));
        $response->assertStatus(200);

        $draftResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'draft',
            'property_type' => 'multi_tenant_building',
            'city' => 'Raipur',
        ]);
        $draftResponse->assertSessionHasNoErrors();
        $entry = PropertyEntry::latest('id')->first();
        $this->assertEquals('draft', $entry->status);
        $this->assertStringStartsWith('ZI-CB-', $entry->code);

        $submitResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'submit',
            'property_type' => 'multi_tenant_building',
            'deal_type' => 'Rent',
            'owner_full_name' => 'Devendra Verma',
            'owner_contact_number' => '9876543210',
            'city' => 'Raipur',
            'pin_code' => '493111',
            'full_address_house_plot_no_street' => 'Zendo Towers, Commercial Hub',
            'carpet_area_sq_ft' => 12000,
            'expected_rent' => 400000,
            'security_deposit_months' => '6 months',
            'availability' => 'Immediate',
        ]);
        $submitResponse->assertSessionHasNoErrors();
        $submitted = PropertyEntry::latest('id')->first();
        $this->assertEquals('submitted', $submitted->status);
        $this->assertStringStartsWith('ZI-CB-', $submitted->code);

        $this->actingAs($this->owner)->get(route('owner.properties.show', $submitted))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.property-entry-report.show', $submitted))->assertStatus(200);
    }

    /** @test */
    public function qa_type_14_hotel_resort_guesthouse_banquet(): void
    {
        $slug = 'hotel-resort-guesthouse-banquet';
        $createRoute = "owner.properties.{$slug}.create";
        $storeRoute = "owner.properties.{$slug}.store";

        $response = $this->actingAs($this->owner)->get(route($createRoute));
        $response->assertStatus(200);

        $draftResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'draft',
            'property_type' => 'hotel_resort_guesthouse_banquet',
            'city' => 'Raipur',
        ]);
        $draftResponse->assertSessionHasNoErrors();
        $entry = PropertyEntry::latest('id')->first();
        $this->assertEquals('draft', $entry->status);
        $this->assertStringStartsWith('ZI-CH-', $entry->code);

        $submitResponse = $this->actingAs($this->owner)->post(route($storeRoute), [
            'action' => 'submit',
            'property_type' => 'hotel_resort_guesthouse_banquet',
            'deal_type' => 'Rent',
            'owner_full_name' => 'Devendra Verma',
            'owner_contact_number' => '9876543210',
            'city' => 'Raipur',
            'pin_code' => '493111',
            'full_address_house_plot_no_street' => 'Grand Palace Hotel, VIP Road',
            'carpet_area_sq_ft' => 15000,
            'expected_rent' => 500000,
            'security_deposit_months' => '6 months',
            'availability' => 'Immediate',
        ]);
        $submitResponse->assertSessionHasNoErrors();
        $submitted = PropertyEntry::latest('id')->first();
        $this->assertEquals('submitted', $submitted->status);
        $this->assertStringStartsWith('ZI-CH-', $submitted->code);

        $this->actingAs($this->owner)->get(route('owner.properties.show', $submitted))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.property-entry-report.show', $submitted))->assertStatus(200);
    }
}
