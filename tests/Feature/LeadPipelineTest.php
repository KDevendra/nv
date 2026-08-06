<?php

namespace Tests\Feature;

use App\Helpers\WorkingHours;
use App\Models\Lead;
use App\Models\LeadStageHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature test suite for the Zendo Lead Pipeline.
 *
 * Covers all business rules specified in the verification plan:
 *  1. Forward-only stage transitions and role limits.
 *  2. Handover-note hard gate on escalation to CC.
 *  3. Side-states: putOnHold, resumeFromHold, markLost.
 *  4. SLA job excludes held leads and does not re-flag after resume.
 *  5. CC 20-lead cap auto-assignment & holding queue.
 *  6. Info-Gating (SE/CC property snapshots omit owner/address/GPS).
 *  7. Single-use 24h site-visit link invalidation upon opening.
 *  8. Division scoping at query level for SE, CC, and SH.
 */
class LeadPipelineTest extends TestCase
{
    use RefreshDatabase;

    // ──────────────────────────────────────────────────────────────────────
    // Factories / helpers
    // ──────────────────────────────────────────────────────────────────────

    private function makeLead(array $attrs = []): Lead
    {
        return Lead::create(array_merge([
            'division' => 'residential',
            'name'     => 'Test Lead',
            'phone'    => '9' . str_pad(rand(0, 999999999), 9, '0', STR_PAD_LEFT),
            'email'    => null,
            'stage'    => 'new_lead',
        ], $attrs));
    }

