# ZENDO Property Type Wizard QA Checklist

- [x] 1. warehouse (`ZI-WH-`) -> `/owner/properties/create`
- [x] 2. apartment_flat_studio (`ZI-RA-`) -> `/owner/properties/create/apartment-flat-studio`
- [x] 3. house_villa_farmhouse (`ZI-RH-`) -> `/owner/properties/create/house-villa-farmhouse`
- [x] 4. builder_floor (`ZI-RB-`) -> `/owner/properties/create/builder-floor`
- [x] 5. residential_plot_land (`ZI-RP-`) -> `/owner/properties/create/residential-plot-land`
- [x] 6. service_apartment_pg (`ZI-RS-`) -> `/owner/properties/create/service-apartment-pg`
- [x] 7. office_space (`ZI-CO-`) -> `/owner/properties/create/office-space`
- [x] 8. retail_shop_showroom (`ZI-CR-`) -> `/owner/properties/create/retail-shop-showroom`
- [x] 9. sez_eou_stpi_unit (`ZI-CS-`) -> `/owner/properties/create/sez-eou-stpi-unit`
- [x] 10. factory_manufacturing_industrial (`ZI-CF-`) -> `/owner/properties/create/factory-manufacturing-industrial`
- [x] 11. commercial_institutional_land (`ZI-CL-`) -> `/owner/properties/create/commercial-institutional-land`
- [x] 12. agricultural_farm_land (`ZI-CA-`) -> `/owner/properties/create/agricultural-farm-land`
- [x] 13. multi_tenant_building (`ZI-CB-`) -> `/owner/properties/create/multi-tenant-building`
- [x] 14. hotel_resort_guesthouse_banquet (`ZI-CH-`) -> `/owner/properties/create/hotel-resort-guesthouse-banquet`

---

## Detailed QA Notes & Results

### 1. Warehouse (`ZI-WH-`)
- **Route**: `/owner/properties/create`
- **Prefix Verification**: Generated code starts with `ZI-WH-` (e.g. `ZI-WH-0001`).
- **Gating & Tab Navigation**: Client-side tab gating script `window.__wizTabGateInstalled` verified.
- **Draft & Submission**: Draft saves with partial parameters; submission marks status as `submitted`.
- **Downstream Views**: Verified on Owner details page (`/owner/properties/{id}`) and Admin Report (`/admin/property-entry-report/{id}`).
- **Bugs Fixed**: Resolved unassigned `$property` variable in controller create action.

### 2. Apartment / Flat / Studio (`ZI-RA-`)
- **Route**: `/owner/properties/create/apartment-flat-studio`
- **Prefix Verification**: Code prefix `ZI-RA-`.
- **Gating & Tab Navigation**: Custom step gating and validation installed.
- **Draft & Submission**: Form Request validation handles draft (`action=draft`) vs full submit (`action=submit`) cleanly.
- **Downstream Views**: Renders section-wise details and photo slots correctly.

### 3. House / Villa / Farmhouse (`ZI-RH-`)
- **Route**: `/owner/properties/create/house-villa-farmhouse`
- **Prefix Verification**: Code prefix `ZI-RH-`.
- **Draft & Submission**: Partial draft save & full submission verified.
- **Downstream Views**: Verified on Owner and Admin Show views.

### 4. Builder Floor (`ZI-RB-`)
- **Route**: `/owner/properties/create/builder-floor`
- **Prefix Verification**: Code prefix `ZI-RB-`.
- **Draft & Submission**: Draft & full submission verified.
- **Downstream Views**: Verified on Owner and Admin Show views.

### 5. Residential Plot / Land (`ZI-RP-`)
- **Route**: `/owner/properties/create/residential-plot-land`
- **Prefix Verification**: Code prefix `ZI-RP-`.
- **Draft & Submission**: Verified with plot area and sale price inputs.
- **Downstream Views**: Verified on Owner and Admin Show views.

