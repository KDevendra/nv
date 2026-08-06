<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    // ──────────────────────────────────────────────────────────────────────
    // Constants
    // ──────────────────────────────────────────────────────────────────────

    /** Ordered pipeline stages — index determines forward-only enforcement. */
    const STAGES = [
        'new_lead',
        'contacted',
        'interest_confirmed',
        'escalated_to_cc',
        'feasibility_check',
        'options_shared',
        'site_visit_scheduled',
        'site_visit_done',
        'negotiation',
        'deal_closed',
    ];

    /** Stages owned exclusively by the Sales Executive (panel 1). */
    const SE_STAGES = [
        'new_lead',
        'contacted',
        'interest_confirmed',
    ];

    /** Stages owned exclusively by the Chief Coordinator (panel 2). */
    const CC_STAGES = [
        'escalated_to_cc',
        'feasibility_check',
        'options_shared',
        'site_visit_scheduled',
        'site_visit_done',
        'negotiation',
        'deal_closed',
    ];

    /** Side-states — orthogonal to stage. */
    const SIDE_STATES = ['on_hold', 'deferred', 'lost'];

    const DIVISIONS  = ['warehousing', 'residential', 'commercial'];

    const FEASIBILITY_STATUSES = ['pending', 'feasible', 'not_feasible', 'conditional'];

    /** Maximum active CC leads before overflow goes to the holding queue. */
    const CC_MAX_ACTIVE_LEADS = 20;

    // ──────────────────────────────────────────────────────────────────────
    // Eloquent config
    // ──────────────────────────────────────────────────────────────────────

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
        'hold_until_date',
        'hold_ended_at',
        'deferred_until',
        'lost_reason',
        'lost_at',
        'assigned_se_id',
        'assigned_cc_id',
        'cc_load_at_assignment',
        'se_assigned_at',
        'cc_assigned_at',
        'contact_attempts',
        'last_contacted_at',
        'qualification_notes',
        'options_shared_property_ids',
        'options_shared_at',
        'handover_note',
        'handover_completed_at',
        'feasibility_requested_at',
        'feasibility_sh_id',
        'feasibility_status',
        'feasibility_notes',
        'feasibility_responded_at',
        'site_visit_token',
        'site_visit_token_expires_at',
        'site_visit_token_opened_at',
        'site_visit_scheduled_at',
        'site_visit_feedback',
        'site_visit_done_at',
        'negotiation_notes',
        'deal_value',
        'deal_notes',
        'deal_closed_at',
        'sla_contact_due_at',
        'sla_contact_breached',
        'sla_feasibility_due_at',
        'sla_feasibility_breached',
        'origin_table',
        'origin_id',
        'needs_division_review',
    ];

    protected $casts = [
        'options_shared_property_ids'   => 'array',
        'hold_started_at'               => 'datetime',
        'hold_ended_at'                 => 'datetime',
        'hold_until_date'               => 'date',
        'deferred_until'                => 'datetime',
        'lost_at'                       => 'datetime',
        'se_assigned_at'                => 'datetime',
        'cc_assigned_at'                => 'datetime',
        'last_contacted_at'             => 'datetime',
        'options_shared_at'             => 'datetime',
        'handover_completed_at'         => 'datetime',
        'feasibility_requested_at'      => 'datetime',
        'feasibility_responded_at'      => 'datetime',
        'site_visit_token_expires_at'   => 'datetime',
        'site_visit_token_opened_at'    => 'datetime',
        'site_visit_scheduled_at'       => 'datetime',
        'site_visit_done_at'            => 'datetime',
        'deal_closed_at'                => 'datetime',
        'sla_contact_due_at'            => 'datetime',
        'sla_feasibility_due_at'        => 'datetime',
        'sla_contact_breached'          => 'boolean',
        'sla_feasibility_breached'      => 'boolean',
        'needs_division_review'         => 'boolean',
        'deal_value'                    => 'decimal:2',
    ];

    // ──────────────────────────────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────────────────────────────

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

    // ──────────────────────────────────────────────────────────────────────
    // Stage transition logic
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Return the numeric index of a stage (or -1 if unknown).
     */
    public static function stageIndex(string $stage): int
    {
        $idx = array_search($stage, self::STAGES, true);
        return $idx === false ? -1 : (int) $idx;
    }

    /**
     * Check whether transitioning to $newStage is allowed.
     *
     * Rules:
     *  - Must be a recognised stage.
     *  - Must be strictly forward (higher index than current).
     *  - Cannot transition while on a side-state (hold/lost/deferred).
     *  - Hard gate: escalated_to_cc requires handover_note & handover_completed_at.
     */
    public function canTransitionTo(string $newStage): bool
    {
        if (!in_array($newStage, self::STAGES, true)) {
            return false;
        }

        if ($this->side_state !== null) {
            return false; // must resume/un-hold before advancing
        }

        $currentIdx = self::stageIndex($this->stage);
        $newIdx     = self::stageIndex($newStage);

        if ($newIdx <= $currentIdx) {
            return false; // forward-only
        }

        // Hard gate: escalated_to_cc
        if ($newStage === 'escalated_to_cc') {
            if (empty($this->handover_note) || empty($this->handover_completed_at)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Perform the stage transition, record history, and persist.
     *
     * @throws \RuntimeException if transition is not allowed.
     */
    public function transitionTo(string $newStage, ?string $note = null, ?User $actor = null): void
    {
        if (!$this->canTransitionTo($newStage)) {
            throw new \RuntimeException(
                "Cannot transition lead #{$this->id} from '{$this->stage}' to '{$newStage}'."
            );
        }

        $fromStage = $this->stage;
        $this->stage = $newStage;

        // Stamp timestamps for key transitions
        match ($newStage) {
            'deal_closed'          => $this->deal_closed_at          = now(),
            'site_visit_done'      => $this->site_visit_done_at      = now(),
            'site_visit_scheduled' => $this->site_visit_scheduled_at = $this->site_visit_scheduled_at ?? now(),
            'feasibility_check'    => $this->feasibility_requested_at = $this->feasibility_requested_at ?? now(),
            default                => null,
        };

        $this->save();

        LeadStageHistory::create([
            'lead_id'            => $this->id,
            'from_stage'         => $fromStage,
            'to_stage'           => $newStage,
            'note'               => $note,
            'changed_by_user_id' => $actor?->id,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────
    // Side-state methods
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Put the lead on hold. Saves the current stage as pre_hold_status.
     */
    public function putOnHold(?string $reason = null, ?\DateTimeInterface $until = null, ?User $actor = null): void
    {
        if ($this->side_state === 'lost') {
            throw new \RuntimeException("Cannot hold a lost lead.");
        }

        $fromSideState    = $this->side_state;
        $this->side_state       = 'on_hold';
        $this->pre_hold_status  = $this->stage;
        $this->hold_started_at  = now();
        $this->hold_until_date  = $until ? \Carbon\Carbon::instance($until)->toDateString() : null;
        $this->hold_ended_at    = null;
        $this->save();

        LeadStageHistory::create([
            'lead_id'            => $this->id,
            'from_stage'         => $this->stage,
            'to_stage'           => $this->stage,
            'from_side_state'    => $fromSideState,
            'to_side_state'      => 'on_hold',
            'note'               => $reason,
            'changed_by_user_id' => $actor?->id,
        ]);
    }

    /**
     * Resume a lead that is on hold or deferred.
     * Restores stage from pre_hold_status.
     */
    public function resumeFromHold(?User $actor = null): void
    {
        if (!in_array($this->side_state, ['on_hold', 'deferred'], true)) {
            throw new \RuntimeException("Lead is not on hold or deferred.");
        }

        $fromSideState    = $this->side_state;
        $this->hold_ended_at = now();
        $this->side_state    = null;
        $this->save();

        LeadStageHistory::create([
            'lead_id'            => $this->id,
            'from_stage'         => $this->stage,
            'to_stage'           => $this->stage,
            'from_side_state'    => $fromSideState,
            'to_side_state'      => null,
            'note'               => 'Resumed from ' . $fromSideState,
            'changed_by_user_id' => $actor?->id,
        ]);
    }

    /**
     * Defer follow-up to a future datetime.
     */
    public function deferFollowUp(\DateTimeInterface $until, ?string $reason = null, ?User $actor = null): void
    {
        if ($this->side_state === 'lost') {
            throw new \RuntimeException("Cannot defer a lost lead.");
        }

        $fromSideState      = $this->side_state;
        $this->side_state   = 'deferred';
        $this->pre_hold_status = $this->stage;
        $this->deferred_until  = \Carbon\Carbon::instance($until);
        $this->save();

        LeadStageHistory::create([
            'lead_id'            => $this->id,
            'from_stage'         => $this->stage,
            'to_stage'           => $this->stage,
            'from_side_state'    => $fromSideState,
            'to_side_state'      => 'deferred',
            'note'               => $reason ?? ('Deferred until ' . $this->deferred_until->toDateTimeString()),
            'changed_by_user_id' => $actor?->id,
        ]);
    }

    /**
     * Mark the lead as lost.
     */
    public function markLost(string $reason, ?User $actor = null): void
    {
        $fromSideState    = $this->side_state;
        $this->side_state = 'lost';
        $this->lost_reason = $reason;
        $this->lost_at     = now();
        $this->save();

        LeadStageHistory::create([
            'lead_id'            => $this->id,
            'from_stage'         => $this->stage,
            'to_stage'           => $this->stage,
            'from_side_state'    => $fromSideState,
            'to_side_state'      => 'lost',
            'note'               => $reason,
            'changed_by_user_id' => $actor?->id,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────
    // Site-visit token
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Generate a fresh single-use 24-hour site-visit token.
     * Invalidates any prior token.
     */
    public function generateSiteVisitToken(): string
    {
        $token = Str::random(64);
        $this->update([
            'site_visit_token'            => $token,
            'site_visit_token_expires_at' => now()->addHours(24),
            'site_visit_token_opened_at'  => null,
        ]);
        return $token;
    }

    /**
     * Check whether the site-visit token is still valid (not expired, not opened).
     */
    public function isSiteVisitTokenValid(): bool
    {
        return $this->site_visit_token !== null
            && $this->site_visit_token_opened_at === null
            && $this->site_visit_token_expires_at !== null
            && $this->site_visit_token_expires_at->isFuture();
    }

    /**
     * Consume the token — marks it as opened (single-use invalidation).
     */
    public function consumeSiteVisitToken(): void
    {
        $this->update(['site_visit_token_opened_at' => now()]);
    }

    // ──────────────────────────────────────────────────────────────────────
    // Info-gating helper
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Return a property snapshot safe for SE/CC consumption.
     * Strips owner details, full address, and GPS coordinates.
     *
     * @param  Property|null  $property  Defaults to $this->property if null.
     * @return array
     */
    public function publicPropertySnapshot(?Property $property = null): array
    {
        $p = $property ?? $this->property;

        if (!$p) {
            return [];
        }

        return [
            'id'            => $p->id,
            'title'         => $p->title,
            'slug'          => $p->slug,
            'city'          => $p->city?->name,
            'location'      => $p->location?->name,
            'property_type' => $p->propertyType?->name,
            'bhk'           => $p->bhk?->name,
            'price'         => $p->price,
            'price_per_sqft'=> $p->price_per_sqft,
            'carpet_area'   => $p->carpet_area,
            'built_up_area' => $p->built_up_area,
            'is_featured'   => $p->is_featured,
            'is_verified'   => $p->is_verified,
            // address, latitude, longitude, map_embed_code intentionally excluded
            // user_id (owner) intentionally excluded
        ];
    }

    // ──────────────────────────────────────────────────────────────────────
    // CC assignment helper
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Assign the best available CC for this lead's division.
     * Returns the assigned User, or null if all CCs are at cap (holding queue).
     */
    public function assignBestCC(): ?User
    {
        $ccs = User::getChiefCoordinatorsByDivision($this->division);

        foreach ($ccs as $cc) {
            if ($cc->active_cc_lead_count < self::CC_MAX_ACTIVE_LEADS) {
                $this->update([
                    'assigned_cc_id'        => $cc->id,
                    'cc_load_at_assignment' => $cc->active_cc_lead_count,
                    'cc_assigned_at'        => now(),
                ]);
                return $cc;
            }
        }

        // All CCs at cap — leave assigned_cc_id null (holding queue)
        return null;
    }

    // ──────────────────────────────────────────────────────────────────────
    // Query scopes
    // ──────────────────────────────────────────────────────────────────────

    /** Leads actively progressing (no side-state). */
    public function scopeActive($query)
    {
        return $query->whereNull('side_state');
    }

    /** Leads in the holding queue (no CC assigned, escalated but waiting). */
    public function scopeHoldingQueue($query)
    {
        return $query->whereNull('assigned_cc_id')
                     ->where('stage', 'escalated_to_cc');
    }

    public function scopeForDivision($query, string $division)
    {
        return $query->where('division', $division);
    }

    public function scopeForSE($query, int $userId)
    {
        return $query->where('assigned_se_id', $userId);
    }

    public function scopeForCC($query, int $userId)
    {
        return $query->where('assigned_cc_id', $userId);
    }

    public function scopeNeedsReview($query)
    {
        return $query->where('needs_division_review', true);
    }

    // ──────────────────────────────────────────────────────────────────────
    // Accessors
    // ──────────────────────────────────────────────────────────────────────

    public function getStageLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->stage));
    }

    public function getSideStateLabelAttribute(): string
    {
        return $this->side_state ? ucwords(str_replace('_', ' ', $this->side_state)) : 'Active';
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->side_state === null;
    }

    public function getIsOnHoldAttribute(): bool
    {
        return $this->side_state === 'on_hold';
    }

    public function getIsLostAttribute(): bool
    {
        return $this->side_state === 'lost';
    }

    public function getIsDeferredAttribute(): bool
    {
        return $this->side_state === 'deferred';
    }

    /**
     * Stage badge colour classes for Tailwind.
     */
    public function getStageBadgeAttribute(): string
    {
        return match($this->stage) {
            'new_lead'             => 'bg-gray-100 text-gray-700',
            'contacted'            => 'bg-blue-100 text-blue-700',
            'interest_confirmed'   => 'bg-indigo-100 text-indigo-700',
            'escalated_to_cc'      => 'bg-purple-100 text-purple-700',
            'feasibility_check'    => 'bg-yellow-100 text-yellow-700',
            'options_shared'       => 'bg-orange-100 text-orange-700',
            'site_visit_scheduled' => 'bg-cyan-100 text-cyan-700',
            'site_visit_done'      => 'bg-teal-100 text-teal-700',
            'negotiation'          => 'bg-amber-100 text-amber-700',
            'deal_closed'          => 'bg-green-100 text-green-700',
            default                => 'bg-gray-100 text-gray-700',
        };
    }

    public function getSideStateBadgeAttribute(): string
    {
        return match($this->side_state) {
            'on_hold'  => 'bg-red-100 text-red-700',
            'deferred' => 'bg-yellow-100 text-yellow-700',
            'lost'     => 'bg-gray-200 text-gray-500',
            default    => 'bg-emerald-100 text-emerald-700',
        };
    }
}
