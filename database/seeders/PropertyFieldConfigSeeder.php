<?php

namespace Database\Seeders;

use App\Models\PropertyFieldConfig;
use Illuminate\Database\Seeder;

class PropertyFieldConfigSeeder extends Seeder
{
    /**
     * Run the database seeds based on exact field configurations
     */
    public function run(): void
    {
        
        PropertyFieldConfig::truncate();

        $fieldConfigs = [
            'facility_type'              => [1, 1, 1, 1], 
            'district'                   => [1, 1, 1, 1], 
            'state'                      => [1, 1, 1, 1], 
            'country'                    => [1, 1, 1, 1], 
            'property_name'              => [1, 1, 1, 1], 
            'village_town_district'      => [1, 1, 0, 1], 
            'postal_address_pin'         => [1, 1, 0, 1], 
            'nearest_highway'            => [1, 1, 0, 1], 
            'nearest_airport'            => [1, 1, 0, 1], 
            'fire_noc'                   => [1, 1, 0, 1], 
            'clu_conversion_status'      => [1, 1, 0, 1], 
            'built_up_area'              => [1, 1, 0, 1], 
            'clear_height_side'          => [1, 1, 0, 1], 
            'pollution_noc'              => [1, 1, 0, 1], 
            'carpet_area'                => [1, 1, 0, 1], 
            'tehsil'                     => [1, 1, 0, 1], 
            'name_full_address'          => [1, 1, 0, 0], 
            'nearest_city'               => [1, 1, 0, 0], 
            'nearest_railway_station'    => [1, 1, 0, 0], 
            'owner_contact_name'         => [1, 1, 0, 0], 
            'owner_contact_phone'        => [1, 1, 0, 0], 
            'tenure'                     => [1, 1, 0, 0], 
            'approved_land_use'          => [1, 1, 0, 0], 
            'occupancy_certificate'      => [1, 1, 0, 0], 
            'plot_area'                  => [1, 1, 0, 0], 
            'clear_height_highest'       => [1, 1, 0, 0], 
            'number_of_floors'           => [1, 1, 0, 0], 
            'fsi_far'                    => [1, 1, 0, 0], 
            'dock_door_count'            => [1, 1, 0, 0], 
            'dock_type'                  => [1, 1, 0, 0], 
            'dock_height'                => [1, 1, 0, 0], 
            'truck_movement'             => [1, 1, 0, 0], 
            'flooring_type'              => [1, 1, 0, 0], 
            'office_cabin_area'          => [1, 1, 0, 0], 
            'washrooms'                  => [1, 1, 0, 0], 
            'ventilation_lighting'       => [1, 1, 0, 0], 
            'power_sanctioned_kva'       => [1, 1, 0, 0], 
            'discom_name'                => [1, 1, 0, 0], 
            'water_source'               => [1, 1, 0, 0], 
            'fire_fighting_system'       => [1, 1, 0, 0], 
            'deal_type'                  => [1, 1, 0, 0], 
            'expected_rent'              => [1, 1, 0, 0], 
            'expected_sale_price'        => [1, 1, 0, 0], 
            'security_deposit_months'    => [1, 1, 0, 0], 
            'lock_in_years'              => [1, 1, 0, 0], 
            'available_from'             => [1, 1, 0, 0], 
            'approach_road_width'        => [1, 1, 0, 0], 
            'top_neighbouring_companies' => [1, 1, 0, 0], 
            'flood_risk'                 => [1, 1, 0, 0], 
            'nearest_hospital_km'        => [1, 1, 0, 0], 
            'nearest_fire_station_km'    => [1, 1, 0, 0], 
            'nearest_police_station_km'  => [1, 1, 0, 0], 
            'remarks'                    => [1, 1, 0, 0], 
            'village'                    => [1, 1, 0, 0], 
            'owner_email'                => [1, 1, 0, 0], 
            'pollution_category'         => [1, 1, 0, 0], 
            'available_area'             => [1, 1, 0, 0], 
            'shed_width'                 => [1, 1, 0, 0], 
            'shed_length'                => [1, 1, 0, 0], 
            'dock_front'                 => [1, 1, 0, 0], 
            'dock_left'                  => [1, 1, 0, 0], 
            'dock_right'                 => [1, 1, 0, 0], 
            'dock_back'                  => [1, 1, 0, 0], 
            'dock_leveller_front'        => [1, 1, 0, 0], 
            'dock_leveller_left'         => [1, 1, 0, 0], 
            'dock_leveller_right'        => [1, 1, 0, 0], 
            'dock_leveller_back'         => [1, 1, 0, 0], 
            'fire_exit_front'            => [1, 1, 0, 0], 
            'fire_exit_left'             => [1, 1, 0, 0], 
            'fire_exit_right'            => [1, 1, 0, 0], 
            'fire_exit_back'             => [1, 1, 0, 0], 
            'canopy_width_front'         => [1, 1, 0, 0], 
            'canopy_width_left'          => [1, 1, 0, 0], 
            'canopy_width_right'         => [1, 1, 0, 0], 
            'canopy_width_back'          => [1, 1, 0, 0], 
            'canopy_length_front'        => [1, 1, 0, 0], 
            'canopy_length_left'         => [1, 1, 0, 0],
            'canopy_length_right'        => [1, 1, 0, 0],
            'canopy_length_back'         => [1, 1, 0, 0],
            'road_width_front'           => [1, 1, 0, 0], 
            'road_width_left'            => [1, 1, 0, 0], 
            'road_width_right'           => [1, 1, 0, 0], 
            'road_width_back'            => [1, 1, 0, 0], 
            'no_of_offices'              => [1, 1, 0, 0], 
            'office_sizes'               => [1, 1, 0, 0], 
            'canteen'                    => [1, 1, 0, 0], 
            'canteen_size'               => [1, 1, 0, 0], 
            'stp_plant'                  => [1, 1, 0, 0], 
            'stp_capacity'               => [1, 1, 0, 0], 
            'no_of_urinals'              => [1, 1, 0, 0], 
            'no_of_closets'              => [1, 1, 0, 0], 
            'female_washroom'            => [1, 1, 0, 0], 
            'driver_rest_room'           => [1, 1, 0, 0], 
            'mezzanine'                  => [1, 1, 0, 0], 
            'mezzanine_size'             => [1, 1, 0, 0], 
            'structure_type'             => [1, 1, 0, 0], 
            'insulation_roof'            => [1, 1, 0, 0], 
            'insulation_side'            => [1, 1, 0, 0], 
            'fire_sprinkler'             => [1, 1, 0, 0], 
            'scrap_yard'                 => [1, 1, 0, 0], 
            'no_of_companies_same_premise' => [1, 1, 0, 0], 
            'extension_possible'         => [1, 1, 0, 0], 
            'water_tank_capacity'        => [1, 1, 0, 0], 
            'solar'                      => [1, 1, 0, 0], 
        ];

        foreach ($fieldConfigs as $fieldKey => [$keepField, $mandatoryField, $showOnWebsite, $showAfterVerification]) {
            PropertyFieldConfig::updateOrCreate(
                ['field_key' => $fieldKey],
                [
                    'keep_field'              => $keepField,
                    'mandatory_field'         => $mandatoryField,
                    'show_on_website'         => $showOnWebsite,
                    'show_after_verification' => $showAfterVerification,
                    'created_at'              => now(),
                    'updated_at'              => now(),
                ]
            );
        }

        $this->command->info('PropertyFieldConfig seeder completed. Total fields configured: ' . count($fieldConfigs));
        $this->command->info('Fields with show_on_website=1: ' . collect($fieldConfigs)->filter(fn($config) => $config[2] === 1)->count());
        $this->command->info('Fields with show_after_verification=1: ' . collect($fieldConfigs)->filter(fn($config) => $config[3] === 1)->count());
    }
}