### 6. Service Apartment / PG (`ZI-RS-`)
- **Route**: `/owner/properties/create/service-apartment-pg`
- **Prefix Verification**: Code prefix `ZI-RS-`.
- **Draft & Submission**: Verified with rental details and deposit months.
- **Downstream Views**: Verified on Owner and Admin Show views.

### 7. Office Space (`ZI-CO-`)
- **Route**: `/owner/properties/create/office-space`
- **Prefix Verification**: Code prefix `ZI-CO-`.
- **Draft & Submission**: Verified workstation capacity, cabins, seating, and rent fields.
- **Downstream Views**: Verified on Owner and Admin Show views.

### 8. Retail Shop / Showroom (`ZI-CR-`)
- **Route**: `/owner/properties/create/retail-shop-showroom`
- **Prefix Verification**: Code prefix `ZI-CR-`.
- **Draft & Submission**: Verified carpet area, frontages, and expected rent.
- **Downstream Views**: Verified on Owner and Admin Show views.

### 9. SEZ / EOU / STPI Unit (`ZI-CS-`)
- **Route**: `/owner/properties/create/sez-eou-stpi-unit`
- **Prefix Verification**: Code prefix `ZI-CS-`.
- **Draft & Submission**: Verified SEZ specific regulatory fields.
- **Downstream Views**: Verified on Owner and Admin Show views.

### 10. Factory / Manufacturing / Industrial (`ZI-CF-`)
- **Route**: `/owner/properties/create/factory-manufacturing-industrial`
- **Prefix Verification**: Code prefix `ZI-CF-`.
- **Draft & Submission**: Verified industrial power, ceiling height, and shed area.
- **Downstream Views**: Verified on Owner and Admin Show views.

### 11. Commercial / Institutional Land (`ZI-CL-`)
- **Route**: `/owner/properties/create/commercial-institutional-land`
- **Prefix Verification**: Code prefix `ZI-CL-`.
- **Draft & Submission**: Verified plot dimension and zoning details.
- **Downstream Views**: Verified on Owner and Admin Show views.

### 12. Agricultural / Farm Land (`ZI-CA-`)
- **Route**: `/owner/properties/create/agricultural-farm-land`
- **Prefix Verification**: Code prefix `ZI-CA-`.
- **Draft & Submission**: Verified Khasra number, soil type, and irrigation source inputs.
- **Downstream Views**: Verified on Owner and Admin Show views.

### 13. Multi-Tenant Building (`ZI-CB-`)
- **Route**: `/owner/properties/create/multi-tenant-building`
- **Prefix Verification**: Code prefix `ZI-CB-`.
- **Draft & Submission**: Verified floor breakdown, total area, and multi-tenant rental income.
- **Downstream Views**: Verified on Owner and Admin Show views.

### 14. Hotel / Resort / Guesthouse / Banquet (`ZI-CH-`)
- **Route**: `/owner/properties/create/hotel-resort-guesthouse-banquet`
- **Prefix Verification**: Code prefix `ZI-CH-`.
- **Draft & Submission**: Verified key count, banquet capacity, and operational status.
- **Downstream Views**: Verified on Owner and Admin Show views.

---

## Consolidated UX Notes & Decisions Summary
1. **Unassigned PHP Variable Warning**: Fixed `$property` undefined variable warning across all 13 dedicated property controllers by initializing `$property = null;` in `create()`.
2. **Invalid `supply_head_id` Parameter**: Removed invalid `supply_head_id` binding in `PropertyEntryController::store()` to prevent SQL column 1054 exceptions.
3. **PIN-Code Auto-Lookup via Postal API**: Postal API integration (`https://api.postalpincode.in/pincode/{pincode}`) handles auto-filling District, State, and Post Office locations across wizards.
4. **Section K (Team Remarks)**: Positioned in proper form order with tab gating and Save & Next flow matching.
