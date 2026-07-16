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
        // ── Platform ──────────────────────────────────────────────────────
        'super_admin'                    => 'Super Admin',
        'admin'                          => 'Admin',
        'user'                           => 'User', // Regular website users

        // ── Warehousing Division ──────────────────────────────────────────
        'chief_coordinator_warehousing'  => 'Chief Coordinator (Warehousing)',
        'sales_executive_warehousing'    => 'Sales Executive (Warehousing)',
        'supply_head'                    => 'Supply Head (Warehousing)',
        'field_officer'                  => 'Field Officer (Warehousing)',

        // ── Residential & Commercial Division ─────────────────────────────
        'chief_coordinator_rescomm'      => 'Chief Coordinator (Res/Comm)',
        'sales_executive_rescomm'        => 'Sales Executive (Res/Comm)',
        'supply_head_rescomm'            => 'Supply Head (Res/Comm)',
        'channel_partner'                => 'Zendo Channel Partner',
    ];

    /** Roles that belong to the Warehousing division. */
    const WAREHOUSING_ROLES = [
        'chief_coordinator_warehousing',
        'sales_executive_warehousing',
        'supply_head',
        'field_officer',
    ];

    /** Roles that belong to the Residential & Commercial division. */
    const RESCOMM_ROLES = [
        'chief_coordinator_rescomm',
        'sales_executive_rescomm',
        'supply_head_rescomm',
        'channel_partner',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'supply_head_id',
        'region_id',
        'area_id',
        'is_active',
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
     * Get all property entries created by this field officer.
     */
    public function propertyEntries()
    {
        return $this->hasMany(\App\Models\PropertyEntry::class, 'field_officer_id');
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
}
