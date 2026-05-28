<?php

use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $permissionIds = [];

        foreach (['view', 'edit'] as $action) {
            $perm = Permission::create([
                'name'   => "video-tour.{$action}",
                'module' => 'video-tour',
                'action' => $action,
                'label'  => ucfirst($action) . ' Video Tour',
            ]);
            $permissionIds[$action] = $perm->id;
        }

        $rows = [];
        // super_admin & admin get all
        foreach (['super_admin', 'admin'] as $role) {
            foreach ($permissionIds as $permId) {
                $rows[] = ['role' => $role, 'permission_id' => $permId, 'created_at' => $now, 'updated_at' => $now];
            }
        }
        // staff gets view only
        $rows[] = ['role' => 'staff', 'permission_id' => $permissionIds['view'], 'created_at' => $now, 'updated_at' => $now];

        DB::table('role_permissions')->insert($rows);
    }

    public function down(): void
    {
        $permIds = Permission::where('module', 'video-tour')->pluck('id');
        DB::table('role_permissions')->whereIn('permission_id', $permIds)->delete();
        Permission::where('module', 'video-tour')->delete();
    }
};
