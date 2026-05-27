<?php

use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $actions = ['view', 'create', 'edit', 'delete'];
        $now = now();
        $permissionIds = [];

        foreach ($actions as $action) {
            $perm = Permission::create([
                'name'   => "seo-metas.{$action}",
                'module' => 'seo-metas',
                'action' => $action,
                'label'  => ucfirst($action) . ' SEO Metas',
            ]);
            $permissionIds[$action] = $perm->id;
        }

        // Grant all permissions to super_admin
        $rows = [];
        foreach ($permissionIds as $permId) {
            $rows[] = ['role' => 'super_admin', 'permission_id' => $permId, 'created_at' => $now, 'updated_at' => $now];
        }

        // Grant all permissions to admin
        foreach ($permissionIds as $permId) {
            $rows[] = ['role' => 'admin', 'permission_id' => $permId, 'created_at' => $now, 'updated_at' => $now];
        }

        // Grant view only to staff
        $rows[] = ['role' => 'staff', 'permission_id' => $permissionIds['view'], 'created_at' => $now, 'updated_at' => $now];

        DB::table('role_permissions')->insert($rows);
    }

    public function down(): void
    {
        $permIds = Permission::where('module', 'seo-metas')->pluck('id');
        DB::table('role_permissions')->whereIn('permission_id', $permIds)->delete();
        Permission::where('module', 'seo-metas')->delete();
    }
};
