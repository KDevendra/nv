<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use App\Models\User;

class RolePermissionController extends Controller
{
    private function getRoles(): array
    {
        return User::ROLES;
    }

    public function index()
    {
        $roles       = $this->getRoles();
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
        $roles = $this->getRoles();
        abort_unless(array_key_exists($role, $roles), 404);

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

        return back()->with('success', $roles[$role] . ' permissions updated successfully.');
    }
}
