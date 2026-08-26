<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $existing = [];
        try {
            $existing = collect(DB::select('SHOW INDEX FROM property_entries'))
                ->pluck('Key_name')
                ->unique()
                ->all();
        } catch (\Throwable $e) {
            $existing = [];
        }

        Schema::table('property_entries', function (Blueprint $table) use ($existing) {
            // ── Single-Column Indexes ─────────────────────────────────────────

            if (!in_array('property_entries_status_index', $existing)) {
                $table->index('status', 'property_entries_status_index');
            }

            if (!in_array('property_entries_nearest_city_index', $existing)) {
                $table->index('nearest_city', 'property_entries_nearest_city_index');
            }

            if (!in_array('property_entries_locality_broad_area_index', $existing)) {
                $table->index('locality_broad_area', 'property_entries_locality_broad_area_index');
            }

            if (!in_array('property_entries_facility_type_index', $existing)) {
                $table->index('facility_type', 'property_entries_facility_type_index');
            }

            if (!in_array('property_entries_submitted_at_index', $existing)) {
                $table->index('submitted_at', 'property_entries_submitted_at_index');
            }

            if (!in_array('property_entries_created_at_index', $existing)) {
                $table->index('created_at', 'property_entries_created_at_index');
            }

            // ── Composite Indexes ─────────────────────────────────────────────

            // Supports: Public website listing query in HomeController::properties()
            if (!in_array('property_entries_public_listing_idx', $existing)) {
                $table->index(['admin_status', 'show_on_website', 'property_type', 'submitted_at'], 'property_entries_public_listing_idx');
            }

            // Supports: Owner & Field Officer portal queries & stat counter totals
            if (!in_array('property_entries_officer_status_idx', $existing)) {
                $table->index(['field_officer_id', 'status', 'property_type'], 'property_entries_officer_status_idx');
            }

            // Supports: Supply Head & Zone portal queries & stat counters
            if (!in_array('property_entries_zone_status_idx', $existing)) {
                $table->index(['zone_id', 'status'], 'property_entries_zone_status_idx');
            }

            // Supports: Admin Property Entry Report multi-column filter queries
            if (!in_array('property_entries_admin_report_filter_idx', $existing)) {
                $table->index(['status', 'property_type', 'created_at'], 'property_entries_admin_report_filter_idx');
            }
        });

        // TEXT column indexes require explicit prefix length in MySQL
        if (DB::getDriverName() === 'mysql') {
            if (!in_array('property_entries_city_index', $existing)) {
                DB::statement('CREATE INDEX property_entries_city_index ON property_entries (city(191))');
            }

            if (!in_array('property_entries_public_city_idx', $existing)) {
                DB::statement('CREATE INDEX property_entries_public_city_idx ON property_entries (admin_status, show_on_website, city(191))');
            }
        } else {
            Schema::table('property_entries', function (Blueprint $table) use ($existing) {
                if (!in_array('property_entries_city_index', $existing)) {
                    $table->index('city', 'property_entries_city_index');
                }
                if (!in_array('property_entries_public_city_idx', $existing)) {
                    $table->index(['admin_status', 'show_on_website', 'city'], 'property_entries_public_city_idx');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $existing = [];
        try {
            $existing = collect(DB::select('SHOW INDEX FROM property_entries'))
                ->pluck('Key_name')
                ->unique()
                ->all();
        } catch (\Throwable $e) {
            $existing = [];
        }

        Schema::table('property_entries', function (Blueprint $table) use ($existing) {
            $indexesToDrop = [
                'property_entries_status_index',
                'property_entries_nearest_city_index',
                'property_entries_locality_broad_area_index',
                'property_entries_facility_type_index',
                'property_entries_submitted_at_index',
                'property_entries_created_at_index',
                'property_entries_public_listing_idx',
                'property_entries_public_city_idx',
                'property_entries_officer_status_idx',
                'property_entries_zone_status_idx',
                'property_entries_admin_report_filter_idx',
                'property_entries_city_index',
            ];

            foreach ($indexesToDrop as $indexName) {
                if (empty($existing) || in_array($indexName, $existing)) {
                    try {
                        $table->dropIndex($indexName);
                    } catch (\Throwable $e) {
                        // Ignore if index doesn't exist
                    }
                }
            }
        });
    }
};
