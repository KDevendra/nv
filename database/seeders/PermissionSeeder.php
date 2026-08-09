<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('role_permissions')->truncate();
        DB::table('permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // ---------------------------------------------------------------
        // Module definitions
        //   full = view, create, edit, delete
        //   read = view only
        //   page = view, edit  (single-page settings)
        // ---------------------------------------------------------------
        $modules = [
            // ── Full CRUD ───────────────────────────────────────────────
            'about-us'               => ['type' => 'full', 'label' => 'About Us'],
            'amenities'              => ['type' => 'full', 'label' => 'Amenities'],
            'bhks'                   => ['type' => 'full', 'label' => 'BHKs'],
            'blogs'                  => ['type' => 'full', 'label' => 'Blogs'],
            'builders'               => ['type' => 'full', 'label' => 'Builders'],
            'categories'             => ['type' => 'full', 'label' => 'Categories'],
            'cities'                 => ['type' => 'full', 'label' => 'Cities'],
            'commercial-sections'    => ['type' => 'full', 'label' => 'Commercial Sections'],
            'faqs'                   => ['type' => 'full', 'label' => 'FAQs'],
            'features'               => ['type' => 'full', 'label' => 'Features'],
            'hero-sections'          => ['type' => 'full', 'label' => 'Hero Sections'],
            'locations'              => ['type' => 'full', 'label' => 'Locations'],
            'our-clients'            => ['type' => 'full', 'label' => 'Our Clients'],
            'project-statuses'       => ['type' => 'full', 'label' => 'Project Statuses'],
            'properties'             => ['type' => 'full', 'label' => 'Properties', 'extra' => ['restore']],
            'property-page-sections' => ['type' => 'full', 'label' => 'Property Page Sections'],
            'property-types'         => ['type' => 'full', 'label' => 'Property Types'],
            'service-types'          => ['type' => 'full', 'label' => 'Service Types'],
            'team-members'           => ['type' => 'full', 'label' => 'Team Members'],
            'testimonials'           => ['type' => 'full', 'label' => 'Testimonials'],
            'work-processes'         => ['type' => 'full', 'label' => 'Work Processes'],
            'users'                  => ['type' => 'full', 'label' => 'Users'],
            'seo-metas'              => ['type' => 'full', 'label' => 'SEO Metas'],

            // ── Read-only ───────────────────────────────────────────────
            'consultations'          => ['type' => 'read', 'label' => 'Consultations'],
            'inquiries'              => ['type' => 'read', 'label' => 'Inquiries'],
            'property-inquiries'     => ['type' => 'read', 'label' => 'Property Inquiries'],

            // ── Single-page settings ────────────────────────────────────
            'about-page'             => ['type' => 'page', 'label' => 'About Page Settings'],
            'contact-info'           => ['type' => 'page', 'label' => 'Contact Info'],
            'contact-page'           => ['type' => 'page', 'label' => 'Contact Page Settings'],
            'privacy-policy'         => ['type' => 'page', 'label' => 'Privacy Policy'],
            'terms-and-conditions'   => ['type' => 'page', 'label' => 'Terms & Conditions'],

            // ── Dashboard ───────────────────────────────────────────────
            'dashboard'              => ['type' => 'read', 'label' => 'Dashboard'],
        ];

        $actionsByType = [
            'full' => ['view', 'create', 'edit', 'delete'],
            'read' => ['view'],
            'page' => ['view', 'edit'],
        ];

        // ---------------------------------------------------------------
        // Shared permission sets (reused across roles)
        // ---------------------------------------------------------------

        // Everything admin touches except user management
        $adminFull = [
            'about-us'               => ['view', 'create', 'edit', 'delete'],
            'amenities'              => ['view', 'create', 'edit', 'delete'],
            'bhks'                   => ['view', 'create', 'edit', 'delete'],
            'blogs'                  => ['view', 'create', 'edit', 'delete'],
            'builders'               => ['view', 'create', 'edit', 'delete'],
            'categories'             => ['view', 'create', 'edit', 'delete'],
            'cities'                 => ['view', 'create', 'edit', 'delete'],
            'commercial-sections'    => ['view', 'create', 'edit', 'delete'],
            'faqs'                   => ['view', 'create', 'edit', 'delete'],
            'features'               => ['view', 'create', 'edit', 'delete'],
            'hero-sections'          => ['view', 'create', 'edit', 'delete'],
            'locations'              => ['view', 'create', 'edit', 'delete'],
            'our-clients'            => ['view', 'create', 'edit', 'delete'],
            'project-statuses'       => ['view', 'create', 'edit', 'delete'],
            'properties'             => ['view', 'create', 'edit', 'delete', 'restore'],
            'property-page-sections' => ['view', 'create', 'edit', 'delete'],
            'property-types'         => ['view', 'create', 'edit', 'delete'],
            'service-types'          => ['view', 'create', 'edit', 'delete'],
            'team-members'           => ['view', 'create', 'edit', 'delete'],
            'testimonials'           => ['view', 'create', 'edit', 'delete'],
            'work-processes'         => ['view', 'create', 'edit', 'delete'],
            'seo-metas'              => ['view', 'create', 'edit', 'delete'],
            'consultations'          => ['view'],
            'inquiries'              => ['view'],
            'property-inquiries'     => ['view'],
            'about-page'             => ['view', 'edit'],
            'contact-info'           => ['view', 'edit'],
            'contact-page'           => ['view', 'edit'],
            'privacy-policy'         => ['view', 'edit'],
            'terms-and-conditions'   => ['view', 'edit'],
            'dashboard'              => ['view'],
        ];

        // View-only across all content (used as a base for limited roles)
        $viewAll = [
            'about-us'               => ['view'],
            'amenities'              => ['view'],
            'bhks'                   => ['view'],
            'blogs'                  => ['view'],
            'builders'               => ['view'],
            'categories'             => ['view'],
            'cities'                 => ['view'],
            'commercial-sections'    => ['view'],
            'faqs'                   => ['view'],
            'features'               => ['view'],
            'hero-sections'          => ['view'],
            'locations'              => ['view'],
            'our-clients'            => ['view'],
            'project-statuses'       => ['view'],
            'properties'             => ['view'],
            'property-page-sections' => ['view'],
            'property-types'         => ['view'],
            'service-types'          => ['view'],
            'team-members'           => ['view'],
            'testimonials'           => ['view'],
            'work-processes'         => ['view'],
            'seo-metas'              => ['view'],
            'consultations'          => ['view'],
            'inquiries'              => ['view'],
            'property-inquiries'     => ['view'],
            'about-page'             => ['view'],
            'contact-info'           => ['view'],
            'contact-page'           => ['view'],
            'privacy-policy'         => ['view'],
            'terms-and-conditions'   => ['view'],
            'dashboard'              => ['view'],
        ];

        // ---------------------------------------------------------------
        // Role → permission mapping
        //
        // Division access summary (from spec):
        //
        //  Role                           | Warehousing | Res/Comm | Notes
        //  -------------------------------|-------------|----------|-------------------------------
        //  super_admin                    | ✓           | ✓        | sees everything
        //  admin                          | ✓           | ✓        | shared; no cross-division limit
        //  chief_coordinator_warehousing  | ✓           | –        | W only; can manage W properties/users
        //  chief_coordinator_rescomm      | –           | ✓        | RC only; can manage RC properties/users
        //  sales_executive_warehousing    | ✓           | –        | W only; view + manage own leads
        //  sales_executive_rescomm        | –           | ✓        | RC only; view + manage own leads
        //  supply_head                    | ✓           | –        | W only; reviews field officer entries
        //  field_officer                  | ✓           | –        | W only; Zendo employee
        //  supply_head_rescomm            | –           | ✓        | RC only
        //  channel_partner                | –           | ✓        | external registered partner; RC only
        // ---------------------------------------------------------------
        $roleMap = [
            'super_admin' => '*',
            'admin'       => $adminFull,
            'user'        => [
                'dashboard' => ['view'],
            ],
            'supply_head' => [
                'dashboard' => ['view'],
            ],
            'field_officer' => [
                'dashboard' => ['view'],
            ],
            'owner'       => [
                'dashboard' => ['view'],
            ],
            'sales_executive' => [
                'dashboard'  => ['view'],
                'properties' => ['view'],
            ],
            'chief_coordinator' => [
                'dashboard'  => ['view'],
                'properties' => ['view'],
            ],
            'channel_partner' => [
                'properties'         => ['view'],
                'property-inquiries' => ['view'],
                'consultations'      => ['view'],
                'locations'          => ['view'],
                'cities'             => ['view'],
                'property-types'     => ['view'],
                'builders'           => ['view'],
                'dashboard'          => ['view'],
            ],
        ];

        // ---------------------------------------------------------------
        // Seed permissions table
        // ---------------------------------------------------------------
        $permissionMap = []; // name => id

        foreach ($modules as $module => $config) {
            $actions = array_merge($actionsByType[$config['type']], $config['extra'] ?? []);
            foreach ($actions as $action) {
                $name  = "{$module}.{$action}";
                $label = ucfirst($action) . ' ' . $config['label'];
                $perm  = Permission::create([
                    'name'   => $name,
                    'module' => $module,
                    'action' => $action,
                    'label'  => $label,
                ]);
                $permissionMap[$name] = $perm->id;
            }
        }

        // ---------------------------------------------------------------
        // Seed role_permissions table
        // ---------------------------------------------------------------
        $rows = [];
        $now  = now();

        foreach ($roleMap as $role => $access) {
            if ($access === '*') {
                foreach ($permissionMap as $permId) {
                    $rows[] = ['role' => $role, 'permission_id' => $permId, 'created_at' => $now, 'updated_at' => $now];
                }
            } else {
                foreach ($access as $module => $actions) {
                    foreach ($actions as $action) {
                        $name = "{$module}.{$action}";
                        if (isset($permissionMap[$name])) {
                            $rows[] = [
                                'role'          => $role,
                                'permission_id' => $permissionMap[$name],
                                'created_at'    => $now,
                                'updated_at'    => $now,
                            ];
                        }
                    }
                }
            }
        }

        DB::table('role_permissions')->insert($rows);

        $this->command->info('Permissions and role mappings seeded successfully.');
        $this->command->table(
            ['Role', 'Permission Count'],
            collect($rows)
                ->groupBy('role')
                ->map(fn ($g, $r) => [$r, count($g)])
                ->values()
                ->toArray()
        );
    }
}
