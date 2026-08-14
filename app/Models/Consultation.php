<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'inquiry_type',
        'source',
        'name',
        'phone',
        'email',
        'pincode',
        'property_type',
        'location',
        'budget_range',
        'message',
        'requirements',
        'status',
        'contacted_at',
        'admin_notes',
        'assigned_to',
        'priority'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'contacted_at' => 'datetime',
    ];

    // Status scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeContacted($query)
    {
        return $query->where('status', 'contacted');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    // Priority scopes
    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }

    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent');
    }

    // Inquiry type scopes
    public function scopeConsultations($query)
    {
        return $query->where('inquiry_type', 'consultation');
    }

    public function scopeInquiries($query)
    {
        return $query->where('inquiry_type', 'inquiry');
    }

    // Accessors
    public function getInitialsAttribute()
    {
        $names = explode(' ', $this->name);
        $initials = '';
        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }
        return $initials;
    }

    public function getFormattedStatusAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->status));
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'contacted' => 'bg-blue-100 text-blue-800',
            'in_progress' => 'bg-purple-100 text-purple-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getPriorityBadgeAttribute()
    {
        return match($this->priority) {
            'low' => 'bg-gray-100 text-gray-800',
            'medium' => 'bg-blue-100 text-blue-800',
            'high' => 'bg-orange-100 text-orange-800',
            'urgent' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getFormattedPriorityAttribute()
    {
        return ucfirst($this->priority);
    }

    public function getFormattedInquiryTypeAttribute()
    {
        return ucfirst($this->inquiry_type);
    }
}