<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::with(['supplyHead', 'region', 'area', 'zone', 'zones'])->withCount('fieldOfficers')->latest();

        // Add filters
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('supply_head_id')) {
            $query->where('supply_head_id', $request->supply_head_id);
        }

        if ($request->filled('zone_id')) {
            // Matches both the field officer's own zone and any supply head
            // covering that zone.
            $zoneId = $request->zone_id;
            $query->where(function ($q) use ($zoneId) {
                $q->where('zone_id', $zoneId)
                  ->orWhereHas('zones', fn($z) => $z->where('zones.id', $zoneId));
            });
        }

        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        if ($request->filled('area_id')) {
            $query->where('area_id', $request->area_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->boolean('status'));
        }
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        $users = $query->paginate(20);
        $supplyHeads = User::getSupplyHeads();
        $regions = \App\Models\Region::active()->ordered()->get();
        $areas = \App\Models\Area::active()->ordered()->get();
        $zones = \App\Models\Zone::active()->ordered()->get();

        return view('admin.users.index', compact('users', 'supplyHeads', 'regions', 'areas', 'zones'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $regions = \App\Models\Region::active()->ordered()->get();
        $zones = \App\Models\Zone::active()->ordered()->get();
        return view('admin.users.create', compact('regions', 'zones'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->rules());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'division' => $this->resolveDivision($request),
            // Field officers are routed to supply heads through their zone,
            // so no direct supply head is stored on the user any more.
            'supply_head_id' => null,
            'region_id' => $request->region_id,
            'area_id' => $request->area_id,
            'zone_id' => $request->role === 'field_officer' ? $request->zone_id : null,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
            'can_approve_owner_listings' => $request->role === 'supply_head' ? $request->boolean('can_approve_owner_listings') : false,
            'email_verified_at' => now(),
        ]);

        $this->syncSupplyHeadZones($user, $request);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Shared validation rules for creating and updating a user.
     * $user is passed on update so the unique email rule can ignore it.
     */
    private function rules(?User $user = null): array
    {
        $validRoles = implode(',', array_keys(User::ROLES));

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'max:255',
                $user ? Rule::unique('users')->ignore($user->id) : 'unique:users',
            ],
            'password' => $user
                ? ['nullable', 'string', 'min:8', 'confirmed']
                : ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:' . $validRoles],
            'division' => [
                'nullable',
                'in:' . implode(',', array_keys(User::DIVISIONS)),
                function ($attribute, $value, $fail) {
                    $role = request('role');
                    // field_officer is forced to warehousing by the model.
                    if ($role !== 'field_officer'
                        && in_array($role, User::DIVISION_REQUIRED_ROLES, true)
                        && empty($value)
                    ) {
                        $fail('A division is required for this role.');
                    }
                },
            ],
            'is_active' => ['boolean'],
            'region_id' => ['required', 'exists:regions,id'],
            'area_id' => ['required', 'exists:areas,id'],
            // Single zone — field officers only.
            'zone_id' => [
                'nullable',
                'exists:zones,id',
                function ($attribute, $value, $fail) {
                    if (request('role') === 'field_officer' && empty($value)) {
                        $fail('Field officers must be assigned to a zone.');
                    }
                },
            ],
            // Multiple zones — supply heads only.
            'zone_ids' => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    if (request('role') === 'supply_head' && empty($value)) {
                        $fail('Supply heads must be assigned to at least one zone.');
                    }
                },
            ],
            'zone_ids.*' => ['exists:zones,id'],
        ];
    }

    /**
     * Division to persist. Field officers are always warehousing (the User
     * model enforces this too); roles that don't need a division get null.
     */
    private function resolveDivision(Request $request): ?string
    {
        if ($request->role === 'field_officer') {
            return 'warehousing';
        }

        return in_array($request->role, User::DIVISION_REQUIRED_ROLES, true)
            ? $request->division
            : null;
    }

    /**
     * Keep the supply_head_zone pivot in step with the submitted zones.
     * Non-supply-head users never hold zone coverage, so their pivot rows
     * are cleared (e.g. when a supply head is switched to another role).
     */
    private function syncSupplyHeadZones(User $user, Request $request): void
    {
        $user->zones()->sync(
            $user->role === 'supply_head' ? ($request->input('zone_ids') ?? []) : []
        );
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['supplyHead', 'fieldOfficers', 'zone', 'zones']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $user->load(['zone', 'zones']);
        $regions = \App\Models\Region::active()->ordered()->get();
        $areas = \App\Models\Area::active()->ordered()->get();

        // Include zones already assigned to this user even if they have
        // since been deactivated, so editing doesn't silently drop them.
        $assignedZoneIds = $user->zones->pluck('id')->push($user->zone_id)->filter()->all();
        $zones = \App\Models\Zone::where(function ($q) use ($assignedZoneIds) {
            $q->where('status', true);
            if ($assignedZoneIds) {
                $q->orWhereIn('id', $assignedZoneIds);
            }
        })->ordered()->get();
        return view('admin.users.edit', compact('user', 'regions', 'areas', 'zones'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate($this->rules($user));

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'division' => $this->resolveDivision($request),
            'supply_head_id' => null,
            'region_id' => $request->region_id,
            'area_id' => $request->area_id,
            'zone_id' => $request->role === 'field_officer' ? $request->zone_id : null,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : $user->is_active,
            'can_approve_owner_listings' => $request->role === 'supply_head' ? $request->boolean('can_approve_owner_listings') : false,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        $this->syncSupplyHeadZones($user, $request);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting the current user
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        // Check if this supply head still owns property entries
        if ($user->isSupplyHead() && $user->supplyHeadEntries()->exists()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete supply head who has property entries assigned. Please reassign those entries first.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus(User $user)
    {
        // Prevent deactivating the current user
        if ($user->id === auth()->id() && $user->is_active) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot deactivate your own account.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('admin.users.index')
            ->with('success', "User has been {$status} successfully.");
    }
}