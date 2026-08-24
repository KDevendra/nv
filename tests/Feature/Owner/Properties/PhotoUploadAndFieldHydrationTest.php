<?php

namespace Tests\Feature\Owner\Properties;

use App\Models\PropertyEntry;
use App\Models\PropertyEntryPhoto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Regression coverage for two bugs found during an end-to-end audit of the
 * 12 dedicated property wizards (every type except warehouse and
 * apartment-flat-studio):
 *
 * 1. PropertyEntryPhoto::$fillable is ['property_entry_id', 'slot_label',
 *    'file_path'], but all 12 controllers wrote 'slot' / 'photo_path' —
 *    neither a real column nor a fillable key. Uploading any photo on any
 *    of these 12 forms threw a hard SQLSTATE error ("Field 'slot_label'
 *    doesn't have a default value"), failing the entire request — draft or
 *    full submit — not just the photo.
 *
 * 2. Every dedicated create/edit view pre-fills inputs via
 *    `old($f, $property->$f ?? '')`. For the 12 dedicated types, most
 *    fields aren't real columns — they live in `custom_fields` JSON — so
 *    `$property->$f` was always null and every such input reset to blank
 *    on edit, even though the value was genuinely saved. Same bug existed
 *    in the owner/field "view" page's shared property-details-view
 *    component. Both now route through PropertyEntry::fieldValue(), which
 *    already existed for the admin detail view.
 */
class PhotoUploadAndFieldHydrationTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->create(['role' => 'owner', 'is_active' => true]);
        Storage::fake('public');
    }

    /** @test */
    public function uploading_a_photo_on_a_dedicated_type_does_not_crash_the_request(): void
    {
        $response = $this->actingAs($this->owner)->post(
            route('owner.properties.service-apartment-pg.store'),
            [
                'action' => 'draft',
                'submitter_full_name' => 'Photo Test',
                'photo_0' => UploadedFile::fake()->image('front.jpg', 200, 200),
            ]
        );

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        $entry = PropertyEntry::where('submitter_full_name', 'Photo Test')->firstOrFail();
        $photo = PropertyEntryPhoto::where('property_entry_id', $entry->id)->first();

        $this->assertNotNull($photo, 'A PropertyEntryPhoto row must be created.');
        $this->assertNotNull($photo->slot_label, 'slot_label must be populated, not silently dropped.');
        $this->assertNotNull($photo->file_path, 'file_path must be populated, not silently dropped.');
        Storage::disk('public')->assertExists($photo->file_path);
    }

    /** @test */
    public function updating_replaces_an_existing_slots_photo_rather_than_duplicating_it(): void
    {
        $entry = PropertyEntry::create([
            'property_type' => 'service_apartment_pg',
            'field_officer_id' => $this->owner->id,
            'status' => 'draft',
            'submitter_full_name' => 'Replace Test',
        ]);
        PropertyEntryPhoto::create([
            'property_entry_id' => $entry->id,
            'slot_label' => 'Front / Exterior / Living room',
            'file_path' => 'property-photos/original.jpg',
        ]);

        $response = $this->actingAs($this->owner)->put(
            route('owner.properties.service-apartment-pg.update', $entry),
            [
                'action' => 'draft',
                'photo_0' => UploadedFile::fake()->image('replacement.jpg', 200, 200),
            ]
        );

        $response->assertSessionHasNoErrors();

        $photos = PropertyEntryPhoto::where('property_entry_id', $entry->id)->get();
        $this->assertCount(1, $photos, 'Re-uploading the same slot must replace it, not add a second row.');
        $this->assertNotSame('property-photos/original.jpg', $photos->first()->file_path);
    }

    /**
     * @test
     * @dataProvider dedicatedTypeProvider
     */
    public function edit_page_hydrates_a_custom_fields_backed_value(string $slug, string $type, string $field, string $value): void
    {
        $entry = PropertyEntry::create([
            'property_type' => $type,
            'field_officer_id' => $this->owner->id,
            'status' => 'draft',
            'custom_fields' => json_encode([$field => $value]),
        ]);

        $response = $this->actingAs($this->owner)->get(route("owner.properties.{$slug}.edit", $entry));

        $response->assertStatus(200);
        $response->assertSee($value);
    }

    public static function dedicatedTypeProvider(): array
    {
        return [
            'service-apartment-pg / gender_policy' => ['service-apartment-pg', 'service_apartment_pg', 'gender_policy', 'Co-ed'],
            'house-villa-farmhouse / car_parking_capacity' => ['house-villa-farmhouse', 'house_villa_farmhouse', 'car_parking_capacity', '2 covered'],
            'office-space / seating_workstation_capacity' => ['office-space', 'office_space', 'seating_workstation_capacity', '42'],
        ];
    }

    /**
     * @test
     *
     * property-details-view's Section A previously read `$property->submitter_name`
     * and `$property->submitter_relationship_to_owner` — neither was ever a real
     * field on any of the 13 forms, so this section rendered blank/"—" for
     * every entry regardless of what was actually submitted.
     *
     * Note: this only proves the fieldValue() routing itself is correct.
     * property-details-view's template has a fixed, generic set of field
     * slots (deal_type, currently_rented_tenanted, etc.) — most of the 12
     * dedicated types' OWN field names (e.g. service-apartment-pg's
     * gender_policy) have no slot in this template at all yet. Giving the
     * view full per-type coverage, the way the admin detail view's
     * config/property_entry_sections.php already does, is separate,
     * larger follow-up work — not a regression of this fix.
     */
    public function the_property_details_view_component_resolves_submitter_name_via_field_value(): void
    {
        $entry = PropertyEntry::create([
            'property_type' => 'service_apartment_pg',
            'field_officer_id' => $this->owner->id,
            'status' => 'submitted',
            'submitter_full_name' => 'View Page Test',
        ]);

        $html = view('components.property-details-view', ['property' => $entry])->render();

        $this->assertStringContainsString('View Page Test', $html, 'submitter_full_name must resolve, not the non-existent "submitter_name".');
    }
}
