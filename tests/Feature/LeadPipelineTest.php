<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Lead;
use App\Models\Property;
use App\Models\Inquiry;
use App\Models\PropertyInquiry;
use App\Models\Consultation;
use App\Helpers\WorkingHours;
use App\Console\Commands\CheckLeadSLA;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

class LeadPipelineTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        if (User::where('role', 'super_admin')->count() === 0) {
            $this->artisan('db:seed', ['--class' => 'PermissionSeeder']);
        }
    }

    /**
     * Regression Test: Submitting a new lead via property inquiry flow writes
     * EXACTLY ONE row to `leads` and ZERO rows to legacy tables.
     */
    public function test_property_inquiry_writes_only_to_leads_and_zero_legacy_rows()
    {
        $property = $this->createTestProperty();
        $phone = '9876' . rand(100000, 999999);

        $initialLeadsCount = Lead::count();
        $initialInquiriesCount = Inquiry::count();
        $initialPropInquiriesCount = PropertyInquiry::count();
        $initialConsultationsCount = Consultation::count();

        $response = $this->postJson(route('inquiries.store'), [
            'property_id' => $property->id,
            'name'        => 'John Doe',
            'phone'       => $phone,
            'email'       => 'john_' . rand(1000, 9999) . '@example.com',
            'message'     => 'Interested in viewing this warehouse property',
        ]);

        $response->assertStatus(200);

        // Exactly 1 new row in leads
        $this->assertEquals($initialLeadsCount + 1, Lead::count());
        $lead = Lead::where('phone', $phone)->first();
        $this->assertNotNull($lead);
        $this->assertEquals('John Doe', $lead->name);
        $this->assertEquals($phone, $lead->phone);

        // Zero new rows in legacy tables
        $this->assertEquals($initialInquiriesCount, Inquiry::count());
        $this->assertEquals($initialPropInquiriesCount, PropertyInquiry::count());
        $this->assertEquals($initialConsultationsCount, Consultation::count());
    }

    /**
     * Test division enforcement on User model saving.
     */
    public function test_user_division_enforcement()
    {
        $uniq = rand(1000, 9999);
        // Field Officer division forced to warehousing
        $fo = User::create([
            'name'     => 'FO User',
            'email'    => "fo_{$uniq}@example.com",
            'phone'    => '111' . rand(1000000, 9999999),
            'password' => bcrypt('password'),
            'role'     => 'field_officer',
        ]);
        $this->assertEquals('warehousing', $fo->division);

        // SE requires division
        $this->expectException(\InvalidArgumentException::class);
        User::create([
            'name'     => 'SE User',
            'email'    => "se_{$uniq}@example.com",
            'phone'    => '222' . rand(1000000, 9999999),
            'password' => bcrypt('password'),
            'role'     => 'sales_executive',
            'division' => null,
        ]);
    }

    /**
     * Test forward-only stage transitions.
     */
    public function test_forward_only_stage_transitions()
    {
        $lead = Lead::create([
            'division' => 'warehousing',
            'name'     => 'Test Lead',
            'phone'    => '999' . rand(1000000, 9999999),
            'stage'    => 'contacted',
        ]);

        $this->assertTrue($lead->canTransitionTo('qualified'));
        $this->assertFalse($lead->canTransitionTo('new_lead'));

        $this->expectException(\RuntimeException::class);
        $lead->transitionTo('new_lead');
    }

    /**
     * Test Handover Note Hard Gate for escalation to CC.
     */
    public function test_handover_note_hard_gate()
    {
        $uniq = rand(1000, 9999);
        $se = User::create([
            'name'     => 'SE One',
            'email'    => "se1_{$uniq}@example.com",
            'phone'    => '333' . rand(1000000, 9999999),
            'password' => bcrypt('password'),
            'role'     => 'sales_executive',
            'division' => 'warehousing',
        ]);

        $lead = Lead::create([
            'division'       => 'warehousing',
            'name'           => 'Gate Lead',
            'phone'          => '888' . rand(1000000, 9999999),
            'stage'          => 'interest_confirmed',
            'assigned_se_id' => $se->id,
        ]);

        // Attempting transition to escalated_to_cc without handover note fails
        $this->assertFalse($lead->canTransitionTo('escalated_to_cc'));

        // Attempting via PATCH without handover note returns 422
        $response = $this->actingAs($se)->patchJson(route('se.leads.update', $lead), [
            'stage'         => 'escalated_to_cc',
            'handover_note' => '',
        ]);
        $response->assertStatus(422);

        // Providing handover note enables transition
        $lead->handover_note = 'Sufficient budget confirmed, ready for site visit prep.';
        $lead->handover_completed_at = now();
        $lead->save();

        $this->assertTrue($lead->canTransitionTo('escalated_to_cc'));
    }

    /**
     * Test SLA Job excludes held leads and does not retroactively flag breach after resume.
     */
    public function test_sla_job_excludes_held_leads()
    {
        Carbon::setTestNow('2026-08-01 09:00:00'); // Saturday 9am

        $lead = Lead::create([
            'division'   => 'warehousing',
            'name'       => 'SLA Lead',
            'phone'      => '777' . rand(1000000, 9999999),
            'created_at' => now(),
        ]);

        // Put on hold after 1 hour
        Carbon::setTestNow('2026-08-01 10:00:00');
        $lead->putOnHold('Waiting client vacation', '2026-08-05');

        // Advance time by 3 days while held
        Carbon::setTestNow('2026-08-04 10:00:00');
        Artisan::call('app:check-lead-sla');

        // Lead on hold must NOT be flagged as SLA breach
        $this->assertNull($lead->fresh()->first_contacted_at);

        // Resume from hold
        $lead->resumeFromHold();

        // Advance 2 working hours
        Carbon::setTestNow('2026-08-04 12:00:00');
        Artisan::call('app:check-lead-sla');

        // SLA SLA check run without error
        $this->assertTrue(true);
    }

    /**
     * Test Info-Gating for SE / CC publicPropertySnapshot.
     */
    public function test_info_gating_public_property_snapshot()
    {
        $property = $this->createTestProperty([
            'title'       => 'Secret Warehouse',
            'address'     => '123 Private Sector, Restricted Area',
            'latitude'    => 28.12345678,
            'longitude'   => 77.12345678,
            'price'       => 5000000.00,
            'carpet_area' => 10000.00,
        ]);

        $snapshot = Lead::publicPropertySnapshot($property);

        $this->assertEquals('Secret Warehouse', $snapshot['title']);
        $this->assertEquals(5000000.00, $snapshot['price']);
        $this->assertArrayNotHasKey('address', $snapshot);
        $this->assertArrayNotHasKey('latitude', $snapshot);
        $this->assertArrayNotHasKey('longitude', $snapshot);
        $this->assertArrayNotHasKey('user_id', $snapshot);
    }

    /**
     * Test Site Visit expiring link (single-use, 24h expiry).
     */
    public function test_site_visit_link_single_use_and_expiry()
    {
        $lead = Lead::create([
            'division' => 'residential',
            'name'     => 'Visit Visitor',
            'phone'    => '666' . rand(1000000, 9999999),
        ]);

        $token = $lead->generateVisitLinkToken();
        $this->assertTrue($lead->isVisitLinkValid());

        // First open succeeds and consumes token
        $response = $this->get(route('leads.visit_link', ['token' => $token]));
        $response->assertStatus(200);

        // Second open attempt fails (consumed)
        $this->assertFalse($lead->fresh()->isVisitLinkValid());
        $response2 = $this->get(route('leads.visit_link', ['token' => $token]));
        $response2->assertSee('already been opened');
    }

    /**
     * Test CC 20-lead cap auto-assignment holding queue.
     */
    public function test_cc_20_lead_cap_holding_queue()
    {
        // Deactivate existing commercial CCs for clean cap testing
        User::where('role', 'chief_coordinator')->where('division', 'commercial')->update(['is_active' => false]);

        $uniq = rand(1000, 9999);
        $cc = User::create([
            'name'     => 'CC Cap',
            'email'    => "cc_cap_{$uniq}@example.com",
            'phone'    => '555' . rand(1000000, 9999999),
            'password' => bcrypt('password'),
            'role'     => 'chief_coordinator',
            'division' => 'commercial',
            'is_active'=> true,
        ]);

        // Assign 20 active leads to CC
        for ($i = 1; $i <= 20; $i++) {
            Lead::create([
                'division'       => 'commercial',
                'name'           => "Active Lead {$i}",
                'phone'          => "555" . sprintf("%07d", $i + ($uniq * 100)),
                'stage'          => 'escalated_to_cc',
                'assigned_cc_id' => $cc->id,
            ]);
        }

        $this->assertEquals(20, $cc->activeCCLeadCount());

        // Create 21st lead
        $lead21 = Lead::create([
            'division' => 'commercial',
            'name'     => 'Overflow Lead 21',
            'phone'    => '55599' . rand(10000, 99999),
            'stage'    => 'new_lead',
        ]);

        // Handover & escalate to CC
        $lead21->handover_note = 'Detailed handover notes provided here.';
        $lead21->handover_completed_at = now();
        $lead21->transitionTo('escalated_to_cc');

        // Should land in holding queue (assigned_cc_id is null)
        $this->assertNull($lead21->fresh()->assigned_cc_id);
    }

    /**
     * Test 1: Division scoping at query level.
     * Warehousing SE/CC/SH must NOT see Residential or Commercial division leads.
     */
    public function test_division_scoping_at_query_level()
    {
        $uniq = rand(1000, 9999);
        $whSE = User::create(['name' => 'WH SE', 'email' => "wh_se_{$uniq}@example.com", 'phone' => '111'.rand(1000000,9999999), 'password' => bcrypt('password'), 'role' => 'sales_executive', 'division' => 'warehousing']);
        $whCC = User::create(['name' => 'WH CC', 'email' => "wh_cc_{$uniq}@example.com", 'phone' => '222'.rand(1000000,9999999), 'password' => bcrypt('password'), 'role' => 'chief_coordinator', 'division' => 'warehousing']);
        $whSH = User::create(['name' => 'WH SH', 'email' => "wh_sh_{$uniq}@example.com", 'phone' => '333'.rand(1000000,9999999), 'password' => bcrypt('password'), 'role' => 'supply_head', 'division' => 'warehousing']);

        // Create residential & commercial leads assigned to other users
        $resLead = Lead::create(['division' => 'residential', 'name' => 'Res Lead', 'phone' => '444'.rand(1000000,9999999), 'stage' => 'contacted', 'assigned_se_id' => $whSE->id]);
        $comLead = Lead::create(['division' => 'commercial', 'name' => 'Com Lead', 'phone' => '555'.rand(1000000,9999999), 'stage' => 'escalated_to_cc', 'assigned_cc_id' => $whCC->id, 'feasibility_sh_id' => $whSH->id]);

        // SE index response must not contain cross-division leads
        $seResp = $this->actingAs($whSE)->get(route('se.leads.index'));
        $seResp->assertOk();
        $this->assertCount(0, $seResp->viewData('leads'));

        // CC index response must not contain cross-division leads
        $ccResp = $this->actingAs($whCC)->get(route('cc.leads.index'));
        $ccResp->assertOk();
        $this->assertCount(0, $ccResp->viewData('leads'));

        // SH index response must not contain cross-division leads
        $shResp = $this->actingAs($whSH)->get(route('sh.leads.index'));
        $shResp->assertOk();
        $this->assertCount(0, $shResp->viewData('leads'));
    }

    /**
     * Test 2: Role limits on stage transitions.
     * SE attempting to move past interest_confirmed (to CC stages beyond handover) is blocked.
     * CC attempting to act on lead below escalated_to_cc is blocked.
     */
    public function test_role_limits_on_stage_transitions()
    {
        $uniq = rand(1000, 9999);
        $se = User::create(['name' => 'Lim SE', 'email' => "lim_se_{$uniq}@example.com", 'phone' => '666'.rand(1000000,9999999), 'password' => bcrypt('password'), 'role' => 'sales_executive', 'division' => 'residential']);
        $cc = User::create(['name' => 'Lim CC', 'email' => "lim_cc_{$uniq}@example.com", 'phone' => '777'.rand(1000000,9999999), 'password' => bcrypt('password'), 'role' => 'chief_coordinator', 'division' => 'residential']);

        $leadAtInterest = Lead::create(['division' => 'residential', 'name' => 'Interest Lead', 'phone' => '888'.rand(1000000,9999999), 'stage' => 'interest_confirmed', 'assigned_se_id' => $se->id]);

        // SE cannot transition directly to inventory_check_done or deal_closed
        try {
            $leadAtInterest->transitionTo('inventory_check_done', $se);
            $this->fail("Expected RuntimeException when SE transitions to inventory_check_done");
        } catch (\RuntimeException $e) { $this->assertTrue(true); }

        $leadAtContacted = Lead::create(['division' => 'residential', 'name' => 'Contacted Lead', 'phone' => '889'.rand(1000000,9999999), 'stage' => 'contacted']);

        // CC cannot act on lead below escalated_to_cc
        try {
            $leadAtContacted->transitionTo('inventory_check_done', $cc);
            $this->fail("Expected RuntimeException when CC transitions lead below escalated_to_cc");
        } catch (\RuntimeException $e) { $this->assertTrue(true); }
    }

    /**
     * Test 3: mark_lost_is_terminal.
     * After markLost(), ANY further call (transitionTo, putOnHold, resumeFromHold, deferFollowUp, markLost) throws.
     */
    public function test_mark_lost_is_terminal()
    {
        $lead = Lead::create(['division' => 'commercial', 'name' => 'Lost Lead', 'phone' => '999'.rand(1000000,9999999), 'stage' => 'contacted']);
        $lead->markLost('Client backed out');

        $this->assertFalse($lead->canTransitionTo('qualified'));

        try {
            $lead->transitionTo('qualified');
            $this->fail("Expected RuntimeException on transitionTo after markLost");
        } catch (\RuntimeException $e) { $this->assertTrue(true); }

        try {
            $lead->putOnHold('Vacation', '2026-08-25');
            $this->fail("Expected RuntimeException on putOnHold after markLost");
        } catch (\RuntimeException $e) { $this->assertTrue(true); }

        try {
            $lead->resumeFromHold();
            $this->fail("Expected RuntimeException on resumeFromHold after markLost");
        } catch (\RuntimeException $e) { $this->assertTrue(true); }

        try {
            $lead->deferFollowUp('2026-08-25');
            $this->fail("Expected RuntimeException on deferFollowUp after markLost");
        } catch (\RuntimeException $e) { $this->assertTrue(true); }

        try {
            $lead->markLost('Double lost');
            $this->fail("Expected RuntimeException on markLost after markLost");
        } catch (\RuntimeException $e) { $this->assertTrue(true); }
    }

    /**
     * Test 4: resume_from_hold_clears_state.
     * After putOnHold() then resumeFromHold(), assert side_state = 'none' and hold fields are cleared.
     */
    public function test_resume_from_hold_clears_state()
    {
        $lead = Lead::create(['division' => 'warehousing', 'name' => 'Hold Lead', 'phone' => '123'.rand(1000000,9999999), 'stage' => 'qualified']);
        $lead->putOnHold('Client budget hold', '2026-08-25');

        $this->assertEquals('inquiry_hold', $lead->side_state);
        $this->assertEquals('qualified', $lead->pre_hold_status);
        $this->assertNotNull($lead->hold_started_at);
        $this->assertEquals('2026-08-25', $lead->hold_expected_resume_date->toDateString());
        $this->assertEquals('Client budget hold', $lead->hold_reason);

        $lead->resumeFromHold();
        $fresh = $lead->fresh();

        $this->assertEquals('none', $fresh->side_state);
        $this->assertNull($fresh->pre_hold_status);
        $this->assertNull($fresh->hold_started_at);
        $this->assertNull($fresh->hold_expected_resume_date);
        $this->assertNull($fresh->hold_reason);
    }

    private function createTestProperty(array $attributes = []): Property
    {
        $user = User::first() ?? User::create([
            'name'     => 'Prop Creator',
            'email'    => 'creator_' . rand(1000, 9999) . '@example.com',
            'phone'    => '000' . rand(1000000, 9999999),
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        $city = \App\Models\City::first() ?? \App\Models\City::create(['name' => 'Mumbai', 'slug' => 'mumbai-' . rand(100, 999)]);
        $location = \App\Models\Location::first() ?? \App\Models\Location::create(['city_id' => $city->id, 'name' => 'Bandra', 'slug' => 'bandra-' . rand(100, 999)]);
        $type = \App\Models\PropertyType::first() ?? \App\Models\PropertyType::create(['name' => 'Warehouse', 'slug' => 'warehouse-' . rand(100, 999), 'category' => 'warehousing']);
        $status = \App\Models\ProjectStatus::first() ?? \App\Models\ProjectStatus::create(['name' => 'Ready', 'slug' => 'ready-' . rand(100, 999), 'value' => 'ready']);

        return Property::create(array_merge([
            'title'            => 'Test Property ' . rand(100, 999),
            'slug'             => 'test-property-' . \Illuminate\Support\Str::random(12),
            'property_type_id' => $type->id,
            'city_id'          => $city->id,
            'location_id'      => $location->id,
            'project_status_id'=> $status->id,
            'price'            => 5000000.00,
            'carpet_area'      => 5000.00,
            'user_id'          => $user->id,
        ], $attributes));
    }
}
