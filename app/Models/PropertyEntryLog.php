<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyEntryLog extends Model
{
    protected $fillable = [
        'property_entry_id',
        'user_id',
        'action',
        'old_status',
        'new_status',
        'note',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function propertyEntry(): BelongsTo
    {
        return $this->belongsTo(PropertyEntry::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log an action on a property entry
     */
    public static function logAction(
        PropertyEntry $entry,
        string $action,
        ?string $oldStatus = null,
        ?string $newStatus = null,
        ?string $note = null,
        ?array $changes = null
    ): self {
        return self::create([
            'property_entry_id' => $entry->id,
            'user_id' => auth()->id(),
            'action' => $action,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'note' => $note,
            'changes' => $changes,
        ]);
    }
}
