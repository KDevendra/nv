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
        Schema::create('property_entries', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->foreignId('field_officer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('supply_head_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Status workflow
            $table->enum('status', ['draft', 'submitted', 'verified', 'rejected', 'recheck'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            
            // A. Location & Identification
            $table->string('facility_type')->nullable();
            $table->text('name_full_address')->nullable();
            $table->string('village_town_district')->nullable();
            $table->string('postal_address_pin', 10)->nullable();
            $table->string('nearest_city')->nullable();
            $table->string('nearest_highway')->nullable();
            $table->string('nearest_railway_station')->nullable();
            $table->string('nearest_airport')->nullable();
            
            // B. Legal & Statutory
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
            $table->decimal('office_cabin_area', 12, 2)->nullable();
            $table->integer('washrooms')->nullable();
            $table->string('ventilation_lighting')->nullable();
            
            // F. Utilities & Infrastructure
            $table->decimal('power_sanctioned_kva', 10, 2)->nullable();
            $table->string('discom_name')->nullable();
            $table->string('water_source')->nullable();
            $table->string('fire_fighting_system')->nullable();
            
            // G. Financial & Lease
            $table->string('deal_type')->nullable();
            $table->decimal('expected_rent', 12, 2)->nullable();
            $table->decimal('expected_sale_price', 15, 2)->nullable();
            $table->decimal('security_deposit_months', 5, 1)->nullable();
            $table->decimal('lock_in_years', 5, 1)->nullable();
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
            $table->string('owner_contact_phone', 20)->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('field_officer_id');
            $table->index('supply_head_id');
            $table->index('status');
            $table->index('nearest_city');
            $table->index('facility_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_entries');
    }
};
