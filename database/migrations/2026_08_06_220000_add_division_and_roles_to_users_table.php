<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const ROLES = [
        'super_admin',
        'admin',
        'user',
        'supply_head',
        'field_officer',
        'owner',
        'channel_partner',
        'sales_executive',
        'chief_coordinator',
    ];

    public function up(): void
    {
        $roleList = implode("','", self::ROLES);

        DB::table('role_permissions')->whereNotIn('role', self::ROLES)->delete();
        DB::table('users')->whereNotIn('role', self::ROLES)->update(['role' => 'user']);

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('{$roleList}') NOT NULL DEFAULT 'user'");
        DB::statement("ALTER TABLE role_permissions MODIFY COLUMN role ENUM('{$roleList}') NOT NULL");
        DB::statement("ALTER TABLE property_types MODIFY COLUMN category ENUM('residential', 'commercial', 'warehousing') NULL");

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'division')) {
                $table->enum('division', ['warehousing', 'residential', 'commercial'])->nullable()->after('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'division')) {
                $table->dropColumn('division');
            }
        });

        $oldRoles = [
            'super_admin',
            'admin',
            'user',
            'supply_head',
            'field_officer',
            'owner',
            'channel_partner',
        ];
        $roleList = implode("','", $oldRoles);

        DB::table('users')
            ->whereNotIn('role', $oldRoles)
            ->update(['role' => 'user']);

        DB::table('role_permissions')
            ->whereNotIn('role', $oldRoles)
            ->delete();

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('{$roleList}') NOT NULL DEFAULT 'user'");
        DB::statement("ALTER TABLE role_permissions MODIFY COLUMN role ENUM('{$roleList}') NOT NULL");
    }
};
