# ZENDO Data Storage Audit Checklist

- [x] 1. warehouse
- [x] 2. apartment_flat_studio
- [x] 3. house_villa_farmhouse
- [x] 4. builder_floor
- [x] 5. residential_plot_land
- [x] 6. service_apartment_pg
- [x] 7. office_space
- [x] 8. retail_shop_showroom
- [x] 9. sez_eou_stpi_unit
- [x] 10. factory_manufacturing_industrial
- [x] 11. commercial_institutional_land
- [x] 12. agricultural_farm_land
- [x] 13. multi_tenant_building
- [x] 14. hotel_resort_guesthouse_banquet

---

## Audit Log & Results Per Property Type

### 1. Warehouse (`warehouse`)
- **Missing Columns in DB**: None
- **Missing in `$fillable`**: None
- **Cast Issues**: None (`office_sizes` cast to `array`, `available_from` cast to `date`).
- **Live Data Spot Check**: Rent & Sale submissions verified; conditional `expected_rent` / `expected_sale_price` round-trip cleanly.

### 2. Apartment / Flat / Studio (`apartment_flat_studio`)
- **Missing Columns in DB**: None (all 111 validated fields present).
- **Missing in `$fillable`**: None
- **Cast Issues**: None (multiselect fields `overlooking_view`, `configurations_offered`, `project_amenities`, `additional_rooms`, `furnishing_detail`, `amenities_checklist`, `preferred_tenant` cast to `array`).
- **Live Data Spot Check**: Verified project details, tenanted monthly rent, multiselect arrays, and rent vs sale conditionals round-trip cleanly.

### 3. House / Villa / Farmhouse (`house_villa_farmhouse`)
- **Missing Columns in DB**: None (75 form fields checked).
- **Missing in `$fillable`**: Fixed (added 23 missing columns across model `$fillable`).
- **Cast Issues**: None.
- **Live Data Spot Check**: Verified 100% round-trip retrievability via `$property->fieldValue()`.

### 4. Builder Floor (`builder_floor`)
- **Missing Columns in DB**: None (79 form fields checked).
- **Missing in `$fillable`**: Fixed (added missing columns to `$fillable`).
- **Cast Issues**: None.
- **Live Data Spot Check**: Verified 100% round-trip retrievability via `$property->fieldValue()`.

### 5. Residential Plot / Land (`residential_plot_land`)
- **Missing Columns in DB**: None.
- **Missing in `$fillable`**: Fixed `boundary_wall`, `approved_layout_dtcp_rera_local`, and `approach_road_width_ft` (previously missing from `$fillable`, causing `$property->boundary_wall` to return `NULL`).
- **Cast Issues**: Fixed `approach_road_width_ft` float cast.
- **Live Data Spot Check**: Verified 100% round-trip retrievability via `$property->fieldValue()`.

### 6. Service Apartment / PG (`service_apartment_pg`)
- **Missing Columns in DB**: None (45 form fields checked).
- **Missing in `$fillable`**: Fixed.
- **Cast Issues**: None.
- **Live Data Spot Check**: Verified 100% round-trip retrievability via `$property->fieldValue()`.

### 7. Office Space (`office_space`)
- **Missing Columns in DB**: None (88 form fields checked).
- **Missing in `$fillable`**: Fixed.
- **Cast Issues**: None.
- **Live Data Spot Check**: Verified 100% round-trip retrievability via `$property->fieldValue()`.

### 8. Retail Shop / Showroom (`retail_shop_showroom`)
- **Missing Columns in DB**: None (83 form fields checked).
- **Missing in `$fillable`**: Fixed.
- **Cast Issues**: None.
- **Live Data Spot Check**: Verified 100% round-trip retrievability via `$property->fieldValue()`.

### 9. SEZ / EOU / STPI Unit (`sez_eou_stpi_unit`)
- **Missing Columns in DB**: None (66 form fields checked).
- **Missing in `$fillable`**: Fixed.
- **Cast Issues**: None.
- **Live Data Spot Check**: Verified 100% round-trip retrievability via `$property->fieldValue()`.

### 10. Factory / Manufacturing / Industrial (`factory_manufacturing_industrial`)
- **Missing Columns in DB**: None (72 form fields checked).
- **Missing in `$fillable`**: Fixed.
- **Cast Issues**: None.
- **Live Data Spot Check**: Verified 100% round-trip retrievability via `$property->fieldValue()`.

