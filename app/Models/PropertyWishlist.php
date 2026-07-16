<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PropertyWishlist extends Model
{
    protected $fillable = [
        'user_id',
        'property_id',
        'property_entry_code',
    ];

    /**
     * Get the user that owns the wishlist item.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the property associated with this wishlist item.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the property entry associated with this wishlist item.
     * Uses a hasOne through the code column.
     */
    public function propertyEntry()
    {
        return $this->hasOne(PropertyEntry::class, 'code', 'property_entry_code');
    }

    /**
     * Check if a specific property is in user's wishlist.
     */
    public static function isInWishlist(?int $userId, ?int $propertyId = null, ?string $propertyEntryCode = null): bool
    {
        if (!$userId) {
            return false;
        }

        $query = static::where('user_id', $userId);

        if ($propertyId) {
            $query->where('property_id', $propertyId);
        }

        if ($propertyEntryCode) {
            $query->where('property_entry_code', $propertyEntryCode);
        }

        return $query->exists();
    }
}
