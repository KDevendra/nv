<?php

namespace Tests\Feature\Admin;

use App\Models\PropertyEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PropertyEntryReportShowTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $officer;

    protected function setUp(): void
    {
        parent::setUp();

        // This route is gated by the `permission` middleware on the
        // dashboard.view key, resolved from the role_permissions table and
        // cached per role. RefreshDatabase truncates those tables, so grant
        // it to admin (and deliberately not to field_officer) here.
        $permissionId = DB::table('permissions')->insertGetId([
            'name'       => 'dashboard.view',
            'module'     => 'dashboard',
            'action'     => 'view',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('role_permissions')->insert([
            'role'          => 'admin',
            'permission_id' => $permissionId,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        Cache::flush();

        $this->admin   = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $this->officer = User::factory()->create(['role' => 'field_officer', 'is_active' => true]);
    }

    private function entry(array $attributes = []): PropertyEntry
    {
        return PropertyEntry::create(array_merge([
            'field_officer_id' => $this->officer->id,
            'status'           => 'submitted',
            'submitted_at'     => now(),
        ], $attributes));
    }

    private function showPage(PropertyEntry $entry)
    {
        return $this->actingAs($this->admin)
            ->get(route('admin.property-entry-report.show', $entry));
    }

    /** @test */
    public function warehouse_entry_shows_warehouse_sections_and_labels(): void
    {
        // Legacy warehouse rows store a NULL property_type — the view must
        // still resolve them to the warehouse layout.
        $entry = $this->entry([
            'property_type' => null,
            'facility_type' => 'Industrial Shed',
        ]);

        $response = $this->showPage($entry);
        $response->assertStatus(200);

        $response->assertSee('A. Location &amp; Identification', false);
        $response->assertSee('C. Dock, Exit &amp; Width Details', false);
        $response->assertSee('I. Health &amp; Emergency Facilities Nearby', false);
        $response->assertSee('Facility Type');
        $response->assertSee('Total Dock Doors');
        $response->assertSee('Industrial Shed');
    }

    /** @test */
    public function apartment_entry_shows_its_own_sections_not_warehouse_ones(): void
    {
        $entry = $this->entry([
            'property_type' => 'apartment_flat_studio',
            'configuration' => '2 BHK',
        ]);

        $response = $this->showPage($entry);
        $response->assertStatus(200);

        // Its own Section A–K headers
        $response->assertSee('A. Submitter &amp; Owner Details', false);
        $response->assertSee('B2. Project / Society', false);
        $response->assertSee('C. Unit Configuration');
        $response->assertSee('C2. Transaction &amp; Possession', false);
        $response->assertSee('D. Legal &amp; Compliance', false);
        $response->assertSee('G2. Tenant Preferences &amp; Rental Terms', false);
        $response->assertSee('H. Investment / ROI', false);
        $response->assertSee('K. Team Remarks');

        // Warehouse-only sections and labels must NOT appear
        $response->assertDontSee('C. Dock, Exit &amp; Width Details', false);
        $response->assertDontSee('Total Dock Doors');
        $response->assertDontSee('Shed Width (ft)');
    }

    /** @test */
    public function service_apartment_pg_entry_shows_its_own_layout(): void
    {
        $entry = $this->entry(['property_type' => 'service_apartment_pg']);

        $response = $this->showPage($entry);
        $response->assertStatus(200);

        $response->assertSee('C. Unit &amp; Occupancy', false);
        $response->assertSee('Room / Occupancy Type');
        $response->assertSee('Gender Policy');

        $response->assertDontSee('Total Dock Doors');
        $response->assertDontSee('C. Unit Configuration');
    }

    /** @test */
    public function every_property_type_renders_its_own_first_section(): void
    {
        foreach (array_keys(config('property_entry_sections')) as $type) {
            $entry = $this->entry(['property_type' => $type]);
            $firstSection = array_key_first($entry->sectionMap());

            $this->showPage($entry)
                ->assertStatus(200)
                ->assertSee(e($firstSection), false);
        }
    }

    /** @test */
    public function a_null_field_still_renders_as_a_dash_rather_than_being_omitted(): void
    {
        $entry = $this->entry(['property_type' => null, 'facility_type' => null]);

        $response = $this->showPage($entry);

        // The label is present even though the value is empty...
        $response->assertSee('Facility Type');
        // ...and the empty value renders as an em dash.
        $response->assertSee('—', false);
    }

    /** @test */
    public function a_multiselect_field_renders_as_tags_not_raw_json(): void
    {
        $entry = $this->entry([
            'property_type'       => 'apartment_flat_studio',
            'amenities_checklist' => ['Gym', 'Pool', 'Clubhouse'],
        ]);

        $response = $this->showPage($entry);

        $response->assertSee('Gym');
        $response->assertSee('Pool');
        $response->assertSee('Clubhouse');
        // No raw JSON array syntax leaking into the page
        $response->assertDontSee('["Gym"', false);
    }

    /** @test */
    public function boolean_fields_render_yes_no_rather_than_a_dash(): void
    {
        // Regression: the model casts these to real booleans, so the old
        // strict `=== 1` comparison rendered every answered Yes/No as "—".
        $entry = $this->entry([
            'property_type' => null,
            'stp_plant'     => true,
            'canteen'       => false,
        ]);

        $response = $this->showPage($entry);

        $response->assertSee('STP Plant');
        $response->assertSee('Yes');
        $response->assertSee('No');
    }

    /** @test */
    public function orphaned_data_not_in_the_type_map_appears_under_other_data(): void
    {
        // canopy_length_* are real warehouse columns the original hardcoded
        // layout never displayed, so they are genuinely orphaned data.
        $entry = $this->entry([
            'property_type'       => null,
            'canopy_length_front' => 12.5,
        ]);

        $this->assertArrayHasKey('canopy_length_front', $entry->unmappedData());

        $response = $this->showPage($entry);
        $response->assertSee('Other Data');
        $response->assertSee('Canopy Length Front');
        $response->assertSee('12.5');
    }

    /** @test */
    public function custom_fields_values_are_shown_rather_than_dropped(): void
    {
        $entry = $this->entry([
            'property_type' => 'service_apartment_pg',
            'custom_fields' => json_encode(['gender_policy' => 'Co-ed', 'totally_unmapped_key' => 'KeepMe']),
        ]);

        $response = $this->showPage($entry);

        // A mapped field whose value lives in custom_fields renders in place...
        $response->assertSee('Co-ed');
        // ...and an unmapped one still surfaces under Other Data.
        $response->assertSee('KeepMe');
    }

    /** @test */
    public function tier_badges_are_shown_for_spec_classified_fields(): void
    {
        $entry = $this->entry(['property_type' => 'service_apartment_pg']);

        $response = $this->showPage($entry);

        $response->assertSee('INTERNAL');
        $response->assertSee('WEBSITE');
        $response->assertSee('VERIFIED');
    }

    /** @test */
    public function a_guest_cannot_view_the_entry_detail_page(): void
    {
        $entry = $this->entry(['property_type' => null]);

        $this->get(route('admin.property-entry-report.show', $entry))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function a_non_admin_user_cannot_view_the_entry_detail_page(): void
    {
        $entry = $this->entry(['property_type' => null]);

        $this->actingAs($this->officer)
            ->get(route('admin.property-entry-report.show', $entry))
            ->assertStatus(403);
    }
}
