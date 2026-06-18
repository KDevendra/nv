<?php

namespace Database\Seeders;

use App\Models\PropertyFieldConfig;
use Illuminate\Database\Seeder;

class PropertyFieldConfigSeeder extends Seeder
{
    /**
     * show_on_website = true:
     *   facility_type, district, state, country, plot_area
     *
     * show_after_verification = true:
     *   facility_type, tehsil, district, state, postal_address_pin, country,
     *   nearest_highway, nearest_airport, fire_noc, clu_conversion_status,
     *   pollution_noc, built_up_area, carpet_area, clear_height_side
     *
     * All fields: keep_field = true, mandatory_field = true (unless listed otherwise)
     */
    public function run(): void
    {
        // field_key => [show_on_website, show_after_verification]
        $fields = [
            // ── A. Location & Identification ─────────────────────────────────
            'facility_type'              => [true,  true],
            'name_full_address'          => [false, false],
            'village'                    => [false, false],
            'tehsil'                     => [false, true],
            'district'                   => [true,  true],
            'state'                      => [true,  true],
            'country'                    => [true,  true],
            'postal_address_pin'         => [false, true],
            'nearest_highway'            => [false, true],
            'nearest_city'               => [false, false],   // superseded by district; kept for compat
            'nearest_railway_station'    => [false, false],
            'nearest_airport'            => [false, true],
            'owner_contact_name'         => [false, false],
            'owner_contact_phone'        => [false, false],
            'owner_email'                => [false, false],

            // ── B. Legal & Statutory ─────────────────────────────────────────
            'tenure'                     => [false, false],
            'approved_land_use'          => [false, false],
            'fire_noc'                   => [false, true],
            'clu_conversion_status'      => [false, true],
            'pollution_noc'              => [false, true],
            'pollution_category'         => [false, false],
            'occupancy_certificate'      => [false, false],

            // ── C. Property Dimensions ───────────────────────────────────────
            'plot_area'                  => [true,  false],
            'built_up_area'              => [false, true],
            'carpet_area'                => [false, true],
            'available_area'             => [false, false],
            'clear_height_highest'       => [false, false],
            'clear_height_side'          => [false, true],
            'shed_width'                 => [false, false],
            'shed_length'                => [false, false],
            'number_of_floors'           => [false, false],
            'fsi_far'                    => [false, false],
            // Docks
            'dock_door_count'            => [false, false],
            'dock_front'                 => [false, false],
            'dock_left'                  => [false, false],
            'dock_right'                 => [false, false],
            'dock_back'                  => [false, false],
            'dock_leveller_front'        => [false, false],
            'dock_leveller_left'         => [false, false],
            'dock_leveller_right'        => [false, false],
            'dock_leveller_back'         => [false, false],
            'fire_exit_front'            => [false, false],
            'fire_exit_left'             => [false, false],
            'fire_exit_right'            => [false, false],
            'fire_exit_back'             => [false, false],
            'canopy_width_front'         => [false, false],
            'canopy_width_left'          => [false, false],
            'canopy_width_right'         => [false, false],
            'canopy_width_back'          => [false, false],
            'road_width_front'           => [false, false],
            'road_width_left'            => [false, false],
            'road_width_right'           => [false, false],
            'road_width_back'            => [false, false],
            // Facilities
            'no_of_offices'              => [false, false],
            'office_sizes'               => [false, false],
            'canteen'                    => [false, false],
            'canteen_size'               => [false, false],
            'stp_plant'                  => [false, false],
            'stp_capacity'               => [false, false],
            'no_of_urinals'              => [false, false],
            'no_of_closets'              => [false, false],
            'female_washroom'            => [false, false],
            'driver_rest_room'           => [false, false],
            'mezzanine'                  => [false, false],
            'mezzanine_size'             => [false, false],
            'structure_type'             => [false, false],
            'insulation_roof'            => [false, false],
            'insulation_side'            => [false, false],
            'fire_sprinkler'             => [false, false],
            'scrap_yard'                 => [false, false],
            'no_of_companies_same_premise' => [false, false],
            'extension_possible'         => [false, false],

            // ── D. Loading & Docking ─────────────────────────────────────────
            'dock_type'                  => [false, false],
            'dock_height'                => [false, false],
            'truck_movement'             => [false, false],

            // ── E. Internal Environment ──────────────────────────────────────
            'flooring_type'              => [false, false],
            'office_cabin_area'          => [false, false],
            'washrooms'                  => [false, false],
            'ventilation_lighting'       => [false, false],

            // ── F. Utilities & Infrastructure ────────────────────────────────
            'power_sanctioned_kva'       => [false, false],
            'discom_name'                => [false, false],
            'water_source'               => [false, false],
            'water_tank_capacity'        => [false, false],
            'fire_fighting_system'       => [false, false],
            'solar'                      => [false, false],

            // ── G. Financial ─────────────────────────────────────────────────
            'deal_type'                  => [false, false],
            'expected_rent'              => [false, false],
            'expected_sale_price'        => [false, false],
            'security_deposit_months'    => [false, false],
            'lock_in_years'              => [false, false],
            'available_from'             => [false, false],

            // ── H. Surroundings ──────────────────────────────────────────────
            'approach_road_width'        => [false, false],
            'top_neighbouring_companies' => [false, false],
            'flood_risk'                 => [false, false],

            // ── I. Health & Emergency ────────────────────────────────────────
            'nearest_hospital_km'        => [false, false],
            'nearest_fire_station_km'    => [false, false],
            'nearest_police_station_km'  => [false, false],

            // ── K. Remarks ───────────────────────────────────────────────────
            'remarks'                    => [false, false],
        ];

        foreach ($fields as $key => [$web, $verif]) {
            PropertyFieldConfig::updateOrCreate(
                ['field_key' => $key],
                [
                    'keep_field'              => true,
                    'mandatory_field'         => true,
                    'show_on_website'         => $web,
                    'show_after_verification' => $verif,
                ]
            );
        }
    }
}
