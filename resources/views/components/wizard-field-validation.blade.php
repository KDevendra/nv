{{--
    Shared client-side field validation for every ZENDO property wizard.

    Included once by the wizard shell (all 13 dedicated property types) and
    once by the warehouse form partial — one implementation, no per-type copies.

    Rules are INFERRED from the markup each form already emits (name pattern,
    input type, required/maxlength/min/max/step), so no per-field wiring or
    markup edits are needed across the 13 form files. Anything the inference
    can't express can be overridden per input with data-validate="<rule>".

    This is an additive convenience layer only — the server-side Form Requests
    remain authoritative and still reject anything that bypasses JS.

    Implemented in plain JS rather than an Alpine.data() component on purpose:
    the wizard's existing step-gating (wizardValidateStep) and the warehouse
    form's original field validators are both plain JS, and an Alpine component
    would require adding x-data to ~1,000 individual inputs across 13 files.
    This hooks the same DOM declaratively instead, and reuses the exact
    error styling the warehouse form already established.
--}}
<script>
(function () {
    if (window.ZendoFieldValidation) return; // already installed
    'use strict';

    // ── Error rendering — matches the warehouse form's existing convention:
    //    red ring on the control + small red text directly below it.
    var ERROR_INPUT_CLASSES = ['border-red-500', 'ring-2', 'ring-red-300'];
    var ERROR_MSG_CLASS = 'field-format-err mt-1 text-xs text-red-600 font-medium';

    function fieldWrapper(input) {
        // Inputs live inside a per-field <div> wrapper in every one of these
        // forms; fall back to the parent if that ever stops holding true.
        return input.closest('div') || input.parentElement;
    }

    function showFieldError(input, message) {
        ERROR_INPUT_CLASSES.forEach(function (c) { input.classList.add(c); });
        var wrap = fieldWrapper(input);
        if (!wrap) return;

        var s2Selection = wrap.querySelector('.select2-selection');
        if (s2Selection) {
            ERROR_INPUT_CLASSES.forEach(function (c) { s2Selection.classList.add(c); });
        }

        var msg = wrap.querySelector(':scope > .field-format-err');
        if (!msg) {
            msg = document.createElement('p');
            msg.className = ERROR_MSG_CLASS;
            wrap.appendChild(msg);
        }
        msg.textContent = message;
        input.setAttribute('aria-invalid', 'true');
    }

    function clearFieldError(input) {
        ERROR_INPUT_CLASSES.forEach(function (c) { input.classList.remove(c); });
        var wrap = fieldWrapper(input);
        if (wrap) {
            var s2Selection = wrap.querySelector('.select2-selection');
            if (s2Selection) {
                ERROR_INPUT_CLASSES.forEach(function (c) { s2Selection.classList.remove(c); });
            }
            var msg = wrap.querySelector(':scope > .field-format-err');
            if (msg) msg.remove();
        }
        input.removeAttribute('aria-invalid');
    }

    function getSelectValues(input) {
        if (typeof $ !== 'undefined' && $(input).data('select2')) {
            var s2Val = $(input).val();
            if (Array.isArray(s2Val)) return s2Val.filter(function (v) { return v !== null && v !== ''; });
            return s2Val ? [s2Val] : [];
        }
        if (input.selectedOptions) {
            var res = [];
            for (var i = 0; i < input.selectedOptions.length; i++) {
                var optVal = input.selectedOptions[i].value;
                if (optVal !== null && optVal !== '') res.push(optVal);
            }
            return res;
        }
        var v = (input.value || '').trim();
        return v ? [v] : [];
    }

    // ── Rule inference ──────────────────────────────────────────────────────
    var PHONE_RE = /^[6-9][0-9]{9}$/;
    var EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    var PIN_RE   = /^[1-9][0-9]{5}$/;

    var NON_NUMERIC_EXACT = {
        'project_name': true, 'builder_developer_name': true, 'developer_builder_name': true,
        'owner_full_name': true, 'submitter_full_name': true, 'field_officer_name': true,
        'owner_contact_name': true, 'company_entity_name': true, 'tenant_name_profile': true,
        'project_society_name': true, 'zone_park_name': true, 'property_name': true,
        'full_address_house_plot_no_street': true, 'locality_broad_area': true,
        'sub_locality_society_name': true, 'state': true, 'city': true, 'district': true,
        'country': true, 'village': true, 'tehsil': true, 'discom_name': true,
        'project_rera_id': true, 'rera_registration_id': true,
        'interior_unit_photos': true, 'exterior_building_face_photos': true,
        'floor_plan_layout': true, 'room_common_area_photos': true,
        'plot_site_photos_facing_inward': true, 'shop_interior_frontage_inside_view': true,
        'video_virtual_tour_link': true, 'video_walkthrough_link': true,
        'virtual_tour_360_link': true, 'nearby_landmarks_key_distances': true,
        'distance_from_key_locations': true, 'current_crop_plantation': true,
        'pollution_category': true, 'structure_type': true, 'clu_conversion_status': true,
        'suitable_for_activity': true, 'approved_loan_banks': true,
        'existing_brand_operator': true, 'existing_tenants_anchor': true,
        'surrounding_development': true, 'field_verified': true,
        'plot_dimensions_ft_ft': true, 'construction_permitted_floors': true,
        'canteen_size': true, 'stp_capacity': true, 'water_tank_capacity': true,
        'water_source_capacity': true, 'water_supply_tank_capacity': true, 'water_source_stp': true,
        'facing_orientation': true
    };

    var NUMERIC_NAME_RE = /^(.*_)?(area|sq_ft|sq_yd|acres|acreage|floor|floors|towers|blocks|units|bedrooms|bathrooms|balconies|rooms|beds|keys|inventory|workstations|seats|cabins|bays|docks|dock|parking|slots|rent|price|cost|amount|deposit|charges|escalation|yield|roi|maintenance|rate|value|booking|kva|kw|capacity|height|width|length|depth|frontage|dimensions|distance|months|years|tenure|age|bhk|washrooms|pax)(_.*)?$/i;

    function inferKind(input) {
        if (input.tagName === 'SELECT') return 'select';
        var explicit = input.getAttribute('data-field-kind');
        if (explicit) return explicit;

        var tagName = (input.tagName || '').toUpperCase();
        if (tagName === 'SELECT') return 'select';
        var name = (input.getAttribute('name') || '').toLowerCase();
        var type = (input.getAttribute('type') || '').toLowerCase();

        if (type === 'email' || /email/.test(name)) return 'email';
        if (/(^|_)pin(_|$)|pin_code|postal_address_pin/.test(name)) return 'pincode';
        if (/phone|contact_number|mobile/.test(name)) return 'phone';
        if (/latitude/.test(name)) return 'latitude';
        if (/longitude/.test(name)) return 'longitude';

        if (NON_NUMERIC_EXACT[name]) return 'text';

        if (type === 'number' || NUMERIC_NAME_RE.test(name)) return 'number';

        return 'text';
    }

    function allowsDecimal(input) {
        var step = (input.getAttribute('step') || '').toLowerCase();
        if (step === 'any' || (step !== '' && parseFloat(step) % 1 !== 0)) return true;

        var name = (input.getAttribute('name') || '').toLowerCase();
        if (/bedroom|bathroom|balcony|floor_number|total_floors|no_of_floors|number_of_floors|units_on_this_floor|total_towers|total_units|parking_slots|car_parking|dock_door_count|dock_front|dock_back|dock_left|dock_right|dock_leveller|fire_exit|keys_rooms|guest_capacity|workstation|no_of_cabins|no_of_meeting_rooms|lifts|washrooms|urinals|closets/i.test(name)) {
            return false;
        }
        return true;
    }

    function allowsNegative(input) {
        var min = input.getAttribute('min');
        return min !== null && parseFloat(min) < 0;
    }

    function restrict(input, kind) {
        var before = input.value;
        var v = before;

        if (kind === 'phone') {
            v = v.replace(/\D/g, '').slice(0, 10);
        } else if (kind === 'pincode') {
            v = v.replace(/\D/g, '').slice(0, 6);
        }

        var maxlen = parseInt(input.getAttribute('maxlength'), 10);
        if (!isNaN(maxlen) && v.length > maxlen) v = v.slice(0, maxlen);

        if (v !== before) {
            var pos = input.selectionStart;
            input.value = v;
            if (input.type !== 'number' && pos !== null) {
                try { input.setSelectionRange(pos - 1, pos - 1); } catch (e) {}
            }
            return true;
        }
        return false;
    }

    function checkIfRequired(input) {
        var fieldWrap = input.closest('div');
        if (fieldWrap && (fieldWrap.style.display === 'none' || input.offsetParent === null)) {
            return false;
        }
        if (input.hasAttribute('required')) return true;
        if (fieldWrap) {
            var label = fieldWrap.querySelector('label');
            if (label) {
                var asterisk = label.querySelector('.text-red-500');
                if (asterisk && asterisk.style.display !== 'none' && (asterisk.textContent || '').indexOf('*') !== -1) {
                    return true;
                }
            }
        }
        return false;
    }

    function validateField(input, enforceRequired) {
        if (!input) return true;
        if (input.classList && input.classList.contains('select2-search__field')) return true;

        var fieldWrap = input.closest('div');
        if (input.disabled || input.type === 'hidden' || input.offsetParent === null || (fieldWrap && fieldWrap.style.display === 'none')) {
            clearFieldError(input);
            return true;
        }

        var kind = inferKind(input);
        var isRequired = checkIfRequired(input);
        var isBadInput = !!(input.validity && input.validity.badInput);
        var rawValue = input.value || '';
        var value = rawValue.trim();

        // ── Dropdown / Select Fields Validation ──
        if (kind === 'select' || input.tagName === 'SELECT') {
            var isMultiple = input.multiple || input.hasAttribute('multiple') || /\[\]$/.test(input.name || '');
            var hasValue = false;

            if (isMultiple) {
                var selectedArr = getSelectValues(input);
                hasValue = selectedArr.length > 0;
            } else {
                hasValue = !!value;
            }

            if (!hasValue) {
                if (enforceRequired && isRequired) {
                    showFieldError(input, 'Please select an option.');
                    return false;
                }
                clearFieldError(input);
                return true;
            }
            clearFieldError(input);
            return true;
        }

        // ── Numeric Fields Validation ──
        if (kind === 'number' || kind === 'latitude' || kind === 'longitude') {
            if (isBadInput || (value !== '' && !/^-?\d*\.?\d+$/.test(value))) {
                showFieldError(input, 'Please enter a valid number.');
                return false;
            }

            if (!value) {
                if (enforceRequired && isRequired) {
                    showFieldError(input, 'This field is required.');
                    return false;
                }
                clearFieldError(input);
                return true;
            }

            var num = parseFloat(value);
            if (isNaN(num)) {
                showFieldError(input, 'Please enter a valid number.');
                return false;
            }

            if (kind === 'latitude' && (num < -90 || num > 90)) {
                showFieldError(input, 'Latitude must be between -90 and 90.');
                return false;
            }
            if (kind === 'longitude' && (num < -180 || num > 180)) {
                showFieldError(input, 'Longitude must be between -180 and 180.');
                return false;
            }

            if (kind === 'number') {
                if (!allowsDecimal(input) && num % 1 !== 0) {
                    showFieldError(input, 'Please enter a whole number.');
                    return false;
                }

                var min = input.getAttribute('min');
                var max = input.getAttribute('max');
                var hasMin = min !== null && min !== '';
                var hasMax = max !== null && max !== '';

                if (hasMin && num < parseFloat(min)) {
                    if (parseFloat(min) >= 0) {
                        showFieldError(input, 'Value cannot be negative.');
                    } else {
                        showFieldError(input, 'Value must be ' + min + ' or more.');
                    }
                    return false;
                }
                if (hasMax && num > parseFloat(max)) {
                    showFieldError(input, 'Value must be ' + max + ' or less.');
                    return false;
                }
            }

            clearFieldError(input);
            return true;
        }

        if (!value) {
            if (enforceRequired && isRequired) {
                showFieldError(input, input.tagName === 'SELECT' ? 'Please select an option.' : 'This field is required.');
                return false;
            }
            clearFieldError(input);
            return true;
        }

        if (kind === 'email' && !EMAIL_RE.test(value)) {
            showFieldError(input, 'Enter a valid email address (e.g. name@example.com).');
            return false;
        }

        if (kind === 'phone' && !PHONE_RE.test(value)) {
            showFieldError(input, 'Enter a valid 10-digit mobile number starting with 6, 7, 8 or 9.');
            return false;
        }

        if (kind === 'pincode' && !PIN_RE.test(value)) {
            showFieldError(input, 'Enter a valid 6-digit PIN code.');
            return false;
        }

        var maxlen = parseInt(input.getAttribute('maxlength'), 10);
        if (!isNaN(maxlen) && value.length > maxlen) {
            showFieldError(input, 'Maximum ' + maxlen + ' characters.');
            return false;
        }

        clearFieldError(input);
        return true;
    }

    // ── Validate every visible control inside a container ───────────────────
    // offsetParent is null for anything display:none — an inactive wizard
    // step, or a conditional field Alpine is currently hiding — so those are
    // correctly skipped.
    function validateContainer(container, enforceRequired) {
        if (!container) return true;
        var controls = container.querySelectorAll('input, select, textarea');
        var firstBad = null;

        for (var i = 0; i < controls.length; i++) {
            var el = controls[i];
            if (el.disabled || el.type === 'hidden' || el.offsetParent === null) continue;
            if (!validateField(el, enforceRequired) && !firstBad) firstBad = el;
        }

        if (firstBad) {
            firstBad.scrollIntoView({ behavior: 'smooth', block: 'center' });
            try { firstBad.focus({ preventScroll: true }); } catch (e) {}
            return false;
        }
        return true;
    }

    // ── Wiring — delegated, so it covers controls added after load ──────────
    // A field is only nagged after its first blur ("touched"); before that it
    // validates silently so an error doesn't flash while it's half-typed.
    var touched = new WeakSet();

    function isCandidate(el) {
        if (!el) return false;
        if (el.classList && el.classList.contains('select2-search__field')) return false;
        return (el.tagName === 'INPUT' || el.tagName === 'SELECT' || el.tagName === 'TEXTAREA')
            && el.type !== 'hidden' && el.type !== 'file'
            && el.type !== 'checkbox' && el.type !== 'radio'
            && el.type !== 'submit' && el.type !== 'button';
    }

    document.addEventListener('input', function (e) {
        if (!isCandidate(e.target)) return;
        var kind = inferKind(e.target);

        if (kind === 'number' || kind === 'latitude' || kind === 'longitude') {
            var isBad = !!(e.target.validity && e.target.validity.badInput) || (e.target.value.trim() !== '' && !/^-?\d*\.?\d+$/.test(e.target.value.trim()));
            if (isBad) {
                showFieldError(e.target, 'Please enter a valid number.');
                return;
            }
        }

        if (touched.has(e.target)) {
            validateField(e.target, false);
        }
    }, true);

    document.addEventListener('blur', function (e) {
        if (!isCandidate(e.target)) return;
        touched.add(e.target);
        validateField(e.target, false);
    }, true);

    document.addEventListener('change', function (e) {
        if (!isCandidate(e.target)) return;
        if (touched.has(e.target)) validateField(e.target, false);
    }, true);

    if (typeof $ !== 'undefined') {
        $(document).on('change select2:select select2:unselect select2:clear', 'select', function () {
            validateField(this, false);
        });
    }

    // ── Postal API PIN Code Auto-Fill (Applies to all 13 forms & warehouse form) ──
    (function () {
        function setupPinAutofill() {
            var pinInput = document.querySelector('input[name="postal_address_pin"], input[name="pin_code"]');
            if (!pinInput || pinInput.dataset.pinAutofillBound) return;
            pinInput.dataset.pinAutofillBound = 'true';

            var villageInput = document.querySelector('input[name="village"], input[name="locality_broad_area"], input[name="sub_locality_society_name"]');
            var tehsilInput = document.querySelector('input[name="tehsil"]');
            var districtInput = document.querySelector('input[name="district"], input[name="city"], input[name="nearest_city"]');
            var stateInput = document.querySelector('input[name="state"], select[name="state"]');
            var countryInput = document.querySelector('input[name="country"]');

            var statusEl = document.createElement('p');
            statusEl.className = 'mt-1 text-xs text-gray-400';
            if (pinInput.parentElement) pinInput.parentElement.appendChild(statusEl);

            var localitySelect = null;
            if (villageInput && villageInput.parentElement) {
                localitySelect = document.createElement('select');
                localitySelect.className = 'mt-1 w-full px-2 py-1.5 border border-gray-300 rounded-md text-xs bg-white hidden';
                villageInput.parentElement.appendChild(localitySelect);
            }

            function setStatus(text, kind) {
                statusEl.textContent = text;
                statusEl.className = 'mt-1 text-xs ' + (kind === 'error' ? 'text-red-600' : kind === 'success' ? 'text-emerald-600' : 'text-gray-400');
            }

            function applyPostOffice(po) {
                if (villageInput && po.Name) villageInput.value = po.Name;
                if (tehsilInput) tehsilInput.value = (po.Block && po.Block !== 'NA') ? po.Block : (po.Division || '');
                
                var cityElements = document.querySelectorAll('input[name="city"], input[name="district"], input[name="nearest_city"], select[name="city"], select[name="nearest_city"]');
                if (cityElements.length > 0 && po.District) {
                    cityElements.forEach(function(districtInput) {
                        if (districtInput.tagName === 'SELECT') {
                            var distName = (po.District || '').toLowerCase();
                            var matched = false;
                            for (var i = 0; i < districtInput.options.length; i++) {
                                if (districtInput.options[i].value.toLowerCase() === distName || districtInput.options[i].text.toLowerCase() === distName) {
                                    districtInput.selectedIndex = i;
                                    matched = true;
                                    break;
                                }
                            }
                            if (!matched && po.District) {
                                var opt = document.createElement('option');
                                opt.value = po.District;
                                opt.text = po.District;
                                opt.selected = true;
                                districtInput.appendChild(opt);
                            }
                        } else {
                            districtInput.value = po.District;
                        }
                    });
                }

                if (stateInput) {
                    if (stateInput.tagName === 'SELECT') {
                        var stName = (po.State || '').toLowerCase();
                        for (var i = 0; i < stateInput.options.length; i++) {
                            if (stateInput.options[i].value.toLowerCase() === stName || stateInput.options[i].text.toLowerCase() === stName) {
                                stateInput.selectedIndex = i;
                                break;
                            }
                        }
                    } else if (po.State) {
                        stateInput.value = po.State;
                    }
                }
                if (countryInput && po.Country) countryInput.value = po.Country;
                
                // Clear any validation error on auto-filled fields
                var allAutofilled = [villageInput, tehsilInput, stateInput, countryInput];
                cityElements.forEach(function(el) { allAutofilled.push(el); });
                allAutofilled.forEach(function(el) {
                    if (el && window.ZendoFieldValidation) {
                        window.ZendoFieldValidation.clearFieldError(el);
                    }
                });
            }

            function populateLocalityPicker(offices) {
                if (!localitySelect) return;
                if (offices.length <= 1) {
                    localitySelect.classList.add('hidden');
                    localitySelect.innerHTML = '';
                    return;
                }
                localitySelect.innerHTML = offices
                    .map(function(po, i) { return '<option value="' + i + '">' + po.Name + (po.Block && po.Block !== 'NA' ? ' — ' + po.Block : '') + '</option>'; })
                    .join('');
                localitySelect.classList.remove('hidden');
                localitySelect.onchange = function() { applyPostOffice(offices[localitySelect.value]); };
            }

            var lookupToken = 0;
            async function lookupPincode(pin) {
                var token = ++lookupToken;
                setStatus('Looking up PIN code…', 'muted');
                if (localitySelect) { localitySelect.classList.add('hidden'); localitySelect.innerHTML = ''; }

                try {
                    var res = await fetch('https://api.postalpincode.in/pincode/' + pin);
                    var data = await res.json();
                    if (token !== lookupToken) return;

                    var result = Array.isArray(data) ? data[0] : null;
                    var offices = result && result.Status === 'Success' ? (result.PostOffice || []) : [];

                    if (!offices.length) {
                        setStatus('No location found for this PIN code.', 'error');
                        return;
                    }

                    applyPostOffice(offices[0]);
                    populateLocalityPicker(offices);
                    setStatus(
                        offices.length > 1
                            ? 'Auto-filled from ' + offices[0].Name + ' — ' + (offices.length - 1) + ' more nearby, pick below if needed.'
                            : 'Auto-filled from ' + offices[0].Name + '.',
                        'success'
                    );
                } catch (e) {
                    if (token !== lookupToken) return;
                    setStatus('Could not reach PIN code lookup service.', 'error');
                }
            }

            var debounceTimer = null;
            var lastLookedUp = pinInput.value.trim().length === 6 ? pinInput.value.trim() : '';
            
            pinInput.addEventListener('input', function () {
                clearTimeout(debounceTimer);
                var pin = pinInput.value.trim();
                if (pin.length !== 6) {
                    setStatus('', 'muted');
                    return;
                }
                debounceTimer = setTimeout(function () {
                    if (pin === lastLookedUp) return;
                    lastLookedUp = pin;
                    lookupPincode(pin);
                }, 400);
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupPinAutofill);
        } else {
            setupPinAutofill();
        }
    })();

    // ── Photo upload card live image preview & removal helper ────────────
    window.previewPhotoCard = function (input, idx) {
        if (!input.files || !input.files[0]) return;
        var file = input.files[0];

        var img = document.getElementById('photo-img-preview-' + idx);
        var dropzone = document.getElementById('photo-dropzone-' + idx);
        var meta = document.getElementById('photo-meta-' + idx);
        var filename = document.getElementById('photo-filename-' + idx);
        var removeFlag = document.getElementById('remove-photo-input-' + idx);

        var reader = new FileReader();
        reader.onload = function (e) {
            if (img) {
                img.src = e.target.result;
                img.classList.remove('hidden');
            }
            if (dropzone) dropzone.classList.add('hidden');
            if (meta) meta.classList.remove('hidden');
            if (filename) filename.textContent = file.name;
            if (removeFlag) removeFlag.disabled = true;
            if (window.ZendoFieldValidation) {
                window.ZendoFieldValidation.clearFieldError(input);
            }
        };
        reader.readAsDataURL(file);
    };

    window.clearPhotoCard = function (idx) {
        var input = document.getElementById('photo-input-' + idx);
        var img = document.getElementById('photo-img-preview-' + idx);
        var dropzone = document.getElementById('photo-dropzone-' + idx);
        var meta = document.getElementById('photo-meta-' + idx);
        var filename = document.getElementById('photo-filename-' + idx);
        var removeFlag = document.getElementById('remove-photo-input-' + idx);

        if (input) input.value = '';
        if (img) {
            img.src = '';
            img.classList.add('hidden');
        }
        if (dropzone) dropzone.classList.remove('hidden');
        if (meta) meta.classList.add('hidden');
        if (filename) filename.textContent = '';
        if (removeFlag && removeFlag.value) removeFlag.disabled = false;
    };

    // ── Browser Geolocation auto-fill for GPS Latitude & Longitude ──
    (function () {
        function setupGpsLocationHelper() {
            var buttons = document.querySelectorAll('.btn-use-gps-location');
            if (!buttons.length) return;

            buttons.forEach(function (btn) {
                if (btn.dataset.gpsBound) return;
                btn.dataset.gpsBound = 'true';

                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    var form = btn.closest('form') || document;
                    var latInput = form.querySelector('input[name="gps_latitude"]');
                    var lngInput = form.querySelector('input[name="gps_longitude"]');

                    if (!latInput || !lngInput) return;

                    if (!navigator.geolocation) {
                        alert('Geolocation is not supported by your browser. Please enter coordinates manually.');
                        return;
                    }

                    btn.disabled = true;
                    var span = btn.querySelector('span') || btn;
                    var originalText = span.textContent;
                    span.textContent = 'Detecting...';

                    navigator.geolocation.getCurrentPosition(
                        function (pos) {
                            var lat = pos.coords.latitude.toFixed(6);
                            var lng = pos.coords.longitude.toFixed(6);

                            latInput.value = lat;
                            lngInput.value = lng;

                            latInput.dispatchEvent(new Event('input', { bubbles: true }));
                            lngInput.dispatchEvent(new Event('input', { bubbles: true }));
                            latInput.dispatchEvent(new Event('change', { bubbles: true }));
                            lngInput.dispatchEvent(new Event('change', { bubbles: true }));

                            if (window.ZendoFieldValidation) {
                                window.ZendoFieldValidation.clearFieldError(latInput);
                                window.ZendoFieldValidation.clearFieldError(lngInput);
                            }

                            btn.disabled = false;
                            span.textContent = '✓ Location Applied';

                            setTimeout(function () {
                                span.textContent = originalText;
                            }, 3000);
                        },
                        function (err) {
                            btn.disabled = false;
                            span.textContent = originalText;

                            var msg = 'Unable to fetch location. Please enter coordinates manually.';
                            if (err.code === err.PERMISSION_DENIED) {
                                msg = 'Location access denied. Please enable location permission in browser or enter manually.';
                            }
                            alert(msg);
                        },
                        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                    );
                });
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupGpsLocationHelper);
        } else {
            setupGpsLocationHelper();
        }
    })();

    // ── Conditional required validation for "Part of a Project / Society?" ──
    (function () {
        function setupProjectSocietyToggle() {
            var selects = document.querySelectorAll('select[name="part_of_a_project_society"]');
            selects.forEach(function (projectSelect) {
                if (projectSelect.dataset.projectToggleBound) return;
                projectSelect.dataset.projectToggleBound = 'true';

                var form = projectSelect.closest('form') || document;

                var projectFieldNames = [
                    'project_society_name',
                    'project_name',
                    'project_rera_id',
                    'developer_builder_name',
                    'builder_developer_name',
                    'total_towers_blocks',
                    'total_units_in_project',
                    'approved_loan_banks',
                    'configurations_offered',
                    'project_amenities'
                ];

                function toggleProjectFields() {
                    var val = (projectSelect.value || '').trim().toLowerCase();
                    var isYes = val === 'yes' || val.startsWith('yes');

                    projectFieldNames.forEach(function (name) {
                        var inputs = form.querySelectorAll('[name="' + name + '"], [name="' + name + '[]"]');
                        inputs.forEach(function (input) {
                            var fieldWrap = input.closest('div');
                            if (!fieldWrap) return;

                            var label = fieldWrap.querySelector('label');
                            var asterisk = label ? label.querySelector('.text-red-500') : null;

                            if (isYes) {
                                fieldWrap.style.display = '';
                                input.required = true;
                                input.setAttribute('required', 'required');
                                if (label && !asterisk) {
                                    var span = document.createElement('span');
                                    span.className = 'text-red-500 ml-0.5';
                                    span.textContent = '*';
                                    label.appendChild(span);
                                } else if (asterisk) {
                                    asterisk.style.display = '';
                                }
                            } else {
                                fieldWrap.style.display = 'none';
                                input.required = false;
                                input.removeAttribute('required');
                                if (asterisk) asterisk.style.display = 'none';
                                if (window.ZendoFieldValidation) {
                                    window.ZendoFieldValidation.clearFieldError(input);
                                }
                            }
                        });
                    });
                }

                projectSelect.addEventListener('change', toggleProjectFields);
                projectSelect.addEventListener('input', toggleProjectFields);
                toggleProjectFields();
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupProjectSocietyToggle);
        } else {
            setupProjectSocietyToggle();
        }
    })();

    // ── Conditional required validation for Availability -> Available From Date ──
    (function () {
        function setupAvailabilityToggle() {
            var selects = document.querySelectorAll('select[name="availability"]');
            selects.forEach(function (availSelect) {
                if (availSelect.dataset.availToggleBound) return;
                availSelect.dataset.availToggleBound = 'true';

                var form = availSelect.closest('form') || document;

                function toggleAvailabilityFields() {
                    var val = (availSelect.value || '').trim().toLowerCase();
                    var isFromDate = val === 'from date' || val === 'from_date' || val.indexOf('from date') !== -1;
                    var dateInputs = form.querySelectorAll('input[name="available_from"]');

                    dateInputs.forEach(function (input) {
                        var fieldWrap = input.closest('div');
                        if (!fieldWrap) return;

                        var label = fieldWrap.querySelector('label');
                        var asterisk = label ? label.querySelector('.text-red-500') : null;

                        if (isFromDate) {
                            fieldWrap.style.display = '';
                            input.required = true;
                            input.setAttribute('required', 'required');
                            if (label && !asterisk) {
                                var span = document.createElement('span');
                                span.className = 'text-red-500 ml-0.5';
                                span.textContent = '*';
                                label.appendChild(span);
                            } else if (asterisk) {
                                asterisk.style.display = '';
                            }
                        } else {
                            fieldWrap.style.display = 'none';
                            input.required = false;
                            input.removeAttribute('required');
                            if (asterisk) asterisk.style.display = 'none';
                            if (window.ZendoFieldValidation) {
                                window.ZendoFieldValidation.clearFieldError(input);
                            }
                        }
                    });
                }

                availSelect.addEventListener('change', toggleAvailabilityFields);
                toggleAvailabilityFields();
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupAvailabilityToggle);
        } else {
            setupAvailabilityToggle();
        }
    })();

    // ── Conditional required validation for Construction Status -> Possession By Date ──
    (function () {
        function setupConstructionStatusToggle() {
            var selects = document.querySelectorAll('select[name="construction_status"], select[name="property_status"], select[name="construction_listing_status"]');
            selects.forEach(function (statusSelect) {
                if (statusSelect.dataset.constrToggleBound) return;
                statusSelect.dataset.constrToggleBound = 'true';

                var form = statusSelect.closest('form') || document;

                function toggleConstructionFields() {
                    var val = (statusSelect.value || '').trim().toLowerCase();
                    var isUnderConstr = val === 'under construction' || val === 'under_construction' || val.indexOf('under construction') !== -1;
                    var dateInputs = form.querySelectorAll('input[name="possession_by"], input[name="possession_by_if_under_constr"]');

                    dateInputs.forEach(function (input) {
                        var fieldWrap = input.closest('div');
                        if (!fieldWrap) return;

                        var label = fieldWrap.querySelector('label');
                        var asterisk = label ? label.querySelector('.text-red-500') : null;

                        if (isUnderConstr) {
                            fieldWrap.style.display = '';
                            input.required = true;
                            input.setAttribute('required', 'required');
                            if (label && !asterisk) {
                                var span = document.createElement('span');
                                span.className = 'text-red-500 ml-0.5';
                                span.textContent = '*';
                                label.appendChild(span);
                            } else if (asterisk) {
                                asterisk.style.display = '';
                            }
                        } else {
                            fieldWrap.style.display = 'none';
                            input.required = false;
                            input.removeAttribute('required');
                            if (asterisk) asterisk.style.display = 'none';
                            if (window.ZendoFieldValidation) {
                                window.ZendoFieldValidation.clearFieldError(input);
                            }
                        }
                    });
                }

                statusSelect.addEventListener('change', toggleConstructionFields);
                toggleConstructionFields();
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupConstructionStatusToggle);
        } else {
            setupConstructionStatusToggle();
        }
    })();

    // ── Conditional required validation for RERA Registered -> RERA ID ──
    (function () {
        function setupReraToggle() {
            var selects = document.querySelectorAll('select[name="rera_registered"]');
            selects.forEach(function (reraSelect) {
                if (reraSelect.dataset.reraToggleBound) return;
                reraSelect.dataset.reraToggleBound = 'true';

                var form = reraSelect.closest('form') || document;

                function toggleReraFields() {
                    var val = (reraSelect.value || '').trim().toLowerCase();
                    var isYes = val === 'yes' || val.startsWith('yes');
                    var reraInputs = form.querySelectorAll('input[name="rera_registration_id"], input[name="project_rera_id"]');

                    reraInputs.forEach(function (input) {
                        var fieldWrap = input.closest('div');
                        if (!fieldWrap) return;

                        var label = fieldWrap.querySelector('label');
                        var asterisk = label ? label.querySelector('.text-red-500') : null;

                        if (isYes) {
                            fieldWrap.style.display = '';
                            input.required = true;
                            input.setAttribute('required', 'required');
                            if (label && !asterisk) {
                                var span = document.createElement('span');
                                span.className = 'text-red-500 ml-0.5';
                                span.textContent = '*';
                                label.appendChild(span);
                            } else if (asterisk) {
                                asterisk.style.display = '';
                            }
                        } else {
                            fieldWrap.style.display = 'none';
                            input.required = false;
                            input.removeAttribute('required');
                            if (asterisk) asterisk.style.display = 'none';
                            if (window.ZendoFieldValidation) {
                                window.ZendoFieldValidation.clearFieldError(input);
                            }
                        }
                    });
                }

                reraSelect.addEventListener('change', toggleReraFields);
                toggleReraFields();
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupReraToggle);
        } else {
            setupReraToggle();
        }
    })();

    // ── Conditional required validation for Deal Type / Commercial Terms ──
    (function () {
        function setupDealTypeToggle() {
            var selects = document.querySelectorAll('select[name="deal_type"], select[name="listing_purpose_transaction_type"]');
            selects.forEach(function (dealSelect) {
                if (dealSelect.dataset.dealToggleBound) return;
                dealSelect.dataset.dealToggleBound = 'true';

                var form = dealSelect.closest('form') || document;

                function toggleDealFields() {
                    var val = (dealSelect.value || '').trim().toLowerCase();
                    var isRent = val === 'rent' || val === 'both' || val === 'lease' || val.indexOf('rent') !== -1 || val.indexOf('lease') !== -1;
                    var isSale = val === 'sale' || val === 'both' || val.indexOf('sale') !== -1;

                    var rentFieldNames = ['expected_rent', 'rent_per_month', 'rent_per_bed_room_month', 'security_deposit_months', 'security_deposit'];
                    var saleFieldNames = ['expected_sale_price', 'total_sale_price', 'price_cost', 'sale_price_band_shown_live'];

                    rentFieldNames.forEach(function (name) {
                        var inputs = form.querySelectorAll('[name="' + name + '"]');
                        inputs.forEach(function (input) {
                            var fieldWrap = input.closest('div');
                            if (!fieldWrap) return;
                            var label = fieldWrap.querySelector('label');
                            var asterisk = label ? label.querySelector('.text-red-500') : null;

                            if (isRent) {
                                input.required = true;
                                input.setAttribute('required', 'required');
                                if (label && !asterisk) {
                                    var span = document.createElement('span');
                                    span.className = 'text-red-500 ml-0.5';
                                    span.textContent = '*';
                                    label.appendChild(span);
                                } else if (asterisk) {
                                    asterisk.style.display = '';
                                }
                            } else {
                                input.required = false;
                                input.removeAttribute('required');
                                if (asterisk) asterisk.style.display = 'none';
                                if (window.ZendoFieldValidation) {
                                    window.ZendoFieldValidation.clearFieldError(input);
                                }
                            }
                        });
                    });

                    saleFieldNames.forEach(function (name) {
                        var inputs = form.querySelectorAll('[name="' + name + '"]');
                        inputs.forEach(function (input) {
                            var fieldWrap = input.closest('div');
                            if (!fieldWrap) return;
                            var label = fieldWrap.querySelector('label');
                            var asterisk = label ? label.querySelector('.text-red-500') : null;

                            if (isSale) {
                                input.required = true;
                                input.setAttribute('required', 'required');
                                if (label && !asterisk) {
                                    var span = document.createElement('span');
                                    span.className = 'text-red-500 ml-0.5';
                                    span.textContent = '*';
                                    label.appendChild(span);
                                } else if (asterisk) {
                                    asterisk.style.display = '';
                                }
                            } else {
                                input.required = false;
                                input.removeAttribute('required');
                                if (asterisk) asterisk.style.display = 'none';
                                if (window.ZendoFieldValidation) {
                                    window.ZendoFieldValidation.clearFieldError(input);
                                }
                            }
                        });
                    });
                }

                dealSelect.addEventListener('change', toggleDealFields);
                toggleDealFields();
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupDealTypeToggle);
        } else {
            setupDealTypeToggle();
        }
    })();

    // ── Conditional required validation for Currently Rented / Tenanted ──
    (function () {
        function setupTenantedToggle() {
            var selects = document.querySelectorAll('select[name="currently_rented_tenanted"], select[name="is_tenanted"]');
            selects.forEach(function (tenantedSelect) {
                if (tenantedSelect.dataset.tenantedToggleBound) return;
                tenantedSelect.dataset.tenantedToggleBound = 'true';

                var form = tenantedSelect.closest('form') || document;

                function toggleTenantedFields() {
                    var val = (tenantedSelect.value || '').trim().toLowerCase();
                    var isTenanted = val === 'yes' || val === 'partially' || val.indexOf('yes') !== -1;

                    var tenantedFieldNames = ['current_monthly_rent_received', 'lease_start_date', 'lease_tenure', 'lock_in_remaining'];

                    tenantedFieldNames.forEach(function (name) {
                        var inputs = form.querySelectorAll('[name="' + name + '"]');
                        inputs.forEach(function (input) {
                            var fieldWrap = input.closest('div');
                            if (!fieldWrap) return;
                            var label = fieldWrap.querySelector('label');
                            var asterisk = label ? label.querySelector('.text-red-500') : null;

                            if (isTenanted) {
                                input.required = true;
                                input.setAttribute('required', 'required');
                                if (label && !asterisk) {
                                    var span = document.createElement('span');
                                    span.className = 'text-red-500 ml-0.5';
                                    span.textContent = '*';
                                    label.appendChild(span);
                                } else if (asterisk) {
                                    asterisk.style.display = '';
                                }
                            } else {
                                input.required = false;
                                input.removeAttribute('required');
                                if (asterisk) asterisk.style.display = 'none';
                                if (window.ZendoFieldValidation) {
                                    window.ZendoFieldValidation.clearFieldError(input);
                                }
                            }
                        });
                    });
                }

                tenantedSelect.addEventListener('change', toggleTenantedFields);
                toggleTenantedFields();
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupTenantedToggle);
        } else {
            setupTenantedToggle();
        }
    })();

    window.ZendoFieldValidation = {
        validateField: validateField,
        validateContainer: validateContainer,
        showFieldError: showFieldError,
        clearFieldError: clearFieldError,
    };
})();
</script>

@if(isset($errors) && $errors->any())
<script>
    (function () {
        var serverErrors = @json($errors->getMessages());
        if (!serverErrors || !Object.keys(serverErrors).length) return;

        function applyServerErrors() {
            if (!window.ZendoFieldValidation) return;

            var allSteps = document.querySelectorAll('.wizard-step-content');
            var firstInvalidStep = -1;
            var firstInvalidInput = null;

            for (var fieldKey in serverErrors) {
                if (!Object.prototype.hasOwnProperty.call(serverErrors, fieldKey)) continue;

                var msgs = serverErrors[fieldKey];
                if (!msgs || !msgs.length) continue;
                var rawMsg = msgs[0];

                var targetName = fieldKey;
                var displayMsg = rawMsg;

                // Handle file upload fields like photo_0, photo_1, photo.0, etc.
                if (/^photo[._]\d+$/i.test(fieldKey)) {
                    var numMatch = fieldKey.match(/\d+/);
                    if (numMatch) {
                        var idx = parseInt(numMatch[0], 10);
                        targetName = 'photo_' + idx;
                        displayMsg = rawMsg.replace(/photo\s*\d+/gi, 'Photo ' + (idx + 1));
                    }
                }

                // Find matching input in the DOM
                var input = document.querySelector('[name="' + targetName + '"], [name="' + targetName + '[]"], [name="' + fieldKey + '"]');

                if (input) {
                    window.ZendoFieldValidation.showFieldError(input, displayMsg);

                    // Find which step panel contains this input
                    var stepPanel = input.closest('.wizard-step-content');
                    if (stepPanel) {
                        for (var i = 0; i < allSteps.length; i++) {
                            if (allSteps[i] === stepPanel) {
                                if (firstInvalidStep === -1 || i < firstInvalidStep) {
                                    firstInvalidStep = i;
                                    firstInvalidInput = input;
                                }
                                break;
                            }
                        }
                    }
                }
            }

            // Directly move user to that tab/step and scroll/focus first invalid field
            if (firstInvalidStep !== -1 && typeof window.wizardGoTo === 'function') {
                window.wizardGoTo(firstInvalidStep);

                if (firstInvalidInput) {
                    setTimeout(function () {
                        firstInvalidInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        try { firstInvalidInput.focus({ preventScroll: true }); } catch (e) {}
                    }, 250);
                }
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', applyServerErrors);
        } else {
            applyServerErrors();
        }
    })();
</script>
@endif
