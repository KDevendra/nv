<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\LeadStageHistory;
use App\Models\User;
use App\Models\PropertyEntry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadSeeder extends Seeder
{
    public function run(): void
    {
        $se = User::where('email', 'testing.salesexecutive1@zendoindia.com')->first();
        $cc = User::where('email', 'testing.chiefcoordinator1@zendoindia.com')->first();
        $sh = User::where('email', 'testing.supplyhead1@zendoindia.com')->first();
        $admin = User::where('role', 'admin')->first() ?: $se;

        if (!$se || !$cc || !$sh) {
            $this->command->error("LeadSeeder: Missing testing users. Please run UserSeeder first.");
            return;
        }

        // Disable foreign key checks for clean truncation of test data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        LeadStageHistory::truncate();
        Lead::whereIn('id', range(1, 11))->forceDelete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $propertyEntries = PropertyEntry::limit(4)->pluck('code')->toArray();
        if (empty($propertyEntries)) {
            $propertyEntries = ['PE-1001', 'PE-1002', 'PE-1003'];
        }

        $stagesOrdered = Lead::STAGES; // ['new_lead','contacted','qualified','options_shared','interest_confirmed','escalated_to_cc','inventory_check_done','site_visit_scheduled','site_visit_completed','negotiation','deal_closed']

        $stagesData = [
            1 => [
                'stage' => 'new_lead',
                'name'  => 'Stage 1 Test Client (New Lead)',
                'phone' => '+919876543201',
                'email' => 'client1.newlead@test.com',
                'qualification_notes' => 'Looking for 10,000 sqft warehouse near Raipur.',
            ],
            2 => [
                'stage' => 'contacted',
                'name'  => 'Stage 2 Test Client (Contacted)',
                'phone' => '+919876543202',
                'email' => 'client2.contacted@test.com',
                'contact_attempts' => 1,
                'first_contacted_at' => now()->subHours(2),
                'last_contacted_at'  => now()->subHours(1),
                'contact_outcome'    => 'Spoke with client. Arranged qualification call.',
                'qualification_notes' => 'Attempt 1 logged. Spoke with client about storage requirements.',
            ],
            3 => [
                'stage' => 'qualified',
                'name'  => 'Stage 3 Test Client (Qualified)',
                'phone' => '+919876543203',
                'email' => 'client3.qualified@test.com',
                'contact_attempts' => 2,
                'first_contacted_at' => now()->subHours(5),
                'last_contacted_at'  => now()->subHours(3),
                'qualification_notes' => 'Need: 15,000 sqft RCC warehouse. Budget: ₹1.5L/mo confirmed. Need clear height 24ft.',
            ],
            4 => [
                'stage' => 'options_shared',
                'name'  => 'Stage 4 Test Client (Options Shared)',
                'phone' => '+919876543204',
                'email' => 'client4.options@test.com',
                'contact_attempts' => 3,
                'first_contacted_at' => now()->subHours(8),
                'last_contacted_at'  => now()->subHours(4),
                'qualification_notes' => 'Budget confirmed. Shared matching warehouse catalog entries.',
                'options_shared_property_ids' => array_slice($propertyEntries, 0, 3),
            ],
            5 => [
                'stage' => 'interest_confirmed',
                'name'  => 'Stage 5 Test Client (Interest Confirmed)',
                'phone' => '+919876543205',
                'email' => 'client5.interest@test.com',
                'contact_attempts' => 3,
                'first_contacted_at' => now()->subHours(12),
                'last_contacted_at'  => now()->subHours(6),
                'qualification_notes' => 'Client expressed interest in options. Ready for handover summary.',
                'options_shared_property_ids' => array_slice($propertyEntries, 0, 2),
            ],
            6 => [
                'stage' => 'escalated_to_cc',
                'name'  => 'Stage 6 Test Client (Escalated to CC)',
                'phone' => '+919876543206',
                'email' => 'client6.escalated@test.com',
                'contact_attempts' => 3,
                'first_contacted_at' => now()->subHours(24),
                'last_contacted_at'  => now()->subHours(12),
                'qualification_notes' => 'Qualified for CC escalation.',
                'options_shared_property_ids' => array_slice($propertyEntries, 0, 2),
                'handover_note' => 'Budget ₹2L/mo confirmed, timeline 15 days, 20k sqft required.',
                'handover_completed_at' => now()->subHours(2),
                'assigned_cc_id' => $cc->id,
            ],
            7 => [
                'stage' => 'inventory_check_done',
                'name'  => 'Stage 7 Test Client (Inventory Check Done)',
                'phone' => '+919876543207',
                'email' => 'client7.inventory@test.com',
                'contact_attempts' => 3,
                'first_contacted_at' => now()->subHours(30),
                'last_contacted_at'  => now()->subHours(15),
                'qualification_notes' => 'Qualified.',
                'options_shared_property_ids' => array_slice($propertyEntries, 0, 2),
                'handover_note' => 'Handover summary provided to CC team.',
                'handover_completed_at' => now()->subHours(10),
                'assigned_cc_id' => $cc->id,
                'feasibility_sh_id' => $sh->id,
                'feasibility_raised_at' => now()->subHours(4),
                'feasibility_status' => 'pending',
                'feasibility_notes' => 'Please confirm owner availability for site visit on Friday.',
            ],
            8 => [
                'stage' => 'site_visit_scheduled',
                'name'  => 'Stage 8 Test Client (Site Visit Scheduled)',
                'phone' => '+919876543208',
                'email' => 'client8.scheduled@test.com',
                'contact_attempts' => 3,
                'first_contacted_at' => now()->subHours(48),
                'last_contacted_at'  => now()->subHours(20),
                'qualification_notes' => 'Qualified.',
                'options_shared_property_ids' => array_slice($propertyEntries, 0, 2),
                'handover_note' => 'Handover complete.',
                'handover_completed_at' => now()->subHours(20),
                'assigned_cc_id' => $cc->id,
                'feasibility_sh_id' => $sh->id,
                'feasibility_raised_at' => now()->subHours(18),
                'feasibility_responded_at' => now()->subHours(12),
                'feasibility_status' => 'feasible',
                'feasibility_notes' => 'Owner confirmed ready for viewing.',
                'site_visit_date' => now()->addDay()->toDateString(),
                'visit_link_token' => 'test-token-stage-8-scheduled',
                'visit_link_sent_at' => now()->subHours(2),
                'visit_link_expires_at' => now()->addHours(22),
            ],
            9 => [
                'stage' => 'site_visit_completed',
                'name'  => 'Stage 9 Test Client (Site Visit Completed)',
                'phone' => '+919876543209',
                'email' => 'client9.completed@test.com',
                'contact_attempts' => 3,
                'first_contacted_at' => now()->subDays(3),
                'last_contacted_at'  => now()->subDays(1),
                'qualification_notes' => 'Qualified.',
                'options_shared_property_ids' => array_slice($propertyEntries, 0, 2),
                'handover_note' => 'Handover complete.',
                'handover_completed_at' => now()->subDays(2),
                'assigned_cc_id' => $cc->id,
                'feasibility_sh_id' => $sh->id,
                'feasibility_raised_at' => now()->subDays(2),
                'feasibility_responded_at' => now()->subDays(2),
                'feasibility_status' => 'feasible',
                'site_visit_date' => now()->subDay()->toDateString(),
                'site_visit_feedback' => 'Client very satisfied with location and power load. Ready for negotiation.',
            ],
            10 => [
                'stage' => 'negotiation',
                'name'  => 'Stage 10 Test Client (Negotiation)',
                'phone' => '+919876543210',
                'email' => 'client10.negotiation@test.com',
                'contact_attempts' => 4,
                'first_contacted_at' => now()->subDays(5),
                'last_contacted_at'  => now()->subDays(1),
                'qualification_notes' => 'Qualified.',
                'options_shared_property_ids' => array_slice($propertyEntries, 0, 2),
                'handover_note' => 'Handover complete.',
                'handover_completed_at' => now()->subDays(4),
                'assigned_cc_id' => $cc->id,
                'feasibility_sh_id' => $sh->id,
                'feasibility_raised_at' => now()->subDays(4),
                'feasibility_responded_at' => now()->subDays(3),
                'feasibility_status' => 'feasible',
                'site_visit_date' => now()->subDays(2)->toDateString(),
                'site_visit_feedback' => 'Visit positive.',
                'negotiation_notes' => 'Client offered ₹1.30L/mo. Owner agreed to ₹1.35L/mo with 3 months deposit.',
            ],
            11 => [
                'stage' => 'deal_closed',
                'name'  => 'Stage 11 Test Client (Deal Closed)',
                'phone' => '+919876543211',
                'email' => 'client11.dealclosed@test.com',
                'contact_attempts' => 5,
                'first_contacted_at' => now()->subDays(10),
                'last_contacted_at'  => now()->subDays(1),
                'qualification_notes' => 'Qualified.',
                'options_shared_property_ids' => array_slice($propertyEntries, 0, 2),
                'handover_note' => 'Handover complete.',
                'handover_completed_at' => now()->subDays(8),
                'assigned_cc_id' => $cc->id,
                'feasibility_sh_id' => $sh->id,
                'feasibility_raised_at' => now()->subDays(8),
                'feasibility_responded_at' => now()->subDays(7),
                'feasibility_status' => 'feasible',
                'site_visit_date' => now()->subDays(5)->toDateString(),
                'site_visit_feedback' => 'Visit positive.',
                'negotiation_notes' => 'Agreed at ₹1.35L/mo.',
                'deal_closed_at' => now()->subDays(1),
                'commission_amount' => 135000.00,
                'owner_notified_at' => now()->subDays(1),
                'reminder_6mo_at' => now()->addMonths(6),
            ],
        ];

        foreach ($stagesData as $id => $data) {
            $targetStage = $data['stage'];
            $targetIndex = array_search($targetStage, $stagesOrdered, true);

            $lead = Lead::create(array_merge([
                'id'                    => $id,
                'division'              => 'warehousing',
                'assigned_se_id'        => $se->id,
                'origin_table'          => 'seed',
                'needs_division_review' => false,
                'side_state'            => 'none',
            ], $data));

            // Create step-by-step history progression
            LeadStageHistory::create([
                'lead_id'            => $lead->id,
                'from_stage'         => 'new_lead',
                'to_stage'           => 'new_lead',
                'changed_by_user_id' => $admin->id,
                'note'               => "Lead auto-created & assigned to Sales Executive {$se->name}",
                'created_at'         => now()->subHours($targetIndex * 4 + 10),
            ]);

            for ($i = 1; $i <= $targetIndex; $i++) {
                $prevStage = $stagesOrdered[$i - 1];
                $currStage = $stagesOrdered[$i];
                $actorId   = ($i <= 4) ? $se->id : ($i == 6 ? $sh->id : $cc->id);

                LeadStageHistory::create([
                    'lead_id'            => $lead->id,
                    'from_stage'         => $prevStage,
                    'to_stage'           => $currStage,
                    'changed_by_user_id' => $actorId,
                    'note'               => "Advanced stage from " . ucwords(str_replace('_', ' ', $prevStage)) . " to " . ucwords(str_replace('_', ' ', $currStage)),
                    'created_at'         => now()->subHours(($targetIndex - $i) * 3 + 1),
                ]);
            }
        }

        $this->command->info("LeadSeeder: Cleared existing stage histories and successfully seeded 11 fresh leads (IDs 1 through 11).");
    }
}
