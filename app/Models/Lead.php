<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Lead extends Model
{
    use HasFactory;

    const STAGES = [
        'new_lead',
        'contacted',
        'qualified',
        'options_shared',
        'interest_confirmed',
        'escalated_to_cc',
        'inventory_check_done',
        'site_visit_scheduled',
        'site_visit_completed',
        'negotiation',
        'deal_closed',
    ];

    const SE_STAGES = [
        'new_lead',
        'contacted',
        'qualified',
        'options_shared',
        'interest_confirmed',
    ];

    const CC_STAGES = [
        'escalated_to_cc',
        'inventory_check_done',
        'site_visit_scheduled',
        'site_visit_completed',
        'negotiation',
        'deal_closed',
    ];

    const SIDE_STATES = ['none', 'inquiry_hold', 'follow_up_later', 'lost'];

    const DIVISIONS = ['warehousing', 'residential', 'commercial'];

    const CC_MAX_ACTIVE_LEADS = 20;

    protected $fillable = [
        'division',
        'name',
        'phone',
        'email',
        'property_id',
        'stage',
        'side_state',
        'pre_hold_status',
        'hold_started_at',
        'hold_expected_resume_date',
        'hold_reason',
        'follow_up_date',
        'lost_reason',
        'lost_reason_other',
        'assigned_se_id',
        'assigned_cc_id',
        'cc_load_at_assignment',
        'contact_attempts',
        'first_contacted_at',
        'contact_outcome',
        'qualification_notes',
        'options_shared_property_ids',
        'handover_note',
        'handover_completed_at',
        'feasibility_raised_at',
        'feasibility_sh_id',
        'feasibility_responded_at',
        'feasibility_notes',
        'visit_link_token',
        'visit_link_sent_at',
        'visit_link_expires_at',
        'visit_link_opened_at',
        'site_visit_date',
        'site_visit_feedback',
        'deal_closed_at',
        'commission_amount',
        'owner_notified_at',
        'reminder_6mo_at',
    ];

    protected $casts = [
        'options_shared_property_ids' => 'array',
        'hold_started_at'             => 'datetime',
        'hold_expected_resume_date'   => 'date',
        'follow_up_date'              => 'date',
        'first_contacted_at'          => 'datetime',
        'handover_completed_at'       => 'datetime',
        'feasibility_raised_at'       => 'datetime',
        'feasibility_responded_at'    => 'datetime',
        'visit_link_sent_at'          => 'datetime',
        'visit_link_expires_at'       => 'datetime',
        'visit_link_opened_at'        => 'datetime',
        'site_visit_date'             => 'date',
        'deal_closed_at'              => 'datetime',
        'owner_notified_at'           => 'datetime',
        'reminder_6mo_at'             => 'datetime',
        'commission_amount'           => 'decimal:2',
    ];

    // Relationships
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function assignedSE(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_se_id');
    }

    public function assignedCC(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_cc_id');
    }

    public function feasibilitySH(): BelongsTo
    {
        return $this->belongsTo(User::class, 'feasibility_sh_id');
    }

    public function stageHistories(): HasMany
    {
        return $this->hasMany(LeadStageHistory::class)->orderBy('id');
    }

    // Stage Engine
    public static function stageIndex(string $stage): int
    {
        $idx = array_search($stage, self::STAGES, true);
        return $idx === false ? -1 : (int) $idx;
    }

    public function canTransitionTo(string $newStage): bool
    {
        if (!in_array($newStage, self::STAGES, true)) {
            return false;
        }

        // Terminal lost state or inquiry hold blocks transitions
        if (in_array($this->side_state, ['inquiry_hold', 'lost'], true)) {
            return false;
        }

        $currentIdx = self::stageIndex($this->stage);
        $newIdx     = self::stageIndex($newStage);

        if ($newIdx <= $currentIdx) {
            return false; // forward-only
        }

        // Hard gate on escalation to CC
        if ($newStage === 'escalated_to_cc') {
            if (empty(trim((string) $this->handover_note)) || $this->handover_completed_at === null) {
                return false;
            }
        }

        return true;
    }

    public function transitionTo(string $newStage, ?User $actor = null): void
    {
        if (!$this->canTransitionTo($newStage)) {
            throw new \RuntimeException(
                "Cannot transition lead #{$this->id} from '{$this->stage}' to '{$newStage}'."
            );
        }

        // Role restrictions on transitions
        if ($actor && $actor->role !== 'admin' && $actor->role !== 'super_admin') {
            if ($actor->isSalesExecutive()) {
                // SE can only transition within SE stages or escalate to CC
                if (!in_array($newStage, array_merge(self::SE_STAGES, ['escalated_to_cc']), true)) {
                    throw new \RuntimeException("Sales Executives cannot transition lead to stage '{$newStage}'.");
                }
            } elseif ($actor->isChiefCoordinator()) {
                // CC cannot act on leads below escalated_to_cc
                if (!in_array($this->stage, self::CC_STAGES, true)) {
                    throw new \RuntimeException("Chief Coordinators cannot act on leads below 'escalated_to_cc' stage.");
                }
                // CC can only transition within CC stages
                if (!in_array($newStage, self::CC_STAGES, true)) {
                    throw new \RuntimeException("Chief Coordinators cannot transition lead to stage '{$newStage}'.");
                }
            }
        }

        $fromStage   = $this->stage;
        $this->stage = $newStage;

        if ($newStage === 'deal_closed') {
            $this->deal_closed_at = now();
        }

        // Auto-assign CC on escalation if not assigned yet
        if ($newStage === 'escalated_to_cc' && !$this->assigned_cc_id) {
            $this->assignBestCC();
        }

        $this->save();

        LeadStageHistory::create([
            'lead_id'            => $this->id,
            'from_stage'         => $fromStage,
            'to_stage'           => $newStage,
            'changed_by_user_id' => $actor?->id,
        ]);
    }

    // Side States
    public function putOnHold(string $reason, ?string $expectedResumeDate): void
    {
        if ($this->side_state === 'lost') {
            throw new \RuntimeException("Cannot put a lost lead on hold.");
        }

        if (empty($expectedResumeDate)) {
            throw new \InvalidArgumentException("Hold expected resume date is required.");
        }

        $resumeCarbon = Carbon::parse($expectedResumeDate);
        if ($resumeCarbon->isPast()) {
            throw new \InvalidArgumentException("Hold expected resume date must be in the future.");
        }

        if (now()->diffInDays($resumeCarbon, false) > 90) {
            throw new \InvalidArgumentException("Hold duration cannot exceed 90 days.");
        }

        $oldSideState = $this->side_state;

        $this->update([
            'side_state'                 => 'inquiry_hold',
            'pre_hold_status'            => $this->stage,
            'hold_started_at'            => now(),
            'hold_expected_resume_date'  => $resumeCarbon->toDateString(),
            'hold_reason'                => $reason,
        ]);

        LeadStageHistory::create([
            'lead_id'         => $this->id,
            'from_stage'      => $this->stage,
            'to_stage'        => $this->stage,
            'from_side_state' => $oldSideState,
            'to_side_state'   => 'inquiry_hold',
            'note'            => "Placed on hold: {$reason}",
        ]);
    }

    public function resumeFromHold(): void
    {
        if ($this->side_state !== 'inquiry_hold') {
            throw new \RuntimeException("Lead is not currently on hold.");
        }

        $oldSideState = $this->side_state;

        $this->update([
            'side_state'                => 'none',
            'pre_hold_status'           => null,
            'hold_started_at'           => null,
            'hold_expected_resume_date' => null,
            'hold_reason'               => null,
        ]);

        LeadStageHistory::create([
            'lead_id'         => $this->id,
            'from_stage'      => $this->stage,
            'to_stage'        => $this->stage,
            'from_side_state' => $oldSideState,
            'to_side_state'   => 'none',
            'note'            => "Resumed from hold",
        ]);
    }

    public function deferFollowUp(string $date): void
    {
        if ($this->side_state === 'lost') {
            throw new \RuntimeException("Cannot defer follow up for a lost lead.");
        }

        $oldSideState = $this->side_state;
        $followUpCarbon = Carbon::parse($date);
        $this->update([
            'side_state'      => 'follow_up_later',
            'follow_up_date'  => $followUpCarbon->toDateString(),
        ]);

        LeadStageHistory::create([
            'lead_id'         => $this->id,
            'from_stage'      => $this->stage,
            'to_stage'        => $this->stage,
            'from_side_state' => $oldSideState,
            'to_side_state'   => 'follow_up_later',
            'note'            => "Deferred follow-up to {$date}",
        ]);
    }

    public function markLost(string $reason, ?string $otherText = null): void
    {
        if ($this->side_state === 'lost') {
            throw new \RuntimeException("Lead is already marked as lost.");
        }

        $oldSideState = $this->side_state;
        $this->update([
            'side_state'        => 'lost',
            'lost_reason'       => $reason,
            'lost_reason_other' => $otherText,
        ]);

        LeadStageHistory::create([
            'lead_id'         => $this->id,
            'from_stage'      => $this->stage,
            'to_stage'        => $this->stage,
            'from_side_state' => $oldSideState,
            'to_side_state'   => 'lost',
            'note'            => "Marked as lost: {$reason}" . ($otherText ? " ({$otherText})" : ""),
        ]);
    }

    // Site Visit Expiring Link
    public function generateVisitLinkToken(): string
    {
        $token = Str::random(64);
        $this->update([
            'visit_link_token'      => $token,
            'visit_link_sent_at'    => now(),
            'visit_link_expires_at' => now()->addHours(24),
            'visit_link_opened_at'  => null,
        ]);
        return $token;
    }

    public function isVisitLinkValid(): bool
    {
        return !empty($this->visit_link_token)
            && $this->visit_link_opened_at === null
            && $this->visit_link_expires_at !== null
            && $this->visit_link_expires_at->isFuture();
    }

    public function consumeVisitLinkToken(): void
    {
        $this->update(['visit_link_opened_at' => now()]);
    }

    // CC Assignment & Cap (20)
    public function assignBestCC(): ?User
    {
        $ccs = User::getChiefCoordinatorsByDivision($this->division);

        foreach ($ccs as $cc) {
            if ($cc->active_cc_lead_count < self::CC_MAX_ACTIVE_LEADS) {
                $this->update([
                    'assigned_cc_id'        => $cc->id,
                    'cc_load_at_assignment' => $cc->active_cc_lead_count,
                ]);
                return $cc;
            }
        }

        // Holding queue if all CCs are at cap (assigned_cc_id remains null)
        return null;
    }

    // Info-Gating Public Property Snapshot
    public static function publicPropertySnapshot(?Property $property): ?array
    {
        if (!$property) {
            return null;
        }

        return [
            'id'             => $property->id,
            'title'          => $property->title,
            'slug'           => $property->slug,
            'price'          => $property->price,
            'formatted_price'=> $property->formatted_price,
            'price_per_sqft' => $property->price_per_sqft,
            'carpet_area'    => $property->carpet_area,
            'built_up_area'  => $property->built_up_area,
            'plot_area'      => $property->plot_area,
            'city'           => $property->city?->name,
            'location'       => $property->location?->name,
            'property_type'  => $property->propertyType?->name,
            'bhk'            => $property->bhk?->name,
            'main_image_url' => $property->main_image_url,
        ];
    }

    // Scopes
    public function scopeForDivision($query, string $division)
    {
        return $query->where('division', $division);
    }

    public function scopeHoldingQueue($query)
    {
        return $query->whereNull('assigned_cc_id')
                     ->where('stage', 'escalated_to_cc');
    }
}
