<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Property extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'hidden_details',
        'show_hidden_details',
        'property_type_id',
        'bhk_id',
        'city_id',
        'location_id',
        'project_status_id',
        'builder_id',
        'price',
        'price_per_sqft',
        'carpet_area',
        'built_up_area',
        'plot_area',
        'address',
        'latitude',
        'longitude',
        'map_embed_code',
        'video_path',
        'youtube_url',
        'is_featured',
        'is_verified',
        'is_active',
        'views_count',
        'user_id',
        'published_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_per_sqft' => 'decimal:2',
        'carpet_area' => 'decimal:2',
        'built_up_area' => 'decimal:2',
        'plot_area' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'show_hidden_details' => 'boolean',
        'published_at' => 'datetime',
    ];

    // Relationships
    public function propertyType(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function bhk(): BelongsTo
    {
        return $this->belongsTo(Bhk::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function projectStatus(): BelongsTo
    {
        return $this->belongsTo(ProjectStatus::class);
    }

    public function builder(): BelongsTo
    {
        return $this->belongsTo(Builder::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class)->orderBy('display_order');
    }

    public function mainImage(): HasOne
    {
        return $this->hasOne(PropertyImage::class)->where('image_type', 'main');
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'property_amenities')
                    ->withTimestamps();
    }

    public function specifications(): HasOne
    {
        return $this->hasOne(PropertySpecification::class);
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(PropertyInquiry::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(PropertyView::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(PropertyFavorite::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(PropertyFaq::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopeFilterByCity($query, $cityId)
    {
        if ($cityId) {
            return $query->where('city_id', $cityId);
        }
        return $query;
    }

    public function scopeFilterByLocation($query, $locationId)
    {
        if ($locationId) {
            return $query->where('location_id', $locationId);
        }
        return $query;
    }

    public function scopeFilterByPropertyType($query, $propertyTypeId)
    {
        if ($propertyTypeId) {
            return $query->where('property_type_id', $propertyTypeId);
        }
        return $query;
    }

    public function scopeFilterByBhk($query, $bhkId)
    {
        if ($bhkId) {
            return $query->where('bhk_id', $bhkId);
        }
        return $query;
    }

    public function scopeFilterByProjectStatus($query, $statusId)
    {
        if ($statusId) {
            return $query->where('project_status_id', $statusId);
        }
        return $query;
    }

    public function scopeFilterByBuilder($query, $builderId)
    {
        if ($builderId) {
            return $query->where('builder_id', $builderId);
        }
        return $query;
    }

    public function scopeFilterByPriceRange($query, $minPrice, $maxPrice)
    {
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }
        return $query;
    }

    public function scopeSearch($query, $searchTerm)
    {
        if ($searchTerm) {
            return $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('address', 'LIKE', "%{$searchTerm}%");
            });
        }
        return $query;
    }

    // Accessors
    public function getFormattedPriceAttribute(): string
    {
        if ($this->price >= 10000000) {
            return '₹' . number_format($this->price / 10000000, 2) . ' Cr';
        } elseif ($this->price >= 100000) {
            return '₹' . number_format($this->price / 100000, 2) . ' Lac';
        }
        return '₹' . number_format($this->price, 2);
    }

    public function getMainImageUrlAttribute(): ?string
    {
        $imagePath = $this->mainImage?->image_path ?? $this->images->first()?->image_path;
        
        if ($imagePath) {
            return asset($imagePath);
        }
        
        return null;
    }

    // Helper Methods
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    public function isFavoritedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }
        return $this->favorites()->where('user_id', $user->id)->exists();
    }
}
