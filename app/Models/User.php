<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    const ROLES = [
        'super_admin'       => 'Super Admin',
        'admin'             => 'Admin',
        'user'              => 'User',
        'supply_head'       => 'Supply Head',
        'field_officer'     => 'Field Officer',
        'owner'             => 'Owner',
        'channel_partner'   => 'Channel Partner',
        'sales_executive'   => 'Sales Executive',
        'chief_coordinator' => 'Chief Coordinator',
    ];

    /** Roles that require a division to be set. */
    const DIVISION_REQUIRED_ROLES = [
        'sales_executive',
        'chief_coordinator',
        'supply_head',
        'field_officer',
    ];

    const DIVISIONS = [
        'warehousing'  => 'Warehousing',
        'residential'  => 'Residential',
        'commercial'   => 'Commercial',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'division',
        'supply_head_id',
        'region_id',
        'area_id',
        'zone_id',
        'is_active',
        'can_approve_owner_listings',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'can_approve_owner_listings' => 'boolean',
        ];
    }

    /**
     * Get all permission names for this user's role (cached per request).
     */
    public function getPermissions(): array
    {
        return Cache::remember("permissions.role.{$this->role}", 3600, function () {
            return \DB::table('role_permissions')
                ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
                ->where('role_permissions.role', $this->role)
                ->pluck('permissions.name')
                ->toArray();
        });
    }

    /**
     * Check if the user has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->getPermissions());
    }

    /**
     * Get the supply head that this field officer reports to.
     */
    public function supplyHead()
    {
        return $this->belongsTo(User::class, 'supply_head_id');
    }

    /**
     * Get all field officers under this supply head.
     */
    public function fieldOfficers()
    {
        return $this->hasMany(User::class, 'supply_head_id');
    }

    /**
     * Get the region assigned to this user.
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Get the area assigned to this user.
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Get the single zone assigned to this user (field officers).
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    /**
     * Get every zone this supply head covers.
     */
    public function zones()
    {
        return $this->belongsToMany(Zone::class, 'supply_head_zone', 'user_id', 'zone_id')->withTimestamps();
    }

    /**
     * Get all property entries created by this field officer.
     */
    public function propertyEntries()
    {
        return $this->hasMany(\App\Models\PropertyEntry::class, 'field_officer_id');
    }

    /**
     * Get all property entries this user owns as supply head.
     */
    public function supplyHeadEntries()
    {
        return $this->hasMany(\App\Models\PropertyEntry::class, 'supply_head_id');
    }

    /**
     * Zone ids this user works across — the covered zones for a supply
     * head, the single assigned zone for everyone else.
     */
    public function zoneIds(): array
    {
        if ($this->isSupplyHead()) {
            return $this->zones()->pluck('zones.id')->all();
        }

        return $this->zone_id ? [$this->zone_id] : [];
    }

    /**
     * Check if this user is a supply head.
     */
    public function isSupplyHead(): bool
    {
        return $this->role === 'supply_head';
    }

    /**
     * Check if this user is a field officer.
     */
    public function isFieldOfficer(): bool
    {
        return $this->role === 'field_officer';
    }

    /**
     * Get all supply heads for dropdown options.
     */
    public static function getSupplyHeads()
    {
        return self::where('role', 'supply_head')->get();
    }

    /**
     * Check if the user is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Activate the user.
     */
    public function activate()
    {
        return $this->update(['is_active' => true]);
    }

    /**
     * Deactivate the user.
     */
    public function deactivate()
    {
        return $this->update(['is_active' => false]);
    }

    // ──────────────────────────────────────────────────────────────────────
    // Lead-pipeline role helpers
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Enforce division rules on every save.
     *  - Roles that require a division must have one set.
     *  - field_officer is always forced to 'warehousing'.
     */
    protected static function booted(): void
    {
        static::saving(function (self $user) {
            // Force field_officer division regardless of input
            if ($user->role === 'field_officer') {
                $user->division = 'warehousing';
            }

            // Require division for designated roles
            if (
                in_array($user->role, self::DIVISION_REQUIRED_ROLES, true)
                && empty($user->division)
            ) {
                throw new \InvalidArgumentException(
                    "Users with role '{$user->role}' must have a division assigned."
                );
            }
        });
    }

    public function isSalesExecutive(): bool
    {
        return $this->role === 'sales_executive';
    }

    public function isChiefCoordinator(): bool
    {
        return $this->role === 'chief_coordinator';
    }

    /**
     * Leads assigned to this user as Sales Executive.
     */
    public function assignedLeadsSE()
    {
        return $this->hasMany(\App\Models\Lead::class, 'assigned_se_id');
    }

    /**
     * Leads assigned to this user as Chief Coordinator.
     */
    public function assignedLeadsCC()
    {
        return $this->hasMany(\App\Models\Lead::class, 'assigned_cc_id');
    }

    /**
     * Count of active (non-held, non-lost) CC leads for this user.
     * Used for the 20-lead cap check.
     */
    public function activeCCLeadCount(): int
    {
        return $this->assignedLeadsCC()
            ->whereNotIn('stage', ['deal_closed'])
            ->where(function ($q) {
                $q->whereNull('side_state')->orWhere('side_state', '!=', 'lost');
            })
            ->count();
    }

    /**
     * Get all Supply Heads for a given division.
     */
    public static function getSupplyHeadsByDivision(string $division)
    {
        return self::where('role', 'supply_head')
            ->where('division', $division)
            ->where('is_active', true)
            ->get();
    }

    /**
     * Get all Sales Executives for a given division, optionally filtered by zone_id.
     */
    public static function getSalesExecutivesByDivision(string $division, ?int $zoneId = null)
    {
        $query = self::where('role', 'sales_executive')
            ->where('division', $division)
            ->where('is_active', true);

        if ($zoneId) {
            $query->where('zone_id', $zoneId);
        }

        return $query->get();
    }

    /**
     * Get all Chief Coordinators for a given division, ordered by current load, optionally filtered by zone_id.
     */
    public static function getChiefCoordinatorsByDivision(string $division, ?int $zoneId = null)
    {
        $query = self::where('role', 'chief_coordinator')
            ->where('division', $division)
            ->where('is_active', true);

        if ($zoneId) {
            $query->where('zone_id', $zoneId);
        }

        return $query->withCount([
                'assignedLeadsCC as active_cc_lead_count' => function ($q) {
                    $q->whereNotIn('stage', ['deal_closed'])
                        ->where(function ($sq) {
                            $sq->whereNull('side_state')->orWhere('side_state', '!=', 'lost');
                        });
                },
            ])
            ->orderBy('active_cc_lead_count')
            ->get();
    }

    /**
     * Get the role-appropriate dashboard URL for this user.
     */
    public function getDashboardUrl(): string
    {
        return match ($this->role) {
            'supply_head'                => route('supplyhead.properties.index'),
            'field_officer'              => route('field.dashboard'),
            'sales_executive'            => route('se.leads.index'),
            'chief_coordinator'          => route('cc.leads.index'),
            'owner'                      => route('owner.dashboard'),
            'channel_partner'            => route('channel_partner.dashboard'),
            'user'                       => route('user.dashboard'),
            default                      => route('dashboard'),
        };
    }
}
