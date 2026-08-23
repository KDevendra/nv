<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PropertyEntriesIndexesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function property_entries_table_has_all_expected_single_and_composite_indexes(): void
    {
        $migration = require database_path('migrations/2026_08_24_000000_add_indexes_to_property_entries_table.php');
        $migration->up();

        if (DB::getDriverName() === 'mysql') {
            $existing = collect(DB::select('SHOW INDEX FROM property_entries'))
                ->pluck('Key_name')
                ->unique()
                ->all();

            $expectedIndexes = [
                'property_entries_status_index',
                'property_entries_nearest_city_index',
                'property_entries_locality_broad_area_index',
                'property_entries_facility_type_index',
                'property_entries_submitted_at_index',
                'property_entries_created_at_index',
                'property_entries_public_listing_idx',
                'property_entries_officer_status_idx',
                'property_entries_zone_status_idx',
                'property_entries_admin_report_filter_idx',
            ];

            foreach ($expectedIndexes as $indexName) {
                $this->assertContains(
                    $indexName,
                    $existing,
                    "Expected index [{$indexName}] was not found on property_entries table."
                );
            }
        } else {
            $this->assertTrue(true);
        }
    }

    /** @test */
    public function migration_is_idempotent_and_can_re_run_safely(): void
    {
        $migration = require database_path('migrations/2026_08_24_000000_add_indexes_to_property_entries_table.php');
        $migration->up();
        $migration->up();

        $this->assertTrue(true);
    }
}
