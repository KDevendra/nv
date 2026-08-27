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
            $columns = [
                'car_parking_slots',
                'car_parking_capacity',
                'video_virtual_tour_link',
                'ac_rooms',
                'age_of_building',
                'air_conditioning',
                'amenities',
                'annual_escalation',
                'approach_road_width_ft',
                'approved_layout_dtcp_rera_local',
                'area_in_standard_unit_sq_ft',
                'attached_bathroom',
                'bank_loan_lease_financing_available',
                'banquet_event_space_sq_ft',
                'banquet_guest_capacity_pax',
                'boiler_steam_gas_line',
                'bonded_export_oriented_unit_distinct_compliance_loa_nfe_cust',
                'boundary_demarcation',
                'boundary_wall',
                'building_management_system',
                'building_security_access_control',
                'built_up_chargeable_area_sq_ft',
                'buyer_eligibility_restriction',
                'cam_charges_sq_ft_month',
                'carpet_area_per_unit_sq_ft',
            ];

            foreach ($columns as $column) {
                if (!Schema::hasColumn('property_entries', $column)) {
                    $table->text($column)->nullable();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            // Safe down method
        });
    }
};
