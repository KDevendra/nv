<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadStageHistory extends Model
{
    public $timestamps = false; // table has only created_at (set via useCurrent())

    protected $fillable = [
        'lead_id',
        'from_stage',
        'to_stage',
        'from_side_state',
        'to_side_state',
        'note',
        'changed_by_user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────────

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }

    // ── Accessors ──────────────────────────────────────────────────────────

    public function getFromStageLabelAttribute(): string
    {
        return $this->from_stage
            ? ucwords(str_replace('_', ' ', $this->from_stage))
            : '—';
    }

    public function getToStageLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->to_stage));
    }

    public function getIsSideStateChangeAttribute(): bool
    {
        return $this->from_side_state !== $this->to_side_state;
    }
}
