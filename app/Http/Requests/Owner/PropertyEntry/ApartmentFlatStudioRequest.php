<?php

namespace App\Http\Requests\Owner\PropertyEntry;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApartmentFlatStudioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'owner';
    }

    public function rules(): array
    {
        $isDraft = ($this->input('action') === 'draft');

        if ($isDraft) {
            return [
                'submitter_full_name' => 'nullable|string|max:120',
                'submitter_phone' => 'nullable|string|max:20',
                'submitter_email' => 'nullable|email|max:255',
                'submitter_role' => 'nullable|string|max:255',
                'company_entity_name' => 'nullable|string|max:120',
                'owner_contact_name' => 'nullable|string|max:120',
                'owner_contact_phone' => 'nullable|string|max:20',
                'owner_email' => 'nullable|email|max:255',
                'city' => 'nullable|string|max:255',
                'locality_broad_area' => 'nullable|string|max:255',
                'sub_locality_society_name' => 'nullable|string|max:120',
                'project_name' => 'nullable|string|max:120',
                'builder_developer_name' => 'nullable|string|max:120',
                'name_full_address' => 'nullable|string|max:255',
                'postal_address_pin' => 'nullable|string|max:20',
                'state' => 'nullable|string|max:255',
                'gps_latitude' => 'nullable|numeric|between:-90,90',
                'gps_longitude' => 'nullable|numeric|between:-180,180',
                'nearby_landmarks' => 'nullable|string|max:120',
                'distance_from_key_locations' => 'nullable|string|max:120',
                'facing_orientation' => 'nullable|string|max:255',
                'overlooking_view' => 'nullable|array',
                'part_of_a_project_society' => 'nullable|string|max:255',
                'project_society_name' => 'nullable|string|max:120',
                'project_rera_id' => 'nullable|string|max:120',
                'developer_builder_name' => 'nullable|string|max:120',
                'total_towers_blocks' => 'nullable|numeric|min:0',
                'total_units_in_project' => 'nullable|numeric|min:0',
                'configurations_offered' => 'nullable|array',
                'project_amenities' => 'nullable|array',
                'approved_loan_banks' => 'nullable|string|max:120',
                'unit_property_type' => 'nullable|string|max:255',
                'configuration' => 'nullable|string|max:255',
                'carpet_area' => 'nullable|numeric|min:0',
                'built_up_area' => 'nullable|numeric|min:0',
                'super_built_up_area' => 'nullable|numeric|min:0',
                'floor_number' => 'nullable|numeric|min:0',
                'number_of_floors' => 'nullable|numeric|min:0',
                'units_on_this_floor' => 'nullable|numeric|min:0',
                'no_of_bedrooms' => 'nullable|numeric|min:0',
                'no_of_bathrooms' => 'nullable|numeric|min:0',
                'no_of_balconies' => 'nullable|numeric|min:0',
                'additional_rooms' => 'nullable|array',
                'furnishing_status' => 'nullable|string|max:255',
                'furnishing_detail' => 'nullable|array',
                'parking_slots' => 'nullable|string|max:120',
                'covered_parking_slots' => 'nullable|numeric|min:0',
                'open_parking_slots' => 'nullable|numeric|min:0',
                'property_status' => 'nullable|string|max:255',
                'construction_listing_status' => 'nullable|string|max:255',
                'possession_by' => 'nullable|date',
                'age_of_property' => 'nullable|string|max:255',
                'availability' => 'nullable|string|max:255',
                'available_from' => 'nullable|date',
                'bank_loan_emi_available' => 'nullable|string|max:255',
                'ownership_type' => 'nullable|string|max:255',
                'title_status' => 'nullable|string|max:255',
                'rera_registered' => 'nullable|string|max:255',
                'rera_registration_id' => 'nullable|string|max:120',
                'completion_certificate' => 'nullable|string|max:255',
                'occupancy_certificate' => 'nullable|string|max:255',
                'encumbrance_loan_on_property' => 'nullable|string|max:255',
                'khata_property_tax_status' => 'nullable|string|max:255',
                'lift_elevator' => 'nullable|string|max:255',
                'power_backup' => 'nullable|string|max:255',
                'water_source' => 'nullable|string|max:255',
                'water_availability' => 'nullable|string|max:255',
                'electricity_status' => 'nullable|string|max:255',
                'gated_society' => 'nullable|string|max:255',
                'security_cctv' => 'nullable|string|max:255',
                'amenities_checklist' => 'nullable|array',
                'pet_friendly' => 'nullable|string|max:255',
                'deal_type' => 'nullable|string|max:255',
                'price_on_request' => 'nullable|string|max:255',
                'expected_rent' => 'nullable|numeric|min:0',
                'rent_range_band' => 'nullable|string|max:255',
                'maintenance_charge' => 'nullable|numeric|min:0',
                'security_deposit_months' => 'nullable|string|max:255',
                'expected_sale_price' => 'nullable|numeric|min:0',
                'sale_price_band' => 'nullable|string|max:255',
                'price_per_sqft' => 'nullable|numeric|min:0',
                'booking_amount' => 'nullable|numeric|min:0',
                'negotiable_floor_price' => 'nullable|numeric|min:0',
                'owner_flexibility_notes' => 'nullable|string|max:2000',
                'preferred_tenant' => 'nullable|array',
                'non_veg_allowed' => 'nullable|string|max:255',
                'pets_allowed' => 'nullable|string|max:255',
                'notice_period' => 'nullable|numeric|min:0',
                'minimum_lease_agreement_term' => 'nullable|numeric|min:0',
                'electricity_charges' => 'nullable|string|max:255',
                'water_charges' => 'nullable|string|max:255',
                'maintenance_inclusion' => 'nullable|string|max:255',
                'currently_rented_tenanted' => 'nullable|string|max:255',
                'current_monthly_rent_received' => 'nullable|numeric|min:0',
                'rental_income_band' => 'nullable|string|max:255',
                'rental_yield_roi' => 'nullable|numeric|min:0',
                'tenant_name_profile' => 'nullable|string|max:120',
                'tenant_type' => 'nullable|string|max:255',
                'lease_start_date' => 'nullable|date',
                'lease_tenure' => 'nullable|numeric|min:0',
                'lock_in_remaining' => 'nullable|numeric|min:0',
                'annual_escalation_in_lease' => 'nullable|numeric|min:0',
                'security_deposit_held' => 'nullable|string|max:120',
                'deposit_adjustment_on_sale' => 'nullable|string|max:255',
                'cam_outgoings_borne_by' => 'nullable|string|max:255',
                'payback_capital_value_note' => 'nullable|string|max:2000',
                'video_walkthrough_link' => 'nullable|url|max:255',
                'virtual_tour_360_link' => 'nullable|url|max:255',
                'video_virtual_tour_link' => 'nullable|url|max:255',
                'remarks' => 'nullable|string|max:2000',
                'property_description' => 'nullable|string|max:2000',
                'inspection_submission_date' => 'nullable|date',
            ] + $this->photoRules();
        }

        return [
            // Section A
            'submitter_full_name' => 'required|string|max:120',
            'submitter_phone' => 'required|regex:/^[6-9][0-9]{9}$/',
            'submitter_email' => 'required|email|max:255',
            'submitter_role' => 'required|in:Owner,Builder,Authorised Agent,Broker,GPA holder',
            'company_entity_name' => 'nullable|string|max:120',
            'owner_contact_name' => 'required|string|max:120',
            'owner_contact_phone' => 'required|regex:/^[6-9][0-9]{9}$/',
            'owner_email' => 'nullable|email|max:255',

            // Section B
            'city' => 'required|string|max:255',
            'locality_broad_area' => 'required|string|max:50',
            'sub_locality_society_name' => 'nullable|string|max:120',
            'project_name' => 'nullable|string|max:120',
            'builder_developer_name' => 'nullable|string|max:120',
            'name_full_address' => 'required|string|max:120',
            'postal_address_pin' => 'required|regex:/^[1-9][0-9]{5}$/',
            'state' => 'required|string|max:255',
            'gps_latitude' => 'nullable|numeric|between:-90,90',
            'gps_longitude' => 'nullable|numeric|between:-180,180',
            'nearby_landmarks' => 'nullable|string|max:120',
            'distance_from_key_locations' => 'nullable|string|max:120',
            'facing_orientation' => 'nullable|in:N,E,W,S,NE,NW,SE,SW',
            'overlooking_view' => 'nullable|array',
            'overlooking_view.*' => 'in:Main Road,Park-Garden,Pool,Club,Others',

            // Section B2
            'part_of_a_project_society' => 'required|in:Yes,No',
            'project_society_name' => 'nullable|required_if:part_of_a_project_society,Yes|string|max:120',
            'project_rera_id' => 'nullable|required_if:part_of_a_project_society,Yes|string|max:120',
            'developer_builder_name' => 'nullable|required_if:part_of_a_project_society,Yes|string|max:120',
            'total_towers_blocks' => 'nullable|numeric|min:0',
            'total_units_in_project' => 'nullable|numeric|min:0',
            'configurations_offered' => 'nullable|array',
            'configurations_offered.*' => 'in:1,2,3,4,4+ BHK,Studio,Shop,Office',
            'project_amenities' => 'nullable|array',
            'project_amenities.*' => 'in:Clubhouse,Pool,Gym,Park,Sports,Power backup',
            'approved_loan_banks' => 'nullable|string|max:120',

            // Section C
            'unit_property_type' => 'required|in:Apartment,Flat,Studio Apartment,Penthouse,Duplex',
            'configuration' => 'required|in:1RK,1,2,3,4,4+ BHK',
            'carpet_area' => 'required|numeric|gt:0',
            'built_up_area' => 'nullable|numeric|gt:0',
            'super_built_up_area' => 'nullable|numeric|gt:0',
            'floor_number' => 'required|integer|min:0|max:999',
            'number_of_floors' => 'required|integer|min:0|max:999',
            'units_on_this_floor' => 'nullable|integer|min:0|max:999',
            'no_of_bedrooms' => 'required|integer|min:0|max:999',
            'no_of_bathrooms' => 'required|integer|min:0|max:999',
            'no_of_balconies' => 'nullable|integer|min:0|max:999',
            'additional_rooms' => 'nullable|array',
            'additional_rooms.*' => 'in:Pooja,Study,Servant,Store',
            'furnishing_status' => 'required|in:Unfurnished,Semi,Fully furnished',
            'furnishing_detail' => 'nullable|array',
            'furnishing_detail.*' => 'in:wardrobes,modular kitchen,ACs,appliances',
            'covered_parking_slots' => 'nullable|integer|min:0|max:999',
            'open_parking_slots' => 'nullable|integer|min:0|max:999',

            // Section C2
            'property_status' => 'required|in:New,Resale',
            'construction_listing_status' => 'required|in:Ready to Move,Under Construction,New Launch,Pre-launch',
            'possession_by' => 'nullable|required_if:construction_listing_status,Under Construction|date',
            'age_of_property' => 'nullable|in:<1,1-5,5-10,10+ yrs',
            'availability' => 'required|in:Immediate,From date',
            'available_from' => 'nullable|required_if:availability,From date|date',
            'bank_loan_emi_available' => 'nullable|in:Yes — approved banks,No',

            // Section D
            'ownership_type' => 'required|in:Freehold,Leasehold,GPA,Co-op Society',
            'title_status' => 'required|in:Clear,Under Dispute,Encumbrance Being Resolved',
            'rera_registered' => 'required|in:Yes,No,Not Applicable',
            'rera_registration_id' => 'nullable|required_if:rera_registered,Yes|string|max:120',
            'occupancy_certificate' => 'required|in:Received,Applied,Not Received',
            'completion_certificate' => 'nullable|in:Received,Applied,Not Received',
            'encumbrance_loan_on_property' => 'required|in:None,Home Loan,Mortgage,Other',
            'khata_property_tax_status' => 'nullable|in:Up to date,Pending',

            // Section E
            'lift_elevator' => 'required|in:Yes,No',
            'power_backup' => 'nullable|in:Full,Partial,None',
            'water_source' => 'nullable|in:Municipal,Borewell,Both,24x7',
            'water_availability' => 'nullable|in:24x7,Fixed hours,Tanker dependent',
            'electricity_status' => 'nullable|in:No cuts,Rare cuts,Frequent cuts',
            'gated_society' => 'nullable|in:Yes,No',
            'security_cctv' => 'nullable|in:24x7,Partial,None',
            'amenities_checklist' => 'nullable|array',
            'amenities_checklist.*' => 'in:Gym,Pool,Clubhouse,Park,Play area,Lift,Garden',
            'pet_friendly' => 'nullable|in:Yes,No',

            // Section G
            'deal_type' => 'required|in:Rent,Sale,Both',
            'price_on_request' => 'nullable|in:Yes,No',
            'expected_rent' => Rule::requiredIf(fn() => in_array($this->deal_type, ['Rent', 'Both']) && $this->price_on_request !== 'Yes') . '|nullable|numeric|gt:0',
            'rent_range_band' => 'nullable|string|max:255',
            'maintenance_charge' => 'nullable|numeric|gt:0',
            'security_deposit_months' => 'nullable|string|max:255',
            'expected_sale_price' => Rule::requiredIf(fn() => in_array($this->deal_type, ['Sale', 'Both']) && $this->price_on_request !== 'Yes') . '|nullable|numeric|gt:0',
            'sale_price_band' => 'nullable|string|max:255',
            'price_per_sqft' => 'nullable|numeric|gt:0',
            'booking_amount' => 'nullable|numeric|gt:0',
            'negotiable_floor_price' => 'nullable|numeric|gt:0',
            'owner_flexibility_notes' => 'nullable|string|max:2000',

            // Section G2
            'preferred_tenant' => 'nullable|array',
            'preferred_tenant.*' => 'in:Family,Bachelor Male,Bachelor Female,Company,Any',
            'non_veg_allowed' => 'nullable|in:Yes,No',
            'pets_allowed' => 'nullable|in:Yes,No',
            'notice_period' => 'nullable|numeric|min:0',
            'minimum_lease_agreement_term' => 'nullable|numeric|min:0',
            'electricity_charges' => 'nullable|in:Included,Extra — as per meter',
            'water_charges' => 'nullable|in:Included,Extra',
            'maintenance_inclusion' => 'nullable|in:Included in rent,Extra fixed,Extra per sq ft',

            // Section H
            'currently_rented_tenanted' => 'required|in:Yes,No,Partially',
            'current_monthly_rent_received' => 'nullable|required_if:currently_rented_tenanted,Yes,Partially|numeric|gt:0',
            'rental_income_band' => 'nullable|string|max:255',
            'rental_yield_roi' => 'nullable|numeric|min:0|max:100',
            'tenant_name_profile' => 'nullable|string|max:120',
            'tenant_type' => 'nullable|in:MNC,Corporate,Bank,Brand Retail,Individual',
            'lease_start_date' => 'nullable|required_if:currently_rented_tenanted,Yes,Partially|date',
            'lease_tenure' => 'nullable|required_if:currently_rented_tenanted,Yes,Partially|numeric|min:0',
            'lock_in_remaining' => 'nullable|required_if:currently_rented_tenanted,Yes,Partially|numeric|min:0',
            'annual_escalation_in_lease' => 'nullable|numeric|min:0|max:15',
            'security_deposit_held' => 'nullable|string|max:120',
            'deposit_adjustment_on_sale' => 'nullable|in:Transferred to buyer,Refunded,Negotiable',
            'cam_outgoings_borne_by' => 'nullable|in:Tenant,Owner,Shared',
            'payback_capital_value_note' => 'nullable|string|max:2000',

            // Section J & K
            'video_walkthrough_link' => 'nullable|url|max:255',
            'virtual_tour_360_link' => 'nullable|url|max:255',
            'video_virtual_tour_link' => 'nullable|url|max:255',
            'remarks' => 'required|string|max:2000',
            'property_description' => 'nullable|string|max:2000',
            'inspection_submission_date' => 'nullable|date',
        ] + $this->photoRules();
    }

    /**
     * photo_0..photo_7 are fixed named slots (not a `photos[]` array like
     * the warehouse wizard), so each needs its own key. Always optional —
     * Section J's "min 5 photos" spec requirement isn't enforceable per-slot
     * since slots are independently optional; existing photos on edit mean
     * a re-submit shouldn't force a fresh upload either.
     */
    private function photoRules(): array
    {
        $rules = [];
        for ($i = 0; $i < 8; $i++) {
            $rules["photo_{$i}"] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240';
        }
        return $rules;
    }
}
