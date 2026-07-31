<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const ALLOWED_ROLES = [
        'super_admin',
        'admin',
        'user',
        'supply_head',
        'field_officer',
        'owner',
        'channel_partner',
    ];

    public function up(): void
    {
        // 1. Remove permissions for removed roles
        DB::table('role_permissions')
            ->whereNotIn('role', self::ALLOWED_ROLES)
            ->delete();

        // 2. Reassign any users with removed roles to 'user'
        DB::table('users')
            ->whereNotIn('role', self::ALLOWED_ROLES)
            ->update(['role' => 'user']);

        // 3. Alter role ENUM on users and role_permissions tables
        $roleList = implode("','", self::ALLOWED_ROLES);
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('{$roleList}') NOT NULL DEFAULT 'user'");
        DB::statement("ALTER TABLE role_permissions MODIFY COLUMN role ENUM('{$roleList}') NOT NULL");
    }

    public function down(): void
    {
        $previousRoles = [
            'super_admin',
            'admin',
            'chief_coordinator_warehousing',
            'sales_executive_warehousing',
            'supply_head',
            'field_officer',
            'chief_coordinator_rescomm',
            'sales_executive_rescomm',
            'supply_head_rescomm',
            'channel_partner',
            'user',
            'owner',
        ];
        $roleList = implode("','", $previousRoles);
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('{$roleList}') NOT NULL DEFAULT 'user'");
        DB::statement("ALTER TABLE role_permissions MODIFY COLUMN role ENUM('{$roleList}') NOT NULL");
    }
};