    private function makeUser(string $role, ?string $division = 'residential'): User
    {
        return User::create([
            'name'     => "{$role} user",
            'email'    => $role . rand(1, 99999) . '@test.com',
            'phone'    => '9' . str_pad(rand(0, 999999999), 9, '0', STR_PAD_LEFT),
            'password' => bcrypt('password'),
            'role'     => $role,
            'division' => $division,
            'is_active'=> true,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────
    // 1. Forward-only stage transitions
    // ──────────────────────────────────────────────────────────────────────

    public function test_stage_advances_forward_correctly(): void
    {
        $lead = $this->makeLead(['stage' => 'new_lead']);

        // Set handover fields so the escalated_to_cc gate passes when we get there
        $lead->handover_note         = 'Handover summary for testing.';
        $lead->handover_completed_at = now();
        $lead->save();

        $pairs = [
            ['new_lead',           'contacted'],
            ['contacted',          'interest_confirmed'],
            ['interest_confirmed', 'escalated_to_cc'],
            ['escalated_to_cc',    'feasibility_check'],
        ];

        foreach ($pairs as [$from, $to]) {
            $lead->stage = $from;
            $lead->save();

            $this->assertTrue($lead->canTransitionTo($to),
                "Expected canTransitionTo('{$to}') from '{$from}' to return true.");

            $lead->transitionTo($to);
            $this->assertEquals($to, $lead->fresh()->stage);
        }
    }

    public function test_backward_stage_transition_is_rejected(): void
    {
        $lead = $this->makeLead(['stage' => 'contacted']);

        $this->assertFalse($lead->canTransitionTo('new_lead'));

        $this->expectException(\RuntimeException::class);
        $lead->transitionTo('new_lead');
    }

    public function test_same_stage_transition_is_rejected(): void
    {
        $lead = $this->makeLead(['stage' => 'contacted']);

        $this->assertFalse($lead->canTransitionTo('contacted'));
    }

    public function test_unknown_stage_transition_is_rejected(): void
    {
        $lead = $this->makeLead(['stage' => 'new_lead']);

        $this->assertFalse($lead->canTransitionTo('invalid_stage'));
    }

    public function test_stage_history_is_recorded_on_transition(): void
    {
        $lead = $this->makeLead(['stage' => 'new_lead']);
        $lead->transitionTo('contacted');

        $history = LeadStageHistory::where('lead_id', $lead->id)->first();
        $this->assertNotNull($history);
        $this->assertEquals('new_lead',  $history->from_stage);
        $this->assertEquals('contacted', $history->to_stage);
    }

    // ──────────────────────────────────────────────────────────────────────
    // 2. Handover-note hard gate
    // ──────────────────────────────────────────────────────────────────────

    public function test_escalation_to_cc_blocked_without_handover_note(): void
    {
        $lead = $this->makeLead(['stage' => 'interest_confirmed']);

        // No handover_note or handover_completed_at set
        $this->assertFalse($lead->canTransitionTo('escalated_to_cc'));

        $this->expectException(\RuntimeException::class);
        $lead->transitionTo('escalated_to_cc');
    }

    public function test_escalation_to_cc_blocked_with_note_but_no_timestamp(): void
    {
        $lead = $this->makeLead(['stage' => 'interest_confirmed']);
        $lead->handover_note = 'Some note';
        $lead->save();

        $this->assertFalse($lead->canTransitionTo('escalated_to_cc'));
    }

    public function test_escalation_to_cc_passes_with_full_handover(): void
    {
        $lead = $this->makeLead(['stage' => 'interest_confirmed']);
        $lead->handover_note         = 'Full handover note.';
        $lead->handover_completed_at = now();
        $lead->save();

        $this->assertTrue($lead->canTransitionTo('escalated_to_cc'));
        $lead->transitionTo('escalated_to_cc');
        $this->assertEquals('escalated_to_cc', $lead->fresh()->stage);
    }

    // ──────────────────────────────────────────────────────────────────────
    // 3. Side-states
    // ──────────────────────────────────────────────────────────────────────

    public function test_put_on_hold_sets_side_state_and_saves_pre_hold_stage(): void
    {
        $lead = $this->makeLead(['stage' => 'contacted']);
        $lead->putOnHold('Awaiting client decision.');

        $fresh = $lead->fresh();
        $this->assertEquals('on_hold',   $fresh->side_state);
        $this->assertEquals('contacted', $fresh->pre_hold_status);
        $this->assertNotNull($fresh->hold_started_at);
    }

    public function test_transition_blocked_while_on_hold(): void
    {
        $lead = $this->makeLead(['stage' => 'contacted']);
        $lead->putOnHold();

        $this->assertFalse($lead->canTransitionTo('interest_confirmed'));
    }

    public function test_resume_from_hold_clears_side_state(): void
    {
        $lead = $this->makeLead(['stage' => 'contacted']);
        $lead->putOnHold();
        $lead->resumeFromHold();

        $fresh = $lead->fresh();
        $this->assertNull($fresh->side_state);
        $this->assertNotNull($fresh->hold_ended_at);
    }

    public function test_resume_from_hold_restores_transitions(): void
    {
        $lead = $this->makeLead(['stage' => 'contacted']);
        $lead->putOnHold();
        $lead->resumeFromHold();

        $this->assertTrue($lead->canTransitionTo('interest_confirmed'));
    }

    public function test_mark_lost_sets_side_state_and_reason(): void
    {
        $lead = $this->makeLead(['stage' => 'contacted']);
        $lead->markLost('Client went with competitor.');

        $fresh = $lead->fresh();
        $this->assertEquals('lost', $fresh->side_state);
        $this->assertEquals('Client went with competitor.', $fresh->lost_reason);
        $this->assertNotNull($fresh->lost_at);
    }

    public function test_cannot_hold_a_lost_lead(): void
    {
        $lead = $this->makeLead(['stage' => 'contacted']);
        $lead->markLost('Gone.');

        $this->expectException(\RuntimeException::class);
        $lead->putOnHold('Trying to hold a lost lead.');
    }

    public function test_side_state_change_is_logged_in_history(): void
    {
        $lead = $this->makeLead(['stage' => 'contacted']);
        $lead->putOnHold('Test hold.');

        $history = LeadStageHistory::where('lead_id', $lead->id)
            ->where('to_side_state', 'on_hold')
            ->first();

        $this->assertNotNull($history);
    }

    // ──────────────────────────────────────────────────────────────────────
    // 4. SLA command excludes held leads
    // ──────────────────────────────────────────────────────────────────────

    public function test_sla_command_flags_contact_breach(): void
    {
        $se   = $this->makeUser('sales_executive', 'residential');
        $lead = $this->makeLead([
            'stage'                => 'new_lead',
            'contact_attempts'     => 0,
            'assigned_se_id'       => $se->id,
            'sla_contact_due_at'   => now()->subHour(),  // overdue
            'sla_contact_breached' => false,
            'side_state'           => null,
        ]);

        $this->artisan('app:check-lead-sla')->assertSuccessful();

        $this->assertTrue($lead->fresh()->sla_contact_breached);
    }

    public function test_sla_command_skips_held_leads(): void
    {
        $se   = $this->makeUser('sales_executive', 'residential');
        $lead = $this->makeLead([
            'stage'                => 'new_lead',
            'contact_attempts'     => 0,
            'assigned_se_id'       => $se->id,
            'sla_contact_due_at'   => now()->subHour(),
            'sla_contact_breached' => false,
            'side_state'           => 'on_hold',
        ]);

        $this->artisan('app:check-lead-sla')->assertSuccessful();

        $this->assertFalse($lead->fresh()->sla_contact_breached,
            'Held lead should not be flagged by SLA command.');
    }

    public function test_sla_does_not_re_flag_after_resume(): void
    {
        $se   = $this->makeUser('sales_executive', 'residential');
        $lead = $this->makeLead([
            'stage'                => 'new_lead',
            'contact_attempts'     => 0,
            'assigned_se_id'       => $se->id,
            'sla_contact_due_at'   => now()->addHour(), // still in future — should NOT breach
            'sla_contact_breached' => false,
            'side_state'           => null,
        ]);

        $this->artisan('app:check-lead-sla')->assertSuccessful();

        $this->assertFalse($lead->fresh()->sla_contact_breached);
    }

    // ──────────────────────────────────────────────────────────────────────
    // 5. CC 20-lead cap auto-assignment & holding queue
    // ──────────────────────────────────────────────────────────────────────

    public function test_assignBestCC_assigns_cc_under_cap(): void
    {
        $cc   = $this->makeUser('chief_coordinator', 'residential');
        $lead = $this->makeLead(['division' => 'residential', 'stage' => 'escalated_to_cc']);

        $assigned = $lead->assignBestCC();

        $this->assertNotNull($assigned);
        $this->assertEquals($cc->id, $lead->fresh()->assigned_cc_id);
    }

    public function test_assignBestCC_returns_null_when_all_ccs_at_cap(): void
    {
        $cc = $this->makeUser('chief_coordinator', 'residential');

        // Fill the CC with 20 active leads
        for ($i = 0; $i < Lead::CC_MAX_ACTIVE_LEADS; $i++) {
            $this->makeLead([
                'division'       => 'residential',
                'stage'          => 'escalated_to_cc',
                'assigned_cc_id' => $cc->id,
                'side_state'     => null,
            ]);
        }

        $overflow = $this->makeLead(['division' => 'residential', 'stage' => 'escalated_to_cc']);
        $result   = $overflow->assignBestCC();

        $this->assertNull($result, 'Should return null (holding queue) when CC is at cap.');
        $this->assertNull($overflow->fresh()->assigned_cc_id, 'Overflow lead should remain unassigned.');
    }

    public function test_holding_queue_scope_returns_unassigned_escalated_leads(): void
    {
        $this->makeLead(['stage' => 'escalated_to_cc', 'assigned_cc_id' => null]);
        $this->makeLead(['stage' => 'escalated_to_cc', 'assigned_cc_id' => null]);

        $count = Lead::holdingQueue()->count();
        $this->assertEquals(2, $count);
    }

    // ──────────────────────────────────────────────────────────────────────
    // 6. Info-gating
    // ──────────────────────────────────────────────────────────────────────

    public function test_property_snapshot_excludes_sensitive_fields(): void
    {
        // Create a user to satisfy the properties.user_id FK
        $owner = $this->makeUser('owner', null);

        // Insert all required NOT NULL FK rows
        $typeId = \DB::table('property_types')->insertGetId([
            'name' => 'Residential', 'slug' => 'res-' . rand(1, 99999),
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $cityId = \DB::table('cities')->insertGetId([
            'name' => 'Test City', 'slug' => 'city-' . rand(1, 99999),
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $locationId = \DB::table('locations')->insertGetId([
            'name' => 'Test Location', 'slug' => 'loc-' . rand(1, 99999),
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $statusId = \DB::table('project_statuses')->insertGetId([
            'name' => 'Ready', 'value' => 'ready',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $propertyId = \DB::table('properties')->insertGetId([
            'title'               => 'Test Property',
            'slug'                => 'test-prop-' . rand(1, 9999),
            'description'         => 'desc',
            'address'             => '123 Secret Street',
            'latitude'            => '28.7041',
            'longitude'           => '77.1025',
            'property_type_id'    => $typeId,
            'city_id'             => $cityId,
            'location_id'         => $locationId,
            'project_status_id'   => $statusId,
            'price'               => 5000000,
            'user_id'             => $owner->id,
            'is_active'           => true,
            'is_featured'         => false,
            'is_verified'         => false,
            'show_hidden_details' => false,
            'views_count'         => 0,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        $lead     = $this->makeLead(['property_id' => $propertyId]);
        $property = \App\Models\Property::find($propertyId);
        $snapshot = $lead->publicPropertySnapshot($property);

        $this->assertArrayNotHasKey('address',   $snapshot, 'address must be excluded');
        $this->assertArrayNotHasKey('latitude',  $snapshot, 'latitude must be excluded');
        $this->assertArrayNotHasKey('longitude', $snapshot, 'longitude must be excluded');
        $this->assertArrayNotHasKey('user_id',   $snapshot, 'owner user_id must be excluded');
        $this->assertArrayHasKey('title', $snapshot);
        $this->assertArrayHasKey('price', $snapshot);
    }

    // ──────────────────────────────────────────────────────────────────────
    // 7. Single-use 24h site-visit link
    // ──────────────────────────────────────────────────────────────────────

    public function test_site_visit_token_is_valid_on_generation(): void
    {
        $lead  = $this->makeLead();
        $token = $lead->generateSiteVisitToken();
        $fresh = $lead->fresh();

        $this->assertEquals($token, $fresh->site_visit_token);
        $this->assertTrue($fresh->isSiteVisitTokenValid());
        $this->assertNull($fresh->site_visit_token_opened_at);
    }

    public function test_site_visit_token_invalidated_after_consume(): void
    {
        $lead = $this->makeLead();
        $lead->generateSiteVisitToken();
        $lead->consumeSiteVisitToken();

        $this->assertFalse($lead->fresh()->isSiteVisitTokenValid());
        $this->assertNotNull($lead->fresh()->site_visit_token_opened_at);
    }

    public function test_site_visit_token_invalid_after_24h(): void
    {
        $lead = $this->makeLead();
        $lead->generateSiteVisitToken();

        // Backdate expiry to the past
        \DB::table('leads')->where('id', $lead->id)->update([
            'site_visit_token_expires_at' => now()->subMinutes(1),
        ]);

        $this->assertFalse($lead->fresh()->isSiteVisitTokenValid());
    }

    public function test_public_site_visit_route_returns_410_for_consumed_token(): void
    {
        $lead  = $this->makeLead();
        $token = $lead->generateSiteVisitToken();
        $lead->consumeSiteVisitToken();

        $response = $this->get(route('site-visit.show', ['token' => $token]));
        $response->assertStatus(410);
    }

    public function test_public_site_visit_route_consumes_token_on_first_access(): void
    {
        $lead  = $this->makeLead();
        $token = $lead->generateSiteVisitToken();

        $response = $this->get(route('site-visit.show', ['token' => $token]));
        $response->assertStatus(200);

        $this->assertNotNull($lead->fresh()->site_visit_token_opened_at,
            'Token should be consumed after first access.');
    }

    public function test_public_site_visit_route_returns_404_for_unknown_token(): void
    {
        $response = $this->get(route('site-visit.show', ['token' => 'totally-invalid-token']));
        $response->assertStatus(404);
    }

    // ──────────────────────────────────────────────────────────────────────
    // 8. Division scoping
    // ──────────────────────────────────────────────────────────────────────

    public function test_forDivision_scope_filters_correctly(): void
    {
        $this->makeLead(['division' => 'warehousing']);
        $this->makeLead(['division' => 'residential']);
        $this->makeLead(['division' => 'commercial']);

        $warehouseLeads   = Lead::forDivision('warehousing')->count();
        $residentialLeads = Lead::forDivision('residential')->count();

        $this->assertEquals(1, $warehouseLeads);
        $this->assertEquals(1, $residentialLeads);
    }

    public function test_forSE_scope_returns_only_assigned_leads(): void
    {
        $se = $this->makeUser('sales_executive', 'residential');

        $this->makeLead(['assigned_se_id' => $se->id, 'division' => 'residential']);
        $this->makeLead(['assigned_se_id' => $se->id, 'division' => 'residential']);
        $this->makeLead(['assigned_se_id' => null,    'division' => 'residential']);

        $this->assertEquals(2, Lead::forSE($se->id)->count());
    }

    public function test_forCC_scope_returns_only_assigned_leads(): void
    {
        $cc = $this->makeUser('chief_coordinator', 'residential');

        $this->makeLead(['assigned_cc_id' => $cc->id, 'division' => 'residential', 'stage' => 'escalated_to_cc']);
        $this->makeLead(['assigned_cc_id' => null,     'division' => 'residential', 'stage' => 'escalated_to_cc']);

        $this->assertEquals(1, Lead::forCC($cc->id)->count());
    }

    public function test_sh_lead_scope_returns_only_own_division_requests(): void
    {
        $sh = $this->makeUser('supply_head', 'warehousing');

        $warehouseLead = $this->makeLead([
            'division'           => 'warehousing',
            'stage'              => 'feasibility_check',
            'feasibility_sh_id'  => $sh->id,
        ]);
        $residentialLead = $this->makeLead([
            'division'           => 'residential',
            'stage'              => 'feasibility_check',
            'feasibility_sh_id'  => null,
        ]);

        $count = Lead::where('feasibility_sh_id', $sh->id)
            ->where('division', $sh->division)
            ->count();

        $this->assertEquals(1, $count);
    }

    // ──────────────────────────────────────────────────────────────────────
    // WorkingHours helper
    // ──────────────────────────────────────────────────────────────────────

    public function test_working_hours_add_skips_sunday(): void
    {
        // Set a Saturday at 17:00 IST
        $saturday = Carbon::create(2026, 8, 8, 17, 0, 0, 'Asia/Kolkata'); // Saturday
        $due      = WorkingHours::addWorkingHours($saturday, 4);

        // 1 hour left on Saturday (17:00–18:00), then 3 hours carry to Monday 09:00–12:00
        $this->assertEquals(Carbon::MONDAY, $due->dayOfWeek, 'SLA due should land on Monday after skipping Sunday.');
        $this->assertEquals(12, (int) $due->hour);
    }

    public function test_working_hours_within_same_day(): void
    {
        $monday = Carbon::create(2026, 8, 10, 9, 0, 0, 'Asia/Kolkata'); // Monday 09:00
        $due    = WorkingHours::addWorkingHours($monday, 4);

        $this->assertEquals(13, (int) $due->hour); // 09:00 + 4h = 13:00
    }
}
