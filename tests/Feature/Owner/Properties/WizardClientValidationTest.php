<?php

namespace Tests\Feature\Owner\Properties;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Server-side coverage for the shared client-side validation + step-tab
 * gating layer.
 *
 * These assert the shared component is actually WIRED INTO every form and
 * that the gating rules are sourced from one place — they cannot exercise
 * the in-browser behaviour itself (typing, blurring, clicking a locked tab),
 * because this project has no browser-test tool installed (no Dusk, Cypress,
 * Playwright or Jest). That behaviour was verified manually in-browser; see
 * the QA checklist in the accompanying notes.
 */
class WizardClientValidationTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;

    /** Every dedicated property-type wizard route slug. */
    private const TYPE_SLUGS = [
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

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->create(['role' => 'owner', 'is_active' => true]);
    }

    private function createPage(string $slug)
    {
        return $this->actingAs($this->owner)
            ->get(route("owner.properties.{$slug}.create"));
    }

    /** @test */
    public function every_dedicated_wizard_loads_the_shared_field_validator(): void
    {
        foreach (self::TYPE_SLUGS as $slug) {
            $response = $this->createPage($slug);
            $response->assertStatus(200);

            // The shared component, not a per-form copy.
            $response->assertSee('window.ZendoFieldValidation', false);
            $response->assertSee('field-format-err', false);
        }
    }

    /** @test */
    public function every_dedicated_wizard_installs_the_step_tab_gate(): void
    {
        foreach (self::TYPE_SLUGS as $slug) {
            $response = $this->createPage($slug);
            $response->assertStatus(200);

            $response->assertSee('__wizTabGateInstalled', false);
            $response->assertSee('wizardRefreshTabLocks', false);
            $response->assertSee('wiz-lock-msg', false);
        }
    }

    /** @test */
    public function the_warehouse_form_also_loads_the_shared_field_validator(): void
    {
        $response = $this->actingAs($this->owner)->get(route('owner.properties.create'));

        $response->assertStatus(200);
        $response->assertSee('window.ZendoFieldValidation', false);
        $response->assertSee('field-format-err', false);
    }

    /** @test */
    public function tab_gating_and_save_and_next_share_one_validation_function(): void
    {
        // Both paths must call window.wizardValidateStep — if the tab gate
        // ever grew its own check the two could disagree, which is exactly
        // the bug this guards against.
        $response = $this->createPage('apartment-flat-studio');
        $body = $response->getContent();

        $this->assertStringContainsString('wizardValidateStep', $body);

        // The tab-gate click handler resolves validity through the same fn.
        $this->assertMatchesRegularExpression(
            '/wiz-dot[\s\S]{0,4000}?wizardValidateStep/',
            $body,
            'Step-tab gating must call wizardValidateStep, not a separate check.'
        );
    }

    /** @test */
    public function save_draft_stays_unguarded_by_required_field_rules(): void
    {
        // A draft may legitimately be incomplete, so the Save Draft control
        // must keep bypassing native constraint validation.
        $response = $this->createPage('apartment-flat-studio');

        $response->assertSee('formnovalidate', false);
        $response->assertSee('name="action" value="draft"', false);
    }

    /** @test */
    public function the_shared_validator_is_defined_once_not_duplicated_per_form(): void
    {
        $body = $this->createPage('apartment-flat-studio')->getContent();

        // The guard clause that makes the component idempotent, plus exactly
        // one definition of the public API object.
        $this->assertSame(
            1,
            substr_count($body, 'window.ZendoFieldValidation = {'),
            'The shared validator should be emitted exactly once per page.'
        );
    }

    /**
     * @test
     *
     * Documents a real gap rather than asserting a behaviour: the gating and
     * validation layer is installed on all 13 forms, but it can only enforce
     * required-ness where the markup actually marks fields required. Today
     * only apartment-flat-studio (35) and commercial-institutional-land (3)
     * do; the other 11 dedicated views emit no `required` attributes at all,
     * so on those forms every step validates trivially and the tab gate lets
     * the user through. Warehouse is unaffected — it drives requiredness
     * dynamically from PropertyFieldConfig.
     *
     * This should start failing (and be updated) as `required` attributes are
     * added to the remaining forms from their spec sheets' Mandatory column.
     */
    public function forms_currently_lacking_required_attributes_are_tracked(): void
    {
        $withoutRequired = [];

        foreach (self::TYPE_SLUGS as $slug) {
            $path = resource_path("views/owner/properties/{$slug}/create.blade.php");
            $count = preg_match_all('/\brequired\b/', file_get_contents($path));
            if ($count === 0) {
                $withoutRequired[] = $slug;
            }
        }

        $this->assertSame([], $withoutRequired, 'Update this list as `required` attributes are added per spec sheet.');
    }

    /** @test */
    public function the_shared_validator_includes_numeric_field_validation(): void
    {
        $body = $this->createPage('apartment-flat-studio')->getContent();

        $this->assertStringContainsString('Please enter a valid number.', $body);
        $this->assertStringContainsString('Please enter a whole number.', $body);
        $this->assertStringContainsString('NUMERIC_NAME_RE', $body);
        $this->assertStringContainsString('NON_NUMERIC_EXACT', $body);
    }

    /** @test */
    public function dropdown_select_fields_are_never_evaluated_as_numeric_fields(): void
    {
        $body = $this->createPage('apartment-flat-studio')->getContent();

        $this->assertStringContainsString("if (tagName === 'SELECT') return 'select';", $body);
        $this->assertStringContainsString("if (kind === 'select' || input.tagName === 'SELECT')", $body);
        $this->assertStringContainsString("Please select an option.", $body);
    }

    /** @test */
    public function conditional_fields_like_availability_from_date_and_possession_by_are_validated(): void
    {
        $body = $this->createPage('apartment-flat-studio')->getContent();

        $this->assertStringContainsString('setupAvailabilityToggle', $body);
        $this->assertStringContainsString('setupConstructionStatusToggle', $body);
        $this->assertStringContainsString('setupReraToggle', $body);
        $this->assertStringContainsString('setupProjectSocietyToggle', $body);
    }
}