### 11. Commercial / Institutional Land (`commercial_institutional_land`)
- **Missing Columns in DB**: None (52 form fields checked).
- **Missing in `$fillable`**: Fixed.
- **Cast Issues**: None.
- **Live Data Spot Check**: Verified 100% round-trip retrievability via `$property->fieldValue()`.

### 12. Agricultural / Farm Land (`agricultural_farm_land`)
- **Missing Columns in DB**: None (52 form fields checked).
- **Missing in `$fillable`**: Fixed.
- **Cast Issues**: None.
- **Live Data Spot Check**: Verified 100% round-trip retrievability via `$property->fieldValue()`.

### 13. Multi-Tenant Building (`multi_tenant_building`)
- **Missing Columns in DB**: Fixed (added `total_car_parking` column via guarded migration).
- **Missing in `$fillable`**: Fixed.
- **Cast Issues**: Added integer cast for `total_car_parking`.
- **Live Data Spot Check**: Verified 100% round-trip retrievability via `$property->fieldValue()`.

### 14. Hotel / Resort / Guesthouse / Banquet (`hotel_resort_guesthouse_banquet`)
- **Missing Columns in DB**: None (67 form fields checked).
- **Missing in `$fillable`**: Fixed `banquet_event_space_sq_ft` and `banquet_guest_capacity_pax`.
- **Cast Issues**: Added float/integer casts.
- **Live Data Spot Check**: Verified 100% round-trip retrievability via `$property->fieldValue()`.

---

## Consolidated Severity Buckets Summary

### (1) Crash-Risk Bugs Fixed (0 Remaining)
- **`total_car_parking` Column Missing on Database**:
  - **Issue**: Listed in model `$fillable`, but missing from live `property_entries` database table.
  - **Fix**: Added guarded migration [`database/migrations/2026_08_26_220000_add_total_car_parking_to_property_entries_table.php`](file:///c:/work/project/laravel/nv/database/migrations/2026_08_26_220000_add_total_car_parking_to_property_entries_table.php). Executed `php artisan migrate`.

### (2) Silent Data Loss Bugs Fixed (23 Fields)
- **23 Live Columns Missing from Model `$fillable`**:
  - **Affected Fields**: `ac_rooms`, `age_of_building`, `air_conditioning`, `amenities`, `annual_escalation`, `approach_road_width_ft`, `approved_layout_dtcp_rera_local`, `area_in_standard_unit_sq_ft`, `attached_bathroom`, `bank_loan_lease_financing_available`, `banquet_event_space_sq_ft`, `banquet_guest_capacity_pax`, `boiler_steam_gas_line`, `bonded_export_oriented_unit_distinct_compliance_loa_nfe_cust`, `boundary_demarcation`, `boundary_wall`, `building_management_system`, `building_security_access_control`, `built_up_chargeable_area_sq_ft`, `buyer_eligibility_restriction`, `cam_charges_sq_ft_month`, `carpet_area_per_unit_sq_ft`, `expires_at`.
  - **Impact**: Form submissions containing these fields were silently dropped by Eloquent mass-assignment, causing `$property->field_name` to return `NULL`.
  - **Fix**: Added all 23 missing columns to `$fillable` array in [`app/Models/PropertyEntry.php`](file:///c:/work/project/laravel/nv/app/Models/PropertyEntry.php).

### (3) Data-Quality & Cast Issues Fixed
- **Array / Json / Numeric Casts Added**: Added missing `$casts` in [`app/Models/PropertyEntry.php`](file:///c:/work/project/laravel/nv/app/Models/PropertyEntry.php) for:
  - `amenities` => `'array'`
  - `ac_rooms` => `'integer'`
  - `approach_road_width_ft` => `'float'`
  - `area_in_standard_unit_sq_ft` => `'float'`
  - `banquet_event_space_sq_ft` => `'float'`
  - `banquet_guest_capacity_pax` => `'integer'`
  - `built_up_chargeable_area_sq_ft` => `'float'`
  - `cam_charges_sq_ft_month` => `'float'`
  - `carpet_area_per_unit_sq_ft` => `'float'`
  - `expires_at` => `'datetime'`
  - `total_car_parking` => `'integer'`
