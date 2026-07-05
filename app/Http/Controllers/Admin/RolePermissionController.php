<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
    private const ROLES = [
        // ── Platform ──────────────────────────────────────────────────────
        'super_admin'                    => 'Super Admin',
        'admin'                          => 'Admin',

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

    public function index()
    {
        $roles       = self::ROLES;
        $permissions = Permission::orderBy('module')->orderBy('action')->get()->groupBy('module');

        // Current assignments: role => [permission_name, ...]
        $assigned = DB::table('role_permissions')
            ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->select('role_permissions.role', 'permissions.name')
            ->get()
            ->groupBy('role')
            ->map(fn($rows) => $rows->pluck('name')->toArray());

        return view('admin.role-permissions.index', compact('roles', 'permissions', 'assigned'));
    }

    public function update(Request $request, string $role)
    {
        abort_unless(array_key_exists($role, self::ROLES), 404);

        $permissionNames = $request->input('permissions', []);

        // Resolve names to IDs
        $ids = Permission::whereIn('name', $permissionNames)->pluck('id');

        // Replace all permissions for this role
        DB::table('role_permissions')->where('role', $role)->delete();

        $rows = $ids->map(fn($id) => [
            'role'          => $role,
            'permission_id' => $id,
            'created_at'    => now(),
            'updated_at'    => now(),
        ])->values()->toArray();

        if (count($rows)) {
            DB::table('role_permissions')->insert($rows);
        }

        // Bust the permission cache for this role
        Cache::forget("permissions.role.{$role}");

        return back()->with('success', self::ROLES[$role] . ' permissions updated successfully.');
    }
}
