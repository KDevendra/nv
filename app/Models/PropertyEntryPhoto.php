<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PropertyEntryPhoto extends Model
{
    protected $fillable = ['property_entry_id', 'slot_label', 'file_path'];

    public function entry(): BelongsTo
    {
        return $this->belongsTo(PropertyEntry::class, 'property_entry_id');
    }

    /**
     * Public URL accessor.
     */
    public function getUrlAttribute(): string
    {
        return asset($this->file_path);
    }
}
