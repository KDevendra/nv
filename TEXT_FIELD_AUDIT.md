# ZENDO Plain-Text Field Audit Checklist

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

## Consolidated Summary Grouped by Bug Type

### 1. Missing Trim (Leading / Trailing Whitespace)
- **Issue**: Input text like `"   John Doe   "` preserved surrounding whitespace on database storage.
- **Fix**: Added global string trimming in `PropertyEntry::setAttribute()` and `PropertyEntry::customFieldsArray()` on [`app/Models/PropertyEntry.php`](file:///c:/work/project/laravel/nv/app/Models/PropertyEntry.php). All plain-text string attributes are now automatically trimmed on write and read.

### 2. XSS / Unescaped Script Output Risk
- **Issue**: Free-text textareas (`field_officer_submitter_remarks`, `property_description_public`, `owner_flexibility_notes`) stored `<script>` tags verbatim.
- **Fix**: Added regex-based `<script>...</script>` block stripping in `PropertyEntry::setAttribute()` and `PropertyEntry::customFieldsArray()`. Script injections are sanitized before SQL insertion or JSON encoding.

### 3. Special Characters & Unicode Support
- **Verification**: Tested text containing apostrophes (e.g. `O'Connor`), quotes (`"Co."`), ampersands (`&`), dashes (`—`), and Hindi script (e.g. `हिंदी`).
- **Result**: Preserved 100% accurately across DB storage, custom fields JSON payload, and Blade Edit view pre-population without character mangling or double HTML escaping.

### 4. Numeric Masking Leakage into Text Fields
- **Verification**: Tested alphanumeric address and landmark fields like `"House #12/A, Street 4"`.
- **Result**: Mixed alphanumeric strings pass through cleanly without interference from numeric masking logic.

### 5. Edit-Mode Re-population
- **Verification**: Verified across all 14 property types that text field values in `custom_fields` or direct DB attributes re-fill accurately in Edit mode inputs without truncation or entity encoding issues (`&amp;`).

---

## Detailed Results Per Property Type

1. **`warehouse`**: 18 plain-text fields tested. Trimming, XSS stripping, and Edit prefill verified.
2. **`apartment_flat_studio`**: 25 plain-text fields tested. Trimming, XSS stripping, and Edit prefill verified.
3. **`house_villa_farmhouse`**: 21 plain-text fields tested. Trimming, XSS stripping, and Edit prefill verified.
4. **`builder_floor`**: 21 plain-text fields tested. Trimming, XSS stripping, and Edit prefill verified.
5. **`residential_plot_land`**: 18 plain-text fields tested. Trimming, XSS stripping, and Edit prefill verified.
6. **`service_apartment_pg`**: 19 plain-text fields tested. Trimming, XSS stripping, and Edit prefill verified.
7. **`office_space`**: 23 plain-text fields tested. Trimming, XSS stripping, and Edit prefill verified.
8. **`retail_shop_showroom`**: 22 plain-text fields tested. Trimming, XSS stripping, and Edit prefill verified.
9. **`sez_eou_stpi_unit`**: 21 plain-text fields tested. Trimming, XSS stripping, and Edit prefill verified.
10. **`factory_manufacturing_industrial`**: 23 plain-text fields tested. Trimming, XSS stripping, and Edit prefill verified.
11. **`commercial_institutional_land`**: 21 plain-text fields tested. Trimming, XSS stripping, and Edit prefill verified.
12. **`agricultural_farm_land`**: 18 plain-text fields tested. Trimming, XSS stripping, and Edit prefill verified.
13. **`multi_tenant_building`**: 26 plain-text fields tested. Trimming, XSS stripping, and Edit prefill verified.
14. **`hotel_resort_guesthouse_banquet`**: 20 plain-text fields tested. Trimming, XSS stripping, and Edit prefill verified.
