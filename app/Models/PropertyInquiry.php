<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyInquiry extends Model
{
    protected $fillable = [
        'property_id',
        'property_entry_code',
        'page_visit_id',
        'name',
        'email',
        'phone',
        'message',
        'inquiry_type',
        'status',
        'ip_address',
    ];

    // Relationships
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function pageVisit(): BelongsTo
    {
        return $this->belongsTo(PageVisit::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeContacted($query)
    {
        return $query->where('status', 'contacted');
    }

    public function scopeInterested($query)
    {
        return $query->where('status', 'interested');
    }

    public function scopeNotInterested($query)
    {
        return $query->where('status', 'not_interested');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    /**
     * Get daily inquiry submissions for the last N days
     */
    public static function getDailySubmissions($days = 7)
    {
        return self::select(
                        \DB::raw('DATE(created_at) as date'),
                        \DB::raw('COUNT(*) as submissions')
                    )
                    ->where('created_at', '>=', now()->subDays($days))
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
    }

    // Accessors
    public function getFormattedInquiryTypeAttribute(): string
    {
        return match($this->inquiry_type) {
            'site_visit' => 'Site Visit',
            'call_back' => 'Call Back',
            'email_info' => 'Email Info',
            'popup_inquiry' => 'Popup Inquiry',
            'general' => 'General',
            default => 'General',
        };
    }

    public function getFormattedStatusAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'contacted' => 'Contacted',
            'interested' => 'Interested',
            'not_interested' => 'Not Interested',
            'closed' => 'Closed',
            default => 'Pending',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'contacted' => 'bg-blue-100 text-blue-800',
            'interested' => 'bg-green-100 text-green-800',
            'not_interested' => 'bg-red-100 text-red-800',
            'closed' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }
}
