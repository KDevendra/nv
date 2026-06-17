<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_entries', function (Blueprint $table) {
            $table->id();

            // Meta
            $table->string('code')->unique();
            $table->foreignId('field_officer_id')->constrained('users');
            $table->enum('status', [
                'not_verified',
                'submitted',
                'verified',
                'rejected',
                'recheck',
            ])->default('not_verified');
            $table->text('supply_head_note')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users');

            // A. Location & Identification
            $table->string('facility_type')->nullable();
            $table->text('name_full_address')->nullable();
            $table->string('village_town_district')->nullable();
            $table->string('postal_address_pin')->nullable();
            $table->string('nearest_highway')->nullable();
            $table->string('nearest_city')->nullable();
            $table->string('nearest_railway_station')->nullable();
            $table->string('nearest_airport')->nullable();

            // B. Legal & Statutory Compliance
            $table->string('tenure')->nullable();
            $table->string('approved_land_use')->nullable();
            $table->string('fire_noc')->nullable();
            $table->string('clu_conversion_status')->nullable();
            $table->string('occupancy_certificate')->nullable();

            // C. Property Dimensions
            $table->decimal('plot_area', 12, 2)->nullable();
            $table->decimal('built_up_area', 12, 2)->nullable();
            $table->decimal('clear_height_highest', 8, 2)->nullable();
            $table->decimal('clear_height_side', 8, 2)->nullable();
            $table->integer('number_of_floors')->nullable();
            $table->string('fsi_far')->nullable();

            // D. Loading & Docking
            $table->integer('dock_door_count')->nullable();
            $table->string('dock_type')->nullable();
            $table->decimal('dock_height', 8, 2)->nullable();
            $table->string('truck_movement')->nullable();

            // E. Internal Environment
            $table->string('flooring_type')->nullable();
            $table->decimal('office_cabin_area', 10, 2)->nullable();
            $table->integer('washrooms')->nullable();
            $table->string('ventilation_lighting')->nullable();

            // F. Utilities & Infrastructure
            $table->decimal('power_sanctioned_kva', 10, 2)->nullable();
            $table->string('discom_name')->nullable();
            $table->string('water_source')->nullable();
            $table->string('fire_fighting_system')->nullable();

            // G. Financial & Lease Terms
            $table->string('deal_type')->nullable();
            $table->decimal('expected_rent', 10, 2)->nullable();
            $table->decimal('expected_sale_price', 15, 2)->nullable();
            $table->decimal('security_deposit_months', 8, 2)->nullable();
            $table->decimal('lock_in_years', 5, 2)->nullable();
            $table->date('available_from')->nullable();

            // H. Surroundings
            $table->decimal('approach_road_width', 8, 2)->nullable();
            $table->text('top_neighbouring_companies')->nullable();
            $table->string('flood_risk')->nullable();

            // I. Health & Emergency
            $table->decimal('nearest_hospital_km', 8, 2)->nullable();
            $table->decimal('nearest_fire_station_km', 8, 2)->nullable();
            $table->decimal('nearest_police_station_km', 8, 2)->nullable();

            // K. General Remarks
            $table->text('remarks')->nullable();
            $table->string('owner_contact_name')->nullable();
            $table->string('owner_contact_phone')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_entries');
    }
};
