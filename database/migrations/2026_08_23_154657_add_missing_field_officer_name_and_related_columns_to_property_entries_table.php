<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            // Section A — Submitter & Owner
            if (!Schema::hasColumn('property_entries', 'company_entity_name')) { $table->text('company_entity_name')->nullable(); }

            // Section B — Location & Identification
            if (!Schema::hasColumn('property_entries', 'sub_locality_society_name')) { $table->text('sub_locality_society_name')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'nearby_landmarks')) { $table->text('nearby_landmarks')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'distance_from_key_locations')) { $table->text('distance_from_key_locations')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'overlooking_view')) { $table->json('overlooking_view')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'gps_latitude')) { $table->decimal('gps_latitude', 9, 6)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'gps_longitude')) { $table->decimal('gps_longitude', 9, 6)->nullable(); }

            // Section B2 — Project / Society
            if (!Schema::hasColumn('property_entries', 'project_society_name')) { $table->text('project_society_name')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'developer_builder_name')) { $table->text('developer_builder_name')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'total_units_in_project')) { $table->decimal('total_units_in_project', 14, 2)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'approved_loan_banks')) { $table->text('approved_loan_banks')->nullable(); }

            // Section C — Unit Configuration
            if (!Schema::hasColumn('property_entries', 'super_built_up_area')) { $table->decimal('super_built_up_area', 14, 2)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'units_on_this_floor')) { $table->decimal('units_on_this_floor', 14, 2)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'covered_parking_slots')) { $table->decimal('covered_parking_slots', 14, 2)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'open_parking_slots')) { $table->decimal('open_parking_slots', 14, 2)->nullable(); }

            // Section D — Legal & Compliance
            if (!Schema::hasColumn('property_entries', 'completion_certificate')) { $table->text('completion_certificate')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'khata_property_tax_status')) { $table->text('khata_property_tax_status')->nullable(); }

            // Section E — Society & Building Amenities
            if (!Schema::hasColumn('property_entries', 'water_availability')) { $table->text('water_availability')->nullable(); }

            // Section G — Commercial Terms
            if (!Schema::hasColumn('property_entries', 'price_per_sqft')) { $table->decimal('price_per_sqft', 14, 2)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'booking_amount')) { $table->decimal('booking_amount', 14, 2)->nullable(); }

            // Section G2 — Tenant Preferences & Rental Terms
            if (!Schema::hasColumn('property_entries', 'non_veg_allowed')) { $table->text('non_veg_allowed')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'pets_allowed')) { $table->text('pets_allowed')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'electricity_charges')) { $table->text('electricity_charges')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'water_charges')) { $table->text('water_charges')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'maintenance_inclusion')) { $table->text('maintenance_inclusion')->nullable(); }

            // Section H — Investment / ROI
            if (!Schema::hasColumn('property_entries', 'tenant_name_profile')) { $table->text('tenant_name_profile')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'lease_start_date')) { $table->date('lease_start_date')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'annual_escalation_in_lease')) { $table->decimal('annual_escalation_in_lease', 5, 2)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'security_deposit_held')) { $table->text('security_deposit_held')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'deposit_adjustment_on_sale')) { $table->text('deposit_adjustment_on_sale')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'payback_capital_value_note')) { $table->text('payback_capital_value_note')->nullable(); }

            // Section J — Photos & Media
            if (!Schema::hasColumn('property_entries', 'video_walkthrough_link')) { $table->text('video_walkthrough_link')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'virtual_tour_360_link')) { $table->text('virtual_tour_360_link')->nullable(); }

            // Section K — Team Remarks
            if (!Schema::hasColumn('property_entries', 'field_officer_name')) { $table->text('field_officer_name')->nullable(); }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            //
        });
    }
};
