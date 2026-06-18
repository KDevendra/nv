<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            // ── A. Location (new separate fields) ────────────────────────────
            $table->string('village')->nullable()->after('village_town_district');
            $table->string('tehsil')->nullable()->after('village');
            $table->string('district')->nullable()->after('tehsil');
            $table->string('state')->nullable()->after('district');
            $table->string('country')->nullable()->after('state');
            $table->string('owner_email')->nullable()->after('owner_contact_phone');

            // ── B. Legal ─────────────────────────────────────────────────────
            $table->string('pollution_noc')->nullable()->after('occupancy_certificate');
            $table->string('pollution_category')->nullable()->after('pollution_noc');

            // ── C. Dimensions (new) ──────────────────────────────────────────
            $table->decimal('carpet_area', 12, 2)->nullable()->after('built_up_area');
            $table->decimal('available_area', 12, 2)->nullable()->after('carpet_area');
            $table->decimal('shed_width', 8, 2)->nullable()->after('available_area');
            $table->decimal('shed_length', 8, 2)->nullable()->after('shed_width');

            // Docks (counts per side)
            $table->integer('dock_front')->nullable()->after('dock_door_count');
            $table->integer('dock_left')->nullable()->after('dock_front');
            $table->integer('dock_right')->nullable()->after('dock_left');
            $table->integer('dock_back')->nullable()->after('dock_right');

            // Dock levellers
            $table->integer('dock_leveller_front')->nullable()->after('dock_back');
            $table->integer('dock_leveller_left')->nullable()->after('dock_leveller_front');
            $table->integer('dock_leveller_right')->nullable()->after('dock_leveller_left');
            $table->integer('dock_leveller_back')->nullable()->after('dock_leveller_right');

            // Fire exits
            $table->integer('fire_exit_front')->nullable()->after('dock_leveller_back');
            $table->integer('fire_exit_left')->nullable()->after('fire_exit_front');
            $table->integer('fire_exit_right')->nullable()->after('fire_exit_left');
            $table->integer('fire_exit_back')->nullable()->after('fire_exit_right');

            // Canopy widths
            $table->decimal('canopy_width_front', 8, 2)->nullable()->after('fire_exit_back');
            $table->decimal('canopy_width_left', 8, 2)->nullable()->after('canopy_width_front');
            $table->decimal('canopy_width_right', 8, 2)->nullable()->after('canopy_width_left');
            $table->decimal('canopy_width_back', 8, 2)->nullable()->after('canopy_width_right');

            // Road widths
            $table->decimal('road_width_front', 8, 2)->nullable()->after('canopy_width_back');
            $table->decimal('road_width_left', 8, 2)->nullable()->after('road_width_front');
            $table->decimal('road_width_right', 8, 2)->nullable()->after('road_width_left');
            $table->decimal('road_width_back', 8, 2)->nullable()->after('road_width_right');

            // Facilities
            $table->integer('no_of_offices')->nullable()->after('road_width_back');
            $table->string('office_sizes')->nullable()->after('no_of_offices');
            $table->boolean('canteen')->nullable()->after('office_sizes');
            $table->string('canteen_size')->nullable()->after('canteen');
            $table->boolean('stp_plant')->nullable()->after('canteen_size');
            $table->string('stp_capacity')->nullable()->after('stp_plant');
            $table->integer('no_of_urinals')->nullable()->after('stp_capacity');
            $table->integer('no_of_closets')->nullable()->after('no_of_urinals');
            $table->boolean('female_washroom')->nullable()->after('no_of_closets');
            $table->boolean('driver_rest_room')->nullable()->after('female_washroom');
            $table->boolean('mezzanine')->nullable()->after('driver_rest_room');
            $table->string('mezzanine_size')->nullable()->after('mezzanine');
            $table->string('structure_type')->nullable()->after('mezzanine_size');
            $table->string('insulation_roof')->nullable()->after('structure_type');
            $table->string('insulation_side')->nullable()->after('insulation_roof');
            $table->string('fire_sprinkler')->nullable()->after('insulation_side');
            $table->boolean('scrap_yard')->nullable()->after('fire_sprinkler');
            $table->integer('no_of_companies_same_premise')->nullable()->after('scrap_yard');
            $table->boolean('extension_possible')->nullable()->after('no_of_companies_same_premise');

            // ── F. Utilities (new) ───────────────────────────────────────────
            $table->string('water_tank_capacity')->nullable()->after('fire_fighting_system');
            $table->boolean('solar')->nullable()->after('water_tank_capacity');
        });
    }

    public function down(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            $table->dropColumn([
                // A
                'village', 'tehsil', 'district', 'state', 'country', 'owner_email',
                // B
                'pollution_noc', 'pollution_category',
                // C
                'carpet_area', 'available_area', 'shed_width', 'shed_length',
                'dock_front', 'dock_left', 'dock_right', 'dock_back',
                'dock_leveller_front', 'dock_leveller_left', 'dock_leveller_right', 'dock_leveller_back',
                'fire_exit_front', 'fire_exit_left', 'fire_exit_right', 'fire_exit_back',
                'canopy_width_front', 'canopy_width_left', 'canopy_width_right', 'canopy_width_back',
                'road_width_front', 'road_width_left', 'road_width_right', 'road_width_back',
                'no_of_offices', 'office_sizes',
                'canteen', 'canteen_size',
                'stp_plant', 'stp_capacity',
                'no_of_urinals', 'no_of_closets',
                'female_washroom', 'driver_rest_room',
                'mezzanine', 'mezzanine_size',
                'structure_type', 'insulation_roof', 'insulation_side',
                'fire_sprinkler', 'scrap_yard', 'no_of_companies_same_premise', 'extension_possible',
                // F
                'water_tank_capacity', 'solar',
            ]);
        });
    }
};
