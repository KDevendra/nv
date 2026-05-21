<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutPageSection extends Model
{
    protected $fillable = [
        'section_title',
        'section_subtitle',
        'who_we_are_title',
        'who_we_are_description',
        'who_we_are_icon',
        'mission_title',
        'mission_description',
        'mission_icon',
        'vision_title',
        'vision_description',
        'vision_icon',
        'values_heading',
        'values_who_we_are',
        'values_who_we_are_image',
        'values_mission',
        'values_mission_image',
        'values_vision',
        'values_vision_image',
        'values_teamwork',
        'values_teamwork_image',
        'team_section_title',
        'team_section_heading',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function getActive()
    {
        return self::active()->first();
    }

    public function getWhoWeAreIconUrlAttribute()
    {
        return $this->who_we_are_icon ? asset('storage/' . $this->who_we_are_icon) : null;
    }

    public function getMissionIconUrlAttribute()
    {
        return $this->mission_icon ? asset('storage/' . $this->mission_icon) : null;
    }

    public function getVisionIconUrlAttribute()
    {
        return $this->vision_icon ? asset('storage/' . $this->vision_icon) : null;
    }

    public function getValuesWhoWeAreImageUrlAttribute()
    {
        return $this->values_who_we_are_image ? asset($this->values_who_we_are_image) : null;
    }

    public function getValuesMissionImageUrlAttribute()
    {
        return $this->values_mission_image ? asset($this->values_mission_image) : null;
    }

    public function getValuesVisionImageUrlAttribute()
    {
        return $this->values_vision_image ? asset($this->values_vision_image) : null;
    }

    public function getValuesTeamworkImageUrlAttribute()
    {
        return $this->values_teamwork_image ? asset($this->values_teamwork_image) : null;
    }
}
