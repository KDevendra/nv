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
            if (!Schema::hasColumn('property_entries', 'submitter_full_name')) { $table->text('submitter_full_name')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'submitter_phone')) { $table->string('submitter_phone', 15)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'submitter_email')) { $table->text('submitter_email')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'submitter_role')) { $table->text('submitter_role')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'company_entity_name')) { $table->text('company_entity_name')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'owner_email')) { $table->text('owner_email')->nullable(); }

            // Section B — Location & Identification
            if (!Schema::hasColumn('property_entries', 'city')) { $table->text('city')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'locality_broad_area')) { $table->text('locality_broad_area')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'sub_locality_society_name')) { $table->text('sub_locality_society_name')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'project_name')) { $table->text('project_name')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'builder_developer_name')) { $table->text('builder_developer_name')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'nearby_landmarks_key_distances')) { $table->text('nearby_landmarks_key_distances')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'nearby_landmarks')) { $table->text('nearby_landmarks')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'distance_from_key_locations')) { $table->text('distance_from_key_locations')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'facing_orientation')) { $table->text('facing_orientation')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'overlooking_view')) { $table->json('overlooking_view')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'gps_latitude')) { $table->decimal('gps_latitude', 9, 6)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'gps_longitude')) { $table->decimal('gps_longitude', 9, 6)->nullable(); }

            // Section B2 — Project / Society
            if (!Schema::hasColumn('property_entries', 'part_of_a_project_society')) { $table->text('part_of_a_project_society')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'project_society_name')) { $table->text('project_society_name')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'project_rera_id')) { $table->text('project_rera_id')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'developer_builder_name')) { $table->text('developer_builder_name')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'total_towers_blocks')) { $table->decimal('total_towers_blocks', 14, 2)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'total_units_in_project')) { $table->decimal('total_units_in_project', 14, 2)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'configurations_offered')) { $table->json('configurations_offered')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'project_amenities')) { $table->json('project_amenities')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'approved_loan_banks')) { $table->text('approved_loan_banks')->nullable(); }

            // Section C — Unit Configuration
            if (!Schema::hasColumn('property_entries', 'unit_property_type')) { $table->text('unit_property_type')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'configuration')) { $table->text('configuration')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'super_built_up_area')) { $table->decimal('super_built_up_area', 14, 2)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'floor_number')) { $table->decimal('floor_number', 14, 2)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'units_on_this_floor')) { $table->decimal('units_on_this_floor', 14, 2)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'no_of_bedrooms')) { $table->decimal('no_of_bedrooms', 14, 2)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'no_of_bathrooms')) { $table->decimal('no_of_bathrooms', 14, 2)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'no_of_balconies')) { $table->decimal('no_of_balconies', 14, 2)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'additional_rooms')) { $table->json('additional_rooms')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'furnishing_status')) { $table->text('furnishing_status')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'furnishing_detail')) { $table->json('furnishing_detail')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'parking_slots')) { $table->text('parking_slots')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'covered_parking_slots')) { $table->decimal('covered_parking_slots', 14, 2)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'open_parking_slots')) { $table->decimal('open_parking_slots', 14, 2)->nullable(); }

            // Section C2 — Transaction & Possession
            if (!Schema::hasColumn('property_entries', 'property_status')) { $table->text('property_status')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'construction_listing_status')) { $table->text('construction_listing_status')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'possession_by')) { $table->date('possession_by')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'age_of_property')) { $table->text('age_of_property')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'availability')) { $table->text('availability')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'bank_loan_emi_available')) { $table->text('bank_loan_emi_available')->nullable(); }

            // Section D — Legal & Compliance
            if (!Schema::hasColumn('property_entries', 'ownership_type')) { $table->text('ownership_type')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'title_status')) { $table->text('title_status')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'rera_registered')) { $table->text('rera_registered')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'rera_registration_id')) { $table->text('rera_registration_id')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'completion_certificate')) { $table->text('completion_certificate')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'encumbrance_loan_on_property')) { $table->text('encumbrance_loan_on_property')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'khata_property_tax_status')) { $table->text('khata_property_tax_status')->nullable(); }

            // Section E — Society & Building Amenities
            if (!Schema::hasColumn('property_entries', 'lift_elevator')) { $table->text('lift_elevator')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'power_backup')) { $table->text('power_backup')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'water_source')) { $table->text('water_source')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'water_availability')) { $table->text('water_availability')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'water_supply')) { $table->text('water_supply')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'electricity_status')) { $table->text('electricity_status')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'gated_society')) { $table->text('gated_society')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'security_cctv')) { $table->text('security_cctv')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'amenities_checklist')) { $table->json('amenities_checklist')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'pet_friendly')) { $table->text('pet_friendly')->nullable(); }

            // Section G — Commercial Terms
            if (!Schema::hasColumn('property_entries', 'price_on_request')) { $table->text('price_on_request')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'rent_range_band')) { $table->text('rent_range_band')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'maintenance_charge')) { $table->decimal('maintenance_charge', 14, 2)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'sale_price_band')) { $table->text('sale_price_band')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'price_per_sqft')) { $table->decimal('price_per_sqft', 14, 2)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'booking_amount')) { $table->decimal('booking_amount', 14, 2)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'negotiable_floor_price')) { $table->decimal('negotiable_floor_price', 14, 2)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'owner_flexibility_notes')) { $table->text('owner_flexibility_notes')->nullable(); }

            // Section G2 — Tenant Preferences & Rental Terms
            if (!Schema::hasColumn('property_entries', 'preferred_tenant')) { $table->json('preferred_tenant')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'non_veg_allowed')) { $table->text('non_veg_allowed')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'pets_allowed')) { $table->text('pets_allowed')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'non_veg_pets_allowed')) { $table->text('non_veg_pets_allowed')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'notice_period')) { $table->decimal('notice_period', 14, 2)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'minimum_lease_agreement_term')) { $table->decimal('minimum_lease_agreement_term', 14, 2)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'electricity_charges')) { $table->text('electricity_charges')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'water_charges')) { $table->text('water_charges')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'maintenance_inclusion')) { $table->text('maintenance_inclusion')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'utilities_who_bears')) { $table->text('utilities_who_bears')->nullable(); }

            // Section H — Investment / ROI
            if (!Schema::hasColumn('property_entries', 'currently_rented_tenanted')) { $table->text('currently_rented_tenanted')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'current_monthly_rent_received')) { $table->decimal('current_monthly_rent_received', 14, 2)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'rental_income_band')) { $table->text('rental_income_band')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'rental_yield_roi')) { $table->decimal('rental_yield_roi', 5, 1)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'tenant_name_profile')) { $table->text('tenant_name_profile')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'tenant_type')) { $table->text('tenant_type')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'lease_start_date')) { $table->date('lease_start_date')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'lease_tenure')) { $table->decimal('lease_tenure', 14, 2)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'lock_in_remaining')) { $table->decimal('lock_in_remaining', 14, 2)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'annual_escalation_in_lease')) { $table->decimal('annual_escalation_in_lease', 5, 2)->nullable(); }
            if (!Schema::hasColumn('property_entries', 'security_deposit_held')) { $table->text('security_deposit_held')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'deposit_adjustment_on_sale')) { $table->text('deposit_adjustment_on_sale')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'cam_outgoings_borne_by')) { $table->text('cam_outgoings_borne_by')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'payback_capital_value_note')) { $table->text('payback_capital_value_note')->nullable(); }

            // Section J — Photos & Media
            if (!Schema::hasColumn('property_entries', 'video_walkthrough_link')) { $table->text('video_walkthrough_link')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'virtual_tour_360_link')) { $table->text('virtual_tour_360_link')->nullable(); }

            // Section K — Team Remarks
            if (!Schema::hasColumn('property_entries', 'property_description')) { $table->text('property_description')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'field_officer_name')) { $table->text('field_officer_name')->nullable(); }
            if (!Schema::hasColumn('property_entries', 'field_verified')) { $table->boolean('field_verified')->default(false); }
            if (!Schema::hasColumn('property_entries', 'inspection_submission_date')) { $table->date('inspection_submission_date')->nullable(); }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
