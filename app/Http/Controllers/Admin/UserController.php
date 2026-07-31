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
        $query = User::with(['supplyHead', 'region', 'area'])->withCount('fieldOfficers')->latest();
        
        // Add filters
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        if ($request->filled('supply_head_id')) {
            $query->where('supply_head_id', $request->supply_head_id);
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
        
        return view('admin.users.index', compact('users', 'supplyHeads', 'regions', 'areas'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $supplyHeads = User::getSupplyHeads();
        $regions = \App\Models\Region::active()->ordered()->get();
        return view('admin.users.create', compact('supplyHeads', 'regions'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validRoles = implode(',', array_keys(User::ROLES));
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:' . $validRoles],
            'is_active' => ['boolean'],
            'region_id' => ['required', 'exists:regions,id'],
            'area_id' => ['required', 'exists:areas,id'],
            'supply_head_id' => [
                'nullable',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->role === 'field_officer' && empty($value)) {
                        $fail('Field officers must be assigned to a supply head.');
                    }
                    if ($request->role !== 'field_officer' && !empty($value)) {
                        $fail('Only field officers can be assigned to a supply head.');
                    }
                    if (!empty($value)) {
                        $supplyHead = User::find($value);
                        if (!$supplyHead || $supplyHead->role !== 'supply_head') {
                            $fail('Selected supply head is invalid.');
                        }
                    }
                }
            ],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'supply_head_id' => $request->role === 'field_officer' ? $request->supply_head_id : null,
            'region_id' => $request->region_id,
            'area_id' => $request->area_id,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
            'can_approve_owner_listings' => $request->role === 'supply_head' ? $request->boolean('can_approve_owner_listings') : false,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['supplyHead', 'fieldOfficers']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $supplyHeads = User::getSupplyHeads();
        $regions = \App\Models\Region::active()->ordered()->get();
        $areas = \App\Models\Area::active()->ordered()->get();
        return view('admin.users.edit', compact('user', 'supplyHeads', 'regions', 'areas'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validRoles = implode(',', array_keys(User::ROLES));
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:' . $validRoles],
            'is_active' => ['boolean'],
            'region_id' => ['required', 'exists:regions,id'],
            'area_id' => ['required', 'exists:areas,id'],
            'supply_head_id' => [
                'nullable',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($request, $user) {
                    if ($request->role === 'field_officer' && empty($value)) {
                        $fail('Field officers must be assigned to a supply head.');
                    }
                    if ($request->role !== 'field_officer' && !empty($value)) {
                        $fail('Only field officers can be assigned to a supply head.');
                    }
                    if (!empty($value)) {
                        $supplyHead = User::find($value);
                        if (!$supplyHead || $supplyHead->role !== 'supply_head') {
                            $fail('Selected supply head is invalid.');
                        }
                        // Prevent circular reference
                        if ($value == $user->id) {
                            $fail('User cannot be assigned to themselves.');
                        }
                    }
                }
            ],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'supply_head_id' => $request->role === 'field_officer' ? $request->supply_head_id : null,
            'region_id' => $request->region_id,
            'area_id' => $request->area_id,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : $user->is_active,
            'can_approve_owner_listings' => $request->role === 'supply_head' ? $request->boolean('can_approve_owner_listings') : false,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

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

        // Check if this supply head has field officers assigned
        if ($user->isSupplyHead() && $user->fieldOfficers()->exists()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete supply head who has field officers assigned. Please reassign field officers first.');
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