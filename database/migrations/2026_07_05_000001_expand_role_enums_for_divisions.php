<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Expand the role enums on both users and role_permissions tables
 * to support the full Warehousing + Residential & Commercial division structure.
 *
 * New roles added (existing roles preserved):
 *   chief_coordinator_warehousing, chief_coordinator_rescomm
 *   sales_executive_warehousing,   sales_executive_rescomm
 *   supply_head_rescomm
 *   channel_partner
 */
return new class extends Migration
{
    /** Complete set of valid roles after this migration. */
    private const NEW_ROLES = [
        'super_admin',
        'admin',
        // Warehousing division
        'chief_coordinator_warehousing',
        'sales_executive_warehousing',
        'supply_head',          // existing — Warehousing Supply Head
        'field_officer',        // existing — Warehousing Field Officer
        // Residential & Commercial division
        'chief_coordinator_rescomm',
        'sales_executive_rescomm',
        'supply_head_rescomm',
        'channel_partner',
    ];

    public function up(): void
    {
        $roleList = implode("','", self::NEW_ROLES);

        // --- users table ---------------------------------------------------
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('{$roleList}') NOT NULL DEFAULT 'field_officer'");

        // --- role_permissions table ----------------------------------------
        // MySQL ENUM columns must list every value; re-declare the column.
        DB::statement("ALTER TABLE role_permissions MODIFY COLUMN role ENUM('{$roleList}') NOT NULL");
    }

    public function down(): void
    {
        // Revert to the previous set of four roles.
        // Any rows with a new role value must be removed first to avoid
        // a data-truncation error on revert.
        $oldRoles = ['super_admin', 'admin', 'supply_head', 'field_officer'];
        $oldList  = implode("','", $oldRoles);

        DB::table('role_permissions')
            ->whereNotIn('role', $oldRoles)
            ->delete();

        DB::table('users')
            ->whereNotIn('role', $oldRoles)
            ->update(['role' => 'field_officer']);

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('{$oldList}') NOT NULL DEFAULT 'field_officer'");
        DB::statement("ALTER TABLE role_permissions MODIFY COLUMN role ENUM('{$oldList}') NOT NULL");
    }
};
