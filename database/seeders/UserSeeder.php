<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Seeds 2 testing users per role.
 *
 * Naming convention : testing_<role>_<n>
 * Email convention  : testing.<role><n>@zendoindia.com
 * Password          : Testing@123  (same for all)
 *
 * Division rules:
 *  - sales_executive   → warehousing (user 1) / residential (user 2)
 *  - chief_coordinator → warehousing (user 1) / residential (user 2)
 *  - supply_head       → warehousing (user 1) / residential (user 2)
 *  - field_officer     → warehousing (always, enforced by model)
 *  - all other roles   → null
 *
 * Safe to re-run — uses upsert on email.
 */
class UserSeeder extends Seeder
{
    private const PASSWORD = 'Testing@123';

    public function run(): void
    {
        // ── Prerequisite: regions & areas for FK constraints ──────────────
        $this->seedRegionsAndAreas();

        // ── Build user list ───────────────────────────────────────────────
        $users = $this->buildUsers();

        foreach ($users as $user) {
            DB::table('users')->upsert(
                $user,
                ['email'],           // deduplicate on email
                array_diff_key($user, ['email' => null])  // update all except email
            );
        }

        $this->command->info('UserSeeder: ' . count($users) . ' testing users seeded/updated.');
        $this->command->table(
            ['Name', 'Email', 'Role', 'Division'],
            array_map(fn($u) => [
                $u['name'], $u['email'], $u['role'], $u['division'] ?? '—'
            ], $users)
        );
    }

    // ──────────────────────────────────────────────────────────────────────
    // User definitions
    // ──────────────────────────────────────────────────────────────────────

    private function buildUsers(): array
    {
        $pw  = Hash::make(self::PASSWORD);
        $now = now()->toDateTimeString();

        $definitions = [
            // role                 division 1        division 2
            ['super_admin',         null,              null],
            ['admin',               null,              null],
            ['sales_executive',     'warehousing',     'residential'],
            ['chief_coordinator',   'warehousing',     'residential'],
            ['supply_head',         'warehousing',     'residential'],
            ['field_officer',       'warehousing',     'warehousing'],  // always warehousing
            ['owner',               null,              null],
            ['channel_partner',     null,              null],
            ['user',                null,              null],
        ];

        $users = [];

        foreach ($definitions as [$role, $div1, $div2]) {
            $slug = str_replace('_', '', $role); // e.g. salesexecutive

            foreach ([1 => $div1, 2 => $div2] as $n => $division) {
                $users[] = [
                    'name'                     => "testing_{$role}_{$n}",
                    'email'                    => "testing.{$slug}{$n}@zendoindia.com",
                    'phone'                    => null,
                    'role'                     => $role,
                    'division'                 => $division,
                    'supply_head_id'           => null,
                    'region_id'                => ($role === 'supply_head' || $role === 'field_officer') ? $n : null,
                    'area_id'                  => ($role === 'supply_head' || $role === 'field_officer') ? $n : null,
                    'is_active'                => 1,
                    'can_approve_owner_listings' => 0,
                    'email_verified_at'        => null,
                    'password'                 => $pw,
                    'remember_token'           => null,
                    'created_at'               => $now,
                    'updated_at'               => $now,
                ];
            }
        }

        return $users;
    }

    // ──────────────────────────────────────────────────────────────────────
    // Region / area prerequisites
    // ──────────────────────────────────────────────────────────────────────

    private function seedRegionsAndAreas(): void
    {
        $now = now()->toDateTimeString();

        $regions = [
            ['id' => 1, 'name' => 'North', 'slug' => 'north', 'status' => 1, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'South', 'slug' => 'south', 'status' => 1, 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
        ];

        $areas = [
            ['id' => 1, 'region_id' => 1, 'name' => 'Area North 1', 'slug' => 'area-north-1', 'status' => 1, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'region_id' => 2, 'name' => 'Area South 1', 'slug' => 'area-south-1', 'status' => 1, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
        ];

        foreach ($regions as $r) {
            DB::table('regions')->upsert($r, ['id'], ['name', 'slug', 'status', 'updated_at']);
        }
        foreach ($areas as $a) {
            DB::table('areas')->upsert($a, ['id'], ['name', 'slug', 'region_id', 'status', 'updated_at']);
        }
    }
}
