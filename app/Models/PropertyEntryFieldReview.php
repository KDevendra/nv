<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyEntryFieldReview extends Model
{
    protected $fillable = [
        'property_entry_id',
        'reviewed_by',
        'field_name',
        'field_label',
        'field_value',
        'is_correct',
        'remark',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function propertyEntry(): BelongsTo
    {
        return $this->belongsTo(PropertyEntry::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
